<?php
if($_GET)
{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

	//header("Location: profilBearbeiten.php?PHPSESSID=" . $_GET['PHPSESSID']);	

if (isset($_GET['d']))
{
   $del = 0 + $_GET['d'];   /// intval   mysql_real_escape_string
//   echo "Gelöscht wird $del";
   $db = new Database();
   $con = $db->connect();
   $tmp = $db->deleteSani($con, $del);
}
	
include_once('header.php');

$db = new Database();
$con = $db->connect();
$tmp = $db->select_all_users($con);

$Rolle = 0 + $_SESSION['rollenid'];
// echo "Rolle = $Rolle";

echo "<div class='container'><table border=1>";

if ($Rolle == 1)
{
  echo '<tr><td width=80 align=center>Klasse</td><td width=200 align=center>Name</td><td width=80 align=center>Raum</td><td align=center>Email</td><td width=200 align=center>Telefonnummer</td><td align=center>Bemerkungen</td><td>Löschen</td></tr>';
  while($user = $tmp->fetch_assoc()) 
  {
     echo '<tr><td align=center>'.$user['Klasse'] . '</td><td align=center><b>'. $user['vorname'] ." ". $user['name'] . '</b> </td>'.
          '<td align=center>'. $user['Raum'] .'</td><td align=center>'. $user['email'] .'</td><td align=center>'. $user['telefonnummer'] . ' </td><td align=center>'. $user['vorbildung'] . ' </td><td align=center><a href="alle.php?PHPSESSID='.$_GET['PHPSESSID'].'&d='.$user['SanitaeterID'].'">x</a></td></tr>';
  } 
}
else
{
  echo '<tr><td width=80 align=center>Klasse</td><td width=200 align=center>Name</td><td width=80 align=center>Raum</td><td align=center>Email</td><td align=center>Bemerkungen</td></tr>';
  while($user = $tmp->fetch_assoc()) 
  {
     echo '<tr><td align=center>'.$user['Klasse'] . '</td><td align=center><b>'. $user['vorname'] ." ". $user['name'] . '</b> </td>'.
          '<td align=center>'. $user['Raum'] .'</td><td align=center>'. $user['email'] .'</td><td align=center>'. $user['vorbildung'] . ' </td></tr>';
  } 
}



echo "</table></div>";

$db = new Database();
$con = $db->connect();
$tmp = $db->select_all_users($con);

echo "<br><br><b>Aktuelle Mail-Verteiler-Liste </b><br><br>";

$user = $tmp->fetch_assoc();
echo $user['email'];

while($user = $tmp->fetch_assoc()) 
{
   echo ', ' . $user['email'];
} 
echo "</table></div>";

require_once('footer.php');

?>