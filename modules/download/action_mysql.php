<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data;
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp";

$result = $db->query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_comments'" );
$rows = $result->fetchAll();
if( sizeof( $rows ) )
{
	$sql_drop_module[] = "DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_comments WHERE module='" . $module_name . "'";
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 catid smallint(5) unsigned NOT NULL,
 title varchar(255) NOT NULL,
 alias varchar(255) NOT NULL,
 description mediumtext NOT NULL,
 introtext text NOT NULL,
 uploadtime int(11) unsigned NOT NULL,
 updatetime int(11) unsigned NOT NULL DEFAULT '0',
 user_id mediumint(8) unsigned NOT NULL,
 user_name varchar(100) NOT NULL,
 author_name varchar(100) NOT NULL,
 author_email varchar(60) NOT NULL,
 author_url varchar(255) NOT NULL,
 fileupload text NOT NULL,
 linkdirect text NOT NULL,
 version varchar(20) NOT NULL,
 filesize int(11) NOT NULL DEFAULT '0',
 fileimage varchar(255) NOT NULL,
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 copyright varchar(255) NOT NULL,
 view_hits int(11) NOT NULL DEFAULT '0',
 download_hits int(11) NOT NULL DEFAULT '0',
 groups_comment varchar(255) NOT NULL,
 groups_view varchar(255) NOT NULL,
 groups_download varchar(255) NOT NULL,
 comment_hits int(11) NOT NULL DEFAULT '0',
 rating_detail varchar(255) NOT NULL,
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias),
 KEY catid (catid),
 KEY user_id (user_id)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 catid smallint(5) unsigned NOT NULL DEFAULT '0',
 title varchar(255) NOT NULL,
 description mediumtext NOT NULL,
 introtext text NOT NULL,
 uploadtime int(11) unsigned NOT NULL DEFAULT '0',
 user_id mediumint(8) unsigned NOT NULL DEFAULT '0',
 user_name varchar(100) NOT NULL,
 author_name varchar(100) NOT NULL,
 author_email varchar(60) NOT NULL,
 author_url varchar(255) NOT NULL,
 fileupload text NOT NULL,
 linkdirect text NOT NULL,
 version varchar(20) NOT NULL,
 filesize varchar(255) NOT NULL,
 fileimage varchar(255) NOT NULL,
 copyright varchar(255) NOT NULL,
 PRIMARY KEY (id),
 UNIQUE KEY title (title),
 KEY catid (catid)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories (
 id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 parentid smallint(5) unsigned NOT NULL,
 title varchar(255) NOT NULL,
 alias varchar(255) NOT NULL,
 description text,
 groups_view varchar(255) DEFAULT '',
 groups_download varchar(255) DEFAULT '',
 weight smallint(4) unsigned NOT NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY alias (alias)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report (
 fid mediumint(8) unsigned NOT NULL DEFAULT '0',
 post_ip varchar(45) NOT NULL,
 post_time int(11) unsigned NOT NULL DEFAULT '0',
 UNIQUE KEY fid (fid),
 KEY post_time (post_time)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
 config_name varchar(30) NOT NULL,
 config_value varchar(255) NOT NULL,
 UNIQUE KEY config_name (config_name)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES
('is_addfile', '1'),
('is_upload', '1'),
('groups_upload', ''),
('maxfilesize', '2097152'),
('upload_filetype', 'doc,xls,zip,rar'),
('upload_dir', 'files'),
('temp_dir', 'temp'),
('groups_addfile', ''),
('is_zip', '1'),
('is_resume', '1'),
('max_speed', '0')";

// Comments
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_comm', '-1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'view_comm', '6')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '4')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'adminscomm', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'sortcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha', '1')";