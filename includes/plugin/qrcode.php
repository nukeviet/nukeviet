<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/29/2009 20:7
 */
if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if ($nv_Request->get_string('second', 'get') == 'qr') {
    $url = $nv_Request->get_string('u', 'get', '');
    if (!empty($url)) {
        // instantiate the barcode class
        $barcode = new Com\Tecnick\Barcode\Barcode();
        // generate a barcode
        $bobj = $barcode->getBarcodeObj(
            'QRCODE,H',                     // barcode type and additional comma-separated parameters
            $url,          // data string to encode
            160,                             // bar width (use absolute or negative value as multiplication factor)
            160,                             // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(5, 5, 5, 5)           // padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor('white'); // background color
        $bobj->getPng();
    }
    exit();
}
