<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:24
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('main');

$admins = [];
if ($nv_Request->isset_request('id', 'get')) {
    $admin_id = $nv_Request->get_int('id', 'get', 0);
    $sql = 'SELECT t1.admin_id as admin_id, t1.admin_theme admin_theme, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
    t2.username as username, t2.email as email, t2.first_name as first_name, t2.last_name as last_name, t2.view_mail as view_mail, t2.regdate as regdate, t2.active as active
    FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE admin_id=' . $admin_id;
    $adminrows = $db->query($sql)->fetchAll();
    $numrows = sizeof($adminrows);

    if ($numrows != 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} else {
    $sql = 'SELECT t1.admin_id as admin_id, t1.admin_theme admin_theme, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
        t2.username as username, t2.email as email, t2.first_name as first_name, t2.last_name as last_name, t2.view_mail as view_mail, t2.regdate as regdate, t2.active as active
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid ORDER BY t1.lev ASC';

    $adminrows = $db->query($sql)->fetchAll();
    $numrows = sizeof($adminrows);
}

if ($numrows) {
    $sql = 'SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
    $list_modules = $nv_Cache->db($sql, '', 'modules');
    foreach ($adminrows as $row) {
        $login = $row['username'];
        $email = (defined('NV_IS_SPADMIN')) ? $row['email'] : (($row['admin_id'] == $admin_info['admin_id']) ? $row['email'] : (intval($row['view_mail']) ? $row['email'] : ''));
        $email = !empty($email) ? nv_EncodeEmail($email) : '';
        $level = intval($row['lev']);
        if ($level == 1) {
            $level_txt = $nv_Lang->getGlobal('level1');
        } elseif ($level == 2) {
            $level_txt = $nv_Lang->getGlobal('level2');
        } else {
            $array_mod = [];
            foreach ($list_modules as $row_mod) {
                if (!empty($row_mod['admins']) and in_array($row['admin_id'], explode(',', $row_mod['admins']))) {
                    $array_mod[] = $row_mod['custom_title'];
                }
            }
            $level_txt = implode(', ', $array_mod);
        }
        $last_login = intval($row['last_login']);
        $last_login = $last_login ? nv_date('l, d/m/Y H:i', $last_login) : $nv_Lang->getModule('last_login0');
        $last_agent = $row['last_agent'];

        $_browser = new NukeViet\Client\Browser($last_agent);
        $browser = [
            'key' => $_browser->getBrowserKey(),
            'name' => $_browser->getBrowser()
        ];
        $os = [
            'key' => $_browser->getPlatformKey(),
            'name' => $_browser->getPlatform()
        ];

        $is_suspend = intval($row['is_suspend']);
        $suspen_id = 0;
        $suspen_name = '';
        $suspen_starttime = '';
        $suspen_info = '';
        $suspen_adminlink = '';
        if (!empty($is_suspend)) {
            $last_reason = unserialize($row['susp_reason']);
            $last_reason = array_shift($last_reason);
            list($susp_admin_id, $susp_admin_uname, $susp_admin_fname, $susp_admin_lname) = $db->query('SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . intval($last_reason['start_admin']))->fetch(3);
            $suspen_id = $susp_admin_id;
            $suspen_name = nv_show_name_user($susp_admin_fname, $susp_admin_lname, $susp_admin_uname);
            $suspen_starttime = nv_date('d/m/Y H:i', $last_reason['starttime']);
            $suspen_info = $last_reason['info'];
            $suspen_adminlink = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;id=" . $susp_admin_id;
        }

        $tool_is_edit = $tool_is_suspend = $tool_is_del = $tool_is_2step = 0;
        if (defined('NV_IS_GODADMIN')) {
            // Quản trị tối cao thao tác
            $tool_is_edit = $tool_is_2step = 1;
            $tool_is_suspend = $tool_is_del = ($row['admin_id'] != $admin_info['admin_id']) ? 1 : 0;
        } elseif (defined('NV_IS_SPADMIN')) {
            // Điều hành chung hoặc quản trị tối cao
            if ($row['lev'] == 1) {
                // Đối với tài khoản quản trị tối cao
                $tool_is_edit = $tool_is_2step = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
                $tool_is_suspend = $tool_is_del = 0;
            } elseif ($row['lev'] == 2) {
                // Đối với tài khoản điều hành chung
                if ($row['admin_id'] == $admin_info['admin_id'] or $admin_info['level'] == 1) {
                    $tool_is_edit = $tool_is_2step = 1;
                } else {
                    $tool_is_edit = $tool_is_2step = 0;
                }
                $tool_is_suspend = $tool_is_del = 0;
            } elseif ($global_config['spadmin_add_admin'] == 1) {
                // Đối với tài khoản quản lý module khi hệ thống cho phép điều hành chung quản lý quản trị module
                $tool_is_edit = $tool_is_2step = $tool_is_suspend = $tool_is_del = 1;
            } else {
                // Đối với tài khoản quản lý module khi hệ thống không cho phép điều hành chung quản lý quản trị module
                $tool_is_edit = $tool_is_2step = $tool_is_suspend = $tool_is_del = 0;
            }
        } else {
            // Quản trị module thao tác
            $tool_is_edit = $tool_is_2step = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
            $tool_is_suspend = $tool_is_del = 0;
        }

        if (empty($row['files_level'])) {
            $allow_files_type = [];
            $allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
        } else {
            list($allow_files_type, $allow_modify_files, $allow_create_subdirectories, $allow_modify_subdirectories) = explode('|', $row['files_level']);
            $allow_files_type = !empty($allow_files_type) ? explode(',', $allow_files_type) : [];
            $allow_files_type = array_values(array_intersect($global_config['file_allowed_ext'], $allow_files_type));
        }

        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);

        $admins[$row['admin_id']] = [];
        $admins[$row['admin_id']]['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $row['admin_id'];
        $admins[$row['admin_id']]['full_name'] = $row['full_name'];
        $admins[$row['admin_id']]['login'] = $login;
        $admins[$row['admin_id']]['email'] = $email;
        $admins[$row['admin_id']]['level_txt'] = $level_txt;
        $admins[$row['admin_id']]['level'] = $level;
        $admins[$row['admin_id']]['levelloop'] = 4 - $level;
        $admins[$row['admin_id']]['position'] = $row['position'];
        $admins[$row['admin_id']]['is_suspend'] = $is_suspend;
        $admins[$row['admin_id']]['active'] = $row['active'];
        $admins[$row['admin_id']]['suspen_id'] = $suspen_id;
        $admins[$row['admin_id']]['suspen_name'] = $suspen_name;
        $admins[$row['admin_id']]['suspen_starttime'] = $suspen_starttime;
        $admins[$row['admin_id']]['suspen_info'] = $suspen_info;
        $admins[$row['admin_id']]['suspen_adminlink'] = $suspen_adminlink;
        $admins[$row['admin_id']]['editor'] = $row['editor'];
        $admins[$row['admin_id']]['allow_files_type'] = !empty($allow_files_type) ? implode(', ', $allow_files_type) : '';
        $admins[$row['admin_id']]['allow_modify_files'] = $allow_modify_files;
        $admins[$row['admin_id']]['allow_create_subdirectories'] = $allow_create_subdirectories;
        $admins[$row['admin_id']]['allow_modify_subdirectories'] = $allow_modify_subdirectories;
        $admins[$row['admin_id']]['regtime'] = nv_date('l, d/m/Y H:i', $row['regdate']);
        $admins[$row['admin_id']]['last_login'] = $last_login;
        $admins[$row['admin_id']]['last_ip'] = $row['last_ip'];
        $admins[$row['admin_id']]['browser'] = $browser['name'];
        $admins[$row['admin_id']]['os'] = $os['name'];
        $admins[$row['admin_id']]['admin_theme'] = $row['admin_theme'];
        $admins[$row['admin_id']]['t_is_2step'] = $tool_is_2step;
        $admins[$row['admin_id']]['t_is_edit'] = $tool_is_edit;
        $admins[$row['admin_id']]['t_is_suspend'] = $tool_is_suspend;
        $admins[$row['admin_id']]['t_is_del'] = $tool_is_del;
    }
}

if (!empty($admins)) {
    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('ADMINS', $admins);
    $tpl->assign('ADMIN_INFO', $admin_info);
    $tpl->assign('IS_SPADMIN', defined('NV_IS_SPADMIN') ? true : false);
    $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $tpl->assign('MODULE_NAME', $module_name);

    if ($global_config['authors_detail_main'] or $numrows == 1) {
        $contents = $tpl->fetch('main.tpl');
    } else {
        $contents = $tpl->fetch('list.tpl');
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
