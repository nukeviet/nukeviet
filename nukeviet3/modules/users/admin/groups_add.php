<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 14:52
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_groups'] . " -> " . $lang_module['nv_admin_add'];

if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$error = "";
if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $title = $nv_Request->get_string( 'title', 'post', '' );
    $content = defined( 'NV_EDITOR' ) ? $nv_Request->get_string( 'content', 'post', '' ) : filter_text_input( 'content', 'post', '' );
    $public = $nv_Request->get_int( 'public', 'post', 0 );
    $min = $nv_Request->get_int( 'min', 'post', 0 );
    $hour = $nv_Request->get_int( 'hour', 'post', 0 );
    $day = $nv_Request->get_int( 'day', 'post', 0 );
    $month = $nv_Request->get_int( 'month', 'post', 0 );
    $year = $nv_Request->get_int( 'year', 'post', 0 );
    if ( empty( $title ) )
    {
        $error = $lang_module['title_empty'];
    }
    elseif ( $db->sql_numrows( $db->sql_query( "SELECT `group_id` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `title`=" . $db->dbescape( $title ) ) ) > 0 )
    {
        $error = sprintf( $lang_module['error_title_exists'], $title );
    }
    else
    {
        if ( ! empty( $content ) )
        {
            $content = defined( 'NV_EDITOR' ) ? nv_nl2br( $content, '' ) : nv_nl2br( nv_htmlspecialchars( $content ), '<br />' );
        }
        $exp_time = ( ! $day or ! $month or ! $year ) ? 0 : mktime( $hour, $min, 0, $month, $day, $year );
        
        $sql = "INSERT INTO `" . NV_GROUPS_GLOBALTABLE . "` ( `group_id` ,`title` ,`content` ,`add_time` ,`exp_time` ,`users` ,`public` ,`act` ) VALUES (
			NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $content ) . ", " . NV_CURRENTTIME . ", " . $exp_time . ", 
			'', " . $public . ", 1)";
        if ( $db->sql_query_insert_id( $sql ) )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=groups" );
            die();
        }
    }
}
else
{
    $title = $content = "";
    $public = $min = $hour = $day = $month = $year = 0;
}
if ( ! empty( $content ) ) $content = nv_htmlspecialchars( $content );

$contents = array();
$contents['is_error'] = ! empty( $error ) ? 1 : 0;
$contents['caption'] = ! empty( $error ) ? $error : $lang_module['nv_admin_add_caption'];
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
$contents['title'] = array( 
    $lang_module['title'], $title, 255 
);
$contents['content'] = array( 
    $lang_module['content'], $content, '100%', '300px', defined( 'NV_EDITOR' ) ? true : false 
);
$contents['exp_time'] = array( 
    $lang_module['exp_time'], $day, $month, $year, $hour, $min, $lang_global['day'], $lang_global['month'], $lang_global['year'], $lang_global['hour'], $lang_global['min'] 
);
$contents['public'] = array( 
    $lang_module['public'], $public 
);
$contents['submit'] = $lang_global['submit'];
$contents = nv_groups_add_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>