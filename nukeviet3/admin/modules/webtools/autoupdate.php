<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );
if ( $sys_info['allowed_set_time_limit'] )
{
    set_time_limit( 0 );
}
$nv_sites_update = array(  //
    'update.nukeviet.vn', //
'update2.nukeviet.vn', //
'update.nukeviet.info', //
'update2.nukeviet.info' 
);

$temp_extract_dir = 'install/update';

$ftp_check_login = 0;
if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) == 1 )
{
    $ftp_server = nv_unhtmlspecialchars( $global_config['ftp_server'] );
    $ftp_port = intval( $global_config['ftp_port'] );
    $ftp_user_name = nv_unhtmlspecialchars( $global_config['ftp_user_name'] );
    $ftp_user_pass = nv_unhtmlspecialchars( $global_config['ftp_user_pass'] );
    $ftp_path = nv_unhtmlspecialchars( $global_config['ftp_path'] );
    // set up basic connection
    $conn_id = ftp_connect( $ftp_server, $ftp_port );
    // login with username and password
    $login_result = ftp_login( $conn_id, $ftp_user_name, $ftp_user_pass );
    if ( ( ! $conn_id ) || ( ! $login_result ) )
    {
        $ftp_check_login = 3;
    }
    elseif ( ftp_chdir( $conn_id, $ftp_path ) )
    {
        $ftp_check_login = 1;
    }
    else
    {
        $ftp_check_login = 2;
    }
}

$page_title = "autoupdate";
$revision = isset( $global_config['revision'] ) ? intval( $global_config['revision'] ) : 0;
$version = $global_config['version'];

$step = ( isset( $_GET['step'] ) ) ? intval( $_GET['step'] ) : 0;
$checkss = ( isset( $_GET['checkss'] ) ) ? trim( $_GET['checkss'] ) : '';
if ( empty( $step ) )
{
    if ( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) )
    {
        $step = 3;
    }
    else
    {
        $step = 1;
    }
}

$nextstep = $step + 1;

