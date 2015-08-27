<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-10-2010 14:43
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'notification_reset', 'post' ) )
{
	$db->query( 'UPDATE ' . NV_NOTIFICATION_GLOBALTABLE . ' SET view=1 WHERE view=0' );
	die();
}

if( $nv_Request->isset_request( 'notification_get', 'get' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$last_time_call = $nv_Request->get_int( 'timestamp', 'get', 0 );
	$last_time = 0;
	$count = 0;
	$return = array();

	$result = $db->query( 'SELECT add_time FROM ' . NV_NOTIFICATION_GLOBALTABLE . ' WHERE language="' . NV_LANG_DATA . '" AND area=1 AND view=0 ORDER BY id DESC' );
	$count = $result->rowCount();
	if( $result )
	{
		$last_time = $result->fetchColumn();
	}

	if ( $last_time > $last_time_call )
	{
	    $return = array(
	        'data_from_file' => $count,
	        'timestamp' => $last_time
	    );
	}
	$json = json_encode( $return );
	echo $json;
	die();
}

$contents = '';
$per_page = 20;
$page = $nv_Request->get_int( 'page', 'get', 1 );

if( $page == 1 )
{
	$contents = $lang_module['notification_empty'];
}
$array_data = array();
$db->sqlreset()
  ->select( '*' )
  ->from( NV_NOTIFICATION_GLOBALTABLE )
  ->where( 'language = "' . NV_LANG_DATA . '" AND area = 1 OR area = 2' )
  ->order( 'id DESC' )
  ->limit( $per_page )
  ->offset( ($page - 1) * $per_page );
$_query = $db->query( $db->sql() );

while( $row = $_query->fetch() )
{
	$array_data[$row['id']] = $row;
}

if( ! empty( $array_data ) )
{
	$xtpl = new XTemplate( 'notification_load.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/siteinfo/' );
	$xtpl->assign( 'LANG', $lang_module );

	$i = 0;
	foreach( $array_data as $data )
	{
		$mod = $data['module'];
		if( isset( $admin_mods[$mod] ) or isset( $site_mods[$mod] ) )
		{
			$data['content'] = !empty( $data['content'] ) ? unserialize( $data['content'] ) : '';

			// Hien thi thong bao tu cac module he thong
			if( $mod == 'modules' )
			{
				if( $data['type'] == 'auto_deactive_module' )
				{
					$data['title'] = sprintf( $lang_module['notification_module_auto_deactive'], $data['content']['custom_title'] );
					$data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'];
				}
			}

			if( $mod == 'settings' )
			{
				if( $data['type'] == 'auto_deactive_cronjobs' )
				{
					$cron_title = $db->query( 'SELECT ' . NV_LANG_DATA . '_cron_name FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id=' . $data['content']['cron_id'] )->fetchColumn();
					$data['title'] = sprintf( $lang_module['notification_cronjobs_auto_deactive'], $cron_title );
					$data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=cronjobs';
				}
			}

			// Hien thi tu cac module
			if( file_exists( NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/notification.php' ) ) // Hien thi thong bao tu cac module site
			{
				if( $data['send_from'] > 0 )
				{
					$user_info = $db->query( 'SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $data['send_from'] )->fetch();
					if( $user_info )
					{
						$data['send_from'] = nv_show_name_user( $user_info['first_name'], $user_info['last_name'], $user_info['username'] );
					}
					else
					{
						$data['send_from'] = $lang_global['level5'];
					}

					if( ! empty( $user_info['photo'] ) and file_exists( NV_ROOTDIR . '/' . $user_info['photo'] ) )
					{
						$data['photo'] = NV_BASE_SITEURL . $admin_info['photo'];
					}
					else
					{
						$data['photo'] = NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.png';
					}
				}
				else
				{
					$data['photo'] = NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.png';
					$data['send_from'] = $lang_global['level5'];
				}

				include NV_ROOTDIR . '/modules/' . $site_mods[$mod]['module_file'] . '/notification.php';
			}

			if( !empty( $data['title'] ) )
			{
				$data['add_time_iso'] = nv_date( DATE_ISO8601, $data['add_time'] );
				$data['add_time'] = nv_date( 'H:i d/m/Y', $data['add_time'] );

				$xtpl->assign( 'DATA', $data );
				$xtpl->parse( 'main.loop' );
			}
			$i++;
		}
	}
	if( $i > 0 )
	{
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';