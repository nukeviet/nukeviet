<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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
