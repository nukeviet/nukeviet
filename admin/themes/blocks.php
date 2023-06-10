<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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

// Danh sách các position của theme
$xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
$content = $xml->xpath('positions');
$theme_positionlist = $content[0]->position;

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
    $blocklist[$row['bid']] = $row;
    !isset($positionlist[$row['position']]) && $positionlist[$row['position']] = 0;
    ++$positionlist[$row['position']];
}

// Tiêu đề trang
$page_title = $set_by_func ? $nv_Lang->getModule('theme', nv_ucfirst($selectthemes)) . ' -> ' . $nv_Lang->getModule('blocks_by_funcs') : $nv_Lang->getModule('theme', nv_ucfirst($selectthemes)) . ' -> ' . $nv_Lang->getModule('blocks');

$xtpl = new XTemplate('blocks.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('BLOCKREDIRECT', '');
$xtpl->assign('CHECKSS', md5($selectthemes . NV_CHECK_SESSION));
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('SELECTTHEMES', $selectthemes);
$xtpl->assign('FUNC_ID', $func_id);
$xtpl->assign('SELECTEDMODULE', $selectedmodule);

// Xác đinh URL Kéo thả block
$new_drag_block = $nv_Request->get_int('drag_block', 'session', 0) ? 0 : 1;
$lang_drag_block = ($new_drag_block) ? $nv_Lang->getGlobal('drag_block') : $nv_Lang->getGlobal('no_drag_block');
$url_dblock = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;drag_block=' . $new_drag_block;
if (empty($new_drag_block)) {
    $url_dblock .= '&amp;nv_redirect=' . nv_redirect_encrypt($page_url);
}
$xtpl->assign('URL_DBLOCK', $url_dblock);
$xtpl->assign('LANG_DBLOCK', $lang_drag_block);

// SELECT chọn module
foreach ($modlist as $key => $title) {
    $xtpl->assign('MODULE', [
        'key' => $key,
        'selected' => ($selectedmodule == $key) ? ' selected="selected"' : '',
        'title' => nv_ucfirst($nv_Lang->getModule('module', $title))
    ]);
    $xtpl->parse('main.module');
}

// SELECT chọn function
if ($set_by_func) {
    foreach ($funclist as $key => $title) {
        $xtpl->assign('FUNCTION', [
            'key' => $key,
            'selected' => ($func_id == $key) ? ' selected="selected"' : '',
            'title' => nv_ucfirst($nv_Lang->getModule('function', $title))
        ]);
        $xtpl->parse('main.function.func');
    }
    $xtpl->parse('main.function');
}

// Hiển thị danh sách block
$md = '';
foreach ($blocklist as $row) {
    $row['module'] = ucfirst($row['module']);
    $row['order_func'] = $set_by_func ? 'order_func' : 'order';
    $row['checkss'] = md5(NV_CHECK_SESSION . '_' . $row['bid']);
    $row['dtime_type_format'] = $nv_Lang->getModule('dtime_type_' . $row['dtime_type']);
    $xtpl->assign('ROW', $row);

    // Thứ tự block
    $numposition = $positionlist[$row['position']];
    $weight = $set_by_func ? $row['bweight'] : $row['weight'];
    for ($i = 1; $i <= $numposition; ++$i) {
        $xtpl->assign('WEIGHT', [
            'key' => $i,
            'selected' => ($weight == $i) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.loop.weight');
    }

    // Vị trí block
    $count = sizeof($theme_positionlist);
    $position_name = '';
    for ($i = 0; $i < $count; ++$i) {
        $title = (string) $theme_positionlist[$i]->name;
        $selected = '';
        if ($row['position'] == $theme_positionlist[$i]->tag) {
            $position_name = $title;
            $selected = ' selected="selected"';
        }

        $xtpl->assign('POSITION', [
            'key' => (string) $theme_positionlist[$i]->tag,
            'selected' => $selected,
            'title' => $title
        ]);
        $xtpl->parse('main.loop.position');
    }

    // Chia block theo vị trí
    if (!empty($md) and $row['position'] != $md) {
        $xtpl->parse('main.loop.tbody');
    }
    if ($row['position'] != $md) {
        $xtpl->assign('POSITION_NAME', $position_name);
        $xtpl->parse('main.loop.tbody2');
    }
    $md = $row['position'];

    if ($row['all_func'] == 1) {
        $xtpl->parse('main.loop.all_func');
    } else {
        $result_func = $db->query('SELECT a.func_id, a.in_module, a.func_custom_name FROM ' . NV_MODFUNCS_TABLE . ' a INNER JOIN ' . NV_BLOCKS_TABLE . '_weight b ON a.func_id=b.func_id WHERE b.bid=' . $row['bid']);
        $count = 0;
        while (list($funcid_inlist, $func_inmodule, $funcname_inlist) = $result_func->fetch(3)) {
            $xtpl->assign('FUNCID_INLIST', $funcid_inlist);
            $xtpl->assign('FUNC_INMODULE', $func_inmodule);
            $xtpl->assign('FUNCNAME_INLIST', $funcname_inlist);

            $xtpl->parse('main.loop.func_inmodule.item');
            ++$count;
        }
        if ($count > 2) {
            $xtpl->parse('main.loop.func_inmodule.more');
            $xtpl->parse('main.loop.func_inmodule.more2');
        }
        $xtpl->parse('main.loop.func_inmodule');
    }

    $statuses = [$nv_Lang->getModule('act_0'), $nv_Lang->getModule('act_1')];
    foreach ($statuses as $val => $name) {
        $xtpl->assign('STATUS', [
            'val' => $val,
            'sel' => $val == $row['act'] ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.loop.status');
    }

    $xtpl->parse('main.loop');
}

$active_device = [1];
for ($i = 1; $i <= 4; ++$i) {
    $xtpl->assign('ACTIVE_DEVICE', [
        'key' => $i,
        'checked' => (in_array($i, $active_device, true)) ? ' checked="checked"' : '',
        'title' => $nv_Lang->getModule('show_device_' . $i)
    ]);
    $xtpl->parse('main.active_device');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
