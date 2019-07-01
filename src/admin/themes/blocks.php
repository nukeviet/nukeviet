<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);
if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
        $result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
        while (list($theme) = $result->fetch(3)) {
            $array_site_cat_theme[] = $theme;
        }
        $theme_array = array_intersect($theme_array, $array_site_cat_theme);
    }
}

foreach ($theme_array as $themes_i) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini')) {
        $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks&amp;selectthemes=' . $themes_i] = $themes_i;
    }
}

$selectthemes_old = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);
$selectthemes = $nv_Request->get_string('selectthemes', 'get', $selectthemes_old);

if (!in_array($selectthemes, $theme_array)) {
    $selectthemes = $global_config['site_theme'];
}
if ($selectthemes_old != $selectthemes) {
    $nv_Request->set_Cookie('selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME);
}

if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini')) {
    $page_title = $nv_Lang->getModule('blocks') . ':' . $selectthemes;

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
    $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    $xtpl = new XTemplate('blocks.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);

    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

    $tpl->assign('SELECTTHEMES', $selectthemes);

    $new_drag_block = $nv_Request->get_int('drag_block', 'session', 0) ? 0 : 1;
    $lang_drag_block = ($new_drag_block) ? $nv_Lang->getGlobal('drag_block') : $nv_Lang->getGlobal('no_drag_block');

    $url_dblock = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;drag_block=' . $new_drag_block;
    if (empty($new_drag_block)) {
        $url_dblock .= '&amp;nv_redirect=' . nv_redirect_encrypt(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks&selectthemes=' . $selectthemes);
    }
    $tpl->assign('URL_DBLOCK', $url_dblock);
    $tpl->assign('LANG_DBLOCK', $lang_drag_block);

    $array_modules = [];
    $result = $db->query('SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
    while (list($m_title, $m_custom_title) = $result->fetch(3)) {
        $array_modules[] = [
            'key' => $m_title,
            'title' => $m_custom_title
        ];
    }
    $tpl->assign('ARRAY_MODULES', $array_modules);

    $a = 0;
    //load position file
    $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
    $content = $xml->xpath('positions');
    $positions = $content[0]->position;

    $blocks_positions = [];
    $sth = $db->prepare('SELECT position, COUNT(*) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme GROUP BY position');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    while (list($position, $numposition) = $sth->fetch(3)) {
        $blocks_positions[$position] = $numposition;
    }

    $array_blocks = $array_block_funcs = [];

    $sth = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        $array_blocks[$row['bid']] = [
            'bid' => $row['bid'],
            'title' => $row['title'],
            'module' => $row['module'],
            'file_name' => $row['file_name'],
            'active' => $row['active'] ? 'checked="checked"' : '',
            'numposition' => $blocks_positions[$row['position']],
            'weight' => $row['weight'],
            'position' => $row['position'],
            'positions' => $positions,
            'positionnum' => sizeof($positions) - 1,
            'all_func' => $row['all_func']
        ];

        if ($row['all_func'] != 1) {
            $result_func = $db->query('SELECT a.func_id, a.in_module, a.func_custom_name FROM ' . NV_MODFUNCS_TABLE . ' a INNER JOIN ' . NV_BLOCKS_TABLE . '_weight b ON a.func_id=b.func_id WHERE b.bid=' . $row['bid']);
            while (list($funcid_inlist, $func_inmodule, $funcname_inlist) = $result_func->fetch(3)) {
                $array_block_funcs[$row['bid']][] = [
                    'func_id' => $funcid_inlist,
                    'in_module' => $func_inmodule,
                    'func_custom_name' => $funcname_inlist,
                ];
            }
        }
    }

    $tpl->assign('ARRAY_BLOCKS', $array_blocks);
    $tpl->assign('ARRAY_BLOCK_FUNCS', $array_block_funcs);
    $tpl->assign('BLOCKREDIRECT', '');
    $tpl->assign('CHECKSS', md5($selectthemes . NV_CHECK_SESSION));

    $active_device = [1];
    for ($i = 1; $i <= 4; ++$i) {
        $xtpl->assign('ACTIVE_DEVICE', [
            'key' => $i,
            'checked' => (in_array($i, $active_device)) ? ' checked="checked"' : '',
            'title' => $nv_Lang->getModule('show_device_' . $i)
        ]);
        $xtpl->parse('main.active_device');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    $contents = $tpl->fetch('blocks.tpl');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
