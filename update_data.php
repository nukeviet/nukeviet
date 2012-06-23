<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 29-03-2012 03:29
 */

if( ! defined( 'NV_IS_UPDATE' ) ) die( 'Stop!!!' );
 
$nv_update_config = array();

$nv_update_config['type'] = 1; // Kieu nang cap 1: Update; 2: Upgrade
$nv_update_config['packageID'] = 'NVUD3401'; // ID goi cap nhat
$nv_update_config['formodule'] = ""; // Cap nhat cho module nao, de trong neu la cap nhat NukeViet, ten thu muc module neu la cap nhat module

// Thong tin phien ban, tac gia, ho tro
$nv_update_config['release_date'] = 1333929600;
$nv_update_config['author'] = "VINADES.,JSC (contact@vinades.vn)";
$nv_update_config['support_website'] = "http://nukeviet.vn/phpbb/";
$nv_update_config['to_version'] = "3.4.01.r1758";
$nv_update_config['allow_old_version'] = array( "3.4.00.r1722" );
$nv_update_config['update_auto_type'] = 2; // 0:Nang cap bang tay, 1:Nang cap tu dong, 2:Nang cap nua tu dong

$nv_update_config['lang'] = array();
$nv_update_config['lang']['vi'] = array();
$nv_update_config['lang']['en'] = array();

// Tiếng Việt
$nv_update_config['lang']['vi']['update_nukeviet_version'] = 'Nâng cấp phiên bản và Revision';
$nv_update_config['lang']['vi']['fix_users_birthday'] = 'Tối ưu module users';
$nv_update_config['lang']['vi']['add_admin_mod_title'] = 'Thêm tiêu đề module cho admin';
$nv_update_config['lang']['vi']['add_cool_phpcaptcha'] = 'Thêm cool php captcha 0.3';
$nv_update_config['lang']['vi']['delete_unuse_file'] = 'Xóa file thừa';
$nv_update_config['lang']['vi']['update_voting'] = 'Nâng cấp module voting';
$nv_update_config['lang']['vi']['update_mod_menu'] = 'Cập nhật module menu';
$nv_update_config['lang']['vi']['update_mod_upload'] = 'Cập nhật module upload';
$nv_update_config['lang']['vi']['update_theme_mobile_nukeviet'] = 'Cập giao diện mobile_nukeviet';
$nv_update_config['lang']['vi']['nv_up_r1749'] = 'Thêm cấu hình hiển thị nguồn tin';
$nv_update_config['lang']['vi']['nv_up_r1767'] = 'Xóa file CSS thừa giao diện admin';
$nv_update_config['lang']['vi']['nv_up_r1780'] = 'Sửa lỗi bảng nhóm thành viên';

// English
$nv_update_config['lang']['en']['update_nukeviet_version'] = 'Update Version and Revision';
$nv_update_config['lang']['en']['fix_users_birthday'] = 'Optimize module users';
$nv_update_config['lang']['en']['add_admin_mod_title'] = 'Add custom title module for Admin';
$nv_update_config['lang']['en']['add_cool_phpcaptcha'] = 'Add cool php captcha 0.3';
$nv_update_config['lang']['en']['delete_unuse_file'] = 'Delete unused files';
$nv_update_config['lang']['en']['update_voting'] = 'Update module voting';
$nv_update_config['lang']['en']['update_mod_menu'] = 'Update module menu';
$nv_update_config['lang']['en']['update_mod_upload'] = 'Update module upload';
$nv_update_config['lang']['en']['update_theme_mobile_nukeviet'] = 'Update theme mobile nukeviet';
$nv_update_config['lang']['en']['nv_up_r1749'] = 'Add config to show article source';
$nv_update_config['lang']['en']['nv_up_r1767'] = 'Delete unused CSS files';
$nv_update_config['lang']['en']['nv_up_r1780'] = 'Fix table groups';

// Require level: 0: Khong bat buoc hoan thanh; 1: Canh bao khi that bai; 2: Bat buoc hoan thanh neu khong se dung nang cap.
// r: Revision neu la nang cap site, phien ban neu la nang cap module

