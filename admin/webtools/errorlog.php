<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
        if (preg_match('/^(\d{4}\-\d{2}\-\d{2})\_(' . nv_preg_quote($error_log_filename) . '|' . nv_preg_quote($notice_log_filename) . ')\_([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $file, $matches)) {
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
    $xtpl = new XTemplate('errorlog.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

    $xtpl->parse('filelist_empty');
    $contents = $xtpl->text('filelist_empty');

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
$xtpl = new XTemplate('errorlog.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('PAGE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('ERROR_FILE_NAME', $errorfile);
$xtpl->assign('ERROR_FILE_CONTENT', $file_content);

foreach ($errors as $id => $item) {
    $item = json_decode($item, true);
    $item['time'] = date('d/m/Y H:i:s P', strtotime($item['time']));
    $xtpl->assign('ERROR', [
        'id' => $id,
        'time' => $item['time']
    ]);

    foreach ($item as $key => $value) {
        if ($key != 'time') {
            if ($key == 'backtrace') {
                $b = '';
                foreach ($value as $vl) {
                    $b .= '<li>' . $vl . '</li>';
                }
                $value = '<ul>' . $b . '</ul>';
            }
            $xtpl->assign('OPTION', [
                'title' => $nv_Lang->getModule('errorlog_' . $key),
                'value' => $value
            ]);
            unset($matches);
            if ($key == 'errno' and preg_match('/^(\d+)\s*\(.+\)/', $value, $matches)) {
                if (!empty($nv_Lang->getModule('errorcode_' . $matches[1]))) {
                    $xtpl->assign('NOTE', $nv_Lang->getModule('errorcode_' . $matches[1]));
                    $xtpl->parse('errorlist.error.option.note');
                }
            }
            $xtpl->parse('errorlist.error.option');
        }
    }
    $xtpl->parse('errorlist.error');
}

$xtpl->parse('errorlist');
$errorlist = $xtpl->text('errorlist');

if (!$is_default) {
    nv_jsonOutput([
        'errorlist' => $errorlist,
        'errorfilename' => $errorfile,
        'errorfilecontent' => $file_content
    ]);
}

$xtpl->assign('ERRORLIST', $errorlist);

foreach ($filelist2 as $type => $list) {
    if (!empty($list)) {
        if ($type == 'error') {
            $optgroup = $nv_Lang->getModule('errorlog_log');
        } elseif ($type == 'notice') {
            $optgroup = $nv_Lang->getModule('noticelog_log');
        } elseif ($type == 'e256') {
            $optgroup = $nv_Lang->getModule('errorlog_256');
        } else {
            $optgroup = $nv_Lang->getModule('errorlog_sendmail');
        }
        $xtpl->assign('OPTGROUP', $optgroup);

        $list = array_keys($list);
        foreach ($list as $key) {
            $xtpl->assign('ERRORFILE', [
                'val' => $key,
                'sel' => $key == $errorfile ? ' selected="selected"' : '',
                'name' => $key
            ]);
            $xtpl->parse('main.error_type.error_file');
        }
        $xtpl->parse('main.error_type');
    }
}

$mode = $nv_Request->get_string('errorfile_view_mode', 'session', '');
$modes = [
    'tabular' => $nv_Lang->getModule('display_mode_tabular'),
    'plaintext' => $nv_Lang->getModule('display_mode_plaintext')
];
empty($mode) && $mode = array_key_first($modes);
foreach ($modes as $key => $name) {
    $xtpl->assign('MODE', [
        'val' => $key,
        'sel' => $key == $mode ? ' selected="selected"' : '',
        'name' => $name
    ]);
    $xtpl->parse('main.display_mode');
}

if ($mode == 'tabular') {
    $xtpl->parse('main.plaintext_mode_hide');
} else {
    $xtpl->parse('main.tabular_mode_hide');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
