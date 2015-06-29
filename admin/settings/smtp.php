<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['smtp_config'];
$smtp_encrypted_array = array();
$smtp_encrypted_array[0] = 'None';
$smtp_encrypted_array[1] = 'SSL';
$smtp_encrypted_array[2] = 'TSL';

$array_config = array();
$errormess = '';
$array_config['mailer_mode'] = nv_substr( $nv_Request->get_title( 'mailer_mode', 'post', $global_config['mailer_mode'], 1 ), 0, 255 );
$array_config['smtp_host'] = nv_substr( $nv_Request->get_title( 'smtp_host', 'post', $global_config['smtp_host'], 1 ), 0, 255 );
$array_config['smtp_port'] = nv_substr( $nv_Request->get_title( 'smtp_port', 'post', $global_config['smtp_port'], 1 ), 0, 255 );
$array_config['smtp_username'] = nv_substr( $nv_Request->get_title( 'smtp_username', 'post', $global_config['smtp_username'] ), 0, 255 );
$array_config['smtp_password'] = nv_substr( $nv_Request->get_title( 'smtp_password', 'post', $global_config['smtp_password'] ), 0, 255 );

if( $nv_Request->isset_request( 'mailer_mode', 'post' ) )
{
	$array_config['smtp_ssl'] = $nv_Request->get_int( 'smtp_ssl', 'post', 0 );
}
else
{
	$array_config['smtp_ssl'] = intval( $global_config['smtp_ssl'] );
}

if( $nv_Request->isset_request( 'mailer_mode', 'post' ) )
{
	$smtp_password = $array_config['smtp_password'];
	$array_config['smtp_password'] = nv_base64_encode( $crypt->aes_encrypt( $smtp_password ) );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name" );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR, 30 );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}
	nv_del_moduleCache( 'settings' );

	if( $array_config['smtp_ssl'] == 1 and $array_config['mailer_mode'] == 'smtp' )
	{
		require_once NV_ROOTDIR . '/includes/core/phpinfo.php';
		$array_phpmod = phpinfo_array( 8, 1 );
		if( ! empty( $array_phpmod ) and ! array_key_exists( 'openssl', $array_phpmod ) )
		{
			$errormess = $lang_module['smtp_error_openssl'];
		}
	}

	if( empty( $errormess ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
		exit();
	}
	$array_config['smtp_password'] = $smtp_password;
}

$array_config['smtp_ssl_checked'] = ( $array_config['smtp_ssl'] == 1 ) ? ' checked="checked"' : '';

$array_config['mailer_mode_smtpt'] = ( $array_config['mailer_mode'] == 'smtp' ) ? ' checked="checked"' : '';
$array_config['mailer_mode_sendmail'] = ( $array_config['mailer_mode'] == 'sendmail' ) ? ' checked="checked"' : '';
$array_config['mailer_mode_phpmail'] = ( $array_config['mailer_mode'] == '' ) ? ' checked="checked"' : '';
$array_config['mailer_mode_smtpt_show'] = ( $array_config['mailer_mode'] == 'smtp' ) ? '' : ' style="display: none" ';

$xtpl = new XTemplate( 'smtp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
foreach( $smtp_encrypted_array as $id => $value )
{
	$encrypted = array(
		'id' => $id,
		'value' => $value,
		'sl' => ( $global_config['smtp_ssl'] == $id ) ? ' selected="selected"' : ''
	);

	$xtpl->assign( 'EMCRYPTED', $encrypted );
	$xtpl->parse( 'smtp.encrypted_connection' );
}

if( $errormess != '' )
{
	$xtpl->assign( 'ERROR', $errormess );
	$xtpl->parse( 'smtp.error' );
}

$xtpl->parse( 'smtp' );
$contents = $xtpl->text( 'smtp' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';