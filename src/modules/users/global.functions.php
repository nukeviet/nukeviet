<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
        'title' => $nv_Lang->getModule('male'),
        'selected' => ''
    ],
    'F' => [
        'key' => 'F',
        'title' => $nv_Lang->getModule('female'),
        'selected' => ''
    ],
    'N' => [
        'key' => 'N',
        'title' => $nv_Lang->getModule('na'),
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
        $row_field['title'] = isset($language[NV_LANG_DATA]) ? $language[NV_LANG_DATA][0] : $row_field['field'];
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
            while ([$key, $val] = $result->fetch(3)) {
                $row_field['field_choices'][$key] = $val;
            }
        }
        $row_field['system'] = $row_field['is_system'];
        $array_field_config[$row_field['field']] = $row_field;
    }

    return $array_field_config;
}

/**
 * oldPassSave()
 *
 * @param mixed $userid
 * @param mixed $oldpass
 * @param mixed $oldpass_creation_time
 */
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

/**
 * passCmp()
 *
 * @param mixed $newpass
 * @param mixed $currentpass
 * @param mixed $userid
 * @return bool
 */
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

/**
 * forcedrelogin()
 *
 * @param mixed $userid
 * @throws PDOException
 */
function forcedrelogin($userid)
{
    global $db;

    $checknum = md5(nv_genpass(10));
    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . ' SET checknum=:checknum WHERE userid=' . $userid);
    $stmt->bindParam(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->execute();
}

/**
 * get_file_save_info()
 * Tạo tên file bí mật và thư mục lưu file
 *
 * @param mixed $value
 * @return string[]
 */
function get_file_save_info($value)
{
    global $global_config;

    $md5filename = md5($value . '_' . substr($global_config['sitekey'], 5, 8));

    return [
        'dir' => substr($md5filename, 0, 2),
        'basename' => $md5filename . '.' . nv_getextension($value)
    ];
}

/**
 * get_other_fields()
 *
 * @param mixed $array_field_config
 * @return mixed
 */
function get_other_fields($array_field_config)
{
    return array_diff_key($array_field_config, [
        'first_name' => 1,
        'last_name' => 1,
        'gender' => 1,
        'birthday' => 1,
        'sig' => 1,
        'question' => 1,
        'answer' => 1
    ]);
}

/**
 * delete_userfile()
 *
 * @param mixed $file_save_info
 */
