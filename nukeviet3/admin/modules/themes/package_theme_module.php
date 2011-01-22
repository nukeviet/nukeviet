<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$title = $note = $module_file = "";
$page_title = $lang_module['autoinstall_method_packet'];

function getDirectoryTree ( $outerDir, $basepath )
{
    $dirs = array_diff( scandir( $outerDir ), array( 
        ".", ".." 
    ) );
    $dir_array = array();
    foreach ( $dirs as $d )
        $dir_array[] = is_dir( $outerDir . "/" . $d ) ? getDirectoryTree( $outerDir . "/" . $d, $filters ) : $dir_array[] = $basepath . $d;
    return $dir_array;
}
if ( $nv_Request->isset_request( 'op', 'post' ) )
{
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    $themename = $nv_Request->get_string( 'themename', 'post' );
    $modulename = $nv_Request->get_string( 'modulename', 'post' );
    $allowfolder = array();
    $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/modules/' . $modulename . '/';
    if ( file_exists( NV_ROOTDIR . '/themes/' . $themename . '/css/' . $modulename . '.css' ) )
    {
        $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/css/' . $modulename . '.css';
    }
    if ( file_exists( NV_ROOTDIR . '/themes/' . $themename . '/images/' . $modulename . '/' ) )
    {
        $allowfolder[] = NV_ROOTDIR . '/themes/' . $themename . '/images/' . $modulename . '/';
    }
    $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . 'theme_' . $themename . '_' . $modulename . '_' . md5( nv_genpass( 10 ) . session_id() ) . '.zip';
    require_once NV_ROOTDIR . '/includes/class/pclzip.class.php';
    $zip = new PclZip( $file_src );
    $zip->create( $allowfolder, PCLZIP_OPT_REMOVE_PATH, NV_ROOTDIR . '/themes' );
    
    $filesize = @filesize( $file_src );
    $file_name = basename( $file_src );
    nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['autoinstall_method_packet_module'] , 'file name : ' . $themename . '_' . $modulename .".zip" , $admin_info['userid'] );
    $linkgetfile = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=getfile&amp;mod=nv3_theme_" . $themename . "_" . $modulename . ".zip&amp;checkss=" . md5( $file_name . $client_info['session_id'] . $global_config['sitekey'] ) . "&amp;filename=" . $file_name;
    echo '<a href="' . $linkgetfile . '"><span style="font-size:16px;color:red">nv3_theme_' . $themename . '_' . $modulename . '   - ' . nv_convertfromBytes( $filesize ) . '</span></a>';
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
    $contents .= "<p id='message' style='color:
    red;display:none'></p>";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
    $contents .= "</table>";
    $contents .= "</form>";
    $contents .= '
<script type="text/javascript">
 $(function(){
 	$("input[name=continue]").click(function(){
 		var themename = $
    ( "select[name=themename]" ) . val();
 		var modulename = $
    ( "select[name=modulename]" ) . val();
    if ( themename != 0 && modulename != 0 )
    {
 			$("#message").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
 			$
        ( "#message" ) . fadeIn();
 			$
        ( "input[name=continue]" ) . attr( "disabled", "disabled" );
 			$
        ( "input[name=back]" ) . attr( "disabled", "disabled" );
 			$
        ( "#step1" ) . slideUp();
			$.ajax({	
				type: "POST",
				url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '",
				data: "themename="+ themename+"&modulename="+modulename+"&' . NV_OP_VARIABLE . '=' . $op . '",
				success: function(data){				
					$
        ( "input[name=back]" ) . removeAttr( "disabled" );
					$
        ( "input[name=continue]" ) . removeAttr( "disabled" );
					$
        ( "#message" ) . html( data );
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