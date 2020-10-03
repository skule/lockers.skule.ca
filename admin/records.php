<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Rental Records";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Approve Booking
  if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $adminId = $_SESSION['admin_id'];
    $sql = "UPDATE `record` SET record_status='approved', record_sub='active', record_approved_by='$adminId' WHERE record_id='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Update Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error updating this recrod";
      $msgClass = "red";
    }
  }

  // Delete form handling
  if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = "DELETE FROM `record` WHERE `record_id`='$id'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this recrod";
      $msgClass = "red";
    }
  }
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(function(){
    $('.order-id').on("click",
      function(){
        var textarea = document.createElement("textarea");
        textarea.value = this.innerText;
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        document.execCommand("copy");
        textarea.remove();
      });
  });
</script>
<style>
		.order-id{
		background-color: black;
		border-radius: 5px;
		cursor: pointer;
		}

		.order-id:hover{
		background-color: inherit;
	}

  #order-asterisk{
    margin: 3%;
  }
	</style>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5>Rental Records</h5>
      <div class="divider"></div>
      <br>

      <!-- Search field -->
      <div class="row valign">
        <div class="col s12 m6 l6 right">
          <div class="input-field">
            <i class="material-icons prefix">search</i>
            <input type="text" id="search">
            <label for="search">Search</label>
          </div>
        </div>
      </div>
      <!-- Locker table list -->
      <table id="myTable" class="table highlight centered">
        <thead class="blue darken-2 white-text">
          <tr class="myHead">
            <th>Locker Id</th>
            <th>Student Email</th>
            <th>Start</th>
            <th>End</th>
            <th>Price</th>
            <th class="center-align">Status</th>
            <th>Approved by</th>
            <th>Order ID<a style="color: inherit;" href="#order-asterisk">*</a> <span style="font-size: .75em;">(Click to Copy)</span></th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $sql = "SELECT * FROM `record`";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
          <tr>
            <td><?php echo $row['locker_id']; ?></td>
            <td><?php echo $row['student_email']; ?></td>
            <td><?php echo $row['record_start']; ?></td>
            <td><?php echo $row['record_end']; ?></td>
            <td><?php echo "$"."".$row['record_price']; ?></td>
            <td><?php echo $row['record_status']; ?></td>
            <td><?php echo $row['record_approved_by']; ?></td>
            <td><span class="order-id"><?php echo $row['record_order_id'] ?></span></td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <?php if ($row['record_status'] == 'pending'): ?>
                  <button type='submit' name='update' class='green-text btn1 tooltipped' data-position='right' data-tooltip='Approve'>
                    <i class="fas fa-check"></i>
                  </button>
                <?php else: ?>
                  <button type='submit' name='update' class='blue-text btn1 tooltipped' data-position='right' data-tooltip='Approve' disabled>
                    <i class="fas fa-check"></i>
                  </button>
                <?php endif ?>
              </form>
            </td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <button type='submit' onclick='return confirm(`Delete this record <?php echo $row['record_id']; ?> ?`);' name='delete' class='red-text btn1 tooltipped' data-position='top' data-tooltip='Delete'>
                  <i class='far fa-trash-alt'></i>
                </button>
              </form>
            </td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
    <p id="order-asterisk">*Order ID: This is different from the value shown to the user. The user is shown a two-part number, the first part of which is the order ID. The second part is the "capture ID" and is only relevant for technical debugging.</p>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
