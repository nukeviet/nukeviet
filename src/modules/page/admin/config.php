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

$page_title = $nv_Lang->getModule('config');

$array_config = [];
$socialbuttons = ['facebook', 'twitter', 'zalo'];

if ($nv_Request->isset_request('save', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Error session!!!'
        ]);
    }

    $array_config['viewtype'] = $nv_Request->get_int('viewtype', 'post', 0);
    $array_config['facebookapi'] = $nv_Request->get_title('facebookapi', 'post', '');
    $array_config['per_page'] = $nv_Request->get_page('per_page', 'post', 20);
    $array_config['related_articles'] = $nv_Request->get_int('related_articles', 'post', '0');
    $array_config['news_first'] = $nv_Request->get_int('news_first', 'post', 0);
    $array_config['copy_page'] = $nv_Request->get_int('copy_page', 'post', 0);
    $array_config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);
    $array_config['socialbutton'] = $nv_Request->get_typed_array('socialbutton', 'post', 'title', []);
    $array_config['socialbutton'] = array_intersect($array_config['socialbutton'], $socialbuttons);
    if (in_array('zalo', $array_config['socialbutton'], true) and empty($global_config['zaloOfficialAccountID'])) {
        $array_config['socialbutton'] = array_diff($array_config['socialbutton'], ['zalo']);
    }
    $array_config['socialbutton'] = !empty($array_config['socialbutton']) ? implode(',', $array_config['socialbutton']) : '';

    $array_config['schema_type'] = $nv_Request->get_title('schema_type', 'post', '');
    $array_config['schema_about'] = $nv_Request->get_title('schema_about', 'post', '');
    if (!array_key_exists($array_config['schema_type'], $schema_types)) {
        $array_config['schema_type'] = 'newsarticle';
    }
    if (!array_key_exists($array_config['schema_about'], $schema_abouts)) {
        $array_config['schema_about'] = 'organization';
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change config', '', $admin_info['userid']);

    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET config_value = :config_value WHERE config_name = :config_name');
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => 1
    ]);
}

$array_config['viewtype'] = 0;
$array_config['facebookapi'] = '';
$array_config['socialbutton'] = '';
$array_config['per_page'] = '5';
$array_config['related_articles'] = '5';
$array_config['news_first'] = 0;
$array_config['copy_page'] = 0;
$array_config['alias_lower'] = 1;

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query($sql);
while ([$c_config_name, $c_config_value] = $result->fetch(3)) {
    $array_config[$c_config_name] = $c_config_value;
}

$array_config['socialbutton'] = !empty($array_config['socialbutton']) ? array_map('trim', explode(',', $array_config['socialbutton'])) : [];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config.tpl'));
$tpl->registerPlugin('modifier', 'ucfirst', 'ucfirst');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $array_config);
$tpl->assign('SOCIAL_BUTTONS', $socialbuttons);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('SCHEMA_TYPES', $schema_types);
$tpl->assign('SCHEMA_ABOUTS', $schema_abouts);

$contents = $tpl->fetch('config.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
