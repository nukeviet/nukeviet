<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('categories');

// Thay đổi thứ tự
if ($nv_Request->isset_request('new_weight', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit($nv_Lang->getGlobal('error_checkss'));
    }

    $catid = $nv_Request->get_int('catid', 'post', 0);

    $stmt = $db->prepare('SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = :catid');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt->execute();
    $catid = $stmt->fetchColumn();
    if (empty($catid)) {
        exit('NO_' . $catid);
    }

    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
    if (empty($new_weight)) {
        exit('NO_' . $module_name);
    }

    $stmt = $db->prepare('SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid != :catid ORDER BY weight ASC');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt->execute();

    $stmt_update = $db->prepare('UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight = :weight WHERE catid = :catid');

    $weight = 0;
    while ($row = $stmt->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }

        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':catid', $row['catid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();

    $stmt_update2 = $db->prepare('UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight = :weight WHERE catid = :catid');
    $stmt_update2->bindValue(':weight', $new_weight, PDO::PARAM_INT);
    $stmt_update2->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt_update2->execute();

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $catid;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xóa danh mục
if ($nv_Request->isset_request('delcat', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit($nv_Lang->getGlobal('error_checkss'));
    }

    $catid = $nv_Request->get_int('catid', 'post', 0);

    $stmt = $db->prepare('SELECT catid, is_system FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = :catid');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($row) or $row['is_system']) {
        exit('NO_' . $catid);
    }

    $stmt = $db->prepare('DELETE FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = :catid');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);

    if ($stmt->execute() and $stmt->rowCount()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete cat', 'ID: ' . $catid, $admin_info['userid']);

        $sql = 'SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;

        $stmt_update = $db->prepare('UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight = :weight WHERE catid = :catid');

        while ($row = $result->fetch()) {
            ++$weight;
            $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update->bindValue(':catid', $row['catid'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $result->closeCursor();

        $stmt_update = $db->prepare('UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' SET catid = 0 WHERE catid = :catid');
        $stmt_update->bindValue(':catid', $catid, PDO::PARAM_INT);
        $stmt_update->execute();

        $nv_Cache->delMod($module_name);
    } else {
        exit('NO_' . $catid);
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $catid;
    include NV_ROOTDIR . '/includes/footer.php';
}

$data = [];
$error = '';

$catid = $nv_Request->get_int('catid', 'post,get', 0);

if (!empty($catid)) {
    $stmt = $db->prepare('SELECT catid, status, ' . NV_LANG_DATA . '_title title FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = :catid');
    $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($data)) {
        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
        nv_redirect_location($url);
    }
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;catid=' . $catid;
    $caption = $nv_Lang->getModule('categories_edit');
} else {
    $data = [
        'catid' => 0,
        'title' => '',
        'status' => 1
    ];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $caption = $nv_Lang->getModule('categories_add');
}

if ($nv_Request->isset_request('saveform', 'post')) {
    $data['title'] = $nv_Request->get_title('title', 'post', '');
    $data['status'] = (int) $nv_Request->get_bool('status', 'post', false);

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        $error = $nv_Lang->getGlobal('error_checkss');
    } elseif (empty($data['title'])) {
        $error = $nv_Lang->getModule('categories_error_title');
    } else {
        if (!$catid) {
            // Kiểm tra trùng lặp trên tất cả các ngôn ngữ khi thêm mới
            $sql_or = [];
            foreach ($global_config['setup_langs'] as $lang) {
                $sql_or[] = $lang . '_title = :' . $lang . '_title';
            }
            $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE ' . implode(' OR ', $sql_or);
            $sth = $db->prepare($sql);
            foreach ($global_config['setup_langs'] as $lang) {
                $sth->bindParam(':' . $lang . '_title', $data['title'], PDO::PARAM_STR);
            }
        } else {
            // Kiểm tra trùng lặp trên ngôn ngữ hiện tại khi sửa
            $sql = 'SELECT * FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE ' . NV_LANG_DATA . '_title = :title AND catid != ' . $catid;
            $sth = $db->prepare($sql);
            $sth->bindParam(':title', $data['title'], PDO::PARAM_STR);
        }
        $sth->execute();
        $num = $sth->fetchColumn();

        if (!empty($num)) {
            $error = $nv_Lang->getModule('categories_error_exists');
        } else {
            if (!$catid) {
                $sql = 'SELECT MAX(weight) weight FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories';
                $result = $db->query($sql);
                $weight = $result->fetch();
                $result->closeCursor();
                $weight = $weight['weight'] + 1;

                $field_title = $field_value = '';
                foreach ($global_config['setup_langs'] as $lang) {
                    $field_title .= ', ' . $lang . '_title';
                    $field_value .= ', :' . $lang . '_title';
                }

                $sql = 'INSERT INTO ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories (
                    time_add, time_update, weight, is_system, status' . $field_title . '
                ) VALUES (
                    ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $weight . ', 0, ' . $data['status'] . $field_value . '
                )';
            } else {
                $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET
                    time_update = ' . NV_CURRENTTIME . ',
                    status=' . $data['status'] . ',
                    ' . NV_LANG_DATA . '_title=:' . NV_LANG_DATA . '_title
                WHERE catid = ' . $catid;
            }

            try {
                $sth = $db->prepare($sql);
                if (!$catid) {
                    foreach ($global_config['setup_langs'] as $lang) {
                        $sth->bindParam(':' . $lang . '_title', $data['title'], PDO::PARAM_STR);
                    }
                } else {
                    $sth->bindParam(':' . NV_LANG_DATA . '_title', $data['title'], PDO::PARAM_STR);
                }
                $sth->execute();

                if ($sth->rowCount()) {
                    if ($catid) {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit cat', 'ID: ' . $catid, $admin_info['userid']);
                    } else {
                        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add cat', ' ', $admin_info['userid']);
                    }

                    $nv_Cache->delMod($module_name);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                } else {
                    $error = $nv_Lang->getModule('errorsave');
                }
            } catch (Throwable $e) {
                trigger_error($e);
                $error = $e->getMessage();
            }
        }
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', $form_action);
$tpl->assign('BASE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('DATA', $data);
$tpl->assign('CAPTION', $caption);
$tpl->assign('LIST', $global_array_cat);
$tpl->assign('LISTCOUNT', count($global_array_cat));
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ERROR', $error);

$contents = $tpl->fetch('categories.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
