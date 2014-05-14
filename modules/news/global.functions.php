<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
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
	$query = $db->query( 'SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=2 AND publtime < ' . NV_CURRENTTIME . ' ORDER BY publtime ASC' );
	while( list( $id, $listcatid ) = $query->fetch( 3 ) )
	{
		$array_catid = explode( ',', $listcatid );
		foreach( $array_catid as $catid_i )
		{
			$catid_i = intval( $catid_i );
			if( $catid_i > 0 )
			{
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=1 WHERE id=' . $id );
			}
		}
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=1 WHERE id=' . $id );
	}

	// Ngung hieu luc cac bai da het han
	$query = $db->query( 'SELECT id, listcatid, archive FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY exptime ASC' );
	while( list( $id, $listcatid, $archive ) = $query->fetch( 3 ) )
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
	$time_publtime = $db->query( 'SELECT min(publtime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=2 AND publtime > ' . NV_CURRENTTIME )->fetchColumn();
	$time_exptime = $db->query( 'SELECT min(exptime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=1 AND exptime > ' . NV_CURRENTTIME )->fetchColumn();

	$timecheckstatus = min( $time_publtime, $time_exptime );
	if( ! $timecheckstatus ) $timecheckstatus = max( $time_publtime, $time_exptime );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'timecheckstatus'" );
	$sth->bindValue( ':module_name', $module_name, PDO::PARAM_STR );
	$sth->bindValue( ':config_value', intval( $timecheckstatus ), PDO::PARAM_STR );
	$sth->execute();

	nv_del_moduleCache( 'settings' );
	nv_del_moduleCache( $module_name );

	unlink( $check_run_cronjobs );
	clearstatcache();
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
	$content_del = 'NO_' . $id;
	$title = '';
	list( $id, $listcatid, $title, $homeimgfile, $homeimgthumb ) = $db->query( 'SELECT id, listcatid, title, homeimgfile, homeimgthumb FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		if( $homeimgthumb != '' and $homeimgthumb != '|' )
		{
			$homeimgthumb_arr = explode( '|', $homeimgthumb );
			foreach( $homeimgthumb_arr as $file )
			{
				if( ! empty( $file ) and is_file( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file ) )
				{
					@nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file );
				}
			}
		}
		$number_no_del = 0;
		$array_catid = explode( ',', $listcatid );
		foreach( $array_catid as $catid_i )
		{
			$catid_i = intval( $catid_i );
			if( $catid_i > 0 )
			{
				$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' WHERE id=' . $id;
				if( ! $db->exec( $_sql ) )
				{
					++$number_no_del;
				}
			}
		}
		if( $number_no_del == 0 )
		{
			$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
			if( ! $db->exec( $_sql ) )
			{
				++$number_no_del;
			}
		}
		$number_no_del = 0;
		if( $number_no_del == 0 )
		{
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $id / 2000 ) . ' WHERE id = ' . $id );
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext WHERE id = ' . $id );
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND id = ' . $id );
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $id );

			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid IN (SELECT tid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $id . ')' );
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $id );
			$content_del = 'OK_' . $id;
		}
		else
		{
			$content_del = 'ERR_' . $lang_module['error_del_content'];
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
	$array_catid = explode( ',', $listcatid );
	foreach( $array_catid as $catid_i )
	{
		$catid_i = intval( $catid_i );
		if( $catid_i > 0 )
		{
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=3 WHERE id=' . $id );
		}
	}
	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=3 WHERE id=' . $id );
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
	$link = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a>";
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
	$link = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5( $id . session_id() ) . "','" . NV_BASE_ADMINURL . "')\">" . $lang_global['delete'] . "</a>";
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
			$textimg = '';
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
	$bodytext = str_replace( '&nbsp;', ' ', $bodytext );
	return preg_replace( '/[ ]+/', ' ', $bodytext );
}