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

$page_title = $nv_Lang->getModule('sources');

// Thay đổi thứ tự nguồn tin
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $sourceid = $nv_Request->get_int('sourceid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    $numrows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid)->fetchColumn();
    if ($numrows != 1 || $new_weight < 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $result = $db->query('SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid!=' . $sourceid . ' ORDER BY weight ASC');
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight=' . $weight . ' WHERE sourceid=' . $row['sourceid']);
    }
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight=' . $new_weight . ' WHERE sourceid=' . $sourceid);

    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa nguồn tin
if ($nv_Request->isset_request('delete', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $sourceid = $nv_Request->get_int('sourceid', 'post', 0);

    $row = $db->query('SELECT sourceid, title, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid)->fetch();
    if (empty($row)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $logo_old = $row['logo'];

    // Cập nhật bài viết tham chiếu đến nguồn này
    $result = $db->query('SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE sourceid = ' . $sourceid);
    while ($art = $result->fetch()) {
        $arr_catid = explode(',', $art['listcatid']);
        foreach ($arr_catid as $catid_i) {
            // Sử dụng try/catch để đảm bảo có vấn đề vẫn xóa thành công
            try {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET sourceid = 0 WHERE id =' . $art['id']);
            } catch (Throwable $e) {
                trigger_error($e);
            }
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET sourceid = 0 WHERE id =' . $art['id']);
    }
    $result->closeCursor();

    $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid);

    // Xóa logo nếu không còn nguồn nào khác dùng
    if (!empty($logo_old)) {
        $_count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE logo =' . $db->quote(basename($logo_old)))->fetchColumn();
        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/source/' . $logo_old);

            $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old)))->fetchColumn();
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($logo_old)));
        }
    }

    nv_fix_source();
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_source', $row['title'], $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Lưu nguồn tin (thêm mới hoặc cập nhật)
if ($nv_Request->isset_request('savecat', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $sourceid = $nv_Request->get_int('sourceid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '');
    $link = strtolower($nv_Request->get_title('link', 'post', ''));

    $url_info = parse_url($link);
    if (isset($url_info['scheme']) and isset($url_info['host'])) {
        $link = $url_info['scheme'] . '://' . $url_info['host'];
    } else {
        $link = '';
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_name'),
            'input' => 'title'
        ]);
    }

    // Xử lý logo
    $logo_old = $sourceid > 0 ? $db->query('SELECT logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid =' . $sourceid)->fetchColumn() : '';

    $logo = $nv_Request->get_title('logo', 'post', '');
    if (!nv_is_url($logo) and nv_is_file($logo, NV_UPLOADS_DIR . '/' . $module_upload . '/source')) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/source/');
        $logo = substr($logo, $lu);
    } elseif (!nv_is_url($logo) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/source/' . $logo_old)) {
        $logo = $logo_old;
    } else {
        $logo = '';
    }

    if (($logo != $logo_old) and !empty($logo_old)) {
        $_count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid != ' . $sourceid . ' AND logo =' . $db->quote(basename($logo_old)))->fetchColumn();
        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/source/' . $logo_old);

            $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old)))->fetchColumn();
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($logo_old)));
        }
    }

    if ($sourceid == 0) {
        $weight = (int) $db->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn() + 1;
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_sources (title, link, logo, weight, add_time, edit_time) VALUES (:title, :link, :logo, :weight, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
        $data_insert = [
            'title' => $title,
            'link' => $link,
            'logo' => $logo,
            'weight' => $weight
        ];
        if ($db->insert_id($sql, 'sourceid', $data_insert)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_source', ' ', $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET title=:title, link=:link, logo=:logo, edit_time=' . NV_CURRENTTIME . ' WHERE sourceid=' . $sourceid);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        if ($stmt->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_source', 'sourceid ' . $sourceid, $admin_info['userid']);
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

// Tải dữ liệu nguồn tin đang sửa
$item = [
    'sourceid' => 0,
    'title' => '',
    'link' => 'http://',
    'logo' => ''
];
$is_edit = false;

$sourceid = $nv_Request->get_int('sourceid', 'get', 0);
if ($sourceid > 0) {
    $row = $db->query('SELECT sourceid, title, link, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid=' . $sourceid)->fetch();
    if (!empty($row)) {
        $item = $row;
        if (!empty($item['logo'])) {
            $item['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $item['logo'];
        }
        $is_edit = true;
    }
}

// Query danh sách nguồn tin
$per_page = 20;
$page = $nv_Request->get_page('page', 'get', 1);
$num = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();

$array = [];
if ($num > 0) {
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_sources')
        ->order('weight')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $array[] = $row;
    }
    $result->closeCursor();
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
$num_items = $num > 1 ? $num : 1;
$pagination = nv_generate_page($base_url, $num_items, $per_page, $page);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('sources.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('ITEM', $item);
$tpl->assign('SOURCES', $array);
$tpl->assign('NUM_SOURCES', $num);
$tpl->assign('PAGINATION', $pagination);

$contents = $tpl->fetch('sources.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
