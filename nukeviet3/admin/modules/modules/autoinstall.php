<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );
$title = $note = $module_file = "";
$page_title = $lang_module['autoinstall'];
if ( ! $sys_info['zlib_support'] )
{
    $contents = "<br><br><br>" . $lang_global['error_zlib_support'];
}
else
{
    $contents .= "<div id='step1'><table summary=\"\" class=\"tab1\">\n";
    $contents .= "<tbody class=\"second\">";
    $contents .= "<tr>";
    $contents .= "<td align=\"right\"><strong>" . $lang_module['autoinstall_method'] . ": </strong></td>\n";
    $contents .= "<td>";
    $contents .= "<select name=\"installtype\">\n";
    $contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_none'] . "</option>\n";
    $contents .= "<option value=\"install_module\">" . $lang_module['autoinstall_method_module'] . "</option>\n";
    $contents .= "<option value=\"install_package\">" . $lang_module['autoinstall_method_packet'] . "</option>\n";
    $contents .= "</select>\n";
    $contents .= "</td>";
    $contents .= "</tr>";
    $contents .= "</tbody>";
	$contents .= "<tbody>";
    $contents .= "<tr>";
    $contents .= "<td colspan='2' align='center'>";
    $contents .= "<input name=\"method\" type=\"button\" value=\"" . $lang_module['autoinstall_continue'] . "\" />\n";
    $contents .= "</td>";
    $contents .= "</tr>";
	$contents .= "</tbody>";
    $contents .= "</table></div>";
    $contents .= "<div id='content'></div>";
    $contents .= '
<script type="text/javascript">
 $(function(){
 	$("input[name=method]").click(function(){
 		var method = $("select[name=installtype]").val();
 		if (method!=0){
 			$("#step1").slideUp();
 			$("#content").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '="+method);
 			$("#content").slideDown();
 		} else {
 			alert("' . $lang_module['autoinstall_error_nomethod'] . '");
 			return false;
 		}
 	});
 });
</script>
';
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>