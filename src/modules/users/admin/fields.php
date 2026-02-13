<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Chỉnh thứ tự các trường tùy chỉnh. Không cho phép chỉnh các trường mặc định
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $fid = $nv_Request->get_int('fid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $checkss = $nv_Request->get_title('checkss', 'post', '');

    if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error!'
        ]);
    }

    $query = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid . ' AND is_system=0';
    $numrows = $db->query($query)->fetchColumn();

    $weightsystem = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_field WHERE is_system=1')->fetchColumn();
    if ($numrows != 1 or $new_vid <= $weightsystem) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_save')
        ]);
    }

    $query = 'SELECT fid FROM ' . NV_MOD_TABLE . '_field WHERE fid!=' . $fid . ' ORDER BY weight ASC';
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_MOD_TABLE . '_field SET weight=' . $weight . ' WHERE fid=' . $row['fid'];
        $db->query($sql);
    }
    $sql = 'UPDATE ' . NV_MOD_TABLE . '_field SET weight=' . $new_vid . ' WHERE fid=' . $fid;
    $db->query($sql);
    
    // Ghi log
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Change field weight', 'fid: ' . $fid . ', weight: ' . $new_vid, $admin_info['userid']);
    
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success')
    ]);
}

$array_sqlchoice_order = [
    'ASC' => $nv_Lang->getModule('field_options_choicesql_sort_asc'),
    'DESC' => $nv_Lang->getModule('field_options_choicesql_sort_desc')
];

