<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */
global $db, $global_config,$module_data, $module_name;

function getSubcategory($parentid)
{
	global $db, $module_data, $module_name;
	$sql = $db->sql_query ( "SELECT cid,title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid = " . $parentid." ORDER BY weight" );
	$data = '<ul>';
	while ($sqlsubcat = $db->sql_fetchrow($sql))
	{
		$data .= "\n<li><a href=\"".NV_BASE_SITEURL."?".NV_LANG_VARIABLE."=".NV_LANG_DATA."&amp;".NV_NAME_VARIABLE."=".$module_name."&amp;" . NV_OP_VARIABLE . "=viewcat&id=".$sqlsubcat['cid']."\">".$sqlsubcat['title']."</a>";
		$data .= getSubcategory($sqlsubcat['cid']);
		$data .= "</li>\n";
	}
	$data .= '</ul>';
	return $data;
}
$sqlcat ="Select cid, title from `" . NV_PREFIXLANG . "_" . $module_data . "_categories` where parentid =0 AND active=1 ORDER BY `weight` ASC";
$resultcat = $db->sql_query($sqlcat);
if ($db->sql_numrows($resultcat) > 0)
{
	$menunews = "<link rel=\"stylesheet\" href=\"".NV_BASE_SITEURL."themes/default/css/menu_news.css\"/>\n";
	$menunews .= "<script type=\"text/javascript\" src=\"".NV_BASE_SITEURL."js/menu_news.js\"></script>\n";
	$menunews .= "<div class=\"sidebarmenu\"><ul id=\"sidebarmenu1\">";
	while ($cat = $db->sql_fetchrow($resultcat))
	{
		$menunews .= "\n<li><a href=\"".NV_BASE_SITEURL."?".NV_LANG_VARIABLE."=".NV_LANG_DATA."&amp;".NV_NAME_VARIABLE."=".$module_name."&amp;" . NV_OP_VARIABLE . "=viewcat&id=".$cat['cid']."\">".$cat['title']."</a>";
		$menunews .= getSubcategory($cat['cid']);
		$menunews .= '</li>';
	}
	$menunews .= '</ul></div><br>';
}
$content .= $menunews;
unset($menunews);

?>