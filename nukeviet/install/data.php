<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/28/2009 20:8
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

//Ten cac table cua CSDL dung chung cho he thong
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
  `website` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL,
  `yim` varchar(100) NOT NULL DEFAULT '',
  `telephone` varchar(100) NOT NULL DEFAULT '',
  `fax` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(100) NOT NULL DEFAULT '',
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL DEFAULT '',
  `passlostkey` varchar(40) NOT NULL DEFAULT '',
  `view_mail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remember` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `in_groups` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `checknum` varchar(40) NOT NULL DEFAULT '',
  `last_login` int(11) unsigned NOT NULL DEFAULT '0',
  `last_ip` varchar(45) NOT NULL DEFAULT '',
  `last_agent` varchar(255) NOT NULL DEFAULT '',
  `last_openid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `md5username` (`md5username`),
  UNIQUE KEY `email` (`email`)
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
  `users` mediumtext NOT NULL,
  `public` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` int(11) unsigned NOT NULL DEFAULT '0',
  `act` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `title` (`title`),
  KEY `exp_time` (`exp_time`)
) ENGINE=MyISAM AUTO_INCREMENT=10";

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
  `click_url` varchar(255) NOT NULL,
  `file_name_tmp` varchar(255) NOT NULL,
  `file_alt_tmp` varchar(255) NOT NULL,
  `click_url_tmp` varchar(255) NOT NULL,
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

$sql_create_table[] = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_config` (`config`, `content`, `edit_time`) VALUES
        ('registertype', '1', " . NV_CURRENTTIME . "),
        ('deny_email', 'yoursite.com|mysite.com|localhost|xxx', " . NV_CURRENTTIME . "),
        ('deny_name', 'anonimo|anonymous|god|linux|nobody|operator|root', " . NV_CURRENTTIME . ")";

$sql_create_table[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES
('sys', 'global', 'closed_site', '0'),
('sys', 'global', 'site_keywords', 'NukeViet, portal, mysql, php'),
('sys', 'global', 'site_phone', ''),
('sys', 'global', 'site_lang', '" . NV_LANG_DATA . "'),
('sys', 'global', 'admin_theme', 'admin_full'),
('sys', 'global', 'date_pattern', 'l, d-m-Y'),
('sys', 'global', 'time_pattern', 'H:i'),
('sys', 'global', 'block_admin_ip', '0'),
('sys', 'global', 'admfirewall', '0'),
('sys', 'global', 'online_upd', '1'),
('sys', 'global', 'statistic', '1'),
('sys', 'global', 'dump_autobackup', '1'),
('sys', 'global', 'dump_backup_ext', 'gz'),
('sys', 'global', 'dump_backup_day', '30'),
('sys', 'global', 'gfx_chk', '" . $global_config['gfx_chk'] . "'),
('sys', 'global', 'file_allowed_ext', 'adobe,archives,audio,documents,flash,images,real,video'),
('sys', 'global', 'forbid_extensions', 'php'),
('sys', 'global', 'forbid_mimes', ''),
('sys', 'global', 'nv_max_size', '" . min( nv_converttoBytes( ini_get( 'upload_max_filesize' ) ), nv_converttoBytes( ini_get( 'post_max_size' ) ) ) . "'),
('sys', 'global', 'upload_checking_mode', 'strong'),
('sys', 'global', 'upload_logo', 'images/logo.png'),
('sys', 'global', 'str_referer_blocker', '0'),
('sys', 'global', 'getloadavg', '0'),
('sys', 'global', 'mailer_mode', ''),
('sys', 'global', 'smtp_host', 'smtp.gmail.com'),
('sys', 'global', 'smtp_ssl', '1'),
('sys', 'global', 'smtp_port', '465'),
('sys', 'global', 'smtp_username', 'user@gmail.com'),
('sys', 'global', 'smtp_password', 'userpass'),
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
('sys', 'global', 'autocheckupdate', '1'),
('sys', 'global', 'autologomod', ''),
('sys', 'global', 'autologosize1', '50'),
('sys', 'global', 'autologosize2', '40'),
('sys', 'global', 'autologosize3', '30'),
('sys', 'global', 'autoupdatetime', '24'),
('sys', 'global', 'gzip_method', '" . $global_config['gzip_method'] . "'),
('sys', 'global', 'is_user_forum', '0'),
('sys', 'global', 'openid_mode', '1'),
('sys', 'global', 'authors_detail_main', '0'),
('sys', 'global', 'spadmin_add_admin', '1'),
('sys', 'global', 'openid_servers', 'yahoo,google,myopenid'),
('sys', 'global', 'optActive', '1'),
('sys', 'global', 'timestamp', '1'),
('sys', 'global', 'googleAnalyticsID', ''),
('sys', 'global', 'googleAnalyticsSetDomainName', '0'),
('sys', 'global', 'searchEngineUniqueID', ''),
('sys', 'global', 'captcha_type', '0'),
('sys', 'global', 'revision', '" . $global_config['revision'] . "'),
('sys', 'global', 'version', '" . $global_config['version'] . "'),
('sys', 'global', 'whoviewuser', '2')";

$sql_create_table[] = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` (`id`, `start_time`, `interval`, `run_file`, `run_func`, `params`, `del`, `is_sys`, `act`, `last_time`, `last_result`) VALUES
(NULL, " . NV_CURRENTTIME . ", 5, 'online_expired_del.php', 'cron_online_expired_del', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 1440, 'dump_autobackup.php', 'cron_dump_autobackup', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 60, 'temp_download_destroy.php', 'cron_auto_del_temp_download', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 30, 'ip_logs_destroy.php', 'cron_del_ip_logs', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 1440, 'error_log_destroy.php', 'cron_auto_del_error_log', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 360, 'error_log_sendmail.php', 'cron_auto_sendmail_error_log', '', 0, 1, 0, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 60, 'ref_expired_del.php', 'cron_ref_expired_del', '', 0, 1, 1, 0, 0),
(NULL, " . NV_CURRENTTIME . ", 1440, 'siteDiagnostic_update.php', 'cron_siteDiagnostic_update', '', 0, 1, 1, 0, 0),
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
(1, 'Bo ngoai giao', 2, 0, 'bongoaigiao.jpg', 'jpg', 'image/jpeg', 160, 54, '', 'http://www.mofa.gov.vn', '', '', '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1), 
(2, 'vinades', 2, 0, 'vinades.jpg', 'jpg', 'image/jpeg', 190, 454, '', 'http://vinades.vn', '', '', '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,2), 
(3, 'Quang cao giua trang', 1, 0, 'webnhanh_vn.gif', 'gif', 'image/gif', 510, 65, '', 'http://webnhanh.vn', '', '', '', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0, 0, 1,1)";

?>