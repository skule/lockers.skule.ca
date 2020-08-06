<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Lockers";
  include 'navbar.php';
  require '../model/db.php';
  if(isset($_POST['go'])){
    //Parse the csv into an associative array
    //Credit: "starrychloe at oliveyou dot net" from php.net
        $csv = array_map('str_getcsv', $_POST['lockers']);
          array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv); //Remove collumn header

    //If we need to delete existing lockers, do so
    if($_POST[''])
    //Prepare statement and bind variables
    $stmt = $conn->prepare("INSERT INTO locker(locker_id, locker_status, locker_price, locker_location, locker_size) VALUES(?, ?, ?, ?, ?)");
    $stmt->bind("ssiss", $locker_id, $locker_status, $locker_price, $locker_location, $locker_size);

    //Go through each row and insert
    foreach ($csv as $row) {
      $locker_id = htmlspecialchars($_POST['locker_id']);
      $locker_status = htmlspecialchars($_POST['locker_status']);
      $locker_price = intval($_POST['locker_price']);
      $locker_location = htmlspecialchars($_POST['locker_location']);
      $locker_size = htmlspecialchars($_POST['locker_size']);
      $stmt->execute();
      //If an error occured, output it
      $err = mysqli_error($conn);
      if(!empty($err)){
        $errMsg += $err + "<br/>\n";
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
            Please use "Available", "Booked" or "Damaged" to indicate the locker status and please use "Large", "Medium" or "Small" to indicate the locker size.
          </p>
          <form method=POST action="#" enctype="application/x-www-form-urlencoded">
            <input type=radio id=overwrite-yes name=overwrite checked value=true> <label for="overwrite-yes">Delete existing lockers</label><br/>
            <input type=radio id=overwrite-no name=overwrite value=false> <label for="overwrite-no">Append to existing lockers</label><br/><br/>
            <input type="file" accept="text/csv" name="lockers"/>
            <input type="submit" name="go"/>
          </form>
          <?php
            if(isset($_POST['']))
          ?>
        </div>
    </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
