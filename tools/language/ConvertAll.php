<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

set_time_limit(0);

function list_all_file($dir = '', $base_dir = '')
{
    $file_list = array();

    if (is_dir($dir)) {
        $array_filedir = scandir($dir);

        foreach ($array_filedir as $v) {
            if ($v == '.' or $v == '..') {
                continue;
            }

            if (is_dir($dir . '/' . $v)) {
                foreach (list_all_file($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                    $file_list[] = $file;
                }
            } else {
                // if( $base_dir == '' and ( $v == 'index.html' or $v == 'index.htm' ) ) continue; // Khong di chuyen index.html
                if (
                    preg_match('/\.php$/', $v) and !preg_match('/^\/?(data|vendor)\//', $base_dir . '/' . $v) and
                    !preg_match('/\/?includes\/language/', $base_dir . '/' . $v) and
                    !preg_match('/\/?modules\/(.*?)\/language/', $base_dir . '/' . $v) and
                    !preg_match('/\/?themes\/(.*?)\/language/', $base_dir . '/' . $v)
                ) {
                    $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                }
            }
        }
    }

    return $file_list;
}

// Chuyển đổi toàn bộ hoặc một thư mục hoặc một file chỉ định
$set_file = '';
if (!empty($argv) and isset($argv[1])) {
    $argv[1] = str_replace('\\', '/', $argv[1]);
    if (is_file($argv[1])) {
        $set_file = basename($argv[1]);
        define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname($argv[1]))));
    } else {
        define('NV_ROOTDIR', str_replace('\\', '/', realpath($argv[1])));
    }
} else {
    define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../src')));
}

if (empty($set_file)) {
    $allfiles = list_all_file(NV_ROOTDIR);
} else {
    $allfiles = [$set_file];
}

foreach ($allfiles as $filepath) {
    $filecontents = $filecontentsNew = file_get_contents(NV_ROOTDIR . '/' . $filepath);

    // Xử lý cái biến global
    unset($m);
    preg_match_all("/global[\s]+\\$([a-zA-Z0-9\_\,\s\\$]+)\;/i", $filecontentsNew, $m);
    if (!empty($m[1])) {
        foreach ($m[1] as $k => $v) {
            $m[1][$k] = '$' . $m[1][$k];
            $array_variable = array_map('trim', explode(',', $m[1][$k]));

            $isGlobalLang = false;
            $newVariable = array();

            foreach ($array_variable as $vk => $vv) {
                if ($vv == '$lang_global' or $vv == '$lang_module' or $vv == '$lang_block') {
                    $isGlobalLang = true;
                } else {
                    $newVariable[] = $vv;
                }
            }
            if ($isGlobalLang) {
                $newVariable[] = '$nv_Lang';
            }
            $array_variable = 'global ' . implode(', ', $newVariable) . ';';

            if ($array_variable != $m[0][$k]) {
                $filecontentsNew = str_replace($m[0][$k], $array_variable, $filecontentsNew);
            }
        }
    }

    // Xử lý assign ra tpl
    unset($m);
    preg_match_all("/\\$([a-zA-Z0-9\_]+)\-\>assign[\s]*\([\s]*('|\")([a-zA-Z0-9\_]+)('|\")[\s]*\,[\s]*\\$(lang_module|lang_block|lang_global)[\s]*\)[\s]*\;/i", $filecontentsNew, $m);
    if (!empty($m[1])) {
        foreach ($m[1] as $k => $v) {
            $replace = '$' . $m[1][$k] . '->assign(\'' . $m[3][$k] . '\', \NukeViet\Core\Language::$' . $m[5][$k] . ');';
            $filecontentsNew = str_replace($m[0][$k], $replace, $filecontentsNew);
        }
    }

    // Xử lý get lang
    $filecontentsNew = preg_replace("/\\\$lang\_module[\s]*\[[\s]*('|\")([a-zA-Z0-9\_\-]+)('|\")[\s]*\]/", '$nv_Lang->getModule(\'\\2\')', $filecontentsNew);
    $filecontentsNew = preg_replace("/\\\$lang\_global[\s]*\[[\s]*('|\")([a-zA-Z0-9\_\-]+)('|\")[\s]*\]/", '$nv_Lang->getGlobal(\'\\2\')', $filecontentsNew);
    $filecontentsNew = preg_replace("/\\\$lang\_block[\s]*\[[\s]*('|\")([a-zA-Z0-9\_\-]+)('|\")[\s]*\]/", '$nv_Lang->getBlock(\'\\2\')', $filecontentsNew);

    if ($filecontentsNew != $filecontents) {
        echo("Change: " . $filepath . "\n");
        file_put_contents(NV_ROOTDIR . '/' . $filepath, $filecontentsNew, LOCK_EX);
    }
}

echo("OK\n");
