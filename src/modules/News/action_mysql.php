<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();
$array_table = array(
    'admins',
    'block',
    'block_cat',
    'bodytext',
    'cat',
    'config_post',
    'rows',
    'sources',
    'tags',
    'tags_id',
    'topics',
    'detail',
    'logs',
    'tmp'
);
$table = $db_config['prefix'] . '_' . $lang . '_' . $module_data;
$result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($table . '_%'));
while ($item = $result->fetch()) {
    $name = substr($item['name'], strlen($table) + 1);
    if (preg_match('/^' . $db_config['prefix'] . '\_' . $lang . '\_' . $module_data . '\_/', $item['name']) and (preg_match('/^([0-9]+)$/', $name) or in_array($name, $array_table) or preg_match('/^bodyhtml\_([0-9]+)$/', $name))) {
        $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $item['name'];
    }
}

$result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_comment'");
$rows = $result->fetchAll();
if (sizeof($rows)) {
    $sql_drop_module[] = "DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_comment WHERE module='" . $module_name . "'";
}
$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat (
	  catid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	  parentid smallint(5) unsigned NOT NULL DEFAULT '0',
	  title varchar(250) NOT NULL,
	  titlesite varchar(250) DEFAULT '',
	  alias varchar(250) NOT NULL DEFAULT '',
	  description text,
	  descriptionhtml text,
	  image varchar(255) DEFAULT '',
	  viewdescription tinyint(2) NOT NULL DEFAULT '0',
	  weight smallint(5) unsigned NOT NULL DEFAULT '0',
	  sort smallint(5) NOT NULL DEFAULT '0',
	  lev smallint(5) NOT NULL DEFAULT '0',
	  viewcat varchar(50) NOT NULL DEFAULT 'viewcat_page_new',
	  numsubcat smallint(5) NOT NULL DEFAULT '0',
	  subcatid varchar(255) DEFAULT '',
	  numlinks tinyint(2) unsigned NOT NULL DEFAULT '3',
	  newday tinyint(2) unsigned NOT NULL DEFAULT '2',
	  featured int(11) NOT NULL DEFAULT '0',
	  ad_block_cat varchar(255) NOT NULL DEFAULT '',
	  keywords text,
	  admins text,
	  add_time int(11) unsigned NOT NULL DEFAULT '0',
	  edit_time int(11) unsigned NOT NULL DEFAULT '0',
	  groups_view varchar(255) DEFAULT '',
      status smallint(4) NOT NULL DEFAULT '1',
	  PRIMARY KEY (catid),
	  UNIQUE KEY alias (alias),
	  KEY parentid (parentid),
	  KEY status (status)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sources (
	 sourceid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 title varchar(250) NOT NULL DEFAULT '',
	 link varchar(255) DEFAULT '',
	 logo varchar(255) DEFAULT '',
	 weight mediumint(8) unsigned NOT NULL DEFAULT '0',
	 add_time int(11) unsigned NOT NULL,
	 edit_time int(11) unsigned NOT NULL,
	 PRIMARY KEY (sourceid),
	 UNIQUE KEY title (title)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics (
	 topicid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	 title varchar(250) NOT NULL DEFAULT '',
	 alias varchar(250) NOT NULL DEFAULT '',
	 image varchar(255) DEFAULT '',
	 description varchar(255) DEFAULT '',
	 weight smallint(5) NOT NULL DEFAULT '0',
	 keywords text,
	 add_time int(11) NOT NULL DEFAULT '0',
	 edit_time int(11) NOT NULL DEFAULT '0',
	 PRIMARY KEY (topicid),
	 UNIQUE KEY title (title),
	 UNIQUE KEY alias (alias)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat (
	 bid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	 adddefault tinyint(4) NOT NULL DEFAULT '0',
	 numbers smallint(5) NOT NULL DEFAULT '10',
	 title varchar(250) NOT NULL DEFAULT '',
	 alias varchar(250) NOT NULL DEFAULT '',
	 image varchar(255) DEFAULT '',
	 description varchar(255) DEFAULT '',
	 weight smallint(5) NOT NULL DEFAULT '0',
	 keywords text,
	 add_time int(11) NOT NULL DEFAULT '0',
	 edit_time int(11) NOT NULL DEFAULT '0',
	 PRIMARY KEY (bid),
	 UNIQUE KEY title (title),
	 UNIQUE KEY alias (alias)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block (
	 bid smallint(5) unsigned NOT NULL,
	 id int(11) unsigned NOT NULL,
	 weight int(11) unsigned NOT NULL,
	 UNIQUE KEY bid (bid,id)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
	 id int(11) unsigned NOT NULL auto_increment,
	 catid smallint(5) unsigned NOT NULL default '0',
	 listcatid varchar(255) NOT NULL default '',
	 topicid smallint(5) unsigned NOT NULL default '0',
	 admin_id mediumint(8) unsigned NOT NULL default '0',
	 author varchar(250) default '',
	 sourceid mediumint(8) NOT NULL default '0',
	 addtime int(11) unsigned NOT NULL default '0',
	 edittime int(11) unsigned NOT NULL default '0',
	 status tinyint(4) NOT NULL default '1',
	 weight int(11) unsigned NOT NULL default '0',
	 publtime int(11) unsigned NOT NULL default '0',
	 exptime int(11) unsigned NOT NULL default '0',
	 archive tinyint(1) unsigned NOT NULL default '0',
	 title varchar(250) NOT NULL default '',
	 alias varchar(250) NOT NULL default '',
	 hometext text NOT NULL,
	 homeimgfile varchar(255) default '',
	 homeimgalt varchar(255) default '',
	 homeimgthumb tinyint(4) NOT NULL default '0',
	 inhome tinyint(1) unsigned NOT NULL default '0',
	 allowed_comm varchar(255) default '',
	 allowed_rating tinyint(1) unsigned NOT NULL default '0',
     external_link tinyint(1) unsigned NOT NULL default '0',
	 hitstotal mediumint(8) unsigned NOT NULL default '0',
	 hitscm mediumint(8) unsigned NOT NULL default '0',
	 total_rating int(11) NOT NULL default '0',
	 click_rating int(11) NOT NULL default '0',
	 instant_active tinyint(1) NOT NULL default '0',
     instant_template varchar(100) NOT NULL default '',
	 instant_creatauto tinyint(1) NOT NULL default '0',
	 PRIMARY KEY (id),
	 KEY catid (catid),
	 KEY topicid (topicid),
	 KEY admin_id (admin_id),
	 KEY author (author),
	 KEY title (title),
	 KEY addtime (addtime),
	 KEY edittime (edittime),
	 KEY publtime (publtime),
	 KEY exptime (exptime),
	 KEY status (status),
	 KEY instant_active (instant_active),
	 KEY instant_creatauto (instant_creatauto)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_detail (
	 id int(11) unsigned NOT NULL,
     titlesite varchar(255) NOT NULL DEFAULT '',
     description text NOT NULL,
	 bodyhtml longtext NOT NULL,
	 keywords varchar(255) default '',
	 sourcetext varchar(255) default '',
	 files TEXT NULL,
	 imgposition tinyint(1) NOT NULL default '1',
	 layout_func varchar(100) DEFAULT '',
	 copyright tinyint(1) NOT NULL default '0',
	 allowed_send tinyint(1) NOT NULL default '0',
	 allowed_print tinyint(1) NOT NULL default '0',
	 allowed_save tinyint(1) NOT NULL default '0',
	 gid mediumint(8) NOT NULL default '0',
	 PRIMARY KEY (id)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
	 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 sid mediumint(8) NOT NULL DEFAULT '0',
	 userid mediumint(8) unsigned NOT NULL DEFAULT '0',
	 status tinyint(4) NOT NULL DEFAULT '0',
	 note varchar(255) NOT NULL,
	 set_time int(11) unsigned NOT NULL DEFAULT '0',
	 PRIMARY KEY (id),
	 KEY sid (sid),
	 KEY userid (userid)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config_post (
	 group_id smallint(5) NOT NULL,
	 addcontent tinyint(4) NOT NULL,
	 postcontent tinyint(4) NOT NULL,
	 editcontent tinyint(4) NOT NULL,
	 delcontent tinyint(4) NOT NULL,
	 PRIMARY KEY (group_id)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_admins (
	 userid mediumint(8) unsigned NOT NULL default '0',
	 catid smallint(5) NOT NULL default '0',
	 admin tinyint(4) NOT NULL default '0',
	 add_content tinyint(4) NOT NULL default '0',
	 pub_content tinyint(4) NOT NULL default '0',
	 edit_content tinyint(4) NOT NULL default '0',
	 del_content tinyint(4) NOT NULL default '0',
	 app_content tinyint(4) NOT NULL default '0',
	 UNIQUE KEY userid (userid,catid)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags (
	 tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	 numnews mediumint(8) NOT NULL DEFAULT '0',
	 alias varchar(250) NOT NULL DEFAULT '',
	 image varchar(255) DEFAULT '',
	 description text,
	 keywords varchar(255) DEFAULT '',
	 PRIMARY KEY (tid),
	 UNIQUE KEY alias (alias)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tags_id (
	 id int(11) NOT NULL,
	 tid mediumint(9) NOT NULL,
	 keyword varchar(65) NOT NULL,
	 UNIQUE KEY id_tid (id,tid),
	 KEY tid (tid)
	) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp (
	  id mediumint(8) unsigned NOT NULL,
	  admin_id int(11) NOT NULL DEFAULT '0',
	  time_edit int(11) NOT NULL,
	  time_late int(11) NOT NULL,
	  ip varchar(50) NOT NULL,
	  PRIMARY KEY (id)
	) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'indexfile', 'viewcat_main_right')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '20')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'st_links', '10')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homewidth', '100')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'homeheight', '150')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockwidth', '70')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'blockheight', '75')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'imagefull', '460')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'copyright', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'showtooltip', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tooltip_position', 'bottom')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tooltip_length', '150')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'showhometext', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timecheckstatus', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'config_source', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'show_no_image', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowed_rating_point', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'facebookappid', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'socialbutton', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'alias_lower', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tags_alias', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_tags', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'tags_remind', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'keywords_tag', '1')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'copy_news', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'structure_upload', 'Ym')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'imgposition', '2')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'htmlhometext', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'order_articles', '1')";

// Cau hinh elasticseach
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'elas_use', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'elas_host', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'elas_port', '9200')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'elas_index', '')";

// Bai viet tuc thoi
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_active', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_template', 'default')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_httpauth', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_username', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_password', '')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_livetime', '60')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_gettime', '120')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'instant_articles_auto', '1')";

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
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'perpagecomm', '5')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'timeoutcomm', '360')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'allowattachcomm', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'alloweditorcomm', '0')";

// Cau hinh dang bai ngoai trang
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'frontend_edit_alias', '0')";
$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'frontend_edit_layout', '1')";
