<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
        if (!empty($url)) {
            // instantiate the barcode class
            $barcode = new Com\Tecnick\Barcode\Barcode();
            // generate a barcode
            $bobj = $barcode->getBarcodeObj(
                'QRCODE,H',  // barcode type and additional comma-separated parameters
                $url,        // data string to encode
                160,         // bar width (use absolute or negative value as multiplication factor)
                160,         // bar height (use absolute or negative value as multiplication factor)
                'black',     // foreground color
                [5, 5, 5, 5] // padding (use absolute or negative values as multiplication factors)
            )->setBackgroundColor('white'); // background color
            $data = $bobj->getSvgCode();
            header('Content-Type: image/svg+xml');
            header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
            header('Pragma: public');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Content-Disposition: inline; filename="' . md5($url) . '.svg";');
            header('access-control-allow-origin: *');
            header('Vary: Accept-Encoding');
            if (empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                // the content length may vary if the server is using compression
                header('Content-Length: ' . strlen($data));
            }
            echo $data;
        }
        exit();
    }
});
