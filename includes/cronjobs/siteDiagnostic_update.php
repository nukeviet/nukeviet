<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 23/12/2010, 18:6
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    die('Stop!!!');
}

/**
 * cron_siteDiagnostic_update()
 *
 * @return
 */
function cron_siteDiagnostic_update()
{
    $Diagnostic = new NukeViet\Client\Diagnostic();

    $cacheFile = $Diagnostic->currentCache;
    $updtime = 0;

    if (file_exists($cacheFile)) {
        $updtime = @filemtime($cacheFile);
    }

    $currentMonth = mktime(0, 0, 0, date('m', NV_CURRENTTIME), 1, date('Y', NV_CURRENTTIME));

    if ($updtime < $currentMonth) {
        $info = $Diagnostic->process(1);
    }

    return true;
}