<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if ($nv_Request->isset_request('save', 'post')) {
    $postdata = [
        'inform_active' => (int) $nv_Request->get_float('inform_active', 'post', false),
        'inform_default_exp' => $nv_Request->get_int('inform_default_exp', 'post', 0),
        'inform_exp_del' => $nv_Request->get_int('inform_exp_del', 'post', 0),
        'inform_refresh_time' => $nv_Request->get_int('inform_refresh_time', 'post', 0),
        'inform_max_characters' => $nv_Request->get_int('inform_max_characters', 'post', 0),
        'inform_numrows' => $nv_Request->get_int('inform_numrows', 'post', 0)
    ];

    foreach ($postdata as $key => $value) {
        if ($key != 'inform_active') {
            if (empty($value)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'input' => $key
                ]);
            }
        }
    }

    $postdata['inform_default_exp'] = $postdata['inform_default_exp'] * 86400;
    $postdata['inform_exp_del'] = $postdata['inform_exp_del'] * 86400;
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    $sth2 = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', :config_name, :config_value)");
    foreach ($postdata as $config_name => $config_value) {
        if (isset($global_config[$config_name])) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        } else {
            $sth2->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
            $sth2->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth2->execute();
        }
    }
    $nv_Cache->delAll(false);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

$page_title = $nv_Lang->getModule('configs');

$data = [
    'inform_active_checked' => !empty($global_config['inform_active']) ? ' checked="checked"' : '',
    'inform_default_exp' => !empty($global_config['inform_default_exp']) ? round($global_config['inform_default_exp'] / 86400) : 30,
    'inform_exp_del' => !empty($global_config['inform_exp_del']) ? round($global_config['inform_exp_del'] / 86400) : 30,
    'inform_refresh_time' => !empty($global_config['inform_refresh_time']) ? $global_config['inform_refresh_time'] : 30,
    'inform_max_characters' => !empty($global_config['inform_max_characters']) ? $global_config['inform_max_characters'] : 200,
    'inform_numrows' => !empty($global_config['inform_numrows']) ? $global_config['inform_numrows'] : 10
];

$xtpl = new XTemplate('configs.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('DATA', $data);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
