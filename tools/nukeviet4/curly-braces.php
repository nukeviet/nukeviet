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
                if (preg_match('/\.php$/', $v)) {
                    $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                }
            }
        }
    }

    return $file_list;
}

define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '')));

$allfiles = list_all_file(NV_ROOTDIR);

foreach ($allfiles as $filepath) {
    $is_line_by_line_check = 0;
    $handle_read = fopen(NV_ROOTDIR . '/' . $filepath, "r");
    $line = 0;
    while (($buffer = fgets($handle_read, 4096)) !== false) {
        $line++;
        if (preg_match('/\$([a-zA-Z0-9\_\n\s\r\[\]]+)\{/s', $buffer)) {
            echo("Error in line " . $line . ": " . $filepath . "\n");
            $is_line_by_line_check++;
        }
    }
    if (!feof($handle_read)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle_read);
    if ($is_line_by_line_check == 0) {
        $filecontents = file_get_contents(NV_ROOTDIR . '/' . $filepath);
        if (preg_match('/\$([a-zA-Z0-9\_\n\s\r\[\]]+)\{/s', $filecontents)) {
            echo("Error in: " . $filepath . "\n");
        }
    }
}

echo("OK\n");
