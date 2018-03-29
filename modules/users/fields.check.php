<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/5/2012 11:29
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (empty($query_field)) {
    $query_field = array();
}
if (defined('NV_ADMIN') and (!isset($_user) or !is_array($_user))) {
    $_user = array();
} elseif ($op == 'register' and (!isset($array_register) or !is_array($array_register))) {
    $array_register = array();
} elseif ($op == 'editinfo' and (!isset($array_data) or !is_array($array_data))) {
    $array_data = array();
}

foreach ($array_field_config as $row_f) {
    $value = (isset($custom_fields[$row_f['field']])) ? $custom_fields[$row_f['field']] : '';
    $field_input_name = empty($row_f['system']) ? 'custom_fields[' . $row_f['field'] . ']' : $row_f['field'];
    if ($value != '') {
        if ($row_f['field_type'] == 'number') {
            $number_type = $row_f['field_choices']['number_type'];
            $pattern = ($number_type == 1) ? '/^[0-9]+$/' : '/^[0-9\.]+$/';
            
            if (!preg_match($pattern, $value)) {
                nv_jsonOutput(array(
                    'status' => 'error',
                    'input' => $field_input_name,
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ));
            } else {
                $value = ($number_type == 1) ? intval($value) : floatval($value);
                
                if ($value < $row_f['min_length'] or $value > $row_f['max_length']) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ));
                }
            }
        } elseif ($row_f['field_type'] == 'date') {
            if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $value, $m)) {
                $m[1] = intval($m[1]);
                $m[2] = intval($m[2]);
                $m[3] = intval($m[3]);
                $value = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
                
                if ($row_f['min_length'] > 0 and ($value < $row_f['min_length'] or $value > $row_f['max_length'])) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_min_max_value'], $row_f['title'], date('d/m/Y', $row_f['min_length']), date('d/m/Y', $row_f['max_length']))
                    ));
                } elseif ($row_f['field'] == 'birthday' and !empty($global_users_config['min_old_user']) and ($m[3] > (date('Y') - $global_users_config['min_old_user']) or ($m[3] == (date('Y') - $global_users_config['min_old_user']) and ($m[2] > date('n') or ($m[2] == date('n') and $m[1] > date('j')))))) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['old_min_user_error'], $global_users_config['min_old_user'])
                    ));
                }
            } else {
                nv_jsonOutput(array(
                    'status' => 'error',
                    'input' => $field_input_name,
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ));
            }
        } elseif ($row_f['field_type'] == 'textbox') {
            if ($row_f['match_type'] == 'alphanumeric') {
                if (!preg_match('/^[a-zA-Z0-9\_]+$/', $value)) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ));
                }
            } elseif ($row_f['match_type'] == 'email') {
                if (($error = nv_check_valid_email($value)) != '') {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $error
                    ));
                }
            } elseif ($row_f['match_type'] == 'url') {
                if (!nv_is_url($value)) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ));
                }
            } elseif ($row_f['match_type'] == 'regex') {
                if (!preg_match('/' . $row_f['match_regex'] . '/', $value)) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ));
                }
            } elseif ($row_f['match_type'] == 'callback') {
                if (function_exists($row_f['func_callback'])) {
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        nv_jsonOutput(array(
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ));
                    }
                } else {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => 'error function not exists ' . $row_f['func_callback']
                    ));
                }
            } else {
                $value = nv_htmlspecialchars($value);
            }
            
            $strlen = nv_strlen($value);
            
            if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                nv_jsonOutput(array(
                    'status' => 'error',
                    'input' => $field_input_name,
                    'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                ));
            }
        } elseif ($row_f['field_type'] == 'textarea' or $row_f['field_type'] == 'editor') {
            $allowed_html_tags = array_map('trim', explode(',', NV_ALLOWED_HTML_TAGS));
            $allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';
            $value = strip_tags($value, $allowed_html_tags);
            if ($row_f['match_type'] == 'regex') {
                if (!preg_match('/' . $row_f['match_regex'] . '/', $value)) {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                    ));
                }
            } elseif ($row_f['match_type'] == 'callback') {
                if (function_exists($row_f['func_callback'])) {
                    if (!call_user_func($row_f['func_callback'], $value)) {
                        nv_jsonOutput(array(
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                        ));
                    }
                } else {
                    nv_jsonOutput(array(
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => 'error function not exists ' . $row_f['func_callback']
                    ));
                }
            }
            
            $value = ($row_f['field_type'] == 'textarea') ? nv_nl2br($value, '<br />') : $value;
            $strlen = nv_strlen($value);
            
            if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                nv_jsonOutput(array(
                    'status' => 'error',
                    'input' => $field_input_name,
                    'mess' => sprintf($lang_module['field_min_max_error'], $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                ));
            }
        } elseif ($row_f['field_type'] == 'checkbox' or $row_f['field_type'] == 'multiselect') {
            $temp_value = array();
            foreach ($value as $value_i) {
                if (isset($row_f['field_choices'][$value_i])) {
                    $temp_value[] = $value_i;
                }
            }
            
            $value = implode(',', $temp_value);
        } elseif ($row_f['field_type'] == 'select' or $row_f['field_type'] == 'radio') {
            if (!isset($row_f['field_choices'][$value])) {
                nv_jsonOutput(array(
                    'status' => 'error',
                    'input' => $field_input_name,
                    'mess' => sprintf($lang_module['field_match_type_error'], $row_f['title'])
                ));
            }
        }
        
        $custom_fields[$row_f['field']] = $value;
    }
    
    if (empty($value) and $row_f['required']) {
        nv_jsonOutput(array(
            'status' => 'error',
            'input' => $field_input_name,
            'mess' => sprintf($lang_module['field_match_type_required'], $row_f['title'])
        ));
    }
    
    if (empty($row_f['system'])) {
        if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
            $value = floatval($value);
        } else {
            $value = $db->quote($value);
        }
        if (!empty($userid)) {
            $query_field[] = $row_f['field'] . '=' . $value;
        } else {
            $query_field[$row_f['field']] = $value;
        }
    } elseif (defined('NV_ADMIN')) {
        $_user[$row_f['field']] = $value;
    } elseif ($op == 'register') {
        $array_register[$row_f['field']] = $value;
    } elseif ($op == 'editinfo') {
        $array_data[$row_f['field']] = $value;
    }
}
