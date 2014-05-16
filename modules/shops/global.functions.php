<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Cau hinh mac dinh
$pro_config = $module_config[$module_name];

if( ! empty( $pro_config ) )
{
	$temp = explode( 'x', $pro_config['image_size'] );
	$pro_config['homewidth'] = $temp[0];
	$pro_config['homeheight'] = $temp[1];
	$pro_config['blockwidth'] = $temp[0];
	$pro_config['blockheight'] = $temp[1];
}
if( empty( $pro_config['format_order_id'] ) ) $pro_config['format_order_id'] = strtoupper( $module_name ) . '%d';
if( empty( $pro_config['timecheckstatus'] ) ) $pro_config['timecheckstatus'] = 0;
// Thoi gian xu ly archive

// Lay ty gia ngoai te
$money_config = array();

$sql = 'SELECT code, currency, exchange FROM ' . $db_config['prefix'] . '_' . $module_data . '_money_' . NV_LANG_DATA;
$list = nv_db_cache( $sql, '', $module_name );

foreach( $list as $row )
{
	$is_config = ( $row['code'] == $pro_config['money_unit'] ) ? 1 : 0;
	$money_config[$row['code']] = array(
		'code' => $row['code'],
		'currency' => $row['currency'],
		'exchange' => $row['exchange'],
		'is_config' => $is_config
	);
}
unset( $list, $row );

// Xu ly viec dang san pham tu dong, cho het han san pham ...
if( $pro_config['timecheckstatus'] > 0 and $pro_config['timecheckstatus'] < NV_CURRENTTIME )
{
	nv_set_status_module();
}

/**
 * nv_fomart_money()
 *
 * @return
 */
function nv_fomart_money( $number, $dec_point = ',', $thousands_sep = ' ' )
{
	return preg_replace( "/\\" . $dec_point . "00$/", "", number_format( $number, 2, $dec_point, $thousands_sep ) );
}

/**
 * nv_set_status_module()
 *
 * @return
 */
