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

nv_add_hook($module_name, 'get_qr_code', $priority, function ($vars) {
    $nv_Request = $vars[0];
    if ($nv_Request->get_string('second', 'get') == 'qr') {
        $url = $nv_Request->get_string('u', 'get', '');
        $level = $nv_Request->get_title('l', 'get', 'M');
        $ModuleSize = $nv_Request->get_int('ppp', 'get', 4);
        $outer_frame = $nv_Request->get_int('of', 'get', 1);

        $_ErrorCorrection = [
            'L' => Endroid\QrCode\ErrorCorrectionLevel::LOW,
            'M' => Endroid\QrCode\ErrorCorrectionLevel::MEDIUM,
            'Q' => Endroid\QrCode\ErrorCorrectionLevel::QUARTILE,
            'H' => Endroid\QrCode\ErrorCorrectionLevel::HIGH
        ];
        if (!empty($url) and isset($_ErrorCorrection[$level]) and ($ModuleSize > 0 and $ModuleSize < 13) and ($outer_frame > 0 and $outer_frame < 6)) {
            // Readmore: https://github.com/endroid/QrCode and http://www.qrcode.com/en/about/version.html
            $qrCode = new Endroid\QrCode\QrCode($url);

            $qrCode->setSize(200);
            $qrCode->setWriterByName('png');
            $qrCode->setMargin($ModuleSize);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel($_ErrorCorrection[$level]);

            header('Content-Type: ' . $qrCode->getContentType());
            echo $qrCode->writeString();
        }
        exit();
    }
});
