<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

/**
 * valid_name_config()
 *
 * @param array $array_name
 * @return array
 */
function valid_name_config($array_name)
{
    $array_retutn = [];
    foreach ($array_name as $v) {
        $v = trim($v);
        if (!empty($v) and preg_match('/^[a-z0-9\-\.\_]+$/', $v)) {
            $array_retutn[] = $v;
        }
    }

    return $array_retutn;
}

$groups_list = nv_groups_list();
$array_config = [];

// Cấu hình riêng các cổng đăng nhập bên thứ 3
$oauth_config = $nv_Request->get_title('oauth_config', 'post,get');
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $oauth_config);
if (preg_match('/^([a-z0-9\-\_]+)$/', $oauth_config, $m) and file_exists(NV_ROOTDIR . '/modules/users/admin/config_' . $oauth_config . '.php')) {
    $page_title = $nv_Lang->getModule('oauth_config', $oauth_config);
    require NV_ROOTDIR . '/modules/users/admin/config_' . $oauth_config . '.php';
}

$page_title = $nv_Lang->getModule('config');
$files = nv_scandir(NV_ROOTDIR . '/modules/users/methods/', '/(.*?)/');
$login_name_types = [];
foreach ($files as $file) {
    if (preg_match('/^(?!adm_)([^0-9]+[a-z0-9\_]{0,})\.php$/', $file, $m)) {
        $login_name_types[] = $m[1];
    }
}
$nv_Lang->setGlobal('unick_type_0', $nv_Lang->getModule('unick_type_0'));
$nv_Lang->setGlobal('upass_type_0', $nv_Lang->getModule('upass_type_0'));

