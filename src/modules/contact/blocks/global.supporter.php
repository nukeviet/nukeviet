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
                $_others = unserialize($others, NV_UNSERIALIZE_SAFE);
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
            return unserialize($cache, NV_UNSERIALIZE_SAFE);
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
     * @return string
     * @throws PDOException
     */
    function nv_contact_supporter($module)
    {
        global $nv_Cache, $site_mods, $nv_Lang, $module_name;

        if (!isset($site_mods[$module])) {
            return '';
        }

        if ($module_name == $module) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.supporter.tpl', true, $module);
        if (empty($dir)) {
            return '';
        }

        $mod_table = NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'];
        $departments = $nv_Cache->db('SELECT * FROM ' . $mod_table . '_department ORDER BY weight', 'id', $module);
        $supporters = block_supporter_get_list($module, $departments);
        if (empty($supporters)) {
            return '';
        }

        $deps_data = [];
        foreach ($supporters as $depid => $sps) {
            $dep_name = $depid == 0
                ? $nv_Lang->getGlobal('general_support')
                : $departments[$depid]['full_name'];

            $supporters_list = [];
            foreach ($sps as $sp) {
                $contacts = [];

                // Điện thoại — $num[0] đã nv_htmlspecialchars() bởi nv_parse_phone()
                foreach ($sp['phone'] as $num) {
                    $contacts[] = [
                        'type'    => 'phone',
                        'display' => $num[0],
                        'link'    => count($num) === 2 ? 'tel:' . $num[1] : '',
                    ];
                }

                // Email
                if (!empty($sp['email'])) {
                    $contacts[] = [
                        'type'    => 'email',
                        'display' => nv_htmlspecialchars($sp['email']),
                        'link'    => 'mailto:' . $sp['email'],
                    ];
                }

                // Mạng xã hội và liên hệ khác
                $others_list = [];
                if (!empty($sp['others'])) {
                    foreach ($sp['others'] as $key => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $lkey = strtolower($key);
                        if (in_array($lkey, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            foreach (array_map('trim', explode(',', $value)) as $s) {
                                if (empty($s)) {
                                    continue;
                                }
                                $link = match ($lkey) {
                                    'skype'    => 'skype:' . $s . '?call',
                                    'viber'    => 'viber://pa?chatURI=' . $s,
                                    'whatsapp' => 'https://wa.me/' . $s,
                                    'zalo'     => 'https://zalo.me/' . $s,
                                };
                                $contacts[] = [
                                    'type'    => $lkey,
                                    'display' => nv_htmlspecialchars($s),
                                    'link'    => $link,
                                ];
                            }
                        } else {
                            $others_list[] = [
                                'name'  => nv_htmlspecialchars(ucfirst($key)),
                                'value' => nv_htmlspecialchars($value),
                                'link'  => nv_is_url($value) ? $value : '',
                            ];
                        }
                    }
                }

                $supporters_list[] = [
                    'full_name' => $sp['full_name'],
                    'image'     => $sp['image'],
                    'contacts'  => $contacts,
                    'others'    => $others_list,
                ];
            }

            $deps_data[] = [
                'id'         => $depid,
                'full_name'  => $dep_name,
                'supporters' => $supporters_list,
            ];
        }

        addition_module_assets($module, 'js');

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MODULE', $module);
        $tpl->assign('DEPARTMENTS', $deps_data);

        return $tpl->fetch('global.supporter.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_supporter($block_config['module']);
}