function nv_set_status_module()
{
	global $db, $module_name, $module_data, $global_config, $db_config;

	$check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5( $module_data . 'nv_set_status_module' . $global_config['sitekey'] ) . '.txt';
	$p = NV_CURRENTTIME - 300;
	if( file_exists( $check_run_cronjobs ) and @filemtime( $check_run_cronjobs ) > $p )
	{
		return;
	}
	file_put_contents( $check_run_cronjobs, '' );

	// status_0 = "Cho duyet";
	// status_1 = "Xuat ban";
	// status_2 = "Hen gio dang";
	// status_3= "Het han";

	// Dang cac san pham cho kich hoat theo thoi gian
	$result = $db->query( 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =2 AND publtime < ' . NV_CURRENTTIME . ' ORDER BY publtime ASC' );
	while( list( $id ) = $result->fetch( 3 ) )
	{
		$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status =1 WHERE id=' . $id );
	}

	// Ngung hieu luc cac san pham da het han
	$result = $db->query( 'SELECT id, archive FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY exptime ASC' );
	while( list( $id, $archive ) = $result->fetch( 3 ) )
	{
		if( intval( $archive ) == 0 )
		{
			nv_del_content_module( $id );
		}
		else
		{
			nv_archive_content_module( $id );
		}
	}

	// Tim kiem thoi gian chay lan ke tiep
	$time_publtime = $db->query( 'SELECT MIN(publtime) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =2 AND publtime > ' . NV_CURRENTTIME )->fetchColumn();

	$time_exptime = $db->query( 'SELECT MIN(exptime) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND exptime > ' . NV_CURRENTTIME )->fetchColumn();

	$timecheckstatus = min( $time_publtime, $time_exptime );
	if( ! $timecheckstatus )
	{
		$timecheckstatus = max( $time_publtime, $time_exptime );
	}

	$db->query( "REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . NV_LANG_DATA . "', " . $db->quote( $module_name ) . ", 'timecheckstatus', '" . intval( $timecheckstatus ) . "')" );
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
	global $db, $module_name, $module_data, $title, $db_config;

	$content_del = 'NO_' . $id;
	$title = '';

	list( $id, $listcatid, $title, $group_id ) = $db->query( 'SELECT id, listcatid, ' . NV_LANG_DATA . '_title, group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$number_no_del = 0;
		$array_catid = explode( ',', $listcatid );
		if( $number_no_del == 0 )
		{
			$sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
			if( ! $db->exec( $sql ) )
			{
				++$number_no_del;
			}
		}
		if( $number_no_del == 0 )
		{
			$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comments WHERE module=' . $db->quote( $module_name ) . ' AND id = ' . $id );
			$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE id = ' . $id );
			$content_del = 'OK_' . $id;
			nv_fix_group_count( $group_id );
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
 * @return
 */
function nv_archive_content_module( $id )
{
	global $db, $module_data, $db_config;
	$db->query( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status =3 WHERE id=' . $id );
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
	$link = "<em class=\"fa fa-edit\">&nbsp;</em><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\">" . $lang_global['edit'] . "</a>";
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
	$link = "<em class=\"fa fa-trash-o\">&nbsp;</em><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5( $id . session_id() ) . "','" . NV_BASE_ADMINURL . "')\">" . $lang_global['delete'] . "</a>";
	return $link;
}

/**
 * nv_file_table()
 *
 * @param mixed $table
 * @return
 */
function nv_file_table( $table )
{
	global $db_config, $db;
	$lang_value = nv_list_lang();
	$arrfield = array();
	$result = $db->query( 'SHOW COLUMNS FROM ' . $table );
	while( list( $field ) = $result->fetch( 3 ) )
	{
		$tmp = explode( '_', $field );
		foreach( $lang_value as $lang_i )
		{
			if( ! empty( $tmp[0] ) && ! empty( $tmp[1] ) )
			{
				if( $tmp[0] == $lang_i )
				{
					$arrfield[] = array( $tmp[0], $tmp[1] );
					break;
				}
			}
		}
	}
	return $arrfield;
}

/**
 * nv_list_lang()
 *
 * @return
 */
function nv_list_lang()
{
	global $db_config, $db;
	$re = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1' );
	$lang_value = array();
	while( list( $lang_i ) = $re->fetch( 3 ) )
	{
		$lang_value[] = $lang_i;
	}
	return $lang_value;
}

// Tru so luong trong kho $type = "-"
// Cong so luong trong kho $type = "+"
// $listid : danh sach cac id product
// $listnum : danh sach so luong tuong ung

/**
 * product_number_order()
 *
 * @param mixed $listid
 * @param mixed $listnum
 * @param string $type
 * @return
 */
function product_number_order( $listid, $listnum, $type = '-' )
{
	global $db_config, $db, $module_data;

	$arrayid = explode( '|', $listid );
	$arraynum = explode( '|', $listnum );

	foreach( $arrayid as $i => $id )
	{
		if( $id > 0 )
		{
			if( empty( $arraynum[$i] ) ) $arraynum[$i] = 0;

			$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_number = product_number ' . $type . ' ' . intval( $arraynum[$i] ) . ' WHERE id =' . $id;
			$db->query( $sql );
		}
	}
}

/**
 * nv_fix_group_count()
 *
 * @param mixed $listid
 * @return
 */
function nv_fix_group_count( $listid )
{
	global $db, $module_data, $db_config;

	$array_id = explode( ',', $listid );

	foreach( $array_id as $id )
	{
		if( ! empty( $id ) )
		{
			$sql = "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_rows WHERE ( group_id='" . $id . "' OR group_id REGEXP '^" . $id . "\\\,' OR group_id REGEXP '\\\," . $id . "\\\,' OR group_id REGEXP '\\\," . $id . "$' ) AND status =1 AND publtime <= " . NV_CURRENTTIME . " AND (exptime=0 OR exptime >=" . NV_CURRENTTIME . ")";
			$num = $db->query( $sql )->fetchColumn();

			$sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET numpro=" . $num . " WHERE groupid=" . intval( $id );
			$db->query( $sql );

			unset( $result );
		}
	}
}

/**
 * GetCatidInParent()
 *
 * @param mixed $catid
 * @param integer $check_inhome
 * @return
 */
function GetCatidInParent( $catid, $check_inhome = 0 )
{
	global $global_array_cat, $array_cat;
	$array_cat[] = $catid;
	$subcatid = explode( ',', $global_array_cat[$catid]['subcatid'] );
	if( ! empty( $subcatid ) )
	{
		foreach( $subcatid as $id )
		{
			if( $id > 0 )
			{
				if( $global_array_cat[$id]['numsubcat'] == 0 )
				{
					if( ! $check_inhome or ( $check_inhome and $global_array_cat[$id]['inhome'] == 1 ) )
					{
						$array_cat[] = $id;
					}
				}
				else
				{
					$array_cat_temp = GetCatidInParent( $id, $check_inhome );
					foreach( $array_cat_temp as $catid_i )
					{
						if( ! $check_inhome or ( $check_inhome and $global_array_cat[$catid_i]['inhome'] == 1 ) )
						{
							$array_cat[] = $catid_i;
						}
					}
				}
			}
		}
	}
	return array_unique( $array_cat );
}

/**
 * GetGroupidInParent()
 *
 * @param mixed $groupid
 * @param integer $check_inhome
 * @return
 */
function GetGroupidInParent( $groupid, $check_inhome = 0 )
{
	global $global_array_group, $array_group;
	$array_group[] = $groupid;
	$subgroupid = explode( ',', $global_array_group[$groupid]['subgroupid'] );
	if( ! empty( $subgroupid ) )
	{
		foreach( $subgroupid as $id )
		{
			if( $id > 0 )
			{
				if( $global_array_group[$id]['numsubgroup'] == 0 )
				{
					if( ! $check_inhome or ( $check_inhome and $global_array_group[$id]['inhome'] == 1 ) )
					{
						$array_group[] = $id;
					}
				}
				else
				{
					$array_group_temp = GetGroupidInParent( $id, $check_inhome );
					foreach( $array_group_temp as $groupid_i )
					{
						if( ! $check_inhome or ( $check_inhome and $global_array_group[$groupid_i]['inhome'] == 1 ) )
						{
							$array_group[] = $groupid_i;
						}
					}
				}
			}
		}
	}
	return array_unique( $array_group );
}