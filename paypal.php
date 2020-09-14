<?php
include 'model/paypal_creds.php';
session_start();

//If the order ID is not set or it is badly formatted, throw error and die. This is important because we are putting the error ID into URL's and in the exec function. Also, this filters out any arrays passed in, which would break the HMAC function silently
if(!isset($_GET['order']) || !preg_match('/[A-Z0-9]+/', $_GET['order'])){
  die(json_encode(array(
    "error" => "Illegal order ID"
  )));
}

//Check the same with locker ID, make sure it exists and that no arrays are passed in as that would, again, break the HMAC function silently
if(!isset($_GET['locker_id']) || gettype($_GET['locker_id']) !== "string"){
  die(json_encode(array(
    "error" => "Illegal locker ID"
  )));
}

//Get Oauth2 token using our credentials
$response = json_decode(shell_exec('curl https://api.paypal.com/v1/oauth2/token    -H "Accept: application/json"    -H "Accept-Language: en_US"    -u "'.$API_CLIENT_ID.':'.$API_SECRET.'"    -d "grant_type=client_credentials"'));
$token = $response->access_token;
die(json_encode(array(
  "error" => "Couldn't get Oauth2 token from PayPal. No transaction attemtped"
)));

//Check that order details are what we expect them to be
//First, receive the order details from the server and make sure it exists
$response_text = shell_exec('curl -X GET https://api.paypal.com/v2/checkout/orders/' . urlencode($_GET['order']) . ' -H "Content-Type: application/json" -H "Authorization: Bearer '.$token);
$response = json_decode($response_text);
//If it doesn't, throw an error and die
if(!isset($response->purchase_units[0]->amount->currency_code) || !isset($response->purchase_units[0]->amount->value)){
  die(json_encode(array(
    "error" => "Couldn't get order details. No transaction attempted.",
    "debug" => $response_text
  )));
}
//Set the order details to easy-to-type variables
$order_currency_code = $response->purchase_units[0]->amount->currency_code;
$order_price = $response->purchase_units[0]->amount->value;

//Then, query the database to see what the order amount should have been
$DB_SETTINGS['DONT_CHECK_CONNECTION'] = true; //Needed so we don't get a non-JSON output if the databse connection fails
include 'model/db.php'
//Check databse connection. If not successful, throw an error and die.
if(!$conn){
  die(json_encode(array(
    "error" => "Couldn't connect to the database. No transaction attemtped",
    "debug" => mysqli_connect_error()
  )));
}
//Get locker price
$sql = "SELECT `locker_price` FROM `locker` WHERE `locker_id` = ".mysqli_real_escape_string($conn, $_GET['locker_id']);
$res = mysqli_query($conn, $sql) or die(json_encode(array(
  "error" => "Couldn't get locker price from database. No transaction attempted.",
  "debug" => mysqli_error($conn)."\n$sql"
)));
if(mysqli_num_rows($res) != 1){
  die(json_encode(array(
    "error" => "More/less than one locker found at given ID. No transaction attempted.",
    "debug" => "Row count: ".mysqli_num_rows($res)
  )));
}
$locker_price = mysqli_fetch_array($res)[0];
//If the data we got from the database doesn't match that in the order, throw an error and die
//Assume lockers are always being sold for Canadian Dollars (CAD)
if($order_currency_code !== "CAD" || $order_price !== number_format($locker_price, 2, '.', '')){
  die(json_encode(array(
    "error" => "Incorrect amount in order. No transaction attempted.",
    "debug" => "Locker price is $locker_price and order was for $order_price $order_currency_code.";
  )));
}

//If everything checks out, attempt the transaction.
$response_text = shell_exec('curl -X POST https://api.paypal.com/v2/checkout/orders/' . urlencode($_GET['order']) . '/capture -H "Content-Type: application/json" -H "Authorization: Bearer '.$token.'" -H "Prefer: return=representation"');
$response = json_decode($response_text);

//Check if we got back a response in the format we expect. If not, throw an error and die.
if(!isset($response -> purchase_units[0] -> payments -> captures[0] -> status) || !isset($response -> purchase_units[0] -> payments -> captures[0] -> id)){
  die(json_encode(array(
    "error" => "Couldn't get capture status. A transaction was attempted.",
    "debug" => $response_text
  )));
}
//Store the order status and capture number in easy-to-type variables
$order_status = $response -> purchase_units[0] -> payments -> captures[0] -> status;
$capture_nr = $response -> purchase_units[0] -> payments -> captures[0] -> id;

//If the order was successful, generate token and send to client
if($order_status == "COMPLETED"){
  $time = time();
  $expires = $time + 173;
  echo(json_encode(
    array(
      "token" => hash_hmac('ripemd160', $_SESSION['s_id'].$_GET['locker_id'].$expires.$capture_nr.$_GET['order'], file_get_contents("./paypal_hmac_secret")),
      "order" => $_GET['order'],
      "capture" => $capture_nr,
      "expires" => $expires
    )
  ));
//Otherwise, throw an error
}else{
  echo(json_encode(array(
    "error" => "Order incomplete. A transaction was attempted.",
    "debug" => $response_text
  )));
}

//Suppress any other output
die();

?>
