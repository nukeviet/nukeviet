<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
     * @param array  $lang_block
     * @return string
     */
    function nv_block_config_contact_department($module, $data_block, $lang_block)
    {
        global $site_mods, $nv_Cache;

        $html = '';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_block['departmentid'] . ':</label>';
        $html .= '<div class="col-sm-9"><select name="config_departmentid" class="form-control">';
        $sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1';
        $list = $nv_Cache->db($sql, 'id', $module);
        foreach ($list as $l) {
            $html .= '<option value="' . $l['id'] . '" ' . (($data_block['departmentid'] == $l['id']) ? ' selected="selected"' : '') . '>' . $l['full_name'] . '</option>';
        }
        $html .= '</select></div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * nv_block_config_contact_department_submit()
     *
     * @param string $module
     * @param array  $lang_block
     * @return array
     */
    function nv_block_config_contact_department_submit($module, $lang_block)
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
        global $global_config, $site_mods, $nv_Cache, $module_name, $lang_module;

        $module = $block_config['module'];
        $module_data = $site_mods[$module]['module_data'];
        $module_file = $site_mods[$module]['module_file'];

        if ($module != $module_name) {
            $lang_temp = $lang_module;
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $global_config['site_lang'] . '.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $global_config['site_lang'] . '.php';
            }
            $lang_module = $lang_module + $lang_temp;
            unset($lang_temp);
        }

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/block.department.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $module_file . '/block.department.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }

        //Danh sach cac bo phan
        $sql = 'SELECT id, full_name, phone, fax, email, address, others, note, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act=1 AND id=' . $block_config['departmentid'];
        $array_department = $nv_Cache->db($sql, 'id', $module);

        $xtpl = new XTemplate('block.department.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);

        if (!empty($array_department)) {
            foreach ($array_department as $value => $row) {
                if (!empty($row)) {
                    $row['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

                    $xtpl->assign('DEPARTMENT', $row);

                    if (!empty($row['phone'])) {
                        $nums = array_map('trim', explode('|', nv_unhtmlspecialchars($row['phone'])));
                        foreach ($nums as $k => $num) {
                            unset($m);
                            if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m)) {
                                $phone = [
                                    'number' => nv_htmlspecialchars($m[1]),
                                    'href' => $m[2]
                                ];
                                $xtpl->assign('PHONE', $phone);
                                $xtpl->parse('main.phone.item.href');
                                $xtpl->parse('main.phone.item.href2');
                            } else {
                                $num = preg_replace("/\[[^\]]*\]/", '', $num);
                                $phone = [
                                    'number' => nv_htmlspecialchars($num)
                                ];
                                $xtpl->assign('PHONE', $phone);
                            }
                            if ($k) {
                                $xtpl->parse('main.phone.item.comma');
                            }
                            $xtpl->parse('main.phone.item');
                        }

                        $xtpl->parse('main.phone');
                    }

                    if (!empty($row['fax'])) {
                        $xtpl->parse('main.fax');
                    }

                    if (!empty($row['email'])) {
                        $emails = array_map('trim', explode(',', $row['email']));

                        foreach ($emails as $k => $email) {
                            $xtpl->assign('EMAIL', $email);
                            if ($k) {
                                $xtpl->parse('main.email.item.comma');
                            }
                            $xtpl->parse('main.email.item');
                        }

                        $xtpl->parse('main.email');
                    }

                    if (!empty($row['others'])) {
                        $others = json_decode($row['others'], true);

                        if (!empty($others)) {
                            foreach ($others as $key => $value) {
                                if (!empty($value)) {
                                    if (strtolower($key) == 'yahoo') {
                                        $ys = array_map('trim', explode(',', $value));
                                        foreach ($ys as $k => $y) {
                                            $xtpl->assign('YAHOO', [
                                                'name' => $key,
                                                'value' => $y
                                            ]);
                                            if ($k) {
                                                $xtpl->parse('main.yahoo.item.comma');
                                            }
                                            $xtpl->parse('main.yahoo.item');
                                        }
                                        $xtpl->parse('main.yahoo');
                                    } elseif (strtolower($key) == 'skype') {
                                        $ss = array_map('trim', explode(',', $value));
                                        foreach ($ss as $k => $s) {
                                            $xtpl->assign('SKYPE', [
                                                'name' => $key,
                                                'value' => $s
                                            ]);
                                            if ($k) {
                                                $xtpl->parse('main.skype.item.comma');
                                            }
                                            $xtpl->parse('main.skype.item');
                                        }
                                        $xtpl->parse('main.skype');
                                    } elseif (strtolower($key) == 'viber') {
                                        $ss = array_map('trim', explode(',', $value));
                                        foreach ($ss as $k => $s) {
                                            $xtpl->assign('VIBER', [
                                                'name' => $key,
                                                'value' => $s
                                            ]);
                                            if ($k) {
                                                $xtpl->parse('main.viber.item.comma');
                                            }
                                            $xtpl->parse('main.viber.item');
                                        }
                                        $xtpl->parse('main.viber');
                                    } elseif (strtolower($key) == 'icq') {
                                        $ss = array_map('trim', explode(',', $value));
                                        foreach ($ss as $k => $s) {
                                            $xtpl->assign('ICQ', [
                                                'name' => $key,
                                                'value' => $s
                                            ]);
                                            if ($k) {
                                                $xtpl->parse('main.icq.item.comma');
                                            }
                                            $xtpl->parse('main.icq.item');
                                        }
                                        $xtpl->parse('main.icq');
                                    } elseif (strtolower($key) == 'whatsapp') {
                                        $ss = array_map('trim', explode(',', $value));
                                        foreach ($ss as $k => $s) {
                                            $xtpl->assign('WHATSAPP', [
                                                'name' => $key,
                                                'value' => $s
                                            ]);
                                            if ($k) {
                                                $xtpl->parse('main.whatsapp.item.comma');
                                            }
                                            $xtpl->parse('main.whatsapp.item');
                                        }
                                        $xtpl->parse('main.whatsapp');
                                    } else {
                                        $xtpl->assign('OTHER', [
                                            'name' => $key,
                                            'value' => $value
                                        ]);
                                        $xtpl->parse('main.other');
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($row['address'])) {
                        $xtpl->parse('main.address');
                    }
                } else {
                    return '';
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
