<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Rental Records";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';
  $allRecords = isset($_GET['allRecords']) && $_GET['allRecords'] == '1';

  // Handle form submission for refunding or archiving records
  if (
    isset($_POST['refund']) || isset($_POST['unrefund']) ||
    isset($_POST['archive']) || isset($_POST['unarchive'])
  ) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Determine the action and column to update
    if (isset($_POST['refund']) || isset($_POST['unrefund'])) {
      $column = 'is_refunded';
      $value = isset($_POST['refund']) ? 'TRUE' : 'FALSE';
      $action = isset($_POST['refund']) ? 'refunded' : 'un-refunded';
    } else {
      $column = 'is_archived';
      $value = isset($_POST['archive']) ? 'TRUE' : 'FALSE';
      $action = isset($_POST['archive']) ? 'archived' : 'un-archived';
    }

    // SQL insert or update
    $sql = "INSERT INTO record_meta (record_id, $column)
            VALUES ('$id', $value)
            ON DUPLICATE KEY UPDATE $column = $value;";

    if (mysqli_query($conn, $sql)) {
      $msg = "Record $id $action";
      $msgClass = "green";
    } else {
      $msg = "Error marking record $id as $action";
      $msgClass = "red";
    }
  }

  if (isset($_POST['update_comment'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $sql = "INSERT INTO record_meta (record_id, comment)
            VALUES ('$id', '$comment')
            ON DUPLICATE KEY UPDATE comment = '$comment'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Comment updated for record $id";
      $msgClass = "green";
    } else {
      $msg = "Error updating comment for record $id";
      $msgClass = "red";
    }
  }

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  function toggleArchive() {
    const checked = document.getElementById('archiveToggle').checked;
    const url = new URL(window.location.href);
    
    if (checked) {
      url.searchParams.set('allRecords', '1');
    } else {
      url.searchParams.delete('allRecords');
    }
    
    window.location.href = url.toString();
  }
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
      <!-- Filters -->
      <div class="row valign-wrapper">
        <!-- Archive toggle on the left -->
        <div class="col s12 m6 l6 left-align flow-text">
          <label>
            <input type="checkbox" id="archiveToggle" <?php echo $allRecords ? 'checked' : ''; ?> onchange="toggleArchive()">
            <span><?php echo $allRecords ? 'Show Only Active' : 'Show All'; ?></span>
          </label>
        </div>

        <!-- Search field on the right -->
        <div class="col s12 m6 l6 right-align">
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
            <th>Comment</th>
            <th>Order ID<a style="color: inherit;" href="#order-asterisk">*</a> <span style="font-size: .75em;">(Click to Copy)</span></th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
			      $sql = "SELECT r.locker_id, student_email, book_date, record_start, record_end, 
                           record_capture_id, r.record_id, locker_price, 
                           comment, is_refunded, is_archived 
                    FROM `record` r 
                    left join `locker` l on r.locker_id = l.locker_id 
                    left join `record_meta` m on r.record_id = m.record_id";
            if (!$allRecords) {
              $sql .= " WHERE is_archived = 0";
            }
            $sql .= " ORDER BY book_date DESC";
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
            <td>
            <form method="POST" action="records.php" style="display: flex; align-items: center; gap: 2px; margin: 0;">
              <input type="hidden" name="id" value="<?php echo $row['record_id']; ?>">
              <input
                type="text"
                name="comment"
                value="<?php echo htmlspecialchars($row['comment'] ?? ''); ?>"
                style="flex: 1; min-width: 0; margin: 0; padding: 2px 4px; font-size: 13px; line-height: 1.2; height: 24px;"
                placeholder="Add comment"
              >
              <button
                type="submit"
                name="update_comment"
                class="btn-flat"
                style="padding: 0; height: 24px; line-height: 1;"
                title="Save"
              >
                <i class="fa fa-check" style="font-size: 14px;"></i>
              </button>
            </form>
            </td>
            <td><span class="capture-id"><?php echo htmlspecialchars($row['record_capture_id']); ?></span></td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <?php if ($row['is_refunded'] === '1'): ?>
                  <button type='submit' name='unrefund' class='grey-text btn1 tooltipped' data-position='top' data-tooltip='Unmark as Refunded'>
                    <i class="fa fa-share"></i>
                  </button>
                <?php else: ?>
                  <button type='submit' name='refund' class='green-text btn1 tooltipped' data-position='top' data-tooltip='Refund' onclick='return confirm(`Refund record <?php echo $row['record_id']; ?> ?`);' >
                    <i class="fa fa-solid fa-reply"></i>
                  </button>
                <?php endif; ?>
              </form>
            </td>
            <td>
              <form method='POST' action='records.php'>
                <input type='hidden' name='id' value='<?php echo $row['record_id']; ?>'>
                <?php if ($row['is_archived'] === '1'): ?>
                  <button type='submit' name='unarchive' class='grey-text btn1 tooltipped' data-position='top' data-tooltip='Unarchive'>
                    <i class='fa fa-solid fa-folder-open'></i>
                  </button>
                <?php else: ?>
                  <button type='submit' name='archive' class='blue-text btn1 tooltipped' data-position='top' data-tooltip='Archive'>
                    <i class='fa fa-archive'></i>
                  </button>
                <?php endif; ?>
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
