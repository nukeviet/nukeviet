<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_DOWNLOAD', true );

/**
 * nv_setcats()
 *
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @param mixed $is_parentlink
 * @return
 */
function nv_setcats( $id, $list, $name, $is_parentlink )
{
	global $module_name;

	if( $is_parentlink )
	{
		$name = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
	}
	else
	{
		$name = $list[$id]['title'] . " &raquo; " . $name;
	}
	$parentid = $list[$id]['parentid'];
	if( $parentid )
	{
		$name = nv_setcats( $parentid, $list, $name, $is_parentlink );
	}

	return $name;
}

/**
 * nv_list_cats()
 *
 * @param bool $is_link
 * @param bool $is_parentlink
 * @return
 */
function nv_list_cats( $is_link = false, $is_parentlink = true )
{
	global $module_data, $module_name, $module_info;

	$sql = "SELECT id,title,alias,description,groups_view,groups_download, parentid
 FROM " . NV_PREFIXLANG . "_" . $module_data . "_categories WHERE status=1 ORDER BY parentid,weight ASC";

	$list = nv_db_cache( $sql, 'id' );

	$list2 = array();

	if( ! empty( $list ) )
	{
		foreach( $list as $row )
		{
			if( nv_user_in_groups( $row['groups_view'] ) )
			{
				if( ! $row['parentid'] or isset( $list[$row['parentid']] ) )
				{
					$list2[$row['id']] = $list[$row['id']];
					$list2[$row['id']]['name'] = $list[$row['id']]['title'];
					$list2[$row['id']]['is_download_allow'] = ( int )nv_user_in_groups( $row['groups_download'] );
					$list2[$row['id']]['subcats'] = array();

					if( $is_link )
					{
						$list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list2[$row['id']]['alias'] . "\">" . $list2[$row['id']]['name'] . "</a>";
					}

					if( $row['parentid'] )
					{
						$list2[$row['parentid']]['subcats'][] = $row['id'];

						$list2[$row['id']]['name'] = nv_setcats( $row['parentid'], $list, $list2[$row['id']]['name'], $is_parentlink );
					}

					if( $is_parentlink )
					{
						$list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $module_info['custom_title'] . "</a> &raquo; " . $list2[$row['id']]['name'];
					}
				}
			}
		}
	}

	return $list2;
}

/**
 * nv_mod_down_config()
 *
 * @return
 */
function nv_mod_down_config()
{
	global $module_name, $module_data, $module_name;

	$sql = "SELECT config_name,config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";

	$list = nv_db_cache( $sql );

	$download_config = array();
	foreach( $list as $values )
	{
		$download_config[$values['config_name']] = $values['config_value'];
	}

	$download_config['upload_filetype'] = ! empty( $download_config['upload_filetype'] ) ? explode( ',', $download_config['upload_filetype'] ) : array();
	if( ! empty( $download_config['upload_filetype'] ) ) $download_config['upload_filetype'] = array_map( "trim", $download_config['upload_filetype'] );

	if( empty( $download_config['upload_filetype'] ) )
	{
		$download_config['is_upload'] = 0;
	}

	if( $download_config['is_addfile'] )
	{
		$download_config['is_addfile_allow'] = nv_user_in_groups( $download_config['groups_addfile'] );
	}
	else
	{
		$download_config['is_addfile_allow'] = false;
	}

	if( $download_config['is_addfile_allow'] and $download_config['is_upload'] )
	{
		$download_config['is_upload_allow'] = nv_user_in_groups( $download_config['groups_upload'] );
	}
	else
	{
		$download_config['is_upload_allow'] = false;
	}

	return $download_config;
}

if( $op == "main" )
{
	$catalias = '';
	$filealias = '';
	$catid = 0;
	$nv_vertical_menu = array();

	$list_cats = nv_list_cats( true );
	if( ! empty( $list_cats ) )
	{
		if( ! empty( $array_op ) )
		{
			$catalias = isset( $array_op[0] ) ? $array_op[0] : "";
			$filealias = isset( $array_op[1] ) ? $array_op[1] : "";
		}

		// Xac dinh ID cua chu de
		foreach( $list_cats as $c )
		{
			if( $c['alias'] == $catalias )
			{
				$catid = intval( $c['id'] );
				break;
			}
		}
		//Het Xac dinh ID cua chu de

		//Xac dinh menu, RSS
		if( $module_info['rss'] )
		{
			$rss[] = array(
				'title' => $module_info['custom_title'],
				'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['rss']
			);
		}

		foreach( $list_cats as $c )
		{
			if( $c['parentid'] == 0 )
			{
				$sub_menu = array();
				$act = ( $c['id'] == $catid ) ? 1 : 0;
				if( $act or ( $catid > 0 and $c['id'] == $list_cats[$catid]['parentid'] ) )
				{
					foreach( $c['subcats'] as $catid_i )
					{
						$s_c = $list_cats[$catid_i];
						$s_act = ( $s_c['alias'] == $catalias ) ? 1 : 0;
						$s_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $s_c['alias'];
						$sub_menu[] = array( $s_c['title'], $s_link, $s_act );
					}
				}

				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias'];
				$nv_vertical_menu[] = array( $c['title'], $link, $act, 'submenu' => $sub_menu );
			}
			if( $module_info['rss'] )
			{
				$rss[] = array(
					'title' => $module_info['custom_title'] . ' - ' . $c['title'],
					'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['rss'] . "/" . $c['alias']
				);
			}
		}
		//het Xac dinh menu, RSS
		//Xem chi tiet
		if( $catid > 0 )
		{
			$op = "viewcat";
			$page = 1;
			if( preg_match( "/^page\-([0-9]+)$/", $filealias, $m ) )
			{
				$page = intval( $m[1] );
			}
			elseif( ! empty( $filealias ) )
			{
				$op = "viewfile";
			}
			$parentid = $catid;
			while( $parentid > 0 )
			{
				$c = $list_cats[$parentid];
				$array_mod_title[] = array(
					'catid' => $parentid,
					'title' => $c['title'],
					'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias']
				);
				$parentid = $c['parentid'];
			}
			sort( $array_mod_title, SORT_NUMERIC );
		}
	}
}