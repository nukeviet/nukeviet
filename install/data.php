<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @createdate 12/28/2009 20:8
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
// Ten cac table cua CSDL dung chung cho he thong
define( 'NV_AUTHORS_GLOBALTABLE', $db_config['prefix'] . '_authors' );
define( 'NV_USERS_GLOBALTABLE', $db_config['prefix'] . '_users' );
define( 'NV_CONFIG_GLOBALTABLE', $db_config['prefix'] . '_config' );
define( 'NV_GROUPS_GLOBALTABLE', $db_config['prefix'] . '_groups' );
define( 'NV_LANGUAGE_GLOBALTABLE', $db_config['prefix'] . '_language' );
define( 'NV_SESSIONS_GLOBALTABLE', $db_config['prefix'] . '_sessions' );
define( 'NV_CRONJOBS_GLOBALTABLE', $db_config['prefix'] . '_cronjobs' );

$sql_create_table[] = "CREATE TABLE `" . NV_AUTHORS_GLOBALTABLE . "` (
	`admin_id` mediumint(8) unsigned NOT NULL,
	`editor` varchar(100) NOT NULL,
	`lev` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`files_level` varchar(255) NOT NULL,
	`position` varchar(255) NOT NULL,
	`addtime` int(11) NOT NULL DEFAULT '0',
	`edittime` int(11) NOT NULL DEFAULT '0',
	`is_suspend` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`susp_reason` mediumtext NOT NULL,
	`check_num` varchar(40) NOT NULL,
	`last_login` int(11) unsigned NOT NULL DEFAULT '0',
	`last_ip` varchar(45) NOT NULL,
	`last_agent` varchar(255) NOT NULL,
	 PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_AUTHORS_GLOBALTABLE . "_config` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`keyname` varchar(32) DEFAULT NULL,
	`mask` tinyint(4) NOT NULL DEFAULT '0',
	`begintime` int(11) DEFAULT NULL,
	`endtime` int(11) DEFAULT NULL,
	`notice` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `keyname` (`keyname`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_AUTHORS_GLOBALTABLE . "_module` (
	`mid` int(11) NOT NULL AUTO_INCREMENT,
	`module` varchar(55) NOT NULL,
	`lang_key` varchar(50) NOT NULL DEFAULT '',
	`weight` int(11) NOT NULL DEFAULT '0',
	`act_1` tinyint(4) NOT NULL DEFAULT '0',
	`act_2` tinyint(4) NOT NULL DEFAULT '1',
	`act_3` tinyint(4) NOT NULL DEFAULT '1',
	`checksum` varchar(32) NOT NULL DEFAULT '',
	PRIMARY KEY (`mid`),
	UNIQUE KEY `module` (`module`)
) ENGINE=MyISAM";

