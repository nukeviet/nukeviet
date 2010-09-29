<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
$month_dir_module = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, date( "Y_m" ), true );
$array_block_cat_module = array();
$id_block_content = array();
$sql = "SELECT bid, adddefault, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $bid_i, $adddefault_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
    $array_block_cat_module[$bid_i] = $title_i;
    if ( $adddefault_i )
    {
        $id_block_content[] = $bid_i;
    }
}

$catid = $nv_Request->get_int( 'catid', 'get', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );
$array_imgposition = array( 
    0 => $lang_module['imgposition_0'], 1 => $lang_module['imgposition_1'], 2 => $lang_module['imgposition_2'] 
);

$rowcontent = array( 
    "id" => "", "listcatid" => "" . $catid . "," . $parentid . "", "topicid" => "", "admin_id" => $admin_info['admin_id'], "author" => "", "sourceid" => 0, "addtime" => NV_CURRENTTIME, "edittime" => NV_CURRENTTIME, "status" => 0, "publtime" => NV_CURRENTTIME, "exptime" => 0, "archive" => 1, "title" => "", "alias" => "", "hometext" => "", "homeimgfile" => "", "homeimgalt" => "", "homeimgthumb" => "", "imgposition" => 1, "bodytext" => "", "copyright" => 0, "inhome" => 1, "allowed_comm" => $module_config[$module_name]['setcomm'], "allowed_rating" => 1, "ratingdetail" => "0|0", "allowed_send" => 1, "allowed_print" => 1, "allowed_save" => 1, "hitstotal" => 0, "hitscm" => 0, "hitslm" => 0, "keywords" => "" 
);

$rowcontent['sourcetext'] = "";
$rowcontent['topictext'] = "";
$page_title = $lang_module['content_add'];
$error = "";
$groups_list = nv_groups_list();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );

if ( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $catids = array_unique( $nv_Request->get_typed_array( 'catids', 'post', 'int', array() ) );
    $id_block_content = array_unique( $nv_Request->get_typed_array( 'bids', 'post', 'int', array() ) );
    
    $rowcontent['listcatid'] = implode( ",", $catids );
    $rowcontent['topicid'] = $nv_Request->get_int( 'topicid', 'post', 0 );
    if ( $rowcontent['topicid'] == 0 )
    {
        $rowcontent['topictext'] = filter_text_input( 'topictext', 'post', '' );
        if ( ! empty( $rowcontent['topictext'] ) )
        {
            list( $rowcontent['topicid'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `topicid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` WHERE `title`=" . $db->dbescape( $rowcontent['topictext'] ) . "" ) );
        }
    }
    $rowcontent['author'] = filter_text_input( 'author', 'post', '', 1 );
    $rowcontent['sourceid'] = $nv_Request->get_int( 'sourceid', 'post', 0 );
    if ( $rowcontent['sourceid'] == 0 )
    {
        $rowcontent['sourcetext'] = filter_text_input( 'sourcetext', 'post', '' );
        if ( ! empty( $rowcontent['sourcetext'] ) )
        {
            list( $rowcontent['sourceid'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `title`=" . $db->dbescape( $rowcontent['sourcetext'] ) . "" ) );
        }
    }
    if ( intval( $rowcontent['sourceid'] ) > 0 ) $rowcontent['sourcetext'] = "";
    
    $publ_date = filter_text_input( 'publ_date', 'post', '' );
    $exp_date = filter_text_input( 'exp_date', 'post', '' );
    
    if ( ! empty( $publ_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date ) ) $publ_date = "";
    if ( ! empty( $exp_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date ) ) $exp_date = "";
    if ( empty( $publ_date ) )
    {
        $rowcontent['publtime'] = NV_CURRENTTIME;
    }
    else
    {
        $phour = $nv_Request->get_int( 'phour', 'post', 0 );
        $pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
        unset( $m );
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date, $m );
        $rowcontent['publtime'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
    }
    
    if ( empty( $exp_date ) )
    {
        $rowcontent['exptime'] = 0;
    }
    else
    {
        $ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
        $emin = $nv_Request->get_int( 'emin', 'post', 0 );
        unset( $m );
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date, $m );
        $rowcontent['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
    }
    
    $rowcontent['archive'] = $nv_Request->get_int( 'archive', 'post', 0 );
    if ( $rowcontent['archive'] > 0 )
    {
        $rowcontent['archive'] = ( $rowcontent['exptime'] > NV_CURRENTTIME ) ? 1 : 2;
    }
    $rowcontent['title'] = filter_text_input( 'title', 'post', '', 1 );
    
    $alias = filter_text_input( 'alias', 'post', '' );
    $rowcontent['alias'] = ( $alias == "" ) ? change_alias( $rowcontent['title'] ) : change_alias( $alias );
    
    $rowcontent['hometext'] = filter_text_input( 'hometext', 'post', '' );
    
    $rowcontent['homeimgfile'] = filter_text_input( 'homeimg', 'post', '' );
    $rowcontent['homeimgalt'] = filter_text_input( 'homeimgalt', 'post', '', 1 );
    $rowcontent['imgposition'] = $nv_Request->get_int( 'imgposition', 'post', 0 );
    if ( ! array_key_exists( $rowcontent['imgposition'], $array_imgposition ) )
    {
        $rowcontent['imgposition'] = 1;
    }
    $bodytext = $nv_Request->get_string( 'bodytext', 'post', '' );
    $rowcontent['bodytext'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodytext, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodytext ) ), '<br />' );
    
    $sourcetext = filter_text_input( 'sourcetext', 'post', '', 1 );
    $rowcontent['copyright'] = ( int )$nv_Request->get_bool( 'copyright', 'post' );
    $rowcontent['inhome'] = ( int )$nv_Request->get_bool( 'inhome', 'post' );
    
    $rowcontent['allowed_comm'] = $nv_Request->get_int( 'allowed_comm', 'post', 0 );
    
    $rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool( 'allowed_rating', 'post' );
    $rowcontent['allowed_send'] = ( int )$nv_Request->get_bool( 'allowed_send', 'post' );
    $rowcontent['allowed_print'] = ( int )$nv_Request->get_bool( 'allowed_print', 'post' );
    $rowcontent['allowed_save'] = ( int )$nv_Request->get_bool( 'allowed_save', 'post' );
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
    else
    {
        if ( ! empty( $rowcontent['topictext'] ) )
        {
            list( $weightopic ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics`" ) );
            $weightopic = intval( $weightopic ) + 1;
            $aliastopic = change_alias( $rowcontent['topictext'] );
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_topics` (`topicid`, `title`, `alias`, `description`, `image`, `thumbnail`, `weight`, `keywords`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $rowcontent['topictext'] ) . ", " . $db->dbescape( $aliastopic ) . ", " . $db->dbescape( $rowcontent['topictext'] ) . ", '', '', " . $db->dbescape( $weightopic ) . ", " . $db->dbescape( $rowcontent['topictext'] ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
            $rowcontent['topicid'] = $db->sql_query_insert_id( $query );
        }
        if ( ! empty( $rowcontent['sourcetext'] ) )
        {
            list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources`" ) );
            $weight = intval( $weight ) + 1;
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_sources` (`sourceid`, `title`, `link`, `logo`, `weight`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $rowcontent['sourcetext'] ) . ", '', '', " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
            $rowcontent['sourceid'] = $db->sql_query_insert_id( $query );
        }
        if ( $rowcontent['keywords'] == "" )
        {
            if ( $rowcontent['hometext'] != "" )
            {
                $rowcontent['keywords'] = nv_content_keywords( $rowcontent['hometext'] );
            }
            else
            {
                $rowcontent['keywords'] = nv_content_keywords( $rowcontent['bodytext'] );
            }
        }
        $rowcontent['status'] = ( $nv_Request->isset_request( 'status1', 'post' ) ) ? 1 : 0;
        
        // Xu ly anh minh ha
        $rowcontent['homeimgthumb'] = "";
        if ( ! nv_is_url( $rowcontent['homeimgfile'] ) and file_exists( NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'] ) )
        {
            $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
            $rowcontent['homeimgfile'] = substr( $rowcontent['homeimgfile'], $lu );
        }
        elseif ( ! nv_is_url( $rowcontent['homeimgfile'] ) )
        {
            $rowcontent['homeimgfile'] = "";
        }
        $check_thumb = false;
        if ( $rowcontent['id'] > 0 )
        {
            list( $homeimgfile, $homeimgthumb ) = $db->sql_fetchrow( $db->sql_query( "SELECT `homeimgfile`, `homeimgthumb` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $rowcontent['id'] . "" ) );
            if ( $rowcontent['homeimgfile'] != $homeimgfile )
            {
                $check_thumb = true;
                if ( $homeimgthumb != "" and $homeimgthumb != "|" )
                {
                    $rowcontent['homeimgthumb'] = "";
                    $homeimgthumb_arr = explode( "|", $homeimgthumb );
                    foreach ( $homeimgthumb_arr as $homeimgthumb_i )
                    {
                        if ( file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $homeimgthumb_i ) )
                        {
                            nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $homeimgthumb_i );
                        }
                    }
                
                }
            }
            else
            {
                $rowcontent['homeimgthumb'] = $homeimgthumb;
            }
        }
        elseif ( ! empty( $rowcontent['homeimgfile'] ) )
        {
            $check_thumb = true;
        }
        $homeimgfile = NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $rowcontent['homeimgfile'];
        if ( $check_thumb and file_exists( $homeimgfile ) )
        {
            require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
            
            $basename = basename( $homeimgfile );
            $image = new image( $homeimgfile, NV_MAX_WIDTH, NV_MAX_HEIGHT );
            
            $thumb_basename = $basename;
            $i = 1;
            while ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/' . $thumb_basename ) )
            {
                $thumb_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
                $i ++;
            }
            
            $image->resizeXY( $module_config[$module_name]['homewidth'], $module_config[$module_name]['homeheight'] );
            $image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb', $thumb_basename );
            $image_info = $image->create_Image_info;
            $thumb_name = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/', '', $image_info['src'] );
            
            $block_basename = $basename;
            $i = 1;
            while ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/block/' . $block_basename ) )
            {
                $block_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
                $i ++;
            }
            $image->resizeXY( $module_config[$module_name]['blockwidth'], $module_config[$module_name]['blockheight'] );
            $image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/block', $block_basename );
            $image_info = $image->create_Image_info;
            $block_name = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/', '', $image_info['src'] );
            
            $image->close();
            $rowcontent['homeimgthumb'] = $thumb_name . "|" . $block_name;
        }
        
        if ( $rowcontent['id'] == 0 )
        {
            $rowcontent['publtime'] = ( $rowcontent['publtime'] > NV_CURRENTTIME ) ? $rowcontent['publtime'] : NV_CURRENTTIME;
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `status`, `publtime`, `exptime`, `archive`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `imgposition`, `bodytext`, `copyright`, `inhome`, `allowed_comm`, `allowed_rating`, `ratingdetail`, `allowed_send`, `allowed_print`, `allowed_save`, `hitstotal`, `hitscm`, `hitslm`, `keywords`) VALUES 
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
                " . $db->dbescape_string( $rowcontent['ratingdetail'] ) . ",  
                " . intval( $rowcontent['allowed_send'] ) . ",  
                " . intval( $rowcontent['allowed_print'] ) . ",  
                " . intval( $rowcontent['allowed_save'] ) . ",  
                " . intval( $rowcontent['hitstotal'] ) . ",  
                " . intval( $rowcontent['hitscm'] ) . ",  
                " . intval( $rowcontent['hitslm'] ) . ",  
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
            nv_save_log_content( $rowcontent['id'] );
            $rowcontent_old = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `id`=" . $rowcontent['id'] . "" ) );
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
            $db->sql_freeresult();
        }
        nv_del_moduleCache( $module_name );
        if ( $error == "" )
        {
            if ( $rowcontent['publtime'] > NV_CURRENTTIME or $rowcontent['exptime'] > 0 )
            {
                $rowcontent['exptime'] = ( $rowcontent['exptime'] > 0 ) ? $rowcontent['exptime'] : NV_CURRENTTIME + 26000000;
                $array_cat_new = explode( ",", $rowcontent['listcatid'] );
                foreach ( $array_cat_new as $catid )
                {
                    list( $del_cache_time ) = $db->sql_fetchrow( $db->sql_query( "SELECT `del_cache_time` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat`  WHERE `catid` =" . $catid . "" ) );
                    $del_cache_time = min( $rowcontent['publtime'], $rowcontent['exptime'], $del_cache_time );
                    $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `del_cache_time`=" . $db->dbescape( $del_cache_time ) . " WHERE `catid`=" . $catid . "" );
                }
            }
            
            foreach ( $id_block_content as $bid_i )
            {
                $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_block` (`bid`, `id`, `weight`) VALUES ('" . $bid_i . "', '" . $rowcontent['id'] . "', '0')" );
            }
            $id_block_content[] = 0;
            $db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` WHERE `id` = " . $rowcontent['id'] . " AND `bid` NOT IN (" . implode( ",", $id_block_content ) . ")" );
            $id_block_content = array_keys( $array_block_cat_module );
            foreach ( $id_block_content as $bid_i )
            {
                nv_news_fix_block( $bid_i, false );
            }
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
            die();
        }
    }

}
elseif ( $rowcontent['id'] > 0 )
{
    $rowcontent = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `id`=" . $rowcontent['id'] . "" ) );
    $page_title = $lang_module['content_edit'];
    $rowcontent['sourcetext'] = "";
    $rowcontent['topictext'] = "";
    
    $id_block_content = array();
    $sql = "SELECT bid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block` where `id`='" . $rowcontent['id'] . "' ";
    $result = $db->sql_query( $sql );
    while ( list( $bid_i ) = $db->sql_fetchrow( $result ) )
    {
        $id_block_content[] = $bid_i;
    }
}

if ( ! empty( $rowcontent['bodytext'] ) ) $rowcontent['bodytext'] = nv_htmlspecialchars( $rowcontent['bodytext'] );

if ( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $rowcontent['homeimgfile'] ) )
{
    $rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $rowcontent['homeimgfile'];
}

$array_catid_in_row = explode( ",", $rowcontent['listcatid'] );

$sql = "SELECT topicid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$array_topic_module = array();
$array_topic_module[0] = $lang_module['topic_sl'];
while ( list( $topicid_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
    $array_topic_module[$topicid_i] = $title_i;
}

$sql = "SELECT sourceid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$array_source_module = array();
$array_source_module[0] = $lang_module['sources_sl'];
while ( list( $sourceid_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
    $array_source_module[$sourceid_i] = $title_i;
}

$tdate = date( "H|i", $rowcontent['publtime'] );
$publ_date = date( "d.m.Y", $rowcontent['publtime'] );
list( $phour, $pmin ) = explode( "|", $tdate );
if ( $rowcontent['exptime'] == 0 )
{
    $emin = $ehour = 0;
    $exp_date = "";
}
else
{
    $exp_date = date( "d.m.Y", $rowcontent['exptime'] );
    $tdate = date( "H|i", $rowcontent['exptime'] );
    list( $ehour, $emin ) = explode( "|", $tdate );
}

$contents = "";
if ( $error != "" )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}

$my_head = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/jquery/jquery.autocomplete.css\" />\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.autocomplete.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $rowcontent['id'] . "\" enctype=\"multipart/form-data\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" value=\"1\" name=\"save\">\n";
$contents .= "<input type=\"hidden\" value=\"" . $rowcontent['id'] . "\" name=\"id\">\n";
$contents .= "<table summary=\"\" class=\"tab2\">\n";
$contents .= "<tr>";
$contents .= "<td valign=\"top\">";
$contents .= "     <div class=\"news\"><label><strong>" . $lang_module['name'] . "</strong></label>\n";
$contents .= "     		<input type=\"text\" maxlength=\"255\" value=\"" . $rowcontent['title'] . "\" name=\"title\" />";
$contents .= "     </div>\n";

if ( $rowcontent['alias'] != "" )
{
    $contents .= "<div class=\"news\"><label><strong><strong>" . $lang_module['alias'] . ": </strong></label>\n";
    $contents .= "		<input style=\"width: 380px\" name=\"alias\" type=\"text\" value=\"" . $rowcontent['alias'] . "\" maxlength=\"255\" />";
    $contents .= "</div>\n";
}

$contents .= "<div class=\"news\"><label><strong>" . $lang_module['content_cat'] . "</strong></label>\n";
$contents .= "	<div style=\"height: 130px; width: 380px; overflow: auto; text-align:left;\">";
$contents .= "		<table>\n";

$sql = "SELECT catid, title, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result_cat = $db->sql_query( $sql );
if ( $db->sql_numrows( $result_cat ) == 0 )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
    die();
}
while ( list( $catid_i, $title_i, $lev_i ) = $db->sql_fetchrow( $result_cat ) )
{
    $xtitle_i = "";
    if ( $lev_i > 0 )
    {
        for ( $i = 1; $i <= $lev_i; $i ++ )
        {
            $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    $ch = "";
    if ( in_array( $catid_i, $array_catid_in_row ) )
    {
        $ch = " checked=\"checked\"";
    }
    $contents .= "<tr><td>" . $xtitle_i . "<input class=\"news_checkbox\" type=\"checkbox\" name=\"catids[]\" value=\"" . $catid_i . "\"" . $ch . ">" . $title_i . "</td></tr>";
}

$contents .= "		</table>\n";
$contents .= "	</div>\n";
$contents .= "</div>\n";
$contents .= "<div class=\"news\"><label><strong>" . $lang_module['content_topic'] . "</strong></label>\n";
$contents .= "<select name=\"topicid\" style=\"width: 370px;\">\n";
while ( list( $topicid_i, $title_i ) = each( $array_topic_module ) )
{
    $sl = "";
    if ( $topicid_i == $rowcontent['topicid'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $topicid_i . "\" " . $sl . ">" . $title_i . "</option>\n";
}

$contents .= "</select>";
$contents .= "<br><input type=\"text\" maxlength=\"255\" id=\"AjaxTopicText\" value=\"" . $rowcontent['topictext'] . "\" name=\"topictext\" style=\"width: 370px;\">";
$contents .= "</div>\n";
$contents .= "<div class=\"news\"><label><strong>" . $lang_module['content_homeimg'] . "</strong></label>\n";
$contents .= '<input style="width:260px" type="text" name="homeimg" id="homeimg" value="' . $rowcontent['homeimgfile'] . '"/> ';
$contents .= '<input style="width:100px" type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/>';

$contents .= "</div>\n";
$contents .= "<div class=\"news\"><label><strong>" . $lang_module['content_homeimgalt'] . "</strong></label>\n";
$contents .= "<input type=\"text\" maxlength=\"255\" value=\"" . $rowcontent['homeimgalt'] . "\" name=\"homeimgalt\" /></div>\n";
$contents .= "<div style=\"clear:both;\"></div>\n";
$contents .= "<div class=\"news\"><label><strong>" . $lang_module['imgposition'] . "</strong></label>\n";
$contents .= "	<select name=\"imgposition\">\n";
while ( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
{
    $sl = "";
    if ( $id_imgposition == $rowcontent['imgposition'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $id_imgposition . "\" " . $sl . ">" . $title_imgposition . "</option>\n";
}

$contents .= "</select></div><br>\n";

$contents .= "<div style=\"margin-bottom: 1em;\"><label><strong>" . $lang_module['content_hometext'] . "</strong> " . $lang_module['content_notehome'] . "</label><br>\n";
$contents .= "<textarea class=\"textareas\" rows=\"6\" cols=\"20\" name=\"hometext\" style=\"width: 530px;\">" . $rowcontent['hometext'] . "</textarea></div>\n";
$contents .= "</td>";
$contents .= "<td style=\"width:20px;\" >";
$contents .= "</td>";
$contents .= "<td valign=\"top\">";
// BEGIN
$contents .= "<ol class=\"message_list\">\n";
if ( count( $array_block_cat_module ) > 0 )
{
    $contents .= "	<li>\n";
    $contents .= "		<p class=\"message_head\"><cite>" . $lang_module['content_block'] . ":</cite> <span class=\"timestamp\"></span></p>\n";
    $contents .= "			<div class=\"message_body\">\n";
    $contents .= "				<div style=\"width: 260px; overflow: auto; text-align:left;\">";
    $contents .= "					<table>\n";
    foreach ( $array_block_cat_module as $bid_i => $bid_title )
    {
        $ch = in_array( $bid_i, $id_block_content ) ? " checked=\"checked\"" : "";
        $contents .= "					<tr><td><input class=\"news_checkbox\" type=\"checkbox\" name=\"bids[]\" value=\"" . $bid_i . "\"" . $ch . ">" . $bid_title . "</td></tr>";
    }
    $contents .= "					</table>\n";
    $contents .= "				</div>\n";
    $contents .= "			</div>\n";
    $contents .= "	</li>\n";
}
$contents .= "	<li>\n";
$contents .= "		<p class=\"message_head\"><cite>" . $lang_module['content_keywords'] . ":</cite> <span class=\"timestamp\"></span></p>\n";
$contents .= "			<div class=\"message_body\">\n";
$contents .= "				<p>" . $lang_module['content_keywords_note'] . " <a onclick=\"create_keywords();\" href=\"javascript:void(0);\">" . $lang_module['content_clickhere'] . "</a></p>\n";
$contents .= "				<textarea rows=\"3\" cols=\"20\" id=\"keywords\" name=\"keywords\" style=\"width: 250px;\">" . $rowcontent['keywords'] . "</textarea>\n";
$contents .= "			</div>\n";
$contents .= "	</li>\n";

$contents .= "<li>\n";
$contents .= "<p class=\"message_head\"><cite>" . $lang_module['content_publ_date'] . "</cite> <span class=\"timestamp\">" . $lang_module['content_notetime'] . "</span></p>\n";
$contents .= "<div class=\"message_body\"><center>\n";
$contents .= "<input name=\"publ_date\" id=\"publ_date\" value=\"" . $publ_date . "\" style=\"width: 90px;\" maxlength=\"10\" readonly=\"readonly\" type=\"text\">\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'publ_date', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
$contents .= "<select name=\"phour\">\n";
for ( $i = 0; $i < 24; $i ++ )
{
    $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$contents .= "</select>:<select name=\"pmin\">\n";
for ( $i = 0; $i < 60; $i ++ )
{
    $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</center></div>\n";
$contents .= "</li>\n";

$contents .= "<li>\n";
$contents .= "<p class=\"message_head\"><cite>" . $lang_module['content_exp_date'] . ":</cite> <span class=\"timestamp\">" . $lang_module['content_notetime'] . "</span></p>\n";
$contents .= "<div class=\"message_body\"><center> \n";
$contents .= "<input name=\"exp_date\" id=\"exp_date\" value=\"" . $exp_date . "\" style=\"width: 90px;\" maxlength=\"10\" type=\"text\">\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_date', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
$contents .= "<select name=\"ehour\">\n";
for ( $i = 0; $i < 24; $i ++ )
{
    $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$contents .= "</select>:<select name=\"emin\">\n";
for ( $i = 0; $i < 60; $i ++ )
{
    $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</center>";
$contents .= "<div style=\"margin-top: 10px;\"><input type=\"checkbox\" value=\"1\" name=\"archive\" " . ( ( $rowcontent['archive'] ) ? "  checked=\"checked\"" : "" ) . "> <label>" . $lang_module['content_archive'] . "</label></div>\n";
$contents .= "</div>\n";
$contents .= "</li>\n";

$contents .= "<li>\n";
$contents .= "<p class=\"message_head\"><cite>" . $lang_module['content_extra'] . ":</cite></p>\n";
$contents .= "<div class=\"message_body\">\n";

$contents .= "<div style=\"margin-bottom: 2px;\"><input type=\"checkbox\" value=\"1\" name=\"inhome\" " . ( ( $rowcontent['inhome'] ) ? "  checked=\"checked\"" : "" ) . "><label>" . $lang_module['content_inhome'] . "</label></div>\n";
$contents .= "<div style=\"margin-bottom: 2px;\"><label>" . $lang_module['content_allowed_comm'] . "</label> \n";
$contents .= "<select name=\"allowed_comm\">\n";
while ( list( $comm_i, $title_i ) = each( $array_allowed_comm ) )
{
    $sl = "";
    if ( $comm_i == $rowcontent['allowed_comm'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $comm_i . "\" " . $sl . ">" . $title_i . "</option>\n";

}
$contents .= "</select></div>\n";
$contents .= "<div style=\"margin-bottom: 2px;\"><input type=\"checkbox\" value=\"1\" name=\"allowed_rating\" " . ( ( $rowcontent['allowed_rating'] ) ? "  checked=\"checked\"" : "" ) . "><label>" . $lang_module['content_allowed_rating'] . "</label></div>\n";
$contents .= "<div style=\"margin-bottom: 2px;\"><input type=\"checkbox\" value=\"1\" name=\"allowed_send\" " . ( ( $rowcontent['allowed_send'] ) ? "  checked=\"checked\"" : "" ) . "><label>" . $lang_module['content_allowed_send'] . "</label></div>\n";
$contents .= "<div style=\"margin-bottom: 2px;\"><input type=\"checkbox\" value=\"1\" name=\"allowed_print\" " . ( ( $rowcontent['allowed_print'] ) ? "  checked=\"checked\"" : "" ) . "><label>" . $lang_module['content_allowed_print'] . "</label></div>\n";
$contents .= "<div style=\"margin-bottom: 2px;\"><input type=\"checkbox\" value=\"1\" name=\"allowed_save\" " . ( ( $rowcontent['allowed_save'] ) ? "  checked=\"checked\"" : "" ) . "><label>" . $lang_module['content_allowed_save'] . "</label></div>\n";
$contents .= "</div>\n";
$contents .= "</ol>\n";
$contents .= "<p class=\"collapse_buttons\"><a href=\"#\" class=\"collpase_all_message\">" . $lang_module['content_allcollapse'] . "</a><a href=\"#\" class=\"show_all_message\">" . $lang_module['content_allshow'] . "</a></p>\n";
//end
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</table>";
$contents .= "<div style=\"margin-bottom: 1em;\"><label><strong>" . $lang_module['content_bodytext'] . "</strong>" . $lang_module['content_bodytext_note'] . "</label><br>\n";
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( 'bodytext', '810px', '300px', $rowcontent['bodytext'] );
}
else
{
    $contents .= "<textarea style=\"width: 810px\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
}
$contents .= "</div>\n";
$contents .= "<div style=\"margin-bottom: 1em;\"><label><strong>" . $lang_module['content_author'] . "</strong></label><br>\n";
$contents .= "<input type=\"text\" maxlength=\"255\" value=\"" . $rowcontent['author'] . "\" name=\"author\" style=\"width: 530px;\"></div>\n";
$contents .= "<div style=\"margin-bottom: 1em;\"><label><strong>" . $lang_module['content_sourceid'] . "</strong></label><br>\n";
$contents .= "<select name=\"sourceid\" style=\"width: 530px;\">\n";
while ( list( $sourceid_i, $title_i ) = each( $array_source_module ) )
{
    $sl = "";
    if ( $sourceid_i == $rowcontent['sourceid'] )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $sourceid_i . "\" " . $sl . ">" . $title_i . "</option>\n";
}
$contents .= "</select><br>\n";
$contents .= "<input type=\"text\" maxlength=\"255\" id=\"AjaxSourceText\" value=\"" . $rowcontent['sourcetext'] . "\" name=\"sourcetext\" style=\"width: 530px;\"></div>\n";
$contents .= "<div style=\"margin-bottom: 1em;\"><input type=\"checkbox\" value=\"1\" name=\"copyright\" " . ( ( $rowcontent['copyright'] ) ? "  checked=\"checked\"" : "" ) . "> <label>" . $lang_module['content_copyright'] . "</label></div>\n";

$contents .= "<center>";
if ( $rowcontent['status'] == 1 )
{
    $contents .= "<input name=\"statussave\" type=\"submit\" value=\"" . $lang_module['save'] . "\" />";
}
else
{
    $contents .= "<input name=\"status0\" type=\"submit\" value=\"" . $lang_module['save_temp'] . "\" />";
    $contents .= "<input name=\"status1\" type=\"submit\" value=\"" . $lang_module['publtime'] . "\" />";
}
$contents .= "</center>\n";
$contents .= "</form>\n";

$contents .= "<script type=\"text/javascript\">\n";
$contents .= '$("input[name=selectimg]").click(function(){
						var area = "homeimg";
						var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '";						
						var currentpath= "' . NV_UPLOADS_DIR . '/' . $module_name . '/' . date( "Y_m" ) . '";						
						var type= "image";
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type+"&currentpath="+currentpath, "NVImg", "850", "400","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});';
$contents .= "$(document).ready(function() {\n";
$contents .= "	$(\"#AjaxSourceText\").autocomplete(\n";
$contents .= "		\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=sourceajax\",\n";
$contents .= "		{\n";
$contents .= "			delay:10,\n";
$contents .= "			minChars:2,\n";
$contents .= "			matchSubset:1,\n";
$contents .= "			matchContains:1,\n";
$contents .= "			cacheLength:10,\n";
$contents .= "			onItemSelect:selectItem,\n";
$contents .= "			onFindValue:findValue,\n";
$contents .= "			formatItem:formatItem,\n";
$contents .= "			autoFill:true\n";
$contents .= "		}\n";
$contents .= "	);\n";
$contents .= "\n";

$contents .= "	$(\"#AjaxTopicText\").autocomplete(\n";
$contents .= "		\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicajax\",\n";
$contents .= "		{\n";
$contents .= "			delay:10,\n";
$contents .= "			minChars:2,\n";
$contents .= "			matchSubset:1,\n";
$contents .= "			matchContains:1,\n";
$contents .= "			cacheLength:10,\n";
$contents .= "			onItemSelect:selectItem,\n";
$contents .= "			onFindValue:findValue,\n";
$contents .= "			autoFill:true\n";
$contents .= "		}\n";
$contents .= "	);\n";
$contents .= "\n";

$contents .= "});\n";
$contents .= "</script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 