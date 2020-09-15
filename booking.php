<?php

  session_start();
  include 'navbar.php';
  require_once 'model/db.php';

  $msg = $msgClass = '';
  function get_price($date1, $date2) {
    $diff = date_diff(date_create($date1), date_create($date2));
    $price = $diff->format("%a");
    return $price * 1;
  }

  // handle the get request base on user id
  if (isset($_REQUEST['id'])) {
    $id = mysqli_real_escape_string($conn, $_REQUEST['id']);
    $sql = "SELECT * FROM `locker` WHERE `locker_id`='$id'";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn)."<br/>\n$sql");

    $row = mysqli_fetch_array($result);

    $_SESSION['locker_id'] = $row['locker_id'];
    $_SESSION['locker_price'] = $row['locker_price'];
    $_SESSION['locker_location'] = $row['locker_location'];
  }
  $hmac_secret = file_get_contents("./paypal_hmac_secret");
  // When the form is submitted
  if (filter_has_var(INPUT_POST, 'submit')) {
    //Check to make sure no arrays are passed into the variables we'll be putting in the HMAC as that would break the function silently
    if(gettype($_POST['lockerid']) !== "string" || gettype($_POST['expires']) !== "string" || gettype($_POST['capture']) !== "string" || gettype($_POST['order']) !== "string"){
      $err = "Your browser sent malformed parameters. A payment likely was made but we couldn't verify it and so we didn't assign you a locker. Please contact <a href='mailto:webmaster@skule.ca'>webmaster@skule.ca</a> with the following debug information\n";
      ob_start();
      var_dump($_POST, $_SESSION);
      $err_debug = ob_get_clean();
    } else if($hmac_secret === false || empty($hmac_secret)) {
      //Also check to make sure we have a valid HMAC secret, else, throw an error
      $err = "No valid HMAC secret was found. This is an internal server error. A payment likely was made but we couldn't verify it and so we didn't assign you a locker. Please contact <a href='mailto:webmaster@skule.ca'>webmaster@skule.ca</a> with this error message for assistance.";
    } else {
      //If all parameters are of the correct type, continue by verifying the token.
      //Check if the MAC matches
      if($_POST['token'] == hash_hmac('ripemd160', $_SESSION['user_id'].$_POST['lockerid'].$_POST['expires'].$_POST['capture'].$_POST['order'], $hmac_secret)){
        //If the MAC is valid, check if the token has expired
        $time = time();
        if($_POST['expires'] < $time){
          //If the token has expired, throw an error
          $err = "Token expired.<br/>\nToken expiry time: ".htmlentities($_POST['expires'])."<br/>\nCurrent time at server: $time. This is probably because your internet connection was too slow for a secure transaction. A payment likely was made but we couldn't verify it and so we didn't assign you a locker. Please contact <a href='mailto:webmaster@skule.ca'>webmaster@skule.ca</a> with the following debug information\n";
          ob_start();
          var_dump($_POST, $_SESSION, $time);
          $err_debug = ob_get_clean();
        }
      } else {
        //If the MAC isn't valid, throw an error
        $err = "Your browser submitted a malformed authentication token. A payment likely was made but we couldn't verify it and so we didn't assign you a locker. Contact <a href='mailto:webmaster@skule.ca'>webmaster@skule.ca</a> with the following debug information:";
        ob_start();
        var_dump($_POST, $_SESSION);
        $err_debug = ob_get_clean();
      }
      //If everything checks out and we haven't thrown any errors, assign locker and store the transaction details
      if(!isset($err)){
        $start = mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['start'])));
        $end = mysqli_real_escape_string($conn, date("Y-m-d",strtotime($_POST['end'])));
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $semail = mysqli_real_escape_string($conn, $_SESSION['s_email']);
        $lid = mysqli_real_escape_string($conn, $_POST['lockerid']);
        $order_id = mysqli_real_escape_string($conn, $_POST['order']);
        $capture_id = mysqli_real_escape_string($conn, $_POST['capture']);

        if (strtotime($end) <= strtotime($start)) {
          $msg = "Please pick a correct date";
          $msgClass = "red";
        } else {
          $totalPrice = get_price($start, $end);

          $sql = "INSERT INTO `record` (record_start, record_end, record_price, student_email, locker_id, record_order_id, record_capture_id, record_status, record_sub)
          VALUES ('$start', '$end', '$totalPrice', '$semail', '$lid', '$order_id', '$capture_id', 'approved', 'active');";
          $sql .= "UPDATE `locker` SET locker_status='Booked' WHERE locker_id='$lid'";

          $result = mysqli_multi_query($conn, $sql) or die(mysqli_error($conn)."<br/>\n$sql");
          $msg = "<a href='index.php' class='white-text'><i class='fas fa-arrow-circle-left'></i></a> Booking success.";
          $msgClass = "green";
        }
      }
  }
  }
  mysqli_close($conn);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=ATbzqfZdbq6Yx1nDbab1HQDf5Zrs0CSVoFxi3cQfnqnrvfcdKzalfZJKycfGuhO1Gt4VfBMmKrbLAbXi&currency=CAD"></script>
