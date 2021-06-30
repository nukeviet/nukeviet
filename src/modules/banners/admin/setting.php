<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['setting'];
$array_config = [];
if ($nv_Request->isset_request('submit', 'post')) {
    $array_config['captcha_type'] = $nv_Request->get_string('captcha_type', 'post', '');

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = '" . $module_name . "' AND config_name = :config_name");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config module', '', $admin_info['userid']);
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$array_config = $module_config[$module_name];

$xtpl = new XTemplate('setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DATA', $array_config);

$captcha_types = [
    '',
    'captcha',
    'recaptcha'
];
foreach ($captcha_types as $type) {
    $captcha_type = [
        'key' => $type,
        'selected' => $array_config['captcha_type'] == $type ? ' selected="selected"' : '',
        'title' => $lang_module['captcha_type_' . $type]
    ];
    $xtpl->assign('CAPTCHATYPE', $captcha_type);
    $xtpl->parse('main.captcha_type');
}

$is_recaptcha_note = empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']);
$xtpl->assign('IS_RECAPTCHA_NOTE', (int) $is_recaptcha_note);
$xtpl->assign('RECAPTCHA_NOTE', $is_recaptcha_note ? sprintf($lang_module['captcha_type_recaptcha_note'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=settings&amp;' . NV_OP_VARIABLE . '=security&amp;selectedtab=2') : '');
if (!$is_recaptcha_note or $array_config['captcha_type'] != 'recaptcha') {
    $xtpl->parse('main.recaptcha_note_hide');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
