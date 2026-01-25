<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\users\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Navs
{
    /**
     * Lấy thanh menu trên cơ sở lược bỏ các mục không cần thiết
     *
     * @param array $funcs Truyền vào $module_info['funcs']
     * @return array $module_info['funcs'] sau khi lọc
     */
    public static function getNavs(array $funcs): array
    {
        global $global_config;

        $ignore_names = ['avatar', 'groups', 'verify-password', 'security-privacy', 'datadeletion'];
        if (empty($global_config['allowuserreg'])) {
            $ignore_names[] = 'register';
        }
        if (!defined('NV_IS_USER')) {
            $ignore_names[] = 'main';
        }
        $ignore_names = array_flip($ignore_names);
        $funcs = array_diff_key($funcs, $ignore_names);
        return array_filter($funcs, function($value, $key) {
            if (empty($value['show_func']) or (empty($value['in_submenu']) and $key != 'main')) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
