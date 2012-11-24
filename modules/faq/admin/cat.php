<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_FixWeightCat()
 * 
 * @param integer $parentid
 * @return
 */
function nv_FixWeightCat( $parentid = 0 )
{
    global $db, $module_data;

    $sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
    }
}

/**
 * nv_del_cat()
 * 
 * @param mixed $catid
 * @return
 */
function nv_del_cat( $catid )
{
    global $db, $module_data;

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $catid;
    $db->sql_query( $sql );

    $sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `parentid`=" . $catid;
    $result = $db->sql_query( $sql );
    while ( list( $id ) = $db->sql_fetchrow( $result ) )
    {
        nv_del_cat( $id );
    }

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $catid;
    $db->sql_query( $sql );
}

$groups_list = nv_groups_list();
$array_who = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
if ( ! empty( $groups_list ) )
{
    $array_who[] = $lang_global['who_view3'];
}

$array = array();
$error = "";

//them chu de
if ( $nv_Request->isset_request( 'add', 'get' ) )
{
    $page_title = $lang_module['faq_addcat_titlebox'];

    $is_error = false;

    if ( $nv_Request->isset_request( 'submit', 'post' ) )
    {
        $array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
        $array['title'] = filter_text_input( 'title', 'post', '', 1 );
        $array['description'] = filter_text_input( 'description', 'post', '' );
        $array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
        $array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );

        $alias = change_alias( $array['title'] );

        if ( empty( $array['title'] ) )
        {
            $error = $lang_module['faq_error_cat2'];
            $is_error = true;
        }
        else
        {
            if ( ! empty( $array['parentid'] ) )
            {
                $sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $array['parentid'];
                $result = $db->sql_query( $sql );
                list( $count ) = $db->sql_fetchrow( $result );

                if ( ! $count )
                {
                    $error = $lang_module['faq_error_cat3'];
                    $is_error = true;
                }
            }

            if ( ! $is_error )
            {
                $sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `alias`=" . $db->dbescape( $alias );
                $result = $db->sql_query( $sql );
                list( $count ) = $db->sql_fetchrow( $result );

                if ( $count )
                {
                    $error = $lang_module['faq_error_cat1'];
                    $is_error = true;
                }
            }
        }

        if ( ! $is_error )
        {
            if ( ! in_array( $array['who_view'], array_keys( $array_who ) ) )
            {
                $array['who_view'] = 0;
            }

            $array['groups_view'] = ( ! empty( $array['groups_view'] ) ) ? implode( ',', $array['groups_view'] ) : '';

            $sql = "SELECT MAX(weight) AS new_weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `parentid`=" . $array['parentid'];
            $result = $db->sql_query( $sql );
            list( $new_weight ) = $db->sql_fetchrow( $result );
            $new_weight = ( int )$new_weight;
            ++$new_weight;

            $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_categories` VALUES (
            NULL, 
            " . $array['parentid'] . ", 
            " . $db->dbescape( $array['title'] ) . ", 
            " . $db->dbescape( $alias ) . ", 
            " . $db->dbescape( $array['description'] ) . ", 
            " . $array['who_view'] . ", 
            " . $db->dbescape( $array['groups_view'] ) . ", 
            " . $new_weight . ", 
            1, '')";

            $catid = $db->sql_query_insert_id( $sql );

            if ( ! $catid )
            {
                $error = $lang_module['faq_error_cat4'];
                $is_error = true;
            }
            else
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_cat', "cat ".$catid, $admin_info['userid'] );
            	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
                exit();
            }
        }
    }
    else
    {
        $array['parentid'] = 0;
        $array['title'] = "";
        $array['description'] = "";
        $array['who_view'] = 0;
        $array['groups_view'] = array();
    }

    $listcats = array( array( 'id' => 0, 'name' => $lang_module['faq_category_cat_maincat'], 'selected' => "" ) );
    $listcats = $listcats + nv_listcats( $array['parentid'] );

    $who_view = $array['who_view'];
    $array['who_view'] = array();
    foreach ( $array_who as $key => $who )
    {
        $array['who_view'][] = array( //
            'key' => $key, //
            'title' => $who, //
            'selected' => $key == $who_view ? " selected=\"selected\"" : "" //
            );
    }

    $groups_view = $array['groups_view'];
    $array['groups_view'] = array();
    if ( ! empty( $groups_list ) )
    {
        foreach ( $groups_list as $key => $title )
        {
            $array['groups_view'][] = array( //
                'key' => $key, //
                'title' => $title, //
                'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : "" //
                );
        }
    }

    $xtpl = new XTemplate( "cat_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;add=1" );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $array );

    if ( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.error' );
    }

    foreach ( $listcats as $cat )
    {
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.parentid' );
    }

    foreach ( $array['who_view'] as $who )
    {
        $xtpl->assign( 'WHO_VIEW', $who );
        $xtpl->parse( 'main.who_view' );
    }

    if ( ! empty( $array['groups_view'] ) )
    {
        foreach ( $array['groups_view'] as $group )
        {
            $xtpl->assign( 'GROUPS_VIEW', $group );
            $xtpl->parse( 'main.group_view_empty.groups_view' );
        }
        $xtpl->parse( 'main.group_view_empty' );
    }

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );

    exit;
}

//Sua chu de
if ( $nv_Request->isset_request( 'edit', 'get' ) )
{
    $page_title = $lang_module['faq_editcat_cat'];

    $catid = $nv_Request->get_int( 'catid', 'get', 0 );

    if ( empty( $catid ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
        exit();
    }

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $catid;
    $result = $db->sql_query( $sql );
    $numcat = $db->sql_numrows( $result );

    if ( $numcat != 1 )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
        exit();
    }

    $row = $db->sql_fetchrow( $result );

    $is_error = false;

    if ( $nv_Request->isset_request( 'submit', 'post' ) )
    {
        $array['parentid'] = $nv_Request->get_int( 'parentid', 'post', 0 );
        $array['title'] = filter_text_input( 'title', 'post', '', 1 );
        $array['description'] = filter_text_input( 'description', 'post', '' );
        $array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
        $array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );

        $alias = change_alias( $array['title'] );

        if ( empty( $array['title'] ) )
        {
            $error = $lang_module['faq_error_cat2'];
            $is_error = true;
        }
        else
        {
            if ( ! empty( $array['parentid'] ) )
            {
                $sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $array['parentid'];
                $result = $db->sql_query( $sql );
                list( $count ) = $db->sql_fetchrow( $result );

                if ( ! $count )
                {
                    $error = $lang_module['faq_error_cat3'];
                    $is_error = true;
                }
            }

            if ( ! $is_error )
            {
                $sql = "SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`!=" . $catid . " AND `alias`=" . $db->dbescape( $alias ) . " AND `parentid`=" . $array['parentid'];
                $result = $db->sql_query( $sql );
                list( $count ) = $db->sql_fetchrow( $result );

                if ( $count )
                {
                    $error = $lang_module['faq_error_cat1'];
                    $is_error = true;
                }
            }
        }

        if ( ! $is_error )
        {
            if ( ! in_array( $array['who_view'], array_keys( $array_who ) ) )
            {
                $array['who_view'] = 0;
            }

            $array['groups_view'] = ( ! empty( $array['groups_view'] ) ) ? implode( ',', $array['groups_view'] ) : '';

            if ( $array['parentid'] != $row['parentid'] )
            {
                $sql = "SELECT MAX(weight) AS new_weight FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `parentid`=" . $array['parentid'];
                $result = $db->sql_query( $sql );
                list( $new_weight ) = $db->sql_fetchrow( $result );
                $new_weight = ( int )$new_weight;
                ++$new_weight;
            }
            else
            {
                $new_weight = $row['weight'];
            }

            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET 
            `parentid`=" . $array['parentid'] . ", 
            `title`=" . $db->dbescape( $array['title'] ) . ", 
            `alias`=" . $db->dbescape( $alias ) . ", 
            `description`=" . $db->dbescape( $array['description'] ) . ", 
            `who_view`=" . $array['who_view'] . ", 
            `groups_view`=" . $db->dbescape( $array['groups_view'] ) . ", 
            `weight`=" . $new_weight . " 
            WHERE `id`=" . $catid;
            $result = $db->sql_query( $sql );

            if ( ! $result )
            {
                $error = $lang_module['faq_error_cat5'];
                $is_error = true;
            }
            else
            {
                if ( $array['parentid'] != $row['parentid'] )
                {
                    nv_FixWeightCat( $row['parentid'] );
                }

                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
                exit();
            }
        }
    }
    else
    {
        $array['parentid'] = ( int )$row['parentid'];
        $array['title'] = $row['title'];
        $array['description'] = $row['description'];
        $array['who_view'] = ( int )$row['who_view'];
        $array['groups_view'] = ! empty( $row['groups_view'] ) ? explode( ",", $row['groups_view'] ) : array();
    }

    $listcats = array( array( 'id' => 0, 'name' => $lang_module['faq_category_cat_maincat'], 'selected' => "" ) );
    $listcats = $listcats + nv_listcats( $array['parentid'], $catid );

    $who_view = $array['who_view'];
    $array['who_view'] = array();
    foreach ( $array_who as $key => $who )
    {
        $array['who_view'][] = array( //
            'key' => $key, //
            'title' => $who, //
            'selected' => $key == $who_view ? " selected=\"selected\"" : "" //
            );
    }

    $groups_view = $array['groups_view'];
    $array['groups_view'] = array();
    if ( ! empty( $groups_list ) )
    {
        foreach ( $groups_list as $key => $title )
        {
            $array['groups_view'][] = array( //
                'key' => $key, //
                'title' => $title, //
                'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : "" //
                );
        }
    }

    $xtpl = new XTemplate( "cat_add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;catid=" . $catid );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $array );

    if ( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.error' );
    }

    foreach ( $listcats as $cat )
    {
        $xtpl->assign( 'LISTCATS', $cat );
        $xtpl->parse( 'main.parentid' );
    }

    foreach ( $array['who_view'] as $who )
    {
        $xtpl->assign( 'WHO_VIEW', $who );
        $xtpl->parse( 'main.who_view' );
    }

    if ( ! empty( $array['groups_view'] ) )
    {
        foreach ( $array['groups_view'] as $group )
        {
            $xtpl->assign( 'GROUPS_VIEW', $group );
            $xtpl->parse( 'main.group_view_empty.groups_view' );
        }
        $xtpl->parse( 'main.group_view_empty' );
    }

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );

    exit;
}

