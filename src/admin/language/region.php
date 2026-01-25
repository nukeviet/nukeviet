<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('region_settings');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('region.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$format_post = [
    'd/m/Y',
    'm/d/Y',
    'Y/m/d'
];
$format_get = [
    'd-m-Y',
    'm-d-Y',
    'Y-m-d'
];

if ($nv_Request->get_title('saveform', 'post', '') === NV_CHECK_SESSION) {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];

    $array = [];
    $array['decimal_symbol'] = nv_substr($nv_Request->get_title('decimal_symbol', 'post', ''), 0, 1);
    $array['decimal_length'] = $nv_Request->get_absint('decimal_length', 'post', 0);
    $array['thousand_symbol'] = nv_substr($nv_Request->get_title('thousand_symbol', 'post', ''), 0, 1);
    $array['leading_zero'] = (int) $nv_Request->get_bool('leading_zero', 'post', false);
    $array['trailing_zero'] = (int) $nv_Request->get_bool('trailing_zero', 'post', false);

    $array['currency_symbol'] = nv_substr($nv_Request->get_title('currency_symbol', 'post', ''), 0, 10);
    $array['currency_display'] = $nv_Request->get_absint('currency_display', 'post', 0);
    $array['currency_decimal_symbol'] = nv_substr($nv_Request->get_title('currency_decimal_symbol', 'post', ''), 0, 1);
    $array['currency_thousand_symbol'] = nv_substr($nv_Request->get_title('currency_thousand_symbol', 'post', ''), 0, 1);
    $array['currency_decimal_length'] = $nv_Request->get_absint('currency_decimal_length', 'post', 0);
    $array['currency_trailing_zero'] = (int) $nv_Request->get_bool('currency_trailing_zero', 'post', false);

    $array['date_short'] = nv_substr($nv_Request->get_title('date_short', 'post', ''), 0, 50);
    $array['date_long'] = nv_substr($nv_Request->get_title('date_long', 'post', ''), 0, 50);
    $array['first_day_of_week'] = (int) $nv_Request->get_bool('first_day_of_week', 'post', false);

    $array['time_short'] = nv_substr($nv_Request->get_title('time_short', 'post', ''), 0, 50);
    $array['time_long'] = nv_substr($nv_Request->get_title('time_long', 'post', ''), 0, 50);
    $array['am_char'] = nv_substr($nv_Request->get_title('am_char', 'post', ''), 0, 50);
    $array['pm_char'] = nv_substr($nv_Request->get_title('pm_char', 'post', ''), 0, 50);

    $array['date_get'] = nv_substr($nv_Request->get_title('date_get', 'post', ''), 0, 50);
    $array['date_post'] = nv_substr($nv_Request->get_title('date_post', 'post', ''), 0, 50);

    if ($array['decimal_length'] > 9) {
        $array['decimal_length'] = 9;
    }
    if ($array['currency_decimal_length'] > 9) {
        $array['currency_decimal_length'] = 9;
    }
    if ($array['currency_display'] < 0 or $array['currency_display'] > 3) {
        $array['currency_display'] = 0;
    }

    if (empty($array['decimal_symbol'])) {
        $respon['input'] = 'decimal_symbol';
        $respon['tab'] = 'link-numbers';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['thousand_symbol'])) {
        $respon['input'] = 'thousand_symbol';
        $respon['tab'] = 'link-numbers';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['currency_symbol'])) {
        $respon['input'] = 'currency_symbol';
        $respon['tab'] = 'link-currency';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['currency_decimal_symbol'])) {
        $respon['input'] = 'currency_decimal_symbol';
        $respon['tab'] = 'link-currency';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['currency_thousand_symbol'])) {
        $respon['input'] = 'currency_thousand_symbol';
        $respon['tab'] = 'link-currency';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['date_short'])) {
        $respon['input'] = 'date_short';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['date_long'])) {
        $respon['input'] = 'date_long';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['date_get'])) {
        $respon['input'] = 'date_get';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['date_post'])) {
        $respon['input'] = 'date_post';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (!in_array($array['date_get'], $format_get)) {
        $respon['input'] = 'date_get';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getModule('date_getpost_error');
        nv_jsonOutput($respon);
    }
    if (!in_array($array['date_post'], $format_post)) {
        $respon['input'] = 'date_post';
        $respon['tab'] = 'link-date';
        $respon['mess'] = $nv_Lang->getModule('date_getpost_error');
        nv_jsonOutput($respon);
    }
    if (empty($array['time_short'])) {
        $respon['input'] = 'time_short';
        $respon['tab'] = 'link-time';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['time_long'])) {
        $respon['input'] = 'time_long';
        $respon['tab'] = 'link-time';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['am_char'])) {
        $respon['input'] = 'am_char';
        $respon['tab'] = 'link-time';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }
    if (empty($array['pm_char'])) {
        $respon['input'] = 'pm_char';
        $respon['tab'] = 'link-time';
        $respon['mess'] = $nv_Lang->getGlobal('required_invalid');
        nv_jsonOutput($respon);
    }

    $array['jsdate_get'] = str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yyyy'], $array['date_get']);
    $array['jsdate_post'] = str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yyyy'], $array['date_post']);

    $region = $global_config['region'] ?? [];
    $region[NV_LANG_DATA] = $array;
    $region = json_encode($region, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    $sth->bindValue(':config_name', 'region', PDO::PARAM_STR);
    $sth->bindParam(':config_value', $region, PDO::PARAM_STR);
    $sth->execute();

    nv_save_file_config_global();

    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    nv_jsonOutput($respon);
}

$array = isset($global_config['region'], $global_config['region'][NV_LANG_DATA]) ? $global_config['region'][NV_LANG_DATA] : ($nv_default_regions[NV_LANG_DATA] ?? $nv_default_regions['en']);

$tabs = ['numbers', 'currency', 'date', 'time'];
$tab = $nv_Request->get_title('tab', 'get', '');
if (!in_array($tab, $tabs)) {
    $tab = $tabs[0];
}
$tpl->assign('TAB', $tab);
$tpl->assign('DATA', $array);
$tpl->assign('FORMAT_GET', $format_get);
$tpl->assign('FORMAT_POST', $format_post);

$contents = $tpl->fetch('region.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
