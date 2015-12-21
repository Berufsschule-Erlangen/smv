<?php

session_name('PHPSESSID');
if (!empty($_GET['PHPSESSID']))
	session_id($_GET['PHPSESSID']);
session_start();

session_unset();
session_destroy();

header('Location: index.php');

?>