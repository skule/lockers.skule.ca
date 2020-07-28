<?php
  require '../model/db.php';
  if (!isset($_SESSION['admin_uname'])){
    header("Location: login.php");
  }
?>
