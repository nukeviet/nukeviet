<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];

$skins = nv_scandir(NV_ROOTDIR . "/images/jwplayer/skin/", "/^[a-zA-Z0-9\_\-\.]+\.zip$/", 1);

$array_config = array();
if ($nv_Request->isset_request('submit', 'post')) {
    $array_config['liketool'] = $nv_Request->get_int('liketool', 'post', 0);
    $array_config['viewtype'] = $nv_Request->get_title('viewtype', 'post', 'viewlist');
    $array_config['otherClipsNum'] = $nv_Request->get_int('otherClipsNum', 'post', 0);
    $array_config['playerAutostart'] = $nv_Request->get_int('playerAutostart', 'post', 0);
    $array_config['playerSkin'] = $nv_Request->get_title('playerSkin', 'post', '', 1);
    $array_config['playerMaxWidth'] = $nv_Request->get_int('playerMaxWidth', 'post', 0);
    $array_config['idhomeclips'] = $nv_Request->get_int('idhomeclips', 'post', 0);
    $array_config['clean_title_video'] = $nv_Request->get_int('clean_title_video', 'post', 0);
    if (!in_array($array_config['playerSkin'] . ".zip", $skins)) $array_config['playerSkin'] = "";
    if ($array_config['playerMaxWidth'] < 50 or $array_config['playerMaxWidth'] > 1000) $array_config['playerMaxWidth'] = 640;

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$configMods = array();
$configMods = $module_config[$module_name];
$configMods['playerAutostart_checked'] = ($configMods['playerAutostart'] == 1) ? ' checked="checked"' : '';
$configMods['ck_liketool'] = ($configMods['liketool'] == 1) ? ' checked="checked"' : '';

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('CONFIGMODULE', $configMods);
$xtpl->assign('CLEAN_TITLE_VIDEO', $configMods['clean_title_video']);

$sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip ORDER BY addtime DESC LIMIT 100";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $row['select'] = ($row['id'] == $configMods['idhomeclips']) ? ' selected="selected"' : '';
    $xtpl->assign('VHOME', $row);
    $xtpl->parse('main.idhomeclips');
}

for ($i = 10; $i <= 50; ++$i) {
    $sel = $i == $configMods['otherClipsNum'] ? " selected=\"selected\"" : "";
    $xtpl->assign('NUMS', array(
        'value' => $i,
        'select' => $sel
    ));
    $xtpl->parse('main.otherClipsNum');
}

foreach ($skins as $skin) {
    $skin = substr($skin, 0, -4);
    $sel = $skin == $configMods['playerSkin'] ? " selected=\"selected\"" : "";
    $xtpl->assign('SKIN', array(
        'value' => $skin,
        'select' => $sel
    ));
    $xtpl->parse('main.playerSkin');
}

$array_viewtype = array(
    'viewlist' => $lang_module['viewtype_viewlist'],
    'viewgrid' => $lang_module['viewtype_viewgrid']
);
foreach ($array_viewtype as $index => $value) {
    $sl = $index == $configMods['viewtype'] ? 'selected="selected"' : '';
    $xtpl->assign('VIEWTYPE', array(
        'index' => $index,
        'value' => $value,
        'selected' => $sl
    ));
    $xtpl->parse('main.viewtype');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';