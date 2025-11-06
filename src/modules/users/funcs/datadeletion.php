<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

$nv_BotManager->setPrivate();

// Xử lý cho trường hợp gửi yêu cầu xóa dữ liệu cá nhân
$sender = $array_op[1] ?? '';
if ($sender == 'facebook') {
    $signed_request = $nv_Request->get_string('signed_request', 'post', '', false, false);
    $signed_request = explode('.', $signed_request);
    if (empty($signed_request[1])) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_request',
            'message' => 'Invalid signed request'
        ]);
    }

    // Tách JWT
    $encoded_sig = $signed_request[0];
    $payload = $signed_request[1];

    $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
    $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

    // Kiểm tra chữ ký
    $expected_sig = hash_hmac('sha256', $payload, $secret, true);
    if ($sig !== $expected_sig) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_signature',
            'message' => 'Bad Signed JSON signature!'
        ]);
    }

    // Kiểm tra data hợp lệ
    if (!is_array($data) or empty($data['user_id']) or empty($data['issued_at'])) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_data',
            'message' => 'Invalid data in signed request'
        ]);
    }

    // FIXME Xử lý
}

$code = $nv_Request->get_title('code', 'get', '');
if (empty($code)) {
    nv_error404();
}

//Giữ trang trạng thái này hoạt động ít nhất 7–30 ngày sau yêu cầu xóa

nv_error404();

$contents = 'datadeletion';

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
