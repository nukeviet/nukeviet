<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-11-2010 0:44
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$contents = array();

$mod = $nv_Request->get_title( 'mod', 'get' );

if( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}

$sql = "SELECT * FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $sql );

if( $db->sql_numrows( $result ) != 1 )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
	die();
}
$row = $db->sql_fetch_assoc( $result );

$theme_site_array = $theme_mobile_array = array();
$theme_array = scandir( NV_ROOTDIR . "/themes" );

foreach( $theme_array as $dir )
{
	if( preg_match( $global_config['check_theme'], $dir ) )
	{
		if( file_exists( NV_ROOTDIR . '/themes/' . $dir . '/config.ini' ) )
		{
			$theme_site_array[] = $dir;
		}
	}
	elseif( preg_match( $global_config['check_theme_mobile'], $dir ) )
	{
		if( file_exists( NV_ROOTDIR . '/themes/' . $dir . '/config.ini' ) )
		{
			$theme_mobile_array[] = $dir;
		}
	}
}

$theme_list = $theme_mobile_list = $array_theme = array();

// Chi nhung giao dien da duoc thiet lap layout moi duoc them
$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `func_id`=0";
$result = $db->sql_query( $sql );

while( list( $theme ) = $db->sql_fetchrow( $result ) )
{
	$theme = $db->unfixdb( $theme );

	if( in_array( $theme, $theme_site_array ) )
	{
		$array_theme[] = $theme;
		$theme_list[] = $theme;
	}
	elseif( in_array( $theme, $theme_mobile_array ) )
	{
		$array_theme[] = $theme;
		$theme_mobile_list[] = $theme;
	}
}

$groups_list = nv_groups_list();

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$custom_title = $nv_Request->get_title( 'custom_title', 'post', 1 );
	$admin_title = $nv_Request->get_title( 'admin_title', 'post', 1 );
	$theme = $nv_Request->get_title( 'theme', 'post', '', 1 );
	$mobile = $nv_Request->get_title( 'mobile', 'post', '', 1 );
	$description = $nv_Request->get_title( 'description', 'post', '', 1 );
	$description = nv_substr( $description, 0, 255 );
	$keywords = $nv_Request->get_title( 'keywords', 'post', '', 1 );
	$act = $nv_Request->get_int( 'act', 'post', 0 );
	$rss = $nv_Request->get_int( 'rss', 'post', 0 );
	
	if( ! empty( $theme ) and ! in_array( $theme, $theme_list ) ) $theme = '';

	if( ! empty( $mobile ) and ! in_array( $mobile, $theme_mobile_list ) ) $mobile = '';

	if( ! empty( $keywords ) )
	{
		$keywords = explode( ",", $keywords );
		$keywords = array_map( "trim", $keywords );
		$keywords = implode( ", ", $keywords );
	}

	if( $mod != $global_config['site_home_module'] )
	{
		$who_view = $nv_Request->get_int( 'who_view', 'post', 0 );

		if( $who_view < 0 or $who_view > 3 ) $who_view = 0;

		$groups_view = '';

		if( $who_view == 3 )
		{
			$groups_view = $nv_Request->get_array( 'groups_view', 'post', array() );
			$groups_view = ! empty( $groups_view ) ? implode( ",", array_map( "intval", $groups_view ) ) : "";
		}
		else
		{
			$groups_view = ( string )$who_view;
		}
	}
	else
	{
		$act = 1;
		$who_view = 0;
		$groups_view = "0";
	}

	if( $groups_view != '' and $custom_title != '' )
	{
		$array_layoutdefault = array();

		foreach( $array_theme as $_theme )
		{
			$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $_theme . '/config.ini' );
			$layoutdefault = ( string )$xml->layoutdefault;

			if( ! empty( $layoutdefault ) and file_exists( NV_ROOTDIR . "/themes/" . $_theme . "/layout/layout." . $layoutdefault . ".tpl" ) )
			{
				$array_layoutdefault[$_theme] = $layoutdefault;
			}
			else
			{
				$data['error'][] = $_theme;
			}
		}

		if( empty( $data['error'] ) )
		{
			foreach( $array_layoutdefault as $selectthemes => $layoutdefault )
			{
				$array_func_id = array();
				$fnsql = "SELECT `func_id` FROM `" . NV_PREFIXLANG . "_modthemes` WHERE `theme`=" . $db->dbescape( $selectthemes );
				$fnresult = $db->sql_query( $fnsql );

				while( list( $func_id ) = $db->sql_fetchrow( $fnresult ) )
				{
					$array_func_id[] = $func_id;
				}

				$fnsql = "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `in_module`=" . $db->dbescape( $mod ) . " AND `show_func`='1' ORDER BY `subweight` ASC";
				$fnresult = $db->sql_query( $fnsql );

				while( list( $func_id ) = $db->sql_fetchrow( $fnresult ) )
				{
					if( ! in_array( $func_id, $array_func_id ) )
					{
						$db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_modthemes` (`func_id`, `layout`, `theme`) VALUES (" . $func_id . "," . $db->dbescape( $layoutdefault ) . ", " . $db->dbescape( $selectthemes ) . ")" );
					}
				}
			}

			$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `custom_title`=" . $db->dbescape( $custom_title ) . ", `admin_title`=" . $db->dbescape( $admin_title ) . ", `theme`=" . $db->dbescape( $theme ) . ", `mobile`=" . $db->dbescape( $mobile ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`=" . $db->dbescape( $keywords ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `act`='" . $act . "', `rss`='" . $rss . "' WHERE `title`=" . $db->dbescape( $mod );
			$db->sql_query( $sql );
			
			$mod_name = change_alias( $nv_Request->get_title( 'mod_name', 'post' ) );
			if( $mod_name != $mod AND preg_match( $global_config['check_module'], $mod_name ) )
			{
				if( $db->sql_query("UPDATE `" . NV_MODULES_TABLE . "` SET `title`=" . $db->dbescape( $mod_name ) . " WHERE `title`=" . $db->dbescape( $mod ) ) )
				{
					$db->sql_query("UPDATE `" . NV_MODFUNCS_TABLE . "` SET `in_module`=" . $db->dbescape( $mod_name ) . " WHERE `in_module`=" . $db->dbescape( $mod ) );
				}
			}
			nv_delete_all_cache();
			nv_insert_logs( NV_LANG_DATA, $module_name, sprintf( $lang_module['edit'], $mod ), '', $admin_info['userid'] );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
			exit();
		}
		else
		{
			$data['error'] = sprintf( $lang_module['edit_error_update_theme'], implode( ", ", $data['error'] ) );
		}
	}
	elseif( $groups_view != '' )
	{
		$row['groups_view'] = $groups_view;
	}
}
else
{
	$custom_title = $row['custom_title'];
	$admin_title = $row['admin_title'];
	$theme = $row['theme'];
	$mobile = $row['mobile'];
	$act = $row['act'];
	$description = $row['description'];
	$keywords = $row['keywords'];
	$rss = $row['rss'];
}

$who_view = 3;
$groups_view = array();

if( $row['groups_view'] == "0" or $row['groups_view'] == "1" or $row['groups_view'] == "2" )
{
	$who_view = intval( $row['groups_view'] );
}
else
{
	$groups_view = array_map( "intval", explode( ",", $row['groups_view'] ) );
}

if( empty( $custom_title ) ) $custom_title = $mod;

$page_title = sprintf( $lang_module['edit'], $mod );

if( file_exists( NV_ROOTDIR . "/modules/" . $db->unfixdb( $row['module_file'] ) . "/funcs/rss.php" ) )
{
	$data['rss'] = array( $lang_module['activate_rss'], $rss );
}

$data['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;mod=" . $mod;
$data['custom_title'] = $custom_title;
$data['admin_title'] = $admin_title;
$data['theme'] = array( $lang_module['theme'], $lang_module['theme_default'], $theme_list, $theme );
$data['mobile'] = array( $lang_module['mobile'], $lang_module['theme_default'], $theme_mobile_list, $mobile );
$data['description'] = $description;
$data['keywords'] = $keywords;
$data['mod_name'] = $mod;

if( $mod != $global_config['site_home_module'] )
{
	$data['who_view'] = array( $lang_global['who_view'], array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] ), $who_view );
	$data['groups_view'] = array( $lang_global['groups_view'], $groups_list, $groups_view );
}
$data['submit'] = $lang_global['submit'];

