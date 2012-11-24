<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$img_dir = "default";

if( file_exists( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/images/' . $module_name ) )
{
	$img_dir = $global_config['module_theme'];
}
elseif( file_exists( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/images/' . $module_name ) )
{
	$img_dir = $global_config['site_theme'];
}

$iconrss = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/rss.gif" />';
$imgmid = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line1.gif" />';
$imgmid2 = '<img alt=""  style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line3.gif" />';
$imgbottom = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line2.gif" />';

/**
 * nv_rss_main_theme()
 * 
 * @param mixed $array
 * @return
 */
function nv_rss_main_theme( $array )
{
	global $img_dir, $module_name, $module_info;
	
	$array .= ( $array ? "<br />" : "" ) . "<img  alt=\"\" style=\"border-width: 0px; vertical-align: middle;\" src=\"" . NV_BASE_SITEURL . "themes/" . $img_dir . "/images/" . $module_name . "/home.gif\" /><b>" . $module_info['custom_title'] . "</b><br />";
	
	$array .= nv_get_rss_link();
	
	return $array;
}

?>