<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if (!defined('NV_IS_MOD_SHOPS'))
    die('Stop!!!');

$contents = "";
$groupid = 0;
$page_title = $lang_module['group_title'];

$temp_id = isset($array_op[1]) ? $array_op[1] : "";
if (!empty($temp_id))
{
    $array_page = explode('-', $temp_id);
    $groupid = intval(end($array_page));
}

if (empty($groupid) or empty($global_array_group[$groupid]))
{
    $redirect = "<meta http-equiv=\"Refresh\" content=\"3;URL=" . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true) . "\" />";
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect);
}

$page = 1;
if (isset($array_op[2]))
{
    if (substr($array_op[2], 0, 5) == "page-")
    {
        $page = intval(substr($array_op[2], 5));
    }
}

$page_title = $global_array_group[$groupid]['title'];
$key_words = $global_array_group[$groupid]['keywords'];
$description = $global_array_group[$groupid]['description'];
$data_content = array();

/*call funtion view*/
$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=";
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=group/" . $global_array_group[$groupid]['alias'] . "-" . $groupid;
$sql = "SELECT SQL_CALC_FOUND_ROWS id,listcatid, publtime, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, " . NV_LANG_DATA . "_hometext, " . NV_LANG_DATA . "_address, homeimgalt, homeimgthumb,product_price,product_discounts,money_unit,showprice  FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` WHERE ";
$sql .= " group_id LIKE '%" . $groupid . ",%'";
$sql .= " AND status=1 AND publtime < " . NV_CURRENTTIME . " AND (exptime=0 OR exptime>" . NV_CURRENTTIME . ") ORDER BY ID DESC LIMIT " . ($page - 1) * $per_page . "," . $per_page;

$result = $db->sql_query($sql);
$result_page = $db->sql_query("SELECT FOUND_ROWS()");
list($all_page) = $db->sql_fetchrow($result_page);

$data_content = GetDataInGroup($result, $groupid);
$data_content['count'] = $all_page;
$pages = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);

$contents = call_user_func($global_array_group[$groupid]['viewgroup'], $data_content, $pages);
if ($page > 1)
{
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");
?>