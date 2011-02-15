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
    if ( $global_config['revision'] < 900 )
    {
        //$result = $db->sql_query( $sql );
	    //if ( ! $result )
	    // {
	    //   $error_contents[] = 'error update sql';
	    //}
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