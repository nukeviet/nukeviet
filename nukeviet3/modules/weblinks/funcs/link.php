<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:14
 */
if (! defined ( 'NV_IS_MOD_WEBLINKS' ))
	die ( 'Stop!!!' );
	//initial varible value
$sql = "SELECT name,value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query ( $sql );
while ( $row = $db->sql_fetchrow ( $result ) ) {
	$$row ['name'] = $row ['value'];
}
$link = $nv_Request->get_int ( 'id', 'get' );
$subsql = "SELECT id, title, url, urlimg, add_time, description,hits_total FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE status='1' AND id='" . $link . "'";
$subresult = $db->sql_query ( $subsql );
$contents .= '<tr><td valign="top">';
$subrow = $db->sql_fetchrow ( $subresult );

$contents .= '<table style="width:100%"><tr>';
$contents .= ($showlinkimage == 1) ? '<td valign="top" style="padding-top:5px"><img src="' . $subrow ['urlimg'] . '" width=' . $imgwidth . ' height=' . $imgheight . '></td>' : '';
$contents .= '<td valign="top" style="padding-top:5px;padding-left:5px;text-align:justify"><strong><a target="_blank" href="' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&op=visitlink&id=' . $subrow ['id'] . '">' . $subrow ['title'] . '</a></strong><br />' . $subrow ['description'] . '</td></tr>';
$contents .= '<tr><td colspan="2" style="padding-top:10px"><p style="font-size:11px;background-color:#DEDEDE;border-bottom:1px solid #000;line-height:20px;border-top:1px solid #000">' . $subrow ['url'] . $lang_module ['added'] . date ( 'd-m-Y H:i', $subrow ['add_time'] ) . $lang_module ['hits'] . $subrow ['hits_total'] . '<span class="addthis_toolbox addthis_default_style" style="float:right">
		<a href="http://addthis.com/bookmark.php?v=250&amp;username=uservn" class="addthis_button_compact">Share</a>
		<span class="addthis_separator">|</span>
		<a class="addthis_button_facebook"></a>
		<a class="addthis_button_myspace"></a>
		<a class="addthis_button_google"></a>
		<a class="addthis_button_twitter"></a>
		</span>
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=uservn"></script>' . '<a href="' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&op=reportlink&id=' . $subrow ['id'] . '" style="font-weight:bold;color:red;float:right">' . $lang_module ['report'] . ' </a>' . '</p></td></tr>';
$contents .= '</table>';
include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>