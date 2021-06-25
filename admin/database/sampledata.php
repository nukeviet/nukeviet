<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$array_ignore_save = [
    NV_AUTHORS_GLOBALTABLE,
    NV_AUTHORS_GLOBALTABLE . '_config',
    NV_AUTHORS_GLOBALTABLE . '_module',
    NV_AUTHORS_GLOBALTABLE . '_api_credential',
    NV_AUTHORS_GLOBALTABLE . '_api_role',
    $db_config['prefix'] . '_banners_click',
    $db_config['prefix'] . '_cookies',
    $db_config['prefix'] . '_counter',
    $db_config['prefix'] . '_logs',
    $db_config['prefix'] . '_sessions',
    $db_config['prefix'] . '_upload_dir',
    $db_config['prefix'] . '_upload_file'
];
$array_ignore_drop = [
    $db_config['prefix'] . '_config',
    NV_USERS_GLOBALTABLE
];
$array_method_update = [
    $db_config['prefix'] . '_config' => [
        'key' => ['lang', 'module', 'config_name'],
        'value' => ['config_value'],
        'ignore' => [
            0 => [
                'module' => 'global',
                'config_name' => 'site_name'
            ],
            1 => [
                'lang' => 'sys',
                'module' => 'global',
                'config_name' => 'lang_multi'
            ],
            2 => [
                'lang' => 'sys',
                'module' => 'global',
                'config_name' => 'cookie_prefix'
            ],
            3 => [
                'lang' => 'sys',
                'module' => 'global',
                'config_name' => 'session_prefix'
            ]
        ]
    ]
];

$file_data_tmp = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/data_samplewrite_' . NV_CHECK_SESSION;
$file_data_dump = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/data_sampledump_' . NV_CHECK_SESSION . '.php';

// Xóa gói dữ liệu
if ($nv_Request->isset_request('delete', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }
    $sname = nv_strtolower(nv_substr($nv_Request->get_title('sname', 'post', ''), 0, 50));
    if ($nv_Request->get_string('delete', 'post') == md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $sname) and preg_match('/^([a-z0-9]+)$/', $sname) and file_exists(NV_ROOTDIR . '/install/samples/data_' . $sname . '.php')) {
        nv_deletefile(NV_ROOTDIR . '/install/samples/data_' . $sname . '.php');
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['sampledata'], 'Delete: ' . $sname, $admin_info['userid']);
    }
    nv_htmlOutput('OK');
}

