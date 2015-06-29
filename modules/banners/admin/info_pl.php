<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/13/2010 0:12
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( $client_info['is_myreferer'] != 1 ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE. '_plans WHERE id=' . $id;
$row = $db->query( $sql )->fetch();

if( empty( $row ) ) die( 'Stop!!!' );

$contents = array();
$contents['caption'] = sprintf( $lang_module['info_plan_caption'], $row['title'] );
$contents['rows']['title'] = array( $lang_module['title'], $row['title'] );
$contents['rows']['blang'] = array( $lang_module['blang'], ( ! empty( $row['blang'] ) ) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all'] );
$contents['rows']['form'] = array( $lang_module['form'], $row['form'] );
$contents['rows']['size'] = array( $lang_module['size'], $row['width'] . ' x ' . $row['height'] . 'px' );
$contents['rows']['is_act'] = array( $lang_module['is_act'], $row['act'] ? $lang_global['yes'] : $lang_global['no'] );

if( ! empty( $row['description'] ) ) $contents['rows']['description'] = array( $lang_module['description'], $row['description'] );

$contents['edit'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_plan&amp;id=' . $id, $lang_global['edit'] );
$contents['add'] = array( NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add_banner&amp;pid=' . $id, $lang_module['add_banner'] );
$contents['del'] = array( 'nv_pl_del2(' . $id . ');', $lang_global['delete'] );
$contents['act'] = array( 'nv_pl_chang_act2(' . $id . ');', $lang_module['change_act'] );

$contents = nv_info_pl_theme( $contents );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';