<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

set_time_limit(0);

function list_all_file($dir = '', $base_dir = '')
{
    $file_list = [];

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

define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../src')));

$allfiles = list_all_file(NV_ROOTDIR);

foreach ($allfiles as $filepath) {
    $filecontents = file_get_contents(NV_ROOTDIR . '/' . $filepath);

    if (preg_match("/\\$(lang_global|lang_module|lang_block)[\s]*\[/", $filecontents)) {
        echo 'Check: ' . $filepath . "\n";
    }
}

echo "OK\n";
