<?php
class Database
{
  private $db_hostname = 'aaa';
  private $db_username = 'xxx';
  private $db_password = 'yyy';
  private $db_database = 'zzz';
  
  //DB-Tabellen-Namen mit optionalem gemeinsamen Prename
  
  private $tab_prename = "smv_";
  private function tab_anwesenheit() { return  $this->tab_prename . "anwesenheit"; }
  private function tab_dienst() { return  $this->tab_prename . "dienst"; }
  private function tab_user() { return  $this->tab_prename . "user"; }
  private function tab_handys() { return  $this->tab_prename . "handys"; }
  private function tab_team() { return  $this->tab_prename . "team"; }
  private function tab_team_user() { return  $this->tab_prename . "team_user"; }
  
  public function connect() {
    $db = @new mysqli($this->db_hostname, $this->db_username, $this->db_password, $this->db_database);
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

    $sql = "select s.SanitaeterID, s.name, s.vorname, s.Klasse, s.deleted, d.montag dmon, d.dienstag ddie, d.mittwoch dmit, d.donnerstag ddon, d.freitag dfre,
                                      a.montag amon, a.dienstag adie, a.mittwoch amit, a.donnerstag adon, a.freitag afre
            from ".$this->tab_anwesenheit()." a, ".$this->tab_dienst()." d, ".$this->tab_user()." s
            where a.sanitaeterid = s.sanitaeterid
            and   d.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw."
            and   d.kalenderwoche = a.kalenderwoche
            and   s.deleted is NULL";
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }
  
    public function select_anwesenheit($connected_db, $kw) {

    $sql = "select s.SanitaeterID, s.name, s.vorname, s.Klasse, s.deleted, a.montag dmon, a.dienstag ddie, a.mittwoch dmit, a.donnerstag ddon, a.freitag dfre,
                                      a.montag amon, a.dienstag adie, a.mittwoch amit, a.donnerstag adon, a.freitag afre
            from ".$this->tab_anwesenheit()." a, ".$this->tab_user()." s
            where a.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw."
            and   s.deleted is NULL";
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }
  
    public function select_all_users($connected_db) {

    $sql = "select s.SanitaeterID, s.name, s.vorname, s.Klasse, s.Raum, s.telefonnummer, s.email, s.vorbildung, s.deleted, s.Status
            from ".$this->tab_user()." s where s.deleted is NULL order by s.Klasse";

    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }
  
