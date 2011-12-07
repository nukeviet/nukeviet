<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Feb 15, 2011  3:37:23 PM
 */

define('NV_ADMIN', true);
require_once (str_replace('\\\\', '/', dirname(__file__)) . '/mainfile.php');
require_once (NV_ROOTDIR . "/includes/core/admin_functions.php");
require_once (NV_ROOTDIR . "/includes/rewrite.php");
if (defined("NV_IS_GODADMIN"))
{
    if ($global_config['revision'] < 1488)
    {
        $statistics_timezone = floor(NV_SITE_TIMEZONE_OFFSET / 3600);
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'statistics_timezone', '" . $statistics_timezone . "')");
    }

    $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '1488')");
    nv_save_file_config_global();
    die("Update successfully, you should immediately delete this file.");
}
else
{
    die("You need login with god administrator");
}
?>