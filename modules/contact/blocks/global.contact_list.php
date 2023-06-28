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

            $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
            if (empty($departments)) {
                return '';
            }

            $xtpl = new XTemplate('block.contact_list.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('LANG', $lang_global);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            $active = false;
            foreach ($departments as $row) {
                if ($row['act']) {
                    $active = true;
                    $row['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'];

                    if (!empty($row['image'])) {
                        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $row['image'];
                    }

                    $xtpl->assign('DEPARTMENT', $row);

                    if (!empty($row['image'])) {
                        $xtpl->parse('main.loop.image');
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
                            'icon' => 'fa-phone',
                            'value' => implode(', ', $items)
                        ]);
                        $xtpl->parse('main.loop.cd');
                    }

                    if (!empty($row['email'])) {
                        $emails = array_map('trim', explode(',', $row['email']));
                        $items = [];
                        foreach ($emails as $email) {
                            $items[] = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . '">' . $email . '</a>';
                        }
                        $xtpl->assign('CD', [
                            'icon' => 'fa-envelope',
                            'value' => implode(', ', $items)
                        ]);

                        $xtpl->parse('main.loop.cd');
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
                                            'icon' => 'fa-skype',
                                            'value' => implode(', ', $items)
                                        ]);
                                        $xtpl->parse('main.loop.cd');
                                    } elseif (strtolower($key) == 'viber') {
                                        $ss = array_map('trim', explode(',', $value));
                                        $items = [];
                                        foreach ($ss as $s) {
                                            $items[] = '<a href="viber://pa?chatURI=' . $s . '">' . $s . '</a>';
                                        }
                                        $xtpl->assign('CD', [
                                            'icon' => 'icon-viber',
                                            'value' => implode(', ', $items)
                                        ]);
                                        $xtpl->parse('main.loop.cd');
                                    } elseif (strtolower($key) == 'whatsapp') {
                                        $ss = array_map('trim', explode(',', $value));
                                        $items = [];
                                        foreach ($ss as $s) {
                                            $items[] = '<a href="https://wa.me/' . $s . '">' . $s . '</a>';
                                        }
                                        $xtpl->assign('CD', [
                                            'icon' => 'fa-whatsapp',
                                            'value' => implode(', ', $items)
                                        ]);
                                        $xtpl->parse('main.loop.cd');
                                    } elseif (strtolower($key) == 'zalo') {
                                        $ss = array_map('trim', explode(',', $value));
                                        $items = [];
                                        foreach ($ss as $s) {
                                            $items[] = '<a href="https://zalo.me/' . $s . '">' . $s . '</a>';
                                        }
                                        $xtpl->assign('CD', [
                                            'icon' => 'icon-zalo',
                                            'value' => implode(', ', $items)
                                        ]);
                                        $xtpl->parse('main.loop.cd');
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
            }
            if (!$active) {
                return '';
            }

            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_list_info($block_config['module']);
}
