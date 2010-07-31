<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 22:5
 */

if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$page_title = $lang_module['site_configs_info'];
$info = array();

if ( defined( 'NV_IS_GODADMIN' ) )
{
    $global_config['version'] .= "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=settings&amp;" . NV_OP_VARIABLE . "=checkupdate\">" . $lang_module['checkversion'] . "</a>";
}
$info[] = array(  //
    'caption' => $lang_module['site_configs_info'], //
'field' => array(  //
    array(  //
    'key' => $lang_module['site_domain'], //
'value' => NV_MY_DOMAIN  //
), //
array(  //
    'key' => $lang_module['site_url'], //
'value' => $global_config['site_url']  //
), //
array(  //
    'key' => $lang_module['site_root'], //
'value' => NV_ROOTDIR  //
), //
array(  //
    'key' => $lang_module['site_script_path'], //
'value' => $nv_Request->base_siteurl  //
), //
array(  //
    'key' => $lang_module['site_cookie_domain'], //
'value' => $global_config['cookie_domain']  //
), //
array(  //
    'key' => $lang_module['site_cookie_path'], //
'value' => $global_config['cookie_path']  //
), //
array(  //
    'key' => $lang_module['site_session_path'], //
'value' => $sys_info['sessionpath']  //
), //
array(  //
    'key' => $lang_module['site_timezone'], //
'value' => NV_SITE_TIMEZONE_NAME . ( NV_SITE_TIMEZONE_GMT_NAME != NV_SITE_TIMEZONE_NAME ? " (" . NV_SITE_TIMEZONE_GMT_NAME . ")" : "" )  //
)  //
)  //
);

$info[] = array(  //
    'caption' => $lang_module['server_configs_info'], //
'field' => array(  //
    array(  //
    'key' => $lang_module['version'], //
'value' => $global_config['version']  //
), //
array(  //
    'key' => $lang_module['server_phpversion'], //
'value' => ( PHP_VERSION != '' ? PHP_VERSION : phpversion() )  //
), //
array(  //
    'key' => $lang_module['server_api'], //
'value' => ( function_exists( 'apache_get_version' ) ? apache_get_version() . ', ' : ( nv_getenv( 'SERVER_SOFTWARE' ) != '' ? nv_getenv( 'SERVER_SOFTWARE' ) . ', ' : '' ) ) . ( PHP_SAPI != '' ? PHP_SAPI : php_sapi_name() )  //
), //
array(  //
    'key' => $lang_module['server_phpos'], //
'value' => $sys_info['os']  //
), //
array(  //
    'key' => $lang_module['server_mysqlversion'], //
'value' => $db->sql_version  //
)  //
)  //
);

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
foreach ( $info as $if )
{
    $xtpl->assign( 'CAPTION', $if['caption'] );
    foreach ( $if['field'] as $key => $field )
    {
        $xtpl->assign( 'CLASS', ( $key % 2 ) ? " class=\"second\"" : "" );
        $xtpl->assign( 'KEY', $field['key'] );
        $xtpl->assign( 'VALUE', $field['value'] );
        $xtpl->parse( 'main.loop' );
    }
    $xtpl->parse( 'main' );
}
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>