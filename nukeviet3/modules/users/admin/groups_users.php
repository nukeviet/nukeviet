<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:18
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$group_id = $nv_Request->get_int( 'group_id', 'get', 0 );

$query = "SELECT `title`,`users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id;
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 ) die( error_info_theme( $lang_module['error_group_not_found'] ) );

$row = $db->sql_fetchrow( $result );
$group_title = $row['title'];
if ( ! empty( $row['users'] ) )
{
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    $all_page = count( explode( ",", $row['users'] ) );
    $per_page = 10;
    
    $users_in_group = array();
    $users_in_group['caption'] = sprintf( $lang_module['users_in_group_caption'], $group_title, $all_page );
    $users_in_group['thead'] = array( 
        "UserId", $lang_global['nickname'], $lang_global['full_name'], $lang_global['email'], $lang_global['regdate'], $lang_global['last_login'] 
    );
    $users_in_group['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=";
    $base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=groups_users&amp;group_id=" . $group_id;
    $query = "SELECT `userid`, `username`, `email`, `full_name`, `regdate`, `last_login` 
        FROM `" . NV_USERS_GLOBALTABLE . "` 
        WHERE `userid` IN (" . $row['users'] . ") 
        ORDER BY `username` ASC 
        LIMIT " . $page . "," . $per_page;
    $result = $db->sql_query( $query );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $users_in_group['row'][$row['userid']]['username'] = $row['username'];
        $users_in_group['row'][$row['userid']]['full_name'] = $row['full_name'];
        $users_in_group['row'][$row['userid']]['email'] = nv_EncodeEmail( $row['email'] );
        $users_in_group['row'][$row['userid']]['regdate'] = nv_date( "l, d/m/Y H:i", $row['regdate'] );
        $users_in_group['row'][$row['userid']]['last_login'] = ! empty( $row['last_login'] ) ? nv_date( "l, d/m/Y H:i", $row['last_login'] ) : $lang_global['never'];
        $users_in_group['row'][$row['userid']]['onclick'] = array( 
            "nv_group_exclude_user(" . $group_id . ", " . $row['userid'] . ")", sprintf( $lang_module['exclude_user'], $row['full_name'], $group_title ) 
        );
    }
    $users_in_group['generate_page'] = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'list_users' );
    $contents = main_list_users_theme( $users_in_group );
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    die( error_info_theme( $lang_module['error_users_not_found'] ) );
}

?>