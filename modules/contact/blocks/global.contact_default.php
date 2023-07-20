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

if (!nv_function_exists('nv_contact_default_info')) {
    /**
     * nv_contact_default_info()
     *
     * @param string $module
     * @return string|void
     */
    function nv_contact_default_info($module)
    {
        global $nv_Cache, $site_mods, $global_config;

        if (isset($site_mods[$module])) {
            $block_theme = get_tpl_dir([$global_config['module_theme'], $global_config['site_theme']], 'default', '/modules/' . $site_mods[$module]['module_file'] . '/block.contact_default.tpl');
            $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
            if (empty($departments)) {
                return '';
            }
            $default_department = [];
            foreach ($departments as $department) {
                if ($department['act']) {
                    if (empty($default_department)) {
                        $default_department = $department;
                    }
                    if ($department['is_default']) {
                        $default_department = $department;
                        break;
                    }
                }
            }

            if (empty($default_department)) {
                return '';
            }

            $xtpl = new XTemplate('block.contact_default.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $site_mods[$module]['module_file']);
            $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
            $xtpl->assign('TEMPLATE', $block_theme);
            $xtpl->assign('MODULE', $module);

            $default_department['emailhref'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $default_department['alias'];

            $xtpl->assign('DEPARTMENT', $default_department);

            if (!empty($default_department['phone'])) {
                $default_department['phone'] = nv_parse_phone($default_department['phone']);
                $items = [];
                foreach ($default_department['phone'] as $num) {
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
                $xtpl->parse('main.cd');
            }

            if (!empty($default_department['email'])) {
                $emails = array_map('trim', explode(',', $default_department['email']));
                $items = [];
                foreach ($emails as $email) {
                    $items[] = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $default_department['alias'] . '">' . $email . '</a>';
                }
                $xtpl->assign('CD', [
                    'icon' => 'fa-envelope',
                    'value' => implode(', ', $items)
                ]);
                $xtpl->parse('main.cd');
            }

            if (!empty($default_department['others'])) {
                $others = json_decode($default_department['others'], true);

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
                                $xtpl->parse('main.cd');
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
                                $xtpl->parse('main.cd');
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
                                $xtpl->parse('main.cd');
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
                                $xtpl->parse('main.cd');
                            } else {
                                $xtpl->assign('OTHER', ['name' => $key, 'value' => $value]);
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
