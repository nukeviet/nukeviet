<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . ";";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows;";

$_maxlength = ($db_config['charset'] == 'utf8') ? 333 : 250;
$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
 vid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 question varchar(". $_maxlength . ") NOT NULL,
 link varchar(255) default '',
 acceptcm int(2) NOT NULL DEFAULT '1',
 active_captcha tinyint(1) unsigned NOT NULL DEFAULT '0',
 admin_id mediumint(8) unsigned NOT NULL DEFAULT '0',
 groups_view varchar(255) default '',
 publ_time int(11) unsigned NOT NULL DEFAULT '0',
 exp_time int(11) unsigned NOT NULL DEFAULT '0',
 act tinyint(1) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (vid),
 UNIQUE KEY question (question)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 vid smallint(5) unsigned NOT NULL,
 title varchar(245) NOT NULL DEFAULT '',
 url varchar(255) DEFAULT '',
 hitstotal int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY vid (vid,title)
) ENGINE=MyISAM";
