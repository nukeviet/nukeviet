<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$allow_func = ['main', 'notification'];
if (defined('NV_IS_GODADMIN')) {
    $submenu['system_info'] = $nv_Lang->getModule('site_configs_info');

    if (nv_function_exists('phpinfo')) {
        $submenu['php_info_configuration'] = $nv_Lang->getModule('configuration_php');
        $submenu['php_info_modules'] = $nv_Lang->getModule('extensions');
        $submenu['php_info_environment'] = $nv_Lang->getModule('environment_php');
        $submenu['php_info_variables'] = $nv_Lang->getModule('variables_php');

        $allow_func[] = 'php_info_configuration';
        $allow_func[] = 'php_info_modules';
        $allow_func[] = 'php_info_environment';
        $allow_func[] = 'php_info_variables';
    }

    $allow_func[] = 'system_info';
    $allow_func[] = 'checkchmod';
}

if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'logs';
    $submenu['logs'] = $nv_Lang->getModule('logs_title');
}
