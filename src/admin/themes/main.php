<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('theme_manager');

if (!empty($restrict_access)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config');
}

$theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
$theme_mobile_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
$theme_list = array_merge($theme_list, $theme_mobile_list);

$number_theme = count($theme_list);

$errorconfig = [];
$array_site_theme = [];
$array_site_cat_theme = [];
$result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
while ([$theme] = $result->fetch(3)) {
    $array_site_theme[] = $theme;
}
if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
    }
    $array_site_cat_theme = array_unique(array_merge($array_site_theme, $array_site_cat_theme));
}

$array_allow_preview = explode(',', $global_config['preview_theme']);

// Bật/Tắt cho phép xem trước giao diện
if ($nv_Request->isset_request('togglepreviewtheme', 'post')) {
    $array = [
        'status' => 'ERROR',
        'message' => ''
    ];
    $theme = $nv_Request->get_title('theme', 'post', '');
    if (in_array($theme, $theme_list, true)) {
        $array['status'] = 'SUCCESS';
        if (in_array($theme, $array_allow_preview, true)) {
            $array['mode'] = 'disable';
            $array['spantext'] = $nv_Lang->getModule('preview_theme_on');
            $array_allow_preview = array_flip($array_allow_preview);
            unset($array_allow_preview[$theme]);
            $array_allow_preview = array_flip($array_allow_preview);
        } else {
            $array_allow_preview[] = $theme;
            $array['mode'] = 'enable';
            $array['spantext'] = $nv_Lang->getModule('preview_theme_off');
            $array['link'] = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=nv-preview-theme&theme=' . $theme . '&checksum=' . md5(NV_LANG_DATA . $theme . $global_config['sitekey']), NV_MY_DOMAIN);
        }
        $array_allow_preview = implode(',', array_intersect($array_allow_preview, $theme_list));
        $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value=' . $db->quote($array_allow_preview) . ' WHERE lang=' . $db->quote(NV_LANG_DATA) . ' AND module=\'global\' AND config_name=\'preview_theme\'');
        $nv_Cache->delMod('settings');
        nv_insert_logs(NV_LANG_DATA, $module_name, $array['mode'] . ' preview theme', $theme, $admin_info['userid']);
    }
    nv_jsonOutput($array);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$array = [];
foreach ($theme_list as $value) {
    if (!$xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $value . '/config.ini')) {
        $errorconfig[] = $value;
        continue;
    }
    // Kiem tra giao dien co danh cho subsite hay ko
    if ($global_config['idsite'] and !in_array($value, $array_site_cat_theme, true)) {
        continue;
    }

    $info = $xml->xpath('info');

    // Các vị trí
    $position = $xml->xpath('positions');
    $positions = $position[0]->position;
    $pos = [];

    for ($j = 0, $count = count($positions); $j < $count; ++$j) {
        $pos[] = $positions[$j]->name;
    }

    $array[$value] = [
        'name' => (string) $info[0]->name,
        'website' => (string) $info[0]->website,
        'author' => (string) $info[0]->author,
        'thumbnail' => (string) $info[0]->thumbnail,
        'description' => (string) $info[0]->description,
        'checkss' => md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid'] . '_' . $value),
        'value' => $value,
        'pos' => $pos,
        'allowed_delete' => 0,
        'allowed_active' => 0,
        'allowed_setting' => 0,
        'allowed_preview' => 0,
        'link_preview' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=nv-preview-theme&amp;theme=' . $value . '&amp;checksum=' . md5(NV_LANG_DATA . $value . $global_config['sitekey']), NV_MY_DOMAIN),
    ];

    if ($global_config['site_theme'] != $value) {
        if (in_array($value, $array_site_theme, true)) {
            if ($value != 'default') {
                $array[$value]['allowed_delete'] = 1;
            }
            if (!in_array($value, $theme_mobile_list, true)) {
                $array[$value]['allowed_active'] = 1;
            }
            $array[$value]['allowed_preview'] = 1;
        } else {
            $array[$value]['allowed_setting'] = 1;
        }
    }
}

$tpl->assign('ARRAY', $array);
$tpl->assign('ERRORCONFIG', $errorconfig);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('ARRAY_ALLOW_PREVIEW', $array_allow_preview);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
