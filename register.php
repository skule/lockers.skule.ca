<?php
  session_start();
  require 'model/db.php';
  require 'functions.php';

  // If user already logged in, redirect them to index page
  if (isset($_SESSION['s_email'])) {
    header("Location: index.php");
  }

  $msg = $msgClass = '';

  // Check for submit
  if (filter_has_var(INPUT_POST, 'submit')){
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check required fields
    if (empty($name) || empty($email) || empty($department) || empty($password) || empty($phone) || !isset($_POST['disclaimer'])){
      $msg = "Please fill in all fields and confirm that you have read the notice.";
      $msgClass = "red";
    } else {

      // Check email
      if (strlen($email) > 320 || filter_var($email, FILTER_VALIDATE_EMAIL) === false || !is_email_utoronto($email)){
        $msg = "Please use a valid UofT or EngSoc email";
        $msgClass = "red";
      } else {
        // Hashing the password
        $hashedPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO `student` (`student_pwd`,
            `student_name`, `student_email`, `student_department`, `student_phone`)
            VALUES ('$hashedPwd', '$name', '$email', '$department', '$phone')";

        // TODO: User friendly error messages
        if (mysqli_query($conn, $sql)){
          //Register was successful, log user in
          $_SESSION['s_name'] = $name;
          $_SESSION['s_email'] = $email;
          $_SESSION['s_phone'] = $phone;

          //After logging in, if a return-to address was set, return there
          if(isset($_GET['return-to']))
            header("Location: /".$_GET['return-to']);
          else
            header("Location: /");
        } else {
          $msg = "Register error: " . $sql . "<br>" . mysqli_error($conn);
          $msgClass = "red";
        }
      }
    }
  }



  include 'navbar.php';
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
                action="#"
                novalidate>

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
                    <label for="email">Student Email (UofT or Skule)</label>
                  </div>
                </div>

                <!-- Department -->
                <div class="row">
                  <div class="input-field">
                    <select id="department" name="department">
                      <option disabled selected></option>
                      <?php
                        $departments = array("Chem", "Civ", "ECE", "EngSci", "Indy", "Mech", "Min", "MSE", "T1", "Grad");
                        foreach ($departments as $department) {
                          //If a department was selected, keep it selected
                          echo("<option value=$department".(isset($_POST['department']) && $_POST['department'] == $department ? " selected" : "")." > $department </option>");
                        }
                      ?>
                    </select>
                    <label for="department">Department</label>
                  </div>
                </div>

                <!-- Phone -->
                <div class="row">
                  <div class="input-field">
                    <input type="tel" id="phone" name="phone"
                      value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    <label for="phone">Student Phone Number</label>
                  </div>
                </div>

                <!-- Password -->
                <div class="row">
                  <div class="input-field">
                    <input type="password" id="password" name="password">
                    <label for="userid">Password</label>
                  </div>
                </div>
                <div class="row">
                  <p><b>Please note:</b> This system is intended for Engineering students only. By signing up, you understand and agree that if we find out that you are not an Engineering Undergraduate or an Engineering Graduate student, we will cut your lock and empty the contents of your locker.</p>

                    <input type="checkbox" id="disclaimer" name="disclaimer"/>
                    <label for="disclaimer">I have read and agree to the above</label>

                </div>
                <!-- Submit -->
                <div class="row">
                  <p class="center-align">
                    Already registered? <a href="login.php<?php
                      if(isset($_GET['return-to']))
                        echo("?return-to=".$_GET['return-to']);
                      ?>">Login</a><br><br>
                      <!-- PAYPAL INTEGRATION HERE -->
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
