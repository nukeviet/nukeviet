<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$title = $note = $module_file = "";

$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme' . md5( $global_config['sitekey'] . session_id() ) . '.zip';
if ( file_exists( $filename ) )
{
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    $zip = new PclZip( $filename );
    $ziplistContent = $zip->listContent();
    $overwrite = $nv_Request->get_string( 'overwrite', 'get', '' );
    $errorfile = '';
    if ( $overwrite != md5( $filename . $global_config['sitekey'] . session_id() ) )
    {
        foreach ( $ziplistContent as $array_file )
        {
            //check exist file on system
            if ( empty( $array_file['folder'] ) and file_exists( NV_ROOTDIR . '/themes/' . trim( $array_file['filename'] ) ) )
            {
                $errorfile .= '<span style="color:red">' . $array_file['filename'] . '</span><br />';
            }
        }
    }
    if ( ! empty( $errorfile ) )
    {
        echo '<div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif"/>' . $lang_module['autoinstall_package_processing'] . '</div>';
        echo '<strong>' . $lang_module['autoinstall_theme_error_warning_fileexist'] . '</strong>';
        echo '<hr /><div style="overflow:auto;height:200px;width:700px">' . $errorfile . '</div>';
        echo '<br><b>' . $lang_module['autoinstall_theme_error_warning_overwrite'] . '</b>';
        echo '<br><input style="margin-top:10px;font-size:15px" type="button" name="install_content_overwrite" value="' . $lang_module['autoinstall_theme_overwrite'] . '"/>';
        
        echo '<script type="text/javascript">
        		 $(function(){
        		 	$("input[name=install_content_overwrite]").click(function(){
        		 		if(confirm("' . $lang_module['autoinstall_theme_error_warning_overwrite'] . '")){
	        		 		$("#message").show();
					 		$("#step1").html("");
					 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=' . $op . '&overwrite=' . md5( $filename . $global_config['sitekey'] . session_id() ) . '",function(){
								$("#message").hide();
								});
        					}
					});
        		 });
			</script>';
    }
    else
    {
        $temp_extract_dir = NV_TEMP_DIR . '/' . md5( $filename . $global_config['sitekey'] . session_id() );
        if ( NV_ROOTDIR . '/' . $temp_extract_dir )
        {
            nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
        }
        
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
        if ( empty( $no_extract ) )
        {
            $error_create_folder = array();
            foreach ( $ziplistContent as $array_file )
            {
                if ( ! empty( $array_file['folder'] ) and ! file_exists( NV_ROOTDIR . '/themes/' . $array_file['filename'] ) )
                {
                    $cp = "";
                    $e = explode( "/", $array_file['filename'] );
                    foreach ( $e as $p )
                    {
                        if ( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
                        {
                            if ( ! ( $ftp_check_login == 1 and ftp_mkdir( $conn_id, 'themes/' . $cp . $p ) ) )
                            {
                                @mkdir( NV_ROOTDIR . '/themes/' . $cp . $p );
                            }
                            if ( ! is_dir( NV_ROOTDIR . '/themes/' . $cp . $p ) )
                            {
                                $error_create_folder[] = 'themes/' . $cp . $p;
                            }
                        }
                        $cp .= $p . '/';
                    }
                }
            }
            $error_create_folder = array_unique( $error_create_folder );
            if ( ! empty( $error_create_folder ) )
            {
                asort( $error_create_folder );
                echo "<b>" . $lang_module['autoinstall_theme_permission_folder'] . "</b>:<br> " . implode( "<br>", $error_create_folder );
            }
            else
            {
                $error_move_folder = array();
                foreach ( $ziplistContent as $array_file )
                {
                    if ( empty( $array_file['folder'] ) )
                    {
                        if ( file_exists( NV_ROOTDIR . '/themes/' . $array_file['filename'] ) )
                        {
                            if ( ! ( $ftp_check_login == 1 and ftp_delete( $conn_id, 'themes/' . $array_file['filename'] ) ) )
                            {
                                nv_deletefile( NV_ROOTDIR . '/themes/' . $array_file['filename'] );
                            }
                        }
                        if ( ! ( $ftp_check_login == 1 and ftp_rename( $conn_id, $temp_extract_dir . '/' . $array_file['filename'], 'themes/' . $array_file['filename'] ) ) )
                        {
                            @rename( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'], NV_ROOTDIR . '/themes/' . $array_file['filename'] );
                        }
                        if ( file_exists( NV_ROOTDIR . '/' . $temp_extract_dir . '/' . $array_file['filename'] ) )
                        {
                            $error_move_folder[] = $array_file['filename'];
                        }
                    }
                }
                if ( empty( $error_move_folder ) )
                {
                    nv_deletefile( $filename );
                    nv_deletefile( NV_ROOTDIR . '/' . $temp_extract_dir, true );
                    $theme = substr( $ziplistContent[0]['filename'], 0, - 1 );
                    $nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=setuplayout&selectthemes=' . $theme;
                    echo "<br /><b>" . $lang_module['autoinstall_theme_unzip_success'] . "</b><br />";
                    echo "<br /><br /><center><a href=\"" . $nv_redirect . "\">" . $lang_module['autoinstall_module_unzip_setuppage'] . "</a></center>";
                    echo '<script type="text/javascript">
							setTimeout("redirect_page()",5000);
							function redirect_page()
							{
								parent.location="' . $nv_redirect . '";
							}
						</script>';
                }
                else
                {
                    asort( $error_move_folder );
                    echo "<b>" . $lang_module['autoinstall_theme_error_movefile'] . ":</b> <BR>" . implode( "<br>", $error_move_folder );
                }
            }
        }
        else
        {
            echo $lang_module['autoinstall_theme_cantunzip'];
            echo '<div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif"/>' . $lang_module['autoinstall_package_processing'] . '</div>';
            echo '<input style="margin-top:10px;font-size:15px" type="button" name="checkfile" value="' . $lang_module['autoinstall_theme_checkfile'] . '"/>';
            echo '<script type="text/javascript">
			 $(function(){
			 	$("input[name=checkfile]").click(function(){
			 		$("#message").show();
			 		$("#step1").html("");
			 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=install_check",function(){
						$("#message").hide();
					});
				});
			 });
			</script>';
        }
    
    }
}

?>