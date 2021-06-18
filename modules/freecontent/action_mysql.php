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

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows;";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_blocks;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
 id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 bid mediumint(8) unsigned NOT NULL DEFAULT '0',
 title varchar(255) NOT NULL DEFAULT '',
 description mediumtext NOT NULL,
 link varchar(255) NOT NULL DEFAULT '',
 target varchar(10) NOT NULL DEFAULT '' COMMENT '_blank|_self|_parent|_top',
 image varchar(255) NOT NULL DEFAULT '',
 start_time int(11) NOT NULL DEFAULT '0',
 end_time int(11) NOT NULL DEFAULT '0',
 status tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0: In-Active, 1: Active, 2: Expired',
 PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_blocks (
 bid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
 title varchar(255) NOT NULL DEFAULT '',
 description mediumtext NOT NULL,
 PRIMARY KEY (bid)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . $lang . "', '" . $module_name . "', 'next_execute', '0')";
