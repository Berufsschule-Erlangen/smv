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

    if (isset($_POST['submit']) ) {
      $db = new Database();
      $con = $db->connectUser();
      $sid = $_POST['sid'];
      $akw = $_POST['akw'];
	  if($_POST['submit'] == 'komplette_KW'){
		$dienst_montag = 1;
		$dienst_dienstag = 1;
        $dienst_mittwoch = 1;
        $dienst_donnerstag = 1;
        $dienst_freitag = 1;
	  }
	  else{
		isset($_POST['check_'.$_POST['sid'].'_montag']) ? $dienst_montag = $_POST['check_'.$_POST['sid'].'_montag'] : $dienst_montag = 0;
		isset($_POST['check_'.$_POST['sid'].'_dienstag']) ? $dienst_dienstag = $_POST['check_'.$_POST['sid'].'_dienstag'] : $dienst_dienstag = 0;
		isset($_POST['check_'.$_POST['sid'].'_mittwoch']) ? $dienst_mittwoch = $_POST['check_'.$_POST['sid'].'_mittwoch'] : $dienst_mittwoch = 0;
		isset($_POST['check_'.$_POST['sid'].'_donnerstag']) ? $dienst_donnerstag = $_POST['check_'.$_POST['sid'].'_donnerstag'] : $dienst_donnerstag = 0;
		isset($_POST['check_'.$_POST['sid'].'_freitag']) ? $dienst_freitag = $_POST['check_'.$_POST['sid'].'_freitag'] : $dienst_freitag = 0; 
		  
	  }
      

      $insert_result = $db->insert_anwesenheit($con,
                                               $akw, 
                                               $sid,
                                               $dienst_montag,
                                               $dienst_dienstag,
                                               $dienst_mittwoch,
                                               $dienst_donnerstag,
                                               $dienst_freitag);
      $db->close($con);
      }
	  else if (isset($_POST['tagesunterricht']) ){
		  switch($_POST['tagesunterricht']){
			case 0:
				
			  
		  }
		  
	  }
}
?>

<div class="row">
  <div class="col-sm-12">
    <h1>Anwesenheit bearbeiten <small>Kalenderwoche: <?php echo $akw ?></small></h1>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <form class="form-inline" <?php echo 'action="anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'"' ?> method="post">

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

            for($count = 1; $count < 53; $count++)
            {
              if ($count == $akw) {
                echo '<option selected="selected" value="'.$akw.'">Kalenderwoche '.$akw.'</option>';
              } else {
                echo '<option value="'.$count.'">Kalenderwoche '.$count.'</option>';
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
            <!--<th>Sanitäter</th>-->
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
          $db = new Database();
          $con = $db->connectUser();
          
          $sid = $_SESSION['userid'];
          $anwesend_result = $db->select_anwesend($con, $akw, $sid);

          $anwesend = $anwesend_result->fetch_assoc();
          //while () {
                  
                  echo '<form action="anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
                  echo '<input type="hidden"  name="sid" value='.$sid.'>';
                  echo '<input type="hidden"  name="akw" value='.$akw.'>';
                  echo "<tr>";			  
				  
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
				  
				  echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="komplette_KW">Ganze Kalenderwoche</button>
							<button type="submit" class="btn btn-success btn-sm" name="submit" value="anwesenheit_bearbeiten">Anwesenheit speichern</button>
						</td>';
				  
				  echo '</tr>';
				  
				  echo '<td><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="0">Montagsunterricht</button></td>';
				  echo '<td><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="1">Dienstagsunterricht</button></td>';
				  echo '<td><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="2">Mittwochsunterricht</button></td>';
				  echo '<td><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="3">Donnerstagsunterricht</button></td>';
				  echo '<td><button type="submit" class="btn btn-info btn-sm" name="tagesunterricht" value="4">Freitagsunterricht</button></td>';
				 
				  echo "<tr>";
				  
				  
				  echo '</tr>';
				  
				  
				  
				  
                  echo '</form>';
                  
          //}
          //$kalenderwoche[] = $akw
			$db->close($con);
        ?>
        </tbody>
      </table>     
    </div>
</div>
<?php require_once('footer.php'); ?>