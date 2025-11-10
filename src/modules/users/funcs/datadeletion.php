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
$page_title = $nv_Lang->getModule('datadeletion');
$description = $keywords = 'no';

$confirmation_code = nv_uuid4();
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['datadeletion'];
$url = urlRewriteWithDomain(str_replace('&amp;', '&', $page_url . '&amp;code=' . $confirmation_code), NV_MY_DOMAIN);
$offset_time = NV_CURRENTTIME - (7 * 86400);

// Xử lý cho trường hợp gửi yêu cầu xóa dữ liệu cá nhân
$sender = $array_op[1] ?? '';
if ($sender == 'facebook') {
    $page_url .= '/facebook';
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
    $expected_sig = hash_hmac('sha256', $payload, $global_config['facebook_client_secret'], true);
    if ($sig !== $expected_sig) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_signature',
            'message' => 'Bad Signed JSON signature!'
        ]);
    }

    // Kiểm tra data hợp lệ
    if (
        !is_array($data) or empty($data['user_id']) or
        empty($data['issued_at']) or !is_int($data['issued_at']) or
        empty($data['expires']) or !is_int($data['expires']) or $data['expires'] < NV_CURRENTTIME
    ) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_data',
            'message' => 'Invalid data in signed request'
        ]);
    }

    $opid = $crypt->hash($data['user_id']);

    // Xác định xem đã xóa chưa, đã xóa thì báo thành công và kết thúc. Trạng thái check trong 7 ngày, sau đó vô hiệu
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_deleted WHERE request_time>=" . $offset_time . " AND
    request_source='facebook' AND opid=" . $db->quote($opid);
    $deleted = $db->query($sql)->fetch();
    if (!empty($deleted)) {
        nv_jsonOutput([
            'url' => $url,
            'confirmation_code' => $confirmation_code
        ]);
    }

    // Tìm tài khoản gắn với ID này
    $sql = "SELECT tb1.userid, tb2.md5username, tb2.email, tb2.active, tb2.photo, tb2.idsite FROM " . NV_MOD_TABLE . "_openid tb1
    INNER JOIN " . NV_MOD_TABLE . " tb2 ON tb1.userid=tb2.userid
    WHERE tb1.openid='facebook' AND tb1.opid=" . $db->quote($opid);
    $row = $db->query($sql)->fetch();
    if (empty($row) or empty($row['active']) or ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'])) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_data',
            'message' => 'User not found'
        ]);
    }

    // Tài khoản admin không thể xóa
    $sql = "SELECT COUNT(*) FROM " . NV_AUTHORS_GLOBALTABLE . " WHERE admin_id=" . $row['userid'];
    $sql2 = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_groups_users WHERE group_id IN (1,2,3) AND userid=" . $row['userid'];
    if ($db->query($sql)->fetchColumn() or $db->query($sql2)->fetchColumn()) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_data',
            'message' => 'Admin account cannot be deleted'
        ]);
    }

    $db->beginTransaction();
    try {
        $new_data = [];
        $new_data['username'] = 'deleteduser.' . nv_genpass(8);
        $new_data['first_name'] = 'User';
        $new_data['last_name'] = 'Deleted';
        $new_data['email'] = $new_data['username'] . '@' . NV_SERVER_NAME;

        // Xóa các dữ liệu liên quan
        $sql = "DELETE FROM " . NV_MOD_TABLE . "_info WHERE userid=" . $row['userid'];
        $db->query($sql);

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_info (userid) VALUES (" . $row['userid'] . ")";
        $db->query($sql);

        $sql = "DELETE FROM " . NV_MOD_TABLE . "_openid WHERE userid=" . $row['userid'];
        $db->query($sql);

        $sql = "DELETE FROM " . NV_MOD_TABLE . "_backupcodes WHERE userid=" . $row['userid'];
        $db->query($sql);

        $sql = "DELETE FROM " . NV_MOD_TABLE . "_edit WHERE userid=" . $row['userid'];
        $db->query($sql);

        $sql = "DELETE FROM " . NV_MOD_TABLE . "_login WHERE userid=" . $row['userid'];
        $db->query($sql);

        $sql = "DELETE FROM " . NV_MOD_TABLE . "_passkey WHERE userid=" . $row['userid'];
        $db->query($sql);

        // Hủy thông tin cá nhân
        $sql = "UPDATE " . NV_MOD_TABLE . " SET
            username=" . $db->quote($new_data['username']) . ",
            md5username=" . $db->quote(nv_md5safe($new_data['username'])) . ",
            email=" . $db->quote($new_data['email']) . ",
            first_name=" . $db->quote($new_data['first_name']) . ",
            last_name=" . $db->quote($new_data['last_name']) . ",
            gender='N', birthday=0, sig='', question='', answer='',
            photo='', active=0, checknum=''
        WHERE userid=" . $row['userid'];
        $db->query($sql);

        // Lưu ghi nhận đã xóa
        $sql = "INSERT INTO " . NV_MOD_TABLE . "_deleted (
            userid, md5username, md5email, request_source, opid, confirmation_code, request_time, issued_at
        ) VALUES (
            " . $row['userid'] . ", " . $db->quote($row['md5username']) . ", " . $db->quote(nv_md5safe($row['email'])) . ",
            'facebook', " . $db->quote($opid) . ", " . $db->quote($confirmation_code) . ",
            " . NV_CURRENTTIME . ", " . intval($data['issued_at']) . "
        )";
        $db->query($sql);

        $db->commit();
    } catch (Throwable $e) {
        $db->rollBack();
        trigger_error(print_r($e, true));
        http_response_code(500);
        nv_jsonOutput([
            'error' => 'server_error',
            'message' => $e->getMessage()
        ]);
    }

    // Xóa ảnh đại diện
    if (!empty($row['photo'])) {
        nv_deletefile(NV_ROOTDIR . '/' . $row['photo']);
    }

    nv_jsonOutput([
        'url' => $url,
        'confirmation_code' => $confirmation_code
    ]);
}

// Hiển thị trang trạng thái yêu cầu xóa dữ liệu cá nhân
$code = $nv_Request->get_title('code', 'get', '');
if (empty($code)) {
    nv_error404();
}
$page_url .= '&amp;code=' . urlencode($code);
$canonicalUrl = getCanonicalUrl($page_url);

// Giữ trang trạng thái này hoạt động ít nhất 7–30 ngày sau yêu cầu xóa
$sql = "SELECT * FROM " . NV_MOD_TABLE . "_deleted WHERE request_time>=" . $offset_time . " AND confirmation_code=" . $db->quote($code);
$data = $db->query($sql)->fetch();
if (empty($data)) {
    nv_error404();
}

$data['link_home'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;

$data = nv_apply_hook($module_name, 'prepare_user_data_deletion_show', [$data], $data);
$contents = user_data_deletion($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
