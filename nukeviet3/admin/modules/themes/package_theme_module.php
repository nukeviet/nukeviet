<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

if ( ! extension_loaded( 'zip' ) )
{
	die( "<br><br><br>" . $lang_global['error_zip_extension'] );
}

$title = $note = $module_file = "";
$page_title = $lang_module['autoinstall_method_packet'];

function getDirectoryTree( $outerDir, $basepath )
{
	$dirs = array_diff( scandir( $outerDir ), array( ".", ".." ) );
	$dir_array = array();
	foreach ( $dirs as $d ) $dir_array[] = is_dir( $outerDir . "/" . $d ) ? getDirectoryTree( $outerDir . "/" . $d, $filters ) : $dir_array[] = $basepath . $d;
	return $dir_array;
}
if ( $nv_Request->isset_request( 'op', 'post' ) )
{
	require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
	$themename = $nv_Request->get_string( 'themename', 'post' );
	$modulename = $nv_Request->get_string( 'modulename', 'post' );
	$tempfolder = NV_ROOTDIR . '/'.NV_TEMP_DIR;
	//first all file and folder in theme folder
	if ( file_exists( NV_ROOTDIR . '/themes/' . $themename . '/' ) )
	{
		$themefolder[] = NV_ROOTDIR . '/themes/' . $themename . '/';
	}

	$zip = new PclZip( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $themename . '.zip' );
	$zip->create( $themefolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes' );

	# del file not in condition
	$filelist = array();
	$images_list = array();
	$module_list = array();
	$sql = "SELECT module_file FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		if ( $row['module_file'] != $modulename )
		{
			#css folder
			$filelist[] = $themename . '/css/' . $row['module_file'] . '.css';
			#images files
			$images_list[] = $themename . '/images/' . $row['module_file'] . '/';
			$images_list2 = getDirectoryTree( NV_ROOTDIR . '/themes/' . $themename . '/images/' . $row['module_file'], $themename . '/images/' . $row['module_file'] . '/' );
			$zip->delete( PCLZIP_OPT_BY_NAME, $images_list2 );
			#modules files
			$module_list[] = $themename . '/modules/' . $row['module_file'] . '/';
			$module_list2 = getDirectoryTree( NV_ROOTDIR . '/themes/' . $themename . '/modules/' . $row['module_file'], $themename . '/modules/' . $row['module_file'] . '/' );
			$zip->delete( PCLZIP_OPT_BY_NAME, $module_list2 );
		}
	}
	#remove css file
	$zip->delete( PCLZIP_OPT_BY_NAME, $filelist );

	#remove empty folder on new class, only for PHP 5 >= 5.2.0, PECL zip >= 1.5.0
	$zip1 = new ZipArchive();
	if ( $zip1->open( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $themename . '.zip' ) === true )
	{
		foreach ( $images_list as $images )
		{
			$kq = $zip1->deleteName( $images );
		}
		foreach ( $module_list as $modules )
		{
			$zip1->deleteName( $modules );
		}
		$zip1->close();
	}

	$filesize = @filesize( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $themename . '.zip' );
	$filesize = ( round( $filesize / 1024, 2 ) > 1024 ) ? ( ( round( $filesize / ( pow( 1024, 2 ) ), 2 ) ) > 1024 ) ? ( round( $filesize / ( pow( 1024, 3 ) ), 2 ) ) . 'GB' : ( round( $filesize / ( pow( 1024, 2 ) ), 2 ) ) . 'MB' : round( $filesize / 1024, 2 ) . ' KB';
	echo '<a href="' . NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $themename . '.zip"><span style="font-size:16px;color:red">' . $themename . '.zip' . ' - ' . $filesize . '</span></a>';
}
else
{
	$op = $nv_Request->get_string( 'op', 'get' );
	$contents .= "<form name='install_theme' enctype='multipart/form-data' action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" method=\"post\">";
	$contents .= "<table summary=\"\" class=\"tab1\">\n";
	$contents .= "<tbody class=\"second\">";
	$contents .= "<tr>";
	$contents .= "<td align=\"center\" colspan='2'><strong>" . $lang_module['autoinstall_package_module_select'] . ": </strong>\n";
	$contents .= "<input type='hidden' name='" . NV_OP_VARIABLE . "' value='" . $op . "'/>";
	$contents .= "<select name=\"themename\">\n";
	$contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_theme_none'] . "</option>\n";
	$theme_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );
	foreach ( $theme_list as $value )
	{
		$contents .= "<option value=\"" . $value . "\">" . $value . "</option>\n";
	}
	$contents .= "</select>\n";

	$contents .= "<select name=\"modulename\">\n";
	$contents .= "<option value=\"0\">" . $lang_module['autoinstall_method__module_none'] . "</option>\n";
	$sql = "SELECT module_file, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
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
// jquery xman 18/05/2010
 $(function(){
 	$("input[name=continue]").click(function(){
 		var themename = $("select[name=themename]").val();
 		var modulename = $("select[name=modulename]").val();
 		if (themename!=0 && modulename!=0){
 			$("#message").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
 			$("#message").fadeIn();
 			$("input[name=continue]").attr("disabled","disabled");
 			$("input[name=back]").attr("disabled","disabled");
 			$("#step1").slideUp();
			$.ajax({	
				type: "POST",
				url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '",
				data: "themename="+ themename+"&modulename="+modulename+"&' . NV_OP_VARIABLE . '=' . $op . '",
				success: function(data){				
					$("input[name=back]").removeAttr("disabled");
					$("input[name=continue]").removeAttr("disabled");
					$("#message").html(data);
				}
			});
 		} else {
 			alert("' . $lang_module['autoinstall_package_noselect_module_theme'] . '");
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