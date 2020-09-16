<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Lockers";
  include 'navbar.php';
  require '../model/db.php';
  if(isset($_POST['go'])){
    //Parse the csv from the submitted file into an associative array, then delete it to save space
    //Credit: "starrychloe at oliveyou dot net" from php.net (edited)
        $csv = array_map('str_getcsv', file($_FILES["lockers"]["tmp_name"]));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine(preg_replace("/[^\x21-\x7e]+/", "", $csv[0]), $a);
        });
        array_shift($csv); //Remove collumn header
        unlink($_FILES["lockers"]["tmp_name"]);

    //If we need to delete existing lockers, do so. Also delete buildings since we're updating that too (my God, the data redundancy in this thing)
    if(isset($_POST['overwrite']) && $_POST['overwrite']){
      mysqli_query($conn, "TRUNCATE TABLE locker");
      mysqli_query($conn, "TRUNCATE TABLE buildings");
    }
    //Prepare statement and bind variables
    $stmt = $conn->prepare("INSERT INTO locker(locker_id, locker_status, locker_price, locker_location, locker_size) VALUES(?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $locker_id, $locker_status, $locker_price, $locker_location, $locker_size);
    $errMsg = "";

    //Go through each row and insert. Also add each new location to the list of buildings
    foreach ($csv as $row) {
      //die();
      $locker_id = htmlspecialchars(preg_replace("/[^\x20-\x7e]+/", "", $row['locker_id']));
      $locker_status = htmlspecialchars(preg_replace("/[^\x20-\x7e]+/", "", $row['locker_status']));
      $locker_price = intval(preg_replace("/[^\x20-\x7e]+/", "", $row['locker_price']));
      $locker_location = htmlspecialchars(preg_replace("/[^\x20-\x7e]+/", "", $row['locker_location']));
      $locker_size = htmlspecialchars(preg_replace("/[^\x20-\x7e]+/", "", $row['locker_size']));
      $stmt->execute();

      //Check to see if we know of this building and if not, add it
      $res = mysqli_query($conn, "SELECT * FROM buildings WHERE name = '".mysqli_real_escape_string($conn, $locker_location)."'");
      if(mysqli_num_rows($res) == 0){
        mysqli_query($conn, "INSERT INTO buildings(name) VALUES ('".mysqli_real_escape_string($conn, $locker_location)."')");
      }

      //If an error occured, output it
      $err = mysqli_error($conn);
      if(!empty($err)){
        $errMsg .= $err . "<br/>\n";
      }
    }
  }
?>

<div class="wrapper">
    <section class="section">
        <div class="container2">
          <h5>Bulk Locker Upload</h5>
          <p>
            Download <a href="./lockers%20template.csv" target="_blank">this</a> file, open it in a spreadsheet editor and fill in the table. When you're done, upload the file using the form below. Indicate whether or not you want to keep the existing locker information by checking the corresponding box.
          </p>
          <p>
            Please use "Available", "Booked" or "Damaged" to indicate the locker status and please use "Large", "Medium", "Small" or "X-Small" to indicate the locker size.
          </p>
          <form method=POST action="#" enctype="multipart/form-data">
            <input type=radio id=overwrite-yes name=overwrite checked value=true> <label for="overwrite-yes">Delete existing lockers</label><br/>
            <input type=radio id=overwrite-no name=overwrite value=false> <label for="overwrite-no">Append to existing lockers</label><br/><br/>
            <input type="file" accept="text/csv" name="lockers"/>
            <input type="submit" name="go"/>
          </form>
          <?php
            if(isset($errMsg)) echo($errMsg);
          ?>
        </div>
    </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
