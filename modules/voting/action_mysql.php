<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ';';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows;';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_voted;';

$_maxlength = ($db_config['charset'] == 'utf8') ? 333 : 250;
$sql_create_module = $sql_drop_module;
$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ' (
 vid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
 question varchar(' . $_maxlength . ") NOT NULL,
 link varchar(255) default '',
 acceptcm int(2) NOT NULL DEFAULT '1',
 active_captcha tinyint(1) unsigned NOT NULL DEFAULT '0',
 admin_id mediumint(8) unsigned NOT NULL DEFAULT '0',
 groups_view varchar(255) default '',
 publ_time int(11) unsigned NOT NULL DEFAULT '0',
 exp_time int(11) unsigned NOT NULL DEFAULT '0',
 act tinyint(1) unsigned NOT NULL DEFAULT '0',
 vote_one tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 cho phép vote nhiều lần 1 cho phép vote 1 lần',
 PRIMARY KEY (vid),
 UNIQUE KEY question (question)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 vid smallint(5) unsigned NOT NULL,
 title varchar(245) NOT NULL DEFAULT '',
 url varchar(255) DEFAULT '',
 hitstotal int(11) unsigned NOT NULL DEFAULT '0',
 PRIMARY KEY (id),
 UNIQUE KEY vid (vid,title)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_voted (
 vid SMALLINT(5) UNSIGNED NOT NULL,
 voted TEXT,
 UNIQUE KEY vid (vid)
) ENGINE=MyISAM';

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'difftimeout', '3600')";
$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'captcha_type', 'captcha')";
