<?php
if(!$_GET){
	
}else{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

include_once('header.php');
session_write_close();
header("Location: index.php");

?>