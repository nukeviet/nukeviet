<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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

        $html = '<div class="form-group">';
        $html .= '	<div class="col-sm-18 col-sm-offset-6"><div class="alert alert-info panel-block-content-last">' . $nv_Lang->getModule('menu_note_auto') . '</div></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">';
        $html .= $nv_Lang->getModule('title_length');
        $html .= ':</label>';
        $html .= '<div class="col-sm-9">';
        $html .= '<input type="text" class="form-control" name="config_title_length" value="' . $data_block['title_length'] . '"/>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="form-group"><label class="control-label col-sm-6">' . $nv_Lang->getModule('module_display') . ':</label><div class="col-sm-9"><ul id="sortable" class="list-group" style="margin-bottom:0">';

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
                $html .= '<li class="list-group-item"><i class="fa fa-sort pull-right"></i><label style="text-align: left;margin-bottom:0;"><input type="checkbox" ' . $checked . ' value="' . $modname . '" name="module_in_menu[]">' . $modvalues['custom_title'] . '</label></li>';
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

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/menu/global.bootstrap.tpl');
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('BLOCK_THEME', $block_theme);
        $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);

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
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname
                ];

                $classcurrent = []; // For bootstrap 3
                $liclass = []; // For bootstrap 4/5
                $aclass = []; // For bootstrap 4/5

                // Set current menu
                if ($modname == $module_name and empty($home)) {
                    $classcurrent[] = 'active';
                    $aclass[] = 'active';
                }

                // Get submenu
                if (!empty($modvalues['funcs'])) {
                    $sub_nav_item = [];

                    if ($modvalues['module_file'] == 'news' or $modvalues['module_file'] == 'weblinks') {
                        $db->sqlreset()->select('title, alias')->from(NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_cat')->where('parentid=0 AND ' . ($modvalues['module_file'] == 'news' ? 'status=1' : 'inhome=1'))->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and !empty($array_op) and $l['alias'] == $array_op[0]) ? true : false;
                            if ($is_active) {
                                $classcurrent[] = 'active';
                                $aclass[] = 'active';
                            }

                            $sub_nav_item[] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    }
                    if ($modvalues['module_file'] == 'shops') {
                        $db->sqlreset()->select(NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias as alias')->from($db_config['prefix'] . '_' . $modvalues['module_data'] . '_catalogs')->where('parentid=0 AND inhome=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            if ($is_active) {
                                $classcurrent[] = 'active';
                                $aclass[] = 'active';
                            }

                            $sub_nav_item[] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'message') {
                        if (defined('NV_IS_USER')) {
                            $is_active = ($modname == $module_name and 'config' == $op) ? true : false;
                            if ($is_active) {
                                $classcurrent[] = 'active';
                                $aclass[] = 'active';
                            }

                            $sub_nav_item[] = [
                                'note' => $nv_Lang->getGlobal('your_account'),
                                'title_trim' => nv_clean60($nv_Lang->getGlobal('your_account'), $block_config['title_length']),
                                'active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=config'
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'download' or $modvalues['module_file'] == 'faq' or $modvalues['module_file'] == 'saas') {
                        $db->sqlreset()->select('title, alias')->from(NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_categories')->where('parentid=0 AND status=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            if ($is_active) {
                                $classcurrent[] = 'active';
                                $aclass[] = 'active';
                            }

                            $sub_nav_item[] = [
                                'note' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias']
                            ];
                        }
                    } else {
                        foreach ($modvalues['funcs'] as $key => $sub_item) {
                            if ($sub_item['in_submenu'] == 1) {
                                $is_active = ($modname == $module_name and $key == $op) ? true : false;
                                if ($is_active) {
                                    $classcurrent[] = 'active';
                                    $aclass[] = 'active';
                                }

                                $sub_nav_item[] = [
                                    'note' => $sub_item['func_custom_name'],
                                    'title_trim' => nv_clean60($sub_item['func_custom_name'], $block_config['title_length']),
                                    'active' => $is_active,
                                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key
                                ];
                            }
                        }
                    }

                    // Prase sub menu
                    if (!empty($sub_nav_item)) {
                        $classcurrent[] = 'dropdown';
                        $liclass[] = 'dropdown';
                        $aclass[] = 'dropdown-toggle';

                        $submenu = nv_menu_site_mods_submenu($sub_nav_item, $block_theme);
                        $xtpl->assign('SUB', $submenu);
                        $xtpl->parse('main.top_menu.sub');
                        $xtpl->parse('main.top_menu.has_sub');
                    }
                }

                $array_menu['current'] = empty($classcurrent) ? '' : ' class="' . (implode(' ', array_unique($classcurrent))) . '"';
                $array_menu['liclass'] = empty($liclass) ? '' : ' ' . implode(' ', array_unique($liclass));
                $array_menu['aclass'] = empty($aclass) ? '' : ' ' . implode(' ', array_unique($aclass));

                $xtpl->assign('TOP_MENU', $array_menu);
                $xtpl->parse('main.top_menu');
            }
        }

        // Assign init clock text
        $xtpl->assign('THEME_DIGCLOCK_TEXT', nv_date('H:i T l, d/m/Y', NV_CURRENTTIME));

        // Active home menu
        if (!empty($home)) {
            $xtpl->parse('main.home_active');
        }

        $xtpl->parse('main');

        return $xtpl->text('main');
    }

    /**
     * nv_menu_site_mods_submenu()
     *
     * @param array  $sub_nav_item
     * @param string $block_theme
     * @return string
     */
    function nv_menu_site_mods_submenu($sub_nav_item, $block_theme)
    {
        $xtpl = new XTemplate('global.bootstrap.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/menu');

        foreach ($sub_nav_item as $sub_nav) {
            $sub_nav['liclass'] = '';
            $sub_nav['aclass'] = $sub_nav['active'] ? ' active' : '';

            $xtpl->assign('SUBMENU', $sub_nav);
            $xtpl->parse('submenu.loop');
        }
        $xtpl->parse('submenu');

        return $xtpl->text('submenu');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_site_mods($block_config);
}
