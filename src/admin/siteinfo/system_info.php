<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('site_configs_info');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('system_info.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('NVRQ', $nv_Request);
$tpl->assign('SYS', $sys_info);
$tpl->assign('PHPVERSION', PHP_VERSION != '' ? PHP_VERSION : phpversion());
$tpl->assign('DBVERSION', $db->getAttribute(PDO::ATTR_DRIVER_NAME) . ' ' . $db->getAttribute(PDO::ATTR_SERVER_VERSION));
$tpl->assign('SERVER_API', (nv_function_exists('apache_get_version') ? apache_get_version() . ', ' : (nv_getenv('SERVER_SOFTWARE') != '' ? nv_getenv('SERVER_SOFTWARE') . ', ' : '')) . (PHP_SAPI != '' ? PHP_SAPI : php_sapi_name()));

$is_windows = substr($sys_info['os'], 0, 3) != 'WIN';
$is_windows = 0;
$tpl->assign('IS_WIN', $is_windows);

$chmods = [];
if (defined('NV_IS_GODADMIN') and !$is_windows) {
    $chmods = [
        ['key' => NV_DATADIR, 'value' => (is_writable(NV_ROOTDIR . '/' . NV_DATADIR))],
        ['key' => NV_CACHEDIR, 'value' => (is_writable(NV_ROOTDIR . '/' . NV_CACHEDIR))],
        ['key' => NV_UPLOADS_DIR, 'value' => (is_writable(NV_ROOTDIR . '/' . NV_UPLOADS_DIR))],
        ['key' => NV_TEMP_DIR, 'value' => (is_writable(NV_ROOTDIR . '/' . NV_TEMP_DIR))],
        ['key' => NV_LOGS_DIR . '/data_logs', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs'))],
        ['key' => NV_LOGS_DIR . '/dump_backup', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs'))],
        ['key' => NV_LOGS_DIR . '/error_logs', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs'))],
        ['key' => NV_LOGS_DIR . '/error_logs/errors256', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256'))],
        ['key' => NV_LOGS_DIR . '/error_logs/old', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old'))],
        ['key' => NV_LOGS_DIR . '/error_logs/tmp', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp'))],
        ['key' => NV_LOGS_DIR . '/ip_logs', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs'))],
        ['key' => NV_LOGS_DIR . '/ref_logs', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ref_logs'))],
        ['key' => NV_LOGS_DIR . '/voting_logs', 'value' => (is_writable(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs'))]
    ];
    if ($dh = opendir(NV_ROOTDIR . '/' . NV_CACHEDIR)) {
        while (($modname = readdir($dh)) !== false) {
            if (preg_match('/^([a-z0-9\_]+)$/', $modname)) {
                $chmods[] = ['key' => NV_CACHEDIR . '/' . $modname, 'value' => (is_writable(NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname))];
            }
        }
        closedir($dh);
    }
}

$tpl->assign('CHMODS', $chmods);

$contents = $tpl->fetch('system_info.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
