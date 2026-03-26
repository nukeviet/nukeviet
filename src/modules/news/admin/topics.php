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

$page_title = $nv_Lang->getModule('topics');

// Thay đổi thứ tự dòng sự kiện
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $topicid = $nv_Request->get_int('topicid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    $numrows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . $topicid)->fetchColumn();
    if ($numrows != 1 || $new_weight < 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $result = $db->query('SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid!=' . $topicid . ' ORDER BY weight ASC');
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topics SET weight=' . $weight . ' WHERE topicid=' . $row['topicid']);
    }
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topics SET weight=' . $new_weight . ' WHERE topicid=' . $topicid);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_topic', 'topicid ' . $topicid, $admin_info['userid']);
    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => ''
    ]);
}

// Xóa dòng sự kiện
if ($nv_Request->isset_request('delete', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }
    $topicid = $nv_Request->get_int('topicid', 'post', 0);
    $force = $nv_Request->get_int('force', 'post', 0);

    $row_topic = $db->query('SELECT topicid, image FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . $topicid)->fetch();
    if (empty($row_topic)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => ''
        ]);
    }

    $_rows = $db->query('SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid=' . $topicid)->fetchAll();
    $check_rows = count($_rows);

    if ($check_rows > 0 && !$force) {
        nv_jsonOutput([
            'status' => 'confirm',
            'mess' => $nv_Lang->getModule('deltopic_msg_rows', $check_rows)
        ]);
    }

    if ($check_rows > 0) {
        foreach ($_rows as $row) {
            $arr_catid = explode(',', $row['listcatid']);
            foreach ($arr_catid as $catid_i) {
                // try/catch để có vấn đề về CSDL cũng hoạt động được
                try {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET topicid=0 WHERE id=' . $row['id']);
                } catch (Throwable $e) {
                    trigger_error($e);
                }
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid=0 WHERE id=' . $row['id']);
        }
    }

    if ($db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . $topicid)) {
        nv_fix_topic();
        if (is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $row_topic['image'])) {
            nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/topics/' . $row_topic['image']);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_topic', 'topicid ' . $topicid, $admin_info['userid']);
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

// Lưu dòng sự kiện (thêm mới hoặc cập nhật)
if ($nv_Request->isset_request('savecat', 'post')) {
    if (!csrf_check($nv_Request->get_title('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $topicid = $nv_Request->get_int('topicid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);
    $keywords = $nv_Request->get_title('keywords', 'post', '', 1);
    $alias = $nv_Request->get_title('alias', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');
    $description = nv_nl2br(nv_substr(nv_htmlspecialchars(strip_tags($description)), 0, 250), '<br />');

    // Xử lý ảnh minh họa
    $image = $nv_Request->get_title('homeimg', 'post', '');
    if (!nv_is_url($image) and nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload . '/topics')) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/topics/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('topics_error_title'),
            'input' => 'title'
        ]);
    }

    $alias = ($alias == '') ? get_mod_alias($title, 'topics', $topicid) : get_mod_alias($alias, 'topics', $topicid);

    // Kiểm tra trùng tiêu đề hoặc alias
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE (title=:title OR alias=:alias)' . ($topicid ? ' AND topicid!=' . $topicid : '');
    $sth = $db->prepare($sql);
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn()) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorexists'),
            'input' => 'title'
        ]);
    }

    if ($topicid == 0) {
        $weight = (int) $db->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics')->fetchColumn() + 1;
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_topics (title, alias, description, image, weight, keywords, add_time, edit_time) VALUES (:title, :alias, :description, :image, :weight, :keywords, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
        $data_insert = [
            'title' => $title,
            'alias' => $alias,
            'description' => $description,
            'image' => $image,
            'weight' => $weight,
            'keywords' => $keywords
        ];
        if ($db->insert_id($sql, 'topicid', $data_insert)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_topic', ' ', $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true)
            ]);
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topics SET title=:title, alias=:alias, description=:description, image=:image, keywords=:keywords, edit_time=' . NV_CURRENTTIME . ' WHERE topicid=' . $topicid);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        if ($stmt->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_topic', 'topicid ' . $topicid, $admin_info['userid']);
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
    'topicid' => 0,
    'title' => '',
    'alias' => '',
    'image' => '',
    'description' => '',
    'keywords' => ''
];
$is_edit = false;

$topicid = $nv_Request->get_int('topicid', 'get', 0);
if ($topicid > 0) {
    $row = $db->query('SELECT topicid, title, alias, image, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid=' . $topicid)->fetch();
    if (!empty($row)) {
        $item = $row;
        if (!empty($item['image']) and is_file(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/topics/' . $item['image'])) {
            $item['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/topics/' . $item['image'];
        }
        $is_edit = true;
    }
}

// Query danh sách dòng sự kiện
$page = $nv_Request->get_page('page', 'get', 1);
$per_page = $module_config[$module_name]['per_page'];
$num_topics = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics')->fetchColumn();

$topics_array = [];
if ($num_topics > 0) {
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $result = $db_slave->query($db_slave->sql());
    while ($row = $result->fetch()) {
        $row['numnews'] = (int) $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE topicid=' . $row['topicid'])->fetchColumn();
        $topics_array[] = $row;
    }
    $result->closeCursor();
}

$pagination = '';
if ($num_topics > $per_page) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    $pagination = nv_generate_page($base_url, $num_topics, $per_page, $page);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('topics.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('IS_EDIT', $is_edit);
$tpl->assign('ITEM', $item);
$tpl->assign('TOPICS', $topics_array);
$tpl->assign('NUM_TOPICS', $num_topics);
$tpl->assign('PAGINATION', $pagination);

$contents = $tpl->fetch('topics.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
