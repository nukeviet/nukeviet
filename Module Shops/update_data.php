<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 08/09/2012, 00:00
 */

if( ! defined( 'NV_IS_UPDATE' ) ) die( 'Stop!!!' );
 
$nv_update_config = array();

$nv_update_config['type'] = 1; // Kieu nang cap 1: Update; 2: Upgrade
$nv_update_config['packageID'] = 'NVUDSHOPS3501'; // ID goi cap nhat
$nv_update_config['formodule'] = "shops"; // Cap nhat cho module nao, de trong neu la cap nhat NukeViet, ten thu muc module neu la cap nhat module

// Thong tin phien ban, tac gia, ho tro
$nv_update_config['release_date'] = 1351728000;
$nv_update_config['author'] = "VINADES.,JSC (contact@vinades.vn)";
$nv_update_config['support_website'] = "http://nukeviet.vn/";
$nv_update_config['to_version'] = "3.5.01";
$nv_update_config['allow_old_version'] = array( "3.3.00" );
$nv_update_config['update_auto_type'] = 1; // 0:Nang cap bang tay, 1:Nang cap tu dong, 2:Nang cap nua tu dong

$nv_update_config['lang'] = array();
$nv_update_config['lang']['vi'] = array();
$nv_update_config['lang']['en'] = array();

// Tiếng Việt
$nv_update_config['lang']['vi']['nv_up_group'] = 'Cập nhật nhóm sản phẩm';
$nv_update_config['lang']['vi']['nv_up_rows'] = 'Cập nhật thuộc tính sản phẩm';
$nv_update_config['lang']['vi']['nv_up_config'] = 'Thêm cấu hình module';
$nv_update_config['lang']['vi']['nv_up_funcs'] = 'Thêm các functions';

$nv_update_config['lang']['vi']['nv_up_version'] = 'Cập nhật phiên bản module';

// English
$nv_update_config['lang']['en']['nv_up_group'] = 'Cập nhật nhóm sản phẩm';
$nv_update_config['lang']['en']['nv_up_rows'] = 'Cập nhật thuộc tính sản phẩm';
$nv_update_config['lang']['en']['nv_up_config'] = 'Thêm cấu hình module';
$nv_update_config['lang']['en']['nv_up_funcs'] = 'Thêm các functions';

$nv_update_config['lang']['en']['nv_up_version'] = 'Update module version';

// Require level: 0: Khong bat buoc hoan thanh; 1: Canh bao khi that bai; 2: Bat buoc hoan thanh neu khong se dung nang cap.
// r: Revision neu la nang cap site, phien ban neu la nang cap module

$nv_update_config['tasklist'] = array();
$nv_update_config['tasklist'][] = array( 'r' => '3.5.01', 'rq' => 2, 'l' => 'nv_up_group', 'f' => 'nv_up_group' );
$nv_update_config['tasklist'][] = array( 'r' => '3.5.01', 'rq' => 2, 'l' => 'nv_up_rows', 'f' => 'nv_up_rows' );
$nv_update_config['tasklist'][] = array( 'r' => '3.5.01', 'rq' => 2, 'l' => 'nv_up_config', 'f' => 'nv_up_config' );
$nv_update_config['tasklist'][] = array( 'r' => '3.5.01', 'rq' => 2, 'l' => 'nv_up_funcs', 'f' => 'nv_up_funcs' );

$nv_update_config['tasklist'][] = array( 'r' => '3.5.01', 'rq' => 2, 'l' => 'nv_up_version', 'f' => 'nv_up_version' );

// Danh sach cac function
/*
Chuan hoa tra ve:
array(
	'status' =>
	'complete' => 
	'next' =>
	'link' =>
	'lang' =>
	'message' =>
);

status: Trang thai tien trinh dang chay
- 0: That bai
- 1: Thanh cong

complete: Trang thai hoan thanh tat ca tien trinh
- 0: Chua hoan thanh tien trinh nay
- 1: Da hoan thanh tien trinh nay

next:
- 0: Tiep tuc ham nay voi "link"
- 1: Chuyen sang ham tiep theo

link:
- NO
- Url to next loading

lang:
- ALL: Tat ca ngon ngu
- NO: Khong co ngon ngu loi
- LangKey: Ngon ngu bi loi vi,en,fr ...

message:
- Any message

Duoc ho tro boi bien $nv_update_baseurl de load lai nhieu lan mot function
Kieu cap nhat module duoc ho tro boi bien $old_module_version
*/

$array_shops_update = array();
$array_shops_lang_update = array();

// Lay danh sach ngon ngu
$result = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
while( list( $_tmp ) = $db->sql_fetchrow( $result ) )
{
	// Get all module of shops
	$result1 = $db->sql_query( "SELECT `title`, `module_data` FROM `" . $db_config['prefix'] . "_" . $_tmp . "_modules` WHERE `module_file`='shops'" );
	while( list( $_modt, $_modd ) = $db->sql_fetchrow( $result1 ) )
	{
		$array_shops_lang_update[$_tmp][$_modt] = $_modt;
		$array_shops_update[$_modt] = array( "module_title" => $_modt, "module_data" => $_modd );
	}
}

function nv_list_lang()
{
	global $db_config, $db;
	$re = $db->sql_query( "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE setup=1" );
	$lang_value = array();
	while( list( $lang_i ) = $db->sql_fetchrow( $re ) )
	{
		$lang_value[] = $lang_i;
	}
	return $lang_value;
}

