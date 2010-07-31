<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );
global $configdownload;
$ispost = 0;
$permission = false;
if ( $configdownload['who_view6'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
{
    $permission = true;
}
if ( $configdownload['who_view6'] == 0 )
{
    $permission = true;
}
if ( $configdownload['who_view6'] == 1 && defined( 'NV_IS_USER' ) )
{
    $permission = true;
}
if ( $configdownload['who_view6'] == 2 && defined( 'NV_IS_ADMIN' ) )
{
    $permission = true;
}
if ( ! $permission )
{
    Header( "Location: " . NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    exit();
}
$con_data = array();
$check = $nv_Request->isset_request( 'title', 'post' );

$con_data['title'] = filter_text_input( 'title', 'post', '', 1 );
$con_data['catparent'] = $nv_Request->get_int( 'catparent', 'post' );
$con_data['title'] = filter_text_input( 'title', 'post', '', 1 );
$con_data['description'] = filter_text_input( 'description', 'post', '', 1 );
$con_data['author'] = filter_text_input( 'author', 'post', '', 1 );
$con_data['authoremail'] = filter_text_input( 'authoremail', 'post', '', 1 );
$con_data['homepage'] = filter_text_input( 'homepage', 'post', '', 1 );
$con_data['linkdirect'] = filter_text_input( 'linkdirect', 'post', '', 1 );
$con_data['version'] = filter_text_input( 'version', 'post', '', 1 );
$con_data['taglist'] = filter_text_input( 'taglist', 'post', '', 1 );
$con_data['copyright'] = filter_text_input( 'copyright', 'post', '', 1 );

$rows_cat = array();
$result = $db->sql_query( "SELECT cid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE active=1" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $rows_cat[] = $row;
}
$content['filetype'] = $configdownload['filetype'];
$content['captcha'] = NV_BASE_SITEURL;
$content['checkcaptcha'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_data . '&' . NV_OP_VARIABLE . '=checkcaptcha';
$content['cat'] = $rows_cat;
$error = "";
$fileimage = "";
$fileupload = "";
$filesize = 0;
/////////////////////////////////////////////////////////////////////////
$captcha = $nv_Request->get_string( 'captcha', 'post' );

if ( $check )
{
    if ( strlen( $con_data['title'] ) <= 5 )
    {
        $error = $lang_module['upload_error_title'];
    }
    if ( strlen( $con_data['description'] ) <= 15 )
    {
        $error = $lang_module['upload_error_des'];
    }
    if ( nv_check_valid_email( $con_data['authoremail'] ) != "" && $con_data['authoremail'] != "" )
    {
        $error = $lang_module['upload_error_email'];
    }
    if ( ! nv_capcha_txt( $captcha ) )
    {
        $error = $lang_module['comment_error_captcha'];
    }
    if ( $error == "" )
    {
        require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
        
        $allowarray = explode( ',', $configdownload['filetype'] );
        $allowarray = array_map( 'trim', $allowarray );
        if ( is_uploaded_file( $_FILES['fileupload']['tmp_name'] ) && $_FILES['fileupload']['size'] < $configdownload['maxfilesize'] )
        {
            $extension = trim( end( explode( '.', $_FILES['fileupload']['name'] ) ) );
            if ( in_array( $extension, $allowarray ) )
            {
                $arr_allow_files_type = array( 
                    "images", "documents", "archives" 
                );
                $upload = new upload( $arr_allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                $upload_info = $upload->save_file( $_FILES['fileupload'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $configdownload['filetempdir'], false );
                if ( ! empty( $upload_info['error'] ) )
                {
                    $error = $upload_info['error'];
                }
                else
                {
                    $fileupload = $upload_info['basename'];
                    $filesize = nv_convertfromBytes( $upload_info['size'] );
                    $message = $lang_module['upload_congratulations'];
                }
            }
            else
            {
                $error = $lang_module['upload_error_filetypesize'];
            }
        }
        elseif ( $_FILES['fileupload']['size'] > $configdownload['maxfilesize'] )
        {
            $error = $lang_module['upload_error_maxsize'];
        }
        else
        {
            $filesize = filter_text_input( 'filesize', 'post', '', 1 );
        }
        if ( is_uploaded_file( $_FILES['fileimage']['tmp_name'] ) )
        {
            $extension = end( explode( '.', $_FILES['fileimage']['name'] ) );
            if ( in_array( $extension, array( 
                'gif', 'jpg', 'png' 
            ) ) )
            {
                $arr_allow_files_type = array( 
                    "images" 
                );
                $upload = new upload( $arr_allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                $upload_info = $upload->save_file( $_FILES['fileimage'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $configdownload['filetempdir'], false );
                if ( ! empty( $upload_info['error'] ) )
                {
                    $error = $upload_info['error'];
                }
                else
                {
                    $fileimage = $upload_info['basename'];
                    $filesize = nv_convertfromBytes( $upload_info['size'] );
                    $message = $lang_module['upload_congratulations'];
                }
            }
            else
            {
                $error = $lang_module['upload_error_fileimagetype'];
            }
        }
    }
    if ( $error == "" )
    {
        $userid = isset( $user_info['userid'] ) ? $user_info['userid'] : 0;
        $con_data['description'] = nv_nl2br( $con_data['description'] );
        $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_tmp`(`id`, `userid`, `title`, `catid`, `description`,`introtext`, `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`,`copyright`)  
				VALUES (
					NULL,
					" . $userid . ",					
					" . $db->dbescape( $con_data['title'] ) . ",
					" . intval( $con_data['catparent'] ) . ",
					" . $db->dbescape( $con_data['description'] ) . ",
					'',
					UNIX_TIMESTAMP(),
					" . $db->dbescape( $con_data['author'] ) . ",
					" . $db->dbescape( $con_data['authoremail'] ) . ",
					" . $db->dbescape( $con_data['homepage'] ) . ",
					" . $db->dbescape( $fileupload ) . ",
					" . $db->dbescape( $con_data['version'] ) . ",
					" . $db->dbescape( $con_data['linkdirect'] ) . ",
					" . $db->dbescape( $filesize ) . ",
					" . $db->dbescape( $fileimage ) . ",
					" . $db->dbescape( $con_data['taglist'] ) . ",
					0,
					" . $db->dbescape( $con_data['copyright'] ) . " 
				)";
        $db->sql_query( $sql );
        $ispost = 1;
    }
}
$page_title = $lang_module['upload_pagetitle'];
$contents = nv_uploads_form( $content, $con_data, $error, $ispost );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>