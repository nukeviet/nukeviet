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
	$_sql = 'SELECT cid, module, id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid;
	$row = $db->query( $_sql )->fetch();
	if( isset( $row['cid'] ) )
	{
		$module = $row['module'];
		$id = $row['id'];

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
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid );
			$mod_info = $site_mods[$module];
			if( file_exists( NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php' ) )
			{
				$row = array();
				$row['module'] =  $module;
				$row['id'] = $id;
				include NV_ROOTDIR . '/modules/' . $mod_info['module_file'] . '/comment.php';
			}

			$contents = 'OK_' . $cid;
		}
	}
}
include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';