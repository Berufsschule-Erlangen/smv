<?php
if($_GET) session_start($_GET['PHPSESSID']);


require_once('classes/Database.class.php');

require_once('header.php');

$akw = date('W', time());
if ('POST' == $_SERVER['REQUEST_METHOD']){

    if ($_POST['submit'] == 'handy_bearbeiten') {
      $db = new Database();
      $con = $db->connect();
      $sid = $_POST['sid'];
      $handy = $_POST['handy'];
      $insert_result = $db->insert_handy($con,
                                         $sid,
                                         $handy);
      $db->close($con);
    }
	else if ($_POST['submit'] == 'handy_austragen') {
		$db = new Database();
		$con = $db->connect();
		$sid = $_POST['sid'];
		$db->handy_austragen($con, $sid);
		$db->close($con);	
	}
}
 
?>

<div class="row">
  <div class="col-sm-12">
    <h1>Diensthandy eintragen <small>Kalenderwoche: <?php echo $akw ?></small></h1>
  </div>
</div>

<div class="row">
    <div class="col-sm-12">
      <table class="table">
        <thead>
          <tr>
            <th>Sanitäter</th>
            <th>Handy</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        <?php
		  $db = new Database();
          $con = $db->connect();
          $anwesend_result = $db->select_anwesend($con, $akw, 's.sanitaeterid');
          while ($anwesend = $anwesend_result->fetch_assoc()) {
                  $dienst_result = $db->select_dienst_by_id($con, $akw, $anwesend['sid']);
                  $dienst = $dienst_result->fetch_assoc();
                  $handy_result = $db->select_handy($con);
                  echo '<form action="handy.php?PHPSESSID='.$_GET['PHPSESSID'].'" method="post">';
                  echo '<input type="hidden"  name="sid" value='.$anwesend['sid'].'>';
                  echo '<tr>';
                  echo "<td>".$anwesend['vorname']. " ". $anwesend['name']." (". $anwesend['Klasse'].")</td>";
                  echo '<td>';
                  echo '<select class="form-control" name="handy">';
				  echo '<option value=" 0 "> Kein Handy  </option>';
                  while ($handy = $handy_result->fetch_assoc()) {
                    if ($anwesend['sid'] == $handy['SanitaeterID']) {
                      echo '<option selected="selected" value="'.$handy['HandyID'].'">Handy '.$handy['HandyID'].': '.$handy['Handynummer'].'</option>';
                    }else{
						echo '<option value="'.$handy['HandyID'].'">Handy '.$handy['HandyID'].': '.$handy['Handynummer'].'</option>';
					}
					
                  }
                  echo '</select>';
                  echo '</td>';
                  echo '<td><button type="submit" class="btn btn-success btn-sm" name="submit" value="handy_bearbeiten">Handy speichern</button>
				  <button type="submit" class="btn btn-success btn-sm" name="submit" value="handy_austragen">Handy austragen</button></td>';
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