if ($nv_Request->isset_request('save', 'post')) {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];

    if ($checkss != $nv_Request->get_string('checkss', 'post')) {
        $respon['mess'] = 'Wrong session';
        nv_jsonOutput($respon);
    }

    $array_config['is_user_forum'] = $nv_Request->get_int('is_user_forum', 'post', 0);
    $array_config['dir_forum'] = $nv_Request->get_string('dir_forum', 'post');
    if (!is_dir(NV_ROOTDIR . '/' . $array_config['dir_forum'] . '/nukeviet')) {
        $array_config['dir_forum'] = '';
    }

    // Kiểm tra cấu trúc thư mục diễn đàn mới cho lưu
    if ($array_config['dir_forum']) {
        $forum_files = @scandir(NV_ROOTDIR . '/' . $array_config['dir_forum'] . '/nukeviet');
        if (empty($forum_files) or !in_array('is_user.php', $forum_files, true) or !in_array('changepass.php', $forum_files, true) or !in_array('editinfo.php', $forum_files, true) or !in_array('login.php', $forum_files, true) or !in_array('logout.php', $forum_files, true) or !in_array('lostpass.php', $forum_files, true) or !in_array('register.php', $forum_files, true)) {
            $array_config['is_user_forum'] = 0;
            $array_config['dir_forum'] = '';
        }
    } else {
        $array_config['is_user_forum'] = 0;
    }

    $array_config['nv_unickmin'] = $nv_Request->get_int('nv_unickmin', 'post', 3);
    $array_config['nv_unickmax'] = $nv_Request->get_int('nv_unickmax', 'post', 100);
    $array_config['nv_upassmin'] = $nv_Request->get_int('nv_upassmin', 'post', 5);
    $array_config['nv_upassmax'] = $nv_Request->get_int('nv_upassmax', 'post', 255);

    $array_config['nv_upass_type'] = $nv_Request->get_int('nv_upass_type', 'post', 0);
    $array_config['nv_unick_type'] = $nv_Request->get_int('nv_unick_type', 'post', 0);
    $array_config['allowmailchange'] = $nv_Request->get_int('allowmailchange', 'post', 0);
    $array_config['allowuserpublic'] = $nv_Request->get_int('allowuserpublic', 'post', 0);
    $array_config['allowquestion'] = $nv_Request->get_int('allowquestion', 'post', 0);
    $array_config['allowloginchange'] = $nv_Request->get_int('allowloginchange', 'post', 0);
    $array_config['allowuserlogin'] = $nv_Request->get_int('allowuserlogin', 'post', 0);
    $array_config['allowuserloginmulti'] = $nv_Request->get_int('allowuserloginmulti', 'post', 0);
    $array_config['allowuserreg'] = $nv_Request->get_int('allowuserreg', 'post', 0);
    $array_config['openid_servers'] = $nv_Request->get_typed_array('openid_servers', 'post', 'string');
    $array_config['openid_servers'] = !empty($array_config['openid_servers']) ? implode(',', $array_config['openid_servers']) : '';
    $array_config['user_check_pass_time'] = 60 * $nv_Request->get_int('user_check_pass_time', 'post');
    $array_config['email_dot_equivalent'] = $nv_Request->get_int('email_dot_equivalent', 'post', 0);
    $array_config['email_plus_equivalent'] = (int) $nv_Request->get_bool('email_plus_equivalent', 'post', false);
    $array_config['auto_login_after_reg'] = $nv_Request->get_int('auto_login_after_reg', 'post', 0);
    $array_config['pass_timeout'] = 86400 * $nv_Request->get_int('pass_timeout', 'post', 0);
    $array_config['oldpass_num'] = $nv_Request->get_int('oldpass_num', 'post', 5);
    $array_config['send_pass'] = (int) $nv_Request->get_bool('send_pass', 'post', false);
    $array_config['login_name_type'] = $nv_Request->get_title('login_name_type', 'post', '');
    if (!in_array($array_config['login_name_type'], $login_name_types, true)) {
        $array_config['login_name_type'] = 'username';
    }
    $array_config['remove_2step_method'] = (int) $nv_Request->get_bool('remove_2step_method', 'post', false);
    $array_config['remove_2step_allow'] = (int) $nv_Request->get_bool('remove_2step_allow', 'post', false);

    $array_config['whoviewuser'] = $nv_Request->get_typed_array('whoviewuser', 'post', 'int', []);
    $array_config['whoviewuser'] = !empty($array_config['whoviewuser']) ? implode(',', nv_groups_post(array_intersect($array_config['whoviewuser'], array_keys($groups_list)))) : '';

    $array_config['openid_processing'] = $nv_Request->get_typed_array('openid_processing', 'post', 'string', []);
    $array_config['openid_processing'] = !empty($array_config['openid_processing']) ? implode(',', $array_config['openid_processing']) : '';

    if ($array_config['user_check_pass_time'] < 120 and $array_config['user_check_pass_time'] != 0) {
        $array_config['user_check_pass_time'] = 120;
    }
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $array_config['name_show'] = $nv_Request->get_int('name_show', 'post', 0);
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = :config_name");
    $sth->bindValue(':config_name', 'name_show', PDO::PARAM_STR);
    $sth->bindParam(':config_value', $array_config['name_show'], PDO::PARAM_INT);
    $sth->execute();

    // Tự động gán oauth vào tài khoản đã tồn tại
    $array_config['auto_assign_oauthuser'] = (int) $nv_Request->get_bool('auto_assign_oauthuser', 'post', false);
    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='auto_assign_oauthuser'");
    $stmt->bindParam(':content', $array_config['auto_assign_oauthuser'], PDO::PARAM_STR);
    $stmt->execute();

    // Gửi email cho người dùng khi admin thao tác tới tài khoản
    $array_config['admin_email'] = (int) $nv_Request->get_bool('admin_email', 'post', false);
    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='admin_email'");
    $stmt->bindParam(':content', $array_config['admin_email'], PDO::PARAM_STR);
    $stmt->execute();

    if (defined('NV_IS_GODADMIN') and empty($global_config['idsite'])) {
        // Thời gian tài khoản chờ kích hoạt bị xóa
        $array_config['register_active_time'] = 3600 * $nv_Request->get_int('register_active_time', 'post', 0);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='register_active_time'");
        $stmt->bindParam(':content', $array_config['register_active_time'], PDO::PARAM_STR);
        $stmt->execute();

        // Cau hinh kich thuoc avatar
        $array_config['avatar_width'] = $nv_Request->get_int('avatar_width', 'post', 120);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='avatar_width'");
        $stmt->bindParam(':content', $array_config['avatar_width'], PDO::PARAM_STR);
        $stmt->execute();

        $array_config['min_old_user'] = $nv_Request->get_int('min_old_user', 'post', 0);
        if ($array_config['min_old_user'] < 0) {
            $array_config['min_old_user'] = 0;
        }
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='min_old_user'");
        $stmt->bindParam(':content', $array_config['min_old_user'], PDO::PARAM_STR);
        $stmt->execute();

        // Chặn đăng ký lại username đã xóa
        $array_config['hold_deleted_username'] = $nv_Request->get_int('hold_deleted_username', 'post', 0);
        if ($array_config['hold_deleted_username'] < 0) {
            $array_config['hold_deleted_username'] = 0;
        } elseif ($array_config['hold_deleted_username'] > 1000) {
            $array_config['hold_deleted_username'] = 1000;
        }
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='hold_deleted_username'");
        $stmt->bindParam(':content', $array_config['hold_deleted_username'], PDO::PARAM_STR);
        $stmt->execute();

        // Cấu hình số tuổi nhỏ nhất để thành viên có thể tham gia
        $array_config['avatar_height'] = $nv_Request->get_int('avatar_height', 'post', 16);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='avatar_height'");
        $stmt->bindParam(':content', $array_config['avatar_height'], PDO::PARAM_STR);
        $stmt->execute();

        // Kich hoat chuc nang xet duyet thanh vien moi dang ky
        $array_config['active_group_newusers'] = ($nv_Request->get_int('active_group_newusers', 'post', 0) ? 1 : 0);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='active_group_newusers'");
        $stmt->bindParam(':content', $array_config['active_group_newusers'], PDO::PARAM_STR);
        $stmt->execute();

        // Chức năng kiểm duyệt chỉnh sửa
        $array_config['active_editinfo_censor'] = ($nv_Request->get_int('active_editinfo_censor', 'post', 0) ? 1 : 0);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='active_editinfo_censor'");
        $stmt->bindParam(':content', $array_config['active_editinfo_censor'], PDO::PARAM_STR);
        $stmt->execute();

        $array_config['active_user_logs'] = ($nv_Request->get_int('active_user_logs', 'post', 0) ? 1 : 0);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='active_user_logs'");
        $stmt->bindParam(':content', $array_config['active_user_logs'], PDO::PARAM_STR);
        $stmt->execute();

        $array_config['deny_email'] = $nv_Request->get_title('deny_email', 'post', '', 1);

        if (!empty($array_config['deny_email'])) {
            $array_config['deny_email'] = valid_name_config(explode(',', $array_config['deny_email']));
            $array_config['deny_email'] = implode('|', $array_config['deny_email']);
        }

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='deny_email'");
        $stmt->bindParam(':content', $array_config['deny_email'], PDO::PARAM_STR, strlen($array_config['deny_email']));
        $stmt->execute();

        $array_config['deny_name'] = $nv_Request->get_title('deny_name', 'post', '', 1);
        if (!empty($array_config['deny_name'])) {
            $array_config['deny_name'] = valid_name_config(explode(',', $array_config['deny_name']));
            $array_config['deny_name'] = implode('|', $array_config['deny_name']);
        }
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='deny_name'");
        $stmt->bindParam(':content', $array_config['deny_name'], PDO::PARAM_STR, strlen($array_config['deny_name']));
        $stmt->execute();

        $array_config['password_simple'] = $nv_Request->get_title('password_simple', 'post', '', 1);
        if (!empty($array_config['password_simple'])) {
            $array_config['password_simple'] = array_map('trim', explode(',', $array_config['password_simple']));
            $array_config['password_simple'] = array_unique($array_config['password_simple']);
            asort($array_config['password_simple']);
            $array_config['password_simple'] = implode('|', $array_config['password_simple']);
        }
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_config SET content= :content, edit_time=' . NV_CURRENTTIME . " WHERE config='password_simple'");
        $stmt->bindParam(':content', $array_config['password_simple'], PDO::PARAM_STR, strlen($array_config['password_simple']));
        $stmt->execute();

        $access_admin = [];
        $access_admin['access_viewlist'] = $nv_Request->get_typed_array('access_viewlist', 'post', 'bool');
        $access_admin['access_addus'] = $nv_Request->get_typed_array('access_addus', 'post', 'bool');
        $access_admin['access_waiting'] = $nv_Request->get_typed_array('access_waiting', 'post', 'bool');
        $access_admin['access_editcensor'] = $nv_Request->get_typed_array('access_editcensor', 'post', 'bool');
        $access_admin['access_editus'] = $nv_Request->get_typed_array('access_editus', 'post', 'bool');
        $access_admin['access_delus'] = $nv_Request->get_typed_array('access_delus', 'post', 'bool');
        $access_admin['access_passus'] = $nv_Request->get_typed_array('access_passus', 'post', 'bool');
        $access_admin['access_groups'] = $nv_Request->get_typed_array('access_groups', 'post', 'bool');
        $sql = 'UPDATE ' . NV_MOD_TABLE . "_config SET content='" . serialize($access_admin) . "', edit_time=" . NV_CURRENTTIME . " WHERE config='access_admin'";
        $db->query($sql);
        nv_save_file_config_global();
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('ChangeConfigModule'), '', $admin_info['userid']);
    $nv_Cache->delAll();

    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    $respon['refresh'] = 1;
    nv_jsonOutput($respon);
} else {
    $array_config = $global_config;
}

