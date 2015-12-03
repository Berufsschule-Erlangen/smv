<?php
require_once('classes/Database.class.php');

include_once('header.php');
?>

<div class="container">
  <form class="form-signin" action="login.php" method="post">
    <h2 class="form-signin-heading">Bitte einloggen</h2>
    <label for="inputEmail" class="sr-only">Email Addresse</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="Email Addresse" name="Username" >
    <label for="inputPassword" class="sr-only">Passwort</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Passwort" name="Password" >
   
    <button class="btn btn-lg btn-primary btn-block" type="submit" value="login">Login</button>
  </form>
</div> 

<?php
require_once('footer.php');
?>
<?php

if ('POST' == $_SERVER['REQUEST_METHOD']){
	session_start();
	$db = new Database();
	$con = $db->connectUser();	
	$Username = $_POST['Username'];
	$Password = md5($_POST['Password']);
	$SID = session_id();
	
    $sql = "SELECT SanitaeterID
            FROM user
            WHERE EMail = '".$Username."'";
	$result = $con->query($sql);
	
	if(!$tmp = $result->fetch_assoc()){
		echo "Username falsch";
		return;  
	}else{
		$ID = $tmp['SanitaeterID'];
	}
	
	
	$sql = "SELECT Passwort, RollenID
            FROM user
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
	header("Location: index.php?PHPSESSID=" . $SID);
    
}
	
	?>