// Xử lý lấy dữ liệu từ CSDL
if ($nv_Request->isset_request('choicesql', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $array_choicesql = [
        'module' => 'table',
        'table' => 'column',
        'column' => ''
    ];
    $choice = $nv_Request->get_string('choice', 'post', '');
    $choice_seltected = $nv_Request->get_string('choice_seltected', 'post', '');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);

    if ($choice == 'module') {
        $tpl->assign('choicesql_name', 'choicesql_' . $choice);
        $tpl->assign('choicesql_next', $array_choicesql[$choice]);
        $sql_list = [];
        foreach ($site_mods as $module) {
            $sql_list[] = [
                'sl' => ($choice_seltected == $module['module_data']),
                'key' => $module['module_data'],
                'val' => $module['custom_title']
            ];
        }
        $tpl->assign('SQL_LIST', $sql_list);
        $contents = $tpl->fetch('fields_choicesql.tpl');
    } elseif ($choice == 'table') {
        // Đây là trên bảng dữ liệu không phải tên module do đó chỉ chấp nhận ký tự thường, số và dấu gạch dưới
        $module = $nv_Request->get_string('module', 'post', '');
        if (!preg_match('/^[a-z0-9\_]+$/', $module)) {
            exit();
        }
        $_items = $db->query("SHOW TABLE STATUS LIKE '%\_" . $module . "%'")->fetchAll();
        $num_table = count($_items);

        $array_table_module = [];
        $tpl->assign('choicesql_name', 'choicesql_' . $choice);
        $tpl->assign('choicesql_next', $array_choicesql[$choice]);

        $sql_list = [];
        if ($num_table > 0) {
            foreach ($_items as $item) {
                $sql_list[] = [
                    'sl' => ($choice_seltected == $item['name']),
                    'key' => $item['name'],
                    'val' => $item['name']
                ];
            }
        }
        $tpl->assign('SQL_LIST', $sql_list);
        $contents = $tpl->fetch('fields_choicesql.tpl');
    } elseif ($choice == 'column') {
        $table = $nv_Request->get_string('table', 'post', '');
        if (!preg_match('/^[a-z0-9\_]+$/', $table)) {
            exit();
        }

        $_items = $db->columns_array($table);
        $num_table = count($_items);

        $array_table_module = [];
        $tpl->assign('choicesql_name', 'choicesql_' . $choice);
        $tpl->assign('choicesql_next', $array_choicesql[$choice]);
        $choice_seltected = explode('|', $choice_seltected);
        
        $sql_list = [];
        if ($num_table > 0) {
            foreach ($_items as $item) {
                $sql_list[] = [
                    'sl_key' => (!empty($choice_seltected[0]) and $choice_seltected[0] == $item['field']),
                    'sl_val' => (!empty($choice_seltected[1]) and $choice_seltected[1] == $item['field']),
                    'sl_order' => (!empty($choice_seltected[2]) and $choice_seltected[2] == $item['field']),
                    'key' => $item['field'],
                    'val' => $item['field']
                ];
            }
        }
        $tpl->assign('SQL_LIST', $sql_list);

        $sort_list = [];
        foreach ($array_sqlchoice_order as $sort_key => $sort_name) {
            $sort_list[] = [
                'key' => $sort_key,
                'title' => $sort_name,
                'selected' => (!empty($choice_seltected[3]) and $choice_seltected[3] == $sort_key)
            ];
        }
        $tpl->assign('SORT_LIST', $sort_list);
        $contents = $tpl->fetch('fields_column.tpl');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

//Add, Edit
$text_fields = $number_fields = $date_fields = $choice_fields = $file_fields = $choice_type_sql = $choice_type_text = 0;
$error = '';
$error_input = '';
$field_choices = [];
if ($nv_Request->isset_request('save', 'post')) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    
    if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_session')
        ]);
    }
    
    $validatefield = [
        'pattern' => '/[^a-zA-Z0-9\_]/',
        'replacement' => ''
    ];
    $validatefieldCss = [
        'pattern' => '/[^a-zA-Z0-9\_\-]/',
        'replacement' => ''
    ];
    $preg_replace = [
        'pattern' => '/[^a-zA-Z0-9\_]/',
        'replacement' => ''
    ];

    $dataform = [];
    $dataform['sql_choices'] = '';
    $dataform['limited_values'] = '';

    $dataform['fid'] = $nv_Request->get_int('fid', 'post', 0);
    $dataform['system'] = $nv_Request->get_int('system', 'post', 0);

    $dataform['title'] = $nv_Request->get_title('title', 'post', '');
    $dataform['description'] = $nv_Request->get_title('description', 'post', '');

    $dataform['for_admin'] = (int) $nv_Request->get_bool('for_admin', 'post', false);
    if ($dataform['for_admin']) {
        $dataform['required'] = $dataform['show_register'] = $dataform['user_editable'] = $dataform['show_profile'] = 0;
    } else {
        $dataform['required'] = (int) $nv_Request->get_bool('required', 'post', false);
        $dataform['show_register'] = ($dataform['required']) ? 1 : (int) $nv_Request->get_bool('show_register', 'post', false);
        $dataform['user_editable'] = (int) $nv_Request->get_bool('user_editable', 'post', false);
        $dataform['show_profile'] = (int) $nv_Request->get_bool('show_profile', 'post', false);
    }

    $dataform['class'] = nv_substr($nv_Request->get_title('class', 'post', '', 0, $validatefieldCss), 0, 50);

    $dataform['field_type'] = nv_substr($nv_Request->get_title('field_type', 'post', '', 0, $preg_replace), 0, 50);

    $save = 0;
    $language = [];
    if ($dataform['fid']) {
        $dataform_old = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $dataform['fid'])->fetch();
        if (empty($dataform_old)) {
            throw new \NukeViet\Http\HttpException('Data error!!!', 500);
        }
        $dataform['field_type'] = $dataform_old['field_type'];
        if (!empty($dataform_old['language'])) {
            $language = unserialize($dataform_old['language']);
        }
        $dataform['field'] = $dataform['fieldid'] = $dataform_old['field'];
    } else {
        $dataform['field'] = $dataform['fieldid'] = nv_strtolower(nv_substr($nv_Request->get_title('field', 'post', '', 0, $validatefield), 0, 50));

        require_once NV_ROOTDIR . '/includes/field_not_allow.php';

        if (in_array($dataform['field'], $field_not_allow, true)) {
            $error = $nv_Lang->getModule('field_error_not_allow');
            $error_input = 'field';
        } elseif (empty($dataform['field'])) {
            $error = $nv_Lang->getModule('field_error_empty');
            $error_input = 'field';
        } else {
            // Kiểm tra trùng trường dữ liệu
            $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE field= :field');
            $stmt->bindParam(':field', $dataform['field'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $error = $nv_Lang->getModule('field_error');
                $error_input = 'field';
            }
        }
    }

    $language[NV_LANG_DATA] = [
        $dataform['title'],
        $dataform['description']
    ];
    if ($dataform['field_type'] == 'textbox' or $dataform['field_type'] == 'textarea' or $dataform['field_type'] == 'editor') {
        $text_fields = 1;
        $dataform['match_type'] = nv_substr($nv_Request->get_title('match_type', 'post', '', 0, $preg_replace), 0, 50);
        $dataform['match_regex'] = ($dataform['match_type'] == 'regex') ? $nv_Request->get_string('match_regex', 'post', '', false) : '';
        $dataform['func_callback'] = ($dataform['match_type'] == 'callback') ? $nv_Request->get_string('match_callback', 'post', '', false) : '';
        if ($dataform['func_callback'] != '' and !function_exists($dataform['func_callback'])) {
            $dataform['func_callback'] = '';
        }

        if ($dataform['field_type'] == 'editor') {
            $dataform['editor_width'] = $nv_Request->get_string('editor_width', 'post', '100%', 0);
            $dataform['editor_height'] = $nv_Request->get_string('editor_height', 'post', '300px', 0);
            if (!preg_match('/^([0-9]+)(\%|px)+$/', $dataform['editor_width'])) {
                $dataform['editor_width'] = '100%';
            }
            if (!preg_match('/^([0-9]+)(\%|px)+$/', $dataform['editor_height'])) {
                $dataform['editor_height'] = '300px';
            }
            $dataform['class'] = $dataform['editor_width'] . '@' . $dataform['editor_height'];
        }
        $dataform['min_length'] = $nv_Request->get_int('min_length', 'post', 0);
        if (isset($array_systemfield_cfg[$dataform['field']]) and $dataform['min_length'] < $array_systemfield_cfg[$dataform['field']][0]) {
            $dataform['min_length'] = $array_systemfield_cfg[$dataform['field']][0];
        } elseif ($dataform['min_length'] < 0) {
            $dataform['min_length'] = 0;
        }
        $dataform['max_length'] = $nv_Request->get_int('max_length', 'post', 255);
        if (isset($array_systemfield_cfg[$dataform['field']]) and $dataform['max_length'] > $array_systemfield_cfg[$dataform['field']][1]) {
            $dataform['max_length'] = $array_systemfield_cfg[$dataform['field']][1];
        } elseif ($dataform['max_length'] < 0) {
            $dataform['max_length'] = 255;
        }

        $default_value = [];
        if (!empty($dataform_old['default_value'])) {
            $default_value = json_decode($dataform_old['default_value'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $default_value = [];
            }
        }
        $default_value[NV_LANG_DATA] = $nv_Request->get_title('default_value', 'post', '');
        $dataform['default_value'] = json_encode($default_value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($dataform['min_length'] >= $dataform['max_length']) {
            $error = $nv_Lang->getModule('field_number_error');
        } else {
            $dataform['field_choices'] = '';
        }
    } elseif ($dataform['field_type'] == 'number') {
        $number_fields = 1;
        $dataform['number_type'] = $nv_Request->get_int('number_type', 'post', 1);
        if ($dataform['number_type'] == 1) {
            $dataform['default_value_number'] = $nv_Request->get_int('default_value_number', 'post', 0);
        } else {
            $dataform['default_value_number'] = $nv_Request->get_float('default_value_number', 'post', 0);
        }
        $dataform['min_length'] = $nv_Request->get_int('min_number_length', 'post', 0);
        $dataform['max_length'] = $nv_Request->get_int('max_number_length', 'post', 0);
        $dataform['match_type'] = 'none';
        $dataform['match_regex'] = $dataform['func_callback'] = '';

        $field_choices['number_type'] = $dataform['number_type'];
        $dataform['default_value'] = $dataform['default_value_number'];

        if ($dataform['min_length'] >= $dataform['max_length']) {
            $error = $nv_Lang->getModule('field_number_error');
        } else {
            $dataform['field_choices'] = serialize(['number_type' => $dataform['number_type']]);
        }
    } elseif ($dataform['field_type'] == 'date') {
        $date_fields = 1;

        $dataform['min_length'] = nv_d2u_post($nv_Request->get_string('min_date', 'post'));
        $dataform['max_length'] = nv_d2u_post($nv_Request->get_string('max_date', 'post'));
        $dataform['current_date'] = $nv_Request->get_int('current_date', 'post', 0);
        $dataform['default_value'] = 0;
        if (empty($dataform['current_date'])) {
            $dataform['default_value'] = nv_d2u_post($nv_Request->get_string('default_date', 'post'));
        }

        $dataform['match_type'] = 'none';
        $dataform['match_regex'] = $dataform['func_callback'] = '';
        $field_choices['current_date'] = $dataform['current_date'];
        if ($dataform['min_length'] >= $dataform['max_length'] and $dataform['min_length'] != 0) {
            $error = $nv_Lang->getModule('field_date_error');
        } else {
            $dataform['field_choices'] = serialize(['current_date' => $dataform['current_date']]);
        }
    } elseif ($dataform['field_type'] == 'file') {
        $file_fields = 1;
        $dataform['field_choices'] = $dataform['match_regex'] = $dataform['func_callback'] = $dataform['default_value'] = '';
        $dataform['match_type'] = 'none';
        $dataform['min_length'] = $dataform['max_length'] = 0;
        $datafile = [
            'filetype' => $nv_Request->get_typed_array('filetype', 'post', 'string', []),
            'mime' => $nv_Request->get_typed_array('mime', 'post', 'string', []),
            'file_max_size' => $nv_Request->get_int('file_max_size', 'post', 0),
            'maxnum' => $nv_Request->get_int('maxnum', 'post', 0),
            'widthlimit' => $nv_Request->get_typed_array('widthlimit', 'post', 'int', []),
            'heightlimit' => $nv_Request->get_typed_array('heightlimit', 'post', 'int', [])
        ];
        if (empty($datafile['filetype'])) {
            $error = $nv_Lang->getModule('field_file_exts_error');
        } else {
            if (!empty($datafile['filetype']) and in_array('images', $datafile['filetype'], true)) {
                if ($datafile['widthlimit']['equal'] > 0) {
                    $datafile['widthlimit']['greater'] = 0;
                    $datafile['widthlimit']['less'] = 0;
                } else {
                    $datafile['widthlimit']['equal'] = 0;
                    if ($datafile['widthlimit']['greater'] != 0 and $datafile['widthlimit']['less'] != 0) {
                        if ($datafile['widthlimit']['greater'] == $datafile['widthlimit']['less']) {
                            $datafile['widthlimit']['equal'] = $datafile['widthlimit']['greater'];
                            $datafile['widthlimit']['greater'] = 0;
                            $datafile['widthlimit']['less'] = 0;
                        } elseif ($datafile['widthlimit']['greater'] > $datafile['widthlimit']['less']) {
                            $datafile['widthlimit']['greater'] = 0;
                        }
                    }
                }
                if ($datafile['heightlimit']['equal'] > 0) {
                    $datafile['heightlimit']['greater'] = 0;
                    $datafile['heightlimit']['less'] = 0;
                } else {
                    $datafile['heightlimit']['equal'] = 0;
                    if ($datafile['heightlimit']['greater'] != 0 and $datafile['heightlimit']['less'] != 0) {
                        if ($datafile['heightlimit']['greater'] == $datafile['heightlimit']['less']) {
                            $datafile['heightlimit']['equal'] = $datafile['heightlimit']['greater'];
                            $datafile['heightlimit']['greater'] = 0;
                            $datafile['heightlimit']['less'] = 0;
                        } elseif ($datafile['heightlimit']['greater'] > $datafile['heightlimit']['less']) {
                            $datafile['heightlimit']['greater'] = 0;
                        }
                    }
                }
            } else {
                $datafile['widthlimit'] = [
                    'equal' => 0,
                    'greater' => 0,
                    'less' => 0
                ];
                $datafile['heightlimit'] = [
                    'equal' => 0,
                    'greater' => 0,
                    'less' => 0
                ];
            }

            $dataform['limited_values'] = json_encode($datafile);
        }
    } else {
        $dataform['choicetypes'] = $nv_Request->get_string('choicetypes', 'post', '');
        $dataform['match_type'] = 'none';
        $dataform['match_regex'] = $dataform['func_callback'] = '';
        $dataform['min_length'] = 0;
        $dataform['max_length'] = 255;
        $dataform['default_value'] = $nv_Request->get_int('default_value_choice', 'post', 0);

        if ($dataform['choicetypes'] == 'field_choicetypes_text') {
            if ($dataform['fid'] and $dataform['fieldid'] == 'gender') {
                $dataform['field_choices'] = serialize([
                    'N' => $global_array_genders['N']['title'],
                    'M' => $global_array_genders['M']['title'],
                    'F' => $global_array_genders['F']['title']
                ]);
            } else {
                $old_field_choices = !empty($dataform_old['field_choices']) ? unserialize($dataform_old['field_choices']) : [];
                $field_choice_value = $nv_Request->get_typed_array('field_choice', 'post', 'title', []);
                $field_choice_text = $nv_Request->get_typed_array('field_choice_text', 'post', 'title', []);
                if (!count($field_choice_value)) {
                    $error = $nv_Lang->getModule('field_choices_empty');
                } else {
                    $field_choices = [];
                    foreach ($field_choice_value as $k => $val) {
                        if (preg_match('/^[a-zA-Z0-9\_]+$/', $val)) {
                            $field_choices[$val] = (isset($old_field_choices[$val]) and is_array($old_field_choices[$val])) ? $old_field_choices[$val] : [];
                            $field_choices[$val][NV_LANG_DATA] = trim(strip_tags($field_choice_text[$k]));
                        }
                    }
                    if (empty($field_choices)) {
                        $error = $nv_Lang->getModule('field_choices_empty');
                    } else {
                        $dataform['field_choices'] = serialize($field_choices);
                    }
                }
            }
        } else {
            // Module data
            $choicesql_module = $nv_Request->get_string('choicesql_module', 'post', '');
            // Bảng dữ liệu
            $choicesql_table = $nv_Request->get_string('choicesql_table', 'post', '');
            // Cột làm key
            $choicesql_column_key = $nv_Request->get_string('choicesql_column_key', 'post', '');
            // Cột làm tên hiển thị
            $choicesql_column_val = $nv_Request->get_string('choicesql_column_val', 'post', '');
            // Cột sắp xếp
            $choicesql_column_order = $nv_Request->get_string('choicesql_column_order', 'post', '');
            // Kiểu sắp xếp
            $choicesql_sort_type = $nv_Request->get_string('choicesql_sort_type', 'post', '');
            if (!isset($choicesql_sort_type)) {
                $choicesql_sort_type = current(array_keys($array_sqlchoice_order));
            }

            if ($choicesql_module != '' and $choicesql_table != '' and $choicesql_column_key != '' and $choicesql_column_val != '') {
                $dataform['sql_choices'] = $choicesql_module . '|' . $choicesql_table . '|' . $choicesql_column_key . '|' . $choicesql_column_val . '|' . $choicesql_column_order . '|' . $choicesql_sort_type;
                $dataform['field_choices'] = '';
            } else {
                $error = $nv_Lang->getModule('field_sql_choices_empty');
            }
        }
    }
    if (empty($error)) {
        if (empty($dataform['fid'])) {
            $_columns_array = $db->columns_array(NV_MOD_TABLE);

            if ($dataform['max_length'] <= 4294967296 and !empty($dataform['field']) and !empty($dataform['title']) and !isset($_columns_array[$dataform['field']])) {
                $weight = $db->query('SELECT MAX(weight) FROM ' . NV_MOD_TABLE . '_field')->fetchColumn();
                $weight = (int) $weight + 1;

                $sql = 'INSERT INTO ' . NV_MOD_TABLE . "_field (
                    field, weight, field_type, field_choices, sql_choices, match_type,
                    match_regex, func_callback, min_length, max_length, limited_values,
                    for_admin, required, show_register, user_editable,
                    show_profile, class, language, default_value
                ) VALUES (
                    '" . $dataform['field'] . "', " . $weight . ", '" . $dataform['field_type'] . "', '" . $dataform['field_choices'] . "', " . $db->quote($dataform['sql_choices']) . ", '" . $dataform['match_type'] . "',
                    :match_regex, :func_callback,
                    " . $dataform['min_length'] . ', ' . $dataform['max_length'] . ', :limited_values,
                    ' . $dataform['for_admin'] . ', ' . $dataform['required'] . ', ' . $dataform['show_register'] . ", '" . $dataform['user_editable'] . "',
                    " . $dataform['show_profile'] . ", :class, '" . serialize($language) . "', :default_value
                )";

                $data_insert = [];
                $data_insert['limited_values'] = $dataform['limited_values'];
                $data_insert['match_regex'] = nv_unhtmlspecialchars($dataform['match_regex']);
                $data_insert['func_callback'] = nv_unhtmlspecialchars($dataform['func_callback']);
                $data_insert['class'] = $dataform['class'];
                $data_insert['default_value'] = $dataform['default_value'];
                $dataform['fid'] = $db->insert_id($sql, 'fid', $data_insert);
                if ($dataform['fid']) {
                    $type_date = '';
                    if ($dataform['field_type'] == 'number' or $dataform['field_type'] == 'date') {
                        $type_date = "DOUBLE NOT NULL DEFAULT '" . $dataform['default_value'] . "'";
                    } elseif ($dataform['field_type'] == 'file') {
                        $type_date = 'TEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 255) {
                        $type_date = 'VARCHAR( ' . $dataform['max_length'] . " ) NOT NULL DEFAULT ''";
                    } elseif ($dataform['max_length'] <= 65536) {
                        //2^16 TEXT
                        $type_date = 'TEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 16777216) {
                        //2^24 MEDIUMTEXT
                        $type_date = 'MEDIUMTEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 4294967296) {
                        //2^32 LONGTEXT
                        $type_date = 'LONGTEXT NOT NULL';
                    }
                    $save = $db->exec('ALTER TABLE ' . NV_MOD_TABLE . '_info ADD ' . $dataform['field'] . ' ' . $type_date . ' COMMENT ' . $db->quote($dataform['title']));
                }
            }
        } elseif ($dataform['max_length'] <= 4294967296) {
            $query = 'UPDATE ' . NV_MOD_TABLE . '_field SET';
            if ($text_fields == 1) {
                $query .= " match_type='" . $dataform['match_type'] . "',
                match_regex=:match_regex, func_callback=:func_callback, ";
            }
            $query .= ' max_length=' . $dataform['max_length'] . ', min_length=' . $dataform['min_length'] . ',
                limited_values = :limited_values,
                for_admin = ' . $dataform['for_admin'] . ',
                required = ' . $dataform['required'] . ",
                field_choices='" . $dataform['field_choices'] . "',
                sql_choices = '" . $dataform['sql_choices'] . "',
                show_register = " . $dataform['show_register'] . ',
                user_editable = ' . $dataform['user_editable'] . ',
                show_profile = ' . $dataform['show_profile'] . ",
                class = :class,
                language='" . serialize($language) . "',
                default_value= :default_value
                WHERE fid = " . $dataform['fid'];

            $stmt = $db->prepare($query);
            if ($text_fields == 1) {
                $dataform['match_regex'] = nv_unhtmlspecialchars($dataform['match_regex']);
                $dataform['func_callback'] = nv_unhtmlspecialchars($dataform['func_callback']);
                $stmt->bindParam(':match_regex', $dataform['match_regex'], PDO::PARAM_STR);
                $stmt->bindParam(':func_callback', $dataform['func_callback'], PDO::PARAM_STR);
            }
            $stmt->bindParam(':limited_values', $dataform['limited_values'], PDO::PARAM_STR);
            $stmt->bindParam(':class', $dataform['class'], PDO::PARAM_STR);
            $stmt->bindParam(':default_value', $dataform['default_value'], PDO::PARAM_STR, strlen($dataform['default_value']));
            $save = $stmt->execute();

            if (empty($dataform['system'])) {
                if ($save and $dataform['max_length'] != $dataform_old['max_length']) {
                    $type_date = '';
                    if ($dataform['field_type'] == 'number' or $dataform['field_type'] == 'date') {
                        $type_date = "DOUBLE NOT NULL DEFAULT '" . $dataform['default_value'] . "'";
                    } elseif ($dataform['field_type'] == 'file') {
                        $type_date = 'TEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 255) {
                        $type_date = 'VARCHAR( ' . $dataform['max_length'] . " ) NOT NULL DEFAULT ''";
                    } elseif ($dataform['max_length'] <= 65536) {
                        //2^16 TEXT
                        $type_date = 'TEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 16777216) {
                        //2^24 MEDIUMTEXT
                        $type_date = 'MEDIUMTEXT NOT NULL';
                    } elseif ($dataform['max_length'] <= 4294967296) {
                        //2^32 LONGTEXT
                        $type_date = 'LONGTEXT NOT NULL';
                    }
                    try {
                        $db->query('ALTER TABLE ' . NV_MOD_TABLE . '_info CHANGE ' . $dataform_old['field'] . ' ' . $dataform_old['field'] . ' ' . $type_date . ' COMMENT ' . $db->quote($dataform['title']));
                        $save = true;
                    } catch (Throwable $e) {
                        $save = false;
                        trigger_error(print_r($e, true));
                    }
                }
            }
        }
        if ($save) {
            $log_action = $dataform['fid'] ? 'Edit' : 'Add';
            nv_insert_logs(NV_LANG_DATA, $module_name, $log_action . ' field', 'field: ' . $dataform['field'], $admin_info['userid']);
            
            $redirect_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass();
            nv_jsonOutput([
                'status' => 'success',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite($redirect_url, true)
            ]);
        } else {
            // Trường hợp không lưu được (có lỗi database hoặc validation)
            nv_jsonOutput([
                'status' => 'error',
                'mess' => !empty($error) ? $error : $nv_Lang->getGlobal('error_save')
            ]);
        }
    } else {
        // Trả về lỗi dạng JSON cho AJAX request
        $json_data = [
            'status' => 'error',
            'mess' => $error
        ];
        if (!empty($error_input)) {
            $json_data['input'] = $error_input;
        }
        nv_jsonOutput($json_data);
    }
}

// Xóa trường
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $fid = $nv_Request->get_int('fid', 'post', 0);
    $checkss = $nv_Request->get_title('checkss', 'post', '');

    if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error!'
        ]);
    }

    [$fid, $field, $weight, $system] = $db->query('SELECT fid, field, weight, is_system FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid)->fetch(3);

    if ($fid and !empty($field) and empty($system)) {
        $query1 = 'DELETE FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid;
        $query2 = 'ALTER TABLE ' . NV_MOD_TABLE . '_info DROP ' . $field;
        if ($db->query($query1) and $db->query($query2)) {
            $query = 'SELECT fid FROM ' . NV_MOD_TABLE . '_field WHERE weight > ' . $weight . ' ORDER BY weight ASC';
            $result = $db->query($query);
            while ($row = $result->fetch()) {
                $db->query('UPDATE ' . NV_MOD_TABLE . '_field SET weight=' . $weight . ' WHERE fid=' . $row['fid']);
                ++$weight;
            }
            
            // Ghi log
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete field', 'field: ' . $field, $admin_info['userid']);
            
            nv_jsonOutput([
                'status' => 'success',
                'mess' => $nv_Lang->getGlobal('delete_success')
            ]);
        }
    }
    
    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_delete')
    ]);
}

