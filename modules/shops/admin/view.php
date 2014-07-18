<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get,post', 0 );
$row = $db->query( 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows where id=' . $id )->fetch();
if( ! empty( $row['id'] ) )
{
	$_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['listcatid']]['alias'] . '/' . $row[NV_LANG_DATA . '_alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'], true );
	Header( 'Location: ' . $_url_rewrite );
	die();
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'] );