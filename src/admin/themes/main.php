<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('theme_manager');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
$theme_mobile_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
$theme_list = array_merge($theme_list, $theme_mobile_list);

$number_theme = sizeof($theme_list);

$errorconfig = [];
$array_site_theme = [];
$array_site_cat_theme = [];
$result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
while (list($theme) = $result->fetch(3)) {
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
    if (in_array($theme, $theme_list)) {
        $array['status'] = 'SUCCESS';
        if (in_array($theme, $array_allow_preview)) {
            $array['mode'] = 'disable';
            $array['spantext'] = $nv_Lang->getModule('preview_theme_on');
            $array_allow_preview = array_flip($array_allow_preview);
            unset($array_allow_preview[$theme]);
            $array_allow_preview = array_flip($array_allow_preview);
        } else {
            $array_allow_preview[] = $theme;
            $array['mode'] = 'enable';
            $array['spantext'] = $nv_Lang->getModule('preview_theme_off');
            $array['link'] = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=nv-preview-theme&theme=' . $theme . '&checksum=' . md5(NV_LANG_DATA . $theme . $global_config['sitekey']), true);
        }
        $array_allow_preview = implode(',', array_intersect($array_allow_preview, $theme_list));
        $db->query('UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value=' . $db->quote($array_allow_preview) . ' WHERE lang=' . $db->quote(NV_LANG_DATA) . ' AND module=\'global\' AND config_name=\'preview_theme\'');
        $nv_Cache->delMod('settings');
        nv_insert_logs(NV_LANG_DATA, $module_name, $array['mode'] . ' preview theme', $theme, $admin_info['userid']);
    }
    nv_jsonOutput($array);
}

$array_themes = [];
foreach ($theme_list as $value) {
    if (!$xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $value . '/config.ini')) {
        $errorconfig[] = $value;
        continue;
    }
    // Kiem tra giao dien co danh cho subsite hay ko
    if ($global_config['idsite'] and !in_array($value, $array_site_cat_theme)) {
        continue;
    }

    $info = $xml->xpath('info');
    $position = $xml->xpath('positions');
    $positions = $position[0]->position;

    $pos = [];
    for ($j = 0, $count = sizeof($positions); $j < $count; ++$j) {
        $pos[] = $positions[$j]->name;
    }

    $array_themes[] = [
        'name' => (string)$info[0]->name,
        'website' => (string)$info[0]->website,
        'author' => (string)$info[0]->author,
        'thumbnail' => (string)$info[0]->thumbnail,
        'description' => (string)$info[0]->description,
        'value' => $value,
        'pos' => $pos,
        'link_preview' => NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=nv-preview-theme&theme=' . $value . '&checksum=' . md5(NV_LANG_DATA . $value . $global_config['sitekey']), true)
    ];
}

$tpl->assign('SITE_THEME', $global_config['site_theme']);
$tpl->assign('ARRAY_SITE_THEME', $array_site_theme);
$tpl->assign('ARRAY_ALLOW_PREVIEW', $array_allow_preview);
$tpl->assign('THEME_MOBILE_LIST', $theme_mobile_list);
$tpl->assign('ARRAY_THEMES', $array_themes);
$tpl->assign('ERRORCONFIG', $errorconfig);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
