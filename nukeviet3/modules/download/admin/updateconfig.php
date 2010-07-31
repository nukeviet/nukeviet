<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$data['deslimit'] = $nv_Request->get_int( 'deslimit', 'post', 0 );
$data['showemail'] = $nv_Request->get_int( 'showemail', 'post', 0 );
$data['textlimit'] = $nv_Request->get_int( 'textlimit', 'post', 0 );
$data['directlink'] = $nv_Request->get_int( 'directlink', 'post', 0 );
$data['showmessage'] = $nv_Request->get_int( 'showmessage', 'post', 0 );
$data['messagecontent'] = htmlspecialchars( strip_tags( $nv_Request->get_string( 'messagecontent', 'post', '' ) ) );
$data['showsubfolder'] = $nv_Request->get_int( 'showsubfolder', 'post', 0 );
$data['numsubfolder'] = $nv_Request->get_int( 'numsubfolder', 'post', 0 );
$data['numfile'] = $nv_Request->get_int( 'numfile', 'post', 0 );
$data['who_view1'] = $nv_Request->get_int( 'who_view1', 'post', 0 );
$data['groups_view1'] = $nv_Request->get_string( 'groups_view1', 'post', '' );
$data['showcaptcha'] = $nv_Request->get_int( 'showcaptcha', 'post', 0 );
$data['who_view2'] = $nv_Request->get_int( 'who_view2', 'post', 0 );
$data['groups_view2'] = $nv_Request->get_string( 'groups_view2', 'post', '' );
$data['who_view3'] = $nv_Request->get_int( 'who_view3', 'post', 0 );
$data['groups_view3'] = $nv_Request->get_string( 'groups_view3', 'post', '' );
$data['who_view4'] = $nv_Request->get_int( 'who_view4', 'post', 0 );
$data['groups_view4'] = $nv_Request->get_string( 'groups_view4', 'post', '' );
$data['who_view5'] = $nv_Request->get_int( 'who_view5', 'post', 0 );
$data['groups_view5'] = $nv_Request->get_string( 'groups_view5', 'post', '' );
$data['who_view6'] = $nv_Request->get_int( 'who_view6', 'post', 0 );
$data['groups_view6'] = $nv_Request->get_string( 'groups_view6', 'post', '' );
$data['maxfilesize'] = $nv_Request->get_int( 'maxfilesize', 'post', 0 );
list( $olddir ) = $db->sql_fetchrow( $db->sql_query( "SELECT value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config` WHERE name='filedir'" ) );
list( $oldtempdir ) = $db->sql_fetchrow( $db->sql_query( "SELECT value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config` WHERE name='filetempdir'" ) );
if ( is_writeable( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $olddir . '' ) && is_writeable( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $oldtempdir . '' ) )
{
    $data['filedir'] = htmlspecialchars( strip_tags( $nv_Request->get_string( 'filedir', 'post', '' ) ) );
    $data['filetempdir'] = htmlspecialchars( strip_tags( $nv_Request->get_string( 'filetempdir', 'post', '' ) ) );
    if ( ! rename( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $olddir . '', '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['filedir'] . '' ) )
    {
        echo $lang_module['updateconfig_error_dirdown'];
        $data['filedir'] = $olddir;
    }
    if ( ! rename( '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $oldtempdir . '', '' . NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $data['filetempdir'] . '' ) )
    {
        echo $lang_module['updateconfig_error_dirqueue'];
        $data['filetempdir'] = $oldtempdir;
    }
}
else
{
    $data['filedir'] = $olddir;
    $data['filetempdir'] = $oldtempdir;
}
$data['filetype'] = htmlspecialchars( strip_tags( $nv_Request->get_string( 'filetype', 'post', '' ) ) );
foreach ( $data as $key => $value )
{
    $result = $db->sql_query( "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_config` VALUES (" . $db->dbescape( $key ) . "," . $db->dbescape( $value ) . ")" );
}
if ( $result ) echo $lang_module['updateconfig_error_save'];
else echo $lang_module['updateconfig_error_error'];
?>