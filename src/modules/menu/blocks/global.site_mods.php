<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_menu_site_mods')) {
    /**
     * nv_menu_site_mods_config()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_menu_site_mods_config($module, $data_block)
    {
        global $site_mods, $nv_Lang;

        $html = '<div class="row mb-3">';
        $html .= '	<div class="col-sm-9 offset-sm-3"><div class="alert alert-info mb-0" role="alert">' . $nv_Lang->getModule('menu_note_auto') . '</div></div>';
        $html .= '</div>';
        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">';
        $html .= $nv_Lang->getModule('title_length');
        $html .= ':</label>';
        $html .= '<div class="col-sm-7">';
        $html .= '<input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="row mb-3"><label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('module_display') . ':</label><div class="col-sm-7"><ul id="sortable" class="list-group">';

        if (empty($data_block['module_in_menu']) or !is_array($data_block['module_in_menu'])) {
            $data_block['module_in_menu'] = [];
        }

        $array_no_show = ['comment', 'menu'];
        $modlist = !empty($data_block['module_in_menu']) ? ($data_block['module_in_menu'] + array_diff(array_keys($site_mods), $data_block['module_in_menu'])) : array_keys($site_mods);
        $modlist = array_diff($modlist, $array_no_show);
        foreach ($modlist as $modname) {
            if (isset($site_mods[$modname])) {
                $modvalues = $site_mods[$modname];
                $checked = in_array($modname, $data_block['module_in_menu'], true) ? ' checked="checked"' : '';
                $html .= '<li class="list-group-item"><div class="d-flex align-items-center justify-content-between"><div class="form-check"><input class="form-check-input" type="checkbox" ' . $checked . ' value="' . $modname . '" name="module_in_menu[]" id="module_in_menu_' . $modname . '"><label class="form-check-label" for="module_in_menu_' . $modname . '">' . $modvalues['custom_title'] . '</label></div><i class="fa-solid fa-sort"></i></li>';
            }
        }
        $html .= '</ul></div></div>';
        $html .= '<script>$( function() {$( "#sortable" ).sortable().disableSelection()});</script>';

        return $html;
    }

    /**
     * nv_menu_site_mods_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_menu_site_mods_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config']['title_length'] = $nv_Request->get_int('config_title_length', 'post', 24);
        $return['config']['module_in_menu'] = $nv_Request->get_typed_array('module_in_menu', 'post', 'string');

        return $return;
    }

    /**
     * nv_menu_site_mods()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_site_mods($block_config)
    {
        global $nv_Cache, $db, $db_config, $global_config, $site_mods, $module_name, $module_file, $module_data, $nv_Lang, $catid, $home, $op, $array_op;

        if (empty($block_config['module_in_menu'])) {
            return '';
        }

        $menulist = [];
        foreach ($block_config['module_in_menu'] as $modname) {
            if (isset($site_mods[$modname]) and !empty($site_mods[$modname]['funcs'])) {
                $modvalues = $site_mods[$modname];
                $array_menu = [
                    'title' => $modvalues['custom_title'],
                    'title_trim' => nv_clean60($modvalues['custom_title'], $block_config['title_length']),
                    'class' => $modname,
                    'current' => '',
                    'liclass' => '',
                    'aclass' => '',
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname,
                    'is_active' => ($modname == $module_name and empty($home)),
                    'sub' => []
                ];

                $sub_is_active = false;
                // Get submenu
                if (!empty($modvalues['funcs'])) {
                    if ($modvalues['module_file'] == 'news' or $modvalues['module_file'] == 'weblinks') {
                        $db->sqlreset()->select('title, alias')->from(NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_cat')->where('parentid=0 AND ' . ($modvalues['module_file'] == 'news' ? 'status=1' : 'inhome=1'))->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and !empty($array_op) and $l['alias'] == $array_op[0]) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'shops') {
                        $db->sqlreset()->select(NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias as alias')->from($db_config['prefix'] . '_' . $modvalues['module_data'] . '_catalogs')->where('parentid=0 AND inhome=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'message') {
                        if (defined('NV_IS_USER')) {
                            $is_active = ($modname == $module_name and 'config' == $op) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'note' => $nv_Lang->getGlobal('your_account'),
                                'title_trim' => nv_clean60($nv_Lang->getGlobal('your_account'), $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=config'
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'download' or $modvalues['module_file'] == 'faq' or $modvalues['module_file'] == 'saas') {
                        $db->sqlreset()->select('title, alias')->from(NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_categories')->where('parentid=0 AND status=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    } else {
                        foreach ($modvalues['funcs'] as $key => $sub_item) {
                            if ($sub_item['in_submenu'] == 1) {
                                $is_active = ($modname == $module_name and $key == $op) ? true : false;
                                $is_active && $sub_is_active = true;
                                $array_menu['sub'][] = [
                                    'note' => $sub_item['func_custom_name'],
                                    'title_trim' => nv_clean60($sub_item['func_custom_name'], $block_config['title_length']),
                                    'is_active' => $is_active,
                                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key
                                ];
                            }
                        }
                    }
                }

                if (!empty($array_menu['sub'])) {
                    $array_menu['current'] = 'dropdown';
                    $array_menu['liclass'] = 'dropdown';
                    $array_menu['aclass'] = 'dropdown-toggle';

                    if (!$array_menu['is_active'] and $sub_is_active) {
                        $array_menu['is_active'] = true;
                    }
                }

                if ($array_menu['is_active']) {
                    $array_menu['aclass'] .= (!empty($array_menu['aclass']) ? ' ' : '') . 'active';
                    $array_menu['current'] .= (!empty($array_menu['current']) ? ' ' : '') . 'active';
                }

                $menulist[] = $array_menu;
            }
        }

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/menu/global.bootstrap.tpl');
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

        if (!empty($menulist)) {
            foreach ($menulist as $menu) {
                !empty($menu['liclass']) && $menu['liclass'] = ' ' . $menu['liclass'];
                !empty($menu['aclass']) && $menu['aclass'] = ' ' . $menu['aclass'];
                !empty($menu['current']) && $menu['current'] = ' class="' . $menu['current'] . '"';
                $xtpl->assign('TOP_MENU', $menu);
                if (!empty($menu['icon'])) {
                    $xtpl->parse('main.top_menu.icon');
                }
                if (!empty($menu['sub'])) {
                    $submenu = nv_menu_site_mods_submenu($menu['sub'], $block_theme);
                    $xtpl->assign('SUB', $submenu);
                    $xtpl->parse('main.top_menu.sub');
                    $xtpl->parse('main.top_menu.has_sub');
                }
                $xtpl->parse('main.top_menu');
            }
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    /**
     * nv_menu_site_mods_submenu()
     *
     * @param array  $smenus
     * @param string $block_theme
     * @return string
     */
    function nv_menu_site_mods_submenu($smenus, $block_theme)
    {
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');

        foreach ($smenus as $smenu) {
            !empty($smenu['liclass']) && $smenu['liclass'] = ' class="' . $smenu['liclass'] . '"';
            !empty($smenu['aclass']) && $smenu['aclass'] = ' ' . $smenu['aclass'];
            $xtpl->assign('SUBMENU', $smenu);
            if (!empty($smenu['icon'])) {
                $xtpl->parse('submenu.loop.icon');
            }
            if (!empty($smenu['sub'])) {
                $submenu = nv_menu_site_mods_submenu($smenu['sub'], $block_theme);
                $xtpl->assign('SUB', $submenu);
                $xtpl->parse('submenu.loop.submenu');
                $xtpl->parse('submenu.loop.item');
                $xtpl->parse('submenu.loop.has_sub');
                $xtpl->parse('submenu.loop.sub');
            }
            $xtpl->parse('submenu.loop');
        }

        $xtpl->parse('submenu');

        return $xtpl->text('submenu');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_site_mods($block_config);
}
