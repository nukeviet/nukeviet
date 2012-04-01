<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$contents = "Error Access!!!";

$checksess = filter_text_input( 'checksess', 'get', '' );
if( $checksess ==  md5( $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . '/install/update_data.php' ) )
{
	$contents = "";
	$list_file_docs = nv_scandir( NV_ROOTDIR . '/install', "/^update_docs_([a-z]{2})\.html$/" );

	foreach( $list_file_docs as $docsfile )
	{
		nv_deletefile( NV_ROOTDIR . '/install/' . $docsfile );
	}
	
	$check_delete_file = nv_deletefile( NV_ROOTDIR . '/install/update_data.php' );
	if( $check_delete_file[0] == 0 )
	{
		$contents .= $check_delete_file[1] . ' ' . $lang_module['update_manual_delete'];
	}
	
	if( file_exists( NV_ROOTDIR . '/install/update' ) )
	{
		$check_delete_dir = nv_deletefile( NV_ROOTDIR . '/install/update', true );
		if( $check_delete_dir[0] == 0 )
		{
			$contents .= $check_delete_dir[1] . ' ' . $lang_module['update_manual_delete'];
		}
	}
	
	clearstatcache();
}

if( $contents == "" ) $contents = "OK";

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>