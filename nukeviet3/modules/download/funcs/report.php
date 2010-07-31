<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );
$page_title = $lang_module['report_pagetitle'];

$fileid = $nv_Request->get_int( 'id', 'get,post' );
$url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=view&id=' . $fileid;
$permission = false;
if ( $configdownload['who_view3'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view3'] ) )
{
    $permission = true;
}
if ( $configdownload['who_view3'] == 0 )
{
    $permission = true;
}
if ( $configdownload['who_view3'] == 1 && defined( 'NV_IS_USER' ) )
{
    $permission = true;
}
if ( $configdownload['who_view3'] == 2 && defined( 'NV_IS_ADMIN' ) )
{
    $permission = true;
}
if ( ! $permission )
{
    Header( "Location: " . $url_link );
    die();
}
$action = $nv_Request->get_int( 'action', 'post', '0' );
$er = 0;
$content_n = '';
if ( $action == '1' )
{
    $content_n = filter_text_input( 'content', 'post', '', 1, 255 );
    if ( strlen( $content_n ) >= 5 )
    {
        if ( $db->sql_query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_report(`id`,`content`,`date_up`) VALUES (" . $fileid . "," . $db->dbescape_string( $content_n ) . ",UNIX_TIMESTAMP())" ) )
        {
            $er = 1;
        }
    }
    else
    {
        $er = 2;
    }
}
$contents .= call_user_func( "report", $fileid, $permission, $er, $url_link, $content_n );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>