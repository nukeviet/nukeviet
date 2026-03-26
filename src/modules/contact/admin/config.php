<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $array = [];
    $array['bodytext'] = nv_editor_nl2br($nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS));
    $array['sendcopymode'] = (int) $nv_Request->get_bool('sendcopymode', 'post', 0);
    $array['silent_mode'] = (int) $nv_Request->get_bool('silent_mode', 'post', 0);
    $array['feedback_phone'] = $nv_Request->get_int('feedback_phone', 'post', 0);
    $array['feedback_address'] = $nv_Request->get_int('feedback_address', 'post', 0);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value = :config_value WHERE config_name = :config_name AND lang = :lang AND module = :module');
    $sth->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $sth->bindValue(':module', $module_name, PDO::PARAM_STR);
    foreach ($array as $config_name => $config_value) {
        $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config module', '', $admin_info['userid']);
    $nv_Cache->delMod('settings');
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
    ]);
}

$page_title = $nv_Lang->getModule('config');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$array = $module_config[$module_name];

$array['bodytext'] = nv_htmlspecialchars(nv_editor_br2nl($array['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodytext'] = nv_aleditor('bodytext', '100%', '150px', $array['bodytext'], 'Basic');
} else {
    $array['bodytext'] = '<textarea style="width: 100%" name="bodytext" id="bodytext" cols="20" rows="8" class="form-control">' . $array['bodytext'] . '</textarea>';
}
$array['silent_mode'] = !empty($module_config[$module_name]['silent_mode']) ? 1 : 0;
$array['feedback_phone'] = !empty($module_config[$module_name]['feedback_phone']) ? (int) $module_config[$module_name]['feedback_phone'] : 0;
$array['feedback_address'] = !empty($module_config[$module_name]['feedback_address']) ? (int) $module_config[$module_name]['feedback_address'] : 0;

$tpl->assign('DATA', $array);

// Thu thập options cho select boxes
$sendcopymode_options = [];
for ($i = 0; $i <= 1; ++$i) {
    $sendcopymode_options[] = [
        'key' => $i,
        'title' => $nv_Lang->getModule('config_sendcopymode' . $i)
    ];
}

$feedback_phone_options = [];
$feedback_address_options = [];
for ($i = 0; $i <= 2; ++$i) {
    $feedback_phone_options[] = [
        'val' => $i,
        'title' => $nv_Lang->getModule('option_' . $i)
    ];
    $feedback_address_options[] = [
        'val' => $i,
        'title' => $nv_Lang->getModule('option_' . $i)
    ];
}

$tpl->assign('SENDCOPYMODE_OPTIONS', $sendcopymode_options);
$tpl->assign('FEEDBACK_PHONE_OPTIONS', $feedback_phone_options);
$tpl->assign('FEEDBACK_ADDRESS_OPTIONS', $feedback_address_options);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
