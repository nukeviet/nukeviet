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

    $stmt = $db->prepare('SELECT id FROM ' . NV_MOD_TABLE . '_supporter WHERE id != :skip_id AND departmentid = :departmentid ORDER BY weight ASC');
    $stmt->bindValue(':skip_id', $skip_id, PDO::PARAM_INT);
    $stmt->bindValue(':departmentid', $departmentid, PDO::PARAM_INT);
    $stmt->execute();
    $weight = 0;
    $res = [];
    while ($row = $stmt->fetch()) {
        ++$weight;
        if ($weight == $skip_weight) {
            ++$weight;
        }
        $res[$row['id']] = 'WHEN id = ' . $row['id'] . ' THEN ' . $weight;
    }
    if (!empty($res)) {
        $in = implode(',', array_keys($res));
        $when = implode(' ', $res);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_supporter SET weight = CASE ' . $when . ' ELSE weight END WHERE id IN (' . $in . ')');
        $stmt->execute();
    }
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if ($nv_Request->isset_request('fc', 'post')) {
    $fc = $nv_Request->get_string('fc', 'post', '');
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    // Thay đổi thứ tự
    if ($fc == 'change_weight') {
        $id = $nv_Request->get_int('id', 'post', 0);
        $new_weight = $nv_Request->get_int('nw', 'post', 0);

        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $supporter = $stmt->fetch();
        $stmt->closeCursor();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Unspecified Supporter'
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_SUPPORTER_WEIGHT', 'ID: ' . $id . ', W: ' . $new_weight, $admin_info['userid']);
        supporter_fix_weight($supporter['departmentid'], $id, $new_weight);
        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_supporter SET weight = :weight WHERE id = :id');
        $stmt->bindValue(':weight', $new_weight, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }

    // Thêm/Sửa nhân viên hỗ trợ
    if ($fc == 'content') {
        $id = $nv_Request->get_int('id', 'post', 0);
        if (!empty($id)) {
            $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $supporter = $stmt->fetch();
            $stmt->closeCursor();
            if (!$supporter) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('error_code_11')
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
                    'mess' => $nv_Lang->getModule('error_required_departmentid'),
                    'input' => 'departmentid'
                ]);
            }

            if (nv_strlen($post['full_name']) < 3) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_required_full_name'),
                    'input' => 'full_name'
                ]);
            }

            if (nv_strlen($post['phone']) < 6) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('error_required_phone'),
                    'input' => 'phone'
                ]);
            }

            $check_email = nv_check_valid_email($post['email'], true);
            $post['email'] = $check_email[1];
            if (!empty($post['email']) and $check_email[0] != '') {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $check_email[0],
                    'input' => 'email'
                ]);
            }

            if (!empty($post['image']) and nv_is_file($post['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $size = getimagesize(NV_DOCUMENT_ROOT . $post['image']);
                if (empty($size[0]) or $size[0] < 100 or $size[0] > 300 or $size[0] != $size[1]) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('supporter_avatar_note'),
                        'input' => 'image'
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
            $post['others'] = !empty($others) ? json_encode($others, NV_JSON_ENCODE) : '';

            if (empty($id)) {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_ADD_SUPPORTER', 'NAME: ' . $post['full_name'], $admin_info['userid']);

                $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_supporter WHERE departmentid = :departmentid');
                $stmt->bindValue(':departmentid', $post['departmentid'], PDO::PARAM_INT);
                $stmt->execute();
                $weight = (int) $stmt->fetchColumn() + 1;
                $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_supporter (departmentid, full_name, image, phone, email, others, weight) VALUES (:departmentid, :full_name, :image, :phone, :email, :others, :weight)');
                $old_departmentid = 0;
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_EDIT_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $post['full_name'], $admin_info['userid']);

                $old_departmentid = (int) $supporter['departmentid'];
                if ($post['departmentid'] == $supporter['departmentid']) {
                    $weight = (int) $supporter['weight'];
                } else {
                    $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_supporter WHERE departmentid = :departmentid');
                    $stmt->bindValue(':departmentid', $post['departmentid'], PDO::PARAM_INT);
                    $stmt->execute();
                    $weight = (int) $stmt->fetchColumn() + 1;
                }
                $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_supporter SET departmentid = :departmentid, full_name = :full_name, image = :image, phone = :phone, email = :email, others = :others, weight = :weight WHERE id = :id');
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->bindValue(':departmentid', $post['departmentid'], PDO::PARAM_INT);
            $stmt->bindValue(':full_name', $post['full_name'], PDO::PARAM_STR);
            $stmt->bindValue(':image', $post['image'], PDO::PARAM_STR);
            $stmt->bindValue(':phone', $post['phone'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $post['email'], PDO::PARAM_STR);
            $stmt->bindValue(':others', $post['others'], PDO::PARAM_STR);
            $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
            $exc = $stmt->execute();
            if ($exc) {
                if (!empty($old_departmentid) and $old_departmentid != $post['departmentid']) {
                    supporter_fix_weight($old_departmentid);
                }

                $nv_Cache->delMod($module_name);
                nv_jsonOutput([
                    'status' => 'OK',
                    'mess' => $nv_Lang->getGlobal('save_success'),
                    'refresh' => true
                ]);
            } else {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getGlobal('error_code_11')
                ]);
            }
        } else {
            $supporter = array_merge([
                'id' => 0,
                'departmentid' => 0,
                'full_name' => '',
                'image' => '',
                'phone' => '',
                'email' => '',
                'others' => ''
            ], $supporter);

            if (!empty($supporter['image']) and nv_is_file(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $supporter['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $supporter['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $supporter['image'];
            } else {
                $supporter['image'] = '';
            }

            if (!empty($supporter['others'])) {
                $supporter['others'] = json_decode($supporter['others'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $supporter['others'] = unserialize($supporter['others'], NV_UNSERIALIZE_SAFE);
                }
            }

            if (empty($supporter['others']) or !is_array($supporter['others'])) {
                $supporter['others'] = ['' => ''];
            }

            $department_options = [[
                'id' => 0,
                'full_name' => $nv_Lang->getModule('department_empty')
            ]];
            foreach ($departments as $department) {
                $department_options[] = [
                    'id' => (int) $department['id'],
                    'full_name' => $department['full_name']
                ];
            }

            $other_contacts = [];
            foreach ($supporter['others'] as $name => $value) {
                $other_contacts[] = [
                    'name' => $name,
                    'value' => $value
                ];
            }

            $tpl = new \NukeViet\Template\NVSmarty();
            $tpl->setTemplateDir(get_module_tpl_dir('supporter-content.tpl'));
            $tpl->assign('LANG', $nv_Lang);
            $tpl->assign('MODULE_NAME', $module_name);
            $tpl->assign('OP', $op);
            $tpl->assign('CHECKSS', csrf_create($csrf_key));
            $tpl->assign('SUPPORTER', $supporter);
            $tpl->assign('DEPARTMENT_OPTIONS', $department_options);
            $tpl->assign('OTHER_CONTACTS', $other_contacts);
            $tpl->assign('MODULE_UPLOAD', $module_upload);

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
        $id = $nv_Request->get_int('id', 'post', 0);

        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $supporter = $stmt->fetch();
        $stmt->closeCursor();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_DEL_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $supporter['full_name'], $admin_info['userid']);

        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_supporter WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        supporter_fix_weight($supporter['departmentid']);
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }

    // Thay đổi trạng thái
    if ($fc == 'change_act') {
        $id = $nv_Request->get_int('id', 'post', 0);

        $stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_supporter WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $supporter = $stmt->fetch();
        $stmt->closeCursor();
        if (!$supporter) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_code_11')
            ]);
        }

        $new_status = !empty($supporter['act']) ? 0 : 1;

        nv_insert_logs(NV_LANG_DATA, $module_name, 'LOG_STATUS_SUPPORTER', 'ID: ' . $id . ', NAME: ' . $supporter['full_name'], $admin_info['userid']);

        $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_supporter SET act = :act WHERE id = :id');
        $stmt->bindValue(':act', $new_status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success')
        ]);
    }
}

