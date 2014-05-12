<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$page_title = $lang_module['site_configs_info'];

$info = array();

$info['website'] = array(
	'caption' => $lang_module['site_configs_info'],
	'field' => array(
		array( 'key' => $lang_module['site_domain'], 'value' => NV_MY_DOMAIN ),
		array( 'key' => $lang_module['site_url'], 'value' => $global_config['site_url'] ),
		array( 'key' => $lang_module['site_root'], 'value' => NV_ROOTDIR ),
		array( 'key' => $lang_module['site_script_path'], 'value' => $nv_Request->base_siteurl ),
		array( 'key' => $lang_module['site_cookie_domain'], 'value' => $global_config['cookie_domain'] ),
		array( 'key' => $lang_module['site_cookie_path'], 'value' => $global_config['cookie_path'] ),
		array( 'key' => $lang_module['site_session_path'], 'value' => $sys_info['sessionpath'] ),
		array( 'key' => $lang_module['site_timezone'], 'value' => NV_SITE_TIMEZONE_NAME . ( NV_SITE_TIMEZONE_GMT_NAME != NV_SITE_TIMEZONE_NAME ? ' (' . NV_SITE_TIMEZONE_GMT_NAME . ')' : '' ) )
	)
);

if( defined( 'NV_IS_GODADMIN' ) )
{
	$global_config['version'] .= '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=checkupdate">' . $lang_module['checkversion'] . '</a>';
}

$info['server'] = array(
	'caption' => $lang_module['server_configs_info'],
	'field' => array(
		array( 'key' => $lang_module['version'], 'value' => $global_config['version'] ),
		array( 'key' => $lang_module['server_phpversion'], 'value' => ( PHP_VERSION != '' ? PHP_VERSION : phpversion() ) ),
		array( 'key' => $lang_module['server_api'], 'value' => ( nv_function_exists( 'apache_get_version' ) ? apache_get_version() . ', ' : ( nv_getenv( 'SERVER_SOFTWARE' ) != '' ? nv_getenv( 'SERVER_SOFTWARE' ) . ', ' : '' ) ) . ( PHP_SAPI != '' ? PHP_SAPI : php_sapi_name() ) ),
		array( 'key' => $lang_module['server_phpos'], 'value' => $sys_info['os'] ),
		array( 'key' => $lang_module['server_databaseversion'], 'value' => $db->getAttribute( PDO::ATTR_DRIVER_NAME ) . ' ' . $db->getAttribute( PDO::ATTR_SERVER_VERSION ) )
	)
);

$js = false;

if( defined( 'NV_IS_GODADMIN' ) and substr( $sys_info['os'], 0, 3 ) != 'WIN' )
{
	$js = true;

	$info['chmod'] = array(
		'caption' => $lang_module['chmod'],
		'field' => array(
			array( 'key' => NV_DATADIR, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_DATADIR ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_CACHEDIR, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_CACHEDIR ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_UPLOADS_DIR, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_UPLOADS_DIR ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_TEMP_DIR, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_TEMP_DIR ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/data_logs', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/dump_backup', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/error_logs', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/error_logs/errors256', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/error_logs/old', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/error_logs/tmp', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/ip_logs', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/ref_logs', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_LOGS_DIR . '/voting_logs', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
			array( 'key' => NV_FILES_DIR . '/css', 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_FILES_DIR . '/css' ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) ),
		)
	);
	if( $dh = opendir( NV_ROOTDIR . '/' . NV_CACHEDIR ) )
	{
		while( ( $modname = readdir( $dh ) ) !== false )
		{
			if( preg_match( '/^([a-z0-9\_]+)$/', $modname ) )
			{
				$info['chmod']['field'][] = array( 'key' => NV_CACHEDIR . '/' .$modname, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_SESSION_SAVE_PATH ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) );
			}
		}
		closedir( $dh );
	}
	if( NV_SESSION_SAVE_PATH != '' )
	{
		$info['chmod']['field'][] = array( 'key' => NV_SESSION_SAVE_PATH, 'value' => ( is_writable( NV_ROOTDIR . '/' . NV_SESSION_SAVE_PATH ) ? $lang_module['chmod_noneed'] : $lang_module['chmod_need'] ) );
	}
}

$xtpl = new XTemplate( 'system_info.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'LANG', $lang_module );

foreach( $info as $key => $if )
{
	$xtpl->assign( 'CAPTION', $if['caption'] );

	if( $key == 'chmod' )
	{
		$xtpl->assign( 'URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo&amp;' . NV_OP_VARIABLE . '=checkchmod' );
		$xtpl->parse( 'main.urlcap' );
	}
	else
	{
		$xtpl->parse( 'main.textcap' );
	}

	foreach( $if['field'] as $key => $field )
	{
		$xtpl->assign( 'KEY', $field['key'] );
		$xtpl->assign( 'VALUE', $field['value'] );
		$xtpl->parse( 'main.loop' );
	}

	$xtpl->parse( 'main' );
}

$contents = $xtpl->text( 'main' );

if( $js )
{
	$xtpl->parse( 'js' );
	$contents .= $xtpl->text( 'js' );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';