$array_config['pass_timeout'] /= 86400;
$array_config['openid_processing'] = !empty($array_config['openid_processing']) ? array_map('trim', explode(',', $array_config['openid_processing'])) : [];

$sql = 'SELECT config, content FROM ' . NV_MOD_TABLE . "_config WHERE
    config='deny_email' OR config='deny_name' OR config='password_simple' OR
    config='avatar_width' OR config='avatar_height' OR config='active_group_newusers' OR
    config='active_editinfo_censor' OR config='active_user_logs' OR config='min_old_user' OR
    config='auto_assign_oauthuser' OR config='admin_email' OR config='register_active_time' OR
    config='hold_deleted_username'
";
$result = $db->query($sql);
while ([$config, $content] = $result->fetch(3)) {
    $content = array_map('trim', explode('|', $content));
    $array_config[$config] = implode(', ', $content);
}
$result->closeCursor();

$array_config['active_group_newusers'] = !empty($array_config['active_group_newusers']) ? ' checked="checked"' : '';
$array_config['active_editinfo_censor'] = !empty($array_config['active_editinfo_censor']) ? ' checked="checked"' : '';
$array_config['active_user_logs'] = !empty($array_config['active_user_logs']) ? ' checked="checked"' : '';
$array_config['auto_assign_oauthuser'] = !empty($array_config['auto_assign_oauthuser']) ? ' checked="checked"' : '';
$array_config['admin_email'] = !empty($array_config['admin_email']) ? ' checked="checked"' : '';
$array_config['register_active_time'] /= 3600;

