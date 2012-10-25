<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

$contents = "";
$publtime = 0;
$func_who_view = $global_array_cat[$catid]['who_view'];
$allowed = false;
if( $func_who_view == 0 )
{
	$allowed = true;
}
if( $func_who_view == 1 and defined( 'NV_IS_USER' ) )
{
	$allowed = true;
}
elseif( $func_who_view == 2 and defined( 'NV_IS_MODADMIN' ) )
{
	$allowed = true;
}
elseif( $func_who_view == 3 and defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $global_array_cat[$catid]['groups_view'] ) )
{
	$allowed = true;
}

if( $allowed )
{
	$query = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `id` = " . $id . "" );
	$news_contents = $db->sql_fetch_assoc( $query );
	if( $news_contents['id'] > 0 )
	{
		$body_contents = $db->sql_fetch_assoc( $db->sql_query( "SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM `" . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $news_contents['id'] / 2000 ) . "` where `id`=" . $news_contents['id'] ) );
		$news_contents = array_merge( $news_contents, $body_contents );
		unset( $body_contents );

		if( defined( 'NV_IS_MODADMIN' ) or ( $news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME and ( $news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME ) ) )
		{
			$time_set = $nv_Request->get_int( $module_name . '_' . $op . '_' . $id, 'session' );
			if( empty( $time_set ) )
			{
				$nv_Request->set_Session( $module_data . '_' . $op . '_' . $id, NV_CURRENTTIME );
				$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET hitstotal=hitstotal+1 WHERE `id`=" . $id;
				$db->sql_query( $query );

				$array_catid = explode( ",", $news_contents['listcatid'] );
				foreach( $array_catid as $catid_i )
				{
					$query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET hitstotal=hitstotal+1 WHERE `id`=" . $id;
					$db->sql_query( $query );
				}
			}
			$news_contents['showhometext'] = $module_config[$module_name]['showhometext'];
			$news_contents['homeimgalt'] = ( empty( $news_contents['homeimgalt'] ) ) ? $news_contents['title'] : $news_contents['homeimgalt'];
			if( ! empty( $news_contents['homeimgfile'] ) and $news_contents['imgposition'] > 0 )
			{
				$src = $alt = $note = "";
				$width = $height = 0;
				$array_img = explode( "|", $news_contents['homeimgthumb'] );
				if( ! empty( $array_img[0] ) and $news_contents['imgposition'] == 1 and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
				{
					$src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
					$width = $module_config[$module_name]['homewidth'];
				}
				elseif( nv_is_url( $news_contents['homeimgfile'] ) )
				{
					$src = $news_contents['homeimgfile'];
					$width = ( $news_contents['imgposition'] == 1 ) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
				}
				elseif( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] ) )
				{
					$src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'];
					if( $news_contents['imgposition'] == 1 )
					{
						$width = $module_config[$module_name]['homewidth'];
					}
					else
					{
						$imagesize = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] );
						if( $imagesize[0] > 0 and $imagesize[0] > $module_config[$module_name]['imagefull'] )
						{
							$width = $module_config[$module_name]['imagefull'];
						}
						else
						{
							$width = $imagesize[0];
						}
					}
				}

				if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'] ) )
				{
					$news_contents['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['homeimgfile'];
				}
				$news_contents['image'] = array(
					"src" => $src,
					"width" => $width,
					"alt" => $news_contents['homeimgalt'],
					"note" => $news_contents['homeimgalt'],
					"position" => $news_contents['imgposition'] );
			}
			if( $alias_url == $db->unfixdb( $news_contents['alias'] ) )
			{
				$publtime = intval( $news_contents['publtime'] );
			}
		}
	}

	if( $publtime == 0 )
	{
		$redirect = "<meta http-equiv=\"Refresh\" content=\"3;URL=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
	}
	if( $catid != $news_contents['catid'] )
	{
		$canonicalUrl = $global_config['site_url'] . "/index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$news_contents['catid']]['alias'] . "/" . $news_contents['alias'] . "-" . $news_contents['id'];
	}

	$news_contents['url_sendmail'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=sendmail/" . $global_array_cat[$catid]['alias'] . "/" . $news_contents['alias'] . "-" . $news_contents['id'];
	$news_contents['url_print'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=print/" . $global_array_cat[$catid]['alias'] . "/" . $news_contents['alias'] . "-" . $news_contents['id'];
	$news_contents['url_savefile'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=savefile/" . $global_array_cat[$catid]['alias'] . "/" . $news_contents['alias'] . "-" . $news_contents['id'];

	$sql = "SELECT `title`, `link`, `logo` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid` = '" . $news_contents['sourceid'] . "'";
	$result = $db->sql_query( $sql );

	list( $sourcetext, $source_link, $source_logo ) = $db->sql_fetchrow( $result );
	unset( $sql, $result );

	$news_contents['newscheckss'] = md5( $news_contents['id'] . session_id() . $global_config['sitekey'] );
	if( $module_config[$module_name]['config_source'] == 0 ) $news_contents['source'] = $sourcetext;
	elseif( $module_config[$module_name]['config_source'] == 1 ) $news_contents['source'] = $source_link;
	elseif( $module_config[$module_name]['config_source'] == 2 && !empty( $source_logo ) )  $news_contents['source'] = "<img width=\"100px\" src=\"" . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" . $source_logo . "\">";
	$news_contents['publtime'] = nv_date( "l - d/m/Y  H:i", $news_contents['publtime'] );

	$related_new_array = array();
	$related_new = $db->sql_query( "SELECT `id`, `title`, `alias`,`publtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` > " . $publtime . " AND `publtime` < " . NV_CURRENTTIME . " ORDER BY `id` ASC LIMIT 0, " . $st_links . "" );
	while( $row = $db->sql_fetch_assoc( $related_new ) )
	{
		$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
		$related_new_array[] = array(
			"title" => $row['title'],
			"time" => nv_date( "d/m/Y", $row['publtime'] ),
			"link" => $link );
	}
	sort( $related_new_array, SORT_NUMERIC );

	$db->sql_freeresult( $related_new );
	unset( $related_new, $row );

	$related_array = array();
	$related = $db->sql_query( "SELECT `id`, `title`, `alias`,`publtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `status`=1 AND `publtime` < " . $publtime . " AND `publtime` < " . NV_CURRENTTIME . " ORDER BY `id` DESC LIMIT 0, " . $st_links . "" );
	while( $row = $db->sql_fetch_assoc( $related ) )
	{
		$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
		$related_array[] = array(
			"title" => $row['title'],
			"time" => nv_date( "d/m/Y", $row['publtime'] ),
			"link" => $link );
	}
	$db->sql_freeresult( $related );
	unset( $related, $row );

	$topic_array = array();
	$topic_a = "";
	if( $news_contents['topicid'] > 0 )
	{
		list( $topic_title, $topic_alias ) = $db->sql_fetchrow( $db->sql_query( "SELECT `title`,`alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `topicid` = '" . $news_contents['topicid'] . "'" ) );
		$topic = $db->sql_query( "SELECT `id`, `catid`, `title`, `alias`,`publtime` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`=1 AND `topicid` = '" . $news_contents['topicid'] . "' AND `id` != " . $id . " ORDER BY `id` DESC  LIMIT 0, " . $st_links . "" );
		while( $row = $db->sql_fetch_assoc( $topic ) )
		{
			$topiclink = "" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=topic/" . $topic_alias;
			$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['catid']]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
			$topic_array[] = array(
				"title" => $row['title'],
				"link" => $link,
				"time" => nv_date( "d/m/Y", $row['publtime'] ),
				"topiclink" => $topiclink,
				"topictitle" => $topic_title
			);
		}
		$db->sql_freeresult( $topic );
		unset( $topic, $rows );
	}

	//Check: comment
	$commentenable = 0;

	if( $news_contents['allowed_comm'] and $module_config[$module_name]['activecomm'] )
	{
		$comment_array = nv_comment_module( $news_contents['id'], 0 );
		$news_contents['comment'] = comment_theme( $comment_array );
		if( $news_contents['allowed_comm'] == 1 or ( $news_contents['allowed_comm'] == 2 and defined( 'NV_IS_USER' ) ) )
		{
			$commentenable = 1;
		}
		elseif( $news_contents['allowed_comm'] == 2 )
		{
			$commentenable = 2;
		}
	}
	else
	{
		$news_contents['comment'] = "";
	}
	if( $news_contents['allowed_rating'] )
	{
		$time_set_rating = $nv_Request->get_int( $module_name . '_' . $op . '_' . $news_contents['id'], 'cookie', 0 );
		if( $time_set_rating > 0 )
		{
			$news_contents['disablerating'] = 1;
		}
		else
		{
			$news_contents['disablerating'] = 0;
		}
		$news_contents['stringrating'] = sprintf( $lang_module['stringrating'], $news_contents['total_rating'], $news_contents['click_rating'] );
		$news_contents['click_rating'] = ( $news_contents['click_rating'] > 0 ) ? $news_contents['click_rating'] : 1;
		$news_contents['numberrating'] = round( $news_contents['total_rating'] / $news_contents['click_rating'] ) - 1;
		$news_contents['langstar'] = array(
			"note" => $lang_module['star_note'],
			"verypoor" => $lang_module['star_verypoor'],
			"poor" => $lang_module['star_poor'],
			"ok" => $lang_module['star_ok'],
			"good" => $lang_module['star_good}'],
			"verygood" => $lang_module['star_verygood']
		);
	}

	$page_title = $news_contents['title'];
	$key_words = $news_contents['keywords'];
	$description = $news_contents['hometext'];

	list( $post_username, $post_full_name ) = $db->sql_fetchrow( $db->sql_query( "SELECT `username`, `full_name` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` = '" . $news_contents['admin_id'] . "' LIMIT 0,1 " ) );
	$news_contents['post_name'] = empty( $post_full_name ) ? $post_username : $post_full_name;

	$contents = detail_theme( $news_contents, $related_new_array, $related_array, $topic_array, $commentenable );
}
else
{
	$contents = no_permission( $func_who_view );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>