<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_USER')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_USER') or !$global_config['allowuserlogin']) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if (defined('NV_IS_USER_FORUM')) {
    require_once NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet/editinfo.php';
    exit();
}

/**
 * nv_check_username_change()
 *
 * @param string $login
 * @param int    $edit_userid
 * @return string
 */
function nv_check_username_change($login, $edit_userid)
{
    global $db, $lang_module, $global_users_config, $global_config;

    $error = nv_check_valid_login($login, $global_config['nv_unickmax'], $global_config['nv_unickmin']);
    if ($error != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error));
    }
    if ("'" . $login . "'" != $db->quote($login)) {
        return sprintf($lang_module['account_deny_name'], $login);
    }

    if (!empty($global_users_config['deny_name']) and preg_match('/' . $global_users_config['deny_name'] . '/i', $login)) {
        return sprintf($lang_module['account_deny_name'], $login);
    }

    $sql = 'SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $edit_userid . ' AND (username LIKE ' . $db->quote($login) . ' OR md5username=' . $db->quote(nv_md5safe($login)) . ')';
    if ($db->query($sql)->fetchColumn()) {
        return sprintf($lang_module['account_registered_name'], $login);
    }

    $sql = 'SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE username LIKE ' . $db->quote($login) . ' OR md5username=' . $db->quote(nv_md5safe($login));
    if ($db->query($sql)->fetchColumn()) {
        return sprintf($lang_module['account_registered_name'], $login);
    }

    return '';
}

/**
 * nv_check_email_change()
 *
 * @param string $email
 * @param int    $edit_userid
 * @return string
 * @throws PDOException
 */
function nv_check_email_change(&$email, $edit_userid)
{
    global $db, $lang_module, $user_info, $global_users_config;

    $error = nv_check_valid_email($email, true);
    if ($error[0] != '') {
        return preg_replace('/\&(l|r)dquo\;/', '', strip_tags($error[0]));
    }
    $email = $error[1];

    if (!empty($global_users_config['deny_email']) and preg_match('/' . $global_users_config['deny_email'] . '/i', $email)) {
        return sprintf($lang_module['email_deny_name'], $email);
    }

    list($left, $right) = explode('@', $email);
    $left = preg_replace('/[\.]+/', '', $left);
    $pattern = str_split($left);
    $pattern = implode('.?', $pattern);
    $pattern = '^' . $pattern . '@' . $right . '$';

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $edit_userid . ' AND email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_reg WHERE email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    $stmt = $db->prepare('SELECT userid FROM ' . NV_MOD_TABLE . '_openid WHERE userid!=' . $edit_userid . ' AND email RLIKE :pattern');
    $stmt->bindParam(':pattern', $pattern, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn()) {
        return sprintf($lang_module['email_registered_name'], $email);
    }

    return '';
}

/**
 * get_field_config()
 *
 * @return array
 */
function get_field_config()
{
    global $db;

    $array_field_config = [];
    $is_custom_field = false;

    $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE user_editable = 1 ORDER BY weight ASC');
    while ($row_field = $result_field->fetch()) {
        $language = unserialize($row_field['language']);
        $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row_field['field'];
        $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
        if (!empty($row_field['field_choices'])) {
            $row_field['field_choices'] = unserialize($row_field['field_choices']);
        } elseif (!empty($row_field['sql_choices'])) {
            $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
            $row_field['field_choices'] = [];
            $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
            if (!empty($row_field['sql_choices'][4]) and !empty($row_field['sql_choices'][5])) {
                $query .= ' ORDER BY ' . $row_field['sql_choices'][4] . ' ' . $row_field['sql_choices'][5];
            }
            $result = $db->query($query);
            while (list($key, $val) = $result->fetch(3)) {
                $row_field['field_choices'][$key] = $val;
            }
        }
        $row_field['system'] = $row_field['is_system'];
        $array_field_config[$row_field['field']] = $row_field;
        if ($row_field['fid'] > 7) {
            $is_custom_field = true;
        }
    }

    return [
        $array_field_config,
        $is_custom_field
    ];
}

/**
 * opidr()
 *
 * @param int $openid_info
 */
