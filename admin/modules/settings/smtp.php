<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['smtp_config'];
$smtp_encrypted_array = array();
$smtp_encrypted_array[0] = "None";
$smtp_encrypted_array[1] = "SSL";
$smtp_encrypted_array[2] = "TSL";

$array_config = array();
$errormess = "";
$array_config['mailer_mode'] = filter_text_input( 'mailer_mode', 'post', $global_config['mailer_mode'], 1, 255 );
$array_config['smtp_host'] = filter_text_input( 'smtp_host', 'post', $global_config['smtp_host'], 1, 255 );
$array_config['smtp_port'] = filter_text_input( 'smtp_port', 'post', $global_config['smtp_port'], 1, 255 );
$array_config['smtp_username'] = filter_text_input( 'smtp_username', 'post', $global_config['smtp_username'], 1, 255 );
$array_config['smtp_password'] = filter_text_input( 'smtp_password', 'post', $global_config['smtp_password'], 1, 255 );

if ( $nv_Request->isset_request( 'mailer_mode', 'post' ) )
{
    $array_config['smtp_ssl'] = $nv_Request->get_int( 'smtp_ssl', 'post', 0 );
}
else
{
    $array_config['smtp_ssl'] = intval( $global_config['smtp_ssl'] );
}

if ( $nv_Request->isset_request( 'mailer_mode', 'post' ) )
{
    foreach ( $array_config as $config_name => $config_value )
    {
        $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
        SET `config_value`=" . $db->dbescape_string( $config_value ) . " 
        WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
        AND `lang` = 'sys' AND `module`='global' 
        LIMIT 1" );
    }
	
    nv_save_file_config_global();
	
    if ( $array_config['smtp_ssl'] == 1 )
    {
        require_once ( NV_ROOTDIR . "/includes/core/phpinfo.php" );
        $array_phpmod = phpinfo_array( 8, 1 );
        if ( ! empty( $array_phpmod ) and ! array_key_exists( "openssl", $array_phpmod ) )
        {
            $errormess = $lang_module['smtp_error_openssl'];
        }
    }
	
    if ( empty( $errormess ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
        exit();
    }
}

$array_config['smtp_ssl_checked'] = ( $array_config['smtp_ssl'] == 1 ) ? ' checked="checked"' : '';

$array_config['mailer_mode_smtpt'] = ( $array_config['mailer_mode'] == "smtp" ) ? ' checked="checked"' : '';
$array_config['mailer_mode_sendmail'] = ( $array_config['mailer_mode'] == "sendmail" ) ? ' checked="checked"' : '';
$array_config['mailer_mode_phpmail'] = ( $array_config['mailer_mode'] == "" ) ? ' checked="checked"' : '';
$array_config['mailer_mode_smtpt_show'] = ( $array_config['mailer_mode'] == "smtp" ) ? "" : " style=\"display: none\" ";

$xtpl = new XTemplate( "smtp.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );

foreach ( $smtp_encrypted_array as $id => $value )
{
    $encrypted = array( "id" => $id, "value" => $value, "sl" => ( $global_config['smtp_ssl'] == $id ) ? ' selected="selected"' : '' );
	
    $xtpl->assign( 'EMCRYPTED', $encrypted );
    $xtpl->parse( 'smtp.encrypted_connection' );
}

if ( $errormess != "" )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'smtp.error' );
}

$xtpl->parse( 'smtp' );
$contents = $xtpl->text( 'smtp' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>