//Xoa chu de
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $catid = $nv_Request->get_int( 'catid', 'post', 0 );

    if ( empty( $catid ) )
    {
        die( "NO" );
    }

    $sql = "SELECT COUNT(*) AS count, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $catid;
    $result = $db->sql_query( $sql );
    list( $count, $parentid ) = $db->sql_fetchrow( $result );

    if ( $count != 1 )
    {
        die( "NO" );
    }

    nv_del_cat( $catid );
    nv_FixWeightCat( $parentid );

    die( "OK" );
}

//Chinh thu tu chu de
if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $catid = $nv_Request->get_int( 'catid', 'post', 0 );
    $new = $nv_Request->get_int( 'new', 'post', 0 );

    if ( empty( $catid ) ) die( "NO" );

    $query = "SELECT `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $catid;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 ) die( 'NO' );
    list( $parentid ) = $db->sql_fetchrow( $result );

    $query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`!=" . $catid . " AND `parentid`=" . $parentid . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        ++$weight;
        if ( $weight == $new ) ++$weight;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
        $db->sql_query( $sql );
    }
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `weight`=" . $new . " WHERE `id`=" . $catid;
    $db->sql_query( $sql );
    die( "OK" );
}

//Kich hoat - dinh chi
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $catid = $nv_Request->get_int( 'catid', 'post', 0 );

    if ( empty( $catid ) ) die( "NO" );

    $query = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $catid;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 ) die( 'NO' );

    list( $status ) = $db->sql_fetchrow( $result );
    $status = $status ? 0 : 1;

    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_categories` SET `status`=" . $status . " WHERE `id`=" . $catid;
    $db->sql_query( $sql );
    die( "OK" );
}

