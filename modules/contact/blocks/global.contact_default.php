<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/25/2010 18:6
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_contact_default_info')) {
    function nv_contact_default_info($module)
    {
        global $nv_Cache, $site_mods, $global_config, $lang_global;
        if (isset($site_mods[$module])) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.contact_default.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.contact_default.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $sql = 'SELECT id, alias, phone, email, others FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1 AND is_default=1';
            $array_department = $nv_Cache->db($sql, 'id', $module);
            if (empty($array_department)) {
                $sql = 'SELECT id, alias, phone, email, others FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1 ORDER BY weight LIMIT 1';
                $array_department = $nv_Cache->db($sql, 'id', $module);
            }

            if (empty($array_department)) {
                return '';
            }

            $xtpl = new XTemplate('block.contact_default.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('LANG', $lang_global);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            $row = array_shift($array_department);
            if (empty($row)) {
                return '';
            }
            $row['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

            $xtpl->assign('DEPARTMENT', $row);

            if (! empty($row['phone'])) {
                $nums = array_map('trim', explode('|', nv_unhtmlspecialchars($row['phone'])));
                foreach ($nums as $k => $num) {
                    unset($m);
                    if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m)) {
                        $phone = array( 'number' => nv_htmlspecialchars($m[1]), 'href' => $m[2] );
                        $xtpl->assign('PHONE', $phone);
                        $xtpl->parse('main.phone.item.href');
                        $xtpl->parse('main.phone.item.href2');
                    } else {
                        $num = preg_replace("/\[[^\]]*\]/", '', $num);
                        $phone = array( 'number' => nv_htmlspecialchars($num) );
                        $xtpl->assign('PHONE', $phone);
                    }
                    if ($k) {
                        $xtpl->parse('main.phone.item.comma');
                    }
                    $xtpl->parse('main.phone.item');
                }

                $xtpl->parse('main.phone');
            }

            if (! empty($row['email'])) {
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

            if (! empty($row['others'])) {
                $others = json_decode($row['others'], true);

                if (!empty($others)) {
                    foreach ($others as $key => $value) {
                        if (!empty($value)) {
                            if (strtolower($key) == 'yahoo') {
                                $ys = array_map('trim', explode(',', $value));
                                foreach ($ys as $k => $y) {
                                    $xtpl->assign('YAHOO', array('name' => $key, 'value' => $y ));
                                    if ($k) {
                                        $xtpl->parse('main.yahoo.item.comma');
                                    }
                                    $xtpl->parse('main.yahoo.item');
                                }
                                $xtpl->parse('main.yahoo');
                            } elseif (strtolower($key) == 'skype') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('SKYPE', array('name' => $key, 'value' => $s ));
                                    if ($k) {
                                        $xtpl->parse('main.skype.item.comma');
                                    }
                                    $xtpl->parse('main.skype.item');
                                }
                                $xtpl->parse('main.skype');
                            } elseif (strtolower($key) == 'viber') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('VIBER', array('name' => $key, 'value' => $s ));
                                    if ($k) {
                                        $xtpl->parse('main.viber.item.comma');
                                    }
                                    $xtpl->parse('main.viber.item');
                                }
                                $xtpl->parse('main.viber');
                            } elseif (strtolower($key) == 'icq') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('ICQ', array('name' => $key, 'value' => $s ));
                                    if ($k) {
                                        $xtpl->parse('main.icq.item.comma');
                                    }
                                    $xtpl->parse('main.icq.item');
                                }
                                $xtpl->parse('main.icq');
                            } elseif (strtolower($key) == 'whatsapp') {
                                $ss = array_map('trim', explode(',', $value));
                                foreach ($ss as $k => $s) {
                                    $xtpl->assign('WHATSAPP', array('name' => $key, 'value' => $s ));
                                    if ($k) {
                                        $xtpl->parse('main.whatsapp.item.comma');
                                    }
                                    $xtpl->parse('main.whatsapp.item');
                                }
                                $xtpl->parse('main.whatsapp');
                            } else {
                                $xtpl->assign('OTHER', array( 'name' => $key, 'value' => $value ));
                                $xtpl->parse('main.other');
                            }
                        }
                    }
                }
            }
            $xtpl->parse('main');
            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_default_info($block_config['module']);
}