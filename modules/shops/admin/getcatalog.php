<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$pid = $nv_Request->get_int( 'pid', 'get', 0 );

if( $pid == 0 )
{
	$cateid = $nv_Request->get_int( 'cid', 'get', 0 );
	$table = $db_config['prefix'] . "_" . $module_data . "_catalogs";

	$sql = "SELECT catid, " . NV_LANG_DATA . "_title, lev, numsubcat FROM " . $table . " ORDER BY sort ASC";
	$result_cat = $db->query( $sql );

	$contents .= $lang_module['group_of'] . ' <select class="form-control" name="cateid">';
	$contents .= '<option value="0">' . $lang_module['group_of_none'] . '</option>';
	while( list( $catid_i, $title_i, $lev_i, $numsubcat_i ) = $result_cat->fetch( 3 ) )
	{
		$xtitle_i = "";
		if( $lev_i > 0 )
		{
			for( $i = 1; $i <= $lev_i; $i++ )
			{
				$xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
		}
		$select = ( $catid_i == $cateid ) ? " selected=\"selected\"" : "";
		$contents .= '<option value="' . $catid_i . '" ' . $select . '>' . $xtitle_i . $title_i . '</option>';
	}
	$contents .= '</select>';
}
else
{
	$contents = '';
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';