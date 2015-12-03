<?php
require_once('classes/Database.class.php');

if ('POST' == $_SERVER['REQUEST_METHOD']){
	session_start();
	$db = new Database();
	$con = $db->connect();	
	$Username =  strtolower($_POST['Username']);
	$Password = md5($_POST['Password']);
	$SID = session_id();
	
        $sql = "SELECT SanitaeterID
            FROM smv_user
            WHERE EMail = '".$Username."' 
            AND deleted is NULL";
	$result = $con->query($sql);
	
	if(!$tmp = $result->fetch_assoc()){
		echo "Username falsch";
		//echo mysql_error();
		return;  
	}else{
		$ID = $tmp['SanitaeterID'];
	}
	
	
	$sql = "SELECT Passwort, RollenID
            FROM smv_user
            WHERE SanitaeterID = ".$ID;
	$result = $con->query($sql);	
	
	if(!$tmp = $result->fetch_assoc()){
		echo "Password falsch";
		return;  
	}else{
		$DBPassword = $tmp['Passwort'];
		$Rollenid = $tmp['RollenID'];	
	}	
	
	if($DBPassword != $Password){
		echo "Passwort falsch";
		return;  
	}
	
	$_SESSION['userid'] = $ID;
	$_SESSION['username'] = $Username;
	$_SESSION['password'] = $Password;
	$_SESSION['rollenid'] = $Rollenid;	
	$db->close($con);
	//echo "<a href='http://ebertebert.de/sani/index.php?PHPSESSID=" . $SID."'>weiter</a>";
	header("Location: http://ebertebert.de/smv/index.php?PHPSESSID=" . $SID);
        die();
}
	
?>

<?
include_once('header.php');
?>

<div class="container">
  <form class="form-signin" action="login.php" method="post">
    <h2 class="form-signin-heading">Bitte einloggen</h2>
    <label for="inputEmail" class="sr-only">Email</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email" name="Username" >
    <label for="inputPassword" class="sr-only">Passwort</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Passwort" name="Password" >
   
    <button class="btn btn-lg btn-primary btn-block" type="submit" value="login">Login</button>
  </form>
</div> 

<?php
require_once('footer.php');
?>

