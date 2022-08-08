<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    exit('Stop!!!');
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $config_key = $nv_Request->get_typed_array('config_key', 'post', 'title', []);
    $config_name = $nv_Request->get_typed_array('config_name', 'post', 'title', []);
    $config_value = $nv_Request->get_typed_array('config_value', 'post', 'title', []);

    $custom_configs = [];
    if (!empty($config_key)) {
        foreach ($config_key as $i => $key) {
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9\_]*$/', $key) and !empty($config_value[$i])) {
                $custom_configs[$key] = [$config_name[$i], $config_value[$i]];
            }
        }
    }
    $custom_configs = !empty($custom_configs) ? json_encode($custom_configs) : '';
    if (empty($global_config['custom_configs'])) {
        if (!empty($custom_configs)) {
            $sth = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('" . NV_LANG_DATA . "', 'global', 'custom_configs', :config_value)");
            $sth->bindParam(':config_value', $custom_configs, PDO::PARAM_STR);
            $sth->execute();
        }
    } else {
        if (!empty($custom_configs)) {
            $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = 'custom_configs' AND lang = '" . NV_LANG_DATA . "' AND module='global'");
            $sth->bindParam(':config_value', $custom_configs, PDO::PARAM_STR);
            $sth->execute();
        } else {
            $db->query('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE config_name = 'custom_configs' AND lang = '" . NV_LANG_DATA . "' AND module='global'");
        }
    }

    $nv_Cache->delAll();
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$xtpl = new XTemplate('custom.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('CHECKSS', $checkss);

$custom_configs = [];
if (!empty($global_config['custom_configs'])) {
    foreach ($global_config['custom_configs'] as $key => $item) {
        $custom_configs[] = [
            'key' => $key,
            'name' => $item[0],
            'value' => $item[1]
        ];
    }
}
if (empty($custom_configs)) {
    $custom_configs[] = [
        'key' => '',
        'name' => '',
        'value' => ''
    ];
}

foreach ($custom_configs as $custom_config) {
    $xtpl->assign('CUSTOM', $custom_config);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = sprintf($lang_module['custom_configs'], $array_lang_admin[NV_LANG_DATA]);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';