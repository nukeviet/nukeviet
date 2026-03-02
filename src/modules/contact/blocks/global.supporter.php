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
        global $nv_Cache, $site_mods, $nv_Lang, $module_name;

        if (!isset($site_mods[$module])) {
            return '';
        }

        if ($module_name == $module) {
            return '';
        }

        $module_data = $site_mods[$module]['module_data'];
        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ORDER BY weight', 'id', $module);
        $supporters = block_supporter_get_list($module, $departments);
        if (empty($supporters)) {
            return '';
        }

        $module_file = $site_mods[$module]['module_file'];
        addition_module_assets($module, 'css');
        $nv_Lang->loadModule($module_file, false, true);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_block_tpl_dir('block.supporter.tpl', $module));
        $tpl->assign('LANG', $nv_Lang);

        $deps = [];
        $active = false;
        foreach ($supporters as $depid => $sps) {
            $fullName = $depid == 0 ? $nv_Lang->getGlobal('general_support') : $departments[$depid]['full_name'];
            // Icon cấu hình qua 'others' của bộ phận (key: icon). Mặc định trung tính.
            $icon = 'fa-circle-info';
            if ($depid != 0 && !empty($departments[$depid]['others'])) {
                $depOthers = block_supporter_parse_others($departments[$depid]['others']);
                if (!empty($depOthers['icon'])) {
                    $icon = preg_replace('/[^a-zA-Z0-9\\-\\s_]/', '', $depOthers['icon']);
                }
            }

            $deps[] = [
                'id' => $depid,
                'full_name' => $fullName,
                'active' => !$active,
                'icon' => $icon
            ];
            $active = true;
        }
        $tpl->assign('DEPARTMENTS', $deps);

        $SUPPORTERS = [];
        foreach ($supporters as $depid => $sps) {
            $SUPPORTERS[$depid] = [];
            $sp_count = count($sps) - 1;
            $i = 0;
            foreach ($sps as $supporter) {
                $items = [];
                $callHref = '';
                $phoneText = '';
                foreach ($supporter['phone'] as $num) {
                    if (count($num) == 2) {
                        $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                        if ($callHref === '') {
                            $callHref = 'tel:' . $num[1];
                        }
                        if ($phoneText === '') {
                            $phoneText = $num[0];
                        }
                    } else {
                        $items[] = $num[0];
                        if ($callHref === '') {
                            $digits = preg_replace('/[^0-9+]/', '', $num[0]);
                            if (!empty($digits)) {
                                $callHref = 'tel:' . $digits;
                            }
                        }
                        if ($phoneText === '') {
                            $phoneText = $num[0];
                        }
                    }
                }
                if (!empty($items)) {
                    $SUPPORTERS[$depid][] = [
                        'full_name' => $supporter['full_name'],
                        'image' => $supporter['image'],
                        'items' => [
                            [
                                'icon' => 'fa-phone',
                                'value' => implode(', ', $items)
                            ]
                        ],
                        'has_separator' => false,
                        'call_href' => $callHref,
                        'has_call' => !empty($callHref),
                        'phone_text' => $phoneText,
                        'email_href' => '',
                        'has_email' => false
                    ];
                } else {
                    $SUPPORTERS[$depid][] = [
                        'full_name' => $supporter['full_name'],
                        'image' => $supporter['image'],
                        'items' => [],
                        'has_separator' => false,
                        'call_href' => $callHref,
                        'has_call' => !empty($callHref),
                        'phone_text' => $phoneText,
                        'email_href' => '',
                        'has_email' => false
                    ];
                }

                $idx = count($SUPPORTERS[$depid]) - 1;

                $others_processed = [];
                if (!empty($supporter['others']) && is_array($supporter['others'])) {
                    foreach ($supporter['others'] as $k => $v) {
                        if ($v) {
                            $others_processed[strtolower($k)] = $v;
                        }
                    }
                }
                $SUPPORTERS[$depid][$idx]['others'] = $others_processed;

                if (!empty($supporter['email'])) {
                    $email = trim($supporter['email']);
                    $SUPPORTERS[$depid][$idx]['items'][] = [
                        'icon' => 'fa-envelope',
                        'value' => '<a href="mailto:' . $email . '">' . $email . '</a>'
                    ];
                    $SUPPORTERS[$depid][$idx]['email_href'] = 'mailto:' . $email;
                     $SUPPORTERS[$depid][$idx]['email_text'] = $email;
                     $SUPPORTERS[$depid][$idx]['has_email'] = true;
                }

                if (!empty($supporter['others'])) {
                    foreach ($supporter['others'] as $key => $value) {
                        if (!empty($value)) {
                            $k = strtolower($key);
                            if ($k == 'skype') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);
                                    return '<a href="skype:' . $item . '?call">' . $item . '</a>';
                                }, explode(',', $value));
                                $SUPPORTERS[$depid][$idx]['items'][] = [
                                    'icon' => 'fa-skype',
                                    'value' => implode(', ', $items)
                                ];
                            } elseif ($k == 'viber') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);
                                    return '<a href="viber://pa?chatURI=' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $SUPPORTERS[$depid][$idx]['items'][] = [
                                    'icon' => 'icon-viber',
                                    'value' => implode(', ', $items)
                                ];
                            } elseif ($k == 'whatsapp') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);
                                    return '<a href="https://wa.me/' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $SUPPORTERS[$depid][$idx]['items'][] = [
                                    'icon' => 'fa-whatsapp',
                                    'value' => implode(', ', $items)
                                ];
                            } elseif ($k == 'zalo') {
                                $items = array_map(function ($item) {
                                    $item = trim($item);
                                    return '<a href="https://zalo.me/' . $item . '">' . $item . '</a>';
                                }, explode(',', $value));
                                $SUPPORTERS[$depid][$idx]['items'][] = [
                                    'icon' => 'icon-zalo',
                                    'value' => implode(', ', $items)
                                ];
                            } else {
                                $SUPPORTERS[$depid][$idx]['items'][] = [
                                    'icon' => '',
                                    'value' => nv_is_url($value) ? '<a href="' . $value . '">' . $value . '</a>' : $value
                                ];
                            }
                        }
                    }
                }

                if ($i < $sp_count) {
                    $SUPPORTERS[$depid][$idx]['has_separator'] = true;
                }

                ++$i;
            }
        }

        $tpl->assign('SUPPORTERS', $SUPPORTERS);
        $content = $tpl->fetch('block.supporter.tpl');
        $nv_Lang->changeLang();
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_supporter($block_config['module']);
}
