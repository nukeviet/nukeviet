<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$page = 1;
if( isset( $array_op[0] ) and substr( $array_op[0], 0, 5 ) == 'page-' )
{
	$page = intval( substr( $array_op[0], 5 ) );
}

$contents = $cache_file = '';
$per_page = $nv_laws_setting['nummain'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = $nv_Cache->getItem( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	if( in_array( $nv_laws_setting['typeview'], array( 0, 1, 3, 4) ) ) // Hien thi danh sach van ban
	{
		$order = ( $nv_laws_setting['typeview'] == 1 OR $nv_laws_setting['typeview'] == 4 ) ? "ASC" : "DESC";
		$order_param = ( $nv_laws_setting['typeview'] == 0 OR $nv_laws_setting['typeview'] == 1 ) ? "publtime" : "addtime";

		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE status=1 ORDER BY " . $order_param . " " . $order . " LIMIT " . $per_page . " OFFSET " . ( $page - 1 ) * $per_page;

		$result = $db->query( $sql );
		$query = $db->query( "SELECT FOUND_ROWS()" );
		$all_page = $query->fetchColumn();

		$generate_page = nv_alias_page( $page_title, $base_url, $all_page, $per_page, $page );

		$array_data = array();
		$stt = nv_get_start_id( $page, $per_page );
		while ( $row = $result->fetch() )
		{
			$row['areatitle'] = array();
			$_result = $db->query( 'SELECT area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id=' . $row['id'] );
			while( list( $area_id ) = $_result->fetch( 3 ) )
			{
				$row['areatitle'][] = $nv_laws_listarea[$area_id]['title'];
			}
			$row['areatitle'] = !empty( $row['areatitle'] ) ? implode( ', ', $row['areatitle'] ) : '';
			$row['subjecttitle'] = $nv_laws_listsubject[$row['sid']]['title'];
			$row['cattitle'] = $nv_laws_listcat[$row['cid']]['title'];
			$row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=".$module_info['alias']['detail']."/" . $row['alias'];
			$row['stt'] = $stt++;

			if( $nv_laws_setting['down_in_home'] )
			{
				// File download
				if( ! empty( $row['files'] ) )
				{
					$row['files'] = explode( ",", $row['files'] );
					$files = $row['files'];
					$row['files'] = array();

					foreach( $files as $id => $file )
					{
						$file_title = basename( $file );
						$row['files'][] = array(
							"title" => $file_title,
							"titledown" => $lang_module['download'] . ' ' . ( count( $files ) > 1 ? $id + 1 : '' ),
							"url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=".$module_info['alias']['detail']."/" . $row['alias'] . "&amp;download=1&amp;id=" . $id
						);
					}
				}
			}

			$array_data[] = $row;
		}
		$contents = nv_theme_laws_main( $array_data, $generate_page );
	}
	elseif( $nv_laws_setting['typeview'] == 2 ) // Hien thi theo phan muc
	{
		if( !empty( $nv_laws_listsubject ) )
		{
			foreach( $nv_laws_listsubject as $subjectid => $subject )
			{
				$result = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $subjectid . ' ORDER BY addtime DESC LIMIT ' . $subject['numlink'] );
				while( $row = $result->fetch() )
				{
					$row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=".$module_info['alias']['detail']."/" . $row['alias'];
					if( $nv_laws_setting['down_in_home'] )
					{
						// File download
						if( ! empty( $row['files'] ) )
						{
							$row['files'] = explode( ",", $row['files'] );
							$files = $row['files'];
							$row['files'] = array();

							foreach( $files as $id => $file )
							{
								$file_title = basename( $file );
								$row['files'][] = array(
									"title" => $file_title,
									"titledown" => $lang_module['download'] . ' ' . ( count( $files ) > 1 ? $id + 1 : '' ),
									"url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=".$module_info['alias']['detail']."/" . $row['alias'] . "&amp;download=1&amp;id=" . $id
								);
							}
						}
					}
					$nv_laws_listsubject[$subjectid]['rows'][] = $row;
				}
			}
		}
		$contents = nv_theme_laws_maincat( 'subject', $nv_laws_listsubject );
	}

	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
	{
		$nv_Cache->setItem( $module_name, $cache_file, $contents );
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';