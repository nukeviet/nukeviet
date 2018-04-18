<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:13
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

if (!(defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['spadmin_add_admin'] == 1))) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($nv_Request->get_int('result', 'get', 0)) {
    $checksess = $nv_Request->get_title('checksess', 'get', '');
    if ($checksess != NV_CHECK_SESSION) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $session_files = $nv_Request->get_string('nv_admin_profile', 'session', '');
    if (empty($session_files)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $session_files = unserialize($session_files);
    $nv_Request->unset_request('nv_admin_profile', 'session');
    nv_admin_add_result($session_files);
    exit();
}

$adminThemes = array( '' );
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);

if ($nv_Request->get_int('save', 'post', 0)) {
    $userid = $nv_Request->get_title('userid', 'post', 0);
    $lev = $nv_Request->get_int('lev', 'post', 0);
    $editor = $nv_Request->get_title('editor', 'post');
    $allow_files_type = $nv_Request->get_array('allow_files_type', 'post', array());
    $allow_create_subdirectories = $nv_Request->get_int('allow_create_subdirectories', 'post', 0);
    $allow_modify_files = $nv_Request->get_int('allow_modify_files', 'post', 0);
    $allow_modify_subdirectories = $nv_Request->get_int('allow_modify_subdirectories', 'post', 0);
    $modules = $nv_Request->get_array('modules', 'post', array());
    $position = $nv_Request->get_title('position', 'post', '', 1);

    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    $admin_theme =  (! empty($admin_theme) and in_array($admin_theme, $adminThemes))? $admin_theme : '';

    $md5username = nv_md5safe($userid);
    if (preg_match('/^([0-9]+)$/', $userid)) {
        $sql = 'SELECT userid, username, active, group_id, in_groups FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . intval($userid) . ' OR md5username=' . $db->quote($md5username);
    } else {
        $sql = 'SELECT userid, username, active, group_id, in_groups FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username=' . $db->quote($md5username);
    }
    list ($userid, $username, $active, $_group_id, $_in_groups) = $db->query($sql)->fetch(3);
    if (empty($userid)) {
        nv_htmlOutput($lang_module['add_error_choose']);
    }

    $sql = 'SELECT COUNT(*) FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
    $count = $db->query($sql)->fetchColumn();
    if ($count) {
        nv_htmlOutput($lang_module['add_error_exist']);
    }

    if (empty($userid)) {
        nv_htmlOutput($lang_module['add_error_notexist']);
    }
    if (empty($position)) {
        nv_htmlOutput($lang_module['position_incorrect']);
    }
    if (empty($active)) {
        nv_htmlOutput(sprintf($lang_module['username_noactive'], $username));
    }

    $lev = ($lev != 2 or !defined('NV_IS_GODADMIN')) ? 3 : 2;
    $mds = array();
    if ($lev == 3 and !empty($modules)) {
        $update = 'UPDATE ' . NV_MODULES_TABLE . ' SET admins= CASE ';
        $titles = array();
        $array_keys = array_keys($site_mods);
        foreach ($array_keys as $i => $mod) {
            if (!empty($mod) and in_array($mod, $modules)) {
                $site_mods_admins = ((!empty($site_mods[$mod]['admins'])) ? $site_mods[$mod]['admins'] . ',' : '') . $userid;
                $site_mods_admins = explode(',', $site_mods_admins);
                $site_mods_admins = array_map('intval', $site_mods_admins);
                $site_mods_admins = array_unique($site_mods_admins);
                $site_mods_admins = implode(',', $site_mods_admins);
                $titles[$i] = $db->quote($mod);
                $mds[$i] = $site_mods[$mod]['custom_title'];
                $update .= 'WHEN title = ' . $titles[$i] . ' THEN ' . $db->quote($site_mods_admins) . ' ';
            }
        }

        if (!empty($titles)) {
            $update .= 'END WHERE title IN (' . implode(',', $titles) . ')';
            $db->query($update);
            $nv_Cache->delMod('modules');
        }
    }

    $allow_files_type = array_values(array_intersect($global_config['file_allowed_ext'], $allow_files_type));
    $files_level = (!empty($allow_files_type) ? implode(',', $allow_files_type) : '') . '|' . $allow_modify_files . '|' . $allow_create_subdirectories . '|' . $allow_modify_subdirectories;

    $sth = $db->prepare("INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "
		(admin_id, editor, lev, files_level, position, admin_theme, is_suspend, susp_reason, check_num, last_login, last_ip, last_agent) VALUES
		( " . $userid . ", :editor, " . $lev . ", :files_level, :position, :admin_theme, 0,'', '', 0, '', ''	)");
    $sth->bindParam(':editor', $editor, PDO::PARAM_STR);
    $sth->bindParam(':files_level', $files_level, PDO::PARAM_STR);
    $sth->bindParam(':position', $position, PDO::PARAM_STR);
    $sth->bindParam(':admin_theme', $admin_theme, PDO::PARAM_STR);

    if ($sth->execute()) {
        nv_groups_add_user($lev, $userid);

        //Nếu là thành viên mới, thì xóa khỏi nhóm thành viên mới
        if ($_group_id == 7 or in_array(7, explode(',', $_in_groups))) {
            if ($_group_id == 7) {
                $_group_id = $lev;
            }
            $_in_groups = array_diff($_in_groups, array(
                7
            ));

            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET group_id = ' . $_group_id . ", in_groups='" . implode(',', $_in_groups) . "' WHERE userid = " . $userid);
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . '_groups SET numbers = numbers-1 WHERE group_id=7');
            $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . '_groups SET numbers = numbers+1 WHERE group_id=4');
        }

        $result = array(
            'admin_id' => $userid,
            'editor' => $editor,
            'lev' => $lev,
            'allow_files_type' => $allow_files_type,
            'allow_modify_files' => $allow_modify_files,
            'allow_create_subdirectories' => $allow_create_subdirectories,
            'allow_modify_subdirectories' => $allow_modify_subdirectories,
            'position' => $position,
            'modules' => (!empty($mds)) ? implode(', ', $mds) : ''
        );

        $session_files = serialize($result);
        $nv_Request->set_Session('nv_admin_profile', $session_files);

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['menuadd'], 'Username: ' . $username, $admin_info['userid']);
        nv_htmlOutput('OK');
    } else {
        nv_htmlOutput($lang_module['add_error_diff']);
    }
} else {
    $position = '';
    $admin_theme =  '';
    $userid = $nv_Request->get_title('userid', 'get');
    $editor = 'ckeditor';
    $lev = 3;
    $modules = array();
    $allow_files_type = explode(',', 'adobe,application,archives,audio,documents,flash,images,real,video');
    $allow_modify_files = $allow_modify_subdirectories = 0;
    $allow_create_subdirectories = 1;
}

