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
    global $global_config, $db_config, $db, $error_contents, $language_array;
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
        $array_config_rewrite = array( 'rewrite_optional' => $global_config['rewrite_optional'] );
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
    
    if ( $global_config['revision'] < 1150 )
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query( $sql );
        while ( list( $lang_i ) = $db->sql_fetchrow( $result ) )
        {
            $db->sql_query( "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "__menu`" );
            $db->sql_query( "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "__rows`" );
            
            $sql = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_menu_rows` (
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
            
            $sql = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_menu_menu` (
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
    
    if ( $global_config['revision'] < 1123 )
    {
        $db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'getloadavg', '0')" );
    }
    
    if ( $global_config['revision'] < 1135 )
    {
        $db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'update_revision_lang_mode', '1')" );
    }
    
    if ( $global_config['revision'] < 1157 )
    {
        $db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'allowquestion', '1')" );
        $db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'allowuserpublic', '0')" );
        $db->sql_query( "ALTER TABLE `" . NV_GROUPS_GLOBALTABLE . "` ADD `weight` smallint(4) unsigned NOT NULL DEFAULT '0' AFTER `public`" );
    }
    if ( $global_config['revision'] < 1174 )
    {
        $db->sql_query( "ALTER TABLE `" . NV_GROUPS_GLOBALTABLE . "` ADD `weight` int(11) unsigned NOT NULL DEFAULT '0' AFTER `public`" );
        
        $sql = "SELECT `group_id` FROM `" . NV_GROUPS_GLOBALTABLE . "` ORDER BY `group_id`";
        $result = $db->sql_query( $sql );
        $weight = 0;
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $weight ++;
            $db->sql_query( "UPDATA `" . NV_GROUPS_GLOBALTABLE . "` SET `weight` =" . $weight . " WHERE `group_id`= " . $row['group_id'] );
        }
        
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query( $sql );
        while ( list( $lang_i ) = $db->sql_fetchrow( $result ) )
        {
            $regroups_func_id = $db->sql_query_insert_id( "INSERT INTO `" . $db_config['prefix'] . "_modfuncs` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `layout`, `setting`) VALUES(NULL, 'regroups', 'Regroups', 'users', 1, 0, 1, 'left-body-right', '')" );
            
            list( $user_main_func_id ) = $db->sql_fetchrow( $db->sql_query( "SELECT `func_id` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='users' AND `func_name`='main'" ) );
            
            $result_blocks_weight = $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` WHERE `func_id`= " . $user_main_func_id );
            while ( $row = $db->sql_fetchrow( $result_blocks_weight ) )
            {
                $db->sql_query( "INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $row['bid'] . "', '" . $regroups_func_id . "', '" . $row['weight'] . "')" );
            }
        }
        
        $db->sql_query( "ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "_file` CHANGE `admin_file` `admin_file` VARCHAR( 255 ) NOT NULL DEFAULT '0'" );
    }
	
    if ( $global_config['revision'] < 1209 )
    {
		$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%\_modules'" );
		$num_table = intval( $db->sql_numrows( $result ) );
		
		$array_update_lang = array();
		
		if ( $num_table > 0 )
		{
			while ( $item = $db->sql_fetch_assoc( $result ) )
			{
				$item['Name'] = explode( "_", $item['Name'] );
				
				if( isset( $item['Name'][1] ) )
				{
					if( in_array( $item['Name'][1], array_keys( $language_array ) ) )
					{
						$array_update_lang[] = $item['Name'][1];
					}
				}
			}
		}
		
		foreach( $array_update_lang as $langupdate )
		{
			$sql = "SELECT `title` FROM `" . $db_config['prefix'] . "_" . $langupdate . "_modules` WHERE `module_file`='download'";
			$resultq = $db->sql_query( $sql );
			
			while ( list( $module_update ) = $db->sql_fetchrow( $resultq ) )
			{
				$array_table = array( "", "_tmp" );
				foreach( $array_table as $table )
				{
					$sql = "SELECT `id`, `fileupload`, `fileimage` FROM `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "`";
					$result = $db->sql_query( $sql );
					while ( list( $id, $fileupload, $fileimage ) = $db->sql_fetchrow( $result ) )
					{
						if( ! empty( $fileimage ) )
						{
							if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $fileimage ) )
							{
								$fileimage = substr ( $fileimage, strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
								
								$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "` SET `fileimage`=" . $db->dbescape( $fileimage ) . " WHERE `id`=" . $id );
							}
						}
						
						if( ! empty( $fileupload ) )
						{
							$fileupload = explode( "[NV]", $fileupload );
							$array_fileupload = array();
							foreach( $fileupload as $file )
							{
								if ( preg_match( "/^" . str_replace( "/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR ) . "\//", $file ) )
								{
									$file = substr ( $file, strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
									$array_fileupload[] = $file;
								}
							}
								
							$fileupload = implode( "[NV]", $array_fileupload );
							$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "` SET `fileupload`=" . $db->dbescape( $fileupload ) . " WHERE `id`=" . $id );
						}
					}
				}
			}
		}
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