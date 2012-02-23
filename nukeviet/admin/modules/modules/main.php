<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 9:32
 */

if (!defined('NV_IS_FILE_MODULES')) die('Stop!!!');

if (sizeof($site_mods) < 2)
{
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=setup");
    exit();
}

$theme_array = array();
$theme_array_file = nv_scandir(NV_ROOTDIR . "/themes", $global_config['check_theme']);
$sql = "SELECT DISTINCT `theme` FROM `" . NV_PREFIXLANG . "_modthemes`  WHERE `func_id`=0";
$result = $db->sql_query($sql);
while (list($theme) = $db->sql_fetchrow($result))
{
    if (in_array($theme, $theme_array_file))
    {
        $theme_array[] = $theme;
    }
}

$page_title = $lang_module['main'];

$contents['div_id'] = "list_mods";
$contents['ajax'] = "nv_show_list_mods();";

$contents = call_user_func("main_theme", $contents);

include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme($contents);
include (NV_ROOTDIR . "/includes/footer.php");

?>