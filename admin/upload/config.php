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

$array_logo_position = [
    'bottomRight' => $lang_module['logoposbottomright'],
    'bottomLeft' => $lang_module['logoposbottomleft'],
    'bottomCenter' => $lang_module['logoposbottomcenter'],
    'centerRight' => $lang_module['logoposcenterright'],
    'centerLeft' => $lang_module['logoposcenterleft'],
    'centerCenter' => $lang_module['logoposcentercenter'],
    'topRight' => $lang_module['logopostopright'],
    'topLeft' => $lang_module['logopostopleft'],
    'topCenter' => $lang_module['logopostopcenter']
];

if ($nv_Request->isset_request('save', 'post')) {
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

    if ((in_array('all', $autologomod, true))) {
        $autologomod = 'all';
    } else {
        $autologomod = array_intersect($autologomod, array_keys($site_mods));
        $autologomod = implode(',', $autologomod);
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'upload_logo'");
    $sth->bindParam(':config_value', $upload_logo, PDO::PARAM_STR);
    $sth->execute();

    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize1 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize1'");
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize2 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize2'");
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologosize3 . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologosize3'");
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $autologomod . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'autologomod'");
    $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $upload_logo_pos . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'upload_logo_pos'");

    $nv_Cache->delAll();

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $lang_module['configlogo'];

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

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('ADMIN_THEME', $global_config['module_theme']);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('OP', $op);
$xtpl->assign('AUTOLOGOSIZE', $array_autologosize);

$a = 0;
$xtpl->assign('CLASS', '');

if ($global_config['autologomod'] == 'all') {
    $autologomod = [];
} else {
    $autologomod = explode(',', $global_config['autologomod']);
}

foreach ($site_mods as $mod => $value) {
    if (is_dir(NV_UPLOADS_REAL_DIR . '/' . $value['module_upload'])) {
        ++$a;
        $xtpl->assign('MOD_VALUE', $mod);
        $xtpl->assign('LEV_CHECKED', (in_array($mod, $autologomod, true)) ? 'checked="checked"' : '');
        $xtpl->assign('CUSTOM_TITLE', $value['custom_title']);
        $xtpl->parse('main.loop1.loop2');

        if ($a % 3 == 0) {
            $xtpl->parse('main.loop1');
        }
    }
}

++$a;
$xtpl->assign('MOD_VALUE', 'all');
$xtpl->assign('LEV_CHECKED', ($global_config['autologomod'] == 'all') ? 'checked="checked"' : '');
$xtpl->assign('CUSTOM_TITLE', '<strong>' . $lang_module['autologomodall'] . '</strong>');

$xtpl->parse('main.loop1.loop2');
$xtpl->parse('main.loop1');

foreach ($array_logo_position as $pos => $posName) {
    $upload_logo_pos = [
        'key' => $pos,
        'title' => $posName,
        'selected' => $pos == $global_config['upload_logo_pos'] ? ' selected="selected"' : ''
    ];

    $xtpl->assign('UPLOAD_LOGO_POS', $upload_logo_pos);
    $xtpl->parse('main.upload_logo_pos');
}

$xtpl->parse('main');

$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
