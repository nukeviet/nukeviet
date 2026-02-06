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

$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);
if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
        $result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
        while ($_scratch = $result->fetch(3)) {
            list($theme) = $_scratch;
            unset($_scratch);
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

if (!in_array($selectthemes, $theme_array, true)) {
    $selectthemes = $global_config['site_theme'];
}
if ($selectthemes_old != $selectthemes) {
    $nv_Request->set_Cookie('selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME);
}

if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini')) {
    $page_title = $lang_module['blocks'] . ':' . $selectthemes;

    $xtpl = new XTemplate('blocks.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    $xtpl->assign('MODULE_NAME', $module_name);

    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('SELECTTHEMES', $selectthemes);

    $new_drag_block = $nv_Request->get_int('drag_block', 'session', 0) ? 0 : 1;
    $lang_drag_block = ($new_drag_block) ? $lang_global['drag_block'] : $lang_global['no_drag_block'];

    $url_dblock = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;drag_block=' . $new_drag_block;
    if (empty($new_drag_block)) {
        $url_dblock .= '&amp;nv_redirect=' . nv_redirect_encrypt(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks&selectthemes=' . $selectthemes);
    }
    $xtpl->assign('URL_DBLOCK', $url_dblock);
    $xtpl->assign('LANG_DBLOCK', $lang_drag_block);

    $result = $db->query('SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
    while ($_scratch = $result->fetch(3)) {
        list($m_title, $m_custom_title) = $_scratch;
        unset($_scratch);
        $xtpl->assign('MODULE', ['key' => $m_title, 'title' => $m_custom_title]);
        $xtpl->parse('main.module');
    }

    // Tên các khối block trong giao diện
    $positions = nv_get_blocks($selectthemes, false);

    // Số block đã thêm tại mỗi vị trí
    $blocks_positions = [];
    $sth = $db->prepare('SELECT position, COUNT(*) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme GROUP BY position');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    while ($_scratch = $sth->fetch(3)) {
        list($position, $numposition) = $_scratch;
        unset($_scratch);
        $blocks_positions[$position] = $numposition;
    }

    $sth = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        $xtpl->assign('ROW', [
            'bid' => $row['bid'],
            'title' => $row['title'],
            'module' => $row['module'],
            'file_name' => $row['file_name'],
            'active' => $row['active'] ? 'checked="checked"' : ''
        ]);

        $numposition = $blocks_positions[$row['position']];

        for ($i = 1; $i <= $numposition; ++$i) {
            $xtpl->assign('WEIGHT', ['key' => $i, 'selected' => ($row['weight'] == $i) ? ' selected="selected"' : '']);
            $xtpl->parse('main.loop.weight');
        }

        foreach ($positions as $position) {
            $xtpl->assign('POSITION', [
                'key' => $position['tag'],
                'selected' => ($row['position'] == $position['tag']) ? ' selected="selected"' : '',
                'title' => (empty($position['module']) ? '' : ((isset($site_mods[$position['module']]) ? $site_mods[$position['module']]['custom_title'] : $position['module']) . ': ')) . $position['name']
            ]);
            $xtpl->parse('main.loop.position');
        }

        if ($row['all_func'] == 1) {
            $xtpl->parse('main.loop.all_func');
        } else {
            $result_func = $db->query('SELECT a.func_id, a.in_module, a.func_custom_name FROM ' . NV_MODFUNCS_TABLE . ' a INNER JOIN ' . NV_BLOCKS_TABLE . '_weight b ON a.func_id=b.func_id WHERE b.bid=' . $row['bid']);
            while ($_scratch = $result_func->fetch(3)) {
                list($funcid_inlist, $func_inmodule, $funcname_inlist) = $_scratch;
                unset($_scratch);
                $xtpl->assign('FUNCID_INLIST', $funcid_inlist);
                $xtpl->assign('FUNC_INMODULE', $func_inmodule);
                $xtpl->assign('FUNCNAME_INLIST', $funcname_inlist);

                $xtpl->parse('main.loop.func_inmodule');
            }
        }

        $xtpl->parse('main.loop');
    }

    $xtpl->assign('BLOCKREDIRECT', '');
    $xtpl->assign('CHECKSS', md5($selectthemes . NV_CHECK_SESSION));

    $active_device = [1];
    for ($i = 1; $i <= 4; ++$i) {
        $xtpl->assign('ACTIVE_DEVICE', [
            'key' => $i,
            'checked' => (in_array($i, $active_device, true)) ? ' checked="checked"' : '',
            'title' => $lang_module['show_device_' . $i]
        ]);
        $xtpl->parse('main.active_device');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
