<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );
$contents = "";
$error = "";
$page_title = $lang_module['ftp_config'];

$array_config = array();

$array_config['ftp_server'] = filter_text_input( 'ftp_server', 'post', $global_config['ftp_server'], 1, 255 );
$array_config['ftp_port'] = filter_text_input( 'ftp_port', 'post', $global_config['ftp_port'], 1, 255 );
$array_config['ftp_user_name'] = filter_text_input( 'ftp_user_name', 'post', $global_config['ftp_user_name'], 1, 255 );
$array_config['ftp_user_pass'] = filter_text_input( 'ftp_user_pass', 'post', $global_config['ftp_user_pass'], 1, 255 );
$array_config['ftp_path'] = filter_text_input( 'ftp_path', 'post', $global_config['ftp_path'], 1, 255 );
$array_config['ftp_check_login'] = $global_config['ftp_check_login'];
if ( $nv_Request->isset_request( 'ftp_server', 'post' ) )
{
    $array_config['ftp_check_login'] = 0;
    if ( ! empty( $array_config['ftp_server'] ) and ! empty( $array_config['ftp_user_name'] ) and ! empty( $array_config['ftp_user_pass'] ) )
    {
        $ftp_server = nv_unhtmlspecialchars( $array_config['ftp_server'] );
        $ftp_port = intval( $array_config['ftp_port'] );
        $ftp_user_name = nv_unhtmlspecialchars( $array_config['ftp_user_name'] );
        $ftp_user_pass = nv_unhtmlspecialchars( $array_config['ftp_user_pass'] );
        $ftp_path = nv_unhtmlspecialchars( $array_config['ftp_path'] );
        
        // set up basic connection        
        $conn_id = ftp_connect( $ftp_server, $ftp_port );
        // login with username and password
        $login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
        if ( ( ! $conn_id ) || ( ! $login_result ) )
        {
            $array_config['ftp_check_login'] = 3;
            $error = $lang_global['ftp_error_account'];
        }
        elseif ( ftp_chdir( $conn_id, $ftp_path ) )
        {
            $check_files = array( 
                NV_CACHEDIR, NV_DATADIR, "images", "includes", "index.php", "js", "language", NV_LOGS_DIR, "mainfile.php", "modules", NV_SESSION_SAVE_PATH, "themes", NV_TEMP_DIR, NV_UPLOADS_DIR 
            );
            $list_files = ftp_nlist( $conn_id, "." );
            $a = 0;
            foreach ( $list_files as $filename )
            {
                if ( in_array( $filename, $check_files ) )
                {
                    $a ++;
                }
            }
            if ( $a == count( $check_files ) )
            {
                $array_config['ftp_check_login'] = 1;
            }
            else
            {
                $array_config['ftp_check_login'] = 2;
                $error = $lang_global['ftp_error_path'];
            }
        }
        else
        {
            $array_config['ftp_check_login'] = 2;
            $error = $lang_global['ftp_error_path'];
        }
        // close this connection
        ftp_close( $conn_id );
    }
    
    foreach ( $array_config as $config_name => $config_value )
    {
        $db->sql_query( "UPDATE `" . NV_CONFIG_GLOBALTABLE . "` 
        SET `config_value`=" . $db->dbescape_string( $config_value ) . " 
        WHERE `config_name` = " . $db->dbescape_string( $config_name ) . " 
        AND `lang` = 'sys' AND `module`='global' 
        LIMIT 1" );
    }
    nv_save_file_config_global();
    if ( empty( $error ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
        exit();
    }
}

$xtpl = new XTemplate( "ftp.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file . "" );
if ( ! empty( $error ) )
{
    $errorcontent = "<div class=\"quote\" style=\"width:780px;\">\n";
    $errorcontent .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $errorcontent .= "</div>\n";
    $errorcontent .= "<div class=\"clear\"></div>\n";
    
    $xtpl->assign( 'ERRORCONTENT', $errorcontent );
    $xtpl->parse( 'ftp.error' );
}
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'VALUE', $array_config );
$xtpl->parse( 'ftp' );
$contents .= $xtpl->text( 'ftp' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>