if ( $step == 1 )
{
    $filedownload = "";
    $domain_update = $global_config['site_url'];
    
    srand( ( float )microtime() * 10000000 );
    $rand = array_rand( $nv_sites_update );
    $nv_site = $nv_sites_update[$rand];
    
    $contents = '<center><br /><br /><br /><b>' . $lang_module['autoupdate_get_error'] . '</b><br /></center>';
    
    require_once NV_ROOTDIR . '/includes/class/geturl.class.php';
    $getContent = new UrlGetContents( $global_config );
    $xml = $getContent->get( "http://" . $nv_site . "/autoupdate.php?revision=" . $revision . "&version=" . $version . "&lang=" . NV_LANG_INTERFACE );
    
    if ( ! empty( $xml ) )
    {
        $new_version = simplexml_load_string( $xml );
        if ( $new_version !== false )
        {
            $update = ( int )$new_version->update;
            $contents = ( string )$new_version->note;
            if ( $update )
            {
                $filedownload = ( string )$new_version->filedownload;
                $nv_Request->set_Session( 'autoupdate_filedownload', $filedownload );
                
                $lang_waiting = sprintf( $lang_module['autoupdate_download_waiting'], $filedownload, $filedownload );
                
                $contents .= '<div id="message" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/><br /><br />' . $lang_waiting . '</div>';
                $contents .= '<div id="step1" ><center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate_download'] . '"/><center></div>';
                $contents .= '<script type="text/javascript">
				        		 $(function(){
				        		 	$("input[name=install_content_overwrite]").click(function(){
				        		 		$("#message").show();
								 		$("#step1").html("");
								 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '",function(){
											$("#message").hide();
											});
									});
				        		 });
							</script>';
            }
        }
    
    }
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $step == 2 and md5( $step . $global_config['sitekey'] . session_id() ) )
{
    $filedownload = $nv_Request->get_string( 'autoupdate_filedownload', 'session', '' );
    if ( ! empty( $filedownload ) )
    {
        //downlod file
        require_once NV_ROOTDIR . '/includes/class/upload.class.php';
        $allow_files_type = array( 
            'archives' 
        );
        $upload = new upload( $allow_files_type, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT );
        $upload_info = $upload->save_urlfile( $filedownload, NV_ROOTDIR . '/' . NV_TEMP_DIR, false );
        if ( ! empty( $upload_info['error'] ) )
        {
            $contents = 'error filedownload: ' . $filedownload;
        }
        else
        {
            //extract Zip file
            require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
            $zip = new PclZip( $upload_info['name'] );
            $status = $zip->properties();
            if ( $status['status'] == 'ok' )
            {
                $ziplistContent = $zip->listContent();
                
                if ( NV_ROOTDIR . '/' . $temp_extract_dir )
                {
                    nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
                }
                
                if ( $ftp_check_login == 1 )
                {
                    ftp_mkdir( $conn_id, $temp_extract_dir );
                    ftp_chmod( $conn_id, 0777, $temp_extract_dir );
                    foreach ( $ziplistContent as $array_file )
                    {
                        if ( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
                        {
                            $cp = "";
                            $e = explode( "/", $array_file['filename'] );
                            foreach ( $e as $p )
                            {
                                if ( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $cp . $p ) )
                                {
                                    ftp_mkdir( $conn_id, $temp_extract_dir . '/' . $cp . $p );
                                    ftp_chmod( $conn_id, 0777, $temp_extract_dir . '/' . $cp . $p );
                                }
                                $cp .= $p . '/';
                            }
                        }
                    }
                }
                
                $no_extract = array();
                $extract = $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR . '/' . $temp_extract_dir );
                foreach ( $extract as $extract_i )
                {
                    $filename_i = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $extract_i['filename'] ) );
                    if ( $extract_i['status'] != 'ok' and $extract_i['status'] != 'already_a_directory' )
                    {
                        $no_extract[] = $filename_i;
                    }
                }
                nv_deletefile( $upload_info['name'] );
                if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' ) )
                {
                    nv_copyfile( NV_ROOTDIR . '/language/index.html', NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' );
                }
                if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' ) )
                {
                    nv_copyfile( NV_ROOTDIR . '/language/.htaccess', NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' );
                }
                if ( empty( $no_extract ) )
                {
                    $contents = '<br />';
                    $contents .= '<div id="message_31" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/></div>';
                    $contents .= '<div id="step_31" >' . $lang_module['autoupdate_download_complete'];
                    $contents .= '<center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate_check_file'] . ' "/><center></div>';
                    $contents .= '<script type="text/javascript">
				        		 $(function(){
				        		 	$("input[name=install_content_overwrite]").click(function(){
				        		 		$("#message_31").show();
								 		$("#step_31").html("");
								 		$("#step_31").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '",function(){
											$("#message_31").hide();
											});
									});
				        		 });
							</script>';
                }
                else
                {
                    $contents = '<div id="message" style="text-align:center;color:red"><br /><br />' . sprintf( $lang_module['autoupdate_download_error'], $filedownload, $filedownload ) . '</div>';
                }
            }
            else
            {
                $contents = '<div id="message" style="text-align:center;color:red"><br /><br />' . $lang_module['autoupdate_invalidfile'] . '</div>';
            }
        }
    }
    else
    {
        $contents = 'error filedownload';
    }
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $step == 3 and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) )
{
    if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' ) )
    {
        nv_copyfile( NV_ROOTDIR . '/language/index.html', NV_ROOTDIR . '/' . $temp_extract_dir . '/index.html' );
    }
    if ( ! file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' ) )
    {
        nv_copyfile( NV_ROOTDIR . '/language/.htaccess', NV_ROOTDIR . '/' . $temp_extract_dir . '/.htaccess' );
    }
    
    define( 'NV_AUTOUPDATE', true );
    $update_info = array();
    $add_files = array();
    $edit_files = array();
    $delete_files = array();
    require_once ( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' );
    if ( $checkss != md5( $step . $global_config['sitekey'] . session_id() ) )
    {
        $contents = '<br />';
        $contents .= '<div id="message_31" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/></div>';
        $contents .= '<div id="step_31" >' . $lang_module['autoupdate_form_upload'];
        $contents .= '<center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate_check_file'] . '"/><center></div>';
        $contents .= '<script type="text/javascript">
        		 $(function(){
        		 	$("input[name=install_content_overwrite]").click(function(){
        		 		$("#message_31").show();
				 		$("#step_31").html("");
				 		$("#step_31").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op . '&step=' . $step . '&checkss=' . md5( $step . $global_config['sitekey'] . session_id() ) . '",function(){
							$("#message_31").hide();
							});
					});
        		 });
			</script>';
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_admin_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }
    else
    {
        $user_edit_file = array();
        $check_files = array_merge( $edit_files, $delete_files );
        if ( ! empty( $check_files ) )
        {
            asort( $check_files );
            foreach ( $check_files as $file )
            {
                $cur_file = NV_ROOTDIR . '/' . $file;
                $old_file = NV_ROOTDIR . '/' . $temp_extract_dir . '/old/' . $file;
                if ( ! file_exists( $cur_file ) or ! file_exists( $old_file ) )
                {
                    $user_edit_file[] = $file;
                }
                elseif ( md5_file( $cur_file ) != md5_file( $old_file ) )
                {
                    $user_edit_file[] = $file;
                }
            }
        }
        if ( ! empty( $user_edit_file ) )
        {
            $contents .= '<br /><br /><b>' . $lang_module['autoupdate_change'] . ':</b>';
            $contents .= '<br /> ' . implode( "<br />", $user_edit_file );
            $contents .= '<br /><br />' . $lang_module['autoupdate_overwrite'];
        }
        else
        {
            $contents .= '<br />' . $lang_module['autoupdate_click_update'];
        }
        $contents .= '<br /><br />' . $lang_module['autoupdate_backupfile'] . ': <br /><br /><b>' . NV_LOGS_DIR . '/data_logs/backup_update_' . date( 'Y_m_d' ) . '_' . md5( $global_config['sitekey'] . session_id() ) . '.zip</b>';
        $contents .= '<br /><br />';
        $contents .= '<div id="message_32" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif"/></div>';
        $contents .= '<br /><div id="step_32" ><center><br /><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoupdate'] . '"/><center></div>';
        $contents .= '<script type="text/javascript">
        		 $(function(){
        		 	$("input[name=install_content_overwrite]").click(function(){
        		 		if(confirm("' . $lang_module['autoupdate_confirm'] . '")){
	        		 		$("#message_32").show();
					 		$("#step_32").html("");
					 		$("#step_32").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '",function(){
								$("#message_32").hide();
								});
        					}
					});
        		 });
			</script>';
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo $contents;
        include ( NV_ROOTDIR . "/includes/footer.php" );
    }
}
elseif ( $step == 4 and md5( $step . $global_config['sitekey'] . session_id() ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/old/' ) and file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' ) )
{
    define( 'NV_AUTOUPDATE', true );
    $update_info = array();
    $add_files = array();
    $edit_files = array();
    $delete_files = array();
    require_once ( NV_ROOTDIR . '/' . $temp_extract_dir . '/update.php' );
    
    // backup file
    $error_backup = false;
    $backup_files = array_merge( $edit_files, $delete_files );
    if ( ! empty( $backup_files ) )
    {
        $zip_file_backup = array();
        foreach ( $backup_files as $file_i )
        {
            if ( is_file( NV_ROOTDIR . '/' . $file_i ) )
            {
                $zip_file_backup[] = NV_ROOTDIR . '/' . $file_i;
            }
        }
        if ( ! empty( $zip_file_backup ) )
        {
            $file_src = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/backup_update_' . date( 'Y_m_d' ) . '_' . md5( $global_config['sitekey'] . session_id() ) . '.zip';
            
            require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
            $zip = new PclZip( $file_src );
            $return = $zip->add( $zip_file_backup, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
            if ( empty( $return ) )
            {
                $check_backup = true;
            }
        }
    }
    if ( $error_backup )
    {
        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_backupfile_error'] . ':' . NV_LOGS_DIR . '/data_logs</b>';
    }
    else
    {
        $move_files = array_merge( $add_files, $edit_files );
        if ( ! empty( $move_files ) )
        {
            // create new folder
            $error_create_folder = array();
            foreach ( $move_files as $file_i )
            {
                $cp = "";
                $e = explode( "/", $file_i );
                foreach ( $e as $p )
                {
                    if ( ! empty( $p ) and is_dir( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $cp . $p ) and ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
                    {
                        if ( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, $cp . $p ) ) )
                        {
                            @mkdir( NV_ROOTDIR . '/' . $cp . $p );
                        }
                        if ( ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
                        {
                            $error_create_folder[] = $cp . $p;
                        }
                    }
                    $cp .= $p . '/';
                }
            }
            if ( ! empty( $error_create_folder ) )
            {
                $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_create_folder'] . ':</b>';
                $contents .= '<br /> ' . implode( "<br />", $error_create_folder );
            }
            else
            {
                //Move file";
                $error_move_folder = array();
                foreach ( $move_files as $file_i )
                {
                    if ( is_file( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i ) )
                    {
                        if ( file_exists( NV_ROOTDIR . '/' . $file_i ) )
                        {
                            if ( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, $file_i ) ) )
                            {
                                nv_deletefile( NV_ROOTDIR . '/' . $file_i );
                            }
                        }
                        if ( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/new/' . $file_i, 'themes/' . $file_i ) ) )
                        {
                            @rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i, NV_ROOTDIR . '/' . $file_i );
                        }
                        if ( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/new/' . $file_i ) )
                        {
                            $error_move_folder[] = $file_i;
                        }
                    }
                }
                if ( ! empty( $error_move_folder ) )
                {
                    $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_move_file'] . ':</b>';
                    $contents .= '<br /> ' . implode( "<br />", $error_move_folder );
                }
                else
                {
                    global $error_contents;
                    $error_contents = array();
                    
                    $update_data = true;
                    if ( nv_function_exists( 'nv_func_update_data' ) )
                    {
                        $update_data = nv_func_update_data();
                    }
                    if ( $update_data )
                    {
                        if ( isset( $update_info['version']['to'] ) )
                        {
                            $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_config` SET `config_value` = " . $db->dbescape_string( $update_info['version']['to'] ) . " WHERE `lang` = 'sys' AND `module` = 'global' AND `config_name` = 'version'" );
                        }
                        if ( isset( $update_info['revision']['to'] ) )
                        {
                            $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_config` SET `config_value` = '" . intval( $update_info['revision']['to'] ) . "' WHERE `lang` = 'sys' AND `module` = 'global' AND `config_name` = 'revision'" );
                        }
                        nv_save_file_config_global();
                        $del = nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
                        
                        if ( $del[0] == 1 )
                        {
                            $msg = $lang_module['autoupdate_complete'];
                        }
                        else
                        {
                            $msg = $lang_module['autoupdate_complete_error_del_file'];
                        }
                        $contents .= '<br /><br /><b>' . $msg . '</b>';
                    }
                    else
                    {
                        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_complete_file'] . '.</b>';
                        $contents .= '<br /><br /><b>' . $lang_module['autoupdate_error_data'] . ':</b><br />' . implode( "<br />", $error_contents );
                    }
                }
            }
        }
    }
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $step > 2 )
{
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo '<div style="text-align:center;color:red">' . $lang_module['autoupdate_error_dir_update'] . '</div>';
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name );
    exit();
}

?>