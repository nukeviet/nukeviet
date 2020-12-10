<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM
 */

if (!defined('NV_IS_MOD_OCHU')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// lay thong tin
$id = $nv_Request->get_int('id', 'get', 0);

// xu li
$sql_data = "SELECT * FROM " . NV_PREFIXLANG . "_{$module_data} WHERE id = {$id}";
$data = $db->query($sql_data);
$row = $data->fetch();

// xu li khoa hang doc
$key = explode("|", $row['key']);

// xu li goi y
$suggest = explode("|", $row['quession']);

// xu li du lieu
$data = explode("|", $row['content']);

// kiem tra dap an
$do = $nv_Request->get_title('do', 'post', '');
$testarray = array();
$info = array();
foreach ($data as $stt => $tr) {
    $a = '';
    $tddata = explode(",", $tr);
    $num = count($tddata);

    // khoi tao gia tri true
    $info[$stt] = true;
    foreach ($tddata as $thisstt => $thistd) {
        $id = $nv_Request->get_title("tr_" . $stt . "_td_" . $thisstt, 'get,post', '');
        if ($thisstt < ($num - 1)) {
            $a .= $id . ",";
        } else {
            $a .= $id;
        }

        if ($id != $thistd) {
            $info[$stt] = false;
        }

    }
    $testarray[$stt] = $a;
}
// goi ham
$contents = nv_theme_onbai_test($key, $suggest, $testarray, $info, $do);

include NV_ROOTDIR . "/includes/header.php";
echo nv_site_theme($contents);
include NV_ROOTDIR . "/includes/footer.php";

?>