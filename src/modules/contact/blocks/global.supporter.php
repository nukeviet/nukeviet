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

if (!function_exists('block_supporter_parse_others')) {
    /**
     * block_supporter_parse_others()
     *
     * @param mixed $others
     * @return mixed
     */
    function block_supporter_parse_others($others)
    {
        if (!empty($others)) {
            $_others = json_decode($others, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $_others = unserialize($others);
            }

            return $_others;
        }

        return [];
    }
}

if (!function_exists('block_supporter_get_list')) {
    /**
     * block_supporter_get_list()
     *
     * @return mixed
     * @param mixed $module
     * @param mixed $departments
     */
    function block_supporter_get_list($module, $departments)
    {
        global $db, $nv_Cache, $site_mods;

        $mod_table = NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'];
        $cache_file = 'supporterlist' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
            return unserialize($cache);
        }

        $supporter_list = [];
        $result = $db->query('SELECT * FROM ' . $mod_table . '_supporter WHERE act = 1 ORDER BY departmentid, weight');
        while ($row = $result->fetch()) {
            !isset($supporter_list[$row['departmentid']]) && $supporter_list[$row['departmentid']] = [];
            $supporter_list[$row['departmentid']][$row['id']] = [
                'full_name' => $row['full_name'],
                'image' => !empty($row['image']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'] : NV_BASE_SITEURL . NV_ASSETS_DIR . '/images/supporter.svg',
                'phone' => !empty($row['phone']) ? nv_parse_phone($row['phone']) : [],
                'email' => $row['email'],
                'others' => block_supporter_parse_others($row['others'])
            ];
        }

        $supporters = [];
        if (isset($supporter_list[0])) {
            $supporters[0] = $supporter_list[0];
        }
        if (!empty($departments)) {
            $keys = array_keys($departments);
            foreach ($keys as $key) {
                if ($departments[$key]['act'] and isset($supporter_list[$key])) {
                    $supporters[$key] = $supporter_list[$key];
                }
            }
        }

        $cache = serialize($supporters);
        $nv_Cache->setItem($module, $cache_file, $cache);

        return $supporters;
    }
}

if (!nv_function_exists('nv_contact_supporter')) {
    /**
     * nv_contact_supporter()
     *
     * @param string $module
     * @return string|void
     * @throws PDOException
     */
    function nv_contact_supporter($module)
    {
        global $nv_Cache, $site_mods, $global_config, $nv_Lang, $module_name;

        if (!isset($site_mods[$module])) {
            return '';
        }

        if ($module_name == $module) {
            return '';
        }

        $mod_table = NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'];
        $departments = $nv_Cache->db('SELECT * FROM ' . $mod_table . '_department ORDER BY weight', 'id', $module);
        $supporters = block_supporter_get_list($module, $departments);
        if (empty($supporters)) {
            return '';
        }

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $site_mods[$module]['module_file'] . '/block.supporter.tpl');
        $xtpl = new XTemplate('block.supporter.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
        $xtpl->assign('TEMPLATE', $block_theme);
        $xtpl->assign('MODULE', $module);
        $active = false;
        foreach ($supporters as $depid => $sps) {
            if ($depid == 0) {
                $dep = [
                    'id' => 0,
                    'sel' => !$active ? ' selected="selected"' : '',
                    'full_name' => $nv_Lang->getGlobal('general_support')
                ];
            } else {
                $dep = $departments[$depid];
                $dep['sel'] = !$active ? ' selected="selected"' : '';
            }
            $xtpl->assign('DEP', $dep);
            $xtpl->parse('main.deps_tab.option');

            if (!$active) {
                $xtpl->parse('main.deps_content.active');
            }

            $sp_count = count($sps) - 1;
            $i = 0;
            foreach ($sps as $supporter) {
                $xtpl->assign('SUPPORTER', $supporter);

                $items = [];
                foreach ($supporter['phone'] as $num) {
                    if (count($num) == 2) {
                        $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                    } else {
                        $items[] = $num[0];
                    }
                }
                $xtpl->assign('CD', [
                    'icon' => 'fa-phone',
                    'name' => $nv_Lang->getGlobal('phonenumber'),
                    'value' => implode(', ', $items)
                ]);
                $xtpl->parse('main.deps_content.supporter.cd');

                if (!empty($supporter['email'])) {
                    $xtpl->assign('CD', [
                        'icon' => 'fa-envelope',
                        'name' => $nv_Lang->getGlobal('email'),
                        'value' => '<a href="' . $supporter['email'] . '">' . $supporter['email'] . '</a>'
                    ]);
                    $xtpl->parse('main.deps_content.supporter.cd');
                }

                if (!empty($supporter['others'])) {
                    foreach ($supporter['others'] as $key => $value) {
                        if (!empty($value)) {
                            if (strtolower($key) == 'skype') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);

                                    return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                                }, explode(',', $value));
                                $xtpl->assign('CD', [
                                    'icon' => 'fa-skype',
                                    'name' => 'Skype',
                                    'value' => implode(', ', $items)
                                ]);
                            } elseif (strtolower($key) == 'viber') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);

                                    return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $xtpl->assign('CD', [
                                    'icon' => 'icon-viber',
                                    'name' => 'Viber',
                                    'value' => implode(', ', $items)
                                ]);
                            } elseif (strtolower($key) == 'whatsapp') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);

                                    return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $xtpl->assign('CD', [
                                    'icon' => 'fa-whatsapp',
                                    'name' => 'WhatsApp',
                                    'value' => implode(', ', $items)
                                ]);
                            } elseif (strtolower($key) == 'zalo') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);

                                    return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $xtpl->assign('CD', [
                                    'icon' => 'icon-zalo',
                                    'name' => 'Zalo',
                                    'value' => implode(', ', $items)
                                ]);
                            } else {
                                $xtpl->assign('CD', [
                                    'icon' => '',
                                    'name' => ucfirst($key),
                                    'value' => nv_is_url($value) ? '<a href="' . $value . '">' . $value . '</a>' : $value
                                ]);
                            }
                            $xtpl->parse('main.deps_content.supporter.cd');
                        }
                    }
                }

                if ($i < $sp_count) {
                    $xtpl->parse('main.deps_content.supporter.hr');
                }

                $xtpl->parse('main.deps_content.supporter');
                ++$i;
            }

            $xtpl->parse('main.deps_content');
            $active = true;
        }

        $dep_count = count($supporters);
        if ($dep_count > 1) {
            $xtpl->parse('main.deps_tab');
        }
        $xtpl->parse('main');

        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_supporter($block_config['module']);
}
