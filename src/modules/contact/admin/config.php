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
    $array = [];
    $array['bodytext'] = nv_editor_nl2br($nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS));
    $array['sendcopymode'] = (int) $nv_Request->get_bool('sendcopymode', 'post', 0);
    $array['silent_mode'] = (int) $nv_Request->get_bool('silent_mode', 'post', 0);
    $array['feedback_phone'] = $nv_Request->get_int('feedback_phone', 'post', 0);
    $array['feedback_address'] = $nv_Request->get_int('feedback_address', 'post', 0);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value=:config_value WHERE config_name=:config_name AND lang = '" . NV_LANG_DATA . "' AND module='" . $module_name . "'");
    foreach ($array as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR, strlen($config_value));
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config module', '', $admin_info['userid']);
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$page_title = $nv_Lang->getModule('config');

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$array = $module_config[$module_name];

$array['bodytext'] = nv_htmlspecialchars(nv_editor_br2nl($array['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodytext'] = nv_aleditor('bodytext', '100%', '150px', $array['bodytext'], 'Basic');
} else {
    $array['bodytext'] = '<textarea style="width: 100%" name="bodytext" id="bodytext" cols="20" rows="8" class="form-control">' . $array['bodytext'] . '</textarea>';
}
$array['silent_mode'] = !empty($module_config[$module_name]['silent_mode']) ? ' checked="checked"' : '';
$array['feedback_phone'] = !empty($module_config[$module_name]['feedback_phone']) ? (int) $module_config[$module_name]['feedback_phone'] : 0;
$array['feedback_address'] = !empty($module_config[$module_name]['feedback_address']) ? (int) $module_config[$module_name]['feedback_address'] : 0;

$xtpl->assign('DATA', $array);

for ($i = 0; $i <= 1; ++$i) {
    $sendcopymode = [
        'key' => $i,
        'title' => $nv_Lang->getModule('config_sendcopymode' . $i),
        'selected' => $i == $array['sendcopymode'] ? ' selected="selected"' : ''
    ];
    $xtpl->assign('SENDCOPYMODE', $sendcopymode);
    $xtpl->parse('main.sendcopymode');
}

for ($i = 0; $i <= 2; ++$i) {
    $xtpl->assign('PHONE', [
        'val' => $i,
        'sel' => $i == $array['feedback_phone'] ? ' selected="selected"' : '',
        'title' => $nv_Lang->getModule('option_' . $i)
    ]);
    $xtpl->parse('main.feedback_phone');

    $xtpl->assign('ADDRESS', [
        'val' => $i,
        'sel' => $i == $array['feedback_address'] ? ' selected="selected"' : '',
        'title' => $nv_Lang->getModule('option_' . $i)
    ]);
    $xtpl->parse('main.feedback_address');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
