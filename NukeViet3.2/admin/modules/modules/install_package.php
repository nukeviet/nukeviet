<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
 
if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$title = $note = $module_file = "";

$page_title = $lang_module['autoinstall_method_packet'];

if ( $nv_Request->isset_request( NV_OP_VARIABLE, 'post' ) )
{
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    $modulename = $nv_Request->get_string( 'modulename', 'post' );
    $tempfolder = NV_ROOTDIR . '/' . NV_TEMP_DIR;
    //module folder
    if ( file_exists( NV_ROOTDIR . '/modules/' . $modulename . '/' ) )
    {
        $allowfolder[] = NV_ROOTDIR . '/modules/' . $modulename . '/';
    }
    
    //theme folder
    $theme_package = "";
    if ( is_dir( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $modulename ) )
    {
        $theme_package = $global_config['site_theme'];
    }
    elseif ( is_dir( NV_ROOTDIR . '/themes/default/modules/' . $modulename ) )
    {
        $theme_package = "default";
    }
    
    if ( ! empty( $theme_package ) )
    {
        $allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/modules/' . $modulename . '/';
        if ( file_exists( NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $modulename . '.css' ) )
        {
            $allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $modulename . '.css';
        }
        if ( file_exists( NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $modulename . '/' ) )
        {
            $allowfolder[] = NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $modulename . '/';
        }
    }
    
    // admin default theme
    if ( file_exists( NV_ROOTDIR . '/themes/admin_default' ) )
    {
        if ( file_exists( NV_ROOTDIR . '/themes/admin_default/css/' . $modulename . '.css' ) )
        {
            $allowfolder[] = NV_ROOTDIR . '/themes/admin_default/css/' . $modulename . '.css';
        }
        if ( file_exists( NV_ROOTDIR . '/themes/admin_default/images/' . $modulename . '/' ) )
        {
            $allowfolder[] = NV_ROOTDIR . '/themes/admin_default/images/' . $modulename . '/';
        }
        if ( file_exists( NV_ROOTDIR . '/themes/admin_default/modules/' . $modulename . '/' ) )
        {
            $allowfolder[] = NV_ROOTDIR . '/themes/admin_default/modules/' . $modulename . '/';
        }
    }
    $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'module_' . $modulename . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';
    if ( file_exists( $file_src ) )
    {
        @unlink( $file_src );
    }
    $zip = new PclZip( $file_src );
    $zip->add( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
    $filesize = @filesize( $file_src );
    $file_name = basename( $file_src );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_module'], "packet " . basename( $modulename ), $admin_info['userid'] );
    $linkgetfile = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getfile&amp;mod=nv3_module_" . $modulename . ".zip&amp;checkss=" . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) . "&amp;filename=" . $file_name;
    echo '<a href="' . $linkgetfile . '"><span style="font-size:16px;color:red">nv3_module_' . $modulename . '' . ' - ' . nv_convertfromBytes( $filesize ) . '</span></a>';
}
else
{
    $op = $nv_Request->get_string( NV_OP_VARIABLE, 'get' );
    $contents .= "<form name='install_module' enctype='multipart/form-data' action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" method=\"post\">";
    $contents .= "<table summary=\"\" class=\"tab1\">\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td align=\"center\" colspan='2'><strong>" . $lang_module['autoinstall_package_select'] . ": </strong>\n";
    $contents .= "<input type='hidden' name='" . NV_OP_VARIABLE . "' value='" . $op . "'/>";
    $contents .= "<select name=\"modulename\">\n";
    $contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_none'] . "</option>\n";
    $sql = "SELECT module_file FROM `" . $db_config['prefix'] . "_setup_modules` where `title`=`module_file` ORDER BY `title` ASC";
    $result = $db->sql_query( $sql );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $contents .= "<option value=\"" . $row['module_file'] . "\">" . $row['module_file'] . "</option>\n";
    }
    $contents .= "</select>\n";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "<tr>";
    $contents .= "<td colspan='2' align='center'>";
    $contents .= "<input name=\"continue\" type=\"button\" value=\"" . $lang_module['autoinstall_continue'] . "\" />\n";
    $contents .= "<input name=\"back\" type=\"button\" value=\"" . $lang_module['autoinstall_back'] . "\" />\n";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td colspan='2' align='center'>";
    $contents .= "<p id='message' style='color:red;display:none'></p>";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "</table>";
    $contents .= "</form>";
    $contents .= '
<script type="text/javascript">
 $(function(){
 //<![CDATA[
 	$("input[name=continue]").click(function(){
 		var modulename = $("select[name=modulename]").val();
 		if (modulename!=0){
 			$("#message").html("<img src=\'' . NV_BASE_SITEURL . 'images/load_bar.gif\' alt=\'\'/>' . $lang_module['autoinstall_package_processing'] . '");
 			$("#message").fadeIn();
 			$("input[name=continue]").attr("disabled","disabled");
 			$("input[name=back]").attr("disabled","disabled");
 			$("#step1").slideUp();
			$.ajax({	
				type: "POST",
				url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '",
				data: "modulename="+ modulename+"&' . NV_OP_VARIABLE . '=' . $op . '",
				success: function(data){				
					$("input[name=back]").removeAttr("disabled");
					$("input[name=continue]").removeAttr("disabled");
					$("#message").html(data);
				}
			});
 		} else {
 			alert("' . $lang_module['autoinstall_package_noselect'] . '");
 			return false;
 		}
 	});
 	$("input[name=back]").click(function(){
 		$("#content").slideUp();
		$("#step1").slideDown();
 	});

 });
 //]]>
</script>';
    echo $contents;
}

?>