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

if ( $sys_info['allowed_set_time_limit'] )
{
    set_time_limit( 0 );
}

$vini = isset( $global_config['revision'] ) ? $global_config['revision'] : 0; // Phien ban truoc
if ( $vini < 750 )
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
        $svn->setRepository( "http://nuke-viet.googlecode.com/svn" );
        
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
                $svn_data_files = array( 
                    'add_files' => $add_files, 'del_files' => $del_files, 'mod_files' => $mod_files 
                );
                file_put_contents( NV_ROOTDIR . '/' . NV_DATADIR . '/svn_data_files_' . md5( $global_config['revision'] . $global_config['sitekey'] ) . '.log', serialize( $svn_data_files ), LOCK_EX );
                
                Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) );
                exit();
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
            
            $contents = '<div style="text-align:center;color:red;">' . $lang_module['revision_list_file'] . '</div>';
            $contents .= '<div style="overflow:auto;height:300px;width:100%">';
            if ( ! empty( $svn_data_files['add_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_add_files'] . '</b><br />' . implode( "<br />", $svn_data_files['add_files'] );
            if ( ! empty( $svn_data_files['mod_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_mod_files'] . '</b><br />' . implode( "<br />", $svn_data_files['mod_files'] );
            if ( ! empty( $svn_data_files['del_files'] ) ) $contents .= '<br /><br /><b>' . $lang_module['revision_del_files'] . '</b><br />' . implode( "<br />", $svn_data_files['del_files'] );
            $contents .= '</div><br /><br />';
            $contents .= $lang_module['revision_msg_download'];
            
            $contents .= '<br /><br /><center><input style="margin-top:10px;font-size:15px" type="button" name="download_file" value="' . $lang_module['revision_download_files'] . '"/></center>';
            $contents .= '<br /><br /><div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif" alt=""/></div>';
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
						alert(r_split[1]);
        			} 
        			else{
        				alert("' . $lang_module['revision_download_error'] . '");
        			}       			
				}
            	nv_download_result
        		 $(function(){
        		 	$("input[name=download_file]").click(function(){
        		 		$("#message").show();
				 		$("#step1").html("");
				 		nv_ajax("get", "' . NV_BASE_ADMINURL . 'index.php", "' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&step=' . $nextstep . '&checkss=' . md5( $nextstep . $global_config['sitekey'] . session_id() ) . '", "", "nv_download_result");
					});
        		 });
			</script><br /><br />';
        }
        else
        {
            $contents = $lang_module['revision_error_cache_file'];
        }
    }
    elseif ( $step == 3 and $checkss == md5( $step . $global_config['sitekey'] . session_id() ) )
    {
        die( "OK_DOWNLOADFILE" );
        die( "OK_DOWNLOADCOMPLETE" );
    }
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

if ( $step == 1 )
{
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
            
            $list_del_files = implode( "<br />", $del_files );
            $list_mod_files = implode( "<br />", $mod_files );
            $list_add_files = implode( "<br />", $add_files );
            $contents = "<a target=\"_blank\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=2\">Update r" . $vini . " -> r" . $vend . "</a>";
            $contents .= "<br /><br /><b>Add files:</b><br />" . $list_add_files . "<br /><br /><b>Edit files:</b><br />" . $list_mod_files . "<br /><br /><b>Del files:</b><br />" . $list_del_files;
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
            $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=" . $step . "&amp;n=" . $n;
            $contents = "<meta http-equiv=\"refresh\" content=\"0;url=" . $nv_redirect . "\" />";
            $contents .= "Chuan bi thuc hien file: " . $mod_files[$n];
            die( $contents );
        }
        else
        {
            $contents .= "<br /><br /><br /><a  href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=3\"> Step 3 </a>";
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