<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Feb 15, 2011  3:37:23 PM
 */

define( 'NV_ADMIN', true );

require_once ( str_replace( '\\\\', '/', dirname( __file__ ) ) . '/mainfile.php' );
require_once ( NV_ROOTDIR . "/includes/core/admin_functions.php" );
require_once ( NV_ROOTDIR . "/includes/rewrite.php" );

if( defined( "NV_IS_GODADMIN" ) )
{
	if( $global_config['revision'] < 1491 )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'statistics_timezone', '" . NV_SITE_TIMEZONE_NAME . "')" );
	}
	if( $global_config['revision'] < 1501 )
	{
		$db->sql_query( "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "` CHANGE `birthday` `birthday` INT(11) NOT NULL" );
	}
	if( $global_config['revision'] < 1559 )
	{
		$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
		while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_voting_rows` ADD `url` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `title`" );
			$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `show_func` = '1' WHERE `in_module`='voting' AND `func_name`='main'" );

			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_modules` ADD `admin_title` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `custom_title`" );
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_modules` ADD `main_file` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `set_time`" );

			$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modules` SET `main_file` = '0' WHERE `module_file`='menu'" );
		}
	}
	if( $global_config['revision'] < 1576 )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'captcha_type', '0')" );

		$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
		while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
		{
			$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', 'global', 'switch_mobi_des', '1')" );
		}
	}
	if( $global_config['revision'] < 1587 )
	{
		nv_deletefile( NV_ROOTDIR . '/files/js', true );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/nav_menu.css' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/reset.css' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/tab_info.css' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/footer_bg.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/logo.gif' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_l.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_l.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_r.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_r.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_home_l.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_home_r.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_hover_l.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_hover_r.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_l.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_r.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_sub_a.jpg' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/navmenu-v_hover.jpg' );
	}
	if( $global_config['revision'] < 1590 )
	{
		$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
		while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_voting` ADD `link` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `question`" );
		}
	}
	if( $global_config['revision'] < 1592 ) // Cap nhat CSDL module menu
	{
		$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
		while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
		{
			$mquery = $db->sql_query( "SELECT `title`, `module_data` FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `module_file`='menu'" );
			while( list( $mod, $mod_data ) = $db->sql_fetchrow( $mquery ) )
			{
				$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows` ADD `css` varchar(255) NOT NULL DEFAULT '' AFTER `target`, ADD `active_type` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `css`" );
			}
		}
	}
	if( $global_config['revision'] < 1597 ) // Xoa file thua giao dien mobile
	{
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/news.css' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/arrow_down.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/arrow_left_orange.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/bg_link_mod.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/bg_linked_mod.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_header.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_l.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_r.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/comment.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/comment_add.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/content-cat-title-current.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/content-cat-title-ul.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/icon-news.gif' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/module-header.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/other.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/other_link.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/save_file.png' );
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/user.png' );
	}

	// Cap nhat CSDL module upload
	if( $global_config['revision'] < 1604 )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologomod', '')" );
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize1', '50')" );
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize2', '40')" );
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize3', '30')" );
	}
	
	// Xoa file thua module menu
	if( $global_config['revision'] < 1633 )
	{
		nv_deletefile( NV_ROOTDIR . '/themes/admin_default/modules/menu/config.tpl' );
	}
		
	//Update revision
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'version', '3.4.00')" );
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '1685')" );

	$array_config_rewrite = array(
		'rewrite_optional' => $global_config['rewrite_optional'],
		'rewrite_endurl' => $global_config['rewrite_endurl'],
		'rewrite_exturl' => $global_config['rewrite_exturl']
	);
		
	nv_rewrite_change( $array_config_rewrite );
	nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/searchEngines.xml' );

	nv_save_file_config_global();

	die( "Update successfully, you should immediately delete this file." );
}
else
{
	die( "You need login with god administrator" );
}

?>