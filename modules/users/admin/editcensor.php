<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $table_caption = $lang_module['editcensor'];

// Hủy bỏ thông tin chỉnh sửa
if ($nv_Request->isset_request('del', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

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

    if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $userid) {
        $allow = false;
    }

    if (!$allow) {
        nv_htmlOutput('ERROR');
    }

    $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid;
    $db->exec($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Denied User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_htmlOutput('OK');
}

// Xác nhận thông tin chỉnh sửa
if ($nv_Request->isset_request('approved', 'post')) {
    $userid = $nv_Request->get_int('userid', 'post', 0);

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

    if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $userid) {
        $allow = false;
    }

    if (!$allow) {
        nv_jsonOutput([
            'status' => 'ERROR',
            'mess' => 'Not allowed!!!',
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
                    $custom_fields[$fkey] = empty($custom_fields[$fkey]) ? '' : date('d/m/Y', $custom_fields[$fkey]);
                } elseif ($field['field_type'] == 'checkbox' or $field['field_type'] == 'multiselect') {
                    $custom_fields[$fkey] = empty($custom_fields[$fkey]) ? [] : explode(',', $custom_fields[$fkey]);
                }
            }
        }

        unset($array_field_config['question'], $array_field_config['answer']);
        $query_field = [];
        if (!empty($array_field_config)) {
            require NV_ROOTDIR . '/modules/users/fields.check.php';
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
            $db->query('UPDATE ' . NV_MOD_TABLE . '_info SET ' . implode(', ', $query_field) . ' WHERE userid=' . $userid);
        }
    }

    // Xóa thông tin chỉnh sửa
    $db->query('DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $userid);

    $nv_Cache->delMod($module_name);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Approved User Edit', 'Userid: ' . $userid, $admin_info['userid']);
    nv_jsonOutput(['status' => 'SUCCESS']);
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

    if ($global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $reviewuid) {
        $allow = false;
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
    $custom_fields_old = array_merge($custom_fields_old, $row_info);
    $custom_fields = array_merge($custom_fields, $row_info);
    $custom_fields = array_merge($custom_fields, $info_custom);

    // Xác nhận duyệt thông tin chỉnh sửa
    if ($nv_Request->isset_request('confirm', 'post')) {
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
            $custom_fields['birthday'] = empty($custom_fields['birthday']) ? '' : date('d/m/Y', $custom_fields['birthday']);
        }

        // Kiểm tra các trường dữ liệu tùy biến + Hệ thống
        $query_field = [];
        $userid = $reviewuid;
        if (!empty($array_field_config)) {
            require NV_ROOTDIR . '/modules/users/fields.check.php';
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
            $db->query('UPDATE ' . NV_MOD_TABLE . '_info SET ' . implode(', ', $query_field) . ' WHERE userid=' . $reviewuid);
        }

        // Xóa thông tin chỉnh sửa
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_edit WHERE userid=' . $reviewuid);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Log Approved User Edit', 'Userid: ' . $reviewuid, $admin_info['userid']);
        $nv_Cache->delMod($module_name);

        nv_jsonOutput([
            'status' => 'ok',
            'input' => '',
            'admin_add' => 'no',
            'mess' => ''
        ]);
    }

    $xtpl = new XTemplate('editcensor_review.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('REVIEWUID', $reviewuid);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;reviewuid=' . $reviewuid);

    $page_title .= ': ' . $row_basic['username'];

    $have_custom_fields = false;
    $have_name_field = false;
    foreach ($array_field_config as $row) {
        $row['value'] = (isset($custom_fields[$row['field']])) ? $custom_fields[$row['field']] : $row['default_value'];
        $row['valueold'] = (isset($custom_fields_old[$row['field']])) ? $custom_fields_old[$row['field']] : '';
        $row['required'] = ($row['required']) ? 'required' : '';

        $xtpl->assign('FIELD', $row);

        // Các trường hệ thống xuất độc lập
        if (!empty($row['system'])) {
            if ($row['field'] == 'birthday') {
                $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                $row['valueold'] = (empty($row['valueold'])) ? '' : date('d/m/Y', $row['valueold']);
            } elseif ($row['field'] == 'sig') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
            }
            $xtpl->assign('FIELD', $row);
            if ($row['field'] == 'first_name' or $row['field'] == 'last_name') {
                $show_key = 'name_show_' . $global_config['name_show'] . '.show_' . $row['field'];
                $have_name_field = true;
            } else {
                $show_key = 'show_' . $row['field'];
            }
            if ($row['required']) {
                $xtpl->parse('main.basic.' . $show_key . '.required');
            }
            if ($row['field'] == 'gender') {
                $xtpl->assign('GENDER_OLD', isset($global_array_genders[$row['valueold']]) ? $global_array_genders[$row['valueold']]['title'] : '');
                foreach ($global_array_genders as $gender) {
                    $gender['selected'] = $row['value'] == $gender['key'] ? ' selected="selected"' : '';
                    $xtpl->assign('GENDER', $gender);
                    $xtpl->parse('main.basic.' . $show_key . '.gender');
                }
            }
            if ($row['description']) {
                $xtpl->parse('main.basic.' . $show_key . '.description');
            }
            $xtpl->parse('main.basic.' . $show_key);
        } else {
            if ($row['required']) {
                $xtpl->parse('main.custom.loop.required');
            }
            if ($row['description']) {
                $xtpl->parse('main.custom.loop.description');
            }
            if ($row['field_type'] == 'textbox' or $row['field_type'] == 'number') {
                $xtpl->parse('main.custom.loop.textbox');
            } elseif ($row['field_type'] == 'date') {
                $row['value'] = (empty($row['value'])) ? '' : date('d/m/Y', $row['value']);
                $row['valueold'] = (empty($row['valueold'])) ? '' : date('d/m/Y', $row['valueold']);
                $xtpl->assign('FIELD', $row);
                $xtpl->parse('main.custom.loop.date');
            } elseif ($row['field_type'] == 'textarea') {
                $row['value'] = nv_htmlspecialchars(nv_br2nl($row['value']));
                $xtpl->assign('FIELD', $row);
                $xtpl->parse('main.custom.loop.textarea');
            } elseif ($row['field_type'] == 'editor') {
                $row['value'] = htmlspecialchars(nv_editor_br2nl($row['value']));
                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                    $array_tmp = explode('@', $row['class']);
                    $edits = nv_aleditor('custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value']);
                    $xtpl->assign('EDITOR', $edits);
                    $xtpl->parse('main.custom.loop.editor');
                } else {
                    $row['class'] = '';
                    $xtpl->assign('FIELD', $row);
                    $xtpl->parse('main.custom.loop.textarea');
                }
            } elseif ($row['field_type'] == 'select') {
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'key' => $key,
                        'selected' => ($key == $row['value']) ? ' selected="selected"' : '',
                        'value' => $value
                    ]);
                    $xtpl->parse('main.custom.loop.select.loop');
                }
                $row['valueold'] = isset($row['field_choices'][$row['valueold']]) ? $row['field_choices'][$row['valueold']] : $row['valueold'];
                $xtpl->assign('FIELD', $row);
                $xtpl->parse('main.custom.loop.select');
            } elseif ($row['field_type'] == 'radio') {
                $number = 0;
                $row['valueold'] = isset($row['field_choices'][$row['valueold']]) ? $row['field_choices'][$row['valueold']] : $row['valueold'];
                $xtpl->assign('FIELD', $row);
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => ($key == $row['value']) ? ' checked="checked"' : '',
                        'value' => $value
                    ]);
                    $xtpl->parse('main.custom.loop.radio');
                }
            } elseif ($row['field_type'] == 'checkbox') {
                $valueold = empty($row['valueold']) ? [] : explode(',', $row['valueold']);
                $row['valueold'] = [];
                $number = 0;
                $valuecheckbox = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'id' => $row['fid'] . '_' . $number++,
                        'key' => $key,
                        'checked' => (in_array((string) $key, $valuecheckbox, true)) ? ' checked="checked"' : '',
                        'value' => $value
                    ]);
                    $xtpl->parse('main.custom.loop.checkbox');
                    if ((in_array((string) $key, $valueold, true))) {
                        $row['valueold'][] = $value;
                    }
                }
                $row['valueold'] = implode(', ', $row['valueold']);
                $xtpl->assign('FIELD', $row);
            } elseif ($row['field_type'] == 'multiselect') {
                $valueold = empty($row['valueold']) ? [] : explode(',', $row['valueold']);
                $row['valueold'] = [];
                $valueselect = (!empty($row['value'])) ? explode(',', $row['value']) : [];
                foreach ($row['field_choices'] as $key => $value) {
                    $xtpl->assign('FIELD_CHOICES', [
                        'key' => $key,
                        'selected' => (in_array((string) $key, $valueselect, true)) ? ' selected="selected"' : '',
                        'value' => $value
                    ]);
                    $xtpl->parse('main.custom.loop.multiselect.loop');
                    if (in_array((string) $key, $valueold, true)) {
                        $row['valueold'][] = $value;
                    }
                }
                $xtpl->parse('main.custom.loop.multiselect');
                $row['valueold'] = implode(', ', $row['valueold']);
                $xtpl->assign('FIELD', $row);
            }
            $xtpl->parse('main.custom.loop');
            $have_custom_fields = true;
        }
    }
    if ($have_name_field) {
        $xtpl->parse('main.basic.name_show_' . $global_config['name_show']);
    }
    if (!empty($info_basic)) {
        // Hiển thị email xuất riêng không theo trường dữ liệu quản lý
        $xtpl->assign('VIEW_MAIL_OLD', empty($custom_fields_old['view_mail']) ? $lang_global['no'] : $lang_global['yes']);
        $xtpl->assign('VIEW_MAIL_NEW', empty($custom_fields['view_mail']) ? '' : ' checked="checked"');
        $xtpl->parse('main.basic');
    }
    if ($have_custom_fields and !empty($info_custom)) {
        $xtpl->parse('main.custom');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
$methods = [
    'userid' => [
        'key' => 'userid',
        'sql' => 'tb2.userid',
        'value' => $lang_module['search_id'],
        'selected' => ''
    ],
    'username' => [
        'key' => 'username',
        'sql' => 'tb2.username',
        'value' => $lang_module['search_account'],
        'selected' => ''
    ],
    'full_name' => [
        'key' => 'full_name',
        'sql' => $global_config['name_show'] == 0 ? "concat(tb2.last_name,' ',tb2.first_name)" : "concat(tb2.first_name,' ',tb2.last_name)",
        'value' => $lang_module['search_name'],
        'selected' => ''
    ],
    'email' => [
        'key' => 'email',
        'sql' => 'tb2.email',
        'value' => $lang_module['search_mail'],
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
    $table_caption = $lang_module['search_page_title'];
    $where[] = $methods[$method]['sql'] . " LIKE '%" . $db->dblikeescape($methodvalue) . "%'";
}

$db->where(implode(' AND ', $where));
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 30;

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
        'lastedit' => date('d/m/Y H:i', $row['lastedit'])
    ];
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$head_tds = [];
$head_tds['userid']['title'] = $lang_module['userid'];
$head_tds['userid']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=userid&amp;sorttype=ASC';
$head_tds['username']['title'] = $lang_module['account'];
$head_tds['username']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=username&amp;sorttype=ASC';
$head_tds['full_name']['title'] = $lang_module['name'];
$head_tds['full_name']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=full_name&amp;sorttype=ASC';
$head_tds['email']['title'] = $lang_module['email'];
$head_tds['email']['href'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sortby=email&amp;sorttype=ASC';
$head_tds['lastedit']['title'] = $lang_module['editcensor_lastedit'];
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

$xtpl = new XTemplate('editcensor.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$xtpl->assign('SORTURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
$xtpl->assign('SEARCH_VALUE', nv_htmlspecialchars($methodvalue));
$xtpl->assign('TABLE_CAPTION', $table_caption);

if (defined('NV_IS_USER_FORUM')) {
    $xtpl->parse('main.is_forum');
}

foreach ($methods as $m) {
    $xtpl->assign('METHODS', $m);
    $xtpl->parse('main.method');
}

foreach ($head_tds as $head_td) {
    $xtpl->assign('HEAD_TD', $head_td);
    $xtpl->parse('main.head_td');
}

foreach ($users_list as $u) {
    $xtpl->assign('CONTENT_TD', $u);

    // Kiểm duyệt tài khoản thành viên hoặc chính bản thân hoặc admin cấp thấp hơn
    // Không có quyền kiểm duyệt admin đồng cấp hoặc cấp cao hơn
    if (!isset($array_admin[$u['userid']]) or $u['userid'] == $admin_info['userid'] or $array_admin[$u['userid']] > $admin_info['level']) {
        $xtpl->assign('VIEW_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;reviewuid=' . $u['userid']);
        $xtpl->parse('main.xusers.allowed');
        $xtpl->parse('main.xusers.user_link');
    } else {
        $xtpl->parse('main.xusers.user_text');
    }

    $xtpl->parse('main.xusers');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
