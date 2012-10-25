<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['download_config'];

$array_exts = get_allow_exts();
$groups_list = nv_groups_list();
$array_who_upload = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
if ( ! empty( $groups_list ) )
{
    $array_who_upload[] = $lang_global['who_view3'];
}

$readme_file = NV_ROOTDIR . '/' . NV_DATADIR . '/README.txt';

$array_config = array();

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array_config['is_addfile'] = $nv_Request->get_int( 'is_addfile', 'post', 0 );
    $array_config['who_addfile'] = $nv_Request->get_int( 'who_addfile', 'post', 0 );
    $array_config['groups_addfile'] = $nv_Request->get_typed_array( 'groups_addfile', 'post', 'int' );
    $array_config['is_upload'] = $nv_Request->get_int( 'is_upload', 'post', 0 );
    $array_config['who_upload'] = $nv_Request->get_int( 'who_upload', 'post', 0 );
    $array_config['groups_upload'] = $nv_Request->get_typed_array( 'groups_upload', 'post', 'int' );
    $array_config['who_autocomment'] = $nv_Request->get_int( 'who_autocomment', 'post', 0 );
    $array_config['groups_autocomment'] = $nv_Request->get_typed_array( 'groups_autocomment', 'post', 'int' );
    $array_config['maxfilesize'] = $nv_Request->get_int( 'maxfilesize', 'post', 0 );
    $array_config['upload_filetype'] = $nv_Request->get_typed_array( 'upload_filetype', 'post', 'string' );
    $array_config['upload_dir'] = filter_text_input( 'upload_dir', 'post', '' );
    $array_config['temp_dir'] = filter_text_input( 'temp_dir', 'post', '' );
    $array_config['is_zip'] = $nv_Request->get_int( 'is_zip', 'post', 0 );
    $array_config['readme'] = filter_text_textarea( 'readme', '' );
    $array_config['readme'] = strip_tags( $array_config['readme'] );
    $array_config['is_resume'] = $nv_Request->get_int( 'is_resume', 'post', 0 );
    $array_config['max_speed'] = $nv_Request->get_int( 'max_speed', 'post', 0 );

    if ( ! in_array( $array_config['who_addfile'], array_keys( $array_who_upload ) ) )
    {
        $array_config['who_addfile'] = 0;
    }

    $array_config['groups_addfile'] = ( ! empty( $array_config['groups_addfile'] ) ) ? implode( ',', $array_config['groups_addfile'] ) : '';

    if ( ! in_array( $array_config['who_upload'], array_keys( $array_who_upload ) ) )
    {
        $array_config['who_upload'] = 0;
    }

    $array_config['groups_upload'] = ( ! empty( $array_config['groups_upload'] ) ) ? implode( ',', $array_config['groups_upload'] ) : '';

    if ( ! in_array( $array_config['who_autocomment'], array_keys( $array_who_upload ) ) )
    {
        $array_config['who_autocomment'] = 0;
    }

    $array_config['groups_autocomment'] = ( ! empty( $array_config['groups_autocomment'] ) ) ? implode( ',', $array_config['groups_autocomment'] ) : '';

    if ( $array_config['maxfilesize'] <= 0 or $array_config['maxfilesize'] > NV_UPLOAD_MAX_FILESIZE )
    {
        $array_config['maxfilesize'] = NV_UPLOAD_MAX_FILESIZE;
    }

    $array_config['upload_filetype'] = ( ! empty( $array_config['upload_filetype'] ) ) ? implode( ',', $array_config['upload_filetype'] ) : '';

    if ( ! preg_match( "/^[a-zA-Z][a-zA-Z0-9\_]*$/", $array_config['upload_dir'] ) )
    {
        $array_config['upload_dir'] = "files";
    }
    else
    {
        if ( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_config['upload_dir'] ) )
        {
            $mkdir = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, $array_config['upload_dir'] );
            nv_loadUploadDirList( false );
            if ( $mkdir[0] == 0 )
            {
                $array_config['upload_dir'] = "files";
            }
        }
    }

    if ( ! preg_match( "/^[a-zA-Z][a-zA-Z0-9\_]*$/", $array_config['temp_dir'] ) )
    {
        $array_config['temp_dir'] = "temp";
    }
    else
    {
        if ( ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $array_config['temp_dir'] ) )
        {
            $mkdir = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $module_name, $array_config['temp_dir'] );
            nv_loadUploadDirList( false );
            if ( $mkdir[0] == 0 )
            {
                $array_config['temp_dir'] = "temp";
            }
        }
    }

    foreach ( $array_config as $config_name => $config_value )
    {
        if ( $config_name != 'readme' )
        {
            $query = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_config` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
            $db->sql_query( $query );
        }
    }

    if ( ! empty( $array_config['readme'] ) )
    {
        file_put_contents( $readme_file, $array_config['readme'] );
    }
    else
    {
        if ( file_exists( $readme_file ) )
        {
            @nv_deletefile( $readme_file );
        }
    }

    nv_del_moduleCache( $module_name );

    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    die();
}

$array_config['is_addfile'] = 0;
$array_config['who_addfile'] = 0;
$array_config['groups_addfile'] = "";
$array_config['is_upload'] = 0;
$array_config['who_upload'] = 0;
$array_config['groups_upload'] = "";
$array_config['who_autocomment'] = 0;
$array_config['groups_autocomment'] = "";
$array_config['maxfilesize'] = NV_UPLOAD_MAX_FILESIZE;
$array_config['upload_filetype'] = '';
$array_config['upload_dir'] = 'files';
$array_config['temp_dir'] = 'temp';
$array_config['is_zip'] = 0;
$array_config['readme'] = '';
$array_config['is_resume'] = 0;
$array_config['max_speed'] = 0;

if ( file_exists( $readme_file ) )
{
    $array_config['readme'] = file_get_contents( $readme_file );
    $array_config['readme'] = nv_htmlspecialchars( $array_config['readme'] );
}

$sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query( $sql );
while ( list( $c_config_name, $c_config_value ) = $db->sql_fetchrow( $result ) )
{
    $array_config[$c_config_name] = $c_config_value;
}

$array_config['is_addfile'] = ! empty( $array_config['is_addfile'] ) ? " checked=\"checked\"" : "";
$array_config['is_upload'] = ! empty( $array_config['is_upload'] ) ? " checked=\"checked\"" : "";
$array_config['is_zip'] = ! empty( $array_config['is_zip'] ) ? " checked=\"checked\"" : "";
$array_config['is_resume'] = ! empty( $array_config['is_resume'] ) ? " checked=\"checked\"" : "";

$who_addfile = $array_config['who_addfile'];
$array_config['who_addfile'] = array();
foreach ( $array_who_upload as $key => $who )
{
    $array_config['who_addfile'][$key] = array( //
        'key' => $key, //
        'title' => $who, //
        'selected' => $key == $who_addfile ? " selected=\"selected\"" : "" //
        );
}

$who_upload = $array_config['who_upload'];
$array_config['who_upload'] = array();
foreach ( $array_who_upload as $key => $who )
{
    $array_config['who_upload'][$key] = array( //
        'key' => $key, //
        'title' => $who, //
        'selected' => $key == $who_upload ? " selected=\"selected\"" : "" //
        );
}

$who_autocomment = $array_config['who_autocomment'];
$array_config['who_autocomment'] = array();
foreach ( $array_who_upload as $key => $who )
{
    $array_config['who_autocomment'][$key] = array( //
        'key' => $key, //
        'title' => $who, //
        'selected' => $key == $who_autocomment ? " selected=\"selected\"" : "" //
        );
}

$upload_filetype = ! empty( $array_config['upload_filetype'] ) ? explode( ",", $array_config['upload_filetype'] ) : array();
$array_config['upload_filetype'] = array();
if ( ! empty( $array_exts ) )
{
    foreach ( $array_exts as $ext => $mime )
    {
        $array_config['upload_filetype'][$ext] = array( //
            'ext' => $ext, //
            'title' => $ext . " (mime: " . $mime . ")", //
            'checked' => ( in_array( $ext, $upload_filetype ) ) ? " checked=\"checked\"" : "" //
            );
    }
}

$groups_addfile = ! empty( $array_config['groups_addfile'] ) ? explode( ",", $array_config['groups_addfile'] ) : array();
$array_config['groups_addfile'] = array();
if ( ! empty( $groups_list ) )
{
    foreach ( $groups_list as $key => $title )
    {
        $array_config['groups_addfile'][$key] = array( //
            'key' => $key, //
            'title' => $title, //
            'checked' => in_array( $key, $groups_addfile ) ? " checked=\"checked\"" : "" //
            );
    }
}

$groups_upload = ! empty( $array_config['groups_upload'] ) ? explode( ",", $array_config['groups_upload'] ) : array();
$array_config['groups_upload'] = array();
if ( ! empty( $groups_list ) )
{
    foreach ( $groups_list as $key => $title )
    {
        $array_config['groups_upload'][$key] = array( //
            'key' => $key, //
            'title' => $title, //
            'checked' => in_array( $key, $groups_upload ) ? " checked=\"checked\"" : "" //
            );
    }
}

$groups_autocomment = ! empty( $array_config['groups_autocomment'] ) ? explode( ",", $array_config['groups_autocomment'] ) : array();
$array_config['groups_autocomment'] = array();
if ( ! empty( $groups_list ) )
{
    foreach ( $groups_list as $key => $title )
    {
        $array_config['groups_autocomment'][$key] = array( //
            'key' => $key, //
            'title' => $title, //
            'checked' => in_array( $key, $groups_autocomment ) ? " checked=\"checked\"" : "" //
            );
    }
}

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );
$xtpl->assign( 'NV_UPLOAD_MAX_FILESIZE', NV_UPLOAD_MAX_FILESIZE );

foreach ( $array_config['upload_filetype'] as $filetype )
{
    $xtpl->assign( 'UPLOAD_FILETYPE', $filetype );
    $xtpl->parse( 'main.upload_filetype' );
}

foreach ( $array_config['who_addfile'] as $who )
{
    $xtpl->assign( 'WHO_ADDFILE', $who );
    $xtpl->parse( 'main.who_addfile' );
}

if ( ! empty( $array_config['groups_addfile'] ) )
{
    foreach ( $array_config['groups_addfile'] as $group )
    {
        $xtpl->assign( 'GROUPS_ADDFILE', $group );
        $xtpl->parse( 'main.group3.groups_addfile' );
    }
    $xtpl->parse( 'main.group3' );
}

foreach ( $array_config['who_upload'] as $who )
{
    $xtpl->assign( 'WHO_UPLOAD', $who );
    $xtpl->parse( 'main.who_upload' );
}

if ( ! empty( $array_config['groups_upload'] ) )
{
    foreach ( $array_config['groups_upload'] as $group )
    {
        $xtpl->assign( 'GROUPS_UPLOAD', $group );
        $xtpl->parse( 'main.group_empty.groups_upload' );
    }
    $xtpl->parse( 'main.group_empty' );
}

foreach ( $array_config['who_autocomment'] as $who )
{
    $xtpl->assign( 'WHO_AUTOCOMMENT', $who );
    $xtpl->parse( 'main.who_autocomment' );
}

if ( ! empty( $array_config['groups_autocomment'] ) )
{
    foreach ( $array_config['groups_autocomment'] as $group )
    {
        $xtpl->assign( 'GROUPS_AUTOCOMMENT', $group );
        $xtpl->parse( 'main.group2.groups_autocomment' );
    }
    $xtpl->parse( 'main.group2' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>