<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
$title = $note = $module_file = "";

$xauto = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . md5( session_id() ) . '.list';
$filename = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'auto_' . md5( session_id() ) . '.zip';
$xfolder = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'folder' . md5( session_id() ) . '.list';

$file = file( $xauto );
$errorfile = '';
$errorfolder = '';
$allowfolder = array( 
    'themes', 'modules', 'uploads' 
);

foreach ( $file as $line )
{
    $folder = explode( '/', $line );
    //check valid folder structure nukeviet (modules, themes, uploads)
    if ( ! in_array( $folder[0], $allowfolder ) )
    {
        $errorfolder .= '<span style="color:red">' . $line . '</span><br />';
    }
    //check exist file on system
    if ( file_exists( NV_ROOTDIR . '/' . trim( $line ) ) && ! is_dir( NV_ROOTDIR . '/' . trim( $line ) ) )
    {
        $errorfile .= '<span style="color:red">' . $line . '</span><br />';
    }
}
if ( $errorfile || $errorfolder )
{
    echo '<div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif"/>' . $lang_module['autoinstall_package_processing'] . '</div>';
    echo '<div id="install_content"><h4>' . $lang_module['autoinstall_module_error_warning_install'] . '</h4><input style="margin-top:10px;font-size:15px" type="button" name="checkfile" value="' . $lang_module['autoinstall_module_checkfile'] . '"/><br /><br />';
    if ( $errorfile )
    {
        echo '<strong>' . $lang_module['autoinstall_module_error_warning_fileexist'] . '</strong><hr /><div style="overflow:auto;height:200px;width:700px">' . $errorfile . '</div>';
    }
    if ( $errorfolder )
    {
        echo '<strong>' . $lang_module['autoinstall_module_error_warning_invalidfolder'] . '</strong><hr /><div style="overflow:auto;height:200px;width:700px">' . $errorfolder . '</div>';
    }
    echo '</div><script type="text/javascript">
		 $(function(){
		 	$("input[name=checkfile]").click(function(){
		 		$("#message").show();
		 		$("#step1").html("");
		 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=install_check",function(){
					$("#message").hide();
				});
			});
		 });
		</script>
	';
}
else
{
    # pre create folder structure for special hosting permissions
    $folder = file( $xfolder );
    $error = false;
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
    foreach ( $folder as $dir )
    {
        if ( $ftp_check_login )
        {
            if ( is_dir( NV_ROOTDIR . '/' . trim( $dir ) ) )
            {
                nv_chmod_dir( $conn_id, trim( $dir ), true );
            }
            else
            {
                $continueflag = ftp_mkdir( $conn_id, trim( $dir ) );
                nv_chmod_dir( $conn_id, trim( $dir ), true );
                //try to create if ftp error
                if ( ! $continueflag )
                {
                    if ( mkdir( NV_ROOTDIR . '/' . trim( $dir ) ) )
                    {
                        chmod( NV_ROOTDIR . '/' . trim( $dir ), 0777 );
                    }
                }
            }
        }
        else
        {
            if ( mkdir( NV_ROOTDIR . '/' . trim( $dir ) ) )
            {
                chmod( NV_ROOTDIR . '/' . trim( $dir ), 0777 );
            }
        }
        if ( ! is_writable( NV_ROOTDIR . '/' . trim( $dir ) ) )
        {
            echo NV_ROOTDIR . '/' . trim( $dir );
            echo '<div id="install_content"><h4>' . $lang_module['autoinstall_module_unzip_abort'] . '</h4><input style="margin-top:10px;font-size:15px" type="button" name="checkfile" value="' . $lang_module['autoinstall_module_checkfile'] . '"/><br /><br />';
            echo '</div><script type="text/javascript">
        		 $(function(){
        		 	$("input[name=checkfile]").click(function(){
        		 		$("#message").show();
        		 		$("#step1").html("");
        		 		$("#step1").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=install_check",function(){
        					$("#message").hide();
        				});
        			});
        		 });
        		</script>
        	';
            die();
        }
    }
    if ( intval( $global_config['ftp_check_login'] ) == 1 )
    {
        ftp_close( $conn_id );
    }
    
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    $zip = new PclZip( $filename );
    if ( $zip->extract( PCLZIP_OPT_PATH, NV_ROOTDIR ) == 0 )
    {
        echo $lang_module['autoinstall_module_cantunzip'];
        echo '<div id="message" style="display:none;text-align:center;color:red"><img src="../images/load_bar.gif"/>' . $lang_module['autoinstall_package_processing'] . '</div>';
        echo '<input style="margin-top:10px;font-size:15px" type="button" name="checkfile" value="' . $lang_module['autoinstall_module_checkfile'] . '"/>';
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
			</script>
		';
    }
    else
    {
        $list = $zip->listContent();
        echo "<br /><b>" . $lang_module['autoinstall_module_unzip_filelist'] . "</b><br /><div style='overflow:auto;height:200px;width:700px'>";
        for ( $i = 0; $i < sizeof( $list ); $i ++ )
        {
            if ( ! $list[$i]['folder'] ) $bytes = " - " . $list[$i]['size'] . " bytes";
            else $bytes = "";
            echo "" . $list[$i]['filename'] . "$bytes<br />";
        }
        echo '</div><br /><br /><a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=setup">' . $lang_module['autoinstall_module_unzip_setuppage'] . '</a>';
        unlink( $xfolder );
        unlink( $xauto );
        unlink( $filename );
    }
}

?>