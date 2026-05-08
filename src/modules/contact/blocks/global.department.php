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

if (!nv_function_exists('nv_department_info')) {
    /**
     * nv_block_config_contact_department()
     *
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_contact_department($module, $data_block)
    {
        global $site_mods, $nv_Cache, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('global.department.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);

        $departments_raw = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        $tpl->assign('DEPARTMENTS', array_filter($departments_raw, fn($d) => $d['act']));

        return $tpl->fetch('global.department.config.tpl');
    }

    /**
     * nv_block_config_contact_department_submit()
     *
     * @param string $module
     * @return array
     */
    function nv_block_config_contact_department_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['departmentid'] = $nv_Request->get_int('config_departmentid', 'post', 0);

        return $return;
    }

    /**
     * nv_department_info()
     *
     * @param array $block_config
     * @return string
     */
    function nv_department_info($block_config)
    {
        global $site_mods, $nv_Cache, $nv_Lang;

        $module = $block_config['module'];
        if (!isset($site_mods[$module])) {
            return '';
        }

        [$block_theme, $dir] = get_block_tpl_dir('global.department.tpl', true, $module);
        if (empty($dir)) {
            return '';
        }

        // Danh sách bộ phận
        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        if (!isset($departments[$block_config['departmentid']]) || !$departments[$block_config['departmentid']]['act']) {
            return '';
        }
        $row = $departments[$block_config['departmentid']];

        $emailhref = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

        $department = [
            'full_name' => $row['full_name'],
            'url' => $emailhref,
            'note' => $row['note'],
            'contacts' => [],
            'others' => [],
        ];

        // Địa chỉ
        if (!empty($row['address'])) {
            $department['contacts'][] = [
                'type' => 'address',
                'display' => $row['address'],
                'link' => '',
            ];
        }

        // Điện thoại
        if (!empty($row['phone'])) {
            $phones = nv_parse_phone($row['phone']);
            foreach ($phones as $num) {
                $department['contacts'][] = [
                    'type' => 'phone',
                    'display' => $num[0],
                    'link' => count($num) == 2 ? 'tel:' . $num[1] : '',
                ];
            }
        }

        // Fax
        if (!empty($row['fax'])) {
            $department['contacts'][] = [
                'type' => 'fax',
                'display' => nv_htmlspecialchars($row['fax']),
                'link' => '',
            ];
        }

        // Email
        if (!empty($row['email'])) {
            $emails = array_map('trim', explode(',', $row['email']));
            foreach ($emails as $email) {
                if (empty($email)) {
                    continue;
                }
                $department['contacts'][] = [
                    'type' => 'email',
                    'display' => nv_htmlspecialchars($email),
                    'link' => $emailhref,
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
                            $department['contacts'][] = [
                                'type' => $lkey,
                                'display' => nv_htmlspecialchars($s),
                                'link' => $link,
                            ];
                        }
                    } else {
                        $department['others'][] = [
                            'name' => nv_htmlspecialchars($key),
                            'value' => nv_htmlspecialchars($value),
                        ];
                    }
                }
            }
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('MODULE', $module);
        $tpl->assign('DEPARTMENT', $department);

        return $tpl->fetch('global.department.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_department_info($block_config);
}
