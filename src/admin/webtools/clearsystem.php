<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9/9/2010, 6:51
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('clearsystem');

/**
 * nv_clear_files()
 *
 * @param mixed $dir
 * @param mixed $base
 * @return
 */
function nv_clear_files($dir, $base)
{
    $dels = array();
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (!preg_match("/^[\.]{1,2}([a-zA-Z0-9]*)$/", $file) and $file != "index.html" and is_file($dir . '/' . $file)) {
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

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('IS_GOD_ADMIN', defined('NV_IS_GODADMIN'));

$files_deleted = [];

if ($nv_Request->isset_request('submit', 'post') and $nv_Request->isset_request('deltype', 'post')) {
    $deltype = $nv_Request->get_typed_array('deltype', 'post', 'string', array());

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('clearsystem'), implode(", ", $deltype), $admin_info['userid']);
    clearstatcache();
    if (in_array('clearcache', $deltype)) {
        if ($dh = opendir(NV_ROOTDIR . '/' . NV_CACHEDIR)) {
            while (($modname = readdir($dh)) !== false) {
                if (preg_match($global_config['check_module'], $modname)) {
                    $cacheDir = NV_ROOTDIR . '/' . NV_CACHEDIR . '/' . $modname;
                    $_files = nv_clear_files($cacheDir, NV_CACHEDIR . '/' . $modname);
                    if (!empty($_files)) {
                        $files_deleted = array_merge_recursive($files_deleted, $_files);
                    }
                }
            }
            closedir($dh);
        }
        $files_deleted = nv_apply_hook('', 'after_clear_cache_manually', array($files_deleted), $files_deleted);
        $nv_Cache->delAll();
        if (defined('NV_IS_GODADMIN')) {
            $timestamp = intval($global_config['timestamp']) + 1;
            $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $timestamp . "' WHERE lang = 'sys' AND module = 'global' AND config_name = 'timestamp'");
            nv_save_file_config_global();
        }
    }

    if (defined('NV_IS_GODADMIN')) {
        if (in_array('clearfiletemp', $deltype)) {
            $dir = NV_ROOTDIR . "/" . NV_TEMP_DIR;
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (preg_match("/^(" . nv_preg_quote(NV_TEMPNAM_PREFIX) . ")[a-zA-Z0-9\-\_\.]+$/", $file)) {
                        if (is_file($dir . '/' . $file)) {
                            if (@unlink($dir . '/' . $file)) {
                                $files_deleted[] = NV_TEMP_DIR . '/' . $file;
                            }
                        } else {
                            $rt = nv_deletefile($dir . '/' . $file, true);
                            if ($rt[0] == 1) {
                                $files_deleted[] = NV_TEMP_DIR . '/' . $file;
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }

        if (in_array('clearerrorlogs', $deltype)) {
            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs');
            if (!empty($files)) {
                $files_deleted = array_merge_recursive($files_deleted, $files);
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/errors256');
            if (!empty($files)) {
                $files_deleted = array_merge_recursive($files_deleted, $files);
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/old';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/old');
            if (!empty($files)) {
                $files_deleted = array_merge_recursive($files_deleted, $files);
            }

            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/tmp';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/error_logs/tmp');
            if (!empty($files)) {
                $files_deleted = array_merge_recursive($files_deleted, $files);
            }
        }

        if (in_array('clearip_logs', $deltype)) {
            $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/ip_logs';
            $files = nv_clear_files($dir, NV_LOGS_DIR . '/ip_logs');
            $files = nv_apply_hook('', 'after_clear_ip_logs', array($files), $files);
            if (!empty($files)) {
                $files_deleted = array_merge_recursive($files_deleted, $files);
            }
        }
    }
    asort($files_deleted);
}

$tpl->assign('FILES', $files_deleted);

$contents = $tpl->fetch('clearsystem.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
