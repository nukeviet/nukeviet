<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/28/2009 20:8
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Ten cac table cua CSDL dung chung cho he thong

$query = $db->query( "select table_name from all_tables WHERE table_name LIKE '" . strtoupper( $db_config['prefix'] . "_%" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_table[] = 'drop table ' . $row['table_name'] . ' cascade constraints PURGE';
}

$query = $db->query( "select sequence_name from user_sequences WHERE sequence_name LIKE '" . strtoupper( "SNV_%" ) . "'" );
while( $row = $query->fetch() )
{
	$sql_drop_table[] = 'drop SEQUENCE ' . $row['sequence_name'];
}

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . " (
	admin_id NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	editor VARCHAR2(100 CHAR) DEFAULT '',
	lev NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	files_level VARCHAR2(255 CHAR) DEFAULT '',
	position VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	addtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	edittime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	is_suspend NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	susp_reason VARCHAR2(4000 CHAR) DEFAULT '',
	check_num VARCHAR2(40 CHAR) DEFAULT NULL,
	last_login NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	last_ip VARCHAR2(45 CHAR) DEFAULT '',
	last_agent VARCHAR2(255 CHAR) DEFAULT '',
	primary key (admin_id)
)";

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . "_config (
	id NUMBER(8,0) DEFAULT NULL,
	keyname VARCHAR2(32 CHAR)DEFAULT '',
	mask NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	begintime NUMBER(11,0) DEFAULT 0,
	endtime NUMBER(11,0) DEFAULT 0,
	notice VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (id),
	CONSTRAINT unv_authors_config_keyname UNIQUE (keyname)
)";
$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_CONFIG';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_CONFIG
  BEFORE INSERT  ON ' . NV_AUTHORS_GLOBALTABLE . '_config
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_CONFIG.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_CONFIG;';

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . "_module (
	mid NUMBER(8,0) DEFAULT NULL,
	module VARCHAR2(55 CHAR) DEFAULT '' NOT NULL ENABLE,
	lang_key VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	weight NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	act_1 NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	act_2 NUMBER(3,0) DEFAULT 1 NOT NULL ENABLE,
	act_3 NUMBER(3,0) DEFAULT 1 NOT NULL ENABLE,
	checksum VARCHAR2(32 CHAR) DEFAULT NULL,
	primary key (mid),
	CONSTRAINT unv_authors_module_module UNIQUE (module)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_MODULE MINVALUE 100';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_MODULE
  BEFORE INSERT  ON ' . NV_AUTHORS_GLOBALTABLE . '_module
  FOR EACH ROW WHEN (new.mid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_MODULE.nextval INTO :new.mid FROM DUAL;
	END TNV_' . strtoupper( NV_AUTHORS_GLOBALTABLE ) . '_MODULE;';

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_config (
	config VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	content VARCHAR2(4000 CHAR) DEFAULT NULL,
	edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (config)
)";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_question (
	qid NUMBER(5,0) DEFAULT NULL,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	lang CHAR(2 CHAR) DEFAULT '' NOT NULL ENABLE,
	weight NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (qid),
	CONSTRAINT unv_users_question_title UNIQUE (title,lang)
)";
$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_QUESTION MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_QUESTION
  BEFORE INSERT  ON ' . NV_USERS_GLOBALTABLE . '_question
  FOR EACH ROW WHEN (new.qid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_QUESTION.nextval INTO :new.qid FROM DUAL;
	END TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_QUESTION;';

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . " (
	userid NUMBER(8,0) DEFAULT NULL,
	username VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	md5username CHAR(32 CHAR) DEFAULT '' NOT NULL ENABLE,
	password VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	full_name VARCHAR2(255 CHAR) DEFAULT '',
	gender CHAR(1 CHAR) DEFAULT '',
	photo VARCHAR2(255 CHAR) DEFAULT '',
	birthday NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	sig VARCHAR2(4000 CHAR) DEFAULT '',
	regdate NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	question VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	answer VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	passlostkey VARCHAR2(50 CHAR) DEFAULT '',
	view_mail NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	remember NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	in_groups VARCHAR2(255 CHAR) DEFAULT '',
	active NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	checknum VARCHAR2(40 CHAR) DEFAULT '',
	last_login NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	last_ip VARCHAR2(45 CHAR) DEFAULT '',
	last_agent VARCHAR2(255 CHAR) DEFAULT '',
	last_openid VARCHAR2(255 CHAR) DEFAULT '',
	idsite NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (userid),
	CONSTRAINT unv_users_username UNIQUE (username),
	CONSTRAINT unv_users_md5username UNIQUE (md5username),
	CONSTRAINT unv_users_email UNIQUE (email)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_ROW MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_ROW
  BEFORE INSERT  ON ' . NV_USERS_GLOBALTABLE . '
  FOR EACH ROW WHEN (new.userid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_ROW.nextval INTO :new.userid FROM DUAL;
	END TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_ROW;';

$sql_create_module[] = "CREATE INDEX inv_" . NV_USERS_GLOBALTABLE . "_idsite ON " . NV_USERS_GLOBALTABLE . "(idsite) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_reg (
	userid NUMBER(8,0) DEFAULT NULL,
	username VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	md5username CHAR(32 CHAR) DEFAULT '' NOT NULL ENABLE,
	password VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	full_name VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	regdate NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	question VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	answer VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	checknum VARCHAR2(50 CHAR) DEFAULT '',
	users_info VARCHAR2(4000 CHAR) DEFAULT '',
	primary key (userid),
	CONSTRAINT unv_users_reg_login UNIQUE (username),
	CONSTRAINT unv_users_reg_md5username UNIQUE (md5username),
	CONSTRAINT unv_users_reg_email UNIQUE (email)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_REG';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_REG
  BEFORE INSERT  ON ' . NV_USERS_GLOBALTABLE . '_reg
  FOR EACH ROW WHEN (new.userid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_REG.nextval INTO :new.userid FROM DUAL;
	END TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_REG;';

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_openid (
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	openid VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	opid VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (opid)
)";

$sql_create_table[] = "CREATE INDEX inv_users_openid_userid ON NV3_USERS_OPENID(userid) TABLESPACE USERS";
$sql_create_table[] = "CREATE INDEX inv_users_openid_email ON NV3_USERS_OPENID(email) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_field (
	fid NUMBER(8,0) DEFAULT NULL,
	field VARCHAR2(25 CHAR) DEFAULT '' NOT NULL ENABLE,
	weight NUMBER(11,0) DEFAULT 1 NOT NULL ENABLE,
	field_type VARCHAR2(4000 CHAR) DEFAULT 'textbox' NOT NULL ENABLE,
	field_choices VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	sql_choices VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	match_type VARCHAR2(4000 CHAR) DEFAULT 'none' NOT NULL ENABLE,
	match_regex VARCHAR2(250 CHAR) DEFAULT '' NOT NULL ENABLE,
	func_callback VARCHAR2(75 CHAR) DEFAULT '' NOT NULL ENABLE,
	min_length NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	max_length NUMBER(20,0) DEFAULT 0 NOT NULL ENABLE,
	required NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	show_register NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	user_editable NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	show_profile NUMBER(3,0) DEFAULT 1 NOT NULL ENABLE,
	class VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	language VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	default_value VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (fid),
	CONSTRAINT unv_" . NV_USERS_GLOBALTABLE . "_field UNIQUE (field)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_FIELD';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_FIELD
  BEFORE INSERT  ON ' . NV_USERS_GLOBALTABLE . '_field
  FOR EACH ROW WHEN (new.fid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_FIELD.nextval INTO :new.fid FROM DUAL;
	END TNV_' . strtoupper( NV_USERS_GLOBALTABLE ) . '_FIELD;';

$sql_create_table[] = "CREATE OR REPLACE TRIGGER TNV_USERS_FIELD_MATCH_TYPE
	BEFORE INSERT OR UPDATE ON " . NV_USERS_GLOBALTABLE . "_field
	FOR EACH ROW
	DECLARE
	  v_val " . NV_USERS_GLOBALTABLE . "_field.match_type%TYPE;
	  v_test " . NV_USERS_GLOBALTABLE . "_field.match_type%TYPE;
	BEGIN
	  v_val := 'none';
	  v_test := TRIM(:new.match_type);
	  if v_test = '1' OR v_test = 'none' THEN
	  v_val := 'none';
	  elsif v_test = '2' OR v_test = 'alphanumeric' THEN
	  v_val := 'alphanumeric';
	  elsif v_test = '3' OR v_test = 'email' THEN
	  v_val := 'email';
	  elsif v_test = '4' OR v_test = 'url' THEN
	  v_val := 'url';
	  elsif v_test = '5' OR v_test = 'regex' THEN
	  v_val := 'regex';
	  elsif v_test = '6' OR v_test = 'callback' THEN
	  v_val := 'callback';
	  end if;  :new.match_type := v_val;
	END TNV_USERS_FIELD_MATCH_TYPE;";

$sql_create_table[] = "CREATE OR REPLACE TRIGGER TNV_USERS_FIELD_TYPE
	BEFORE INSERT OR UPDATE ON " . NV_USERS_GLOBALTABLE . "_field
	FOR EACH ROW
	DECLARE
	  v_val " . NV_USERS_GLOBALTABLE . "_field.field_type%TYPE;
	  v_test " . NV_USERS_GLOBALTABLE . "_field.field_type%TYPE;
	BEGIN
	  v_val := 'number';
	  v_test := TRIM(:new.field_type);
	  if v_test = '1' OR v_test = 'number' THEN
	  v_val := 'number';
	  elsif v_test = '2' OR v_test = 'date' THEN
	  v_val := 'date';
	  elsif v_test = '3' OR v_test = 'textbox' THEN
	  v_val := 'textbox';
	  elsif v_test = '4' OR v_test = 'textarea' THEN
	  v_val := 'textarea';
	  elsif v_test = '5' OR v_test = 'editor' THEN
	  v_val := 'editor';
	  elsif v_test = '6' OR v_test = 'select' THEN
	  v_val := 'select';
	  elsif v_test = '7' OR v_test = 'radio' THEN
	  v_val := 'radio';
	  elsif v_test = '8' OR v_test = 'checkbox' THEN
	  v_val := 'checkbox';
	  elsif v_test = '9' OR v_test = 'multiselect' THEN
	  v_val := 'multiselect';
	  end if;  :new.field_type := v_val;
	END TNV_USERS_FIELD_TYPE;";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_info (
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (userid)
)";

$sql_create_table[] = "CREATE TABLE " . NV_CONFIG_GLOBALTABLE . " (
	lang VARCHAR2(3 CHAR) DEFAULT 'sys' NOT NULL ENABLE,
	module VARCHAR2(25 CHAR) DEFAULT 'global' NOT NULL ENABLE,
	config_name VARCHAR2(30 CHAR) DEFAULT '' NOT NULL ENABLE,
	config_value  VARCHAR2(4000 CHAR) DEFAULT NULL,
	PRIMARY KEY (lang,module,config_name)
)";

$sql_create_table[] = "CREATE TABLE " . NV_CRONJOBS_GLOBALTABLE . " (
	id NUMBER(8,0) DEFAULT NULL,
	start_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	inter_val NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	run_file VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	run_func VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	params VARCHAR2(255 CHAR) DEFAULT NULL,
	del NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	is_sys NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	last_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	last_result NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (id)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_CRONJOBS_GLOBALTABLE );
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_CRONJOBS_GLOBALTABLE ) . '
  BEFORE INSERT  ON ' . NV_CRONJOBS_GLOBALTABLE . '
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_CRONJOBS_GLOBALTABLE ) . '.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( NV_CRONJOBS_GLOBALTABLE ) . ';';

