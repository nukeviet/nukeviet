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

        $html = '';
        $html .= '<div class="row mb-3">';
        $html .= '<label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">' . $nv_Lang->getModule('departmentid') . ':</label>';
        $html .= '<div class="col-sm-5"><select name="config_departmentid" class="form-select">';
        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        foreach ($departments as $l) {
            if ($l['act']) {
                $html .= '<option value="' . $l['id'] . '" ' . (($data_block['departmentid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['full_name'] . '</option>';
            }
        }
        $html .= '</select></div>';
        $html .= '</div>';

        return $html;
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
        global $global_config, $site_mods, $nv_Cache, $module_name, $nv_Lang;

        $module = $block_config['module'];
        $module_data = $site_mods[$module]['module_data'];
        $module_file = $site_mods[$module]['module_file'];

        $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $module_file . '/block.department.tpl');

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(dirname(NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_file . '/block.department.tpl'));
        $tpl->assign('LANG', $nv_Lang);

        //Danh sach cac bo phan
        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        if (!isset($departments[$block_config['departmentid']]) or !$departments[$block_config['departmentid']]['act']) {
            return '';
        }
        $row = $departments[$block_config['departmentid']];
        if (empty($row)) {
            return '';
        }

        $row['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

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

        if (!empty($row['fax'])) {
            $cd[] = ['type' => 'fax', 'value' => $row['fax']];
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
        $tpl->assign('DEPARTMENT', $row);

        return $tpl->fetch('block.department.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_department_info($block_config);
    }
}
