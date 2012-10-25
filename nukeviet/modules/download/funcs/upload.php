<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$page_title = $lang_module['upload'];

$download_config = nv_mod_down_config();

if ( ! $download_config['is_addfile_allow'] )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    exit();
}

$list_cats = nv_list_cats( false, false );

if ( empty( $list_cats ) )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    exit();
}

$is_error = false;
$error = "";

if ( $nv_Request->isset_request( 'addfile', 'post' ) )
{
    @require_once ( NV_ROOTDIR . "/includes/class/upload.class.php" );
    
    $addfile = $nv_Request->get_string( 'addfile', 'post', '' );
    
    if ( empty( $addfile ) or $addfile != md5( $client_info['session_id'] ) )
    {
        Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
        exit();
    }
    
    $array = array();
    
    $array['catid'] = $nv_Request->get_int( 'upload_catid', 'post', 0 );
    $array['title'] = filter_text_input( 'upload_title', 'post', '', 1, 255 );
    $array['description'] = filter_text_textarea( 'upload_description', '', NV_ALLOWED_HTML_TAGS );
    $array['introtext'] = filter_text_textarea( 'upload_introtext', '', NV_ALLOWED_HTML_TAGS );
    $array['author_name'] = filter_text_input( 'upload_author_name', 'post', '', 1, 100 );
    $array['author_email'] = filter_text_input( 'upload_author_email', 'post', '', 60 );
    $array['author_url'] = filter_text_input( 'upload_author_url', 'post', '', 0, 255 );
    $array['linkdirect'] = filter_text_textarea( 'upload_linkdirect', '' );
    $array['version'] = filter_text_input( 'upload_version', 'post', '', 1, 20 );
    $array['filesize'] = $nv_Request->get_int( 'upload_filesize', 'post', 0 );
    $array['copyright'] = filter_text_input( 'upload_copyright', 'post', '', 1, 255 );
    $array['user_name'] = filter_text_input( 'upload_user_name', 'post', '', 1, 100 );
    $array['user_id'] = 0;
    $seccode = filter_text_input( 'upload_seccode', 'post', '' );
    
    if ( defined( 'NV_IS_USER' ) )
    {
        $array['user_name'] = $user_info['username'];
        $array['user_id'] = $user_info['userid'];
    }
    
    if ( ! empty( $array['author_url'] ) )
    {
        if ( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $array['author_url'] ) )
        {
            $array['author_url'] = "http://" . $array['author_url'];
        }
    }
    
    if ( ! empty( $array['linkdirect'] ) )
    {
        $linkdirect = $array['linkdirect'];
        $linkdirect = nv_nl2br( $linkdirect, "<br />" );
        $linkdirect = explode( "<br />", $linkdirect );
        $linkdirect = array_map( "trim", $linkdirect );
        $linkdirect = array_unique( $linkdirect );
        
        $array['linkdirect'] = array();
        foreach ( $linkdirect as $link )
        {
            if ( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $link ) )
            {
                $link = "http://" . $link;
            }
            
            if ( nv_is_url( $link ) )
            {
                $array['linkdirect'][] = $link;
            }
        }
        
        $array['linkdirect'] = ! empty( $array['linkdirect'] ) ? implode( "\n", $array['linkdirect'] ) : "";
    }
    
    $alias = change_alias( $array['title'] );
    
    $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $alias );
    $result = $db->sql_query( $sql );
    list( $is_exists ) = $db->sql_fetchrow( $result );
    
    if ( ! $is_exists )
    {
        $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE `title`=" . $db->dbescape( $array['title'] );
        $result = $db->sql_query( $sql );
        list( $is_exists ) = $db->sql_fetchrow( $result );
    }
    
    if ( ! nv_capcha_txt( $seccode ) )
    {
        $is_error = true;
        $error = $lang_module['upload_error1'];
    }
    elseif ( empty( $array['user_name'] ) )
    {
        $is_error = true;
        $error = $lang_module['upload_error2'];
    }
    elseif ( empty( $array['title'] ) )
    {
        $is_error = true;
        $error = $lang_module['file_error_title'];
    }
    elseif ( $is_exists )
    {
        $is_error = true;
        $error = $lang_module['file_title_exists'];
    }
    elseif ( ! $array['catid'] or ! isset( $list_cats[$array['catid']] ) )
    {
        $is_error = true;
        $error = $lang_module['file_catid_exists'];
    }
    elseif ( ! empty( $array['author_email'] ) and ( $check_valid_email = nv_check_valid_email( $array['author_email'] ) ) != "" )
    {
        $is_error = true;
        $error = $check_valid_email;
    }
    elseif ( ! empty( $array['author_url'] ) and ! nv_is_url( $array['author_url'] ) )
    {
        $is_error = true;
        $error = $lang_module['file_error_author_url'];
    }
    else
    {
        $fileupload = "";
        if ( $download_config['is_upload_allow'] )
        {
            if ( isset( $_FILES['upload_fileupload'] ) and is_uploaded_file( $_FILES['upload_fileupload']['tmp_name'] ) )
            {
                $upload = new upload( $global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], $download_config['maxfilesize'], NV_MAX_WIDTH, NV_MAX_HEIGHT );
                $upload_info = $upload->save_file( $_FILES['upload_fileupload'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $download_config['temp_dir'], false );
                
                @unlink( $_FILES['upload_fileupload']['tmp_name'] );
                
                if ( empty( $upload_info['error'] ) )
                {
                    if ( in_array( $upload_info['ext'], $download_config['upload_filetype'] ) )
                    {
                        mt_srand( ( double )microtime() * 1000000 );
                        $maxran = 1000000;
                        $random_num = mt_rand( 0, $maxran );
                        $random_num = md5( $random_num );
                        $nv_pathinfo_filename = nv_pathinfo_filename( $upload_info['name'] );
                        $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $download_config['temp_dir'] . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];
                        
                        $rename = nv_renamefile( $upload_info['name'], $new_name );
                        
                        if ( $rename[0] == 1 )
                        {
                            $fileupload = $new_name;
                        }
                        else
                        {
                            $fileupload = $upload_info['name'];
                        }
                        
                        @chmod( $fileupload, 0644 );
                        $fileupload = str_replace( NV_ROOTDIR . "/" . NV_UPLOADS_DIR, "", $fileupload );
                        $array['filesize'] = $upload_info['size'];
                    }
                    else
                    {
                        @nv_deletefile( $upload_info['name'] );
                        $is_error = true;
                        $error = $lang_module['upload_error4'];
                    }
                }
                else
                {
                    $is_error = true;
                    $error = $upload_info['error'];
                }
                
                unset( $upload, $upload_info );
            }
        }
        
        if ( ! $is_error )
        {
            if ( empty( $fileupload ) and empty( $array['linkdirect'] ) )
            {
                $is_error = true;
                $error = $lang_module['file_error_fileupload'];
            }
            else
            {
                $fileimage = "";
                if ( isset( $_FILES['upload_fileimage'] ) and is_uploaded_file( $_FILES['upload_fileimage']['tmp_name'] ) )
                {
                    $upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                    $upload_info = $upload->save_file( $_FILES['upload_fileimage'], NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $download_config['temp_dir'], false );
                    
                    @unlink( $_FILES['upload_fileimage']['tmp_name'] );
                    
                    if ( empty( $upload_info['error'] ) )
                    {
                        mt_srand( ( double )microtime() * 1000000 );
                        $maxran = 1000000;
                        $random_num = mt_rand( 0, $maxran );
                        $random_num = md5( $random_num );
                        $nv_pathinfo_filename = nv_pathinfo_filename( $upload_info['name'] );
                        $new_name = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $download_config['temp_dir'] . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];
                        
                        $rename = nv_renamefile( $upload_info['name'], $new_name );
                        
                        if ( $rename[0] == 1 )
                        {
                            $fileimage = $new_name;
                        }
                        else
                        {
                            $fileimage = $upload_info['name'];
                        }
                        
                        @chmod( $fileimage, 0644 );
                        $fileimage = str_replace( NV_ROOTDIR . "/" . NV_UPLOADS_DIR, "", $fileimage );
                    }
                }
                
                $array['description'] = nv_nl2br( $array['description'], "<br />" );
                $array['introtext'] = nv_nl2br( $array['introtext'], "<br />" );
                $array['linkdirect'] = nv_nl2br( $array['linkdirect'], "<br />" );
                
                $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` VALUES (
                NULL, 
                " . $array['catid'] . ", 
                " . $db->dbescape( $array['title'] ) . ", 
                " . $db->dbescape( $array['description'] ) . ", 
                " . $db->dbescape( $array['introtext'] ) . ", 
                " . NV_CURRENTTIME . ", 
                " . $array['user_id'] . ", 
                " . $db->dbescape( $array['user_name'] ) . ", 
                " . $db->dbescape( $array['author_name'] ) . ", 
                " . $db->dbescape( $array['author_email'] ) . ", 
                " . $db->dbescape( $array['author_url'] ) . ", 
                " . $db->dbescape( $fileupload ) . ", 
                " . $db->dbescape( $array['linkdirect'] ) . ", 
                " . $db->dbescape( $array['version'] ) . ", 
                " . $array['filesize'] . ", 
                " . $db->dbescape( $fileimage ) . ", 
                " . $db->dbescape( $array['copyright'] ) . ")";
                
                if ( ! $db->sql_query_insert_id( $sql ) )
                {
                    $is_error = true;
                    $error = $lang_module['upload_error3'];
                }
                else
                {
                    $contents = "<div class=\"info_exit\">" . $lang_module['file_upload_ok'] . "</div>";
                    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
					
					$user_post = defined ( "NV_IS_USER" ) ? " | " . $user_info['username'] : "";
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_files_log'], $array['title'] . " | " .  $client_info['ip'] . $user_post, 0 );
			
                    include ( NV_ROOTDIR . "/includes/header.php" );
                    echo nv_site_theme( $contents );
                    include ( NV_ROOTDIR . "/includes/footer.php" );
                    exit();
                }
            }
        }
    }
}
else
{
    $array['catid'] = $array['filesize'] = 0;
    $array['title'] = $array['description'] = $array['introtext'] = $array['author_name'] = $array['author_email'] = $array['author_url'] = $array['linkdirect'] = $array['version'] = $array['copyright'] = $array['user_name'] = "";
    if ( defined( 'NV_IS_USER' ) )
    {
        $array['user_name'] = $user_info['username'];
        $array['user_id'] = $user_info['userid'];
    }
}

if ( ! $array['filesize'] ) $array['filesize'] = '';

if ( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
if ( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );

$array['disabled'] = "";
if ( defined( 'NV_IS_USER' ) )
{
    $array['disabled'] = " disabled=\"disabled\"";
}
$array['addfile'] = md5( $client_info['session_id'] );

$contents = theme_upload( $array, $list_cats, $download_config, $error );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>