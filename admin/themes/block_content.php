<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_THEMES')) {
    exit('Stop!!!');
}

// AJAX load danh sách block của module
if ($nv_Request->isset_request('loadBlocks, bid', 'get')) {
    $module = $nv_Request->get_string('loadBlocks', 'get', '');
    $bid = $nv_Request->get_int('bid', 'get', 0);
    $selectthemes = $nv_Request->get_string('selectthemes', 'get', $global_config['site_theme']);
    nv_htmlOutput(loadblock($module, $bid, $selectthemes));
}

$functionid = $nv_Request->get_int('func', 'get');
$blockredirect = $nv_Request->get_string('blockredirect', 'get');

$selectthemes = $nv_Request->get_string('selectthemes', 'post,get', $global_config['site_theme']);
if (!(preg_match($global_config['check_theme'], $selectthemes) or preg_match($global_config['check_theme_mobile'], $selectthemes))) {
    nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
}

$dtime_types = ['regular', 'specific', 'daily', 'weekly', 'monthly', 'yearly'];

$row = [
    'bid' => 0,
    'theme' => '',
    'module' => 'theme',
    'file_name' => '',
    'title' => '',
    'link' => '',
    'template' => '',
    'position' => $nv_Request->get_title('tag', 'get', ''),
    'dtime_type' => 'regular',
    'dtime_details' => [],
    'active' => 1,
    'act' => 1,
    'groups_view' => '6',
    'all_func' => 1,
    'weight' => 0,
    'config' => ''];
$row_old = [];
$is_add = true;
$row['bid'] = $nv_Request->get_int('bid', 'get,post', 0);
if ($row['bid'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $row['bid'])->fetch();

    if (empty($row)) {
        nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('error_404_content'), 404);
    } else {
        $row['dtime_details'] = json_decode($row['dtime_details'], true);
        $row_old = $row;
    }
    $is_add = false;
}

// AJAX lấy thông tin hiển thị
if ($nv_Request->isset_request('get_dtime_details', 'post')) {
    $dtime_type = $nv_Request->get_title('get_dtime_details', 'post');
    !in_array($dtime_type, $dtime_types, true) && $dtime_type = 'regular';
    nv_htmlOutput(get_dtime_details($dtime_type, $row['dtime_details']));
}

