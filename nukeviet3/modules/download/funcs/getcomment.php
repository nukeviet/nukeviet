<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );
global $configdownload, $ips;
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
        $comment_array[] = array( 
            "comment" => $row['comment'], "date" => $row['date'], "name" => $row['name'], "email" => $row['email'] 
        );
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
        $k ++;
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
    }
    elseif ( $validemail )
    {
        echo $lang_module['comment_noemail'];
    }
    elseif ( $content == '' || strlen( $content ) < 10 )
    {
        echo $lang_module['comment_nocontent'];
    }
    elseif ( ! nv_capcha_txt( $captcha ) )
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