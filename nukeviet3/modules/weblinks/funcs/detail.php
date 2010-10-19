<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_WEBLINKS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

//$fileid = $nv_Request->get_int ( 'id', 'get' );

global $global_array_cat;

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` = '".$id."'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$page_title = "".$row['title']." - ".$global_array_cat[$row['catid']]['title']."";
$row['visit'] = "".NV_BASE_SITEURL."?".NV_LANG_VARIABLE."=".NV_LANG_DATA."&amp;".NV_NAME_VARIABLE."=".$module_name."&amp;" . NV_OP_VARIABLE . "=visitlink-".$row['alias']."-".$row['id']."";
$row['report'] = "".NV_BASE_SITEURL."?".NV_LANG_VARIABLE."=".NV_LANG_DATA."&amp;".NV_NAME_VARIABLE."=".$module_name."&amp;" . NV_OP_VARIABLE . "=reportlink-".$row['alias']."-".$row['id']."";
$contents = call_user_func("detail", $row);
include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>