$xtpl = new XTemplate( "edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data );

if( ! empty( $data['error'] ) )
{
	$xtpl->parse( 'main.error' );
}

foreach( $data['theme'][2] as $tm )
{
	$xtpl->assign( 'THEME', array( 'key' => $tm, 'selected' => $tm == $data['theme'][3] ? " selected=\"selected\"" : "" ) );
	$xtpl->parse( 'main.theme' );
}

if( ! empty( $data['mobile'][2] ) )
{
	foreach( $data['mobile'][2] as $tm )
	{
		$xtpl->assign( 'MOBILE', array( 'key' => $tm, 'selected' => $tm == $data['mobile'][3] ? " selected=\"selected\"" : "" ) );
		$xtpl->parse( 'main.mobile.loop' );
	}

	$xtpl->parse( 'main.mobile' );
}

if( isset( $data['who_view'] ) )
{
	foreach( $data['who_view'][1] as $k => $w )
	{
		$xtpl->assign( 'WHO_VIEW', array(
			'key' => $k,
			'selected' => $k == $data['who_view'][2] ? " selected=\"selected\"" : "",
			'title' => $w
		) );
		$xtpl->parse( 'main.who_view.loop' );
	}

	$xtpl->assign( 'DISPLAY', $data['who_view'][2] == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );

	foreach( $data['groups_view'][1] as $group_id => $grtl )
	{
		$xtpl->assign( 'GROUPS_VIEW', array(
			'key' => $group_id,
			'checked' => in_array( $group_id, $data['groups_view'][2] ) ? " checked=\"checked\"" : "",
			'title' => $grtl
		) );

		$xtpl->parse( 'main.who_view.groups_view' );
	}

	$xtpl->parse( 'main.who_view' );
}

$xtpl->assign( 'ACTIVE', ( $act == 1 ) ? ' checked="checked"' : '' );

if( isset( $data['rss'] ) )
{
	$xtpl->assign( 'RSS', ( $data['rss'][1] == 1 ) ? ' checked="checked"' : '' );
	$xtpl->parse( 'main.rss' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . '/includes/header.php' );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . '/includes/footer.php' );

?>