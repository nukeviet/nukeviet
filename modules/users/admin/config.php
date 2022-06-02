<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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

$oauth_config = $nv_Request->get_title('oauth_config', 'post,get');
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $oauth_config);
if (preg_match('/^([a-z0-9\-\_]+)$/', $oauth_config, $m) and file_exists(NV_ROOTDIR . '/modules/users/admin/config_' . $oauth_config . '.php')) {
    $page_title = sprintf($lang_module['oauth_config'], $oauth_config);

    require NV_ROOTDIR . '/modules/users/admin/config_' . $oauth_config . '.php';
} else {
    if ($nv_Request->isset_request('save', 'post')) {
        if ($checkss != $nv_Request->get_string('checkss', 'post')) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
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
        $array_config['auto_login_after_reg'] = $nv_Request->get_int('auto_login_after_reg', 'post', 0);

        $array_config['whoviewuser'] = $nv_Request->get_typed_array('whoviewuser', 'post', 'int', []);
        $array_config['whoviewuser'] = !empty($array_config['whoviewuser']) ? implode(',', nv_groups_post(array_intersect($array_config['whoviewuser'], array_keys($groups_list)))) : '';

        $array_config['openid_processing'] = $nv_Request->get_typed_array('openid_processing', 'post', 'string', []);
        $array_config['openid_processing'] = !empty($array_config['openid_processing']) ? implode(',', $array_config['openid_processing']) : '';

        if ($array_config['user_check_pass_time'] < 120) {
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
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['ChangeConfigModule'], '', $admin_info['userid']);
        $nv_Cache->delAll();
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    } else {
        $array_config = $global_config;
    }

    $array_config['allowmailchange'] = !empty($array_config['allowmailchange']) ? ' checked="checked"' : '';
    $array_config['allowuserpublic'] = !empty($array_config['allowuserpublic']) ? ' checked="checked"' : '';
    $array_config['allowquestion'] = !empty($array_config['allowquestion']) ? ' checked="checked"' : '';
    $array_config['allowloginchange'] = !empty($array_config['allowloginchange']) ? ' checked="checked"' : '';
    $array_config['allowuserlogin'] = !empty($array_config['allowuserlogin']) ? ' checked="checked"' : '';
    $array_config['allowuserloginmulti'] = !empty($array_config['allowuserloginmulti']) ? ' checked="checked"' : '';
    $array_config['is_user_forum'] = !empty($array_config['is_user_forum']) ? ' checked="checked"' : '';
    $array_config['auto_login_after_reg'] = !empty($array_config['auto_login_after_reg']) ? ' checked="checked"' : '';
    $array_config['openid_processing'] = !empty($array_config['openid_processing']) ? array_map('trim', explode(',', $array_config['openid_processing'])) : [];

    $sql = 'SELECT config, content FROM ' . NV_MOD_TABLE . "_config WHERE
        config='deny_email' OR config='deny_name' OR config='password_simple' OR
        config='avatar_width' OR config='avatar_height' OR config='active_group_newusers' OR
        config='active_editinfo_censor' OR config='active_user_logs' OR config='min_old_user' OR
        config='auto_assign_oauthuser' OR config='admin_email'
    ";
    $result = $db->query($sql);
    while (list($config, $content) = $result->fetch(3)) {
        $content = array_map('trim', explode('|', $content));
        $array_config[$config] = implode(', ', $content);
    }
    $result->closeCursor();

    $array_config['active_group_newusers'] = !empty($array_config['active_group_newusers']) ? ' checked="checked"' : '';
    $array_config['active_editinfo_censor'] = !empty($array_config['active_editinfo_censor']) ? ' checked="checked"' : '';
    $array_config['active_user_logs'] = !empty($array_config['active_user_logs']) ? ' checked="checked"' : '';
    $array_config['auto_assign_oauthuser'] = !empty($array_config['auto_assign_oauthuser']) ? ' checked="checked"' : '';
    $array_config['admin_email'] = !empty($array_config['admin_email']) ? ' checked="checked"' : '';

    $array_name_show = [
        0 => $lang_module['lastname_firstname'],
        1 => $lang_module['firstname_lastname']
    ];

    $array_registertype = [
        0 => $lang_module['active_not_allow'],
        1 => $lang_module['active_all'],
        2 => $lang_module['active_email'],
        3 => $lang_module['active_admin_check']
    ];
    $array_openid_processing = [
        'connect' => $lang_module['openid_processing_connect'],
        'create' => $lang_module['openid_processing_create'],
        'auto' => $lang_module['openid_processing_auto']
    ];

    $ignorefolders = [
        '',
        '.',
        '..',
        'index.html',
        '.htaccess'
    ];
    $array_config['checkss'] = $checkss;

    $xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('DATA', $array_config);
    $xtpl->assign('USER_CHECK_PASS_TIME', round($global_config['user_check_pass_time'] / 60));

    if (!in_array($global_config['dir_forum'], $ignorefolders, true) and file_exists(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet')) {
        $forum_files = @scandir(NV_ROOTDIR . '/' . $global_config['dir_forum'] . '/nukeviet');
        if (!empty($forum_files) and in_array('is_user.php', $forum_files, true) and in_array('changepass.php', $forum_files, true) and in_array('editinfo.php', $forum_files, true) and in_array('login.php', $forum_files, true) and in_array('logout.php', $forum_files, true) and in_array('lostpass.php', $forum_files, true) and in_array('register.php', $forum_files, true)) {
            $xtpl->parse('main.user_forum');
        }
    }

    for ($id = 3; $id < 20; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_unickmin'] == $id) ? ' selected="selected"' : '',
            'value' => $id
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_unickmin');
    }
    for ($id = 20; $id < 100; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_unickmax'] == $id) ? ' selected="selected"' : '',
            'value' => $id
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_unickmax');
    }

    $lang_global['unick_type_0'] = $lang_module['unick_type_0'];
    for ($id = 0; $id < 5; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_unick_type'] == $id) ? ' selected="selected"' : '',
            'value' => $lang_global['unick_type_' . $id]
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_unick_type');
    }

    for ($id = 5; $id < 20; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_upassmin'] == $id) ? ' selected="selected"' : '',
            'value' => $id
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_upassmin');
    }
    for ($id = 20; $id < 255; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_upassmax'] == $id) ? ' selected="selected"' : '',
            'value' => $id
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_upassmax');
    }

    $lang_global['upass_type_0'] = $lang_module['upass_type_0'];
    for ($id = 0; $id < 5; ++$id) {
        $array = [
            'id' => $id,
            'select' => ($global_config['nv_upass_type'] == $id) ? ' selected="selected"' : '',
            'value' => $lang_global['upass_type_' . $id]
        ];
        $xtpl->assign('OPTION', $array);
        $xtpl->parse('main.nv_upass_type');
    }

    foreach ($array_registertype as $id => $titleregister) {
        $array = [
            'id' => $id,
            'select' => ($array_config['allowuserreg'] == $id) ? ' selected="selected"' : '',
            'value' => $titleregister
        ];
        $xtpl->assign('REGISTERTYPE', $array);
        $xtpl->parse('main.registertype');
    }

    $nv_files = @scandir(NV_ROOTDIR);
    $i = 0;
    foreach ($nv_files as $value) {
        if (!in_array($value, $ignorefolders, true) and is_dir(NV_ROOTDIR . '/' . $value)) {
            if (is_dir(NV_ROOTDIR . '/' . $value . '/nukeviet')) {
                $array = [
                    'id' => $value,
                    'select' => ($value == $global_config['dir_forum']) ? ' selected="selected"' : '',
                    'value' => $value
                ];
                $xtpl->assign('DIR_FORUM', $array);
                $xtpl->parse('main.dir_forum.loop');
                ++$i;
            }
        }
    }
    if ($i) {
        $xtpl->parse('main.dir_forum');
    }

    foreach ($array_name_show as $id => $titleregister) {
        $array = [
            'id' => $id,
            'select' => ($global_config['name_show'] == $id) ? ' selected="selected"' : '',
            'value' => $titleregister
        ];
        $xtpl->assign('NAME_SHOW', $array);
        $xtpl->parse('main.name_show');
    }

    $array_config['whoviewuser'] = array_map('intval', explode(',', $array_config['whoviewuser']));
    foreach ($groups_list as $group_id => $group_name) {
        $whoview = [
            'key' => $group_id,
            'checked' => in_array((int) $group_id, $array_config['whoviewuser'], true) ? ' checked="checked"' : '',
            'title' => $group_name
        ];
        $xtpl->assign('WHOVIEW', $whoview);
        $xtpl->parse('main.whoviewlistuser');
    }

    foreach ($array_openid_processing as $key => $name) {
        $checked = (!empty($array_config['openid_processing']) and in_array((string) $key, $array_config['openid_processing'], true)) ? ' checked="checked"' : '';
        $xtpl->assign('OPENID_PROCESSING', [
            'key' => $key,
            'checked' => $checked,
            'name' => $name
        ]);
        $xtpl->parse('main.openid_processing');
    }

    $servers = $array_config['openid_servers'];

    $openid_files = @scandir(NV_ROOTDIR . '/modules/users/login');
    foreach ($openid_files as $server) {
        if (preg_match('/^(cas|oauth)\-([a-z0-9\-\_]+)\.php$/', $server, $m)) {
            $checked = (!empty($servers) and in_array($m[2], $servers, true)) ? ' checked="checked"' : '';
            $disabled = '';

            if ($server == 'cas-single-sign-on.php' and !isset($global_config['config_sso'])) {
                $disabled = ' disabled="disabled" ';
            } elseif ($server == 'oauth-facebook.php' and (empty($global_config['facebook_client_id']) or empty($global_config['facebook_client_secret']))) {
                $disabled = ' disabled="disabled" ';
            } elseif ($server == 'oauth-google.php' and (empty($global_config['google_client_id']) or empty($global_config['google_client_secret']))) {
                $disabled = ' disabled="disabled" ';
            } elseif ($server == 'oauth-zalo.php' and (empty($global_config['zaloOfficialAccountID']) or empty($global_config['zaloAppID']) or empty($global_config['zaloAppSecretKey']))) {
                $disabled = ' disabled="disabled" ';
            }

            $link_config = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $m[2];
            if ($server == 'oauth-zalo.php') {
                $link_config = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings';
            }
            $openid_assign = [
                'name' => $m[2],
                'title' => $m[1] . ' ' . $m[2],
                'checked' => $checked,
                'disabled' => $disabled,
                'link_config' => $link_config,
                'note' => sprintf($lang_module['oauth_config'], $m[1] . ' ' . $m[2])
            ];

            $xtpl->assign('OPENID', $openid_assign);
            if ($server == 'oauth-zalo.php' or file_exists(NV_ROOTDIR . '/modules/users/admin/config_' . $m[2] . '.php')) {
                $xtpl->parse('main.openid_servers.config');
            } else {
                $xtpl->parse('main.openid_servers.noconfig');
            }
            $xtpl->parse('main.openid_servers');
        }
    }

    $array_access = [
        [
            'id' => 1,
            'title' => $lang_global['level1']
        ],
        [
            'id' => 2,
            'title' => $lang_global['level2']
        ],
        [
            'id' => 3,
            'title' => $lang_global['level3']
        ]
    ];

    if (defined('NV_IS_GODADMIN') and empty($global_config['idsite'])) {
        $xtpl->assign('LINK_EDITCENSOR', sprintf($lang_module['active_editinfo_censor_note1'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=editcensor'));
        $xtpl->parse('main.avatar_size');
        $xtpl->parse('main.active_group_newusers');
        $xtpl->parse('main.active_editinfo_censor');
        $xtpl->parse('main.active_user_logs');
        foreach ($array_access as $access) {
            $level = $access['id'];
            $access['checked_viewlist'] = !empty($access_admin['access_viewlist'][$level]) ? ' checked="checked" ' : '';
            $access['checked_addus'] = !empty($access_admin['access_addus'][$level]) ? ' checked="checked" ' : '';
            $access['checked_waiting'] = !empty($access_admin['access_waiting'][$level]) ? ' checked="checked" ' : '';
            $access['checked_editcensor'] = !empty($access_admin['access_editcensor'][$level]) ? ' checked="checked" ' : '';
            $access['checked_editus'] = !empty($access_admin['access_editus'][$level]) ? ' checked="checked" ' : '';
            $access['checked_delus'] = !empty($access_admin['access_delus'][$level]) ? ' checked="checked" ' : '';
            $access['checked_passus'] = !empty($access_admin['access_passus'][$level]) ? ' checked="checked" ' : '';
            $access['checked_groups'] = !empty($access_admin['access_groups'][$level]) ? ' checked="checked" ' : '';
            $xtpl->assign('ACCESS', $access);
            $xtpl->parse('main.access.loop');
        }
        $xtpl->parse('main.access');
        $xtpl->parse('main.deny_config');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    $page_title = $lang_module['config'];
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
