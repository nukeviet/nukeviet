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

        [$block_theme, $dir] = get_block_tpl_dir('global.site_mods.config.tpl', true, 'menu');
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        if (empty($data_block['module_in_menu']) or !is_array($data_block['module_in_menu'])) {
            $data_block['module_in_menu'] = [];
        }

        $array_no_show = ['comment', 'menu'];
        $modlist = !empty($data_block['module_in_menu'])
            ? ($data_block['module_in_menu'] + array_diff(array_keys($site_mods), $data_block['module_in_menu']))
            : array_keys($site_mods);
        $modlist = array_diff($modlist, $array_no_show);

        $mods = [];
        foreach ($modlist as $modname) {
            if (isset($site_mods[$modname])) {
                $mods[] = [
                    'name' => $modname,
                    'title' => $site_mods[$modname]['custom_title'],
                    'checked' => in_array($modname, $data_block['module_in_menu'], true),
                ];
            }
        }
        $tpl->assign('MODS', $mods);

        return $tpl->fetch('global.site_mods.config.tpl');
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
        global $nv_Cache, $db_config, $site_mods, $module_name, $nv_Lang, $home, $op, $array_op;

        if (empty($block_config['module_in_menu'])) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.site_mods.tpl', true, 'menu');
        if (empty($dir)) {
            return '';
        }

        $menulist = [];
        foreach ($block_config['module_in_menu'] as $modname) {
            if (isset($site_mods[$modname]) and !empty($site_mods[$modname]['funcs'])) {
                $modvalues = $site_mods[$modname];
                $array_menu = [
                    'title' => $modvalues['custom_title'],
                    'title_trim' => nv_clean60($modvalues['custom_title'], $block_config['title_length']),
                    'note' => $modvalues['custom_title'],
                    'css' => $modname,
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname,
                    'is_active' => ($modname == $module_name and empty($home)),
                    'sub' => [],
                ];

                $sub_is_active = false;
                if (!empty($modvalues['funcs'])) {
                    if ($modvalues['module_file'] == 'news' or $modvalues['module_file'] == 'weblinks') {
                        $sql = 'SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_cat WHERE parentid=0 AND ' . ($modvalues['module_file'] == 'news' ? 'status=1' : 'inhome=1') . ' ORDER BY weight ASC LIMIT 10';
                        $list = $nv_Cache->db($sql, '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and !empty($array_op) and $l['alias'] == $array_op[0]);
                            if ($is_active) {
                                $sub_is_active = true;
                            }
                            $array_menu['sub'][] = [
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'note' => $l['title'],
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => [],
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'shops') {
                        $sql = 'SELECT ' . NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias as alias FROM ' . $db_config['prefix'] . '_' . $modvalues['module_data'] . '_catalogs WHERE parentid=0 AND inhome=1 ORDER BY weight ASC LIMIT 10';
                        $list = $nv_Cache->db($sql, '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]);
                            if ($is_active) {
                                $sub_is_active = true;
                            }
                            $array_menu['sub'][] = [
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'note' => $l['title'],
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => [],
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'message') {
                        if (defined('NV_IS_USER')) {
                            $is_active = ($modname == $module_name and 'config' == $op);
                            if ($is_active) {
                                $sub_is_active = true;
                            }
                            $array_menu['sub'][] = [
                                'title' => $nv_Lang->getGlobal('your_account'),
                                'title_trim' => nv_clean60($nv_Lang->getGlobal('your_account'), $block_config['title_length']),
                                'note' => $nv_Lang->getGlobal('your_account'),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=config',
                                'sub' => [],
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'download' or $modvalues['module_file'] == 'faq' or $modvalues['module_file'] == 'saas') {
                        $sql = 'SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_categories WHERE parentid=0 AND status=1 ORDER BY weight ASC LIMIT 10';
                        $list = $nv_Cache->db($sql, '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]);
                            if ($is_active) {
                                $sub_is_active = true;
                            }
                            $array_menu['sub'][] = [
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'note' => $l['title'],
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => [],
                            ];
                        }
                    } else {
                        foreach ($modvalues['funcs'] as $key => $sub_item) {
                            if ($sub_item['in_submenu'] == 1) {
                                $is_active = ($modname == $module_name and $key == $op);
                                if ($is_active) {
                                    $sub_is_active = true;
                                }
                                $array_menu['sub'][] = [
                                    'title' => $sub_item['func_custom_name'],
                                    'title_trim' => nv_clean60($sub_item['func_custom_name'], $block_config['title_length']),
                                    'note' => $sub_item['func_custom_name'],
                                    'is_active' => $is_active,
                                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key,
                                    'sub' => [],
                                ];
                            }
                        }
                    }
                }

                if (!$array_menu['is_active'] and $sub_is_active) {
                    $array_menu['is_active'] = true;
                }

                $menulist[] = $array_menu;
            }
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MENULIST', $menulist);

        return $tpl->fetch('global.site_mods.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_site_mods($block_config);
}
