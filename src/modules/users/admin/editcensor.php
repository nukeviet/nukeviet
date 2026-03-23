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

$page_title = $table_caption = $nv_Lang->getModule('editcensor');

// Hủy bỏ thông tin chỉnh sửa
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }


    // Kiểm tra quyền
    $allow = false;

    $sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
    $rowlev = $db->query($sql)->fetch();
    if (empty($rowlev)) {
        $allow = true;
    } else {
        if ($admin_info['admin_id'] == $userid or $admin_info['level'] < $rowlev['lev']) {
            $allow = true;
        }
    }

    if ($global_config['idsite'] > 0 and $admin_info['admin_id'] != $userid) {
        $sql = 'SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
        $rowsite = $db->query($sql)->fetch();
        if (!empty($rowsite) and $rowsite['idsite'] != $global_config['idsite']) {
            $allow = false;
        }
    }

    if (!$allow) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch();
    if (!empty($row['info_custom'])) {
        $info_custom = json_decode($row['info_custom'], true);

        $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid;
        $row_info = $db->query($sql)->fetch();

        $array_field_config = nv_get_users_field_config();

        foreach ($info_custom as $key => $value) {
            if (!empty($value)) {
                if ($array_field_config[$key]['field_type'] == 'file') {
                    $current = !empty($row_info[$key]) ? array_map('trim', explode(',', $row_info[$key])) : [];
                    $new = array_map('trim', explode(',', $value));
                    foreach ($new as $file) {
                        if (empty($current) or !in_array($file, $current, true)) {
                            $file_save_info = get_file_save_info($file);
                            if (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/userfiles/' . $file_save_info['dir'] . '/' . $file_save_info['basename'])) {
                                delete_userfile($file_save_info);
                            }
                        }
                    }
                }
            }
        }
    }
    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid;
    $db->exec($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Denied User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success')
    ]);
}

// Xác nhận thông tin chỉnh sửa (từ danh sách)
if ($nv_Request->isset_request('approved', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }


    // Kiểm tra quyền
    $allow = false;

    $sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
    $rowlev = $db->query($sql)->fetch();
    if (empty($rowlev)) {
        $allow = true;
    } else {
        if ($admin_info['admin_id'] == $userid or $admin_info['level'] < $rowlev['lev']) {
            $allow = true;
        }
    }

    if ($global_config['idsite'] > 0 and $admin_info['admin_id'] != $userid) {
        $sql = 'SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
        $rowsite = $db->query($sql)->fetch();
        if (!empty($rowsite) and $rowsite['idsite'] != $global_config['idsite']) {
            $allow = false;
        }
    }

    if (!$allow) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_edit tb1, ' . NV_MOD_TABLE . ' tb2 WHERE tb1.userid=tb2.userid AND tb1.userid=' . $userid;
    $row = $db->query($sql)->fetch();

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid;
    $row_info = $db->query($sql)->fetch();

    if (!empty($row)) {
        $array_field_config = nv_get_users_field_config();

        // Thông tin cơ bản
        $custom_fields = $row;
        if (!empty($row['info_basic'])) {
            $info_basic = json_decode($row['info_basic'], true);
            $custom_fields = array_merge($custom_fields, $info_basic);
        }

        if (!empty($row_info)) {
            $custom_fields = array_merge($custom_fields, $row_info);
        }

        // Các trường tùy biến
        if (!empty($row['info_custom'])) {
            $info_custom = json_decode($row['info_custom'], true);
            $custom_fields = array_merge($custom_fields, $info_custom);
        }

        /*
         * Duyệt các trường và trả về dữ liệu
         * tương tự lúc submit form để kiểm tra
         */
        foreach ($custom_fields as $fkey => $fval) {
            if (isset($array_field_config[$fkey])) {
                $field = $array_field_config[$fkey];
                if ($field['field_type'] == 'date') {
                    $custom_fields[$fkey] = nv_u2d_post($custom_fields[$fkey] ?? 0);
                } elseif ($field['field_type'] == 'checkbox' or $field['field_type'] == 'multiselect' or $field['field_type'] == 'file') {
                    $custom_fields[$fkey] = empty($custom_fields[$fkey]) ? '' : explode(',', $custom_fields[$fkey]);
                }
            }
        }

        unset($array_field_config['question'], $array_field_config['answer']);
        $query_field = [];
        $valid_field = [];
        if (!empty($array_field_config)) {
            $check = fieldsCheck($custom_fields, $_user, $query_field, $valid_field);
            if ($check['status'] == 'error') {
                nv_jsonOutput($check);
            }
        }

        /*
         * Đến đây tức là đã check hợp lệ dữ liệu
         * Cập nhật thông tin cơ bản
         */
        $db->query('UPDATE ' . NV_MOD_TABLE . ' SET
            first_name=' . $db->quote($custom_fields['first_name']) . ',
            last_name=' . $db->quote($custom_fields['last_name']) . ',
            gender=' . $db->quote($custom_fields['gender']) . ',
            birthday=' . (int) ($custom_fields['birthday']) . ',
            sig=' . $db->quote($custom_fields['sig']) . ',
            view_mail=' . $custom_fields['view_mail'] . ',
            last_update=' . NV_CURRENTTIME . '
        WHERE userid=' . $userid);

        // Cập nhật thông tin tùy biến dữ liệu
        if (!empty($query_field)) {
            userInfoTabDb($query_field, $userid);
        }
    }

    // Xóa thông tin chỉnh sửa
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Approved User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => $nv_Lang->getModule('active_success'),
        'refresh' => true
    ]);
}

