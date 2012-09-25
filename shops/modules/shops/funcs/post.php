<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */

if ( ! defined( 'NV_IS_MOD_SHOPS' ) ) die( 'Stop!!!' );
/////////////////////////////////////////////////////////////
if ( ! defined( 'NV_IS_USER' ) )
{
    $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login";
    redict_link( $lang_module['product_login_fail'], $lang_module['redirect_to_back_login'], $nv_redirect );
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
$com_id = 0;
if ( defined( 'NV_IS_USER' ) and isset( $site_mods['company'] ) )
{
    $com_id = isset( $user_info['array_company']['com_id'] ) ? intval( $user_info['array_company']['com_id'] ) : 0;
}
$data_content = array( 
    "id" => 0, "listcatid" => $catid, "topic_id" => "", "user_id" => $user_info['userid'], "shop_id" => 0, 'shopcat_id' => 0, "com_id" => 0, "cate_id" => 0, "source_id" => 0, "addtime" => NV_CURRENTTIME, "edittime" => NV_CURRENTTIME, "status" => $pro_config['post_auto_member'], "publtime" => NV_CURRENTTIME, "exptime" => 0, "archive" => 1, "product_number" => 0, "product_price" => 0, "money_unit" => "", "product_unit" => "", "homeimgfile" => "", "homeimgthumb" => "", "homeimgalt" => "", "imgposition" => 1, "copyright" => 0, "inhome" => 1, "allowed_comm" => "", "allowed_rating" => 1, "ratingdetail" => "0", "allowed_send" => 1, "allowed_print" => 1, "allowed_save" => 1, "hitstotal" => 0, "hitscm" => 0, "hitslm" => 0, "com_id" => $com_id, "title" => "", "alias" => "", "hometext" => "", "bodytext" => "", "note" => "", "keywords" => "", "address" => "", "pstatus" => "", "payment" => "", "move" => "" 
);
$data_content['sourcetext'] = "";
$data_content['topictext'] = "";
$page_title = $lang_module['content_add'];

$id = isset( $array_op[1] ) ? $array_op[1] : 0;
if ( $id == 0 ) $lang_submit = $lang_module['product_post_title'];
else $lang_submit = $lang_module['product_edit_title'];

$error = "";
$table_name = $db_config['prefix'] . "_" . $module_data . "_rows";
if ( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $field_lang = nv_file_table( $table_name );
    $data_content['title'] = filter_text_input( 'title', 'post', '' );
    $bodytext = $nv_Request->get_string( 'bodytext', 'post', '' );
    $data_content['bodytext'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $bodytext, '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $bodytext ) ), '<br />' );
    $data_content['hometext'] = filter_text_input( 'hometext', 'post', '' );
    $data_content['product_number'] = $nv_Request->get_int( 'product_number', 'post', 0 );
    $data_content['product_price'] = $nv_Request->get_int( 'product_price', 'post', 0 );
    $data_content['money_unit'] = filter_text_input( 'money_unit', 'post', 'VND' );
    $data_content['product_unit'] = $nv_Request->get_int( 'product_unit', 'post', 0 );
    $data_content['address'] = filter_text_input( 'address', 'post', '', 1 );
    $data_content['address'] = filter_text_input( 'address', 'post', '', 1 );
    $data_content['listcatid'] = $nv_Request->get_int( 'catalogs', 'post', 0 );
    $data_content['cate_id'] = $nv_Request->get_int( 'cateshop', 'post', 0 );
    $data_content['keywords'] = filter_text_input( 'keywords', 'post', '' );
    $data_content['pstatus'] = filter_text_input( 'pstatus', 'post', '' );
    $data_content['payment'] = filter_text_input( 'payment', 'post', '' );
    $data_content['move'] = filter_text_input( 'move', 'post', '' );
    $data_content['com_id'] = $com_id;
    $alias = filter_text_input( 'alias', 'post', '' );
    $data_content['alias'] = ( $alias == "" ) ? change_alias( $data_content['title'] ) : change_alias( $alias );
    
    //$data_content ['shop_id'] = $nv_Request->get_int ( 'shopid', 'post', 0 );
    $data_content['note'] = $data_content['pstatus'] . "|" . $data_content['payment'] . "|" . $data_content['move'];
    $exp_date = filter_text_input( 'exp_date', 'post', '' );
    
    if ( $data_content['title'] == "" ) $error = $lang_module['err_no_title'] . ".";
    elseif ( $data_content['listcatid'] == 0 ) $error = $lang_module['err_no_catalogs'] . ".";
    elseif ( trim( strip_tags( $data_content['hometext'] ) ) == "" ) $error = $lang_module['err_no_hometext'] . ".";
    elseif ( $data_content['bodytext'] == "" ) $error = $lang_module['err_no_bodytext'] . ".";
    elseif ( $data_content['product_price'] <= 0 ) $error = $lang_module['err_no_product_price'] . ".";
    elseif ( $data_content['product_number'] <= 0 ) $error = $lang_module['err_no_product_number'] . ".";
    
    // Xu ly anh minh ha
    $data_content['homeimgfile'] = '';
    if ( $error == "" )
    {
        if ( is_uploaded_file( $_FILES['homeimg']["tmp_name"] ) )
        {
            $contents_type = array();
            $contents_type['upload_blocked'] = "";
            $contents_type['file_allowed_ext'] = array();
            $contents_type['file_allowed_ext'][] = "images";
            nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, "u_" . $user_info['username'], true );
            require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
            $upload = new upload( $contents_type['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
            $upload_info = $upload->save_file( $_FILES['homeimg'], NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . "u_" . $user_info['username'], false );
            if ( ! empty( $upload_info['error'] ) )
            {
                $error = $lang_module['err_no_image'];
            }
            else
            {
                $data_content['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . "u_" . $user_info['username'] . '/' . $upload_info['basename'];
            }
        }
        
        $data_content['homeimgthumb'] = "";
        if ( file_exists( NV_DOCUMENT_ROOT . $data_content['homeimgfile'] ) )
        {
            $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" );
            $data_content['homeimgfile'] = substr( $data_content['homeimgfile'], $lu );
        }
        elseif ( ! nv_is_url( $data_content['homeimgfile'] ) )
        {
            $data_content['homeimgfile'] = "";
        }
        $check_thumb = false;
        if ( $data_content['id'] > 0 )
        {
            list( $homeimgfile, $homeimgthumb ) = $db->sql_fetchrow( $db->sql_query( "SELECT `homeimgfile`, `homeimgthumb` FROM `" . $table_name . "` WHERE `id`=" . $data_content['id'] . "" ) );
            if ( $data_content['homeimgfile'] != $homeimgfile )
            {
                $check_thumb = true;
                if ( $homeimgthumb != "" and $homeimgthumb != "|" )
                {
                    $data_content['homeimgthumb'] = "";
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
                $data_content['homeimgthumb'] = $homeimgthumb;
            }
        }
        elseif ( ! empty( $data_content['homeimgfile'] ) )
        {
            $check_thumb = true;
        }
        $homeimgfile = NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $data_content['homeimgfile'];
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
            $image->resizeXY( $pro_config['homewidth'], $pro_config['homeheight'] );
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
            $image->resizeXY( $pro_config['blockwidth'], $pro_config['blockheight'] );
            $image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/block', $block_basename );
            $image_info = $image->create_Image_info;
            $block_name = str_replace( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/', '', $image_info['src'] );
            $image->close();
            $data_content['homeimgthumb'] = $thumb_name . "|" . $block_name;
        }
        if ( empty( $exp_date ) )
        {
            $data_content['exptime'] = 0;
        }
        else
        {
            unset( $m );
            preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date, $m );
            $data_content['exptime'] = mktime( $m[2], $m[1], $m[3] );
        }
    }
    if ( $error == "" )
    {
        $id = isset( $array_op[1] ) ? $array_op[1] : 0;
        if ( $id == 0 )
        {
            $listfield = "";
            $listvalue = "";
            foreach ( $field_lang as $field_lang_i )
            {
                list( $flang, $fname ) = $field_lang_i;
                $listfield .= ", `" . $flang . "_" . $fname . "`";
                if ( $flang == NV_LANG_DATA )
                {
                    $listvalue .= ", " . $db->dbescape( $data_content[$fname] );
                }
                else
                {
                    $listvalue .= ", " . $db->dbescape( $data_content[$fname] );
                }
            }
            $data_content['publtime'] = ( $data_content['publtime'] > NV_CURRENTTIME ) ? $data_content['publtime'] : NV_CURRENTTIME;
            $query = "INSERT INTO `" . $table_name . "` (`id` ,`listcatid` ,`topic_id` ,`user_id`,`com_id`,`shopcat_id`, `source_id` ,`addtime` ,`edittime` ,`status` ,`publtime` ,`exptime` ,`archive` ,`product_number` ,`product_price` ,`money_unit` , `product_unit` ,`homeimgfile` ,`homeimgthumb` ,`homeimgalt`,`imgposition` ,`copyright` ,`inhome` ,`allowed_comm` ,`allowed_rating` ,`ratingdetail` ,`allowed_send` ,`allowed_print` ,`allowed_save` ,`hitstotal` ,`hitscm` ,`hitslm` " . $listfield . ") 
                VALUES ( NULL , " . $db->dbescape_string( $data_content['listcatid'] ) . ", 
                " . intval( $data_content['topic_id'] ) . ", 
                " . intval( $data_content['user_id'] ) . ",
                " . intval( $data_content['com_id'] ) . ", 
                " . intval( $data_content['shopcat_id'] ) . ", 
                " . intval( $data_content['source_id'] ) . ", 
                " . intval( $data_content['addtime'] ) . ", 
                " . intval( $data_content['edittime'] ) . ", 
                " . intval( $data_content['status'] ) . ", 
                " . intval( $data_content['publtime'] ) . ",  
                " . intval( $data_content['exptime'] ) . ",  
                " . intval( $data_content['archive'] ) . ",  
                " . intval( $data_content['product_number'] ) . ",  
                " . intval( $data_content['product_price'] ) . ",  
                " . $db->dbescape_string( $data_content['money_unit'] ) . ",
                " . intval( $data_content['product_unit'] ) . ", 
                " . $db->dbescape_string( $data_content['homeimgfile'] ) . ", 
                " . $db->dbescape_string( $data_content['homeimgthumb'] ) . ", 
                " . $db->dbescape_string( $data_content['homeimgalt'] ) . ", 
                " . intval( $data_content['imgposition'] ) . ", 
                " . intval( $data_content['copyright'] ) . ", 
                " . intval( $data_content['inhome'] ) . ", 
                " . intval( $data_content['allowed_comm'] ) . ", 
                " . intval( $data_content['allowed_rating'] ) . ", 
                " . $db->dbescape_string( $data_content['ratingdetail'] ) . ",
                " . intval( $data_content['allowed_send'] ) . ", 
                " . intval( $data_content['allowed_print'] ) . ", 
                " . intval( $data_content['allowed_save'] ) . ", 
                " . intval( $data_content['hitstotal'] ) . ", 
                " . intval( $data_content['hitscm'] ) . ", 
                " . intval( $data_content['hitslm'] ) . "
				" . $listvalue . ")";
            $data_content['id'] = $db->sql_query_insert_id( $query );
            if ( $data_content['id'] > 0 )
            {
                $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=profile";
                $info = "<div class=\"frame\">";
                $info .= $lang_module['product_post_ok'] . "<br /><br />\n";
                $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
                $info .= "<a href=\"" . $nv_redirect . "\">" . $lang_module['redirect_to_back'] . "</a>";
                $info .= "</div>";
                $contents .= $info;
                $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
            else
            {
                $error = $lang_module['err_no_save'];
            }
            $db->sql_freeresult();
        }
        else
        {
            $data_content_old = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `id`=" . $id . "" ) );
            if ( $data_content_old['status'] == 1 )
            {
                $data_content['status'] = 1;
            }
            if ( $data_content['homeimgfile'] == "" )
            {
                $data_content['homeimgfile'] = $data_content_old['homeimgfile'];
                $data_content['homeimgthumb'] = $data_content_old['homeimgthumb'];
            }
            $query = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_rows` SET 
                           `listcatid`=" . $db->dbescape_string( $data_content['listcatid'] ) . ", 
                           `topic_id`=" . intval( $data_content['topic_id'] ) . ",                      
                           `source_id`=" . intval( $data_content['source_id'] ) . ", 
                           `status`=" . intval( $data_content['status'] ) . ", 
                           `publtime`=" . intval( $data_content['publtime'] ) . ", 
                           `exptime`=" . intval( $data_content['exptime'] ) . ", 
                           `edittime`=UNIX_TIMESTAMP( ) ,
                           `archive`=" . intval( $data_content['archive'] ) . ", 
                           `product_number` = " . intval( $data_content['product_number'] ) . ",  
                		   `product_price` = " . intval( $data_content['product_price'] ) . ",  
               			   `money_unit` = " . $db->dbescape_string( $data_content['money_unit'] ) . ", 
               			   `product_unit` = " . intval( $data_content['product_unit'] ) . ", 
                           `homeimgfile`=" . $db->dbescape_string( $data_content['homeimgfile'] ) . ",
                           `homeimgalt`=" . $db->dbescape_string( $data_content['homeimgalt'] ) . ",
                           `homeimgthumb`=" . $db->dbescape_string( $data_content['homeimgthumb'] ) . ",
                           `imgposition`=" . intval( $data_content['imgposition'] ) . ",
                           `copyright`=" . intval( $data_content['copyright'] ) . ", 
                           `inhome`=" . intval( $data_content['inhome'] ) . ", 
                           `allowed_comm`=" . intval( $data_content['allowed_comm'] ) . ", 
                           `allowed_rating`=" . intval( $data_content['allowed_rating'] ) . ", 
                           `allowed_send`=" . intval( $data_content['allowed_send'] ) . ", 
                           `allowed_print`=" . intval( $data_content['allowed_print'] ) . ", 
                           `allowed_save`=" . intval( $data_content['allowed_save'] ) . ", 
                           `" . NV_LANG_DATA . "_title`=" . $db->dbescape_string( $data_content['title'] ) . ", 
                           `" . NV_LANG_DATA . "_alias`=" . $db->dbescape_string( $data_content['alias'] ) . ", 
                           `" . NV_LANG_DATA . "_hometext`=" . $db->dbescape_string( $data_content['hometext'] ) . ",
                           `" . NV_LANG_DATA . "_bodytext`=" . $db->dbescape_string( $data_content['bodytext'] ) . ", 
                           `" . NV_LANG_DATA . "_address`=" . $db->dbescape_string( $data_content['address'] ) . ", 
                           `" . NV_LANG_DATA . "_note`=" . $db->dbescape_string( $data_content['note'] ) . ",
                           `" . NV_LANG_DATA . "_keywords`=" . $db->dbescape_string( $data_content['keywords'] ) . "
                        WHERE `id` =" . $id . "";
            $db->sql_query( $query );
            
            if ( $db->sql_affectedrows() > 0 )
            {
                $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=profile";
                $info = "<div class=\"frame\">";
                $info .= $lang_module['product_post_ok'] . "<br /><br />\n";
                $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
                $info .= "<a href=\"" . $nv_redirect . "\">" . $lang_module['redirect_to_back'] . "</a>";
                $info .= "</div>";
                $contents .= $info;
                $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
                include ( NV_ROOTDIR . "/includes/header.php" );
                echo nv_site_theme( $contents );
                include ( NV_ROOTDIR . "/includes/footer.php" );
                exit();
            }
            else
            {
                $error = $lang_module['errorsave'];
            }
            $db->sql_freeresult();
        }
    }

}
else
{
    $id = isset( $array_op[1] ) ? $array_op[1] : 0;
    if ( $id > 0 )
    {
        $rowdata = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . $db_config['prefix'] . "_" . $module_data . "_rows` where `id`=" . $id . "" ) );
        $data_content = array( 
            "id" => $rowdata['id'], "listcatid" => $rowdata['listcatid'], "topic_id" => $rowdata['topic_id'], "user_id" => $rowdata['user_id'], "source_id" => $rowdata['source_id'], "addtime" => $rowdata['addtime'], "edittime" => $rowdata['edittime'], "status" => $rowdata['status'], "publtime" => $rowdata['publtime'], "exptime" => $rowdata['exptime'], "archive" => $rowdata['archive'], "product_number" => $rowdata['product_number'], "product_price" => $rowdata['product_price'], "money_unit" => $rowdata['money_unit'], "product_unit" => $rowdata['product_unit'], "homeimgfile" => $rowdata['homeimgfile'], "homeimgthumb" => $rowdata['homeimgthumb'], "homeimgalt" => $rowdata['homeimgalt'], "imgposition" => $rowdata['imgposition'], "copyright" => $rowdata['copyright'], "inhome" => $rowdata['inhome'], "allowed_comm" => $rowdata['allowed_comm'], "allowed_rating" => $rowdata['allowed_rating'], "ratingdetail" => $rowdata['ratingdetail'], "allowed_send" => $rowdata['allowed_send'], "allowed_print" => $rowdata['allowed_print'], "allowed_save" => $rowdata['allowed_save'], "hitstotal" => $rowdata['hitstotal'], "hitscm" => $rowdata['hitscm'], "hitslm" => $rowdata['hitslm'], "title" => $rowdata[NV_LANG_DATA . '_title'], "alias" => $rowdata[NV_LANG_DATA . '_alias'], "hometext" => $rowdata[NV_LANG_DATA . '_hometext'], "bodytext" => $rowdata[NV_LANG_DATA . '_bodytext'], "note" => $rowdata[NV_LANG_DATA . '_note'], "keywords" => $rowdata[NV_LANG_DATA . '_keywords'], "address" => $rowdata[NV_LANG_DATA . '_address'] 
        );
        $temp = explode( "|", $data_content['note'] );
        $data_content['pstatus'] = isset( $temp[0] ) ? $temp[0] : "";
        $data_content['payment'] = isset( $temp[1] ) ? $temp[1] : "";
        $data_content['move'] = isset( $temp[2] ) ? $temp[2] : "";
        if ( $data_content['user_id'] != $user_info['userid'] || empty( $data_content ) )
        {
            $nv_redirect = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login";
            redict_link( $lang_module['product_login_fail'], $lang_module['redirect_to_back_login'], $nv_redirect );
        }
    }
}

///////////////// catalogs //////////////////
$sql = "SELECT catid," . NV_LANG_DATA . "_title, lev,numsubcat FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result_cat = $db->sql_query( $sql );
$data_cata = array();
while ( list( $catid_i, $title_i, $lev_i, $numsubcat_i ) = $db->sql_fetchrow( $result_cat ) )
{
    $xtitle_i = "";
    if ( $lev_i > 0 )
    {
        for ( $i = 1; $i <= $lev_i; $i ++ )
        {
            $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    $select = ( $data_content['listcatid'] == $catid_i ) ? "selected=\"selected\"" : "";
    //$disable = ($numsubcat_i != 0) ? "disabled=\"disabled\"" : "";
    $disable = "";
    $data_cata[] = array( 
        'xtitle' => $xtitle_i, 'title' => $title_i, 'catid' => $catid_i, 'numsubcat' => $numsubcat_i, 'select' => $select, 'disabled' => $disable 
    );
}
/////// List shops ////////
$sql = "SELECT shopid," . NV_LANG_DATA . "_name FROM `" . $db_config['prefix'] . "_" . $module_data . "_shops` WHERE userid = " . $user_info['userid'] . " LIMIT 1";
$result_shop = $db->sql_query( $sql );
list( $shopid, $shopname ) = $db->sql_fetchrow( $result_shop );
/////// List category of shops ////////
$data_catshop = array();
$sql = "SELECT cateid," . NV_LANG_DATA . "_title FROM `" . $db_config['prefix'] . "_" . $module_data . "_cateshops` WHERE shopid = " . $shopid;
$result = $db->sql_query( $sql );
while ( list( $cateid_i, $title_i ) = $db->sql_fetchrow( $result ) )
{
    $select = ( $data_content['cate_id'] == $cateid_i ) ? "selected=\"selected\"" : "";
    $data_catshop[] = array( 
        'cateid' => $cateid_i, 'title' => $title_i, 'select' => $select 
    );
}

/////// List pro_unit ////////
$data_unit = array();
$sql = "SELECT id," . NV_LANG_DATA . "_title FROM `" . $db_config['prefix'] . "_" . $module_data . "_units`";
$result_unit = $db->sql_query( $sql );
while ( list( $unitid_i, $title_i ) = $db->sql_fetchrow( $result_unit ) )
{
    $select = ( $data_content['product_unit'] == $unitid_i ) ? "selected=\"selected\"" : "";
    $data_unit[] = array( 
        'unitid' => $unitid_i, 'title' => $title_i, 'select' => $select 
    );
}

$contents = call_user_func( "post_product", $data_content, $data_cata, $data_catshop, $data_unit, $shopid, $error, $lang_submit );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>