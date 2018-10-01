<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 21:51
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    die('Stop!!!');
}

// Danh sách các bảng dữ liệu
if ($nv_Request->get_bool('show_tabs', 'post')) {
    $tables = [];

    $db_size = 0;
    $db_totalfree = 0;
    $db_tables_count = 0;

    $tables = [];

    $result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'");
    while ($item = $result->fetch()) {
        $tables_size = floatval($item['data_length']) + floatval($item['index_length']);

        /*
         * MyISAM cho ra chính xác số row, các enginee khác chỉ là số xấp xỉ
         * Xem https://dev.mysql.com/doc/refman/8.0/en/show-table-status.html
         */
        if ($item['engine'] != 'MyISAM') {
            if ($item['rows'] < 100000) {
                $item['rows'] = $db->query("SELECT COUNT(*) FROM " . $item['name'])->fetchColumn();
                $item['rows'] = number_format($item['rows']);
            } else {
                $item['rows'] = '~' . number_format($item['rows']);
            }
        } else {
            $item['rows'] = number_format($item['rows']);
        }
        $tables[$item['name']]['table_size'] = nv_convertfromBytes($tables_size);
        $tables[$item['name']]['table_max_size'] = !empty($item['max_data_length']) ? nv_convertfromBytes(floatval($item['max_data_length'])) : 0;
        $tables[$item['name']]['table_datafree'] = !empty($item['data_free']) ? nv_convertfromBytes(floatval($item['data_free'])) : 0;
        $tables[$item['name']]['table_numrow'] = $item['rows'];
        $tables[$item['name']]['table_charset'] = (!empty($item['collation']) and preg_match('/^([a-z0-9]+)_/i', $item['collation'], $m)) ? $m[1] : '';
        $tables[$item['name']]['table_type'] = (isset($item['engine'])) ? $item['engine'] : $item['type'];
        $tables[$item['name']]['table_auto_increment'] = (isset($item['auto_increment'])) ? intval($item['auto_increment']) : 'n/a';
        $tables[$item['name']]['table_create_time'] = !empty($item['create_time']) ? strftime('%H:%M %d/%m/%Y', strtotime($item['create_time'])) : 'n/a';
        $tables[$item['name']]['table_update_time'] = !empty($item['update_time']) ? strftime('%H:%M %d/%m/%Y', strtotime($item['update_time'])) : 'n/a';
        $db_size += $tables_size;
        $db_totalfree += floatval($item['data_free']);
        ++$db_tables_count;
    }
    $result->closeCursor();

    $db_size = !empty($db_size) ? nv_convertfromBytes($db_size) : 0;
    $db_totalfree = !empty($db_totalfree) ? nv_convertfromBytes($db_totalfree) : 0;

    $array_tables = [];
    foreach ($tables as $key => $values) {
        $table_name = substr($key, strlen($db_config['prefix']) + 1);
        array_unshift($values, '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;tab=' . $key . '">' . $table_name . '</a>');
        $array_tables[$key] = $values;
    }

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('CAPTION', $nv_Lang->getModule('tables_info', $db->dbname));
    $tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);

    $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);
    $tpl->assign('TABLES', $array_tables);
    $tpl->assign('SUMMARY', $nv_Lang->getModule('third', $db_tables_count, $db_size, $db_totalfree));

    $contents = $tpl->fetch('tables.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Chi tiết bảng dữ liệu
if ($nv_Request->isset_request('tab', 'get') and preg_match('/^(' . $db_config['prefix'] . ')\_[a-zA-Z0-9\_\.\-]+$/', $nv_Request->get_title('tab', 'get'))) {
    $tab = $nv_Request->get_title('tab', 'get');

    $sth = $db->prepare('SHOW TABLE STATUS WHERE name= :tab');
    $sth->bindParam(':tab', $tab, PDO::PARAM_STR);
    $sth->execute();
    $item = $sth->fetch();

    if (empty($item)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if (in_array($nv_Request->get_title('show_highlight', 'post'), array(
        'php',
        'sql'
    ))) {
        $content = nv_highlight_string($tab, $nv_Request->get_title('show_highlight', 'post'));
        include NV_ROOTDIR . '/includes/header.php';
        echo $content;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    /*
     * MyISAM cho ra chính xác số row, các enginee khác chỉ là số xấp xỉ
     * Xem https://dev.mysql.com/doc/refman/8.0/en/show-table-status.html
     */
    if ($item['engine'] != 'MyISAM') {
        $item['rows'] = $db->query("SELECT COUNT(*) FROM " . $item['name'])->fetchColumn();
    }

    $tablename = substr($item['name'], strlen($db_config['prefix']) + 1);
    $contents = array();
    $contents['table']['caption'] = sprintf($nv_Lang->getModule('table_caption'), $tablename);
    $contents['table']['info']['name'] = array(
        $nv_Lang->getModule('table_name'),
        $tablename
    );
    $contents['table']['info']['engine'] = array(
        $nv_Lang->getModule('table_type'),
        ((isset($item['engine'])) ? $item['engine'] : $item['type'])
    );
    $contents['table']['info']['row_format'] = array(
        $nv_Lang->getModule('row_format'),
        $item['row_format']
    );
    $contents['table']['info']['data_length'] = array(
        $nv_Lang->getModule('table_size'),
        nv_convertfromBytes(intval($item['data_length']) + intval($item['index_length']))
    );
    $contents['table']['info']['max_data_length'] = array(
        $nv_Lang->getModule('table_max_size'),
        (!empty($item['max_data_length']) ? nv_convertfromBytes(floatval($item['max_data_length'])) : 'n/a')
    );
    $contents['table']['info']['data_free'] = array(
        $nv_Lang->getModule('table_datafree'),
        (!empty($item['data_free']) ? nv_convertfromBytes(intval($item['data_free'])) : 0)
    );
    $contents['table']['info']['rows'] = array(
        $nv_Lang->getModule('table_numrow'),
        $item['rows']
    );
    $contents['table']['info']['auto_increment'] = array(
        $nv_Lang->getModule('table_auto_increment'),
        ((isset($item['auto_increment'])) ? intval($item['auto_increment']) : 'n/a')
    );
    $contents['table']['info']['create_time'] = array(
        $nv_Lang->getModule('table_create_time'),
        (!empty($item['create_time']) ? strftime('%H:%M:%S %d/%m/%Y', strtotime($item['create_time'])) : 'n/a')
    );
    $contents['table']['info']['update_time'] = array(
        $nv_Lang->getModule('table_update_time'),
        (!empty($item['update_time']) ? strftime('%H:%M:%S %d/%m/%Y', strtotime($item['update_time'])) : 'n/a')
    );
    $contents['table']['info']['check_time'] = array(
        $nv_Lang->getModule('table_check_time'),
        (!empty($item['check_time']) ? strftime('%H:%M:%S %d/%m/%Y', strtotime($item['check_time'])) : 'n/a')
    );
    $contents['table']['info']['collation'] = array(
        $nv_Lang->getModule('table_charset'),
        ((!empty($item['collation']) and preg_match('/^([a-z0-9]+)_/i', $item['collation'], $m)) ? $m[1] : '')
    );

    $contents['table']['row']['detail'] = array();
    $columns_array = $db->columns_array($tab);
    foreach ($columns_array as $row) {
        $row['null'] = ($row['null'] == 'NO') ? 'NOT NULL' : 'NULL';
        $row['key'] = empty($row['key']) ? '' : ($row['key'] == 'PRI' ? 'PRIMARY KEY' : ($row['key'] == 'UNI' ? 'UNIQUE KEY' : 'KEY'));
        $contents['table']['row']['detail'][] = $row;
    }

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('CAPTION', $nv_Lang->getModule('table_caption', $tablename));
    $tpl->assign('RCAPTION', $nv_Lang->getModule('table_row_caption', $tablename));
    $tpl->assign('INFO', $contents['table']['info']);
    $tpl->assign('CODEINFO', nv_highlight_string($tab, 'php'));
    $tpl->assign('ROWS', $contents['table']['row']['detail']);

    $contents = $tpl->fetch('tabs.tpl');
    $page_title = $nv_Lang->getModule('nv_show_tab', $tablename);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('CAPTION', $nv_Lang->getModule('database_info', $db->dbname));

$database = [];

$database['db_host_info'] = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
$database['db_sql_version'] = $db->getAttribute(PDO::ATTR_SERVER_VERSION);
$database['db_proto_info'] = $db->getAttribute(PDO::ATTR_CLIENT_VERSION);

$database['server'] = $db->server;
$database['db_dbname'] = $db->dbname;
$database['db_uname'] = $db->user;
if ($db->dbtype == 'mysql') {
    $row = $db->query('SELECT @@session.time_zone AS db_time_zone, @@session.character_set_database AS db_charset, @@session.collation_database AS db_collation')->fetch();
    $database['db_charset'] = $row['db_charset'];
    $database['db_collation'] = $row['db_collation'];
    $database['db_time_zone'] = $row['db_time_zone'];
}

$tpl->assign('DATA', $database);

$contents = $tpl->fetch('main.tpl');
$page_title = $nv_Lang->getModule('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
