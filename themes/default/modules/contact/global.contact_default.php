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
        global $nv_Cache, $site_mods, $nv_Lang;

        if (!isset($site_mods[$module])) {
            return '';
        }

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

        $default_department['cd'] = [];
        if (!empty($default_department['phone'])) {
            $default_department['cd'][] = [
                'type' => 'phone',
                'value' => nv_parse_phone($default_department['phone'])
            ];
        }

        if (!empty($default_department['email'])) {
            $default_department['cd'][] = [
                'type' => 'email',
                'value' => array_map('trim', explode(',', $default_department['email']))
            ];
        }

        if (!empty($default_department['others'])) {
            $others = json_decode($default_department['others'], true);

            if (!empty($others)) {
                foreach ($others as $key => $value) {
                    if (!empty($value)) {
                        $_k = strtolower($key);
                        if (in_array($_k, ['skype', 'viber', 'whatsapp', 'zalo'], true)) {
                            $default_department['cd'][] = [
                                'type' => $_k,
                                'value' => array_map('trim', explode(',', $value))
                            ];
                        } else {
                            $default_department['cd'][] = [
                                'type' => ucfirst($key),
                                'value' => ['is_url' => nv_is_url($value), 'content' => $value]
                            ];
                        }
                    }
                }
            }
        }

        $stpl = new \NukeViet\Template\NVSmarty();
        $stpl->setTemplateDir(str_replace(DIRECTORY_SEPARATOR, '/', __DIR__));
        $stpl->assign('LANG', $nv_Lang);
        $stpl->assign('DATA', $default_department['cd']);
        $stpl->assign('MODULE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=' . $default_department['alias']);

        return $stpl->fetch('block.contact_default.tpl');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_contact_default_info($block_config['module']);
}
