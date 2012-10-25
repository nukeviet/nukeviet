<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['setting'];

$savesetting = $nv_Request->get_int( 'savesetting', 'post', 0 );
if( ! empty( $savesetting ) )
{
	$array_config = array();
	$array_config['indexfile'] = filter_text_input( 'indexfile', 'post', '', 1 );
	$array_config['per_page'] = $nv_Request->get_int( 'per_page', 'post', 0 );
	$array_config['st_links'] = $nv_Request->get_int( 'st_links', 'post', 0 );
	$array_config['homewidth'] = $nv_Request->get_int( 'homewidth', 'post', 0 );
	$array_config['homeheight'] = $nv_Request->get_int( 'homeheight', 'post', 0 );
	$array_config['blockwidth'] = $nv_Request->get_int( 'blockwidth', 'post', 0 );
	$array_config['blockheight'] = $nv_Request->get_int( 'blockheight', 'post', 0 );
	$array_config['imagefull'] = $nv_Request->get_int( 'imagefull', 'post', 0 );

	$array_config['activecomm'] = $nv_Request->get_int( 'activecomm', 'post', 0 );
	$array_config['emailcomm'] = $nv_Request->get_int( 'emailcomm', 'post', 0 );
	$array_config['auto_postcomm'] = $nv_Request->get_int( 'auto_postcomm', 'post', 0 );
	$array_config['setcomm'] = $nv_Request->get_int( 'setcomm', 'post', 0 );
	$array_config['copyright'] = filter_text_input( 'copyright', 'post', '', 1 );
	$array_config['showhometext'] = $nv_Request->get_int( 'showhometext', 'post', 0 );
	$array_config['module_logo'] = filter_text_input( 'module_logo', 'post', '', 0 );
	$array_config['structure_upload'] = filter_text_input( 'structure_upload', 'post', '', 0 );
	$array_config['config_source'] = $nv_Request->get_int( 'config_source', 'post', 0 );

	if( ! nv_is_url( $array_config['module_logo'] ) and file_exists( NV_DOCUMENT_ROOT . $array_config['module_logo'] ) )
	{
		$lu = strlen( NV_BASE_SITEURL );
		$array_config['module_logo'] = substr( $array_config['module_logo'], $lu );
	}
	elseif( ! nv_is_url( $array_config['module_logo'] ) )
	{
		$array_config['module_logo'] = $global_config['site_logo'];
	}

	foreach( $array_config as $config_name => $config_value )
	{
		$db->sql_query( "REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES('" . NV_LANG_DATA . "', " . $db->dbescape( $module_name ) . ", " . $db->dbescape( $config_name ) . ", " . $db->dbescape( $config_value ) . ")" );
	}
	$db->sql_freeresult();

	nv_del_moduleCache( 'settings' );
	nv_del_moduleCache( $module_name );
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
	die();
}

$module_logo = ( isset( $module_config[$module_name]['module_logo'] ) ) ? $module_config[$module_name]['module_logo'] : $global_config['site_logo'];
$module_logo = ( ! nv_is_url( $module_logo ) ) ? NV_BASE_SITEURL . $module_logo : $module_logo;

