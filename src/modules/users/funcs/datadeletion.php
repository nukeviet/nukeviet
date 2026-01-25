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

use NukeViet\Module\users\Shared\Emails;

$page_title = $nv_Lang->getModule('datadeletion');
$description = $keywords = 'no';

$confirmation_code = nv_uuid4();
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['datadeletion'];
$offset_time = NV_CURRENTTIME - (10 * 86400);

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
    if ($nv_Request->isset_request('nv_redirect', 'get') and !empty($nv_redirect)) {
        $nv_Request->set_Session('nv_redirect_' . $module_data, $nv_redirect);
    }
} elseif ($nv_Request->isset_request('sso_redirect', 'get')) {
    $sso_redirect = $nv_Request->get_title('sso_redirect', 'get', '');
    if (!empty($sso_redirect)) {
        $nv_Request->set_Session('sso_redirect_' . $module_data, $sso_redirect);
    }
}
if (defined('SSO_CLIENT_DOMAIN')) {
    /** @disregard PHP0415 */
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $sso_client = $nv_Request->get_title('client', 'get', '');
    if (!empty($sso_client)) {
        if (!in_array($sso_client, $allowed_client_origin, true)) {
            // 406 Not Acceptable
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
        }
        $nv_Request->set_Session('sso_client_' . $module_data, $sso_client);
    }
}

if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/datadeletion.php';
    exit();
}

/**
 * Đặt lệnh chờ xóa tài khoản
 *
 * @param array $row
 * @return void
 */