$array_name_show = [
    0 => $nv_Lang->getModule('lastname_firstname'),
    1 => $nv_Lang->getModule('firstname_lastname')
];

$array_registertype = [
    0 => $nv_Lang->getModule('active_not_allow'),
    1 => $nv_Lang->getModule('active_all'),
    2 => $nv_Lang->getModule('active_email'),
    3 => $nv_Lang->getModule('active_admin_check')
];
$array_openid_processing = [
    'connect' => $nv_Lang->getModule('admin_openid_processing_connect'),
    'create' => $nv_Lang->getModule('admin_openid_processing_create'),
    'auto' => $nv_Lang->getModule('admin_openid_processing_auto')
];

$ignorefolders = [
    '',
    '.',
    '..',
    'index.html',
    '.htaccess'
];
$array_config['checkss'] = $checkss;
$array_config['whoviewuser'] = array_map('intval', explode(',', $array_config['whoviewuser']));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);

$tpl->assign('DATA', $array_config);
$tpl->assign('ACCESS_ADMIN', $access_admin);
$tpl->assign('REGISTER_TYPES', $array_registertype);
$tpl->assign('NAMES_SHOW', $array_name_show);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('LOGIN_NAME_TYPES', $login_name_types);
$tpl->assign('OPENID_PROCESSING', $array_openid_processing);

