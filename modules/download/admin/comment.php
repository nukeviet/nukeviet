<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

//Edit
if ( $nv_Request->isset_request( 'edit', 'get' ) )
{
    $page_title = $lang_module['comment_edit'];

    $id = $nv_Request->get_int( 'id', 'get', 0 );

    if ( ! $id )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment&status=1" );
        exit();
    }

    $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment&status=1" );
        exit();
    }

    $row = $db->sql_fetchrow( $result );

    $array = array();

    $is_error = false;
    $error = "";

    if ( $nv_Request->isset_request( 'submit', 'post' ) )
    {
        $array['subject'] = filter_text_input( 'subject', 'post', '', 1 );
        $array['comment'] = filter_text_textarea( 'comment', '', NV_ALLOWED_HTML_TAGS );
        $array['admin_reply'] = filter_text_input( 'admin_reply', 'post', '', 1 );
        $array['admin_id'] = ( int )$row['admin_id'];

        if ( empty( $array['subject'] ) )
        {
            $is_error = true;
            $error = $lang_module['comment_edit_error1'];
        } elseif ( empty( $array['comment'] ) )
        {
            $is_error = true;
            $error = $lang_module['comment_edit_error2'];
        }
        else
        {
            $array['comment'] = nv_nl2br( $array['comment'], "<br />" );

            if ( ! empty( $array['admin_reply'] ) and $array['admin_reply'] != $row['admin_reply'] )
            {
                $array['admin_id'] = $admin_info['admin_id'];
            }

            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET 
            `subject`=" . $db->dbescape( $array['subject'] ) . ", 
            `comment`=" . $db->dbescape( $array['comment'] ) . ", 
            `admin_reply`=" . $db->dbescape( $array['admin_reply'] ) . ", 
            `admin_id`=" . $array['admin_id'] . " 
            WHERE `id`=" . $id;
            $result = $db->sql_query( $sql );

            if ( ! $result )
            {
                $is_error = true;
                $error = $lang_module['file_error1'];
            }
            else
            {
				nv_del_moduleCache( $module_name );
				
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment&status=" . $row['status'] );
                exit();
            }
        }
    }
    else
    {
        $array['subject'] = $row['subject'];
        $array['comment'] = nv_br2nl( $row['comment'] );
        $array['admin_reply'] = $row['admin_reply'];
        $array['admin_id'] = ( int )$row['admin_id'];
    }

    if ( ! empty( $array['comment'] ) ) $array['comment'] = nv_htmlspecialchars( $array['comment'] );

    $xtpl = new XTemplate( "comment_edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
    $xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;edit=1&amp;id=" . $id );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $array );

    if ( $is_error )
    {
        $xtpl->assign( 'ERROR', $error );
        $xtpl->parse( 'main.error' );
    }

    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

//del
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if ( ! $id )
    {
        die( "NO" );
    }

    $query = "SELECT `fid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 )
    {
        die( "NO" );
    }

    list( $fid ) = $db->sql_fetchrow( $result );
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits-1 WHERE `id`=" . $fid;
    $db->sql_query( $sql );

    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`=" . $id;
    $db->sql_query( $sql );

	nv_del_moduleCache( $module_name );
    die( "OK" );
}

//Chap nhan - dinh chi
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if ( empty( $id ) ) die( "NO" );

    $query = "SELECT `fid`, `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 ) die( 'NO' );

    list( $fid, $status ) = $db->sql_fetchrow( $result );
    if ( $status == 0 )
    {
        $status = 1;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits+1 WHERE `id`=" . $fid;
    } elseif ( $status == 1 )
    {
        $status = 2;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits-1 WHERE `id`=" . $fid;
    }
    else
    {
        $status = 1;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits+1 WHERE `id`=" . $fid;
    }
    $db->sql_query( $sql );

    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_comments` SET `status`=" . $status . " WHERE `id`=" . $id;
    $db->sql_query( $sql );
	
	nv_del_moduleCache( $module_name );
    die( "OK" );
}

//List
$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` b ON a.fid=b.id";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment";

