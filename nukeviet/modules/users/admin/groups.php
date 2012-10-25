<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:5
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_global['mod_groups'];
$contents = "";

//Lay danh sach nhom
$groupsList = groupList();
$groupcount = sizeof( $groupsList );

//Neu khong co nhom => chuyen den trang tao nhom
if ( ! $groupcount and ! $nv_Request->isset_request( 'add', 'get' ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
    die();
}

//Thay doi thu tu nhom
if ( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
    $id = $nv_Request->get_int( 'id', 'post' );
    $cWeight = $nv_Request->get_int( 'cWeight', 'post' );
    if ( ! isset( $groupsList[$id] ) ) die( "ERROR" );

    $cWeight = min( $cWeight, $groupcount );

    $query = array();
    $query[] = "WHEN `group_id` = " . $id . " THEN " . $cWeight;
    unset( $groupsList[$id] );
    --$groupcount;
    $idList = array_keys( $groupsList );

    for ( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
    {
        if ( $weight == $cWeight ) ++$weight;
        $query[] = "WHEN `group_id` = " . $idList[$i] . " THEN " . $weight;
    }

    $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `weight` = CASE " . implode( " ", $query ) . " END";
    $db->sql_query( $query );

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['changeGroupWeight'], "Id: " . $id, $admin_info['userid'] );
    die( "OK" );
}

//Thay doi tinh trang hien thi cua nhom
if ( $nv_Request->isset_request( 'act', 'post' ) )
{
    $id = $nv_Request->get_int( 'act', 'post' );
    if ( ! isset( $groupsList[$id] ) ) die( "ERROR|" . $groupsList[$id]['act'] );

    $act = $groupsList[$id]['act'] ? 0 : 1;
    $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `act`=" . $act . " WHERE `group_id`=" . $id . " LIMIT 1";
    $db->sql_query( $query );

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['ChangeGroupAct'], "Id: " . $id, $admin_info['userid'] );
    die( "OK|" . $act );
}

//Thay doi tinh trang cong cong hien thi cua nhom
if ( $nv_Request->isset_request( 'pub', 'post' ) )
{
    $id = $nv_Request->get_int( 'pub', 'post' );
    if ( ! isset( $groupsList[$id] ) ) die( "ERROR|" . $groupsList[$id]['public'] );

    $pub = $groupsList[$id]['public'] ? 0 : 1;
    $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `public`=" . $pub . " WHERE `group_id`=" . $id . " LIMIT 1";
    $db->sql_query( $query );

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['ChangeGroupPublic'], "Id: " . $id, $admin_info['userid'] );
    die( "OK|" . $pub );
}

//Xoa nhom
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    $id = $nv_Request->get_int( 'del', 'post', 0 );
    if ( ! isset( $groupsList[$id] ) ) die( $lang_module['error_group_not_found'] );

    if ( ! empty( $groupsList[$id]['users'] ) )
    {
        $query = "SELECT `userid`, `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (" . $groupsList[$id]['users'] . ")";
        $result = $db->sql_query( $query );
        $update = array();
        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $in_groups = $row['in_groups'];
            if ( empty( $in_groups ) ) continue;
            $in_groups = "," . $in_groups . ",";
            $in_groups = str_replace( "," . $id . ",", ",", $in_groups );
            $in_groups = trim( $in_groups, "," );
            $update[] = "WHEN `userid` = " . $row['userid'] . " THEN " . $db->dbescape_string( $in_groups );
        }

        if ( ! empty( $update ) )
        {
            $update = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups` = CASE " . implode( " ", $update ) . " END";
            $db->sql_query( $query );
        }
    }

    $query = "DELETE FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id` = " . $id . " LIMIT 1";
    $db->sql_query( $query );

    unset( $groupsList[$id] );
    --$groupcount;
    $idList = array_keys( $groupsList );

    $query = array();
    for ( $i = 0, $weight = 1; $i < $groupcount; ++$i, ++$weight )
    {
        $query[] = "WHEN `group_id` = " . $idList[$i] . " THEN " . $weight;
    }

    if ( ! empty( $query ) )
    {
        $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `weight` = CASE " . implode( " ", $query ) . " END";
        $db->sql_query( $query );
    }

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delGroup'], "Id: " . $id, $admin_info['userid'] );
    die( "OK" );
}

