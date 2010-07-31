<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['download_catmanager'];
$catid = $nv_Request->get_int ( 'cid', 'get', 0 );
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td>ID</td>\n";
$contents .= "<td>" . $lang_module ['category_cat_name'] . "</td>\n";
$contents .= "<td>" . $lang_module ['category_cat_sort'] . "</td>\n";
$contents .= "<td>" . $lang_module ['category_cat_parent'] . "</td>\n";
$contents .= "<td>" . $lang_module ['category_cat_active'] . "</td>\n";
$contents .= "<td align='center'>" . $lang_module ['category_cat_feature'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;
$catselect = "";
if ($catid)
{
	$catselect = "WHERE parentid=" . $catid . "";
} else
{
	$catselect = "WHERE parentid=0";
}
#order
$numcat = $db->sql_numrows ( $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=" . $catid . "" ) );
$sql = "SELECT cid, title, cdescription,active,parentid, weight  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` " . $catselect . " ORDER BY `weight` ASC"; // LIMIT $page,$per_page
$result = $db->sql_query ( $sql );
while ( $row = $db->sql_fetchrow ( $result ) )
{
	$class = ($a % 2) ? " class=\"second\"" : "";
	$contents .= "<tbody" . $class . ">\n";
	$contents .= "<tr>\n";
	$contents .= "<td align=\"center\">" . $row ['cid'] . "</td>\n";
	$numsub = $db->sql_numrows ( $db->sql_query ( "SELECT cid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid='" . $row ['cid'] . "'" ) );
	$subcat = ($numsub) ? " <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=cat&amp;cid=" . $row ['cid'] . "\"><i>(" . $numsub . " " . $lang_module ['category_cat_sub'] . ")</i></a>" : "";
	$contents .= "<td>" . $row ['title'] . $subcat . "</td>\n";
	$contents .= "<td>";
	$contents .= '<select class="order" name="'.$row['cid'].'">';
	for($i = 1; $i <= $numcat; $i ++)
	{
		$sel = ($row ['weight'] == $i) ? ' selected' : '';
		$contents .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
	}
	$contents .= '</select>';
	$contents .= "</td>\n";
	$contents .= "<td>";
	if ($row ['parentid'])
	{
		$subrow = $db->sql_fetchrow ( $db->sql_query ( "SELECT cid, title,parentid  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid=" . $row ['parentid'] . "" ) );
		$contents .= "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=cat&amp;cid=" . $subrow ['parentid'] . "\">" . $subrow ['title'] . "</a>";
	} else
		$contents .= $lang_module ['category_cat_maincat'];
	$contents .= "</td>\n";
	$contents .= "<td align=\"center\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=actcat&cid=" . $row ['cid'] . "&cat=" . $catid . "\">" . (($row ['active']) ? $lang_module ['download_yes'] : $lang_module ['download_no']) . "</a></td>\n";
	$contents .= "<td align=\"center\">";
	$contents .= "<span class=\"edit_icon\"><a class=\"editcat\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=editcat&amp;cid=" . $row ['cid'] . "&cat=" . $catid . "\">" . $lang_global ['edit'] . "</a></span>\n";
	$contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a class=\"delcat\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=delcat&amp;cid=" . $row ['cid'] . "\">" . $lang_global ['delete'] . "</a></span></td>\n";
	$contents .= "</tr>\n";
	$contents .= "</tbody>\n";
	$a ++;
}
$contents .= "<tfoot><tr><td colspan='6'><span class=\"add_icon\"><a class=\"addcat\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&" . NV_OP_VARIABLE . "=addcat&cid=" . $catid . "\">" . $lang_global ['add'] . "</a></span></td></tr></tfoot>";
$contents .= "</table>\n";
/*$generate_page = nv_generate_page ( $base_url, $all_page, $per_page, $page );
if ($generate_page != "")
	$contents .= "<br><p align=\"center\">" . $generate_page . "</p>\n";*/
$contents .= "<div id='contentedit'></div><input id='hasfocus' style='width:0px;height:0px'/>";
$contents .= "
<script type='text/javascript'>
$(function(){
$('select.order').change(function(){
	$('select.order').attr({'disabled':''});
	var order = $(this).val();
	var cid = $(this).attr('name');
	$.ajax({	
		type: 'POST',
		url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ordercat',
		data: 'order='+order+'&cid='+cid,
		success: function(data){				
			window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&cid=" . $catid . "';
		}
	});
});
$('a[class=\"addcat\"]').click(function(event){
	event.preventDefault();
	var href= $(this).attr('href');
	$('#contentedit').load(href,function(){
		$('#hasfocus').focus();
	});

});
$('a[class=\"editcat\"]').click(function(event){
	event.preventDefault();
	var href= $(this).attr('href');
	$('#contentedit').load(href,function(){
		$('#hasfocus').focus();
	});
});
$('a[class=\"delcat\"]').click(function(event){
	event.preventDefault();
	var href= $(this).attr('href');
	$('#contentedit').load(href,function(){
		$('#hasfocus').focus();
	});
});
});
</script>
";
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>