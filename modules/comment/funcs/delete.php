<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if( ! defined( 'NV_IS_MOD_COMMENT' ) ) die( 'Stop!!!' );

$contents = 'ERR_' . $lang_module['comment_unsuccess'];

$cid = $nv_Request->get_int( 'cid', 'post' );
$checkss = $nv_Request->get_string( 'checkss', 'post' );

$session_id = session_id() . '_' . $global_config['sitekey'];

if( $cid > 0 and $checkss == md5( $cid . '_' . $session_id ) )
{
	$_sql = 'SELECT cid, module FROM ' . NV_PREFIXLANG . '_comments WHERE cid=' . $cid;
	$row = $db->query( $_sql )->fetch();
	if( isset( $row['cid'] ) )
	{
		$module = $row['module'];

		// Kiểm tra lại quyền xóa comment
		$is_delete = false;
		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$is_delete = true;
		}
		elseif( defined( 'NV_IS_MODADMIN' ) )
		{
			$adminscomm = explode( ',', $module_config[$module]['adminscomm'] );
			if( in_array( $admin_info['admin_id'], $adminscomm ) )
			{
				$is_delete = true;
			}
		}

		if( $is_delete )
		{
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE cid=' . $cid );
			$contents = 'OK_' . $cid;
		}
	}
}
include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';