$array_field_type = [
    'number' => $nv_Lang->getModule('field_type_number'),
    'date' => $nv_Lang->getModule('field_type_date'),
    'textbox' => $nv_Lang->getModule('field_type_textbox'),
    'textarea' => $nv_Lang->getModule('field_type_textarea'),
    'editor' => $nv_Lang->getModule('field_type_editor'),
    'select' => $nv_Lang->getModule('field_type_select'),
    'radio' => $nv_Lang->getModule('field_type_radio'),
    'checkbox' => $nv_Lang->getModule('field_type_checkbox'),
    'multiselect' => $nv_Lang->getModule('field_type_multiselect'),
    'file' => $nv_Lang->getModule('field_type_file')
];

$array_choice_type = [
    'field_choicetypes_sql' => $nv_Lang->getModule('field_choicetypes_sql'),
    'field_choicetypes_text' => $nv_Lang->getModule('field_choicetypes_text')
];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

// Danh sách các trường dữ liệu tùy biến
if ($nv_Request->isset_request('qlist', 'get')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC';
    $_rows = $db->query($sql)->fetchAll();
    $num = count($_rows);

    // Các trường hệ thống luôn ở trên đầu, do đó bắt đầu weight từ khi có trường tùy chỉnh
    $fieldsys_offset = 0;
    $data_rows = [];

    if ($num) {
        foreach ($_rows as $row) {
            $language = unserialize($row['language']);

            $weights = [];
            for ($i = ($row['is_system'] == 1 ? $row['weight'] : $fieldsys_offset + 1); $i <= ($row['is_system'] == 1 ? $row['weight'] : $num); ++$i) {
                $weights[] = [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight']
                ];
            }

            $data_rows[] = [
                'fid' => $row['fid'],
                'field' => $row['field'],
                'field_lang' => (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : '',
                'field_type' => $array_field_type[$row['field_type']],
                'for_admin' => (bool) $row['for_admin'],
                'required' => (bool) $row['required'],
                'show_register' => (bool) $row['show_register'],
                'show_profile' => (bool) $row['show_profile'],
                'is_system' => (bool) $row['is_system'],
                'weights' => $weights,
                'disabled_weight' => $row['is_system'] == 1
            ];

            if ($row['is_system'] == 1) {
                ++$fieldsys_offset;
            }
        }
    }

    $tpl->assign('DATA_ROWS', $data_rows);
    $contents = $tpl->fetch('fields_data.tpl');
} else {
    $fid = $nv_Request->get_int('fid', 'get,post', 0);
    if (!isset($dataform)) {
        if ($fid) {
            $dataform = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid)->fetch();
            
            $dataform['fid'] = $fid;
            if ($dataform['field_type'] == 'editor') {
                $array_tmp = explode('@', $dataform['class']);
                $dataform['editor_width'] = $array_tmp[0];
                $dataform['editor_height'] = $array_tmp[1];
                $dataform['class'] = '';
            }
            if (!empty($dataform['field_choices'])) {
                $field_choices = unserialize($dataform['field_choices']);
            }
            if (!empty($dataform['language'])) {
                $language = unserialize($dataform['language']);
                if (isset($language[NV_LANG_DATA])) {
                    $dataform['title'] = $language[NV_LANG_DATA][0];
                    $dataform['description'] = $language[NV_LANG_DATA][1];
                }
            }
            $dataform['fieldid'] = $dataform['field'];
            $dataform['default_value_number'] = $dataform['default_value'];
            $dataform['system'] = $dataform['is_system'];
        } else {
            $dataform = [];
            $dataform['fid'] = 0;
            $dataform['for_admin'] = 0;
            $dataform['required'] = 0;
            $dataform['show_profile'] = 1;
            $dataform['user_editable'] = 1;
            $dataform['show_register'] = 1;
            $dataform['field_type'] = 'textbox';
            $dataform['match_type'] = 'none';
            $dataform['min_length'] = 0;
            $dataform['max_length'] = 255;
            $dataform['limited_values'] = '';
            $dataform['match_regex'] = $dataform['func_callback'] = '';
            $dataform['editor_width'] = '100%';
            $dataform['editor_height'] = '100px';
            $dataform['fieldid'] = '';
            $dataform['field'] = '';
            $dataform['title'] = '';
            $dataform['description'] = '';
            $dataform['class'] = 'input';
            $dataform['default_value'] = '';
            $dataform['default_value_number'] = 0;
            $dataform['min_number'] = 0;
            $dataform['max_number'] = 1000;
            $dataform['number_type'] = 1;
            $dataform['current_date'] = 0;
            $dataform['default_date'] = '';
            $dataform['min_date'] = '';
            $dataform['max_date'] = '';
            $dataform['system'] = 0;
        }
    }

    if (!isset($datafile)) {
        if (!empty($dataform['limited_values'])) {
            $datafile = json_decode($dataform['limited_values'], true);
        } else {
            $datafile = [
                'filetype' => [],
                'mime' => [],
                'file_max_size' => 0,
                'maxnum' => 1,
                'widthlimit' => [
                    'equal' => 0,
                    'greater' => 0,
                    'less' => 0
                ],
                'heightlimit' => [
                    'equal' => 0,
                    'greater' => 0,
                    'less' => 0
                ]
            ];
        }
    }
    empty($datafile['widthlimit']['equal']) && $datafile['widthlimit']['equal'] = '';
    empty($datafile['widthlimit']['greater']) && $datafile['widthlimit']['greater'] = '';
    empty($datafile['widthlimit']['less']) && $datafile['widthlimit']['less'] = '';
    empty($datafile['heightlimit']['equal']) && $datafile['heightlimit']['equal'] = '';
    empty($datafile['heightlimit']['greater']) && $datafile['heightlimit']['greater'] = '';
    empty($datafile['heightlimit']['less']) && $datafile['heightlimit']['less'] = '';

    if ($dataform['field_type'] == 'textbox' or $dataform['field_type'] == 'textarea' or $dataform['field_type'] == 'editor') {
        $text_fields = 1;
        $default_value = json_decode($dataform['default_value'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $dataform['default_value'] = !empty($default_value[NV_LANG_DATA]) ? $default_value[NV_LANG_DATA] : '';
        }
    } elseif ($dataform['field_type'] == 'number') {
        $number_fields = 1;
        $dataform['min_number'] = $dataform['min_length'];
        $dataform['max_number'] = $dataform['max_length'];
        $dataform['number_type'] = !empty($field_choices['number_type']) ? $field_choices['number_type'] : 1;
    } elseif ($dataform['field_type'] == 'date') {
        $date_fields = 1;
        $dataform['current_date'] = !empty($field_choices['current_date']) ? $field_choices['current_date'] : 0;
        $dataform['default_date'] = nv_u2d_post($dataform['default_value']);
        $dataform['min_date'] = nv_u2d_post($dataform['min_length']);
        $dataform['max_date'] = nv_u2d_post($dataform['max_length']);
    } elseif ($dataform['field_type'] == 'file') {
        $file_fields = 1;
    } else {
        $choice_fields = 1;
        if (!empty($dataform['sql_choices'])) {
            $choice_type_sql = 1;
            $sql_data_choice = explode('|', $dataform['sql_choices']);
            $tpl->assign('SQL_DATA_CHOICE', $sql_data_choice);
            $tpl->assign('LOAD_SQLCHOICE', true);
        } else {
            $choice_type_text = 1;
        }
    }
    
    // Chuẩn bị danh sách field choices
    $field_choices_list = [];
    if ($fid == 0 or $text_fields == 0) {
        $number = 1;
        $disable_edit_choose = ($dataform['fieldid'] == 'gender' and !empty($dataform['fid']));

        if (!empty($field_choices)) {
            foreach ($field_choices as $key => $value) {
                $field_choices_list[] = [
                    'checked' => ($number == $dataform['default_value']),
                    'number' => $number++,
                    'key' => $key,
                    'value' => $disable_edit_choose ? $global_array_genders[$key]['title'] : get_value_by_lang2($key, $value),
                    'readonly' => $disable_edit_choose
                ];
            }
        }
        if (!$disable_edit_choose) {
            $field_choices_list[] = [
                'number' => $number,
                'key' => '',
                'value' => '',
                'readonly' => false,
                'checked' => false
            ];
        }
        $tpl->assign('FIELD_CHOICES_NUMBER', $number);
        $tpl->assign('ADD_FIELD_CHOICE', !$disable_edit_choose);
    }
    $tpl->assign('FIELD_CHOICES_LIST', $field_choices_list);
    
    // Xác định các section hiển thị
    $dataform['display_textfields'] = (bool) $text_fields;
    $dataform['display_numberfields'] = (bool) $number_fields;
    $dataform['display_datefields'] = (bool) $date_fields;
    $dataform['display_choicetypes'] = (bool) $choice_fields;
    $dataform['display_choiceitems'] = (bool) $choice_type_text;
    $dataform['display_choicesql'] = (bool) $choice_type_sql;
    $dataform['display_filefields'] = (bool) $file_fields;

    $dataform['editordisabled'] = $dataform['field_type'] != 'editor';
    $dataform['classdisabled'] = $dataform['field_type'] == 'editor';

    // Xác định trạng thái for_admin ẩn các field khác
    $is_hidden = (bool) $dataform['for_admin'];
    $tpl->assign('IS_HIDDEN', $is_hidden);

    // Captionform
    $captionform = ($fid) ? $nv_Lang->getModule('captionform_edit') . ': ' . $dataform['fieldid'] : $nv_Lang->getModule('captionform_add');
    $tpl->assign('CAPTIONFORM', $captionform);
    $tpl->assign('DATAFORM', $dataform);
    
    // Danh sách field types
    $field_type_list = [];
    foreach ($array_field_type as $key => $value) {
        $field_type_list[] = [
            'key' => $key,
            'value' => $value,
            'checked' => ($dataform['field_type'] == $key)
        ];
    }
    $tpl->assign('FIELD_TYPE_LIST', $field_type_list);
    $tpl->assign('SHOW_FIELD_TYPE_SELECT', empty($fid));
    
    // Danh sách choice types
    if (empty($fid)) {
        $choice_type_list = [];
        foreach ($array_choice_type as $key => $value) {
            $choice_type_list[] = [
                'key' => $key,
                'value' => $value
            ];
        }
        $tpl->assign('CHOICE_TYPE_LIST', $choice_type_list);
        $tpl->assign('SHOW_CHOICE_TYPES_SELECT', true);
    } else {
        $tpl->assign('FIELD_TYPE_TEXT', $array_field_type[$dataform['field_type']]);
        if ((!empty($dataform['sql_choices']))) {
            $tpl->assign('CHOICETYPES_HIDDEN_VALUE', 'field_choicetypes_sql');
            $tpl->assign('FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_sql']);
        } else {
            $tpl->assign('CHOICETYPES_HIDDEN_VALUE', 'field_choicetypes_text');
            $tpl->assign('FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_text']);
        }
        $tpl->assign('SHOW_CHOICE_TYPES_SELECT', false);
    }
    
    // Danh sách match types
    $array_match_type = [];
    $array_match_type['none'] = $nv_Lang->getModule('field_match_type_none');
    if ($dataform['field_type'] != 'editor' and $dataform['field_type'] != 'textarea') {
        $array_match_type['alphanumeric'] = $nv_Lang->getModule('field_match_type_alphanumeric');
        $array_match_type['unicodename'] = $nv_Lang->getModule('field_match_type_unicodename');
        $array_match_type['email'] = $nv_Lang->getGlobal('email');
        $array_match_type['url'] = $nv_Lang->getModule('field_match_type_url');
    }
    $array_match_type['regex'] = $nv_Lang->getModule('field_match_type_regex');
    $array_match_type['callback'] = $nv_Lang->getModule('field_match_type_callback');
    
    $match_type_list = [];
    foreach ($array_match_type as $key => $value) {
        $match_type_list[] = [
            'key' => $key,
            'value' => $value,
            'match_value' => ($key == 'regex') ? $dataform['match_regex'] : $dataform['func_callback'],
            'checked' => ($dataform['match_type'] == $key),
            'has_input' => ($key == 'regex' or $key == 'callback')
        ];
    }
    $tpl->assign('MATCH_TYPE_LIST', $match_type_list);

    // File types và MIME types
    $tpl->assign('DATAFILE', $datafile);
    $ini = array_intersect_key(nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true), array_flip($global_config['file_allowed_ext']));
    $filetype_list = [];
    foreach ($ini as $filetype => $mimes) {
        $mime_list = [];
        foreach ($mimes as $key => $val) {
            $mime_list[] = [
                'key' => $key,
                'checked' => (!empty($datafile['mime']) and in_array($key, $datafile['mime'], true))
            ];
        }
        $filetype_list[] = [
            'key' => $filetype,
            'checked' => (!empty($datafile['filetype']) and in_array($filetype, $datafile['filetype'], true)),
            'mimes' => $mime_list
        ];
    }
    $tpl->assign('FILETYPE_LIST', $filetype_list);

    // File sizes
    $p_size = $global_config['nv_max_size'] / 100;
    $size_list = [];
    for ($index = 100; $index > 0; --$index) {
        $size = floor($index * $p_size);
        $size_list[] = [
            'key' => $size,
            'name' => nv_convertfromBytes($size),
            'sel' => (!empty($datafile['file_max_size']) and $size == $datafile['file_max_size'])
        ];
    }
    $tpl->assign('SIZE_LIST', $size_list);

    // Max number of files
    $maxnum_list = [];
    for ($i = 1; $i <= 20; ++$i) {
        $maxnum_list[] = [
            'key' => $i,
            'sel' => (!empty($datafile['maxnum']) and $i == $datafile['maxnum'])
        ];
    }
    $tpl->assign('MAXNUM_LIST', $maxnum_list);

    if (!empty($error)) {
        $tpl->assign('ERROR', $error);
    }

    $contents = $tpl->fetch('fields.tpl');

    $page_title = $nv_Lang->getModule('fields');
    $contents = nv_admin_theme($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
