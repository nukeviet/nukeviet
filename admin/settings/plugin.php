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

$errormess = $nv_Lang->getModule('plugin_info');
$pattern_plugin = '/^([a-zA-Z0-9\_]+)\.php$/';
$page_title = $nv_Lang->getModule('plugin');

$plugin_file = $nv_Request->get_title('plugin_file', 'post,get');
if ($nv_Request->isset_request('plugin_file', 'post')) {
    $config_plugin = array();
    if (preg_match($pattern_plugin, $plugin_file) and nv_is_file(NV_BASE_SITEURL . 'includes/plugin/' . $plugin_file, 'includes/plugin')) {
        $plugin_area = $nv_Request->get_int('plugin_area', 'post');
        if ($nv_Request->isset_request('delete', 'post')) {
            $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_file=:plugin_file');
            $sth->bindParam(':plugin_file', $plugin_file, PDO::PARAM_STR, strlen($title));
            $sth->execute();
            $count = $sth->fetchColumn();
            if (empty($count)) {
                nv_deletefile(NV_ROOTDIR . '/includes/plugin/' . $plugin_file);
            }
        } elseif (!empty($plugin_area)) {
            $_sql = 'SELECT max(weight) FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $plugin_area;
            $weight = $db->query($_sql)->fetchColumn();
            $weight = intval($weight) + 1;

            try {
                $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_plugin (plugin_file, plugin_area, weight) VALUES (:plugin_file, :plugin_area, :weight)');
                $sth->bindParam(':plugin_file', $plugin_file, PDO::PARAM_STR);
                $sth->bindParam(':plugin_area', $plugin_area, PDO::PARAM_INT);
                $sth->bindParam(':weight', $weight, PDO::PARAM_INT);
                $sth->execute();

                nv_save_file_config_global();
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}
if ($nv_Request->isset_request('dpid', 'get')) {
    $dpid = $nv_Request->get_int('dpid', 'get');
    $checkss = $nv_Request->get_title('checkss', 'get');
    if ($dpid > 0 and $checkss == md5($dpid . '-' . NV_CHECK_SESSION)) {
        $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $dpid)->fetch();
        if (!empty($row) and $db->exec('DELETE FROM ' . $db_config['prefix'] . '_plugin WHERE pid = ' . $dpid)) {
            $weight = intval($row['weight']);
            $_query = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $row['plugin_area'] . ' AND weight > ' . $weight . ' ORDER BY weight ASC');
            while (list ($pid) = $_query->fetch(3)) {
                $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $weight++ . ' WHERE pid=' . $pid);
            }

            nv_save_file_config_global();
        }
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
} elseif ($nv_Request->isset_request('pid', 'get') and $nv_Request->isset_request('weight', 'get')) {
    $pid = $nv_Request->get_int('pid', 'get');
    $row = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_plugin WHERE pid=' . $pid)->fetch();
    if (!empty($row)) {
        $new = $nv_Request->get_int('weight', 'get');

        $weight = 0;
        $_query = $db->query('SELECT pid FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_area=' . $row['plugin_area'] . ' AND pid != ' . $pid . ' ORDER BY weight ASC');
        while (list ($pid_i) = $_query->fetch(3)) {
            ++$weight;
            if ($weight == $new) {
                ++$weight;
            }
            $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $weight . ' WHERE pid=' . $pid_i);
        }
        $db->query('UPDATE ' . $db_config['prefix'] . '_plugin SET weight = ' . $new . ' WHERE pid=' . $pid);

        nv_save_file_config_global();
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

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
$sql = "SELECT * FROM " . $db_config['prefix'] . "_plugin";
if (!empty($array_search['plugin_area'])) {
    $sql .= " WHERE plugin_area=" . $db->quote($array_search['plugin_area']);
}

$result = $db->query($sql);
while ($row = $result->fetch()) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

foreach ($array_plugin_area as $plugin_area) {
    $xtpl->assign('PLUGIN_AREA_SELECTED', $plugin_area == $array_search['plugin_area'] ? ' selected="selected"' : '');
    $xtpl->assign('PLUGIN_AREA', $plugin_area);
    $xtpl->parse('main.plugin_area');
}

// Xuất các plugin chưa thêm vào CSDL

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';