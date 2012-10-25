<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/14/2010 0:50
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( empty( $id ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `id`=" . $id;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if( $numrows != 1 ) die( 'Stop!!!' );

$row = $db->sql_fetchrow( $result );

$file_name = $row['file_name'];
$file_ext = $row['file_ext'];
$file_mime = $row['file_mime'];
$width = $row['width'];
$height = $row['height'];

$page_title = $lang_module['edit_banner'];

$contents = array();
$contents['upload_blocked'] = "";
$contents['file_allowed_ext'] = array();

if( preg_match( "/images/", NV_ALLOW_FILES_TYPE ) )
{
	$contents['file_allowed_ext'][] = "images";
}

if( preg_match( "/flash/", NV_ALLOW_FILES_TYPE ) )
{
	$contents['file_allowed_ext'][] = "flash";
}

if( empty( $contents['file_allowed_ext'] ) )
{
	$contents['upload_blocked'] = $lang_module['upload_blocked'];
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( nv_edit_banner_theme( $contents ) );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

$sql = "SELECT `id`,`login`,`full_name` FROM `" . NV_BANNERS_CLIENTS_GLOBALTABLE . "` ORDER BY `login` ASC";
$result = $db->sql_query( $sql );

$clients = array();
while( $cl_row = $db->sql_fetchrow( $result ) )
{
	$clients[$cl_row['id']] = $cl_row['full_name'] . " (" . $cl_row['login'] . ")";
}

$sql = "SELECT `id`,`title`,`blang` FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` ORDER BY `blang`, `title` ASC";
$result = $db->sql_query( $sql );

$plans = array();
while( $pl_row = $db->sql_fetchrow( $result ) )
{
	$plans[$pl_row['id']] = $pl_row['title'] . " (" . ( ! empty( $pl_row['blang'] ) ? $language_array[$pl_row['blang']]['name'] : $lang_module['blang_all'] ) . ")";
}

if( empty( $plans ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add_plan" );
	die();
}

$error = "";

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$title = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'title', 'post', '' ) ) );
	$pid = $nv_Request->get_int( 'pid', 'post', 0 );
	$clid = $nv_Request->get_int( 'clid', 'post', 0 );
	$file_alt = nv_htmlspecialchars( strip_tags( $nv_Request->get_string( 'file_alt', 'post', '' ) ) );
	$click_url = strip_tags( $nv_Request->get_string( 'click_url', 'post', '' ) );
	$publ_date = strip_tags( $nv_Request->get_string( 'publ_date', 'post', '' ) );
	$exp_date = strip_tags( $nv_Request->get_string( 'exp_date', 'post', '' ) );

	if( ! empty( $publ_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date ) ) $publ_date = "";
	if( ! empty( $exp_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date ) ) $exp_date = "";

	if( ! empty( $clid ) and ! isset( $clients[$clid] ) ) $clid = 0;
	if( $click_url == "http://" ) $click_url = "";

	if( empty( $title ) )
	{
		$error = $lang_module['title_empty'];
	}
	elseif( empty( $pid ) or ! isset( $plans[$pid] ) )
	{
		$error = $lang_module['plan_not_selected'];
	}
	elseif( ! empty( $click_url ) and ! nv_is_url( $click_url ) )
	{
		$error = $lang_module['click_url_invalid'];
	}
	else
	{
		if( isset( $_FILES['banner'] ) and is_uploaded_file( $_FILES['banner']['tmp_name'] ) )
		{
			require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
			$upload = new upload( $contents['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
			$upload_info = $upload->save_file( $_FILES['banner'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false );
			@unlink( $_FILES['banner']['tmp_name'] );
			
			if( ! empty( $upload_info['error'] ) )
			{
				$error = $upload_info['error'];
			}
			else
			{
				@chmod( $upload_info['name'], 0644 );
			
				if( ! empty( $file_name ) and is_file( NV_ROOTDIR . '/' . $file_name ) )
				{
					@nv_deletefile( NV_ROOTDIR . '/' . $file_name );
				}
			
				$file_name = $upload_info['basename'];
				$file_ext = $upload_info['ext'];
				$file_mime = $upload_info['mime'];
				$width = $upload_info['img_info'][0];
				$height = $upload_info['img_info'][1];
			}
		}
		
		if( empty( $error ) )
		{
			if( empty( $publ_date ) )
			{
				$publtime = NV_CURRENTTIME;
			}
			else
			{
				unset( $m );
				preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date, $m );
				$publtime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
				if( $publtime < $row['add_time'] ) $publtime = $row['add_time'];
			}

			if( empty( $exp_date ) )
			{
				$exptime = 0;
			}
			else
			{
				unset( $m );
				preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date, $m );
				$exptime = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
			}

			if( $exptime != 0 and $exptime <= $publtime ) $exptime = $publtime;
			list( $pid_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT `pid` FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` WHERE `id`=" . intval( $id ) . "" ) );

			$sql = "UPDATE `" . NV_BANNERS_ROWS_GLOBALTABLE . "` SET `title`=" . $db->dbescape( $title ) . ", `pid`=" . $pid . ", `clid`=" . $clid . ", 
            `file_name`=" . $db->dbescape( $file_name ) . ", `file_ext`=" . $db->dbescape( $file_ext ) . ", `file_mime`=" . $db->dbescape( $file_mime ) . ", 
            `width`=" . $width . ", `height`=" . $height . ", `file_alt`=" . $db->dbescape( $file_alt ) . ", `click_url`=" . $db->dbescape( $click_url ) . ", 
            `publ_time`=" . $publtime . ", `exp_time`=" . $exptime . " WHERE `id`=" . $id;
			$db->sql_query( $sql );
			
			if( $pid_old != $pid )
			{
				nv_fix_banner_weight( $pid );
				nv_fix_banner_weight( $pid_old );
			}
		
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_banner', "bannerid " . $id, $admin_info['userid'] );
			nv_CreateXML_bannerPlan();

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=info_banner&id=" . $id );
			die();
		}
	}
}
else
{
	$title = $row['title'];
	$pid = $row['pid'];
	$clid = $row['clid'];
	$file_alt = $row['file_alt'];
	$click_url = $row['click_url'];
	$publ_date = ! empty( $row['publ_time'] ) ? date( "d.m.Y", $row['publ_time'] ) : "";
	$exp_date = ! empty( $row['exp_time'] ) ? date( "d.m.Y", $row['exp_time'] ) : "";
}

if( $click_url == "" ) $click_url = "http://";
$contents['info'] = ( ! empty( $error ) ) ? $error : $lang_module['edit_banner_info'];
$contents['is_error'] = ( ! empty( $error ) ) ? 1 : 0;
$contents['file_allowed_ext'] = implode( ", ", $contents['file_allowed_ext'] );
$contents['submit'] = $lang_module['edit_banner'];
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit_banner&amp;id=" . $id;
$contents['title'] = array( $lang_module['title'], 'title', $title, 255 );
$contents['plan'] = array( $lang_module['in_plan'], 'pid', $plans, $pid );
$contents['client'] = array( $lang_module['of_client'], 'clid', $clients, $clid );
$contents['file_name'] = array( $lang_module['file_name'], NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . NV_BANNER_DIR . "/" . $file_name, "rel=\"shadowbox;height=" . $height . ";width=" . $width . "\"", NV_BASE_SITEURL . "images/ico_" . $file_ext . ".gif", $lang_global['show_picture'] );
$contents['upload'] = array( sprintf( $lang_module['re_upload'], $contents['file_allowed_ext'] ), 'banner' );
$contents['file_alt'] = array( $lang_module['file_alt'], 'file_alt', $file_alt, 255 );
$contents['click_url'] = array( $lang_module['click_url'], 'click_url', $click_url, 255 );
$contents['publ_date'] = array( $lang_module['publ_date'], 'publ_date', $publ_date, 10, NV_BASE_SITEURL . "images/calendar.jpg", 18, 17, "popCalendar.show(this, 'publ_date', 'dd.mm.yyyy', true);" );
$contents['exp_date'] = array( $lang_module['exp_date'], 'exp_date', $exp_date, 10, NV_BASE_SITEURL . "images/calendar.jpg", 18, 17, "popCalendar.show(this, 'exp_date', 'dd.mm.yyyy', true);" );

$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
$my_head .= "<script type=\"text/javascript\">\n";
$my_head .= "Shadowbox.init({\n";
$my_head .= "});\n";
$my_head .= "</script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( nv_edit_banner_theme( $contents ) );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>