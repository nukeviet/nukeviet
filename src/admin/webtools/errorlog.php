<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('changemode, mode', 'post')) {
    $mode = $nv_Request->get_string('mode', 'post', '');
    if ($mode == 'tabular' or $mode == 'plaintext') {
        $nv_Request->set_Session('errorfile_view_mode', $mode);
    }
    exit('OK');
}

$page_title = $nv_Lang->getModule('errorlog');
$filelist = [];
$filelist2 = [
    'error' => [],
    'notice' => [],
    'e256' => [],
    'sendmail' => []
];
$logext = $ErrorHandler->cfg['error_log_fileext'];
$error_log_filename = $ErrorHandler->cfg['error_log_filename'];
$notice_log_filename = $ErrorHandler->cfg['notice_log_filename'];
$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
clearstatcache();
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        unset($matches);
        if (preg_match('/^(\d{4}\-\d{2}\-\d{2})\_(' . nv_preg_quote($error_log_filename) . '|' . nv_preg_quote($notice_log_filename) . ')([^\.]*)\.' . nv_preg_quote($logext) . '$/', $file, $matches)) {
            $filemtime = filemtime($dir . '/' . $file);
            $filelist[$file] = $filemtime;
            if ($matches[2] == $error_log_filename) {
                $filelist2['error'][$file] = $filemtime;
            } else {
                $filelist2['notice'][$file] = $filemtime;
            }
        }
    }
    closedir($dh);
}

if (!empty($filelist2['error'])) {
    arsort($filelist2['error'], SORT_NUMERIC);
}
if (!empty($filelist2['notice'])) {
    arsort($filelist2['notice'], SORT_NUMERIC);
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        if (preg_match('/^(\d{4})\-(\d{2})\_([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $file)) {
            $filemtime = filemtime($dir . '/' . $file);
            $filelist[$file] = $filemtime;
            $filelist2['e256'][$file] = $filemtime;
        }
    }
    closedir($dh);
}
if (!empty($filelist2['e256'])) {
    arsort($filelist2['e256'], SORT_NUMERIC);
}

if (file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $logext)) {
    $filemtime = filemtime(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $logext);
    $filelist['sendmail.' . $logext] = $filemtime;
    $filelist2['sendmail']['sendmail.' . $logext] = $filemtime;
}

if (empty($filelist)) {
    $contents = nv_theme_alert($nv_Lang->getGlobal('info_level'), $nv_Lang->getModule('error_filelist_empty'), 'info');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$errorfile = $nv_Request->get_string('errorfile', 'post', '');
if (!empty($errorfile) and !isset($filelist[$errorfile])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$is_default = false;
if (empty($errorfile)) {
    foreach ($filelist2 as $list) {
        if (!empty($list)) {
            $errorfile = array_key_first($list);
            $is_default = true;
            break;
        }
    }
}

if (preg_match('/^(\d{4})\-(\d{2})\_([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $errorfile)) {
    $erf = 'errors256/' . $errorfile;
} else {
    $erf = $errorfile;
}

$file_content = file_get_contents(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/' . $erf);
$errors = explode($ErrorHandler::LOG_DELIMITER, $file_content);
$errors = array_map('trim', $errors);
$errors = array_filter($errors);
krsort($errors);

$file_content = nv_htmlspecialchars($file_content);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('errorlog.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->registerPlugin('modifier', 'array_keys', 'array_keys');
$tpl->registerPlugin('modifier', 'json_decode', 'json_decode');
$tpl->registerPlugin('modifier', 'nv_datetime_format', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'strtotime', 'strtotime');
$tpl->registerPlugin('modifier', 'is_numeric', 'is_numeric');

$tpl->assign('PAGE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('ERROR_FILE_NAME', $errorfile);
$tpl->assign('ERROR_FILE_CONTENT', $file_content);

$mode = $nv_Request->get_string('errorfile_view_mode', 'session', '');
$modes = [
    'tabular' => $nv_Lang->getModule('display_mode_tabular'),
    'plaintext' => $nv_Lang->getModule('display_mode_plaintext')
];
empty($mode) && $mode = array_key_first($modes);

$tpl->assign('MODES', $modes);
$tpl->assign('MODE', $mode);
$tpl->assign('ERRORS', $errors);

$errorlist = $tpl->fetch('errorlog-list.tpl');
if (!$is_default) {
    nv_jsonOutput([
        'errorlist' => $errorlist,
        'errorfilename' => $errorfile,
        'errorfilecontent' => $file_content
    ]);
}

$tpl->assign('ERRORLIST', $errorlist);
$tpl->assign('FILELIST', $filelist2);

$contents = $tpl->fetch('errorlog.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
