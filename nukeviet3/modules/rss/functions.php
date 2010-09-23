<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_RSS', true );
$img_dir = $global_config['site_theme'];
if ( ! file_exists( NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_name ) )
{
    $img_dir = "default";
}

$iconrss = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/rss.gif" />';
$imgmid = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line1.gif" />';
$imgmid2 = '<img alt=""  style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line3.gif" />';
$imgbottom = '<img alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $img_dir . '/images/' . $module_name . '/line2.gif" />';
?>