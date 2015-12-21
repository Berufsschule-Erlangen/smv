<?php

session_name('PHPSESSID');
if (!empty($_GET['PHPSESSID']))
	session_id($_GET['PHPSESSID']);
session_start();

require_once('classes/Database.class.php');
require_once('header.php');

$db = new Database();
$db->connect();
$userid = intval($_SESSION['userid']);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$montag     = (isset($_POST['montag'])     && $_POST['montag'] == 1)     ? 1 : 0;
	$dienstag   = (isset($_POST['dienstag'])   && $_POST['dienstag'] == 1)   ? 1 : 0;
	$mittwoch   = (isset($_POST['mittwoch'])   && $_POST['mittwoch'] == 1)   ? 1 : 0;
	$donnerstag = (isset($_POST['donnerstag']) && $_POST['donnerstag'] == 1) ? 1 : 0;
	$freitag    = (isset($_POST['freitag'])    && $_POST['freitag'] == 1)    ? 1 : 0;
	
	$akw = intval($_POST['akw']);
	
	$db->insert_anwesenheit($akw, $userid, $montag, $dienstag, $mittwoch, $donnerstag, $freitag);
}

?>

<div class="row">
	<div class="col-sm-12">
		<h2>Deine aktuelle Anwesenheit</h2>
		
		<p>
		<h4>Heute ist der <?=date('d.m.Y')?></h4>
		
		<strong>Einzeltage ändern:</strong> Klicke auf einzelne Tage <span style="color: #0f0;">einer</span> Woche und speichere diese Woche.
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<table class="table">
			<thead>
				<tr>
					<th>KW</th>
					<th>Schulwoche</th>
					<th>Mo</th>
					<th>Di</th>
					<th>Mi</th>
					<th>Do</th>
					<th>Fr</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$akw = date('W', time());
				for ($kw = $akw, $i = 0; $i < 4 && $kw <= number_of_weeks($schuljahr) + 1; $kw++, $i++)
				{
					$anwesenheit = $db->select_anwesend($kw, $userid);
					$anwesend = $anwesenheit[0];
					
					echo '<form action="anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
					echo '<input type="hidden" name="akw" value="'.$kw.'">';
					echo '<tr>';
					
					if ($kw == $anzahl_kw + 1)
					{
						$kw = 1;
						$neues_jahr = 1;
					}
					
					if ($neues_jahr == 1 && $kw == ($letzte_kw + 1))
						break;
					
					echo '<td>'.$kw.'</td>';
					echo '<td>'.printweek($kw, $schuljahr + $neues_jahr).'</td>';
					
					echo '<td><input type="checkbox" name="montag" value="1"'.($anwesend->montag ? 'checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="dienstag" value="1"'.($anwesend->dienstag ? 'checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="mittwoch" value="1"'.($anwesend->mittwoch ? 'checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="donnerstag" value="1"'.($anwesend->donnerstag ? 'checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="freitag" value="1"'.($anwesend->freitag ? 'checked="checked"' : '').'></td>';
					
					echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="anwesenheit_bearbeiten">Woche speichern</button></td>';
					
					echo '</tr>';
					echo '</form>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
require_once('footer.php');
?>