$sql_create_table[] = "CREATE TABLE " . NV_GROUPS_GLOBALTABLE . " (
	group_id NUMBER(5,0) DEFAULT NULL,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	content VARCHAR2(4000 CHAR) DEFAULT NULL,
	add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	exp_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	publics NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	weight NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	idsite NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	numbers NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	siteus NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (group_id),
	CONSTRAINT unv_groups_ktitle UNIQUE (title,idsite)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_GROUPS_GLOBALTABLE ) . ' MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_GROUPS_GLOBALTABLE ) . '
  BEFORE INSERT  ON ' . NV_GROUPS_GLOBALTABLE . '
  FOR EACH ROW WHEN (new.group_id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_GROUPS_GLOBALTABLE ) . '.nextval INTO :new.group_id FROM DUAL;
	END TNV_' . strtoupper( NV_GROUPS_GLOBALTABLE ) . ';';

$sql_create_table[] = "CREATE TABLE " . NV_GROUPS_GLOBALTABLE . "_users (
	group_id NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	data VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	primary key (group_id,userid)
)";

$sql_create_table[] = "CREATE TABLE " . NV_LANGUAGE_GLOBALTABLE . " (
	id NUMBER(8,0) DEFAULT NULL,
	idfile NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	lang_key VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (id),
	CONSTRAINT unv_language_filelang UNIQUE (idfile,lang_key)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE );
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '
  BEFORE INSERT  ON ' . NV_LANGUAGE_GLOBALTABLE . '
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . ';';

