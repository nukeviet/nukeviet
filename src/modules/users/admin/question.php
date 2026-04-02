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

$page_title = $nv_Lang->getModule('question');

// Sua cau hoi
if ($nv_Request->isset_request('edit', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '');

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('question') . ' ' . $nv_Lang->getGlobal('empty_data_error')
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_question SET title = :title, edit_time = :edit_time WHERE qid = :qid AND lang = :lang');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);

    if ($stmt->execute()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('savequestion'), 'id: ' . $qid . '; ' . $title, $admin_info['userid']);
        nv_jsonOutput([
            'status' => 'success',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'refresh' => true
        ]);
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_save')
    ]);
}

// Them cau hoi
if ($nv_Request->isset_request('add', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $title = $nv_Request->get_title('title', 'post', '');
    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('question') . ' ' . $nv_Lang->getGlobal('empty_data_error')
        ]);
    }

    $stmt = $db->prepare('SELECT MAX(weight) FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang');
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    $weight = (int) $stmt->fetchColumn();
    $weight = $weight + 1;

    $stmt = $db->prepare('INSERT INTO ' . NV_MOD_TABLE . '_question (title, lang, weight, add_time, edit_time) VALUES (:title, :lang, :weight, :add_time, :edit_time)');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt->bindValue(':add_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->bindValue(':edit_time', NV_CURRENTTIME, PDO::PARAM_INT);

    if ($stmt->execute()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('addquestion'), $title, $admin_info['userid']);
        nv_jsonOutput([
            'status' => 'success',
            'mess' => $nv_Lang->getGlobal('add_success'),
            'refresh' => true
        ]);
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_save')
    ]);
}

// Chinh thu tu
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_question WHERE qid = :qid AND lang = :lang');
    $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    $numrows = $stmt->fetchColumn();
    if ($numrows != 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_submit')
        ]);
    }

    $stmt = $db->prepare('SELECT qid FROM ' . NV_MOD_TABLE . '_question WHERE qid != :qid AND lang = :lang ORDER BY weight ASC');
    $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    $weight = 0;
    $stmt_update = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_question SET weight = :weight WHERE qid = :qid');
    while ($row = $stmt->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt_update->bindValue(':qid', $row['qid'], PDO::PARAM_INT);
        $stmt_update->execute();
    }
    $stmt->closeCursor();

    $stmt_update->bindValue(':weight', $new_vid, PDO::PARAM_INT);
    $stmt_update->bindValue(':qid', $qid, PDO::PARAM_INT);
    $stmt_update->execute();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('changeweight'), 'qid: ' . $qid, $admin_info['userid']);
    nv_jsonOutput([
        'status' => 'success',
        'mess' => '',
        'refresh' => true
    ]);
}

// Xoa cau hoi
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }

    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);

    $stmt = $db->prepare('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE qid = :qid');
    $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    $stmt->closeCursor();

    if ($res) {
        $qid   = $res['qid'];
        $title = $res['title'];
        $stmt = $db->prepare('DELETE FROM ' . NV_MOD_TABLE . '_question WHERE qid = :qid');
        $stmt->bindValue(':qid', $qid, PDO::PARAM_INT);
        if ($stmt->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('deletequestion'), 'id: ' . $qid . '; ' . $title, $admin_info['userid']);

            // fix weight question
            $stmt = $db->prepare('SELECT qid FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang ORDER BY weight ASC');
            $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
            $stmt->execute();
            $weight = 0;
            $stmt_update = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_question SET weight = :weight WHERE qid = :qid');
            while ($row = $stmt->fetch()) {
                ++$weight;
                $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
                $stmt_update->bindValue(':qid', $row['qid'], PDO::PARAM_INT);
                $stmt_update->execute();
            }
            $stmt->closeCursor();

            nv_jsonOutput([
                'status' => 'success',
                'mess' => $nv_Lang->getGlobal('delete_success'),
                'refresh' => true
            ]);
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getGlobal('error_delete')
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('question.tpl'));
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('LANG', $nv_Lang);

// Load danh sách câu hỏi
$stmt = $db->prepare('SELECT * FROM ' . NV_MOD_TABLE . '_question WHERE lang = :lang ORDER BY weight ASC');
$stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
$stmt->execute();
$_rows = $stmt->fetchAll();
$num = count($_rows);

$array_questions = [];
if ($num) {
    foreach ($_rows as $row) {
        $weights = [];
        for ($i = 1; $i <= $num; ++$i) {
            $weights[] = [
                'key' => $i,
                'title' => $i,
                'selected' => $i == $row['weight']
            ];
        }

        $array_questions[] = [
            'qid' => $row['qid'],
            'title' => $row['title'],
            'weights' => $weights
        ];
    }
}

$tpl->assign('DATA', $array_questions);
$contents = $tpl->fetch('question.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
