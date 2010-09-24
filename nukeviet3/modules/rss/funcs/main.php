<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

function nv_get_rss_link ( )
{
    global $db, $module_data, $global_config, $imgmid, $imgmid2, $iconrss;
    $contentrss = "";
    $result = $db->sql_query( "SELECT title, module_file, custom_title, module_data FROM " . NV_MODULES_TABLE . " WHERE act=1 AND module_file!='rss' AND rss=1 ORDER BY weight" );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $module_title = $row['title'];
        $custom_title = $row['custom_title'];
        $module_file = $row['module_file'];
        $module_data = $row['module_data'];
        if ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/rssdata.php' ) )
        {
            $contentrss .= $imgmid2 . "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_title . "&amp;" . NV_OP_VARIABLE . "=rss\">" . $iconrss . "</a>";
            $contentrss .= '<strong> ' . $custom_title . "</strong><br />";
            include ( NV_ROOTDIR . '/modules/' . $module_file . '/rssdata.php' );
            foreach ( $rssarray as $key => $value )
            {
                if ( $value['parentid'] == 0 )
                {
                    $contentrss .= $imgmid . $imgmid2 . "<a href=\"" . $value['link'] . "\">" . $iconrss . '</a> ' . $value['title'] . "<br />";
                    if ( ! empty( $value['numsubcat'] ) )
                    {
                        $array_catid = explode( ",", $value['subcatid'] );
                        $contentrss .= getsubcat( $rssarray, $array_catid, $imgmid . $imgmid );
                    }
                }
            }
        }
    
    }
    return $contentrss;
}

function getsubcat ( $rssarray, $array_catid, $i )
{
    global $imgmid, $imgmid2, $iconrss;
    $content = '';
    foreach ( $array_catid as $sub_catid )
    {
        if ( isset( $rssarray[$sub_catid] ) )
        {
            $content .= $i . $imgmid2 . "<a href=\"" . $rssarray[$sub_catid]['link'] . "\">" . $iconrss . '</a> ' . $rssarray[$sub_catid]['title'] . "<br />";
            if ( ! empty( $rssarray[$sub_catid]['numsubcat'] ) )
            {
                $array_sub_cat = $rssarray[$sub_catid];
                $array_catid2 = explode( ",", $array_sub_cat['subcatid'] );
                $content .= getsubcat( $rssarray, $array_catid2, $i . $imgmid );
            }
        }
    }
    return $content;
}

$contents = "";
$content_file = NV_ROOTDIR . '/' . NV_DATADIR . '/' . NV_LANG_DATA . '_' . $module_data . 'Content.txt';
if ( file_exists( $content_file ) )
{
    $contents = file_get_contents( $content_file );
}
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$contents .= '<img  alt="" style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_name . '/home.gif" /><b>' . $module_info['custom_title'] . '</b><br />';

$contents .= nv_get_rss_link();

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>