$sql_create_table[] = "CREATE TABLE " . NV_LANGUAGE_GLOBALTABLE . "_file (
	idfile NUMBER(8,0) DEFAULT NULL,
	module VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	admin_file VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	langtype VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (idfile),
	CONSTRAINT unv_language_file_module UNIQUE (module,admin_file)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '_FILE';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '_FILE
  BEFORE INSERT  ON ' . NV_LANGUAGE_GLOBALTABLE . '_file
  FOR EACH ROW WHEN (new.idfile is null)
	BEGIN
	  SELECT SNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '_FILE.nextval INTO :new.idfile FROM DUAL;
	END TNV_' . strtoupper( NV_LANGUAGE_GLOBALTABLE ) . '_FILE;';

$sql_create_table[] = "CREATE TABLE " . NV_SESSIONS_GLOBALTABLE . " (
	session_id VARCHAR2(50 CHAR)DEFAULT '',
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	full_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	onl_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT unv_sessions_session_id UNIQUE (session_id)
)";

$sql_create_table[] = "CREATE INDEX inv_sessions_onl_time ON NV3_SESSIONS(onl_time) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup (
	lang CHAR(2 CHAR) NOT NULL ENABLE,
	module VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	tables VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	version VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	setup_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT unv_setup_lang UNIQUE (lang,module)
)";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup_language (
	lang CHAR(2 CHAR) NOT NULL ENABLE,
	setup NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (lang)
)";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup_modules (
	title VARCHAR2(55 CHAR) NOT NULL ENABLE,
	is_sysmod NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	virtual NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	module_file VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	module_data VARCHAR2(55 CHAR) DEFAULT '' NOT NULL ENABLE,
	mod_version VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	addtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	author VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	note VARCHAR2(255 CHAR) DEFAULT NULL,
	primary key (title)
)";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_click (
	bid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	click_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	click_day NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	click_ip VARCHAR2(15 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_country VARCHAR2(10 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_browse_key VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_browse_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_os_key VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_os_name VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	click_ref VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE
)";
$sql_create_table[] = "CREATE INDEX inv_banners_click_bid ON NV3_BANNERS_CLICK(bid) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_banners_click_click_day ON NV3_BANNERS_CLICK(click_day) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_banners_click_click_ip ON NV3_BANNERS_CLICK(click_ip) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_banners_click_country ON NV3_BANNERS_CLICK(click_country) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_banners_click_browse_key ON NV3_BANNERS_CLICK(click_browse_key) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_banners_click_click_os_key ON NV3_BANNERS_CLICK(click_os_key) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_clients (
	id NUMBER(8,0) DEFAULT NULL,
	login VARCHAR2(60 CHAR) DEFAULT '' NOT NULL ENABLE,
	pass VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	reg_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	full_name VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	email VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	website VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	location VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	yim VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	phone VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	fax VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	mobile VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	check_num VARCHAR2(40 CHAR) DEFAULT '' NOT NULL ENABLE,
	last_login NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	last_ip VARCHAR2(15 CHAR) DEFAULT '' NOT NULL ENABLE,
	last_agent VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	uploadtype VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (id),
	CONSTRAINT unv_banners_clients_login UNIQUE (login),
	CONSTRAINT unv_banners_clients_email UNIQUE (email)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_CLIENTS';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_CLIENTS
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_banners_clients
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_CLIENTS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_CLIENTS;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_plans (
	id NUMBER(5,0) DEFAULT NULL,
	blang CHAR(2 CHAR) DEFAULT NULL,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	description VARCHAR2(255 CHAR) DEFAULT '',
	form VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	width NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	height NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (id)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_PLANS MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_PLANS
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_banners_plans
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_PLANS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_PLANS;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_rows (
	id NUMBER(8,0) DEFAULT NULL,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	pid NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	clid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	file_name VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	file_ext VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	file_mime VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	width NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	height NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	file_alt VARCHAR2(255 CHAR) DEFAULT '',
	imageforswf VARCHAR2(255 CHAR) DEFAULT '',
	click_url VARCHAR2(255 CHAR) DEFAULT '',
	target VARCHAR2(10 CHAR) DEFAULT '_blank' NOT NULL ENABLE,
	add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	publ_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	exp_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	hits_total NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	act NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	weight NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (id)
)";
$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_ROWS MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_ROWS
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_banners_rows
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_ROWS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_BANNERS_ROWS;';

$sql_create_table[] = "CREATE INDEX inv_banners_rows_pid ON NV3_BANNERS_ROWS(pid) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banip (
	id NUMBER(8,0) DEFAULT NULL,
	ip VARCHAR2(32 CHAR)DEFAULT '',
	mask NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	area NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	begintime NUMBER(11,0) DEFAULT 0,
	endtime NUMBER(11,0) DEFAULT 0,
	notice VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	primary key (id),
	CONSTRAINT unv_banip_ip UNIQUE (ip)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_BANIP';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_BANIP
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_banip
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_BANIP.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_BANIP;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_logs (
	id NUMBER(11,0) DEFAULT NULL,
	lang VARCHAR2(10 CHAR) DEFAULT '' NOT NULL ENABLE,
	module_name VARCHAR2(150 CHAR) DEFAULT '' NOT NULL ENABLE,
	name_key VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	note_action VARCHAR2(4000 CHAR) NOT NULL ENABLE,
	link_acess VARCHAR2(255 CHAR) DEFAULT '',
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	log_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (id)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_LOGS';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_LOGS
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_logs
  FOR EACH ROW WHEN (new.id is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_LOGS.nextval INTO :new.id FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_LOGS;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_upload_dir (
	did NUMBER(8,0) DEFAULT NULL,
	dirname VARCHAR2(255 CHAR) DEFAULT NULL,
	time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	thumb_type NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	thumb_width NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	thumb_height NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
	thumb_quality NUMBER(3,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (did),
	CONSTRAINT unv_upload_dir_name UNIQUE (dirname)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_UPLOAD_DIR MINVALUE 10';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_UPLOAD_DIR
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_upload_dir
  FOR EACH ROW WHEN (new.did is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_UPLOAD_DIR.nextval INTO :new.did FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_UPLOAD_DIR;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_upload_file (
	name VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	ext VARCHAR2(10 CHAR) DEFAULT '' NOT NULL ENABLE,
	type VARCHAR2(5 CHAR) DEFAULT '' NOT NULL ENABLE,
	filesize NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	src VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	srcwidth NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	srcheight NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	sizes VARCHAR2(50 CHAR) DEFAULT '' NOT NULL ENABLE,
	userid NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	mtime NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	did NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	alt VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	CONSTRAINT unv_upload_file_did UNIQUE (did,title)
)";

$sql_create_table[] = "CREATE INDEX inv_upload_file_userid ON NV3_UPLOAD_FILE(userid) TABLESPACE USERS";

$sql_create_table[] = "CREATE INDEX inv_upload_file_type ON NV3_UPLOAD_FILE(type) TABLESPACE USERS";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_googleplus (
	gid NUMBER(5,0) DEFAULT NULL,
	title VARCHAR2(255 CHAR) DEFAULT '' NOT NULL ENABLE,
	idprofile VARCHAR2(25 CHAR) DEFAULT '' NOT NULL ENABLE,
	weight NUMBER(8,0) DEFAULT 0 NOT NULL ENABLE,
	add_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	edit_time NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	primary key (gid),
	CONSTRAINT unv_googleplus_idprofile UNIQUE (idprofile)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_GOOGLEPLUS';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_GOOGLEPLUS
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_googleplus
  FOR EACH ROW WHEN (new.gid is null)
	BEGIN
	  SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_GOOGLEPLUS.nextval INTO :new.gid FROM DUAL;
	END TNV_' . strtoupper( $db_config['prefix'] ) . '_GOOGLEPLUS;';

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_plugin (
    pid NUMBER(5,0) DEFAULT NULL,
    plugin_file VARCHAR2(255 CHAR) NOT NULL ENABLE,
    plugin_area NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
    weight NUMBER(5,0) DEFAULT 0 NOT NULL ENABLE,
    primary key (pid),
    CONSTRAINT unv_plugin_file UNIQUE (plugin_file)
)";

$sql_create_table[] = 'create sequence SNV_' . strtoupper( $db_config['prefix'] ) . '_PLUGIN';
$sql_create_table[] = 'CREATE OR REPLACE TRIGGER TNV_' . strtoupper( $db_config['prefix'] ) . '_PLUGIN
  BEFORE INSERT  ON ' . $db_config['prefix'] . '_plugin
  FOR EACH ROW WHEN (new.pid is null)
    BEGIN
      SELECT SNV_' . strtoupper( $db_config['prefix'] ) . '_PLUGIN.nextval INTO :new.pid FROM DUAL;
    END TNV_' . strtoupper( $db_config['prefix'] ) . '_PLUGIN;';

$sql_create_table[] = "CREATE TABLE " . $db_config["prefix"] . "_counter (
	 c_type VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 c_val VARCHAR2(100 CHAR) DEFAULT '' NOT NULL ENABLE,
	 last_update NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 c_count NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 " . NV_LANG_DATA . "_count NUMBER(11,0) DEFAULT 0 NOT NULL ENABLE,
	 CONSTRAINT cnv_counter UNIQUE (c_type,c_val)
)";