$page_title = $lang_module['nv_admin_add'];

$mods = array();
$array_keys = array_keys($site_mods);
foreach ($array_keys as $mod) {
    $mods[$mod]['checked'] = in_array($mod, $modules) ? 1 : 0;
    $mods[$mod]['custom_title'] = $site_mods[$mod]['custom_title'];
}

$contents = array();

$contents['action'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add';
$contents['lev'] = array(
    $lang_module['lev'],
    $lev,
    $lang_global['level2'],
    $lang_global['level3']
);

$editors = array();

$dirs = nv_scandir(NV_ROOTDIR . '/' . NV_EDITORSDIR, '/^[a-zA-Z0-9_]+$/');
if (!empty($dirs)) {
    foreach ($dirs as $dir) {
        if (file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $dir . '/nv.php')) {
            $editors[] = $dir;
        }
    }
}

if (!empty($editors)) {
    $contents['editor'] = array(
        $lang_module['editor'],
        $editors,
        $editor,
        $lang_module['not_use']
    );
}

if (!empty($global_config['file_allowed_ext'])) {
    $contents['allow_files_type'] = array(
        $lang_module['allow_files_type'],
        $global_config['file_allowed_ext'],
        $allow_files_type
    );
}

$contents['allow_modify_files'] = array(
    $lang_module['allow_modify_files'],
    $allow_modify_files
);
$contents['allow_create_subdirectories'] = array(
    $lang_module['allow_create_subdirectories'],
    $allow_create_subdirectories
);
$contents['allow_modify_subdirectories'] = array(
    $lang_module['allow_modify_subdirectories'],
    $allow_modify_subdirectories
);

