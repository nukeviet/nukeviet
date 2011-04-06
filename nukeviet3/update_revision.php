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
        $array_config_rewrite = array( 'rewrite_optional' => $global_config['rewrite_optional'] );
        nv_rewrite_change( $array_config_rewrite );
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