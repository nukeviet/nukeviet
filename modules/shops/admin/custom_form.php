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
if( $cid AND ! empty( $global_array_cat[$cid]['form'] ) )
{
	$array_custom = array();
	$array_custom_lang = array();

	$id = $nv_Request->get_int( 'id', 'get', 0 );
	if( $id )
	{
		$rowcontent = $db->query( 'SELECT custom, ' . NV_LANG_DATA . '_custom FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows where id=' . $id )->fetch();
		if ( !empty( $rowcontent['custom'] ) )
		{
			$array_custom = unserialize( $rowcontent['custom'] );
		}
		if ( !empty( $rowcontent[NV_LANG_DATA . '_custom'] ) )
		{
			$array_custom_lang = unserialize( $rowcontent[NV_LANG_DATA . '_custom'] );
		}
	}

	$datacustom_form = nv_show_custom_form( $global_array_cat[$cid]['form'], $array_custom, $array_custom_lang );
}

include NV_ROOTDIR . '/includes/header.php';
echo $datacustom_form;
include NV_ROOTDIR . '/includes/footer.php';