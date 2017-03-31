<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (! defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$contents = '';

$array = $nv_Request->get_string('data', 'post', '');
$array = $array ? nv_base64_decode($array) : '';
if ($array and is_serialized_string($array)) {
    $array = @unserialize($array);
} else {
    $array = array();
}

$request = array();
$request['id'] = isset($array['id']) ? intval($array['id']) : 0;
$request['fid'] = isset($array['compatible']['id']) ? intval($array['compatible']['id']) : 0;

// Fixed request
$request['lang'] = NV_LANG_INTERFACE;
$request['basever'] = $global_config['version'];
$request['mode'] = 'download';

if (empty($request['id']) or empty($request['fid']) or ! isset($array['tid'])) {
    $contents = "ERR|" . $lang_module['download_error_preparam'];
} else {
    $filename = NV_TEMPNAM_PREFIX . 'auto_' . NV_CHECK_SESSION . '.zip';

    $NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);

    $args = array(
        'headers' => array(
            'Referer' => NUKEVIET_STORE_APIURL,
        ),
        'stream' => true,
        'filename' => NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename,
        'body' => $request,
        'timeout' => 0
    );

    // Delete temp file if exists
    if (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename)) {
        @nv_deletefile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $filename);
    }

    $array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);

    if (! empty(NukeViet\Http\Http::$error)) {
        $contents = "ERR|" . nv_http_get_lang(NukeViet\Http\Http::$error);
    } elseif (empty($array['filename']) or ! file_exists($array['filename']) or filesize($array['filename']) <= 0) {
        $contents = "ERR|" . $lang_module['download_error_save'];
    } else {
        $contents = 'OK|' . $filename;
    }
}

echo $contents;
