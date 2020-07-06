<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
  <link rel="stylesheet" href="../css/materialize.min.css">
  <script src="../js/fontawesome-all.min.js"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
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
              <div class="row">
                <div class="col s8">
                    <span class="name white-text">
                      &nbsp <?php echo $_SESSION['s_id']; ?>
                    </span>
                    <span class="email white-text">
                      &nbsp <?php echo $_SESSION['s_email']; ?>
                    </span>
                </div>
              </div>
            </div>
          </li>
          <li><a class="white-text" href="index.php">&nbsp  Dashboard</a></li>
          <li><a class="white-text" href="../index.php"></i>&nbsp  Home</a></li>
          <?php if (isset($_SESSION['s_id'])): ?>
            <li><a class="white-text" href="../logout.php">&nbsp Logout</a></li>
          <?php else: ?>
            <li><a  class="white-text" href="../login.php">&nbsp Login</a></li>
          <?php endif ?>
        </ul>

      <!-- Nav - Tablet/Mobile Button -->
      <a href="#" data-activates="nav-mobile" class="button-collapse white-text"
      style="margin-top: -10px;">
        <i class="material-icons">menu</i>
      </a>
    </div>
  </nav>

  <!-- Nav - Desktop -->
  <ul class="side-nav fixed z-depth-0">
    <li>
      <div class="user-view">
        <div class="row">
          <div class="col s8">
            <a href="#">
              <span class="name white-text">&nbsp<?php echo $_SESSION['s_id']; ?></span>
            </a>
            <a href="#">
              <span class="email white-text">&nbsp<?php echo $_SESSION['s_email']; ?></span>
            </a>
          </div>
        </div>
      </div>
    </li>
    <li><a class="white-text" href="index.php">&nbsp  Dashboard</a></li>
    <li><a class="white-text" href="../index.php">&nbsp  Home</a></li>
    <?php if (isset($_SESSION['s_id'])): ?>
      <li><a class="white-text" href="../logout.php">&nbsp Logout</a></li>
    <?php else: ?>
      <li><a class="white-text" href="../login.php"><i class="fas fa-sign-in-alt"></i>&nbsp Login</a></li>
    <?php endif ?>
  </ul>
