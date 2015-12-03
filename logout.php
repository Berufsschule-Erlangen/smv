<?php
  if($_GET) session_start($_GET['PHPSESSID']);

  require_once('classes/Database.class.php');

  session_write_close();

  header("Location: .");
?>