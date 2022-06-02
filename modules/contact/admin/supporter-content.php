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

$row = [];
$error = [];

$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['supporter_add'] = $lang_module['supporter_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
} else {
    $row['id'] = 0;
    $row['full_name'] = '';
    $row['image'] = '';
    $row['phone'] = '';
    $row['email'] = '';
    $row['others'] = '';
    $row['departmentid'] = $nv_Request->get_int('departmentid', 'post,get', 0);
}

if ($nv_Request->isset_request('save', 'post')) {
    $row['departmentid'] = $nv_Request->get_int('departmentid', 'post', 0);
    $row['full_name'] = $nv_Request->get_title('full_name', 'post', '');
    $row['image'] = $nv_Request->get_title('image', 'post', '');
    if (is_file(NV_DOCUMENT_ROOT . $row['image'])) {
        $row['image'] = substr($row['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
    } else {
        $row['image'] = '';
    }
    $row['phone'] = $nv_Request->get_title('phone', 'post', '');
    $row['email'] = $nv_Request->get_title('email', 'post', '');
    $row['others'] = $nv_Request->get_array('others', 'post', '');

    $check_email = nv_check_valid_email($row['email'], true);
    $row['email'] = $check_email[1];

    if (!empty($row['others'])) {
        foreach ($row['others'] as $index => $value) {
            if (empty($value['name']) or empty($value['value'])) {
                unset($row['others'][$index]);
            }
        }
        $row['others'] = serialize($row['others']);
    } else {
        $row['others'] = '';
    }

    if (empty($row['departmentid'])) {
        $error[] = $lang_module['error_required_departmentid'];
    } elseif (empty($row['full_name'])) {
        $error[] = $lang_module['error_required_full_name'];
    } elseif (empty($row['phone'])) {
        $error[] = $lang_module['error_required_phone'];
    } elseif (!empty($row['email']) and $check_email[0] != '') {
        $error[] = $check_email[0];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_supporter (departmentid, full_name, image, phone, email, others, weight) VALUES (:departmentid, :full_name, :image, :phone, :email, :others, :weight)');

                $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_supporter WHERE departmentid=' . $row['departmentid'])->fetchColumn();
                $weight = (int) $weight + 1;
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_supporter SET departmentid = :departmentid, full_name = :full_name, image = :image, phone = :phone, email = :email, others = :others WHERE id=' . $row['id']);
            }
            $stmt->bindParam(':departmentid', $row['departmentid'], PDO::PARAM_INT);
            $stmt->bindParam(':full_name', $row['full_name'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $row['image'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $row['phone'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $row['email'], PDO::PARAM_STR);
            $stmt->bindParam(':others', $row['others'], PDO::PARAM_STR, strlen($row['others']));

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=supporter&departmentid=' . $row['departmentid']);
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$sql = 'SELECT id, full_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department';
$array_department = $nv_Cache->db($sql, 'id', $module_name);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

if (!empty($array_department)) {
    foreach ($array_department as $department) {
        $department['selected'] = $department['id'] == $row['departmentid'] ? 'selected="selected"' : '';
        $xtpl->assign('DEPARTMENT', $department);
        $xtpl->parse('main.department');
    }
}

if (empty($row['others'])) {
    $row['others'] = [];
    $row['others'][] = ['name' => '', 'value' => ''];
} else {
    $row['others'] = unserialize($row['others']);
}

foreach ($row['others'] as $index => $others) {
    $others['index'] = $index;
    $xtpl->assign('OTHERS', $others);
    $xtpl->parse('main.others');
}
$xtpl->assign('COUNT', sizeof($row['others']));

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['supporter_add'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
