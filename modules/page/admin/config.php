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

$page_title = $lang_module['config'];

$array_config = [];
$socialbuttons = ['facebook', 'twitter', 'zalo'];

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['viewtype'] = $nv_Request->get_int('viewtype', 'post', 0);
    $array_config['facebookapi'] = $nv_Request->get_string('facebookapi', 'post', '');
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', '0');
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
$array_config['socialbutton'] = '';
$array_config['per_page'] = '5';
$array_config['related_articles'] = '5';
$array_config['news_first'] = 0;
$array_config['copy_page'] = 0;
$array_config['alias_lower'] = 1;

$sql = 'SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$result = $db->query($sql);
while (list($c_config_name, $c_config_value) = $result->fetch(3)) {
    $array_config[$c_config_name] = $c_config_value;
}
$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);
$xtpl->assign('NEWS_FIRST', $array_config['news_first'] ? ' checked="checked"' : '');
$xtpl->assign('COPY_PAGE', $array_config['copy_page'] ? ' checked="checked"' : '');
$xtpl->assign('ALIAS_LOWER', $array_config['alias_lower'] ? ' checked="checked"' : '');

$view_array = [
    $lang_module['config_view_type_0'],
    $lang_module['config_view_type_1'],
    $lang_module['config_view_type_2']
];
foreach ($view_array as $key => $title) {
    $xtpl->assign('VIEWTYPE', [
        'id' => $key,
        'title' => $title,
        'selected' => $array_config['viewtype'] == $key ? 'selected="selected"' : ''
    ]);
    $xtpl->parse('main.loop');
}
for ($i = 5; $i <= 30; ++$i) {
    $xtpl->assign('PER_PAGE', [
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array_config['per_page'] ? 'selected="selected"' : ''
    ]);
    $xtpl->parse('main.per_page');
}

for ($i = 0; $i <= 30; ++$i) {
    $xtpl->assign('RELATED_ARTICLES', [
        'key' => $i,
        'title' => $i,
        'selected' => $i == $array_config['related_articles'] ? 'selected="selected"' : ''
    ]);
    $xtpl->parse('main.related_articles');
}

// Social_buttons
$my_socialbuttons = !empty($array_config['socialbutton']) ? array_map('trim', explode(',', $array_config['socialbutton'])) : [];
foreach ($socialbuttons as $socialbutton) {
    $array = [
        'key' => $socialbutton,
        'title' => ucfirst($socialbutton),
        'checked' => (!empty($my_socialbuttons) and in_array($socialbutton, $my_socialbuttons, true)) ? ' checked="checked"' : ''
    ];
    if ($socialbutton == 'zalo' and empty($global_config['zaloOfficialAccountID'])) {
        $array['title'] .= ' (<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings">' . $lang_module['socialbutton_zalo_note'] . '</a>)';
        $array['checked'] = ' disabled="disabled"';
    }
    $xtpl->assign('SOCIALBUTTON', $array);
    $xtpl->parse('main.socialbutton');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
