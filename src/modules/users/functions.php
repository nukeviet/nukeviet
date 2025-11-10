<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

use NukeViet\Module\users\Shared\Emails;

define('NV_IS_MOD_USER', true);
define('NV_MOD_TABLE', ($module_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $module_data);

$nv_Lang->setModule('in_groups', $nv_Lang->getGlobal('in_groups'));
require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$nv_BotManager->setPrivate();

/**
 * get_checknum()
 *
 * @param mixed $userid
 * @return mixed
 */
function get_checknum($userid)
{
    global $db, $global_config;

    if (!empty($global_config['allowuserloginmulti'])) {
        $checknum = $db->query('SELECT checknum FROM ' . NV_MOD_TABLE . ' WHERE userid = ' . $userid)->fetchColumn();
        if (!empty($checknum)) {
            return $checknum;
        }
    }

    return md5(nv_genpass(10));
}

/**
 * Xác lập phiên đăng nhập của người dùng
 *
 * @param array $array_user
 * @param int   $remember
 * @param array $mode_data
 * @param int   $current_mode
 * @throws PDOException
 */
function validUserLog($array_user, $remember, $mode_data = [], $current_mode = 0)
{
    global $db, $global_config, $nv_Lang, $global_users_config, $module_name, $module_file, $client_info;

    $remember = (int) $remember;
    $checknum = get_checknum($array_user['userid']);
    $opid = $passkey = $mode_extra = '';
    if (!empty($mode_data)) {
        if (in_array($current_mode, [2, 3, 4], true)) {
            $opid = $mode_extra = $mode_data['id'];
        } elseif ($current_mode == 6) {
            $passkey = $mode_extra = $mode_data['nickname'];
        }
    }

    // Thay đổi rule tạo hash, login ở đây cần xem xét tương tự tại includes/core/admin_login.php
    $user = [
        'userid' => $array_user['userid'],
        'current_mode' => $current_mode,
        'checknum' => $checknum,
        'checkhash' => md5($array_user['userid'] . $checknum . $global_config['sitekey'] . $client_info['clid']),
        'current_agent' => NV_USER_AGENT,
        'prev_agent' => $array_user['last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'prev_ip' => $array_user['last_ip'],
        'current_login' => NV_CURRENTTIME,
        'prev_login' => (int) ($array_user['last_login']),
        'prev_openid' => $array_user['last_openid'],
        'current_openid' => $opid,
        'language' => $array_user['language']
    ];

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET
        checknum = :checknum,
        last_login = ' . NV_CURRENTTIME . ',
        last_ip = :last_ip,
        last_agent = :last_agent,
        last_openid = :opid,
        last_passkey = :passkey,
        remember = ' . $remember . '
        WHERE userid=' . $array_user['userid']);

    $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
    $stmt->bindValue(':opid', $opid, PDO::PARAM_STR);
    $stmt->bindValue(':passkey', $passkey, PDO::PARAM_STR);
    $stmt->execute();

    if ($global_config['allowuserloginmulti']) {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid=' . $array_user['userid'] . ' AND clid=' . $db->quote($client_info['clid']));
    } else {
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_login WHERE userid=' . $array_user['userid']);
    }

    $sth = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_login (
        userid, clid, logtime, mode, agent, ip, mode_extra
    ) VALUES (
        ' . $array_user['userid'] . ', :clid, ' . NV_CURRENTTIME . ', ' . $current_mode . ', :agent, :ip, :mode_extra
    )');
    $sth->bindValue(':clid', $client_info['clid'], PDO::PARAM_STR);
    $sth->bindValue(':agent', NV_USER_AGENT, PDO::PARAM_STR);
    $sth->bindValue(':ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $sth->bindValue(':mode_extra', $mode_extra, PDO::PARAM_STR);
    $sth->execute();

    NukeViet\Core\User::set_userlogin_hash($user, $remember);

    // Tạo thông báo nếu đăng nhập lần đầu
    if (empty($array_user['last_login'])) {
        $messages = [];
        foreach ($global_config['setup_langs'] as $lang) {
            if ($lang == NV_LANG_DATA) {
                $messages[NV_LANG_DATA] = $nv_Lang->getModule('welcome_new_account', $global_config['site_name']);
            } else {
                $site_name = $db->query('SELECT config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name='site_name' AND lang='" . $lang . "' AND module='global'")->fetchColumn();
                $nv_Lang->changeLang($lang);
                $nv_Lang->loadModule($module_file, false, true);
                $messages[$lang] = $nv_Lang->getModule('welcome_new_account', $site_name);
                $nv_Lang->changeLang();
            }
        }
        add_notification([
            'receiver_ids' => [$array_user['userid']],
            'isdef' => 'en',
            'message' => $messages,
            'link' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name, true)
        ]);
    }

    if (!empty($global_users_config['active_user_logs'])) {
        if (!empty($passkey)) {
            $log_message = $nv_Lang->getModule('mode_login_6') . ' ' . $passkey;
        } elseif (!empty($opid)) {
            $log_message = $nv_Lang->getModule('userloginviaopt') . ' ' . $mode_data['provider'];
        } else {
            $log_message = $nv_Lang->getModule('st_login');
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $array_user['username'] . '] ' . $log_message, ' Client IP:' . NV_CLIENT_IP, 0);
    }
}

/**
 * updateUserCookie()
 *
 * @param mixed $newValues
 */
function updateUserCookie($newValues)
{
    global $db, $user_info, $user_cookie;

    if (!empty($user_cookie)) {
        $isUpdate = false;
        if (!empty($newValues)) {
            foreach ($newValues as $key => $value) {
                if (isset($user_cookie[$key]) and $value != $user_cookie[$key]) {
                    $user_cookie[$key] = $value;
                    $isUpdate = true;
                }
            }
        }
        if ($isUpdate) {
            $remember = (int) $db->query('SELECT remember FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $user_info['userid'] . ' AND active=1')->fetchColumn();
            NukeViet\Core\User::set_userlogin_hash($user_cookie, $remember);
        }
    }
}

/**
 * Hàm kiểm tra email khả dụng
 *
 * @param mixed $email
 */
function nv_check_email_reg(&$email)
{
    global $db, $nv_Lang, $global_users_config, $global_config, $module_name;

    $error = nv_check_valid_email($email, true);
    $email = $error[1];
    if ($error[0] != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error[0]));
    }

    $custom_check = nv_apply_hook($module_name, 'check_email_already_exists', [$email]);
    if (!empty($custom_check)) {
        return $custom_check;
    }

    if (!empty($global_users_config['deny_email']) and preg_match('/' . $global_users_config['deny_email'] . '/i', $email)) {
        return $nv_Lang->getModule('email_deny_name', $email);
    }

    // Lấy email chính, không cho phép nhập alias. Nếu nhập alias cũng chuyển về email chính
    if (!empty($global_config['email_plus_equivalent'])) {
        [$left, $right] = explode('@', $email);
        $left = explode('+', $left)[0];
        $email = $left . '@' . $right;
    }

    if (!empty($global_config['email_dot_equivalent']) or !empty($global_config['email_plus_equivalent'])) {
        [$left, $right] = explode('@', $email);

        if (!empty($global_config['email_dot_equivalent'])) {
            $left = preg_replace('/[\.]+/', '', $left);
            $pattern = str_split($left);
            $pattern = implode('.?', $pattern);
        }
        if (!empty($global_config['email_plus_equivalent'])) {
            $pattern .= '(\\+.+)*';
        }
        $pattern = '^' . $pattern . '@' . $right . '$';

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email RLIKE :pattern');
        $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email RLIKE :pattern');
        $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email RLIKE :pattern');
        $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }
    } else {
        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }

        $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE email = :email');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('email_registered_name', $email);
        }
    }

    return '';
}

