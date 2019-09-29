<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$global_array_genders = [
    'N' => [
        'key' => 'N',
        'title' => $lang_module['na'],
        'selected' => ''
    ],
    'M' => [
        'key' => 'M',
        'title' => $lang_module['male'],
        'selected' => ''
    ],
    'F' => [
        'key' => 'F',
        'title' => $lang_module['female'],
        'selected' => ''
    ]
];

/**
 * @return mixed[]
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
            while (list ($key, $val) = $result->fetch(3)) {
                $row_field['field_choices'][$key] = $val;
            }
        }
        $row_field['system'] = $row_field['is_system'];
        $array_field_config[$row_field['field']] = $row_field;
    }

    return $array_field_config;
}
