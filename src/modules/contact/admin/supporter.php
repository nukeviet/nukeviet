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

/**
 * @param int $departmentid
 * @param int $skip_id
 * @param int $skip_weight
 */
function supporter_fix_weight($departmentid, $skip_id = 0, $skip_weight = 0)
{
    global $db;

    $sql = 'SELECT id FROM ' . NV_MOD_TABLE . '_supporter WHERE id != ' . $skip_id . ' AND departmentid = ' . $departmentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    $res = [];
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $skip_weight) {
            ++$weight;
        }
        $res[$row['id']] = 'WHEN id = ' . $row['id'] . ' THEN ' . $weight;
    }
    if (!empty($res)) {
        $in = implode(',', array_keys($res));
        $when = implode(' ', $res);
        $db->query('UPDATE ' . NV_MOD_TABLE . '_supporter SET weight = CASE ' . $when . ' ELSE weight END WHERE id in (' . $in . ')');
    }
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;

if ($nv_Request->isset_request('fc', 'post')) {
    $fc = $nv_Request->get_string('fc', 'post', '');
    // Thay đổi thứ tự
    if ($fc == 'change_weight') {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Session error'
            ]);
        }

        $id = $nv_Request->get_int('id', 'post', 0);
        $new_weight = $nv_Request->get_int('nw', 'post', 0);

        $supporter = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id=' . $id)->fetch();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Unspecified Supporter'
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_SUPPORTER_WEIGHT', 'ID: ' . $id . ', W: ' . $new_weight, $admin_info['userid']);
        supporter_fix_weight($supporter['departmentid'], $id, $new_weight);
        $db->query('UPDATE ' . NV_MOD_TABLE . '_supporter SET weight=' . $new_weight . ' WHERE id=' . $id);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    // Thêm/Sửa nhân viên hỗ trợ
    if ($fc == 'content') {
        $id = $nv_Request->get_int('id', 'post', 0);
        if (!empty($id)) {
            $supporter = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id=' . $id)->fetch();
            if (!$supporter) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Unspecified Supporter'
                ]);
            }
        } else {
            $supporter = [
                'id' => 0,
                'departmentid' => 0,
                'full_name' => '',
                'image' => '',
                'phone' => '',
                'email' => '',
                'others' => '',
                'act' => 1,
                'weight' => 0
            ];
        }

        $departments = get_department_list();

        if ($nv_Request->isset_request('save', 'post')) {
            $checkss = $nv_Request->get_title('checkss', 'post', '');
            if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Session error'
                ]);
            }

            $post = [
                'departmentid' => $nv_Request->get_int('departmentid', 'post', 0),
                'full_name' => $nv_Request->get_title('full_name', 'post', ''),
                'image' => $nv_Request->get_title('image', 'post', ''),
                'phone' => $nv_Request->get_title('phone', 'post', ''),
                'email' => $nv_Request->get_title('email', 'post', ''),
                'others' => []
            ];

            if (!empty($post['departmentid']) and !isset($departments[$post['departmentid']])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_required_departmentid')
                ]);
            }

            if (nv_strlen($post['full_name']) < 3) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_required_full_name')
                ]);
            }

            if (nv_strlen($post['phone']) < 6) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_required_phone')
                ]);
            }

            $check_email = nv_check_valid_email($post['email'], true);
            $post['email'] = $check_email[1];
            if (!empty($post['email']) and $check_email[0] != '') {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $check_email[0]
                ]);
            }

            if (is_file(NV_DOCUMENT_ROOT . $post['image'])) {
                $size = getimagesize(NV_DOCUMENT_ROOT . $post['image']);
                if (empty($size[0]) or $size[0] < 100 or $size[0] > 300 or $size[0] != $size[1]) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('supporter_avatar_note')
                    ]);
                }
                $post['image'] = substr($post['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
            } else {
                $post['image'] = '';
            }

            $other_name = $nv_Request->get_typed_array('other_name', 'post', 'title', []);
            $other_value = $nv_Request->get_typed_array('other_value', 'post', 'title', []);
            $others = [];
            if (!empty($other_name)) {
                foreach ($other_name as $key => $name) {
                    if (!empty($name) and !empty($other_value[$key])) {
                        $others[$name] = $other_value[$key];
                    }
                }
            }
            $post['others'] = !empty($others) ? json_encode($others) : '';
            try {
                if (empty($id)) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_SUPPORTER', 'NAME: ' . $post['full_name'], $admin_info['userid']);

                    $weight = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_supporter WHERE departmentid=' . $post['departmentid'])->fetchColumn();
                    $weight = (int) $weight + 1;
                    $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_supporter (departmentid, full_name, image, phone, email, others, weight) VALUES (' . $post['departmentid'] . ', :full_name, :image, :phone, :email, :others, ' . $weight . ')');
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $post['full_name'], $admin_info['userid']);

                    if ($post['departmentid'] == $supporter['departmentid']) {
                        $weight = (int) $supporter['weight'];
                    } else {
                        $weight = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_supporter WHERE departmentid=' . $post['departmentid'])->fetchColumn();
                        $weight = (int) $weight + 1;
                        define('OLD_DEPARTMENTID', $supporter['departmentid']);
                    }
                    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_supporter SET departmentid = ' . $post['departmentid'] . ', full_name = :full_name, image = :image, phone = :phone, email = :email, others = :others, weight = ' . $weight . ' WHERE id=' . $id);
                }
                $stmt->bindParam(':full_name', $post['full_name'], PDO::PARAM_STR);
                $stmt->bindParam(':image', $post['image'], PDO::PARAM_STR);
                $stmt->bindParam(':phone', $post['phone'], PDO::PARAM_STR);
                $stmt->bindParam(':email', $post['email'], PDO::PARAM_STR);
                $stmt->bindParam(':others', $post['others'], PDO::PARAM_STR, strlen($post['others']));
                $exc = $stmt->execute();
                if ($exc) {
                    if (defined('OLD_DEPARTMENTID')) {
                        supporter_fix_weight(OLD_DEPARTMENTID);
                    }

                    $nv_Cache->delMod($module_name);
                    nv_jsonOutput([
                        'status' => 'OK'
                    ]);
                } else {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => 'An unknown error has occurred'
                    ]);
                }
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        } else {
            if (!empty($supporter['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $supporter['image'])) {
                $supporter['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $supporter['image'];
            } else {
                $supporter['image'] = '';
            }

            if (!empty($supporter['others'])) {
                $supporter['others'] = json_decode($supporter['others'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $supporter['others'] = unserialize($supporter['others']);
                }
            }
            if (empty($supporter['others'])) {
                $supporter['others'] = ['' => ''];
            }

            $tpl = new \NukeViet\Template\NVSmarty();
            $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
            $tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
            $tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
            $tpl->assign('FORM_ACTION', $page_url);
            $tpl->assign('SUPPORTER', $supporter);
            $tpl->assign('MODULE_UPLOAD', NV_UPLOADS_DIR . '/' . $module_upload);
            $tpl->assign('DEPARTMENTS', $departments);

            $contents = $tpl->fetch('supporter-content.tpl');
            nv_jsonOutput([
                'status' => 'OK',
                'title' => $id ? $nv_Lang->getModule('supporter_edit') : $nv_Lang->getModule('supporter_add'),
                'content' => $contents
            ]);
        }
    }

    // Xóa nhân viên hỗ trợ
    if ($fc == 'delete') {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Session error'
            ]);
        }

        $id = $nv_Request->get_int('id', 'post', 0);

        $supporter = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id=' . $id)->fetch();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Unspecified Supporter'
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DEL_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $supporter['full_name'], $admin_info['userid']);

        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_supporter  WHERE id = ' . $id);
        supporter_fix_weight($supporter['departmentid']);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }

    // Thay đổi trạng thái
    if ($fc == 'change_act') {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Session error'
            ]);
        }

        $id = $nv_Request->get_int('id', 'post', 0);

        $supporter = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id=' . $id)->fetch();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Unspecified Supporter'
            ]);
        }

        $new_status = !empty($supporter['act']) ? 0 : 1;

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_STATUS_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $supporter['full_name'], $admin_info['userid']);

        $db->query('UPDATE ' . NV_MOD_TABLE . '_supporter SET act=' . $new_status . ' WHERE id=' . $id);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK'
        ]);
    }
}

