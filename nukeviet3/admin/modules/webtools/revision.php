<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['revision'];

function del_path_svn ( $subject )
{
    return str_replace( '/trunk/nukeviet3/', '', $subject );
}

if ( ! isset( $global_config['revision'] ) )
{
    //Update Site From SVN
    if ( nv_version_compare( $global_config['version'], "3.0.05" ) < 0 )
    {
        $contents = $lang_module['revision_no_support'];
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit();
    }
    elseif ( $global_config['version'] == "3.0.05" )
    {
        $global_config['revision'] = 2;
    }
    elseif ( $global_config['version'] == "3.0.06" )
    {
        $global_config['revision'] = 52;
    }
    elseif ( $global_config['version'] == "3.0.07" )
    {
        $global_config['revision'] = 75;
    }
    elseif ( $global_config['version'] == "3.0.08" )
    {
        $global_config['revision'] = 140;
    }
    elseif ( $global_config['version'] == "3.0.09" )
    {
        $global_config['revision'] = 211; //NUKEVIET 3 RC1
    }
    elseif ( $global_config['version'] == "3.0.10" )
    {
        $global_config['revision'] = 302; //NUKEVIET 3 RC2
    }
    elseif ( $global_config['version'] == "3.0.11" )
    {
        $global_config['revision'] = 348; //NUKEVIET 3 RC3
    }
    elseif ( $global_config['version'] == "3.0.12" )
    {
        $global_config['revision'] = 415;
    }
    $db->sql_query( "REPLACE INTO `" . $db_config['prefix'] . "_config` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '" . $global_config['revision'] . "')" );
    nv_save_file_config_global();
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
    exit();
}
$vini = $global_config['revision']; // Phien ban truoc
$vend = $nv_Request->get_int( 'getVersion', 'session', - 1 );

if ( $sys_info['allowed_set_time_limit'] )
{
    set_time_limit( 0 );
}
require ( NV_ROOTDIR . '/includes/phpsvnclient/phpsvnclient.php' );
$svn = new phpsvnclient();
$svn->setRepository( "http://nuke-viet.googlecode.com/svn" );

$step = ( isset( $_GET['step'] ) ) ? intval( $_GET['step'] ) : 1;
$n = ( isset( $_GET['n'] ) ) ? intval( $_GET['n'] ) : 0;

