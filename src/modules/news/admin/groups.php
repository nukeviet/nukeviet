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

$page_title = $nv_Lang->getModule('block');

// Thay đổi thứ tự nhóm tin
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $bid = $nv_Request->get_int('bid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid= :bid');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $numrows = $stmt->fetchColumn();
    if ($numrows != 1 || $new_weight < 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $stmt_result = $db->prepare('SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid!= :bid ORDER BY weight ASC');
    $stmt_result->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt_result->execute();
    
    $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET weight= :weight WHERE bid= :bid');
    $weight = 0;
    while ($_row = $stmt_result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':bid', $_row['bid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt_result->closeCursor();
    
    $stmt_update->bindValue(':weight', $new_weight, PDO::PARAM_INT);
    $stmt_update->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt_update->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_blockcat', 'block_catid ' . $bid, $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Thay đổi trạng thái mặc định khi tạo bài viết
if ($nv_Request->isset_request('changeadddefault', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $bid = $nv_Request->get_int('bid', 'post', 0);
    $new_val = ($nv_Request->get_int('new_val', 'post', 0) == 1) ? 1 : 0;
    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET adddefault= :new_val WHERE bid= :bid');
    $stmt->bindValue(':new_val', $new_val, PDO::PARAM_INT);
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_blockcat', 'block_catid ' . $bid . ' adddefault=' . $new_val, $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Thay đổi số lượng liên kết hiển thị
if ($nv_Request->isset_request('changenumlinks', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $bid = $nv_Request->get_int('bid', 'post', 0);
    $new_val = $nv_Request->get_int('new_val', 'post', 0);
    if ($new_val < 1 || $new_val > 30) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }
    $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET numbers= :new_val WHERE bid= :bid');
    $stmt->bindValue(':new_val', $new_val, PDO::PARAM_INT);
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_blockcat', 'block_catid ' . $bid . ' numbers=' . $new_val, $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa nhóm tin
if ($nv_Request->isset_request('delete', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $bid = $nv_Request->get_int('bid', 'post', 0);

    $stmt = $db->prepare('SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid= :bid');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $bid_check = $stmt->fetchColumn();
    if (empty($bid_check)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_blockcat', 'block_catid ' . $bid, $admin_info['userid']);
    $stmt = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid= :bid');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()) {
        $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid= :bid');
        $stmt_del->bindValue(':bid', $bid, PDO::PARAM_INT);
        $stmt_del->execute();
        nv_fix_block_cat();
        $nv_Cache->delMod($module_name);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => ''
        ]);
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => ''
    ]);
}

// Lưu nhóm tin (thêm mới hoặc cập nhật)
if ($nv_Request->isset_request('savecat', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $bid = $nv_Request->get_int('bid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '');
    $keywords = $nv_Request->get_title('keywords', 'post', '');
    $alias = $nv_Request->get_title('alias', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_htmlspecialchars(strip_tags($description)), '<br />');
    $alias = ($alias == '') ? get_mod_alias($title, 'blockcat', $bid) : get_mod_alias($alias, 'blockcat', $bid);

    $image = $nv_Request->get_string('image', 'post', '');
    if (!empty($image)) {
        if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload) === true) {
            $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
            $image = substr($image, $lu);
        } else {
            $image = '';
        }
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_name'),
            'input' => 'title'
        ]);
    }

    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE (title=:title OR alias=:alias)' . ($bid ? ' AND bid!= :bid' : '');
    $sth = $db->prepare($sql);
    $sth->bindValue(':title', $title, PDO::PARAM_STR);
    $sth->bindValue(':alias', $alias, PDO::PARAM_STR);
    if ($bid) {
        $sth->bindValue(':bid', $bid, PDO::PARAM_INT);
    }
    $sth->execute();
    if ($sth->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorexists'),
            'input' => 'title'
        ]);
    }

    if ($bid == 0) {
        $weight = (int) $db->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat')->fetchColumn() + 1;
        $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat (adddefault, numbers, title, alias, description, image, weight, keywords, add_time, edit_time) VALUES (0, 4, :title, :alias, :description, :image, :weight, :keywords, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')');
        $stmt_ins->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt_ins->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt_ins->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt_ins->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt_ins->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_ins->bindValue(':keywords', $keywords, PDO::PARAM_STR);
        if ($stmt_ins->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_blockcat', ' ', $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET title=:title, alias=:alias, description=:description, image=:image, keywords=:keywords, edit_time=' . NV_CURRENTTIME . ' WHERE bid= :bid');
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);
        $stmt->bindValue(':keywords', $keywords, PDO::PARAM_STR);
        $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
        if ($stmt->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_blockcat', 'block_catid ' . $bid, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('errorsave')
    ]);
}

// Tải dữ liệu form thêm/sửa
$item = [
    'bid' => 0,
    'title' => '',
    'alias' => '',
    'description' => '',
    'image' => '',
    'keywords' => ''
];
$is_edit = false;

$bid = $nv_Request->get_int('bid', 'get', 0);
if ($bid > 0) {
    $stmt = $db->prepare('SELECT bid, title, alias, description, image, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat WHERE bid= :bid');
    $stmt->bindValue(':bid', $bid, PDO::PARAM_INT);
    $stmt->execute();
    $_row = $stmt->fetch();
    $stmt->closeCursor();
    if (!empty($_row)) {
        $item = $_row;
        $item['description'] = nv_htmlspecialchars(nv_br2nl($item['description']));
        if (!empty($item['image']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $item['image'])) {
            $item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['image'];
        } else {
            $item['image'] = '';
        }
        $is_edit = true;
    }
}

// Query danh sách nhóm tin
$num_groups = (int) $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat')->fetchColumn();
$groups_array = [];
if ($num_groups > 0) {
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC');
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid= :bid');
    
    while ($_row = $result->fetch()) {
        $stmt->bindValue(':bid', $_row['bid'], PDO::PARAM_INT);
        $stmt->execute();
        $_row['numnews'] = (int) $stmt->fetchColumn();
        $groups_array[] = $_row;
    }
    $result->closeCursor();
}

$nv_Lang->setGlobal('title_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 65));
$nv_Lang->setGlobal('description_suggest_max', $nv_Lang->getGlobal('length_suggest_max', 160));

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('groups.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('ITEM', $item);
$tpl->assign('GROUPS', $groups_array);
$tpl->assign('NUM_GROUPS', $num_groups);
$tpl->assign('NUMLINKS_OPTIONS', range(1, 30));

$contents = $tpl->fetch('groups.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
