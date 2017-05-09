<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */
 
if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$pid = $nv_Request->get_int('pid', 'get', 0);

//print_r($global_array_group[$pid]);die('pass');
if ($pid >= 0) {
    $cateid = $nv_Request->get_string('cid', 'get', '');
    $cateid = nv_base64_decode($cateid);
    $cateid = unserialize($cateid);
    $cateid = $cateid ? $cateid : array();
	$list_cat='';
	if($pid>0)
	{
		$sql="SELECT cateid FROM ".$db_config['prefix'] . "_" . $module_data . "_group_cateid WHERE `groupid`=".$pid;
		$result_cat = $db->query($sql);
		while (list($catid_i) = $result_cat->fetch(3)) {
			if(empty($list_cat))
			{
				$list_cat.=$catid_i;
			}
			else {
				$list_cat.=','.$catid_i;
			}
		}
	}
	if(!empty($list_cat))
	{
		$list_cat=' WHERE `catid` in ('.$list_cat.')';
	}
    $table = $db_config['prefix'] . "_" . $module_data . "_catalogs";

    $sql = "SELECT catid, parentid, " . NV_LANG_DATA . "_title, lev, numsubcat FROM " . $table . $list_cat." ORDER BY sort ASC";
    $result_cat = $db->query($sql);

    $contents .= '<select class="form-control" style="width: 500px; min-height: 150px" name="cateid[]" multiple="multiple">';
    while (list($catid_i, $parentid_i, $title_i, $lev_i, $numsubcat_i) = $result_cat->fetch(3)) {
        if ($numsubcat_i > 0 or $parentid_i == 0) {
            $xtitle_i = "";
            if ($lev_i > 0) {
                for ($i = 1; $i <= $lev_i; $i++) {
                    $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
            }
            $select = in_array($catid_i, $cateid) ? " selected=\"selected\"" : "";
            $contents .= '<option value="' . $catid_i . '" ' . $select . '>' . $xtitle_i . $title_i . '</option>';
        }
    }
    $contents .= '</select>';
} else {
    $contents = '';
}
include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