/**
 * nv_check_username_reg()
 * Ham kiem tra ten dang nhap kha dung
 *
 * @param mixed $login
 */
function nv_check_username_reg($login)
{
    global $db, $nv_Lang, $global_users_config, $global_config;

    // Tên đăng nhập hợp lệ
    $error = nv_check_valid_login($login, $global_config['nv_unickmax'], $global_config['nv_unickmin']);
    if ($error != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error));
    }
    if ("'" . $login . "'" != $db->quote($login)) {
        return $nv_Lang->getModule('account_deny_name', $login);
    }

    // Tên đăng nhập bị cấm
    if (!empty($global_users_config['deny_name']) and preg_match('/' . $global_users_config['deny_name'] . '/i', $login)) {
        return $nv_Lang->getModule('account_deny_name', $login);
    }

    // Kiểm tra tên đăng nhập trong tài khoản đã kích hoạt
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE username LIKE :username OR md5username= :md5username');
    $stmt->bindValue(':username', $login, PDO::PARAM_STR);
    $stmt->bindValue(':md5username', nv_md5safe($login), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return $nv_Lang->getModule('account_registered_name', $login);
    }

    // Kiểm tra tên đăng nhập trong tài khoản đợi kích hoạt
    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE username LIKE :username OR md5username= :md5username');
    $stmt->bindValue(':username', $login, PDO::PARAM_STR);
    $stmt->bindValue(':md5username', nv_md5safe($login), PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return $nv_Lang->getModule('account_registered_name', $login);
    }

    // Kiểm tra tên đăng nhập trong tài khoản đã xóa
    if ($global_users_config['hold_deleted_username'] > 0) {
        $sql = 'SELECT userid FROM ' . NV_MOD_TABLE . '_deleted WHERE md5username=:md5username';
        if ($global_users_config['hold_deleted_username'] < 1000) {
            $sql .= ' AND request_time>' . (NV_CURRENTTIME - $global_users_config['hold_deleted_username'] * 86400);
        }
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':md5username', nv_md5safe($login), PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            return $nv_Lang->getModule('account_registered_name', $login);
        }
    }

    return '';
}

