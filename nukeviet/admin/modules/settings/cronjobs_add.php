<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:35
 */

if( ! defined( 'NV_IS_FILE_SETTINGS' ) ) die( 'Stop!!!' );

$error = "";

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$cron_name = filter_text_input( 'cron_name', 'post', '', 1 );
	$run_file = filter_text_input( 'run_file', 'post', '' );
	$run_func = filter_text_input( 'run_func_iavim', 'post', '' );
	$params = filter_text_input( 'params_iavim', 'post', '' );
	$interval = $nv_Request->get_int( 'interval_iavim', 'post', 0 );
	$del = $nv_Request->get_int( 'del', 'post', 0 );

	$min = $nv_Request->get_int( 'min', 'post', 0 );
	$hour = $nv_Request->get_int( 'hour', 'post', 0 );
	
	if( preg_match( "/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $nv_Request->get_string( 'start_date', 'post' ), $m ) )
	{
		$start_time = mktime( $hour, $min, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$start_time = NV_CURRENTTIME;
	}

	if( empty( $cron_name ) )
	{
		$error = $lang_module['cron_name_empty'];
	}
	elseif( ! empty( $run_file ) and ! file_exists( NV_ROOTDIR . '/includes/cronjobs/' . $run_file ) )
	{
		$error = $lang_module['file_not_exist'];
	}
	elseif( empty( $run_func ) or ! preg_match( $global_config['check_cron'], $run_func ) )
	{
		$error = $lang_module['func_name_invalid'];
	}
	else
	{
		if( ! empty( $run_file ) and preg_match( "/^([a-zA-Z0-9\-\_\.]+)\.php$/", $run_file ) and file_exists( NV_ROOTDIR . '/includes/cronjobs/' . $run_file ) )
		{
			if( ! defined( 'NV_IS_CRON' ) ) define( "NV_IS_CRON", true );
			require_once ( NV_ROOTDIR . '/includes/cronjobs/' . $run_file );
		}

		if( ! nv_function_exists( $run_func ) )
		{
			$error = $lang_module['func_name_not_exist'];
		}
		else
		{
			if( ! empty( $params ) )
			{
				$params = explode( ",", $params );
				$params = array_map( "trim", $params );
				$params = implode( ",", $params );
			}
			
			$sql = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` (`id`, `start_time`, `interval`, `run_file`, `run_func`, `params`, `del`, `is_sys`, `act`, `last_time`, `last_result`, `" . NV_LANG_INTERFACE . "_cron_name`) VALUES (
				NULL, " . $start_time . ", " . $interval . ", " . $db->dbescape( $run_file ) . ", 
				" . $db->dbescape( $run_func ) . ", " . $db->dbescape( $params ) . ", " . $del . ", 0, 1, 0, 0, " . $db->dbescape( $cron_name ) . ")";
			$id = $db->sql_query_insert_id( $sql );
			
			if( $id )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, 'log_cronjob_add', "id  " . $id, $admin_info['userid'] );
				
				$sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` where `lang`!='" . NV_LANG_INTERFACE . "'";
				$result = $db->sql_query( $sql );
				
				while( list( $lang_i ) = $db->sql_fetchrow( $result ) )
				{
					$sql = "UPDATE `" . NV_CRONJOBS_GLOBALTABLE . "` SET `" . $lang_i . "_cron_name`=" . $db->dbescape( $run_func ) . " WHERE `id`=" . $id;
					$db->sql_query( $sql );

				}
				
				Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cronjobs" );
				die();
			}
		}
	}
}
else
{
	$min = date( 'i', NV_CURRENTTIME );
	$hour = date( 'G', NV_CURRENTTIME );
	$start_time = NV_CURRENTTIME;
	$interval = 60;
	$cron_name = $run_file = $run_func = $params = "";
	$del = 0;
}

$contents = array();
$contents['is_error'] = ! empty( $error ) ? 1 : 0;
$contents['title'] = ! empty( $error ) ? $error : $lang_module['nv_admin_add_title'];
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cronjobs_add";
$contents['cron_name'] = array( $lang_module['cron_name'], $cron_name, 100 );

$filelist = nv_scandir( NV_ROOTDIR . '/includes/cronjobs', "/^([a-zA-Z0-9\_\.]+)\.php$/" );

$contents['run_file'] = array( $lang_module['run_file'], $lang_module['file_none'], $filelist, $run_file, $lang_module['run_file_info'] );
$contents['run_func'] = array( $lang_module['run_func'], $run_func, 255, $lang_module['run_func_info'] );
$contents['params'] = array( $lang_module['params'], $params, 255, $lang_module['params_info'] );
$contents['start_time'] = array( $lang_module['start_time'], $lang_module['day'], date( "d/m/Y", $start_time ) );
$contents['min'] = array( $lang_module['min'], $min );
$contents['hour'] = array( $lang_module['hour'], $hour );
$contents['interval'] = array( $lang_module['interval'], $interval, 11, $lang_module['min'], $lang_module['interval_info'] );
$contents['del'] = array( $lang_module['is_del'], $del );

$contents['submit'] = $lang_global['submit'];
$contents = call_user_func( "nv_admin_add_theme", $contents );

$page_title = $lang_global['mod_cronjobs'] . " -> " . $lang_module['nv_admin_add'];
$set_active_op = "cronjobs";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>