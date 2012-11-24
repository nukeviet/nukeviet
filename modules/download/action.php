<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `introtext` mediumtext NOT NULL,
  `uploadtime` int(11) unsigned NOT NULL,
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(60) NOT NULL,
  `author_url` varchar(255) NOT NULL,
  `fileupload` mediumtext NOT NULL,
  `linkdirect` mediumtext NOT NULL,
  `version` varchar(20) NOT NULL,
  `filesize` int(11) NOT NULL DEFAULT '0',
  `fileimage` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `copyright` varchar(255) NOT NULL,
  `view_hits` int(11) NOT NULL DEFAULT '0',
  `download_hits` int(11) NOT NULL DEFAULT '0',
  `comment_allow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `who_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groups_comment` varchar(255) NOT NULL,
  `who_view` tinyint(4) unsigned NOT NULL,
  `groups_view` varchar(255) NOT NULL,
  `who_download` tinyint(4) unsigned NOT NULL,
  `groups_download` varchar(255) NOT NULL,
  `comment_hits` int(11) NOT NULL DEFAULT '0',
  `rating_detail` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `catid` (`catid`),
  KEY `user_id` (`user_id`)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `introtext` mediumtext NOT NULL,
  `uploadtime` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(100) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_email` varchar(60) NOT NULL,
  `author_url` varchar(255) NOT NULL,
  `fileupload` mediumtext NOT NULL,
  `linkdirect` mediumtext NOT NULL,
  `version` varchar(20) NOT NULL,
  `filesize` varchar(255) NOT NULL,
  `fileimage` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `catid` (`catid`)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `who_view` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groups_view` varchar(255) NOT NULL,
  `who_download` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groups_download` varchar(255) NOT NULL,
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)  
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `post_id` mediumint(8) unsigned NOT NULL,
  `post_name` varchar(100) NOT NULL,
  `post_email` varchar(60) NOT NULL,
  `post_ip` varchar(45) NOT NULL,
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `comment` mediumtext NOT NULL,
  `admin_reply` varchar(255) NOT NULL,
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report` (
  `fid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `post_ip` varchar(45) NOT NULL,
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `fid` (`fid`),
  KEY `post_time` (`post_time`)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `config_name` varchar(30) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  UNIQUE KEY `config_name` (`config_name`)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES
('is_addfile', '1'),
('is_upload', '1'),
('who_upload', '1'),
('groups_upload', ''),
('maxfilesize', '2097152'),
('upload_filetype', 'doc,xls,zip,rar'),
('upload_dir', 'files'),
('temp_dir', 'temp'),
('who_autocomment', '0'),
('groups_autocomment', ''),
('who_addfile', '0'),
('groups_addfile', ''),
('is_zip', '1'),
('is_resume', '1'),
('max_speed', '0')";

?>