function delete_userfile($file_save_info)
{
    global $module_upload;

    @unlink(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename']);
    $files = scandir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/');
    $files = array_diff($files, ['.', '..', '.htaccess', 'index.html']);
    if (!count($files)) {
        nv_deletefile(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/');
    }
}

/**
 * fieldsCheck()
 *
 * @param mixed $custom_fields
 * @param mixed $array_data
 * @param mixed $query_field
 * @param mixed $valid_field
 * @return array
 */
function fieldsCheck(&$custom_fields, &$array_data, &$query_field, &$valid_field)
{
    global $array_field_config, $nv_Lang, $global_users_config, $module_upload;

    if (empty($array_field_config)) {
        $array_field_config = get_other_fields(nv_get_users_field_config());
    }

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
                        'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                    ];
                }

                $value = ($row_f['field_choices']['number_type'] == 1) ? (int) $value : (float) $value;

                if ($value < $row_f['min_length'] or $value > $row_f['max_length']) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $nv_Lang->getModule('field_min_max_value', $row_f['title'], $row_f['min_length'], $row_f['max_length'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'date') {
                $value = nv_d2u_post($value);
                if (empty($value)) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                    ];
                }

                if ($row_f['min_length'] > 0 and ($value < $row_f['min_length'] or $value > $row_f['max_length'])) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $nv_Lang->getModule('field_min_max_value_date', $row_f['title'], nv_u2d_post($row_f['min_length']), nv_u2d_post($row_f['max_length']))
                    ];
                }

                $dd = intval(date('j', $value));
                $mm = intval(date('n', $value));
                $yy = intval(date('Y', $value));

                if ($row_f['field'] == 'birthday' and !empty($global_users_config['min_old_user']) and ($yy > (date('Y') - $global_users_config['min_old_user']) or ($yy == (date('Y') - $global_users_config['min_old_user']) and ($mm > date('n') or ($mm == date('n') and $dd > date('j')))))) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $nv_Lang->getModule('old_min_user_error', $global_users_config['min_old_user'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'textbox') {
                if ($row_f['match_type'] == 'alphanumeric') {
                    if (!preg_match('/^[a-zA-Z0-9\_]+$/', $value)) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'unicodename') {
                    if (!preg_match('/^([\p{L}\p{Mn}\p{Pd}\'][\p{L}\p{Mn}\p{Pd}\',\s]*)*$/u', str_replace('&#039;', "'", $value))) {
                        return [
                            'status' => 'error',
                            'input' => $field_input_name,
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'email') {
                    [$errorContent, $value] = nv_check_valid_email($value, true);
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
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                        ];
                    }
                } elseif ($row_f['match_type'] == 'regex') {
                    $value_t = str_replace(['&#039;', '&quot;', '&lt;', '&gt;'], ["'", '"', '<', '>'], $value);
                    if (@preg_match($row_f['match_regex'], '') !== false) {
                        if (!preg_match($row_f['match_regex'], $value_t)) {
                            return [
                                'status' => 'error',
                                'input' => $field_input_name,
                                'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                            ];
                        }
                    } else {
                        if (!preg_match('/' . $row_f['match_regex'] . '/', $value_t)) {
                            return [
                                'status' => 'error',
                                'input' => $field_input_name,
                                'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
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
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
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
                        'mess' => $nv_Lang->getModule('field_min_max_error', $row_f['title'], $row_f['min_length'], $row_f['max_length'])
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
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
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
                            'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                        ];
                    }
                }

                $value = ($row_f['field_type'] == 'textarea') ? nv_nl2br($value, '<br />') : $value;
                $strlen = nv_strlen($value);

                if ($strlen < $row_f['min_length'] or $strlen > $row_f['max_length']) {
                    return [
                        'status' => 'error',
                        'input' => $field_input_name,
                        'mess' => $nv_Lang->getModule('field_min_max_error', $row_f['title'], $row_f['min_length'], $row_f['max_length'])
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
                        'mess' => $nv_Lang->getModule('field_match_type_error', $row_f['title'])
                    ];
                }
            } elseif ($row_f['field_type'] == 'file') {
                $temp_value = [];
                if (!empty($value)) {
                    $value = array_values(array_unique($value));
                    foreach ($value as $i => $value_i) {
                        $file_save_info = get_file_save_info($value_i);
                        if (!empty($row_f['limited_values']['maxnum']) and $i >= $row_f['limited_values']['maxnum']) {
                            if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                delete_userfile($file_save_info);
                            } elseif (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename'])) {
                                @unlink(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename']);
                            }
                        } else {
                            if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                $temp_value[] = $value_i;
                            } elseif (file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename'])) {
                                if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'])) {
                                    nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/', $file_save_info['dir']);
                                }
                                if (nv_copyfile(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename'], NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                    $temp_value[] = $value_i;
                                }
                                @unlink(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $file_save_info['basename']);
                            }
                        }
                    }
                }
                $value = !empty($temp_value) ? implode(',', $temp_value) : '';
            }

            $custom_fields[$row_f['field']] = $value;
        }

        if (empty($value) and $row_f['required']) {
            return [
                'status' => 'error',
                'input' => $field_input_name,
                'mess' => $nv_Lang->getModule('field_match_type_required', $row_f['title'])
            ];
        }

        if ($row_f['field_type'] == 'number' or $row_f['field_type'] == 'date') {
            $value = (float) $value;
        }

        if (empty($row_f['system'])) {
            $valid_field[$row_f['field']] = $value;
            $query_field[$row_f['field']] = $value;
        } else {
            $array_data[$row_f['field']] = $value;
        }
    }

    return [
        'status' => 'OK'
    ];
}

/**
 * userInfoTabDb()
 * Lưu/cập nhật bảng nv4_users_info
 *
 * @param mixed $data
 * @param int   $userid
 * @return false|PDOStatement
 */