function nv_file_table( $table )
{
	global $db_config, $db;
	
	$lang_value = nv_list_lang();
	$arrfield = array();
	$result = $db->sql_query( "SHOW COLUMNS FROM " . $table );
	
	while( list( $field ) = $db->sql_fetchrow( $result ) )
	{
		$tmp = explode( "_", $field );
		foreach( $lang_value as $lang_i )
		{
			if( ! empty( $tmp[0] ) and ! empty( $tmp[1] ) )
			{
				if( $tmp[0] == $lang_i )
				{
					$arrfield[$tmp[0]] = $tmp[0];
					break;
				}
			}
		}
	}
	return $arrfield;
}

function nv_up_group()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_shops_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	foreach( $array_shops_update as $module_info )
	{
		$table = $db_config['prefix'] . "_" . $module_info['module_data'] . "_group";
		$table_rows = $db_config['prefix'] . "_" . $module_info['module_data'] . "_rows";
		
		if( ! $db->sql_query( "ALTER TABLE `" . $table . "` ADD `cateid` int(11) NOT NULL DEFAULT '0' AFTER `parentid`, ADD `numpro` int(11) unsigned NOT NULL DEFAULT '0' AFTER `groups_view`" ) )
		{
			$return['status'] = 0;
			break;
		}
		
		// Lay so san pham thuoc nhom san pham
		$sql = "SELECT `groupid` FROM `" . $table_rows . "`";
		$result = $db->sql_query( $sql );
		
		while( list( $groupid ) = $db->sql_fetchrow( $result ) )
		{
			$sql = "SELECT COUNT(*) FROM `" . $table_rows . "` WHERE `status`=1 AND (`group_id`='" . $groupid . "' OR `group_id` REGEXP '^" . $groupid . "\\\,' OR `group_id` REGEXP '\\\," . $groupid . "\\\,' OR `group_id` REGEXP '\\\," . $groupid . "$')";
			list( $num_products ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
			
			$db->sql_query( "UPDATE `" . $table . "` SET `numpro`=" . $num_products . " WHERE `groupid`=" . $groupid );
		}
		
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_rows()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_shops_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	foreach( $array_shops_update as $module_info )
	{
		$table = $db_config['prefix'] . "_" . $module_info['module_data'] . "_rows";
		
		// Them truong
		if( ! $db->sql_query( "ALTER TABLE `" . $table . "` ADD `topic_id` mediumint(8) NOT NULL DEFAULT '0' AFTER `listcatid`, ADD `otherimage` text NOT NULL AFTER `homeimgalt`" ) )
		{
			$return['status'] = 0;
			break;
		}
		
		// Xoa truong
		if( ! $db->sql_query( "ALTER TABLE `" . $table . "` DROP `product_code`" ) )
		{
			$return['status'] = 0;
			break;
		}
		
		// Them khoa
		if( ! $db->sql_query( "ALTER TABLE `" . $table . "` ADD INDEX `topic_id` (`topic_id`)" ) )
		{
			$return['status'] = 0;
			break;
		}
		
		$all_lang = nv_file_table( $table );
		$array_query = array();
		foreach( $all_lang as $lang )
		{
			$array_query[] = "ADD `" . $lang . "_warranty` text NOT NULL AFTER `" . $lang . "_address`, ADD `" . $lang . "_promotional` text NOT NULL AFTER `" . $lang . "_warranty`";
		}
		
		// Them truong theo ngon ngu
		if( ! $db->sql_query( "ALTER TABLE `" . $table . "` " . implode( ", ", $array_query ) ) )
		{
			$return['status'] = 0;
			break;
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_config()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_shops_lang_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	foreach( $array_shops_lang_update as  $lang => $array_module_name )
	{		
		foreach( $array_module_name as $module )
		{
			if( ! $db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . $lang . "', " . $db->dbescape( $module ) . ", " . $db->dbescape('active_tooltip') . ", " . $db->dbescape('1') . ")" ) )
			{
				$return['status'] = 0;
				break;
			}
		}
		
		if( $return['status'] == 0 )
		{
			break;
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_funcs()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_shops_lang_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	foreach( $array_shops_lang_update as  $lang => $array_module_name )
	{		
		foreach( $array_module_name as $module )
		{
			if( ! $db->sql_query( "INSERT INTO `" . NV_MODFUNCS_TABLE . "` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `setting`) VALUES 
				(NULL, " . $db->dbescape('myinfo') . ", " . $db->dbescape('My Info') . ", " . $db->dbescape( $module ) . ", 0, 0, 1, ''),
				(NULL, " . $db->dbescape('myproduct') . ", " . $db->dbescape('My Product') . ", " . $db->dbescape( $module ) . ", 1, 0, 1, ''),
				(NULL, " . $db->dbescape('post') . ", " . $db->dbescape('Post') . ", " . $db->dbescape( $module ) . ", 1, 0, 1, ''),
				(NULL, " . $db->dbescape('profile') . ", " . $db->dbescape('Profile') . ", " . $db->dbescape( $module ) . ", 1, 0, 1, ''),
				(NULL, " . $db->dbescape('search_result') . ", " . $db->dbescape('Search Result') . ", " . $db->dbescape( $module ) . ", 1, 0, 1, '')
			" ) )
			{
				$return['status'] = 0;
				break;
			}
		}
		
		if( $return['status'] == 0 )
		{
			break;
		}
	}
	$db->sql_freeresult();
	
	return $return;
}

function nv_up_version()
{
	global $nv_update_baseurl, $db, $db_config, $old_module_version, $array_shops_update;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_setup_modules` SET `mod_version`='3.5.01 1351728000' WHERE `module_file`='shops'" );
	
	nv_delete_all_cache();
	
	return $return;
}

?>