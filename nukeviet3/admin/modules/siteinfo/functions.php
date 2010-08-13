<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 5:50
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

unset( $page_title, $select_options );
global $global_config;

$menu_top = array( 
    "title" => $module_name, "module_file" => "", "custom_title" => $lang_global['mod_siteinfo'] 
);
if ( function_exists( 'phpinfo' ) and ! in_array( 'phpinfo', $sys_info['disable_functions'] ) )
{
    $submenu['main'] = $lang_module['site_configs_info'];
    $submenu['php_info_configuration'] = $lang_module['configuration_php'];
    $submenu['php_info_modules'] = $lang_module['extensions'];
    $submenu['php_info_environment'] = $lang_module['environment_php'];
    $submenu['php_info_variables'] = $lang_module['variables_php'];
}
$allow_func = array( 
    'main', 'php_info_configuration', 'php_info_modules', 'php_info_environment', 'php_info_variables' 
);
define( 'NV_IS_FILE_SITEINFO', true );

?>