/**
 * nv_del_user()
 *
 * @param int $userid
 * @return int
 * @throws PDOException
 */
function nv_del_user($userid)
{
    global $db, $global_config, $module_name, $user_info, $nv_Lang;

    $sql = 'SELECT group_id, username, first_name, last_name, gender, email, photo, in_groups, idsite, language
    FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch(3);
    if (empty($row)) {
        return 0;
    }

    [$group_id, $username, $first_name, $last_name, $gender, $email, $photo, $in_groups, $idsite, $lang] = $row;

    if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
        return 0;
    }

    $query = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id IN (1,2,3) AND userid=' . $userid);
    if ($query->fetchColumn()) {
        return 0;
    }

    $result = $db->exec('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
    if (!$result) {
        return 0;
    }

    $in_groups = array_map('intval', explode(',', $in_groups));

    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid . ' AND approved = 1)');
    $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=' . (($group_id == 7 or in_array(7, $in_groups, true)) ? 7 : 4));
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $userid);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid);
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_passkey WHERE userid=' . $userid);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $user_info['userid']);

    if (!empty($photo) and is_file(NV_ROOTDIR . '/' . $photo)) {
        @nv_deletefile(NV_ROOTDIR . '/' . $photo);
    }

    // Gửi email thông báo xóa tài khoản
    if (empty($lang)) {
        $lang = NV_LANG_INTERFACE;
    }
    $send_data = [[
        'to' => $email,
        'data' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,
            'email' => $email,
            'gender' => $gender,
            'lang' => $lang
        ]
    ]];
    nv_sendmail_template_async([$module_name, Emails::USER_DELETE], $send_data, $lang);

    nv_apply_hook($module_name, 'user_delete', [$userid, $row]);

    return $userid;
}

/**
 * opidr_login()
 *
 * @param array $openid_info
 */
function opidr_login($openid_info)
{
    global $nv_Request, $nv_redirect, $module_data;

    $nv_Request->unset_request('openid_attribs', 'session');

    $openid_info['redirect'] = nv_redirect_decrypt($nv_redirect);
    $openid_info['client'] = '';

    if (defined('SSO_REGISTER_SECRET')) {
        $sso_client = $nv_Request->get_title('sso_client_' . $module_data, 'session', '');
        $sso_redirect = $nv_Request->get_title('sso_redirect_' . $module_data, 'session', '');
        $sso_redirect = NukeViet\Client\Sso::decrypt($sso_redirect);

        if (!empty($sso_redirect) and !empty($sso_client) and str_starts_with($sso_redirect, $sso_client)) {
            $openid_info['redirect'] = $sso_redirect;
            $openid_info['client'] = $sso_client;
        }

        $nv_Request->unset_request('sso_client_' . $module_data, 'session');
        $nv_Request->unset_request('sso_redirect_' . $module_data, 'session');
    }

    $contents = openid_callback($openid_info);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * checkLoginName()
 *
 * @param string $type
 * @param string $name
 * @return mixed
 */
function checkLoginName($type, $name)
{
    global $db;

    $type != 'email' && $type = 'username';
    if ($type == 'email') {
        $where = 'email =' . $db->quote($name);
    } else {
        $where = 'md5username =' . $db->quote(nv_md5safe($name));
    }

    $row = $db->query('SELECT * FROM ' . NV_MOD_TABLE . ' WHERE ' . $where)->fetch();
    if (empty($row[$type])) {
        return false;
    }

    if (strcasecmp($row[$type], $name) !== 0) {
        return false;
    }

    return $row;
}

// Reset SSO client token
if (
    defined('SSO_CLIENT_DOMAIN') and $op == 'main' and ($array_op[0] ?? '') == 'login' and
    defined('NV_IS_USER') and isset($_GET['sso_reset'], $_GET['client'])
) {
    /** @disregard P1011 */
    $allowed_client_origin = explode(',', SSO_CLIENT_DOMAIN);
    $sso_client = $nv_Request->get_title('client', 'get', '');
    $sso_reset = $nv_Request->get_title('sso_reset', 'get', '');
    if (empty($sso_client) or empty($sso_reset) or !in_array($sso_client, $allowed_client_origin, true)) {
        // 406 Not Acceptable
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
    }

    $sso_reset = NukeViet\Client\Sso::decrypt($sso_reset);
    if (empty($sso_reset) or !str_starts_with($sso_reset, $sso_client)) {
        // 406 Not Acceptable
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 406);
    }

    nv_redirect_location($sso_reset, 307);
}

