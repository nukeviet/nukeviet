<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$per_page = 50;

$check_allow_upload_dir = nv_check_allow_upload_dir( $path );
if( isset( $check_allow_upload_dir['view_dir'] ) and isset( $array_dirname[$path] ) )
{
	if( $refresh )
	{
		nv_filesListRefresh( $path );
	}

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$type = $nv_Request->get_string( 'type', 'get', 'file' );
	$order = $nv_Request->get_int( 'order', 'get', 0 );

	$q = nv_string_to_filename( htmlspecialchars( trim( $nv_Request->get_string( 'q', 'get' ) ), ENT_QUOTES ) );

	$selectfile = htmlspecialchars( trim( $nv_Request->get_string( 'imgfile', 'get', '' ) ), ENT_QUOTES );
	$selectfile = basename( $selectfile );

	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;path=" . $path . "&amp;type=" . $type . "&amp;order=" . $order;

	if( empty( $q ) )
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` WHERE `did` = " . $array_dirname[$path];
		if( $type == "image" or $type == "flash" )
		{
			$sql .= " AND `type`='" . $type . "'";
		}
		if( $nv_Request->isset_request( 'author', 'get' ) )
		{
			$sql .= " AND `userid`=" . $admin_info['userid'];
			$base_url .= "&amp;author";
		}
		if( $order == 1 )
		{
			$sql .= " ORDER BY `mtime` ASC";
		}
		elseif( $order == 2 )
		{
			$sql .= " ORDER BY `title` ASC";
		}
		else
		{
			$sql .= " ORDER BY `mtime` DESC";
		}
	}
	else
	{
		$sql = "SELECT SQL_CALC_FOUND_ROWS t1.*, t2.dirname FROM `" . NV_UPLOAD_GLOBALTABLE . "_file` AS t1 INNER JOIN `" . NV_UPLOAD_GLOBALTABLE . "_dir` AS t2 ON t1.`did` = t2.`did`";
		$sql .= " WHERE (t2.`dirname` = '" . $path . "' OR t2.`dirname` LIKE '" . $path . "/%')";
		$sql .= " AND t1.`title` LIKE '%" . $db->dblikeescape( $q ) . "%'";
		if( $type == "image" or $type == "flash" )
		{
			$sql .= " AND t1.`type`='" . $type . "'";
		}
		if( $nv_Request->isset_request( 'author', 'get' ) )
		{
			$sql .= " AND t1.`userid`=" . $admin_info['userid'];
			$base_url .= "&amp;author";
		}
		if( $order == 1 )
		{
			$sql .= " ORDER BY t1.`mtime` ASC";
		}
		elseif( $order == 2 )
		{
			$sql .= " ORDER BY t1.`title` ASC";
		}
		else
		{
			$sql .= " ORDER BY t1.`mtime` DESC";
		}
		$base_url .= "&amp;q=" . $q;
	}
	$sql .= " LIMIT " . $page . "," . $per_page;

	$result = $db->sql_query( $sql );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );

	if( $all_page )
	{
		$xtpl = new XTemplate( "listimg.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
		$xtpl->assign( "NV_BASE_SITEURL", NV_BASE_SITEURL );

		while( $file = $db->sql_fetch_assoc( $result ) )
		{
			$file['data'] = $file['size'];
			if( $file['type'] == "image" or $file['ext'] == "swf" )
			{
				$file['size'] = str_replace( "|", " x ", $file['size'] ) . " pixels";
			}
			else
			{
				$file['size'] = nv_convertfromBytes( $file['filesize'] );
			}

			$file['data'] .= "|" . $file['ext'] . "|" . $file['type'] . "|" . nv_convertfromBytes( $file['filesize'] ) . "|" . $file['userid'] . "|" . nv_date( "l, d F Y, H:i:s P", $file['mtime'] ) . "|";
			$file['data'] .= ( empty( $q ) ) ? '' : $file['dirname'];

			$file['sel'] = ( $selectfile == $file['title'] ) ? " imgsel" : "";
			$file['src'] = NV_BASE_SITEURL . $file['src'] . '?' . $file['mtime'];

			$xtpl->assign( "IMG", $file );
			$xtpl->parse( 'main.loopimg' );
		}

		if( ! empty( $selectfile ) )
		{
			$xtpl->assign( "NV_CURRENTTIME", NV_CURRENTTIME );
			$xtpl->parse( 'main.imgsel' );
		}
		if( $all_page > $per_page )
		{
			$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'imglist' );
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.generate_page' );
		}
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );

		include ( NV_ROOTDIR . '/includes/header.php' );
		echo $contents;
		include ( NV_ROOTDIR . '/includes/footer.php' );
	}
}

exit();

?>