<?php

session_name('PHPSESSID');
if (!empty($_GET['PHPSESSID']))
	session_id($_GET['PHPSESSID']);
session_start();

require_once __DIR__.'/classes/Database.class.php';
require_once __DIR__.'/header.php';

$akw   = empty($_POST['kalenderwoche']) ? date('W', time()) : intval($_POST['kalenderwoche']);
$ateam = empty($_POST['team'])          ? 0                 : intval($_POST['team']);

$wtag = date('w', time());
$akw2 = date('W', time());

?>

<div class="row">
	<div class="col-sm-6">
		<form class="form-inline" action="index.php<?=(empty($_GET['PHPSESSID']) ? '' : '?PHPSESSID='.$_GET['PHPSESSID'])?>" method="post">
			<div class="form-group">
				<input type="hidden" name="team" value="<?=$ateam?>">
				<select class="form-control" name="kalenderwoche">
					<?php
					$db = new Database();
					$con = $db->connect();
					
					$kw_result = $db->select_kw();
					
					for ($kw = $erste_kw, $kw <= $anzahl_kw; $kw++)
					{
						$neues_jahr = $kw < $letzte_kw ? 1 : 0;
						
						if ($kw == $akw)
						{
							if ($kw == $akw2)
							{
								echo '<option selected="selected" value="'.$kw.'">KW '.$kw.' (Heute ist der '.date('d.m.Y').')</option>';
							}
							else
							{
								echo '<option selected="selected" value="'.$kw.'">KW '.$kw.' ('.printweek($kw, $schuljahr + $neues_jahr).')</option>';
							}
						}
						else
						{
							if ($kw == $akw2)
							{
								echo '<option value="'.$kw.'">KW '.$kw.' (aktuelle Woche)</option>';
							}
							else
							{
								echo '<option value="'.$kw.'">KW '.$kw.' ('.printweek($kw, $schuljahr + $neues_jahr).')</option>';
							}
						}
					}
					?>
				</select>
				<button type="submit" name="formaction" value="kw" class="btn btn-default">Auswählen</button>
				<? //echo ' Heute ist der ' . date('d.m.Y'); ?>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<form class="form-inline" action="index.php<?=(empty($_GET['PHPSESSID']) ? '' : '?PHPSESSID='.$_GET['PHPSESSID'])?>" method="post">
			<div class="form-group">
				<input type="hidden" name="kalenderwoche" value="<?=$akw?>">
				<select class="form-control" name="team">
					<option value="0">Alle Teams</option>
					<?php
					$db = new Database();
					$con = $db->connect();
					
					$teams = $db->select_all_teams();
					foreach ($teams as $team)
					{
						if ($ateam == $team->id)
						{
							echo '<option selected="selected" value="'.$team->id.'">'.$team->bezeichnung.'</option>';
						}
						else
						{
							echo '<option value="'.$team->id.'">'.$team->bezeichnung.'</option>';
						}
					}
					?>
				</select>
				<button type="submit" name="formaction2" value="team" class="btn btn-default">Auswählen</button>
				<font color=red size=1> Teams funktionieren noch nicht ... ich arbeite dran :)</font>
				<? //echo ' Heute ist der ' . date('d.m.Y'); ?>
			</div>
		</form>
	</div>
</div>

<!-- Dienstplan -->
<div class="row">
	<div class="col-md-12">
		<table class="table table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th>Diese Woche sind da:</th>
					<th <? echo ($wtag==1 && $akw == $akw2 )?"bgcolor='lightgray'":""; ?> >Mo</th>
					<th <? echo ($wtag==2 && $akw == $akw2 )?"bgcolor='lightgray'":""; ?> >Di</th>
					<th <? echo ($wtag==3 && $akw == $akw2 )?"bgcolor='lightgray'":""; ?> >Mi</th>
					<th <? echo ($wtag==4 && $akw == $akw2 )?"bgcolor='lightgray'":""; ?> >Do</th>
					<th <? echo ($wtag==5 && $akw == $akw2 )?"bgcolor='lightgray'":""; ?> >Fr</th>
					<!--th>Handy</th-->
					<!--th>&nbsp;</th-->
				</tr>
			</thead>
			<tbody>
				<?php
				$db = new Database();
				$con = $db->connect();
				$att_result = $db->select_anwesenheit($akw);
				
				foreach ($att_result as $res)
				{
					if ($res->name == 'Ebert' && $res->vorname == 'Matthias')
						continue;
					
					echo '<tr>';
					echo '<td>'.$res->vorname.' '.$res->name.' ('.$res->klasse.')</td>';
					
					echo '<td'.(($wtag == 1 && $akw == $akw2) ? ' bgcolor="lightgray"' : '').'>';
					if ($res->amon)
					{
						echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
					}
					else
					{
						echo "&nbsp;</td>";
					}
					
					echo '<td'.(($wtag == 2 && $akw == $akw2) ? ' bgcolor="lightgray"' : '').'>';
					if ($res->adie)
					{
						echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
					}
					else
					{
						echo "&nbsp;</td>";
					}
					
					echo '<td'.(($wtag == 3 && $akw == $akw2) ? ' bgcolor="lightgray"' : '').'>';
					if ($res->amit)
					{
						echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
					}
					else
					{
						echo "&nbsp;</td>";
					}
					
					echo '<td'.(($wtag == 4 && $akw == $akw2) ? ' bgcolor="lightgray"' : '').'>';
					if ($res->adon)
					{
						echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
					}
					else
					{
						echo "&nbsp;</td>";
					}
					
					echo '<td'.(($wtag == 5 && $akw == $akw2) ? ' bgcolor="lightgray"' : '').'>';
					if ($res->afre)
					{
						echo '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span></td>';
					}
					else
					{
						echo "&nbsp;</td>";
					}
					
					/*
					$handy_result = $db->select_handy();
					$found = false;
					
					foreach ($handy_result as $handy)
					{
						if ($res->id == $handy->user->id)
						{
							echo '<td>'.$handy->nummer.'</td>';
							$found = true;
						}
					}
					if (!$found)
						echo '<td> - </td>';
					
					$user_result = $db->select_user($res->id);
					$found = false;
					
					foreach ($user_result as $user)
					{
						if (!empty($user->telefon))
						{
							echo '<td>'.$user->telefon.'</td>';
							$found = true;
						}
					}
					if (!$found)
						echo '<td> - </td>';
					*/
					
					echo '</tr>';
				}
				
				?>
			</tbody>
		</table>
	</div>
</div>

<?php
require_once __DIR__.'/footer.php';
?>