function opidr($openid_info)
{
    global $lang_module;

    if ($openid_info == 1) {
        $openid_info = [
            'status' => 'error',
            'mess' => $lang_module['canceled_authentication']
        ];
    } elseif ($openid_info == 2) {
        $openid_info = [
            'status' => 'error',
            'mess' => $lang_module['not_logged_in']
        ];
    } elseif ($openid_info == 3) {
        $openid_info = [
            'status' => 'error',
            'mess' => $lang_module['logged_in_failed']
        ];
    } elseif ($openid_info == 4) {
        $openid_info = [
            'status' => 'error',
            'mess' => $lang_module['openid_is_exists']
        ];
    } elseif ($openid_info == 5 or $openid_info == 6) {
        $openid_info = [
            'status' => 'error',
            'mess' => $lang_module['email_is_exists']
        ];
    } else {
        $openid_info = [
            'status' => 'success',
            'mess' => $lang_module['openid_added']
        ];
    }
    $contents = openid_callback($openid_info);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * nv_groups_list_pub2()
 *
 * @param int $edit_userid
 * @return array
 */
function nv_groups_list_pub2($edit_userid)
{
    global $db, $global_config, $lang_global;

    $groups_list = [
        'all' => [],
        'share' => []
    ];
    $resul = $db->query('SELECT g.*, d.*, u.userid, u.is_leader, u.approved FROM ' . NV_MOD_TABLE . '_groups AS g
        LEFT JOIN ' . NV_MOD_TABLE . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' )
        LEFT JOIN " . NV_MOD_TABLE . '_groups_users u ON ( g.group_id = u.group_id AND u.userid=' . $edit_userid . ' )
        WHERE g.act=1 AND (g.idsite = ' . $global_config['idsite'] . ' OR (g.idsite =0 AND g.siteus = 1)) ORDER BY g.idsite, g.weight');
    while ($row = $resul->fetch()) {
        if ($row['group_id'] < 10) {
            $row['title'] = $lang_global['level' . $row['group_id']];
        }
        if ($row['group_type'] == 0 and $row['userid']) {
            $groups_list['all'][$row['group_id']] = $row;
        } elseif (($row['group_type'] == 1 or $row['group_type'] == 2) and ($row['exp_time'] == 0 or $row['exp_time'] > NV_CURRENTTIME)) {
            $groups_list['all'][$row['group_id']] = $row;
            $groups_list['share'][] = $row['group_id'];
        }
    }

    return $groups_list;
}

$array_data = [];
$array_data['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $user_info['userid']);
$array_data['awaitinginfo'] = [];
$array_data['editcensor'] = $global_users_config['active_editinfo_censor'];
$checkss = $nv_Request->get_title('checkss', 'post', '');
if (isset($array_op[2]) and !defined('ACCESS_EDITUS')) {
    nv_jsonOutput([
        'status' => 'error',
        'input' => '',
        'mess' => $lang_module['no_premission_leader']
    ]);
}

// Nếu là trưởng nhóm sửa thì $edit_userid = $userid được sửa còn không thì là $user_info['userid'] của thành viên tự sửa
$edit_userid = (defined('ACCESS_EDITUS')) ? $userid : $user_info['userid'];

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $edit_userid;
$query = $db->query($sql);
$row = $query->fetch();
empty($row['gender']) && $row['gender'] = 'N';
/*
 * Lấy thông tin đợi duyệt trước đó
 * Trưởng nhóm edit thì không phải duyệt
 */
if ($array_data['editcensor'] and !defined('ACCESS_EDITUS')) {
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $edit_userid;
    $query = $db->query($sql);
    $_row = $query->fetch();
    if (!empty($_row)) {
        $array_data['awaitinginfo'] = $_row;
        $array_data['awaitinginfo']['info_basic'] = empty($array_data['awaitinginfo']['info_basic']) ? [] : json_decode($array_data['awaitinginfo']['info_basic'], true);
        $array_data['awaitinginfo']['info_custom'] = empty($array_data['awaitinginfo']['info_custom']) ? [] : json_decode($array_data['awaitinginfo']['info_custom'], true);
    }
}

// Tat safemode
if ((int) $row['safemode'] > 0) {
    $type = $nv_Request->get_title('type', 'post', '');

    if ($checkss == $array_data['checkss'] and $type == 'safe_deactivate') {
        $nv_password = $nv_Request->get_title('nv_password', 'post', '');

        if (!empty($row['password']) and !$crypt->validate_password($nv_password, $row['password'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'nv_password',
                'mess' => $lang_global['incorrect_password']
            ]);
        }

        if ($nv_Request->isset_request('resend', 'post')) {
            $ss_safesend = $nv_Request->get_int('safesend', 'session', 0);
            if ($ss_safesend < NV_CURRENTTIME) {
                $name = $global_config['name_show'] ? [
                    $row['first_name'],
                    $row['last_name']
                ] : [
                    $row['last_name'],
                    $row['first_name']
                ];
                $name = array_filter($name);
                $name = implode(' ', $name);
                $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
                $message = sprintf($lang_module['safe_send_content'], $name, $sitename, $row['safekey']);
                @nv_sendmail([
                    $global_config['site_name'],
                    $global_config['site_email']
                ], $row['email'], $lang_module['safe_send_subject'], $message);

                $ss_safesend = NV_CURRENTTIME + 600;
                $nv_Request->set_Session('safesend', $ss_safesend);
            }

            $ss_safesend = ceil(($ss_safesend - NV_CURRENTTIME) / 60);

            nv_jsonOutput([
                'status' => 'ok',
                'input' => '',
                'mess' => sprintf($lang_module['safe_send_ok'], $ss_safesend)
            ]);
        }

        $safe_key = nv_substr($nv_Request->get_title('safe_key', 'post', '', 1), 0, 32);

        if (empty($row['safekey']) or $safe_key != $row['safekey']) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'safe_key',
                'mess' => $lang_module['verifykey_error']
            ]);
        }

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . " SET safemode=0, safekey='', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
        $stmt->execute();

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo', true),
            'mess' => $lang_module['safe_deactivate_ok']
        ]);
    }

    $array_data['safeshow'] = (isset($array_op[1]) and $array_op[1] == 'safeshow') ? true : false;

    $contents = safe_deactivate($array_data);

    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    $canonicalUrl = getCanonicalUrl($page_url);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$array_data['allowmailchange'] = $global_config['allowmailchange'];
$array_data['allowloginchange'] = ($global_config['allowloginchange'] or (!empty($row['last_openid']) and empty($user_info['last_login']) and empty($user_info['last_agent']) and empty($user_info['last_ip']) and empty($user_info['last_openid']))) ? 1 : 0;

