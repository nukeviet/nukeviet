<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

define( 'NV_MODULE_SETUP_DEFAULT', 'users,statistics,banners,seek,news,contact,about,voting,feeds,menu,page,comment' );

function nv_copy_structure_table( $table_des, $table_src )
{
	global $db, $db_config;
	return $db->exec( 'CREATE TABLE ' . $table_des . ' LIKE ' . $table_src );
}

function nv_delete_table_sys( $lang )
{
	global $db_config, $global_config;

	$sql_drop_table = array();
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modules';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_groups';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_weight';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modfuncs';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_searchkeys';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_referer_stats';
	$sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modthemes';
	$sql_drop_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_cronjobs DROP ' . $lang . '_cron_name';

	return $sql_drop_table;
}

function nv_create_table_sys( $lang )
{
	global $db_config, $global_config;

	$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini' );
	$layoutdefault = ( string )$xml->layoutdefault;

	$sql_create_table = array();
	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modules (
		 title varchar(55) NOT NULL,
		 module_file varchar(55) NOT NULL DEFAULT '',
		 module_data varchar(55) NOT NULL DEFAULT '',
		 custom_title varchar(255) NOT NULL,
		 admin_title varchar(255) DEFAULT '',
		 set_time int(11) unsigned NOT NULL DEFAULT '0',
		 main_file tinyint(1) unsigned NOT NULL DEFAULT '0',
		 admin_file tinyint(1) unsigned NOT NULL DEFAULT '0',
		 theme varchar(100) DEFAULT '',
		 mobile varchar(100) DEFAULT '',
	 	 description varchar(255) DEFAULT '',
		 keywords text,
		 groups_view varchar(255) NOT NULL,
		 weight tinyint(3) unsigned NOT NULL DEFAULT '1',
		 act tinyint(1) unsigned NOT NULL DEFAULT '0',
		 admins varchar(255) DEFAULT '',
		 rss tinyint(4) NOT NULL DEFAULT '1',
		 gid smallint(5) NOT NULL DEFAULT '0',
		 PRIMARY KEY (title)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_blocks_groups (
		 bid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		 theme varchar(55) NOT NULL,
		 module varchar(55) NOT NULL,
		 file_name varchar(55) DEFAULT NULL,
		 title varchar(255) DEFAULT NULL,
		 link varchar(255) DEFAULT NULL,
		 template varchar(55) DEFAULT NULL,
		 position varchar(55) DEFAULT NULL,
		 exp_time int(11) DEFAULT '0',
		 active tinyint(4) DEFAULT '0',
		 groups_view varchar(255) DEFAULT '',
		 all_func tinyint(4) NOT NULL DEFAULT '0',
		 weight int(11) NOT NULL DEFAULT '0',
		 config text,
		 PRIMARY KEY (bid),
		 KEY theme (theme),
		 KEY module (module),
		 KEY position (position),
		 KEY exp_time (exp_time)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_blocks_weight (
		 bid mediumint(8) NOT NULL DEFAULT '0',
		 func_id mediumint(8) NOT NULL DEFAULT '0',
		 weight mediumint(8) NOT NULL DEFAULT '0',
		 UNIQUE KEY bid (bid,func_id)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modfuncs (
		 func_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		 func_name varchar(55) NOT NULL,
		 alias varchar(55) NOT NULL DEFAULT '',
		 func_custom_name varchar(255) NOT NULL,
		 in_module varchar(55) NOT NULL,
		 show_func tinyint(4) NOT NULL DEFAULT '0',
		 in_submenu tinyint(1) unsigned NOT NULL DEFAULT '0',
		 subweight smallint(2) unsigned NOT NULL DEFAULT '1',
		 setting varchar(255) NOT NULL DEFAULT '',
		 PRIMARY KEY (func_id),
		 UNIQUE KEY func_name (func_name,in_module),
		 UNIQUE KEY alias (alias,in_module)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_searchkeys (
		 id varchar(32) NOT NULL DEFAULT '',
		 skey varchar(255) NOT NULL,
		 total int(11) NOT NULL DEFAULT '0',
		 search_engine varchar(50) NOT NULL,
		 KEY (id),
		 KEY skey (skey),
		 KEY search_engine (search_engine)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_referer_stats (
		 host varchar(255) NOT NULL,
		 total int(11) NOT NULL DEFAULT '0',
		 month01 int(11) NOT NULL DEFAULT '0',
		 month02 int(11) NOT NULL DEFAULT '0',
		 month03 int(11) NOT NULL DEFAULT '0',
		 month04 int(11) NOT NULL DEFAULT '0',
		 month05 int(11) NOT NULL DEFAULT '0',
		 month06 int(11) NOT NULL DEFAULT '0',
		 month07 int(11) NOT NULL DEFAULT '0',
		 month08 int(11) NOT NULL DEFAULT '0',
		 month09 int(11) NOT NULL DEFAULT '0',
		 month10 int(11) NOT NULL DEFAULT '0',
		 month11 int(11) NOT NULL DEFAULT '0',
		 month12 int(11) NOT NULL DEFAULT '0',
		 last_update int(11) NOT NULL DEFAULT '0',
		 UNIQUE KEY host (host),
		 KEY total (total)
	) ENGINE=MyISAM";

	$sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modthemes (
		 func_id mediumint(8) DEFAULT NULL,
		 layout varchar(100) DEFAULT NULL,
		 theme varchar(100) DEFAULT NULL,
		 UNIQUE KEY func_id (func_id,layout,theme)
	 ) ENGINE=MyISAM";

 	$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_modules (title, module_file, module_data, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, gid) VALUES
		('about', 'page', 'about', 'About', '', 1276333182, 1, 1, '', 'mobile_nukeviet', '', '', '0', 1, 1, '', 0, 0),
		('news', 'news', 'news', 'News', '', 1270400000, 1, 1, '', 'mobile_nukeviet', '', '', '0', 2, 1, '', 1, 0),
		('users', 'users', 'users', 'Users', 'Users', 1274080277, 1, 1, '', 'mobile_nukeviet', '', '', '0', 3, 1, '', 0, 0),
		('contact', 'contact', 'contact', 'Contact', '', 1275351337, 1, 1, '', 'mobile_nukeviet', '', '', '0', 4, 1, '', 0, 0),
		('statistics', 'statistics', 'statistics', 'Statistics', '', 1276520928, 1, 0, '', 'mobile_nukeviet', '', '', '0', 5, 1, '', 0, 0),
		('voting', 'voting', 'voting', 'Voting', '', 1275315261, 1, 1, '', 'mobile_nukeviet', '', '', '0', 6, 1, '', 1, 0),
		('banners', 'banners', 'banners', 'Banners', '', 1270400000, 1, 1, '', 'mobile_nukeviet', '', '', '0', 7, 1, '', 0, 0),
		('seek', 'seek', 'seek', 'Search', '', 1273474173, 1, 0, '', 'mobile_nukeviet', '', '', '0', 8, 1, '', 0, 0),
		('menu', 'menu', 'menu', 'Menu Site', '', 1295287334, 0, 1, '', 'mobile_nukeviet', '', '', '0', 9, 1, '', 0, 0),
		('feeds', 'feeds', 'feeds', 'Rss Feeds', '', 1279366705, 1, 1, '', 'mobile_nukeviet', '', '', '0', 10, 1, '', 0, 0),
		('page', 'page', 'page', 'Page', '', 1279366705, 1, 1, '', 'mobile_nukeviet', '', '', '0', 11, 1, '', 0, 0),
		('comment', 'comment', 'comment', 'Comment', '', 1279366705, 1, 1, '', '', '', '', '0', 12, 1, '', 0, 0)";

	$sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
	 	('" . $lang . "', 'global', 'site_name', 'NukeViet CMS 4.x'),
	 	('" . $lang . "', 'global', 'site_logo', 'images/logo.png'),
	 	('" . $lang . "', 'global', 'site_description', 'NukeViet CMS 4.x Developed by VINADES.,JSC'),
	 	('" . $lang . "', 'global', 'site_keywords', ''),
        ('" . $lang . "', 'global', 'site_theme', '" . $global_config['site_theme'] . "'),
	 	('" . $lang . "', 'global', 'mobile_theme', ''),
	 	('" . $lang . "', 'global', 'site_home_module', 'users'),
	 	('" . $lang . "', 'global', 'switch_mobi_des', '1'),
		('" . $lang . "', 'global', 'upload_logo', 'images/logo.png'),
		('" . $lang . "', 'global', 'autologosize1', '50'),
		('" . $lang . "', 'global', 'autologosize2', '40'),
		('" . $lang . "', 'global', 'autologosize3', '30'),
		('" . $lang . "', 'global', 'autologomod', ''),
		('" . $lang . "', 'global', 'cronjobs_next_time', '" . NV_CURRENTTIME . "'),
		('" . $lang . "', 'global', 'disable_site_content', 'For technical reasons Web site temporary not available. we are very sorry for any inconvenience!'),
		('" . $lang . "', 'seotools', 'prcservice', '')";

	$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_language (lang, setup) VALUES('" . $lang . "', 1)";

	$sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_modthemes (func_id, layout, theme) VALUES ('0', '" . $layoutdefault . "', '" . $global_config['site_theme'] . "')";
	$sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_cronjobs ADD " . $lang . "_cron_name VARCHAR( 255 ) NOT NULL DEFAULT ''";

	return $sql_create_table;
}