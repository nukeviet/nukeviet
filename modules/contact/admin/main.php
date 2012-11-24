<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$contact_allowed = nv_getAllowed();

if( ! empty( $contact_allowed['view'] ) )
{
	$in = implode( ",", array_keys( $contact_allowed['view'] ) );
	$sql = "`" . NV_PREFIXLANG . "_" . $module_data . "_send` WHERE `cid` IN (" . $in . ")";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 30;
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
	
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $sql . " ORDER BY `id` DESC LIMIT " . $page . "," . $per_page;
	$result = $db->sql_query( $sql );

	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );

	if( $all_page )
	{
		$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del&amp;t=2" );
	
		$a = 0;
		$currday = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );

		while( $row = $db->sql_fetchrow( $result ) )
		{
			$image = array( NV_BASE_SITEURL . 'images/mail_new.gif', 12, 9 );
			$status = "New";
			$style = " style=\"font-weight:bold;cursor:pointer;white-space:nowrap;\"";
			
			if( $row['is_read'] == 1 )
			{
				$image = array( NV_BASE_SITEURL . 'images/mail_old.gif', 12, 11 );
				$status = $lang_module['tt1_row_title'];
				$style = " style=\"cursor:pointer;white-space:nowrap;\"";
			}
			
			if( $row['is_reply'] )
			{
				$image = array( NV_BASE_SITEURL . 'images/mail_reply.gif', 13, 14 );
				$status = $lang_module['tt2_row_title'];
				$style = " style=\"cursor:pointer;white-space:nowrap;\"";
			}
			
			$onclick = "onclick=\"location.href='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=view&amp;id=" . $row['id'] . "'\"";
			
			$xtpl->assign( 'ROW', array(
				'class' => ( ++ $a % 2 ) ? " class=\"second\"" : "",  //
				'id' => $row['id'],  //
				'sender_name' => $row['sender_name'],  //
				'path' => $contact_allowed['view'][$row['cid']],  //
				'title' => nv_clean60( $row['title'], 60 ),  //
				'time' => $row['send_time'] >= $currday ? nv_date( "H:i", $row['send_time'] ) : nv_date( "d/m/Y", $row['send_time'] ),  //
				'style' => $style,  //
				'onclick' => $onclick,  //
				'status' => $status,  //
				'image' => $image,  //
			) );
			
			$xtpl->parse( 'main.data.row' );
		}

		$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
		
		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.data.generate_page' );
		}
	}
}

if( empty( $all_page ) ) 
{
	$xtpl->parse( 'main.empty' );
}
else
{
	$xtpl->parse( 'main.data' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>