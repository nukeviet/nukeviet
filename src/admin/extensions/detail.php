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

$page_title = $nv_Lang->getGlobal('mod_extensions');

$request = [];
$request['lang'] = NV_LANG_DATA;
$request['basever'] = $global_config['version'];
$request['mode'] = 'detail';
$request['id'] = $nv_Request->get_absint('id', 'get', 0);

$NV_Http = new NukeViet\Http\Http($global_config, NV_TEMP_DIR);
$args = [
    'headers' => [
        'Referer' => NUKEVIET_STORE_APIURL,
    ],
    'body' => $request
];

$array = $NV_Http->post(NUKEVIET_STORE_APIURL, $args);
$array = (is_array($array) and !empty($array['body'])) ? @unserialize($array['body']) : [];

$error = '';
if (!empty(NukeViet\Http\Http::$error)) {
    $error = nv_http_get_lang(NukeViet\Http\Http::$error);
} elseif (empty($array['status']) or !isset($array['error']) or !isset($array['data']) or !isset($array['pagination']) or !is_array($array['error']) or !is_array($array['data']) or !is_array($array['pagination']) or (!empty($array['error']) and (!isset($array['error']['level']) or empty($array['error']['message'])))) {
    $error = $nv_Lang->getGlobal('error_valid_response');
} elseif (!empty($array['error']['message'])) {
    $error = $array['error']['message'];
}
if (!empty($error)) {
    nv_htmlOutput(nv_theme_alert($nv_Lang->getGlobal('danger_level'), $error, 'danger'));
}

$page_title = $nv_Lang->getModule('detail_title', $array['data']['title']);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
$tpl->registerPlugin('modifier', 'dcurrency', 'nv_currency_format');
$tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('DATA', $array['data']);

$popup = $nv_Request->get_absint('popup', 'get', 0);
if ($popup) {
    $contents = $tpl->fetch('detail-popup.tpl');
} else {
    $contents = $tpl->fetch('detail.tpl');
}

include NV_ROOTDIR . '/includes/header.php';
echo $popup ? $contents : nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