//Them thanh vien vao nhom
if ( $nv_Request->isset_request( 'gid,uid', 'post' ) )
{
    $gid = $nv_Request->get_int( 'gid', 'post', 0 );
    $uid = $nv_Request->get_int( 'uid', 'post', 0 );
    if ( ! isset( $groupsList[$gid] ) ) die( $lang_module['error_group_not_found'] );

    $sql = "SELECT `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $uid . " LIMIT 1";
    $sql = $db->sql_query( $sql );
    if ( $db->sql_numrows( $sql ) == 0 ) die( $lang_module['search_not_result'] );
    list( $in_groups ) = $db->sql_fetchrow( $sql );
	$in_groups = $in_groups ? explode( ",", $in_groups ) : array();
	
	// Kiem tra neu thuoc nhom roi
	if( in_array( $gid, $in_groups ) ) die( $lang_module['UserInGroup'] );
	
    $users = $groupsList[$gid]['users'];
	$users = $users ? explode( ",", $users ) : array();
	$users[] = $uid;
	$users = implode( ",", array_filter( array_unique( array_map( "intval", $users ) ) ) );
	
    $sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users` = " . $db->dbescape_string( $users ) . " WHERE `group_id`=" . $gid . " LIMIT 1";
    $db->sql_query( $sql );

	$in_groups[] = $gid;
	$in_groups = implode( ",", array_filter( array_unique( array_map( "intval", $in_groups ) ) ) );
	
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups` = " . $db->dbescape_string( $in_groups ) . " WHERE `userid`=" . $uid . " LIMIT 1";
    $db->sql_query( $sql );

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['addMemberToGroup'], "Member Id: " . $uid . " group ID: " . $gid, $admin_info['userid'] );

    die( "OK" );
}

//Loai thanh vien khoi nhom
if ( $nv_Request->isset_request( 'gid,exclude', 'post' ) )
{
    $gid = $nv_Request->get_int( 'gid', 'post', 0 );
    $uid = $nv_Request->get_int( 'exclude', 'post', 0 );
    if ( ! isset( $groupsList[$gid] ) ) die( $lang_module['error_group_not_found'] );
    $sql = "SELECT `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $uid . " LIMIT 1";
    $sql = $db->sql_query( $sql );
    if ( $db->sql_numrows( $sql ) == 0 ) die( $lang_module['search_not_result'] );
    list( $in_groups ) = $db->sql_fetchrow( $sql );
    if ( empty( $in_groups ) or ! preg_match( "/\," . $gid . "\,/", "," . $in_groups . "," ) ) die( $lang_module['UserNotInGroup'] );

    $users = $groupsList[$gid]['users'];
    $users = "," . $users . ",";
    $users = str_replace( "," . $uid . ",", "", $users );
    $users = trim( $users, "," );
    $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users` = " . $db->dbescape_string( $users ) . " WHERE `group_id`=" . $gid . " LIMIT 1";
    $db->sql_query( $query );

    $in_groups = "," . $in_groups . ",";
    $in_groups = str_replace( "," . $gid . ",", "", $in_groups );
    $in_groups = trim( $in_groups, "," );
    $query = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups` = " . $db->dbescape_string( $in_groups ) . " WHERE `userid`=" . $uid . " LIMIT 1";
    $db->sql_query( $query );

    nv_del_moduleCache( $module_name );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['exclude_user2'], "Member Id: " . $uid . " group ID: " . $gid, $admin_info['userid'] );
    die( "OK" );
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );

//Danh sach thanh vien (AJAX)
if ( $nv_Request->isset_request( 'listUsers', 'get' ) )
{
    $id = $nv_Request->get_int( 'listUsers', 'get', 0 );
    if ( ! isset( $groupsList[$id] ) ) die( $lang_module['error_group_not_found'] );

    $users = ! empty( $groupsList[$id]['users'] ) ? sizeof( explode( ",", $groupsList[$id]['users'] ) ) : 0;
    $xtpl->assign( 'PTITLE', sprintf( $lang_module['users_in_group_caption'], $groupsList[$id]['title'], $users ) );
    $xtpl->assign( 'GID', $id );

    if ( ! empty( $groupsList[$id]['users'] ) )
    {
        $sql = "SELECT `userid`, `username`, `full_name`, `email` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (" . $groupsList[$id]['users'] . ")";
        $sql = $db->sql_query( $sql );
        $a = 0;
        while ( $row = $db->sql_fetchrow( $sql, 2 ) )
        {
            $xtpl->assign( 'LOOP', $row );
            $xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );
            $xtpl->parse( 'listUsers.ifExists.loop' );
            ++$a;
        }
        $xtpl->parse( 'listUsers.ifExists' );
    }

    $xtpl->parse( 'listUsers' );
    $xtpl->out( 'listUsers' );
    exit;
}

