<?php

// Use SQL class with injection safety
require_once __DIR__.'/sql-class/src/SQL.class.php';
require_once __DIR__.'/sql-class/src/SQLCommand.class.php';
// remove namespaces for easier use
use AMWD\SQL\SQL as SQL;
use AMWD\SQL\SQLCommand as SQLCommand;

class Database
{
	private $db_hostname = 'localhost';
	private $db_username = 'schulsanidienst';
	private $db_password = 'sani';
	private $db_database = 'SchulSanidienst';
	private $db_port     = 3306;
	private $db;
	
	//DB-Tabellen-Namen mit optionalem gemeinsamen Prename
	private $tab_prename = "";
	private function tab_attendences()   { return  $this->tab_prename . "attendences"; }
	private function tab_dutys()         { return  $this->tab_prename . "dutys"; }
	private function tab_users()         { return  $this->tab_prename . "users"; }
	private function tab_handys()        { return  $this->tab_prename . "handys"; }
	private function tab_teams()         { return  $this->tab_prename . "teams"; }
	private function tab_ref_team_user() { return  $this->tab_prename . "ref_team_user"; }
	
	public function connect() {
		try {
			$this->db = SQL::MySQL($this->db_username, $this->db_password, $this->db_database, $this->db_port, $this->db_hostname);
			$this->db->locales = 'de_DE';
			$this->db->open();
			return true;
		} catch (Exception $ex) {
			die('Keine Verbindung zur Datenbank möglich: '.$ex->getMessage());
		}
	}
	
	public function close() {
		$this->db->close();
	}
	
