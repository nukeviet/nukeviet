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
                if (preg_match('/\.php$/', $v) and !preg_match('/^\/?(data|vendor)\//', $base_dir . '/' . $v)) {
                    $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                }
            }
        }
    }

    return $file_list;
}

define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../src')));

echo('<pre><code style="font-size: 16px;font-family: Courier New;">');

if (isset($_GET['template']) and isset($_GET['f']) and isset($_GET['l'])) {
    $file = $_GET['f'];
    $line = $_GET['l'];
    $filecontents = explode("\n", file_get_contents(NV_ROOTDIR . '/' . $file));
    $linecontents = $filecontents[$line];

    preg_match('/nv\_apply\_hook[\s]*\((\$module\_name|""|\'\')[\s]*\,[\s]*(\'|")([a-zA-Z0-9\_]+)(\'|")[\s]*\,/', $linecontents, $m);

    $hook_tag = $m[3];
    $linecontents = trim(preg_replace('/^(.*?)' . preg_quote($m[0], '/') . '(.*?)$/', '\\2', $linecontents));

    $return_var = '';
    if (preg_match('/\)[\s]*\,[\s]*\$([0-9a-zA-Z\_]+)[\s]*\)[\s]*\;/', $linecontents, $m)) {
        $return_var = '$' . $m[1];
    }

    $array_para = array();
    if (preg_match('/^array[\s]*\(([\sa-zA-Z0-9\_\$\,]+)\)(\,|\)|\s)/', $linecontents, $m)) {
        $array_para = array_map('trim', explode(',', $m[1]));
    }

    $array_table_rows = array();
    $i = 0;
    $maxSize = 8;
    foreach ($array_para as $para) {
        $array_table_rows[$i] = $para;
        $size = strlen($para);
        if ($size > $maxSize) {
            $maxSize = $size;
        }
        $i++;
    }
    if ($maxSize < 15) {
        $maxSize = 15;
    }
    $heading_pad = '';
    for ($i = ($maxSize - 8); $i > 0; $i--) {
        $heading_pad .= ' ';
    }

    echo("<h1 style=\"margin:0;padding:0;\">====== " . $hook_tag . " ======</h1>\n");
    echo("Xảy_ra_khi_nào.\n\n");
    echo("<h3 style=\"margin:0;padding:0;\">==== Tham số ====</h3>\n");
    echo("^ STT ^ Tên biến" . $heading_pad . " ^ Kiểu dữ liệu    ^ Ghi chú                ^\n");

    foreach ($array_table_rows as $ii => $row) {
        echo("| " . str_pad($ii, 3, ' ', STR_PAD_RIGHT) . " | " . str_pad($row, $maxSize, ' ', STR_PAD_RIGHT) . " |                 |                        |\n");
    }

    echo("\n");
    echo("<h3 style=\"margin:0;padding:0;\">==== Dữ liệu trả về ====</h3>\n");
    echo(($return_var ? 'Biến \'\'' . htmlspecialchars($return_var) . '\'\'' : 'Không có') . "\n\n");
    echo("<h3 style=\"margin:0;padding:0;\">==== Ví dụ viết plugin ====</h3>\n");
    echo(htmlspecialchars('<code php>') . "\n");
    echo(htmlspecialchars("nv_add_hook(\$module_name, '" . $hook_tag . "', \$priority, function(\$vars) {") . "\n");

    if ($array_table_rows) {
        foreach ($array_table_rows as $ii => $row) {
            echo("    " . $row . " = \$vars[" . $ii . "];\n");
        }
        echo("\n");
    }

    echo(htmlspecialchars("    // Thực hiện code hook tại đây...") . "\n");

    if ($return_var) {
        echo(htmlspecialchars("\n    return " . $return_var . ";") . "\n");
    }

    echo(htmlspecialchars("});") . "\n");
    echo(htmlspecialchars('</code>') . "\n");

    echo('</code></pre>');
    die();
}

if (isset($_GET['f']) and isset($_GET['c'])) {
    $file = $_GET['f'];
    $code = $_GET['c'];

    $filecontents = explode("\n", file_get_contents(NV_ROOTDIR . '/' . $file));
    foreach ($filecontents as $l => $line) {
        if (strpos($line, $code) !== false) {
            echo("<span id=\"highlight\" style=\"background-color:#e01a31;\"><a style=\"text-decoration: none;color:#fff;\" href=\"?template=1&amp;f=" . urlencode($file) . "&amp;l=" . $l . "\" target=\"_blank\">" . htmlspecialchars($line) . "</a></span>\n");
        } else {
            echo(htmlspecialchars($line) . "\n");
        }
    }

    echo('</code></pre>');
    die();
}

$allfiles = list_all_file(NV_ROOTDIR);

$hook_sys = array();
$hook_modules = array();

foreach ($allfiles as $filepath) {
    $filecontents = file_get_contents(NV_ROOTDIR . '/' . $filepath);

    unset($m);
    preg_match_all('/nv\_apply\_hook[\s]*\((\$module\_name|""|\'\')[\s]*\,[\s]*(\'|")([a-zA-Z0-9\_]+)(\'|")[\s]*\,/', $filecontents, $m);
    if (!empty($m[1])) {
        foreach ($m[1] as $k => $v) {
            $hook_tag = $m[3][$k];
            $hook_data = array(
                'file' => $filepath,
                'code' => $m[0][$k]
            );
            if ($m[1][$k] == '$module_name') {
                $hook_module = explode('/', $filepath);
                $hook_module = $hook_module[1];
                if (!isset($hook_modules[$hook_module])) {
                    $hook_modules[$hook_module] = array();
                }
                $hook_modules[$hook_module][$hook_tag] = $hook_data;
            } else {
                // Hook hệ thống
                $hook_sys[$hook_tag] = $hook_data;
            }
        }
    }
}

echo("<h1 style=\"margin:0;padding:0;\">====== Danh sách các hook của NukeViet ======</h1>
> Khái niệm hook chỉ có từ NukeViet 5 trở đi

<h2 style=\"margin:0;padding:0;\">===== Hook của hệ thống =====</h2>
");

ksort($hook_sys);
ksort($hook_modules);
foreach ($hook_modules as $mod => $modData) {
    ksort($hook_modules[$mod]);
}

foreach ($hook_sys as $tag => $data) {
    echo("  * [[<a style=\"text-decoration: none;\" href=\"?f=" . urlencode($data['file']) . "&amp;c=" . urlencode($data['code']) . "#highlight\" target=\"_blank\">codex:hooks-reference:" . $tag . "|" . $tag . "</a>]]\n");
}

foreach ($hook_modules as $module => $datas) {
    echo("\n<h2 style=\"margin:0;padding:0;\">===== Hook của module " . $module . " =====</h2>\n");
    foreach ($datas as $tag => $data) {
        echo("  * [[<a style=\"text-decoration: none;\" href=\"?f=" . urlencode($data['file']) . "&amp;c=" . urlencode($data['code']) . "#highlight\" target=\"_blank\">codex:hooks-reference:" . $module . ":" . $tag . "|" . $tag . "</a>]]\n");
    }
}

echo("\n");
echo('</code></pre>');
