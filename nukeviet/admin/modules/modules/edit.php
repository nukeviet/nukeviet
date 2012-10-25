<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-11-2010 0:44
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$contents = array();

$mod = filter_text_input( 'mod', 'get' );

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
	$custom_title = filter_text_input( 'custom_title', 'post', 1 );
	$admin_title = filter_text_input( 'admin_title', 'post', 1 );
	$theme = filter_text_input( 'theme', 'post', '', 1 );
	$mobile = filter_text_input( 'mobile', 'post', '', 1 );
    $description = filter_text_input( 'description', 'post', '', 1, 255 );
	$keywords = filter_text_input( 'keywords', 'post', '', 1 );
	$act = $nv_Request->get_int( 'act', 'post', 0 );
	$rss = $nv_Request->get_int( 'rss', 'post', 0 );

	if( ! empty( $theme ) and ! in_array( $theme, $theme_list ) ) $theme = "";

	if( ! empty( $mobile ) and ! in_array( $mobile, $theme_mobile_list ) ) $mobile = "";

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

		$groups_view = "";

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

	if( $groups_view != "" and $custom_title != "" )
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
				$contents['error'][] = $_theme;
			}
		}

		if( empty( $contents['error'] ) )
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

			$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `custom_title`=" . $db->dbescape( $custom_title ) . ", `admin_title`=" . $db->dbescape( $admin_title ) . ", `theme`=" . $db->dbescape( $theme ) . ", `mobile`=" . $db->dbescape( $mobile ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`=" . $db->dbescape( $keywords ) . ", `groups_view`=" . $db->dbescape( $groups_view ) . ", `act`='" . $act . "', `rss`='" . $rss . "'WHERE `title`=" . $db->dbescape( $mod );
			$db->sql_query( $sql );

			nv_delete_all_cache();
			nv_insert_logs( NV_LANG_DATA, $module_name, sprintf( $lang_module['edit'], $mod ), '', $admin_info['userid'] );

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
			exit();
		}
		else
		{
			$contents['error'] = sprintf( $lang_module['edit_error_update_theme'], implode( ", ", $contents['error'] ) );
		}
	}
	elseif( $groups_view != "" )
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
	$contents['rss'] = array( $lang_module['activate_rss'], $rss );
}

$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;mod=" . $mod;
$contents['custom_title'] = array( $lang_module['custom_title'], $custom_title, 70 );
$contents['admin_title'] = array( $lang_module['admin_title'], $admin_title, 70 );
$contents['theme'] = array( $lang_module['theme'], $lang_module['theme_default'], $theme_list, $theme );
$contents['mobile'] = array( $lang_module['mobile'], $lang_module['theme_default'], $theme_mobile_list, $mobile );
$contents['act'] = array( $lang_global['activate'], $act );
$contents['description'] = array( $lang_module['description'], $description, 255 );
$contents['keywords'] = array( $lang_module['keywords'], $keywords, 255, $lang_module['keywords_info'] );

if( $mod != $global_config['site_home_module'] )
{
	$contents['who_view'] = array(
		$lang_global['who_view'],
		array(
			$lang_global['who_view0'],
			$lang_global['who_view1'],
			$lang_global['who_view2'],
			$lang_global['who_view3']
		),
		$who_view
	);
	$contents['groups_view'] = array( $lang_global['groups_view'], $groups_list, $groups_view );
}
$contents['submit'] = $lang_global['submit'];

$contents = edit_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>