if ( $nv_Request->isset_request( "fid", "get" ) )
{
    $fid = $nv_Request->get_int( 'fid', 'get', 0 );
    if ( ! $fid )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment&status=1" );
        exit();
    }

    $sql .= " WHERE a.fid=" . $fid;
    $base_url .= "&amp;fid=" . $fid;

    $sql1 = "SELECT COUNT(*) " . $sql;
    $result = $db->sql_query( $sql1 );
    list( $all_page ) = $db->sql_fetchrow( $result );

    if ( empty( $all_page ) )
    {
        $page_title = $lang_module['comment'];

        $contents = "<div style=\"padding-top:15px;text-align:center\">\n";
        $contents .= "<strong>" . $lang_module['comment_empty'] . "</strong>";
        $contents .= "</div>\n";
        $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=1\" />";

        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit;
    }
}
else
{
    $status = $nv_Request->get_int( 'status', 'get', 0 );
    if ( $status < 0 or $status > 2 ) $status = 0;

    $sql .= " WHERE a.status=" . $status;
    $base_url .= "&amp;status=" . $status;

    $sql1 = "SELECT COUNT(*) " . $sql;
    $result = $db->sql_query( $sql1 );
    list( $all_page ) = $db->sql_fetchrow( $result );

    if ( empty( $all_page ) )
    {
        $page_title = $lang_module['comment_st' . $status];

        if ( $status != 1 )
        {
            $contents = "<div style=\"padding-top:15px;text-align:center\">\n";
            $contents .= "<strong>" . $lang_module['comment_empty' . $status] . "</strong>";
            $contents .= "</div>\n";
            $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=1\" />";
            include ( NV_ROOTDIR . "/includes/header.php" );
            echo nv_admin_theme( $contents );
            include ( NV_ROOTDIR . "/includes/footer.php" );
            exit;
        }
        else
        {
            $xtpl = new XTemplate( "comment_empty.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
            $xtpl->assign( 'LANG', $lang_module );
            $xtpl->assign( 'COMMENT_STATUS0_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=0" );
            $xtpl->assign( 'COMMENT_STATUS1_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=1" );
            $xtpl->assign( 'COMMENT_STATUS2_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=2" );

            $xtpl->parse( 'main' );
            $contents = $xtpl->text( 'main' );

            include ( NV_ROOTDIR . "/includes/header.php" );
            echo nv_admin_theme( $contents );
            include ( NV_ROOTDIR . "/includes/footer.php" );
            exit;
        }
    }
}

$sql .= " ORDER BY a.post_time DESC";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 10;

$sql2 = "SELECT a.id, a.fid, a.subject, a.post_id, a.post_name, a.post_email, a.post_ip, a.post_time, a.comment, a.admin_reply, a.admin_id, a.status, b.title " . $sql . " LIMIT " . $page . ", " . $per_page;
$query2 = $db->sql_query( $sql2 );

$array = array();

while ( $row = $db->sql_fetchrow( $query2 ) )
{
    $post_name = $row['post_id'] ? "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['post_id'] . "\">" . $row['post_name'] . "</a>" : $row['post_id'];
    $file = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $row['fid'] . "\">" . $row['title'] . "</a>";
    $comments_of_file = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;fid=" . $row['fid'] . "\">" . $lang_module['comment_of_file3'] . "</a>";
    $edit_href = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;edit=1&amp;id=" . $row['id'];

    $st = array();
    for ( $i = 0; $i <= 2; ++$i )
    {
        if ( ( $i == 0 and $row['status'] ) or ( $i == 2 and ! $row['status'] ) )
        {
            continue;
        }

        $st[$i] = array( //
            'key' => $i, //
            'value' => $lang_module['comment_status' . $i], //
            'selected' => $i == ( int )$row['status'] ? " selected=\"selected\"" : "" //
            );
    }

    $admin_id = $row['admin_id'];
    if ( $admin_id )
    {
        $sql = "SELECT `username`, `full_name` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $admin_id;
        $result = $db->sql_query( $sql );
        $numrows = $db->sql_numrows( $result );
        if ( $numrows != 1 )
        {
            $admin_id = "";
        }
        else
        {
            list( $username, $full_name ) = $db->sql_fetchrow( $result );
            if ( empty( $full_name ) ) $full_name = $username;
            $admin_id = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=authors&amp;id=" . $row['admin_id'] . "\">" . $full_name . "</a>";
        }
    }

    $array[] = array( 'id' => ( int )$row['id'], //
        'subject' => nv_clean60( $row['subject'], 60 ), //
        'file' => $file, //
        'file_title' => $row['title'], //
        'post_name' => $post_name, //
        'post_email' => "<a href=\"mailto:" . $row['post_email'] . "\">" . $row['post_email'] . "</a>", //
        'post_ip' => $row['post_ip'], //
        'post_time' => nv_date( "d/m/Y H:i", $row['post_time'] ), //
        'comment' => $row['comment'], //
        'admin_reply' => $row['admin_reply'], //
        'admin_id' => $admin_id, //
        'st' => $st, //
        'comments_of_file' => $comments_of_file, //
        'edit_href' => $edit_href //
        );
}

if ( $nv_Request->isset_request( "fid", "get" ) )
{
    $page_title = sprintf( $lang_module['comment_of_file'], $array[0]['file_title'] );
}
else
{
    $page_title = $lang_module['comment_st' . $status];
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'COMMENT_STATUS0_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=0" );
$xtpl->assign( 'COMMENT_STATUS1_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=1" );
$xtpl->assign( 'COMMENT_STATUS2_HREF', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;status=2" );

if ( ! empty( $array ) )
{
    $a = 0;
    foreach ( $array as $row )
    {
        $xtpl->assign( 'CLASS', $a % 2 == 0 ? " class=\"second\"" : "" );
        $xtpl->assign( 'ROW', $row );

        foreach ( $row['st'] as $st )
        {
            $xtpl->assign( 'STATUS', $st );
            $xtpl->parse( 'main.row.status' );
        }

        if ( ! empty( $row['admin_reply'] ) )
        {
            $xtpl->parse( 'main.row.admin_reply' );
        }

        $xtpl->parse( 'main.row' );
        ++$a;
    }
}

if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>