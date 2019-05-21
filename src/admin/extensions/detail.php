<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getGlobal('mod_extensions');

$request = [];

// Fixed request
$request['lang'] = NV_LANG_DATA;
$request['basever'] = $global_config['version'];
$request['mode'] = 'detail';

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$request['id'] = $nv_Request->get_int('id', 'get', 0);

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);

// Debug
$args = [
    'headers' => [
        'Referer' => NUKEVIET_STORE_APIURL,
    ],
    'body' => $request
];

$array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
$array = !empty($array['body']) ? @unserialize($array['body']) : [];

$error = '';
if (!empty(NukeViet\Http\Http::$error)) {
    $error = nv_http_get_lang(NukeViet\Http\Http::$error);
} elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
    $error = $nv_Lang->getGlobal('error_valid_response');
} elseif (!empty($array['error']['message'])) {
    $error = $array['error']['message'];
}

$tpl->assign('ERROR', $error);
$tpl->assign('ALLOW_INSTALL', ($global_config['extension_setup'] == 2 or $global_config['extension_setup'] == 3));

// Show error
if (empty($error)) {
    $array = $array['data'];
    $array_files = $array['files'];
    $array_images = $array['image_demo'];
    unset($array['files'], $array['image_demo']);

    // Change some variable to display value
    $array['updatetime'] = nv_date("H:i d/m/Y", $array['updatetime']);
    $array['view_hits'] = number_format($array['view_hits'], 0, '.', '.');
    $array['download_hits'] = number_format($array['download_hits'], 0, '.', '.');
    $array['rating_text'] = sprintf($nv_Lang->getModule('rating_text_detail'), number_format($array['rating_totals'], 0, '.', '.'), number_format($array['rating_hits'], 0, '.', '.'));
    $array['compatible_class'] = empty($array['compatible']) ? 'text-danger' : 'text-success';
    $array['compatible_title'] = empty($array['compatible']) ? $nv_Lang->getModule('incompatible') : $nv_Lang->getModule('compatible');
    $array['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $array['id'];
    $array['price'] = $array['price'] ? (preg_replace("/\,0$/", '', number_format($array['price'], 1, ',', '.')) . ' ' . $array['currency']) : $nv_Lang->getModule('free');

    $tpl->assign('DATA', $array);
    $tpl->assign('ARRAY_IMAGES', $array_images);

    $array_files_show = [];
    foreach ($array_files as $file) {
        $file['compatible_class'] = empty($file['compatible']) ? 'text-danger' : 'text-success';
        $file['compatible_title'] = empty($file['compatible']) ? $nv_Lang->getModule('incompatible') : $nv_Lang->getModule('compatible');
        $file['install_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=install&amp;id=' . $array['id'] . '&amp;fid=' . $file['id'];
        $file['price'] = $file['price'] ? (preg_replace("/\,0$/", '', number_format($file['price'], 1, ',', '.')) . ' ' . $file['currency']) : $nv_Lang->getModule('free');

        $array_files_show[] = $file;
    }

    $tpl->assign('ARRAY_FILES', $array_files_show);
}

$contents = $tpl->fetch($op . '.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
