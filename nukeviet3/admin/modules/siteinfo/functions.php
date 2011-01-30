<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 5:50
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

global $global_config, $sys_info;

$allow_func = array( 
    'main' 
);

if ( defined( 'NV_IS_SPADMIN' ) )
{
    if ( nv_function_exists( 'phpinfo' ) )
    {
        if ( ini_get( 'expose_php' ) == '1' || strtolower( ini_get( 'expose_php' ) ) == 'on' )
        {
            $submenu['system_info'] = $lang_module['site_configs_info'];
            $submenu['php_info_configuration'] = $lang_module['configuration_php'];
            $submenu['php_info_modules'] = $lang_module['extensions'];
            $submenu['php_info_environment'] = $lang_module['environment_php'];
            $submenu['php_info_variables'] = $lang_module['variables_php'];
            
            $allow_func[] = 'system_info';
            $allow_func[] = 'php_info_configuration';
            $allow_func[] = 'php_info_modules';
            $allow_func[] = 'php_info_environment';
            $allow_func[] = 'php_info_variables';
        }
    }
    $allow_func[] = 'checkchmod';
}

if ( defined( 'NV_IS_GODADMIN' ) )
{
    $allow_func[] = 'logs';
    $allow_func[] = 'logs_del';
    $submenu['logs'] = $lang_module['logs_title'];
}

if ( $module_name == "siteinfo" )
{
    $menu_top = array( 
        "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_siteinfo'] 
    );
    
    define( 'NV_IS_FILE_SITEINFO', true );
}

?>