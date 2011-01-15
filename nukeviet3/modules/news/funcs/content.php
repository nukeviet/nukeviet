<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 12-11-2010 20:40
 */
if ( ! defined( 'NV_IS_MOD_NEWS' ) )
{
    die( 'Stop!!!' );
}

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
else if ( ! function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' ) )
{
    define( 'NV_EDITOR', TRUE );
    define( 'NV_IS_CKEDITOR', TRUE );
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor_php5.php' );

    function nv_aleditor ( $textareaname, $width = "100%", $height = '450px', $val = '' )
    {
        // Create class instance.
        $editortoolbar = array( 
            array( 
            'Link', 'Unlink', 'Image', 'Table', 'Font', 'FontSize', 'RemoveFormat' 
        ), array( 
            'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Subscript', 'Superscript', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'OrderedList', 'UnorderedList', '-', 'Outdent', 'Indent', 'TextColor', 'BGColor', 'Source' 
        ) 
        );
        $CKEditor = new CKEditor();
        // Do not print the code directly to the browser, return it instead
        $CKEditor->returnOutput = true;
        $CKEditor->config['skin'] = 'kama';
        $CKEditor->config['entities'] = false;
        //$CKEditor->config['enterMode'] = 2;
        $CKEditor->config['language'] = NV_LANG_INTERFACE;
        $CKEditor->config['toolbar'] = $editortoolbar;
        // Path to CKEditor directory, ideally instead of relative dir, use an absolute path:
        //   $CKEditor->basePath = '/ckeditor/'
        // If not set, CKEditor will try to detect the correct path.
        $CKEditor->basePath = NV_BASE_SITEURL . '' . NV_EDITORSDIR . '/ckeditor/';
        // Set global configuration (will be used by all instances of CKEditor).
        if ( ! empty( $width ) )
        {
            $CKEditor->config['width'] = strpos( $width, '%' ) ? $width : intval( $width );
        }
        if ( ! empty( $height ) )
        {
            $CKEditor->config['height'] = strpos( $height, '%' ) ? $height : intval( $height );
        }
        // Change default textarea attributes
        $CKEditor->textareaAttributes = array( 
            "cols" => 80, "rows" => 10 
        );
        $val = nv_unhtmlspecialchars( $val );
        return $CKEditor->editor( $textareaname, $val );
    }
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

//check user post content
$array_post_config = array();
$sql = "SELECT pid, member, group_id, addcontent, postcontent, editcontent, delcontent FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config_post` ORDER BY `pid` ASC";
$result = $db->sql_query( $sql );
while ( list( $pid, $member, $group_id, $addcontent, $postcontent, $editcontent, $delcontent ) = $db->sql_fetchrow( $result ) )
{
    $array_post_config[$member][$group_id] = array( 
        "addcontent" => $addcontent, "postcontent" => $postcontent, "editcontent" => $editcontent, "delcontent" => $delcontent 
    );
}

if ( isset( $array_post_config[0][0] ) )
{
    $array_post_user = $array_post_config[0][0];
}
else
{
    $array_post_user = array( 
        "addcontent" => 0, "postcontent" => 0, "editcontent" => 0, "delcontent" => 0 
    );
}

if ( defined( 'NV_IS_USER' ) )
{
    if ( $array_post_config[1][0]['addcontent'] )
    {
        $array_post_user['addcontent'] = 1;
    }
    if ( $array_post_config[1][0]['postcontent'] )
    {
        $array_post_user['postcontent'] = 1;
    }
    if ( $array_post_config[1][0]['editcontent'] )
    {
        $array_post_user['editcontent'] = 1;
    }
    if ( $array_post_config[1][0]['delcontent'] )
    {
        $array_post_user['delcontent'] = 1;
    }
    
    if ( ! empty( $user_info['in_groups'] ) )
    {
        $array_in_groups = explode( ",", $user_info['in_groups'] );
        foreach ( $array_in_groups as $group_id_i )
        {
            if ( $group_id_i > 0 and isset( $array_post_config[1][$group_id_i] ) )
            {
                if ( $array_post_config[1][$group_id_i]['addcontent'] )
                {
                    $array_post_user['addcontent'] = 1;
                }
                if ( $array_post_config[1][$group_id_i]['postcontent'] )
                {
                    $array_post_user['postcontent'] = 1;
                }
                if ( $array_post_config[1][$group_id_i]['editcontent'] )
                {
                    $array_post_user['editcontent'] = 1;
                }
                if ( $array_post_config[1][$group_id_i]['delcontent'] )
                {
                    $array_post_user['delcontent'] = 1;
                }
            }
        }
    }
}
if ( $array_post_user['postcontent'] )
{
    $array_post_user['addcontent'] = 1;
}
//check user post content


$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
if ( ! $array_post_user['addcontent'] )
{
    if ( defined( 'NV_IS_USER' ) )
    {
        $array_temp['urlrefresh'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA;
    }
    else
    {
        $array_temp['urlrefresh'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
    }
    
    $array_temp['content'] = $lang_module['error_addcontent'];
    $template = $module_info['template'];
    if ( ! file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/content.tpl" ) )
    {
        $template = "default";
    }
    
    $xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $template . "/modules/" . $module_file );
    $xtpl->assign( 'DATA', $array_temp );
    $xtpl->parse( 'mainrefresh' );
    $contents = $xtpl->text( 'mainrefresh' );
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

if ( $nv_Request->isset_request( 'get_alias', 'post' ) )
{
    $title = filter_text_input( 'get_alias', 'post', '' );
    $alias = change_alias( $title );
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $alias;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}

$contentid = $nv_Request->get_int( 'contentid', 'get,post', 0 );
$fcheckss = filter_text_input( 'checkss', 'get,post', '' );
$checkss = md5( $contentid . $client_info['session_id'] . $global_config['sitekey'] );
if ( $nv_Request->isset_request( 'contentid', 'get,post' ) and $fcheckss == $checkss )
{
    if ( $contentid > 0 )
    {
        $rowcontent_old = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `id`=" . $contentid . " and `admin_id`= " . $user_info['userid'] . "" ) );
        $contentid = ( isset( $rowcontent_old['id'] ) ) ? intval( $rowcontent_old['id'] ) : 0;
        if ( empty( $contentid ) )
        {
            Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
            die();
        }
        
        if ( $nv_Request->get_int( 'delcontent', 'get' ) and ( empty( $rowcontent_old['status'] ) or $array_post_user['delcontent'] ) )
        {
            nv_del_content_module( $contentid );
            Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
            die();
        }
        elseif ( ! ( empty( $rowcontent_old['status'] ) or $array_post_user['editcontent'] ) )
        {
            Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
            die();
        }
    }
    
    $array_mod_title[] = array( 
        'catid' => 0, 'title' => $lang_module['add_content'], 'link' => $base_url 
    );
    
    $array_imgposition = array( 
        0 => $lang_module['imgposition_0'], 1 => $lang_module['imgposition_1'], 2 => $lang_module['imgposition_2'] 
    );
    
    $rowcontent = array( 
        "id" => "", "listcatid" => "", "topicid" => "", "admin_id" => ( defined( 'NV_IS_USER' ) ) ? $user_info['userid'] : 0, "author" => "", "sourceid" => 0, "addtime" => NV_CURRENTTIME, "edittime" => NV_CURRENTTIME, "status" => 0, "publtime" => NV_CURRENTTIME, "exptime" => 0, "archive" => 1, "title" => "", "alias" => "", "hometext" => "", "homeimgfile" => "", "homeimgalt" => "", "homeimgthumb" => "|", "imgposition" => 1, "bodytext" => "", "copyright" => 0, "inhome" => 1, "allowed_comm" => $module_config[$module_name]['setcomm'], "allowed_rating" => 1, "allowed_send" => 1, "allowed_print" => 1, "allowed_save" => 1, "hitstotal" => 0, "hitscm" => 0, "total_rating" => 0, "click_rating" => 0, "keywords" => "" 
    );
    
    $array_catid_module = array();
    $sql = "SELECT catid, title, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
    $result_cat = $db->sql_query( $sql );
    while ( list( $catid_i, $title_i, $lev_i ) = $db->sql_fetchrow( $result_cat ) )
    {
        $array_catid_module[] = array( 
            "catid" => $catid_i, "title" => $title_i, "lev" => $lev_i 
        );
    }
    
    $sql = "SELECT topicid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $array_topic_module = array();
    $array_topic_module[0] = $lang_module['topic_sl'];
    while ( list( $topicid_i, $title_i ) = $db->sql_fetchrow( $result ) )
    {
        $array_topic_module[$topicid_i] = $title_i;
    }
    $error = "";
    if ( $nv_Request->isset_request( 'contentid', 'post' ) )
    {
        $rowcontent['id'] = $contentid;
        $fcode = filter_text_input( 'fcode', 'post', '' );
        $catids = array_unique( $nv_Request->get_typed_array( 'catids', 'post', 'int', array() ) );
        
        $rowcontent['listcatid'] = implode( ",", $catids );
        $rowcontent['topicid'] = $nv_Request->get_int( 'topicid', 'post', 0 );
        $rowcontent['author'] = filter_text_input( 'author', 'post', '', 1 );
        
        $rowcontent['title'] = filter_text_input( 'title', 'post', '', 1 );
        $alias = filter_text_input( 'alias', 'post', '' );
        $rowcontent['alias'] = ( $alias == "" ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );
        
        $rowcontent['hometext'] = filter_text_input( 'hometext', 'post', '' );
        
        $rowcontent['homeimgfile'] = filter_text_input( 'homeimgfile', 'post', '' );
        $rowcontent['homeimgalt'] = filter_text_input( 'homeimgalt', 'post', '', 1 );
        $rowcontent['imgposition'] = $nv_Request->get_int( 'imgposition', 'post', 0 );
        if ( ! nv_is_url( $rowcontent['homeimgfile'] ) )
        {
            $rowcontent['homeimgfile'] = "";
        }
        if ( ! array_key_exists( $rowcontent['imgposition'], $array_imgposition ) )
        {
            $rowcontent['imgposition'] = 1;
        }
        if ( ! array_key_exists( $rowcontent['topicid'], $array_topic_module ) )
        {
            $rowcontent['topicid'] = 0;
        }
        
        $bodytext = $nv_Request->get_string( 'bodytext', 'post', '' );
        $rowcontent['bodytext'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodytext, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodytext ) ), '<br />' );
        
        $rowcontent['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
        
        if ( empty( $rowcontent['title'] ) )
        {
            $error = $lang_module['error_title'];
        }
        elseif ( empty( $rowcontent['listcatid'] ) )
        {
            $error = $lang_module['error_cat'];
        }
        elseif ( trim( strip_tags( $rowcontent['bodytext'] ) ) == "" )
        {
            $error = $lang_module['error_bodytext'];
        }
        elseif ( ! nv_capcha_txt( $fcode ) )
        {
            $error = $lang_module['error_captcha'];
        }
        else
        {
            $rowcontent['status'] = ( $array_post_user['postcontent'] and $nv_Request->isset_request( 'status1', 'post' ) ) ? 1 : 0;
            if ( $rowcontent['id'] == 0 )
            {
                $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `status`, `publtime`, `exptime`, `archive`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `bodytext`, `copyright`, `inhome`, `allowed_comm`, `allowed_rating`, `allowed_send`, `allowed_print`, `allowed_save`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords`) VALUES 
                (NULL, " . $db->dbescape_string( $rowcontent['listcatid'] ) . ",
                " . intval( $rowcontent['topicid'] ) . ",
                " . intval( $rowcontent['admin_id'] ) . ",
                " . $db->dbescape_string( $rowcontent['author'] ) . ",
                " . intval( $rowcontent['sourceid'] ) . ",
                " . intval( $rowcontent['addtime'] ) . ",
                " . intval( $rowcontent['edittime'] ) . ",
                " . intval( $rowcontent['status'] ) . ",
                " . intval( $rowcontent['publtime'] ) . ",
                " . intval( $rowcontent['exptime'] ) . ", 
                " . intval( $rowcontent['archive'] ) . ",
                " . $db->dbescape_string( $rowcontent['title'] ) . ",
                " . $db->dbescape_string( $rowcontent['alias'] ) . ",
                " . $db->dbescape_string( $rowcontent['hometext'] ) . ",
                " . $db->dbescape_string( $rowcontent['homeimgfile'] ) . ",
                " . $db->dbescape_string( $rowcontent['homeimgalt'] ) . ",
                " . $db->dbescape_string( $rowcontent['homeimgthumb'] ) . ",
                " . intval( $rowcontent['imgposition'] ) . ",
                " . $db->dbescape_string( $rowcontent['bodytext'] ) . ",
                " . intval( $rowcontent['copyright'] ) . ",  
                " . intval( $rowcontent['inhome'] ) . ",  
                " . intval( $rowcontent['allowed_comm'] ) . ",  
                " . intval( $rowcontent['allowed_rating'] ) . ",  
                " . intval( $rowcontent['allowed_send'] ) . ",  
                " . intval( $rowcontent['allowed_print'] ) . ",  
                " . intval( $rowcontent['allowed_save'] ) . ",  
                " . intval( $rowcontent['hitstotal'] ) . ",  
                " . intval( $rowcontent['hitscm'] ) . ",  
                " . intval( $rowcontent['total_rating'] ) . ",  
                " . intval( $rowcontent['click_rating'] ) . ",  
                " . $db->dbescape_string( $rowcontent['keywords'] ) . ")";
                $rowcontent['id'] = $db->sql_query_insert_id( $query );
                if ( $rowcontent['id'] > 0 )
                {
                    foreach ( $catids as $catid )
                    {
                        $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $rowcontent['id'] . "" );
                    }
                }
                else
                {
                    $error = $lang_module['errorsave'];
                }
                $db->sql_freeresult();
            }
            else
            {
                if ( $rowcontent_old['status'] == 1 )
                {
                    $rowcontent['status'] = 1;
                }
                $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET 
                           `listcatid`=" . $db->dbescape_string( $rowcontent['listcatid'] ) . ", 
                           `topicid`=" . intval( $rowcontent['topicid'] ) . ", 
                           `author`=" . $db->dbescape_string( $rowcontent['author'] ) . ", 
                           `sourceid`=" . intval( $rowcontent['sourceid'] ) . ", 
                           `status`=" . intval( $rowcontent['status'] ) . ", 
                           `publtime`=" . intval( $rowcontent['publtime'] ) . ", 
                           `exptime`=" . intval( $rowcontent['exptime'] ) . ", 
                           `archive`=" . intval( $rowcontent['archive'] ) . ", 
                           `title`=" . $db->dbescape_string( $rowcontent['title'] ) . ", 
                           `alias`=" . $db->dbescape_string( $rowcontent['alias'] ) . ", 
                           `hometext`=" . $db->dbescape_string( $rowcontent['hometext'] ) . ", 
                           `homeimgfile`=" . $db->dbescape_string( $rowcontent['homeimgfile'] ) . ",
                           `homeimgalt`=" . $db->dbescape_string( $rowcontent['homeimgalt'] ) . ",
                           `homeimgthumb`=" . $db->dbescape_string( $rowcontent['homeimgthumb'] ) . ",
                           `imgposition`=" . intval( $rowcontent['imgposition'] ) . ",
                           `bodytext`=" . $db->dbescape_string( $rowcontent['bodytext'] ) . ", 
                           `copyright`=" . intval( $rowcontent['copyright'] ) . ", 
                           `inhome`=" . intval( $rowcontent['inhome'] ) . ", 
                           `allowed_comm`=" . intval( $rowcontent['allowed_comm'] ) . ", 
                           `allowed_rating`=" . intval( $rowcontent['allowed_rating'] ) . ", 
                           `allowed_send`=" . intval( $rowcontent['allowed_send'] ) . ", 
                           `allowed_print`=" . intval( $rowcontent['allowed_print'] ) . ", 
                           `allowed_save`=" . intval( $rowcontent['allowed_save'] ) . ", 
                           `keywords`=" . $db->dbescape_string( $rowcontent['keywords'] ) . ", 
                           `edittime`=UNIX_TIMESTAMP( ) 
                        WHERE `id` =" . $rowcontent['id'] . "";
                $db->sql_query( $query );
                
                if ( $db->sql_affectedrows() > 0 )
                {
                    $array_cat_old = explode( ",", $rowcontent_old['listcatid'] );
                    foreach ( $array_cat_old as $catid )
                    {
                        $db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` WHERE `id` = " . $rowcontent['id'] . "" );
                    }
                    $array_cat_new = explode( ",", $rowcontent['listcatid'] );
                    foreach ( $array_cat_new as $catid )
                    {
                        $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $rowcontent['id'] . "" );
                    }
                }
                else
                {
                    $error = $lang_module['errorsave'];
                }
            }
            if ( empty( $error ) )
            {
                $array_temp = array();
                if ( defined( 'NV_IS_USER' ) )
                {
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
                    if ( $rowcontent['status'] )
                    {
                        $array_temp['content'] = $lang_module['save_content_ok'];
                        nv_del_moduleCache( $module_name );
                    }
                    else
                    {
                        $array_temp['content'] = $lang_module['save_content_waite'];
                    }
                }
                elseif ( $rowcontent['status'] == 1 and count( $catids ) > 0 )
                {
                    $catid = $catids[0];
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid]['alias'] . "/" . $rowcontent['alias'] . "-" . $rowcontent['id'];
                    $array_temp['content'] = $lang_module['save_content_view_page'];
                    nv_del_moduleCache( $module_name );
                }
                else
                {
                    $array_temp['urlrefresh'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA;
                    $array_temp['content'] = $lang_module['save_content_waite_home'];
                }
                
                $template = $module_info['template'];
                if ( ! file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/content.tpl" ) )
                {
                    $template = "default";
                }
                
                $xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $template . "/modules/" . $module_file );
                $xtpl->assign( 'DATA', $array_temp );
                $xtpl->parse( 'mainrefresh' );
                $contents = $xtpl->text( 'mainrefresh' );
                
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
        }
    }
    elseif ( $contentid > 0 )
    {
        $rowcontent = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `id`=" . $contentid . "" ) );
        if ( empty( $rowcontent['id'] ) )
        {
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
            die();
        }
    }
    
    if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
    {
        $htmlbodytext = nv_aleditor( 'bodytext', '510px', '300px', $rowcontent['bodytext'] );
    }
    else
    {
        $htmlbodytext .= "<textarea class=\"textareaform\" name=\"bodytext\" id=\"bodytext\" cols=\"60\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
    }
    
    if ( ! empty( $error ) )
    {
        $my_head .= "<script type=\"text/javascript\">\n";
        $my_head .= "	alert('" . $error . "')\n";
        $my_head .= "</script>\n";
    }
    
    $template = $module_info['template'];
    if ( ! file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/content.tpl" ) )
    {
        $template = "default";
    }
    
    $xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $template . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'DATA', $rowcontent );
    $xtpl->assign( 'HTMLBODYTEXT', $htmlbodytext );
    
    $xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
    $xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
    $xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . "images/refresh.png" );
    $xtpl->assign( 'NV_GFX_NUM', NV_GFX_NUM );
    $xtpl->assign( 'CHECKSS', $checkss );
    
    $xtpl->assign( 'CONTENT_URL', $base_url . "&contentid=" . $rowcontent['id'] . "&checkss=" . $checkss );
    $array_catid_in_row = explode( ",", $rowcontent['listcatid'] );
    foreach ( $array_catid_module as $value )
    {
        $xtitle_i = "";
        if ( $value['lev'] > 0 )
        {
            for ( $i = 1; $i <= $value['lev']; $i ++ )
            {
                $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
        }
        $array_temp = array();
        $array_temp['value'] = $value['catid'];
        $array_temp['title'] = $xtitle_i . $value['title'];
        $array_temp['checked'] = ( in_array( $value['catid'], $array_catid_in_row ) ) ? " checked=\"checked\"" : "";
        
        $xtpl->assign( 'DATACATID', $array_temp );
        $xtpl->parse( 'main.catid' );
    }
    
    while ( list( $topicid_i, $title_i ) = each( $array_topic_module ) )
    {
        $array_temp = array();
        $array_temp['value'] = $topicid_i;
        $array_temp['title'] = $title_i;
        $array_temp['selected'] = ( $topicid_i == $rowcontent['topicid'] ) ? " selected=\"selected\"" : "";
        $xtpl->assign( 'DATATOPIC', $array_temp );
        $xtpl->parse( 'main.topic' );
    }
    
    while ( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
    {
        $array_temp = array();
        $array_temp['value'] = $id_imgposition;
        $array_temp['title'] = $title_imgposition;
        $array_temp['selected'] = ( $id_imgposition == $rowcontent['imgposition'] ) ? " selected=\"selected\"" : "";
        
        $xtpl->assign( 'DATAIMGOP', $array_temp );
        $xtpl->parse( 'main.imgposition' );
    }
    if ( ! ( $rowcontent['status'] and $rowcontent['id'] ) )
    {
        $xtpl->parse( 'main.save_temp' );
    }
    if ( $array_post_user['postcontent'] or ( $rowcontent['status'] and $rowcontent['id'] and $array_post_user['editcontent'] ) )
    {
        $xtpl->parse( 'main.postcontent' );
    }
    
    $xtpl->parse( 'main' );
    $contents = $xtpl->text( 'main' );
    if ( empty( $rowcontent['alias'] ) )
    {
        $contents .= "<script type=\"text/javascript\">\n";
        $contents .= '$("#idtitle").change(function () {
    		get_alias();
		});';
        $contents .= "</script>\n";
    }
}
elseif ( defined( 'NV_IS_USER' ) )
{
    $page = 0;
    if ( isset( $array_op[1] ) and substr( $array_op[1], 0, 5 ) == "page-" )
    {
        $page = intval( substr( $array_op[1], 5 ) );
    }
    $array_catpage = array();
    $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `listcatid`, `addtime`, `edittime`, `status`, `publtime`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `hitstotal` , `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `admin_id`= " . $user_info['userid'] . " ORDER BY `id` DESC LIMIT " . $page . "," . $per_page . "";
    $result = $db->sql_query( $sql );
    
    $result_all = $db->sql_query( "SELECT FOUND_ROWS()" );
    list( $numf ) = $db->sql_fetchrow( $result_all );
    $all_page = ( $numf ) ? $numf : 1;
    
    while ( $item = $db->sql_fetchrow( $result ) )
    {
        if ( ! empty( $item['homeimgthumb'] ) )
        {
            $array_img = explode( "|", $item['homeimgthumb'] );
        }
        else
        {
            $array_img = array( 
                "", "" 
            );
        }
        
        if ( $array_img[0] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_img[0] ) )
        {
            $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_img[0];
        }
        elseif ( nv_is_url( $item['homeimgfile'] ) )
        {
            $item['imghome'] = $item['homeimgfile'];
        }
        elseif ( $item['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $item['homeimgfile'] ) )
        {
            $item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $item['homeimgfile'];
        }
        else
        {
            $item['imghome'] = "";
        }
        
        $item['is_edit_content'] = ( empty( $item['status'] ) or $array_post_user['editcontent'] ) ? 1 : 0;
        $item['is_del_content'] = ( empty( $item['status'] ) or $array_post_user['delcontent'] ) ? 1 : 0;
        
        $catid = end( explode( ",", $item['listcatid'] ) );
        $item['link'] = $global_array_cat[$catid]['link'] . "/" . $item['alias'] . "-" . $item['id'];
        $array_catpage[] = $item;
    }
    
    // parse content
    $xtpl = new XTemplate( "viewcat_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );
    $a = 0;
    foreach ( $array_catpage as $array_row_i )
    {
        $array_row_i['publtime'] = nv_date( 'd-m-Y h:i:s A', $array_row_i['publtime'] );
        $xtpl->clear_autoreset();
        $xtpl->assign( 'CONTENT', $array_row_i );
        $id = $array_row_i['id'];
        $array_link_content = array();
        if ( $array_row_i['is_edit_content'] )
        {
            $array_link_content[] = "<span class=\"edit_icon\"><a href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;checkss=" . md5( $id . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_global['edit'] . "</a></span>";
        }
        if ( $array_row_i['is_del_content'] )
        {
            $array_link_content[] = "<span class=\"delete_icon\"><a  onclick=\"return confirm(nv_is_del_confirm[0]);\" href=\"" . $base_url . "&amp;contentid=" . $id . "&amp;delcontent=1&amp;checkss=" . md5( $id . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_global['delete'] . "</a></span>";
        }
        
        if ( ! empty( $array_link_content ) )
        {
            $xtpl->assign( 'ADMINLINK', implode( "&nbsp;-&nbsp;", $array_link_content ) );
            $xtpl->parse( 'main.viewcatloop.adminlink' );
        }
        
        if ( $array_row_i['imghome'] != "" )
        {
            $xtpl->assign( 'HOMEIMG1', $array_row_i['imghome'] );
            $xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
            $xtpl->parse( 'main.viewcatloop.image' );
        }
        $xtpl->set_autoreset();
        $xtpl->parse( 'main.viewcatloop' );
        $a ++;
    }
    $xtpl->parse( 'main' );
    
    $contents .= "<div style=\"border: 1px solid #ccc;margin: 10px; font-size: 15px; font-weight: bold; text-align: center;\"><a href=\"" . $base_url . "&amp;contentid=0&checkss=" . md5( "0" . $client_info['session_id'] . $global_config['sitekey'] ) . "\">" . $lang_module['add_content'] . "</a></h1></div>";
    $contents .= $xtpl->text( 'main' );
    $contents .= nv_news_page( $base_url, $all_page, $per_page, $page );
}
elseif ( $array_post_user['addcontent'] )
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&contentid=0&checkss=" . md5( "0" . $client_info['session_id'] . $global_config['sitekey'] ) . "" );
    die();
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>