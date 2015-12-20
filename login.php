<?php

require_once('classes/Database.class.php');

if ('POST' == $_SERVER['REQUEST_METHOD'])
{
	session_name('PHPSESSID');
	session_start();
	
	$db = new Database();
	$db->connect();
	
	$username = strtolower(trim($_POST['Username']));
	$password = $_POST['Password'];
	
	$usr = $db->get_user_login($username);
	
	if ($usr == null)
		die('Username falsch');
	
	if (substr($usr->passwort, 0, 4) == '$2a$')  // new hash
	{
		$success = ($usr->passwort == crypt($password, $usr->passwort));
	}
	else // old hash
	{
		$success = ($usr->passwort == md5($password));
		
		if ($success) // submitted password seems to be correct; save as new hash
		{
			$salt = md5(time());
			$pw = crypt($password, '$2a$07$'.$salt.'$');
			$db->update_password($usr->id, $pw);
		}
	}
	
	if (!$success)
		die('Passwort flsch');
	
	$_SESSION['userid'] = $usr->id;
	$_SESSION['username'] = $username;
	$_SESSION['rollenid'] = $usr->role;
	// ACHTUNG: NIE PASSWOERTER IN EINER SESSION SPEICHERN => SICHERHEITSRISIKO!!!
	
	$db->close();
	header('Location: index.php?PHPSESSID='.session_id());
	exit();
}

require_once('header.php');
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