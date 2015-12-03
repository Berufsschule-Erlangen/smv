<?php
if(!$_GET){
	
}else{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

include_once('header.php');
?>

<div class="container">
  <form <?php echo 'action="saniAnlegen.php?PHPSESSID='.$_GET['PHPSESSID'].'"' ?> method="post">
	<div class="form-group">
		<label>Vorname</label>
		<input type="text" class="form-control" name="Vorname">
	</div>
	<div class="form-group">
		<label>Name</label>
		<input type="text" class="form-control" name="Name">
	</div>
	<div class="form-group">
		<label>Klasse</label>
		<input type="text" class="form-control" name="Klasse">
	</div>
	<div class="form-group">
		<label>Telefonnummer</label>
		<input type="text" class="form-control" name="Telefonnummer">
	</div>
	<div class="form-group">
		<label>Email</label>
		<input type="email" class="form-control" name="Email">
	</div>
	<div class="form-group">
		<label>Vorbildung</label>
		<input type="text" class="form-control" name="Vorbildung">
	</div>
	<div class="form-group">
		<label>Passwort</label>
		<input type="password" class="form-control" name="Passwort">
	</div>  
	<button type="submit" class="btn btn-primary" name="anlegen" value="anlegen">Anlegen</button>
  </form>
</div> <!-- /container -->

<?php
require_once('footer.php');
?>

<?php
if ('POST' == $_SERVER['REQUEST_METHOD']){
	if($_POST['Vorname']!='' and $_POST['Name']!='' and $_POST['Email']!='' and $_POST['Passwort']!=''){
		$db = new Database();
		$con = $db->connectUser();	
		$db->saniAnlegen($con, 	$_POST['Vorname'], 
								$_POST['Name'], 
								$_POST['Klasse'], 
								$_POST['Telefonnummer'], 
								$_POST['Email'], 
								$_POST['Vorbildung'], 
								md5($_POST['Passwort']));		
	}
	$db->close($con);
	header("Location: saniAnlegen.php?PHPSESSID=" . $_GET['PHPSESSID']);
	echo "Sanitäter erfolgreich angelegt <3";
}
?>