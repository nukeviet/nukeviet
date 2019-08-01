<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_LANG')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('countries');

$array_lang_setup = [];
$array_lang_setup[] = ['', $nv_Lang->getModule('site_lang')];

$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$result = $db->query($sql);
while (list($lang_i) = $result->fetch(3)) {
    if (in_array($lang_i, $global_config['allow_sitelangs'])) {
        $array_lang_setup[$lang_i] = [$lang_i, $language_array[$lang_i]['name']];
    }
}

if ($nv_Request->isset_request('countries', 'post') == 1) {
    $post = $nv_Request->get_typed_array('countries', 'post', 'string', []);

    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if (!defined('NV_MAINFILE')) {\n    die('Stop!!!');\n}\n\n";
    $content_config .= "\$config_geo = [];\n";

    foreach ($countries as $key => $value) {
        if (in_array($post[$key], $global_config['allow_sitelangs'])) {
            $content_config .= "\$config_geo['" . $key . "'] = '" . $post[$key] . "';\n";
        }
    }

    file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php', $content_config, LOCK_EX);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

include NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php' ;

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('CONFIG_GEO', $config_geo);
$tpl->assign('COUNTRIES', $countries);
$tpl->assign('ARRAY_LANG_SETUP', $array_lang_setup);

$contents = $tpl->fetch('countries.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
