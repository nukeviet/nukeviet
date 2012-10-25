<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

//if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
$list_cats = nv_list_cats( true );

//dang thao luan
if ( $nv_Request->isset_request( 'ajax', 'post' ) )
{
    if ( ! empty( $list_cats ) )
    {
        
        $in = implode( ",", array_keys( $list_cats ) );
        
        $id = $nv_Request->get_int( 'id', 'post', 0 );
        $data = $error = array();
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
                        $error[] = $lang_module['comment_error2'];
                    }
                    
                    if ( empty( $uname ) or nv_strlen( $uname ) < 3 )
                    {
                        $error[] = $lang_module['comment_error3'];
                    }
                    
                    if ( ( $validemail = nv_check_valid_email( $uemail ) ) != "" )
                    {
                        $error[] = $validemail;
                    }
                    
                    if ( empty( $subject ) or nv_strlen( $subject ) < 3 )
                    {
                        $error[] = $lang_module['comment_error4'];
                    }
                    
                    if ( empty( $content ) or nv_strlen( $content ) < 3 )
                    {
                        $error[] = $lang_module['comment_error5'];
                    }
                    
                    $download_config = nv_mod_down_config();
                    if ( $download_config['is_autocomment_allow'] )
                    {
                        $status = 1;
                    }
                    else
                    {
                        $status = 0;
                    }
                    if ( ! empty( $error ) )
                    {
                        echo implode( "\n", $error );
                        die();
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
                        $error[] = $lang_module['comment_error6'];
                    }
                    if ( $status )
                    {
                        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `comment_hits`=comment_hits+1 WHERE `id`=" . $id;
                        $db->sql_query( $sql );
                    }
                    if ( ! empty( $error ) )
                    {
                        echo implode( "\n", $error );
                        die();
                    }
                    elseif ( $status == 1 )
                    {
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
}

//list_comment
$generate_page = "";
if ( $nv_Request->isset_request( 'list_comment', 'get' ) )
{
    if ( ! empty( $list_cats ) )
    {
        $in = implode( ",", array_keys( $list_cats ) );
        
        $id = $nv_Request->get_int( 'list_comment', 'get', 0 );
        
        if ( $id )
        {
            $array = array();
            $users = array();
            $admins = array();
            
            $page = $nv_Request->get_int( 'page', 'get', 0 );
            $per_page = 15;
            
            $query = "SELECT SQL_CALC_FOUND_ROWS a.id AS id, a.subject AS subject, a.post_id AS post_id, a.post_name AS post_name, a.post_email AS post_email, 
            a.post_ip AS post_ip, a.post_time AS post_time, a.comment AS comment, a.admin_reply AS admin_reply, a.admin_id AS admin_id, 
            c.email as email, c.full_name as full_name, c.photo as photo, c.view_mail as view_mail  
            FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` AS a 
            INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.fid = b.id 
			LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` as c ON a.post_id =c.userid	            
            WHERE a.fid=" . $id . " AND a.status=1 AND b.catid IN (" . $in . ") AND b.status=1 AND b.comment_allow=1 
            ORDER BY a.post_time DESC LIMIT " . $page . "," . $per_page;
            
            $result = $db->sql_query( $query );
            $query = $db->sql_query( "SELECT FOUND_ROWS()" );
            list( $all_page ) = $db->sql_fetchrow( $query );
            
            if ( $all_page )
            {
                $base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getcomment&amp;list_comment=" . $id;
                
                $today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
                $yesterday = $today - 86400;
                
                while ( $row = $db->sql_fetchrow( $result ) )
                {
                    $post_name = $row['post_name'];
                    if ( ! $row['post_id'] )
                    {
                        $post_name .= " (" . nv_EncodeEmail( $row['post_email'] ) . ", " . $row['post_ip'] . ")";
                        $row['photo'] = "";
                    }
                    else
                    {
                        $row['post_email'] = ( $row['view_mail'] ) ? $row['email'] : "";
                        $row['post_name'] = $row['full_name'];
                        if ( defined( 'NV_IS_MODADMIN' ) )
                        {
                            if ( isset( $users[$row['post_id']] ) )
                            {
                                $users[$row['post_id']][] = ( int )$row['id'];
                            }
                            else
                            {
                                $users[$row['post_id']] = array( 
                                    $row['id'] 
                                );
                            }
                            $post_name = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['post_id'] . "\">" . $post_name . "</a>";
                        }
                    }
                    
                    $post_time = ( int )$row['post_time'];
                    if ( $post_time >= $today )
                    {
                        $post_time = $lang_module['today'] . ", " . date( "H:i", $post_time );
                    }
                    elseif ( $post_time >= $yesterday )
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
                            if ( isset( $admins[$row['admin_id']] ) )
                            {
                                $admins[$row['admin_id']][] = ( int )$row['id'];
                            }
                            else
                            {
                                $admins[$row['admin_id']] = array( 
                                    $row['id'] 
                                );
                            }
                            $admin_reply = $row['admin_reply'];
                        }
                        else
                        {
                            $admin_reply = $lang_module['comment_admin_note'] . ": " . $row['admin_reply'];
                        }
                    }
                    
                    $array[$row['id']] = array(  //
                        'id' => ( int )$row['id'], //
						'post_name' => $post_name, //
						'post_email' => $row['post_email'], //
						'photo' => $row['photo'], //
						'post_ip' => $row['post_ip'], //
						'post_time' => $post_time, //
						'subject' => $row['subject'], //
						'comment' => $row['comment'], //
						'admin_reply' => $admin_reply, //
						'edit_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment&amp;edit=1&amp;id=" . $row['id'], //
						'del_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=comment" 
                    ); //
                }
                
                if ( ! empty( $users ) )
                {
                    $in = array_keys( $users );
                    $in = array_unique( $in );
                    $in = implode( ",", $in );
                    
                    $query = "SELECT `view_mail`, `userid` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (" . $in . ")";
                    $result = $db->sql_query( $query );
                    while ( list( $view_mail, $userid ) = $db->sql_fetchrow( $result ) )
                    {
                        if ( isset( $users[$userid] ) )
                        {
                            foreach ( $users[$userid] as $id )
                            {
                                if ( ! empty( $array[$id]['post_email'] ) and ( defined( 'NV_IS_ADMIN' ) or $view_mail ) )
                                {
                                    $array[$id]['post_email'] = nv_EncodeEmail( $array[$id]['post_email'] );
                                    $array[$id]['post_name'] .= " (" . $array[$id]['post_email'] . ", " . $array[$id]['post_ip'] . ")";
                                }
                                else
                                {
                                    $array[$id]['post_email'] = "";
                                }
                            }
                        }
                    }
                }
                
                if ( ! empty( $admins ) )
                {
                    $in = array_keys( $admins );
                    $in = array_unique( $in );
                    $in = implode( ",", $in );
                    
                    $query = "SELECT `userid` AS admin_id, `username` AS admin_login, `full_name` AS admin_name FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid` IN (" . $in . ")";
                    $result = $db->sql_query( $query );
                    while ( list( $admin_id, $admin_login, $admin_name ) = $db->sql_fetchrow( $result ) )
                    {
                        $admin_name = ! empty( $admin_name ) ? $admin_name : $admin_login;
                        
                        if ( isset( $admins[$admin_id] ) )
                        {
                            foreach ( $admins[$admin_id] as $id )
                            {
                                $array[$id]['admin_reply'] = $lang_module['comment_admin_note'] . " <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=authors&amp;id=" . $admin_id . "\">" . $admin_name . "</a>: " . $array[$id]['admin_reply'];
                            }
                        }
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

?>