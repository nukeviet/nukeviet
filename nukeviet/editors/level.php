<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

define( 'NV_WYSIWYG', true );

if( ! defined( 'NV_MAINFILE' ) )
{
	$temp_dir = str_replace( DIRECTORY_SEPARATOR, '/', dirname( __file__ ) );
	$temp_path = "/../";
	
	for( $i = 0; $i < 10; ++$i )
	{
		$realpath_mainfile = @realpath( $temp_dir . $temp_path . 'mainfile.php' );
		if( ! empty( $realpath_mainfile ) ) break;
		$temp_path .= "../";
	}

	unset( $temp_dir, $temp_path );
	if( empty( $realpath_mainfile ) )
	{
		die();
	}
	
	@require_once ( $realpath_mainfile );
}

@include_once ( NV_ROOTDIR . "/includes/core/wysyiwyg_functions.php" );
define( 'NV_IS_ABSOLUTE_URL', true );

$nv_editor = array();
$nv_editor['allow_files_type'] = defined( 'NV_ALLOW_FILES_TYPE' ) ? explode( "|", NV_ALLOW_FILES_TYPE ) : array();

?>