$array_field_config = get_field_config();
$is_custom_field = $array_field_config[1];
$array_field_config = $array_field_config[0];
$groups_list = [];

$types = [
    'basic'
];

// Trưởng nhóm không thể sửa ảnh đại diện và câu hỏi bí mật của thành viên
if (!defined('ACCESS_EDITUS')) {
    $types[] = 'avatar';
    $types[] = 'question';
}
// Thành viên đổi mật khẩu hoặc trưởng nhóm có quyền đổi mật khẩu
if (!defined('ACCESS_EDITUS') or (defined('ACCESS_EDITUS') and defined('ACCESS_PASSUS'))) {
    $types[] = 'password';
}
// Thành viên mới có quyền bật tắt xác thực hai bước
if (!defined('ACCESS_EDITUS')) {
    $types[] = '2step';
}
// Thành viên có quyền đổi tên đăng nhập
if ($array_data['allowloginchange'] and !defined('ACCESS_EDITUS')) {
    $types[] = 'username';
}
// Thành viên có quyền đổi email
if ($array_data['allowmailchange'] and !defined('ACCESS_EDITUS')) {
    $types[] = 'email';
}
// Thành viên quản lý OpenID
if (defined('NV_OPENID_ALLOWED') and !defined('ACCESS_EDITUS')) {
    $types[] = 'openid';
}
// Bật đăng ký vào nhóm công cộng. Thành viên tự đăng ký tham gia
if ($global_config['allowuserpublic'] and !defined('ACCESS_EDITUS')) {
    $groups_list = nv_groups_list_pub2($edit_userid);
    if (!empty($groups_list['all'])) {
        $types[] = 'group';
    }
}
// Thành viên quản lý chế độ an toàn
if (!defined('ACCESS_EDITUS')) {
    $types[] = 'safemode';
}
// Các trường tùy chỉnh
if ($is_custom_field) {
    $types[] = 'others';
}

// Trường hợp trưởng nhóm truy cập sửa thông tin member
if (defined('ACCESS_EDITUS')) {
    $array_data['group_id'] = $group_id;
    $array_data['userid'] = $edit_userid;
    $array_data['type'] = (isset($array_op[3]) and !empty($array_op[3]) and in_array($array_op[3], $types, true)) ? $array_op[3] : ((isset($array_op[3]) and !empty($array_op[3]) and $array_op[3] == 'password') ? $array_op[3] : 'basic');

    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/' . $group_id . '/' . $edit_userid;
} else {
    $array_data['type'] = (isset($array_op[1]) and !empty($array_op[1]) and in_array($array_op[1], $types, true)) ? $array_op[1] : 'basic';
    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo';
}

$data_openid = [];
$data_openid_key = [];
if (in_array('openid', $types, true)) {
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $edit_userid;
    $query = $db->query($sql);
    while ($row3 = $query->fetch()) {
        $data_openid[] = [
            'opid' => $row3['opid'],
            'openid' => $row3['openid'],
            'id' => $row3['id'],
            'email' => $row3['email'],
            'disabled' => ((!empty($user_info['current_openid']) and $user_info['current_openid'] == $row3['opid']) ? true : false)
        ];
        $data_openid_key[$row3['opid']] = [
            'openid' => $row3['openid'],
            'email' => $row3['email']
        ];
    }
}

// OpenID add
if (in_array('openid', $types, true) and $nv_Request->isset_request('server', 'get')) {
    $server = $nv_Request->get_string('server', 'get', '');
    $result = $nv_Request->isset_request('result', 'get');

    if (empty($server) or !in_array($server, $global_config['openid_servers'], true) or !$result) {
        header('Location: ' . NV_BASE_SITEURL);
        exit();
    }

    $attribs = $nv_Request->get_string('openid_attribs', 'session', '');
    $attribs = !empty($attribs) ? unserialize($attribs) : [];

    $email = isset($attribs['contact/email']) ? $attribs['contact/email'] : '';
    $check_email = nv_check_valid_email($email, true);
    if (!empty($email) and !empty($check_email[0])) {
        opidr(3);
        exit();
    }

    !empty($check_email[1]) && $email = $check_email[1];
    $opid = $crypt->hash($attribs['id']);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_openid WHERE opid= :opid');
    $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count) {
        opidr(4);
        exit();
    }

    if (!empty($email)) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . ' WHERE userid!=' . $edit_userid . ' AND email= :email ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count) {
            opidr(5);
            exit();
        }

        if ($global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3) {
            $query = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_reg WHERE email= :email ';
            if ($global_config['allowuserreg'] == 2) {
                $query .= ' AND regdate>' . (NV_CURRENTTIME - 86400);
            }
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count) {
                opidr(6);
                exit();
            }
        }
    }

    $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_openid VALUES (' . $edit_userid . ', :openid, :opid, :id, :email )');
    $stmt->bindParam(':openid', $server, PDO::PARAM_STR);
    $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
    $stmt->bindParam(':id', $attribs['id'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Gửi email thông báo
    $url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/openid', true);
    $message = defined('ACCESS_EDITUS') ? $lang_module['security_alert_openid_add'] : $lang_module['security_alert_openid_add1'];
    $message = sprintf($message, nv_ucfirst($server), $row['username'], $url);
    nv_sendmail([
        $global_config['site_name'],
        $global_config['site_email']
    ], $row['email'], $lang_module['security_alert'], $message);

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['openid_add'], $user_info['username'] . ' | ' . $client_info['ip'] . ' | ' . $opid, 0);

    opidr(1000);
    exit();
}

