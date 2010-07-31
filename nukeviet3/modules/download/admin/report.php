<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['download_report'];
$id = $nv_Request->get_int( 'id', 'get', 0 );
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` b ON a.id=b.id ORDER BY a.id";
$numcat = $db->sql_numrows( $db->sql_query( $sql ) );
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=report";
$all_page = ( $numcat > 1 ) ? $numcat : 1;
$per_page = 2;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td></td>\n";
$contents .= "<td>" . $lang_module['report_id'] . "</td>\n";
$contents .= "<td>" . $lang_module['report_name'] . "</td>\n";
$contents .= "<td>" . $lang_module['report_cat'] . "</td>\n";
$contents .= "<td align='center'>" . $lang_module['report_feature'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;
$sql = "SELECT a.content,b.id, b.title, b.catid,a.date_up FROM `" . NV_PREFIXLANG . "_" . $module_data . "_report` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` b ON a.id=b.id ORDER BY a.id ASC LIMIT $page,$per_page";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\"><input type='checkbox' class='filelist' value='" . $row['id'] . "'></td>\n";
    $contents .= "<td align=\"center\">" . $row['id'] . "</td>\n";
    $contents .= "<td>" . $lang_module['report_title'] . "" . $row['title'] . "";
    $contents .= "<br /><br /><p>" . $lang_module['report_content'] . " <pre>" . htmlspecialchars_decode( $row['content'] ) . "</pre></p>";
    $contents .= "</td>\n";
    $contents .= "<td>";
    $contents .= date( 'd/m/Y', $row['date_up'] );
    $contents .= "</td>\n";
    $contents .= "<td align=\"center\">";
    $contents .= "<span class=\"edit_icon\"><a class='editfile' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addfile&amp;id=" . $row['id'] . "\">" . $lang_global['edit'] . "</a></span>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $a ++;
}
$contents .= "<tfoot><tr><td colspan='6'><span><a href='javascript:void(0);' id='checkall'>" . $lang_module['report_checkall'] . "</a>&nbsp;&nbsp;<a href='javascript:void(0);' id='uncheckall'>" . $lang_module['report_uncheckall'] . "</a>&nbsp;&nbsp;</span>&nbsp;&nbsp;<span class=\"delete_icon\"><a id='delfilelist' href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span></span></td></tr></tfoot>";
$contents .= "</table>\n";
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( $generate_page != "" ) $contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";
$contents .= "<div id='contentedit'></div>";
$contents .= "
<script type='text/javascript'>
$(document).ready(function(){
$('#checkall').click(function(){
	$('input:checkbox').each(function(){
		$(this).attr('checked','checked');
	});
});
$('#uncheckall').click(function(){
	$('input:checkbox').each(function(){
		$(this).removeAttr('checked');
	});
});
$('#delfilelist').click(function(){
	var listfile = [];
	if (confirm('" . $lang_module['file_del_confirm'] . "'))
	{
		$('input.filelist:checked').each(function(){
			listfile.push($(this).val());
		});
		if (listfile.length<1){
			alert('" . $lang_module['report_error_report'] . "');
			return false;
		}
		$.ajax({	
			type: 'POST',
			url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delreport',
			data:'listfile='+listfile,
			success: function(data){				
				alert(data);
				window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=report';
			}
		});
	}
});
});
</script>
";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>