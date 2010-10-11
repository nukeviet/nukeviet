<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 21:13
 */

if ( ! defined( 'NV_IS_FILE_AUTHORS' ) ) die( 'Stop!!!' );

if ( ! ( defined( "NV_IS_GODADMIN" ) or ( defined( "NV_IS_SPADMIN" ) and $global_config['spadmin_add_admin'] == 1 ) ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$userid = $asel = $nv_Request->get_int( 'userid', 'get', 0 );
if ( empty( $userid ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&asel=1" );
    die();
}

$sql = "SELECT * FROM `" . NV_AUTHORS_GLOBALTABLE . "` WHERE `admin_id`=" . $userid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( $numrows )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}

$sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $userid;
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
    die();
}
$row = $db->sql_fetchrow( $result );

$error = "";
if ( $nv_Request->get_int( 'save', 'post', 0 ) )
{
    $lev = $nv_Request->get_int( 'lev', 'post', 0 );
    $editor = filter_text_input( 'editor', 'post' );
    $allow_files_type = $nv_Request->get_array( 'allow_files_type', 'post', array() );
    $allow_create_subdirectories = $nv_Request->get_int( 'allow_create_subdirectories', 'post', 0 );
    $allow_modify_files = $nv_Request->get_int( 'allow_modify_files', 'post', 0 );
    $allow_modify_subdirectories = $nv_Request->get_int( 'allow_modify_subdirectories', 'post', 0 );
    $modules = $nv_Request->get_array( 'modules', 'post', array() );
    $position = filter_text_input( 'position', 'post', '', 1 );
    
    if ( empty( $position ) )
    {
        $error = $lang_module['position_incorrect'];
    }
    else
    {
        $lev = ( $lev != 2 or ! defined( "NV_IS_GODADMIN" ) ) ? 3 : 2;
        $mds = array();
        if ( $lev == 3 and ! empty( $modules ) )
        {
            $is_delCache = false;
            foreach ( array_keys( $site_mods ) as $mod )
            {
                if ( ! empty( $mod ) and in_array( $mod, $modules ) )
                {
                    $site_mods_admins = $site_mods[$mod]['admins'] . ( ( ! empty( $site_mods[$mod]['admins'] ) ) ? "," : "" ) . $userid;
                    $site_mods_admins = explode( ",", $site_mods_admins );
                    $site_mods_admins = array_unique( $site_mods_admins );
                    $site_mods_admins = implode( ",", $site_mods_admins );
                    $sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `admins`=" . $db->dbescape( $site_mods_admins ) . " WHERE `title`=" . $db->dbescape( $mod );
                    $db->sql_query( $sql );
                    $is_delCache = true;
                    $mds[] = $site_mods[$mod]['custom_title'];
                }
            }
            
            if ( $is_delCache )
            {
                nv_del_moduleCache( 'modules' );
            }
        }
        
        $allow_files_type = array_values( array_intersect( $global_config['file_allowed_ext'], $allow_files_type ) );
        $files_level = ( ! empty( $allow_files_type ) ? implode( ",", $allow_files_type ) : "" ) . "|" . $allow_modify_files . "|" . $allow_create_subdirectories . "|" . $allow_modify_subdirectories;
        
        $sql = "INSERT INTO `" . NV_AUTHORS_GLOBALTABLE . "` 
        (`admin_id`, `editor`, `lev`, `files_level`, `position`, `is_suspend`, `susp_reason`, `check_num`, `last_login`, `last_ip`, `last_agent`) 
        VALUES (
        " . $userid . ", 
        " . $db->dbescape( $editor ) . ", 
        " . $lev . ", 
        " . $db->dbescape( $files_level ) . ", 
        " . $db->dbescape( $position ) . ", 
        0,'', '',0,'','')";
        if ( $db->sql_query( $sql ) )
        {
            $result = array( 
                'admin_id' => $userid, 'editor' => $editor, 'lev' => $lev, 'allow_files_type' => $allow_files_type, 'allow_modify_files' => $allow_modify_files, 'allow_create_subdirectories' => $allow_create_subdirectories, 'allow_modify_subdirectories' => $allow_modify_subdirectories, 'position' => $position, 'modules' => ( ! empty( $mds ) ) ? implode( ", ", $mds ) : "" 
            );
            nv_insert_logs( NV_LANG_DATA, $module_name, 'add_admin', "userid: " . $userid, $admin_info['userid'] );
            nv_admin_add_result( $result );
        }
        exit();
    }
}
else
{
    $position = $editor = "";
    $lev = 3;
    $modules = array();
    $allow_files_type = array( 
        'images', 'archives' 
    );
    $allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
}

$page_title = $lang_module['nv_admin_add'];

$info = ( ! empty( $error ) ) ? $error : sprintf( $lang_module['nv_admin_add_info'], $row['username'] );
$is_error = ( ! empty( $error ) ) ? 1 : 0;

$mods = array();
foreach ( array_keys( $site_mods ) as $mod )
{
    $mods[$mod]['checked'] = in_array( $mod, $modules ) ? 1 : 0;
    $mods[$mod]['custom_title'] = $site_mods[$mod]['custom_title'];
}

$contents = array();

$contents['action'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add&amp;userid=" . $userid;
$contents['lev'] = array( 
    $lang_module['lev'], $lev, $lang_module['level2'], $lang_module['level3'] 
);

$editors = array();

$dirs = nv_scandir( NV_ROOTDIR . '/' . NV_EDITORSDIR, "/^[a-zA-Z0-9_]+$/" );
if ( ! empty( $dirs ) )
{
    foreach ( $dirs as $dir )
    {
        if ( file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $dir . '/nv.php' ) ) $editors[] = $dir;
    }
}

if ( ! empty( $editors ) )
{
    $contents['editor'] = array( 
        $lang_module['editor'], $editors, $editor, $lang_module['not_use'] 
    );
}

if ( ! empty( $global_config['file_allowed_ext'] ) )
{
    $contents['allow_files_type'] = array( 
        $lang_module['allow_files_type'], $global_config['file_allowed_ext'], $allow_files_type 
    );
}

$contents['allow_modify_files'] = array( 
    $lang_module['allow_modify_files'], $allow_modify_files 
);
$contents['allow_create_subdirectories'] = array( 
    $lang_module['allow_create_subdirectories'], $allow_create_subdirectories 
);
$contents['allow_modify_subdirectories'] = array( 
    $lang_module['allow_modify_subdirectories'], $allow_modify_subdirectories 
);

$contents['mods'] = array( 
    $lang_module['if_level3_selected'], $mods 
);
$contents['position'] = array( 
    $lang_module['position'], $position, $lang_module['position_info'] 
);
$contents['info'] = $info;
$contents['is_error'] = $is_error;
$contents['submit'] = $lang_module['nv_admin_add'];

//parse content
$xtpl = new XTemplate( "add.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/authors" );
$xtpl->assign( 'ERROR', $contents['is_error'] ? " class=\"error\"" : "" );
$xtpl->assign( 'INFO', $contents['info'] );

$xtpl->assign( 'ACTION', $contents['action'] );

if ( isset( $contents['editor'] ) )
{
    $xtpl->assign( 'EDITOR0', $contents['editor'][0] );
    $xtpl->assign( 'EDITOR3', $contents['editor'][3] );
    foreach ( $contents['editor'][1] as $edt )
    {
        $xtpl->assign( 'SELECTED', $edt == $contents['editor'][2] ? " selected=\"selected\"" : "" );
        $xtpl->assign( 'EDITOR', $edt );
        $xtpl->parse( 'add.editor.loop' );
    }
    $xtpl->parse( 'add.editor' );
}

if ( isset( $contents['allow_files_type'] ) )
{
    $xtpl->assign( 'ALLOW_FILES_TYPE0', $contents['allow_files_type'][0] );
    foreach ( $contents['allow_files_type'][1] as $tp )
    {
        $xtpl->assign( 'CHECKED', in_array( $tp, $contents['allow_files_type'][2] ) ? " checked=\"checked\"" : "" );
        $xtpl->assign( 'TP', $tp );
        $xtpl->parse( 'add.allow_files_type.loop' );
    }
    $xtpl->parse( 'add.allow_files_type' );
}

$xtpl->assign( 'ALLOW_MODIFY_FILES0', $contents['allow_modify_files'][0] );
$xtpl->assign( 'MODIFY_CHECKED', $contents['allow_modify_files'][1] ? " checked=\"checked\"" : "" );

$xtpl->assign( 'ALLOW_CREATE_SUBDIRECTORIES0', $contents['allow_create_subdirectories'][0] );
$xtpl->assign( 'CREATE_CHECKED', $contents['allow_create_subdirectories'][1] ? " checked=\"checked\"" : "" );

$xtpl->assign( 'ALLOW_MODIFY_SUBDIRECTORIES', $contents['allow_modify_subdirectories'][0] );
$xtpl->assign( 'ALLOW_MODIFY_SUBDIRECTORIES_CHECKED', $contents['allow_modify_subdirectories'][1] ? " checked=\"checked\"" : "" );

$xtpl->assign( 'LEV0', $contents['lev'][0] );
$xtpl->assign( 'LEV2', $contents['lev'][2] );
$xtpl->assign( 'LEV3', $contents['lev'][3] );
$xtpl->assign( 'LEV2_CHECKED', $contents['lev'][1] == 2 ? " checked=\"checked\"" : "" );
$xtpl->assign( 'LEV3_CHECKED', $contents['lev'][1] == 3 ? " checked=\"checked\"" : "" );
$xtpl->assign( 'MODS0', $contents['mods'][0] );
$xtpl->assign( 'STYLE_MODS', $contents['lev'][1] == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" );

if ( defined( "NV_IS_GODADMIN" ) )
{
    $xtpl->parse( 'add.show_lev_2' );
}

foreach ( $contents['mods'][1] as $mod => $value )
{
    $xtpl->assign( 'MOD_VALUE', $mod );
    $xtpl->assign( 'LEV_CHECKED', ( ! empty( $value['checked'] ) ) ? "checked=\"checked\"" : "" );
    $xtpl->assign( 'CUSTOM_TITLE', $value['custom_title'] );
    $xtpl->parse( 'add.lev_loop' );
}

$xtpl->assign( 'POSITION0', $contents['position'][0] );
$xtpl->assign( 'POSITION1', $contents['position'][1] );
$xtpl->assign( 'POSITION2', $contents['position'][2] );
$xtpl->assign( 'SUBMIT', $contents['submit'] );
$xtpl->parse( 'add' );
$contents = $xtpl->text( 'add' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>