// Basic
if ($checkss == $array_data['checkss'] and $array_data['type'] == 'basic') {
    $array_data['first_name'] = isset($array_field_config['first_name']) ? nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255) : $row['first_name'];
    $array_data['last_name'] = isset($array_field_config['last_name']) ? nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255) : $row['last_name'];
    $array_data['gender'] = isset($array_field_config['gender']) ? nv_substr($nv_Request->get_title('gender', 'post', '', 1), 0, 1) : $row['gender'];
    $array_data['birthday'] = isset($array_field_config['birthday']) ? nv_substr($nv_Request->get_title('birthday', 'post', '', 0), 0, 10) : $row['birthday'];
    $array_data['view_mail'] = (int) $nv_Request->get_bool('view_mail', 'post', false);
    $array_data['sig'] = isset($array_field_config['sig']) ? $nv_Request->get_title('sig', 'post', '') : $row['sig'];

    $custom_fields = [];
    $custom_fields['first_name'] = $array_data['first_name'];
    $custom_fields['last_name'] = $array_data['last_name'];
    $custom_fields['gender'] = $array_data['gender'];
    $custom_fields['birthday'] = $array_data['birthday'];
    $custom_fields['sig'] = $array_data['sig'];
    $array_field_config = array_intersect_key($array_field_config, [
        'first_name' => 1,
        'last_name' => 1,
        'gender' => 1,
        'birthday' => 1,
        'sig' => 1
    ]);

    require NV_ROOTDIR . '/modules/users/fields.check.php';

    if (empty($array_data['first_name'])) {
        $array_data['first_name'] = !empty($row['first_name']) ? $row['first_name'] : $row['username'];
    }

    if ($array_data['editcensor'] and !defined('ACCESS_EDITUS') and !defined('NV_IS_MODADMIN')) {
        // Lưu thông tin và thông báo kiểm duyệt
        if (empty($array_data['awaitinginfo'])) {
            $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_edit (userid, lastedit, info_basic, info_custom) VALUES (' . $edit_userid . ', ' . NV_CURRENTTIME . ', :info_basic, :info_custom)';
        } else {
            $sql = 'UPDATE ' . NV_MOD_TABLE . '_edit SET
                lastedit=' . NV_CURRENTTIME . ',
                info_basic=:info_basic,
                info_custom=:info_custom
            WHERE userid=' . $edit_userid;
        }

        $info_basic = $custom_fields;
        $info_basic['view_mail'] = $array_data['view_mail'];
        $array_data['awaitinginfo']['info_basic'] = json_encode($info_basic);
        $array_data['awaitinginfo']['info_custom'] = empty($array_data['awaitinginfo']['info_custom']) ? '' : json_encode($array_data['awaitinginfo']['info_custom']);

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':info_basic', $array_data['awaitinginfo']['info_basic'], PDO::PARAM_STR, strlen($array_data['awaitinginfo']['info_basic']));
        $stmt->bindParam(':info_custom', $array_data['awaitinginfo']['info_custom'], PDO::PARAM_STR, strlen($array_data['awaitinginfo']['info_custom']));
        $stmt->execute();

        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($page_url . '/basic', true),
            'mess' => $lang_module['editinfo_okcensor']
        ]);
    } else {
        // Lưu thông tin và thông báo thành công
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET
            first_name= :first_name,
            last_name= :last_name,
            gender= :gender,
            sig= :sig,
            birthday=' . (int) ($array_data['birthday']) . ',
            view_mail=' . $array_data['view_mail'] . ',
            last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $edit_userid);

        $stmt->bindParam(':first_name', $array_data['first_name'], PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $array_data['last_name'], PDO::PARAM_STR);
        $stmt->bindParam(':gender', $array_data['gender'], PDO::PARAM_STR);
        $stmt->bindParam(':sig', $array_data['sig'], PDO::PARAM_STR, strlen($array_data['sig']));
        $stmt->execute();

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($page_url . '/basic', true),
            'mess' => $lang_module['editinfo_ok']
        ]);
    }
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'avatar') {
    // Avatar
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'username') {
    // Username
    $nv_username = nv_substr($nv_Request->get_title('username', 'post', '', 1), 0, $global_config['nv_unickmax']);
    $nv_password = $nv_Request->get_title('password', 'post', '');

    if (empty($nv_password) or !$crypt->validate_password($nv_password, $row['password'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password',
            'mess' => $lang_global['incorrect_password']
        ]);
    }

    if ($nv_username != $row['username']) {
        $checkusername = nv_check_username_change($nv_username, $edit_userid);
        if (!empty($checkusername)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'username',
                'mess' => $checkusername
            ]);
        }
    }

    $md5_username = nv_md5safe($nv_username);

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET username= :username, md5username= :md5username, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
    $stmt->bindParam(':username', $nv_username, PDO::PARAM_STR);
    $stmt->bindParam(':md5username', $md5_username, PDO::PARAM_STR);
    $stmt->execute();

    $name = $global_config['name_show'] ? [
        $row['first_name'],
        $row['last_name']
    ] : [
        $row['last_name'],
        $row['first_name']
    ];
    $name = array_filter($name);
    $name = implode(' ', $name);
    $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
    $message = sprintf($lang_module['edit_mail_content'], $name, $sitename, $lang_global['username'], $nv_username);
    @nv_sendmail([
        $global_config['site_name'],
        $global_config['site_email']
    ], $row['email'], $lang_module['edit_mail_subject'], $message);

    nv_jsonOutput([
        'status' => 'ok',
        'input' => nv_url_rewrite($page_url . '/username', true),
        'mess' => $lang_module['editinfo_ok']
    ]);
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'email') {
    // Email
    $nv_email = nv_strtolower(nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 100));
    $nv_password = $nv_Request->get_title('password', 'post', '');
    $nv_verikeysend = (int) $nv_Request->get_bool('vsend', 'post', false);
    if (empty($nv_password) or !$nv_Request->get_bool('verikey', 'session')) {
        $nv_verikeysend = 1;
    }

    $checkemail = nv_check_email_change($nv_email, $edit_userid);
    if (!empty($checkemail)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $checkemail
        ]);
    }

    if ($nv_email == $row['email']) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'email',
            'mess' => $lang_module['email_not_change']
        ]);
    }

    if (!empty($row['password']) and !$crypt->validate_password($nv_password, $row['password'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'password',
            'mess' => $lang_global['incorrect_password']
        ]);
    }

    if ($nv_verikeysend) {
        $p = 0;
        $verikey = '';

        if ($nv_Request->get_bool('verikey', 'session')) {
            $ss_verifykey = $nv_Request->get_title('verikey', 'session', '');
            $ss_verifykey = explode('|', $ss_verifykey);
            if ((int) $ss_verifykey[0] > NV_CURRENTTIME) {
                nv_jsonOutput([
                    'status' => 'error',
                    'input' => 'verifykey',
                    'mess' => sprintf($lang_module['verifykey_issend'], ceil(((int) $ss_verifykey[0] - NV_CURRENTTIME) / 60))
                ]);
            } else {
                $p = (int) $ss_verifykey[1];
                $verikey = $ss_verifykey[2];
                $nv_Request->set_Session('verikey', (NV_CURRENTTIME + 300) . '|' . $p . '|' . $verikey);
            }
        }

        if (empty($p) or empty($verikey)) {
            $rand = rand($global_config['nv_upassmin'], $global_config['nv_upassmax']);
            if ($rand < 6) {
                $rand = 6;
            }
            $p = NV_CURRENTTIME + 86400;
            $verikey = md5($row['userid'] . $nv_email . nv_genpass($rand) . $global_config['sitekey']);
            $nv_Request->set_Session('verikey', (NV_CURRENTTIME + 300) . '|' . $p . '|' . $verikey);
        }

        $p = nv_date('H:i d/m/Y', $p);
        $name = $global_config['name_show'] ? [
            $row['first_name'],
            $row['last_name']
        ] : [
            $row['last_name'],
            $row['first_name']
        ];
        $name = array_filter($name);
        $name = implode(' ', $name);
        $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
        $message = sprintf($lang_module['email_active_info'], $name, $sitename, $verikey, $p);
        @nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $nv_email, $lang_module['email_active'], $message);

        nv_jsonOutput([
            'status' => 'error',
            'input' => 'verifykey',
            'mess' => $lang_module['email_active_mes']
        ]);
    } else {
        $nv_verifykey = $nv_Request->get_title('verifykey', 'post', '');

        if (empty($nv_verifykey)) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'verifykey',
                'mess' => $lang_module['verifykey_empty']
            ]);
        }

        $ss_verifykey = $nv_Request->get_title('verikey', 'session', '');
        $ss_verifykey = explode('|', $ss_verifykey);

        if ((int) $ss_verifykey[1] < NV_CURRENTTIME) {
            $nv_Request->unset_request('verifykey', 'session');

            nv_jsonOutput([
                'status' => 'error',
                'input' => 'verifykey',
                'mess' => $lang_module['verifykey_exp']
            ]);
        }

        if ($nv_verifykey != $ss_verifykey[2]) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'verifykey',
                'mess' => $lang_module['verifykey_error']
            ]);
        }

        $nv_Request->unset_request('verifykey', 'session');

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET email=:email, email_verification_time=' . NV_CURRENTTIME . ', last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
        $stmt->bindParam(':email', $nv_email, PDO::PARAM_STR);
        $stmt->execute();

        $name = $global_config['name_show'] ? [
            $row['first_name'],
            $row['last_name']
        ] : [
            $row['last_name'],
            $row['first_name']
        ];
        $name = array_filter($name);
        $name = implode(' ', $name);
        $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
        $message = sprintf($lang_module['edit_mail_content'], $name, $sitename, $lang_global['email'], $nv_email);

        // Gửi thư cho cả email mới và email cũ
        @nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $nv_email, $lang_module['edit_mail_subject'], $message);
        @nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $row['email'], $lang_module['edit_mail_subject'], $message);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($page_url . '/email', true),
            'mess' => $lang_module['editinfo_ok']
        ]);
    }
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'password') {
    // Password
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');
    $new_password = $nv_Request->get_title('new_password', 'post', '');
    $re_password = $nv_Request->get_title('re_password', 'post', '');

    // Kiểm tra lại quyền sửa mật khẩu
    if (!empty($group_id) and !empty($edit_userid) and !defined('ACCESS_PASSUS')) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $lang_module['no_premission_pass']
        ]);
    }

    if (!empty($row['password']) and !$crypt->validate_password($nv_password, $row['password']) and !defined('ACCESS_PASSUS')) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $lang_global['incorrect_password']
        ]);
    }

    if (($check_new_password = nv_check_valid_pass($new_password, $global_config['nv_upassmax'], $global_config['nv_upassmin'])) != '') {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'new_password',
            'mess' => $check_new_password
        ]);
    }

    if ($new_password != $re_password) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 're_password',
            'mess' => $lang_global['passwordsincorrect']
        ]);
    }

    $re_password = $crypt->hash_password($new_password, $global_config['hashprefix']);

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET password= :password, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
    $stmt->bindParam(':password', $re_password, PDO::PARAM_STR);
    $stmt->execute();

    $name = $global_config['name_show'] ? [
        $row['first_name'],
        $row['last_name']
    ] : [
        $row['last_name'],
        $row['first_name']
    ];
    $name = array_filter($name);
    $name = implode(' ', $name);
    $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
    $message = sprintf($lang_module['edit_mail_content'], $name, $sitename, $lang_global['password'], $new_password);
    @nv_sendmail([
        $global_config['site_name'],
        $global_config['site_email']
    ], $row['email'], $lang_module['edit_mail_subject'], $message);

    nv_jsonOutput([
        'status' => 'ok',
        'input' => nv_url_rewrite($page_url . '/basic', true),
        'mess' => $lang_module['editinfo_ok']
    ]);
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'question') {
    // Question
    $array_data['question'] = isset($array_field_config['question']) ? nv_substr($nv_Request->get_title('question', 'post', '', 1), 0, 255) : $row['question'];
    $array_data['answer'] = isset($array_field_config['answer']) ? nv_substr($nv_Request->get_title('answer', 'post', '', 1), 0, 255) : $row['answer'];
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');

    $custom_fields = [];
    $custom_fields['question'] = $array_data['question'];
    $custom_fields['answer'] = $array_data['answer'];
    $array_field_config = array_intersect_key($array_field_config, [
        'question' => 1,
        'answer' => 1
    ]);

    require NV_ROOTDIR . '/modules/users/fields.check.php';

    if (empty($nv_password) or !$crypt->validate_password($nv_password, $row['password'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $lang_global['incorrect_password']
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET question= :question, answer= :answer, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
    $stmt->bindParam(':question', $array_data['question'], PDO::PARAM_STR);
    $stmt->bindParam(':answer', $array_data['answer'], PDO::PARAM_STR);
    $stmt->execute();

    nv_jsonOutput([
        'status' => 'ok',
        'input' => 'ok',
        'mess' => $lang_module['change_question_ok']
    ]);
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'openid') {
    // OpeniD Del
    $openid_del = $nv_Request->get_typed_array('openid_del', 'post', 'string', '');
    $openid_del = array_filter($openid_del);
    if (empty($openid_del)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $lang_module['openid_choose']
        ]);
    }

    $openid_mess = [];
    foreach ($openid_del as $opid) {
        if (!empty($opid) and (empty($user_info['current_openid']) or (!empty($user_info['current_openid']) and $user_info['current_openid'] != $opid))) {
            $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE opid= :opid');
            $stmt->bindParam(':opid', $opid, PDO::PARAM_STR);
            $stmt->execute();

            if (isset($data_openid_key[$opid])) {
                $openid_mess[] = nv_ucfirst($data_openid_key[$opid]['openid']);
            }
        }
    }

    // Gửi email thông báo
    if (!empty($openid_mess)) {
        $url = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editinfo/openid', true);
        $message = defined('ACCESS_EDITUS') ? $lang_module['security_alert_openid_delete'] : $lang_module['security_alert_openid_delete1'];
        $message = sprintf($message, implode(', ', array_unique($openid_mess)), $row['username'], $url);
        nv_sendmail([
            $global_config['site_name'],
            $global_config['site_email']
        ], $row['email'], $lang_module['security_alert'], $message);
    }

    nv_jsonOutput([
        'status' => 'ok',
        'input' => nv_url_rewrite($page_url . '/openid', true),
        'mess' => $lang_module['openid_deleted']
    ]);
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'group') {
    // Groups
    $in_groups = $nv_Request->get_typed_array('in_groups', 'post', 'int');

    $array_old_groups = [];
    $result_gru = $db->query('SELECT group_id, is_leader FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $edit_userid);
    while ($row_gru = $result_gru->fetch()) {
        $array_old_groups[] = $row_gru['group_id'];

        // Trưởng nhóm không thể tự ra khỏi nhóm
        if ($row_gru['is_leader'] and !in_array((int) $row_gru['group_id'], $in_groups, true)) {
            $in_groups[] = $row_gru['group_id'];
        }
    }

    $in_groups = array_intersect($in_groups, $groups_list['share']);
    $in_groups_hiden = array_diff($array_old_groups, $groups_list['share']);
    $in_groups = array_unique(array_merge($in_groups, $in_groups_hiden));

    $in_groups_del = array_diff($array_old_groups, $in_groups);
    if (!empty($in_groups_del)) {
        foreach ($in_groups_del as $gid) {
            nv_groups_del_user($gid, $edit_userid, $module_data);
        }
    }

    $in_groups_add = array_diff($in_groups, $array_old_groups);
    if (!empty($in_groups_add)) {
        foreach ($in_groups_add as $gid) {
            $approved = $groups_list['all'][$gid]['group_type'] == 1 ? 0 : 1;
            if (nv_groups_add_user($gid, $edit_userid, $approved, $module_data)) {
                // Gửi thư thông báo kiểm duyệt
                if ($groups_list['all'][$gid]['group_type'] == 1) {
                    // Danh sách email trưởng nhóm
                    $array_leader = [];
                    $result = $db->query('SELECT t2.email FROM ' . NV_MOD_TABLE . '_groups_users t1 INNER JOIN ' . NV_MOD_TABLE . ' t2 ON t1.userid=t2.userid WHERE t1.is_leader=1 AND t1.group_id=' . $gid);
                    while (list($email) = $result->fetch(3)) {
                        $array_leader[] = $email;
                    }
                    if (!empty($array_leader)) {
                        $array_leader = array_unique($array_leader);
                        foreach ($array_leader as $email) {
                            $mail_from = [
                                $global_config['site_name'],
                                $global_config['site_email']
                            ];
                            $url_group = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups/' . $gid, true);
                            $message = sprintf($lang_module['group_join_queue_message'], $groups_list['all'][$gid]['title'], $user_info['full_name'], $groups_list['all'][$gid]['title'], $url_group);
                            @nv_sendmail($mail_from, $email, $lang_module['group_join_queue'], $message);
                        }
                    }
                }
            }
        }
    }

    $db->query('UPDATE ' . NV_MOD_TABLE . " SET in_groups='" . implode(',', $in_groups) . "', last_update=" . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
    nv_jsonOutput([
        'status' => 'ok',
        'input' => nv_url_rewrite($page_url . '/group', true),
        'mess' => $lang_module['in_group_ok']
    ]);
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'others') {
    // Others
    $query_field = $valid_field = [];
    $userid = $edit_userid;
    $custom_fields = $nv_Request->get_array('custom_fields', 'post');

    $array_field_config = array_diff_key($array_field_config, [
        'first_name' => 1,
        'last_name' => 1,
        'gender' => 1,
        'birthday' => 1,
        'sig' => 1,
        'question' => 1,
        'answer' => 1
    ]);
    require NV_ROOTDIR . '/modules/users/fields.check.php';

    if ($array_data['editcensor'] and !defined('ACCESS_EDITUS') and !defined('NV_IS_MODADMIN')) {
        // Lưu thông tin và thông báo kiểm duyệt
        if (empty($array_data['awaitinginfo'])) {
            $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_edit (userid, lastedit, info_basic, info_custom) VALUES (' . $edit_userid . ', ' . NV_CURRENTTIME . ', :info_basic, :info_custom)';
        } else {
            $sql = 'UPDATE ' . NV_MOD_TABLE . '_edit SET
                lastedit=' . NV_CURRENTTIME . ',
                info_basic=:info_basic,
                info_custom=:info_custom
            WHERE userid=' . $edit_userid;
        }

        $array_data['awaitinginfo']['info_basic'] = empty($array_data['awaitinginfo']['info_basic']) ? '' : json_encode($array_data['awaitinginfo']['info_basic']);
        $array_data['awaitinginfo']['info_custom'] = json_encode($valid_field);

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':info_basic', $array_data['awaitinginfo']['info_basic'], PDO::PARAM_STR, strlen($array_data['awaitinginfo']['info_basic']));
        $stmt->bindParam(':info_custom', $array_data['awaitinginfo']['info_custom'], PDO::PARAM_STR, strlen($array_data['awaitinginfo']['info_custom']));
        $stmt->execute();

        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($page_url . '/others', true),
            'mess' => $lang_module['editinfo_okcensor']
        ]);
    } else {
        $db->query('UPDATE ' . NV_MOD_TABLE . '_info SET ' . implode(', ', $query_field) . ' WHERE userid=' . $edit_userid);
        $db->query('UPDATE ' . NV_MOD_TABLE . ' SET last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => nv_url_rewrite($page_url . '/others', true),
            'mess' => $lang_module['editinfo_ok']
        ]);
    }
} elseif ($checkss == $array_data['checkss'] and $array_data['type'] == 'safemode') {
    // Bat safemode
    $nv_password = $nv_Request->get_title('nv_password', 'post', '');
    if (empty($nv_password) or !$crypt->validate_password($nv_password, $row['password'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'nv_password',
            'mess' => $lang_global['incorrect_password']
        ]);
    }

    if ($nv_Request->isset_request('resend', 'post')) {
        if (empty($row['safekey'])) {
            $rand = rand($global_config['nv_upassmin'], $global_config['nv_upassmax']);
            if ($rand < 6) {
                $rand = 6;
            }
            $row['safekey'] = md5(nv_genpass($rand));

            $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET safekey= :safekey WHERE userid=' . $edit_userid);
            $stmt->bindParam(':safekey', $row['safekey'], PDO::PARAM_STR);
            $stmt->execute();
            $nv_Request->set_Session('safesend', 0);
        }

        $ss_safesend = $nv_Request->get_int('safesend', 'session', 0);
        if ($ss_safesend < NV_CURRENTTIME) {
            $name = $global_config['name_show'] ? [
                $row['first_name'],
                $row['last_name']
            ] : [
                $row['last_name'],
                $row['first_name']
            ];
            $name = array_filter($name);
            $name = implode(' ', $name);
            $sitename = '<a href="' . NV_MY_DOMAIN . NV_BASE_SITEURL . '">' . $global_config['site_name'] . '</a>';
            $message = sprintf($lang_module['safe_send_content'], $name, $sitename, $row['safekey']);
            @nv_sendmail([
                $global_config['site_name'],
                $global_config['site_email']
            ], $row['email'], $lang_module['safe_send_subject'], $message);

            $ss_safesend = NV_CURRENTTIME + 600;
            $nv_Request->set_Session('safesend', $ss_safesend);
        }

        $ss_safesend = ceil(($ss_safesend - NV_CURRENTTIME) / 60);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => '',
            'mess' => sprintf($lang_module['safe_send_ok'], $ss_safesend)
        ]);
    }

    $safe_key = nv_substr($nv_Request->get_title('safe_key', 'post', '', 1), 0, 32);

    if (empty($row['safekey']) or $safe_key != $row['safekey']) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'safe_key',
            'mess' => $lang_module['verifykey_error']
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET safemode=1, safekey= :safekey, last_update=' . NV_CURRENTTIME . ' WHERE userid=' . $edit_userid);
    $stmt->bindParam(':safekey', $row['safekey'], PDO::PARAM_STR);
    $stmt->execute();

    nv_jsonOutput([
        'status' => 'ok',
        'input' => nv_url_rewrite($page_url, true),
        'mess' => $lang_module['safe_activate_ok']
    ]);
}

