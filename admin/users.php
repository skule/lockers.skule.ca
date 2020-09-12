<?php
  session_start();
  require 'session.php';
  $title = "Skule™ Lockers Admin | Manage Users";
  include 'navbar.php';
  require '../model/db.php';

  $msg = $msgClass = '';

  // Delete form handling
  if (isset($_POST['delete'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = "DELETE FROM `student` WHERE `student_email`='$email'";

    if (mysqli_query($conn, $sql)) {
      $msg = "Delete Successfull";
      $msgClass = "green";
    } else {
      $msg = "Error deleting this recrod";
      $msgClass = "red";
    }
  }
?>
<div class="wrapper">
  <section class="section">
    <div class="container2">
      <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
      <?php endif ?>
      <h5>Users List</h5>
      <div class="divider"></div>
      <br>
      <div class="row">
        <div class="col s12 m6"></div>
        <div class="col s12 m6">
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
            <th>Student Email</th>
            <th>Name</th>
            <th colspan="2" class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $i = 1;
            $sql = "SELECT * FROM `student`";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)):
          ?>
          <tr>
            <td><?php echo $row['student_email']; ?></td>
            <td><?php echo $row['student_name']; ?></td>
            <td>
              <a href='users_edit.php?email=<?php echo $row['student_email']; ?>' class='btn-flat blue-text tooltip' data-position='right' data-tooltip='Edit'><i class='fas fa-pencil-alt'></i></a>
            </td>
            <td>
              <form method='POST' action='users.php'>
                <input type='hidden' name='id' value="<?php echo $row['student_email'];?>">
                <button type='submit' onclick='return confirm(`Delete this user <?php echo $row['student_email']; ?>?`);' name='delete' class='btn-flat red-text tooltipped' data-position='top' data-tooltip='Delete'>
                  <i class='far fa-trash-alt'></i>
                </button>
              </form>
            </td>
          </tr>
          <?php endwhile ?>
        </tbody>
      </table>
    </div>
  </section>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
