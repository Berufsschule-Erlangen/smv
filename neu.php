<?php

session_name('PHPSESSID');
if (!empty($_GET['PHPSESSID']))
	session_id($_GET['PHPSESSID']);
session_start();

require_once __DIR__.'/classes/Database.class.php';

$update = (isset($_GET['update']) && $_GET['update'] == 1);

$db = new Database();
$db->connect();

if ($update)
{
	$usr = $db->select_user(intval($_SESSION['userid']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$vorname = trim($_POST['Vorname']);
	$name = trim($_POST['Name']);
	$klasse = trim($_POST['Klasse']);
	$raum = trim($_POST['Raum']);
	$status = intval($_POST['Status']);
	$email = strtolower(trim($_POST['Email']));
	$vorbildung = trim($_POST['Vorbildung']);
	$passwort = $_POST['Passwort'];
	$passwort2 = $_POST['Passwort2'];
	$telefon = trim($_POST['Telefonnummer']);
	
	$ok = true;
	if(empty($vorname))         { $ok = false; $vornamefehlt  = '<span style="color: #f00;">Bitte ausfüllen</span>'; }
	if(empty($name))            { $ok = false; $namefehlt     = '<span style="color: #f00;">Bitte ausfüllen</span>'; }
	if(empty($email))           { $ok = false; $emailfehlt    = '<span style="color: #f00;">Bitte ausfüllen</span>'; }
	if(empty($passwort))        { $ok = false; $passwortfehlt = '<span style="color: #f00;">Bitte ausfüllen</span>'; }
	if($passwort != $passwort2) { $ok = false; $passwortfehlt = '<span style="color: #f00;">Bitte ausfüllen</span>'; }

	if ($ok)
	{
		$salt = md5(time());
		$pw = crypt($passwort, '$2a$07$'.$salt.'$');

		$db->set_user($update, $vorname, $name, $klasse, $raum, $status, $telefon, null, $email, $vorbildung, $pw, intval($_SESSION['userid']));
	}
	
	$usr = $db->select_user(intval($_SESSION['userid']));
}

require_once('header.php');
?>
<div class="container">
	<form action="neu.php?<?=($update ? 'update=1&' : '')?>PHPSESSID=<?=$_GET['PHPSESSID']?>" method="post">
		<div class="form-group">
			<label>Vorname (Pflichtfeld)<?=$vornamefehlt;?></label>
			<input type="text" class="form-control" name="Vorname" value="<?=$usr->vorname?>">
		</div>
		<div class="form-group">
			<label>Name (Pflichtfeld)<?=$namefehlt;?></label>
			<input type="text" class="form-control" name="Name" value="<?=$usr->name?>">
		</div>
		<div class="form-group">
			<label>Klasse</label>
			<input type="text" class="form-control" name="Klasse" value="<?=$usr->klasse?>">
		</div>
		<div class="form-group">
			<label>Raum</label>
			<input type="text" class="form-control" name="Raum" value="<?=$usr->raum?>">
		</div>
		<div class="form-group">
			<label>Status</label> <small><em>TagessprecherIn für:</em></small>
			<div>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="1"<?=($usr->status == 1 ? ' checked="checked"' : '');?>> Mo</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="2"<?=($usr->status == 2 ? ' checked="checked"' : '');?>> Di</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="3"<?=($usr->status == 3 ? ' checked="checked"' : '');?>> Mi</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="4"<?=($usr->status == 4 ? ' checked="checked"' : '');?>> Do</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="5"<?=($usr->status == 5 ? ' checked="checked"' : '');?>> Fr</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="0"<?=($usr->status == 0 ? ' checked="checked"' : '');?>> nix</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="-1"<?=($usr->status == -1 ? ' checked="checked"' : '');?>> freiwillig aktiv</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="-2"<?=($usr->status == -2 ? ' checked="checked"' : '');?>> externer Supporter</label>
				<label style="font-weight: normal; margin-right: 4px;"><input type="radio" name="Status" value="-3"<?=($usr->status == -3 ? ' checked="checked"' : '');?>> Lehrer</label>
			</div>
		</div>
		<div class="form-group">
			<label>Teams</label>
			<div>
				<?php
				$db = new Database();
				$db->connect();
				$teams = $db->select_all_teams();
				
				foreach ($teams as $team)
				{
					echo '<label style="font-weight: normal; margin-right: 4px;"><input type="checkbox" name="Teams[]" value="'.$team->id.'"> '.$team->bezeichnung.'</label>';
				}
				?>
			</div>
		</div>
		<div class="form-group">
			<label>Bemerkungen</label>
			<input type="text" class="form-control" name="Vorbildung" value="<?=$usr->vorbildung?>">
		</div>
		<div class="form-group">
			<label>Handynummer</label>
			<input type="text" class="form-control" name="Telefonnummer" value="<?=$usr->telefon?>">
		</div>
		<div class="form-group">
			<label>E-Mail Adresse (Pflichtfeld)<?=$emailfehlt;?></label>
			<input type="text" class="form-control" name="Email" value="<?=$usr->email?>">
		</div>
		<div class="form-group">
			<label>Altes oder neues Passwort (Pflichtfeld)<?=$passwortfehlt;?></label>
			<input type="text" class="form-control" name="Passwort">
		</div>
		<div class="form-group">
			<label>Passwort wiederholen (Pflichtfeld)<?=$passwortfehlt;?></label>
			<input type="text" class="form-control" name="Passwort2">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-primary" name="speichern" value="speichern">Speichern</button>
		</div>
	</form>
</div>

<?php
require_once('footer.php');
?>