$page_title = $mod_title = $lang_module['editinfo_pagetitle'];
$key_words = $module_info['keywords'];
$page_url .= '/' . $array_data['type'];
$canonicalUrl = getCanonicalUrl($page_url, true);

if (!defined('NV_EDITOR')) {
    define('NV_EDITOR', 'ckeditor');
}
require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $edit_userid;
$result = $db->query($sql);
$custom_fields = $result->fetch();

$custom_fields['first_name'] = $row['first_name'];
$custom_fields['last_name'] = $row['last_name'];
$custom_fields['gender'] = $row['gender'];
$custom_fields['birthday'] = $row['birthday'];
$custom_fields['sig'] = $row['sig'];
$custom_fields['question'] = $row['question'];
$custom_fields['answer'] = $row['answer'];
$custom_fields['view_mail'] = $row['view_mail'];

// Đặt dữ liệu chỉnh sửa tạm đè lên dữ liệu hiện tại
if (!empty($array_data['awaitinginfo'])) {
    $awaitinginfo = array_intersect_key(array_merge($array_data['awaitinginfo']['info_basic'], $array_data['awaitinginfo']['info_custom']), $custom_fields);
    $custom_fields = array_merge($custom_fields, $awaitinginfo);
}

$array_data['username'] = $row['username'];
$array_data['email'] = $row['email'];
$array_data['first_name'] = $row['first_name'];
$array_data['last_name'] = $row['last_name'];
$array_data['gender'] = $row['gender'];
$array_data['birthday'] = !empty($row['birthday']) ? date('d/m/Y', $row['birthday']) : '';
$array_data['view_mail'] = $row['view_mail'] ? ' checked="checked"' : '';
$array_data['photo'] = (!empty($row['photo']) and file_exists(NV_ROOTDIR . '/' . $row['photo'])) ? NV_BASE_SITEURL . $row['photo'] : '';

