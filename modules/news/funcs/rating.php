<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (! defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}
if (! defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$contents = '';
$array_point = array( 1, 2, 3, 4, 5 );

$id = $nv_Request->get_int('id', 'post', 0);
$point = $nv_Request->get_int('point', 'post', 0);
$checkss = $nv_Request->get_title('checkss', 'post');

$time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $id, 'session', 0);

if ($id > 0 and in_array($point, $array_point) and $checkss == md5($id . NV_CHECK_SESSION)) {
    if (! empty($time_set)) {
        die($lang_module['rating_error2'] . '|0|0');
    }

    $nv_Request->set_Session($module_data . '_' . $op . '_' . $id, NV_CURRENTTIME);
    $query = $db->query("SELECT listcatid, allowed_rating, total_rating, click_rating FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id = " . $id . " AND status=1");
    $row = $query->fetch();
    if (isset($row['allowed_rating']) and $row['allowed_rating'] == 1) {
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET total_rating=total_rating+" . $point . ", click_rating=click_rating+1 WHERE id=" . $id;
        $db->query($query);
        $array_catid = explode(',', $row['listcatid']);
        foreach ($array_catid as $catid_i) {
            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " SET total_rating=total_rating+" . $point . ", click_rating=click_rating+1 WHERE id=" . $id;
            $db->query($query);
        }
        $contents = sprintf($lang_module['stringrating'], $row['total_rating'] + $point, $row['click_rating'] + 1);
        die($contents . '|' . round(($row['total_rating'] + $point) / ($row['click_rating'] + 1), 1) . '|' . ($row['click_rating'] + 1));
    }
}

die($lang_module['rating_error1'] . '|0|0');