	public function select_dienst($kw) {
		$query = "SELECT
	user_id,
	user_name,
	user_vorname,
	user_klasse,
	duty_mon,
	duty_tue,
	duty_wed,
	duty_thu,
	duty_fri,
	att_mon,
	att_tue,
	att_wed,
	att_thu,
	att_fri
FROM
	".$this->tab_attendences()."
JOIN
	".$this->tab_users()." ON att_user = user_id
JOIN
	".$this->tab_dutys()." ON att_week = duty_week
	                      AND duty_user = user_id
WHERE
	user_deleted IS NULL
	AND att_week  = @week
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('week', $kw);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->id      = $dr->get_Int('user_id');
			$usr->name    = $dr->get_String('user_name');
			$usr->vorname = $dr->get_String('user_vorname');
			$usr->klasse  = $dr->get_String('user_klasse');
			$usr->dmon    = $dr->get_Boolean('duty_mon');
			$usr->ddie    = $dr->get_Boolean('duty_tue');
			$usr->dmit    = $dr->get_Boolean('duty_wed');
			$usr->ddon    = $dr->get_Boolean('duty_thu');
			$usr->dfre    = $dr->get_Boolean('duty_fri');
			$usr->amon    = $dr->get_Boolean('att_mon');
			$usr->adie    = $dr->get_Boolean('att_tue');
			$usr->amit    = $dr->get_Boolean('att_wed');
			$usr->adon    = $dr->get_Boolean('att_thu');
			$usr->afre    = $dr->get_Boolean('att_fri');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function select_anwesenheit($kw) {
		$query = "SELECT
	user_id,
	user_name,
	user_vorname,
	user_klasse,
	att_mon,
	att_tue,
	att_wed,
	att_thu,
	att_fri
FROM
	".$this->tab_attendences()."
JOIN
	".$this->tab_users()." ON att_user = user_id
WHERE
	user_deleted IS NULL
	AND att_week  = @week
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('week', $kw);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->id      = $dr->get_Int('user_id');
			$usr->name    = $dr->get_String('user_name');
			$usr->vorname = $dr->get_String('user_vorname');
			$usr->klasse  = $dr->get_String('user_klasse');
			$usr->amon    = $dr->get_Boolean('att_mon');
			$usr->adie    = $dr->get_Boolean('att_tue');
			$usr->amit    = $dr->get_Boolean('att_wed');
			$usr->adon    = $dr->get_Boolean('att_thu');
			$usr->afre    = $dr->get_Boolean('att_fri');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function select_all_users() {
		$query = "SELECT * FROM ".$this->tab_users().";";
		$cmd = new SQLCommand($query, $this->db);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->id         = $dr->get_Int('user_id');
			$usr->name       = $dr->get_String('user_name');
			$usr->vorname    = $dr->get_String('user_vorname');
			$usr->klasse     = $dr->get_String('user_klasse');
			$usr->raum       = $dr->get_String('user_raum');
			$usr->telefon    = $dr->get_String('user_telefon');
			$usr->handy      = $dr->get_String('user_handy');
			$usr->email      = $dr->get_String('user_email');
			$usr->vorbildung = $dr->get_String('user_vorbildung');
			$usr->deleted    = $dr->get_Boolean('user_deleted');
			$usr->status     = $dr->get_Int('user_status');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function select_all_teams() {
		$query = "SELECT
	team_id,
	team_bezeichnung,
	user_name,
	user_vorname
FROM
	".$this->tab_teams()."
JOIN
	".$this->tab_users()." ON team_leiter = user_id
;";
		$cmd = new SQLCommand($query, $this->db);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$team = new stdClass();
			$team->id = $dr->get_Int('team_id');
			$team->bezeichnung = $dr->get_String('team_bezeichnung');
			$team->leiter = new stdClass();
			$team->leiter->name = $dr->get_String('user_name');
			$team->leiter->vorname = $dr->get_String('user_vorname');
			
			$result[] = $team;
		}
		
		return $result;
	}
	
	public function select_kw() {
		/*$kw = date('W', time());
		$query = "SELECT DISTINCT
	att_week
FROM
	".$this->tab_attendences()."
WHERE
	att_week >= @week
;";*/
		
		$query = "SELECT DISTINCT att_week FROM ".$this->tab_attendences().";";
		$cmd = new SQLCommand($query, $this->db);
		//$cmd->add_parameter('week', $kw);
		
		$result = array();
		$dr = $cmd->execute_reader();
		while ($dr->read()) {
			$result[] = $dr->get_Int('att_week');
		}
		
		return $result;
	}
	
	public function select_anwesend($kw, $id) {
		$query = "SELECT
	user_name,
	user_vorname,
	user_klasse,
	att_mon,
	att_tue,
	att_wed,
	att_thu,
	att_fri
FROM
	".$this->tab_users()."
JOIN
	".$this->tab_attendences()." ON att_user = user_id
WHERE
	user_id = @id
	AND att_week = @week
	AND user_deleted IS NULL
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('week', $kw);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->name       = $dr->get_String('user_name');
			$usr->vorname    = $dr->get_String('user_vorname');
			$usr->klasse     = $dr->get_String('user_klasse');
			$usr->montag     = $dr->get_Boolean('att_mon');
			$usr->dienstag   = $dr->get_Boolean('att_tue');
			$usr->mittwoch   = $dr->get_Boolean('att_wed');
			$usr->donnerstag = $dr->get_Boolean('att_thu');
			$usr->freitag    = $dr->get_Boolean('att_fri');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function select_dienst_by_id($kw, $id) {
		$query = "SELECT
	user_name,
	user_vorname,
	duty_mon,
	duty_tue,
	duty_wed,
	duty_thu,
	duty_fri
FROM
	".$this->tab_users()."
JOIN
	".$this->tab_dutys()." ON duty_user = user_id
WHERE
	user_id = @id
	AND duty_week = @week
	AND user_deleted IS NULL
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('week', $kw);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->name       = $dr->get_String('user_name');
			$usr->vorname    = $dr->get_String('user_vorname');
			$usr->klasse     = $dr->get_String('user_klasse');
			$usr->montag     = $dr->get_Boolean('duty_mon');
			$usr->dienstag   = $dr->get_Boolean('duty_tue');
			$usr->mittwoch   = $dr->get_Boolean('duty_wed');
			$usr->donnerstag = $dr->get_Boolean('duty_thu');
			$usr->freitag    = $dr->get_Boolean('duty_fri');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function insert_dienstplan($kw, $id, $mon, $tue, $wed, $thu, $fri) {
		$query = "INSERT INTO ".$this->tab_dutys()." (
	duty_week,
	duty_user,
	duty_mon,
	duty_tue,
	duty_wed,
	duty_thu,
	duty_fri
)
VALUES (
	@week,
	@id,
	@mon,
	@tue,
	@wed,
	@thu,
	@fri
)
ON DUPLICATE KEY UPDATE
	duty_mon = @mon,
	duty_tue = @tue,
	duty_wed = @wed,
	duty_thu = @thu,
	duty_fri = @fri
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('week', $kw);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('mon', $mon);
		$cmd->add_parameter('tue', $tue);
		$cmd->add_parameter('wed', $wed);
		$cmd->add_parameter('thu', $thu);
		$cmd->add_parameter('fir', $fri);
		
		if (!$cmd->execute_non_query())
			die('insert_dienstplan fehlgeschlagen');
	}

	public function insert_anwesenheit($kw, $id, $mon, $tue, $wed, $thu, $fri) {
		$query = "INSERT INTO ".$this->tab_attendences()." (
	att_week,
	att_user,
	att_mon,
	att_tue,
	att_wed,
	att_thu,
	att_fri
)
VALUES (
	@week,
	@id,
	@mon,
	@tue,
	@wed,
	@thu,
	@fri
)
ON DUPLICATE KEY UPDATE
	att_mon = @mon,
	att_tue = @tue,
	att_wed = @wed,
	att_thu = @thu,
	att_fri = @fri
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('week', $kw);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('mon', $mon);
		$cmd->add_parameter('tue', $tue);
		$cmd->add_parameter('wed', $wed);
		$cmd->add_parameter('thu', $thu);
		$cmd->add_parameter('fir', $fri);
		
		if (!$cmd->execute_non_query())
			die('insert_anwesenheit fehlgeschlagen');
	}
	
	public function insert_handy($id, $handy) {
		// TODO
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
