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
$logo_exts = nv_editable_imgexts();

if ($nv_Request->isset_request('save', 'post')) {
    $data = [
        'upload_logo' => $nv_Request->get_title('upload_logo', 'post', ''),
        'upload_logo_pos' => $nv_Request->get_title('upload_logo_pos', 'post', ''),
        'autologosize1' => $nv_Request->get_int('autologosize1', 'post', 50),
        'autologosize2' => $nv_Request->get_int('autologosize2', 'post', 40),
        'autologosize3' => $nv_Request->get_int('autologosize3', 'post', 30),
        'autologomod' => $nv_Request->get_typed_array('autologomod', 'post', 'title', []),
        'tinify_active' => (int) $nv_Request->get_bool('tinify_active', 'post', false),
        'tinify_api' => $nv_Request->get_title('tinify_api', 'post', '')
    ];

    if (!empty($data['upload_logo']) and !nv_is_url($data['upload_logo']) and nv_is_file($data['upload_logo']) and in_array(nv_getextension($data['upload_logo']), $logo_exts, true)) {
        $lu = strlen(NV_BASE_SITEURL);
        $data['upload_logo'] = substr($data['upload_logo'], $lu);
    } else {
        $data['upload_logo'] = '';
    }

    if (!isset($array_logo_position[$data['upload_logo_pos']])) {
        $data['upload_logo_pos'] = 'bottomRight';
    }

    if ((in_array('all', $data['autologomod'], true))) {
        $data['autologomod'] = 'all';
    } else {
        $data['autologomod'] = array_intersect($data['autologomod'], array_keys($site_mods));
        $data['autologomod'] = implode(',', $data['autologomod']);
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = :config_name");
    foreach ($data as $config_name => $config_value) {
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->execute();
    }

    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . NV_CURRENTTIME . "' WHERE lang = 'sys' AND module = 'global' AND config_name = 'timestamp'");
    nv_save_file_config_global();

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

$page_title = $nv_Lang->getModule('imgconfig');

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

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'is_dir', 'is_dir');
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('GCONFIG', $global_config);
$tpl->assign('AUTOLOGOSIZE', $array_autologosize);
$tpl->assign('LOGO_POSITION', $array_logo_position);
$tpl->assign('SITE_MODS', $site_mods);
$tpl->assign('LOGO_EXTS', $logo_exts);

if ($global_config['autologomod'] == 'all') {
    $autologomod = [];
} else {
    $autologomod = explode(',', $global_config['autologomod']);
}
$tpl->assign('AUTOLOGOMOD', $autologomod);
$tpl->assign('NO_TINIFY', !class_exists('Tinify\Tinify'));

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
