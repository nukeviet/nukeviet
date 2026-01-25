<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    exit('Stop!!!');
}

$contents = '';

$array = $nv_Request->get_string('data', 'post', '');
$array = empty($array) ? [] : json_decode(nv_base64_decode($array), true);
if (!is_array($array)) {
    $array = [];
}

$request = [];
$request['id'] = isset($array['id']) ? (int) ($array['id']) : 0;
$request['fid'] = isset($array['compatible']['id']) ? (int) ($array['compatible']['id']) : 0;

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];
$request['mode'] = 'download';

if (empty($request['id']) or empty($request['fid']) or !isset($array['tid'])) {
    $contents = 'ERR|' . $nv_Lang->getModule('download_error_preparam');
} else {
    $filename = NV_TEMPNAM_PREFIX . 'auto_' . NV_CHECK_SESSION . '.zip';

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);

    $args = [
        'headers' => [
            'Referer' => NUKEVIET_STORE_APIURL,
        ],
        'stream' => true,
        'filename' => NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename,
        'body' => $request,
        'timeout' => 0
    ];

    // Delete temp file if exists
    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
    }

    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

    if (!empty(NukeViet\Http\Http::$error)) {
        $contents = 'ERR|' . nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['filename']) or !file_exists($array['filename']) or filesize($array['filename']) <= 0) {
        $contents = 'ERR|' . $nv_Lang->getModule('download_error_save');
    } else {
        $contents = 'OK|' . $filename;
    }
}

nv_htmlOutput($contents);
