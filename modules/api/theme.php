<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_API_MOD')) {
    exit('Stop!!!');
}

/**
 * main_theme()
 *
 * @return string
 */
function main_theme($type, $roleCount, $roleList, $api_user, $generate_page)
{
    global $lang_global, $lang_module, $module_info, $module_name, $site_mods;

    $page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PAGE_URL', $page_url);
    $xtpl->assign('TYPE_PUBLIC', [
        'active' => $type == 'public' ? 'active' : '',
        'url' => $page_url,
        'name' => $lang_module['api_role_type_public2']
    ]);
    $xtpl->assign('TYPE_PRIVATE', [
        'active' => $type == 'private' ? 'active' : '',
        'url' => $page_url . '&amp;type=private',
        'name' => $lang_module['api_role_type_private2']
    ]);
    $xtpl->assign('AUTH_INFO', empty($api_user) ? $lang_module['not_access_authentication'] : $lang_module['recreate_access_authentication_info']);

    if (empty($api_user)) {
        $xtpl->parse('main.not_access_authentication');
    } else {
        $xtpl->assign('API_USER', $api_user);
        $xtpl->parse('main.created_access_authentication');
    }

    $methods = [
        'password_verify' => $lang_module['auth_method_password_verify'],
        'md5_verify' => $lang_module['auth_method_md5_verify']
    ];
    
    foreach ($methods as $key => $name) {
        $xtpl->assign('METHOD', [
            'key' => $key,
            'sel' => (!empty($api_user['method']) and $key == $api_user['method']) ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.method');
    }

    if (empty($roleCount)) {
        $xtpl->parse('main.role_empty');
    } else {
        if ($type == 'public') {
            $xtpl->parse('main.rolelist.is_public');
        }

        foreach ($roleList as $role) {
            $role['status'] = !empty($role['status']) ? $lang_module['active'] : $lang_module['inactive'];
            $role['credential_status'] = (int) $role['credential_status'];
            $role['credential_status_format'] = $role['credential_status'] === 1 ? $lang_module['activated'] : ($role['credential_status'] === 0 ? $lang_module['suspended'] : $lang_module['not_activated']);
            $role['credential_addtime'] = $role['credential_addtime'] > 0 ? nv_date('d/m/Y H:i', $role['credential_addtime']) : '';
            $role['credential_access_count'] = $role['credential_access_count'] >= 0 ? $role['credential_access_count'] : '';
            $role['credential_last_access'] = $role['credential_last_access'] > 0 ? nv_date('d/m/Y H:i', $role['credential_last_access']) : '';
            $xtpl->assign('ROLE', $role);

            if ($role['credential_status'] !== 1) {
                $xtpl->parse('main.rolelist.role.credential_status_not_activated');
            }

            if (!empty($role['role_description'])) {
                $xtpl->parse('main.rolelist.role.description');
            }

            // List API hệ thống
            if (!empty($role['apis'][''])) {
                foreach ($role['apis'][''] as $cat_data) {
                    $xtpl->assign('CAT_DATA', $cat_data);

                    foreach ($cat_data['apis'] as $api_data) {
                        $xtpl->assign('API_DATA', $api_data);
                        $xtpl->parse('main.rolelist.role.catsys.loop');
                    }

                    $xtpl->parse('main.rolelist.role.catsys');
                }
            }

            // List API theo ngôn ngữ
            if (!empty($role['apis'][NV_LANG_DATA])) {
                foreach ($role['apis'][NV_LANG_DATA] as $mod_title => $mod_data) {
                    $xtpl->assign('MOD_TITLE', $site_mods[$mod_title]['custom_title']);

                    foreach ($mod_data as $cat_data) {
                        $xtpl->assign('CAT_DATA', $cat_data);

                        foreach ($cat_data['apis'] as $api_data) {
                            $xtpl->assign('API_DATA', $api_data);
                            $xtpl->parse('main.rolelist.role.apimod.mod.loop');
                        }

                        if (!empty($cat_data['title'])) {
                            $xtpl->parse('main.rolelist.role.apimod.mod.title');
                        }

                        $xtpl->parse('main.rolelist.role.apimod.mod');
                    }

                    $xtpl->parse('main.rolelist.role.apimod');
                }
            }

            if ($type == 'public') {
                if ($role['credential_status'] === 1) {
                    $xtpl->parse('main.rolelist.role.is_public.deactivate');
                } elseif ($role['credential_status'] === -1) {
                    $xtpl->parse('main.rolelist.role.is_public.activate');
                }
                $xtpl->parse('main.rolelist.role.is_public');
            }

            $xtpl->parse('main.rolelist.role');
        }

        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.rolelist.generate_page');
        }
        $xtpl->parse('main.rolelist');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
