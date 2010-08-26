<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_RSS' ) ) die( 'Stop!!!' );

$base_url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$contents .= '<img  style="border-width: 0px; vertical-align: middle;" src="' . NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/' . $module_name . '/home.gif"><b>Nguồn cấp Rss</b><br>';
$result = $db->sql_query( "SELECT title, module_file, custom_title, module_data FROM " . NV_MODULES_TABLE . " WHERE act=1 AND module_file!='rss' ORDER BY weight" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $module_title = $row['title'];
	$custom_title = $row['custom_title'];
    $module_file = $row['module_file'];
    $module_data = $row['module_data'];
    if ( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/rssdata.php' ) )
    {
        $contents .= $imgmid2 . "<a href=\"" . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_title . "&" . NV_OP_VARIABLE . "=" . $module_name . "\">" . $iconrss . "</a>";
        $contents .= '<strong> ' . $custom_title . "</strong><br>";
        include ( NV_ROOTDIR . '/modules/' . $module_file . '/rssdata.php' );
        switch ( $module_file )
        {
            case $module_file:
                foreach ( $rssarray as $key => $value )
                {
                    if ( $value['parentid'] == 0 )
                    {
                        $contents .= $imgmid . $imgmid2 . "<a href=\"" . $value['link'] . "\">" . $iconrss . '</a> ' . $value['title'] . "<br />";
                        if ( ! empty( $value['numsubcat'] ) )
                        {
                            $array_catid = explode( ",", $value['subcatid'] );
                            $contents .= getsubcat( $array_catid, $imgmid . $imgmid );
                        }
                    }
                }
                break;
        }
    }

}

function getsubcat ( $array_catid, $i )
{
    global $rssarray, $imgmid, $imgmid2, $iconrss;
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
                $content .= getsubcat( $array_catid2, $i . $imgmid );
            }
        }
    }
    return $content;
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>