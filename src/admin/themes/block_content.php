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

// AJAX load danh sách block của module
if ($nv_Request->isset_request('loadBlocks, bid', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong ajax!');
    }
    $respon = [
        'error' => 1,
        'text' => 'Wrong session!!!'
    ];
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput($respon);
    }

    $module = $nv_Request->get_string('loadBlocks', 'post', '');
    $bid = $nv_Request->get_int('bid', 'post', 0);
    $selectthemes = $nv_Request->get_string('selectthemes', 'post', $global_config['site_theme']);

    $respon['error'] = 0;
    $respon['html'] = loadblock($module, $bid, $selectthemes);

    nv_jsonOutput($respon);
}

$functionid = $nv_Request->get_int('func', 'get');
$blockredirect = $nv_Request->get_string('blockredirect', 'get');

$selectthemes = $nv_Request->get_string('selectthemes', 'post,get', $global_config['site_theme']);
if (!(preg_match($global_config['check_theme'], $selectthemes) or preg_match($global_config['check_theme_mobile'], $selectthemes))) {
    nv_error404();
}

$page_title = $nv_Lang->getModule('blocks') . ': ' . $nv_Lang->getModule('theme', $selectthemes);
$dtime_types = ['regular', 'specific', 'daily', 'weekly', 'monthly', 'yearly'];

$row = [
    'bid' => 0,
    'theme' => '',
    'module' => 'theme',
    'file_name' => '',
    'title' => '',
    'link' => '',
    'template' => '',
    'heading' => 0,
    'position' => $nv_Request->get_title('tag', 'get', ''),
    'dtime_type' => 'regular',
    'dtime_details' => [],
    'active' => 1,
    'bot_visible' => 1,
    'act' => 1,
    'groups_view' => '6',
    'all_func' => 1,
    'weight' => 0,
    'config' => ''
];

$row_old = [];
$is_add = true;

$row['bid'] = $nv_Request->get_int('bid', 'get,post', 0);
if ($row['bid'] > 0) {
    $row = $db->query('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE bid=' . $row['bid'])->fetch();

    if (empty($row)) {
        nv_error404();
    } else {
        $row['dtime_details'] = json_decode($row['dtime_details'], true);
        $row_old = $row;
    }
    $is_add = false;
}

