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
if ( $nv_Request->isset_request( 'op', 'post' ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$modulename = $nv_Request->get_string( 'modulename', 'post' );
	$tempfolder = NV_ROOTDIR . '/'.NV_TEMP_DIR;
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
	} elseif ( is_dir( NV_ROOTDIR . '/themes/default/modules/' . $modulename ) )
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

	if ( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $modulename . '.zip' ) )
	{
		@unlink( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $modulename . '.zip' );
	}
	$zip = new PclZip( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $modulename . '.zip' );
	$zip->add( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR );
	$filesize = @filesize( NV_ROOTDIR . '/'.NV_TEMP_DIR.'/' . $modulename . '.zip' );
	$filesize = ( round( $filesize / 1024, 2 ) > 1024 ) ? ( ( round( $filesize / ( pow( 1024, 2 ) ), 2 ) ) > 1024 ) ? ( round( $filesize / ( pow( 1024, 3 ) ), 2 ) ) . 'GB' : ( round( $filesize / ( pow( 1024, 2 ) ), 2 ) ) . 'MB' : round( $filesize / 1024, 2 ) . ' KB';
	echo '<a href="' . NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $modulename . '.zip"><span style="font-size:16px;color:red">' . $modulename . '.zip' . ' - ' . $filesize . '</span></a>';
}
else
{
	$op = $nv_Request->get_string( 'op', 'get' );
	$contents .= "<form name='install_module' enctype='multipart/form-data' action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" method=\"post\">";
	$contents .= "<table summary=\"\" class=\"tab1\">\n";
	$contents .= "<tbody class=\"second\">";
	$contents .= "<tr>";
	$contents .= "<td align=\"center\" colspan='2'><strong>" . $lang_module['autoinstall_package_select'] . ": </strong>\n";
	$contents .= "<input type='hidden' name='" . NV_OP_VARIABLE . "' value='" . $op . "'/>";
	$contents .= "<select name=\"modulename\">\n";
	$contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_none'] . "</option>\n";
	$sql = "SELECT module_file, custom_title FROM `" . NV_MODULES_TABLE . "` where `title`=`module_file` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		$contents .= "<option value=\"" . $row['module_file'] . "\">" . $row['custom_title'] . "</option>\n";
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
 	$("input[name=continue]").click(function(){
 		var modulename = $("select[name=modulename]").val();
 		if (modulename!=0){
 			$("#message").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
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
</script>
';
	echo $contents;
}

?>