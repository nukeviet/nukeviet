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

$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'" );
while( $item = $result->fetch() )
{
	$sql_drop_table[] = 'DROP TABLE ' . $item['name'];
}

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . " (
	admin_id mediumint(8) unsigned NOT NULL,
	editor varchar(100) DEFAULT '',
	lev tinyint(1) unsigned NOT NULL DEFAULT '0',
	files_level varchar(255) DEFAULT '',
	position varchar(255) NOT NULL,
	addtime int(11) NOT NULL DEFAULT '0',
	edittime int(11) NOT NULL DEFAULT '0',
	is_suspend tinyint(1) unsigned NOT NULL DEFAULT '0',
	susp_reason text,
	check_num varchar(40) NOT NULL,
	last_login int(11) unsigned NOT NULL DEFAULT '0',
	last_ip varchar(45) DEFAULT '',
	last_agent varchar(255) DEFAULT '',
	 PRIMARY KEY (admin_id)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . "_config (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	keyname varchar(32) DEFAULT NULL,
	mask tinyint(4) NOT NULL DEFAULT '0',
	begintime int(11) DEFAULT NULL,
	endtime int(11) DEFAULT NULL,
	notice varchar(255) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY keyname (keyname)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_AUTHORS_GLOBALTABLE . "_module (
	mid mediumint(8) NOT NULL AUTO_INCREMENT,
	module varchar(55) NOT NULL,
	lang_key varchar(50) NOT NULL DEFAULT '',
	weight mediumint(8) NOT NULL DEFAULT '0',
	act_1 tinyint(4) NOT NULL DEFAULT '0',
	act_2 tinyint(4) NOT NULL DEFAULT '1',
	act_3 tinyint(4) NOT NULL DEFAULT '1',
	checksum varchar(32) DEFAULT '',
	PRIMARY KEY (mid),
	UNIQUE KEY module (module)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_config (
	config varchar(100) NOT NULL,
	content text,
	edit_time int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (config)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_question (
	qid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL DEFAULT '',
	lang char(2) NOT NULL DEFAULT '',
	weight mediumint(8) unsigned NOT NULL DEFAULT '0',
	add_time int(11) unsigned NOT NULL DEFAULT '0',
	edit_time int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (qid),
	UNIQUE KEY title (title,lang)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . " (
	userid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	username varchar(100) NOT NULL DEFAULT '',
	md5username char(32) NOT NULL DEFAULT '',
	password varchar(50) NOT NULL DEFAULT '',
	email varchar(100) NOT NULL DEFAULT '',
	full_name varchar(255) NOT NULL DEFAULT '',
	gender char(1) DEFAULT '',
	photo varchar(255) DEFAULT '',
	birthday int(11) NOT NULL,
	sig text,
	regdate int(11) NOT NULL DEFAULT '0',
	question varchar(255) NOT NULL,
	answer varchar(255) NOT NULL DEFAULT '',
	passlostkey varchar(50) DEFAULT '',
	view_mail tinyint(1) unsigned NOT NULL DEFAULT '0',
	remember tinyint(1) unsigned NOT NULL DEFAULT '0',
	in_groups varchar(255) DEFAULT '',
	active tinyint(1) unsigned NOT NULL DEFAULT '0',
	checknum varchar(40) DEFAULT '',
	last_login int(11) unsigned NOT NULL DEFAULT '0',
	last_ip varchar(45) DEFAULT '',
	last_agent varchar(255) DEFAULT '',
	last_openid varchar(255) DEFAULT '',
	idsite int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (userid),
	UNIQUE KEY username (username),
	UNIQUE KEY md5username (md5username),
	UNIQUE KEY email (email),
	KEY idsite (idsite)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_reg (
	userid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	username varchar(100) NOT NULL DEFAULT '',
	md5username char(32) NOT NULL DEFAULT '',
	password varchar(50) NOT NULL DEFAULT '',
	email varchar(100) NOT NULL DEFAULT '',
	full_name varchar(255) NOT NULL DEFAULT '',
	regdate int(11) unsigned NOT NULL DEFAULT '0',
	question varchar(255) NOT NULL,
	answer varchar(255) NOT NULL DEFAULT '',
	checknum varchar(50) NOT NULL DEFAULT '',
	users_info text,
	PRIMARY KEY (userid),
	UNIQUE KEY login (username),
	UNIQUE KEY md5username (md5username),
	UNIQUE KEY email (email)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_openid (
	userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	openid varchar(255) NOT NULL DEFAULT '',
	opid varchar(50) NOT NULL DEFAULT '',
	email varchar(100) NOT NULL DEFAULT '',
	PRIMARY KEY (opid),
	KEY userid (userid),
	KEY email (email)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_field (
	fid mediumint(8) NOT NULL AUTO_INCREMENT,
	field varchar(25) NOT NULL,
	weight int(10) unsigned NOT NULL DEFAULT '1',
	field_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
	field_choices text NOT NULL,
	sql_choices text NOT NULL,
	match_type enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
	match_regex varchar(250) NOT NULL DEFAULT '',
	func_callback varchar(75) NOT NULL DEFAULT '',
	min_length int(11) NOT NULL DEFAULT '0',
	max_length bigint(20) unsigned NOT NULL DEFAULT '0',
	required tinyint(3) unsigned NOT NULL DEFAULT '0',
	show_register tinyint(3) unsigned NOT NULL DEFAULT '0',
	user_editable tinyint(3) unsigned NOT NULL DEFAULT '0',
	show_profile tinyint(4) NOT NULL DEFAULT '1',
	class varchar(50) NOT NULL,
	language text NOT NULL,
	default_value varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (fid),
	UNIQUE KEY field (field)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_USERS_GLOBALTABLE . "_info (
	userid mediumint(8) unsigned NOT NULL,
	PRIMARY KEY (userid)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_CONFIG_GLOBALTABLE . " (
	lang varchar(3) NOT NULL DEFAULT 'sys',
	module varchar(25) NOT NULL DEFAULT 'global',
	config_name varchar(30) NOT NULL DEFAULT '',
	config_value text,
	UNIQUE KEY lang (lang,module,config_name)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_CRONJOBS_GLOBALTABLE . " (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	start_time int(11) unsigned NOT NULL DEFAULT '0',
	inter_val int(11) unsigned NOT NULL DEFAULT '0',
	run_file varchar(255) NOT NULL,
	run_func varchar(255) NOT NULL,
	params varchar(255) DEFAULT NULL,
	del tinyint(1) unsigned NOT NULL DEFAULT '0',
	is_sys tinyint(1) unsigned NOT NULL DEFAULT '0',
	act tinyint(1) unsigned NOT NULL DEFAULT '0',
	last_time int(11) unsigned NOT NULL DEFAULT '0',
	last_result tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	KEY is_sys (is_sys)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_GROUPS_GLOBALTABLE . " (
	group_id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL,
	content text,
	add_time int(11) NOT NULL,
	exp_time int(11) NOT NULL,
	publics tinyint(1) unsigned NOT NULL DEFAULT '0',
	weight int(11) unsigned NOT NULL DEFAULT '0',
	act tinyint(1) unsigned NOT NULL,
	idsite int(11) unsigned NOT NULL DEFAULT '0',
	numbers mediumint(9) unsigned NOT NULL DEFAULT '0',
	siteus tinyint(4) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (group_id),
	UNIQUE KEY ktitle (title,idsite),
	KEY exp_time (exp_time)
) ENGINE=MyISAM AUTO_INCREMENT=10";

$sql_create_table[] = "CREATE TABLE " . NV_GROUPS_GLOBALTABLE . "_users (
	group_id smallint(5) unsigned NOT NULL DEFAULT '0',
	userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	data text NOT NULL,
	PRIMARY KEY (group_id,userid)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_LANGUAGE_GLOBALTABLE . " (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	idfile mediumint(8) unsigned NOT NULL DEFAULT '0',
	lang_key varchar(50) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY filelang (idfile,lang_key)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_LANGUAGE_GLOBALTABLE . "_file (
	idfile mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	module varchar(50) NOT NULL,
	admin_file varchar(255) NOT NULL DEFAULT '0',
	langtype varchar(50) NOT NULL,
	PRIMARY KEY (idfile),
	UNIQUE KEY module (module,admin_file)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . NV_SESSIONS_GLOBALTABLE . " (
	session_id varchar(50) DEFAULT NULL,
	userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	full_name varchar(100) NOT NULL,
	onl_time int(11) unsigned NOT NULL DEFAULT '0',
	UNIQUE KEY session_id (session_id),
	KEY onl_time (onl_time)
) ENGINE=MEMORY";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup (
	lang char(2) NOT NULL,
	module varchar(50) NOT NULL,
	tables varchar(255) NOT NULL,
	version varchar(100) NOT NULL,
	setup_time int(11) unsigned NOT NULL DEFAULT '0',
	UNIQUE KEY lang (lang,module)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup_language (
	lang char(2) NOT NULL,
	setup tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (lang)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_setup_modules (
	title varchar(55) NOT NULL,
	is_sysmod tinyint(1) NOT NULL DEFAULT '0',
	virtual tinyint(1) NOT NULL DEFAULT '0',
	module_file varchar(50) NOT NULL DEFAULT '',
	module_data varchar(55) NOT NULL DEFAULT '',
	mod_version varchar(50) NOT NULL,
	addtime int(11) NOT NULL DEFAULT '0',
	author text NOT NULL,
	note varchar(255) DEFAULT '',
	PRIMARY KEY (title)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_click (
	bid mediumint(8) NOT NULL DEFAULT '0',
	click_time int(11) unsigned NOT NULL DEFAULT '0',
	click_day int(2) NOT NULL,
	click_ip varchar(15) NOT NULL,
	click_country varchar(10) NOT NULL,
	click_browse_key varchar(100) NOT NULL,
	click_browse_name varchar(100) NOT NULL,
	click_os_key varchar(100) NOT NULL,
	click_os_name varchar(100) NOT NULL,
	click_ref varchar(255) NOT NULL,
	KEY bid (bid),
	KEY click_day (click_day),
	KEY click_ip (click_ip),
	KEY click_country (click_country),
	KEY click_browse_key (click_browse_key),
	KEY click_os_key (click_os_key)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_clients (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	login varchar(60) NOT NULL,
	pass varchar(50) NOT NULL,
	reg_time int(11) unsigned NOT NULL DEFAULT '0',
	full_name varchar(255) NOT NULL,
	email varchar(100) NOT NULL,
	website varchar(255) NOT NULL,
	location varchar(255) NOT NULL,
	yim varchar(100) NOT NULL,
	phone varchar(100) NOT NULL,
	fax varchar(100) NOT NULL,
	mobile varchar(100) NOT NULL,
	act tinyint(1) unsigned NOT NULL DEFAULT '0',
	check_num varchar(40) NOT NULL,
	last_login int(11) unsigned NOT NULL DEFAULT '0',
	last_ip varchar(15) NOT NULL,
	last_agent varchar(255) NOT NULL,
	uploadtype varchar(255) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY login (login),
	UNIQUE KEY email (email),
	KEY full_name (full_name)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_plans (
	id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	blang char(2) DEFAULT '',
	title varchar(255) NOT NULL,
	description varchar(255) DEFAULT '',
	form varchar(100) NOT NULL,
	width smallint(4) unsigned NOT NULL DEFAULT '0',
	height smallint(4) unsigned NOT NULL DEFAULT '0',
	act tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (id),
	KEY title (title)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banners_rows (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL,
	pid smallint(5) unsigned NOT NULL DEFAULT '0',
	clid mediumint(8) unsigned NOT NULL DEFAULT '0',
	file_name varchar(255) NOT NULL,
	file_ext varchar(100) NOT NULL,
	file_mime varchar(100) NOT NULL,
	width int(4) unsigned NOT NULL DEFAULT '0',
	height int(4) unsigned NOT NULL DEFAULT '0',
	file_alt varchar(255) DEFAULT '',
	imageforswf varchar(255) DEFAULT '',
	click_url varchar(255) DEFAULT '',
	target varchar(10) NOT NULL DEFAULT '_blank',
	add_time int(11) unsigned NOT NULL DEFAULT '0',
	publ_time int(11) unsigned NOT NULL DEFAULT '0',
	exp_time int(11) unsigned NOT NULL DEFAULT '0',
	hits_total mediumint(8) unsigned NOT NULL DEFAULT '0',
	act tinyint(1) unsigned NOT NULL DEFAULT '0',
	weight int(11) NOT NULL default '0',
	PRIMARY KEY (id),
	KEY pid (pid),
	KEY clid (clid)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_banip (
	id mediumint(8) NOT NULL AUTO_INCREMENT,
	ip varchar(32) DEFAULT NULL,
	mask tinyint(4) NOT NULL DEFAULT '0',
	area tinyint(3) NOT NULL,
	begintime int(11) DEFAULT NULL,
	endtime int(11) DEFAULT NULL,
	notice varchar(255) NOT NULL,
	PRIMARY KEY (id),
	UNIQUE KEY ip (ip)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_logs (
	id int(11) NOT NULL AUTO_INCREMENT,
	lang varchar(10) NOT NULL,
	module_name varchar(150) NOT NULL,
	name_key varchar(255) NOT NULL,
	note_action text NOT NULL,
	link_acess varchar(255) DEFAULT '',
	userid mediumint(8) unsigned NOT NULL,
	log_time int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_upload_dir (
	did mediumint(8) NOT NULL AUTO_INCREMENT,
	dirname varchar(255) DEFAULT NULL,
	time int(11) NOT NULL DEFAULT '0',
	thumb_type tinyint(4) NOT NULL DEFAULT '0',
	thumb_width smallint(6) NOT NULL DEFAULT '0',
	thumb_height smallint(6) NOT NULL DEFAULT '0',
	thumb_quality tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (did),
	UNIQUE KEY name (dirname)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_upload_file (
	name varchar(255) NOT NULL,
	ext varchar(10) NOT NULL DEFAULT '',
	type varchar(5) NOT NULL DEFAULT '',
	filesize int(11) NOT NULL DEFAULT '0',
	src varchar(255) NOT NULL DEFAULT '',
	srcwidth int(11) NOT NULL DEFAULT '0',
	srcheight int(11) NOT NULL DEFAULT '0',
	sizes varchar(50) NOT NULL DEFAULT '',
	userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	mtime int(11) NOT NULL DEFAULT '0',
	did int(11) NOT NULL DEFAULT '0',
	title varchar(255) NOT NULL DEFAULT '',
	alt varchar(255) NOT NULL DEFAULT '',
	UNIQUE KEY did (did,title),
	KEY userid (userid),
	KEY type (type)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_googleplus (
	gid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	title varchar(255) NOT NULL DEFAULT '',
	idprofile varchar(25) NOT NULL DEFAULT '',
	weight mediumint(8) unsigned NOT NULL DEFAULT '0',
	add_time int(11) unsigned NOT NULL DEFAULT '0',
	edit_time int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (gid),
	UNIQUE KEY idprofile (idprofile)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_plugin (
  pid tinyint(4) NOT NULL AUTO_INCREMENT,
  plugin_file varchar(50) NOT NULL,
  plugin_area tinyint(4) NOT NULL,
  weight tinyint(4) NOT NULL,
  PRIMARY KEY (pid),
  UNIQUE KEY plugin_file (plugin_file)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_counter (
	 c_type varchar(100) NOT NULL,
	 c_val varchar(100) NOT NULL,
	 last_update int(11) NOT NULL DEFAULT '0',
	 c_count int(11) unsigned NOT NULL DEFAULT '0',
	 " . NV_LANG_DATA . "_count int(11) unsigned NOT NULL DEFAULT '0',
	 UNIQUE KEY c_type (c_type,c_val)
) ENGINE=MyISAM";