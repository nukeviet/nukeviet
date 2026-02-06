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

/**
 * Trang quản lý các block theo từng module
 */

$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [
    $global_config['check_theme'],
    $global_config['check_theme_mobile']
]);

foreach ($theme_array as $themes_i) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks_func&amp;selectthemes=' . $themes_i] = $themes_i;
}

$selectthemes_old = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);
$selectthemes = $nv_Request->get_string('selectthemes', 'get', $selectthemes_old);

if (!in_array($selectthemes, $theme_array, true)) {
    $selectthemes = $global_config['site_theme'];
}

if ($selectthemes_old != $selectthemes) {
    $nv_Request->set_Cookie('selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME);
}

$selectedmodule = '';
$selectedmodule = $nv_Request->get_title('module', 'get', '', 1);
$func_id = $nv_Request->get_int('func', 'get', 0);

if ($func_id > 0) {
    $selectedmodule = $db->query('SELECT in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id=' . $func_id)->fetchColumn();
} elseif (!empty($selectedmodule)) {
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . " WHERE func_name='main' AND in_module= :module");
    $sth->bindParam(':module', $selectedmodule, PDO::PARAM_STR);
    $sth->execute();
    $func_id = $sth->fetchColumn();
}

if (empty($func_id) or empty($selectedmodule)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks');
}

$page_title = $lang_module['blocks_by_funcs'] . ': ' . $selectthemes;

$xtpl = new XTemplate('blocks_func.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('CHECKSS', md5($selectthemes . NV_CHECK_SESSION));
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC';
$result = $db->query($sql);
while ($_scratch = $result->fetch(3)) {
    list($m_title, $m_custom_title) = $_scratch;
    unset($_scratch);
    $xtpl->assign('MODULE', [
        'key' => $m_title,
        'selected' => ($selectedmodule == $m_title) ? ' selected="selected"' : '',
        'title' => $m_custom_title
    ]);
    $xtpl->parse('main.module');
}

$array_func_id = [];
$sth = $db->prepare('SELECT func_id, func_custom_name
	FROM ' . NV_MODFUNCS_TABLE . '
	WHERE in_module=:module AND show_func=1
	ORDER BY subweight ASC');
$sth->bindParam(':module', $selectedmodule, PDO::PARAM_STR);
$sth->execute();

while ($_scratch = $sth->fetch(3)) {
    list($f_id, $f_custom_title) = $_scratch;
    unset($_scratch);
    $array_func_id[$f_id] = $f_custom_title;

    $xtpl->assign('FUNCTION', [
        'key' => $f_id,
        'selected' => ($func_id == $f_id) ? ' selected="selected"' : '',
        'title' => $f_custom_title
    ]);
    $xtpl->parse('main.function');
}

// Số block đã thêm tại mỗi vị trí
$blocks_positions = [];
$sth = $db->prepare('SELECT t1.position, COUNT(*)
	FROM ' . NV_BLOCKS_TABLE . '_groups t1
	INNER JOIN ' . NV_BLOCKS_TABLE . '_weight t2 ON t1.bid = t2.bid
	WHERE t2.func_id=' . $func_id . ' AND t1.theme = :theme
	GROUP BY t1.position');
$sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
$sth->execute();

while ($_scratch = $sth->fetch(3)) {
    list($position, $numposition) = $_scratch;
    unset($_scratch);
    $blocks_positions[$position] = $numposition;
}

// Đọc vị trí block của giao diện
$positions = nv_get_blocks($selectthemes, false);

$sth = $db->prepare('SELECT t1.*, t2.func_id, t2.weight as bweight
	FROM ' . NV_BLOCKS_TABLE . '_groups t1
	INNER JOIN ' . NV_BLOCKS_TABLE . '_weight t2 ON t1.bid = t2.bid
	WHERE t2.func_id = ' . $func_id . ' AND t1.theme = :theme
	ORDER BY t1.position ASC, t2.weight ASC');
$sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
$sth->execute();

while ($row = $sth->fetch()) {
    $xtpl->assign('ROW', [
        'bid' => $row['bid'],
        'title' => $row['title'],
        'module' => $row['module'],
        'file_name' => $row['file_name'],
        'active' => $row['active'] ? $lang_global['yes'] : $lang_global['no']
    ]);

    $numposition = $blocks_positions[$row['position']];

    for ($i = 1; $i <= $numposition; ++$i) {
        $xtpl->assign('ORDER', [
            'key' => $i,
            'selected' => ($row['bweight'] == $i) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.loop.order');
    }

    foreach ($positions as $position) {
        $xtpl->assign('POSITION', [
            'key' => $position['tag'],
            'selected' => ($row['position'] == $position['tag']) ? ' selected="selected"' : '',
            'title' => (empty($position['module']) ? '' : ((isset($site_mods[$position['module']]) ? $site_mods[$position['module']]['custom_title'] : $position['module']) . ': ')) . $position['name']
        ]);
        $xtpl->parse('main.loop.position');
    }

    $xtpl->parse('main.loop');
}

$xtpl->assign('BLOCKREDIRECT', '');
$xtpl->assign('FUNC_ID', $func_id);
$xtpl->assign('SELECTEDMODULE', $selectedmodule);

$set_active_op = 'blocks';

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
