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

    $query = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid . ' AND is_system=0';
    $numrows = $db->query($query)->fetchColumn();

    $weightsystem = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_field WHERE is_system=1')->fetchColumn();
    if ($numrows != 1 or $new_vid <= $weightsystem) {
        exit('NO');
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
    exit('OK');
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

    $xtpl = new XTemplate('fields.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);

    if ($choice == 'module') {
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);
        $xtpl->parse('choicesql.loop');
        foreach ($site_mods as $module) {
            $_temp_choice['sl'] = ($choice_seltected == $module['module_data']) ? ' selected="selected"' : '';
            $_temp_choice['key'] = $module['module_data'];
            $_temp_choice['val'] = $module['custom_title'];
            $xtpl->assign('SQL', $_temp_choice);
            $xtpl->parse('choicesql.loop');
            unset($_temp_choice);
        }
        $xtpl->parse('choicesql');
        $contents = $xtpl->text('choicesql');
    } elseif ($choice == 'table') {
        // Đây là trên bảng dữ liệu không phải tên module do đó chỉ chấp nhận ký tự thường, số và dấu gạch dưới
        $module = $nv_Request->get_string('module', 'post', '');
        if (!preg_match('/^[a-z0-9\_]+$/', $module)) {
            exit();
        }
        $_items = $db->query("SHOW TABLE STATUS LIKE '%\_" . $module . "%'")->fetchAll();
        $num_table = count($_items);

        $array_table_module = [];
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);

        if ($num_table > 0) {
            $xtpl->parse('choicesql.loop');
            foreach ($_items as $item) {
                $_temp_choice['sl'] = ($choice_seltected == $item['name']) ? ' selected="selected"' : '';
                $_temp_choice['key'] = $item['name'];
                $_temp_choice['val'] = $item['name'];
                $xtpl->assign('SQL', $_temp_choice);
                $xtpl->parse('choicesql.loop');
                unset($_temp_choice);
            }
        }
        $xtpl->parse('choicesql');
        $contents = $xtpl->text('choicesql');
    } elseif ($choice == 'column') {
        $table = $nv_Request->get_string('table', 'post', '');
        if (!preg_match('/^[a-z0-9\_]+$/', $table)) {
            exit();
        }

        $_items = $db->columns_array($table);
        $num_table = count($_items);

        $array_table_module = [];
        $xtpl->assign('choicesql_name', 'choicesql_' . $choice);
        $xtpl->assign('choicesql_next', $array_choicesql[$choice]);
        $choice_seltected = explode('|', $choice_seltected);
        if ($num_table > 0) {
            foreach ($_items as $item) {
                $_temp_choice['sl_key'] = (!empty($choice_seltected[0]) and $choice_seltected[0] == $item['field']) ? ' selected="selected"' : '';
                $_temp_choice['sl_val'] = (!empty($choice_seltected[1]) and $choice_seltected[1] == $item['field']) ? ' selected="selected"' : '';
                $_temp_choice['sl_order'] = (!empty($choice_seltected[2]) and $choice_seltected[2] == $item['field']) ? ' selected="selected"' : '';
                $_temp_choice['key'] = $item['field'];
                $_temp_choice['val'] = $item['field'];
                $xtpl->assign('SQL', $_temp_choice);
                $xtpl->parse('column.loop1');
                $xtpl->parse('column.loop2');
                $xtpl->parse('column.loop3');
                unset($_temp_choice);
            }
        }

        foreach ($array_sqlchoice_order as $sort_key => $sort_name) {
            $xtpl->assign('SORT', [
                'key' => $sort_key,
                'title' => $sort_name,
                'selected' => (!empty($choice_seltected[3]) and $choice_seltected[3] == $sort_key) ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('column.sort');
        }

        $xtpl->parse('column');
        $contents = $xtpl->text('column');
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

//Add, Edit
$text_fields = $number_fields = $date_fields = $choice_fields = $file_fields = $choice_type_sql = $choice_type_text = 0;
$error = '';
$field_choices = [];
if ($nv_Request->isset_request('save', 'post')) {
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
            http_response_code(500);
            trigger_error('Data error!!!', 256);
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
        } elseif (empty($dataform['field'])) {
            $error = $nv_Lang->getModule('field_error_empty');
        } else {
            // Kiểm tra trùng trường dữ liệu
            $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE field= :field');
            $stmt->bindParam(':field', $dataform['field'], PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $error = $nv_Lang->getModule('field_error');
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
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
        }
    }
}

// Xóa trường
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    $fid = $nv_Request->get_int('fid', 'post', 0);

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
            exit('OK');
        }
    }
    exit('NO');
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

