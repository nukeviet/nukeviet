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

if (!nv_function_exists('nv_contact_list_info')) {
    /**
     * @param string $module
     * @return string
     */
    function nv_contact_list_info($module)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        if (!isset($site_mods[$module])) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('block.contact_list.tpl', true, $module);

        $departments_raw = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        if (empty($departments_raw) or empty($dir)) {
            return '';
        }

        $nv_Lang->loadModule($site_mods[$module]['module_file'], loadtmp: true);

        $departments = [];
        foreach ($departments_raw as $row) {
            if (!$row['act']) {
                continue;
            }

            $dept = [
                'full_name' => $row['full_name'],
                'image' => '',
                'emailhref' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'],
                'contacts' => [],
                'others' => [],
            ];

            if (!empty($row['image'])) {
                $dept['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
            }

            // Điện thoại
            if (!empty($row['phone'])) {
                $phones = nv_parse_phone($row['phone']);
                foreach ($phones as $num) {
                    $dept['contacts'][] = [
                        'type' => 'phone',
                        'display' => $num[0],
                        'link' => count($num) == 2 ? 'tel:' . $num[1] : '',
                    ];
                }
            }

            // Email
            if (!empty($row['email'])) {
                $emails = array_map('trim', explode(',', $row['email']));
                foreach ($emails as $email) {
                    if (empty($email)) {
                        continue;
                    }
                    $dept['contacts'][] = [
                        'type' => 'email',
                        'display' => nv_htmlspecialchars($email),
                        'link' => $dept['emailhref'],
                    ];
                }
            }

            // Liên hệ khác
            if (!empty($row['others'])) {
                $others = json_decode($row['others'], true);
                if (!empty($others)) {
                    foreach ($others as $key => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $lkey = strtolower($key);

                        if (in_array($lkey, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            $ss = array_map('trim', explode(',', $value));
                            foreach ($ss as $s) {
                                if (empty($s)) {
                                    continue;
                                }
                                $link = match ($lkey) {
                                    'skype' => 'skype:' . $s . '?call',
                                    'viber' => 'viber://pa?chatURI=' . $s,
                                    'whatsapp' => 'https://wa.me/' . $s,
                                    'zalo' => 'https://zalo.me/' . $s,
                                };
                                $dept['contacts'][] = [
                                    'type' => $lkey,
                                    'display' => nv_htmlspecialchars($s),
                                    'link' => $link,
                                ];
                            }
                        } else {
                            $dept['others'][] = ['name' => nv_htmlspecialchars($key), 'value' => nv_htmlspecialchars($value)];
                        }
                    }
                }
            }

            $departments[] = $dept;
        }

        if (empty($departments)) {
            return '';
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MODULE', $module);
        $tpl->assign('DEPARTMENTS', $departments);

        $content = $tpl->fetch('block.contact_list.tpl');
        $nv_Lang->changeLang();
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_list_info($block_config['module']);
}
