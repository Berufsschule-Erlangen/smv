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
    isset($_POST['kalenderwoche']) ? $akw = $_POST['kalenderwoche'] : $akw = date('W', time());

    if (isset($_POST['submit']) == 'dienstplan_bearbeiten') {
      $db = new Database();
      $con = $db->connectUser();
      $sid = $_POST['sid'];
      isset($_POST['check_'.$_POST['sid'].'_montag']) ? $dienst_montag = $_POST['check_'.$_POST['sid'].'_montag'] : $dienst_montag = 0;
      isset($_POST['check_'.$_POST['sid'].'_dienstag']) ? $dienst_dienstag = $_POST['check_'.$_POST['sid'].'_dienstag'] : $dienst_dienstag = 0;
      isset($_POST['check_'.$_POST['sid'].'_mittwoch']) ? $dienst_mittwoch = $_POST['check_'.$_POST['sid'].'_mittwoch'] : $dienst_mittwoch = 0;
      isset($_POST['check_'.$_POST['sid'].'_donnerstag']) ? $dienst_donnerstag = $_POST['check_'.$_POST['sid'].'_donnerstag'] : $dienst_donnerstag = 0;
      isset($_POST['check_'.$_POST['sid'].'_freitag']) ? $dienst_freitag = $_POST['check_'.$_POST['sid'].'_freitag'] : $dienst_freitag = 0;

      $insert_result = $db->insert_dienstplan($con,
                                              $akw,
                                              $sid,
                                              $dienst_montag,
                                              $dienst_dienstag,
                                              $dienst_mittwoch,
                                              $dienst_donnerstag,
                                              $dienst_freitag);
      $db->close($con);
      }
}
?>

<div class="row">
  <div class="col-sm-12">
    <h1>Dienstplan bearbeiten <small>Kalenderwoche: <?php echo $akw ?></small></h1>
  </div>
	<div class="col-sm-12">
	  <p>Hier können Sie, abhängig von der Anwesenheit der Sanitäter, den Dienst für diese festlegen.
		Es werden nur Kalenderwochen angezeigt für die mindestens ein Sanitäter anwesend ist.</p>
	</div>
</div>
<div class="row">
  <div class="col-sm-6">
    <form class="form-inline" <?php echo 'action="dienstplan.php?PHPSESSID='.$_GET['PHPSESSID'].'"' ?> method="post">

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
          ?>
        </select>
      </div>

      <button type="submit" name="formaction" class="btn btn-default">Auswählen</button>
    </form>
  </div>
</div>
<div class="row">
    <div class="col-sm-12">
      <table class="table">
        <thead>
          <tr>
            <th>Sanitäter</th>
            <th>Montag</th>
            <th>Dienstag</th>
            <th>Mittwoch</th>
            <th>Donnerstag</th>
            <th>Freitag</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        <?php
          $anwesend_result = $db->select_anwesend($con, $akw, 's.sanitaeterid');

          while ($anwesend = $anwesend_result->fetch_assoc()) {
                  $dienst_result = $db->select_dienst_by_id($con, $akw, $anwesend['sid']);
                  $dienst = $dienst_result->fetch_assoc();

                  echo '<form action="dienstplan.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
                  echo '<input type="hidden"  name="sid" value='.$anwesend['sid'].'>';
									echo '<input type="hidden"  name="kalenderwoche" value='.$akw.'>';
                  echo "<tr>";
                  echo "<td>".$anwesend['vorname']." ".$anwesend['name']."</td>";
                  if ($anwesend['montag'] == 1) {
                    ($dienst['montag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                    echo '<td><label><input type="checkbox" name="check_'.$anwesend['sid'].'_montag" value="1" '.$checked.' ></label></td>';
                  } else {
                    echo '<td class="danger"></td>';
                  }
                  if ($anwesend['dienstag'] == 1) {
                     ($dienst['dienstag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                     echo '<td><label><input type="checkbox" name="check_'.$anwesend['sid'].'_dienstag" value="1" '.$checked.' ></label></td>';
                   } else {
                     echo '<td class="danger"></td>';
                  }
                  if ($anwesend['mittwoch'] == 1) {
                    ($dienst['mittwoch'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                     echo '<td><label><input type="checkbox" name="check_'.$anwesend['sid'].'_mittwoch" value="1" '.$checked.' ></label></td>';
                   } else {
                     echo '<td class="danger"></td>';
                  }
                  if ($anwesend['donnerstag'] == 1) {
                    ($dienst['donnerstag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                     echo '<td><label><input type="checkbox" name="check_'.$anwesend['sid'].'_donnerstag" value="1" '.$checked.' ></label></td>';
                   } else {
                     echo '<td class="danger"></td>';
                  }
                  if ($anwesend['freitag'] == 1) {
                    ($dienst['freitag'] == 1) ? ($checked = 'checked="checked"') : ($checked = '');
                     echo '<td><label><input type="checkbox" name="check_'.$anwesend['sid'].'_freitag" value="1" '.$checked.' ></label></td>';
                   } else {
                     echo '<td class="danger"></td>';
                  }
                     echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="dienstplan_bearbeiten">Dienstplan speichern</button></td>';
                  echo '</tr>';
                  echo '</form>';

          }
          //$kalenderwoche[] = $akw
			$db->close($con);
        ?>
        </tbody>
      </table>
      <!--<button type="submit" class="btn btn-success" name="submit" value="dienstplan_bearbeiten">Dienstplan speichern</button>-->

    </div>
</div>
<?php require_once('footer.php'); ?>
