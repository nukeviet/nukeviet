<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:14
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$group_id = $nv_Request->get_int( 'group_id', 'get', 0 );

$query = "SELECT `users`, `title` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 ) die( error_info_theme( $lang_module['error_group_not_found'] ) );

$row = $db->sql_fetchrow( $result );
$users = $row['users'];
$group_title = $row['title'];

$query = "SELECT `userid`, `username`, `email`, `full_name`, `regdate`, `last_login` FROM `" . NV_USERS_GLOBALTABLE . "`";

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups_search_users&amp;group_id=" . $group_id;

$search_option = $nv_Request->get_int( 'search_option', 'get', 0 );
$search_query = rawurldecode( filter_text_input( 'search_query', 'get' ) );
if ( ! empty( $search_query ) ) $search_query = nv_substr( $search_query, 0, 60 );
if ( ! empty( $search_query ) )
{
    switch ( $search_option )
    {
        case 1:
            $query .= " WHERE `email` LIKE '%" . $db->dblikeescape( $search_query ) . "%'";
            break;
        
        case 2:
            $query .= " WHERE `userid` LIKE '%" . intval( $search_query ) . "%'";
            break;
        
        default:
            $search_query = preg_replace( '/[ ]+/', '_', nv_EncString( $search_query ) );
            $search_query = $db->dblikeescape( strtolower( $search_query ) );
            $query .= " WHERE (`username` LIKE '%" . $search_query . "%' OR `full_name` LIKE '%" . $search_query . "%')";
    }
    $base_url .= "&amp;search_option=" . $search_option . "&amp;search_query=" . rawurlencode( $search_query );
}

if ( ! empty( $users ) )
{
    $query .= ! empty( $search_query ) ? " AND" : " WHERE";
    $query .= " `userid` NOT IN (" . $users . ")";
}

$result = $db->sql_query( $query );
$all_page = $db->sql_numrows( $result );
if ( empty( $all_page ) ) die( error_info_theme( $lang_module['search_not_result'] ) );
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 10;

switch ( $search_option )
{
    case 1:
        $query .= " ORDER BY `email` ASC LIMIT " . $page . "," . $per_page;
        break;
    
    case 2:
        $query .= " ORDER BY `userid` ASC LIMIT " . $page . "," . $per_page;
        break;
    
    default:
        $query .= " ORDER BY `username` ASC LIMIT " . $page . "," . $per_page;
}

$result = $db->sql_query( $query );

$search_result = array();
$search_result['caption'] = $lang_module['search_result_caption'];
$search_result['thead'] = array( 
    "UserId", $lang_global['username'], $lang_global['full_name'], $lang_global['email'], $lang_global['regdate'], $lang_global['last_login'] 
);
$search_result['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=";

while ( $row = $db->sql_fetchrow( $result ) )
{
    $search_result['row'][$row['userid']]['username'] = $row['username'];
    $search_result['row'][$row['userid']]['full_name'] = $row['full_name'];
    $search_result['row'][$row['userid']]['email'] = nv_EncodeEmail( $row['email'] );
    $search_result['row'][$row['userid']]['regdate'] = nv_date( "l, d/m/Y H:i", $row['regdate'] );
    $search_result['row'][$row['userid']]['last_login'] = ! empty( $row['last_login'] ) ? nv_date( "l, d/m/Y H:i", $row['last_login'] ) : $lang_global['never'];
    $search_result['row'][$row['userid']]['onclick'] = array( 
        "nv_group_add_user(" . $group_id . "," . $row['userid'] . ")", sprintf( $lang_module['add_user'], $row['full_name'], $group_title ) 
    );
}
$search_result['generate_page'] = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'search_users_result' );
$contents = nv_admin_search_users_theme( $search_result );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>