<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

function getgroup_ckhtml( $subgroupid_i, $array_groupid_in_row )
{
	global $module_name, $global_array_group;

	$contents_temp = '';
	if( ! empty( $subgroupid_i ) )
	{
		foreach( $subgroupid_i as $groupid_i )
		{
			$data_group = $global_array_group[$groupid_i];
			$ch = '';
			if( in_array( $groupid_i, $array_groupid_in_row ) )
			{
				$ch = ' checked="checked"';
			}

			$image = '';
			if( ! empty( $data_group['image'] ) and file_exists( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data_group['image'] ) )
			{
				$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data_group['image'];
				$image = '<img src="' . $image . '" style="margin-top: -3px; max-width: 16px; max-height: 16px" alt="' . $groupinfo_i['title'] . '" />';

			}
			$contents_temp .= '<label style="margin-right: 15px; line-height:25px"><input type="checkbox" name="groupids[]" value="' . $groupid_i . '"' . $ch . ' />' . $image . $data_group['title'] . '</label>';
		}
	}
	return $contents_temp;
}

$cid = $nv_Request->get_int( 'cid', 'get', 0 );
$inrow = $nv_Request->get_string( 'inrow', 'get', '' );
$inrow = nv_base64_decode( $inrow );
$array_groupid_in_row = unserialize( $inrow );
$contents_temp_cate = '';

if( $cid > 0 )
{
	$cid = GetParentCatFilter( $cid );

	$arr_groupid = array();
	$result = $db->query( 'SELECT t1.groupid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid t2 ON t1.groupid = t2.groupid WHERE t2.cateid = ' . $cid );
	while( list( $groupid ) = $result->fetch( 3 ) )
	{
		$arr_groupid[$groupid] = GetGroupidInParent( $groupid, 0, 1 );
	}

	foreach( $arr_groupid as $groupid_i => $subgroupid_i )
	{
		$data_group = $global_array_group[$groupid_i];

		$require = '';
		if( $data_group['is_require'] )
		{
			$require = ' <span class="require">(*)</span>';
		}
		$contents_temp_cate .= '<div class="row">';
		$contents_temp_cate .= '<div class="col-sm-4 text-right"><strong>' . $data_group['title'] . $require . '</strong></div>';
		$contents_temp_cate .= '<div class="col-sm-20">';
		if( $data_group['numsubgroup'] > 0 )
		{
			$contents_temp_cate .= getgroup_ckhtml( $subgroupid_i, $array_groupid_in_row );
		}
		$contents_temp_cate .= '</div>';
		$contents_temp_cate .= '</div>';
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents_temp_cate;
include NV_ROOTDIR . '/includes/footer.php';