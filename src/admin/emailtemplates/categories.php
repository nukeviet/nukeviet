<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('categories');

// Thay đổi thứ tự
if ($nv_Request->isset_request('changeweight', 'post')) {
    $catid = $nv_Request->get_int('catid', 'post', 0);

    $sql = 'SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid=' . $catid;
    $catid = $db->query($sql)->fetchColumn();
    if (empty($catid))
        die('NO_' . $catid);

    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
    if (empty($new_weight))
        die('NO_' . $module_name);

    $sql = 'SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid!=' . $catid . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++ $weight;
        if ($weight == $new_weight)
            ++ $weight;

        $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight=' . $new_weight . ' WHERE catid=' . $catid;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $catid;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Xóa danh mục
if ($nv_Request->isset_request('delete', 'post')) {
    $catid = $nv_Request->get_int('catid', 'post', 0);

    $sql = 'SELECT catid, is_system FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid=' . $catid;
    $row = $db->query($sql)->fetch();

    if (empty($row) or $row['is_system'])
        die('NO_' . $catid);

    $sql = 'DELETE FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = ' . $catid;

    if ($db->exec($sql)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete cat', 'ID: ' . $catid, $admin_info['userid']);

        $sql = 'SELECT catid FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;

        while ($row = $result->fetch()) {
            ++ $weight;
            $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET weight=' . $weight . ' WHERE catid=' . $row['catid'];
            $db->query($sql);
        }

        $db->query('UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' SET catid = 0 WHERE catid =' . $catid);

        $nv_Cache->delMod($module_name);
    } else {
        die('NO_' . $catid);
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $catid;
    include NV_ROOTDIR . '/includes/footer.php';
}

$data = [];
$error = '';

$catid = $nv_Request->get_int('catid', 'post,get', 0);

if (!empty($catid)) {
    $sql = 'SELECT catid, ' . NV_LANG_DATA . '_title title FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories WHERE catid = ' . $catid;
    $result = $db->query($sql);
    $data = $result->fetch();

    if (empty($data)) {
        $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
        nv_redirect_location($url);
    }
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;catid=' . $catid;
    $caption = $nv_Lang->getModule('categories_edit');
} else {
    $data = [
        'catid' => 0,
        'title' => ''
    ];
    $form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
    $caption = $nv_Lang->getModule('categories_add');
}

if ($nv_Request->isset_request('submit', 'post')) {
    $data['title'] = $nv_Request->get_title('title', 'post', '', true);

    if (empty($data['title'])) {
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
                $weight = $weight['weight'] + 1;

                $field_title = $field_value = '';
                foreach ($global_config['setup_langs'] as $lang) {
                    $field_title .= ', ' . $lang . '_title';
                    $field_value .= ', :' . $lang . '_title';
                }

                $sql = 'INSERT INTO ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories (time_add, time_update, weight, is_system' . $field_title . ') VALUES (
                    ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . $weight . ', 0' . $field_value . '
                )';
            } else {
                $sql = 'UPDATE ' . NV_EMAILTEMPLATES_GLOBALTABLE . '_categories SET time_update = ' . NV_CURRENTTIME . ', ' . NV_LANG_DATA . '_title=:' . NV_LANG_DATA . '_title WHERE catid = ' . $catid;
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
            } catch (PDOException $e) {
                $error = $nv_Lang->getModule('errorsave');
            }
        }
    }
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', $form_action);
$tpl->assign('BASE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
$tpl->assign('DATA', $data);
$tpl->assign('CAPTION', $caption);
$tpl->assign('LIST', $global_array_cat);
$tpl->assign('LISTCOUNT', sizeof($global_array_cat));
$tpl->assign('ERROR', $error);

$contents = $tpl->fetch('categories.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
