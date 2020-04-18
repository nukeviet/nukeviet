<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$array = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $array['bodytext'] = nv_editor_nl2br($nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS));
    $array['sendcopymode'] = $nv_Request->get_int('sendcopymode', 'post', 0);
    if ($array['sendcopymode'] != 0 and $array['sendcopymode'] != 1) {
        $array['sendcopymode'] = 0;
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value=:config_value WHERE config_name=:config_name AND lang = '" . NV_LANG_DATA . "' AND module='" . $module_name . "'");
    foreach ($array as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR, strlen($config_value));
        $sth->execute();
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config module', '', $admin_info['userid']);
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

$array = $module_config[$module_name];

$array['bodytext'] = nv_htmlspecialchars(nv_editor_br2nl($array['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $array['bodytext'] = nv_aleditor('bodytext', '100%', '300px', $array['bodytext']);
} else {
    $array['bodytext'] = "<textarea style=\"width: 100%\" name=\"bodytext\" id=\"bodytext\" cols=\"20\" rows=\"8\" class=\"form-control\">" . $array['bodytext'] . "</textarea>";
}

$xtpl->assign('DATA', $array);

for ($i = 0; $i <= 1; ++$i) {
    $sendcopymode = array(
        'key' => $i,
        'title' => $lang_module['config_sendcopymode' . $i],
        'selected' => $i == $array['sendcopymode'] ? ' selected="selected"' : ''
    );
    $xtpl->assign('SENDCOPYMODE', $sendcopymode);
    $xtpl->parse('main.sendcopymode');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
