<?php
  require '../model/db.php';
  if (!isset($_SESSION['admin_uname'])){
    echo("Location: login.php");
  }
?>
