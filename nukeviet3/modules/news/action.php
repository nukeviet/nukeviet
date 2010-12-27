<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_" . $module_data . "\_%'" );
$num_table = intval( $db->sql_numrows( $result ) );
if ( $num_table > 0 )
{
    $result = $db->sql_query( "SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat` ORDER BY `order` ASC" );
    while ( list( $catid_i ) = $db->sql_fetchrow( $result ) )
    {
        $sql_drop_module[] = "DROP TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_" . $catid_i . "`";
    }
    $db->sql_freeresult();
}

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sources`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_log`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config_post`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `thumbnail` varchar(255) NOT NULL DEFAULT '',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `order` mediumint(8) NOT NULL DEFAULT '0',
  `lev` smallint(4) NOT NULL DEFAULT '0',
  `viewcat` varchar(50) NOT NULL DEFAULT 'viewcat_page_new',
  `numsubcat` int(11) NOT NULL DEFAULT '0',
  `subcatid` varchar(255) NOT NULL DEFAULT '',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `numlinks` tinyint(2) unsigned NOT NULL DEFAULT '3',
  `keywords` mediumtext NOT NULL,
  `admins` mediumtext NOT NULL,
  `add_time` int(11) unsigned NOT NULL DEFAULT '0',
  `edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  `del_cache_time` int(11) NOT NULL DEFAULT '0',
  `who_view` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `groups_view` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`catid`),
  UNIQUE KEY `alias` (`alias`),
  KEY `parentid` (`parentid`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sources` (
  `sourceid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `weight` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) unsigned NOT NULL,
  `edit_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`sourceid`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics` (
  `topicid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `keywords` mediumtext NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`topicid`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block_cat` (
  `bid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `adddefault` tinyint(4) NOT NULL DEFAULT '0',
  `number` mediumint(4) NOT NULL DEFAULT '10',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `weight` smallint(4) NOT NULL DEFAULT '0',
  `keywords` mediumtext NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comments` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',  
  `post_name` varchar(100) NOT NULL,
  `post_email` varchar(100) NOT NULL,
  `post_ip` varchar(15) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `post_time` (`post_time`),
  KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_block` (
  `bid` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `weight` int(11) unsigned NOT NULL,
  UNIQUE KEY `bid` (`bid`,`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listcatid` varchar(255) NOT NULL DEFAULT '',
  `topicid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author` varchar(255) NOT NULL DEFAULT '',
  `sourceid` mediumint(8) NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `edittime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',  
  `publtime` int(11) unsigned NOT NULL DEFAULT '0',
  `exptime` int(11) unsigned NOT NULL DEFAULT '0',
  `archive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `hometext` mediumtext NOT NULL,
  `homeimgfile` varchar(255) NOT NULL DEFAULT '',
  `homeimgalt` varchar(255) NOT NULL DEFAULT '',
  `homeimgthumb` varchar(255) NOT NULL DEFAULT '',
  `imgposition` tinyint(1) NOT NULL DEFAULT '1',
  `bodytext` mediumtext NOT NULL,
  `copyright` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ratingdetail` varchar(255) NOT NULL DEFAULT '',
  `allowed_send` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_print` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_save` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hitstotal` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hitscm` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hitslm` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topicid` (`topicid`),
  KEY `admin_id` (`admin_id`),
  KEY `author` (`author`),
  KEY `title` (`title`),
  KEY `addtime` (`addtime`),
  KEY `publtime` (`publtime`),
  KEY `exptime` (`exptime`),
  KEY `listcatid` (`listcatid`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_log` (
   `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(11) NOT NULL DEFAULT '0',
  `log_admin` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `listcatid` varchar(255) NOT NULL DEFAULT '',
  `topicid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `admin_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `author` varchar(255) NOT NULL DEFAULT '',
  `sourceid` mediumint(8) NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  `edittime` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',  
  `publtime` int(11) unsigned NOT NULL DEFAULT '0',
  `exptime` int(11) unsigned NOT NULL DEFAULT '0',
  `archive` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `hometext` mediumtext NOT NULL,
  `homeimgfile` varchar(255) NOT NULL DEFAULT '',
  `homeimgalt` varchar(255) NOT NULL DEFAULT '',
  `homeimgthumb` varchar(255) NOT NULL DEFAULT '',
  `imgposition` tinyint(1) NOT NULL DEFAULT '1',
  `bodytext` mediumtext NOT NULL,
  `copyright` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ratingdetail` varchar(255) NOT NULL DEFAULT '',
  `allowed_send` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_print` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allowed_save` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hitstotal` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hitscm` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hitslm` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `keywords` text NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `id` (`id`),
  KEY `topicid` (`topicid`),
  KEY `admin_id` (`admin_id`),
  KEY `author` (`author`),
  KEY `title` (`title`),
  KEY `addtime` (`addtime`),
  KEY `publtime` (`publtime`),
  KEY `exptime` (`exptime`),
  KEY `listcatid` (`listcatid`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config_post` (
  `pid` mediumint(9) NOT NULL AUTO_INCREMENT,
  `member` tinyint(4) NOT NULL,
  `group_id` mediumint(9) NOT NULL,
  `addcontent` tinyint(4) NOT NULL,
  `postcontent` tinyint(4) NOT NULL,
  `editcontent` tinyint(4) NOT NULL,
  `delcontent` tinyint(4) NOT NULL,
  PRIMARY KEY  (`pid`),
  UNIQUE KEY `member` (`member`,`group_id`)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'indexfile', 'viewcat_main_right')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'per_page', '20')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'st_links', '10')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'auto_postcomm', '1')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'homewidth', '100')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'homeheight', '80')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'blockwidth', '52')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'blockheight', '60')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'imagefull', '460')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'setcomm', '2')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'copyright', '')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'showhometext', '1')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'activecomm', '1')";
$sql_create_module[] = "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $module_name . "', 'emailcomm', '1')";

?>