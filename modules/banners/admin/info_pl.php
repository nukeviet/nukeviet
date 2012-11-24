<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/13/2010 0:12
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( $client_info['is_myreferer'] != 1 ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 ) die( 'Stop!!!' );

$row = $db->sql_fetchrow( $result );

$contents = array();
$contents['caption'] = sprintf( $lang_module['info_plan_caption'], $row['title'] );
$contents['rows']['title'] = array( $lang_module['title'], $row['title'] );
$contents['rows']['blang'] = array( $lang_module['blang'], ( ! empty( $row['blang'] ) ) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all'] );
$contents['rows']['form'] = array( $lang_module['form'], $row['form'] );
$contents['rows']['size'] = array( $lang_module['size'], $row['width'] . ' x ' . $row['height'] . 'px' );
$contents['rows']['is_act'] = array( $lang_module['is_act'], $row['act'] ? $lang_global['yes'] : $lang_global['no'] );

if( ! empty( $row['description'] ) ) $contents['rows']['description'] = array( $lang_module['description'], $row['description'] );

$contents['edit'] = array( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_plan&amp;id=" . $id, $lang_global['edit'] );
$contents['add'] = array( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_banner&amp;pid=" . $id, $lang_module['add_banner'] );
$contents['del'] = array( "nv_pl_del2(" . $id . ");", $lang_global['delete'] );
$contents['act'] = array( "nv_pl_chang_act2(" . $id . ");", $lang_module['change_act'] );

$contents = call_user_func( "nv_info_pl_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>