$sql_create_table[] = "INSERT INTO `" . NV_AUTHORS_GLOBALTABLE . "_module`
	(`mid`, `module`, `lang_key`, `weight`, `act_1`, `act_2`, `act_3`, `checksum`) VALUES
	(1, 'siteinfo', 'mod_siteinfo', 1, 1, 1, 1, ''),
	(2, 'authors', 'mod_authors', 2, 1, 1, 1, ''),
	(3, 'settings', 'mod_settings', 3, 1, 1, 0, ''),
	(4, 'database', 'mod_database', 4, 1, 0, 0, ''),
	(5, 'webtools', 'mod_webtools', 5, 1, 0, 0, ''),
	(6, 'seotools', 'mod_seotools', 6, 1, 0, 0, ''),
	(7, 'language', 'mod_language', 7, 1, 1, 0, ''),
	(8, 'modules', 'mod_modules', 8, 1, 1, 0, ''),
	(9, 'themes', 'mod_themes', 9, 1, 1, 0, ''),
	(10, 'upload', 'mod_upload', 10, 1, 1, 1, '')";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_config` (
	`config` varchar(100) NOT NULL,
	`content` mediumtext NOT NULL,
	`edit_time` int(11) unsigned NOT NULL DEFAULT '0',
	 PRIMARY KEY (`config`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_question` (
	`qid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`lang` char(2) NOT NULL DEFAULT '',
	`weight` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`add_time` int(11) unsigned NOT NULL DEFAULT '0',
	`edit_time` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`qid`),
	UNIQUE KEY `title` (`title`,`lang`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "` (
	`userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(100) NOT NULL DEFAULT '',
	`md5username` char(32) NOT NULL DEFAULT '',
	`password` varchar(50) NOT NULL DEFAULT '',
	`email` varchar(100) NOT NULL DEFAULT '',
	`full_name` varchar(255) NOT NULL DEFAULT '',
	`gender` char(1) NOT NULL,
	`photo` varchar(255) NOT NULL DEFAULT '',
	`birthday` int(11) NOT NULL,
	`sig` text,
	`regdate` int(11) NOT NULL DEFAULT '0',
	`question` varchar(255) NOT NULL,
	`answer` varchar(255) NOT NULL DEFAULT '',
	`passlostkey` varchar(50) NOT NULL DEFAULT '',
	`view_mail` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`remember` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`in_groups` varchar(255) NOT NULL DEFAULT '',
	`active` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`checknum` varchar(40) NOT NULL DEFAULT '',
	`last_login` int(11) unsigned NOT NULL DEFAULT '0',
	`last_ip` varchar(45) NOT NULL DEFAULT '',
	`last_agent` varchar(255) NOT NULL DEFAULT '',
	`last_openid` varchar(255) NOT NULL DEFAULT '',
	`idsite` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`userid`),
	UNIQUE KEY `username` (`username`),
	UNIQUE KEY `md5username` (`md5username`),
	UNIQUE KEY `email` (`email`),
	KEY `idsite` (`idsite`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_reg` (
	`userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(100) NOT NULL DEFAULT '',
	`md5username` char(32) NOT NULL DEFAULT '',
	`password` varchar(50) NOT NULL DEFAULT '',
	`email` varchar(100) NOT NULL DEFAULT '',
	`full_name` varchar(255) NOT NULL DEFAULT '',
	`regdate` int(11) unsigned NOT NULL DEFAULT '0',
	`question` varchar(255) NOT NULL,
	`answer` varchar(255) NOT NULL DEFAULT '',
	`checknum` varchar(50) NOT NULL DEFAULT '',
	`users_info` mediumtext NOT NULL,
	PRIMARY KEY (`userid`),
	UNIQUE KEY `login` (`username`),
	UNIQUE KEY `md5username` (`md5username`),
	UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_openid` (
	`userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`openid` varchar(255) NOT NULL DEFAULT '',
	`opid` varchar(50) NOT NULL DEFAULT '',
	`email` varchar(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`opid`),
	KEY `userid` (`userid`),
	KEY `email` (`email`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_field` (
	`fid` int(11) NOT NULL AUTO_INCREMENT,
	`field` varchar(25) NOT NULL,
	`weight` int(10) unsigned NOT NULL DEFAULT '1',
	`field_type` enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect') NOT NULL DEFAULT 'textbox',
	`field_choices` mediumtext NOT NULL,
	`sql_choices` text NOT NULL,
	`match_type` enum('none','alphanumeric','email','url','regex','callback') NOT NULL DEFAULT 'none',
	`match_regex` varchar(250) NOT NULL DEFAULT '',
	`func_callback` varchar(75) NOT NULL DEFAULT '',
	`min_length` int(11) NOT NULL DEFAULT '0',
	`max_length` bigint(20) unsigned NOT NULL DEFAULT '0',
	`required` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`show_register` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`user_editable` enum('yes','once','never') NOT NULL DEFAULT 'yes',
	`show_profile` tinyint(4) NOT NULL DEFAULT '1',
	`class` varchar(50) NOT NULL,
	`language` text NOT NULL,
	`default_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`fid`),
	UNIQUE KEY `field` (`field`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_USERS_GLOBALTABLE . "_info` (
	`userid` mediumint(8) unsigned NOT NULL,
	PRIMARY KEY (`userid`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_CONFIG_GLOBALTABLE . "` (
	`lang` char(3) NOT NULL DEFAULT 'sys',
	`module` varchar(25) NOT NULL DEFAULT 'global',
	`config_name` varchar(30) NOT NULL DEFAULT '',
	`config_value` mediumtext NOT NULL,
	UNIQUE KEY `lang` (`lang`,`module`,`config_name`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_CRONJOBS_GLOBALTABLE . "` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`start_time` int(11) unsigned NOT NULL DEFAULT '0',
	`interval` int(11) unsigned NOT NULL DEFAULT '0',
	`run_file` varchar(255) NOT NULL,
	`run_func` varchar(255) NOT NULL,
	`params` varchar(255) NOT NULL,
	`del` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`is_sys` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`act` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_time` int(11) unsigned NOT NULL DEFAULT '0',
	`last_result` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `is_sys` (`is_sys`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_GROUPS_GLOBALTABLE . "` (
	`group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`content` mediumtext NOT NULL,
	`add_time` int(11) NOT NULL,
	`exp_time` int(11) NOT NULL,
	`public` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`weight` int(11) unsigned NOT NULL DEFAULT '0',
	`act` tinyint(1) unsigned NOT NULL,
	`idsite` int(11) unsigned NOT NULL DEFAULT '0',
	`number` mediumint(9) unsigned NOT NULL DEFAULT '0',
	`siteus` tinyint(4) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`group_id`),
	UNIQUE KEY `ktitle` (`title`,`idsite`),
	KEY `exp_time` (`exp_time`)
) ENGINE=MyISAM AUTO_INCREMENT=10";

$sql_create_table[] = "INSERT INTO `" . NV_GROUPS_GLOBALTABLE . "`
(`group_id`, `title`, `content`, `add_time`, `exp_time`, `public`, `weight`, `act`, `idsite`, `number`, `siteus`) VALUES
(1, 'Super admin', '', " . NV_CURRENTTIME . ", 0, 0, 1, 1, 0, 1, 0),
(2, 'General admin', '', " . NV_CURRENTTIME . ", 0, 0, 2, 1, 0, 0, 0),
(3, 'Module admin', '', " . NV_CURRENTTIME . ", 0, 0, 3, 1, 0, 2, 0)";

$sql_create_table[] = "CREATE TABLE `" . NV_GROUPS_GLOBALTABLE . "_users` (
	`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`userid` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`data` text NOT NULL,
	PRIMARY KEY (`group_id`,`userid`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_LANGUAGE_GLOBALTABLE . "` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`idfile` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`lang_key` varchar(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `filelang` (`idfile`,`lang_key`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_LANGUAGE_GLOBALTABLE . "_file` (
	`idfile` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`module` varchar(50) NOT NULL,
	`admin_file` varchar(255) NOT NULL DEFAULT '0',
	`langtype` varchar(50) NOT NULL,
	PRIMARY KEY (`idfile`),
	UNIQUE KEY `module` (`module`,`admin_file`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . NV_SESSIONS_GLOBALTABLE . "` (
	`session_id` varchar(50) DEFAULT NULL,
	`uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`full_name` varchar(100) NOT NULL,
	`onl_time` int(11) unsigned NOT NULL DEFAULT '0',
	UNIQUE KEY `session_id` (`session_id`),
	KEY `onl_time` (`onl_time`)
) ENGINE=MEMORY";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_setup` (
	`lang` char(2) NOT NULL,
	`module` varchar(50) NOT NULL,
	`tables` varchar(255) NOT NULL,
	`version` varchar(100) NOT NULL,
	`setup_time` int(11) unsigned NOT NULL DEFAULT '0',
	UNIQUE KEY `lang` (`lang`,`module`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_setup_language` (
	`lang` char(2) NOT NULL,
	`setup` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`lang`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_setup_modules` (
	`title` varchar(55) NOT NULL,
	`is_sysmod` tinyint(1) NOT NULL DEFAULT '0',
	`virtual` tinyint(1) NOT NULL DEFAULT '0',
	`module_file` varchar(50) NOT NULL DEFAULT '',
	`module_data` varchar(55) NOT NULL DEFAULT '',
	`mod_version` varchar(50) NOT NULL,
	`addtime` int(11) NOT NULL DEFAULT '0',
	`author` text NOT NULL,
	`note` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`title`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_banners_click` (
	`bid` mediumint(8) NOT NULL DEFAULT '0',
	`click_time` int(11) unsigned NOT NULL DEFAULT '0',
	`click_day` int(2) NOT NULL,
	`click_ip` varchar(15) NOT NULL,
	`click_country` varchar(10) NOT NULL,
	`click_browse_key` varchar(100) NOT NULL,
	`click_browse_name` varchar(100) NOT NULL,
	`click_os_key` varchar(100) NOT NULL,
	`click_os_name` varchar(100) NOT NULL,
	`click_ref` varchar(255) NOT NULL,
	KEY `bid` (`bid`),
	KEY `click_day` (`click_day`),
	KEY `click_ip` (`click_ip`),
	KEY `click_country` (`click_country`),
	KEY `click_browse_key` (`click_browse_key`),
	KEY `click_os_key` (`click_os_key`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_banners_clients` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`login` varchar(60) NOT NULL,
	`pass` varchar(50) NOT NULL,
	`reg_time` int(11) unsigned NOT NULL DEFAULT '0',
	`full_name` varchar(255) NOT NULL,
	`email` varchar(100) NOT NULL,
	`website` varchar(255) NOT NULL,
	`location` varchar(255) NOT NULL,
	`yim` varchar(100) NOT NULL,
	`phone` varchar(100) NOT NULL,
	`fax` varchar(100) NOT NULL,
	`mobile` varchar(100) NOT NULL,
	`act` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`check_num` varchar(40) NOT NULL,
	`last_login` int(11) unsigned NOT NULL DEFAULT '0',
	`last_ip` varchar(15) NOT NULL,
	`last_agent` varchar(255) NOT NULL,
	`uploadtype` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `login` (`login`),
	UNIQUE KEY `email` (`email`),
	KEY `full_name` (`full_name`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_banners_plans` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`blang` char(2) NOT NULL,
	`title` varchar(255) NOT NULL,
	`description` varchar(255) NOT NULL,
	`form` varchar(100) NOT NULL,
	`width` smallint(4) unsigned NOT NULL DEFAULT '0',
	`height` smallint(4) unsigned NOT NULL DEFAULT '0',
	`act` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `title` (`title`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_banners_rows` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`pid` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`clid` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`file_name` varchar(255) NOT NULL,
	`file_ext` varchar(100) NOT NULL,
	`file_mime` varchar(100) NOT NULL,
	`width` int(4) unsigned NOT NULL DEFAULT '0',
	`height` int(4) unsigned NOT NULL DEFAULT '0',
	`file_alt` varchar(255) NOT NULL,
	`imageforswf` varchar(255) NOT NULL,
	`click_url` varchar(255) NOT NULL,
	`target` varchar(10) NOT NULL DEFAULT '_blank',
	`add_time` int(11) unsigned NOT NULL DEFAULT '0',
	`publ_time` int(11) unsigned NOT NULL DEFAULT '0',
	`exp_time` int(11) unsigned NOT NULL DEFAULT '0',
	`hits_total` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`act` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`weight` int(11) NOT NULL default '0',
	PRIMARY KEY (`id`),
	KEY `pid` (`pid`),
	KEY `clid` (`clid`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_banip` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ip` varchar(32) DEFAULT NULL,
	`mask` tinyint(4) NOT NULL DEFAULT '0',
	`area` tinyint(3) NOT NULL,
	`begintime` int(11) DEFAULT NULL,
	`endtime` int(11) DEFAULT NULL,
	`notice` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_logs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`lang` varchar(10) NOT NULL,
	`module_name` varchar(150) NOT NULL,
	`name_key` varchar(255) NOT NULL,
	`note_action` text NOT NULL,
	`link_acess` varchar(255) NOT NULL,
	`userid` int(11) NOT NULL,
	`log_time` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_ipcountry` (
	`ip_from` int(11) unsigned NOT NULL,
	`ip_to` int(11) unsigned NOT NULL,
	`country` char(2) NOT NULL,
	`ip_file` smallint(5) unsigned NOT NULL,
	`time` int(11) NOT NULL DEFAULT '0',
	UNIQUE KEY `ip_from` (`ip_from`,`ip_to`),
	KEY `ip_file` (`ip_file`),
	KEY `country` (`country`),
	KEY `time` (`time`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_upload_dir` (
	`did` int(11) NOT NULL AUTO_INCREMENT,
	`dirname` varchar(255) NOT NULL,
	`time` int(11) NOT NULL DEFAULT '0',
	`thumb_type` tinyint(4) NOT NULL DEFAULT '0',
	`thumb_width` smallint(6) NOT NULL DEFAULT '0',
	`thumb_height` smallint(6) NOT NULL DEFAULT '0',
	`thumb_quality` tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`did`),
	UNIQUE KEY `name` (`dirname`)
) ENGINE=MyISAM";
$sql_create_table[] = "INSERT INTO `" . $db_config['prefix'] . "_upload_dir` (`did`, `dirname`, `time`, `thumb_type`, `thumb_width`, `thumb_height`, `thumb_quality`) VALUES ('-1', '', 0, 3, 100, 150, 90)";
$sql_create_table[] = "UPDATE `" . $db_config['prefix'] . "_upload_dir` SET `did` = '0' WHERE `did` = '-1'";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_upload_file` (
	`name` varchar(255) NOT NULL,
	`ext` varchar(10) NOT NULL DEFAULT '',
	`type` varchar(5) NOT NULL DEFAULT '',
	`filesize` int(11) NOT NULL DEFAULT '0',
	`src` varchar(255) NOT NULL DEFAULT '',
	`srcwidth` int(11) NOT NULL DEFAULT '0',
	`srcheight` int(11) NOT NULL DEFAULT '0',
	`size` varchar(50) NOT NULL DEFAULT '',
	`userid` int(11) NOT NULL DEFAULT '0',
	`mtime` int(11) NOT NULL DEFAULT '0',
	`did` int(11) NOT NULL DEFAULT '0',
	`title` varchar(255) NOT NULL DEFAULT '',
	UNIQUE KEY `did` (`did`,`title`),
	KEY `userid` (`userid`),
	KEY `type` (`type`)
) ENGINE=MyISAM";

$sql_create_table[] = "CREATE TABLE `" . $db_config['prefix'] . "_googleplus` (
	`gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`idprofile` varchar(25) NOT NULL DEFAULT '',
	`weight` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`add_time` int(11) unsigned NOT NULL DEFAULT '0',
	`edit_time` int(11) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`gid`),
	UNIQUE KEY `idprofile` (`idprofile`)
) ENGINE=MyISAM";


$sql_create_table[] = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_config` (`config`, `content`, `edit_time`) VALUES
	('access_admin', 'a:6:{s:12:\"access_addus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:14:\"access_waiting\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_editus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_delus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_passus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_groups\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}}', 1352873462),
	('deny_email', 'yoursite.com|mysite.com|localhost|xxx', " . NV_CURRENTTIME . "),
	('deny_name', 'anonimo|anonymous|god|linux|nobody|operator|root', " . NV_CURRENTTIME . ")";

$sql_create_table[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES
	('sys', 'site', 'closed_site', '0'),
	('sys', 'site', 'admin_theme', 'admin_default'),
	('sys', 'site', 'date_pattern', 'l, d/m/Y'),
	('sys', 'site', 'time_pattern', 'H:i'),
	('sys', 'site', 'online_upd', '1'),
	('sys', 'site', 'statistic', '1'),
	('sys', 'site', 'mailer_mode', ''),
	('sys', 'site', 'smtp_host', 'smtp.gmail.com'),
	('sys', 'site', 'smtp_ssl', '1'),
	('sys', 'site', 'smtp_port', '465'),
	('sys', 'site', 'smtp_username', 'user@gmail.com'),
	('sys', 'site', 'smtp_password', ''),
	('sys', 'site', 'googleAnalyticsID', ''),
	('sys', 'site', 'googleAnalyticsSetDomainName', '0'),
	('sys', 'site', 'searchEngineUniqueID', ''),
	('sys', 'global', 'site_keywords', 'NukeViet, portal, mysql, php'),
	('sys', 'global', 'site_phone', ''),
	('sys', 'global', 'site_lang', '" . NV_LANG_DATA . "'),
	('sys', 'global', 'block_admin_ip', '0'),
	('sys', 'global', 'admfirewall', '0'),
	('sys', 'global', 'dump_autobackup', '1'),
	('sys', 'global', 'dump_backup_ext', 'gz'),
	('sys', 'global', 'dump_backup_day', '30'),
	('sys', 'global', 'gfx_chk', '" . $global_config['gfx_chk'] . "'),
	('sys', 'global', 'file_allowed_ext', 'adobe,archives,audio,documents,flash,images,real,video'),
	('sys', 'global', 'forbid_extensions', 'php,php3,php4,php5,phtml,inc'),
	('sys', 'global', 'forbid_mimes', ''),
	('sys', 'global', 'nv_max_size', '" . min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) ) . "'),
	('sys', 'global', 'upload_checking_mode', 'strong'),
	('sys', 'global', 'str_referer_blocker', '0'),
	('sys', 'global', 'allowuserreg', '1'),
	('sys', 'global', 'allowuserlogin', '1'),
	('sys', 'global', 'allowloginchange', '0'),
	('sys', 'global', 'allowquestion', '1'),
	('sys', 'global', 'allowuserpublic', '0'),
	('sys', 'global', 'useactivate', '2'),
	('sys', 'global', 'allowmailchange', '1'),
	('sys', 'global', 'allow_sitelangs', '" . NV_LANG_DATA . "'),
	('sys', 'global', 'allow_adminlangs', '" . implode( ",", $languageslist ) . "'),
	('sys', 'global', 'read_type', '0'),
	('sys', 'global', 'is_url_rewrite', '" . $global_config['is_url_rewrite'] . "'),
	('sys', 'global', 'rewrite_optional', '" . $global_config['rewrite_optional'] . "'),
	('sys', 'global', 'rewrite_endurl', '" . $global_config['rewrite_endurl'] . "'),
	('sys', 'global', 'rewrite_exturl', '" . $global_config['rewrite_exturl'] . "'),
	('sys', 'global', 'rewrite_op_mod', ''),
	('sys', 'global', 'autocheckupdate', '1'),
	('sys', 'global', 'autoupdatetime', '24'),
	('sys', 'global', 'gzip_method', '" . $global_config['gzip_method'] . "'),
	('sys', 'global', 'is_user_forum', '0'),
	('sys', 'global', 'openid_mode', '1'),
	('sys', 'global', 'authors_detail_main', '0'),
	('sys', 'global', 'spadmin_add_admin', '1'),
	('sys', 'global', 'openid_servers', 'yahoo,google,myopenid'),
	('sys', 'global', 'optActive', '1'),
	('sys', 'global', 'timestamp', '1'),
	('sys', 'global', 'mudim_displaymode', '1'),
	('sys', 'global', 'mudim_method', '4'),
	('sys', 'global', 'mudim_showpanel', '1'),
	('sys', 'global', 'mudim_active', '1'),
	('sys', 'global', 'captcha_type', '0'),
	('sys', 'global', 'version', '" . $global_config['version'] . "'),
	('sys', 'global', 'whoviewuser', '2'),
	('sys', 'global', 'facebook_client_id', ''),
	('sys', 'global', 'facebook_client_secret', ''),
	('sys', 'global', 'cookie_httponly', '" . $global_config['cookie_httponly'] . "'),
	('sys', 'global', 'admin_check_pass_time', '1800'),
	('sys', 'global', 'adminrelogin_max', '3'),
	('sys', 'global', 'cookie_secure', '" . $global_config['cookie_secure'] . "'),
	('sys', 'global', 'nv_unick_type', '" . $global_config['nv_unick_type'] . "'),
	('sys', 'global', 'nv_upass_type', '" . $global_config['nv_upass_type'] . "'),
	('sys', 'global', 'is_flood_blocker', '1'),
	('sys', 'global', 'max_requests_60', '40'),
	('sys', 'global', 'max_requests_300', '150'),
	('sys', 'global', 'nv_display_errors_list', '1'),
	('sys', 'global', 'display_errors_list', '1'),
	('sys', 'global', 'nv_auto_resize', '1'),
	('sys', 'global', 'dump_interval', '1'),
	('sys', 'define', 'nv_unickmin', '" . NV_UNICKMIN . "'),
	('sys', 'define', 'nv_unickmax', '" . NV_UNICKMAX . "'),
	('sys', 'define', 'nv_upassmin', '" . NV_UPASSMIN . "'),
	('sys', 'define', 'nv_upassmax', '" . NV_UPASSMAX . "'),
	('sys', 'define', 'nv_gfx_num', '6'),
	('sys', 'define', 'nv_gfx_width', '120'),
	('sys', 'define', 'nv_gfx_height', '25'),
	('sys', 'define', 'nv_max_width', '1500'),
	('sys', 'define', 'nv_max_height', '1500'),
	('sys', 'define', 'cdn_url', ''),
	('sys', 'define', 'nv_live_cookie_time', '" . NV_LIVE_COOKIE_TIME . "'),
	('sys', 'define', 'nv_live_session_time', '0'),
	('sys', 'define', 'nv_anti_iframe', '" . NV_ANTI_IFRAME . "'),
	('sys', 'define', 'nv_allowed_html_tags', '" . NV_ALLOWED_HTML_TAGS . "'),
	('sys', 'define', 'dir_forum', '')";

$sql_create_table[] = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` (`id`, `start_time`, `interval`, `run_file`, `run_func`, `params`, `del`, `is_sys`, `act`, `last_time`, `last_result`) VALUES
	(NULL, " . NV_CURRENTTIME . ", 5, 'online_expired_del.php', 'cron_online_expired_del', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 1440, 'dump_autobackup.php', 'cron_dump_autobackup', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 60, 'temp_download_destroy.php', 'cron_auto_del_temp_download', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 30, 'ip_logs_destroy.php', 'cron_del_ip_logs', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 1440, 'error_log_destroy.php', 'cron_auto_del_error_log', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 360, 'error_log_sendmail.php', 'cron_auto_sendmail_error_log', '', 0, 1, 0, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 60, 'ref_expired_del.php', 'cron_ref_expired_del', '', 0, 1, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 1440, 'siteDiagnostic_update.php', 'cron_siteDiagnostic_update', '', 0, 0, 1, 0, 0),
	(NULL, " . NV_CURRENTTIME . ", 60, 'check_version.php', 'cron_auto_check_version', '', 0, 1, 1, 0, 0)";

$sql_create_table[] = "INSERT INTO `" . $db_config['prefix'] . "_setup_modules` (`title`, `is_sysmod`, `virtual`, `module_file`, `module_data`, `mod_version`, `addtime`, `author`, `note`) VALUES
	('about', 0, 1, 'about', 'about', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('banners', 1, 0, 'banners', 'banners', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('contact', 0, 1, 'contact', 'contact', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('news', 0, 1, 'news', 'news', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('voting', 0, 0, 'voting', 'voting', '3.1.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('forum', 0, 0, 'forum', 'forum', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('search', 1, 0, 'search', 'search', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('users', 1, 0, 'users', 'users', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('download', 0, 1, 'download', 'download', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('weblinks', 0, 1, 'weblinks', 'weblinks', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('statistics', 0, 0, 'statistics', 'statistics', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('faq', 0, 1, 'faq', 'faq', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('menu', 0, 1, 'menu', 'menu', '3.1.00 1273225635', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', ''),
	('rss', 1, 0, 'rss', 'rss', '3.0.01 1287532800', " . NV_CURRENTTIME . ", 'VINADES (contact@vinades.vn)', '')";

$sql_create_table[] = "INSERT INTO `" . $db_config['prefix'] . "_banners_plans` VALUES
	(1, '', 'Quang cao giua trang', '', 'sequential', 510, 100, 1),
	(2, '', 'Quang cao trai', '', 'sequential', 190, 500, 1)";

$sql_create_table[] = "INSERT INTO `" . $db_config['prefix'] . "_banners_rows` VALUES
	(1, 'Bo ngoai giao', 2, 0, 'bongoaigiao.jpg', 'jpg', 'image/jpeg', 160, 54, '', '', 'http://www.mofa.gov.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1),
	(2, 'vinades', 2, 0, 'vinades.jpg', 'jpg', 'image/jpeg', 190, 454, '', '', 'http://vinades.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,2),
	(3, 'Quang cao giua trang', 1, 0, 'webnhanh_vn.gif', 'gif', 'image/gif', 510, 65, '', '', 'http://webnhanh.vn', '_blank', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1)";

?>