$group_id = 0;
// Xử lý cho trường hợp trưởng nhóm tạo và sửa thông tin tài khoản nhóm mình quản lý
if (defined('NV_IS_USER') and isset($array_op[1]) and ($array_op[0] == 'register' or $array_op[0] == 'editinfo')) {
    $sql = 'SELECT g.group_id, d.title, g.config FROM ' . NV_MOD_TABLE . '_groups g
    LEFT JOIN ' . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' )";
    $_query = $db->query($sql);
    $group_lists = [];
    while ($_row = $_query->fetch()) {
        $group_lists[$_row['group_id']] = $_row;
    }
    $_query->closeCursor();

    /*
     * Trường hợp trưởng nhóm truy cập sửa thông tin member thì $array_op[1]= group_id
     * ngoài trường hợp này thì $array_op[1] có thể là các string loại chỉnh sửa của thành viên thông thường
     */
    if (isset($group_lists[$array_op[1]])) {
        $sql = 'SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $array_op[1] . '
        AND userid = ' . $user_info['userid'] . ' AND is_leader = 1';
        $result = $db->query($sql);
        $row = $result->fetch();

        if (!empty($row)) {
            $group = $group_lists[$row['group_id']];
            $group['config'] = unserialize($group['config']);

            if ($group['config']['access_addus'] and $array_op[0] == 'register') {
                // Trưởng nhóm tạo tài khoản
                $op = 'register';
                $module_info['funcs'][$op] = $sys_mods[$module_name]['funcs'][$op];
                $group_id = $row['group_id'];
                define('ACCESS_ADDUS', $group['config']['access_addus']);
            } elseif ($group['config']['access_editus'] and $array_op[0] == 'editinfo' and !empty($array_op[2]) and is_numeric($array_op[2])) {
                // Trưởng nhóm sửa tài khoản trong nhóm mình
                $group_id = $row['group_id'];

                $result = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users
                WHERE group_id = ' . $group_id . ' AND userid = ' . intval($array_op[2]) . ' AND is_leader = 0');
                $row = $result->fetch();

                if (!empty($row)) {
                    // Nếu tài khoản nằm trong nhóm đó thì được quyền sửa
                    $userid = intval($array_op[2]);

                    if ($group['config']['access_passus']) {
                        define('ACCESS_PASSUS', $group['config']['access_passus']);
                    }
                    define('ACCESS_EDITUS', $group['config']['access_editus']);
                }
            }
        }
    }
}

