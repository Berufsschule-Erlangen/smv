<?php
if($_GET){
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

require_once('header.php');

echo "Heute ist ".date('d.m.Y')."<br>";

if ('POST' == $_SERVER['REQUEST_METHOD'])
{
    if (isset($_POST['submit']) ) 
    {
          $db = new Database();
          $con = $db->connect();
          $sid = 0 + $_POST['sid'];
          $akw2 = 0 + $_POST['akw'];
	  if($_POST['submit'] == 'komplette_KW')
	  {
		$dienst_montag = 1;
		$dienst_dienstag = 1;
		$dienst_mittwoch = 1;
		$dienst_donnerstag = 1;
		$dienst_freitag = 1;
	  }
	  else
	  {
		isset($_POST['check_'.$_POST['sid'].'_montag']) ? $dienst_montag = $_POST['check_'.$_POST['sid'].'_montag'] : $dienst_montag = 0;
		isset($_POST['check_'.$_POST['sid'].'_dienstag']) ? $dienst_dienstag = $_POST['check_'.$_POST['sid'].'_dienstag'] : $dienst_dienstag = 0;
		isset($_POST['check_'.$_POST['sid'].'_mittwoch']) ? $dienst_mittwoch = $_POST['check_'.$_POST['sid'].'_mittwoch'] : $dienst_mittwoch = 0;
		isset($_POST['check_'.$_POST['sid'].'_donnerstag']) ? $dienst_donnerstag = $_POST['check_'.$_POST['sid'].'_donnerstag'] : $dienst_donnerstag = 0;
		isset($_POST['check_'.$_POST['sid'].'_freitag']) ? $dienst_freitag = $_POST['check_'.$_POST['sid'].'_freitag'] : $dienst_freitag = 0;   
	  }
      
          $insert_result = $db->insert_anwesenheit($con, $akw2, $sid, $dienst_montag, $dienst_dienstag, $dienst_mittwoch, $dienst_donnerstag, $dienst_freitag);
          $db->close($con);
      }
}
?>

<div class="row">
  <div class="col-sm-12">
    <h1>Deine aktuelle Anwesenheit</h1>
  </div>
</div>
<b>Einzeltage ändern:</b> Klicke auf einzelne Tage <font color=green>einer</font> Woche und speichere diese Woche.<br>

<div class="row">
    <div class="col-sm-12">
      <table class="table">
        <thead>
          <tr>
            <th>KW</th>
            <th>Woche</th>
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

             $db = new Database();
             $con = $db->connect();
	     $sid = $_SESSION['userid'];                     		                   
             $akw = date('W', time()); 
             
             for($kw=$akw, $i=0; $kw <= $akw+4; $kw++, $i++) 
             {
                  $anwesend_result = $db->select_anwesend($con, $kw, $sid);

		  $anwesend = $anwesend_result->fetch_assoc();
                  echo '<form action="anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
                  echo '<input type="hidden"  name="sid" value='.$sid.'>';
                  echo '<input type="hidden"  name="akw" value='.$kw.'>';
                  echo "<tr>";	
                  
                  echo "<td>$kw</td>";
             
                  if($akw+$i<=$anzahl_kw) $neues_jahr=0; else $neues_jahr=1;
                  if($akw<35) $neues_jahr=1;
                  
                  echo "<td>" .printweek($kw,$schuljahr+$neues_jahr)."</td>";
				  
                  ($anwesend['montag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                  echo '<td><label><input type="checkbox" name="check_'.$sid.'_montag" value="1" '.$checked.' ></label></td>';

                  ($anwesend['dienstag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                  echo '<td><label><input type="checkbox" name="check_'.$sid.'_dienstag" value="1" '.$checked.' ></label></td>';

                  ($anwesend['mittwoch'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                  echo '<td><label><input type="checkbox" name="check_'.$sid.'_mittwoch" value="1" '.$checked.' ></label></td>';

                  ($anwesend['donnerstag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                  echo '<td><label><input type="checkbox" name="check_'.$sid.'_donnerstag" value="1" '.$checked.' ></label></td>';

                  ($anwesend['freitag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                  echo '<td><label><input type="checkbox" name="check_'.$sid.'_freitag" value="1" '.$checked.' ></label></td>';               
	  
		  echo '<td>
			<button type="submit" class="btn btn-success btn-sm" name="submit" value="anwesenheit_bearbeiten">Woche speichern</button>
		  </td>';
	  
		  echo '</tr>';
	  
	 
                  echo '</form>';
          }
?>
</tbody>
</table>
</div>
</div>

<?php require_once('footer.php'); ?>