function userInfoTabDb($data, $userid = 0)
{
    global $db, $array_field_config, $module_upload;

    if ($userid) {
        if (empty($array_field_config)) {
            $array_field_config = get_other_fields(nv_get_users_field_config());
        }
        foreach ($array_field_config as $row_f) {
            if ($row_f['field_type'] == 'file') {
                $old_values = $db->query('SELECT ' . $row_f['field'] . ' FROM ' . NV_MOD_TABLE . '_info WHERE userid = ' . $userid)->fetchColumn();
                $old_values = !empty($old_values) ? array_map('trim', explode(',', $old_values)) : [];
                if (!empty($old_values)) {
                    $temp_value = $data[$row_f['field']];
                    !empty($temp_value) && $temp_value = array_map('trim', explode(',', $temp_value));
                    foreach ($old_values as $old_value) {
                        if (empty($temp_value) or !in_array($old_value, $temp_value, true)) {
                            $file_save_info = get_file_save_info($old_value);
                            if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                delete_userfile($file_save_info);
                            }
                        }
                    }
                }
            }
        }

        $upd = [];
        foreach ($data as $key => $value) {
            $upd[] = $key . '=' . $db->quote($value);
        }
        $upd = implode(', ', $upd);

        return $db->query('UPDATE ' . NV_MOD_TABLE . '_info SET ' . $upd . ' WHERE userid=' . $userid);
    }
    $keys = implode(', ', array_keys($data));
    $values = implode(', ', array_map(function ($val) {
        global $db;

        return $db->quote($val);
    }, array_values($data)));

    return $db->query('INSERT INTO ' . NV_MOD_TABLE . '_info (' . $keys . ') VALUES (' . $values . ')');
}

/**
 * image_size_info()
 *
 * @param mixed $size
 * @param mixed $coor
 * @return string
 */
function image_size_info($size, $coor)
{
    global $nv_Lang;

    $limit = [];
    if (!empty($size['equal'])) {
        $limit[] = $nv_Lang->getModule('equal') . ' ' . $size['equal'] . ' px';
    } else {
        if (!empty($size['greater'])) {
            $limit[] = $nv_Lang->getModule('greater') . ' ' . $size['greater'] . ' px';
        }
        if (!empty($size['less'])) {
            $limit[] = $nv_Lang->getModule('less') . ' ' . $size['less'] . ' px';
        }
    }
    $limit = !empty($limit) ? $nv_Lang->getModule('file_image_' . $coor) . ' ' . implode(', ', $limit) : '';

    return $limit;
}

/**
 * shorten_name()
 *
 * @param mixed $filename
 * @param mixed $extension
 * @return string
 */
function shorten_name($filename, $extension)
{
    return (strlen($filename) > 11 ? substr($filename, 0, 4) . '...' . substr($filename, -4, 4) : $filename) . '.' . $extension;
}

/**
 * file_type()
 *
 * @param mixed $ext
 * @return string
 */
function file_type($ext)
{
    return in_array($ext, ['gif', 'jpg', 'jpeg', 'png', 'webp'], true) ? 'image' : (in_array($ext, ['doc', 'docx'], true) ? 'doc' : ($ext == 'pdf' ? 'pdf' : 'file'));
}

/**
 * file_type_name()
 *
 * @param mixed $file
 * @return array
 */
function file_type_name($file)
{
    $pathinfo = pathinfo($file);

    return [
        'type' => file_type($pathinfo['extension']),
        'key' => $file,
        'value' => shorten_name($pathinfo['filename'], $pathinfo['extension'])
    ];
}

/**
 * delOldRegAccount()
 * Xóa các tài khoản chờ kích hoạt quá hạn
 */
function delOldRegAccount()
{
    global $global_users_config, $db;

    $register_active_time = isset($global_users_config['register_active_time']) ? (int) $global_users_config['register_active_time'] : 86400;
    if ($register_active_time) {
        $del = NV_CURRENTTIME - $register_active_time;
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reg WHERE regdate < ' . $del);
    }
}

/**
 * get_value_by_lang()
 *
 * @param mixed $value
 * @return mixed
 */
function get_value_by_lang($value)
{
    $value_decode = json_decode($value, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $value = !empty($value_decode[NV_LANG_DATA]) ? $value_decode[NV_LANG_DATA] : '';
    }

    return $value;
}

/**
 * get_value_by_lang2()
 *
 * @param mixed $key
 * @param mixed $value
 * @return mixed
 */
function get_value_by_lang2($key, $value)
{
    if (is_array($value)) {
        if (!empty($value[NV_LANG_DATA])) {
            $return = $value[NV_LANG_DATA];
        } else {
            $return = '';
        }
    } else {
        $return = $value;
    }
    empty($return) && $return = $key;

    return $return;
}

// Xác định cấu hình module
$global_users_config = [];
$cacheFile = 'config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem($module_name, $cacheFile, ttl: $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = 'SELECT config, content FROM ' . NV_MOD_TABLE . '_config';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, ttl: $cacheTTL);
}
