<?php
if($_GET)
{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

$update = 0 + $_GET['update'];


if($update)
{
   $db = new Database();
   $con = $db->connect();	
   $tmp = $db->userdatenselect($con, $_SESSION['userid']);
   $result = $tmp->fetch_assoc(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

  // alle Variablen übernehmen
  if($_POST['update']) $update = 0 + $_POST['update'];
  $vorname = $_POST['Vorname'];
  $name = $_POST['Name'];
  $klasse = $_POST['Klasse'];
  $raum = $_POST['Raum'];
  $status = $_POST['Status'];
  $email = strtolower($_POST['Email']);
  $vorbildung = $_POST['Vorbildung'];
  $passwort = $_POST['Passwort'];
  $passwort2 = $_POST['Passwort2'];
  $telefonnummer= $_POST['Telefonnummer'];

  // Pflichtfelder prüfen
  $ok=true;
  if( $vorname=='' ) { $ok=false; $vornamefehlt = "<font color='red'>Bitte ausfüllen</font>"; }
  if( $name==''    ) { $ok=false; $namefehlt = "<font color='red'>Bitte ausfüllen</font>"; }
  if( $email==''   ) { $ok=false; $emailfehlt = "<font color='red'>Bitte ausfüllen</font>"; }
  if( $passwort=='') { $ok=false; $passwortfehlt = "<font color='red'>Bitte ausfüllen</font>"; }
  if( $passwort!=$passwort2 ) { $ok=false; $passwortfehlt = "<font color='red'>Bitte ausfüllen</font>"; }

  // Ablegen in Datenbank 
  if( $ok==true )
  {
	
		$db = new Database();
		$con = $db->connect();	
		$db->saniUpdaten($con, $update,
					$_POST['Vorname'], 
					$_POST['Name'], 
					$_POST['Klasse'], 
					$_POST['Raum'],
					$status,
					$_POST['Telefonnummer'], 
					strtolower($_POST['Email']), 
					$_POST['Vorbildung'], 
					md5($_POST['Passwort']),
					$_SESSION['userid']);	
					
		$result = $_POST;
  }
  else
  { 
    $result = $_POST;
  }
	
	//header("Location: profilBearbeiten.php?PHPSESSID=" . $_GET['PHPSESSID']);	
}

include_once('header.php');

echo '
<div class="container">
  <form action="neu.php?PHPSESSID='. $_GET['PHPSESSID'].'" method="post">
	<div class="form-group">
		<label>Vorname* (Pflichtfeld) '. $vornamefehlt .'</label>
		<input type="text" class="form-control" name="Vorname" value="'.$result['Vorname'].'">
	</div>
	<div class="form-group">
		<label>Name* (Pflichtfeld) '. $namefehlt .'</label>
		<input type="text" class="form-control" name="Name" value="'.$result['Name'].'">
	</div>
	<div class="form-group">
		<label>Klasse</label>
		<input type="date" class="form-control" name="Klasse" value="'.$result['Klasse'].'">
	</div>
		<div class="form-group">
		<label>Raum <font color=green size=1>  Neu</font> </label>
		<input type="text" class="form-control" name="Raum" value="'.$result['Raum'].'">
	</div>';
	
	echo '
        	<div class="form-group">
		<label>Status: &nbsp; </label> <i> TagessprecherIn für:</i> ';
		

	echo '<input type="radio" name="Status" value="1" ';
	if ($result['Status'] == 1) echo  ' checked="checked" ';
	echo '> Mo </input> &nbsp; ';
	echo '<input type="radio" name="Status" value="2" ';
	if ($result['Status'] == 2) echo  ' checked="checked" ';
	echo '> Di </input> &nbsp; ';
	echo '<input type="radio" name="Status" value="3" ';
	if ($result['Status'] == 3) echo  ' checked="checked" ';
	echo '> Mi </input> &nbsp; ';
	echo '<input type="radio" name="Status" value="4" ';
	if ($result['Status'] == 4) echo  ' checked="checked" ';
	echo '> Do </input> &nbsp; ';
	echo '<input type="radio" name="Status" value="5" ';
	if ($result['Status'] == 5) echo  ' checked="checked" ';
	echo '> Fr </input> &nbsp; ';
	//echo '<input type="radio" name="Status" value="0" ';
	//if ($result['Status'] == NULL or $result['Status'] == 0 ) echo  ' checked="checked" ';
	//echo '> bin kein Tagessprecher </input> &nbsp; ';
	echo ' <b>...bzw...</b> <input type="radio" name="Status" value="-1" ';
	if ($result['Status'] == -1) echo  ' checked="checked" ';
	echo '> freiwilliges aktives SMV-Mitglied</input> &nbsp; ';
	echo '<input type="radio" name="Status" value="-2" ';
	if ($result['Status'] == -2) echo  ' checked="checked" ';
	echo '> externer SMV-Supporter</input> &nbsp; ';
	echo '<input type="radio" name="Status" value="-3" ';
	if ($result['Status'] == -3) echo  ' checked="checked" ';
	echo '> Lehrer</input> &nbsp; ';
	   echo '<font color=green size=1> Neu </font>';
	   
	echo '
	<div class="form-group">
		<label>Teams: &nbsp; </label>';
		
		 
	     $db = new Database();
             $con = $db->connect();
             
             $kw_result = $db->select_all_teams($con);
         
             while ($row = $kw_result->fetch_assoc()) 
             { 
                 $tid = $row['tid'];
                 $team = $row['bezeichnung'];
                 echo '<input type="checkbox" name="Teams[]" value="'.$tid.'"> '.$team.' </input> &nbsp; ';
               /*  
                 if (0) 
                 {
                       echo '<input selected="selected" value="'.$team'">'.$team.' </option>';
                 }
                 else 
                 {
                       echo '<option value="'.$team.'">'.$team.' </option>';
                 }
                 */
             }
             echo '<font color=red size=1> Teams funktionieren noch nicht ... ich arbeite dran :)</font>';
             

             
        echo '
		
	</div>	
	<div class="form-group">
		<label>Bemerkungen</label>
		<input type="text" class="form-control" name="Vorbildung" value="'.$result['Vorbildung'].'">
	</div>
	<div class="form-group">
		<label>private Handynummer</label>
		<input type="text" class="form-control" name="Telefonnummer" value="'.$result['Telefonnummer'].'">
	</div>
	<div class="form-group">
		<label>Email* (Pflichtfeld) '. $emailfehlt .'</label>
		<input type="email" class="form-control" name="Email" value="'.$result['Email'].'">
	</div>
	<div class="form-group">
		<label>Neues oder altes Passwort* (Pflichtfeld) '. $passwortfehlt .'   ... hier kann man das Passwort ändern</label>
		<input type="password" class="form-control" name="Passwort">
	</div>  
	<div class="form-group">
		<label>Passwort wiederholen* (Pflichtfeld) '. $passwortfehlt .' </label>
		<input type="password" class="form-control" name="Passwort2">
	</div> 
	<input type="hidden" name=update value=' . $update . '>
	<button type="submit" class="btn btn-primary" name="speichern" value="speichern">Speichern</button>
  </form>
</div>'; 


require_once('footer.php');

?>