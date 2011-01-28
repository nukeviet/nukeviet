<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

if ( $module_name == "upload" ) return;

define( 'NV_IS_FILE_ADMIN', true );

$allow_upload_dir = array( 'images', NV_UPLOADS_DIR );
$notchange_dirs = array( //
    'news' => array( 'source', 'temp_pic' ), //
    'download' => array( 'files', 'images', 'temp', 'thumb' ) //
    );
$imgNotChangeDirs = array( 'rank' );

$array_hidefolders = array( ".svn", "CVS", ".", "..", "index.html", ".htaccess", ".tmp" );
$allow_func = array( 'main', 'imglist', 'delimg', 'createimg', 'dlimg', 'renameimg', 'moveimg', 'folderlist', 'delfolder', 'renamefolder', 'createfolder', 'quickupload', 'upload' );
$allowed_extensions = array();
$array_images = array( "gif", "jpg", "jpeg", "pjpeg", "png" );
if ( in_array( 'images', $admin_info['allow_files_type'] ) )
{
    $allowed_extensions = array( "gif", "jpg", "jpeg", "pjpeg", "png" );
}
$array_flash = array( 'swf', 'swc', 'flv' );
if ( in_array( 'flash', $admin_info['allow_files_type'] ) )
{
    $allowed_extensions[] = 'swf';
    $allowed_extensions[] = 'swc';
    $allowed_extensions[] = 'flv';
}
$array_archives = array( 'rar', 'zip', 'tar' );
if ( in_array( 'archives', $admin_info['allow_files_type'] ) )
{
    $allowed_extensions[] = 'rar';
    $allowed_extensions[] = 'zip';
    $allowed_extensions[] = 'tar';
}
$array_documents = array( 'doc', 'xls', 'chm', 'pdf', 'docx', 'xlsx' );
if ( in_array( 'documents', $admin_info['allow_files_type'] ) )
{
    $allowed_extensions[] = 'doc';
    $allowed_extensions[] = 'xls';
    $allowed_extensions[] = 'chm';
    $allowed_extensions[] = 'pdf';
    $allowed_extensions[] = 'docx';
    $allowed_extensions[] = 'xlsx';
}
$allowed_mime = array( "image/gif", "image/pjpeg", "image/jpeg", "image/png", "image/x-png" );
$types = array( 1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 5 => 'psd', 6 => 'bmp', 7 => 'tiff(intel byte order)', 8 => 'tiff(motorola byte order)', 9 => 'jpc', 10 => 'jp2', 11 => 'jpx', 12 => 'jb2', 13 => 'swc', 14 => 'iff', 15 => 'wbmp', 16 => 'xbm' );

/**
 * viewdir()
 * 
 * @param mixed $dir
 * @return
 */
function viewdir( $dir )
{
    global $imglibs, $array_hidefolders;
    $handle = scandir( NV_ROOTDIR . '/' . $dir );
    foreach ( $handle as $file )
    {
        $full_d = NV_ROOTDIR . '/' . $dir . '/' . $file;
        if ( is_dir( $full_d ) && ! in_array( $file, $array_hidefolders ) && nv_check_allow_upload_dir( $dir . '/' . $file ) )
        {
            $imglibs[] = $dir . '/' . $file;
            viewdir( $dir . '/' . $file );
        }
    }
    return $imglibs;
}

/**
 * nv_check_allow_upload_dir()
 * 
 * @param mixed $dir
 * @return
 */