      public function select_all_teams($connected_db) {

    $sql = "select t.tid, t.bezeichnung
            from ".$this->tab_team()." t order by t.bezeichnung desc";

    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
   // echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }

  public function select_kw($connected_db) {
    //$kw = date('W', time());

    $sql = "select distinct Kalenderwoche
            from ".$this->tab_anwesenheit();
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

     $sql = "select s.sanitaeterid sid, s.name, s.vorname, s.Klasse, s.deleted, a.montag, a.dienstag, a.mittwoch, a.donnerstag, a.freitag
            from ".$this->tab_anwesenheit()." a, ".$this->tab_user()." s
            where s.sanitaeterid = ".$sid."
            and   a.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw."
            and   s.deleted is NULL";

     $result = $connected_db->query($sql);
     if (!$result) {
       die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
     }
     //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
     return $result;
  }

  public function select_dienst_by_id($connected_db, $kw, $sid) {

    $sql = "SELECT s.name, s.vorname, s.deleted, d.montag, d.dienstag, d.mittwoch, d.donnerstag, d.freitag
            FROM ".$this->tab_dienst()." d, ".$this->tab_user()." s
            WHERE s.sanitaeterid = ".$sid."
            and   d.sanitaeterid = s.sanitaeterid
            and   d.kalenderwoche = ".$kw."
            and   s.deleted is NULL";
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

    $sql = 'INSERT INTO '.$this->tab_dienst().'
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

    $sql = 'INSERT INTO '.$this->tab_anwesenheit().'
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
    $sql1 = 'UPDATE '.$this->tab_handys().' SET SanitaeterID = NULL WHERE SanitaeterID = '.$sid;
    $sql2 = 'UPDATE '.$this->tab_handys().' SET SanitaeterID = '.$sid.' WHERE HandyID = '.$handy;

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
    $sql = 'UPDATE '.$this->tab_handys().' SET SanitaeterID = NULL WHERE SanitaeterID = '.$sid;	
    $result = $connected_db->query($sql);

    if (!$result) {
	die ('Etwas stimmt mit dem Query nicht: '.$connected_db->error);
    }
    return;
    }

  public function select_Handy($connected_db) {

    $sql = 'select *
            from '.$this->tab_handys();
    $result = $connected_db->query($sql);
    if (!$result) {
      die ('Etwas stimmte mit dem Query nicht: '.$connected_db->error);
    }
    //echo 'Die Ergebnistabelle besitzt '.$result->num_rows." Datensätze<br />\n";
    return $result;
  }

 public function saniUpdaten($connection, $update, $vorname, $name, $klasse, $raum, $status, $telefon, $email, $vorbildung, $passwort, $ID){
	if($update==0)
	{
	    $sql = "INSERT INTO ".$this->tab_user()."
			SET 	vorname = '$vorname',
				name = '$name',
				klasse = '$klasse',
				raum = '$raum',
				status = '$status',	
				telefonnummer = '$telefon',
				email = '$email',
				vorbildung = '$vorbildung',
				passwort = '$passwort',
				rollenID = 2";
	     echo "<br>&nbsp; &nbsp; <font color=green>Einfügen der Daten</font>";
	}
	else if($update==1)	    // besser: if( $ID > 0)  oder isset
	{
	   $sql = "UPDATE ".$this->tab_user()."
			SET 	vorname = '$vorname',
				name = '$name',
				klasse = '$klasse',
				raum = '$raum',
				status = '$status',
				telefonnummer = '$telefon',
				email = '$email',
				vorbildung = '$vorbildung',
				passwort = '$passwort'
			WHERE 	SanitaeterID = $ID;";
	    echo "<br>&nbsp; &nbsp; <font color=green>Ändern der Daten</font>";
	}
	else
	{
		echo "<br>&nbsp; &nbsp; <font color=red>Unerlaubter Aufruf</font>";
	}
	
	$result = $connection->query($sql);
	if($result)
	{
	     echo "&nbsp;<font color=green>erfolgreich</font>";
	}
	else
	{
	     echo "&nbsp;<font color=red>gescheitert... bitte Info an Matthias.Ebert@BS-Erlangen.de</font>";
	}
	return;
 }
 
 
 public function deleteSani($connection, $ID){
	
	$sql = "UPDATE ".$this->tab_user()."
		SET 	deleted = 1
		WHERE 	SanitaeterID = $ID;";
	echo "<br>&nbsp; &nbsp; <font color=green>Löschmarkierung der Daten</font>";
	
	$result = $connection->query($sql);
	if($result)
	{
	     echo "&nbsp;<font color=green>erfolgreich</font>";
	}
	else
	{
	     echo "&nbsp;<font color=red>gescheitert... bitte Info an Matthias.Ebert@BS-Erlangen.de</font>";
	}
	return;
 }
 
 public function userdatenselect($connection, $ID){
	$sql = "SELECT *
			FROM ".$this->tab_user()."
			WHERE SanitaeterID = $ID;";

	$result = $connection->query($sql);
	if(!$result){
		echo "Select nicht erfolgreich";
	}
	else{
		return $result;
	}
 }
 
  public function all_users($connection){
	$sql = "SELECT *
			FROM ".$this->tab_user().";";

	$result = $connection->query($sql);
	if(!$result){
		echo "Select nicht erfolgreich";
	}
	else{
		return $result;
	}
 }
 
  public function existsKW($connection, $sid, $kw){
        $sql = "select a.kalenderwoche
            from ".$this->tab_anwesenheit()." a, ".$this->tab_user()." s
            where s.sanitaeterid = ".$sid."
            and   a.sanitaeterid = s.sanitaeterid
            and   a.kalenderwoche = ".$kw;

	$result = $connection->query($sql);
	if(!$result){
		echo "Select nicht erfolgreich";
	}
	else{
		return $result->num_rows;
	}
     }
 
 
} // end class Database
?>
