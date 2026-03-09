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
     * @param array $block_config
     * @return string|void
     */
    function nv_contact_supporter($block_config)
    {
        global $nv_Cache, $site_mods, $global_config, $nv_Lang, $module_name;

        $module = $block_config['module'];

        if (!isset($site_mods[$module])) {
            return '';
        }

        if ($module_name == $module) {
            return '';
        }

        $mod_table = NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'];
        $departments = $nv_Cache->db('SELECT * FROM ' . $mod_table . '_department ORDER BY weight', 'id', $module);
        $_supporters = block_supporter_get_list($module, $departments);
        if (empty($_supporters)) {
            return '';
        }

        $supporters = [];
        foreach ($_supporters as $depid => $sps) {
            $supporters[$depid] = [
                'depid' => $depid,
                'full_name' => $depid == 0 ? $nv_Lang->getGlobal('general_support') : $departments[$depid]['full_name'],
                'url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact' . ($depid != 0 ? '&amp;' . NV_OP_VARIABLE . '=' . $departments[$depid]['alias'] : ''),
                'items' => []
            ];

            foreach ($sps as $supporter) {
                $supporter['cd'] = [];
                $supporter['cd'][] = [
                    'type' => 'phone',
                    'value' => $supporter['phone']
                ];

                if (!empty($supporter['email'])) {
                    $supporter['cd'][] = [
                        'type' => 'email',
                        'value' => [$supporter['email']]
                    ];
                }

                if (!empty($supporter['others'])) {
                    foreach ($supporter['others'] as $key => $value) {
                        if (!empty($value)) {
                            $_k = strtolower($key);
                            if (in_array($_k, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                                $supporter['cd'][] = [
                                    'type' => $_k,
                                    'value' => array_map('trim', explode(',', $value))
                                ];
                            } else {
                                $supporter['cd'][] = [
                                    'type' => ucfirst($key),
                                    'value' => ['is_url' => nv_is_url($value), 'content' => $value]
                                ];
                            }
                        }
                    }
                }

                $supporters[$depid]['items'][] = $supporter;
            }
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('SUPPORTERS', $supporters);

        return $stpl->fetch('block.supporter.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_supporter($block_config);
}
