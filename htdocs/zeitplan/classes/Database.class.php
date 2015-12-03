<?php
class Database
{
  private $host = 'localhost';
  private $database = 'schulsanidienst';

  public function connect($user = 'root', $password = '') {
    $db = @new mysqli($this->host, $user, $password, $this->database);
    if (mysqli_connect_errno()) {
      die ('Konnte keine Verbindung zur Datenbank aufbauen: '.mysqli_connect_error().'('.mysqli_connect_errno().')');
    } else {
    	//echo "Passcht!";
    	return $db;
    }
  }

  public function close($connected_db) {
    mysqli_close($connected_db);
    //echo "Verbindung geschlossen";
    return;
  }

  public function select_dienst($connected_db, $kw) {

    $sql = "select s.name, s.vorname, d.montag dmon, d.dienstag ddie, d.mittwoch dmit, d.donnerstag ddon, d.freitag dfre,
                                      a.montag amon, a.dienstag adie, a.mittwoch amit, a.donnerstag adon, a.freitag afre
            from anwesenheit a, dienst d, user s
            where a.sanitaeterid = s.sanitaeterid
            and   d.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw."
            and   d.kalenderwoche = a.kalenderwoche";
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }

  public function select_kw($connected_db) {
    $kw = date('W', time());

    $sql = "select distinct Kalenderwoche
            from anwesenheit";
            //--where Kalenderwoche >= ".$kw;
   // echo $sql;

    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }

  public function select_anwesend($connected_db, $kw, $sid) {

     $sql = "select s.sanitaeterid sid, s.name, s.vorname, a.montag, a.dienstag, a.mittwoch, a.donnerstag, a.freitag
            from anwesenheit a, user s
            where s.sanitaeterid = ".$sid."
            and   a.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw;

     $result = $connected_db->query($sql);
     if (!$result) {
       die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
     }
     //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
     return $result;
  }

  public function select_dienst_by_id($connected_db, $kw, $sid) {

    $sql = "SELECT s.name, s.vorname, d.montag, d.dienstag, d.mittwoch, d.donnerstag, d.freitag
            FROM dienst d, user s
            WHERE s.sanitaeterid = ".$sid."
            and   d.sanitaeterid = s.sanitaeterid
            and   d.kalenderwoche = ".$kw;
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }



  public function insert_dienstplan($connected_db,
                                    $akw,
                                    $sid,
                                    $dienst_montag,
                                    $dienst_dienstag,
                                    $dienst_mittwoch,
                                    $dienst_donnerstag,
                                    $dienst_freitag){

    $sql = 'INSERT INTO dienst
              (Kalenderwoche,
               SanitaeterId,
               Montag,
               Dienstag,
               Mittwoch,
               Donnerstag,
               Freitag)
            VALUES
              ('.$akw.',
               '.$sid.',
               '.$dienst_montag.',
               '.$dienst_dienstag.',
               '.$dienst_mittwoch.',
               '.$dienst_donnerstag.',
               '.$dienst_freitag.')
            ON DUPLICATE KEY UPDATE
               Montag="'.$dienst_montag.'",
               Dienstag="'.$dienst_dienstag.'",
               Mittwoch="'.$dienst_mittwoch.'",
               Donnerstag="'.$dienst_donnerstag.'",
               Freitag="'.$dienst_freitag.'"';

    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Insert ok';
    return;
  }

  public function insert_anwesenheit($connected_db,
                                     $akw,
                                     $sid,
                                     $dienst_montag,
                                     $dienst_dienstag,
                                     $dienst_mittwoch,
                                     $dienst_donnerstag,
                                     $dienst_freitag){

    $sql = 'INSERT INTO anwesenheit
              (Kalenderwoche,
               SanitaeterId,
               Montag,
               Dienstag,
               Mittwoch,
               Donnerstag,
               Freitag)
            VALUES
              ('.$akw.',
               '.$sid.',
               '.$dienst_montag.',
               '.$dienst_dienstag.',
               '.$dienst_mittwoch.',
               '.$dienst_donnerstag.',
               '.$dienst_freitag.')
            ON DUPLICATE KEY UPDATE
               Montag="'.$dienst_montag.'",
               Dienstag="'.$dienst_dienstag.'",
               Mittwoch="'.$dienst_mittwoch.'",
               Donnerstag="'.$dienst_donnerstag.'",
               Freitag="'.$dienst_freitag.'";';
    //echo $sql;
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
    }
    return;
  }

   public function insert_handy($connected_db,
                               $sid,
                               $handy) {
    $sql1 = 'UPDATE handys SET SanitaeterID = NULL WHERE SanitaeterID = '.$sid;
    $sql2 = 'UPDATE handys SET SanitaeterID = '.$sid.' WHERE HandyID = '.$handy;

    $result1 = $connected_db->query($sql1);
    $result2 = $connected_db->query($sql2);
    if (!$result1) {
      die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
    }
    if (!$result2) {
      die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
    }
    return;
  }
  
	public function  handy_austragen($connected_db, $sid){
		$sql = 'UPDATE handys SET SanitaeterID = NULL WHERE SanitaeterID = '.$sid;	

		$result = $connected_db->query($sql);
		if (!$result) {
			die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
		}
		return;
	}

  public function select_Handy($connected_db) {

    $sql = "select *
            from handys;";
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }

  public function connectUser($user = 'user', $password = ''){
	$db = @new mysqli($this->host, $user, $password, $this->database);
    if (mysqli_connect_errno()) {
      die ('Konnte keine Verbindung zur Datenbank aufbauen: '.mysqli_connect_error().'('.mysqli_connect_errno().')');
    } else {
    	return $db;
    }
  }

 public function sanianlegen($connection, $vorname, $name, $klasse, $telefon, $email, $vorbildung, $passwort){
	$sql = "INSERT INTO user (vorname, name, klasse, telefonnummer, email, vorbildung, passwort, rollenid)
						Values('$vorname', '$name', '$klasse', '$telefon', '$email', '$vorbildung', '$passwort', 1);";

	$result = $connection->query($sql);
	if(!$result){
		echo "Anlegen des Sani gescheitert bitte Daten prüfen";
	}
	return;
 }

 public function saniUpdaten($connection, $vorname, $name, $klasse, $telefon, $email, $vorbildung, $passwort, $ID){
	$sql = "UPDATE user
			SET 	vorname = '$vorname',
					name = '$name',
					klasse = '$klasse',
					telefonnummer = '$telefon',
					email = '$email',
					vorbildung = '$vorbildung',
					passwort = '$passwort'
			WHERE 	SanitaeterID = $ID;";

	$result = $connection->query($sql);
	if(!$result){
		echo "Ändern der Daten gescheitert bitte Eingabe prüfen";
	}
	return;
 }

 public function userdatenselect($connection, $ID){
	$sql = "SELECT *
			FROM user
			WHERE SanitaeterID = $ID;";

	$result = $connection->query($sql);
	if(!$result){
		echo "Select nicht erfolgreich";
	}
	else{
		return $result;
	}
 }
}
?>
