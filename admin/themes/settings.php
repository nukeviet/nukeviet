<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$page_title = $lang_module['settings'];

// Lấy tất cả các giao diện (không phải mobile) đã được thiết lập
$array_site_cat_theme = $array_site_theme = [];
$result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0 ORDER BY theme ASC');
while (list($theme) = $result->fetch(3)) {
    if (preg_match($global_config['check_theme'], $theme)) {
        $array_site_theme[] = $theme;
    }
}
if ($global_config['idsite']) {
    $sql = 'SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1
    INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'];
    $theme = $db->query($sql)->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
    }
    $array_site_cat_theme = array_unique(array_merge($array_site_theme, $array_site_cat_theme));
} else {
    $array_site_cat_theme = $array_site_theme;
}

$array_config = [];

// Submit form
if ($nv_Request->get_title('tokend', 'post', '') === NV_CHECK_SESSION) {
    $array_config['user_allowed_theme'] = $nv_Request->get_typed_array('user_allowed_theme', 'post', 'title', []);
    $array_config['user_allowed_theme'] = array_intersect($array_config['user_allowed_theme'], $array_site_cat_theme);
    $array_config['user_allowed_theme'][] = $global_config['site_theme'];
    $array_config['user_allowed_theme'] = array_unique($array_config['user_allowed_theme']);
    asort($array_config['user_allowed_theme']);
    $array_config['user_allowed_theme'] = empty($array_config['user_allowed_theme']) ? '' : json_encode($array_config['user_allowed_theme']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value= :config_value WHERE config_name = :config_name AND lang = '" . NV_LANG_DATA . "' AND module='global'");
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll();
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
} else {
    $array_config['user_allowed_theme'] = $global_config['array_user_allowed_theme'];
}

$xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('LINK_SET_CONFIG', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('TOKEND', NV_CHECK_SESSION);
$xtpl->assign('LANG_MESSAGE', sprintf($lang_module['settings_utheme_lnote'], $language_array[NV_LANG_DATA]['name']));

foreach ($array_site_cat_theme as $theme) {
    $xtpl->assign('USER_ALLOWED_THEME', [
        'key' => $theme,
        'title' => $theme,
        'checked' => (in_array($theme, $array_config['user_allowed_theme'], true) or $theme == $global_config['site_theme']) ? ' checked="checked"' : '',
        'disabled' => $theme == $global_config['site_theme'] ? ' disabled="disabled"' : ''
    ]);
    $xtpl->parse('main.loop_theme');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
