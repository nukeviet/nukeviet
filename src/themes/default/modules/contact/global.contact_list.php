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
     * @param array $block_config
     * @return string|void
     */
    function nv_contact_list_info($block_config)
    {
        global $nv_Cache, $site_mods, $global_config, $module_name, $nv_Lang;

        $module = $block_config['module'];

        if (!isset($site_mods[$module]) or $module == $module_name) {
            return '';
        }

        $cache_file = 'departments_block' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module, $cache_file)) != false) {
            $departments = json_decode($cache, true);
        } else {
            $departments = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . '_department ORDER BY weight', 'id', $module);
            if (!empty($departments)) {
                $keys = array_keys($departments);
                foreach ($keys as $key) {
                    if (!$departments[$key]['act']) {
                        unset($departments[$key]);
                        continue;
                    }

                    $departments[$key]['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=contact&amp;' . NV_OP_VARIABLE . '=' . $departments[$key]['alias'];

                    if (!empty($departments[$key]['image'])) {
                        if (file_exists(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $departments[$key]['image'])) {
                            $sizes = getimagesize(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $departments[$key]['image']);
                            $departments[$key]['image'] = NV_BASE_SITEURL . NV_MOBILE_FILES_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $departments[$key]['image'];
                        } else {
                            $sizes = getimagesize(NV_UPLOADS_REAL_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $departments[$key]['image']);
                            $departments[$key]['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/' . $departments[$key]['image'];
                        }
                        $departments[$key]['imagewidth'] = $sizes[0];
                        $departments[$key]['imageheight'] = $sizes[1];
                    }

                    $departments[$key]['cd'] = [];
                    if (!empty($departments[$key]['phone'])) {
                        $departments[$key]['cd'][] = [
                            'type' => 'phone',
                            'value' => nv_parse_phone($departments[$key]['phone'])
                        ];
                    }

                    if (!empty($departments[$key]['email'])) {
                        $departments[$key]['cd'][] = [
                            'type' => 'email',
                            'value' => array_map('trim', explode(',', $departments[$key]['email']))
                        ];
                    }

                    if (!empty($departments[$key]['others'])) {
                        $others = json_decode($departments[$key]['others'], true);
                        if (!empty($others)) {
                            foreach ($others as $k => $value) {
                                if (!empty($value)) {
                                    $_k = strtolower($k);
                                    if (in_array($_k, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                                        $departments[$key]['cd'][] = [
                                            'type' => $_k,
                                            'value' => array_map('trim', explode(',', $value))
                                        ];
                                    } else {
                                        $departments[$key]['cd'][] = [
                                            'type' => ucfirst($k),
                                            'value' => ['is_url' => nv_is_url($value), 'content' => $value]
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            empty($departments) && $departments = [];
            $cache = json_encode($departments);
            $nv_Cache->setItem($module, $cache_file, $cache);
        }

        if (empty($departments)) {
            return '';
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir($block_config['real_path'] . '/smarty');
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('DEPARTMENTS', $departments);

        return $stpl->fetch('block.contact_list.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_list_info($block_config);
}
