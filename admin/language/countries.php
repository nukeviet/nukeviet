<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $lang_module['countries'];

$array_lang_setup = [];
$array_lang_setup[] = ['', $lang_module['site_lang']];

$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$result = $db->query($sql);
while (list($lang_i) = $result->fetch(3)) {
    if (in_array($lang_i, $global_config['allow_sitelangs'], true)) {
        $array_lang_setup[$lang_i] = [$lang_i, $language_array[$lang_i]['name']];
    }
}

if ($nv_Request->isset_request('countries', 'post') == 1) {
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

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

include NV_ROOTDIR . '/' . NV_DATADIR . '/config_geo.php';

$xtpl = new XTemplate('countries.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);

$nb = 0;
foreach ($countries as $key => $value) {
    $xtpl->assign('NB', ++$nb);
    $xtpl->assign('LANG_KEY', $key);
    $xtpl->assign('LANG_NAME', $value[1]);

    foreach ($array_lang_setup as $data_name) {
        $data_key = $data_name[0];
        $xtpl->assign('DATA_SELECTED', (isset($config_geo[$key]) and $config_geo[$key] == $data_key) ? ' selected="selected"' : '');
        $xtpl->assign('DATA_KEY', $data_key);
        $xtpl->assign('DATA_TITLE', $data_name[1]);
        $xtpl->parse('main.countries.language');
    }

    $xtpl->parse('main.countries');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
