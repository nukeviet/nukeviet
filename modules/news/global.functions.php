<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$timecheckstatus = $module_config[$module_name]['timecheckstatus'];
if( $timecheckstatus > 0 and $timecheckstatus < NV_CURRENTTIME )
{
	nv_set_status_module();
}

/**
 * nv_set_status_module()
 * 
 * @return
 */
function nv_set_status_module()
{
	global $db, $module_name, $module_data, $global_config;

	$check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5( $module_data . 'nv_set_status_module' . $global_config['sitekey'] ) . '.txt';
	$p = NV_CURRENTTIME - 300;
	if( file_exists( $check_run_cronjobs ) and @filemtime( $check_run_cronjobs ) > $p )
	{
		return;
	}
	file_put_contents( $check_run_cronjobs, '' );

	//status_0 = "Cho duyet";
	//status_1 = "Xuat ban";
	//status_2 = "Hen gio dang";
	//status_3= "Het han";

	// Dang cai bai cho kich hoat theo thoi gian
	$query = $db->sql_query( "SELECT `id`, `listcatid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=2 AND `publtime` < " . NV_CURRENTTIME . " ORDER BY `publtime` ASC" );
	while( list( $id, $listcatid ) = $db->sql_fetchrow( $query ) )
	{
		$array_catid = explode( ",", $listcatid );
		foreach( $array_catid as $catid_i )
		{
			$catid_i = intval( $catid_i );
			if( $catid_i > 0 )
			{
				$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `status`='1' WHERE `id`=" . $id . "" );
			}
		}
		$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `status`='1' WHERE `id`=" . $id . "" );
	}

	// Ngung hieu luc cac bai da het han
	$query = $db->sql_query( "SELECT `id`, `listcatid`, `archive` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `exptime` > 0 AND `exptime` <= " . NV_CURRENTTIME . " ORDER BY `exptime` ASC" );
	while( list( $id, $listcatid, $archive ) = $db->sql_fetchrow( $query ) )
	{
		if( intval( $archive ) == 0 )
		{
			nv_del_content_module( $id );
		}
		else
		{
			nv_archive_content_module( $id, $listcatid );
		}
	}

	// Tim kiem thoi gian chay lan ke tiep
	list( $time_publtime ) = $db->sql_fetchrow( $db->sql_query( "SELECT min(publtime) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=2 AND `publtime` > " . NV_CURRENTTIME ) );
	list( $time_exptime ) = $db->sql_fetchrow( $db->sql_query( "SELECT min(exptime) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `exptime` > " . NV_CURRENTTIME ) );
	
	$timecheckstatus = min( $time_publtime, $time_exptime ); 
	if( ! $timecheckstatus ) $timecheckstatus = max( $time_publtime, $time_exptime );

	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . NV_LANG_DATA . "', " . $db->dbescape( $module_name ) . ", 'timecheckstatus', '" . intval( $timecheckstatus ) . "')" );
	nv_del_moduleCache( 'settings' );
	nv_del_moduleCache( $module_name );

	unlink( $check_run_cronjobs );
	clearstatcache();
}

/**
 * nv_comment_module()
 * 
 * @param mixed $id
 * @param mixed $page
 * @return
 */
function nv_comment_module( $id, $page )
{
	global $db, $module_name, $module_data, $global_config, $module_config, $per_page_comment;
	$comment_array = array();
	$per_page = $per_page_comment;
	$sql = "SELECT SQL_CALC_FOUND_ROWS a.content, a.post_time, a.post_name, a.post_email, b.userid, b.email, b.full_name, b.photo, b.view_mail FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` as a LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` as b ON a.userid =b.userid  WHERE a.id= '" . $id . "' AND a.status=1 ORDER BY a.cid DESC LIMIT " . $page . "," . $per_page;
	$comment = $db->sql_query( $sql );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );

	while( list( $content, $post_time, $post_name, $post_email, $userid, $user_email, $user_full_name, $photo, $view_mail ) = $db->sql_fetchrow( $comment ) )
	{
		if( $userid > 0 )
		{
			$post_email = $user_email;
			$post_name = $user_full_name;
		}
		$post_email = ( $module_config[$module_name]['emailcomm'] and $view_mail ) ? $post_email : "";
		$comment_array[] = array(
			"content" => $content,
			"post_time" => $post_time,
			"userid" => $userid,
			"post_name" => $post_name,
			"post_email" => $post_email,
			"photo" => $photo
		);
	}
	$db->sql_freeresult( $comment );
	unset( $row, $comment );
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;id=" . $id . "&checkss=" . md5( $id . session_id() . $global_config['sitekey'] );
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'showcomment' );
	return array( "comment" => $comment_array, "page" => $generate_page );
}

