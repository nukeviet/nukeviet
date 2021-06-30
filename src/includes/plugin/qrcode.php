<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if ($nv_Request->get_string('second', 'get') == 'qr') {
    $url = $nv_Request->get_string('u', 'get', '');
    $level = $nv_Request->get_title('l', 'get', 'M');
    $ModuleSize = $nv_Request->get_int('ppp', 'get', 4);
    $outer_frame = $nv_Request->get_int('of', 'get', 1);

    $_ErrorCorrection = [
        'L' => 'low',
        'M' => 'medium',
        'Q' => 'quartile',
        'H' => 'high'
    ];
    if (!empty($url) and isset($_ErrorCorrection[$level]) and ($ModuleSize > 0 and $ModuleSize < 13) and ($outer_frame > 0 and $outer_frame < 6)) {
        // Readmore: https://github.com/endroid/QrCode and http://www.qrcode.com/en/about/version.html
        $qrCode = new Endroid\QrCode\QrCode();

        header('Content-type: image/png');
        $qrCode->setText($url)
            ->setErrorCorrection($_ErrorCorrection[$level])
            ->setModuleSize($ModuleSize)
            ->setImageType('png')
            ->render();
    }
    exit();
}
