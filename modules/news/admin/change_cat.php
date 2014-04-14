<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$catid = $nv_Request->get_int( 'catid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $catid;

list( $catid, $parentid, $numsubcat ) = $db->query( 'SELECT catid, parentid, numsubcat FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid  )->fetch( 3 );
if( $catid > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 and ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_cat_admin[$admin_id][$parentid] ) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1 ) ) )
	{
		$sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid!=' . $catid . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $new_vid . ' WHERE catid=' . $catid ;
		$db->query( $sql );

		nv_fix_cat_order();
		$content = 'OK_' . $parentid;
	}
	elseif( defined( 'NV_IS_ADMIN_MODULE' ) or ( isset( $array_cat_admin[$admin_id][$catid] ) and $array_cat_admin[$admin_id][$catid]['add_content'] == 1 ) )
	{
		if( $mod == 'inhome' and ( $new_vid == 0 or $new_vid == 1 ) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET inhome=' . $new_vid . ' WHERE catid=' . $catid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 10 )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET numlinks=' . $new_vid . ' WHERE catid=' . $catid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'newday' and $new_vid >= 0 and $new_vid <= 10 )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET newday=' . $new_vid . ' WHERE catid=' . $catid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'viewcat' and $nv_Request->isset_request( 'new_vid', 'post' ) )
		{
			$viewcat = $nv_Request->get_title( 'new_vid', 'post' );
			$array_viewcat = ( $numsubcat > 0 ) ? $array_viewcat_full : $array_viewcat_nosub;
			if( ! array_key_exists( $viewcat, $array_viewcat ) )
			{
				$viewcat = 'viewcat_page_new';
			}
			$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET viewcat= :viewcat WHERE catid=' . $catid  );
			$stmt->bindParam( ':viewcat', $viewcat, PDO::PARAM_STR );
			$stmt->execute();
			$content = 'OK_' . $parentid;
		}
	}
	nv_del_moduleCache( $module_name );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';