<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['weblink_checkalivelink'];
$submit = $nv_Request->get_string ( 'submit', 'post' );
if ($submit) {
	//session_start();
	$nv_Request->set_Cookie('ok',1);
}
if ($nv_Request->isset_request('ok','cookie')) {
	
	require_once NV_ROOTDIR . '/includes/class/checkurl.class.php';
	
	$check = new CheckUrl ();
	
	$page_title = $lang_module ['weblink_checkalivelink'];
	
	$numcat = $db->sql_numrows ( $db->sql_query ( "SELECT id FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` " ) );
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checklink";
	$all_page = ($numcat > 1) ? $numcat : 1;
	$per_page = 5;
	$page = $nv_Request->get_int ( 'page', 'get', 0 );
	
	$sql = "SELECT url FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` LIMIT $page,$per_page";
	$result = $db->sql_query ( $sql );
	while ( $row = $db->sql_fetchrow ( $result ) ) {
		$contents .= ($check->check_curl ( $row ['url'])) ? $row ['url'] . '<br />' : '<span style="text-decoration:line-through">' . $row ['url'] . '</span>' . $lang_module ['weblink_check_error'];
	}
	$generate_page = nv_generate_page ( $base_url, $all_page, $per_page, $page );
	if ($generate_page != "")
		$contents .= "<br /><p align=\"center\">" . $generate_page . "</p>\n";
} else {
	$contents .= $lang_module ['weblink_check_notice'] . '
<form name="confirm" action="' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checklink" method="post">
<input type="hidden" name="ok" value="1">
<input type="submit" value="' . $lang_module ['weblink_check_confirm'] . '" name="submit">
</form>
';
}
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>