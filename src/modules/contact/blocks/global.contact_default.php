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

if (!nv_function_exists('nv_contact_default_info')) {
    /**
     * @param string $module
     * @param array  $data_block
     * @return string
     */
    function nv_block_config_contact_default($module, $data_block)
    {
        global $site_mods, $nv_Cache, $nv_Lang;

        [$block_theme, $dir] = get_block_tpl_dir('block.contact_default.config.tpl', true, $module);
        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('CONFIG', $data_block);
        $tpl->assign('SHOWS', [
            'phone' => $nv_Lang->getModule('phone'),
            'fax' => $nv_Lang->getModule('fax'),
            'email' => $nv_Lang->getModule('email'),
            'other' => $nv_Lang->getModule('otherContacts'),
        ]);

        $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
        $tpl->assign('DEPARTMENTS', $departments);

        return $tpl->fetch('block.contact_default.config.tpl');
    }

    /**
     * @param string $module
     * @return array
     */
    function nv_block_config_contact_default_submit($module)
    {
        global $nv_Request;
        $return = [];
        $return['error'] = [];
        $return['config'] = [];
        $return['config']['departmentid'] = $nv_Request->get_int('config_departmentid', 'post', 0);
        $return['config']['shows'] = $nv_Request->get_typed_array('config_shows', 'post', 'title', []);
        $return['config']['show_clock'] = $nv_Request->get_bool('config_show_clock', 'post', false);

        $keys = ['phone', 'fax', 'email', 'other'];
        $return['config']['order_shows'] = $return['config']['limit_shows'] = [];
        foreach ($keys as $key) {
            $return['config']['order_shows'][$key] = $nv_Request->get_absint('config_' . $key . '_order', 'post', 0);
            $return['config']['limit_shows'][$key] = $nv_Request->get_absint('config_' . $key . '_limit', 'post', 0);
        }

        return $return;
    }

    /**
     * @param array $block_config
     * @return string
     */
    function nv_contact_default_info($block_config)
    {
        global $nv_Cache, $site_mods, $nv_Lang;

        $module = $block_config['module'];
        if (!isset($site_mods[$module])) {
            return '';
        }
        [$block_theme, $dir] = get_block_tpl_dir('block.contact_default.tpl', true, $module);
        $department = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department WHERE id=' . $block_config['departmentid'] . ' AND act=1', 'id', $module);
        if (empty($department) or empty($dir)) {
            return '';
        }
        $department = $department[$block_config['departmentid']];

        $nv_Lang->loadModule($site_mods[$module]['module_file'], loadtmp: true);

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir($dir);
        $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
        $tpl->registerPlugin('modifier', 'min', 'min');
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('TEMPLATE', $block_theme);
        $tpl->assign('BCONFIG', $block_config);
        $tpl->assign('DATETIME_FORMAT', nv_region_config('date_short') . ' ' . nv_region_config('time_short'));

        $icons = [];

        // Điện thoại
        if (in_array('phone', $block_config['shows'], true)) {
            $limit = $block_config['limit_shows']['phone'];
            $department['phone'] = nv_parse_phone($department['phone']);
            $stt = 0;

            foreach ($department['phone'] as $num) {
                $icons['phone'][$stt]['type'] = 'phone';
                $icons['phone'][$stt]['title'] = $nv_Lang->getModule('phone');
                $icons['phone'][$stt]['value'] = $num[0];
                if (count($num) == 2) {
                    $icons['phone'][$stt]['link'] = 'tel:' . $num[1];
                }

                $stt++;
                if ($limit > 0 and $stt >= $limit) {
                    break;
                }
            }
        }

        // Fax
        if (in_array('fax', $block_config['shows'], true) and !empty($department['fax'])) {
            $limit = $block_config['limit_shows']['fax'];
            $department['fax'] = nv_parse_phone($department['fax']);
            $stt = 0;

            foreach ($department['fax'] as $num) {
                $icons['fax'][$stt]['type'] = 'fax';
                $icons['fax'][$stt]['title'] = $nv_Lang->getModule('fax');
                $icons['fax'][$stt]['value'] = $num[0];
                if (count($num) == 2) {
                    $icons['fax'][$stt]['link'] = 'tel:' . $num[1];
                }

                $stt++;
                if ($limit > 0 and $stt >= $limit) {
                    break;
                }
            }
        }

        // Email
        if (in_array('email', $block_config['shows'], true) and !empty($department['email'])) {
            $stt = 0;
            $limit = $block_config['limit_shows']['email'];
            $emails = array_map('trim', explode(',', $department['email']));

            foreach ($emails as $email) {
                if (empty($email)) {
                    continue;
                }
                $icons['email'][$stt]['type'] = 'email';
                $icons['email'][$stt]['title'] = $nv_Lang->getModule('email');
                $icons['email'][$stt]['value'] = $email;
                $icons['email'][$stt]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $department['alias'];

                $stt++;
                if ($limit > 0 and $stt >= $limit) {
                    break;
                }
            }
        }

        // Liên hệ khác
        if (in_array('other', $block_config['shows'], true) and !empty($department['others'])) {
            $others = json_decode($department['others'], true);
            if (!empty($others)) {
                $limit = $block_config['limit_shows']['other'];
                $stt = 0;

                foreach ($others as $key => $value) {
                    if (empty($value)) {
                        continue;
                    }
                    $key = nv_strtolower($key);

                    if (strtolower($key) == 'skype') {
                        $ss = array_map('trim', explode(',', $value));
                        foreach ($ss as $s) {
                            if (empty($s)) {
                                continue;
                            }
                            $icons['other'][$key][$stt]['type'] = $key;
                            $icons['other'][$key][$stt]['title'] = nv_ucfirst($key);
                            $icons['other'][$key][$stt]['value'] = $s;
                            $icons['other'][$key][$stt]['link'] = 'skype:' . $s . '?chat';
                            $stt++;
                            if ($limit > 0 and $stt >= $limit) {
                                break;
                            }
                        }
                        continue;
                    }

                    if (strtolower($key) == 'viber') {
                        $ss = array_map('trim', explode(',', $value));
                        foreach ($ss as $s) {
                            if (empty($s)) {
                                continue;
                            }
                            $icons['other'][$key][$stt]['type'] = $key;
                            $icons['other'][$key][$stt]['title'] = nv_ucfirst($key);
                            $icons['other'][$key][$stt]['value'] = $s;
                            $icons['other'][$key][$stt]['link'] = 'viber://pa?chatURI=' . $s;
                            $stt++;
                            if ($limit > 0 and $stt >= $limit) {
                                break;
                            }
                        }
                        continue;
                    }

                    if (strtolower($key) == 'whatsapp') {
                        $ss = array_map('trim', explode(',', $value));
                        foreach ($ss as $s) {
                            if (empty($s)) {
                                continue;
                            }
                            $icons['other'][$key][$stt]['type'] = $key;
                            $icons['other'][$key][$stt]['title'] = nv_ucfirst($key);
                            $icons['other'][$key][$stt]['value'] = $s;
                            $icons['other'][$key][$stt]['link'] = 'https://wa.me/' . $s;
                            $stt++;
                            if ($limit > 0 and $stt >= $limit) {
                                break;
                            }
                        }
                        continue;
                    }

                    if (strtolower($key) == 'zalo') {
                        $ss = array_map('trim', explode(',', $value));
                        foreach ($ss as $s) {
                            if (empty($s)) {
                                continue;
                            }
                            $icons['other'][$key][$stt]['type'] = $key;
                            $icons['other'][$key][$stt]['title'] = nv_ucfirst($key);
                            $icons['other'][$key][$stt]['value'] = $s;
                            $icons['other'][$key][$stt]['link'] = 'https://zalo.me/' . $s;
                            $stt++;
                            if ($limit > 0 and $stt >= $limit) {
                                break;
                            }
                        }
                        continue;
                    }

                    $icons['other'][$key][$stt]['type'] = $key;
                    $icons['other'][$key][$stt]['title'] = nv_ucfirst($key);
                    $icons['other'][$key][$stt]['value'] = $value;

                    $stt++;
                    if ($limit > 0 and $stt >= $limit) {
                        break;
                    }
                }
            }
        }
        if (empty($icons)) {
            return '';
        }
        // Sắp xếp
        $order_shows = $block_config['order_shows'];
        uksort($icons, function ($a, $b) use ($order_shows) {
            $orderA = $order_shows[$a] ?? PHP_INT_MAX;
            $orderB = $order_shows[$b] ?? PHP_INT_MAX;
            return $orderA - $orderB;
        });
        $array_icons = [];
        foreach ($icons as $key => $values) {
            if ($key == 'other') {
                foreach ($values as $value) {
                    $array_icons = array_merge($array_icons, $value);
                }
            } else {
                $array_icons = array_merge($array_icons, $values);
            }
        }
        unset($icons);

        $tpl->assign('ICONS', $array_icons);
        $content = $tpl->fetch('block.contact_default.tpl');
        $nv_Lang->changeLang();
        return $content;
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_default_info($block_config);
}
