<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report`;";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(9) NOT NULL,
  `author` varchar(100) NOT NULL DEFAULT '1|1',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `urlimg` varchar(255) NOT NULL,
  `admin_phone` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `note` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  `hits_total` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catid` (`catid`),
  KEY `status` (`status`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat` (
  `catid` mediumint(8) unsigned NOT NULL auto_increment,
  `parentid` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL,
  `catimage` varchar(100) NOT NULL,
  `alias` varchar(100) NOT NULL,
  `description` text,
  `weight` smallint(4) NOT NULL default '0',
  `inhome` tinyint(1) unsigned NOT NULL default '0',
  `numlinks` tinyint(2) unsigned NOT NULL default '3',
  `keywords` text NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  `edit_time` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`catid`),
  UNIQUE KEY `parentid` (`parentid`,`title`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `name` varchar(20) default NULL,
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report` (
  `id` int(11) DEFAULT NULL,
  `type` int(1) DEFAULT NULL,
  `report_time` int(11) NOT NULL,
  `report_userid` int(11) NOT NULL,
  `report_ip` varchar(16) NOT NULL,
  `report_browse_key` varchar(100) NOT NULL,
  `report_browse_name` varchar(100) NOT NULL,
  `report_os_key` varchar(100) NOT NULL,
  `report_os_name` varchar(100) NOT NULL,
  `report_note` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (`name`, `value`) VALUES
('intro', ''),
('numcat', '2'),
('showsub', '1'),
('numsub', '2'),
('numinsub', '1'),
('showcatimage', '0'),
('per_page', '20'),
('numsubcat', '2'),
('shownumsubcat', '1'),
('sort', 'asc'),
('showlinkimage', '1'),
('showdes', '1'),
('sortoption', 'byid'),
('imgwidth', '100'),
('imgheight', '74'),
('timeout', '1')";

?>