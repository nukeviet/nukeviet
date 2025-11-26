<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_admin_add');

if (!(defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['spadmin_add_admin'] == 1))) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Trang chuyển tiếp kết quả
if ($nv_Request->get_int('result', 'get', 0)) {
    $checksess = $nv_Request->get_title('checksess', 'get', '');
    if ($checksess != NV_CHECK_SESSION) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $session_files = $nv_Request->get_string('nv_admin_profile', 'session', '');
    $session_files = json_decode($session_files, true);
    if (!is_array($session_files)) {
        $session_files = [];
    }
    if (empty($session_files) or !defined('NV_IS_GODADMIN')) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $nv_Request->unset_request('nv_admin_profile', 'session');
    $page_title = $nv_Lang->getModule('nv_admin_add_result');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('add-result.tpl'));
    $tpl->assign('LANG', $nv_Lang);

    $lev = ($session_files['lev'] == 2) ? $nv_Lang->getGlobal('level2') : $nv_Lang->getGlobal('level3');
    $lev_expired = !empty($session_files['lev_expired']) ? $session_files['lev_expired'] : $nv_Lang->getModule('unlimited');

    $array = [
        'admin_id' => $session_files['admin_id'],
        'lev' => $lev,
        'modules' => $session_files['modules'],
        'lev_expired' => $lev_expired
    ];
    if ($session_files['downgrade_to_modadmin']) {
        $inf = !empty($session_files['after_modules']) ? ': ' . $session_files['after_modules'] : '';
        $array['after_exp_action'] = $nv_Lang->getModule('downgrade_to_modadmin') . $inf;
    }
    $array['position'] = $session_files['position'];
    $array['editor'] = (!empty($session_files['editor']) ? $session_files['editor'] : $nv_Lang->getModule('not_use'));
    $array['allow_files_type'] = (!empty($session_files['allow_files_type']) ? implode(', ', $session_files['allow_files_type']) : $nv_Lang->getGlobal('no'));
    $array['allow_modify_files'] = ($session_files['allow_modify_files'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'));
    $array['allow_create_subdirectories'] = ($session_files['allow_create_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'));
    $array['allow_modify_subdirectories'] = ($session_files['allow_modify_subdirectories'] ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'));

    $tpl->assign('DATA', $array);
    $tpl->assign('MODULE_NAME', $module_name);

    $contents = $tpl->fetch('add-result.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($global_config['max_user_admin'] > 0) {
    $sql = 'SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE lev>1';
    $user_number = $db->query($sql)->fetchColumn();
    if ($user_number >= $global_config['max_user_admin']) {
        $contents = nv_theme_alert('', $nv_Lang->getGlobal('limit_admin_number', $global_config['max_user_admin']));
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

$adminThemes = [''];
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

$editors = [];
$dirs = nv_scandir(NV_ROOTDIR . '/' . NV_EDITORSDIR, '/^[a-zA-Z0-9_\-]+$/');
if (!empty($dirs)) {
    foreach ($dirs as $dir) {
        if (file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $dir . '/nv.php')) {
            $editors[] = $dir;
        }
    }
}

$allmods = [];
foreach ($global_config['setup_langs'] as $l) {
    $allmods[$l] = nv_site_mods($l);
}

// Lưu vào CSDL thông tin admin mới
if ($nv_Request->get_int('save', 'post', 0)) {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];

    if ($checkss != $nv_Request->get_string('checkss', 'post')) {
        $respon['mess'] = 'Error Session, Please close the browser and try again';
        nv_jsonOutput($respon);
    }
    $userid = $nv_Request->get_title('userid', 'post', 0);
    $md5username = nv_md5safe($userid);
    if (preg_match('/^([0-9]+)$/', $userid)) {
        $sql = 'SELECT userid, username, active, group_id, in_groups, delete_at
        FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . (int) $userid . ' OR md5username=' . $db->quote($md5username);
    } else {
        $sql = 'SELECT userid, username, active, group_id, in_groups, delete_at
        FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username=' . $db->quote($md5username);
    }
    [$userid, $username, $active, $_group_id, $_in_groups, $delete_at] = $db->query($sql)->fetch(3);
    if (empty($userid)) {
        $respon['input'] = 'userid';
        $respon['mess'] = $nv_Lang->getModule('add_error_choose');
        nv_jsonOutput($respon);
    }

    $sql = 'SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
    $count = $db->query($sql)->fetchColumn();
    if ($count) {
        $respon['input'] = 'userid';
        $respon['mess'] = $nv_Lang->getModule('add_error_exist');
        nv_jsonOutput($respon);
    }

    if (empty($userid)) {
        $respon['input'] = 'userid';
        $respon['mess'] = $nv_Lang->getModule('add_error_notexist');
        nv_jsonOutput($respon);
    }

    if (empty($active)) {
        $respon['input'] = 'userid';
        $respon['mess'] = $nv_Lang->getModule('username_noactive', $username);
        nv_jsonOutput($respon);
    }

    if (!empty($delete_at)) {
        $respon['input'] = 'userid';
        $respon['mess'] = $nv_Lang->getModule('add_error_deleted');
        nv_jsonOutput($respon);
    }

    $position = $nv_Request->get_title('position', 'post', '', 1);
    if (empty($position)) {
        $respon['input'] = 'position';
        $respon['mess'] = $nv_Lang->getModule('position_incorrect');
        nv_jsonOutput($respon);
    }

    $lev_expired = $nv_Request->get_title('lev_expired', 'post', '');
    $lev_expired_sql = 0;
    if (!empty($lev_expired)) {
        $lev_expired_sql = nv_d2u_post($lev_expired, 23, 59, 59);
        if (empty($lev_expired_sql) or $lev_expired_sql <= NV_CURRENTTIME) {
            $respon['input'] = 'lev_expired';
            $respon['mess'] = $nv_Lang->getModule('lev_expired_error');
            nv_jsonOutput($respon);
        }
    }

    $lev = $nv_Request->get_int('lev', 'post', 0);
    $lev = ($lev != 2 or !defined('NV_IS_GODADMIN')) ? 3 : 2;

    $editor = $nv_Request->get_title('editor', 'post');
    (!empty($editors) and !empty($editor) and !in_array($editor, $editors, true)) && $editor = '';

    $allow_files_type = $nv_Request->get_typed_array('allow_files_type', 'post', 'title', []);
    $allow_create_subdirectories = $nv_Request->get_int('allow_create_subdirectories', 'post', 0);
    $allow_modify_files = $nv_Request->get_int('allow_modify_files', 'post', 0);
    $allow_modify_subdirectories = $nv_Request->get_int('allow_modify_subdirectories', 'post', 0);
    $modules = [];
    if ($lev == 3) {
        $_modules = $_POST['modules'] ?? [];
        if (!empty($_modules)) {
            foreach ($_modules as $l => $vs) {
                if (!empty($vs)) {
                    foreach ($vs as $m) {
                        if (isset($allmods[$l][$m])) {
                            !isset($modules[$l]) && $modules[$l] = [];
                            $modules[$l][] = $m;
                        }
                    }
                }
            }
        }
    }

    $downgrade_to_modadmin = (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) ? $nv_Request->get_bool('downgrade_to_modadmin', 'post', false) : false;
    $after_modules = [];
    $ss_after_modules = [];
    if ($downgrade_to_modadmin) {
        $_after_modules = $_POST['after_modules'] ?? [];
        if (!empty($_after_modules)) {
            foreach ($_after_modules as $l => $vs) {
                if (!empty($vs)) {
                    foreach ($vs as $m) {
                        if (isset($allmods[$l][$m])) {
                            !isset($after_modules[$l]) && $after_modules[$l] = [];
                            $after_modules[$l][] = $m;
                            $ss_after_modules[] = $allmods[$l][$m]['custom_title'] . ' (' . $language_array[$l]['name'] . ')';
                        }
                    }
                }
            }
        }
    }

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    $admin_theme = (!empty($admin_theme) and in_array($admin_theme, $adminThemes, true)) ? $admin_theme : '';

    $mds = [];
    if (!empty($modules)) {
        foreach ($modules as $l => $_modules) {
            $update = 'UPDATE ' . $db_config['prefix'] . '_' . $l . '_modules SET admins= CASE ';
            $titles = [];
            $array_keys = array_keys($allmods[$l]);
            foreach ($array_keys as $i => $mod) {
                if (!empty($mod) and in_array($mod, $_modules, true)) {
                    $site_mods_admins = ((!empty($allmods[$l][$mod]['admins'])) ? $allmods[$l][$mod]['admins'] . ',' : '') . $userid;
                    $site_mods_admins = array_map('intval', explode(',', $site_mods_admins));
                    $site_mods_admins = array_unique($site_mods_admins);
                    $site_mods_admins = implode(',', $site_mods_admins);
                    $titles[$i] = $db->quote($mod);
                    $mds[] = $allmods[$l][$mod]['custom_title'] . ' (' . $language_array[$l]['name'] . ')';
                    $update .= 'WHEN title = ' . $titles[$i] . ' THEN ' . $db->quote($site_mods_admins) . ' ';
                }
            }

            if (!empty($titles)) {
                $update .= 'END WHERE title IN (' . implode(',', $titles) . ')';
                $db->query($update);
                $nv_Cache->delMod('modules', $l);
            }
        }
    }

    $allow_files_type = array_values(array_intersect($global_config['file_allowed_ext'], $allow_files_type));
    $files_level = (!empty($allow_files_type) ? implode(',', $allow_files_type) : '') . '|' . $allow_modify_files . '|' . $allow_create_subdirectories . '|' . $allow_modify_subdirectories;
    $after_modules_sql = $downgrade_to_modadmin ? json_encode($after_modules) : '';

    $sth = $db->prepare('INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '
        (admin_id, editor, lev, lev_expired, after_exp_action, files_level, position, admin_theme, is_suspend, susp_reason, check_num, last_login, last_ip, last_agent) VALUES
        ( ' . $userid . ', :editor, ' . $lev . ', ' . $lev_expired_sql . ", :after_exp_action, :files_level, :position, :admin_theme, 0,'', '', 0, '', ''	)");
    $sth->bindParam(':editor', $editor, PDO::PARAM_STR);
    $sth->bindParam(':after_exp_action', $after_modules_sql, PDO::PARAM_STR);
    $sth->bindParam(':files_level', $files_level, PDO::PARAM_STR);
    $sth->bindParam(':position', $position, PDO::PARAM_STR);
    $sth->bindParam(':admin_theme', $admin_theme, PDO::PARAM_STR);

    if ($sth->execute()) {
        nv_groups_add_user($lev, $userid);

        // Đặt nhóm mặc định là nhóm quản trị
        $sql = 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET group_id=' . $lev . ' WHERE userid=' . $userid;
        $db->query($sql);

        $session_files = json_encode([
            'admin_id' => $userid,
            'editor' => $editor,
            'lev' => $lev,
            'lev_expired' => $lev_expired,
            'downgrade_to_modadmin' => $downgrade_to_modadmin,
            'after_modules' => !empty($ss_after_modules) ? implode(', ', $ss_after_modules) : '',
            'allow_files_type' => $allow_files_type,
            'allow_modify_files' => $allow_modify_files,
            'allow_create_subdirectories' => $allow_create_subdirectories,
            'allow_modify_subdirectories' => $allow_modify_subdirectories,
            'position' => $position,
            'modules' => !empty($mds) ? implode(', ', $mds) : ''
        ]);
        $nv_Request->set_Session('nv_admin_profile', $session_files);

        $inf = 'Username: ' . $username . '<br/>' . $nv_Lang->getModule('lev') . ': ' . ($lev == 2 ? $nv_Lang->getGlobal('level2') : $nv_Lang->getGlobal('level3') . (!empty($mds) ? ': ' . implode(', ', $mds) : '')) . '<br/>' . $nv_Lang->getModule('lev_expired') . ': ' . (!empty($lev_expired) ? $lev_expired : $nv_Lang->getModule('unlimited'));
        if ($lev == 2 and $downgrade_to_modadmin) {
            $inf .= '<br/>' . $nv_Lang->getModule('after_exp_action') . ': ' . $nv_Lang->getModule('downgrade_to_modadmin') . (!empty($ss_after_modules) ? ': ' . implode(', ', $ss_after_modules) : '');
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('menuadd'), $inf, $admin_info['userid']);

        $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add&result=1&checksess=' . NV_CHECK_SESSION;
        $respon['status'] = 'OK';
        nv_jsonOutput($respon);
    }

    $respon['mess'] = $nv_Lang->getModule('add_error_diff');
    nv_jsonOutput($respon);
}

$userid = $nv_Request->get_title('userid', 'get');

//filtersql
$filtersql = ' userid NOT IN (SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ')';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('add.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('OP', $op);
$tpl->assign('MODULE_NAME', $module_name);

$tpl->assign('CHECKSS', $checkss);
$tpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
$tpl->assign('ADMINTHEMES', $adminThemes);
$tpl->assign('EDITORS', $editors);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ALLMODS', $allmods);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('DATE_FORMAT', nv_region_config('jsdate_post'));
$tpl->assign('USERID', $userid ?: '');

$contents = $tpl->fetch('add.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
