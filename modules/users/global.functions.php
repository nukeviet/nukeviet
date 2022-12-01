<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

define('NV_2STEP_VERIFICATION_MODULE', 'two-step-verification');

$global_array_genders = [
    'M' => [
        'key' => 'M',
        'title' => $lang_module['male'],
        'selected' => ''
    ],
    'F' => [
        'key' => 'F',
        'title' => $lang_module['female'],
        'selected' => ''
    ],
    'N' => [
        'key' => 'N',
        'title' => $lang_module['na'],
        'selected' => ''
    ]
];

/**
 * nv_get_users_field_config()
 *
 * @return array
 */
function nv_get_users_field_config()
{
    global $db;

    $array_field_config = [];
    $result_field = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC');
    while ($row_field = $result_field->fetch()) {
        $language = unserialize($row_field['language']);
        $row_field['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row_field['field'];
        $row_field['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';
        if (!empty($row_field['field_choices'])) {
            $row_field['field_choices'] = unserialize($row_field['field_choices']);
        } elseif (!empty($row_field['sql_choices'])) {
            $row_field['sql_choices'] = explode('|', $row_field['sql_choices']);
            $row_field['field_choices'] = [];
            $query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
            if (!empty($row_field['sql_choices'][4]) and !empty($row_field['sql_choices'][5])) {
                $query .= ' ORDER BY ' . $row_field['sql_choices'][4] . ' ' . $row_field['sql_choices'][5];
            }
            $result = $db->query($query);
            $weight = 0;
            while (list($key, $val) = $result->fetch(3)) {
                $row_field['field_choices'][$key] = $val;
            }
        }
        $row_field['system'] = $row_field['is_system'];
        $array_field_config[$row_field['field']] = $row_field;
    }

    return $array_field_config;
}

function oldPassSave($userid, $oldpass, $oldpass_creation_time)
{
    global $db, $global_config;

    empty($global_config['oldpass_num']) && $global_config['oldpass_num'] = 5;

    try {
        $db->query('INSERT INTO ' . NV_MOD_TABLE . '_oldpass VALUES (' . $userid . ', ' . $db->quote($oldpass) . ', ' . $oldpass_creation_time . ') ON DUPLICATE KEY UPDATE password=VALUES(password)');

        $mtime = $db->query('SELECT pass_creation_time FROM ' . NV_MOD_TABLE . '_oldpass WHERE userid=' . $userid . ' ORDER BY pass_creation_time DESC LIMIT ' . $global_config['oldpass_num'] . ', 1')->fetchColumn();
        if ($mtime !== false) {
            $mtime = (int) $mtime;
            $db->query('DELETE FROM ' . NV_MOD_TABLE . '_oldpass WHERE userid=' . $userid . ' AND pass_creation_time <= ' . $mtime);
        }
    } catch (PDOException $e) {
        trigger_error(print_r($e, true));
    }
}

function passCmp($newpass, $currentpass, $userid)
{
    global $crypt, $db;

    if (!empty($currentpass) and $crypt->validate_password($newpass, $currentpass)) {
        return false;
    }

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_oldpass WHERE userid=' . $userid;
    $query = $db->query($sql);
    while ($row = $query->fetch()) {
        if ($crypt->validate_password($newpass, $row['password'])) {
            return false;
        }
    }

    return true;
}

function forcedrelogin($userid)
{
    global $db;

    $checknum = md5(nv_genpass(10));
    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET checknum=:checknum WHERE userid=' . $userid);
    $stmt->bindParam(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->execute();
}

function fieldsCheck($custom_fields, &$array_data, &$query_field, &$valid_field, $userid = 0)
{
    global $array_field_config, $lang_module, $global_users_config;

    empty($array_field_config) && $array_field_config = nv_get_users_field_config();

    if (empty($query_field)) {
        $query_field = [];
    }
    if (empty($valid_field)) {
        $valid_field = [];
    }

    foreach ($array_field_config as $row_f) {
        $value = (isset($custom_fields[$row_f['field']])) ? $custom_fields[$row_f['field']] : '';
        $field_input_name = empty($row_f['system']) ? 'custom_fields[' . $row_f['field'] . ']' : $row_f['field'];
        if (!empty($value)) {
            if ($row_f['field_type'] == 'number') {
                $pattern = ($row_f['field_choices']['number_type'] == 1) ? '/^[0-9]+$/' : '/^[0-9\.]+$/';

                if (!preg_match($pattern, $value)) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ];
                }

                $value = ($row_f['field_choices']['number_type'] == 1) ? (int) $value : (float) $value;

                if ($value < $row_f['min_length'] or $value > $row_f['max_length']) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'date') {
                if (!preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $value, $m)) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ];
                }

                $m[1] = (int) ($m[1]);
                $m[2] = (int) ($m[2]);
                $m[3] = (int) ($m[3]);
                $value = mktime(0, 0, 0, $m[2], $m[1], $m[3]);

                if ($row_f['min_length'] > 0 and ($value < $row_f['min_length'] or $value > $row_f['max_length'])) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], date('d/m/Y', $row_f['min_length']), date('d/m/Y', $row_f['max_length']))
                    ];
                }

                if ($row_f['field'] == 'birthday' and !empty($global_users_config['min_old_user']) and ($m[3] > (date('Y') - $global_users_config['min_old_user']) or ($m[3] == (date('Y') - $global_users_config['min_old_user']) and ($m[2] > date('n') or ($m[2] == date('n') and $m[1] > date('j')))))) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['old_min_user_error'], $global_users_config['min_old_user'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'textbox') {
                if ($row_f['match_type'] == 'alphanumeric') {
                    if (!preg_match('/^[a-zA-Z0-9\_]+$/', $value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'unicodename') {
                    if (!preg_match('/^([\p{L}\p{Mn}\p{Pd}\'][\p{L}\p{Mn}\p{Pd}\',\s]*)*$/u', str_replace('&#039;', "'", $value))) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'email') {
                    list($errorContent, $value) = nv_check_valid_email($value, true);
                    if (!empty($isError)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => $errorContent
                        ];
                    }
                } elseif ($row_f['match_type'] == 'url') {
                    if (!nv_is_url($value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'regex') {
                    $value_t = str_replace(['&#039;', '&quot;', '&lt;', '&gt;'], ["'", '"', '<', '>'], $value);
                    if (@preg_match($row_f['match_regex'], '') !== false) {
                        if (!preg_match($row_f['match_regex'], $value_t)) {
                            return [
                                'status' => 'error',
                                'input' => $field_input_name,
                                'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                            ];
                        }
                    } else {
                        if (!preg_match('/' . $row_f['match_regex'] . '/', $value_t)) {
                            return [
                                'status' => 'error',
                                'input' => $field_input_name,
                                'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                            ];
                        }
                    }
                } elseif ($row_f['match_type'] == 'callback') {
                    if (!function_exists($row_f['func_callback'])) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => 'error function not exists ' . $row_f['func_callback']
                        ];
                    }
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                } else {
                    $value = nv_htmlspecialchars($value);
                }

                $strlen = nv_strlen($value);

                if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'textarea' or $row_f['field_type'] == 'editor') {
                $allowed_html_tags = array_map('trim', explode(',', NV_ALLOWED_HTML_TAGS));
                $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
                $value = strip_tags($value, $allowed_html_tags);
                if ($row_f['match_type'] == 'regex') {
                    if (!preg_match('/' . $row_f['match_regex'] . '/', $value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'callback') {
                    if (!function_exists($row_f['func_callback'])) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => 'error function not exists ' . $row_f['func_callback']
                        ];
                    }
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ];
                    }
                }

                $value = ($row_f['field_type'] == 'textarea') ? nv_nl2br($value, '<br />') : $value;
                $strlen = nv_strlen($value);

                if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'checkbox' or $row_f['field_type'] == 'multiselect') {
                $temp_value = [];
                foreach ($value as $value_i) {
                    if (isset($row_f['field_choices'][$value_i])) {
                        $temp_value[] = $value_i;
                    }
                }

                $value = implode(',', $temp_value);
            } elseif ($row_f['field_type'] == 'select' or $row_f['field_type'] == 'radio') {
                if (!isset($row_f['field_choices'][$value])) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ];
                }
            }

            $custom_fields[$row_f['field']] = $value;
        }

        if (empty($value) and $row_f['required']) {
            return [
                'status' => 'error',
                'input' => $field_input_name,
                'mess' => sprintf($lang_module['field_match_type_required'], $row_f['title'])
            ];
        }

        if (empty($row_f['system'])) {
            if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
                $value = (float) $value;
                $valid_field[$row_f['field']] = $value;
            } else {
                $valid_field[$row_f['field']] = $value;
                //$value = $db->quote($value);
            }
            if (!empty($userid)) {
                $query_field[] = $row_f['field'] . '=' . $value;
            } else {
                $query_field[$row_f['field']] = $value;
            }
        } else {
            $array_data[$row_f['field']] = $value;
        }
    }

    return [
        'status' => 'OK'
    ];
}

// Xác định cấu hình module
$global_users_config = [];
$cacheFile = NV_LANG_DATA . '_' . $module_data . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = 'SELECT config, content FROM ' . NV_MOD_TABLE . '_config';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}