$nv_update_config['tasklist'] = array();
$nv_update_config['tasklist'][] = array( 'r' => 1501, 'rq' => 2, 'l' => 'fix_users_birthday', 'f' => 'nv_up_r1501' );
$nv_update_config['tasklist'][] = array( 'r' => 1559, 'rq' => 2, 'l' => 'add_admin_mod_title', 'f' => 'nv_up_r1559' );
$nv_update_config['tasklist'][] = array( 'r' => 1576, 'rq' => 2, 'l' => 'add_cool_phpcaptcha', 'f' => 'nv_up_r1576' );
$nv_update_config['tasklist'][] = array( 'r' => 1587, 'rq' => 2, 'l' => 'delete_unuse_file', 'f' => 'nv_up_r1587' );
$nv_update_config['tasklist'][] = array( 'r' => 1590, 'rq' => 2, 'l' => 'update_voting', 'f' => 'nv_up_r1590' );
$nv_update_config['tasklist'][] = array( 'r' => 1592, 'rq' => 2, 'l' => 'update_mod_menu', 'f' => 'nv_up_r1592' );
$nv_update_config['tasklist'][] = array( 'r' => 1604, 'rq' => 2, 'l' => 'update_mod_upload', 'f' => 'nv_up_r1604' );
$nv_update_config['tasklist'][] = array( 'r' => 1726, 'rq' => 0, 'l' => 'update_theme_mobile_nukeviet', 'f' => 'nv_up_r1726' );
$nv_update_config['tasklist'][] = array( 'r' => 1749, 'rq' => 2, 'l' => 'nv_up_r1749', 'f' => 'nv_up_r1749' );
$nv_update_config['tasklist'][] = array( 'r' => 1767, 'rq' => 0, 'l' => 'nv_up_r1767', 'f' => 'nv_up_r1767' );
$nv_update_config['tasklist'][] = array( 'r' => 1780, 'rq' => 2, 'l' => 'nv_up_r1780', 'f' => 'nv_up_r1780' );

$nv_update_config['tasklist'][] = array( 'r' => 1783, 'rq' => 2, 'l' => 'update_nukeviet_version', 'f' => 'nv_up_finish' );

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

/**
 * nv_up_r1501()
 * 
 * @return
 */
function nv_up_r1501()
{
	global $nv_update_baseurl, $db;
	
	$check = $db->sql_query( "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "` CHANGE `birthday` `birthday` INT(11) NOT NULL" );
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$return['status'] = $check ? 1 : 0;
	$return['complete'] = $check ? 1 : 0;
	
	return $return;
}

/**
 * nv_up_r1559()
 * 
 * @return
 */
function nv_up_r1559()
{
	global $nv_update_baseurl, $db, $db_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
	if( ! $language_query )
	{
		$return['status'] = 0;
		$return['complete'] = 0;
		return $return;
	}
	
	while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
	{
		$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_voting_rows` ADD `url` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `title`" );
		$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `show_func` = '1' WHERE `in_module`='voting' AND `func_name`='main'" );

		$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_modules` ADD `admin_title` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `custom_title`" );
		$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_modules` ADD `main_file` TINYINT( 1 ) NOT NULL DEFAULT '1' AFTER `set_time`" );

		$db->sql_query( "UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modules` SET `main_file` = '0' WHERE `module_file`='menu'" );
	}
	
	return $return;
}

/**
 * nv_up_r1576()
 * 
 * @return
 */
function nv_up_r1576()
{
	global $nv_update_baseurl, $db, $db_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'captcha_type', '0')" );

	$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
	if( ! $language_query )
	{
		$return['status'] = 0;
		$return['complete'] = 0;
		return $return;
	}
	
	while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', 'global', 'switch_mobi_des', '1')" );
	}
	
	return $return;
}

/**
 * nv_up_r1587()
 * 
 * @return
 */
function nv_up_r1587()
{
	global $nv_update_baseurl;
	
	nv_deletefile( NV_ROOTDIR . '/files/js', true );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/nav_menu.css' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/reset.css' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/tab_info.css' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/footer_bg.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/logo.gif' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_l.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_l.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_r.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_current_r.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_home_l.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_home_r.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_hover_l.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_hover_r.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_l.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_r.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/nav_sub_a.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/navmenu-v_hover.jpg' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/css/news.css' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/arrow_down.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/arrow_left_orange.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/bg_link_mod.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/bg_linked_mod.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_header.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_l.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/cat_r.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/comment.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/comment_add.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/content-cat-title-current.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/content-cat-title-ul.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/icon-news.gif' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/module-header.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/other.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/other_link.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/save_file.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/images/news/user.png' );
	nv_deletefile( NV_ROOTDIR . '/themes/admin_default/modules/menu/config.tpl' );
	nv_deletefile( NV_ROOTDIR . '/modules/menu/theme.php' );
		
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	return $return;
}

