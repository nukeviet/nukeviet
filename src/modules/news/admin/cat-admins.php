<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$catid = $nv_Request->get_int('catid', 'get,post', 0);
$cat_list_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat';
if (!isset($global_array_cat[$catid])) {
    nv_redirect_location($cat_list_url);
}
$cat_list_url .= '&parentid=' . $global_array_cat[$catid]['parentid'];

// Không tự phân quyền cho chính mình
$cat_admin_userids = array_values(array_diff($array_cat_admin_userids, [(int) $admin_id]));
$array_catids = array_map('intval', GetCatidInParent($catid));
$permission_keys = ['add_content', 'app_content', 'pub_content', 'edit_content', 'del_content', 'admin'];

// Lưu quyền hạn của các quản trị viên tại chuyên mục
if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $array_post = [];
    foreach ($permission_keys as $key) {
        $array_post[$key] = $nv_Request->get_typed_array($key, 'post', 'int', []);
    }

    $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins WHERE userid = :userid AND catid = :catid');
    $stmt_insert = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, catid, admin, add_content, pub_content, edit_content, del_content, app_content) VALUES (:userid, :catid, :admin, :add_content, :pub_content, :edit_content, :del_content, :app_content)');

    foreach ($cat_admin_userids as $userid) {
        $perms = [];
        foreach ($permission_keys as $key) {
            $perms[$key] = in_array($userid, $array_post[$key], true) ? 1 : 0;
        }
        if ($perms['admin']) {
            $perms = array_fill_keys($permission_keys, 1);
        }

        foreach ($array_catids as $catid_i) {
            $row = $perms;
            if ($catid_i != $catid) {
                // Chuyên mục con: chỉ cấp thêm quyền được cấp ở chuyên mục cha, giữ nguyên quyền đang có
                if (!array_sum($perms)) {
                    continue;
                }
                foreach ($permission_keys as $key) {
                    $row[$key] = max($row[$key], (int) ($array_cat_admin[$userid][$catid_i][$key] ?? 0));
                }
            }

            $stmt_del->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_del->bindValue(':catid', $catid_i, PDO::PARAM_INT);
            $stmt_del->execute();

            $stmt_insert->bindValue(':userid', $userid, PDO::PARAM_INT);
            $stmt_insert->bindValue(':catid', $catid_i, PDO::PARAM_INT);
            foreach ($permission_keys as $key) {
                $stmt_insert->bindValue(':' . $key, $row[$key], PDO::PARAM_INT);
            }
            $stmt_insert->execute();
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('cat_admins'), 'catid ' . $catid, $admin_info['userid']);
    $nv_Cache->delMod($module_name);

    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => $cat_list_url
    ]);
}

$users_list = [];
if (!empty($cat_admin_userids)) {
    $sql = 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN (' . implode(',', $cat_admin_userids) . ') ORDER BY username ASC';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $userid = (int) $row['userid'];
        $user = [
            'userid' => $userid,
            'username' => (string) $row['username'],
            'full_name' => nv_show_name_user($row['first_name'], $row['last_name'], $row['username'])
        ];
        foreach ($permission_keys as $key) {
            $user[$key] = (isset($array_cat_admin[$userid][$catid]) and $array_cat_admin[$userid][$catid][$key] == 1);
        }
        $users_list[] = $user;
    }
    $result->closeCursor();
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('cat-admins.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('CATID', $catid);
$tpl->assign('CAT_TITLE', $global_array_cat[$catid]['title']);
$tpl->assign('HAS_SUBCAT', count($array_catids) > 1);
$tpl->assign('USERS_LIST', $users_list);
$tpl->assign('CAT_LIST_URL', nv_htmlspecialchars($cat_list_url));

$contents = $tpl->fetch('cat-admins.tpl');

$page_title = $nv_Lang->getModule('cat_admins');
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
