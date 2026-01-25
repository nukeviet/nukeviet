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

$page_title = $nv_Lang->getModule('main');

$admins = [];
if ($nv_Request->isset_request('id', 'get')) {
    // Trường hợp xem chi tiết một quản trị
    $admin_id = $nv_Request->get_int('id', 'get', 0);
    $sql = 'SELECT t1.admin_id as admin_id, t1.admin_theme admin_theme, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
    t2.username as username, t2.email as email, t2.first_name as first_name, t2.last_name as last_name, t2.view_mail as view_mail, t2.regdate as regdate, t2.active as active
    FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE admin_id=' . $admin_id;
    $adminrows = $db->query($sql)->fetchAll();
    $numrows = count($adminrows);

    if ($numrows != 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
} else {
    // Trường hợp xem danh sách quản trị
    $sql = 'SELECT t1.admin_id as admin_id, t1.admin_theme admin_theme, t1.check_num as check_num, t1.last_agent as last_agent, t1.last_ip as last_ip, t1.last_login as last_login, t1.files_level as files_level, t1.lev as lev,t1.position as position, t1.editor as editor, t1.is_suspend as is_suspend, t1.susp_reason as susp_reason,
    t2.username as username, t2.email as email, t2.first_name as first_name, t2.last_name as last_name, t2.view_mail as view_mail, t2.regdate as regdate, t2.active as active
    FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid ORDER BY t1.lev ASC';

    $adminrows = $db->query($sql)->fetchAll();
    $numrows = count($adminrows);
}

if ($numrows) {
    $sql = 'SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
    $list_modules = $nv_Cache->db($sql, '', 'modules');

    foreach ($adminrows as $row) {
        $email = (defined('NV_IS_SPADMIN')) ? $row['email'] : (($row['admin_id'] == $admin_info['admin_id']) ? $row['email'] : ((int) ($row['view_mail']) ? $row['email'] : ''));
        $email = !empty($email) ? nv_EncodeEmail($email) : '';

        if ($row['lev'] == 1) {
            $level_txt = $nv_Lang->getGlobal('level1');
        } elseif ($row['lev'] == 2) {
            $level_txt = $nv_Lang->getGlobal('level2');
        } else {
            $array_mod = [];
            foreach ($list_modules as $row_mod) {
                if (!empty($row_mod['admins']) and in_array((int) $row['admin_id'], array_map('intval', explode(',', $row_mod['admins'])), true)) {
                    $array_mod[] = $row_mod['custom_title'];
                }
            }
            $level_txt = implode(', ', $array_mod);
        }

        $_browser = new NukeViet\Client\Browser();
        $_browser->setUserAgent($row['last_agent']);
        $br = ['key' => $_browser->getBrowserKey(), 'name' => $_browser->getBrowser()];
        $os = ['key' => $_browser->getPlatformKey(), 'name' => $_browser->getPlatform()];

        $is_suspend = (int) ($row['is_suspend']);
        if (!empty($is_suspend)) {
            $last_reason = unserialize($row['susp_reason']);
            $last_reason = array_shift($last_reason);
            [$susp_admin_id, $susp_admin_name] = $db->query('SELECT userid,first_name,last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . (int) ($last_reason['start_admin']))->fetch(3);
            $susp_admin_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $susp_admin_id . '">' . $susp_admin_name . '</a>';
            $is_suspend = $nv_Lang->getModule('is_suspend1', nv_datetime_format($last_reason['starttime']), $susp_admin_name, $last_reason['info']);
        } elseif (empty($row['active'])) {
            $is_suspend = $nv_Lang->getModule('is_suspend2');
            $row['is_suspend'] = 1;
        } else {
            $is_suspend = $nv_Lang->getModule('is_suspend0');
        }

        $funcs = [];
        if (defined('NV_IS_GODADMIN')) {
            // Quản trị tối cao thao tác
            $funcs['2step'] = 1;
            $funcs['edit'] = 1;
            $funcs['chg_is_suspend'] = ($row['admin_id'] != $admin_info['admin_id']) ? 1 : 0;
            $funcs['del'] = ($row['admin_id'] != $admin_info['admin_id']) ? 1 : 0;
        } elseif (defined('NV_IS_SPADMIN')) {
            // Điều hành chung hoặc quản trị tối cao
            if ($row['lev'] == 1) {
                // Đối với tài khoản quản trị tối cao
                $funcs['2step'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
                $funcs['edit'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
                $funcs['chg_is_suspend'] = 0;
                $funcs['del'] = 0;
            } elseif ($row['lev'] == 2) {
                // Đối với tài khoản điều hành chung
                if ($row['admin_id'] == $admin_info['admin_id'] or $admin_info['level'] == 1) {
                    $funcs['edit'] = 1;
                    $funcs['2step'] = 1;
                } else {
                    $funcs['edit'] = 0;
                    $funcs['2step'] = 0;
                }
                $funcs['chg_is_suspend'] = 0;
                $funcs['del'] = 0;
            } elseif ($global_config['spadmin_add_admin'] == 1) {
                // Đối với tài khoản quản lý module khi hệ thống cho phép điều hành chung quản lý quản trị module
                $funcs['edit'] = 1;
                $funcs['2step'] = 1;
                $funcs['chg_is_suspend'] = 1;
                $funcs['del'] = 1;
            } else {
                // Đối với tài khoản quản lý module khi hệ thống không cho phép điều hành chung quản lý quản trị module
                $funcs['edit'] = 0;
                $funcs['2step'] = 0;
                $funcs['chg_is_suspend'] = 0;
                $funcs['del'] = 0;
            }
        } else {
            // Quản trị module thao tác
            $funcs['2step'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
            $funcs['edit'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
            $funcs['chg_is_suspend'] = 0;
            $funcs['del'] = 0;
        }
        $funcs['num'] = count(array_filter($funcs));

        if (empty($row['files_level'])) {
            $allow_files_type = [];
            $allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
        } else {
            [$allow_files_type, $allow_modify_files, $allow_create_subdirectories, $allow_modify_subdirectories] = explode('|', $row['files_level']);
            $allow_files_type = !empty($allow_files_type) ? explode(',', $allow_files_type) : [];
            $allow_files_type = array_values(array_intersect($global_config['file_allowed_ext'], $allow_files_type));
        }

        $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        $row['level_txt'] = $level_txt;
        $row['show_mail'] = $email;
        $row['suspend_text'] = $is_suspend;
        $row['allow_files_type'] = $allow_files_type;
        $row['allow_modify_files'] = $allow_modify_files;
        $row['allow_create_subdirectories'] = $allow_create_subdirectories;
        $row['allow_modify_subdirectories'] = $allow_modify_subdirectories;
        $row['browser'] = $br;
        $row['os'] = $os;
        $row['funcs'] = $funcs;

        $admins[$row['admin_id']] = $row;
    }
}

$template_file = ($global_config['authors_detail_main'] or $numrows == 1) ? 'main.tpl' : 'list.tpl';
[$template, $dir] = get_module_tpl_dir($template_file, true);
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir($dir);
$tpl->registerPlugin('modifier', 'datetime_format', 'nv_datetime_format');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TEMPLATE', $template);
$tpl->assign('ADMIN_INFO', $admin_info);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('GCONFIG', $global_config);

$tpl->assign('ADMINS', $admins);

$contents = $tpl->fetch($template_file);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
