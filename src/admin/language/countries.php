<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('countries');

$array_lang_setup = [];
$array_lang_setup[] = ['', $nv_Lang->getModule('site_lang')];

$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$result = $db->query($sql);
while ([$lang_i] = $result->fetch(3)) {
    if (in_array($lang_i, $global_config['allow_sitelangs'], true)) {
        $array_lang_setup[$lang_i] = [$lang_i, $language_array[$lang_i]['name']];
    }
}

// Lưu dữ liệu
if ($nv_Request->isset_request('checkss', 'post')) {
    $respon = [
        'status' => 'error',
        'mess' => '',
    ];

    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        $respon['mess'] = 'Session error!!!';
        nv_jsonOutput($respon);
    }

    $post = $nv_Request->get_typed_array('countries', 'post', 'string', []);

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined( 'NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
    $content_config .= "\$config_geo = [];\n";

    foreach ($countries as $key => $value) {
        if (in_array($post[$key], $global_config['allow_sitelangs'], true)) {
            $content_config .= "\$config_geo['" . $key . "'] = '" . $post[$key] . "';\n";
        }
    }

    file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php', $content_config, LOCK_EX);
    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    nv_jsonOutput($respon);
}

include NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php';

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('countries.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('COUNTRIES', $countries);
$tpl->assign('LANG_SETUP', $array_lang_setup);
$tpl->assign('CONFIG_GEO', $config_geo);

$contents = $tpl->fetch('countries.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
