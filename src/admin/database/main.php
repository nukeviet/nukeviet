<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('main');

// Hiển thị danh sách bảng dữ liệu
if ($nv_Request->get_bool('show_tabs', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        die('Wrong ajax!!!');
    }

    $respon = [
        'error' => 1,
        'message' => 'Error!!!',
    ];
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        $respon['message'] = 'Wrong session!!!';
        nv_jsonOutput($respon);
    }

    $db_size = 0;
    $db_totalfree = 0;
    $db_tables_count = 0;

    $tables = [];

    $result = $db->query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%'");
    while ($item = $result->fetch()) {
        $tables_size = (float) ($item['data_length']) + (float) ($item['index_length']);

        if ($item['engine'] != 'MyISAM') {
            if ($item['rows'] < 100000) {
                $item['rows'] = $db->query('SELECT COUNT(*) FROM ' . $item['name'])->fetchColumn();
                $item['rows'] = number_format($item['rows']);
            } else {
                $item['rows'] = '~' . number_format($item['rows']);
            }
        } else {
            $item['rows'] = number_format($item['rows']);
        }
        $tables[$item['name']]['table_size'] = nv_convertfromBytes($tables_size);
        $tables[$item['name']]['table_max_size'] = !empty($item['max_data_length']) ? nv_convertfromBytes((float) ($item['max_data_length'])) : 0;
        $tables[$item['name']]['table_datafree'] = !empty($item['data_free']) ? nv_convertfromBytes((float) ($item['data_free'])) : 0;
        $tables[$item['name']]['table_numrow'] = $item['rows'];
        $tables[$item['name']]['table_charset'] = (!empty($item['collation']) and preg_match('/^([a-z0-9]+)_/i', $item['collation'], $m)) ? $m[1] : '';
        $tables[$item['name']]['table_type'] = (isset($item['engine'])) ? $item['engine'] : $item['type'];
        $tables[$item['name']]['table_auto_increment'] = (isset($item['auto_increment'])) ? (int) ($item['auto_increment']) : 'n/a';
        $tables[$item['name']]['table_create_time'] = !empty($item['create_time']) ? nv_datetime_format(strtotime($item['create_time']), 1) : 'n/a';
        $tables[$item['name']]['table_update_time'] = !empty($item['update_time']) ? nv_datetime_format(strtotime($item['update_time']), 1) : 'n/a';
        $db_size += $tables_size;
        $db_totalfree += (float) ($item['data_free']);
        ++$db_tables_count;
    }
    $result->closeCursor();

    $db_size = !empty($db_size) ? nv_convertfromBytes($db_size) : 0;
    $db_totalfree = !empty($db_totalfree) ? nv_convertfromBytes($db_totalfree) : 0;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('tables.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);

    $tpl->assign('DBNAME', $db->dbname);
    $tpl->assign('TABLES', $tables);
    $tpl->assign('DB_TABLES_COUNT', nv_number_format($db_tables_count));
    $tpl->assign('DB_SIZE', $db_size);
    $tpl->assign('DB_TOTALFREE', $db_totalfree);

    $respon['error'] = 0;
    $respon['html'] = $tpl->fetch('tables.tpl');

    nv_jsonOutput($respon);
}

// Hiển thị chi tiết thông tin một bảng dữ liệu
if ($nv_Request->isset_request('tab', 'get') and preg_match('/^(' . $db_config['prefix'] . ')\_[a-zA-Z0-9\_\.\-]+$/', $nv_Request->get_title('tab', 'get'))) {
    $tab = $nv_Request->get_title('tab', 'get');

    $sth = $db->prepare('SHOW TABLE STATUS WHERE name= :tab');
    $sth->bindParam(':tab', $tab, PDO::PARAM_STR);
    $sth->execute();
    $item = $sth->fetch();

    if (empty($item)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if (in_array($nv_Request->get_title('show_highlight', 'post'), ['php', 'sql'], true)) {
        $content = nv_highlight_string($tab, $nv_Request->get_title('show_highlight', 'post'));
        include NV_ROOTDIR . '/includes/header.php';
        echo $content;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    if ($item['engine'] != 'MyISAM') {
        $item['rows'] = $db->query('SELECT COUNT(*) FROM ' . $item['name'])->fetchColumn();
    }

    $tablename = substr($item['name'], strlen($db_config['prefix']) + 1);
    $page_title = $nv_Lang->getModule('nv_show_tab', $tablename);

    if (!empty($item['collation']) and preg_match('/^([a-z0-9]+)_/i', $item['collation'], $m)) {
        $item['collation'] = $m[1];
    } else {
        $item['collation'] = '';
    }

    $columns_array = $db->columns_array($tab);
    $columns = [];
    foreach ($columns_array as $row) {
        $row['null'] = ($row['null'] == 'NO') ? 'NOT NULL' : 'NULL';
        $row['key'] = empty($row['key']) ? '' : ($row['key'] == 'PRI' ? 'PRIMARY KEY' : ($row['key'] == 'UNI' ? 'UNIQUE KEY' : 'KEY'));
        $columns[] = $row;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('tabs.tpl'));
    $tpl->registerPlugin('modifier', 'displaySize', 'nv_convertfromBytes');
    $tpl->registerPlugin('modifier', 'displayDate', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'strtotime', 'strtotime');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);

    $tpl->assign('TABLE', $tab);
    $tpl->assign('TABLENAME', $tablename);
    $tpl->assign('DATA', $item);
    $tpl->assign('CODE_PHP', nv_highlight_string($tab, 'php'));
    $tpl->assign('CODE_SQL', nv_highlight_string($tab, 'sql'));
    $tpl->assign('COLUMNS', $columns);

    $contents = $tpl->fetch('tabs.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

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

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);

$tpl->assign('DB', $database);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
