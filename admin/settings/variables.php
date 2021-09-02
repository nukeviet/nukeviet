<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$errormess = '';
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$sameSite_array = [
    'Empty' => $lang_module['cookie_SameSite_Empty'],
    'Lax' => $lang_module['cookie_SameSite_Lax'],
    'Strict' => $lang_module['cookie_SameSite_Strict'],
    'None' => $lang_module['cookie_SameSite_None']
];
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $preg_replace = ['pattern' => '/[^a-zA-Z0-9\_]/', 'replacement' => ''];

    $array_config_global = [];
    $array_config_global['cookie_prefix'] = nv_substr($nv_Request->get_title('cookie_prefix', 'post', '', 0, $preg_replace), 0, 255);
    $array_config_global['session_prefix'] = nv_substr($nv_Request->get_title('session_prefix', 'post', '', 0, $preg_replace), 0, 255);
    $array_config_global['cookie_secure'] = (int) $nv_Request->get_bool('cookie_secure', 'post', 0);
    $array_config_global['cookie_httponly'] = (int) $nv_Request->get_bool('cookie_httponly', 'post', 0);
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

    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$global_config['checkss'] = $checkss;

$xtpl = new XTemplate('variables.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('NV_LIVE_COOKIE_TIME', round(NV_LIVE_COOKIE_TIME / 86400));
$xtpl->assign('NV_LIVE_SESSION_TIME', round(NV_LIVE_SESSION_TIME / 60));
$xtpl->assign('CHECKBOX_COOKIE_SECURE', ($global_config['cookie_secure'] == 1) ? ' checked="checked"' : '');
$xtpl->assign('CHECKBOX_COOKIE_HTTPONLY', ($global_config['cookie_httponly'] == 1) ? ' checked="checked"' : '');

if ($errormess != '') {
    $xtpl->assign('ERROR', $errormess);
    $xtpl->parse('main.error');
}

foreach ($sameSite_array as $val => $note) {
    if (empty($global_config['cookie_SameSite'])) {
        $global_config['cookie_SameSite'] = 'Empty';
    }

    $sameSite = [
        'val' => $val,
        'note' => $note,
        'checked' => $val == $global_config['cookie_SameSite'] ? ' checked="checked"' : ''
    ];
    $xtpl->assign('SAMESITE', $sameSite);
    $xtpl->parse('main.SameSite');
}
$xtpl->parse('main');
$content = $xtpl->text('main');

$page_title = $lang_module['variables'];
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
