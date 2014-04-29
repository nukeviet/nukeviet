<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( defined( 'NV_COMM_ID' ) )
{
	if( $module_config[$module_name]['activecomm'] )
	{
		// Kiểm tra quyền xem
		$view = intval( $module_config[$module_name]['view_comm'] );
		if( $view == 3 )
		{
			// Quyền hạn xem bình luận theo bài viết
			$view = ( defined( 'NV_COMM_ALLOWED' ) ) ? NV_COMM_ALLOWED : $module_config[$module_name]['setcomm'];
		}
		if( $view == 1 or ( $view == 2 and defined( 'NV_IS_USER' ) ) )
		{
			$view = 1;
		}
		else
		{
			$view = 0;
		}

		// Kiểm tra quyền đăng bình luận
		$allowed = intval( $module_config[$module_name]['allowed_comm'] );
		if( $allowed == 3 )
		{
			// Quyền hạn đăng bình luận theo bài viết
			$allowed = ( defined( 'NV_COMM_ALLOWED' ) ) ? NV_COMM_ALLOWED : $module_config[$module_name]['setcomm'];
		}
		if( $allowed == 1 or ( $allowed == 2 and defined( 'NV_IS_USER' ) ) )
		{
			$allowed = 1;
		}
		else
		{
			$allowed = 0;
		}
		$area = ( defined( 'NV_COMM_AREA' ) ) ? NV_COMM_AREA : 0;
		define( 'NV_COMM_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=comment&module=' . $module_name . '&area=' . $area . '&id=' . NV_COMM_ID . '&view=' . $view . '&allowed=' . $allowed . '&checkss=' . md5( $module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $view . '-' . $allowed . '-' . NV_CACHE_PREFIX ) );
	}
}