//Danh sach thanh vien
if ( $nv_Request->isset_request( 'userlist', 'get' ) )
{
    $id = $nv_Request->get_int( 'userlist', 'get', 0 );
    if ( ! isset( $groupsList[$id] ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
        die();
    }

    $xtpl->assign( 'GID', $id );

    $xtpl->parse( 'userlist' );
    $contents = $xtpl->text( 'userlist' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

//Them + sua nhom
if ( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
    if ( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

    $post = array();
    if ( $nv_Request->isset_request( 'edit', 'get' ) )
    {
        $post['id'] = $nv_Request->get_int( 'id', 'get' );
        if ( empty( $post['id'] ) or ! isset( $groupsList[$post['id']] ) )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
            die();
        }

        $xtpl->assign( 'PTITLE', $lang_module['nv_admin_edit'] );
        $xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&edit&id=" . $post['id'] );
        $log_title = $lang_module['nv_admin_edit'];
    }
    else
    {
        $xtpl->assign( 'PTITLE', $lang_module['nv_admin_add'] );
        $xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&add" );
        $log_title = $lang_module['nv_admin_add'];
    }

    if ( $nv_Request->isset_request( 'save', 'post' ) )
    {
        $post['title'] = filter_text_input( 'title', 'post', '', 1 );
        if ( empty( $post['title'] ) )
        {
            die( $lang_module['title_empty'] );
        }

        $_groupsList = $groupsList;
        if ( isset( $post['id'] ) ) unset( $_groupsList[$post['id']] );

        foreach ( $_groupsList as $_group )
        {
            if ( strcasecmp( $_group['title'], $post['title'] ) == 0 )
            {
                die( sprintf( $lang_module['error_title_exists'], $post['title'] ) );
            }
        }

        $post['content'] = nv_editor_filter_textarea( 'content', '', NV_ALLOWED_HTML_TAGS );
        $test_content = trim( strip_tags( $post['content'] ) );
        $post['content'] = ! empty( $test_content ) ? nv_editor_nl2br( $post['content'] ) : "";

        $post['exp_time'] = filter_text_input( 'exp_time', 'post', '' );

        if ( preg_match( "/^([\d]{1,2})\.([\d]{1,2})\.([\d]{4})$/", $post['exp_time'], $matches ) )
        {
            $post['exp_time'] = mktime( 23, 59, 59, $matches[2], $matches[1], $matches[3] );
        }
        else
        {
            $post['exp_time'] = 0;
        }

        $post['public'] = $nv_Request->get_int( 'public', 'post', 0 );
        if ( $post['public'] != 1 ) $post['public'] = 0;

        if ( isset( $post['id'] ) )
        {
            $query = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET 
                    `title`=" . $db->dbescape( $post['title'] ) . ", 
                    `content`=" . $db->dbescape( $post['content'] ) . ", 
                    `exp_time`='" . $post['exp_time'] . "', 
                    `public`= " . $post['public'] . " 
                    WHERE `group_id`=" . $post['id'] . " LIMIT 1";
            $ok = $db->sql_query( $query );
        }
        else
        {
            $query = "INSERT INTO `" . NV_GROUPS_GLOBALTABLE . "` 
                VALUES (NULL, " . $db->dbescape( $post['title'] ) . ", 
                " . $db->dbescape( $post['content'] ) . ", 
                " . NV_CURRENTTIME . ", 
                " . $post['exp_time'] . ", 
                '', " . $post['public'] . ", " . ( $groupcount + 1 ) . ", 1);";
            $ok = $post['id'] = $db->sql_query_insert_id( $query );
        }
        if($ok)
        {
            nv_del_moduleCache( $module_name );
            nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
            die( "OK" );
        }
        else
        {
            die($lang_module['errorsave']);
        }
    }

    if ( $nv_Request->isset_request( 'edit', 'get' ) )
    {
        $post = $groupsList[$post['id']];
        $post['content'] = nv_editor_br2nl( $post['content'] );
        $post['exp_time'] = ! empty( $post['exp_time'] ) ? date( "d.m.Y", $post['exp_time'] ) : "";
        $post['public'] = $post['public'] ? " checked=\"checked\"" : "";
    }
    else
    {
        $post['title'] = $post['content'] = $post['exp_time'] = "";
        $post['public'] = "";
    }

    if ( ! empty( $post['content'] ) ) $post['content'] = nv_htmlspecialchars( $post['content'] );

    $xtpl->assign( 'DATA', $post );

    if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
    {
        $xtpl->parse( 'add.is_editor' );
        $_cont = nv_aleditor( 'content', '100%', '300px', $post['content'] );
    }
    else
    {
        $_cont = "<textarea style=\"width:100%;height:300px\" name=\"content\" id=\"content\">" . $post['content'] . "</textarea>";
    }
    $xtpl->assign( 'CONTENT', $_cont );

    $xtpl->parse( 'add' );
    $contents = $xtpl->text( 'add' );

    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    die();
}

//Danh sach nhom
if ( $nv_Request->isset_request( 'list', 'get' ) )
{
    $a = 0;
    foreach ( $groupsList as $id => $values )
    {
        $loop = array( //
            'id' => $id, //
            'title' => $values['title'], //
            'add_time' => nv_date( "d.m.Y H:i", $values['add_time'] ), //
            'exp_time' => ! empty( $values['exp_time'] ) ? nv_date( "d.m.Y H:i", $values['exp_time'] ) : $lang_global['unlimited'], //
            'public' => $values['public'] ? " checked=\"checked\"" : "", //
            'users' => ! empty( $values['users'] ) ? sizeof( explode( ",", $values['users'] ) ) : 0, //
            'act' => $values['act'] ? " checked=\"checked\"" : "" //
            );
        $xtpl->assign( 'LOOP', $loop );

        for ( $i = 1; $i <= $groupcount; $i++ )
        {
            $opt = array( 'value' => $i, 'selected' => $i == $values['weight'] ? " selected=\"selected\"" : "" );
            $xtpl->assign( 'NEWWEIGHT', $opt );
            $xtpl->parse( 'list.loop.option' );
        }

        $xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );
        $xtpl->parse( 'list.loop' );
        $a++;
    }

    $xtpl->parse( 'list' );
    $xtpl->out( 'list' );
    exit;
}

//Trang chu
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>