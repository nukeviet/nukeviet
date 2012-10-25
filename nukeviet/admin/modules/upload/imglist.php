<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$pathimg = nv_check_path_upload( $nv_Request->get_string( 'path', 'get', NV_UPLOADS_DIR ) );
$check_allow_upload_dir = nv_check_allow_upload_dir( $pathimg );

if( isset( $check_allow_upload_dir['view_dir'] ) )
{
	$type = $nv_Request->get_string( 'type', 'get', 'file' );
	if( $type != "image" and $type != "flash" ) $type = "file";

	$selectfile = htmlspecialchars( trim( $nv_Request->get_string( 'imgfile', 'get', '' ) ), ENT_QUOTES );
	$selectfile = basename( $selectfile );

	$author = $nv_Request->isset_request( 'author', 'get' ) ? true : false;
	$refresh = $nv_Request->isset_request( 'refresh', 'get' ) ? true : false;

	$results = nv_filesList( $pathimg, $refresh );

	if( ! empty( $results ) )
	{
		$xtpl = new XTemplate( "listimg.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );

		$author = ( $author === true ) ? $admin_info['userid'] : 0;

		foreach( $results as $title => $file )
		{
			if( $type == "file" or ( $type != "file" and $file[2] == $type ) )
			{
				if( ! $author or $author == $file[8] )
				{
					$file = array_combine( array( 'name0', 'ext', 'type', 'filesize', 'src', 'srcWidth', 'srcHeight', 'name', 'author', 'mtime' ), $file );
						
					$file['title'] = $title;
					
					if( $file['type'] == "image" or $file['ext'] == "swf" )
					{
						$file['size'] = str_replace( "|", " x ", $file['name'] ) . " pixels";
					}
					else
					{
						$file['size'] = nv_convertfromBytes( $file['filesize'] );
					}

					$file['name'] .= "|" . $file['ext'] . "|" . $file['type'] . "|" . nv_convertfromBytes( $file['filesize'] ) . "|" . $file['author'] . "|" . nv_date( "l, d F Y, H:i:s P", $file['mtime'] );
					$file['sel'] = ( $selectfile == $title ) ? " imgsel" : "";
					$file['src'] = NV_BASE_SITEURL . $file['src'] . '?' . $file['mtime'];
					
					$xtpl->assign( "IMG", $file );
					$xtpl->parse( 'main.loopimg' );
				}
			}
		}
		
		if( ! empty( $selectfile ) )
		{
			$xtpl->assign( "NV_CURRENTTIME", NV_CURRENTTIME );
			$xtpl->parse( 'main.imgsel' );
		}
		
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
		
		include ( NV_ROOTDIR . "/includes/header.php" );
		echo $contents;
		include ( NV_ROOTDIR . "/includes/footer.php" );
	}
}

exit();

?>