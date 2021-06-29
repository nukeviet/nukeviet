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
        $ModuleSize = 190 + (10 * $nv_Request->get_int('ppp', 'get', 1));
        $outer_frame = 2 * $nv_Request->get_int('of', 'get', 1);

        if ('L' == $level) {
            $_ErrorCorrection = Endroid\QrCode\ErrorCorrectionLevel::LOW();
        } elseif ('M' == $level) {
            $_ErrorCorrection = Endroid\QrCode\ErrorCorrectionLevel::MEDIUM();
        } elseif ('Q' == $level) {
            $_ErrorCorrection = Endroid\QrCode\ErrorCorrectionLevel::QUARTILE();
        } else {
            $_ErrorCorrection = Endroid\QrCode\ErrorCorrectionLevel::HIGH();
        }
        if (!empty($url)) {
            $qrCode = new Endroid\QrCode\QrCode($url);
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel($_ErrorCorrection);
            $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
            $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
            $qrCode->setSize($ModuleSize);
            $qrCode->setMargin($outer_frame);

            header('Content-Type: ' . $qrCode->getContentType());
            echo $qrCode->writeString();
        }
        exit();
    }
});
