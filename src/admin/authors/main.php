<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['main'];

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
        $email = (defined('NV_IS_SPADMIN')) ? $row['email'] : (($row['admin_id'] == $admin_info['admin_id']) ? $row['email'] : ((int) ($row['view_mail']) ? $row['email'] : ''));
        $email = !empty($email) ? nv_EncodeEmail($email) : '';
        $level = (int) ($row['lev']);
        if ($level == 1) {
            $level_txt = '<strong>' . $lang_global['level1'] . '</strong>';
        } elseif ($level == 2) {
            $level_txt = '<strong>' . $lang_global['level2'] . '</strong>';
        } else {
            $array_mod = [];
            foreach ($list_modules as $row_mod) {
                if (!empty($row_mod['admins']) and in_array((int) $row['admin_id'], array_map('intval', explode(',', $row_mod['admins'])), true)) {
                    $array_mod[] = $row_mod['custom_title'];
                }
            }
            $level_txt = implode(', ', $array_mod);
        }
        $last_login = (int) ($row['last_login']);
        $last_login = $last_login ? nv_date('l, d/m/Y H:i', $last_login) : $lang_module['last_login0'];
        $last_agent = $row['last_agent'];

        $_browser = new NukeViet\Client\Browser($last_agent);
        $browser = ['key' => $_browser->getBrowserKey(), 'name' => $_browser->getBrowser()];
        $os = ['key' => $_browser->getPlatformKey(), 'name' => $_browser->getPlatform()];

        $is_suspend = (int) ($row['is_suspend']);
        if (!empty($is_suspend)) {
            $last_reason = unserialize($row['susp_reason']);
            $last_reason = array_shift($last_reason);
            list($susp_admin_id, $susp_admin_name) = $db->query('SELECT userid,first_name,last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . (int) ($last_reason['start_admin']))->fetch(3);
            $susp_admin_name = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $susp_admin_id . '">' . $susp_admin_name . '</a>';
            $is_suspend = sprintf($lang_module['is_suspend1'], nv_date('d/m/Y H:i', $last_reason['starttime']), $susp_admin_name, $last_reason['info']);
        } elseif (empty($row['active'])) {
            $is_suspend = $lang_module['is_suspend2'];
            $row['is_suspend'] = 1;
        } else {
            $is_suspend = $lang_module['is_suspend0'];
        }

        $thead = [];
        $thead['level'] = $level;
        if (defined('NV_IS_GODADMIN')) {
            // Quản trị tối cao thao tác
            $thead['2step'] = 1;
            $thead['edit'] = 1;
            $thead['chg_is_suspend'] = ($row['admin_id'] != $admin_info['admin_id']) ? 1 : 0;
            $thead['del'] = ($row['admin_id'] != $admin_info['admin_id']) ? 1 : 0;
        } elseif (defined('NV_IS_SPADMIN')) {
            // Điều hành chung hoặc quản trị tối cao
            if ($row['lev'] == 1) {
                // Đối với tài khoản quản trị tối cao
                $thead['2step'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
                $thead['edit'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
                $thead['chg_is_suspend'] = 0;
                $thead['del'] = 0;
            } elseif ($row['lev'] == 2) {
                // Đối với tài khoản điều hành chung
                if ($row['admin_id'] == $admin_info['admin_id'] or $admin_info['level'] == 1) {
                    $thead['edit'] = 1;
                    $thead['2step'] = 1;
                } else {
                    $thead['edit'] = 0;
                    $thead['2step'] = 0;
                }
                $thead['chg_is_suspend'] = 0;
                $thead['del'] = 0;
            } elseif ($global_config['spadmin_add_admin'] == 1) {
                // Đối với tài khoản quản lý module khi hệ thống cho phép điều hành chung quản lý quản trị module
                $thead['edit'] = 1;
                $thead['2step'] = 1;
                $thead['chg_is_suspend'] = 1;
                $thead['del'] = 1;
            } else {
                // Đối với tài khoản quản lý module khi hệ thống không cho phép điều hành chung quản lý quản trị module
                $thead['edit'] = 0;
                $thead['2step'] = 0;
                $thead['chg_is_suspend'] = 0;
                $thead['del'] = 0;
            }
        } else {
            // Quản trị module thao tác
            $thead['2step'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
            $thead['edit'] = ($row['admin_id'] == $admin_info['admin_id']) ? 1 : 0;
            $thead['chg_is_suspend'] = 0;
            $thead['del'] = 0;
        }

        if (!empty($thead['2step'])) {
            $thead['2step'] = [
                NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=2step&amp;admin_id=' . $row['admin_id'],
                $lang_module['2step_manager']
            ];
        }
        if (!empty($thead['edit'])) {
            $thead['edit'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $row['admin_id'], $lang_global['edit']];
        }
        if (!empty($thead['chg_is_suspend'])) {
            $thead['chg_is_suspend'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=suspend&amp;admin_id=' . $row['admin_id'], $lang_module['chg_is_suspend2']];
        }
        if (!empty($thead['del'])) {
            $thead['del'] = [NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;admin_id=' . $row['admin_id'], $lang_global['delete']];
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
        $admins[$row['admin_id']]['caption'] = ($row['admin_id'] == $admin_info['admin_id']) ? sprintf($lang_module['admin_info_title2'], $row['full_name']) : sprintf($lang_module['admin_info_title1'], $row['full_name']);
        $admins[$row['admin_id']]['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;id=' . $row['admin_id'];
        $admins[$row['admin_id']]['thead'] = $thead;
        $admins[$row['admin_id']]['options'] = [];
        $admins[$row['admin_id']]['options']['login'] = [$lang_module['login'], $login];
        $admins[$row['admin_id']]['options']['email'] = [$lang_module['email'], $email];
        $admins[$row['admin_id']]['options']['full_name'] = [$lang_module['name'], $row['full_name']];
        $admins[$row['admin_id']]['options']['lev'] = [$lang_module['lev'], $level_txt];
        $admins[$row['admin_id']]['options']['lev'] = [$lang_module['lev'], $level_txt];
        $admins[$row['admin_id']]['options']['position'] = [$lang_module['position'], $row['position']];
        $admins[$row['admin_id']]['options']['admin_theme'] = [$lang_module['themeadmin'], (empty($row['admin_theme'])) ? $lang_module['theme_default'] : $row['admin_theme']];
        $admins[$row['admin_id']]['options']['is_suspend'] = [$lang_module['is_suspend'], $is_suspend, $row['is_suspend']];

        if (defined('NV_IS_SPADMIN')) {
            $admins[$row['admin_id']]['options']['editor'] = [$lang_module['editor'], !empty($row['editor']) ? $row['editor'] : $lang_module['not_use']];
            $admins[$row['admin_id']]['options']['allow_files_type'] = [$lang_module['allow_files_type'], !empty($allow_files_type) ? implode(', ', $allow_files_type) : $lang_global['no']];
            $admins[$row['admin_id']]['options']['allow_modify_files'] = [$lang_module['allow_modify_files'], !empty($allow_modify_files) ? $lang_global['yes'] : $lang_global['no']];
            $admins[$row['admin_id']]['options']['allow_create_subdirectories'] = [$lang_module['allow_create_subdirectories'], !empty($allow_create_subdirectories) ? $lang_global['yes'] : $lang_global['no']];
            $admins[$row['admin_id']]['options']['allow_modify_subdirectories'] = [$lang_module['allow_modify_subdirectories'], !empty($allow_modify_subdirectories) ? $lang_global['yes'] : $lang_global['no']];

            $admins[$row['admin_id']]['options']['regtime'] = [$lang_module['regtime'], nv_date('l, d/m/Y H:i', $row['regdate'])];
            $admins[$row['admin_id']]['options']['last_login'] = [$lang_module['last_login'], $last_login];
            $admins[$row['admin_id']]['options']['last_ip'] = [$lang_module['last_ip'], $row['last_ip']];
            $admins[$row['admin_id']]['options']['browser'] = [$lang_module['browser'], $browser['name']];
            $admins[$row['admin_id']]['options']['os'] = [$lang_module['os'], $os['name']];
        }
    }
}

if (!empty($admins)) {
    if ($global_config['authors_detail_main'] or $numrows == 1) {
        $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        foreach ($admins as $id => $values) {
            $xtpl->assign('ID', $id);
            $xtpl->assign('CAPTION', $values['caption']);

            if (!empty($values['thead']['edit'])) {
                $xtpl->assign('EDIT_HREF', $values['thead']['edit'][0]);
                $xtpl->assign('EDIT_NAME', $values['thead']['edit'][1]);
                $xtpl->parse('main.loop.edit');
            }

            if (!empty($values['thead']['2step'])) {
                $xtpl->assign('2STEP_HREF', $values['thead']['2step'][0]);
                $xtpl->assign('2STEP_NAME', $values['thead']['2step'][1]);
                $xtpl->parse('main.loop.2step');
            }

            if (!empty($values['thead']['chg_is_suspend'])) {
                $xtpl->assign('SUSPEND_HREF', $values['thead']['chg_is_suspend'][0]);
                $xtpl->assign('SUSPEND_NAME', $values['thead']['chg_is_suspend'][1]);
                $xtpl->parse('main.loop.suspend');
            }

            if (!empty($values['thead']['del'])) {
                $xtpl->assign('DEL_HREF', $values['thead']['del'][0]);
                $xtpl->assign('DEL_NAME', $values['thead']['del'][1]);
                $xtpl->parse('main.loop.del');
            }

            $xtpl->assign('OPTION_LEV', $values['options']['lev'][1]);
            $xtpl->assign('THREAD_LEV', $values['thead']['level']);
            $xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);

            foreach ($values['options'] as $key => $value) {
                if (!empty($value[1])) {
                    $xtpl->assign('VALUE0', $value[0]);
                    $xtpl->assign('VALUE1', $value[1]);
                    $xtpl->parse('main.loop.option_loop');
                }
            }
            $xtpl->parse('main.loop');
        }
    } else {
        $xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);

        foreach ($admins as $id => $values) {
            if ($global_config['idsite'] > 0 and $values['thead']['level'] == 1) {
                continue;
            }

            $is_tools = 0;

            // Nút sửa luôn có, nếu không có nút sửa cũng không có nút khác
            if (!empty($values['thead']['edit'])) {
                $xtpl->assign('EDIT_HREF', $values['thead']['edit'][0]);
                $xtpl->assign('EDIT_NAME', $values['thead']['edit'][1]);
                $xtpl->parse('main.loop.tools.edit');
                ++$is_tools;
            }

            if (!empty($values['thead']['del'])) {
                $xtpl->assign('DEL_HREF', $values['thead']['del'][0]);
                $xtpl->assign('DEL_NAME', $values['thead']['del'][1]);
                $xtpl->parse('main.loop.tools.dropdown.del');
                ++$is_tools;
            }

            $xtpl->assign('OPTION_LEV', $values['options']['lev'][1]);
            $xtpl->assign('THREAD_LEV', $values['thead']['level']);
            $xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);

            $data_row = [];
            $data_row['link'] = $values['link'];
            $data_row['login'] = $values['options']['login'][1];
            $data_row['full_name'] = $values['options']['full_name'][1];
            $data_row['email'] = $values['options']['email'][1];
            $data_row['lev'] = $values['options']['lev'][1];
            $data_row['position'] = $values['options']['position'][1];
            $data_row['is_suspend'] = ($values['options']['is_suspend'][2]) ? $lang_module['is_suspend2'] : $lang_module['is_suspend0'];

            $xtpl->assign('DATA', $data_row);

            if (!empty($values['thead']['chg_is_suspend'])) {
                $xtpl->assign('SUSPEND_HREF', $values['thead']['chg_is_suspend'][0]);
                $xtpl->assign('SUSPEND_NAME', ($values['options']['is_suspend'][2]) ? $lang_module['suspend0'] : $lang_module['suspend1']);
                $xtpl->parse('main.loop.tools.dropdown.suspend');
                ++$is_tools;
            }
            if (!empty($values['thead']['2step'])) {
                $xtpl->assign('2STEP_HREF', $values['thead']['2step'][0]);
                $xtpl->assign('2STEP_NAME', $values['thead']['2step'][1]);
                $xtpl->parse('main.loop.tools.dropdown.2step');
                ++$is_tools;
            }

            // Có công cụ
            if ($is_tools > 0) {
                if ($is_tools > 1) {
                    $xtpl->parse('main.loop.tools.dropdown');
                }
                $xtpl->parse('main.loop.tools');
            }

            $xtpl->parse('main.loop');
        }
    }

    $xtpl->parse('main');

    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
