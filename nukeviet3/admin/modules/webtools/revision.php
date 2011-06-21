<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 9/9/2010, 6:38
 */

if ( ! defined( 'NV_IS_FILE_WEBTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['revision'];

$repository_url = "http://nuke-viet.googlecode.com/svn/trunk/";
define( 'NV3_DIRECTORY_SNV', '/trunk/nukeviet3/' );

function del_path_svn ( $path )
{
    return preg_replace( "/^" . nv_preg_quote( NV3_DIRECTORY_SNV ) . "(.*)$/", "\\1", $path );
}

function nv_mkdir_svn ( $dirname )
{
    global $lang_global, $global_config, $sys_info;
    $ftp_check_login = 0;
    $return = true;
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
    $cp = "";
    $e = explode( "/", $dirname );
    foreach ( $e as $p )
    {
        if ( ! empty( $p ) and ! is_dir( NV_ROOTDIR . '/' . $cp . $p ) )
        {
            if ( $ftp_check_login == 1 )
            {
                $res = ftp_mkdir( $conn_id, $cp . $p );
                if ( substr( $sys_info['os'], 0, 3 ) != 'WIN' ) ftp_chmod( $conn_id, 0777, $cp . $p );
            }
            elseif ( ! @mkdir( NV_ROOTDIR . '/' . $cp . $p, 0777 ) )
            {
                $cp = '';
                $return = false;
                break;
            }
        }
        $cp .= $p . '/';
    }
    if ( $ftp_check_login == 1 )
    {
        ftp_close( $conn_id );
    }
    return $return;
}

if ( $sys_info['allowed_set_time_limit'] )
{
    set_time_limit( 0 );
}

$vini = isset( $global_config['revision'] ) ? $global_config['revision'] : 0; // Phien ban truoc
if ( $vini < 893 )
{
    $contents = $lang_module['revision_nosuport'];
}
else
{
    $step = $nv_Request->get_int( 'step', 'get', 1 );
    $n = $nv_Request->get_int( 'n', 'get', 1 );
    $checkss = $nv_Request->get_string( 'checkss', 'get', '' );
    $nextstep = $step + 1;
    if ( $step == 1 )
    {
        require ( NV_ROOTDIR . '/includes/phpsvnclient/phpsvnclient.php' );
        $svn = new phpsvnclient();
        $svn->setRepository( $repository_url );
        
        $vend = $svn->getVersion();
        if ( $vend > $vini )
        {
            $nv_Request->set_Session( 'getVersion', $vend );
            $nv_Request->set_Session( 'getfile', 0 );
            $logs = $svn->getFileLogs( NV3_DIRECTORY_SNV, $vini, $vend );
            if ( ! empty( $logs ) )
            {
                $add_files = $del_files = $edit_files = array();
				
				$is_check_lang = false;
				if ( $global_config['update_revision_lang_mode'] != 1 )
				{
					$is_check_lang = true;
					
					$rule = implode ( "|", array_keys( $language_array ) );
					$check_rule = ( $global_config['update_revision_lang_mode'] == 2 )? $global_config['allow_adminlangs'] : $global_config['allow_sitelangs'];
					
					// Check is lang file rule
					define ( "NV_WCHECK_LADMIN_GLOBAL_ADMIN", "/^language\/(" . $rule . ")\\/admin_global.php$/" );
					define ( "NV_WCHECK_LADMIN_GLOBAL_SITE", "/^language\/(" . $rule . ")\\/global.php$/" );
					define ( "NV_WCHECK_LADMIN_INSTALL", "/^language\/(" . $rule . ")\\/install.php$/" );
					define ( "NV_WCHECK_LADMIN_MODULES", "/^language\/(" . $rule . ")\\/admin_([a-zA-Z0-9\-\_]+)\.php$/" );
					define ( "NV_WCHECK_LMODULES_SITE", "/^modules\/([a-z0-9\-]+)\\/language\/(" . $rule . ")\.php$/" );
					define ( "NV_WCHECK_LMODULES_ADMIN", "/^modules\/([a-z0-9\-]+)\\/language\/admin_(" . $rule . ")\.php$/" );
					define ( "NV_WCHECK_LJS_GLOBAL", "/^js\/language\/(" . $rule . ")\.js$/" );
					define ( "NV_WCHECK_LJS_JQUERYUI", "/^js\/language\/jquery\.ui\.datepicker\-(" . $rule . ")\.js$/" );
					define ( "NV_WCHECK_LJS_CKEDITOR", "/^admin\/editors\/ckeditor\/lang\/(" . $rule . ")\.php$/" );
					
					/**
					 * nv_check_is_lang_file()
					 * 
					 * @param mixed $file_path
					 * @return
					 */
					function nv_check_is_lang_file ( $file_path )
					{
						if ( preg_match( NV_WCHECK_LADMIN_GLOBAL_ADMIN, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LADMIN_GLOBAL_SITE, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LADMIN_INSTALL, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LADMIN_MODULES, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LMODULES_SITE, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LMODULES_ADMIN, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LJS_GLOBAL, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LJS_JQUERYUI, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						elseif ( preg_match( NV_WCHECK_LJS_CKEDITOR, $file_path, $match ) )
						{
							if ( ! in_array ( $match[1], $check_rule ) )
							{
								return true;
							}
						}
						else
						{
							return false;
						}
					}
				}

                foreach ( $logs as $key => $arr_log_i )
                {					
                    if ( isset( $arr_log_i['del_files'] ) )
                    {
                        $array_remove_add = $array_remove_edit = array();
                        $arr_temp = $arr_log_i['del_files'];
                        foreach ( $arr_temp as $str )
                        {
                            $str = del_path_svn( trim( $str ) );
							
							if ( $is_check_lang )
							{
								$check_ok = nv_check_is_lang_file ( $str );
								if ( $check_ok )
								{
									continue;
								}
							}

                            if ( in_array( $str, $add_files ) )
                            {
                                $array_remove_add[] = $str;
                            }
                            elseif ( in_array( $str, $edit_files ) )
                            {
                                $array_remove_edit[] = $str;
                            }
                            else
                            {
                                $del_files[] = $str;
                            }
                        }
                        $add_files = array_diff( $add_files, $array_remove_add );
                        $edit_files = array_diff( $edit_files, $array_remove_edit );
                    }
                    if ( isset( $arr_log_i['mod_files'] ) )
                    {
                        $arr_temp = $arr_log_i['mod_files'];
                        foreach ( $arr_temp as $str )
                        {
                            $str = del_path_svn( trim( $str ) );
							
							if ( $is_check_lang )
							{
								$check_ok = nv_check_is_lang_file ( $str );
								if ( $check_ok )
								{
									continue;
								}
							}

                            if ( ! in_array( $str, $edit_files ) and ! in_array( $str, $add_files ) )
                            {
                                $edit_files[] = $str;
                            }
                        }
                    }
                    
                    if ( isset( $arr_log_i['add_files'] ) )
                    {
                        $array_remove_del = array();
                        $arr_temp = $arr_log_i['add_files'];
                        foreach ( $arr_temp as $str )
                        {
                            $str = del_path_svn( trim( $str ) );
							
							if ( $is_check_lang )
							{
								$check_ok = nv_check_is_lang_file ( $str );
								if ( $check_ok )
								{
									continue;
								}
							}

                            if ( in_array( $str, $del_files ) )
                            {
                                $array_remove_del[] = $str;
                            }
                            $add_files[] = $str;
                        }
                        $del_files = array_diff( $del_files, $array_remove_del );
                    }
                }
				
				if ( empty ( $add_files ) and empty ( $del_files ) and empty ( $edit_files ) )
				{
					$contents = $lang_module['revision_nochange'];
				}
				else
				{
					asort( $add_files );
					asort( $del_files );
					asort( $edit_files );
					
					$svn_data_files = array( 'version' => $vend, 'add_files' => $add_files, 'del_files' => $del_files, 'edit_files' => $edit_files );
					
					file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log', serialize( $svn_data_files ), LOCK_EX );
					
					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) );
					exit();				
				}
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
    elseif ( $step == 2 and $checkss == md5( $step . $global_config['sitekey'] . session_id() ) )
    {
        if ( file_exists( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log' ) )
        {
            $cache = file_get_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log' );
            $svn_data_files = unserialize( $cache );
            $check_del_dir_update = true;
            if ( is_dir( NV_ROOTDIR . '/install/update' ) )
            {
                $del_error = nv_deletefile( NV_ROOTDIR . '/install/update', true );
                if ( empty( $del_error[0] ) )
                {
                    $contents = $del_error[1];
                    if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                    {
                        $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                    }
                    $check_del_dir_update = false;
                }
            }
            
            if ( $check_del_dir_update )
            {
                if ( ! nv_mkdir_svn( 'install/update' ) )
                {
                    $contents = sprintf( $lang_global['error_create_directories_failed'], 'install/update' );
                    if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                    {
                        $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                    }
                }
                else
                {
                    nv_mkdir_svn( 'install/update/new' );
                    nv_mkdir_svn( 'install/update/old' );
                    
                    @file_put_contents( NV_ROOTDIR . "/install/update/.htaccess", "deny from all", LOCK_EX );
                    @file_put_contents( NV_ROOTDIR . "/install/update/index.html", "", LOCK_EX );
                    @file_put_contents( NV_ROOTDIR . "/install/update/new/index.html", "", LOCK_EX );
                    @file_put_contents( NV_ROOTDIR . "/install/update/old/index.html", "", LOCK_EX );
                    
                    $contents = '<div id="listfile">';
                    $contents .= '<div style="text-align:center;color:red;">' . $lang_module['revision_list_file'] . '</div>';
                    $contents .= '<div style="overflow:auto;height:300px;width:100%">';
                    if ( ! empty( $svn_data_files['add_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_add_files'] . '</b><br />' . implode( "<br />", $svn_data_files['add_files'] );
                    if ( ! empty( $svn_data_files['edit_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_mod_files'] . '</b><br />' . implode( "<br />", $svn_data_files['edit_files'] );
                    if ( ! empty( $svn_data_files['del_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_del_files'] . '</b><br />' . implode( "<br />", $svn_data_files['del_files'] );
                    $contents .= '</div>';
                    $contents .= '</div><br /><br />';
                    $contents .= $lang_module['revision_msg_download'];
                    
                    $contents .= '<br /><br /><center><input style="margin-top:10px;font-size:15px" type="button" name="download_file" value="' . $lang_module['revision_download_files'] . '"/></center>';
                    $contents .= '<br /><br /><div id="message" style="display:none;text-align:center;color:red"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif" alt="" /></div>';
                    $contents .= '<script type="text/javascript">
		            	function nv_download_result(res)
						{
							var r_split = res.split("_");	
							if (r_split[0] != "OK") {
								$("#message").hide();	
								alert(r_split[1]);
		        			}
							else if (r_split[1] == "DOWNLOADFILE") {
						 		nv_ajax("get", "' . NV_BASE_ADMINURL . 'index.php", "' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '", "", "nv_download_result");
		        			}
							else if (r_split[1] == "DOWNLOADCOMPLETE"){
								parent.location="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=autoupdate";
		        			} 
		        			else{
		        				$("input[name=download_file]").removeAttr("disabled");
		        				$("#message").hide();
		        				alert("' . $lang_module['revision_download_error'] . '");
		        			}       			
						 }
		        		 $(function(){
		        		 	$("input[name=download_file]").click(function(){
		        		 		$(this).attr("disabled","disabled");
			        		 	$("#listfile").hide();
			        		 	$("#message").show();
						 		$("#step1").html("");
						 		nv_ajax("get", "' . NV_BASE_ADMINURL . 'index.php", "' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '", "", "nv_download_result");
							});
		        		 });
						</script><br /><br />';
                }
            }
        }
        else
        {
            $contents = $lang_module['revision_error_cache_file'];
        }
    } //elseif ( $step == 3 and $checkss == md5( $step . $global_config['sitekey'] . session_id() ) )
    elseif ( $step == 3 )
    {
        $error_download = array();
        $cache = file_get_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log' );
        $svn_data_files = unserialize( $cache );
        $download_files = array_merge( $svn_data_files['edit_files'], $svn_data_files['add_files'] );
        
        $vend = $nv_Request->get_int( 'getVersion', 'session', 0 );
        $getfile = $nv_Request->get_int( 'getfile', 'session', 0 );
        
        require ( NV_ROOTDIR . '/includes/phpsvnclient/phpsvnclient.php' );
        $svn = new phpsvnclient();
        $svn->setRepository( $repository_url );
        
        if ( $getfile < count( $download_files ) )
        {
            $file_name = $download_files[$getfile];
            $path = NV3_DIRECTORY_SNV . $file_name;
            
            // download new file
            $fileInfo = $svn->getDirectoryTree( $path, $vend, false );
            $dirname = 'install/update/new/' . del_path_svn( $path );
            if ( $fileInfo["type"] != "directory" )
            {
                $contents_f = $svn->getFile( $path, $vend );
                if ( $contents_f === false )
                {
                    $error_download[] = "error getFile: " . $path . "--->" . $vend;
                    $dirname = "";
                }
                else
                {
                    $filename = basename( $path );
                    $dirname = substr( $dirname, 0, - ( strlen( $filename ) + 1 ) );
                }
            }
            
            if ( ! empty( $dirname ) and ! is_dir( NV_ROOTDIR . '/' . $dirname ) )
            {
                if ( ! nv_mkdir_svn( $dirname ) )
                {
                    $contents = sprintf( $lang_global['error_create_directories_failed'], $dirname );
                    if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                    {
                        $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                    }
                    die( $contents );
                }
            }
            if ( ! empty( $filename ) and ! empty( $dirname ) )
            {
                file_put_contents( NV_ROOTDIR . '/' . $dirname . "/" . $filename, $contents_f, LOCK_EX );
                // download old file
                if ( in_array( $file_name, $svn_data_files['edit_files'] ) )
                {
                    $contents_f = $svn->getFile( $path, $vini );
                    if ( $contents_f === false )
                    {
                        $error_download[] = "error getFile: " . $path . "--->" . $vini;
                        $dirname = "";
                    }
                    else
                    {
                        $filename = basename( $path );
                        $dirname = 'install/update/old/' . del_path_svn( $path );
                        $dirname = substr( $dirname, 0, - ( strlen( $filename ) + 1 ) );
                        if ( ! empty( $dirname ) and ! is_dir( NV_ROOTDIR . '/' . $dirname ) )
                        {
                            if ( ! nv_mkdir_svn( $dirname ) )
                            {
                                $contents = sprintf( $lang_global['error_create_directories_failed'], $dirname );
                                if ( $sys_info['ftp_support'] and intval( $global_config['ftp_check_login'] ) != 1 )
                                {
                                    $contents .= '<br /><br />' . $lang_module['revision_config_ftp'];
                                }
                                die( $contents );
                            }
                        }
                        file_put_contents( NV_ROOTDIR . '/' . $dirname . "/" . $filename, $contents_f, LOCK_EX );
                    }
                }
            }
            if ( empty( $error_download ) )
            {
                $nv_Request->set_Session( 'getfile', $getfile + 1 );
                die( "OK_DOWNLOADFILE" );
            }
            else
            {
                die( implode( "<br />", $error_download ) );
            }
        }
        else
        {
            $path = NV3_DIRECTORY_SNV . "update_revision.php";
            $contents_f = $svn->getFile( $path, $vend );
            $contents_f = str_replace( "?>", "\n", $contents_f );
            $contents_f .= "\$update_info = array(
			    'revision' => array( 
			    	'from' => '" . $vini . "', 'to' => '" . $vend . "' 
				) 
			);\n";
            if ( ! empty( $svn_data_files['add_files'] ) ) $contents_f .= "\$add_files = array('" . implode( "',\n '", $svn_data_files['add_files'] ) . "');\n\n\n";
            if ( ! empty( $svn_data_files['edit_files'] ) ) $contents_f .= "\$edit_files = array('" . implode( "',\n '", $svn_data_files['edit_files'] ) . "');\n\n\n";
            if ( ! empty( $svn_data_files['del_files'] ) ) $contents_f .= "\$delete_files = array('" . implode( "',\n '", $svn_data_files['del_files'] ) . "');\n\n\n";
            $contents_f .= "\n?>";
            
            file_put_contents( NV_ROOTDIR . "/install/update/update.php", $contents_f, LOCK_EX );
            nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log' );
            
            die( "OK_DOWNLOADCOMPLETE" );
        }
    }
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>