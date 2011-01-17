<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

if ( $module_name == "upload" )
{
    define( 'NV_IS_FILE_ADMIN', true );
    
    $allow_upload_dir = array( 
        'images', NV_UPLOADS_DIR 
    );
    
    $array_hidefolders = array( 
        ".svn", "CVS", ".", "..", "index.html", ".htaccess" 
    );
    $allow_func = array( 
        'main', 'imglist', 'delimg', 'createimg', 'dlimg', 'renameimg', 'moveimg', 'folderlist', 'delfolder', 'renamefolder', 'createfolder', 'quickupload' 
    );
    $allowed_extensions = array();
    $array_images = array( 
        "gif", "jpg", "jpeg", "pjpeg", "png" 
    );
    if ( in_array( 'images', $admin_info['allow_files_type'] ) )
    {
        $allowed_extensions = array( 
            "gif", "jpg", "jpeg", "pjpeg", "png" 
        );
    }
    $array_flash = array( 
        'swf', 'swc', 'flv' 
    );
    if ( in_array( 'flash', $admin_info['allow_files_type'] ) )
    {
        $allowed_extensions[] = 'swf';
        $allowed_extensions[] = 'swc';
        $allowed_extensions[] = 'flv';
    }
    $array_archives = array( 
        'rar', 'zip', 'tar' 
    );
    if ( in_array( 'archives', $admin_info['allow_files_type'] ) )
    {
        $allowed_extensions[] = 'rar';
        $allowed_extensions[] = 'zip';
        $allowed_extensions[] = 'tar';
    }
    $array_documents = array( 
        'doc', 'xls', 'chm', 'pdf', 'docx', 'xlsx' 
    );
    if ( in_array( 'documents', $admin_info['allow_files_type'] ) )
    {
        $allowed_extensions[] = 'doc';
        $allowed_extensions[] = 'xls';
        $allowed_extensions[] = 'chm';
        $allowed_extensions[] = 'pdf';
        $allowed_extensions[] = 'docx';
        $allowed_extensions[] = 'xlsx';
    }
    $allowed_mime = array( 
        "image/gif", "image/pjpeg", "image/jpeg", "image/png", "image/x-png" 
    );
    $types = array( 
        1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 5 => 'psd', 6 => 'bmp', 7 => 'tiff(intel byte order)', 8 => 'tiff(motorola byte order)', 9 => 'jpc', 10 => 'jp2', 11 => 'jpx', 12 => 'jb2', 13 => 'swc', 14 => 'iff', 15 => 'wbmp', 16 => 'xbm' 
    );

    function viewdir ( $dir )
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

    function viewdirtree ( $dir, $currentpath2 )
    {
        global $array_hidefolders;
        $handle2 = scandir( NV_ROOTDIR . '/' . $dir );
        foreach ( $handle2 as $file2 )
        {
            $full_d2 = NV_ROOTDIR . '/' . $dir . '/' . $file2;
            if ( is_dir( $full_d2 ) && ! in_array( $file2, $array_hidefolders ) && nv_check_allow_upload_dir( $dir . '/' . $file2 ) )
            {
                if ( trim( $dir . '/' . $file2 ) == $currentpath2 )
                {
                    echo '<li class="open collapsable"><span style="color:red" class="folder" title="' . ( $dir . '/' . $file2 ) . '">&nbsp;' . $file2 . '</span>';
                }
                elseif ( strpos( $currentpath2, $dir . '/' . $file2 . '/' ) !== false )
                {
                    echo '<li class="open collapsable"><span class="folder" title="' . ( $dir . '/' . $file2 ) . '">&nbsp;' . $file2 . '</span>';
                }
                else
                {
                    echo '<li class="expandable"><span class="folder" title="' . ( $dir . '/' . $file2 ) . '">&nbsp;' . $file2 . '</span>';
                }
                echo '<ul>';
                viewdirtree( $dir . '/' . $file2, $currentpath2 );
                echo '</ul>';
                echo '</li>';
            }
        }
    }

    function nv_check_allow_upload_dir ( $dir )
    {
        global $site_mods, $allow_upload_dir;
        $arr_dir = explode( "/", $dir );
        if ( defined( 'NV_IS_SPADMIN' ) and in_array( $arr_dir[0], $allow_upload_dir ) )
        {
            return true;
        }
        elseif ( $arr_dir[0] == NV_UPLOADS_DIR and isset( $arr_dir[1] ) and isset( $site_mods[$arr_dir[1]] ) )
        {
            return true;
        }
        return false;
    }
}
?>