$groups_list = nv_groups_list();

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $row['bid']);
if ($checkss == $nv_Request->get_string('checkss', 'post')) {
    $list_file_name = $nv_Request->get_title('file_name', 'post', '', 0);
    $array_file_name = explode('|', $list_file_name);
    if (!isset($array_file_name[1])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('block_error_nsblock')
        ]);
    }

    $file_name = $row['file_name'] = trim($array_file_name[0]);
    $module = $row['module'] = nv_substr($nv_Request->get_title('module_type', 'post', '', 0), 0, 55);
    $row['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);

    unset($matches);
    if ($module == 'theme') {
        preg_match($global_config['check_block_theme'], $row['file_name'], $matches);
    } else {
        preg_match($global_config['check_block_module'], $row['file_name'], $matches);
    }

    $path_file_php = $path_file_ini = $block_type = $block_dir = '';
    if ($module == 'theme' and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name) and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) {
        $path_file_php = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name;
        $path_file_ini = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
        $block_type = 'theme';
        $block_dir = $selectthemes;
    } elseif (isset($site_mods[$module])) {
        $mod_file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name) and file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini')) {
            $path_file_php = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name;
            $path_file_ini = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
            $block_type = 'module';
            $block_dir = $mod_file;
        }
    }

    if (empty($row['title'])) {
        $row['title'] = str_replace('_', ' ', $matches[1] . ' ' . $matches[2]);
    }

    $row['link'] = $nv_Request->get_title('link', 'post', '');
    $row['template'] = nv_substr($nv_Request->get_title('template', 'post', '', 0), 0, 55);
    $row['position'] = $nv_Request->get_title('position', 'post', '', 0);
    $row['position'] = nv_substr(nv_unhtmlspecialchars($row['position']), 0, 55);
    $row['act'] = (int) $nv_Request->get_bool('act', 'post', false);
    $row['dtime_type'] = $nv_Request->get_title('dtime_type', 'post', 'regular');
    !in_array($row['dtime_type'], $dtime_types, true) && $row['dtime_type'] = 'regular';
    $row['dtime_details'] = [];
    if ($row['dtime_type'] == 'specific') {
        $dtime_details = [
            'start_date' => $nv_Request->get_typed_array('start_date', 'post', 'string', []),
            'start_h' => $nv_Request->get_typed_array('start_h', 'post', 'int', []),
            'start_i' => $nv_Request->get_typed_array('start_i', 'post', 'int', []),
            'end_date' => $nv_Request->get_typed_array('end_date', 'post', 'string', []),
            'end_h' => $nv_Request->get_typed_array('end_h', 'post', 'int', []),
            'end_i' => $nv_Request->get_typed_array('end_i', 'post', 'int', [])
        ];

        foreach (array_keys($dtime_details['start_date']) as $key) {
            empty($dtime_details['start_date'][$key]) && $dtime_details['start_date'][$key] = date('d/m/Y');
            unset($array_start_date, $array_end_date);
            preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $dtime_details['start_date'][$key], $array_start_date);
            preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $dtime_details['end_date'][$key], $array_end_date);
            if (!empty($array_start_date) and !empty($array_end_date)) {
                $start_time = mktime($dtime_details['start_h'][$key], $dtime_details['start_i'][$key], 0, (int) $array_start_date[1], (int) $array_start_date[0], (int) $array_start_date[2]);
                $end_time = mktime($dtime_details['end_h'][$key], $dtime_details['end_i'][$key], 0, (int) $array_end_date[1], (int) $array_end_date[0], (int) $array_end_date[2]);
                if ($start_time < $end_time) {
                    $row['dtime_details'][$start_time . '-' . $end_time] = [
                        'start_date' => $dtime_details['start_date'][$key],
                        'start_h' => $dtime_details['start_h'][$key],
                        'start_i' => $dtime_details['start_i'][$key],
                        'end_date' => $dtime_details['end_date'][$key],
                        'end_h' => $dtime_details['end_h'][$key],
                        'end_i' => $dtime_details['end_i'][$key]
                    ];
                }
            }
        }
    } elseif ($row['dtime_type'] == 'daily') {
        $dtime_details = [
            'start_h' => $nv_Request->get_typed_array('start_h', 'post', 'int', []),
            'start_i' => $nv_Request->get_typed_array('start_i', 'post', 'int', []),
            'end_h' => $nv_Request->get_typed_array('end_h', 'post', 'int', []),
            'end_i' => $nv_Request->get_typed_array('end_i', 'post', 'int', [])
        ];

        foreach (array_keys($dtime_details['start_h']) as $key) {
            $start_time = mktime($dtime_details['start_h'][$key], $dtime_details['start_i'][$key], 0);
            $end_time = mktime($dtime_details['end_h'][$key], $dtime_details['end_i'][$key], 0);
            if ($start_time < $end_time) {
                $row['dtime_details'][$start_time . '-' . $end_time] = [
                    'start_h' => $dtime_details['start_h'][$key],
                    'start_i' => $dtime_details['start_i'][$key],
                    'end_h' => $dtime_details['end_h'][$key],
                    'end_i' => $dtime_details['end_i'][$key]
                ];
            }
        }
    } elseif ($row['dtime_type'] == 'weekly') {
        $dtime_details = [
            'day_of_week' => $nv_Request->get_typed_array('day_of_week', 'post', 'int', []),
            'start_h' => $nv_Request->get_typed_array('start_h', 'post', 'int', []),
            'start_i' => $nv_Request->get_typed_array('start_i', 'post', 'int', []),
            'end_h' => $nv_Request->get_typed_array('end_h', 'post', 'int', []),
            'end_i' => $nv_Request->get_typed_array('end_i', 'post', 'int', [])
        ];
        foreach (array_keys($dtime_details['start_h']) as $key) {
            $start_time = mktime($dtime_details['start_h'][$key], $dtime_details['start_i'][$key], 0);
            $end_time = mktime($dtime_details['end_h'][$key], $dtime_details['end_i'][$key], 0);
            if ($start_time < $end_time) {
                $row['dtime_details'][$dtime_details['day_of_week'][$key] . '-' . $start_time . '-' . $end_time] = [
                    'day_of_week' => $dtime_details['day_of_week'][$key],
                    'start_h' => $dtime_details['start_h'][$key],
                    'start_i' => $dtime_details['start_i'][$key],
                    'end_h' => $dtime_details['end_h'][$key],
                    'end_i' => $dtime_details['end_i'][$key]
                ];
            }
        }
    } elseif ($row['dtime_type'] == 'monthly') {
        $dtime_details = [
            'day' => $nv_Request->get_typed_array('day', 'post', 'int', []),
            'start_h' => $nv_Request->get_typed_array('start_h', 'post', 'int', []),
            'start_i' => $nv_Request->get_typed_array('start_i', 'post', 'int', []),
            'end_h' => $nv_Request->get_typed_array('end_h', 'post', 'int', []),
            'end_i' => $nv_Request->get_typed_array('end_i', 'post', 'int', [])
        ];
        foreach (array_keys($dtime_details['start_h']) as $key) {
            $start_time = mktime($dtime_details['start_h'][$key], $dtime_details['start_i'][$key], 0);
            $end_time = mktime($dtime_details['end_h'][$key], $dtime_details['end_i'][$key], 0);
            if ($start_time < $end_time) {
                $row['dtime_details'][$dtime_details['day'][$key] . '-' . $start_time . '-' . $end_time] = [
                    'day' => $dtime_details['day'][$key],
                    'start_h' => $dtime_details['start_h'][$key],
                    'start_i' => $dtime_details['start_i'][$key],
                    'end_h' => $dtime_details['end_h'][$key],
                    'end_i' => $dtime_details['end_i'][$key]
                ];
            }
        }
    } elseif ($row['dtime_type'] == 'yearly') {
        $dtime_details = [
            'month' => $nv_Request->get_typed_array('month', 'post', 'int', []),
            'day' => $nv_Request->get_typed_array('day', 'post', 'int', []),
            'start_h' => $nv_Request->get_typed_array('start_h', 'post', 'int', []),
            'start_i' => $nv_Request->get_typed_array('start_i', 'post', 'int', []),
            'end_h' => $nv_Request->get_typed_array('end_h', 'post', 'int', []),
            'end_i' => $nv_Request->get_typed_array('end_i', 'post', 'int', [])
        ];
        foreach (array_keys($dtime_details['start_h']) as $key) {
            $start_time = mktime($dtime_details['start_h'][$key], $dtime_details['start_i'][$key], 0);
            $end_time = mktime($dtime_details['end_h'][$key], $dtime_details['end_i'][$key], 0);
            if ($start_time < $end_time) {
                $row['dtime_details'][$dtime_details['month'][$key] . '-' . $dtime_details['day'][$key] . '-' . $start_time . '-' . $end_time] = [
                    'month' => $dtime_details['month'][$key],
                    'day' => $dtime_details['day'][$key],
                    'start_h' => $dtime_details['start_h'][$key],
                    'start_i' => $dtime_details['start_i'][$key],
                    'end_h' => $dtime_details['end_h'][$key],
                    'end_i' => $dtime_details['end_i'][$key]
                ];
            }
        }
    }

    if ($row['dtime_type'] != 'regular' and empty($row['dtime_details'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('invalid_display_time')
        ]);
    }
    if (!empty($row['dtime_details'])) {
        ksort($row['dtime_details']);
        $row['dtime_details'] = array_values($row['dtime_details']);
    }

    $row['active_device'] = $nv_Request->get_typed_array('active_device', 'post', 'int');
    if (in_array(1, $row['active_device'], true) or (in_array(2, $row['active_device'], true) and in_array(3, $row['active_device'], true) and in_array(4, $row['active_device'], true))) {
        $row['active'] = 1;
    } else {
        $row['active'] = implode(',', $row['active_device']);
    }

    $groups_view = $nv_Request->get_array('groups_view', 'post', []);
    $row['groups_view'] = !empty($groups_view) ? implode(',', nv_groups_post(array_intersect($groups_view, array_keys($groups_list)))) : '';

    $all_func = ($nv_Request->get_int('all_func', 'post') == 1 and ((preg_match($global_config['check_block_module'], $row['file_name']) or preg_match($global_config['check_block_theme'], $row['file_name'])) and preg_match('/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $row['file_name']))) ? 1 : 0;
    $array_funcid_post = $nv_Request->get_array('func_id', 'post');

    if (empty($all_func) and empty($array_funcid_post)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('block_no_func')
        ]);
    }

    $row['leavegroup'] = $nv_Request->get_int('leavegroup', 'post', 0);

    if (!empty($row['leavegroup']) and !empty($row['bid'])) {
        $all_func = 0;
        $row['leavegroup'] = 1;
    } else {
        $row['leavegroup'] = 0;
    }

    $row['all_func'] = $all_func;
    $row['config'] = '';

    if (!empty($path_file_php) and !empty($path_file_ini)) {
        // Load cac cau hinh cua block
        $xml = simplexml_load_file($path_file_ini);

        if ($xml !== false) {
            $submit_function = trim($xml->submitfunction);

            if (!empty($submit_function)) {
                // Neu ton tai function de xay dung cau truc cau hinh block
                include_once $path_file_php;

                if (nv_function_exists($submit_function) and ($block_type == 'module' or $block_type == 'theme')) {
                    if ($block_type == 'module') {
                        $nv_Lang->loadModule($block_dir, false, true);
                    } elseif ($block_type == 'theme') {
                        $nv_Lang->loadTheme($block_dir, true);
                    }

                    $xmllanguage = $xml->xpath('language');
                    if (!empty($xmllanguage)) {
                        $language = (array) $xmllanguage[0];

                        if (isset($language[NV_LANG_INTERFACE])) {
                            $lang_block = (array) $language[NV_LANG_INTERFACE];
                        } elseif (isset($language['en'])) {
                            $lang_block = (array) $language['en'];
                        } else {
                            $xmlkey = $xml->xpath('config');
                            $language = (array) $xmlkey[0];
    
                            $key = array_keys($language);
                            $lang_block = array_combine($key, $key);
                        }

                        $nv_Lang->setModule($lang_block, '', true);
                    }

                    // Goi ham xu ly hien thi block
                    $array_config = call_user_func($submit_function, $module);

                    // Xóa lang tạm giải phóng bộ nhớ
                    $nv_Lang->changeLang();

                    if (!empty($array_config['config'])) {
                        $row['config'] = serialize($array_config['config']);
                    } else {
                        $row['config'] = '';
                    }

                    if (!empty($array_config['error'])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => implode(', ', $array_config['error'])
                        ]);
                    }
                }
            }
        }
    }

    $array_funcid_module = [];
    foreach ($site_mods as $mod => $_arr_mod) {
        foreach ($_arr_mod['funcs'] as $_func => $_row) {
            if ($_row['show_func']) {
                $array_funcid_module[$_row['func_id']] = $mod;
            }
        }
    }

    if ($all_func) {
        $array_funcid = array_keys($array_funcid_module);
    } elseif (preg_match('/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $row['file_name'])) {
        $array_funcid = array_intersect($array_funcid_post, array_keys($array_funcid_module));
    } else {
        $array_in_module = [];
        if ($module == 'theme') {
            if (preg_match($global_config['check_block_theme'], $row['file_name'], $matches)) {
                foreach ($site_mods as $mod => $row_i) {
                    if ($row_i['module_file'] == $matches[1]) {
                        $array_in_module[] = $mod;
                    }
                }
            }
        } elseif (isset($site_mods[$module])) {
            $array_in_module[] = $module;
        }

        $array_funcid = [];
        foreach ($array_funcid_module as $func_id => $mod) {
            if (in_array($mod, $array_in_module, true) and in_array((int) $func_id, array_map('intval', $array_funcid_post), true)) {
                $array_funcid[] = $func_id;
            }
        }
    }

    if (!empty($array_funcid)) {
        // Tach va tao nhom moi
        if (!empty($row['leavegroup'])) {
            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET all_func= 0 WHERE bid=' . $row['bid']);
            $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] . ' AND func_id in (' . implode(',', $array_funcid) . ')');

            // Cap nhat lai thu tu cho nhom cu
            $func_id_old = $weight = 0;
            $sth = $db->prepare('SELECT t1.bid, t1.func_id FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t2.theme= :theme AND t2.position= :position ORDER BY t1.func_id ASC, t1.weight ASC');
            $sth->bindParam(':theme', $row_old['theme'], PDO::PARAM_STR);
            $sth->bindParam(':position', $row_old['position'], PDO::PARAM_STR);
            $sth->execute();
            while (list($bid_i, $func_id_i) = $sth->fetch(3)) {
                if ($func_id_i == $func_id_old) {
                    ++$weight;
                } else {
                    $weight = 1;
                    $func_id_old = $func_id_i;
                }

                $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_weight SET weight=' . $weight . ' WHERE bid=' . $bid_i . ' AND func_id=' . $func_id_i);
            }
            unset($func_id_old, $weight);

            $row['bid'] = 0;
        }

        $row['dtime_details'] = json_encode($row['dtime_details'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if (empty($row['bid'])) {
            $sth = $db->prepare('SELECT MAX(weight) FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme AND position= :position');
            $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
            $sth->bindParam(':position', $row['position'], PDO::PARAM_STR);
            $sth->execute();
            $row['weight'] = (int) ($sth->fetchColumn()) + 1;

            $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (theme, module, file_name, title, link, template, position, dtime_type, dtime_details, active, act, groups_view, all_func, weight, config) VALUES ( :selectthemes, :module, :file_name, :title, :link, :template, :position, :dtime_type, :dtime_details, :active, ' . $row['act'] . ', :groups_view, ' . $row['all_func'] . ', ' . $row['weight'] . ', :config )';
            $data = [];
            $data['selectthemes'] = $selectthemes;
            $data['module'] = $row['module'];
            $data['file_name'] = $row['file_name'];
            $data['title'] = $row['title'];
            $data['link'] = $row['link'];
            $data['template'] = $row['template'];
            $data['position'] = $row['position'];
            $data['dtime_type'] = $row['dtime_type'];
            $data['dtime_details'] = $row['dtime_details'];
            $data['active'] = $row['active'];
            $data['groups_view'] = $row['groups_view'];
            $data['config'] = $row['config'];
            $row['bid'] = $db->insert_id($_sql, 'bid', $data);

            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('block_add'), 'Name : ' . $row['title'], $admin_info['userid']);
        } else {
            $sth = $db->prepare('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET
                    module=:module,
                    file_name=:file_name,
                    title=:title,
                    link=:link,
                    template=:template,
                    position=:position,
                    dtime_type=:dtime_type,
                    dtime_details=:dtime_details,
                    active=:active,
                    act=' . $row['act'] . ',
                    groups_view=:groups_view,
                    all_func=' . $row['all_func'] . ',
                    config=:config
                    WHERE bid = ' . $row['bid']);

            $sth->bindParam(':module', $row['module'], PDO::PARAM_STR);
            $sth->bindParam(':file_name', $row['file_name'], PDO::PARAM_STR);
            $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sth->bindParam(':link', $row['link'], PDO::PARAM_STR);
            $sth->bindParam(':template', $row['template'], PDO::PARAM_STR);
            $sth->bindParam(':position', $row['position'], PDO::PARAM_STR);
            $sth->bindParam(':dtime_type', $row['dtime_type'], PDO::PARAM_STR);
            $sth->bindParam(':dtime_details', $row['dtime_details'], PDO::PARAM_STR);
            $sth->bindParam(':active', $row['active'], PDO::PARAM_STR);
            $sth->bindParam(':groups_view', $row['groups_view'], PDO::PARAM_STR);
            $sth->bindParam(':config', $row['config'], PDO::PARAM_STR);
            $sth->execute();

            if (isset($site_mods[$module])) {
                $nv_Cache->delMod($module);
            }

            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('block_edit'), 'Name : ' . $row['title'], $admin_info['userid']);
        }

        if (!empty($row['bid'])) {
            $func_list = [];
            $result_func = $db->query('SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid']);

            while (list($func_inlist) = $result_func->fetch(3)) {
                $func_list[] = $func_inlist;
            }

            $array_funcid_old = array_diff($func_list, $array_funcid);

            if (!empty($array_funcid_old)) {
                $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'] . ' AND func_id in (' . implode(',', $array_funcid_old) . ')');
            }
            foreach ($array_funcid as $func_id) {
                if (!in_array((int) $func_id, array_map('intval', $func_list), true)) {
                    $sth = $db->prepare('SELECT MAX(t1.weight) FROM ' . NV_BLOCKS_TABLE . '_weight t1 INNER JOIN ' . NV_BLOCKS_TABLE . '_groups t2 ON t1.bid = t2.bid WHERE t1.func_id=' . $func_id . ' AND t2.theme= :theme AND t2.position= :position');
                    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
                    $sth->bindParam(':position', $row['position'], PDO::PARAM_STR);
                    $sth->execute();
                    $weight = $sth->fetchColumn();
                    $weight = (int) $weight + 1;

                    $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')');
                }
            }

            $nv_Cache->delMod('themes');
        }
    } elseif (!empty($row['bid'])) {
        $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $row['bid']);
        $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid']);

        $nv_Cache->delMod('themes');
    }

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $is_add ? $nv_Lang->getModule('block_add_success') : $nv_Lang->getModule('block_update_success'),
        'redirect' => !empty($blockredirect) ? nv_redirect_decrypt($blockredirect) : ''
    ]);
}

