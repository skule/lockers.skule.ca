<?php
  session_start();
  include 'navbar.php';
  require 'model/db.php';

  // If user already logged in, redirect them to index page
  if (isset($_SESSION['s_id'])) {
    header("Location: index.php");
  }

  $msg = $msgClass = '';

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    // Get form data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check required fields
    if (empty($id) || empty($name) || empty($email) || empty($password)){
      $msg = "Please fill in all fields";
      $msgClass = "red";
    } else {

      // Check email
      if (filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        $msg = "Please use a valid email";
        $msgClass = "red";
      } else {
        // Hashing the password
        $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO `student` (`student_id`, `student_pwd`, 
            `student_name`, `student_email`)
            VALUES ('$id', '$hashedPwd', '$name', '$email')";

        // TODO: User friendly error messages 
        if (mysqli_query($conn, $sql)){
          $msg = "Register Successful <a href='login.php' class='white-text'>Login</a>";
          $msgClass = "green";
        } else {
          $msg = "Register error: " . $sql . "<br>" . mysqli_error($conn);
          $msgClass = "red";
        }
      }
    }
  }
?>
<style>
// Finally sticky footer! Good god have mercy
body {
  display: flex;
  flex-direction: column;
}

.site-content {
  flex: 1 0 auto;
}

</style>

<!-- Register form -->
<div class="container site-content">
  <div class="box">
    <div class="row">
      <div class="col s12 m12 l12">
        <?php if($msg != ''): ?>
        <div id="msgBox" class="card-panel <?php echo $msgClass; ?>">
          <span class="white-text"><?php echo $msg; ?></span>
        </div>
        <?php endif ?>
        <div class="card">
          <div class="card-content" style="padding: 40px;">
            <span class="card-title center-align">User Registration</span>
            <div class="row">
              <form class="col s12" method="POST" 
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                novalidate>

                <!-- Student Number -->
                <div class="row">
                  <div class="input-field">
                    <input type="text" id="id" name="id"
                      value="<?php echo isset($_POST['id']) ? htmlspecialchars($_POST['id']) : ''; ?>">
                    <label for="id">Student Number</label>
                  </div>
                </div>

                <!-- Name -->
                <div class="row">
                  <div class="input-field">
                    <input type="text" id="name" name="name"
                      value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    <label for="name">Student Full Name</label>
                  </div>
                </div>

                <!-- Email -->
                <div class="row">
                  <div class="input-field">
                    <input type="email" id="email" name="email"
                      value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <label for="email">Student Email</label>
                  </div>
                </div>

                <!-- Password -->
                <div class="row">
                  <div class="input-field">
                    <input type="password" id="password" name="password">
                    <label for="userid">Password</label>
                  </div>
                </div>

                <!-- Submit -->
                <div class="row">
                  <p class="center-align">
                    Already registered? <a href="login.php">Login</a><br><br>
                    <button type="submit" class="waves-effect waves-light btn blue" name="submit">
                      Register
                    </button>
                  </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  mysqli_close($conn);
  include 'footer.php';
?>
