<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <?php if(isset($title)) echo("<title>$title</title>"); ?>
  <link rel="stylesheet" href="../css/materialize.min.css">
  <script src="../js/fontawesome-all.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <script>
  //Echo Self-XSS warning
    console.log("%cSTOP!\n%cDon't listen to anyone who tells you to paste anything in here.\nThey're trying to hack you and probably trying to gain access to your PayPal account.\n%cMore info: https://lockers.skule.ca/self-xss.php", "color: red; font-size: 50pt; text-shadow: 0 0 3px black;", "color: red; font-size: 20pt;", "color: red; font-size: 13pt;");
  </script>
</head>
<body>
  <a class="content-link" href="#screen-reader-content">Skip navigation</a>
  <nav role="navigation" class="z-depth-0">
    <div class="nav-wrapper">
      <!-- Skule Logo -->
      <a id="logo-container" href="index.php" class=" center brand-logo">
        <img class="logo-img" src="../img/skule_logo.png" alt="logo">
        <img class="logo-img-lockers" src="../img/lockers.png" alt="logo">
      </a>

      <!-- Nav - Tablet/Mobile -->
        <ul id="nav-mobile" class="side-nav ">
          <li>
            <div class="user-view">
              <a href="../admin/index.php">
                <span class="name white-text">&nbsp  <?php echo $_SESSION['admin_uname']; ?></span>
              </a>
              <a href="../admin/index.php">
                <span class="email white-text">&nbsp  <?php echo $_SESSION['admin_email']; ?></span>
              </a>
            </div>
          </li>
          <li><a class="white-text" href="index.php">&nbsp  Dashboard</a></li>
          <li><a class="white-text" href="records.php">&nbsp  Rental Records</a></li>
          <li><a class="white-text" href="locker.php">&nbsp  Lockers</a></li>
          <li><a class="white-text" href="users.php">&nbsp  Users</a></li>
          <li><a class="white-text" href="admin.php">&nbsp  Admins</a></li>

          <?php if (isset($_SESSION['admin_uname'])): ?>
            <li><a class="white-text" href="logout.php">&nbsp Logout</a></li>
          <?php else: ?>
            <li><a href="login.php">&nbsp Login</a></li>
          <?php endif ?>
        </ul>

      <!-- Nav - Tablet/Mobile Button -->
      <a href="#" data-activates="nav-mobile" class="button-collapse white-text"
          style="margin-top: -10px;">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>


  <ul class="side-nav fixed z-depth-0 ">
    <li>
      <div class="user-view">
        <a href="../admin/index.php">
          <span class="name white-text">&nbsp  <?php echo $_SESSION['admin_uname']; ?></span>
        </a>
        <a href="../admin/index.php">
          <span class="email white-text">&nbsp  <?php echo $_SESSION['admin_email']; ?></span>
        </a>
      </div>
    </li>
    <li><a class="white-text" href="index.php">&nbsp  Dashboard</a></li>
    <li><a class="white-text" href="records.php">&nbsp  Rental Records</a></li>
    <li><a class="white-text" href="locker.php">&nbsp  Lockers</a></li>
    <li><a class="white-text" href="users.php">&nbsp  Users</a></li>
    <li><a class="white-text" href="admin.php">&nbsp  Admins</a></li>

    <?php if (isset($_SESSION['admin_uname'])): ?>
      <li><a class="white-text" href="logout.php">&nbsp Logout</a></li>
    <?php else: ?>
      <li><a href="login.php">&nbsp Login</a></li>
    <?php endif ?>
  </ul>
  <span id="screen-reader-content"></span>
