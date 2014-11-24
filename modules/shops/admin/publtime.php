<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'checkss', 'get' ) and $nv_Request->get_string( 'checkss', 'get' ) == md5( $global_config['sitekey'] . session_id() ) )
{
	$listid = $nv_Request->get_string( 'listid', 'get' );
	$id_array = array_filter( array_map( "intval", explode( ",", $listid ) ) );

	$sql = "SELECT id, listcatid, status , publtime, exptime FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE id IN (" . implode( ",", $id_array ) . ")";
	$result = $db->query( $sql );

	while( list( $id, $listcatid, $status, $publtime, $exptime ) = $result->fetch( 3 ) )
	{
		$data_save = array();
		$data_save['exptime'] = ( int )$exptime;
		$data_save['publtime'] = ( int )$publtime;
		$data_save['status'] = 1;

		if( $exptime > 0 and $exptime < NV_CURRENTTIME )
		{
			$data_save['exptime'] = 0;
		}

		if( $publtime > NV_CURRENTTIME )
		{
			$data_save['publtime'] = NV_CURRENTTIME;
		}

		if( ! empty( $data_save ) )
		{
			$s_ud = "";
			foreach( $data_save as $key => $value )
			{
				$s_ud .= "" . $key . " = '" . $value . "', ";
			}

			$s_ud .= "edittime = '" . NV_CURRENTTIME . "'";
			$db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_rows SET " . $s_ud . " WHERE id =" . $id );
		}
	}
	nv_set_status_module();
	nv_del_moduleCache( $module_name );
}

Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=items" );
die();