$reviewuid = $nv_Request->get_int('reviewuid', 'get', 0);
if (!empty($reviewuid)) {
    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_edit tb1, ' . NV_MOD_TABLE . ' tb2 WHERE tb1.userid=tb2.userid AND tb1.userid=' . $reviewuid;
    $row_basic = $db->query($sql)->fetch();

    $sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $reviewuid;
    $row_info = $db->query($sql)->fetch();

    // Kiểm tra quyền
    $allow = false;

    $sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $reviewuid;
    $rowlev = $db->query($sql)->fetch();
    if (empty($rowlev)) {
        $allow = true;
    } else {
        if ($admin_info['admin_id'] == $reviewuid or $admin_info['level'] < $rowlev['lev']) {
            $allow = true;
        }
    }

    if ($global_config['idsite'] > 0 and $admin_info['admin_id'] != $reviewuid) {
        $sql = 'SELECT idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $reviewuid;
        $rowsite = $db->query($sql)->fetch();
        if (!empty($rowsite) and $rowsite['idsite'] != $global_config['idsite']) {
            $allow = false;
        }
    }

    if (empty($row_basic) or !$allow) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    }

    $array_field_config = nv_get_users_field_config();
    $info_basic = $info_custom = [];
    if (!empty($row_basic['info_basic'])) {
        $info_basic = json_decode($row_basic['info_basic'], true);
    }
    if (!empty($row_basic['info_custom'])) {
        $info_custom = array_intersect_key(json_decode($row_basic['info_custom'], true), $array_field_config);
    }

    // Thông tin cơ bản cũ và thông tin cơ bản mới
    $custom_fields = $custom_fields_old = [];
    $custom_fields_old['first_name'] = $row_basic['first_name'];
    $custom_fields_old['last_name'] = $row_basic['last_name'];
    $custom_fields_old['gender'] = $row_basic['gender'];
    $custom_fields_old['birthday'] = $row_basic['birthday'];
    $custom_fields_old['sig'] = $row_basic['sig'];
    $custom_fields_old['view_mail'] = $row_basic['view_mail'];
    $custom_fields = array_merge($custom_fields_old, $info_basic);

    // Cộng thêm các trường tùy biến cũ và mới
    $custom_fields_old = array_merge($custom_fields_old, (array) $row_info);
    $custom_fields = array_merge($custom_fields, (array) $row_info);
    $custom_fields = array_merge($custom_fields, $info_custom);

    // Xác nhận duyệt thông tin chỉnh sửa
    if ($nv_Request->isset_request('confirm', 'post')) {
        if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_checkss')
            ]);
        }
        $custom_fields = array_merge($row_basic, $row_info, $nv_Request->get_array('custom_fields', 'post'));
        if (!empty($info_basic)) {
            $_user = [];
            $_user['first_name'] = nv_substr($nv_Request->get_title('first_name', 'post', '', 1), 0, 255);
            $_user['last_name'] = nv_substr($nv_Request->get_title('last_name', 'post', '', 1), 0, 255);
            $_user['gender'] = nv_substr($nv_Request->get_title('gender', 'post', '', 1), 0, 1);
            $_user['view_mail'] = $nv_Request->get_int('view_mail', 'post', 0);
            $_user['sig'] = $nv_Request->get_textarea('sig', '', NV_ALLOWED_HTML_TAGS);
            $_user['birthday'] = $nv_Request->get_title('birthday', 'post');

            $custom_fields['first_name'] = $_user['first_name'];
            $custom_fields['last_name'] = $_user['last_name'];
            $custom_fields['gender'] = $_user['gender'];
            $custom_fields['birthday'] = $_user['birthday'];
            $custom_fields['sig'] = $_user['sig'];
            $custom_fields['view_mail'] = $_user['view_mail'];
        } else {
            $custom_fields['birthday'] = nv_u2d_post($custom_fields['birthday']);
        }

        // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
        $query_field = [];
        $valid_field = [];
        $userid = $reviewuid;
        if (!empty($array_field_config)) {
            $check = fieldsCheck($custom_fields, $_user, $query_field, $valid_field);
            if ($check['status'] == 'error') {
                nv_jsonOutput($check);
            }
        }

        // Cập nhật thông tin cơ bản
        if (!empty($info_basic)) {
            $db->query('UPDATE ' . NV_MOD_TABLE . ' SET
                first_name=' . $db->quote($_user['first_name']) . ',
                last_name=' . $db->quote($_user['last_name']) . ',
                gender=' . $db->quote($_user['gender']) . ',
                birthday=' . (int) ($_user['birthday']) . ',
                sig=' . $db->quote($_user['sig']) . ',
                view_mail=' . $_user['view_mail'] . ',
                last_update=' . NV_CURRENTTIME . '
            WHERE userid=' . $reviewuid);
        }

        if (!empty($query_field)) {
            userInfoTabDb($query_field, $reviewuid);
        }

        if (!empty($info_custom)) {
            foreach ($info_custom as $key => $value) {
                if (!empty($value)) {
                    if ($array_field_config[$key]['field_type'] == 'file') {
                        $old_values = array_map('trim', explode(',', $value));
                        $temp_value = $query_field[$array_field_config[$key]['field']];
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
        }

        // Xóa thông tin chỉnh sửa
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $reviewuid);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Approved User Edit', 'Userid: ' . $reviewuid, $admin_info['userid']);
        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'status' => 'ok',
            'mess' => $nv_Lang->getModule('active_success'),
            'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
        ]);
    }

    $page_title .= ': ' . $row_basic['username'];

    // Build basic fields cho template
    $tpl_basic_fields = [];
    $have_name_field = false;
    foreach ($array_field_config as $row) {
        if (!$row['for_admin'] and !empty($row['system'])) {
            $field = $row;
            $field['value'] = $custom_fields[$field['field']] ?? get_value_by_lang($row['default_value']);
            $field['valueold'] = $custom_fields_old[$field['field']] ?? '';
            $field['required'] = !empty($row['required']);

            if ($field['field'] == 'birthday') {
                $field['value'] = nv_u2d_post($field['value']);
                $field['valueold'] = nv_u2d_post($field['valueold']);
            } elseif ($field['field'] == 'sig') {
                $field['value'] = nv_htmlspecialchars(nv_br2nl($field['value']));
            }

            if ($field['field'] == 'first_name' or $field['field'] == 'last_name') {
                $have_name_field = true;
            }

            if ($field['field'] == 'gender') {
                $gender_options = [];
                foreach ($global_array_genders as $gender) {
                    $gender_options[] = [
                        'key' => $gender['key'],
                        'title' => $gender['title'],
                        'selected' => ($field['value'] == $gender['key']),
                    ];
                }
                $field['gender_options'] = $gender_options;
                $field['gender_old'] = isset($global_array_genders[$field['valueold']]) ? $global_array_genders[$field['valueold']]['title'] : '';
            }

            $tpl_basic_fields[$field['field']] = $field;
        }
    }

    // Build custom fields cho template
    $tpl_custom_fields = [];
    $have_custom_fields = false;
    foreach ($array_field_config as $row) {
        if (!$row['for_admin'] and empty($row['system'])) {
            $field = $row;
            $field['value'] = $custom_fields[$field['field']] ?? get_value_by_lang($row['default_value']);
            $field['valueold'] = $custom_fields_old[$field['field']] ?? '';
            $field['required'] = !empty($row['required']);

            if ($field['field_type'] == 'date') {
                $field['value'] = nv_u2d_post($field['value']);
                $field['valueold'] = nv_u2d_post($field['valueold']);
            } elseif ($field['field_type'] == 'textarea') {
                $field['value'] = nv_htmlspecialchars(nv_br2nl($field['value']));
            } elseif ($field['field_type'] == 'editor') {
                $field['value'] = htmlspecialchars(nv_editor_br2nl($field['value']));
                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                    $array_tmp = explode('@', $field['class']);
                    $field['editor_html'] = nv_aleditor('custom_fields[' . $field['field'] . ']', $array_tmp[0], $array_tmp[1], $field['value']);
                    $field['has_editor'] = true;
                } else {
                    $field['class'] = '';
                    $field['has_editor'] = false;
                }
            } elseif ($field['field_type'] == 'select') {
                $options = [];
                foreach ($field['field_choices'] as $key => $value) {
                    $options[] = [
                        'key' => $key,
                        'value' => get_value_by_lang2($key, $value),
                        'selected' => ($key == $field['value']),
                    ];
                }
                $field['choices_options'] = $options;
                $field['valueold'] = isset($field['field_choices'][$field['valueold']]) ? get_value_by_lang2($field['valueold'], $field['field_choices'][$field['valueold']]) : $field['valueold'];
            } elseif ($field['field_type'] == 'radio') {
                $options = [];
                $number = 0;
                $field['valueold'] = isset($field['field_choices'][$field['valueold']]) ? get_value_by_lang2($field['valueold'], $field['field_choices'][$field['valueold']]) : $field['valueold'];
                foreach ($field['field_choices'] as $key => $value) {
                    $options[] = [
                        'id' => $field['fid'] . '_' . $number++,
                        'key' => $key,
                        'value' => get_value_by_lang2($key, $value),
                        'checked' => ($key == $field['value']),
                    ];
                }
                $field['choices_options'] = $options;
            } elseif ($field['field_type'] == 'checkbox') {
                $valueold = empty($field['valueold']) ? [] : explode(',', $field['valueold']);
                $valuecheckbox = !empty($field['value']) ? explode(',', $field['value']) : [];
                $options = [];
                $number = 0;
                $valueold_labels = [];
                foreach ($field['field_choices'] as $key => $value) {
                    $options[] = [
                        'id' => $field['fid'] . '_' . $number++,
                        'key' => $key,
                        'value' => get_value_by_lang2($key, $value),
                        'checked' => in_array((string) $key, $valuecheckbox, true),
                    ];
                    if (in_array((string) $key, $valueold, true)) {
                        $valueold_labels[] = get_value_by_lang2($key, $value);
                    }
                }
                $field['choices_options'] = $options;
                $field['valueold'] = implode(', ', $valueold_labels);
            } elseif ($field['field_type'] == 'multiselect') {
                $valueold = empty($field['valueold']) ? [] : explode(',', $field['valueold']);
                $valueselect = !empty($field['value']) ? explode(',', $field['value']) : [];
                $options = [];
                $valueold_labels = [];
                foreach ($field['field_choices'] as $key => $value) {
                    $options[] = [
                        'key' => $key,
                        'value' => get_value_by_lang2($key, $value),
                        'selected' => in_array((string) $key, $valueselect, true),
                    ];
                    if (in_array((string) $key, $valueold, true)) {
                        $valueold_labels[] = get_value_by_lang2($key, $value);
                    }
                }
                $field['choices_options'] = $options;
                $field['valueold'] = implode(', ', $valueold_labels);
            } elseif ($field['field_type'] == 'file') {
                $filelist = !empty($field['value']) ? array_map('trim', explode(',', $field['value'])) : [];
                $old = !empty($field['valueold']) ? array_map('trim', explode(',', $field['valueold'])) : [];
                $all = array_unique(array_merge($filelist, $old));
                $all_files = [];
                foreach ($all as $file_item) {
                    $finfo = file_type_name($file_item);
                    $finfo['checked'] = !empty($filelist) && in_array($file_item, $filelist, true);
                    $finfo['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;userfile=' . $file_item;
                    $all_files[] = $finfo;
                }
                $limited_values = !empty($field['limited_values']) ? json_decode($field['limited_values'], true) : [];
                $field['all_files'] = $all_files;
                $field['fileaccept'] = !empty($limited_values['mime']) ? '.' . implode(',.', $limited_values['mime']) : '';
                $field['filemaxsize'] = $limited_values['file_max_size'] ?? 0;
                $field['filemaxsize_format'] = nv_convertfromBytes($limited_values['file_max_size'] ?? 0);
                $field['filemaxnum'] = $limited_values['maxnum'] ?? 0;
                $field['csrf'] = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $field['field']);
                $field['widthlimit'] = image_size_info($limited_values['widthlimit'] ?? '', 'width');
                $field['heightlimit'] = image_size_info($limited_values['heightlimit'] ?? '', 'height');
                $field['hide_addfile'] = !(empty($limited_values['maxnum']) or (count($filelist) < $limited_values['maxnum']));
                $field['valueold'] = !empty($old) ? '<p>' . implode('</p><p>', $old) . '</p>' : '';
            }

            $tpl_custom_fields[] = $field;
            $have_custom_fields = true;
        }
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('editcensor_review.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('REVIEWUID', $reviewuid);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('HAVE_NAME_FIELD', $have_name_field);
    $tpl->assign('HAVE_BASIC', !empty($info_basic));
    $tpl->assign('HAVE_CUSTOM', $have_custom_fields and !empty($info_custom));
    $tpl->assign('BASIC_FIELDS', $tpl_basic_fields);
    $tpl->assign('CUSTOM_FIELDS', $tpl_custom_fields);
    $tpl->assign('VIEW_MAIL_OLD', empty($custom_fields_old['view_mail']) ? $nv_Lang->getGlobal('no') : $nv_Lang->getGlobal('yes'));
    $tpl->assign('VIEW_MAIL_NEW', !empty($custom_fields['view_mail']));
    $contents = $tpl->fetch('editcensor_review.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => 'tb2.userid',
        'value' => $nv_Lang->getModule('search_id'),
        'selected' => ''
    ],
    'username' => [
        'key' => 'username',
        'sql' => 'tb2.username',
        'value' => $nv_Lang->getModule('search_account'),
        'selected' => ''
    ],
    'full_name' => [
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(tb2.last_name,' ',tb2.first_name)" : "concat(tb2.first_name,' ',tb2.last_name)",
        'value' => $nv_Lang->getModule('search_name'),
        'selected' => ''
    ],
    'email' => [
        'key' => 'email',
        'sql' => 'tb2.email',
        'value' => $nv_Lang->getModule('search_mail'),
        'selected' => ''
    ]
];
$method = $nv_Request->isset_request('method', 'post') ? $nv_Request->get_string('method', 'post', '') : ($nv_Request->isset_request('method', 'get') ? urldecode($nv_Request->get_string('method', 'get', '')) : '');
$methodvalue = $nv_Request->isset_request('value', 'post') ? $nv_Request->get_string('value', 'post') : ($nv_Request->isset_request('value', 'get') ? urldecode($nv_Request->get_string('value', 'get', '')) : '');

