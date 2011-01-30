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

$username_alias = change_alias( $admin_info['username'] );
$array_structure_image = array();
$array_structure_image[''] = $module_name;
$array_structure_image['Y'] = $module_name . '/' . date( 'Y' );
$array_structure_image['Ym'] = $module_name . '/' . date( 'Y_m' );
$array_structure_image['Y_m'] = $module_name . '/' . date( 'Y/m' );
$array_structure_image['Ym_d'] = $module_name . '/' . date( 'Y_m/d' );
$array_structure_image['Y_m_d'] = $module_name . '/' . date( 'Y/m/d' );
$array_structure_image['username'] = $module_name . '/' . $username_alias;

$array_structure_image['username_Y'] = $module_name . '/' . $username_alias . '/' . date( 'Y' );
$array_structure_image['username_Ym'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m' );
$array_structure_image['username_Y_m'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m' );
$array_structure_image['username_Ym_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y_m/d' );
$array_structure_image['username_Y_m_d'] = $module_name . '/' . $username_alias . '/' . date( 'Y/m/d' );

$structure_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : "Ym";
$currentpath = isset( $array_structure_image[$structure_upload] ) ? $array_structure_image[$structure_upload] : '';

if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
{
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
}
else
{
    $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_name;
    $e = explode( "/", $currentpath );
    if ( ! empty( $e ) )
    {
        $cp = "";
        foreach ( $e as $p )
        {
            if ( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
            {
                $mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
                nv_loadUploadDirList( false );
                if ( $mk[0] > 0 )
                {
                    $upload_real_dir_page = $mk[2];
                }
            }
            elseif ( ! empty( $p ) )
            {
                $upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
            }
            $cp .= $p . '/';
        }
    }
    $upload_real_dir_page = str_replace( "\\", "/", $upload_real_dir_page );
}

$currentpath = str_replace( NV_ROOTDIR . "/", "", $upload_real_dir_page );
$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_name;
if ( ! defined( 'NV_IS_SPADMIN' ) and strpos( $structure_upload, 'username' ) !== false )
{
    $array_currentpath = explode( '/', $currentpath );
    if ( $array_currentpath[2] == $username_alias )
    {
        $uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_name . '/' . $username_alias;
    }
}

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
    "id" => "", "listcatid" => "" . $catid . "," . $parentid . "", "topicid" => "", "admin_id" => $admin_id, "author" => "", "sourceid" => 0, "addtime" => NV_CURRENTTIME, "edittime" => NV_CURRENTTIME, "status" => 0, "publtime" => NV_CURRENTTIME, "exptime" => 0, "archive" => 1, "title" => "", "alias" => "", "hometext" => "", "homeimgfile" => "", "homeimgalt" => "", "homeimgthumb" => "", "imgposition" => 1, "bodytext" => "", "copyright" => 0, "inhome" => 1, "allowed_comm" => $module_config[$module_name]['setcomm'], "allowed_rating" => 1, "allowed_send" => 1, "allowed_print" => 1, "allowed_save" => 1, "hitstotal" => 0, "hitscm" => 0, "total_rating" => 0, "click_rating" => 0, "keywords" => "" 
);

$rowcontent['sourcetext'] = "";
$rowcontent['topictext'] = "";
$page_title = $lang_module['content_add'];
$error = array();
$groups_list = nv_groups_list();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if ( $rowcontent['id'] > 0 )
{
    $check_permission = false;
    $rowcontent = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `id`=" . $rowcontent['id'] . "" ) );
    if ( ! empty( $rowcontent['id'] ) )
    {
        $arr_catid = explode( ",", $rowcontent['listcatid'] );
        if ( defined( 'NV_IS_ADMIN_MODULE' ) )
        {
            $check_permission = true;
        }
        else
        {
            $check_edit = 0;
            
            if ( $rowcontent['status'] == 0 )
            {
                $edit_status = 0;
            }
            elseif ( $rowcontent['publtime'] < NV_CURRENTTIME and ( $rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME ) )
            {
                $edit_status = 1;
            }
            elseif ( $rowcontent['publtime'] > NV_CURRENTTIME )
            {
                $edit_status = 2;
            }
            else
            {
                $edit_status = 3;
            }
            foreach ( $arr_catid as $catid_i )
            {
                if ( isset( $array_cat_admin[$admin_id][$catid_i] ) )
                {
                    if ( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
                    {
                        $check_edit ++;
                    }
                    else
                    {
                        if ( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
                        {
                            $check_edit ++;
                        }
                        elseif ( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ( $edit_status == 0 or $edit_status = 2 ) )
                        {
                            $check_edit ++;
                        }
                        elseif ( $status == 0 and $post_id == $admin_id )
                        {
                            $check_edit ++;
                        }
                    }
                }
            }
            if ( $check_edit == count( $arr_catid ) )
            {
                $check_permission = true;
            }
        }
    }
    
    if ( ! $check_permission )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
        die();
    }
    
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

$array_cat_add_content = $array_cat_pub_content = $array_cat_edit_content = array();
foreach ( $global_array_cat as $catid_i => $array_value )
{
    $check_add_content = $check_pub_content = $check_edit_content = false;
    if ( defined( 'NV_IS_ADMIN_MODULE' ) )
    {
        $check_add_content = $check_pub_content = $check_edit_content = true;
    }
    elseif ( isset( $array_cat_admin[$admin_id][$catid_i] ) )
    {
        if ( $array_cat_admin[$admin_id][$catid_i]['admin'] == 1 )
        {
            $check_add_content = $check_pub_content = $check_edit_content = true;
        }
        else
        {
            if ( $array_cat_admin[$admin_id][$catid_i]['add_content'] == 1 )
            {
                $check_add_content = true;
            }
            
            if ( $array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 )
            {
                $check_pub_content = true;
            }
            
            if ( $array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1 )
            {
                $check_edit_content = true;
            }
        }
    }
    if ( $check_add_content )
    {
        $array_cat_add_content[] = $catid_i;
    }
    
    if ( $check_pub_content )
    {
        $array_cat_pub_content[] = $catid_i;
    }
    
    if ( $check_edit_content )
    {
        $array_cat_edit_content[] = $catid_i;
    }
}

if ( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $catids = array_unique( $nv_Request->get_typed_array( 'catids', 'post', 'int', array() ) );
    $id_block_content = array_unique( $nv_Request->get_typed_array( 'bids', 'post', 'int', array() ) );
    
    $rowcontent['listcatid'] = implode( ",", $catids );
    
    $rowcontent['status'] = ( $nv_Request->isset_request( 'status1', 'post' ) ) ? 1 : 0;
    
    if ( $rowcontent['status'] and $rowcontent['publtime'] > NV_CURRENTTIME )
    {
        $array_cat_check_content = $array_cat_pub_content;
    }
    elseif ( $rowcontent['status'] )
    {
        $array_cat_check_content = $array_cat_edit_content;
    }
    else
    {
        $array_cat_check_content = $array_cat_add_content;
    }
    
    foreach ( $catids as $catid_i )
    {
        if ( ! in_array( $catid_i, $array_cat_check_content ) )
        {
            $error[] = sprintf( $lang_module['permissions_pub_error'], $global_array_cat[$catid_i]['title'] );
        }
    }
    
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
    
    if ( ! empty( $publ_date ) and ! preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date ) ) $publ_date = "";
    if ( ! empty( $exp_date ) and ! preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date ) ) $exp_date = "";
    if ( empty( $publ_date ) )
    {
        $rowcontent['publtime'] = NV_CURRENTTIME;
    }
    else
    {
        $phour = $nv_Request->get_int( 'phour', 'post', 0 );
        $pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
        unset( $m );
        preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $publ_date, $m );
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
        preg_match( "/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $exp_date, $m );
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
        $error[] = $lang_module['error_title'];
    }
    elseif ( empty( $rowcontent['listcatid'] ) )
    {
        $error[] = $lang_module['error_cat'];
    }
    elseif ( trim( strip_tags( $rowcontent['bodytext'] ) ) == "" )
    {
        $error[] = $lang_module['error_bodytext'];
    }
    
    if ( empty( $error ) )
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
                        if ( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . "/" . $module_name . "/" . $homeimgthumb_i ) )
                        {
                            nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . "/" . $module_name . "/" . $homeimgthumb_i );
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
            while ( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/thumb/' . $thumb_basename ) )
            {
                $thumb_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
                $i ++;
            }
            
            $image->resizeXY( $module_config[$module_name]['homewidth'], $module_config[$module_name]['homeheight'] );
            $image->save( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/thumb', $thumb_basename );
            $image_info = $image->create_Image_info;
            $thumb_name = str_replace( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/', '', $image_info['src'] );
            
            $block_basename = $basename;
            $i = 1;
            while ( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/block/' . $block_basename ) )
            {
                $block_basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
                $i ++;
            }
            $image->resizeXY( $module_config[$module_name]['blockwidth'], $module_config[$module_name]['blockheight'] );
            $image->save( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/block', $block_basename );
            $image_info = $image->create_Image_info;
            $block_name = str_replace( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/', '', $image_info['src'] );
            
            $image->close();
            $rowcontent['homeimgthumb'] = $thumb_name . "|" . $block_name;
        }
        
        if ( $rowcontent['id'] == 0 )
        {
            $rowcontent['publtime'] = ( $rowcontent['publtime'] > NV_CURRENTTIME ) ? $rowcontent['publtime'] : NV_CURRENTTIME;
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
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_add'], $rowcontent['title'], $admin_info['userid'] );
                foreach ( $catids as $catid )
                {
                    $db->sql_query( "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "` SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $rowcontent['id'] . "" );
                }
            }
            else
            {
                $error[] = $lang_module['errorsave'];
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
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_edit'], $rowcontent['title'], $admin_info['userid'] );
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
                $error[] = $lang_module['errorsave'];
            }
            $db->sql_freeresult();
        }
        nv_del_moduleCache( $module_name );
        if ( empty( $error ) )
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
            $url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
            $msg1 = $lang_module['content_saveok'];
            $msg2 = $lang_module['content_main'] . " " . $module_info['custom_title'];
            redriect( $msg1, $msg2, $url );
        }
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
$publ_date = date( "d/m/Y", $rowcontent['publtime'] );
list( $phour, $pmin ) = explode( "|", $tdate );
if ( $rowcontent['exptime'] == 0 )
{
    $emin = $ehour = 0;
    $exp_date = "";
}
else
{
    $exp_date = date( "d/m/Y", $rowcontent['exptime'] );
    $tdate = date( "H|i", $rowcontent['exptime'] );
    list( $ehour, $emin ) = explode( "|", $tdate );
}

if ( $rowcontent['status'] and $rowcontent['publtime'] > NV_CURRENTTIME )
{
    $array_cat_check_content = $array_cat_pub_content;
}
elseif ( $rowcontent['status'] )
{
    $array_cat_check_content = $array_cat_edit_content;
}
else
{
    $array_cat_check_content = $array_cat_add_content;
}

if ( empty( $array_cat_check_content ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
    die();
}

$contents = "";
$my_head = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/jquery/jquery.autocomplete.css\" />\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.autocomplete.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
/////////////////////////////////////////////////////////////////////////////////////////////////
$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'module_name', $module_name );
$temp = "";
foreach ( $global_array_cat as $catid_i => $array_value )
{
    if ( defined( 'NV_IS_ADMIN_MODULE' ) )
    {
        $check_show = 1;
    }
    else
    {
        $array_cat = GetCatidInParent( $catid_i );
        $check_show = array_intersect( $array_cat, $array_cat_check_content );
    }
    if ( ! empty( $check_show ) )
    {
        $lev_i = $array_value['lev'];
        $xtitle_i = "";
        if ( $lev_i > 0 )
        {
            for ( $i = 1; $i <= $lev_i; $i ++ )
            {
                $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            }
        }
        $ch = "";
        if ( ! in_array( $catid_i, $array_cat_check_content ) )
        {
            $ch .= " disabled=\"disabled\"";
        }
        if ( in_array( $catid_i, $array_catid_in_row ) )
        {
            $ch .= " checked=\"checked\"";
        }
        
        $temp .= "<li>" . $xtitle_i . "<input class=\"news_checkbox\" type=\"checkbox\" name=\"catids[]\" value=\"" . $catid_i . "\"" . $ch . " />" . $array_value['title'] . "</li>";
    }
}
$xtpl->assign( 'listcatid', $temp );

/// topic
while ( list( $topicid_i, $title_i ) = each( $array_topic_module ) )
{
    $sl = ( $topicid_i == $rowcontent['topicid'] ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'topicid', $topicid_i );
    $xtpl->assign( 'topic_title', $title_i );
    $xtpl->assign( 'sl', $sl );
    $xtpl->parse( 'main.rowstopic' );
}
// position images
while ( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
{
    $sl = ( $id_imgposition == $rowcontent['imgposition'] ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'id_imgposition', $id_imgposition );
    $xtpl->assign( 'title_imgposition', $title_imgposition );
    $xtpl->assign( 'posl', $sl );
    $xtpl->parse( 'main.looppos' );
}

///////////time update////////////
$xtpl->assign( 'publ_date', $publ_date );
$select = "";
for ( $i = 0; $i <= 23; $i ++ )
{
    $select .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'phour', $select );
$select = "";
for ( $i = 0; $i < 60; $i ++ )
{
    $select .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'pmin', $select );
/////////// time exp //////////////////////////////////////////
$xtpl->assign( 'publ_date', $publ_date );
$select = "";
for ( $i = 0; $i <= 23; $i ++ )
{
    $select .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'ehour', $select );
$select = "";
for ( $i = 0; $i < 60; $i ++ )
{
    $select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );
//////// allowed ////////////////
$select = "";
while ( list( $commid_i, $commid_title_i ) = each( $array_allowed_comm ) )
{
    $comm_sl = ( $commid_i == $rowcontent['allowed_comm'] ) ? " selected=\"selected\"" : "";
    $select .= "<option value=\"" . $commid_i . "\" " . $comm_sl . ">" . $commid_title_i . "</option>\n";
}
$xtpl->assign( 'allowed_comm', $select );

/////////// source //////////////////////////
$select = "";
while ( list( $sourceid_i, $source_title_i ) = each( $array_source_module ) )
{
    $source_sl = ( $sourceid_i == $rowcontent['sourceid'] ) ? " selected=\"selected\"" : "";
    $select .= "<option value=\"" . $sourceid_i . "\" " . $source_sl . ">" . $source_title_i . "</option>\n";
}
$xtpl->assign( 'sourceid', $select );
////////////////////////////////////////////////////////////////////////////////////
if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
    $edits = nv_aleditor( 'bodytext', '100%', '300px', $rowcontent['bodytext'], $uploads_dir_user, $currentpath );
}
else
{
    $edits = "<textarea style=\"width: 100%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $rowcontent['bodytext'] . "</textarea>";
}
///////////////////////////////////////////////////////////////////////////////////////////
$shtm = "";
if ( count( $array_block_cat_module ) > 0 )
{
    foreach ( $array_block_cat_module as $bid_i => $bid_title )
    {
        $ch = in_array( $bid_i, $id_block_content ) ? " checked=\"checked\"" : "";
        $shtm .= "<tr><td><input class=\"news_checkbox\" type=\"checkbox\" name=\"bids[]\" value=\"" . $bid_i . "\"" . $ch . " />" . $bid_title . "</td></tr>\n";
    }
    $xtpl->assign( 'row_block', $shtm );
    $xtpl->parse( 'main.block_cat' );
}
//////////////////////////////////////////////////////////////////
$archive_checked = ( $rowcontent['archive'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'archive_checked', $archive_checked );
$inhome_checked = ( $rowcontent['inhome'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'inhome_checked', $inhome_checked );
$allowed_rating_checked = ( $rowcontent['allowed_rating'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_rating_checked', $allowed_rating_checked );
$allowed_send_checked = ( $rowcontent['allowed_send'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_send_checked', $allowed_send_checked );
$allowed_print_checked = ( $rowcontent['allowed_print'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_print_checked', $allowed_print_checked );
$allowed_save_checked = ( $rowcontent['allowed_save'] ) ? "  checked=\"checked\"" : "";
$xtpl->assign( 'allowed_save_checked', $allowed_save_checked );
////////////////////////////////////////////////////////////////////////////////
$xtpl->assign( 'edit_bodytext', $edits );
///////////////////////////////////////////////////////////////////////////////////
if ( $error != "" )
{
    $xtpl->assign( 'error', implode( "<br />", $error ) );
    $xtpl->parse( 'main.error' );
}
if ( $rowcontent['status'] == 1 and $rowcontent['id'] > 0 )
{
    $xtpl->parse( 'main.status' );
}
else
{
    $xtpl->parse( 'main.status0' );
}

if ( empty( $rowcontent['alias'] ) )
{
    $xtpl->parse( 'main.getalias' );
}
$xtpl->assign( 'UPLOADS_DIR_USER', $uploads_dir_user );
$xtpl->assign( 'UPLOAD_CURRENT', $currentpath );

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?> 