<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/11/2010 23:31
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) ) die( 'Stop!!!' );

$sql = "SELECT * FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );

if( $numrows != 1 ) die( 'Stop!!!' );

$row = $db->sql_fetchrow( $result );

$contents = array();
$contents['caption'] = sprintf( $lang_module['info_client_caption'], $row['full_name'] );
$contents['rows'][] = array( $lang_module['login'], $row['login'] );
$contents['rows'][] = array( $lang_module['full_name'], $row['full_name'] );
$contents['rows'][] = array( $lang_module['email'], nv_EncodeEmail( $row['email'] ) );
$contents['rows'][] = array( $lang_module['reg_time'], nv_date( "d/m/Y H:i", $row['reg_time'] ) );

if( ! empty( $row['website'] ) and nv_is_url( $row['website'] ) ) $contents['rows'][] = array( $lang_module['website'], "<a href=\"" . $row['website'] . "\" target=\"_blank\">" . $row['website'] . "</a>" );
if( ! empty( $row['location'] ) ) $contents['rows'][] = array( $lang_module['location'], $row['location'] );
if( ! empty( $row['yim'] ) ) $contents['rows'][] = array( $lang_module['yim'], $row['yim'] );
if( ! empty( $row['phone'] ) ) $contents['rows'][] = array( $lang_module['phone'], $row['phone'] );
if( ! empty( $row['fax'] ) ) $contents['rows'][] = array( $lang_module['fax'], $row['fax'] );
if( ! empty( $row['mobile'] ) ) $contents['rows'][] = array( $lang_module['mobile'], $row['mobile'] );
if( ! empty( $row['uploadtype'] ) ) $contents['rows'][] = array( $lang_module['uploadtype'], $row['uploadtype'] );

$contents['rows'][] = array( $lang_module['is_act'], $row['act'] ? $lang_global['yes'] : $lang_global['no'] );
$contents['edit'] = array( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_client&amp;id=" . $id, $lang_global['edit'] );
$contents['add'] = array( NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_banner&amp;clid=" . $id, $lang_module['add_banner'] );
$contents['del'] = array( "nv_cl_del2(" . $id . ");", $lang_global['delete'] );
$contents['act'] = array( "nv_chang_act2(" . $id . ");", $lang_module['change_act'] );

$contents = call_user_func( "nv_info_cl_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>