//Danh sach chu de
$page_title = $lang_module['faq_catmanager'];

$pid = $nv_Request->get_int( 'pid', 'get', 0 );

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `parentid`=" . $pid . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

if ( ! $num )
{
    if ( $pid )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
    }
    else
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add=1" );
    }
    exit();
}

if ( $pid )
{
    $sql2 = "SELECT `title`,`parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE `id`=" . $pid;
    $result2 = $db->sql_query( $sql2 );
    list( $parentid, $parentid2 ) = $db->sql_fetchrow( $result2 );
    $caption = sprintf( $lang_module['faq_table_caption2'], $parentid );
    $parentid = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;pid=" . $parentid2 . "\">" . $parentid . "</a>";
}
else
{
    $caption = $lang_module['faq_table_caption1'];
    $parentid = $lang_module['faq_category_cat_maincat'];
}

$list = array();
$a = 0;

while ( $row = $db->sql_fetchrow( $result ) )
{
    $numsub = $db->sql_numrows( $db->sql_query( "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $row['id'] ) );
    if ( $numsub )
    {
        $numsub = " (<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;pid=" . $row['id'] . "\">" . $numsub . " " . $lang_module['faq_category_cat_sub'] . "</a>)";
    }
    else
    {
        $numsub = "";
    }

    $weight = array();
    for ( $i = 1; $i <= $num; ++$i )
    {
        $weight[$i]['title'] = $i;
        $weight[$i]['pos'] = $i;
        $weight[$i]['selected'] = ( $i == $row['weight'] ) ? " selected=\"selected\"" : "";
    }

    $class = ( $a % 2 ) ? " class=\"second\"" : "";

    $list[$row['id']] = array( //
        'id' => ( int )$row['id'], //
        'title' => $row['title'], //
        'titlelink' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;catid=" . $row['id'], //
        'numsub' => $numsub, //
        'parentid' => $parentid, //
        'weight' => $weight, //
        'status' => $row['status'] ? " checked=\"checked\"" : "", //
        'class' => $class //
        );

    ++$a;
}

$xtpl = new XTemplate( "cat_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'ADD_NEW_CAT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;add=1" );
$xtpl->assign( 'TABLE_CAPTION', $caption );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );

foreach ( $list as $row )
{
    $xtpl->assign( 'ROW', $row );

    foreach ( $row['weight'] as $weight )
    {
        $xtpl->assign( 'WEIGHT', $weight );
        $xtpl->parse( 'main.row.weight' );
    }

    $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=cat&amp;edit=1&amp;catid=" . $row['id'] );
    $xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>