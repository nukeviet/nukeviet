<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$datacustom_form = '';

$cid = $nv_Request->get_int( 'cid', 'get', 0 );
$cat_form = $global_array_shops_cat[$cid]['form'];

if( $cid AND ! empty( $cat_form ) )
{
	$id = $nv_Request->get_int( 'id', 'get', 0 );

	$idtemplate = $db->query( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template where alias = "' . preg_replace( "/[\_]/", "-", $cat_form ) . '"' )->fetchColumn( );
	if( $idtemplate )
	{
		$table_insert = $db_config['prefix'] . "_" . $module_data . "_info_" . $idtemplate;
		$custom = $db->query( "SELECT * FROM " . $table_insert . " where shopid=" . $id )->fetch( );
	}

	$datacustom_form = nv_show_custom_form( $id, $cat_form, $custom );
}

include NV_ROOTDIR . '/includes/header.php';
echo $datacustom_form;
include NV_ROOTDIR . '/includes/footer.php';