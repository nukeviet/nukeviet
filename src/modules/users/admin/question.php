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
            'mess' => 'Wrong URL'
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);

    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('question') . ' ' . $nv_Lang->getGlobal('empty_data_error')
        ]);
    }

    $stmt = $db->prepare('UPDATE ' . NV_MOD_TABLE . '_question SET
        title= :title, edit_time=' . NV_CURRENTTIME . '
        WHERE qid=' . $qid . " AND lang='" . NV_LANG_DATA . "'");
    $stmt->bindParam(':title', $title, PDO::PARAM_STR, strlen($title));

    if ($stmt->execute()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('savequestion'), 'id: ' . $qid . '; ' . $title);
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
            'mess' => 'Wrong URL'
        ]);
    }

    $title = $nv_Request->get_title('title', 'post', '', 1);
    if (empty($title)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('question') . ' ' . $nv_Lang->getGlobal('empty_data_error')
        ]);
    }

    $sql = 'SELECT MAX(weight) FROM ' . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "'";
    $weight = $db->query($sql)->fetchColumn();
    $weight = (int)$weight + 1;
    $_sql = 'INSERT INTO ' . NV_MOD_TABLE . "_question
        (title, lang, weight, add_time, edit_time) VALUES
        ( :title, '" . NV_LANG_DATA . "', " . $weight . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';

    $data_insert = [];
    $data_insert['title'] = $title;
    if ($db->insert_id($_sql, 'qid', $data_insert)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('addquestion'), $title);
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
            'mess' => 'Wrong URL'
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

    $query = 'SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_question WHERE qid=' . $qid . " AND lang='" . NV_LANG_DATA . "'";
    $numrows = $db->query($query)->fetchColumn();
    if ($numrows != 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_submit')
        ]);
    }

    $query = 'SELECT qid FROM ' . NV_MOD_TABLE . '_question WHERE qid!=' . $qid . " AND lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $sql = 'UPDATE ' . NV_MOD_TABLE . '_question SET weight=' . $weight . ' WHERE qid=' . $row['qid'];
        $db->query($sql);
    }
    $sql = 'UPDATE ' . NV_MOD_TABLE . '_question SET weight=' . $new_vid . ' WHERE qid=' . $qid;
    $db->query($sql);

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('changeweight'), 'qid: ' . $qid);
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
            'mess' => 'Wrong URL'
        ]);
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);

    [$qid, $title] = $db->query('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE qid=' . $qid)->fetch(3);

    if ($qid) {
        $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_question WHERE qid=' . $qid;
        if ($db->exec($sql)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('deletequestion'), 'id: ' . $qid . '; ' . $title);

            // fix weight question
            $sql = 'SELECT qid FROM ' . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $sql = 'UPDATE ' . NV_MOD_TABLE . '_question SET weight=' . $weight . ' WHERE qid=' . $row['qid'];
                $db->query($sql);
            }
            $result->closeCursor();

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
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

// Load danh sách câu hỏi
$sql = 'SELECT * FROM ' . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
$_rows = $db->query($sql)->fetchAll();
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
