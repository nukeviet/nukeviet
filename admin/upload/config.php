<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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

    if (!empty($data['upload_logo']) and !nv_is_url($data['upload_logo']) and nv_is_file($data['upload_logo'])) {
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

    $nv_Cache->delAll();

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$page_title = $lang_module['imgconfig'];

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

$xtpl->assign('TINIFY_CHECKED', !empty($global_config['tinify_active']) ? 'checked="checked"' : '');
$xtpl->assign('TINIFY_KEY', !empty($global_config['tinify_api']) ? $global_config['tinify_api'] : '');
if (!class_exists('Tinify\Tinify')) {
    $xtpl->parse('main.tinify_class');
}

$xtpl->parse('main');

$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
