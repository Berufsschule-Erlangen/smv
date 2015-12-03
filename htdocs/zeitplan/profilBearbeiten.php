<?php
if(!$_GET){
	
}else{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

include_once('header.php');
$db = new Database();
$con = $db->connectUser();	
$tmp = $db->userdatenselect($con, $_SESSION['userid']);
$result = $tmp->fetch_assoc(); 

echo '
<div class="container">
  <form action="profilBearbeiten.php?PHPSESSID='. $_GET['PHPSESSID'].'" method="post">
	<div class="form-group">
		<label>Vorname</label>
		<input type="text" class="form-control" name="Vorname" value="'.$result['Vorname'].'">
	</div>
	<div class="form-group">
		<label>Name</label>
		<input type="text" class="form-control" name="Name" value="'.$result['Name'].'">
	</div>
	<div class="form-group">
		<label>Klasse</label>
		<input type="text" class="form-control" name="Klasse" value="'.$result['Klasse'].'">
	</div>
	<div class="form-group">
		<label>Telefonnummer</label>
		<input type="text" class="form-control" name="Telefonnummer" value="'.$result['Telefonnummer'].'">
	</div>
	<div class="form-group">
		<label>Email</label>
		<input type="email" class="form-control" name="Email" value="'.$result['EMail'].'">
	</div>
	<div class="form-group">
		<label>Vorbildung</label>
		<input type="text" class="form-control" name="Vorbildung" value="'.$result['Vorbildung'].'">
	</div>
	<div class="form-group">
		<label>Neues Passwort</label>
		<input type="password" class="form-control" name="Passwort">
	</div>  
	<button type="submit" class="btn btn-primary" name="speichern" value="speichern">Speichern</button>
  </form>
</div>'; 


require_once('footer.php');

if ('POST' == $_SERVER['REQUEST_METHOD']){
	if($_POST['Vorname']!='' and $_POST['Name']!='' and $_POST['Email']!='' and $_POST['Passwort']!=''){
		$db = new Database();
		$con = $db->connectUser();	
		$db->saniUpdaten($con, 	$_POST['Vorname'], 
								$_POST['Name'], 
								$_POST['Klasse'], 
								$_POST['Telefonnummer'], 
								$_POST['Email'], 
								$_POST['Vorbildung'], 
								md5($_POST['Passwort']),
								$_SESSION['userid']);		
	}
	$db->close($con);
	header("Location: profilBearbeiten.php?PHPSESSID=" . $_GET['PHPSESSID']);	
}
?>