<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/25/2010 21:7
 */

if( ! defined( 'NV_IS_MOD_BANNERS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

global $global_config, $module_name, $module_info, $lang_module;

$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>";

if( defined( 'NV_IS_BANNER_CLIENT' ) )
{
	$upload_blocked = "";
	$file_allowed_ext = array();
	
	if( preg_match( "/images/", $banner_client_info['uploadtype'] ) )
	{
		$file_allowed_ext[] = "images";
	}
	
	if( preg_match( "/flash/", $banner_client_info['uploadtype'] ) )
	{
		$file_allowed_ext[] = "flash";
	}

	if( empty( $file_allowed_ext ) )
	{
		$upload_blocked = $lang_module['upload_blocked'];
		
		include ( NV_ROOTDIR . "/includes/header.php" );
		echo nv_site_theme( $upload_blocked );
		include ( NV_ROOTDIR . "/includes/footer.php" );
		exit();
	}
	
	$xtpl = new XTemplate( "addads.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_URLSITE', NV_BASE_SITEURL );
	$xtpl->assign( 'clientinfo_link', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=clientinfo" );
	$xtpl->assign( 'clientinfo_addads', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=addads" );
	$xtpl->assign( 'clientinfo_stats', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=stats" );
	$xtpl->parse( 'main.management' );

	if( $nv_Request->isset_request( 'confirm', 'post' ) )
	{

		$error = array();
		$title = filter_text_input( 'title', 'post', '', 1 );
		$blockid = filter_text_input( 'block', 'post', '', 1 );
		$description = filter_text_input( 'description', 'post', '', 1 );
		$url = filter_text_input( 'url', 'post', '', 0 );
		$begintime = filter_text_input( 'begintime', 'post', '', 1 );
		$endtime = filter_text_input( 'endtime', 'post', '', 1 );

		if( ! empty( $begintime ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime ) ) $begintime = "";
		if( ! empty( $endtime ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime ) ) $endtime = "";
		if( $url == "http://" ) $url = "";
	
		if( empty( $title ) )
		{
			$error[] = $lang_module['title_empty'];
		}
		elseif( empty( $blockid ) )
		{
			$error[] = $lang_module['plan_not_selected'];
		}
		elseif( ! empty( $url ) and ! nv_is_url( $url ) )
		{
			$error[] = $lang_module['click_url_invalid'];
		}
		elseif( ! isset( $_FILES["image"] ) )
		{
			$error[] = $lang_module['file_upload_empty'];
		}
		else
		{
			require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
			$upload = new upload( $file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
			$upload_info = $upload->save_file( $_FILES['image'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false );
			@unlink( $_FILES['image']['tmp_name'] );
		
			if( ! empty( $upload_info['error'] ) )
			{
				$error[] = $upload_info['error'];
			}
		}

		if( ! empty( $error ) )
		{
			$xtpl->assign( 'errorinfo', implode( '<br/>', $error ) );
		}
		else
		{
			$file_name = $upload_info['basename'];
			$file_ext = $upload_info['ext'];
			$file_mime = $upload_info['mime'];
			$width = $upload_info['img_info'][0];
			$height = $upload_info['img_info'][1];

			if( empty( $begintime ) )
			{
				$begintime = NV_CURRENTTIME;
			}
			else
			{
				unset( $m );
				preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime, $m );
				$begintime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
				if( $begintime < NV_CURRENTTIME ) $begintime = NV_CURRENTTIME;
			}

			if( empty( $endtime ) )
			{
				$endtime = 0;
			}
			else
			{
				unset( $m );
				preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime, $m );
				$endtime = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
			}

			if( $endtime != 0 and $endtime <= $begintime ) $endtime = $begintime;

			$sql = "INSERT INTO `" . NV_BANNERS_ROWS_GLOBALTABLE . "` (`id`, `title`, `pid`, `clid`, `file_name`, `file_ext`, `file_mime`, `width`, `height`, `file_alt`, `click_url`, `file_name_tmp`, `file_alt_tmp`, `click_url_tmp`, `add_time`, `publ_time`, `exp_time`, `hits_total`, `act`, `weight`) VALUES 
            (NULL, " . $db->dbescape( $title ) . ", " . $blockid . ", " . $banner_client_info['id'] . ", " . $db->dbescape( $file_name ) . ", " . $db->dbescape( $file_ext ) . ", " . $db->dbescape( $file_mime ) . ", 
            " . $width . ", " . $height . ", " . $db->dbescape( $description ) . ", " . $db->dbescape( $url ) . ", '', '', '', " . NV_CURRENTTIME . ", " . $begintime . ", " . $endtime . ", 
            0, 3,0)";
			$id = $db->sql_query_insert_id( $sql );
		
			if( $id )
			{
				$xtpl->assign( 'pagetitle', $lang_module['addads_success'] . '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name, true ) . '">' );
			}
		}
	}
	else
	{
		$xtpl->assign( 'pagetitle', $lang_module['addads_pagetitle'] );
	}
	
	$result = $db->sql_query( "SELECT `id`,`title`, `blang` FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` ORDER BY `blang`, `title` ASC" );

	while( $row = $db->sql_fetchrow( $result ) )
	{
		$row['title'] .= ' (' . ( empty( $row['blang'] ) ? $lang_module['addads_block_lang_all'] : $lang_array[$row['blang']] ) . ')';
		$xtpl->assign( 'blockitem', $row );
		$xtpl->parse( 'main.blockitem' );
	}

	$xtpl->parse( 'main' );
	$contents .= $xtpl->text( 'main' );
}
else
{
	$contents .= $lang_module['addads_require_login'];
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>