$xtpl = new XTemplate('block_content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('PAGE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selectthemes=' . $selectthemes . (!empty($blockredirect) ? '&amp;=blockredirect' . $blockredirect : ''));
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('THEME', $nv_Lang->getModule('theme', ucfirst($selectthemes)));
$xtpl->assign('PAGE_TITLE', $row['bid'] != 0 ? $nv_Lang->getModule('block_edit') : $nv_Lang->getModule('block_add'));

$groups_view = array_map('intval', explode(',', $row['groups_view']));

$sql = 'SELECT func_id, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY in_module ASC, subweight ASC';
$func_result = $db->query($sql);
$aray_mod_func = [];
while (list($id_i, $func_custom_name_i, $in_module_i) = $func_result->fetch(3)) {
    $aray_mod_func[$in_module_i][] = ['id' => $id_i, 'func_custom_name' => $func_custom_name_i];
}

// Load position file
$xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini') or nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getModule('block_error_fileconfig_title'), $nv_Lang->getModule('block_error_fileconfig_content'), 404);
$xmlpositions = $xml->xpath('positions');
$positions = $xmlpositions[0]->position;

$xtpl->assign('SELECTTHEMES', $selectthemes);
$xtpl->assign('BLOCKREDIRECT', $blockredirect);
$xtpl->assign('THEME_SELECTED', ($row['module'] == 'theme') ? ' selected="selected"' : '');

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . (!NV_DEBUG ? ' WHERE act = 1' : '') . ' ORDER BY weight ASC';
$result = $db->query($sql);
while ($row_i = $result->fetch()) {
    $xtpl->assign('MODULE', [
        'key' => $row_i['title'],
        'selected' => ($row_i['title'] == $row['module']) ? ' selected="selected"' : '',
        'title' => $row_i['custom_title']]);
    $xtpl->parse('main.module');
}

$blocklist = loadblock($row['module'], $row['bid'], $selectthemes);
$xtpl->assign('BLOCKLIST', $blocklist);

foreach ($dtime_types as $key) {
    $xtpl->assign('DTIME_TYPE', [
        'key' => $key,
        'sel' => $key == $row['dtime_type'] ? ' selected="selected"' : '',
        'title' => $nv_Lang->getModule('dtime_type_' . $key)
    ]);
    $xtpl->parse('main.dtime_type');
}

$dtime_details = get_dtime_details($row['dtime_type'], $row['dtime_details']);
$xtpl->assign('DTIME_DETAILS', $dtime_details);

$row['link'] = nv_htmlspecialchars($row['link']);
$row['checkss'] = $checkss;
$row['is_act'] = $row['act'] ? ' checked="checked"' : '';
$row['is_deact'] = $row['act'] ? '' : ' checked="checked"';

$xtpl->assign('ROW', $row);

$templ_list = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', '/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/');
$templ_list = preg_replace('/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/', '\\1', $templ_list);

foreach ($templ_list as $value) {
    if (!empty($value) and $value != 'default') {
        $xtpl->assign('TEMPLATE', [
            'key' => $value,
            'selected' => ($row['template'] == $value) ? ' selected="selected"' : '',
            'title' => $value]);
        $xtpl->parse('main.template');
    }
}

$active_device = !empty($row['active']) ? array_map('intval', explode(',', $row['active'])) : [];
for ($i = 1; $i <= 4; ++$i) {
    $xtpl->assign('ACTIVE_DEVICE', [
        'key' => $i,
        'checked' => (in_array($i, $active_device, true)) ? ' checked="checked"' : '',
        'title' => $nv_Lang->getModule('show_device_' . $i)
    ]);
    $xtpl->parse('main.active_device');
}

for ($i = 0, $count = sizeof($positions); $i < $count; ++$i) {
    $xtpl->assign('POSITION', [
        'key' => (string) $positions[$i]->tag,
        'selected' => ($row['position'] == $positions[$i]->tag) ? ' selected="selected"' : '',
        'title' => (string) $positions[$i]->name]);
    $xtpl->parse('main.position');
}

foreach ($groups_list as $group_id => $grtl) {
    $xtpl->assign('GROUPS_LIST', [
        'key' => $group_id,
        'selected' => (in_array((int) $group_id, $groups_view, true)) ? ' checked="checked"' : '',
        'title' => $grtl]);
    $xtpl->parse('main.groups_list');
}

if ($row['bid'] != 0) {// Tach ra va tao nhom moi
    $blocks_num = $db->query('SELECT COUNT(*) FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'])->fetchColumn();
    $xtpl->assign('BLOCKS_NUM', $nv_Lang->getModule('block_groupbl', $row['bid'], $blocks_num));

    $xtpl->parse('main.edit');
}

