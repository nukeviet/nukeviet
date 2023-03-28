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

define('NV_MOD_TABLE', NV_PREFIXLANG . '_' . $module_data);

/**
 * parse_admins()
 * 
 * @param mixed $admin_list 
 * @return mixed 
 */
function parse_admins($admin_list)
{
    $admins = json_decode($admin_list, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return $admins;
    }

    $adms = explode(';', $admin_list);
    $admins = [];
    foreach ($adms as $adm) {
        $adm = array_map('intval', explode('/', $adm));
        if (!empty($adm[1])) {
            !isset($admins['view_level']) && $admins['view_level'] = [];
            $admins['view_level'][] = (int) $adm[0];
        }
        if (!empty($adm[2])) {
            !isset($admins['reply_level']) && $admins['reply_level'] = [];
            $admins['reply_level'][] = (int) $adm[0];
        }
        if (!empty($adm[3])) {
            !isset($admins['obt_level']) && $admins['obt_level'] = [];
            $admins['obt_level'][] = (int) $adm[0];
        }
    }

    return $admins;
}
