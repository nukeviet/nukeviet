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
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $nv_Lang->getModule('departmentid') . ':</label>';
        $html .= '<div class="col-sm-9"><select name="config_departmentid" class="form-control">';
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

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/block.department.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file . '/block.department.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        //Danh sach cac bo phan
        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        if (!isset($departments[$block_config['departmentid']]) or !$departments[$block_config['departmentid']]['act']) {
            return '';
        }
        $row = $departments[$block_config['departmentid']];
        if (empty($row)) {
            return '';
        }

        $xtpl = new XTemplate('block.department.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);

        $row['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

        $xtpl->assign('DEPARTMENT', $row);

        if (!empty($row['note'])) {
            $xtpl->parse('main.note');
        }

        if (!empty($row['address'])) {
            $xtpl->parse('main.address');
        }

        if (!empty($row['phone'])) {
            $row['phone'] = nv_parse_phone($row['phone']);
            $items = [];
            foreach ($row['phone'] as $num) {
                if (count($num) == 2) {
                    $items[] = '<a href="tel:' . $num[1] . '">' . $num[0] . '</a>';
                } else {
                    $items[] = $num[0];
                }
            }
            $xtpl->assign('CD', [
                'name' => $nv_Lang->getGlobal('phonenumber'),
                'value' => implode(', ', $items)
            ]);
            $xtpl->parse('main.cd');
        }

        if (!empty($row['fax'])) {
            $xtpl->assign('CD', [
                'name' => 'Fax',
                'value' => $row['fax']
            ]);
            $xtpl->parse('main.cd');
        }

        if (!empty($row['email'])) {
            $emails = array_map('trim', explode(',', $row['email']));
            $items = [];
            foreach ($emails as $email) {
                $items[] = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . '">' . $email . '</a>';
            }
            $xtpl->assign('CD', [
                'name' => $nv_Lang->getGlobal('email'),
                'value' => implode(', ', $items)
            ]);

            $xtpl->parse('main.cd');
        }

        if (!empty($row['others'])) {
            $others = json_decode($row['others'], true);

            if (!empty($others)) {
                foreach ($others as $key => $value) {
                    if (!empty($value)) {
                        if (strtolower($key) == 'skype') {
                            $ss = array_map('trim', explode(',', $value));
                            $items = [];
                            foreach ($ss as $s) {
                                $items[] = '<a href="skype:' . $s . '?call">' . $s . '</a>';
                            }
                            $xtpl->assign('CD', [
                                'name' => 'Skype',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'viber') {
                            $ss = array_map('trim', explode(',', $value));
                            $items = [];
                            foreach ($ss as $s) {
                                $items[] = '<a href="viber://pa?chatURI=' . $s . '">' . $s . '</a>';
                            }
                            $xtpl->assign('CD', [
                                'name' => 'Viber',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'whatsapp') {
                            $ss = array_map('trim', explode(',', $value));
                            $items = [];
                            foreach ($ss as $s) {
                                $items[] = '<a href="https://wa.me/' . $s . '">' . $s . '</a>';
                            }
                            $xtpl->assign('CD', [
                                'name' => 'WhatsApp',
                                'value' => implode(', ', $items)
                            ]);
                        } elseif (strtolower($key) == 'zalo') {
                            $ss = array_map('trim', explode(',', $value));
                            $items = [];
                            foreach ($ss as $s) {
                                $items[] = '<a href="https://zalo.me/' . $s . '">' . $s . '</a>';
                            }
                            $xtpl->assign('CD', [
                                'name' => 'Zalo',
                                'value' => implode(', ', $items)
                            ]);
                        } else {
                            $xtpl->assign('CD', [
                                'name' => ucfirst($key),
                                'value' => $value
                            ]);
                        }
                        $xtpl->parse('main.cd');
                    }
                }
            }
        }
        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $global_array_cat, $module_array_cat;
    $module = $block_config['module'];
    if (isset($site_mods[$module])) {
        $content = nv_department_info($block_config);
    }
}
