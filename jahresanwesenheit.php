<?php
if($_GET)  session_start($_GET['PHPSESSID']);

require_once('classes/Database.class.php');

require_once('header.php');

echo "Heute ist ".date('d.m.Y')."<br>";



if ('POST' != $_SERVER['REQUEST_METHOD'])
{
    $akw = date('W', time());
} 
else 
{
    isset($_POST['kalenderwoche']) ? $akw = $_POST['kalenderwoche'] : $akw = date('W', time());

    if (isset($_POST['submit']) ) 
    {
          $db = new Database();
          $con = $db->connect();
          $sid = $_POST['sid'];
          $akw = $_POST['akw'];
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
      
          $insert_result = $db->insert_anwesenheit($con, $akw, $sid, $dienst_montag, $dienst_dienstag, $dienst_mittwoch, $dienst_donnerstag, $dienst_freitag);
          $db->close($con);
      }
      
      if (isset($_POST['tagesunterricht']) )
      {
      
      //  echo $_POST['tagesunterricht'];
        
          $db = new Database();
          $con = $db->connect();
	  $sid = $_SESSION['userid'];           
          
          $neues_jahr=0;
          
          for($kw=$erstekw; $kw <=53; $kw++) 
          {
		  //echo " $kw";
                  if ($kw==$anzahl_kw) // normalerweise hat das jahr 52 Wochen, manchmal 53
                  { 
                         $kw=1; 
                         $neues_jahr=1;
                  }
                  
                  if ($neues_jahr==1 && $kw == ($letztekw+1))
                  {
                         break;
                  }
                  
                  $freie_woche=false;
                  for( $i=0; $freie_kw[$i]; $i++) 
                  {
                     if( $kw == $freie_kw[$i]) 
                     {
                        $freie_woche = true;
                     }
                  }
                  //echo " Freie Woche:$freie_woche ";
                  if ($freie_woche) continue;
                                 
                  $dienst_montag = 0;
                  $dienst_dienstag = 0;
                  $dienst_mittwoch = 0;
                  $dienst_donnerstag = 0;
                  $dienst_freitag = 0;
                  //echo " $sid $kw "; 
                  
                  // Lese aktuelle Werte aus der Kalenderwoche
                  if ( $db->existsKW($con, $sid, $kw) )
                  {
                    //echo ".";
                    $anwesend_result = $db->select_anwesend($con, $kw, $sid);
		    $anwesend = $anwesend_result->fetch_assoc();
                                  
                    $dienst_montag = $anwesend['montag'];
		    $dienst_dienstag = $anwesend['dienstag'];
		    $dienst_mittwoch = $anwesend['mittwoch'];
		    $dienst_donnerstag = $anwesend['donnerstag'];
		    $dienst_freitag = $anwesend['freitag'];
		  }
		  //echo " $kw ";
		  switch(  $_POST['tagesunterricht'] )
		  {
		       case 0; $dienst_montag = 0; $dienst_dienstag = 0; $dienst_mittwoch = 0; $dienst_donnerstag = 0; $dienst_freitag = 0; break;
		       case 1: $dienst_montag = 1; break;
		       case 2: $dienst_dienstag = 1; break;
		       case 3: $dienst_mittwoch = 1; break;
		       case 4: $dienst_donnerstag = 1; break;
		       case 5: $dienst_freitag = 1; break; 
		  }
		  
		  //echo "  $dienst_montag;$dienst_dienstag;$dienst_mittwoch;$dienst_donnerstag;$dienst_freitag;";
                  $insert_result = $db->insert_anwesenheit($con, $kw, $sid, $dienst_montag, $dienst_dienstag, $dienst_mittwoch, $dienst_donnerstag, $dienst_freitag);
		  //echo "<br>";
           }
           $db->close($con);
      }
}
?>

<!-- Ausgabe -->

<div class="row">
  <div class="col-sm-12">
    <h1>Deine Anwesenheit</h1>
  </div>
