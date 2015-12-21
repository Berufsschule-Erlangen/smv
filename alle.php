<?php

session_name('PHPSESSID');
if (!empty($_GET['PHPSESSID']))
	session_id($_GET['PHPSESSID']);
session_start();

require_once('classes/Database.class.php');

require_once('header.php');

$db = new Database();
$db->connect();

$rolle = intval($_SESSION['rollenid']);

if (isset($_GET['delete']) && intval($_GET['delete']) > 0 && $rolle == 1)
{
	$db->delete_user(intval($_GET['delete']));
}

$users = $db->select_all_users();

?>

<div class="container">
	<table class="table table-striped table-condensed">
		<?php
		if ($rolle == 1)
		{
			echo '<thead>
	<tr>
		<th>Name</th>
		<th>Klasse</th>
		<th>Raum</th>
		<th>E-Mail</th>
		<th>Telefon</th>
		<th>Bemerkung</th>
		<th>Löschen</th>
	</tr>
</thead>';
			
			echo '<tbody>';
			foreach ($users as $usr)
			{
				if (!$usr->deleted)
				{
					echo '<tr>
	<td>'.$usr->vorname.' '.$usr->name.'</td>
	<td>'.$usr->klasse.'</td>
	<td>'.$usr->raum.'</td>
	<td>'.$usr->email.'</td>
	<td>'.$usr->telefon.'</td>
	<td>'.$usr->vorbildung.'</td>
	<td><a href="alle.php?delete='.$usr->id.'&PHPSESSID='.$_GET['PHPSESSID'].'"><span class="glyphicon glyphicon-trash"></span></a></td>
</tr>';
				}
			}
			echo '</tbody>';
		}
		else
		{
			echo '<thead>
	<tr>
		<th>Name</th>
		<th>Klasse</th>
		<th>Raum</th>
		<th>E-Mail</th>
		<th>Bemerkung</th>
	</tr>
</thead>';
			
			echo '<tbody>';
			foreach ($users as $usr)
			{
				if (!$usr->deleted)
				{
					echo '<tr>
	<td>'.$usr->vorname.' '.$usr->name.'</td>
	<td>'.$usr->klasse.'</td>
	<td>'.$usr->raum.'</td>
	<td>'.$usr->email.'</td>
	<td>'.$usr->vorbildung.'</td>
</tr>';
				}
			}
			echo '</tbody>';
		}
		?>
	</table>
	
	<p>
		<h4>Aktueller E-Mail Verteiler</h4>
		
		<?php
		$emails = array();
		foreach ($users as $usr)
		{
			if (!$usr->deleted)
			{
				$emails[] = $usr->email;
			}
		}

		echo implode(', ', $emails);
		?>
	</p>
</div>

<?php
require_once('footer.php');
?>