$orders = [
    'userid',
    'username',
    'full_name',
    'email',
    'lastedit'
];
$orderby = $nv_Request->get_string('sortby', 'get', '');
$ordertype = $nv_Request->get_string('sorttype', 'get', '');
if ($ordertype != 'ASC') {
    $ordertype = 'DESC';
}

$db->sqlreset()
    ->select('COUNT(tb1.userid)')
    ->from(NV_MOD_TABLE . '_edit tb1, ' . NV_MOD_TABLE . ' tb2');

$where = [];
$where[] = 'tb1.userid=tb2.userid';
if (!empty($global_config['idsite'])) {
    $where[] = 'idsite=' . $global_config['idsite'];
}
if (!empty($method) and isset($methods[$method]) and !empty($methodvalue)) {
    $base_url .= '&amp;method=' . urlencode($method) . '&amp;value=' . urlencode($methodvalue);
    $methods[$method]['selected'] = ' selected="selected"';
    $table_caption = $nv_Lang->getModule('search_page_title');
    $where[] = $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
}

$db->where(implode(' AND ', $where));
$page = $nv_Request->get_page('page', 'get', 1);
$per_page = 20;

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('tb1.userid, tb1.lastedit, tb2.username, tb2.first_name, tb2.last_name, tb2.email')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

