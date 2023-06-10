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
$logext = $ErrorHandler->cfg['error_log_fileext'];
$error_log_filename = $ErrorHandler->cfg['error_log_filename'];
$notice_log_filename = $ErrorHandler->cfg['notice_log_filename'];
$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        if (preg_match('/^(\d{4}\-\d{2}\-\d{2})\_(' . nv_preg_quote($error_log_filename) . '|' . nv_preg_quote($notice_log_filename) . ')\.' . nv_preg_quote($logext) . '$/', $file)) {
            $filelist[$file] = $nv_Lang->getModule('errorlog_log') . ': ' . $file;
        }
    }
    closedir($dh);
}

if (!empty($filelist)) {
    krsort($filelist);
}
if (file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $logext)) {
    $filelist['sendmail.' . $logext] = $nv_Lang->getModule('errorlog_sendmail');
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        if (preg_match('/^([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $file)) {
            $filelist[$file] = $nv_Lang->getModule('errorlog_256') . ': ' . $file;
        }
    }
    closedir($dh);
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
    $errorfile = array_key_first($filelist);
    $is_default = true;
}

if (preg_match('/^([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $errorfile)) {
    $erf = 'errors256/' . $errorfile;
    $file_type = 'error256';
} else {
    $erf = $errorfile;
    if ($errorfile == 'sendmail.' . $logext) {
        $file_type = 'sendmail';
    } else {
        $file_type = 'all';
    }
}

$file_content = file_get_contents(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/' . $erf);
if ($file_type == 'sendmail') {
    $errors = array_map('trim', explode("\n", $file_content));
} elseif ($file_type == 'error256') {
    $errors = [trim($file_content)];
} else {
    $errors = explode($ErrorHandler::LOG_DELIMITER, $file_content);
    $errors = array_map('trim', $errors);
}
$errors = array_filter($errors);
krsort($errors);
$items = [];
foreach ($errors as $error) {
    if ($file_type != 'all') {
        $strs = [$error];
    } else {
        $strs = array_map('trim', explode("\n", $error, 2));
    }

    unset($matches);
    preg_match_all('/\[([A-Z\-]+)\:\s*([^\]]*)\]/', $strs[0], $matches, PREG_SET_ORDER);
    if (!empty($matches)) {
        $it = [];
        foreach ($matches as $match) {
            $v = trim($match[2]);
            if ($match[1] == 'TIME') {
                $v = date('d/m/Y H:i:s P', strtotime($v));
            }
            $it[strtolower($match[1])] = $v;
        }

        if (!empty($strs[1])) {
            $_backtraces = array_map('trim', explode("\n", $strs[1]));
            $backtraces = [];
            foreach ($_backtraces as $backtrace) {
                unset($matches2);
                preg_match_all('/\[([A-Z\-]+)\:\s*([^\]]*)\]/', $backtrace, $matches2, PREG_SET_ORDER);
                if (!empty($matches2)) {
                    $bt = [];
                    foreach ($matches2 as $match2) {
                        $bt[strtolower($match2[1])] = trim($match2[2]);
                    }
                    $backtraces[] = $bt;
                }
            }

            $it['backtrace'] = $backtraces;
        }
        $items[] = $it;
    }
}

$file_content = str_replace(['][', '-------------------'], [']<br/>[', '#############################'], $file_content);
$file_content = nv_htmlspecialchars(nv_br2nl($file_content));
$xtpl = new XTemplate('errorlog.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('PAGE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('ERROR_FILE_NAME', $errorfile);
$xtpl->assign('ERROR_FILE_CONTENT', $file_content);

foreach ($items as $id => $item) {
    $xtpl->assign('ERROR', [
        'id' => $id,
        'time' => $item['time']
    ]);

    foreach ($item as $key => $value) {
        if ($key != 'time') {
            if ($key == 'backtrace') {
                $b = '';
                foreach ($value as $vl) {
                    $r = [];
                    foreach ($vl as $k => $v) {
                        $r[] = $nv_Lang->getModule('backtrace_' . $k) . ': ' . $v;
                    }
                    $b .= '<li>' . implode('; ', $r) . '</li>';
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
    nv_htmlOutput($errorlist);
}

$xtpl->assign('ERRORLIST', $errorlist);

foreach ($filelist as $key => $ef) {
    $xtpl->assign('ERRORFILE', [
        'val' => $key,
        'sel' => $key == $errorfile ? ' selected="selected"' : '',
        'name' => $ef
    ]);
    $xtpl->parse('main.error_file');
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