$add_block_module = [1 => $nv_Lang->getModule('add_block_all_module'), 0 => $nv_Lang->getModule('add_block_select_module')];

$i = 1;
foreach ($add_block_module as $b_key => $b_value) {
    $showsdisplay = (!preg_match('/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $row['file_name']) and $b_key == 1) ? ' style="display:none"' : '';

    $xtpl->assign('I', $i);
    $xtpl->assign('SHOWSDISPLAY', $showsdisplay);
    $xtpl->assign('B_KEY', $b_key);
    $xtpl->assign('B_VALUE', $b_value);
    $xtpl->assign('CK', ($row['all_func'] == $b_key) ? ' checked="checked"' : '');

    $xtpl->parse('main.add_block_module');
    ++$i;
}

$xtpl->assign('SHOWS_ALL_FUNC', ((int) ($row['all_func'])) ? ' style="display:none" ' : '');

$func_list = [];

if ($row['bid']) {
    $result_func = $db->query('SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid']);
    while (list($func_inlist) = $result_func->fetch(3)) {
        $func_list[] = $func_inlist;
    }
}

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . (!NV_DEBUG ? ' WHERE act = 1' : '') . ' ORDER BY weight ASC';
$result = $db->query($sql);
while (list($m_title, $m_custom_title) = $result->fetch(3)) {
    if (isset($aray_mod_func[$m_title]) and sizeof($aray_mod_func[$m_title]) > 0) {
        $i = 0;
        foreach ($aray_mod_func[$m_title] as $aray_mod_func_i) {
            $sel = '';

            if (in_array((int) $aray_mod_func_i['id'], array_map('intval', $func_list), true) or $functionid == $aray_mod_func_i['id']) {
                ++$i;
                $sel = ' checked="checked"';
            }

            $xtpl->assign('SELECTED', $sel);
            $xtpl->assign('FUNCID', $aray_mod_func_i['id']);
            $xtpl->assign('FUNCNAME', $aray_mod_func_i['func_custom_name']);

            $xtpl->parse('main.loopfuncs.fuc');
        }

        $xtpl->assign('M_TITLE', $m_title);
        $xtpl->assign('M_CUSTOM_TITLE', $m_custom_title);
        $xtpl->assign('M_CHECKED', (sizeof($aray_mod_func[$m_title]) == $i) ? ' checked="checked"' : '');

        $xtpl->parse('main.loopfuncs');
    }
}

$page_title = '&nbsp;&nbsp;' . $nv_Lang->getModule('blocks') . ': Theme ' . $selectthemes;

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, 0);
include NV_ROOTDIR . '/includes/footer.php';
