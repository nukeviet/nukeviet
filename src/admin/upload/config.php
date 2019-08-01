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

$array_logo_position = [
    'bottomRight' => $nv_Lang->getModule('logoposbottomright'),
    'bottomLeft' => $nv_Lang->getModule('logoposbottomleft'),
    'bottomCenter' => $nv_Lang->getModule('logoposbottomcenter'),
    'centerRight' => $nv_Lang->getModule('logoposcenterright'),
    'centerLeft' => $nv_Lang->getModule('logoposcenterleft'),
    'centerCenter' => $nv_Lang->getModule('logoposcentercenter'),
    'topRight' => $nv_Lang->getModule('logopostopright'),
    'topLeft' => $nv_Lang->getModule('logopostopleft'),
    'topCenter' => $nv_Lang->getModule('logopostopcenter')
];

if ($nv_Request->isset_request('submit', 'post')) {
    $upload_logo = $nv_Request->get_title('upload_logo', 'post', '');

    if (!empty($upload_logo) and !nv_is_url($upload_logo) and nv_is_file($upload_logo)) {
        $lu = strlen(NV_BASE_SITEURL);
        $upload_logo = substr($upload_logo, $lu);
    } else {
        $upload_logo = '';
    }

    $upload_logo_pos = $nv_Request->get_title('upload_logo_pos', 'post', '');
    if (!isset($array_logo_position[$upload_logo_pos])) {
        $upload_logo_pos = 'bottomRight';
    }

    $autologosize1 = $nv_Request->get_int('autologosize1', 'post', 50);
    $autologosize2 = $nv_Request->get_int('autologosize2', 'post', 40);
    $autologosize3 = $nv_Request->get_int('autologosize3', 'post', 30);

    $autologomod = $nv_Request->get_array('autologomod', 'post');

    if ((in_array('all', $autologomod))) {
        $autologomod = 'all';
    } else {
        $autologomod = array_intersect($autologomod, array_keys($site_mods));
        $autologomod = implode(',', $autologomod);
    }

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'upload_logo'");
    $sth->bindParam(':config_value', $upload_logo, PDO::PARAM_STR);
    $sth->execute();

    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize1 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize1'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize2 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize2'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize3 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize3'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologomod . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologomod'");
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $upload_logo_pos . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'upload_logo_pos'");

    $nv_Cache->delAll();

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $nv_Lang->getModule('configlogo');

if (!empty($global_config['upload_logo']) and !nv_is_url($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
    $upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
} else {
    $upload_logo = '';
}

$array_autologosize = [
    'upload_logo' => $upload_logo,
    'autologosize1' => $global_config['autologosize1'],
    'autologosize2' => $global_config['autologosize2'],
    'autologosize3' => $global_config['autologosize3']
];

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);

$tpl->assign('DATA', $array_autologosize);

if ($global_config['autologomod'] == 'all') {
    $autologomod = [];
} else {
    $autologomod = explode(',', $global_config['autologomod']);
}

$array_autolog_mods = [];
foreach ($site_mods as $mod => $value) {
    if (is_dir(NV_UPLOADS_REAL_DIR . '/' . $value['module_upload'])) {
        $array_autolog_mods[] = [
            'key' => $mod,
            'title' => $value['custom_title']
        ];
    }
}

$tpl->assign('AUTOLOG_MOD_TEXT', $global_config['autologomod']);
$tpl->assign('AUTOLOG_MOD', $autologomod);
$tpl->assign('AUTOLOG_MODS', $array_autolog_mods);
$tpl->assign('UPLOAD_LOGO_POS', $global_config['upload_logo_pos']);
$tpl->assign('ARRAY_LOGO_POSITION', $array_logo_position);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
