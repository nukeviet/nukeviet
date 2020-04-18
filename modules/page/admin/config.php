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

$page_title = $lang_module['config'];

$array_config = array();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config['viewtype'] = $nv_Request->get_int('viewtype', 'post', 0);
    $array_config['facebookapi'] = $nv_Request->get_string('facebookapi', 'post', '');
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', '0');
    $array_config['related_articles'] = $nv_Request->get_int('related_articles', 'post', '0');
    $array_config['news_first'] = $nv_Request->get_int('news_first', 'post', 0);
    $array_config['copy_page'] = $nv_Request->get_int('copy_page', 'post', 0);
    $array_config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);

    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET config_value = :config_value WHERE config_name = :config_name');
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$array_config['viewtype'] = 0;
$array_config['facebookapi'] = '';
$array_config['per_page'] = '5';
$array_config['related_articles'] = '5';
$array_config['news_first'] = 0;
$array_config['copy_page'] = 0;
$array_config['alias_lower'] = 1;

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query($sql);
while (list ($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config[$c_config_name] = $c_config_value;
}
$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('NEWS_FIRST', $array_config['news_first'] ? ' checked="checked"' : '');
$xtpl->assign('COPY_PAGE', $array_config['copy_page'] ? ' checked="checked"' : '');
$xtpl->assign('ALIAS_LOWER', $array_config['alias_lower'] ? ' checked="checked"' : '');

$view_array = array(
    $lang_module['config_view_type_0'],
    $lang_module['config_view_type_1'],
    $lang_module['config_view_type_2']
);
foreach ($view_array as $key => $title) {
    $xtpl->assign('VIEWTYPE', array(
        'id' => $key,
        'title' => $title,
        'selected' => $array_config['viewtype'] == $key ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.loop');
}
for ($i = 5; $i <= 30; ++$i) {
    $xtpl->assign('PER_PAGE', array(
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array_config['per_page'] ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.per_page');
}

for ($i = 0; $i <= 30; ++$i) {
    $xtpl->assign('RELATED_ARTICLES', array(
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array_config['related_articles'] ? 'selected="selected"' : ''
    ));
    $xtpl->parse('main.related_articles');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';