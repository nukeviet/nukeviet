<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    exit('Stop!!!');
}

$page_title = $lang_module['clearsystem'];

/**
 * nv_clear_files()
 *
 * @param string $dir
 * @param string $base
 * @return array
 */
function nv_clear_files($dir, $base)
{
    $dels = [];
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (!preg_match("/^[\.]{1,2}([a-zA-Z0-9]*)$/", $file) and $file != 'index.html' and is_file($dir . '/' . $file)) {
                if (unlink($dir . '/' . $file)) {
                    $dels[] = $base . '/' . $file;
                }
            }
        }
        closedir($dh);
    }
    if (!file_exists($dir . '/index.html')) {
        file_put_contents($dir . '/index.html', '');
    }

    return $dels;
}
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);

$xtpl = new XTemplate('clearsystem.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('CHECKSS', $checkss);

if (defined('NV_IS_GODADMIN')) {
    $xtpl->parse('main.godadmin');
}

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->isset_request('deltype', 'post')) {
    $deltype = $nv_Request->get_typed_array('deltype', 'post', 'string', []);

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['clearsystem'], implode(', ', $deltype), $admin_info['userid']);
    clearstatcache();

    if (defined('NV_IS_GODADMIN')) {
        if (in_array('clearfiletemp', $deltype, true)) {
            $dir = NV_ROOTDIR . '/' . NV_TEMP_DIR;
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (preg_match('/^(' . nv_preg_quote(NV_TEMPNAM_PREFIX) . ")[a-zA-Z0-9\-\_\.]+$/", $file)) {
                        if (is_file($dir . '/' . $file)) {
                            if (@unlink($dir . '/' . $file)) {
                                $xtpl->assign('DELFILE', NV_TEMP_DIR . '/' . $file);
                                $xtpl->parse('main.delfile.loop');
                            }
                        } else {
                            $rt = nv_deletefile($dir . '/' . $file, true);
                            if ($rt[0] == 1) {
                                $xtpl->assign('DELFILE', NV_TEMP_DIR . '/' . $file);
                                $xtpl->parse('main.delfile.loop');
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }

        if (in_array('clearerrorlogs', $deltype, true)) {
            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs');
            foreach ($files as $file) {
                $xtpl->assign('DELFILE', $file);
                $xtpl->parse('main.delfile.loop');
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/errors256');
            foreach ($files as $file) {
                $xtpl->assign('DELFILE', $file);
                $xtpl->parse('main.delfile.loop');
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/old');
            foreach ($files as $file) {
                $xtpl->assign('DELFILE', $file);
                $xtpl->parse('main.delfile.loop');
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/tmp');
            foreach ($files as $file) {
                $xtpl->assign('DELFILE', $file);
                $xtpl->parse('main.delfile.loop');
            }
        }

        if (in_array('clearip_logs', $deltype, true)) {
            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/ip_logs');
            foreach ($files as $file) {
                $xtpl->assign('DELFILE', $file);
                $xtpl->parse('main.delfile.loop');
            }
        }
    }

    if (in_array('clearcache', $deltype, true)) {
        if ($dh = opendir(NV_ROOTDIR . '/' . NV_CACHEDIR)) {
            while (($modname = readdir($dh)) !== false) {
                if (preg_match($global_config['check_module'], $modname)) {
                    $cacheDir = NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname;
                    $files = nv_clear_files($cacheDir, NV_CACHEDIR . '/' . $modname);
                    foreach ($files as $file) {
                        $xtpl->assign('DELFILE', $file);
                        $xtpl->parse('main.delfile.loop');
                    }
                }
            }
            closedir($dh);
        }
        $nv_Cache->delAll();
        if (defined('NV_IS_GODADMIN')) {
            $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'global' AND config_name = 'timestamp'");
            nv_save_file_config_global();
        }
    }

    $xtpl->parse('main.delfile');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
