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
	$akw = empty($_POST['akw']) ? date('W', time()) : intval($_POST['akw']);
	
	if (!empty($_POST['submit']))
	{
		if ($_POST['submit'] == 'komplette_KW')
		{
			$montag     = 1;
			$dienstag   = 1;
			$mittwoch   = 1;
			$donnerstag = 1;
			$freitag    = 1;
		}
		else
		{
			$montag     = (isset($_POST['montag'])     && $_POST['montag'] == 1)     ? 1 : 0;
			$dienstag   = (isset($_POST['dienstag'])   && $_POST['dienstag'] == 1)   ? 1 : 0;
			$mittwoch   = (isset($_POST['mittwoch'])   && $_POST['mittwoch'] == 1)   ? 1 : 0;
			$donnerstag = (isset($_POST['donnerstag']) && $_POST['donnerstag'] == 1) ? 1 : 0;
			$freitag    = (isset($_POST['freitag'])    && $_POST['freitag'] == 1)    ? 1 : 0;
		}
		
		$db->insert_anwesenheit($akw, $userid, $montag, $dienstag, $mittwoch, $donnerstag, $freitag);
	}
	
	if (isset($_POST['tagesunterricht']))
	{
		$neues_jahr = 0;
		for ($kw = $erste_kw; $kw <= number_of_weeks($schuljahr) + 1; $kw++)
		{
			if ($kw == number_of_weeks($schuljahr) + 1)
			{
				$kw = 1;
				$neues_jahr = 1;
			}
			
			if ($neues_jahr == 1 && $kw == ($letzte_kw + 1))
				break;
			
			$ferien = false;
			for ($i = 0; $freie_kw[$i]; $i++)
			{
				if ($kw == $freie_kw[$i])
					$ferien = true;
			}
			if ($ferien)
				continue;
			
			$montag     = 0;
			$dienstag   = 0;
			$mittwoch   = 0;
			$donnerstag = 0;
			$freitag    = 0;
			
			if ($db->exists_kw($userid, $kw))
			{
				$anwesenheit = $db->select_anwesend($kw, $userid);
				$anwesend = $anwesenheit[0];
				
				$montag     = $anwesend->montag     ? 1 : 0;
				$dienstag   = $anwesend->dienstag   ? 1 : 0;
				$mittwoch   = $anwesend->mittwoch   ? 1 : 0;
				$donnerstag = $anwesend->donnerstag ? 1 : 0;
				$freitag    = $anwesend->freitag    ? 1 : 0;
			}
			
			switch (intval($_POST['tagesunterricht']))
			{
				case 1: $montag     = 1; break;
				case 2: $dienstag   = 1; break;
				case 3: $mittwoch   = 1; break;
				case 4: $donnerstag = 1; break;
				case 5: $freitag    = 1; break;
				default:
					$montag = $dienstag = $mittwoch = $donnerstag = $freitag = 0;
					break;
			}
			
			$db->insert_anwesenheit($kw, $userid, $montag, $dienstag, $mittwoch, $donnerstag, $freitag);
		}
	}
}
else
{
	$akw = date('W', time());
}
?>

<div class="row">
	<div class="col-sm-12">
		<h2>Deine Anwesenheit</h2>
		
		<p>
			<h4>Heute ist der <?=date('d.m.Y')?></h4>
		
			<span style="color: #0f0;">Bitte trage hier deine Anwesenheitszeiten an der Berufsschule für das komplette Schuljahr ein!</span>
			<br>
			<strong>Blockunterricht:</strong> Klicke auf eine grüne Schulwochen-Nr., um die Anwesenheit in einer Blockwoche zu markieren.
			<br>
			<strong>Tagesunterricht:</strong> Klicke auf einen blauen Wochentag Mo-Fr, um einen Wochentag über das ganze Schuljahr zu markieren.
			<br>
			<strong>Vollzeitunterricht:</strong> Klicke auf alle blauen Wochentage nacheinander, um alle Schultage als anwesend zu markieren.
			<br>
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
					<th><? echo $schuljahr."/".($schuljahr+1); ?></th>
					<?php
					echo '<form action="jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
					echo '<th><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="1">Mo</button></th>';
					echo '<th><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="2">Di</button></th>';
					echo '<th><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="3">Mi</button></th>';
					echo '<th><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="4">Do</button></th>';
					echo '<th><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="5">Fr</button></th>';
					echo '<th><button type="submit" class="btn btn-sm" name="tagesunterricht" value="0">Alle Einträge löschen!</button></th>';
					echo '</form>';
					?>
				</tr>
			</thead>
			
			<tbody>
				<?php
				$neues_jahr=0;
				$sw=0;
				for($kw=$erste_kw; $kw <= number_of_weeks($schuljahr) + 1; $kw++)
				{
					if ($kw == number_of_weeks($schuljahr) + 1)
					{
						$kw = 1;
						$neues_jahr = 1;
					}
					
					if ($neues_jahr == 1 && $kw == ($letzte_kw + 1))
						break;
					
					$ferien = false;
					for ($i = 0; $ferien_kw[$i]; $i++)
					{
						if($kw == $ferien_kw[$i])
							$ferien = true;
					}
					
					if(!$ferien)
						$sw++;
					
					$anwesenheit = $db->select_anwesend($kw, $userid);
					$anwesend = $anwesenheit[0];
					
					echo '<form action="jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
					echo '<input type="hidden" name="akw" value="'.$kw.'">';
					echo '<tr>';
					
					echo '<td>'.$kw.'</td>';
					echo '<td>'.printweek($kw, $schuljahr + $neues_jahr).'</td>';
					
					if ($ferien)
					{
						echo '<td>Ferien</td>';
					}
					else
					{
						echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="komplette_KW">'.$sw.'</button></td>';
					}
					
					echo '<td><input type="checkbox" name="montag" value="1"'.($anwesend->montag ? ' checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="dienstag" value="1"'.($anwesend->dienstag ? ' checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="mittwoch" value="1"'.($anwesend->mittwoch ? ' checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="donnerstag" value="1"'.($anwesend->donnerstag ? ' checked="checked"' : '').'></td>';
					echo '<td><input type="checkbox" name="freitag" value="1"'.($anwesend->freitag ? ' checked="checked"' : '').'></td>';
					
					echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="anwesenheit_bearbeiten">Woche speichern</button></td>';
					echo '</tr>';
					echo '</form>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<?php require_once('footer.php'); ?>