// Upload file vào thư mục tmp
if ($nv_Request->isset_request('field_fileupload,field,_csrf', 'post')) {
    $field = $nv_Request->get_title('field', 'post', '');
    $field = preg_replace('/[^a-zA-Z0-9\-\_]+/', '', $field);
    if (empty($field)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Stop!!!'
        ]);
    }
    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . $field);
    $csrf = $nv_Request->get_title('_csrf', 'post', '');
    if (!hash_equals($checkss, $csrf)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Stop!!!'
        ]);
    }

    $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE user_editable = 1 AND field=' . $db->quote($field));
    $row_field = $result->fetch();
    if (empty($row_field) or $row_field['field_type'] != 'file') {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Stop!!!'
        ]);
    }

    if (empty($_FILES) or empty($_FILES['file']) or empty($_FILES['file']['tmp_name']) or empty($_FILES['file']['type']) or empty($_FILES['file']['size'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_empty')
        ]);
    }

    $limited_values = !empty($row_field['limited_values']) ? json_decode($row_field['limited_values'], true) : [];
    $file_allowed_ext = !empty($limited_values['filetype']) ? $limited_values['filetype'] : $global_config['file_allowed_ext'];
    $file_max_size = !empty($limited_values['file_max_size']) ? min($limited_values['file_max_size'], NV_UPLOAD_MAX_FILESIZE) : NV_UPLOAD_MAX_FILESIZE;
    $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], $file_max_size);
    $upload->setLanguage(\NukeViet\Core\Language::$lang_global);
    $upload_info = $upload->save_file($_FILES['file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, false);
    @unlink($_FILES['file']['tmp_name']);
    if (!empty($upload_info['error'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $upload_info['error']
        ]);
    }

    $extension = nv_getextension($upload_info['basename']);
    if ($extension != $upload_info['ext']) {
        @unlink($upload_info['name']);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_extension_does_not_match')
        ]);
    }

    if (!in_array($upload_info['ext'], $limited_values['mime'], true)) {
        @unlink($upload_info['name']);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('file_extension_not_accepted')
        ]);
    }

    if ($upload_info['is_img']) {
        $img_error = false;
        $width_mess = [];
        $height_mess = [];
        if (!empty($limited_values['widthlimit']['equal'])) {
            $width_mess[] = $nv_Lang->getModule('equal') . ' ' . $limited_values['widthlimit']['equal'] . ' px';
            if ($upload_info['img_info'][0] != $limited_values['widthlimit']['equal']) {
                $img_error = true;
            }
        } else {
            if (!empty($limited_values['widthlimit']['greater'])) {
                $width_mess[] = $nv_Lang->getModule('greater') . ' ' . $limited_values['widthlimit']['greater'] . ' px';
                if ($upload_info['img_info'][0] < $limited_values['widthlimit']['greater']) {
                    $img_error = true;
                }
            }
            if (!empty($limited_values['widthlimit']['less'])) {
                $width_mess[] = $nv_Lang->getModule('less') . ' ' . $limited_values['widthlimit']['less'] . ' px';
                if ($upload_info['img_info'][0] > $limited_values['widthlimit']['less']) {
                    $img_error = true;
                }
            }
        }

        if (!empty($limited_values['heightlimit']['equal'])) {
            $height_mess[] = $nv_Lang->getModule('equal') . ' ' . $limited_values['heightlimit']['equal'] . ' px';
            if ($upload_info['img_info'][1] != $limited_values['heightlimit']['equal']) {
                $img_error = true;
            }
        } else {
            if (!empty($limited_values['heightlimit']['greater'])) {
                $height_mess[] = $nv_Lang->getModule('greater') . ' ' . $limited_values['heightlimit']['greater'] . ' px';
                if ($upload_info['img_info'][1] < $limited_values['heightlimit']['greater']) {
                    $img_error = true;
                }
            }
            if (!empty($limited_values['heightlimit']['less'])) {
                $height_mess[] = $nv_Lang->getModule('less') . ' ' . $limited_values['heightlimit']['less'] . ' px';
                if ($upload_info['img_info'][1] > $limited_values['heightlimit']['less']) {
                    $img_error = true;
                }
            }
        }

        if ($img_error) {
            @unlink($upload_info['name']);
            $width_mess = !empty($width_mess) ? implode(', ', $width_mess) : '';
            $height_mess = !empty($height_mess) ? implode(', ', $height_mess) : '';
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('file_image_size_error') . ' (' . $upload_info['img_info'][0] . ' x ' . $upload_info['img_info'][1] . ' px)' . (!empty($width_mess) ? '. ' . $nv_Lang->getModule('file_image_width') . ' ' . $width_mess : '') . (!empty($height_mess) ? '. ' . $nv_Lang->getModule('file_image_height') . ' ' . $height_mess : '')
            ]);
        }
    }

    $strl = strlen($extension) + 1;
    $bsname = $bsname2 = substr($upload_info['basename'], 0, -$strl);
    $file_save_info = get_file_save_info($upload_info['basename']);
    while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
        $bsname = $bsname2 . '.' . substr(strtolower(nv_genpass(8)), 2, 2);
        $file_save_info = get_file_save_info($bsname . '.' . $extension);
    }
    nv_renamefile($upload_info['name'], $file_save_info['basename']);
    if ($bsname != $bsname2) {
        $upload_info['basename'] = $bsname . '.' . $extension;
    }

    nv_jsonOutput([
        'status' => 'OK',
        'file_value' => shorten_name($bsname, $extension),
        'file_key' => $upload_info['basename'],
        'csrf' => md5(NV_CHECK_SESSION . '_' . $module_name . $file_save_info['basename'])
    ]);
}

