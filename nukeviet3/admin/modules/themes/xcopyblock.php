<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 08-19-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$page_title = $lang_module['xcopyblock'];
$contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
$contents .= "<blockquote class='error'><span id='message'>" . $lang_module['xcopyblock_notice'] . "</span></blockquote>\n";
$contents .= "</div>\n";
$contents .= "<div style='clear:both'></div>\n";
$op = $nv_Request->get_string( 'op', 'get' );
$contents .= "<form name='copy_block' action=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" method=\"post\">";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"center\" colspan='2'><strong>" . $lang_module['xcopyblock'] . $lang_module['xcopyblock_from'] . ": </strong>\n";
$contents .= "<input type='hidden' name='" . NV_OP_VARIABLE . "' value='" . $op . "'/>";
$contents .= "<select name=\"theme1\">\n";
$contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_theme_none'] . "</option>\n";
$theme_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );

$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes`  WHERE `func_id`=0";
$result = $db->sql_query( $sql );
while ( list( $theme ) = $db->sql_fetchrow( $result ) )
{
    if ( in_array( $theme, $theme_list ) )
    {
        $contents .= "<option value=\"" . $theme . "\">" . $theme . "</option>\n";
    }
}
$contents .= "</select>\n";

$contents .= $lang_module['xcopyblock_to'] . "<select name=\"theme2\">\n";
$contents .= "<option value=\"0\">" . $lang_module['autoinstall_method_theme_none'] . "</option>\n";
foreach ( $theme_list as $value )
{
    $contents .= "<option value=\"" . $value . "\">" . $value . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tr>";
$contents .= "<td colspan='2' align='center'>";
$contents .= "<p id='loadposition' style='color:red;font-weight:bold'></p></td>";
$contents .= "</tr>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td colspan='2' align='center'>";
$contents .= "<input name=\"continue\" type=\"button\" value=\"" . $lang_module['xcopyblock_process'] . "\" />\n";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "</table>";
$contents .= "</form>";
$contents .= '
<script type="text/javascript">
$("select[name=theme1]").change(function(){
	var theme1 = $(this).val();
	var theme2 = $("select[name=theme2]").val();
	if (theme2!=0 && theme1!=0 && theme1!=theme2){
		$("#loadposition").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
		$("#loadposition").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=loadposition&theme2="+theme2+"&theme1="+theme1);
	} else {
		$("#loadposition").html("");
	}
});
$("select[name=theme2]").change(function(){
	var theme2 = $(this).val();
	var theme1 = $("select[name=theme1]").val();
	if (theme2!=0 && theme1!=0 && theme1!=theme2){
		$("#loadposition").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
		$("#loadposition").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=loadposition&theme2="+theme2+"&theme1="+theme1);
	} else {
		$("#loadposition").html("");
	}
});

$("input[name=continue]").click(function(){
	var theme1 = $("select[name=theme1]").val();
	var theme2 = $("select[name=theme2]").val();
    var positionlist = [];
	$("input[name=position[]]:checked").each(function(){
	    positionlist.push($(this).val());
    });
    if (positionlist.length<1){
    	alert("' . $lang_module['xcopyblock_no_position'] . '");
    	return false;
    } else {
	    $("#loadposition").html("<img src=\'../images/load_bar.gif\'/>' . $lang_module['autoinstall_package_processing'] . '");
		$.ajax({	
			type: "POST",
			url: "' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=xcopyprocess",
			data: "position="+ positionlist+"&theme1="+theme1+"&theme2="+theme2,
			success: function(data){				
				$("#loadposition").html(data);
			}
		});
	}
});
</script>
';

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>