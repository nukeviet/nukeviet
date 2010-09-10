<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_click`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_clients`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_plans`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_click` (
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

$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_clients` (
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
  `uploadtype` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`),
  KEY `full_name` (`full_name`)
) ENGINE=MyISAM";

$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_plans` (
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

$sql_create_module[] = "
CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows` (
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
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `clid` (`clid`) USING BTREE
) ENGINE=MyISAM";
?>