/**
 * nv_del_content_module()
 * 
 * @param mixed $id
 * @return
 */
function nv_del_content_module( $id )
{
	global $db, $module_name, $module_data, $title, $lang_module;
	$content_del = "NO_" . $id;
	$title = "";
	list( $id, $listcatid, $title, $homeimgfile, $homeimgthumb ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id`, `listcatid`, `title`, `homeimgfile`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . intval( $id ) . "" ) );
	if( $id > 0 )
	{
		if( $homeimgthumb != "" and $homeimgthumb != "|" )
		{
			$homeimgthumb_arr = explode( "|", $homeimgthumb );
			foreach( $homeimgthumb_arr as $file )
			{
				if( ! empty( $file ) and is_file( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file ) )
				{
					@nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file );
				}
			}
		}
		$number_no_del = 0;
		$array_catid = explode( ",", $listcatid );
		foreach( $array_catid as $catid_i )
		{
			$catid_i = intval( $catid_i );
			if( $catid_i > 0 )
			{
				$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `id`=" . $id;
				$db->sql_query( $query );
				if( ! $db->sql_affectedrows() )
				{
					++$number_no_del;
				}
				$db->sql_freeresult();
			}
		}
		if( $number_no_del == 0 )
		{
			$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id;
			$db->sql_query( $query );
			if( ! $db->sql_affectedrows() )
			{
				{
					++$number_no_del;
				}
				$db->sql_freeresult();
			}
		}
		$number_no_del = 0;
		if( $number_no_del == 0 )
		{
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $id / 2000 ) . "` WHERE `id` = " . $id );
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_bodytext` WHERE `id` = " . $id );
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id` = " . $id );
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` WHERE `id` = " . $id );
			$content_del = "OK_" . $id;
		}
		else
		{
			$content_del = "ERR_" . $lang_module['error_del_content'];
		}
	}
	return $content_del;
}

/**
 * nv_archive_content_module()
 * 
 * @param mixed $id
 * @param mixed $listcatid
 * @return
 */
function nv_archive_content_module( $id, $listcatid )
{
	global $db, $module_data;
	$array_catid = explode( ",", $listcatid );
	foreach( $array_catid as $catid_i )
	{
		$catid_i = intval( $catid_i );
		if( $catid_i > 0 )
		{
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `status`='3' WHERE `id`=" . $id . "" );
		}
	}
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `status`='3' WHERE `id`=" . $id . "" );
}

/**
 * nv_link_edit_page()
 * 
 * @param mixed $id
 * @return
 */
function nv_link_edit_page( $id )
{
	global $lang_global, $module_name;
	$link = "<span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>";
	return $link;
}

/**
 * nv_link_delete_page()
 * 
 * @param mixed $id
 * @return
 */
function nv_link_delete_page( $id )
{
	global $lang_global, $module_name;
	$link = "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5( $id . session_id() ) . "','" . NV_BASE_ADMINURL . "')\">" . $lang_global['delete'] . "</a></span>";
	return $link;
}

/**
 * nv_news_get_bodytext()
 * 
 * @param mixed $bodytext
 * @return
 */
function nv_news_get_bodytext( $bodytext )
{
	// Get image tags
	if( preg_match_all( "/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $bodytext, $match ) )
	{
		foreach( $match[0] as $key => $_m )
		{
			$textimg = "";
			if( strpos( $match[1][$key], 'data:image/png;base64' ) === false )
			{
				$textimg = " " . $match[1][$key];
			}
			if( preg_match_all( "/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt ) )
			{
				$textimg .= " " . $m_alt[1][0];
			}
			$bodytext = str_replace( $_m, $textimg, $bodytext );
		}
	}
	// Get link tags
	if( preg_match_all( "/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $bodytext, $match ) )
	{
		foreach( $match[0] as $key => $_m )
		{
			$bodytext = str_replace( $_m, $match[1][$key] . " " . $match[2][$key], $bodytext );
		}
	}

	$bodytext = nv_unhtmlspecialchars( strip_tags( $bodytext ) );
	$bodytext = str_replace( "&nbsp;", " ", $bodytext );
	return preg_replace( "/[ ]+/", " ", $bodytext );
}

?>