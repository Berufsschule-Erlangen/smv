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
	private function tab_duties()        { return  $this->tab_prename . "duties"; }
	private function tab_users()         { return  $this->tab_prename . "users"; }
	private function tab_handies()       { return  $this->tab_prename . "handies"; }
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
	".$this->tab_duties()." ON att_week = duty_week
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
	
	public function select_all_teams() {
		$query = "SELECT
	team_id,
	team_bezeichnung,
	user_name,
	user_vorname
FROM
	".$this->tab_teams()."
LEFT JOIN
	".$this->tab_users()." ON team_leiter = user_id
;";
		$cmd = new SQLCommand($query, $this->db);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$team = new stdClass();
			$team->id              = $dr->get_Int('team_id');
			$team->bezeichnung     = $dr->get_String('team_bezeichnung');
			$team->leiter = new stdClass();
			$team->leiter->name    = $dr->get_String('user_name');
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
	".$this->tab_duties()." ON duty_user = user_id
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
		$query = "INSERT INTO ".$this->tab_duties()." (
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
		$cmd->add_parameter('fri', $fri);
		
		if (!$cmd->execute_non_query())
			die('insert_anwesenheit fehlgeschlagen');
	}
	
	public function insert_handy($id, $handy) {
		$query1 = "UPDATE ".$this->tab_handies()." SET handy_user = NULL WHERE handy_user = @id;";
		$query2 = "UPDATE ".$this->tab_handies()." SET handy_user = @id WHERE handy_id = @handy;";
		$cmd1 = new SQLCommand($query1, $this->db);
		$cmd2 = new SQLCommand($query2, $this->db);
		$cmd1->addParameter('id', $id);
		$cmd2->addParameter('id', $id);
		$cmd2->addParameter('handy', $handy);
		
		$cmd1->execute_non_query();
		$cmd2->execute_non_query();
	}
	
	public function handy_austragen($id) {
		$query = "UPDATE ".$this->tab_handies()." SET handy_user = NULL WHERE handy_user = @id;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		$cmd->execute_non_query();
	}
	
	public function select_handy() {
		$query = "SELECT
	handy_id,
	handy_nummer,
	user_id,
	user_name,
	user_vorname
FROM
	".$this->tab_handies()."
JOIN
	".$this->tab_users()." ON handy_user = user_id
ORDER BY
	handy_nummer
;";
		$cmd = new SQLCommand($query, $this->db);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$handy = new stdClass();
			$handy->id            = $dr->get_Int('handy_id');
			$handy->nummer        = $dr->get_String('handy_nummer');
			$handy->user = new stdClass();
			$handy->user->id      = $dr->get_Int('user_id');
			$handy->user->name    = $dr->get_String('user_name');
			$handy->user->vorname = $dr->get_String('user_vorname');
			
			$result[] = $handy;
		}
		
		return $result;
	}
	
	public function set_user($update, $vorname, $name, $klasse, $raum, $status, $telefon, $handy, $email, $vorbildung, $passwort, $id = 0) {
		if ($update && $id > 0) {
			$query = "UPDATE ".$this->tab_users()." SET
	user_vorname = @vorname,
	user_name = @name,
	user_klasse = @klasse,
	user_raum = @raum,
	user_status = @status,
	user_telefon = @telefon,
	user_handy = @handy,
	user_email = @email,
	user_vorbildung = @vorbildung,
	user_passwort = @passwort
WHERE
	user_id = @id
	;";
		} else {
			$query = "INSERT INTO ".$this->tab_users()." (
	user_vorname,
	user_name,
	user_klasse,
	user_raum,
	user_status,
	user_telefon,
	user_handy,
	user_email,
	user_vorbildung,
	user_passwort,
	user_role
)
VALUES (
	@vorname,
	@name,
	@klasse,
	@raum,
	@status,
	@telefon,
	@handy,
	@email,
	@vorbildung,
	@passwort,
	@role
);";
		}
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('vorname', $vorname);
		$cmd->add_parameter('name', $name);
		$cmd->add_parameter('klasse', $klasse);
		$cmd->add_parameter('raum', $raum);
		$cmd->add_parameter('status', $status);
		$cmd->add_parameter('telefon', $telefon);
		$cmd->add_parameter('handy', $handy);
		$cmd->add_parameter('email', $email);
		$cmd->add_parameter('vorbildung', $vorbildung);
		$cmd->add_parameter('passwort', $passwort);
		$cmd->add_parameter('id', $id);
		
		echo 'Speichern der Daten ';
		if ($cmd->execute_non_query())
			echo '<span style="color: #0f0">erfolgreich</span>';
		else
			echo '<span style="color: #f00">gescheitert</span>... Info an Matthias.Ebert@BS-Erlangen.de';
	}
	
	public function delete_user($id) {
		$query = "UPDATE ".$this->tab_users()." SET user_deleted = 1 WHERE user_id = @id;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		
		echo 'Löschen ';
		if ($cmd->execute_non_query())
			echo '<span style="color: #0f0">erfolgreich</span>';
		else
			echo '<span style="color: #f00">gescheitert</span>... Info an Matthias.Ebert@BS-Erlangen.de';
	}
	
	public function select_user($id) {
		$query = "SELECT
	user_name,
	user_vorname,
	user_geburtstag,
	user_klasse,
	user_raum,
	user_telefon,
	user_handy,
	user_email,
	user_vorbildung,
	user_passwort,
	user_role,
	user_deleted,
	user_status
FROM
	".$this->tab_users()."
WHERE
	user_id = @id
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		
		$dr = $cmd->execute_reader();
		if ($dr->read()) {
			$usr = new stdClass();
			$usr->vorname    = $dr->get_String('user_vorname');
			$usr->name       = $dr->get_String('user_name');
			$usr->geburtstag = $dr->get_DateTime('user_geburtstag');
			$usr->klasse     = $dr->get_String('user_klasse');
			$usr->raum       = $dr->get_String('user_raum');
			$usr->telefon    = $dr->get_String('user_telefon');
			$usr->handy      = $dr->get_String('user_handy');
			$usr->email      = $dr->get_String('user_email');
			$usr->vorbildung = $dr->get_String('user_vorbildung');
			$usr->passwort   = $dr->get_String('user_passwort');
			$usr->role       = $dr->get_Int('user_role');
			$usr->deleted    = $dr->get_Boolean('user_deleted');
			$usr->status     = $dr->get_Int('user_status');
			
			return $usr;
		} else {
			echo 'User nicht gefunden';
			return null;
		}
	}
	
	public function select_all_users() {
		$query = "SELECT
	user_id,
	user_name,
	user_vorname,
	user_geburtstag,
	user_klasse,
	user_raum,
	user_telefon,
	user_handy,
	user_email,
	user_vorbildung,
	user_passwort,
	user_role,
	user_deleted,
	user_status
FROM
	".$this->tab_users()."
ORDER BY
	user_name, user_vorname
;";
		$cmd = new SQLCommand($query, $this->db);
		
		$result = array();
		$dr = $cmd->execute_reader();
		
		while ($dr->read()) {
			$usr = new stdClass();
			$usr->id         = $dr->get_Int('user_id');
			$usr->vorname    = $dr->get_String('user_vorname');
			$usr->name       = $dr->get_String('user_name');
			$usr->geburtstag = $dr->get_DateTime('user_geburtstag');
			$usr->klasse     = $dr->get_String('user_klasse');
			$usr->raum       = $dr->get_String('user_raum');
			$usr->telefon    = $dr->get_String('user_telefon');
			$usr->handy      = $dr->get_String('user_handy');
			$usr->email      = $dr->get_String('user_email');
			$usr->vorbildung = $dr->get_String('user_vorbildung');
			$usr->passwort   = $dr->get_String('user_passwort');
			$usr->role       = $dr->get_Int('user_role');
			$usr->deleted    = $dr->get_Boolean('user_deleted');
			$usr->status     = $dr->get_Int('user_status');
			
			$result[] = $usr;
		}
		
		return $result;
	}
	
	public function exists_kw($id, $kw) {
		$query = "SELECT
	att_week
FROM
	".$this->tab_attendences()."
WHERE
	att_week = @week
	AND att_user = @id
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('week', $kw);
		
		$dr = $cmd->execute_reader();
		
		return $dr->read();
	}
	
	public function get_user_login($email) {
		$query = "SELECT
	user_id,
	user_passwort,
	user_role
FROM
	".$this->tab_users()."
WHERE
	user_email = @email
	AND user_deleted IS NULL
;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('email', $email);
		
		$dr = $cmd->execute_reader();
		
		if ($dr->read()) {
			$usr = new stdClass();
			$usr->id = $dr->get_Int('user_id');
			$usr->passwort = $dr->get_String('user_passwort');
			$usr->role = $dr->get_Int('user_role');
			
			return $usr;
		} else {
			return null;
		}
	}
	
	public function update_password($id, $password) {
		$query = "UPDATE ".$this->tab_users()." SET user_passwort = @pw WHERE user_id = @id;";
		$cmd = new SQLCommand($query, $this->db);
		$cmd->add_parameter('id', $id);
		$cmd->add_parameter('pw', $password);
		
		$cmd->execute_non_query();
	}
 
} // end class Database
?>
