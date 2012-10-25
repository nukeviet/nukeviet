<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 23/12/2010, 18:6
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

if ( ! isset( $Diagnostic ) or ! is_object( $Diagnostic ) )
{
    if ( ! class_exists( 'Diagnostic' ) )
    {
        include ( NV_ROOTDIR . "/includes/class/Diagnostic.class.php" );
    }

    $Diagnostic = new Diagnostic();
}

/**
 * cron_siteDiagnostic_update()
 * 
 * @return
 */
function cron_siteDiagnostic_update()
{
    global $Diagnostic;

    $cacheFile = $Diagnostic->currentCache;
    $updtime = 0;
    
    if ( file_exists( $cacheFile ) )
    {
        $updtime = @filemtime( $cacheFile );
    }

    $currentMonth = mktime( 0, 0, 0, date( "m", NV_CURRENTTIME ), 1, date( "Y", NV_CURRENTTIME ) );
    
    if ( $updtime < $currentMonth )
    {
        $info = $Diagnostic->process( 1 );
    }

    return true;
}

?>