</div>
<font color=green>Bitte trage hier deine Anwesenheitszeiten an der Berufsschule für das komplette Schuljahr ein!</font><br>
<b>Blockunterricht:</b> Klicke auf eine grüne Schulwochen-Nr., um die Anwesenheit in einer Blockwoche zu markieren.<br>
<b>Tagesunterricht:</b> Klicke auf einen blauen Wochentag Mo-Fr, um einen Wochentag über das ganze Schuljahr zu markieren.<br>
<b>Vollzeitunterricht:</b> Klicke auf alle blauen Wochentage nacheinander, um alle Schultage als anwesend zu markieren.<br>
<b>Einzeltage ändern:</b> Klicke auf einzelne Tage <font color=green>einer</font> Woche und speichere diese Woche.<br>
<!--<font color=red>Diese Eintragungen sind die Basis für den Dienstplan!</font>-->
<div class="row">
  <!--<div class="col-sm-6">
    <form class="form-inline" <?php echo 'action="jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'"' ?> method="post">

      <div class="form-group">
        <select class="form-control" name="kalenderwoche">
          <?php
             //$db = new Database();
             //$con = $db->connect();

            //$kw_result = $db->select_kw($con);
            //while ($kw = $kw_result->fetch_assoc()) {
            //  if ($akw == $kw['Kalenderwoche']) {
            //    echo '<option selected="selected" value="'.$kw['Kalenderwoche'].'">Kalenderwoche '.$kw['Kalenderwoche'].'</option>';
            //  } else {
            //    echo '<option value="'.$kw['Kalenderwoche'].'">Kalenderwoche '.$kw['Kalenderwoche'].'</option>';
            //  }
            //}

            //for($count = 1; $count < 53; $count++)
            //{
            //   if ($count == $akw) {
            //    echo '<option selected="selected" value="'.$akw.'">Kalenderwoche '.$akw.'</option>';
            //   } else {
            //     echo '<option value="'.$count.'">Kalenderwoche '.$count.'</option>';
            //  }
            //}
          ?>
        </select>
      </div>

      <button type="submit" name="formaction" class="btn btn-default">Auswählen</button>
    </form>
  </div>
</div>
-->


<div class="row">
    <div class="col-sm-12">
      <table class="table">
        <thead>
          <tr>
            <th>KW</th>
            <th>Schulwoche</th>
            <th><? echo $schuljahr."/".($schuljahr+1); ?></th>
            <?
        echo '<form action="jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
        echo '<input type="hidden"  name="sid" value='.$sid.'>';
                  
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
            
          $db = new Database();
          $con = $db->connect();
	  $sid = $_SESSION['userid'];           
          
          $neues_jahr=0;
          $sw=0;
          for($kw=$erstekw; $kw <=54; $kw++) 
          {
          
                  if ($kw==$anzahl_kw+1) { 
                         $kw=1; 
                         $neues_jahr=1;
                  }
                  if ($neues_jahr==1 && $kw == ($letztekw+1)) 
                         break;
                  
                  
                  $ferien=false;
                  
                  for( $i=0; $ferien_kw[$i]; $i++) {
                    if( $kw == $ferien_kw[$i]) {
                        $ferien = true;
                    }
                  }
                  
                  if(!$ferien) $sw++;
                  
		  $anwesend_result = $db->select_anwesend($con, $kw, $sid);

		  $anwesend = $anwesend_result->fetch_assoc();
                  
                  echo '<form action="jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
                  echo '<input type="hidden"  name="sid" value='.$sid.'>';
                  echo '<input type="hidden"  name="akw" value='.$kw.'>';
                  echo "<tr>";	
                  echo "<td>$kw</td>";
                  echo "<td>" .printweek($kw,$schuljahr+$neues_jahr)."</td>";
                  if(!$ferien)
                     echo "<td><button type='submit' class='btn btn-success btn-sm' name='submit' value='komplette_KW'>$sw</button></td>";
		  else
		     echo "<td>Ferien</td>";
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
          $db->close($con);
				 
				  
        ?>
        </tbody>
      </table>     
    </div>
</div>
<?php require_once('footer.php'); ?>