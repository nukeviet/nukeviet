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
        global $db, $nv_Cache, $site_mods, $global_config, $lang_global;

        if (isset($site_mods[$module])) {
            $cache_file = NV_LANG_DATA . '_block_contact_supporter' . NV_CACHE_PREFIX . '.cache';
            $array_data = [];

            $sql = 'SELECT id, full_name, alias, phone, email, others, image FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE act=1 ORDER BY weight';
            $_array_department = $nv_Cache->db($sql, 'id', $module);

            if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
                $array_data = unserialize($cache);
            } else {
                foreach ($_array_department as $array_department) {
                    $db->sqlreset()
                        ->select('*')
                        ->from(NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_supporter')
                        ->where('act=1 AND departmentid=' . $array_department['id'])
                        ->order('weight ASC');

                    $sth = $db->prepare($db->sql());
                    $sth->execute();

                    while ($_row = $sth->fetch()) {
                        $array_data[$array_department['id']][] = $_row;
                    }
                }
                $cache = serialize($array_data);
                $nv_Cache->setItem($module, $cache_file, $cache);
            }

            if (empty($array_data)) {
                return '';
            }

            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.supporter.tpl')) {
                $block_theme = $global_config['module_theme'];
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $site_mods[$module]['module_file'] . '/block.supporter.tpl')) {
                $block_theme = $global_config['site_theme'];
            } else {
                $block_theme = 'default';
            }

            $xtpl = new XTemplate('block.supporter.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('LANG', $lang_global);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            foreach ($array_data as $departmentid => $supporter) {
                if (!empty($supporter)) {
                    $row = $_array_department[$departmentid];
                    if (!empty($row['image'])) {
                        $row['srcset'] = '';
                        if (!nv_is_url($row['image'])) {
                            if (file_exists(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'])) {
                                $imagesize = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image']);
                                $row['srcset'] = NV_BASE_SITEURL . NV_MOBILE_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'] . ' ' . NV_MOBILE_MODE_IMG . 'w, ';
                                $row['srcset'] .= NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'] . ' ' . $imagesize[0] . 'w';
                            }
                            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                        }
                    }
                    $xtpl->assign('DEPARTMENT', $row);

                    if (!empty($row['image'])) {
                        $xtpl->parse('main.loop.image');
                    }

                    foreach ($supporter as $row) {
                        $xtpl->assign('SUPPORTER', $row);
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
                                    $xtpl->parse('main.loop.supporter.phone.item.href');
                                    $xtpl->parse('main.loop.supporter.phone.item.href2');
                                } else {
                                    $num = preg_replace("/\[[^\]]*\]/", '', $num);
                                    $phone = [
                                        'number' => nv_htmlspecialchars($num)
                                    ];
                                    $xtpl->assign('PHONE', $phone);
                                }
                                if ($k) {
                                    $xtpl->parse('main.loop.supporter.phone.item.comma');
                                }
                                $xtpl->parse('main.loop.supporter.phone.item');
                            }

                            $xtpl->parse('main.loop.supporter.phone');
                        }

                        if (!empty($row['email'])) {
                            $emails = array_map('trim', explode(',', $row['email']));

                            foreach ($emails as $k => $email) {
                                $xtpl->assign('EMAIL', $email);
                                if ($k) {
                                    $xtpl->parse('main.loop.supporter.email.item.comma');
                                }
                                $xtpl->parse('main.loop.supporter.email.item');
                            }

                            $xtpl->parse('main.loop.supporter.email');
                        }

                        if (!empty($row['others'])) {
                            $others = unserialize($row['others']);
                            if (!empty($others)) {
                                foreach ($others as $key => $value) {
                                    $key = $value['name'];
                                    $value = $value['value'];
                                    if (!empty($value)) {
                                        if (strtolower($key) == 'yahoo') {
                                            $ys = array_map('trim', explode(',', $value));
                                            foreach ($ys as $k => $y) {
                                                $xtpl->assign('YAHOO', [
                                                    'name' => $key,
                                                    'value' => $y
                                                ]);
                                                if ($k) {
                                                    $xtpl->parse('main.loop.supporter.yahoo.item.comma');
                                                }
                                                $xtpl->parse('main.loop.supporter.yahoo.item');
                                            }
                                            $xtpl->parse('main.loop.supporter.yahoo');
                                        } elseif (strtolower($key) == 'skype') {
                                            $ss = array_map('trim', explode(',', $value));
                                            foreach ($ss as $k => $s) {
                                                $xtpl->assign('SKYPE', [
                                                    'name' => $key,
                                                    'value' => $s
                                                ]);
                                                if ($k) {
                                                    $xtpl->parse('main.loop.supporter.skype.item.comma');
                                                }
                                                $xtpl->parse('main.loop.supporter.skype.item');
                                            }
                                            $xtpl->parse('main.loop.supporter.skype');
                                        } elseif (strtolower($key) == 'viber') {
                                            $ss = array_map('trim', explode(',', $value));
                                            foreach ($ss as $k => $s) {
                                                $xtpl->assign('VIBER', [
                                                    'name' => $key,
                                                    'value' => $s
                                                ]);
                                                if ($k) {
                                                    $xtpl->parse('main.loop.supporter.viber.item.comma');
                                                }
                                                $xtpl->parse('main.loop.supporter.viber.item');
                                            }
                                            $xtpl->parse('main.loop.supporter.viber');
                                        } elseif (strtolower($key) == 'icq') {
                                            $ss = array_map('trim', explode(',', $value));
                                            foreach ($ss as $k => $s) {
                                                $xtpl->assign('ICQ', [
                                                    'name' => $key,
                                                    'value' => $s
                                                ]);
                                                if ($k) {
                                                    $xtpl->parse('main.loop.supporter.icq.item.comma');
                                                }
                                                $xtpl->parse('main.loop.supporter.icq.item');
                                            }
                                            $xtpl->parse('main.loop.supporter.icq');
                                        } elseif (strtolower($key) == 'whatsapp') {
                                            $ss = array_map('trim', explode(',', $value));
                                            foreach ($ss as $k => $s) {
                                                $xtpl->assign('WHATSAPP', [
                                                    'name' => $key,
                                                    'value' => $s
                                                ]);
                                                if ($k) {
                                                    $xtpl->parse('main.loop.supporter.whatsapp.item.comma');
                                                }
                                                $xtpl->parse('main.loop.supporter.whatsapp.item');
                                            }
                                            $xtpl->parse('main.loop.supporter.whatsapp');
                                        } else {
                                            $xtpl->assign('OTHER', [
                                                'name' => $key,
                                                'value' => $value
                                            ]);
                                            $xtpl->parse('main.loop.supporter.other');
                                        }
                                    }
                                }
                            }
                        }
                        $xtpl->parse('main.loop.supporter');
                    }
                    $xtpl->parse('main.loop');
                }
            }
            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_supporter($block_config['module']);
}
