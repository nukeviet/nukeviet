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
if (!empty($code)) {
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
}

$page_title = $nv_Lang->getModule('delaccount_title');

if (!defined('NV_IS_USER')) {
    nv_error404();
}

$not_allowed = '';
$sql = "SELECT COUNT(*) FROM " . NV_AUTHORS_GLOBALTABLE . " WHERE admin_id=" . $user_info['userid'];
$sql2 = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_groups_users WHERE group_id IN (1,2,3) AND userid=" . $user_info['userid'];
if ($db->query($sql)->fetchColumn() or $db->query($sql2)->fetchColumn()) {
    // Không thể xóa tài khoản quản trị
    $not_allowed = nv_theme_alert($nv_Lang->getGlobal('admin_account'), $nv_Lang->getModule('delaccount_noadmin'), 'warning');
} elseif (!empty($user_info['safemode'])) {
    // Chế độ an toàn được bật thì không làm gì
    $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/safeshow&amp;nv_redirect=' . nv_redirect_encrypt(nv_url_rewrite($page_url, true));
    $not_allowed = nv_theme_alert($nv_Lang->getModule('safe_mode'), $nv_Lang->getModule('delaccount_nosafemode', $url));
}

if ($not_allowed) {
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($not_allowed);
    include NV_ROOTDIR . '/includes/footer.php';
}

$array = [];
$array['link_back'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy';
$array['form_action'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

// Kiểm tra đã xác nhận mật khẩu
$confirm_pwd = $nv_Request->get_string($module_data . '_confirm_pwd', 'session', '');
$confirm_pwd = $confirm_pwd ? json_decode($confirm_pwd, true) : [];
if (!is_array($confirm_pwd) or !isset($confirm_pwd['time']) or (NV_CURRENTTIME - $confirm_pwd['time'] > 1800) or !isset($confirm_pwd['area']) or $confirm_pwd['area'] !== 'datadeletion') {
    $confirm_pwd = false;
} else {
    $confirm_pwd = true;
}

if (!$confirm_pwd) {
    if ($nv_Request->isset_request('resend_code', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('session_expired')
        ]);
    }

    $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=verify-password&area=datadeletion&nv_redirect=' . nv_redirect_encrypt(nv_url_rewrite($page_url, true));
    $nv_redirect = nv_url_rewrite($nv_redirect, true);
    nv_redirect_location($nv_redirect);
}

$checkss = md5('datadeletion.' . NV_CHECK_SESSION);

$sql = "SELECT * FROM " . NV_MOD_TABLE . "_info WHERE userid=" . $user_info['userid'];
$user_more_info = $db->query($sql)->fetch();
if (empty($user_more_info)) {
    http_response_code(500);
    trigger_error('User more info not found', E_USER_ERROR);
    exit(1);
}
$user_more_info['deletion_checkcode'] = empty($user_more_info['deletion_checkcode']) ? [] : explode('|', $user_more_info['deletion_checkcode']);
$array['current_code'] = $user_more_info['deletion_checkcode'][0] ?? '';
$array['time_code'] = intval($user_more_info['deletion_checkcode'][1] ?? 0);
$array['checkss'] = $nv_Request->get_title('checkss', 'post', '');

$array['submit_confirmed'] = (int) $nv_Request->get_bool('submit_confirmed', 'post', false);
$array['i_confirmed'] = (int) $nv_Request->get_bool('i_confirmed', 'post', false);
$array['verification_code'] = $nv_Request->get_title('verification_code', 'post', '');
$array['delete_accepted'] = false;
$array['error'] = '';

// Gửi lại mã
if (
    ($array['submit_confirmed'] or $nv_Request->isset_request('resend_code', 'post')) and
    (empty($array['current_code']) or (NV_CURRENTTIME - $array['time_code'] >= 120))
) {
    if (!$array['submit_confirmed'] and !hash_equals($checkss, $array['checkss'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Wrong session!!!'
        ]);
    }

    // Tạo mã xác nhận mới
    $new_code = strtoupper(nv_genpass(10));
    $user_more_info['deletion_checkcode'] = $new_code . '|' . NV_CURRENTTIME;

    $sql = "UPDATE " . NV_MOD_TABLE . "_info SET deletion_checkcode=" . $db->quote($user_more_info['deletion_checkcode']) . " WHERE userid=" . $user_info['userid'];
    $db->query($sql);

    // Gửi email chứa mã xác nhận

    if (!$array['submit_confirmed']) {
        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('send_success_code')
        ]);
    }

    $array['time_code'] = NV_CURRENTTIME;
}

// Submit xác nhận xóa
if (!empty($array['verification_code'])) {
    if (!hash_equals($checkss, $array['checkss'])) {
        $array['error'] = 'Wrong session!!!';
    } elseif ($array['verification_code'] !== $array['current_code']) {
        $array['error'] = $nv_Lang->getModule('lostpass_active_error');
    } else {
        // Xác nhận xóa thành công
        $array['delete_accepted'] = true;
    }
}

$array['time_code_remain'] = 120 - (NV_CURRENTTIME - $array['time_code']);
$array['time_code_remain'] < 0 && $array['time_code_remain'] = 0;

$email_hint = substr($user_info['email'], 0, 3) . '***' . substr($user_info['email'], -6);
$array['message_checkmail'] = $nv_Lang->getModule('delaccount_veremail_checkinfo', $email_hint);

$contents = user_request_deletion($array);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
