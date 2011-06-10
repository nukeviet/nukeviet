<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Feb 15, 2011  3:37:23 PM
 */

if ( ! defined( 'NV_AUTOUPDATE' ) ) die( 'Stop!!!' );

function nv_func_update_data ( )
{
    global $global_config, $db_config, $db, $error_contents;
    // Update data
    if ( $global_config['revision'] < 902 )
    {
        $sql = "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "_reg` CHANGE `userid` `userid` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT";
        $result = $db->sql_query( $sql );
        if ( ! $result )
        {
            $error_contents[] = 'error update sql revision: 902';
        }
    }
    if ( $global_config['revision'] < 988 )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'rewrite_endurl', '/')" );
        $array_config_rewrite = array( 
            'rewrite_optional' => $global_config['rewrite_optional'] 
        );
        nv_rewrite_change( $array_config_rewrite );
    }
    
    if ( $global_config['revision'] < 1004 )
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query( $sql );
        while ( list( $lang_i ) = $db->sql_fetchrow( $result ) )
        {
            $sql = "SELECT title FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='menu'";
            $result_mod = $db->sql_query( $sql );
            while ( list( $mod ) = $db->sql_fetchrow( $result_mod ) )
            {
                $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` WHERE `func_id` in (SELECT `func_id` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='" . $mod . "')" );
                $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='" . $mod . "'" );
            }
        }
        nv_delete_all_cache();
    }
    if ( $global_config['revision'] < 1042 )
    {
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autocheckupdate', '1')" );
        $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autoupdatetime', '24')" );
    }
    
    if ( $global_config['revision'] < 1071 )
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query( $sql );
        while ( list( $lang_i ) = $db->sql_fetchrow( $result ) )
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='faq'";
            $result_mod = $db->sql_query( $sql );
            while ( list( $mod, $mod_data ) = $db->sql_fetchrow( $result_mod ) )
            {
                $db->sql_query( "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_config` (`config_name` varchar(30) NOT NULL,  `config_value` varchar(255) NOT NULL,  UNIQUE KEY `config_name` (`config_name`))ENGINE=MyISAM" );
                $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_config` VALUES ('type_main', '0')" );
            }
        }
        nv_delete_all_cache();
    }
    if ( $global_config['revision'] < 1107 )
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query( $sql );
        while ( list( $lang_i ) = $db->sql_fetchrow( $result ) )
        {
            $sql = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $module_data . "_rows` (
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
			  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
			   PRIMARY KEY (`id`)
			) ENGINE=MyISAM";
            
            $db->sql_query( $sql );
            
            $sql = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $module_data . "_menu` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(50) NOT NULL,
			  `menu_item` mediumtext NOT NULL,
			  `description` varchar(255) NOT NULL DEFAULT '',
			   PRIMARY KEY (`id`),
			  UNIQUE KEY `title` (`title`)
			) ENGINE=MyISAM";
            $db->sql_query( $sql );
        }
        nv_delete_all_cache();
    
    }
    // End date data
    if ( empty( $error_contents ) )
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>