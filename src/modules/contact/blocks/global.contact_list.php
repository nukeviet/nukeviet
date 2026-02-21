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
     * nv_contact_list_info()
     *
     * @param string $module
     * @return string|void
     */
    function nv_contact_list_info($module)
    {
        global $nv_Cache, $site_mods, $nv_Lang;
        
        $module_data = $site_mods[$module]['module_data'];

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_block_tpl_dir('block.contact_list.tpl', $module));
        $tpl->assign('LANG', $nv_Lang);

        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department ORDER BY weight', 'id', $module);
        if (empty($departments)) {
            return '';
        }

        $list = [];
        foreach ($departments as $row) {
            if (!$row['act']) {
                continue;
            }

            $row['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

            if (!empty($row['image'])) {
                $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
            }

            $cd = [];
            if (!empty($row['phone'])) {
                $phones = nv_parse_phone($row['phone']);
                $items = [];
                foreach ($phones as $num) {
                    $items[] = is_array($num) ? ($num[0] ?? '') : $num;
                }
                $phoneVal = implode(', ', array_filter($items, function ($s) { return $s !== ''; }));
                $cd[] = ['type' => 'phone', 'value' => $phoneVal];
            }

            if (!empty($row['email'])) {
                $emails = array_map('trim', explode(',', $row['email']));
                $cd[] = ['type' => 'email', 'value' => $emails];
            }

            if (!empty($row['others'])) {
                $others = json_decode($row['others'], true);
                if (!empty($others)) {
                    foreach ($others as $key => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $low = strtolower($key);
                        if (in_array($low, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            $vals = array_map('trim', explode(',', $value));
                            $cd[] = ['type' => $low, 'value' => $vals];
                        } else {
                            $isUrl = nv_is_url($value);
                            $cd[] = ['type' => ucfirst($key), 'value' => ['is_url' => $isUrl, 'content' => $value]];
                        }
                    }
                }
            }
            $row['cd'] = $cd;
            $list[] = $row;
        }

        if (empty($list)) {
            return '';
        }

        $tpl->assign('DEPARTMENTS', $list);

        return $tpl->fetch('block.contact_list.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_list_info($block_config['module']);
}
