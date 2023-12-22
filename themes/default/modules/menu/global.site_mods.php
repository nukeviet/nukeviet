<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_menu_site_mods')) {
    /**
     * nv_menu_site_mods()
     *
     * @param array $block_config
     * @return string
     */
    function nv_menu_site_mods($block_config)
    {
        global $nv_Cache, $db, $db_config, $site_mods, $module_name, $nv_Lang, $home, $op, $array_op;

        if (empty($block_config['module_in_menu'])) {
            return '';
        }

        $menulist = [];
        foreach ($block_config['module_in_menu'] as $modname) {
            if (isset($site_mods[$modname]) and !empty($site_mods[$modname]['funcs'])) {
                $modvalues = $site_mods[$modname];
                $array_menu = [
                    'parentid' => 0,
                    'note' => $modvalues['custom_title'],
                    'title' => $modvalues['custom_title'],
                    'title_trim' => nv_clean60($modvalues['custom_title'], $block_config['title_length']),
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
                                'parentid' => $modname,
                                'note' => $l['title'],
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => []
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'shops') {
                        $db->sqlreset()->select(NV_LANG_DATA . '_title as title, ' . NV_LANG_DATA . '_alias as alias')->from($db_config['prefix'] . '_' . $modvalues['module_data'] . '_catalogs')->where('parentid=0 AND inhome=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'parentid' => $modname,
                                'note' => $l['title'],
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => []
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'message') {
                        if (defined('NV_IS_USER')) {
                            $is_active = ($modname == $module_name and 'config' == $op) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'parentid' => $modname,
                                'note' => $nv_Lang->getGlobal('your_account'),
                                'title' => $nv_Lang->getGlobal('your_account'),
                                'title_trim' => nv_clean60($nv_Lang->getGlobal('your_account'), $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=config',
                                'sub' => []
                            ];
                        }
                    } elseif ($modvalues['module_file'] == 'download' or $modvalues['module_file'] == 'faq' or $modvalues['module_file'] == 'saas') {
                        $db->sqlreset()->select('title, alias')->from(NV_PREFIXLANG . '_' . $modvalues['module_data'] . '_categories')->where('parentid=0 AND status=1')->order('weight ASC')->limit(10);
                        $list = $nv_Cache->db($db->sql(), '', $modname);
                        foreach ($list as $l) {
                            $is_active = ($modname == $module_name and $l['alias'] == $array_op[0]) ? true : false;
                            $is_active && $sub_is_active = true;
                            $array_menu['sub'][] = [
                                'parentid' => $modname,
                                'note' => $l['title'],
                                'title' => $l['title'],
                                'title_trim' => nv_clean60($l['title'], $block_config['title_length']),
                                'is_active' => $is_active,
                                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'],
                                'sub' => []
                            ];
                        }
                    } else {
                        foreach ($modvalues['funcs'] as $key => $sub_item) {
                            if ($sub_item['in_submenu'] == 1) {
                                $is_active = ($modname == $module_name and $key == $op) ? true : false;
                                $is_active && $sub_is_active = true;
                                $array_menu['sub'][] = [
                                    'parentid' => $modname,
                                    'note' => $sub_item['func_custom_name'],
                                    'title' => $sub_item['func_custom_name'],
                                    'title_trim' => nv_clean60($sub_item['func_custom_name'], $block_config['title_length']),
                                    'is_active' => $is_active,
                                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $modname . '&amp;' . NV_OP_VARIABLE . '=' . $key,
                                    'sub' => []
                                ];
                            }
                        }
                    }
                }

                if (!empty($array_menu['sub']) and $sub_is_active) {
                    $array_menu['is_active'] = true;
                }

                $menulist[] = $array_menu;
            }
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
        $stpl->assign('MENU', $menulist);

        return $stpl->fetch('global.bootstrap.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_menu_site_mods($block_config);
}
