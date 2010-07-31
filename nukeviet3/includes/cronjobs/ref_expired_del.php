<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_ref_expired_del()
{
    $log_path = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs';
    if ( ! is_dir( $log_path ) )
    {
        @nv_mkdir( NV_ROOTDIR . '/' . NV_LOGS_DIR, 'ref_logs', true );
    }

    $log_start = mktime( 0, 0, 0, date( "n", NV_CURRENTTIME ), 1, date( "Y", NV_CURRENTTIME ) );

    $logfiles = nv_scandir( $log_path, "/^[0-9]{10,12}\." . preg_quote( NV_LOGS_EXT ) . "$/" );
    
    $result = true;
    
    if ( ! empty( $logfiles ) )
    {
        foreach ( $logfiles as $logfile )
        {
            unset( $matches );
            preg_match( "/^([0-9]{10,12})\." . preg_quote( NV_LOGS_EXT ) . "$/", $logfile, $matches );
            $d = ( int )$matches[1];
            if ( $d < $log_start )
            {
                if ( ! @unlink( $log_path . '/' . $logfile ) )
				{
					$result = false;
				}
            }
        }
    }
    
    return $result;
}

?>