function nv_check_allow_upload_dir( $dir )
{
    global $site_mods, $allow_upload_dir, $admin_info, $notchange_dirs, $imgNotChangeDirs;

    $dir = trim( $dir );
    if ( empty( $dir ) ) return array();

    $dir = str_replace( "\\", "/", $dir );
    $dir = rtrim( $dir, "/" );
    $arr_dir = explode( "/", $dir );
    $level = array();

    if ( in_array( $arr_dir[0], $allow_upload_dir ) )
    {

        if ( defined( 'NV_IS_SPADMIN' ) )
        {
            $level['view_dir'] = true;

            if ( $admin_info['allow_create_subdirectories'] )
            {
                $level['create_dir'] = true;
            }
            if ( $admin_info['allow_modify_subdirectories'] and ! in_array( $dir, $allow_upload_dir ) )
            {
                $level['rename_dir'] = true;
                $level['delete_dir'] = true;

                if ( isset( $arr_dir[1] ) and ! empty( $arr_dir[1] ) and isset( $site_mods[$arr_dir[1]] ) and ! isset( $arr_dir[2] ) )
                {
                    unset( $level['rename_dir'], $level['delete_dir'] );
                }
            }
            if ( ! empty( $admin_info['allow_files_type'] ) )
            {
                $level['upload_file'] = true;
            }
            if ( $admin_info['allow_modify_files'] )
            {
                $level['create_file'] = true;
                $level['rename_file'] = true;
                $level['delete_file'] = true;
                $level['move_file'] = true;
            }
        } elseif ( isset( $arr_dir[1] ) and ! empty( $arr_dir[1] ) and isset( $site_mods[$arr_dir[1]] ) )
        {
            $level['view_dir'] = true;

            if ( $admin_info['allow_create_subdirectories'] )
            {
                $level['create_dir'] = true;
            }
            if ( isset( $arr_dir[2] ) and ! empty( $arr_dir[2] ) and $admin_info['allow_modify_subdirectories'] )
            {
                $level['rename_dir'] = true;
                $level['delete_dir'] = true;
            }
            if ( ! empty( $admin_info['allow_files_type'] ) )
            {
                $level['upload_file'] = true;
            }
            if ( $admin_info['allow_modify_files'] )
            {
                $level['create_file'] = true;
                $level['rename_file'] = true;
                $level['delete_file'] = true;
                $level['move_file'] = true;
            }
        }

        if ( preg_match( "/^([\d]{4})\_([\d]{1,2})$/", $arr_dir[count( $arr_dir ) - 1] ) )
        {
            unset( $level['rename_dir'], $level['delete_dir'] );
        }

        if ( isset( $arr_dir[2] ) and ! isset( $arr_dir[3] ) )
        {
            if ( ! empty( $notchange_dirs ) )
            {
                foreach ( $notchange_dirs as $mod => $dirs )
                {
                    if ( $arr_dir[1] == $mod and in_array( $arr_dir[2], $dirs ) )
                    {
                        unset( $level['rename_dir'], $level['delete_dir'] );
                        break;
                    }
                }
            }
        }

        foreach ( $imgNotChangeDirs as $imgdir )
        {
            if ( $dir == 'images/' . $imgdir )
            {
                $level = array( 'view_dir' => true );
                break;
            }
        }
    }

    return $level;
}

/**
 * nv_check_path_upload()
 * 
 * @param mixed $path
 * @return
 */
function nv_check_path_upload( $path )
{
    global $allow_upload_dir;

    $path = htmlspecialchars( trim( $path ), ENT_QUOTES );
    $path = rtrim( $path, "/" );
    if ( empty( $path ) ) return "";

    $path = NV_ROOTDIR . "/" . $path;
    if ( ( $path = realpath( $path ) ) === false ) return "";

    $path = str_replace( "\\", "/", $path );
    $path = str_replace( NV_ROOTDIR . "/", "", $path );

    $result = false;
    foreach ( $allow_upload_dir as $dir )
    {
        $dir = nv_preg_quote( $dir );
        if ( preg_match( "/^" . $dir . "/", $path ) )
        {
            $result = true;
            break;
        }
    }

    if ( $result === false ) return "";
    return $path;
}

/**
 * nv_delete_cache_upload()
 * 
 * @param mixed $realpath
 * @return
 */
function nv_delete_cache_upload( $realpath )
{
    $files = scandir( $realpath );
    $files = array_diff( $files, array( ".", ".." ) );
    if ( count( $files ) )
    {
        $path = str_replace( "\\", "/", $realpath );
        $path = str_replace( NV_ROOTDIR . "/", "", $path );
        foreach ( $files as $f )
        {
            if ( is_dir( $realpath . '/' . $f ) )
            {
                nv_delete_cache_upload( $realpath . '/' . $f );
            }
            else
            {
                $image = basename( $f );
                $md5_view_image = NV_ROOTDIR . "/" . NV_FILES_DIR . "/images/" . md5( $path . '/' . $image ) . "." . nv_getextension( $image );
                if ( file_exists( $md5_view_image ) )
                {
                    @nv_deletefile( $md5_view_image );
                }
            }
        }
    }
}

/**
 * nv_get_viewImage()
 * 
 * @param mixed $fileName
 * @param integer $w
 * @param integer $h
 * @return
 */
function nv_get_viewImage( $fileName, $w = 80, $h = 80 )
{
    $ext = nv_getextension( $fileName );
    $md5_view_image = md5( $fileName );
    $viewDir = NV_FILES_DIR . '/images';
    $viewFile = $viewDir . '/' . $md5_view_image . '.' . $ext;

    if ( file_exists( NV_ROOTDIR . '/' . $viewFile ) )
    {
        $size = @getimagesize( NV_ROOTDIR . '/' . $viewFile );
        return array( $viewFile, $size[0], $size[1] );
    }

    include_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
    $image = new image( NV_ROOTDIR . '/' . $fileName, NV_MAX_WIDTH, NV_MAX_HEIGHT );
    $image->resizeXY( $w, $h );
    $image->save( NV_ROOTDIR . '/' . $viewDir, $md5_view_image, 75 );
    $create_Image_info = $image->create_Image_info;
    $error = $image->error;
    $image->close();
    if ( empty( $error ) )
    {
        return array( $viewFile, $create_Image_info['width'], $create_Image_info['height'] );
    }

    return false;
}

?>