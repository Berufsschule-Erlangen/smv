<?php

if($_GET){
	session_start($_GET['PHPSESSID']);
}

require_once('classes/Database.class.php');
require_once('header.php');

if ('POST' != $_SERVER['REQUEST_METHOD']){
    $akw = date('W', time());
    $ateam = "0";
} else {
    $akw = 0 + $_POST['kalenderwoche'];
    $ateam = $_POST['team'];
}
$wtag = date('w', time());
$akw2 = date('W', time());
?>

<div class="row">
  <div class="col-sm-6">
    <form class="form-inline" 
<?php 
    if (!$_GET) 
      echo 'action="index.php"';
    else 
      echo 'action="index.php?PHPSESSID='.$_GET['PHPSESSID'].'"';

?> method="post">

      <div class="form-group">
        <input type="hidden" name="team" value="<? echo $ateam; ?>">
        <select class="form-control" name="kalenderwoche" script="">
        <?
	     $db = new Database();
             $con = $db->connect();
             
             $kw_result = $db->select_kw($con);
             
             //while ($row = $kw_result->fetch_assoc()) 
             for($kw=38; $kw<=$anzahl_kw; $kw++)
             {
                 //$kw = $row['Kalenderwoche'];
                 
                 $neues_jahr=0;
          
                  if ($kw < 34) // Sommerferien
                  { 
                         $neues_jahr=1;
                  }
             
                 if ($kw == $akw) 
                 {
                    if( $kw == $akw2)
                       echo '<option selected="selected" value="'.$kw.'">KW '.$kw.' ( Heute ist der ' . date('d.m.Y') . ' ) </option>';
                     else
                       echo '<option selected="selected" value="'.$kw.'">KW '.$kw.' ('. printweek($kw,$schuljahr+$neues_jahr).') </option>';             
                 }
                 else 
                 {
                     if( $kw == $akw2 )
                        echo '<option value="'.$kw.'">KW '.$kw.' (aktuelle Woche) </option>';
                     else
                        echo '<option value="'.$kw.'">KW '.$kw.' '. '('. printweek($kw,$schuljahr+$neues_jahr).')</option>';
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
    <form class="form-inline" <?php 
    if (!$_GET) 
      echo 'action="index.php"';
    else 
      echo 'action="index.php?PHPSESSID='.$_GET['PHPSESSID'].'"';

    ?> method="post">
     <div class="form-group">
        <input type="hidden" name="kalenderwoche" value="<? echo $akw; ?>">
        <select class="form-control" name="team" script="">
        <option value="0"> Alle Teams </option>       
        
        <?
	     $db = new Database();
             $con = $db->connect();
             
             $kw_result = $db->select_all_teams($con);
         
             while ($row = $kw_result->fetch_assoc()) 
             { 
                 $tid = $row['tid'];
                 $team = $row['bezeichnung'];
                 //echo '<option value="'.$tid.'">'.$team.' </option>';
               
                 if ($ateam == $tid) 
                 {
                       echo '<option selected="selected" value="'.$tid.'">'.$team.' </option>';
                 }
                 else 
                 {
                       echo '<option value="'.$tid.'">'.$team.' </option>';
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
           <!-- <th>Handy</th>
            <th>&nbsp;</th>-->
          </tr>
        </thead>
        <tbody>
        <?php
          $db = new Database();
          $con = $db->connect();
          $duty_result = $db->select_anwesenheit($con, $akw);

          while ($row = $duty_result->fetch_assoc()) {
          
		   if ( $row['name'] == "Ebert" && $row['vorname'] == "Matthias") continue;
		   
                   echo "<tr>";
                   echo "<td>".$row['vorname']. " ". $row['name']." (". $row['Klasse'].")</td>";
                   
                   if($wtag==1 && $akw == $akw2 ) echo "<td bgcolor='lightgray'>"; else echo "<td>";
                   if ($row['dmon'] == 1 and $row['amon'] == 1) {
                      echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "&nbsp;</td>";
                   }
                   
                   if($wtag==2 && $akw == $akw2 ) echo "<td bgcolor='lightgray'>"; else echo "<td>";
                   if ($row['ddie'] == 1 and $row['adie'] == 1) {
                      echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "&nbsp;</td>";
                   }
                   
                   if($wtag==3 && $akw == $akw2 ) echo "<td bgcolor='lightgray'>"; else echo "<td>";
                   if ($row['dmit'] == 1 and $row['amit'] == 1) {
                      echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "&nbsp;</td>";
                   }
                   
                   if($wtag==4 && $akw == $akw2 ) echo "<td bgcolor='lightgray'>"; else echo "<td>";
                   if ($row['ddon'] == 1 and $row['adon'] == 1) {
                      echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "&nbsp;</td>";
                   }
                   
                   if($wtag==5 && $akw == $akw2 ) echo "<td bgcolor='lightgray'>"; else echo "<td>";
                   if ($row['dfre'] == 1 and $row['afre'] == 1) {
                      echo "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "&nbsp;</td>";
                   }
                   
/*
                   $handy_result = $db->select_handy($con);
                   $found =0;
                   while ($handy = $handy_result->fetch_assoc()) 
                   {
                      if ($row['SanitaeterID'] == $handy['SanitaeterID']) 
                      {
                         echo '<td> '.$handy['Handynummer'].'</td>';
                         $found = 1;
                      }
                   }
                   if (!$found)  echo "<td>nein</td>";
*/
/*                   
                   $user_result = $db->userdatenselect($con,$row['SanitaeterID'] );
                   $found =0;
                   while ($user = $user_result->fetch_assoc()) 
                   {
                      if ($user['Telefonnummer']) 
                      {
                         echo '<td> '.$user['Telefonnummer'].'</td>';
                         $found = 1;
                      }
                   }
                   if (!$found)  echo "<td>nein</td>";
*/                   
                   echo "</tr>";
          }
          $db->close($con);
        ?>
        </tbody>
      </table>
    </div>
</div>
<?php require_once('footer.php'); ?>