// Hiển thị danh sách nhân viên hỗ trợ
$supporters = get_supporter_list();

$departments = [0 => [
    'id' => 0,
    'full_name' => $nv_Lang->getModule('department_empty')
]] + get_department_list();

$department_counts = [];
foreach ($supporters as $supporter) {
    $departmentid = (int) $supporter['departmentid'];
    $department_counts[$departmentid] = ($department_counts[$departmentid] ?? 0) + 1;
}

$department_groups = [];
foreach ($supporters as $supporter) {
    $departmentid = (int) $supporter['departmentid'];
    $department_info = $departments[$departmentid] ?? [
        'id' => $departmentid,
        'full_name' => $nv_Lang->getModule('department_not_exist') . ' #' . $departmentid
    ];

    if (!isset($department_groups[$departmentid])) {
        $department_groups[$departmentid] = [
            'id' => $departmentid,
            'full_name' => $department_info['full_name'],
            'supporters' => []
        ];
    }

    $weight_options = [];
    for ($i = 1; $i <= ($department_counts[$departmentid] ?? 0); ++$i) {
        $weight_options[] = [
            'value' => $i,
            'title' => str_pad((string) $i, 2, '0', STR_PAD_LEFT)
        ];
    }

    $department_groups[$departmentid]['supporters'][] = [
        'id' => (int) $supporter['id'],
        'full_name' => $supporter['full_name'],
        'phone' => $supporter['phone'],
        'email' => $supporter['email'],
        'act' => !empty($supporter['act']) ? 1 : 0,
        'weight' => (int) $supporter['weight'],
        'weight_options' => $weight_options
    ];
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('supporter.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('DEPARTMENT_OP_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=department');
$tpl->assign('DEPARTMENT_CHECKSS', csrf_create($admin_info['admin_id'] . '_' . $module_name . '_department'));
$tpl->assign('OP_URL', $page_url);
$tpl->assign('DEPARTMENT_GROUPS', array_values($department_groups));

$contents = $tpl->fetch('supporter.tpl');

$page_title = $nv_Lang->getModule('supporter');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
