<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$idtemplate = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template where alias = "' . preg_replace("/[\_]/", "-", $global_array_shops_cat[$rowcontent['listcatid']]['form']) . '"')->fetchColumn();
if ($idtemplate) {
    $array_tmp = array( );
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field');
    while ($row = $result->fetch()) {
        $language = unserialize($row['language']);
        $row['title'] = (isset($language[NV_LANG_DATA])) ? $language[NV_LANG_DATA][0] : $row['field'];
        $row['description'] = (isset($language[NV_LANG_DATA])) ? nv_htmlspecialchars($language[NV_LANG_DATA][1]) : '';

        $value = (isset($array_custom[$row['fid']])) ? $array_custom[$row['fid']] : '';

        if (!empty($row['field_choices'])) {
            $row['field_choices'] = unserialize($row['field_choices']);
            if ($row['field_type'] == 'date') {
                $array_custom[$row['fid']] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
            } elseif ($row['field_type'] == 'number') {
                $array_custom[$row['fid']] = $row['default_value'];
            } else {
                $temp = array_keys($row['field_choices']);
                $tempkey = intval($row['default_value']) - 1;
                $array_custom[$row['fid']] = (isset($temp[$tempkey])) ? $temp[$tempkey] : '';
            }
        } elseif (!empty($row['sql_choices'])) {
            $row['sql_choices'] = explode('|', $row['sql_choices']);
            $query = 'SELECT ' . $row['sql_choices'][2] . ', ' . $row['sql_choices'][3] . ' FROM ' . $row['sql_choices'][1];
            $result_sql = $db->query($query);
            $weight = 0;
            while (list($key, $val) = $result_sql->fetch(3)) {
                $row['field_choices'][$key] = $val;
            }
        }

        if ($value != '') {
            if ($row['field_type'] == 'number') {
                $number_type = $row['field_choices']['number_type'];
                $pattern = ($number_type == 1) ? "/^[0-9]+$/" : "/^[0-9\.]+$/";

                if (!preg_match($pattern, $value)) {
                    $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                } else {
                    $value = ($number_type == 1) ? intval($value) : floatval($value);

                    if ($value < $row['min_length'] or $value > $row['max_length']) {
                        $error = sprintf($lang_module['field_min_max_value'], $row['title'], $row['min_length'], $row['max_length']);
                    }
                }
            } elseif ($row['field_type'] == 'date') {
                if (preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $value, $m)) {
                    $value = mktime(0, 0, 0, $m[2], $m[1], $m[3]);

                    if ($value < $row['min_length'] or $value > $row['max_length']) {
                        $error = sprintf($lang_module['field_min_max_value'], $row['title'], date('d/m/Y', $row['min_length']), date('d/m/Y', $row['max_length']));
                    }
                } else {
                    $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                }
            } elseif ($row['field_type'] == 'textbox') {
                if ($row['match_type'] == 'alphanumeric') {
                    if (!preg_match("/^[a-zA-Z0-9\_]+$/", $value)) {
                        $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                    }
                } elseif ($row['match_type'] == 'email') {
                    $error = nv_check_valid_email($value);
                } elseif ($row['match_type'] == 'url') {
                    if (!nv_is_url($value)) {
                        $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                    }
                } elseif ($row['match_type'] == 'regex') {
                    if (!preg_match("/" . $row['match_regex'] . "/", $value)) {
                        $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                    }
                } elseif ($row['match_type'] == 'callback') {
                    if (function_exists($row['func_callback'])) {
                        if (!call_user_func($row['func_callback'], $value)) {
                            $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                        }
                    } else {
                        $error = "error function not exists " . $row['func_callback'];
                    }
                } else {
                    $value = nv_htmlspecialchars($value);
                }

                $strlen = nv_strlen($value);

                if ($strlen < $row['min_length'] or $strlen > $row['max_length']) {
                    $error = sprintf($lang_module['field_min_max_error'], $row['title'], $row['min_length'], $row['max_length']);
                }
            } elseif ($row['field_type'] == 'textarea' or $row['field_type'] == 'editor') {
                $allowed_html_tags = array_map("trim", explode(',', NV_ALLOWED_HTML_TAGS));
                $allowed_html_tags = "<" . implode("><", $allowed_html_tags) . ">";
                $value = strip_tags($value, $allowed_html_tags);

                if ($row['match_type'] == 'regex') {
                    if (!preg_match("/" . $row['match_regex'] . "/", $value)) {
                        $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                    }
                } elseif ($row['match_type'] == 'callback') {
                    if (function_exists($row['func_callback'])) {
                        if (!call_user_func($row['func_callback'], $value)) {
                            $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                        }
                    } else {
                        $error = "error function not exists " . $row['func_callback'];
                    }
                }

                $value = ($row['field_type'] == 'textarea') ? nv_nl2br($value, '<br />') : nv_editor_nl2br($value);
                $strlen = nv_strlen($value);

                if ($strlen < $row['min_length'] or $strlen > $row['max_length']) {
                    $error = sprintf($lang_module['field_min_max_error'], $row['title'], $row['min_length'], $row['max_length']);
                }
            } elseif ($row['field_type'] == 'checkbox' or $row['field_type'] == 'multiselect') {
                $temp_value = array( );
                foreach ($value as $value_i) {
                    if (isset($row['field_choices'][$value_i])) {
                        $temp_value[] = $value_i;
                    }
                }

                $value = implode(',', $temp_value);
            } elseif ($row['field_type'] == 'select' or $row['field_type'] == 'radio') {
                if (!isset($row['field_choices'][$value])) {
                    $error = sprintf($lang_module['field_match_type_error'], $row['title']);
                }
            }

            $array_custom[$row['fid']] = $value;
        }
    }
}
