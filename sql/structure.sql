CREATE TABLE users (
	user_id          INT(11)       UNSIGNED      NOT NULL  AUTO_INCREMENT,
	user_vorname     VARCHAR(40)                 NOT NULL,
	user_name        VARCHAR(40)                 NOT NULL,
	user_geburtstag  DATETIME      DEFAULT NULL,
	user_klasse      VARCHAR(10)   DEFAULT NULL,
	user_raum        VARCHAR(10)   DEFAULT NULL,
	user_telefon     VARCHAR(20)   DEFAULT NULL,
	user_handy       VARCHAR(20)   DEFAULT NULL,
	user_email       VARCHAR(80)                 NOT NULL,
	user_vorbildung  TEXT          DEFAULT NULL,
	user_passwort    VARCHAR(100)                NOT NULL,
	user_role        INT(5)        DEFAULT NULL,
	user_deleted     BIT(1)        DEFAULT NULL,
	user_status      INT(11)       DEFAULT NULL,
	PRIMARY KEY(user_id),
	UNIQUE KEY(user_email)
);

CREATE TABLE teams (
	team_id           INT(11)      UNSIGNED  NOT NULL  AUTO_INCREMENT,
	team_bezeichnung  VARCHAR(50)            NOT NULL,
	team_leiter       INT(11)      UNSIGNED            DEFAULT NULL,
	PRIMARY KEY(team_id),
	UNIQUE KEY(team_bezeichnung),
	CONSTRAINT team_leiter_fk FOREIGN KEY(team_leiter) REFERENCES users(user_id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE ref_team_user (
	ref_user INT(11) UNSIGNED NOT NULL,
	ref_team INT(11) UNSIGNED NOT NULL,
	UNIQUE KEY(ref_user,ref_team),
	CONSTRAINT ref_user_fk FOREIGN KEY(ref_user) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT ref_team_fk FOREIGN KEY(ref_team) REFERENCES teams(team_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE handys (
	handy_id      INT(11)      UNSIGNED  NOT NULL  AUTO_INCREMENT,
	handy_nummer  VARCHAR(20)            NOT NULL,
	handy_user    INT(11)      UNSIGNED,
	PRIMARY KEY(handy_id),
	CONSTRAINT handy_user_fk FOREIGN KEY(handy_user) REFERENCES users(user_id) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE attendences (
	att_week  INT(11)             NOT NULL,
	att_user  INT(11)             UNSIGNED,
	att_mon   BIT(1)   DEFAULT 0,
	att_tue   BIT(1)   DEFAULT 0,
	att_wed   BIT(1)   DEFAULT 0,
	att_thu   BIT(1)   DEFAULT 0,
	att_fri   BIT(1)   DEFAULT 0,
	PRIMARY KEY(att_week,att_user),
	CONSTRAINT att_user_fk FOREIGN KEY(att_user) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE dutys (
	duty_week  INT(11)             NOT NULL,
	duty_user  INT(11)             UNSIGNED,
	duty_mon   BIT(1)   DEFAULT 0,
	duty_tue   BIT(1)   DEFAULT 0,
	duty_wed   BIT(1)   DEFAULT 0,
	duty_thu   BIT(1)   DEFAULT 0,
	duty_fri   BIT(1)   DEFAULT 0,
	PRIMARY KEY(duty_week,duty_user),
	CONSTRAINT duty_user_fk FOREIGN KEY(duty_user) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);
