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

if (!nv_function_exists('nv_contact_list_info')) {
    /**
     * nv_contact_list_info()
     *
     * @param string $module
     * @return string|void
     */
    function nv_contact_list_info($module)
    {
        global $nv_Cache, $site_mods, $global_config, $lang_global;
        if (isset($site_mods[$module])) {
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.contact_list.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.contact_list.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $sql = 'SELECT id, full_name, alias, phone, email, others, image FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1 ORDER BY weight';
            $_array_department = $nv_Cache->db($sql, 'id', $module);

            if (empty($_array_department)) {
                return '';
            }

            $xtpl = new XTemplate('block.contact_list.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('LANG', $lang_global);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            foreach ($_array_department as $array_department) {
                $row = $array_department;

                $row['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

                $is_image = 0;
                if (!empty($row['image']) and file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'])) {
                    $is_image = 1;
                    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                }

                $xtpl->assign('DEPARTMENT', $row);

                if ($is_image) {
                    $xtpl->parse('main.loop.image');
                }

                if (!empty($row['phone'])) {
                    $nums = array_map('trim', explode('|', nv_unhtmlspecialchars($row['phone'])));
                    foreach ($nums as $k => $num) {
                        unset($m);
                        if (preg_match("/^(.*)\s*\[([0-9\+\.\,\;\*\#]+)\]$/", $num, $m)) {
                            $phone = ['number' => nv_htmlspecialchars($m[1]), 'href' => $m[2]];
                            $xtpl->assign('PHONE', $phone);
                            $xtpl->parse('main.loop.phone.item.href');
                            $xtpl->parse('main.loop.phone.item.href2');
                        } else {
                            $num = preg_replace("/\[[^\]]*\]/", '', $num);
                            $phone = ['number' => nv_htmlspecialchars($num)];
                            $xtpl->assign('PHONE', $phone);
                        }
                        if ($k) {
                            $xtpl->parse('main.loop.phone.item.comma');
                        }
                        $xtpl->parse('main.loop.phone.item');
                    }

                    $xtpl->parse('main.loop.phone');
                }

                if (!empty($row['email'])) {
                    $emails = array_map('trim', explode(',', $row['email']));

                    foreach ($emails as $k => $email) {
                        $xtpl->assign('EMAIL', $email);
                        if ($k) {
                            $xtpl->parse('main.loop.email.item.comma');
                        }
                        $xtpl->parse('main.loop.email.item');
                    }

                    $xtpl->parse('main.loop.email');
                }

                if (!empty($row['others'])) {
                    $others = json_decode($row['others'], true);

                    if (!empty($others)) {
                        foreach ($others as $key => $value) {
                            if (!empty($value)) {
                                if (strtolower($key) == 'yahoo') {
                                    $ys = array_map('trim', explode(',', $value));
                                    foreach ($ys as $k => $y) {
                                        $xtpl->assign('YAHOO', ['name' => $key, 'value' => $y]);
                                        if ($k) {
                                            $xtpl->parse('main.loop.yahoo.item.comma');
                                        }
                                        $xtpl->parse('main.loop.yahoo.item');
                                    }
                                    $xtpl->parse('main.loop.yahoo');
                                } elseif (strtolower($key) == 'skype') {
                                    $ss = array_map('trim', explode(',', $value));
                                    foreach ($ss as $k => $s) {
                                        $xtpl->assign('SKYPE', ['name' => $key, 'value' => $s]);
                                        if ($k) {
                                            $xtpl->parse('main.loop.skype.item.comma');
                                        }
                                        $xtpl->parse('main.loop.skype.item');
                                    }
                                    $xtpl->parse('main.loop.skype');
                                } elseif (strtolower($key) == 'viber') {
                                    $ss = array_map('trim', explode(',', $value));
                                    foreach ($ss as $k => $s) {
                                        $xtpl->assign('VIBER', ['name' => $key, 'value' => $s]);
                                        if ($k) {
                                            $xtpl->parse('main.loop.viber.item.comma');
                                        }
                                        $xtpl->parse('main.loop.viber.item');
                                    }
                                    $xtpl->parse('main.loop.viber');
                                } elseif (strtolower($key) == 'icq') {
                                    $ss = array_map('trim', explode(',', $value));
                                    foreach ($ss as $k => $s) {
                                        $xtpl->assign('ICQ', ['name' => $key, 'value' => $s]);
                                        if ($k) {
                                            $xtpl->parse('main.loop.icq.item.comma');
                                        }
                                        $xtpl->parse('main.loop.icq.item');
                                    }
                                    $xtpl->parse('main.loop.icq');
                                } elseif (strtolower($key) == 'whatsapp') {
                                    $ss = array_map('trim', explode(',', $value));
                                    foreach ($ss as $k => $s) {
                                        $xtpl->assign('WHATSAPP', ['name' => $key, 'value' => $s]);
                                        if ($k) {
                                            $xtpl->parse('main.loop.whatsapp.item.comma');
                                        }
                                        $xtpl->parse('main.loop.whatsapp.item');
                                    }
                                    $xtpl->parse('main.loop.whatsapp');
                                } else {
                                    $xtpl->assign('OTHER', ['name' => $key, 'value' => $value]);
                                    $xtpl->parse('main.loop.other');
                                }
                            }
                        }
                    }
                }
                $xtpl->parse('main.loop');
            }
            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_list_info($block_config['module']);
}