if (!empty($orderby) and in_array($orderby, $orders, true)) {
    $orderby_sql = $orderby != 'full_name' ? (($orderby != 'lastedit' ? 'tb2.' : 'tb1.') . $orderby) : ($global_config['name_show'] == 0 ? "concat(tb2.first_name,' ',tb2.last_name)" : "concat(tb2.last_name,' ',tb2.first_name)");
    $db->order($orderby_sql . ' ' . $ordertype);
    $base_url .= '&amp;sortby=' . $orderby . '&amp;sorttype=' . $ordertype;
}

$result = $db->query($db->sql());

$users_list = [];
while ($row = $result->fetch()) {
    $users_list[$row['userid']] = [
        'userid' => $row['userid'],
        'username' => $row['username'],
        'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username']),
        'email' => $row['email'],
        'lastedit' => nv_datetime_format($row['lastedit'])
    ];
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = [];
$head_tds['userid']['title'] = $nv_Lang->getModule('userid');
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $nv_Lang->getGlobal('username');
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $nv_Lang->getModule('name');
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $nv_Lang->getModule('email');
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=email&amp;sorttype=ASC';
$head_tds['lastedit']['title'] = $nv_Lang->getModule('editcensor_lastedit');
$head_tds['lastedit']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=lastedit&amp;sorttype=ASC';

