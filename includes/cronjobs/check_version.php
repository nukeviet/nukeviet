<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11-7-2011 9:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

/**
 * cron_auto_check_version()
 *
 * @return
 */
function cron_auto_check_version()
{
	global $nv_Request, $global_config, $client_info;

	$admin_cookie = $nv_Request->get_bool( 'admin', 'session', false );

	if( ! empty( $admin_cookie ) and $global_config['autocheckupdate'] )
	{
		require NV_ROOTDIR . '/includes/core/admin_access.php';
		require NV_ROOTDIR . '/includes/core/is_admin.php';

		if( defined( 'NV_IS_GODADMIN' ) )
		{
			include_once NV_ROOTDIR . '/includes/core/admin_functions.php';
			nv_geVersion( $global_config['autoupdatetime'] * 3600 );
		}
	}

	return true;
}