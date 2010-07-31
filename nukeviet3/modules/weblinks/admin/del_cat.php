<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$catid = $nv_Request->get_int ( 'catid', 'post', 0 );

$contents = "NO_" . $catid;
list($catid, $parentid) = $db->sql_fetchrow($db->sql_query("SELECT `catid`, `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`=" . intval($catid) . ""));
if ($catid > 0){
    list($check_parentid) = $db->sql_fetchrow($db->sql_query("SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid` = '" . $catid . "'"));
    if (intval($check_parentid) > 0) {
            $contents = "ERR_".sprintf($lang_module['delcat_msg_cat'], $check_parentid);
    }
    else {
        list($check_rows) = $db->sql_fetchrow($db->sql_query("SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `catid` = '" . $catid . "'"));
        if (intval($check_rows) > 0) {
            $contents = "ERR_".sprintf($lang_module['delcat_msg_rows'], $check_rows);
        }
    }
    if ($contents == "NO_" . $catid) {
		$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE catid=".$catid."";
		if ($db->sql_query($query))
		{
			$db->sql_freeresult();
			nv_fix_cat($parentid);
            $contents = "OK_" . $catid;
        }
    }
}

include (NV_ROOTDIR . "/includes/header.php");
echo $contents;
include (NV_ROOTDIR . "/includes/footer.php");

?>