// Tiến trình quét bằng AJAX
if ($nv_Request->isset_request('startwrite', 'get')) {
    if ($sys_info['ini_set_support']) {
        set_time_limit(0);
    }

    $json = [
        'next' => false,
        'nextdata' => [],
        'message' => 'Init Message',
        'lev' => 3,
        'finish' => false,
        'reload' => false
    ];

    $array_request = [];
    $array_request['sample_name'] = nv_strtolower(nv_substr($nv_Request->get_title('sample_name', 'post', ''), 0, 50));
    $array_request['delifexists'] = $nv_Request->get_int('delifexists', 'post', 0);
    $array_request['offsettable'] = $nv_Request->get_int('offsettable', 'post', 0);
    $array_request['offsetrow'] = $nv_Request->get_int('offsetrow', 'post', 0);

    if (empty($array_request['sample_name'])) {
        $json['message'] = $lang_module['sampledata_error_name'];
    } elseif (!preg_match('/^([a-z0-9]+)$/', $array_request['sample_name'])) {
        $json['message'] = $lang_module['sampledata_error_namerule'];
    } elseif (!$array_request['delifexists'] and file_exists(NV_ROOTDIR . '/install/samples/data_' . $array_request['sample_name'] . '.php')) {
        $json['message'] = $lang_module['sampledata_error_exists'];
    } else {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['sampledata'], 'Name: ' . $array_request['sample_name'], $admin_info['userid']);

        // Quét các bảng dữ liệu
        $error = false;
        $array_tables = [];

        if (!file_exists($file_data_tmp)) {
            $a = 0;
            $result = $db->query('SHOW TABLE STATUS LIKE ' . $db->quote($db_config['prefix'] . '_%'));
            while ($row = $result->fetch()) {
                if (!in_array($row['name'], $array_ignore_save, true)) {
                    if ($row['engine'] != 'MyISAM') {
                        $row['rows'] = $db->query('SELECT COUNT(*) FROM ' . $row['name'])->fetchColumn();
                    }
                    $array_tables[$a] = [];
                    $array_tables[$a]['name'] = $row['name'];
                    $array_tables[$a]['size'] = (int) ($row['data_length']) + (int) ($row['index_length']);
                    $array_tables[$a]['limit'] = 1 + round(1048576 / ($row['avg_row_length'] + 1));
                    $array_tables[$a]['numrow'] = $row['rows'];
                    $array_tables[$a]['charset'] = (preg_match('/^([a-z0-9]+)_/i', $row['collation'], $m)) ? $m[1] : '';
                    $array_tables[$a]['type'] = isset($row['engine']) ? $row['engine'] : $row['t'];
                    ++$a;
                }
            }
            $check = file_put_contents($file_data_tmp, serialize($array_tables), LOCK_EX);
            if ($check === false) {
                $json['message'] = sprintf($lang_module['sampledata_error_writetmp'], NV_TEMP_DIR);
                $error = true;
            }
        } else {
            $array_tables = unserialize(file_get_contents($file_data_tmp));
        }

        // Kiểm tra và xuất file dump
        if (!$error) {
            if (!file_exists($file_data_dump)) {
                $dump_content = "<?php\n\n" . NV_FILEHEAD . "\n\nif (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n\$sample_base_siteurl = '" . NV_BASE_SITEURL . "';\n\$sql_create_table = [];\n\n";
                $check = file_put_contents($file_data_dump, $dump_content, LOCK_EX);
                if ($check === false) {
                    $json['message'] = sprintf($lang_module['sampledata_error_writetmp'], NV_TEMP_DIR);
                    $error = true;
                }
            }
        }

        // Xuất CSDL
        if (!$error) {
            $db->query('SET SQL_QUOTE_SHOW_CREATE = 1');
            foreach ($array_tables as $table) {
                $store_table_name = preg_replace('/^' . nv_preg_quote($db_config['prefix']) . '\_/', '" . $db_config[\'prefix\'] . "_', $table['name']);
                $content = '';

                // Xóa bảng tạo lại
                if (!in_array($table['name'], $array_ignore_drop, true)) {
                    $content = $db->query('SHOW CREATE TABLE ' . $table['name'])->fetchColumn(1);
                    $content = preg_replace('/[\s]+COLLATE[\s]+([a-zA-Z0-9\_]+)/i', '', $content);
                    $content = preg_replace('/(KEY[^\(]+)(\([^\)]+\))[\s\r\n\t]+(USING BTREE)/i', '\\1\\3 \\2', $content);
                    $content = preg_replace('/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+|AUTO_INCREMENT=\w+)/i', ' \\1', $content);
                    $content = '$sql_create_table[] = "' . str_replace('"', '\"', $content) . "\";\n";
                    $content = "\n\$sql_create_table[] = \"DROP TABLE IF EXISTS `" . $store_table_name . "`\";\n" . $content;
                    $content = str_replace('`' . $table['name'] . '`', '`' . $store_table_name . '`', $content);
                }

                // Xuất dữ liệu
                if (!empty($table['numrow'])) {
                    $columns = [];
                    $columns_array = $db->columns_array($table['name']);
                    foreach ($columns_array as $col) {
                        $columns[$col['field']] = preg_match('/^(\w*int|year)/', $col['type']) ? 'int' : 'txt';
                    }

                    $maxi = ceil($table['numrow'] / $table['limit']);
                    $from = 0;
                    $a = 0;
                    for ($i = 0; $i < $maxi; ++$i) {
                        $db->sqlreset()->select('*')->from($table['name'])->limit($table['limit'])->offset($from);
                        $result = $db->query($db->sql());
                        while ($row = $result->fetch()) {
                            // Bỏ qua tài khoản có userid = 1
                            if ($table['name'] == NV_USERS_GLOBALTABLE and $row['userid'] == 1) {
                                continue;
                            }
                            if (preg_match('/^' . nv_preg_quote($db_config['prefix']) . '\_([a-z0-9]+)\_menu\_rows$/', $table['name'])) {
                                // Chỉnh lại đường dẫn menu
                                if (isset($row['link'])) {
                                    if ($row['link'] == NV_BASE_SITEURL) {
                                        $row['link'] = '{{NV_BASE_SITEURL}}';
                                    } else {
                                        $row['link'] = preg_replace('/^(' . nv_preg_quote(NV_BASE_SITEURL) . ')([a-zA-Z0-9\-]+)/', '{{NV_BASE_SITEURL}}\\2', $row['link']);
                                    }
                                }
                            } elseif (preg_match('/^' . nv_preg_quote($db_config['prefix']) . '\_([a-z0-9]+)\_blocks\_groups$/', $table['name'])) {
                                // Các đường dẫn trong này không chỉnh được
                            } else {
                                // Chỉnh lại đường dẫn các module
                                $array_mods_news = $array_mods_page = [];
                                foreach ($site_mods as $mod) {
                                    if ($mod['module_file'] == 'news') {
                                        $array_mods_news[] = nv_preg_quote($mod['module_data']);
                                    } elseif ($mod['module_file'] == 'page') {
                                        $array_mods_page[] = nv_preg_quote($mod['module_data']);
                                    }
                                }
                                if (!empty($array_mods_news) and preg_match('/^' . nv_preg_quote($db_config['prefix']) . '\_([a-z0-9]+)\_(' . implode('|', $array_mods_news) . ')\_detail$/', $table['name'])) {
                                    if (isset($row['bodyhtml'])) {
                                        $row['bodyhtml'] = strtr($row['bodyhtml'], [
                                            "\r\n" => '',
                                            "\r" => '',
                                            "\n" => ''
                                        ]);
                                        $row['bodyhtml'] = preg_replace('/(href|src)[\s]*\=[\s]*("|\')(' . nv_preg_quote(NV_BASE_SITEURL) . ')([a-zA-Z0-9\-]+)/i', '\\1=\\2{{NV_BASE_SITEURL}}\\4', $row['bodyhtml']);
                                    }
                                } elseif (!empty($array_mods_page) and preg_match('/^' . nv_preg_quote($db_config['prefix']) . '\_([a-z0-9]+)\_(' . implode('|', $array_mods_page) . ')$/', $table['name'])) {
                                    if (isset($row['bodytext'])) {
                                        $row['bodytext'] = strtr($row['bodytext'], [
                                            "\r\n" => ' ',
                                            "\r" => ' ',
                                            "\n" => ' '
                                        ]);
                                        $row['bodytext'] = preg_replace('/(href|src)[\s]*\=[\s]*("|\')(' . nv_preg_quote(NV_BASE_SITEURL) . ')([a-zA-Z0-9\-]+)/i', '\\1=\\2{{NV_BASE_SITEURL}}\\4', $row['bodytext']);
                                    }
                                }
                            }

                            if (isset($array_method_update[$table['name']])) {
                                // Các bảng thực hiện Update
                                $setting = $array_method_update[$table['name']];
                                $is_ignore = false;
                                foreach ($setting['ignore'] as $ignore_row) {
                                    $check_ignore = 0;
                                    foreach ($ignore_row as $k => $v) {
                                        if ($row[$k] == $v) {
                                            ++$check_ignore;
                                        }
                                    }
                                    if ($check_ignore >= sizeof($ignore_row)) {
                                        $is_ignore = true;
                                        break;
                                    }
                                }
                                if (empty($setting['ignore']) or !$is_ignore) {
                                    // Các bảng chực hiện thực hiện REPLACE
                                    $row2 = [];
                                    foreach ($columns as $key => $kt) {
                                        $row2[] = isset($row[$key]) ? (($kt == 'int') ? $row[$key] : "'" . addslashes($row[$key]) . "'") : 'NULL';
                                    }
                                    $row2 = implode(', ', $row2);
                                    $row2 = str_replace('{{NV_BASE_SITEURL}}', '" . NV_BASE_SITEURL . "', $row2);
                                    $content .= '$sql_create_table[] = "REPLACE INTO `' . $store_table_name . '` (`' . implode('`, `', array_keys($columns)) . '`) VALUES (' . $row2 . ")\";\n";
                                }
                            } else {
                                // Các bảng chực hiện thực hiện Insert
                                $row2 = [];
                                foreach ($columns as $key => $kt) {
                                    $row2[] = isset($row[$key]) ? (($kt == 'int') ? $row[$key] : "'" . addslashes($row[$key]) . "'") : 'NULL';
                                }
                                $row2 = implode(', ', $row2);
                                $row2 = str_replace('{{NV_BASE_SITEURL}}', '" . NV_BASE_SITEURL . "', $row2);
                                $content .= '$sql_create_table[] = "INSERT INTO `' . $store_table_name . '` (`' . implode('`, `', array_keys($columns)) . '`) VALUES (' . $row2 . ")\";\n";
                            }

                            ++$a;
                            if ($a >= $table['numrow']) {
                                break;
                            }
                        }
                        $result->closeCursor();
                        $from += $table['limit'];
                    }
                }

                $check = file_put_contents($file_data_dump, $content, FILE_APPEND);
                if ($check === false) {
                    $json['message'] = sprintf($lang_module['sampledata_error_writetmp'], NV_TEMP_DIR);
                    $error = true;
                    break;
                }
            }
        }

        nv_deletefile($file_data_tmp);

        if ($error) {
            nv_deletefile($file_data_tmp);
        } else {
            $json['finish'] = true;
            $file_sample_data = NV_ROOTDIR . '/install/samples/data_' . $array_request['sample_name'] . '.php';
            if (file_exists($file_sample_data)) {
                nv_deletefile($file_sample_data);
            }
            $check = nv_copyfile($file_data_dump, $file_sample_data);
            if ($check) {
                nv_deletefile($file_data_dump);
                $json['lev'] = 1;
                $json['reload'] = true;
                $json['message'] = $lang_module['sampledata_success_1'];
            } else {
                $link_download = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;downloadfile=1&amp;sample_name=' . urlencode($array_request['sample_name']);
                $json['lev'] = 2;
                $json['message'] = sprintf($lang_module['sampledata_success_2'], $link_download);
            }
        }
    }

    $json['nextdata'] = $array_request;
    nv_jsonOutput($json);
}

$page_title = $lang_module['sampledata'];

if (file_exists($file_data_dump)) {
    nv_deletefile($file_data_dump);
}
if (file_exists($file_data_tmp)) {
    nv_deletefile($file_data_tmp);
}

$xtpl = new XTemplate('sampledata.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

$error = '';
$files = nv_scandir(NV_ROOTDIR . '/install/samples', '/^data\_([a-z0-9]+)\.php$/');
$array = [];

foreach ($files as $file) {
    $array[] = [
        'title' => substr(substr($file, 5), 0, -4),
        'creattime' => nv_date('H:i d/m/Y', filemtime(NV_ROOTDIR . '/install/samples/' . $file)),
    ];
}

if (empty($error)) {
    $xtpl->parse('main.info');
} else {
    $xtpl->parse('main.error');
}

if (empty($array)) {
    $xtpl->parse('main.empty');
} else {
    foreach ($array as $row) {
        $row['checkss'] = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $row['title']);
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.data.loop');
    }
    $xtpl->parse('main.data');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