$contents['mods'] = array(
    $lang_module['if_level3_selected'],
    $mods
);
$contents['position'] = array(
    $lang_module['position'],
    $position,
    $lang_module['position_info']
);
$contents['info'] = $lang_module['nv_admin_add_info'];
$contents['submit'] = $lang_module['nv_admin_add'];

//filtersql
$filtersql = ' userid NOT IN (SELECT admin_id FROM ' . NV_AUTHORS_GLOBALTABLE . ')';

// Parse content
$xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('INFO', $contents['info']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('RESULT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add&result=1&checksess=' . NV_CHECK_SESSION);
$xtpl->assign('FILTERSQL', $crypt->encrypt($filtersql, NV_CHECK_SESSION));
$xtpl->assign('ACTION', $contents['action']);

foreach ($adminThemes as $_admin_theme) {
    $xtpl->assign('THEME_NAME', $_admin_theme);
    $xtpl->assign('THEME_SELECTED', ($_admin_theme == $admin_theme ? ' selected="selected"' : ''));
    $xtpl->parse('add.admin_theme');
}

if (isset($contents['editor'])) {
    $xtpl->assign('EDITOR0', $contents['editor'][0]);
    $xtpl->assign('EDITOR3', $contents['editor'][3]);
    foreach ($contents['editor'][1] as $edt) {
        $xtpl->assign('SELECTED', $edt == $contents['editor'][2] ? ' selected="selected"' : '');
        $xtpl->assign('EDITOR', $edt);
        $xtpl->parse('add.editor.loop');
    }
    $xtpl->parse('add.editor');
}

if (isset($contents['allow_files_type'])) {
    $xtpl->assign('ALLOW_FILES_TYPE0', $contents['allow_files_type'][0]);
    foreach ($contents['allow_files_type'][1] as $tp) {
        $xtpl->assign('CHECKED', in_array($tp, $contents['allow_files_type'][2]) ? ' checked="checked"' : '');
        $xtpl->assign('TP', $tp);
        $xtpl->parse('add.allow_files_type.loop');
    }
    $xtpl->parse('add.allow_files_type');
}

$xtpl->assign('ALLOW_MODIFY_FILES0', $contents['allow_modify_files'][0]);
$xtpl->assign('MODIFY_CHECKED', $contents['allow_modify_files'][1] ? ' checked="checked"' : '');

$xtpl->assign('ALLOW_CREATE_SUBDIRECTORIES0', $contents['allow_create_subdirectories'][0]);
$xtpl->assign('CREATE_CHECKED', $contents['allow_create_subdirectories'][1] ? ' checked="checked"' : '');

$xtpl->assign('ALLOW_MODIFY_SUBDIRECTORIES', $contents['allow_modify_subdirectories'][0]);
$xtpl->assign('ALLOW_MODIFY_SUBDIRECTORIES_CHECKED', $contents['allow_modify_subdirectories'][1] ? ' checked="checked"' : '');

$xtpl->assign('LEV0', $contents['lev'][0]);
$xtpl->assign('LEV2', $contents['lev'][2]);
$xtpl->assign('LEV3', $contents['lev'][3]);
$xtpl->assign('LEV2_CHECKED', $contents['lev'][1] == 2 ? ' checked="checked"' : '');
$xtpl->assign('LEV3_CHECKED', $contents['lev'][1] == 3 ? ' checked="checked"' : '');
$xtpl->assign('MODS0', $contents['mods'][0]);
$xtpl->assign('STYLE_MODS', $contents['lev'][1] == 3 ? 'visibility:visible;display:block;' : 'visibility:hidden;display:none;');

$xtpl->assign('USERID', $userid ? $userid : '');

if (defined("NV_IS_GODADMIN")) {
    $xtpl->parse('add.show_lev_2');
}

foreach ($contents['mods'][1] as $mod => $value) {
    $xtpl->assign('MOD_VALUE', $mod);
    $xtpl->assign('LEV_CHECKED', (!empty($value['checked'])) ? 'checked="checked"' : '');
    $xtpl->assign('CUSTOM_TITLE', $value['custom_title']);
    $xtpl->parse('add.lev_loop');
}

$xtpl->assign('POSITION0', $contents['position'][0]);
$xtpl->assign('POSITION1', $contents['position'][1]);
$xtpl->assign('POSITION2', $contents['position'][2]);
$xtpl->assign('SUBMIT', $contents['submit']);

$xtpl->parse('add');
$contents = $xtpl->text('add');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';