/**
 * nv_up_r1590()
 * 
 * @return
 */
function nv_up_r1590()
{
	global $nv_update_baseurl, $db, $db_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
	while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
	{
		$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_voting` ADD `link` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `question`" );
	}
	
	return $return;
}

/**
 * nv_up_r1592()
 * 
 * @return
 */
function nv_up_r1592()
{
	global $nv_update_baseurl, $db, $db_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
	while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
	{
		$mquery = $db->sql_query( "SELECT `title`, `module_data` FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `module_file`='menu'" );
		while( list( $mod, $mod_data ) = $db->sql_fetchrow( $mquery ) )
		{
			$db->sql_query( "ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows` ADD `css` varchar(255) NOT NULL DEFAULT '' AFTER `target`, ADD `active_type` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `css`" );
		}
	}
	
	return $return;
}

/**
 * nv_up_r1604()
 * 
 * @return
 */
function nv_up_r1604()
{
	global $nv_update_baseurl, $db, $db_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologomod', '')" );
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize1', '50')" );
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize2', '40')" );
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autologosize3', '30')" );
	
	return $return;
}

/**
 * nv_up_r1726()
 * 
 * @return
 */
function nv_up_r1726()
{
	global $nv_update_baseurl;
	
	if( is_file( NV_ROOTDIR . '/themes/mobile_nukeviet/default.jpg' ) )
	{
		nv_deletefile( NV_ROOTDIR . '/themes/mobile_nukeviet/default.jpg' );
	}
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	return $return;
}

/**
 * nv_up_r1749()
 * 
 * @return
 */
function nv_up_r1749()
{
	global $nv_update_baseurl, $db, $db_config;
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$language_query = $db->sql_query( "SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1" );
	if( ! $language_query )
	{
		$return['status'] = 0;
		$return['complete'] = 0;
		return $return;
	}
	
	while( list( $lang ) = $db->sql_fetchrow( $language_query ) )
	{
		$mquery = $db->sql_query( "SELECT `title`, `module_data` FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `module_file`='news'" );
		while( list( $mod, $mod_data ) = $db->sql_fetchrow( $mquery ) )
		{
			$db->sql_query( "INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang . "', '" . $mod . "', 'config_source', '0')" );
		}
	}

	return $return;
}

function nv_up_r1767()
{
	global $nv_update_baseurl;
	
	nv_deletefile( NV_ROOTDIR . '/themes/admin_default/admin.css' );
	nv_deletefile( NV_ROOTDIR . '/themes/admin_full/admin.css' );
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	return $return;
}

function nv_up_r1780()
{
	global $nv_update_baseurl, $db;
	
	$check = $db->sql_query( "ALTER TABLE `" . NV_GROUPS_GLOBALTABLE . "` AUTO_INCREMENT=10" );
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$return['status'] = $check ? 1 : 0;
	$return['complete'] = $check ? 1 : 0;
	
	return $return;
}

/**
 * nv_up_r1811() to r1758 -> r1811
 *
 * @return
 */
function nv_up_r1811()
{
	global $nv_update_baseurl, $db;
	
	$check = $db->sql_query( " ALTER TABLE `" . $db_config['prefix'] . "_" . $lang ."_modules` ADD `description` VARCHAR( 255 ) NOT NULL AFTER `mobile`" );

	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	$return['status'] = $check ? 1 : 0;
	$return['complete'] = $check ? 1 : 0;
	
	return $return;
}

function nv_up_finish()
{
	global $nv_update_baseurl, $db, $db_config, $global_config;
	
	$return = array( 'status' => 1, 'complete' => 1, 'next' => 1, 'link' => 'NO', 'lang' => 'NO', 'message' => '', );
	
	//Update revision
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'version', '3.4.01')" );
	$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '1783')" );

	$array_config_rewrite = array(
		'rewrite_optional' => $global_config['rewrite_optional'],
		'rewrite_endurl' => $global_config['rewrite_endurl'],
		'rewrite_exturl' => $global_config['rewrite_exturl']
	);
	
	nv_rewrite_change( $array_config_rewrite );
	nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/searchEngines.xml' );

	nv_save_file_config_global();
	
	return $return;
}

?>