foreach ($orders as $order) {
    if ($orderby == $order and $ordertype == 'ASC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=' . $order . '&amp;sorttype=DESC';
        $head_tds[$order]['title'] .= ' &darr;';
    } elseif ($orderby == $order and $ordertype == 'DESC') {
        $head_tds[$order]['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=' . $order . '&amp;sorttype=ASC';
        $head_tds[$order]['title'] .= ' &uarr;';
    }
}

// Xác định admin của site
$array_admin = [];
$sql = 'SELECT admin_id, lev FROM ' . NV_AUTHORS_GLOBALTABLE;
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $array_admin[$row['admin_id']] = $row['lev'];
}

// Bổ sung checkss, allow, view_link cho từng user
foreach ($users_list as $uid => $u) {
    $u['allow'] = !isset($array_admin[$u['userid']]) || $u['userid'] == $admin_info['userid'] || $array_admin[$u['userid']] > $admin_info['level'];
    $u['checkss'] = csrf_create($csrf_key);
    $u['view_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;reviewuid=' . $u['userid'];
    $users_list[$uid] = $u;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('editcensor.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('IS_FORUM', defined('NV_IS_USER_FORUM'));
$tpl->assign('METHODS', $methods);
$tpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$tpl->assign('TABLE_CAPTION', $table_caption);
$tpl->assign('HEAD_TDS', $head_tds);
$tpl->assign('USERS_LIST', $users_list);
$tpl->assign('GENERATE_PAGE', $generate_page);
$contents = $tpl->fetch('editcensor.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
