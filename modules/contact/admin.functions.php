<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_getAllowed()
 *
 * @return
 */
function nv_getAllowed()
{
	global $module_data, $db, $admin_info;

	$sql = 'SELECT id,full_name,admins FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department';
	$result = $db->query( $sql );

	$contact_allowed = array(
		'view' => array(),
		'reply' => array(),
		'obt' => array()
	);

	while( $row = $result->fetch() )
	{
		$id = intval( $row['id'] );

		if( defined( 'NV_IS_SPADMIN' ) )
		{
			$contact_allowed['view'][$id] = $row['full_name'];
			$contact_allowed['reply'][$id] = $row['full_name'];
		}

		$admins = $row['admins'];
		$admins = array_map( 'trim', explode( ';', $admins ) );

		foreach( $admins as $a )
		{
			if( preg_match( '/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i', $a ) )
			{
				$admins2 = array_map( 'intval', explode( '/', $a ) );

				if( $admins2[0] == $admin_info['admin_id'] )
				{
					if( $admins2[1] == 1 and ! isset( $contact_allowed['view'][$id] ) ) $contact_allowed['view'][$id] = $row['full_name'];
					if( $admins2[2] == 1 and ! isset( $contact_allowed['reply'][$id] ) ) $contact_allowed['reply'][$id] = $row['full_name'];
					if( $admins2[3] == 1 and ! isset( $contact_allowed['obt'][$id] ) ) $contact_allowed['obt'][$id] = $row['full_name'];
				}
			}
		}
	}

	return $contact_allowed;
}

define( 'NV_IS_FILE_ADMIN', true );