<script>
//Echo Self-XSS warning
  console.log("%cSTOP!\n%cDon't listen to anyone who tells you to paste anything in here.\nThey're trying to hack you and probably trying to gain access to your PayPal account.\n%cMore info: https://lockersbeta.skule.ca/self-xss.php", "color: red; font-size: 50pt; text-shadow: 0 0 3px black;", "color: red; font-size: 20pt;", "color: red; font-size: 13pt;");

//Modal
$(function(){
  $('div#error-modal').on("click",
    function(){
      $(this).fadeOut(100);
    }
  );
  $('div#error-modal-content').on("click",
    function(e){
      e.stopPropagation();
    }
  );
});

//Display PayPal buttons
  paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '<?php
              //Clean up any character other than numbers and the decimal seperator ('.')
              echo(number_format(preg_replace("/[^0-9.]+/","",$_SESSION['locker_price']), 2, ".", "")); ?>',
            currency_code: 'CAD'
          }
        }]
      });
    },
    onApprove: function(data) {
      console.log(data);
      promise = fetch("/paypal.php?&locker_id=<?php echo(urlencode($_SESSION['locker_id'])); ?>&order=" + data.orderID).then(response => response.json()).then(data => window.obj = data).then(function(){
      if(window.obj.error === undefined){
        $('form#order input[name=token]')[0].value = window.obj.token;
        $('form#order input[name=expires]')[0].value = window.obj.expires;
        $('form#order input[name=order]')[0].value = window.obj.order;
        $('form#order input[name=capture]')[0].value = window.obj.capture;
        $('form#order input[type=submit]').click();
      }else{
        $('div#error-modal #message').html("The order failed with the following error: " + window.obj.error + "<br/>\nPlease contact <a href='mailto:webmaster@skule.ca'>webmaster@skule.ca</a> " + (window.obj.debug === undefined ? "for assistance" : "with the following debug information so we can refund you if a payment was made and/or manually assign a locker to you."));
        if(window.obj.debug !== undefined) $('div#error-modal #debug').html(window.obj.debug);
        $('div#error-modal').fadeIn(100);
      }
    });
    }
  }).render('#paypal-button-container'); // This function displays Smart Payment Buttons on your web page.
</script>

<style>
  div#error-modal{
    width: 100vw;
    height: 100vh;
    top: 0;
    left: 0;
    position: fixed;
    background-color: rgba(0, 0, 0, 0.7);
    vertical-align: middle;
    <?php if(!isset($err)) echo("display: none;"); ?>
    z-index: 2147483647; /*This is dirty AF. I'm sorry.*/
  }

  div#error-modal-content{
    margin: 20%;
    background-color: var(--skule-green);
    color: white;
  }
</style>

<div id="error-modal">
  <div id="error-modal-content">
    <div id="content"><?php if(isset($err)) echo($err);?></div>
    <textarea id="debug"><?php if(isset($err_debug)) echo($err_debug);?></textarea>
  </div>
</div>
<!-- TODO: Add locker location to records -->
<section class="section">
    <div class="container">
        <h5><i class="fab fa-wpforms"></i> Booking information</h5>
        <div class="divider"></div><br>
        <?php if($msg != ''): ?>
        <div class="card-panel <?php echo $msgClass; ?>">
            <span class="white-text"><?php echo $msg; ?></span>
        </div>
        <?php endif ?>
        <form id="order" enctype="multipart/form-data" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            class="card-panel">

            <!-- Data required for PayPal payment -->
            <input type="hidden" name="token"/>
            <input type="hidden" name="expires"/>
            <input type="hidden" name="order"/>
            <input type="hidden" name="capture"/>
            <input type="hidden" name=submit value="yes" />
            <input type="submit" style="display: none;" />

            <div class="row">
                <div class="input-field col s6 m6">
                    <input readonly type="text" id="lockerlocation" name="lockerlocation"
                        value="<?php echo $_SESSION['locker_location']; ?>">
                    <label for="id">Building</label>
                </div>

                <div class="input-field col s6 m6">
                    <input readonly type="text" id="lockerid" name="lockerid"
                        value="<?php echo $_SESSION['locker_id']; ?>">
                    <label for="id">Locker No</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6">
                    <input id="start" type="text" class="datepicker" name="start">
                    <label for="start">Start date</label>
                </div>
                <div class="input-field col s6 m6">
                    <input id="end" type="text" class="datepicker" name="end">
                    <label for="end">End date</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6">
                    <input readonly type="text" id="price" name="price"
                        value="$<?php echo $_SESSION['locker_price']; ?>">
                    <label for="price">Locker Price Per Year</label>
                </div>
            </div>
    </div>
    <div class="center">
        <a href="index.php" class="btn btn-flat">Cancel</a>
        <div id="paypal-button-container"></div>
    </div>
    </form>
    </div>
</section>
<?php
  include 'footer.php';
?>
