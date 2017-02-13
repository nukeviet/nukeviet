<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['question'];

// Sua cau hoi
if ($nv_Request->isset_request('edit', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);

    if (empty($title)) {
        die('NO');
    }
    $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . "_question SET
		title= :title, edit_time=" . NV_CURRENTTIME . "
		WHERE qid=" . $qid . " AND lang='" . NV_LANG_DATA . "'");

    $stmt->bindParam(':title', $title, PDO::PARAM_STR, strlen($title));
    if ($stmt->execute()) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['savequestion'], 'id: '. $qid . '; ' .$title);
        die('OK');
    }
    die('NO');
}

// Them cau hoi
if ($nv_Request->isset_request('add', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $title = $nv_Request->get_title('title', 'post', '', 1);
    if (empty($title)) {
        die('NO');
    }

    $sql = "SELECT MAX(weight) FROM " . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "'";
    $weight = $db->query($sql)->fetchColumn();
    $weight = intval($weight) + 1;
    $_sql = "INSERT INTO " . NV_MOD_TABLE . "_question
		(title, lang, weight, add_time, edit_time) VALUES
		( :title, '" .  NV_LANG_DATA  . "', " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";

    $data_insert = array();
    $data_insert['title'] = $title;
    if ($db->insert_id($_sql, 'qid', $data_insert)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['addquestion'], $title);
        die('OK');
    }
    die('NO'.$_sql);
}

// Chinh thu tu
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

    $query = "SELECT COUNT(*) FROM " . NV_MOD_TABLE . "_question WHERE qid=" . $qid . " AND lang='" . NV_LANG_DATA . "'";
    $numrows = $db->query($query)->fetchColumn();
    if ($numrows != 1) {
        die('NO');
    }

    $query = "SELECT qid FROM " . NV_MOD_TABLE . "_question WHERE qid!=" . $qid . " AND lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
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
    die('OK');
}

// Xoa cau hoi
if ($nv_Request->isset_request('del', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $qid = $nv_Request->get_int('qid', 'post', 0);

    list($qid, $title) = $db->query('SELECT qid, title FROM ' . NV_MOD_TABLE . '_question WHERE qid=' . $qid)->fetch(3);

    if ($qid) {
        $sql = 'DELETE FROM ' . NV_MOD_TABLE . '_question WHERE qid=' . $qid;
        if ($db->exec($sql)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['deletequestion'], 'id: '. $qid . '; ' .$title);

            // fix weight question
            $sql = "SELECT qid FROM " . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $sql = 'UPDATE ' . NV_MOD_TABLE . '_question SET weight=' . $weight . ' WHERE qid=' . $row['qid'];
                $db->query($sql);
            }
            $result->closeCursor();
            die('OK');
        }
    }
    die('NO');
}

$xtpl = new XTemplate('question.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

// Danh sach cau hoi
if ($nv_Request->isset_request('qlist', 'post')) {
    if (! defined('NV_IS_AJAX')) {
        die('Wrong URL');
    }

    $sql = "SELECT * FROM " . NV_MOD_TABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
    $_rows = $db->query($sql)->fetchAll();
    $num = sizeof($_rows);
    if ($num) {
        foreach ($_rows as $row) {
            $xtpl->assign('ROW', array(
                'qid' => $row['qid'],
                'title' => $row['title']
            ));

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ));
                $xtpl->parse('main.data.loop.weight');
            }

            $xtpl->parse('main.data.loop');
        }

        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->parse('load');
$contents = $xtpl->text('load');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