if (empty($array_data['photo'])) {
    $array_data['photo'] = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_avatar.png';
    $array_data['photoWidth'] = 80;
    $array_data['photoHeight'] = 80;
    $array_data['imgDisabled'] = ' disabled="disabled"';
} else {
    $size = @getimagesize(NV_ROOTDIR . '/' . $row['photo']);
    $array_data['photoWidth'] = $size[0];
    $array_data['photoHeight'] = $size[1];
    $array_data['imgDisabled'] = '';
}

$data_questions = [];
$sql = 'SELECT qid, title FROM ' . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
$result = $db->query($sql);
while ($row2 = $result->fetch()) {
    $data_questions[$row2['qid']] = [
        'qid' => $row2['qid'],
        'title' => $row2['title']
    ];
}

$groups = [];
$array_data['old_in_groups'] = [];
if (in_array('group', $types, true)) {
    foreach ($groups_list['all'] as $gid => $gvalues) {
        $groups[$gid] = $gvalues;
        $groups[$gid]['exp'] = !empty($gvalues['exp_time']) ? nv_date('d/m/Y', $gvalues['exp_time']) : $lang_module['group_exp_unlimited'];
        $groups[$gid]['group_avatar'] = !empty($groups[$gid]['group_avatar']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $groups[$gid]['group_avatar'] : NV_STATIC_URL . NV_ASSETS_DIR . '/images/user-group.jpg';
        $groups[$gid]['checked'] = '';
        $groups[$gid]['status'] = 0;
        if ($gvalues['userid'] == $edit_userid) {
            $groups[$gid]['checked'] = ' checked="checked"';
            $groups[$gid]['status'] = 1;
            if ($gvalues['group_type']) {
                $array_data['old_in_groups'][] = $gid;
            }

            if (!$gvalues['approved']) {
                $groups[$gid]['status'] = 2;
            }
        }
    }
}
$array_data['old_in_groups'] = !empty($array_data['old_in_groups']) ? implode(',', $array_data['old_in_groups']) : '';

$pass_empty = empty($row['password']) ? true : false;

$contents = user_info($array_data, $array_field_config, $custom_fields, $types, $data_questions, $data_openid, $groups, $pass_empty);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
