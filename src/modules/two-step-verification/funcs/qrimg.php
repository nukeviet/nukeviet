<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

$sess_secretkey = json_decode($nv_Request->get_string($module_data . '_secretkey', 'session', ''), true);
if (!is_array($sess_secretkey)) {
    $sess_secretkey = [];
}
if (empty($sess_secretkey['secretkey']) or !csrf_check($sess_secretkey['csrf'] ?? '', $module_data . '_secretkey')) {
    $data = '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="50">
        <text x="10" y="30" font-family="Arial" font-size="14" fill="black">' . $nv_Lang->getModule('qr_expried') . '</text>
    </svg>';
    nv_htmlOutput($data, 'svg', ['Content-Disposition' => 'inline; filename="session-expried.svg";']);
}

$url = 'otpauth://totp/' . $user_info['email'] . '?secret=' . $sess_secretkey['secretkey'] . '&issuer=' . urlencode(NV_SERVER_NAME . ' | ' . $user_info['username']);

// instantiate the barcode class
$barcode = new Com\Tecnick\Barcode\Barcode();
// generate a barcode
$bobj = $barcode->getBarcodeObj(
    'QRCODE,H',  // barcode type and additional comma-separated parameters
    $url,        // data string to encode
    -4,         // bar width (use absolute or negative value as multiplication factor)
    -4,         // bar height (use absolute or negative value as multiplication factor)
    'black',     // foreground color
    [-2, -2, -2, -2] // padding (use absolute or negative values as multiplication factors)
)->setBackgroundColor('white'); // background color
$data = $bobj->getSvgCode();

nv_htmlOutput($data, 'svg', ['Content-Disposition' => 'inline; filename="' . md5($url) . '.svg";']);