// Khả năng tích hợp diễn đàn
$user_forum_show = 0;
if (!in_array($global_config['dir_forum'], $ignorefolders, true) and file_exists(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet')) {
    $forum_files = @scandir(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet');
    if (!empty($forum_files) and in_array('is_user.php', $forum_files, true) and in_array('changepass.php', $forum_files, true) and in_array('editinfo.php', $forum_files, true) and in_array('login.php', $forum_files, true) and in_array('logout.php', $forum_files, true) and in_array('lostpass.php', $forum_files, true) and in_array('register.php', $forum_files, true)) {
        $user_forum_show = 1;
    }
}
$tpl->assign('USER_FORUM_SHOW', $user_forum_show);

// Thư mục chứa diễn đàn
$nv_files = @scandir(NV_ROOTDIR);
$dirs_forum = [];
foreach ($nv_files as $value) {
    if (!in_array($value, $ignorefolders, true) and is_dir(NV_ROOTDIR . '/' . $value)) {
        if (is_dir(NV_ROOTDIR . '/' . $value . '/nukeviet')) {
            $dirs_forum[] = $value;
        }
    }
}
$tpl->assign('DIRS_FORUM', $dirs_forum);

// Các nhà cung cấp Oauth
$openid_files = @scandir(NV_ROOTDIR . '/modules/users/login');
$openid_servers = [];
foreach ($openid_files as $server) {
    if (preg_match('/^(cas|oauth)\-([a-z0-9\-\_]+)\.php$/', $server, $m)) {
        $link_config = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $m[2];
        if ($server == 'oauth-zalo.php') {
            $link_config = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings';
        }

        $disabled = 0;
        if ($server == 'cas-single-sign-on.php' and !isset($global_config['config_sso'])) {
            $disabled = 1;
        } elseif ($server == 'oauth-facebook.php' and (empty($global_config['facebook_client_id']) or empty($global_config['facebook_client_secret']))) {
            $disabled = 1;
        } elseif ($server == 'oauth-google.php' and (empty($global_config['google_client_id']) or empty($global_config['google_client_secret']))) {
            $disabled = 1;
        } elseif ($server == 'oauth-google-identity.php' and empty($global_config['google_client_id'])) {
            $disabled = 1;
        } elseif ($server == 'oauth-zalo.php' and (empty($global_config['zaloOfficialAccountID']) or empty($global_config['zaloAppID']) or empty($global_config['zaloAppSecretKey']))) {
            $disabled = 1;
        }

        $openid_servers[] = [
            'name' => $m[2],
            'title' => $m[1] . ' ' . $m[2],
            'note' => $nv_Lang->getModule('oauth_config', $m[1] . ' ' . $m[2]),
            'config' => ($server == 'oauth-zalo.php' or file_exists(NV_ROOTDIR . '/modules/users/admin/config_' . $m[2] . '.php')),
            'link' => $link_config,
            'disabled' => $disabled
        ];
    }
}
$tpl->assign('OPENID_SERVERS', $openid_servers);

$facebook_redirecturi = [];
foreach ($global_config['setup_langs'] as $lang) {
    $facebook_redirecturi[] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=facebook';
    $facebook_redirecturi[] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;' . NV_OP_VARIABLE . '=2step&amp;auth=facebook';
}
$facebook_redirecturi[] = NV_BASE_ADMINURL . 'index.php?auth=facebook';
foreach ($facebook_redirecturi as $key => $value) {
    $facebook_redirecturi[$key] = urlRewriteWithDomain($value, NV_MY_DOMAIN);
}

$google_redirecturi = [];
foreach ($global_config['setup_langs'] as $lang) {
    $google_redirecturi[] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=oauth&amp;server=google';
    $google_redirecturi[] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;' . NV_OP_VARIABLE . '=2step&amp;auth=google';
}
$google_redirecturi[] = NV_BASE_ADMINURL . 'index.php?auth=google';
foreach ($google_redirecturi as $key => $value) {
    $google_redirecturi[$key] = urlRewriteWithDomain($value, NV_MY_DOMAIN);
}

$facebook_datadeletionurl = [];
foreach ($global_config['setup_langs'] as $lang) {
    $facebook_datadeletionurl[] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['datadeletion'] . '/facebook';
}
foreach ($facebook_datadeletionurl as $key => $value) {
    $facebook_datadeletionurl[$key] = urlRewriteWithDomain($value, NV_MY_DOMAIN);
}

$tpl->assign('FACEBOOK_REDIRECTURI', $facebook_redirecturi);
$tpl->assign('FACEBOOK_DATADELETIONURL', $facebook_datadeletionurl);
$tpl->assign('GOOGLE_REDIRECTURI', $google_redirecturi);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