// Xóa thủ công file trong thư mục tmp
if ($nv_Request->isset_request('field_filedel,file,_csrf', 'post')) {
    $file = $nv_Request->get_title('file', 'post', '');
    $file = preg_replace('/[^a-zA-Z0-9\-\_\.]+/', '', $file);
    if (empty($file)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Stop!!!'
        ]);
    }

    $file_save_info = get_file_save_info($file);

    $checkss = md5(NV_CHECK_SESSION . '_' . $module_name . $file_save_info['basename']);
    $csrf = $nv_Request->get_title('_csrf', 'post', '');
    if (!hash_equals($checkss, $csrf)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Stop!!!'
        ]);
    }

    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename'])) {
        @unlink(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename']);
    }
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Download/Xem file (Nếu là image hoặc pdf thì xem)
if ($nv_Request->isset_request('userfile', 'get')) {
    if (!(defined('NV_IS_MODADMIN') or defined('NV_IS_USER'))) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
    }
    $userfile = $nv_Request->get_title('userfile', 'get', '');
    if (defined('NV_IS_USER') and !defined('NV_IS_MODADMIN')) {
        $field = $nv_Request->get_title('field', 'get', '');
        $field = preg_replace('/[^a-zA-Z0-9\-\_]+/', '', $field);
        if (empty($field)) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
        }
        $result = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE user_editable = 1 AND field=' . $db->quote($field));
        $row_field = $result->fetch();
        if (empty($row_field) or $row_field['field_type'] != 'file') {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
        }

        $uid = $user_info['userid'];
        if ($nv_Request->isset_request('userid', 'get')) {
            $_user_id = $nv_Request->get_int('userid', 'get', 0);
            if (!empty($_user_id) and $_user_id != $user_info['userid']) {
                if ($nv_Request->isset_request('groupid', 'get')) {
                    $groupid = $nv_Request->get_int('groupid', 'get', 0);
                    if ($db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $groupid . ' AND userid = ' . $user_info['userid'] . ' AND is_leader = 1')->fetchColumn()) {
                        if ($db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $groupid . ' and userid = ' . $_user_id . ' and is_leader = 0')->fetchColumn()) {
                            $uid = $_user_id;
                        }
                    }
                }
            }
        }
        $values = $db->query('SELECT ' . $field . ' FROM ' . NV_MOD_TABLE . '_info WHERE userid = ' . $uid)->fetchColumn();
        $values = !empty($values) ? array_map('trim', explode(',', $values)) : [];
        if (empty($values) or !in_array($userfile, $values, true)) {
            $info_custom = $db->query('SELECT info_custom FROM ' . NV_MOD_TABLE . '_edit WHERE userid = ' . $uid)->fetchColumn();
            if (empty($info_custom)) {
                nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
            }
            $info_custom = json_decode($info_custom, true);
            $editfiles = !empty($info_custom[$field]) ? array_map('trim', explode(',', $info_custom[$field])) : [];
            if (empty($editfiles) or !in_array($userfile, $editfiles, true)) {
                nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
            }
        }
    }

    $file_save_info = get_file_save_info($userfile);
    if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('file_not_allowed'), 404);
    }

    $extension = nv_getextension($userfile);
    if (in_array($extension, ['gif', 'jpg', 'jpeg', 'png', 'webp', 'pdf'], true)) {
        if ($extension == 'pdf') {
            $mime = 'application/pdf';
        } else {
            $sizes = getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename']);
            $mime = $sizes['mime'];
        }
        header('Content-Type: ' . $mime);
        header('Content-Disposition: inline; filename="' . $userfile . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        readfile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename']);
    } else {
        $file_info = pathinfo(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename']);
        $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'], $file_info['dirname'], $userfile, true);
        $download->download_file();
    }
    exit();
}
