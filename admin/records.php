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
    $('.capture-id').on("click",
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
  //Thanks, sampopes from StackOverflow!
      function fnExcelReport(){
        var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
        var textRange; var j=0;
        tab = document.getElementById('myTable'); // id of table

        for(j = 0 ; j < tab.rows.length ; j++)
        {
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)){      // If Internet Explorer
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
        }
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

        return (sa);
      }
</script>
<style>
		.capture-id{
		background-color: black;
		border-radius: 5px;
		cursor: pointer;
		}

		.capture-id:hover{
		background-color: inherit;
	}

  #order-asterisk{
    margin: 1%;
    margin-bottom: 1%;
  }

  #btnExport{
    margin: 1%;
    margin-top: 0;
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
            <th>Book Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Price</th>
            <th>Order ID<a style="color: inherit;" href="#order-asterisk">*</a> <span style="font-size: .75em;">(Click to Copy)</span></th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
			$sql = "SELECT r.locker_id, student_email, book_date, record_start, record_end, record_status, record_capture_id, record_id, locker_price FROM `record` r left join `locker` l on r.locker_id = l.locker_id;";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)):
          ?>
          <tr>
            <td><?php echo htmlspecialchars($row['locker_id']); ?></td>
            <td><?php echo htmlspecialchars($row['student_email']); ?></td>
            <td><?php echo htmlspecialchars($row['book_date']); ?></td>
            <td><?php echo htmlspecialchars($row['record_start']); ?></td>
            <td><?php echo htmlspecialchars($row['record_end']); ?></td>
            <td><?php 
				$price = $row['locker_price'] ?? 0;
				echo "$" . (intval($price) == $price ? intval($price) : number_format($price, 2));
			?></td>
            <td><span class="capture-id"><?php echo htmlspecialchars($row['record_capture_id']); ?></span></td>
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
    <button class="btn" id="btnExport" onclick="fnExcelReport();"> Export as XLSX </button>
  </section>
</div>

<iframe id="txtArea1" style="display:none"></iframe>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
