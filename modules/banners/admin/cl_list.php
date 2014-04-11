<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/11/2010 23:0
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$sql = "SELECT * FROM " . NV_BANNERS_GLOBALTABLE. "_clients ORDER BY login ASC";
$result = $db->query( $sql );

$contents = array();
$contents['caption'] = $lang_module['client_list2'];
$contents['thead'] = array( $lang_module['login'], $lang_module['full_name'], $lang_module['email'], $lang_module['reg_time'], $lang_module['is_act'], $lang_global['actions'] );
$contents['view'] = $lang_global['detail'];
$contents['edit'] = $lang_global['edit'];
$contents['add'] = $lang_global['add'];
$contents['del'] = $lang_global['delete'];
$contents['rows'] = array();

while( $row = $result->fetch() )
{
	$contents['rows'][$row['id']]['login'] = $row['login'];
	$contents['rows'][$row['id']]['full_name'] = $row['full_name'];
	$contents['rows'][$row['id']]['email'] = nv_EncodeEmail( $row['email'] );
	$contents['rows'][$row['id']]['reg_time'] = nv_date( "d/m/Y H:i", $row['reg_time'] );
	$contents['rows'][$row['id']]['act'] = array( 'act_' . $row['id'], $row['act'], "nv_chang_act(" . $row['id'] . ",'act_" . $row['id'] . "');" );
	$contents['rows'][$row['id']]['view'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=info_client&amp;id=" . $row['id'];
	$contents['rows'][$row['id']]['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_client&amp;id=" . $row['id'];
	$contents['rows'][$row['id']]['add'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add_banner&amp;clid=" . $row['id'];
	$contents['rows'][$row['id']]['del'] = "nv_cl_del(" . $row['id'] . ");";
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_cl_list_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';