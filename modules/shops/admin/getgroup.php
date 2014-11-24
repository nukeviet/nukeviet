<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

function getgroup_ckhtml( $data_group, $array_groupid_in_row, $pid )
{
	global $module_name;
	
	$contents_temp = '';
	if( ! empty( $data_group ) )
	{
		foreach( $data_group as $groupid_i => $groupinfo_i )
		{
			if( $groupinfo_i['parentid'] == $pid )
			{
				$xtitle_i = '';
				if( $groupinfo_i['lev'] > 0 )
				{
					for( $i = 1; $i <= $groupinfo_i['lev']; $i++ )
					{
						$xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					}
				}
				$ch = '';
				if( in_array( $groupid_i, $array_groupid_in_row ) )
				{
					$ch = ' checked="checked"';
				}
				
				$image = '';
				if( ! empty( $groupinfo_i['image'] ) )
				{
					$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $groupinfo_i['image'];
					$image = '<img src="' . $image . '" style="margin-top: -3px; max-width: 16px; max-height: 16px" alt="' . $groupinfo_i['title'] . '" />';
				}
				
				$contents_temp .= '<div class="row"><label>' . $xtitle_i . '<input type="checkbox" name="groupids[]" value="' . $groupid_i . '"' . $ch . ' />' . $image . $groupinfo_i['title'] . '<label></div>';
				if( $groupinfo_i['numsubgroup'] > 0 )
				{
					$contents_temp .= getgroup_ckhtml( $data_group, $array_groupid_in_row, $groupid_i );
				}
			}
		}
	}
	return $contents_temp;
}

$cid = $nv_Request->get_int( 'cid', 'get', 0 );
$inrow = $nv_Request->get_string( 'inrow', 'get', '' );
$inrow = nv_base64_decode( $inrow );
$array_groupid_in_row = unserialize( $inrow );

$array_cat = GetCatidInChild( $cid );

$sql = 'SELECT groupid, parentid, cateid, ' . NV_LANG_DATA . '_title AS title, lev, numsubgroup, image FROM ' . $db_config['prefix'] . '_' . $module_data . '_group ORDER BY sort ASC';
$result_group = $db->query( $sql );

$data_group = array();
while( $row = $result_group->fetch() )
{
	$data_group[$row['groupid']] = $row;
}

$contents_temp_none = '';
$contents_temp_cate = '';
foreach( $data_group as $groupid_i => $groupinfo_i )
{
	$image = '';
	if( ! empty( $groupinfo_i['image'] ) )
	{
		$image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $groupinfo_i['image'];
		$image = '<img src="' . $image . '" style="margin-top: -3px; max-width: 16px; max-height: 16px" alt="' . $groupinfo_i['title'] . '" />';
	}
	
	if( $groupinfo_i['parentid'] == 0 && $groupinfo_i['cateid'] == 0 )
	{
		$ch = '';
		if( in_array( $groupid_i, $array_groupid_in_row ) )
		{
			$ch = ' checked="checked"';
		}
		$contents_temp_none .= '<div class="row"><label><input type="checkbox" name="groupids[]" value="' . $groupid_i . '"' . $ch . ' />' . $image . '<strong>' . $groupinfo_i['title'] . '</strong></li></div>';
		if( $groupinfo_i['numsubgroup'] > 0 )
		{
			$contents_temp_none .= getgroup_ckhtml( $data_group, $array_groupid_in_row, $groupid_i );
		}
	}
	elseif( $groupinfo_i['parentid'] == 0 && in_array( $groupinfo_i['cateid'], $array_cat ) )
	{
		$ch = '';
		if( in_array( $groupid_i, $array_groupid_in_row ) )
		{
			$ch = ' checked="checked"';
		}
		$contents_temp_cate .= '<div class="row"><label><input type="checkbox" name="groupids[]" value="' . $groupid_i . '"' . $ch . ' />' . $image . $groupinfo_i['title'] . '</label></div>';
		if( $groupinfo_i['numsubgroup'] > 0 )
		{
			$contents_temp_cate .= getgroup_ckhtml( $data_group, $array_groupid_in_row, $groupid_i );
		}
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents_temp_none . '<hr />' . $contents_temp_cate;
include NV_ROOTDIR . '/includes/footer.php';