<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_stat`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `catid` int(11) DEFAULT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext,
  `introtext` text NOT NULL,
  `uploadtime` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `authoremail` varchar(255) DEFAULT NULL,
  `homepage` text,
  `fileupload` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `linkdirect` text,
  `filesize` varchar(255) DEFAULT NULL,
  `fileimage` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `copyright` text NOT NULL,
  `view` int(11) NOT NULL DEFAULT '0',
  `download` int(11) NOT NULL DEFAULT '0',
  `comment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`) 
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `parentid` int(11) NOT NULL DEFAULT '0',
  `weight` int(11) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `alias` varchar(50) NOT NULL,
  `cdescription` text NOT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `title` (`title`,`parentid`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `lid` int(11) NOT NULL DEFAULT '0',
  `date` int(11) DEFAULT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `host_name` varchar(60) DEFAULT NULL,
  `comment` text NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tid`),
  KEY `lid` (`lid`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_stat` (
  `id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `downloads` int(11) DEFAULT '0',
  `comments` int(11) DEFAULT NULL,
  `rates` int(11) DEFAULT '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_report` (
  `id` int(11) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `date_up` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_tmp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `catid` int(11) DEFAULT NULL,
  `description` text,
  `introtext` text NOT NULL,
  `uploadtime` int(11) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `authoremail` varchar(255) DEFAULT NULL,
  `homepage` varchar(255) DEFAULT NULL,
  `fileupload` varchar(255) DEFAULT NULL,
  `version` varchar(20) DEFAULT NULL,
  `linkdirect` text,
  `filesize` text,
  `fileimage` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `copyright` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (`name`, `value`) VALUES
('deslimit', '1'),
('textlimit', '150'),
('directlink', '1'),
('showmessage', '1'),
('showsubfolder', '1'),
('numsubfolder', '3'),
('numfile', '15'),
('who_view1', '0'),
('groups_view1', '13'),
('showcaptcha', '1'),
('who_view2', '1'),
('groups_view2', '14'),
('who_view3', '1'),
('groups_view3', '15'),
('who_view4', '0'),
('groups_view4', ''),
('who_view5', '0'),
('groups_view5', ''),
('who_view6', '0'),
('groups_view6', '14'),
('maxfilesize', '1048576'),
('filetype', 'zip,rar, doc, xls'),
('filedir', 'files'),
('filetempdir', 'temp'),
('showemail', '1');";
if ( $lang == "vi" )
{
    $sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (`name`, `value`) VALUES('messagecontent', 'Xin chào đến với hệ thống download của chúng tôi. Tại đây các bạn có thể download những phần mềm tiện ích. Chúc bạn tìm được những gì mình muốn.')";
}
else
{
    $sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (`name`, `value`) VALUES('messagecontent', 'Welcome to our download system. You can download many utility softwares. Got everything you want.')";
}

?>