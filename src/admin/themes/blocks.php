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

// Xác định theme của các blocks
$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);
if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);
        $result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
        while ([$theme] = $result->fetch(3)) {
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

if (!in_array($selectthemes, $select_options, true)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks';

// Xác định module và function
$selectedmodule = $nv_Request->get_title('module', 'get', '', 1);
$func_id = $nv_Request->get_int('func', 'get', 0);
$set_by_func = false;
if ($func_id > 0) {
    $selectedmodule = $db->query('SELECT in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id=' . $func_id)->fetchColumn();
    if (empty($selectedmodule)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks');
    }
    $page_url .= '&module=' . $selectedmodule . '&func=' . $func_id;
    $set_by_func = true;
} elseif (!empty($selectedmodule)) {
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . " WHERE func_name='main' AND in_module= :module");
    $sth->bindParam(':module', $selectedmodule, PDO::PARAM_STR);
    $sth->execute();
    $func_id = $sth->fetchColumn();
    if (empty($func_id)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks');
    }
    $func_id = (int) $func_id;
    $page_url .= '&module=' . $selectedmodule;
    $set_by_func = true;
}

// Danh sách module
$result = $db->query('SELECT m.title, m.custom_title FROM ' . NV_MODULES_TABLE . ' AS m WHERE EXISTS (SELECT 1 FROM ' . NV_MODFUNCS_TABLE . ' AS f WHERE f.in_module = m.title AND f.show_func=1 GROUP BY f.in_module) ORDER BY m.weight ASC');
$modlist = [];
while ($row = $result->fetch()) {
    $modlist[$row['title']] = $row['custom_title'];
}

// Danh sách các functions của module đã chọn
$funclist = [];
if ($set_by_func) {
    $sth = $db->prepare('SELECT func_id, func_custom_name FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module=:module AND show_func=1 ORDER BY subweight ASC');
    $sth->bindParam(':module', $selectedmodule, PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        $funclist[$row['func_id']] = $row['func_custom_name'];
    }
}

// Danh sách các block + Danh sách các position đã được sử dụng
$blocklist = [];
$positionlist = [];
if ($set_by_func) {
    $sth = $db->prepare('SELECT t1.*, t2.func_id, t2.weight as bweight FROM ' . NV_BLOCKS_TABLE . '_groups t1
    INNER JOIN ' . NV_BLOCKS_TABLE . '_weight t2 ON t1.bid = t2.bid
    WHERE t2.func_id = ' . $func_id . ' AND t1.theme = :theme
    ORDER BY t1.position ASC, t2.weight ASC');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
} else {
    $sth = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
}
while ($row = $sth->fetch()) {
    $row['module'] = ucfirst($row['module']);
    $row['order_func'] = $set_by_func ? 'order_func' : 'order';
    $row['checkss'] = md5(NV_CHECK_SESSION . '_' . $row['bid']);
    $row['dtime_type_format'] = $nv_Lang->getModule('dtime_type_' . $row['dtime_type']);

    // Lấy danh sách function hiển thị của mỗi block
    $row['in_funcs'] = [];
    if (empty($row['all_func'])) {
        $result_func = $db->query('SELECT a.func_id, a.in_module, a.func_custom_name FROM ' . NV_MODFUNCS_TABLE . ' a INNER JOIN ' . NV_BLOCKS_TABLE . '_weight b ON a.func_id=b.func_id WHERE b.bid=' . $row['bid']);
        while ($func = $result_func->fetch()) {
            $row['in_funcs'][] = $func;
        }
        $result_func->closeCursor();
    }

    $blocklist[$row['bid']] = $row;
    !isset($positionlist[$row['position']]) && $positionlist[$row['position']] = 0;
    ++$positionlist[$row['position']];
}

// Tiêu đề trang
$page_title = $set_by_func ? $nv_Lang->getModule('theme', nv_ucfirst($selectthemes)) . ' &gt; ' . $nv_Lang->getModule('blocks_by_funcs') : $nv_Lang->getModule('theme', nv_ucfirst($selectthemes)) . ' &gt; ' . $nv_Lang->getModule('blocks');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('blocks.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('CHECKSS', md5($selectthemes . NV_CHECK_SESSION));
$tpl->assign('SELECTTHEMES', $selectthemes);
$tpl->assign('FUNC_ID', $func_id);
$tpl->assign('SELECTEDMODULE', $selectedmodule);

// Xác đinh URL Kéo thả block
$new_drag_block = $nv_Request->get_int('drag_block', 'session', 0) ? 0 : 1;
$lang_drag_block = ($new_drag_block) ? $nv_Lang->getGlobal('drag_block') : $nv_Lang->getGlobal('no_drag_block');
$url_dblock = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;drag_block=' . $new_drag_block;
if (empty($new_drag_block)) {
    $url_dblock .= '&amp;nv_redirect=' . nv_redirect_encrypt($page_url);
}
$tpl->assign('URL_DBLOCK', $url_dblock);
$tpl->assign('LANG_DBLOCK', $lang_drag_block);
$tpl->assign('MODLIST', $modlist);
$tpl->assign('SET_BY_FUNC', $set_by_func);
$tpl->assign('FUNCLIST', $funclist);
$tpl->assign('BLOCKLIST', $blocklist);

// Lấy và tính toán các vị trí block của giao diện
$_positions = nv_get_blocks($selectthemes, false);
$positions = [];
foreach ($_positions as $_pos) {
    if (isset($positions[$_pos['tag']])) {
        continue;
    }
    $_pos['title'] = (empty($_pos['module']) ? '' : ((isset($site_mods[$_pos['module']]) ? $site_mods[$_pos['module']]['custom_title'] : $_pos['module']) . ': ')) . $_pos['name'];
    $positions[$_pos['tag']] = $_pos;
}

$tpl->assign('THEME_POS', $positions);
$tpl->assign('POSITIONLIST', $positionlist);

$contents = $tpl->fetch('blocks.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
