<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

/**
 * nv_set_dir_class()
 * 
 * @param mixed $array
 * @return void
 */
function nv_set_dir_class( $array )
{  
    $class = array( "folder" );
    if ( ! empty( $array ) )
    {
        $menu = false;
        foreach ( $array as $key => $item )
        {
            if ( $item ) $class[] = $key;
            if($key == 'create_dir' AND $item) $menu = true;
            if($key == 'rename_dir' AND $item) $menu = true;
            if($key == 'delete_dir' AND $item) $menu = true;
        }
    }

    $class = implode( " ", $class );
    if($menu) $class .= " menu";
    return $class;
}

/**
 * viewdirtree()
 * 
 * @param mixed $dir
 * @param mixed $currentpath2
 * @return
 */
function viewdirtree( $dir, $currentpath )
{
    global $global_config, $module_file, $array_hidefolders;

    $handle = @scandir( NV_ROOTDIR . '/' . $dir );

    $content = "";
    foreach ( $handle as $file )
    {
        $path_file = empty( $dir ) ? $file : $dir . '/' . $file;
        $check_allow_upload_dir = nv_check_allow_upload_dir( $path_file );

        if ( is_dir( NV_ROOTDIR . '/' . $path_file ) && ! in_array( $file, $array_hidefolders ) && $check_allow_upload_dir )
        {
            $class_li = 'expandable';
            $style_color = '';
            if ( $path_file == $currentpath )
            {
                $class_li = "open collapsable";
                $style_color = ' style="color:red"';
            } elseif ( strpos( $currentpath, $path_file . '/' ) !== false )
            {
                $class_li = "open collapsable";
            }
            
            $tree = array();
            $tree['class1'] = $class_li;
            $tree['class2'] = nv_set_dir_class( $check_allow_upload_dir ) . " pos" . nv_string_to_filename($dir);
            $tree['style'] = $style_color;
            $tree['title'] = $path_file;
            $tree['titlepath'] = $file;

            $content2 = viewdirtree( $path_file, $currentpath );

            $xtpl = new XTemplate( "foldlist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
            $xtpl->assign( "DIRTREE", $tree );
            if ( ! empty( $content2 ) )
            {
                $xtpl->assign( "TREE_CONTENT", $content2 );
                $xtpl->parse( 'tree.tree_content' );
            }
            $xtpl->parse( 'tree' );
            $content .= $xtpl->text( 'tree' );
        }
    }

    return $content;
}

$path = nv_check_path_upload( $nv_Request->get_string( 'path', 'get,post', NV_UPLOADS_DIR ) );
if ( empty( $path ) and ! defined( 'NV_IS_SPADMIN' ) )
{
    $path = NV_UPLOADS_DIR;
}

$currentpath = nv_check_path_upload( $nv_Request->get_string( 'currentpath', 'request', NV_UPLOADS_DIR ) );

$check_allow_upload_dir = nv_check_allow_upload_dir( $path );

$data = array();
$data['style'] = $path == $currentpath ? " style=\"color:red\"" : "";
$data['class'] = nv_set_dir_class( $check_allow_upload_dir ) . " pos" . nv_string_to_filename($path);
$data['title'] = $path;
$data['titlepath'] = empty( $path ) ? NV_BASE_SITEURL : $path;

$content = viewdirtree( $path, $currentpath );

$xtpl = new XTemplate( "foldlist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( "DATA", $data );
$xtpl->assign( "PATH", $path );
$xtpl->assign( "CURRENTPATH", $currentpath );

$check_allow_upload_dir = nv_check_allow_upload_dir( $currentpath );
$xtpl->assign( "VIEW_DIR", (isset($check_allow_upload_dir['view_dir']) AND $check_allow_upload_dir['view_dir'] === true) ? 1 : 0 );
$xtpl->assign( "CREATE_DIR", (isset($check_allow_upload_dir['create_dir']) AND $check_allow_upload_dir['create_dir'] === true) ? 1 : 0 );
$xtpl->assign( "RENAME_DIR", (isset($check_allow_upload_dir['rename_dir']) AND $check_allow_upload_dir['rename_dir'] === true) ? 1 : 0 );
$xtpl->assign( "DELETE_DIR", (isset($check_allow_upload_dir['delete_dir']) AND $check_allow_upload_dir['delete_dir'] === true) ? 1 : 0 );
$xtpl->assign( "UPLOAD_FILE", (isset($check_allow_upload_dir['upload_file']) AND $check_allow_upload_dir['upload_file'] === true) ? 1 : 0 );
$xtpl->assign( "CREATE_FILE", (isset($check_allow_upload_dir['create_file']) AND $check_allow_upload_dir['create_file'] === true) ? 1 : 0 );
$xtpl->assign( "RENAME_FILE", (isset($check_allow_upload_dir['rename_file']) AND $check_allow_upload_dir['rename_file'] === true) ? 1 : 0 );
$xtpl->assign( "DELETE_FILE", (isset($check_allow_upload_dir['delete_file']) AND $check_allow_upload_dir['delete_file'] === true) ? 1 : 0 );
$xtpl->assign( "MOVE_FILE", (isset($check_allow_upload_dir['move_file']) AND $check_allow_upload_dir['move_file'] === true) ? 1 : 0 );

if ( ! empty( $content ) )
{
    $xtpl->assign( "CONTENT", $content );
    $xtpl->parse( 'main.main_content' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
echo $contents;
exit;

?>