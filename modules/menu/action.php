<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_menu`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) unsigned NOT NULL,
  `mid` int(11) NOT NULL DEFAULT '0',  
  `title` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `note` varchar(255) NOT NULL DEFAULT '',
  `weight` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `lev` int(11) NOT NULL DEFAULT '0',
  `subitem` mediumtext NOT NULL,
  `who_view` tinyint(2) NOT NULL DEFAULT '0',
  `groups_view` varchar(255) NOT NULL,  
  `module_name` varchar(255) NOT NULL DEFAULT '',
  `op` varchar(255) NOT NULL DEFAULT '', 
  `target` tinyint(4) NOT NULL DEFAULT '0',
  `css` varchar(255) NOT NULL DEFAULT '', 
  `active_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `menu_item` mediumtext NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
   PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM";

?>