if ( $step == 1 )
{
    $vend = $svn->getVersion();
    if ( $vend > $vini )
    {
        $nv_Request->set_Session( 'getVersion', $vend );
        $logs = $svn->getRepositoryLogs( $vini, $vend );
        if ( ! empty( $logs ) )
        {
            $add_files = $del_files = $mod_files = array();
            foreach ( $logs as $key => $arr_log_i )
            {
                if ( isset( $arr_log_i['del_files'] ) )
                {
                    $array_remove_add = $array_remove_edit = array();
                    $arr_temp = $arr_log_i['del_files'];
                    foreach ( $arr_temp as $str )
                    {
                        $str = trim( $str );
                        if ( in_array( $str, $add_files ) )
                        {
                            $array_remove_add[] = $str;
                        }
                        elseif ( in_array( $str, $mod_files ) )
                        {
                            $array_remove_edit[] = $str;
                        }
                        else
                        {
                            $del_files[] = $str;
                        }
                    }
                    $add_files = array_diff( $add_files, $array_remove_add );
                    $mod_files = array_diff( $mod_files, $array_remove_edit );
                }
                if ( isset( $arr_log_i['mod_files'] ) )
                {
                    $arr_temp = $arr_log_i['mod_files'];
                    foreach ( $arr_temp as $str )
                    {
                        $str = trim( $str );
                        if ( ! in_array( $str, $mod_files ) and ! in_array( $str, $add_files ) )
                        {
                            $mod_files[] = $str;
                        }
                    }
                }
                
                if ( isset( $arr_log_i['add_files'] ) )
                {
                    $array_remove_del = array();
                    $arr_temp = $arr_log_i['add_files'];
                    foreach ( $arr_temp as $str )
                    {
                        $str = trim( $str );
                        if ( in_array( $str, $del_files ) )
                        {
                            $array_remove_del[] = $str;
                        }
                        $add_files[] = $str;
                    }
                    $del_files = array_diff( $del_files, $array_remove_del );
                }
            }
            $dow_files = array_merge( $add_files, $mod_files );
            file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_dow_files.log', serialize( $dow_files ), LOCK_EX );
            file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_add_files.log', serialize( $add_files ), LOCK_EX );
            file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_mod_files.log', serialize( $mod_files ), LOCK_EX );
            file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_del_files.log', serialize( $del_files ), LOCK_EX );
            
            $del_files = array_map( 'del_path_svn', $del_files );
            $mod_files = array_map( 'del_path_svn', $mod_files );
            $add_files = array_map( 'del_path_svn', $add_files );
            
            $list_del_files = implode( "<br>", $del_files );
            $list_mod_files = implode( "<br>", $mod_files );
            $list_add_files = implode( "<br>", $add_files );
            $contents = "<a target=\"_blank\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=2\">Update r" . $vini . " -> r" . $vend . "</a>";
            $contents .= "<br><br><b>Add files:</b><br>" . $list_add_files . "<br><br><b>Edit files:</b><br>" . $list_mod_files . "<br><br><b>Del files:</b><br>" . $list_del_files;
        }
        else
        {
            $contents = $lang_module['revision_nochange'];
        }
    }
    elseif ( $vend == $vini )
    {
        $contents = $lang_module['revision_nochange'];
    }
    else
    {
        $contents = $lang_module['revision_error'];
    }
}
elseif ( $step == 2 and $vend > 0 )
{
    $contents = $filename = "";
    
    $temp = file_get_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_dow_files.log' );
    $mod_files = unserialize( $temp );
    $path = $mod_files[$n];
    
    $fileInfo = $svn->getDirectoryTree( $path, $vend, false );
    if ( $fileInfo["type"] == "directory" )
    {
        $dirname = str_replace( "/trunk/nukeviet3/", "tmp/r" . $vini . "_" . $vend . "/", $path );
    }
    else
    {
        $contents_f = $svn->getFile( $path, $vend );
        if ( $contents_f === false )
        {
            $contents = "error getFile: " . $path . "--->" . $vend;
            $dirname = "";
        }
        else
        {
            $dirname = str_replace( "/trunk/nukeviet3/", NV_TEMP_DIR . "/r" . $vini . "_" . $vend . "/", dirname( $path ) );
            $filename = basename( $path );
        }
    }
    
    if ( ! empty( $dirname ) and ! is_dir( NV_ROOTDIR . '/' . $dirname ) )
    {
        $cp = "";
        $e = explode( "/", $dirname );
        foreach ( $e as $p )
        {
            if ( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
            {
                if ( ! @mkdir( NV_ROOTDIR . '/' . $cp . $p, 0777 ) )
                {
                    $cp = '';
                    break;
                }
            }
            $cp .= $p . '/';
        }
    }
    if ( ! empty( $filename ) and ! empty( $dirname ) )
    {
        file_put_contents( NV_ROOTDIR . '/' . $dirname . "/" . $filename, $contents_f, FILE_APPEND );
    }
    
    if ( empty( $contents ) )
    {
        $n ++;
        if ( $n < count( $mod_files ) )
        {
            $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=" . $step . "&n=" . $n;
            $contents = "<meta http-equiv=\"refresh\" content=\"0;url=" . $nv_redirect . "\" />";
            $contents .= "Chuan bi thuc hien file: " . $mod_files[$n];
            die( $contents );
        }
        else
        {
            $contents .= "<br><br><br><a  href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=3\"> Step 3 </a>";
        }
    }
}
elseif ( $step == 3 and $vend > 0 )
{
    
    $temp = file_get_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_del_files.log' );
    $del_files = unserialize( $temp );

}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>