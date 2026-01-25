<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$sameSite_array = [
    'Empty' => $nv_Lang->getModule('cookie_SameSite_Empty'),
    'Lax' => $nv_Lang->getModule('cookie_SameSite_Lax'),
    'Strict' => $nv_Lang->getModule('cookie_SameSite_Strict'),
    'None' => $nv_Lang->getModule('cookie_SameSite_None')
];
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $preg_replace = ['pattern' => '/[^a-zA-Z0-9\_]/', 'replacement' => ''];

    $array_config_global = [];
    $array_config_global['cookie_prefix'] = nv_substr($nv_Request->get_title('cookie_prefix', 'post', '', 0, $preg_replace), 0, 255);
    $array_config_global['session_prefix'] = nv_substr($nv_Request->get_title('session_prefix', 'post', '', 0, $preg_replace), 0, 255);
    $array_config_global['cookie_secure'] = (int) $nv_Request->get_bool('cookie_secure', 'post', false);
    $array_config_global['cookie_httponly'] = (int) $nv_Request->get_bool('cookie_httponly', 'post', false);
    $array_config_global['cookie_share'] = (int) $nv_Request->get_bool('cookie_share', 'post', false);
    $array_config_global['cookie_notice_popup'] = (int) $nv_Request->get_bool('cookie_notice_popup', 'post', false);
    $array_config_global['cookie_SameSite'] = $nv_Request->get_title('cookie_SameSite', 'post', '');
    if (!empty($array_config_global['cookie_SameSite']) and !isset($sameSite_array[$array_config_global['cookie_SameSite']])) {
        $array_config_global['cookie_SameSite'] = '';
    }
    $array_config_global['cookie_SameSite'] == 'Empty' && $array_config_global['cookie_SameSite'] = '';

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $array_config_define = [];
    $array_config_define['nv_live_cookie_time'] = 86400 * $nv_Request->get_int('nv_live_cookie_time', 'post', 1);
    $array_config_define['nv_live_session_time'] = 60 * $nv_Request->get_int('nv_live_session_time', 'post', 0);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'define' AND config_name = :config_name");
    foreach ($array_config_define as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$global_config['checkss'] = $checkss;

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('variables.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('DATA', $global_config);
$tpl->assign('NV_LIVE_COOKIE_TIME', round(NV_LIVE_COOKIE_TIME / 86400));
$tpl->assign('NV_LIVE_SESSION_TIME', round(NV_LIVE_SESSION_TIME / 60));
$tpl->assign('SAMESITE_ARRAY', $sameSite_array);

$contents = $tpl->fetch('variables.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