// AJAX lấy thông tin hiển thị theo thời gian
if ($nv_Request->isset_request('get_dtime_details', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong ajax!');
    }
    $respon = [
        'error' => 1,
        'text' => 'Wrong session!!!'
    ];
    if ($nv_Request->get_title('checkss', 'post', '') !== NV_CHECK_SESSION) {
        nv_jsonOutput($respon);
    }

    $dtime_type = $nv_Request->get_title('get_dtime_details', 'post');
    !in_array($dtime_type, $dtime_types, true) && $dtime_type = 'regular';

    $respon['error'] = 0;
    $respon['html'] = get_dtime_details($dtime_type, $row['dtime_details']);

    nv_jsonOutput($respon);
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

    $path_file_php = $path_file_ini = $path_file_json = $block_type = $block_dir = '';
    if ($module == 'theme' and file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name)) {
        $path_file_php = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $file_name;
        $path_file_ini = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
        $path_file_json = NV_ROOTDIR . '/themes/' . $selectthemes . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
        $block_type = 'theme';
        $block_dir = $selectthemes;
    } elseif (isset($site_mods[$module])) {
        $mod_file = $site_mods[$module]['module_file'];

        if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name)) {
            $path_file_php = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $file_name;
            $path_file_ini = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.ini';
            $path_file_json = NV_ROOTDIR . '/modules/' . $mod_file . '/blocks/' . $matches[1] . '.' . $matches[2] . '.json';
            $block_type = 'module';
            $block_dir = $mod_file;
        }
    }

    if (empty($row['title'])) {
        $row['title'] = str_replace('_', ' ', $matches[1] . ' ' . $matches[2]);
    }

    $row['link'] = $nv_Request->get_title('link', 'post', '');
    $row['template'] = nv_substr($nv_Request->get_title('template', 'post', '', 0), 0, 55);
    $row['heading'] = $nv_Request->get_int('heading', 'post', 0);
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

    if ($row['heading'] < 0 or $row['heading'] > 6) {
        $row['heading'] = 0;
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

    $row['bot_visible'] = (int) $nv_Request->get_bool('bot_visible', 'post', false);

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

    // Xử lý khi block có cấu hình
    if (!empty($path_file_php) and (!empty($path_file_ini) or !empty($path_file_json))) {
        $submit_function = '';
        $lang_block = [];

        // Đọc tệp ini (cũ)
        if (!empty($path_file_ini) and file_exists($path_file_ini)) {
            $xml = simplexml_load_file($path_file_ini);
            if ($xml !== false) {
                $submit_function = trim($xml->submitfunction);

                $xmllanguage = $xml->xpath('language');
                if (!empty($xmllanguage)) {
                    $language = (array) $xmllanguage[0];

                    if (isset($language[NV_LANG_INTERFACE])) {
                        $lang_block = (array) $language[NV_LANG_INTERFACE];
                    } elseif (isset($language['en'])) {
                        $lang_block = (array) $language['en'];
                    }
                }
            }
        }

        // Đọc tệp json (mới)
        if (!empty($path_file_json) and file_exists($path_file_json)) {
            $json = json_decode(file_get_contents($path_file_json), true);

            if (!empty($json['submitfunction'])) {
                $submit_function = trim($json['submitfunction']);
            }

            // Ngôn ngữ cấu hình block
            if (!empty($json['i18n']) and is_array($json['i18n'])) {
                if (!empty($json['i18n'][NV_LANG_INTERFACE]) and is_array($json['i18n'][NV_LANG_INTERFACE]) and !empty($json['i18n'][NV_LANG_INTERFACE]['language']) and is_array($json['i18n'][NV_LANG_INTERFACE]['language'])) {
                    $lang_block = array_merge($lang_block, (array) $json['i18n'][NV_LANG_INTERFACE]['language']);
                }
                if (empty($lang_block) and !empty($json['i18n']['en']) and is_array($json['i18n']['en']) and !empty($json['i18n']['en']['language']) and is_array($json['i18n']['en']['language'])) {
                    $lang_block = array_merge($lang_block, (array) $json['i18n']['en']['language']);
                }
            }
        }

        // Xử lý cấu hình block khi nó tồn tại
        if (!empty($submit_function)) {
            include_once $path_file_php;

            if (nv_function_exists($submit_function) and ($block_type == 'module' or $block_type == 'theme')) {
                if ($block_type == 'module') {
                    $nv_Lang->loadModule($block_dir, false, true);
                } elseif ($block_type == 'theme') {
                    $nv_Lang->loadTheme($block_dir, true);
                }
                if (!empty($lang_block)) {
                    $nv_Lang->setModule($lang_block, '', true);
                }

                // Gọi hàm xử lý hiển thị block
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

    $array_funcid_module = [];
    foreach ($site_mods as $mod => $_arr_mod) {
        foreach ($_arr_mod['funcs'] as $_row) {
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
            while ([$bid_i, $func_id_i] = $sth->fetch(3)) {
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

            $_sql = 'INSERT INTO ' . NV_BLOCKS_TABLE . '_groups (
                theme, module, file_name, title, link, template, heading, position, dtime_type,
                dtime_details, active, bot_visible, act, groups_view, all_func, weight, config
            ) VALUES (
                :selectthemes, :module, :file_name, :title, :link, :template, :heading, :position,
                :dtime_type, :dtime_details, :active, ' . $row['bot_visible'] . ', ' . $row['act'] . ', :groups_view,
                ' . $row['all_func'] . ', ' . $row['weight'] . ', :config
            )';
            $data = [];
            $data['selectthemes'] = $selectthemes;
            $data['module'] = $row['module'];
            $data['file_name'] = $row['file_name'];
            $data['title'] = $row['title'];
            $data['link'] = $row['link'];
            $data['template'] = $row['template'];
            $data['heading'] = $row['heading'];
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
                    heading=:heading,
                    position=:position,
                    dtime_type=:dtime_type,
                    dtime_details=:dtime_details,
                    active=:active,
                    bot_visible=' . $row['bot_visible'] . ',
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
            $sth->bindParam(':heading', $row['heading'], PDO::PARAM_INT);
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

            while ([$func_inlist] = $result_func->fetch(3)) {
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

[$template, $dir] = get_module_tpl_dir('block-content.tpl', true);
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir($dir);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('TEMPLATE', $template);

$row['link'] = nv_htmlspecialchars($row['link']);
$row['checkss'] = $checkss;
$row['active_device'] = !empty($row['active']) ? array_map('intval', explode(',', $row['active'])) : [];
$row['groups_view'] = array_map('intval', explode(',', $row['groups_view']));
$row['block_global'] = preg_match('/^global\.([a-zA-Z0-9\-\_\.]+)\.php$/', $row['file_name']);

$tpl->assign('SELECTTHEMES', $selectthemes);
$tpl->assign('BLOCKREDIRECT', $blockredirect);
$tpl->assign('ROW', $row);

$list_modules = [];
$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . (!NV_DEBUG ? ' WHERE act = 1' : '') . ' ORDER BY weight ASC';
$result = $db->query($sql);
while ($row_i = $result->fetch()) {
    $list_modules[] = [
        'value' => $row_i['title'],
        'title' => $row_i['custom_title']
    ];
}
$result->closeCursor();

$templ_list = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', '/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/');
$templ_list = preg_replace('/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/', '\\1', $templ_list);

$tpl->assign('POSITIONS', nv_get_blocks($selectthemes, false));
$tpl->assign('LIST_TEMPLATES', $templ_list);
$tpl->assign('LIST_MODULES', $list_modules);
$tpl->assign('BLOCKLIST', loadblock($row['module'], $row['bid'], $selectthemes));
$tpl->assign('DTIME_TYPES', $dtime_types);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('SITE_MODS', $site_mods);

$dtime_details = get_dtime_details($row['dtime_type'], $row['dtime_details']);
$tpl->assign('DTIME_DETAILS', $dtime_details);

if (!empty($row['bid'])) {
    $blocks_num = $db->query('SELECT COUNT(*) FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid'])->fetchColumn();
    $tpl->assign('BLOCKS_NUM', nv_number_format($blocks_num));
}

$sql = 'SELECT func_id, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY in_module ASC, subweight ASC';
$func_result = $db->query($sql);
$aray_mod_func = [];
while ([$id_i, $func_custom_name_i, $in_module_i] = $func_result->fetch(3)) {
    $aray_mod_func[$in_module_i][] = [
        'id' => $id_i,
        'func_custom_name' => $func_custom_name_i
    ];
}

$func_list = [];
if ($row['bid']) {
    $result_func = $db->query('SELECT func_id FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid=' . $row['bid']);
    while ([$func_inlist] = $result_func->fetch(3)) {
        $func_list[] = $func_inlist;
    }
}

$sql = 'SELECT title, custom_title FROM ' . NV_MODULES_TABLE . (!NV_DEBUG ? ' WHERE act = 1' : '') . ' ORDER BY weight ASC';
$result = $db->query($sql);

$mod_funcs = [];
while ([$m_title, $m_custom_title] = $result->fetch(3)) {
    if (isset($aray_mod_func[$m_title]) and count($aray_mod_func[$m_title]) > 0) {
        if (!isset($mod_funcs[$m_title])) {
            $mod_funcs[$m_title] = [
                'key' => $m_title,
                'title' => $m_custom_title,
                'func_checked' => 0,
                'funcs' => []
            ];
        }

        foreach ($aray_mod_func[$m_title] as $aray_mod_func_i) {
            $mod_funcs[$m_title]['funcs'][$aray_mod_func_i['id']] = [
                'id' => $aray_mod_func_i['id'],
                'name' => $aray_mod_func_i['func_custom_name'],
                'checked' => 0
            ];

            if (in_array((int) $aray_mod_func_i['id'], array_map('intval', $func_list), true) or $functionid == $aray_mod_func_i['id']) {
                $mod_funcs[$m_title]['funcs'][$aray_mod_func_i['id']]['checked'] = 1;
                $mod_funcs[$m_title]['func_checked']++;
            }
        }
    }
}
$tpl->assign('MOD_FUNCS', $mod_funcs);

$contents = $tpl->fetch('block-content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, 0);
include NV_ROOTDIR . '/includes/footer.php';
