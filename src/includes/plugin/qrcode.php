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
    $size = $nv_Request->get_int('s', 'get', 150);
    $margin = $nv_Request->get_int('m', 'get', 5);

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
        $qrCode->setSize($size);
        $qrCode->setMargin($margin);

        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
    }
    exit();
}