// Hiển thị danh sách nhân viên hỗ trợ
$supporters = get_supporter_list();

$departments = get_department_list();
$departments[0] = [
    'id' => 0,
    'full_name' => $nv_Lang->getModule('department_empty')
];

$list = [];
if (!empty($supporters)) {
    foreach ($supporters as $supporter) {
        empty($list[$supporter['departmentid']]) && $list[$supporter['departmentid']] = [];
        empty($departments[$supporter['departmentid']]['supporters']) && $departments[$supporter['departmentid']]['supporters'] = 0;
        // Đánh dấu trạng thái active
        $supporter['is_active'] = !empty($supporter['act']);
        $list[$supporter['departmentid']][] = $supporter;
        ++$departments[$supporter['departmentid']]['supporters'];
    }
}

// Chuẩn bị danh sách departments với supporters
$department_list = [];
foreach ($list as $department_id => $supporters_in_dept) {
    $dept_info = [
        'id' => $department_id,
        'full_name' => $departments[$department_id]['full_name'],
        'href' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=row&id=' . $department_id,
        'has_link' => !empty($department_id),
        'supporters' => [],
        'max_weight' => $departments[$department_id]['supporters']
    ];
    
    foreach ($supporters_in_dept as $supporter) {
        $dept_info['supporters'][] = $supporter;
    }
    
    $department_list[] = $dept_info;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$tpl->assign('OP_URL', $page_url);
$tpl->assign('DEPARTMENT_LIST', $department_list);
$tpl->assign('SHOW_FORM', empty($supporters));

$contents = $tpl->fetch($op . '.tpl');

$page_title = $nv_Lang->getModule('supporter');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
