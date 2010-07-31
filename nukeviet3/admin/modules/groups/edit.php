<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:21
 */

if ( ! defined( 'NV_IS_FILE_GROUPS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['nv_admin_edit'];

if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

$group_id = $nv_Request->get_int( 'group_id', 'get', 0 );

if ( empty( $group_id ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$query = "SELECT * FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( empty( $numrows ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$row = $db->sql_fetchrow( $result );

$error = "";
if ( $nv_Request->get_int( 'save', 'post' ) == 1)
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $content = filter_text_textarea( 'content', '', NV_ALLOWED_HTML_TAGS );
    $public = $nv_Request->get_int( 'public', 'post' );
    $min = $nv_Request->get_int( 'min', 'post' );
    $hour = $nv_Request->get_int( 'hour', 'post' );
    $day = $nv_Request->get_int( 'day', 'post' );
    $month = $nv_Request->get_int( 'month', 'post' );
    $year = $nv_Request->get_int( 'year', 'post' );
    
    if ( empty( $title ) )
    {
        $error = $lang_module['title_empty'];
    }
    elseif ( $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`!=" . $group_id . " AND `title`=" . $db->dbescape( $title ) ) ) > 0 )
    {
        $error = sprintf( $lang_module['error_title_exists'], $title );
    }
    else
    {
        $content = nv_editor_nl2br( $content );
        $exp_time = ( ! $day or ! $month or ! $year ) ? 0 : mktime( $hour, $min, 0, $month, $day, $year );
        
        $sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` 
            SET `title`=" . $db->dbescape( $title ) . ", `content`=" . $db->dbescape( $content ) . ", `exp_time`=" . $exp_time . ", `public`=" . $public . " 
            WHERE `group_id`=" . $group_id;
        $db->sql_query( $sql );
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
}
else
{
    $title = $row['title'];
    $content = nv_editor_br2nl( $row['content'] );
    $public = intval( $row['public'] );
    $exp_time = intval( $row['exp_time'] );
    if ( empty( $exp_time ) ) $min = $hour = $day = $month = $year = 0;
    else list( $min, $hour, $day, $month, $year ) = explode( ",", date( "i,G,j,n,Y", $exp_time ) );
}

$content = nv_htmlspecialchars( $content );

$contents = array();
$contents['is_error'] = ! empty( $error ) ? 1 : 0;
$contents['caption'] = ! empty( $error ) ? $error : sprintf( $lang_module['nv_admin_edit_caption'], $row['title'] );
$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;group_id=" . $group_id;
$contents['title'] = array( 
    $lang_module['title'], $title, 255 
);
$contents['content'] = array( 
    $lang_module['content'], $content, '800px', '300px', defined( 'NV_EDITOR' ) ? true : false 
);
$contents['exp_time'] = array( 
    $lang_module['exp_time'], $day, $month, $year, $hour, $min, $lang_global['day'], $lang_global['month'], $lang_global['year'], $lang_global['hour'], $lang_global['min'] 
);
$contents['public'] = array( 
    $lang_module['public'], $public 
);
$contents['submit'] = $lang_global['submit'];
$contents = nv_admin_edit_theme( $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>