$xtpl = new XTemplate( "settings.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $module_config[$module_name] );

// Cach hien thi tren trang chu
foreach( $array_viewcat_full as $key => $val )
{
	$xtpl->assign( 'INDEXFILE', array( "key" => $key, "title" => $val, "selected" => $key == $module_config[$module_name]['indexfile'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.indexfile' );
}

// So bai viet tren mot trang
for( $i = 5; $i <= 30; ++ $i )
{
	$xtpl->assign( 'PER_PAGE', array( "key" => $i, "title" => $i, "selected" => $i == $module_config[$module_name]['per_page'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.per_page' );
}

// Bai viet chi hien thi link
for( $i = 0; $i <= 20; ++$i )
{
	$xtpl->assign( 'ST_LINKS', array( "key" => $i, "title" => $i, "selected" => $i == $module_config[$module_name]['st_links'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.st_links' );
}

$xtpl->assign( 'SHOWHOMETEXT', $module_config[$module_name]['showhometext'] ? " checked=\"checked\"" : "" );
$xtpl->assign( 'ACTIVECOMM', $module_config[$module_name]['activecomm'] ? " checked=\"checked\"" : "" );
$xtpl->assign( 'AUTO_POSTCOMM', $module_config[$module_name]['auto_postcomm'] ? " checked=\"checked\"" : "" );
$xtpl->assign( 'EMAILCOMM', $module_config[$module_name]['emailcomm'] ? " checked=\"checked\"" : "" );
$xtpl->assign( 'MODULE_LOGO', $module_logo );

// Thao luan mac dinh khi tao bai viet moi
while( list( $comm_i, $title_i ) = each( $array_allowed_comm ) )
{
	$xtpl->assign( 'SETCOMM', array( "key" => $comm_i, "title" => $title_i, "selected" => $comm_i == $module_config[$module_name]['setcomm'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.setcomm' );
}

$array_structure_image = array();
$array_structure_image[''] = NV_UPLOADS_DIR . '/' . $module_name;
$array_structure_image['Y'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin';

$array_structure_image['username_Y'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin/' . date( 'Y' );
$array_structure_image['username_Ym'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_name . '/username_admin/' . date( 'Y/m/d' );

$structure_image_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : "Ym";

// Thu muc uploads
foreach( $array_structure_image as $type => $dir )
{
	$xtpl->assign( 'STRUCTURE_UPLOAD', array( "key" => $type, "title" => $dir, "selected" => $type == $structure_image_upload ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.structure_upload' );
}

// Cau hinh hien thi nguon tin
$array_config_source = array( $lang_module['config_source_title'], $lang_module['config_source_link'], $lang_module['config_source_logo'] );
foreach( $array_config_source as $key => $val )
{
	$xtpl->assign( 'CONFIG_SOURCE', array( "key" => $key, "title" => $val, "selected" => $key == $module_config[$module_name]['config_source'] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.config_source' );
}

$xtpl->assign( 'PATH', defined( "NV_IS_SPADMIN" ) ? "" : NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'CURRENTPATH', defined( "NV_IS_SPADMIN" ) ? "images" : NV_UPLOADS_DIR . '/' . $module_name );

$contents .= 'nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type+"&currentpath="+currentpath, "NVImg", "850", "420","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");';
$contents .= 'return false;';
$contents .= '});';
$contents .= "\n//]]>\n</script>\n";

if( defined( 'NV_IS_ADMIN_FULL_MODULE' ) or ! in_array( 'admins', $allow_func ) )
{
	$savepost = $nv_Request->get_int( 'savepost', 'post', 0 );
	if( ! empty( $savepost ) )
	{
		$array_config = array();
		$array_pid = $nv_Request->get_typed_array( 'array_pid', 'post' );
		$array_addcontent = $nv_Request->get_typed_array( 'array_addcontent', 'post' );
		$array_postcontent = $nv_Request->get_typed_array( 'array_postcontent', 'post' );
		$array_editcontent = $nv_Request->get_typed_array( 'array_editcontent', 'post' );
		$array_delcontent = $nv_Request->get_typed_array( 'array_delcontent', 'post' );

		foreach( $array_pid as $pid )
		{
			$addcontent = ( isset( $array_addcontent[$pid] ) and intval( $array_addcontent[$pid] ) == 1 ) ? 1 : 0;
			$postcontent = ( isset( $array_postcontent[$pid] ) and intval( $array_postcontent[$pid] ) == 1 ) ? 1 : 0;
			$editcontent = ( isset( $array_editcontent[$pid] ) and intval( $array_editcontent[$pid] ) == 1 ) ? 1 : 0;
			$delcontent = ( isset( $array_delcontent[$pid] ) and intval( $array_delcontent[$pid] ) == 1 ) ? 1 : 0;
			$addcontent = ( $postcontent == 1 ) ? 1 : $addcontent;
			$db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config_post` SET `addcontent` = '" . $addcontent . "', `postcontent` = '" . $postcontent . "', `editcontent` = '" . $editcontent . "', `delcontent` = '" . $delcontent . "' WHERE `pid` =" . $pid . " LIMIT 1" );
		}

		nv_del_moduleCache( 'settings' );
		nv_del_moduleCache( $module_name );
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&rand=" . nv_genpass() );
		die();
	}

	$array_post_title = array();
	$array_post_title[0][0] = $lang_global['who_view0'];

	$array_post_title[1][0] = $lang_global['who_view1'];

	$groups_list = nv_groups_list();
	foreach( $groups_list as $group_id => $grtl )
	{
		$array_post_title[1][$group_id] = $grtl;
	}

	$array_post_member = array();
	$array_post_data = array();

	$sql = "SELECT pid, member, group_id, addcontent, postcontent, editcontent, delcontent FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config_post` ORDER BY `pid` ASC";
	$result = $db->sql_query( $sql );
	while( list( $pid, $member, $group_id, $addcontent, $postcontent, $editcontent, $delcontent ) = $db->sql_fetchrow( $result ) )
	{
		if( isset( $array_post_title[$member][$group_id] ) )
		{
			$array_post_member[$member][$group_id] = $pid;
			$array_post_data[$pid] = array(
				"pid" => $pid,
				"member" => $member,
				"group_id" => $group_id,
				"addcontent" => $addcontent,
				"postcontent" => $postcontent,
				"editcontent" => $editcontent,
				"delcontent" => $delcontent );
		}
		else
		{
			$db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config_post` WHERE `pid` = " . $pid . " LIMIT 1" );
		}
	}
	
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
	
	$a = 0;

	foreach( $array_post_title as $member => $array_post_1 )
	{
		foreach( $array_post_1 as $group_id => $array_post_2 )
		{
			++ $a;
			
			$pid = ( isset( $array_post_member[$member][$group_id] ) ) ? $array_post_member[$member][$group_id] : 0;
			if( $pid > 0 )
			{
				$addcontent = $array_post_data[$pid]['addcontent'];
				$postcontent = $array_post_data[$pid]['postcontent'];
				$editcontent = $array_post_data[$pid]['editcontent'];
				$delcontent = $array_post_data[$pid]['delcontent'];
			}
			else
			{
				$addcontent = $postcontent = $editcontent = $delcontent = 0;
				$pid = $db->sql_query_insert_id( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_config_post` (`pid`,`member`, `group_id`,`addcontent`,`postcontent`,`editcontent`,`delcontent`) VALUES (NULL , '" . $member . "', '" . $group_id . "', '" . $addcontent . "', '" . $postcontent . "', '" . $editcontent . "', '" . $delcontent . "'  )" );
			}
			
			$xtpl->assign( 'ROW', array(
				"class" => ( $a % 2 == 0 ) ? "" : " class=\"second\"",
				"array_post_2" => $array_post_2,
				"pid" => $pid,
				"addcontent" => $addcontent ? " checked=\"checked\"" : "",
				"postcontent" => $postcontent ? " checked=\"checked\"" : "",
				"editcontent" => $editcontent ? " checked=\"checked\"" : "",
				"delcontent" => $delcontent ? " checked=\"checked\"" : "",
			) );
			
			$xtpl->parse( 'main.admin_config_post.loop' );
		}
	}
	
	$xtpl->parse( 'main.admin_config_post' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>