$xtpl = new XTemplate('fields.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('MODULE_NAME', $module_name);

// Fix tpl parse
$xtpl->assign('MATCH4', '{4}');
$xtpl->assign('MATCH2', '{2}');

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

    if ($num) {
        foreach ($_rows as $row) {
            $language = unserialize($row['language']);

            $xtpl->assign('ROW', [
                'fid' => $row['fid'],
                'field' => $row['field'],
                'field_lang' => (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : '',
                'field_type' => $array_field_type[$row['field_type']],
                'for_admin' => ($row['for_admin']) ? 'fa-check' : '',
                'required' => ($row['required']) ? 'fa-check' : '',
                'show_register' => ($row['show_register']) ? 'fa-check' : '',
                'show_profile' => ($row['show_profile']) ? 'fa-check' : ''
            ]);

            for ($i = ($row['is_system'] == 1 ? $row['weight'] : $fieldsys_offset + 1); $i <= ($row['is_system'] == 1 ? $row['weight'] : $num); ++$i) {
                $xtpl->assign('WEIGHT', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.data.loop.weight');
            }

            if ($row['is_system'] == 1) {
                $xtpl->assign('DISABLED_WEIGHT', 'disabled');
                ++$fieldsys_offset;
            } else {
                $xtpl->assign('DISABLED_WEIGHT', '');
                $xtpl->parse('main.data.loop.show_delete');
            }

            $xtpl->parse('main.data.loop');
        }

        $xtpl->parse('main.data');
    }
    $xtpl->parse('main');
    $contents = $xtpl->text('main');
} else {
    $fid = $nv_Request->get_int('fid', 'get,post', 0);
    if (!isset($dataform)) {
        if ($fid) {
            $dataform = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field WHERE fid=' . $fid)->fetch();

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
            $dataform['class'] = 'input';
            $dataform['default_value'] = '';
            $dataform['default_value_number'] = 0;
            $dataform['min_number'] = 0;
            $dataform['max_number'] = 1000;
            $dataform['number_type_1'] = ' checked="checked"';
            $dataform['current_date_0'] = ' checked="checked"';
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
        $dataform['number_type_1'] = ($field_choices['number_type'] == 1) ? ' checked="checked"' : '';
        $dataform['number_type_2'] = ($field_choices['number_type'] == 2) ? ' checked="checked"' : '';
    } elseif ($dataform['field_type'] == 'date') {
        $date_fields = 1;
        $dataform['current_date_1'] = ($field_choices['current_date'] == 1) ? ' checked="checked"' : '';
        $dataform['current_date_0'] = ($field_choices['current_date'] == 0) ? ' checked="checked"' : '';
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
            $xtpl->assign('SQL_DATA_CHOICE', $sql_data_choice);
            $xtpl->parse('main.nv_load_sqlchoice');
        } else {
            $choice_type_text = 1;
        }
    }
    if ($fid == 0 or $text_fields == 0) {
        $number = 1;
        $disable_edit_choose = ($dataform['fieldid'] == 'gender' and !empty($dataform['fid']));

        $xtpl->assign('FIELD_CHOICES_READONLY', $disable_edit_choose ? ' readonly="readonly"' : '');

        if (!empty($field_choices)) {
            foreach ($field_choices as $key => $value) {
                $xtpl->assign('FIELD_CHOICES', [
                    'checked' => ($number == $dataform['default_value']) ? ' checked="checked"' : '',
                    'number' => $number++,
                    'key' => $key,
                    'value' => $disable_edit_choose ? $global_array_genders[$key]['title'] : get_value_by_lang2($key, $value)
                ]);
                $xtpl->parse('main.load.loop_field_choice');
            }
        }
        if (!$disable_edit_choose) {
            $xtpl->assign('FIELD_CHOICES', [
                'number' => $number,
                'key' => '',
                'value' => ''
            ]);
            $xtpl->parse('main.load.loop_field_choice');
            $xtpl->parse('main.load.add_field_choice');
        }
        $xtpl->assign('FIELD_CHOICES_NUMBER', $number);
    }
    $dataform['display_textfields'] = ($text_fields) ? '' : 'style="display: none;"';
    $dataform['display_numberfields'] = ($number_fields) ? '' : 'style="display: none;"';
    $dataform['display_datefields'] = ($date_fields) ? '' : 'style="display: none;"';
    $dataform['display_choicetypes'] = ($choice_fields) ? '' : 'style="display: none;"';
    $dataform['display_choiceitems'] = ($choice_type_text) ? '' : 'style="display: none;"';
    $dataform['display_choicesql'] = ($choice_type_sql) ? '' : 'style="display: none;"';
    $dataform['display_filefields'] = ($file_fields) ? '' : 'style="display: none;"';

    $dataform['editordisabled'] = ($dataform['field_type'] != 'editor') ? ' style="display: none;"' : '';
    $dataform['classdisabled'] = ($dataform['field_type'] == 'editor') ? ' style="display: none;"' : '';

    $dataform['for_admin'] = $dataform['for_admin'] ? ' checked="checked"' : '';
    if ($dataform['for_admin']) {
        $dataform['for_admin'] = ' checked="checked"';
        $dataform['required'] = $dataform['show_register'] = $dataform['show_profile'] = $dataform['user_editable'] = ' disabled="disabled"';
        $xtpl->assign('IS_HIDDEN', 'hidden');
    } else {
        $dataform['for_admin'] = '';
        $dataform['required'] = ($dataform['required']) ? ' checked="checked"' : '';
        $dataform['show_register'] = ($dataform['show_register']) ? ' checked="checked"' : '';
        $dataform['show_profile'] = ($dataform['show_profile']) ? ' checked="checked"' : '';
        $dataform['user_editable'] = ($dataform['user_editable']) ? ' checked="checked"' : '';
    }
    $dataform['fielddisabled'] = ($fid) ? ' disabled="disabled"' : '';

    $xtpl->assign('CAPTIONFORM', ($fid) ? $nv_Lang->getModule('captionform_edit') . ': ' . $dataform['fieldid'] : $nv_Lang->getModule('captionform_add'));
    $xtpl->assign('DATAFORM', $dataform);
    if (empty($fid)) {
        $xtpl->parse('main.load.field');
        foreach ($array_field_type as $key => $value) {
            $xtpl->assign('FIELD_TYPE', [
                'key' => $key,
                'value' => $value,
                'checked' => ($dataform['field_type'] == $key) ? ' checked="checked"' : ''
            ]);
            $xtpl->parse('main.load.field_type.loop');
        }
        $xtpl->parse('main.load.field_type');

        foreach ($array_choice_type as $key => $value) {
            $xtpl->assign('CHOICE_TYPES', [
                'key' => $key,
                'value' => $value,
                'selected' => ($dataform['match_type'] == $key) ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('main.load.choicetypes_add.choicetypes');
        }
        $xtpl->parse('main.load.choicetypes_add');
    } else {
        $xtpl->assign('FIELD_TYPE_TEXT', $array_field_type[$dataform['field_type']]);
        if ((!empty($dataform['sql_choices']))) {
            $xtpl->assign('choicetypes_add_hidden', 'field_choicetypes_sql');
            $xtpl->assign('FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_sql']);
        } else {
            $xtpl->assign('choicetypes_add_hidden', 'field_choicetypes_text');
            $xtpl->assign('FIELD_TYPE_SQL', $array_choice_type['field_choicetypes_text']);
        }
        $xtpl->parse('main.load.choicetypes_add_hidden');
    }
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
    foreach ($array_match_type as $key => $value) {
        $xtpl->assign('MATCH_TYPE', [
            'key' => $key,
            'value' => $value,
            'match_value' => ($key == 'regex') ? $dataform['match_regex'] : $dataform['func_callback'],
            'checked' => ($dataform['match_type'] == $key) ? ' checked="checked"' : '',
            'match_disabled' => ($dataform['match_type'] != $key) ? ' disabled="disabled"' : ''
        ]);

        if ($key == 'regex' or $key == 'callback') {
            $xtpl->parse('main.load.match_type.match_input');
        }
        $xtpl->parse('main.load.match_type');
    }

    $xtpl->assign('DATAFILE', $datafile);
    $ini = array_intersect_key(nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/mime.ini', true), array_flip($global_config['file_allowed_ext']));
    foreach ($ini as $filetype => $mimes) {
        $xtpl->assign('FILETYPE', [
            'key' => $filetype,
            'checked' => (!empty($datafile['filetype']) and in_array($filetype, $datafile['filetype'], true)) ? ' checked="checked"' : ''
        ]);
        foreach ($mimes as $key => $val) {
            $xtpl->assign('MIME', [
                'key' => $key,
                'checked' => (!empty($datafile['mime']) and in_array($key, $datafile['mime'], true)) ? ' checked="checked"' : ''
            ]);
            $xtpl->parse('main.load.filetype.mime');
        }
        $xtpl->parse('main.load.filetype');
    }

    $p_size = $global_config['nv_max_size'] / 100;
    for ($index = 100; $index > 0; --$index) {
        $size = floor($index * $p_size);

        $xtpl->assign('SIZE', [
            'key' => $size,
            'name' => nv_convertfromBytes($size),
            'sel' => (!empty($datafile['file_max_size']) and $size == $datafile['file_max_size']) ? ' selected="selected"' : ''
        ]);

        $xtpl->parse('main.load.size');
    }

    for ($i = 1; $i <= 20; ++$i) {
        $xtpl->assign('MAXNUM', [
            'key' => $i,
            'sel' => (!empty($datafile['maxnum']) and $i == $datafile['maxnum']) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.load.maxnum');
    }

    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.load.error');
    }

    $xtpl->parse('main.load');
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    $page_title = $nv_Lang->getModule('fields');
    $contents = nv_admin_theme($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
