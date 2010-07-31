<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module['download_filemanager'];
$id = $nv_Request->get_int ( 'id', 'get', 0 );
$catid = $nv_Request->get_int ( 'cid', 'get', 0 );
$where =($catid)? ' WHERE catid='.$catid.' ':'';

$sql = "SELECT id, title,catid,active FROM `" . NV_PREFIXLANG . "_" . $module_data."` ".$where."";
$numcat = $db->sql_numrows ( $db->sql_query ( $sql ) );
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=file";
$all_page = ($numcat > 1) ? $numcat : 1;
$per_page = 10;
$page = $nv_Request->get_int ( 'page', 'get', 0 );
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td></td>\n";
$contents .= "<td>".$lang_module['file_id']."</td>\n";
$contents .= "<td>".$lang_module['file_name']."</td>\n";
$contents .= "<td>".$lang_module['file_cat']."</td>\n";
$contents .= "<td>".$lang_module['file_active']."</td>\n";
$contents .= "<td align='center'>".$lang_module['file_feature']."</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;
$sql = "SELECT id, title, catid,active FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ".$where." ORDER BY `id` ASC LIMIT $page,$per_page";
$result = $db->sql_query ( $sql );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$class = ($a % 2) ? " class=\"second\"" : "";
	$contents .= "<tbody" . $class . ">\n";
	$contents .= "<tr>\n";
	$contents .= "<td align=\"center\"><input type='checkbox' class='filelist' value='" . $row ['id'] . "'></td>\n";	
	$contents .= "<td align=\"center\">" . $row ['id'] . "</td>\n";
	$contents .= "<td>" . $row ['title'] .  "</td>\n";
	$contents .= "<td>";
	if ($row ['catid'])
	{
		$subrow = $db->sql_fetchrow ( $db->sql_query ( "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid=" . $row ['catid'] . "" ) );
		$contents .= "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&cid=" . $subrow ['cid'] . "\">" . $subrow ['title'] . "</a>";
	} else
		$contents .= $lang_module['file_maincat'];
	$contents .= "</td>\n";
	$contents .= "<td align=\"center\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=actfile&id=" . $row ['id'] ."\">" . (($row ['active']) ? $lang_module ['download_yes'] : $lang_module ['download_no']) . "</a></td>\n";
	$contents .= "<td align=\"center\">";
	$contents .= "<span class=\"edit_icon\"><a class='editfile' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $row ['id'] . "\">" . $lang_global ['edit'] . "</a></span>\n";
	$contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a class='delfile' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delfile&amp;id=" . $row ['id'] . "\">" . $lang_global ['delete'] . "</a></span></td>\n";
	$contents .= "</tr>\n";
	$contents .= "</tbody>\n";
	$a ++;
}
$contents .="<tfoot><tr><td colspan='6'><span><a href='javascript:void(0);' id='checkall'>".$lang_module['file_checkall']."</a>&nbsp;&nbsp;<a href='javascript:void(0);' id='uncheckall'>".$lang_module['file_uncheckall']."</a>&nbsp;&nbsp;</span><span class=\"add_icon\"><a class='addfile' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content\">" . $lang_global ['add'] . "</a>&nbsp;&nbsp;<span class=\"delete_icon\"><a id='delfilelist' href=\"javascript:void(0);\">" . $lang_global ['delete'] . "</a></span></span></td></tr></tfoot>";
$contents .= "</table>\n";
$generate_page = nv_generate_page ( $base_url, $all_page, $per_page, $page );
if ($generate_page != "")
	$contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";
$contents .= "<input id='hasfocus' style='width:0px;height:0px'/><div id='contentedit'></div>";
$contents .= "
<script type='text/javascript'>
$(function(){
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
	if (confirm('".$lang_module['file_del_confirm']."'))
	{
		var listfile = [];
		$('input.filelist:checked').each(function(){
			listfile.push($(this).val());
		});
		if (listfile.length<1){
			alert('".$lang_module['file_error_file']."');
			return false;
		}
		$.ajax({	
			type: 'POST',
			url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=delfilelist',
			data:'listfile='+listfile,
			success: function(data){				
				alert(data);
				window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=file';
			}
		});
	}
});
$('a[class=\"delfile\"]').click(function(event){
	event.preventDefault();
	if (confirm('".$lang_module['file_del_confirm']."'))
	{
		var href= $(this).attr('href');
		$.ajax({	
			type: 'POST',
			url: href,
			data:'',
			success: function(data){				
				alert(data);
				window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name."';
			}
		});
	}
});
});
</script>
";
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>