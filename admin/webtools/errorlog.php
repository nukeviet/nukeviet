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

$page_title = $lang_module['errorlog'];
$filelist = [];
$logext = $ErrorHandler->cfg['error_log_fileext'];
$error_log_filename = $ErrorHandler->cfg['error_log_filename'];
$notice_log_filename = $ErrorHandler->cfg['notice_log_filename'];
$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        if (preg_match('/^(\d{4}\-\d{2}\-\d{2})\_(' . nv_preg_quote($error_log_filename) . '|' . nv_preg_quote($notice_log_filename) . ')\.' . nv_preg_quote($logext) . '$/', $file)) {
            $filelist[$file] = $lang_module['errorlog_log'] . ': ' . $file;
        }
    }
    closedir($dh);
}

if (!empty($filelist)) {
    krsort($filelist);
}
if (file_exists(NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/sendmail.' . $logext)) {
    $filelist['sendmail.' . $logext] = $lang_module['errorlog_sendmail'];
}

$dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/error_logs/errors256';
if ($dh = opendir($dir)) {
    while (($file = readdir($dh)) !== false) {
        if (preg_match('/^([a-z0-9]{32})\.' . nv_preg_quote($logext) . '$/', $file)) {
            $filelist[$file] = $lang_module['errorlog_256'] . ': ' . $file;
        }
    }
    closedir($dh);
}

if (empty($filelist)) {
    $xtpl = new XTemplate('errorlog.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

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
                $v = date('d/m/Y H:i:s', strtotime($v));
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

$xtpl = new XTemplate('errorlog.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('PAGE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$is_exp = false;
foreach ($items as $id => $item) {
    $xtpl->assign('ERROR', [
        'id' => $id,
        'time' => $item['time'],
        'collapsed' => $is_exp ? 'collapsed' : '',
        'expanded' => $is_exp ? 'true' : 'false',
        'in' => $is_exp ? '' : 'in'
    ]);

    foreach ($item as $key => $value) {
        if ($key != 'time') {
            if ($key == 'backtrace') {
                $b = '';
                foreach ($value as $vl) {
                    $r = [];
                    foreach ($vl as $k => $v) {
                        $r[] = $lang_module['backtrace_' . $k] . ': ' . $v;
                    }
                    $b .= '<li>' . implode('; ', $r) . '</li>';
                }
                $value = '<ul>' . $b . '</ul>';
            }
            $xtpl->assign('OPTION', [
                'title' => $lang_module['errorlog_' . $key],
                'value' => $value
            ]);
            unset($matches);
            if ($key == 'errno' and preg_match('/^(\d+)\s*\(.+\)/', $value, $matches)) {
                if (!empty($lang_module['errorcode_' . $matches[1]])) {
                    $xtpl->assign('NOTE', $lang_module['errorcode_' . $matches[1]]);
                    $xtpl->parse('errorlist.error.option.note');
                }
            }
            $xtpl->parse('errorlist.error.option');
        }
    }
    $xtpl->parse('errorlist.error');
    $is_exp = true;
}

$xtpl->parse('errorlist');
$errorlist = $xtpl->text('errorlist');

if (!$is_default) {
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

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
