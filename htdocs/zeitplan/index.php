<?php

if(!$_GET){
	
}else{
	session_start($_GET['PHPSESSID']);
}
require_once('classes/Database.class.php');

require_once('header.php');

if ('POST' != $_SERVER['REQUEST_METHOD']){
    $akw = date('W', time());
} else {
    $akw = $_POST['kalenderwoche'];
}


?>

<div class="row">
  <div class="col-sm-12">
    <h1>Dienstplan <small>Kalenderwoche: <?php echo $akw ?></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <form class="form-inline" <?php 
    if (!$_GET) {
      echo 'action="index.php"';
    } else {
      echo 'action="index.php?PHPSESSID='.$_GET['PHPSESSID'].'"';
    }
    ?> method="post">

      <div class="form-group">
        <select class="form-control" name="kalenderwoche">
          <?php
             $db = new Database();
             $con = $db->connectUser();

            $kw_result = $db->select_kw($con);
            while ($kw = $kw_result->fetch_assoc()) {
              if ($akw == $kw['Kalenderwoche']) {
                echo '<option selected="selected" value="'.$kw['Kalenderwoche'].'">Kalenderwoche '.$kw['Kalenderwoche'].'</option>';
              } else {
                echo '<option value="'.$kw['Kalenderwoche'].'">Kalenderwoche '.$kw['Kalenderwoche'].'</option>';
              }
            }
            $db->close($con);
          ?>
        </select>
      </div>

      <button type="submit" name="formaction" value="kw" class="btn btn-default">Auswählen</button>
    </form>
  </div>
</div>

<!-- Dienstplan -->
<div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>Sanitäter</th>
            <th>Montag</th>
            <th>Dienstag</th>
            <th>Mittwoch</th>
            <th>Donnerstag</th>
            <th>Freitag</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $db = new Database();
          $con = $db->connectUser();
          $duty_result = $db->select_dienst($con, $akw);

          while ($row = $duty_result->fetch_assoc()) {
                   echo "<tr>";
                   echo "<td>".$row['vorname']. " ". $row['name']."</td>";
                   if ($row['dmon'] == 1 and $row['amon'] == 1) {
                      echo "<td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "<td></td>";
                   }
                   if ($row['ddie'] == 1 and $row['adie'] == 1) {
                      echo "<td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "<td></td>";
                   }
                   if ($row['dmit'] == 1 and $row['amit'] == 1) {
                      echo "<td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "<td></td>";
                   }
                   if ($row['ddon'] == 1 and $row['adon'] == 1) {
                      echo "<td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "<td></td>";
                   }
                   if ($row['dfre'] == 1 and $row['afre'] == 1) {
                      echo "<td><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></td>";
                   } else {
                      echo "<td></td>";
                   }
                   echo "</tr>";
          }
          $db->close($con);
        ?>
        </tbody>
      </table>
    </div>
</div>
<?php require_once('footer.php'); ?>