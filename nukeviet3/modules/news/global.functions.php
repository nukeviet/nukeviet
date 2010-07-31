<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_comment_module( $id, $page )
{
	global $db, $module_name, $module_data, $global_config, $module_config, $per_page_comment;
	$comment_array = array();
	list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
	$all_page = ( $numf ) ? $numf : 1;
	$per_page = $per_page_comment;
	$sql = "SELECT `content`, `post_time`, `post_name`, `post_email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`= '" . $id . "' AND `status`=1 ORDER BY `id` ASC LIMIT " . $page . "," . $per_page . "";
	$comment = $db->sql_query( $sql );
	while ( $row = $db->sql_fetchrow( $comment ) )
	{
		$row['post_email'] = ($module_config[$module_name]['emailcomm']) ? $row['post_email'] : "";
	    $comment_array[] = array( "content" => $row['content'], "post_time" => $row['post_time'], "post_name" => $row['post_name'], "post_email" => $row['post_email'] );
	}
	$db->sql_freeresult( $comment );
	unset( $row, $comment );
	$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;id=" . $id . "&checkss=" . md5( $id . session_id() . $global_config['sitekey'] );
	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'showcomment' );
	return array( "comment" => $comment_array, "page" => $generate_page );
}

function nv_del_content_module( $id )
{
	global $db, $module_name, $module_data, $title;
	$content_del = "NO_" . $id;
	$title = "";
	list( $id, $listcatid, $title, $homeimgfile, $homeimgthumb ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id`, `listcatid`, `title`, `homeimgfile`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . intval( $id ) . "" ) );
	if ( $id > 0 )
	{
		nv_save_log_content($id);
	    if ( $homeimgfile != "" or $homeimgthumb != "" )
		{
			$homeimgfile .= "|" . $homeimgthumb;
			$homeimgfile_arr = explode( "|", $homeimgfile );
			foreach ( $homeimgfile_arr as $homeimgfile_i )
			{
				if ( ! empty( $homeimgfile_i ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i ) )
				{
					@nv_deletefile( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $homeimgfile_i );
				}
			}
		}
		$number_no_del = 0;
		$array_catid = explode( ",", $listcatid );
		foreach ( $array_catid as $catid_i )
		{
			$catid_i = intval( $catid_i );
			if ( $catid_i > 0 )
			{
				$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` WHERE `id`=" . $id . "";
				$db->sql_query( $query );
				if ( ! $db->sql_affectedrows() )
				{
					$number_no_del++;
				}
				$db->sql_freeresult();
			}
		}
		if ( $number_no_del == 0 )
		{
			$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id;
			$db->sql_query( $query );
			if ( ! $db->sql_affectedrows() )
			{
				{
					$number_no_del++;
				}
				$db->sql_freeresult();
			}
		}
		$number_no_del = 0;
		if ( $number_no_del == 0 )
		{
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

function nv_archive_content_module( $id, $listcatid )
{
	global $db, $module_data;
	$array_catid = explode( ",", $listcatid );
	foreach ( $array_catid as $catid_i )
	{
		$catid_i = intval( $catid_i );
		if ( $catid_i > 0 )
		{
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `archive`='2' WHERE `id`=" . $id . "" );
		}
	}
	$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `archive`='2' WHERE `id`=" . $id . "" );
}

function nv_del_cache_module( $listcatid = "" )
{
	global $module_name, $module_data;
	$files_cache = @scandir( NV_ROOTDIR . "/" . NV_CACHEDIR );
	if ( ! empty( $files_cache ) and $listcatid != "" )
	{
		$arr_catid = explode( ",", $listcatid );
		foreach ( $arr_catid as $catid_i )
		{
			foreach ( $files_cache as $file )
			{
				$pregmatch1 = "/^(" . NV_LANG_DATA . "_" . $module_name . "_viewcat_" . $catid_i . ")\_[a-z0-9\_]+.cache$/";
				$pregmatch2 = "/^(" . NV_LANG_DATA . "_" . $module_name . "_main)\_[a-z0-9\_]+.cache$/";
				if ( preg_match( $pregmatch1, $file ) or preg_match( $pregmatch2, $file ) )
				{
					unlink( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file );
				}
			}
		}
	} elseif ( ! empty( $files_cache ) and $listcatid == "" )
	{
		foreach ( $files_cache as $file )
		{
			$pregmatch1 = "/^(" . NV_LANG_DATA . "_" . $module_name . "_viewcat)\_[a-z0-9\_]+.cache$/";
			$pregmatch2 = "/^(" . NV_LANG_DATA . "_" . $module_name . "_main)\_[a-z0-9\_]+.cache$/";
			if ( preg_match( $pregmatch1, $file ) or preg_match( $pregmatch2, $file ) )
			{
				unlink( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $file );
			}
		}
	}
}

function nv_link_edit_page( $id )
{
	global $lang_global, $module_name;
    $link = "<span class=\"edit_icon\"><a href=\"".NV_BASE_ADMINURL."index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a></span>";
    return $link;
}

function nv_link_delete_page( $id )
{
	global $lang_global, $module_name;
    $link = "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5( $id . session_id() ) . "','".NV_BASE_ADMINURL."')\">" . $lang_global['delete'] . "</a></span>";
    return $link;
}

function nv_news_page( $base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true )
{
	global $lang_global;
	$total_pages = ceil( $num_items / $per_page );
	if ( $total_pages == 1 ) return '';
	@$on_page = floor( $start_item / $per_page ) + 1;
	$page_string = "";
	if ( $total_pages > 10 )
	{
		$init_page_max = ( $total_pages > 3 ) ? 3 : $total_pages;
		for ( $i = 1; $i <= $init_page_max; $i++ )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			if ( $i < $init_page_max ) $page_string .= ", ";
		}
		if ( $total_pages > 3 )
		{
			if ( $on_page > 1 && $on_page < $total_pages )
			{
				$page_string .= ( $on_page > 5 ) ? " ... " : ", ";
				$init_page_min = ( $on_page > 4 ) ? $on_page : 5;
				$init_page_max = ( $on_page < $total_pages - 4 ) ? $on_page : $total_pages - 4;
				for ( $i = $init_page_min - 1; $i < $init_page_max + 2; $i++ )
				{
					$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
					$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
					if ( $i < $init_page_max + 1 )
					{
						$page_string .= ", ";
					}
				}
				$page_string .= ( $on_page < $total_pages - 4 ) ? " ... " : ", ";
			}
			else
			{
				$page_string .= " ... ";
			}

			for ( $i = $total_pages - 2; $i < $total_pages + 1; $i++ )
			{
				$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
				$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
				if ( $i < $total_pages )
				{
					$page_string .= ", ";
				}
			}
		}
	}
	else
	{
		for ( $i = 1; $i < $total_pages + 1; $i++ )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $i - 1 ) * $per_page ) . "\"";
			$page_string .= ( $i == $on_page ) ? "<strong>" . $i . "</strong>" : "<a " . $href . ">" . $i . "</a>";
			if ( $i < $total_pages )
			{
				$page_string .= ", ";
			}
		}
	}
	if ( $add_prevnext_text )
	{
		if ( $on_page > 1 )
		{
			$href = "href=\"" . $base_url . "/page-" . ( ( $on_page - 2 ) * $per_page ) . "\"";
			$page_string = "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pageprev'] . "</a></span>&nbsp;&nbsp;" . $page_string;
		}
		if ( $on_page < $total_pages )
		{
			$href = "href=\"" . $base_url . "/page-" . ( $on_page * $per_page ) . "\"";
			$page_string .= "&nbsp;&nbsp;<span><a " . $href . ">" . $lang_global['pagenext'] . "</a></span>";
		}
	}
	return $page_string;
}

?>