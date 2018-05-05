<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 28/10/2012, 14:51
 */

if (!defined('NV_IS_FILE_SETTINGS')) {
    die('Stop!!!');
}

$pattern_plugin = '/^([a-zA-Z0-9\_]+)\.php$/';
$page_title = $nv_Lang->getModule('plugin');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

$sql = "SELECT DISTINCT plugin_area FROM " . $db_config['prefix'] . "_plugin";
$result = $db->query($sql);
$array_plugin_area = array();
while ($row = $result->fetch()) {
    $array_plugin_area[$row['plugin_area']] = $row['plugin_area'];
}

$array_search = array(
    'plugin_area' => $nv_Request->get_title('a', 'get', '')
);
if (!empty($array_search['plugin_area']) and !isset($array_plugin_area[$array_search['plugin_area']])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

// Xuất các plugin trong CSDL
$max_weight = 0;
$sql = "SELECT * FROM " . $db_config['prefix'] . "_plugin";
if (!empty($array_search['plugin_area'])) {
    $sql .= " ORDER BY weight ASC";
    $max_weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $db->quote($array_search['plugin_area']))->fetchColumn();
    $xtpl->parse('main.col_weight');
}
$result = $db->query($sql);
$array_plugin_db = array();

while ($row = $result->fetch()) {
    if (empty($array_search['plugin_area']) or $array_search['plugin_area'] == $row['plugin_area']) {
        $xtpl->assign('ROW', $row);

        if (!empty($array_search['plugin_area'])) {
            for ($i = 1; $i <= $max_weight; $i++) {
                $weight = array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                );
                $xtpl->assign('WEIGHT', $weight);
                $xtpl->parse('main.loop.weight.loop');
            }
            $xtpl->parse('main.loop.weight');
        }

        $xtpl->parse('main.loop');
    }
    $array_plugin_db[] = (empty($row['plugin_module_name']) ? '--' : $row['plugin_module_name']) . ':' . $row['plugin_module_all'] . ':' . $row['plugin_file'];
}

foreach ($array_plugin_area as $plugin_area) {
    $xtpl->assign('PLUGIN_AREA_SELECTED', $plugin_area == $array_search['plugin_area'] ? ' selected="selected"' : '');
    $xtpl->assign('PLUGIN_AREA', $plugin_area);
    $xtpl->parse('main.plugin_area');
}

// Xuất các plugin chưa thêm vào CSDL
$file_plugins = nv_scandir(NV_ROOTDIR . '/includes/plugin', $pattern_plugin);
$available_plugins = array();

foreach ($file_plugins as $_plugin) {
    $_key = '--:0:' . $_plugin;
    if (!in_array($_key, $array_plugin_db)) {
        $available_plugins[md5($_key)] = array(
            'file' => $_plugin,
            'area' => nv_get_plugin_area(NV_ROOTDIR . '/includes/plugin/' . $_plugin)
        );
    }
}

// Tích hợp plugin mới
if ($nv_Request->isset_request('plugin_file', 'get')) {
    $plugin_file = $nv_Request->get_title('plugin_file', 'get');
    $_key = md5('--:0:' . $plugin_file);

    // Kiểm tra plugin hợp lệ
    if (!isset($available_plugins[$_key]) or sizeof($available_plugins[$_key]['area']) != 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }

    $plugin_area = $available_plugins[$_key]['area'][0];

    // Lấy vị trí mới
    $_sql = 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $db->quote($plugin_area) . ' AND plugin_module_name=\'\' AND plugin_module_all=0';
    $weight = $db->query($_sql)->fetchColumn();
    $weight = intval($weight) + 1;

    try {
        $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_plugin (
            plugin_file, plugin_area, weight
        ) VALUES (
            :plugin_file, :plugin_area, :weight
        )');
        $sth->bindParam(':plugin_file', $plugin_file, PDO::PARAM_STR);
        $sth->bindParam(':plugin_area', $plugin_area, PDO::PARAM_STR);
        $sth->bindParam(':weight', $weight, PDO::PARAM_INT);
        $sth->execute();

        nv_save_file_config_global();
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

// Xóa plugin
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    $pid = $nv_Request->get_int('pid', 'post', 0);

    if ($pid > 0) {
        $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $pid)->fetch();
        if (!empty($row) and $db->exec('DELETE FROM ' . $db_config['prefix'] . '_plugin WHERE pid = ' . $pid)) {
            $weight = intval($row['weight']);
            $_query = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $db->quote($row['plugin_area']) . ' AND weight > ' . $weight . ' ORDER BY weight ASC');
            while (list ($pid) = $_query->fetch(3)) {
                $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $weight++ . ' WHERE pid=' . $pid);
            }

            nv_save_file_config_global();
            nv_htmlOutput('OK');
        }
    }
    nv_htmlOutput('ERROR');
}

// Thay đổi thứ tự ưu tiên
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong URL!!!');
    }

    $pid = $nv_Request->get_int('pid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    if ($pid > 0) {
        $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $pid)->fetch();
        if (!empty($row)) {
            $sql = 'SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE pid!=' . $pid . ' AND plugin_area=' . $db->quote($row['plugin_area']) . ' ORDER BY weight ASC';
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                if ($weight == $new_weight) {
                    ++$weight;
                }
                $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight=' . $weight . ' WHERE pid=' . $row['pid']);
            }

            $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight=' . $new_weight . ' WHERE pid=' . $pid);

            nv_save_file_config_global();
            nv_htmlOutput('OK');
        }
    }
    nv_htmlOutput('ERROR');
}

// Các plugin chưa được tích hợp
if (!empty($available_plugins)) {
    foreach ($available_plugins as $plugin) {
        $plugin['parea'] = implode(', ', $plugin['area']);
        $xtpl->assign('ROW', $plugin);

        if (sizeof($plugin['area']) == 1) {
            $xtpl->assign('LINK_INTEGRATE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;plugin_file=' . $plugin['file'] . '&amp;rand=' . nv_genpass());
            $xtpl->parse('main.available.loop.status_ok');
            $xtpl->parse('main.available.loop.integrate');
        } else {
            $xtpl->parse('main.available.loop.status_error');
        }

        $xtpl->parse('main.available.loop');
    }
    $xtpl->parse('main.available');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
