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

function getgroup_ckhtml($subgroupid_i, $array_groupid_in_row)
{
    global $module_name, $global_array_group;

    $contents_temp = '';
    if (! empty($subgroupid_i)) {
        foreach ($subgroupid_i as $groupid_i) {
            $data_group = $global_array_group[$groupid_i];
            $ch = '';
            if (in_array($groupid_i, $array_groupid_in_row)) {
                $ch = ' checked="checked"';
            }

            $image = '';
            if (! empty($data_group['image']) and file_exists(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data_group['image'])) {
                $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $data_group['image'];
                $image = '<img src="' . $image . '" style="margin-top: -3px; max-width: 16px; max-height: 16px" alt="' . $groupinfo_i['title'] . '" />';
            }
            $contents_temp .= '<label class="col-xs-24 col-sm-4"><input type="checkbox" name="groupids[]" value="' . $groupid_i . '"' . $ch . ' />' . $image . $data_group['title'] . '</label>';
        }
    }
    return $contents_temp;
}

$cid = $nv_Request->get_int('cid', 'get', 0);
$inrow = $nv_Request->get_string('inrow', 'get', '');
$array_groupid_in_row = array();
if (!empty($inrow)) {
    $inrow = nv_base64_decode($inrow);
    $array_groupid_in_row = unserialize($inrow);
}
$contents_temp_cate = '';

if ($cid > 0) {
    $cid = GetParentCatFilter($cid);

	$arr=array();
    $arr_groupid = array();
    $result = $db->query('SELECT t1.groupid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid t2 ON t1.groupid = t2.groupid WHERE t2.cateid = ' . $cid);
   while (list($groupid) = $result->fetch(3)) {
   		if($global_array_group[$groupid]['parentid']==0)
   		{
   			$arr_groupid[$groupid] = GetGroupidInParentGroup($groupid, 0, 1,$cid);
   		}
		else {
			$arr[$groupid]=$groupid;
		}
    }
    foreach ($arr_groupid as $key => $value) {
    	$dataarr=array();
        foreach ($arr as $keyar => $valuearr) {
        	if($value[$valuearr]==$valuearr) $dataarr[$valuearr]=$valuearr;
   		 }
		if(!empty($dataarr))
		{
			$arr_groupid[$key]=$dataarr;continue;

		}

    }

    foreach ($arr_groupid as $groupid_i => $subgroupid_i) {
        $data_group = $global_array_group[$groupid_i];

        $require = '';
        if ($data_group['is_require']) {
            $require = ' <span class="require">(*)</span>';
        }
        $contents_temp_cate .= '<div class="row">';
        $contents_temp_cate .= '<label class="col-sm-3 control-label"><strong>' . $data_group['title'] . $require . '</strong></label>';
        $contents_temp_cate .= '<div class="col-sm-21">';
        if ($data_group['numsubgroup'] > 0) {
            $contents_temp_cate .= getgroup_ckhtml($subgroupid_i, $array_groupid_in_row);
        }
        $contents_temp_cate .= '</div>';
        $contents_temp_cate .= '</div>';
    }

}

include NV_ROOTDIR . '/includes/header.php';
echo $contents_temp_cate;
include NV_ROOTDIR . '/includes/footer.php';