function setPendingDeletion(array $row): void
{
    global $db, $module_name;

    $link_login = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login';
    $row['estimated_time'] = $row['estimated_time'] ?? (NV_CURRENTTIME + (7 * 86400));

    // Đánh dấu yêu cầu xóa vào database
    $sql = "UPDATE " . NV_MOD_TABLE . " SET delete_at=" . $row['estimated_time'] . ", checknum='' WHERE userid=" . $row['userid'];
    $db->query($sql);

    $sql = "UPDATE " . NV_MOD_TABLE . "_info SET deletion_checkcode='' WHERE userid=" . $row['userid'];
    $db->query($sql);

    $send_data = [[
        'to' => $row['email'],
        'data' => [
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'username' => $row['username'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'action_time' => $row['estimated_time'],
            'link' => urlRewriteWithDomain($link_login, NV_MY_DOMAIN),
            'lang' => NV_LANG_INTERFACE
        ]
    ]];
    nv_sendmail_template_async([$module_name, Emails::DELETE_ACCOUNT_PENDING], $send_data, NV_LANG_INTERFACE);
}

// Xử lý cho trường hợp gửi yêu cầu xóa dữ liệu cá nhân
$sender = $array_op[1] ?? '';
if ($sender == 'facebook') {
    /**
     * @param mixed $code
     * @return never
     */
    function jsonConfirmSuccess($code)
    {
        global $module_name, $module_info;

        $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['datadeletion'];
        $url = urlRewriteWithDomain(str_replace('&amp;', '&', $url . '&amp;code=' . $code), NV_MY_DOMAIN);
        nv_jsonOutput([
            'url' => $url,
            'confirmation_code' => $code
        ]);
    }

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

    // Tìm tài khoản gắn với ID này
    $sql = "SELECT
        tb1.userid, tb2.username, tb2.md5username, tb2.email, tb2.active, tb2.photo,
        tb2.idsite, tb2.delete_at, tb2.first_name, tb2.last_name, tb2.gender
    FROM " . NV_MOD_TABLE . "_openid tb1
    INNER JOIN " . NV_MOD_TABLE . " tb2 ON tb1.userid=tb2.userid
    WHERE tb1.openid='facebook' AND tb1.opid=" . $db->quote($opid);
    $row = $db->query($sql)->fetch();
    if (empty($row) or ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'])) {
        http_response_code(400);
        nv_jsonOutput([
            'error' => 'invalid_data',
            'message' => 'User not found'
        ]);
    }

    // Xác định xem đã xóa chưa, đã xóa thì báo thành công và kết thúc. Trạng thái check trong 10 ngày, sau đó vô hiệu
    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_deleted WHERE request_time>=" . $offset_time . " AND
    request_source='facebook' AND opid=" . $db->quote($opid) . " LIMIT 1";
    $deleted = $db->query($sql)->fetch();
    if (!empty($deleted)) {
        jsonConfirmSuccess($deleted['confirmation_code']);
    }

    // Liên kết nếu đã xóa thủ công
    if (!empty($row['delete_at'])) {
        // Tìm yêu cầu xóa thủ công nếu có
        $sql = "SELECT * FROM " . NV_MOD_TABLE . "_deleted WHERE userid=" . $row['userid'] . " AND request_source='' LIMIT 1";
        $deleted = $db->query($sql)->fetch();

        // Gắn code này cho yêu cầu nếu chưa có
        if (!empty($deleted)) {
            if (empty($deleted['confirmation_code'])) {
                $sql = "UPDATE " . NV_MOD_TABLE . "_deleted SET confirmation_code=" . $db->quote($confirmation_code) . " WHERE id=" . $deleted['id'];
                $db->query($sql);
            } else {
                // Lấy lại code cũ nếu đã có
                $confirmation_code = $deleted['confirmation_code'];
            }
        }

        jsonConfirmSuccess($confirmation_code);
    }

    // Kiểm tra tài khoản này có mật khẩu hay không
    $sql = "SELECT password FROM " . NV_MOD_TABLE . " WHERE userid=" . $row['userid'];
    $has_password = $db->query($sql)->fetchColumn() ? true : false;

    // Kiểm tra tài khoản này có Oauth khác Oauth đang yêu cầu xóa không
    $sql = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_openid WHERE userid=" . $row['userid'] . " AND NOT (openid='facebook' AND opid=" . $db->quote($opid) . ")";
    $has_other_oauth = $db->query($sql)->fetchColumn() ? true : false;

    // Xác định chế độ xóa
    $delete_mode = ($has_password or $has_other_oauth) ? 'oauth_only' : 'fully_account';

    if ($delete_mode == 'fully_account') {
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
    }

    if ($delete_mode == 'oauth_only') {
        // Xử lý xóa chỉ Oauth
        $db->beginTransaction();
        try {
            // Xóa liên kết Oauth
            $sql = "DELETE FROM " . NV_MOD_TABLE . "_openid WHERE openid='facebook' AND opid=" . $db->quote($opid) . " AND userid=" . $row['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_AUTHORS_GLOBALTABLE . "_oauth WHERE oauth_server='facebook' AND oauth_uid=" . $db->quote($opid) . " AND admin_id=" . $row['userid'];
            $db->query($sql);

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            trigger_error(print_r($e, true));
            http_response_code(500);
            nv_jsonOutput([
                'error' => 'server_error',
                'message' => 'Error duing to unlink OAuth account'
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'unlink_oauth_account', ' Client IP:' . NV_CLIENT_IP, $row['userid']);
    } else {
        // Xóa toàn bộ tài khoản
        setPendingDeletion($row);
    }

    // Lưu ghi nhận đã xóa
    $sql = "INSERT INTO " . NV_MOD_TABLE . "_deleted (
        userid, request_source, request_time, md5username, md5email, opid, confirmation_code, issued_at, status
    ) VALUES (
        " . $row['userid'] . ", 'facebook', " . NV_CURRENTTIME . ",
        " . $db->quote($delete_mode == 'fully_account' ? $row['md5username'] : '') . ",
        " . $db->quote($delete_mode == 'fully_account' ? nv_md5safe($row['email']) : '') . ",
        " . $db->quote($opid) . ", " . $db->quote($confirmation_code) . ",
        " . intval($data['issued_at']) . ",
        " . ($delete_mode == 'fully_account' ? 0 : NV_CURRENTTIME) . "
    )";
    $db->query($sql);

    jsonConfirmSuccess($confirmation_code);
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

    $sql = "SELECT delete_at FROM " . NV_MOD_TABLE . " WHERE userid=" . $data['userid'];
    $data['delete_at'] = $db->query($sql)->fetchColumn() ?: 0;

    $data['link_home'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;

    $data = nv_apply_hook($module_name, 'prepare_user_data_deletion_show', [$data], $data);
    $contents = user_data_deletion($data);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$page_title = $nv_Lang->getModule('delaccount_title');

if (!defined('NV_IS_USER')) {
    // Trường hợp người dùng đang ở đây, ấn thoát thì về trang chủ thay vì 404
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

$checkss = md5('datadeletion.' . NV_CHECK_SESSION);

$array = [];
$array['link_back'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy';
$array['link_login'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=login';
$array['link_logout'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=logout';
$array['link_home'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
$array['form_action'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$array['checkss'] = $nv_Request->get_title('checkss', 'post', '');
$array['error'] = '';
$array['userid'] = $user_info['userid'];
$array['username'] = $user_info['username'];
$array['email'] = $user_info['email'];
$array['first_name'] = $user_info['first_name'];
$array['last_name'] = $user_info['last_name'];
$array['gender'] = $user_info['gender'];
$array = nv_apply_hook($module_name, 'prepare_user_data_deletion', [$array], $array);

// Trường hợp tài khoản đang chờ xóa
if (!empty($user_info['delete_at'])) {
    $array['estimated_time'] = $user_info['delete_at'];
    $array['estimated_time_show'] = nv_datetime_format($array['estimated_time'], 1);
    $array['is_cancel'] = false;

    if (!empty($nv_redirect)) {
        $array['link_logout'] .= '&amp;nv_redirect=' . urlencode($nv_redirect);
    }

    // Hủy yêu cầu xóa
    if ($nv_Request->isset_request('checkss', 'post')) {
        if (!hash_equals($checkss, $array['checkss'])) {
            $array['error'] = 'Wrong session!!!';
        } else {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'cancel_request_deletion', ' Client IP:' . NV_CLIENT_IP, $user_info['userid']);

            $sql = "UPDATE " . NV_MOD_TABLE . " SET delete_at=0 WHERE userid=" . $user_info['userid'];
            $db->query($sql);

            $sql = "UPDATE " . NV_MOD_TABLE . "_info SET deletion_checkcode='' WHERE userid=" . $user_info['userid'];
            $db->query($sql);

            $sql = "DELETE FROM " . NV_MOD_TABLE . "_deleted WHERE userid=" . $user_info['userid'] . " AND request_source=''";
            $db->query($sql);

            $redirect = nv_redirect_decrypt($nv_redirect);
            if (defined('SSO_REGISTER_SECRET')) {
                $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
                $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
                $sso_redirect = NukeViet\Client\Sso::decrypt($sso_redirect);

                if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
                    $redirect = $sso_redirect;
                }

                $nv_Request->unset_request('sso_client_' . $module_data, 'session');
                $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
            }

            empty($redirect) && $redirect = $array['link_home'];

            $array['is_cancel'] = true;
            $array['link_back'] = $redirect;
            $array['link_change_pass'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/password';
            $array['link_security'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=security-privacy';
            $array['protect_account_message'] = $nv_Lang->getModule('delacc_cancel_success_after2', $array['link_change_pass'], $array['link_security']);

            // Gửi email thông báo hủy yêu cầu xóa
            $send_data = [[
                'to' => $user_info['email'],
                'data' => [
                    'first_name' => $user_info['first_name'],
                    'last_name' => $user_info['last_name'],
                    'username' => $user_info['username'],
                    'email' => $user_info['email'],
                    'gender' => $user_info['gender'],
                    'pass_link' => urlRewriteWithDomain($array['link_change_pass'], NV_MY_DOMAIN),
                    'link' => urlRewriteWithDomain($array['link_security'], NV_MY_DOMAIN),
                    'lang' => NV_LANG_INTERFACE
                ]
            ]];
            nv_sendmail_template_async([$module_name, Emails::DELETE_ACCOUNT_CANCEL], $send_data, NV_LANG_INTERFACE);
        }
    }

    $canonicalUrl = getCanonicalUrl($page_url);
    $contents = user_pending_deletion($array);
    $page_title = $nv_Lang->getModule('delacc_pending_title');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
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
    $canonicalUrl = getCanonicalUrl($page_url);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($not_allowed);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Kiểm tra đã xác nhận mật khẩu
$confirm_pwd = is_verified_password('datadeletion');
if (!$confirm_pwd) {
    if ($nv_Request->isset_request('resend_code', 'post')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('session_expired')
        ]);
    }

    if (!empty($nv_redirect)) {
        $page_url .= '&amp;nv_redirect=' . urlencode($nv_redirect);
    }
    go_verified_password('datadeletion', $page_url);
}

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

$array['submit_confirmed'] = (int) $nv_Request->get_bool('submit_confirmed', 'post', false);
$array['i_confirmed'] = (int) $nv_Request->get_bool('i_confirmed', 'post', false);
$array['verification_code'] = $nv_Request->get_title('verification_code', 'post', '');
$array['delete_accepted'] = false;

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
    $send_data = [[
        'to' => $user_info['email'],
        'data' => [
            'first_name' => $user_info['first_name'],
            'last_name' => $user_info['last_name'],
            'username' => $user_info['username'],
            'email' => $user_info['email'],
            'gender' => $user_info['gender'],
            'code' => $new_code,
            'lang' => NV_LANG_INTERFACE
        ]
    ]];
    nv_sendmail_template_async([$module_name, Emails::DELETE_ACCOUNT_SECCODE], $send_data, NV_LANG_INTERFACE);

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
        $array['estimated_time'] = NV_CURRENTTIME + (10 * 86400);
        $array['estimated_time_show'] = nv_datetime_format($array['estimated_time'], 1);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'manual_request_deletion', ' Client IP:' . NV_CLIENT_IP, $user_info['userid']);
        setPendingDeletion($array);

        $sql = "INSERT INTO " . NV_MOD_TABLE . "_deleted (
            userid, request_source, request_time, md5username, md5email, opid, confirmation_code, issued_at
        ) VALUES (
            " . $array['userid'] . ", '', " . NV_CURRENTTIME . ",
            " . $db->quote(nv_md5safe($array['username'])) . ",
            " . $db->quote(nv_md5safe($array['email'])) . ",
            '', '', 0
        )";
        $db->query($sql);

        // Logout toàn bộ ra khỏi hệ thống
        if (defined('NV_IS_USER_FORUM') or defined('SSO_SERVER')) {
            require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/logout.php';
        } else {
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid=' . $user_info['userid']);
            NukeViet\Core\User::unset_userlogin_hash();
            if ($user_info['current_mode'] == 4 and module_file_exists('users/login/cas-' . $user_info['openid_server'] . '.php')) {
                define('CAS_LOGOUT_URL_REDIRECT', $url_redirect);
                include NV_ROOTDIR . '/modules/users/login/cas-' . $user_info['openid_server'] . '.php';
            }
        }

        clear_verified_password();
        $contents = user_success_deletion($array);
        $canonicalUrl = getCanonicalUrl($page_url);

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

$array['time_code_remain'] = 120 - (NV_CURRENTTIME - $array['time_code']);
$array['time_code_remain'] < 0 && $array['time_code_remain'] = 0;

$email_hint = substr($user_info['email'], 0, 3) . '***' . substr($user_info['email'], -6);
$array['message_checkmail'] = $nv_Lang->getModule('delaccount_veremail_checkinfo', $email_hint);

$contents = user_request_deletion($array);
$canonicalUrl = getCanonicalUrl($page_url);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
