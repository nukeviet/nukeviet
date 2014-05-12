<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 19-09-2010 23:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$contents = '';

$download_config = nv_mod_down_config();

$today = mktime( 0, 0, 0, date( 'n' ), date( 'j' ), date( 'Y' ) );
$yesterday = $today - 86400;

if( ! preg_match( '/^([a-z0-9\-\_\.]+)$/i', $filealias ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

$stmt = $db->prepare( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE alias= :filealias AND catid=' . $catid . ' AND status=1' );
$stmt->bindParam( ':filealias', $filealias, PDO::PARAM_STR );
$stmt->execute();
$row = $stmt->fetch();

if( empty( $row ) )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

if( ! nv_user_in_groups( $row['groups_view'] ) )
{
	$redirect = '<meta http-equiv="Refresh" content="4;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
	nv_info_die( $lang_module['error_not_permission_title'], $lang_module['error_not_permission_title'], $lang_module['error_not_permission_content'] . $redirect );
	exit();
}

$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$row['catid']]['alias'] . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );
if( $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
	Header( 'Location: ' . $base_url_rewrite );
	die();
}


$row['cattitle'] = '<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $list_cats[$row['catid']]['alias'] . '">' . $list_cats[$row['catid']]['title'] . '</a>';

$row['uploadtime'] = ( int )$row['uploadtime'];
if( $row['uploadtime'] >= $today )
{
	$row['uploadtime'] = $lang_module['today'] . ', ' . date( 'H:i', $row['uploadtime'] );
}
elseif( $row['uploadtime'] >= $yesterday )
{
	$row['uploadtime'] = $lang_module['yesterday'] . ', ' . date( 'H:i', $row['uploadtime'] );
}
else
{
	$row['uploadtime'] = nv_date( 'd/m/Y H:i', $row['uploadtime'] );
}

$row['updatetime'] = ( int )$row['updatetime'];
if( $row['updatetime'] >= $today )
{
	$row['updatetime'] = $lang_module['today'] . ', ' . date( 'H:i', $row['updatetime'] );
}
elseif( $row['updatetime'] >= $yesterday )
{
	$row['updatetime'] = $lang_module['yesterday'] . ', ' . date( 'H:i', $row['updatetime'] );
}
else
{
	$row['updatetime'] = nv_date( 'd/m/Y H:i', $row['updatetime'] );
}

if( defined( 'NV_IS_MODADMIN' ) and ! empty( $row['user_id'] ) and ! empty( $row['user_name'] ) )
{
	$row['user_name'] = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $row['user_id'] . '">' . $row['user_name'] . '</a>';
}
if( empty( $row['user_name'] ) ) $row['user_name'] = $lang_module['unknown'];

if( ! empty( $row['author_name'] ) )
{
	if( ! empty( $row['author_email'] ) )
	{
		$row['author_name'] .= ' (' . nv_EncodeEmail( $row['author_email'] ) . ')';
	}
}
else
{
	$row['author_name'] = $lang_module['unknown'];
}

if( ! empty( $row['author_url'] ) )
{
	$row['author_url'] = '<a href="' . $row['author_url'] . '" onclick="this.target=\'_blank\'">' . $row['author_url'] . '</a>';
}
else
{
	$row['author_url'] = $lang_module['unknown'];
}

if( empty( $row['description'] ) )
{
	$row['description'] = $row['introtext'];
}

if( empty( $row['version'] ) )
{
	$row['version'] = $lang_module['unknown'];
}

if( empty( $row['copyright'] ) )
{
	$row['copyright'] = $lang_module['unknown'];
}

$row['catname'] = $list_cats[$row['catid']]['name'];

//phan quyen tai file tai danh muc
$row['is_download_allow'] = $list_cats[$row['catid']]['is_download_allow'];
//neu danh muc cho phep tai file thi kiem tra tiep phan quyen tai file trong chi tiet file
if( $row['is_download_allow'] == false )
{
	$row['is_download_allow'] = ( int )nv_user_in_groups( $row['groups_download'] );
}

$session_files = array();
$session_files['fileupload'] = array();
$session_files['linkdirect'] = array();

if( $row['is_download_allow'] )
{
	if( ! empty( $row['fileupload'] ) )
	{
		$fileupload = explode( '[NV]', $row['fileupload'] );
		$row['fileupload'] = array();

		$a = 1;
		$count_file = sizeof( $fileupload );
		foreach( $fileupload as $file )
		{
			if( ! empty( $file ) )
			{
				$file2 = NV_UPLOADS_DIR . $file;
				if( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
				{
					$new_name = str_replace( '-', '_', $filealias ) . ( $count_file > 1 ? '_part' . str_pad( $a, 2, '0', STR_PAD_LEFT ) : '' ) . '.' . nv_getextension( $file );
					$row['fileupload'][] = array( 'link' => '#', 'title' => $new_name );
					$session_files['fileupload'][$new_name] = array( 'src' => NV_ROOTDIR . '/' . $file2, 'id' => $row['id'] );

					++$a;
				}
			}
		}
	}
	else
	{
		$row['fileupload'] = array();
	}

	if( ! empty( $row['linkdirect'] ) )
	{
		$linkdirect = explode( '[NV]', $row['linkdirect'] );
		$row['linkdirect'] = array();

		foreach( $linkdirect as $links )
		{
			if( ! empty( $links ) )
			{
				$links = explode( '<br />', $links );

				$host = '';
				$scheme = '';

				foreach( $links as $link )
				{
					if( ! empty( $link ) and nv_is_url( $link ) )
					{
						if( empty( $host ) )
						{
							$host = @parse_url( $link );
							$scheme = $host['scheme'];
							$host = $host['host'];
							$host = preg_replace( '/^www\./', '', $host );

							$row['linkdirect'][$host] = array();
						}

						$code = md5( $link );
						$row['linkdirect'][$host][] = array(
							'link' => $link,
							'code' => $code,
							'name' => isset( $link{70} ) ? $scheme . '://' . $host . '...' . substr( $link, -( 70 - strlen( $scheme . '://' . $host ) ) ) : $link
						);
						$session_files['linkdirect'][$code] = array( 'link' => $link, 'id' => $row['id'] );
					}
				}
			}
		}
	}
	else
	{
		$row['linkdirect'] = array();
	}

	$row['download_info'] = '';
}
else
{
	$row['fileupload'] = array();
	$row['linkdirect'] = array();
	$session_files = array();

	$row['download_info'] = sprintf( $lang_module['download_not_allow_info1'], NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=register' );
}

$session_files = serialize( $session_files );
$nv_Request->set_Session( 'session_files', $session_files );

$row['filesize'] = ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : $lang_module['unknown'];

$img = NV_UPLOADS_DIR . $row['fileimage'];
$row['fileimage'] = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_ROOTDIR . '/' . NV_TEMP_DIR );

$dfile = $nv_Request->get_string( 'dfile', 'session', '' );

$dfile = ! empty( $dfile ) ? unserialize( $dfile ) : array();

if( ! in_array( $row['id'], $dfile ) )
{
	$dfile[] = $row['id'];
	$dfile = serialize( $dfile );
	$nv_Request->set_Session( 'dfile', $dfile );

	$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET view_hits=view_hits+1 WHERE id=' . $row['id'];
	$db->query( $sql );
	++$row['view_hits'];
}

// comment
define( 'NV_COMM_ID', $row['id'] );
define( 'NV_COMM_ALLOWED', nv_user_in_groups( $row['groups_comment'] ) );
require_once NV_ROOTDIR . '/modules/comment/comment.php';

$row['rating_point'] = 0;
if( ! empty( $row['rating_detail'] ) )
{
	$row['rating_detail'] = explode( '|', $row['rating_detail'] );
	if( $row['rating_detail'][1] )
	{
		$row['rating_point'] = round( ( int )$row['rating_detail'][0] / ( int )$row['rating_detail'][1] );
	}
}
$row['rating_string'] = $lang_module['file_rating' . $row['rating_point']];
if( $row['rating_point'] )
{
	$row['rating_string'] = $lang_module['file_rating_note3'] . ': ' . $row['rating_string'];
}

$flrt = $nv_Request->get_string( 'flrt', 'session', '' );
$flrt = ! empty( $flrt ) ? unserialize( $flrt ) : array();
$row['rating_disabled'] = ! in_array( $row['id'], $flrt ) ? false : true;

$row['edit_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'];
$row['del_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$page_title = $row['title'];
$key_words = $module_info['keywords'];
$description = $list_cats[$row['catid']]['description'];

$contents = view_file( $row, $download_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';