<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_ADMIN')) {
    exit('Stop!!!');
}

if (
    (isset($_GET[NV_LANG_VARIABLE]) and is_array($_GET[NV_LANG_VARIABLE])) or
    (isset($_GET[NV_NAME_VARIABLE]) and is_array($_GET[NV_NAME_VARIABLE])) or
    (isset($_GET[NV_OP_VARIABLE]) and is_array($_GET[NV_OP_VARIABLE]))
) {
    http_response_code(403);
    trigger_error('Request URI is not valid!', E_USER_ERROR);
}

// Fix rewrite IIS 7 with Unicode Permalinks
if (isset($_SERVER['UNENCODED_URL'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['UNENCODED_URL'];
}

$base_siteurl = str_replace(DIRECTORY_SEPARATOR, '/', pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
$base_siteurl = preg_replace('/[\/]+$/', '', $base_siteurl);
$base_siteurl = preg_replace('/^[\/]*(.*)$/', '/\\1', $base_siteurl);
$base_siteurl = preg_replace('#/index\.php(.*)$#', '', $base_siteurl);
$base_siteurl .= '/';
$base_siteurl_quote = nv_preg_quote($base_siteurl);

$request_uri = preg_replace('/(' . $base_siteurl_quote . ')index\.php\//', '\\1', $_SERVER['REQUEST_URI']);
$request_uri = parse_url($request_uri);
if (!isset($request_uri['path'])) {
    nv_redirect_location($base_siteurl);
}
$request_uri_query = isset($request_uri['query']) ? urldecode($request_uri['query']) : '';
$request_uri = urldecode($request_uri['path']);

if (preg_match('/^' . $base_siteurl_quote . '([a-z0-9\-\_\.\/\+]+)' . nv_preg_quote($global_config['rewrite_endurl']) . '$/i', $request_uri, $matches)) {
    // Kiểm tra rewrite dạng /vi/module/func...
    $request_uri_array = explode('/', $matches[1], 3);

    if (in_array($request_uri_array[0], array_keys($language_array), true)) {
        $_GET[NV_LANG_VARIABLE] = $request_uri_array[0];

        if (isset($request_uri_array[1][0])) {
            $_GET[NV_NAME_VARIABLE] = $request_uri_array[1];

            if (isset($request_uri_array[2][0])) {
                $_GET[NV_OP_VARIABLE] = $request_uri_array[2];
            }
        }
    } elseif (isset($request_uri_array[0][0])) {
        $_GET[NV_NAME_VARIABLE] = $request_uri_array[0];

        if (isset($request_uri_array[1][0])) {
            $lop = strlen($request_uri_array[0]) + 1;
            $_GET[NV_OP_VARIABLE] = substr($matches[1], $lop);
        }
    }
} elseif (preg_match('/<(.*)s(.*)c(.*)r(.*)i(.*)p(.*)t(.*)>/i', urldecode($request_uri . $request_uri_query))) {
    nv_redirect_location($base_siteurl);
}

unset($base_siteurl, $request_uri, $request_uri_array, $matches, $lop);
