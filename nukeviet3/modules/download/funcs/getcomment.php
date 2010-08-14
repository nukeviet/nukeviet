<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

//dang thao luan
if ( $nv_Request->isset_request( 'ajax', 'post' ) )
{
    $list_cats = nv_list_cats( true );

    if ( ! empty( $list_cats ) )
    {
        $in = implode( ",", array_keys( $list_cats ) );

        $id = $nv_Request->get_int( 'id', 'post', 0 );

        if ( $id )
        {
            $query = "SELECT `who_comment`, `groups_comment` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id . " AND `catid` IN (" . $in . ") AND `status`=1 AND `comment_allow`=1";
            $result = $db->sql_query( $query );
            $numrows = $db->sql_numrows( $result );

            if ( $numrows )
            {
                list( $who_comment, $groups_comment ) = $db->sql_fetchrow( $result );

                if ( nv_set_allow( $who_comment, $groups_comment ) )
                {
                    $uname = filter_text_input( 'uname', 'post', '', 1 );
                    $uemail = filter_text_input( 'uemail', 'post', '' );
                    $subject = filter_text_input( 'subject', 'post', '', 1 );
                    $content = filter_text_textarea( 'content', '', NV_ALLOWED_HTML_TAGS );
                    $seccode = filter_text_input( 'seccode', 'post', '' );
                    $post_id = 0;

                    if ( defined( 'NV_IS_USER' ) )
                    {
                        $uname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
                        $uemail = $user_info['email'];
                        $post_id = $user_info['userid'];
                    }

                    if ( ! nv_capcha_txt( $seccode ) )
                    {
                        die( $lang_module['comment_error2'] );
                    }

                    if ( empty( $uname ) or nv_strlen( $uname ) < 3 )
                    {
                        die( $lang_module['comment_error3'] );
                    }

                    if ( ( $validemail = nv_check_valid_email( $uemail ) ) != "" )
                    {
                        die( $validemail );
                    }

                    if ( empty( $subject ) or nv_strlen( $subject ) < 3 )
                    {
                        die( $lang_module['comment_error4'] );
                    }

                    if ( empty( $content ) or nv_strlen( $content ) < 3 )
                    {
                        die( $lang_module['comment_error5'] );
                    }

                    $download_config = initial_config_data();
                    if ( $download_config['is_autocomment_allow'] )
                    {
                        $status = 1;
                    }
                    else
                    {
                        $status = 0;
                    }

                    $content = nv_nl2br( $content, "<br />" );

                    $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_comments` VALUES (
                    NULL, 
                    " . $id . ", 
                    " . $db->dbescape( $subject ) . ", 
                    " . $post_id . ", 
                    " . $db->dbescape( $uname ) . ", 
                    " . $db->dbescape( $uemail ) . ", 
                    " . $db->dbescape( $client_info['ip'] ) . ", 
                    " . NV_CURRENTTIME . ", 
                    " . $db->dbescape( $content ) . ", 
                    '', 0, " . $status . ")";

                    if ( ! $db->sql_query_insert_id( $sql ) )
                    {
                        die( $lang_module['comment_error6'] );
                    }

                    if ( $status )
                    {
                        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits+1 WHERE `id`=" . $id;
                        $db->sql_query( $sql );
                        die( "OK" );
                    }
                    else
                    {
                        die( "WAIT" );
                    }
                }
            }
        }
    }

    die( $lang_module['comment_error1'] );
}

//list_comment
if ( $nv_Request->isset_request( 'list_comment', 'get' ) )
{
    $list_cats = nv_list_cats( true );

    if ( ! empty( $list_cats ) )
    {
        $in = implode( ",", array_keys( $list_cats ) );

        $id = $nv_Request->get_int( 'list_comment', 'get', 0 );

        if ( $id )
        {
            $array = array();
            $users = array();
            $admins = array();

            $query = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` AS a 
            INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.fid = b.id 
            WHERE a.fid=" . $id . " AND a.status=1 AND b.catid IN (" . $in . ") AND b.status=1 AND b.comment_allow=1";

            $query1 = "SELECT COUNT(*) " . $query;
            $result = $db->sql_query( $query1 );
            list( $all_page ) = $db->sql_fetchrow( $result );

            if ( $all_page )
            {
                $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getcomment&amp;list_comment=" . $id;
                $page = $nv_Request->get_int( 'page', 'get', 0 );
                $per_page = 15;

                $query = "SELECT a.id AS id, a.subject AS subject, a.post_id AS post_id, a.post_name AS post_name, a.post_email AS post_email, 
                a.post_ip AS post_ip, a.post_time AS post_time, a.comment AS comment, a.admin_reply AS admin_reply, a.admin_id AS admin_id 
                " . $query . " 
                ORDER BY a.post_time DESC LIMIT " . $page . "," . $per_page;
                $result = $db->sql_query( $query );

                $today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
                $yesterday = $today - 86400;

                while ( $row = $db->sql_fetchrow( $result ) )
                {
                    $post_name = $row['post_name'];
                    if ( ! $row['post_id'] )
                    {
                        $post_name .= " (" . nv_EncodeEmail( $row['post_email'] ) . ", " . $row['post_ip'] . ")";
                    }
                    else
                    {
                        if ( defined( 'NV_IS_MODADMIN' ) )
                        {
                            $users[] = ( int )$row['post_id'];
                            $post_name = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['post_id'] . "\">" . $post_name . "</a>";
                        }
                    }

                    $post_time = ( int )$row['post_time'];
                    if ( $post_time >= $today )
                    {
                        $post_time = $lang_module['today'] . ", " . date( "H:i", $post_time );
                    } elseif ( $post_time >= $yesterday )
                    {
                        $post_time = $lang_module['yesterday'] . ", " . date( "H:i", $post_time );
                    }
                    else
                    {
                        $post_time = nv_date( "d/m/Y H:i", $post_time );
                    }

                    $admin_reply = "";
                    if ( ! empty( $row['admin_id'] ) and ! empty( $row['admin_reply'] ) )
                    {
                        if ( defined( 'NV_IS_ADMIN' ) )
                        {
                            $admins[] = $row['admin_id'];
                            $admin_reply = $row['admin_reply'];
                        }
                        else
                        {
                            $admin_reply = $lang_module['comment_admin_note'] . ": " . $row['admin_reply'];
                        }
                    }

                    $array[$row['id']] = array( //
                        'id' => ( int )$row['id'], //
                        'post_name' => $post_name, //
                        'post_email' => $row['post_email'], //
                        'post_ip' => $row['post_ip'], //
                        'post_time' => $post_time, //
                        'subject' => $row['subject'], //
                        'comment' => $row['comment'], //
                        'admin_reply' => $admin_reply, //
                        'edit_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;edit=1&amp;id=" . $row['id'], //
                        'del_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment" //
                        );
                }

                if ( ! empty( $users ) )
                {
                    $users = array_unique( $users );
                    $in = implode( ",", $users );

                    $query = "SELECT a.view_mail AS view_mail, b.id AS id FROM `" . NV_USERS_GLOBALTABLE . "` a, `" . NV_PREFIXLANG . "_" . $module_data . "_comments` b WHERE a.userid=b.post_id AND a.userid IN (" . $in . ")";
                    $result = $db->sql_query( $query );
                    while ( $row = $db->sql_fetchrow( $result ) )
                    {
                        if ( ! empty( $array[$row['id']]['post_email'] ) and ( defined( 'NV_IS_ADMIN' ) or $row['view_mail'] ) )
                        {
                            $array[$row['id']]['post_email'] = nv_EncodeEmail( $array[$row['id']]['post_email'] );
                            $array[$row['id']]['post_name'] .= " (" . $array[$row['id']]['post_email'] . ", " . $array[$row['id']]['post_ip'] . ")";
                        }
                        else
                        {
                            $array[$row['id']]['post_email'] = "";
                        }
                    }
                }

                if ( ! empty( $admins ) )
                {
                    $admins = array_unique( $admins );
                    $in = implode( ",", $admins );

                    $query = "SELECT a.userid AS admin_id, a.username AS admin_login, a.full_name AS admin_name, b.id AS id FROM `" . NV_USERS_GLOBALTABLE . "` a, `" . NV_PREFIXLANG . "_" . $module_data . "_comments` b WHERE a.userid=b.admin_id AND a.userid IN (" . $in . ")";
                    $result = $db->sql_query( $query );
                    while ( $row = $db->sql_fetchrow( $result ) )
                    {
                        $admin_name = ! empty( $row['admin_name'] ) ? $row['admin_name'] : $row['admin_login'];
                        $array[$row['id']]['admin_reply'] = $lang_module['comment_admin_note'] . " <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=authors&amp;id=" . $row['admin_id'] . "\">" . $admin_name . "</a>: " . $array[$row['id']]['admin_reply'];
                    }
                }

                $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, 'nv_urldecode_ajax', 'list_comments' );
            }

            $contents = show_comment( $array, $generate_page );
            die( $contents );
        }
    }

    die( $lang_module['comment_error7'] );
}

$id = $nv_Request->get_int( 'id', 'get,post' );
if ( ! $nv_Request->isset_request( 'commentname', 'post' ) )
{
    $comment_array = array();
    list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `lid`= '" . $id . "' AND `status`=1" ) );
    $all_page = ( $numf ) ? $numf : 1;
    $per_page = 10;
    $page = $nv_Request->get_int( 'page', 'get', 0 );
    $sql = "SELECT `comment`, `date`, `name`, `email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE `lid`= '" . $id . "' AND `status`=1 ORDER BY `tid` ASC LIMIT " . $page . "," . $per_page . "";
    $comment = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $comment ) )
    {
        //$row ['email'] = ($module_config [$module_name] ['emailcomm']) ? $row ['email'] : "";
        $comment_array[] = array( "comment" => $row['comment'], "date" => $row['date'], "name" => $row['name'], "email" => $row['email'] );
    }
    $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getcomment&amp;id=" . $id . "&amp;page=" . $page;
    $xtpl = new XTemplate( "comment.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/download" );
    $k = 0;
    foreach ( $comment_array as $comment_array_i )
    {
        $xtpl->assign( 'TIME', date( "d/m/Y H:i", $comment_array_i['date'] ) );
        $xtpl->assign( 'NAME', $comment_array_i['name'] );
        if ( $configdownload['showemail'] and ! empty( $comment_array_i['email'] ) )
        {
            $xtpl->assign( 'EMAIL', $comment_array_i['email'] );
            $xtpl->parse( 'main.detail.emailcomm' );
        }
        $xtpl->assign( 'CONTENT', $comment_array_i['comment'] );
        $xtpl->assign( 'BG', ( $k % 2 ) ? " bg" : "" );
        $xtpl->parse( 'main.detail' );
        $k++;
    }
    $pagenaver = nv_generate_page( $base_url, $all_page, $per_page, $page, 'tt', true, 'nv_urldecode_ajax', 'showcomment' );
    if ( ! empty( $pagenaver ) )
    {
        $xtpl->assign( 'PAGE', $pagenaver );
    }
    $xtpl->parse( 'main' );
    echo $xtpl->text( 'main' );
}
else
{
    $name = filter_text_input( 'commentname', 'post', '', 1 );
    $email = filter_text_input( 'commentemail', 'post', '' );
    $content = filter_text_input( 'commentcontent', 'post', '', 1 );
    $captcha = filter_text_input( 'commentseccode', 'post', '' );

    $validemail = nv_check_valid_email( $email );
    if ( $name == '' )
    {
        echo $lang_module['comment_noname'];
    } elseif ( $validemail )
    {
        echo $lang_module['comment_noemail'];
    } elseif ( $content == '' || strlen( $content ) < 10 )
    {
        echo $lang_module['comment_nocontent'];
    } elseif ( ! nv_capcha_txt( $captcha ) )
    {
        echo $lang_module['comment_error_captcha'];
    }
    else
    {
        $ati = $configdownload['deslimit'];
        $db->sql_query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_comments VALUES (NULL,' . $id . ',UNIX_TIMESTAMP(),' . $db->dbescape( $name ) . ',' . $db->dbescape( $email ) . ',' . $db->dbescape( $ips->client_ip ) . ',' . $db->dbescape( $content ) . ',' . $ati . ' )' );
        $db->sql_query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET comment=comment+1 WHERE id=' . $id . '' );
        echo $lang_module['comment_sucsser'];
    }
}

?>