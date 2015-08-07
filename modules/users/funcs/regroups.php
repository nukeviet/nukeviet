<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

$page_title = $lang_module['in_group'];

$recomplete = false;

if( $global_config['allowuserpublic'] == 0 )
{
	$contents = user_info_exit( $lang_module['no_act'] );
	$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";
}
else
{
	$groups_list = nv_groups_list_pub();

	if( empty( $groups_list ) )
	{
		$contents = user_info_exit( $lang_module['no_set'] );
		$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";
	}
	else
	{
		$groups = $in_groups = array();

		$array_old_groups = array();
		$result_gru = $db->query( "SELECT group_id FROM " . NV_GROUPS_GLOBALTABLE . "_users WHERE userid=" . $user_info['userid'] );
		while( $row_gru = $result_gru->fetch() )
		{
			$array_old_groups[] = $row_gru['group_id'];
		}

		if( $nv_Request->get_string( 'save', 'post' ) != '' )
		{
			$_sl_groups = $nv_Request->get_typed_array( 'group', 'post', 'int' );

			$in_groups = array_intersect( $_sl_groups, array_keys( $groups_list ) );
			$in_groups_hiden = array_diff( $array_old_groups, array_keys( $groups_list ) );
			$in_groups = array_unique( array_merge( $in_groups, $in_groups_hiden ) );

			$in_groups_del = array_diff( $array_old_groups, $in_groups );
			if( ! empty( $in_groups_del ) )
			{
				foreach( $in_groups_del as $gid )
				{
					nv_groups_del_user( $gid, $user_info['userid'] );
				}
			}

			$in_groups_add = array_diff( $in_groups, $array_old_groups );
			if( ! empty( $in_groups_add ) )
			{
				foreach( $in_groups_add as $gid )
				{
					nv_groups_add_user( $gid, $user_info['userid'] );
				}
			}

			$db->query( "UPDATE " . NV_USERS_GLOBALTABLE . " SET in_groups='" . implode( ',', $in_groups ) . "' WHERE userid=" . $user_info['userid'] );

			$recomplete = true;
		}
		else
		{
			$_sl_groups = $array_old_groups;
		}

		foreach( $groups_list as $group_id => $grtl )
		{
			$groups[] = array(
				'id' => $group_id,
				'title' => $grtl,
				'checked' => ( in_array( $group_id, $_sl_groups ) ) ? " checked=\"checked\"" : ""
			);
		}

		$contents = nv_regroup_theme( $groups );
	}
	if( $recomplete )
	{
		$contents = user_info_exit( $lang_module['re_remove'] );
		$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op, true ) . "\" />";
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}