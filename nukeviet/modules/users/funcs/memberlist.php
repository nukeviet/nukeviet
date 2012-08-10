<?php

/**
 * @Project NUKEVIET 3.4
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2012 VINADES.,JSC. All rights reserved
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );
$page_title = $module_info['funcs'][$op]['func_custom_name'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['listusers'] ) ? $lang_module['listusers'] : $page_title;

$uid = $nv_Request->get_int( 'uid', 'get', 0 );

if( $uid > 0 )
{
	$query = $db->sql_query( "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` = " . $uid );
	if( $db->sql_numrows( $query ) > 0 )
	{
		$item = $db->sql_fetch_assoc( $query );
		$contents = nv_memberslist_detail_theme( $item );
	}
	else
	{
		$nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=memberlist";

		$info = "<div style=\"text-align:center;\">";
		$info .= $lang_module['notuser'] . "<br /><br />\n";
		$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
		$info .= "</div>";
		$contents = $info;
		$contents .= "<meta http-equiv=\"refresh\" content=\"3;url=" . nv_url_rewrite( $nv_redirect ) . "\" />";
	}
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

if( $global_config['whoviewuser'] == 2 && ! defined( "NV_IS_ADMIN" ) )
{
	$nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

	$info = "<div style=\"text-align:center;\">";
	$info .= $lang_module['allow_admin'] . "<br /><br />\n";
	$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
	$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_home'] . "</a>]";
	$info .= "</div>";
	$contents = $info;
	$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( $nv_redirect ) . "\" />";

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif( $global_config['whoviewuser'] == 1 && ! defined( 'NV_IS_USER' ) )
{
	$nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

	$info = "<div style=\"text-align:center;\">";
	$info .= $lang_module['allow_user'] . "<br /><br />\n";
	$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
	$info .= "[<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $lang_module['redirect_to_home'] . "</a>]";
	$info .= "</div>";
	$contents = $info;
	$contents .= "<meta http-equiv=\"refresh\" content=\"5;url=" . nv_url_rewrite( $nv_redirect ) . "\" />";

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    $orderby = $nv_Request->get_string( 'orderby', 'get', 'username' );
    $sortby = $nv_Request->get_string( 'sortby', 'get', 'DESC' );
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=" . $orderby . "&sortby=" . $sortby;
    
    $per_page = 25;
    $array_order = array(
        "username" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=username&sortby=" . $sortby,
        "gender" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=gender&sortby=" . $sortby,
        "regdate" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=regdate&sortby=" . $sortby,
        
    );
    
    foreach( $array_order as $key => $link ){
       if( $orderby == $key ){
        $sortby_new = ( $sortby == "DESC" )? "ASC" : "DESC";
        $array_order_new[$key] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&orderby=" . $key . "&sortby=" . $sortby_new;
       } else{
            $array_order_new[$key] = $link;
       }
    }
	$query = $db->sql_query( "SELECT SQL_CALC_FOUND_ROWS `userid`, `username`, `full_name`, `photo`, `gender`, `yim`, `regdate` FROM `" . NV_USERS_GLOBALTABLE . "` ORDER BY " . $orderby . " " . $sortby . " LIMIT " . $page . "," . $per_page );
	$result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
	list( $all_page ) = $db->sql_fetchrow( $result_all );
	while( $item = $db->sql_fetch_assoc( $query ) )
	{
		if( ! empty( $item['photo'] ) and file_exists( NV_ROOTDIR . "/" . $item['photo'] ) )
		{
			$item['photo'] = NV_BASE_SITEURL . $item['photo'];
		}
		else
		{
			$item['photo'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/no_avatar.jpg";
		}
		$item['regdate'] = nv_date( "d/m/Y", $item['regdate'] );
		$item['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=memberlist&uid=" . $item['userid'];
		$item['gender'] = ( $item['gender'] == "M" ) ? $lang_module['male'] : ( $item['gender'] == 'F' ? $lang_module['female'] : $lang_module['na'] );
		$users_array[$item['userid']] = $item;
	}
    
    $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
    
	$db->sql_freeresult( $query );
	unset( $query, $row );
	$contents = nv_memberslist_theme( $users_array, $array_order_new, $generate_page );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>