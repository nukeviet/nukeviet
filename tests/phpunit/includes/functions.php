<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC.
 * All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

function _tests_default_server()
{
    $_SERVER['HTTP_HOST'] = NV_TESTS_DOMAIN;
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '';
    $_SERVER['SERVER_NAME'] = NV_TESTS_DOMAIN;
    $_SERVER['SERVER_PORT'] = '80';
    $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
    $_SERVER['HTTP_USER_AGENT'] = 'NUKEVIET CMS. Developed by VINADES. Url: http://nukeviet.vn';
    $_SERVER['SERVER_SOFTWARE'] = 'sd';
}

/**
 * Liệt kê tất cả các file PHP trừ file ngôn ngữ và thư mục vendor
 *
 * @param string $dir
 * @param string $base_dir
 * @return string[]
 */
function list_all_php_file($dir = '', $base_dir = '')
{
    $file_list = array();

    if (is_dir($dir)) {
        $array_filedir = scandir($dir);

        foreach ($array_filedir as $v) {
            if ($v == '.' or $v == '..') {
                continue;
            }

            if (is_dir($dir . '/' . $v)) {
                foreach (list_all_php_file($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                    $file_list[] = $file;
                }
            } else {
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
