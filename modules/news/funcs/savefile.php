<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if( ! defined( 'NV_IS_MOD_NEWS' ) )
{
	die( 'Stop!!!' );
}

function nv_src_href_callback( $matches )
{
	if( ! empty( $matches[2] ) and ! preg_match( "/^http\:\/\//", $matches[2] ) and ! preg_match( "/^https\:\/\//", $matches[2] ) and ! preg_match( "/^mailto\:/", $matches[2] ) and ! preg_match( "/^tel\:/", $matches[2] ) and ! preg_match( "/^javascript/", $matches[2] ) )
	{
		if( preg_match( "/^\//", $matches[2] ) ) $_url = NV_MY_DOMAIN;
		else $_url = NV_MY_DOMAIN . "/";
		$matches[2] = $_url . $matches[2];
	}
	return $matches[1] . "=\"" . $matches[2] . "\"";
}

$id = $catid = 0;
if( isset( $array_op[2] ) )
{
	$alias_cat_url = $array_op[1];
	$array_page = explode( "-", $array_op[2] );
	$id = intval( end( $array_page ) );
}
foreach( $global_array_cat as $catid_i => $array_cat_i )
{
	if( $alias_cat_url == $array_cat_i['alias'] )
	{
		$catid = $catid_i;
		break;
	}
}
if( $id > 0 and $catid > 0 )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . " WHERE id ='" . $id . "' AND status=1";
	$result = $db->query( $sql );
	$content = $result->fetch();
	unset( $sql, $result );
	if( $content['id'] > 0 )
	{
		$body_contents = $db->query( "SELECT bodyhtml as bodytext, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save FROM " . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $content['id'] / 2000 ) . " where id=" . $content['id'] )->fetch();
		$content = array_merge( $content, $body_contents );
		unset( $body_contents );

		if( $content['allowed_print'] == 1 )
		{
			$sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE sourceid = '" . $content['sourceid'] . "'";
			$result = $db->query( $sql );
			$sourcetext = $result->fetchColumn();
			unset( $sql, $result );

			$canonicalUrl = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $content['alias'] . '-' . $id . $global_config['rewrite_exturl'], true );
			$link = "<a href=\"" . $canonicalUrl . "\" title=\"" . $content['title'] . "\">" . $canonicalUrl . "</a>\n";

			$meta_tags = nv_html_meta_tags();
			$content['bodytext'] = $db->query( "SELECT bodyhtml FROM " . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil( $content['id'] / 2000 ) . " where id=" . $content['id'] )->fetchColumn();

			$result = array(
				"url" => $global_config['site_url'],
				"meta_tags" => $meta_tags,
				"sitename" => $global_config['site_name'],
				"title" => $content['title'],
				"alias" => $content['alias'],
				"image" => "",
				"position" => $content['imgposition'],
				"time" => nv_date( "l - d/m/Y H:i", $content['publtime'] ),
				"hometext" => $content['hometext'],
				"bodytext" => $content['bodytext'],
				"copyright" => $content['copyright'],
				"copyvalue" => $module_config[$module_name]['copyright'],
				"link" => $link,
				"contact" => $global_config['site_email'],
				"author" => $content['author'],
				"source" => $sourcetext
			);

			if( ! empty( $content['homeimgfile'] ) and $content['imgposition'] > 0 )
			{
				$src = $alt = $note = '';
				$width = $height = 0;
				if( $content['homeimgthumb'] == 1 and $content['imgposition'] == 1 and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $content['homeimgfile'] ) )
				{
					$src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $content['homeimgfile'];
					$width = $module_config[$module_name]['homewidth'];
				}
				elseif( $content['homeimgthumb'] == 3 )
				{
					$src = $content['homeimgfile'];
					$width = ( $content['imgposition'] == 1 ) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
				}
				elseif( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $content['homeimgfile'] ) )
				{
					$src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $content['homeimgfile'];
					$width = ( $content['imgposition'] == 1 ) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
				}
				$alt = ( empty( $content['homeimgalt'] ) ) ? $content['title'] : $content['homeimgalt'];

				$result['image'] = array(
					"src" => $src,
					"width" => $width,
					"alt" => $alt,
					"note" => $content['homeimgalt'],
					"position" => $content['imgposition']
				);
			}
			$contents = call_user_func( "news_print", $result );
			header( "Content-Type: text/x-delimtext; name=\"" . $result['alias'] . ".html\"" );
			header( "Content-disposition: attachment; filename=" . $result['alias'] . ".html" );

			$global_config['mudim_active'] = 0;
			include NV_ROOTDIR . '/includes/header.php';
			echo preg_replace_callback( "/(src|href)\=\"([^\"]+)\"/", "nv_src_href_callback", $contents );
			include NV_ROOTDIR . '/includes/footer.php';
		}
	}
}

header( "Location: " . $global_config['site_url'] );
exit();