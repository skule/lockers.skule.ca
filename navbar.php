<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Skule Locker Rental</title>
  <link rel="stylesheet" href="css/materialize.min.css">
  <script src="js/fontawesome-all.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>

<style>
.side-nav {
padding-top: 30px;
}
.links li a {
  color: white;
}
</style>
<body>
  <a class="content-link" href="#screen-reader-content">Skip navigation</a>
  <nav role="navigation" class="z-depth-0">
    <div class="nav-wrapper">

      <!-- Skule Logo -->
      <a href="index.php" class="brand-logo center" style="padding: 0;">
        <img class="logo-img col" src="img/skule_logo.png" alt="SKULE">
        <img class="logo-img-lockers" src="img/lockers.png" alt="Lockers">
      </a>

      <!-- Nav - Mobile Button -->
      <a href="#" data-activates="nav-mobile" class="button-collapse white-text"
        style="margin-top: -10px;">
        <i class="material-icons">menu</i>
      </a>

      <!-- Nav - Desktop -->
      <ul class="right hide-on-med-and-down">
        <!-- If logged in -->
        <?php if(isset($_SESSION['s_id'])): ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="user/index.php">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        <!-- If not logged in -->
        <?php else: ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="register.php">Register</a></li>
          <li><a href="login.php">Login</a></li>
        <?php endif ?>
      </ul>

      <!-- Nav - Mobile -->
      <ul id="nav-mobile" class="side-nav right links">
      <!-- If logged in -->
        <?php if(isset($_SESSION['s_id'])): ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="user/index.php">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
          <!-- If not logged in -->
        <?php else: ?>
          <li><a href="index.php">Home</a></li>
          <li><a href="register.php">Register</a></li>
          <li><a href="login.php">Login</a></li>
        <?php endif ?>
      </ul>

    </div>
  </nav>
  <span id="screen-reader-content"></span>
