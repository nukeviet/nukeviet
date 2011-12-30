<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES. All rights reserved
 * @Createdate Dec 22, 2011 10:22:41 AM
 */

if (!defined('NV_IS_FILE_MODULES'))
    die('Stop!!!');

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`;";

$result = $db->sql_query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_" . $module_data . "\_con\_%'");
while ($item = $db->sql_fetch_assoc($result))
{
    $tableid = str_replace($db_config['prefix'] . "_" . $lang . "_" . $module_data . '_con_', '', $item['Name']);
    $sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_con_" . $tableid . "`;";
    $sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_kid_" . $tableid . "`;";
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
						  `tid` int(11) NOT NULL AUTO_INCREMENT,
						  `keys` varchar(50) NOT NULL,
						  `total` int(11) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`tid`),
						  UNIQUE KEY `keys` (`keys`)
						) ENGINE=MyISAM";

$db->sql_query("CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_con_1` (
                          `module` varchar(50) NOT NULL,
                          `sid` int(11) NOT NULL DEFAULT '0',
                          `link` varchar(255) NOT NULL,
                          `title` varchar(255) NOT NULL,
                          `text` text NOT NULL,
                          `image` varchar(255) NOT NULL,
                          `publtime` int(11) NOT NULL DEFAULT '0',
                          UNIQUE KEY `module` (`module`,`sid`),
                          KEY `publtime` (`publtime`)
                    ) ENGINE=MyISAM");

$db->sql_query("CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_kid_1` (
                          `tid` int(10) unsigned NOT NULL,
                          `module` varchar(50) NOT NULL,
                          `sid` int(11) NOT NULL DEFAULT '0',
                          UNIQUE KEY `kid` (`tid`, `module`,`sid`)
                    ) ENGINE=MyISAM");
?>