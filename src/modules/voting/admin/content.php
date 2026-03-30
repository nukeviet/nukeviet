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

$page_title = $nv_Lang->getModule('voting_edit');

$vid = $nv_Request->get_int('vid', 'post,get');
$groups_list = nv_groups_list();

if (!empty($vid)) {
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid = :vid');
    $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
    $stmt->execute();
    $exists = $stmt->fetchColumn();
    if (!$exists) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
}

if ($nv_Request->isset_request('save', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $question = $nv_Request->get_title('question', 'post', '');
    $link = $nv_Request->get_title('link', 'post', '');
    if (!empty($link) and !nv_is_url($link, true)) {
        $link = '';
    }

    $vote_one = $nv_Request->get_int('vote_one', 'post', 0) ? 1 : 0;
    $_groups_post = $nv_Request->get_typed_array('groups_view', 'post', 'int', []);
    $_groups_post = !empty($_groups_post) ? array_map('intval', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : [];

    if (!empty($_groups_post) and (in_array(5, $_groups_post, true) or in_array(6, $_groups_post, true))) {
        $vote_one = 0;
    }

    $groups_view = !empty($_groups_post) ? implode(',', $_groups_post) : '';

    $publ_date = $nv_Request->get_title('publ_date', 'post', '');
    $exp_date = $nv_Request->get_title('exp_date', 'post', '');
    $maxoption = $nv_Request->get_int('maxoption', 'post', 1);

    $array_answervote = $nv_Request->get_typed_array('answervote', 'post', 'title', []);
    $array_urlvote = $nv_Request->get_typed_array('urlvote', 'post', 'title', []);

    $answervotenews = $nv_Request->get_typed_array('answervotenews', 'post', 'title', []);
    $urlvotenews = $nv_Request->get_typed_array('urlvotenews', 'post', 'title', []);

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m)) {
        $phour = $nv_Request->get_int('phour', 'post', 0);
        $pmin = $nv_Request->get_int('pmin', 'post', 0);
        $begindate = mktime($phour, $pmin, 0, $m[2], $m[1], $m[3]);
    } else {
        $begindate = NV_CURRENTTIME;
    }
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m)) {
        $ehour = $nv_Request->get_int('ehour', 'post', 0);
        $emin = $nv_Request->get_int('emin', 'post', 0);
        $enddate = mktime($ehour, $emin, 0, $m[2], $m[1], $m[3]);
    } else {
        $enddate = 0;
    }

    $number_answer = 0;
    if (!empty($array_answervote)) {
        $keys = array_keys($array_answervote);
        foreach ($keys as $key) {
            $array_answervote[$key] = nv_htmlspecialchars($array_answervote[$key]);
            if (!empty($array_answervote[$key])) {
                if (!empty($array_urlvote[$key]) and !nv_is_url($array_urlvote[$key], true)) {
                    $array_urlvote[$key] = '';
                }
                ++$number_answer;
            }
        }
    }

    if (!empty($answervotenews)) {
        $keys = array_keys($answervotenews);
        foreach ($keys as $key) {
            $answervotenews[$key] = nv_htmlspecialchars($answervotenews[$key]);
            if (!empty($answervotenews[$key])) {
                if (!empty($urlvotenews[$key]) and !nv_is_url($urlvotenews[$key], true)) {
                    $urlvotenews[$key] = '';
                }
                ++$number_answer;
            }
        }
    }

    if ($maxoption > $number_answer or $maxoption <= 0) {
        $maxoption = $number_answer;
    }

    if (empty($question)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('voting_error_content'),
            'input' => 'question'
        ]);
    }

    if ($number_answer <= 1) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('voting_error_answer')
        ]);
    }

    $active_captcha = $nv_Request->get_int('active_captcha', 'post', 0) ? 1 : 0;

    if (empty($vid)) {
        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
            question, link, acceptcm, active_captcha, admin_id, groups_view, publ_time, exp_time, act, vote_one
        ) VALUES (
            :question, :link, :acceptcm, :active_captcha, :admin_id, :groups_view, 0, 0, 1, :vote_one
        )');
        $stmt->bindValue(':question', $question, PDO::PARAM_STR);
        $stmt->bindValue(':link', $link, PDO::PARAM_STR);
        $stmt->bindValue(':acceptcm', $maxoption, PDO::PARAM_INT);
        $stmt->bindValue(':active_captcha', $active_captcha, PDO::PARAM_INT);
        $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
        $stmt->bindValue(':groups_view', $groups_view, PDO::PARAM_STR);
        $stmt->bindValue(':vote_one', $vote_one, PDO::PARAM_INT);
        $stmt->execute();
        $vid = (int) $db->lastInsertId();
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('voting_add'), $question, $admin_info['userid']);
    }

    if ($vid > 0) {
        $maxoption_data = 0;
        $stmt_update_row = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET title = :title, url = :url WHERE id = :id AND vid = :vid');
        $stmt_delete_row = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id AND vid = :vid');

        foreach ($array_answervote as $id => $title) {
            if (!empty($title)) {
                $url = $array_urlvote[$id];
                $stmt_update_row->bindValue(':title', $title, PDO::PARAM_STR);
                $stmt_update_row->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt_update_row->bindValue(':id', (int) $id, PDO::PARAM_INT);
                $stmt_update_row->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt_update_row->execute();
                ++$maxoption_data;
            } else {
                $stmt_delete_row->bindValue(':id', (int) $id, PDO::PARAM_INT);
                $stmt_delete_row->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt_delete_row->execute();
            }
        }

        $stmt_insert_row = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (vid, title, url, hitstotal) VALUES (:vid, :title, :url, 0)');

        foreach ($answervotenews as $key => $title) {
            if (!empty($title)) {
                $url = $urlvotenews[$key];
                $stmt_insert_row->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt_insert_row->bindValue(':title', $title, PDO::PARAM_STR);
                $stmt_insert_row->bindValue(':url', $url, PDO::PARAM_STR);
                $stmt_insert_row->execute();
                if ($db->lastInsertId()) {
                    ++$maxoption_data;
                }
            }
        }

        if ($maxoption > $maxoption_data) {
            $maxoption = $maxoption_data;
        }

        if ($begindate > NV_CURRENTTIME or ($enddate > 0 and $enddate < NV_CURRENTTIME)) {
            $act = 0;
        } else {
            $act = 1;
        }

        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET
            question = :question, link = :link, acceptcm = :acceptcm, active_captcha = :active_captcha,
            admin_id = :admin_id, groups_view = :groups_view,
            publ_time = :publ_time, exp_time = :exp_time, act = :act, vote_one = :vote_one
        WHERE vid = :vid');
        $stmt->bindValue(':question', $question, PDO::PARAM_STR);
        $stmt->bindValue(':link', $link, PDO::PARAM_STR);
        $stmt->bindValue(':acceptcm', $maxoption, PDO::PARAM_INT);
        $stmt->bindValue(':active_captcha', $active_captcha, PDO::PARAM_INT);
        $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
        $stmt->bindValue(':groups_view', $groups_view, PDO::PARAM_STR);
        $stmt->bindValue(':publ_time', $begindate, PDO::PARAM_INT);
        $stmt->bindValue(':exp_time', $enddate, PDO::PARAM_INT);
        $stmt->bindValue(':act', $act, PDO::PARAM_INT);
        $stmt->bindValue(':vote_one', $vote_one, PDO::PARAM_INT);
        $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() >= 0) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('voting_edit'), $question, $admin_info['userid']);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getGlobal('save_success'),
                'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true)
            ]);
        }
    }

    nv_jsonOutput([
        'status' => 'error',
        'mess' => $nv_Lang->getModule('voting_error')
    ]);
}

// Đọc dữ liệu thăm dò để hiển thị form
$maxoption = 1;
$array_answervote = [];
$array_urlvote = [];

if ($vid > 0) {
    $stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid = :vid');
    $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
    $stmt->execute();
    $rowvote = $stmt->fetch();
    $stmt->closeCursor();

    $stmt = $db->prepare('SELECT id, title, url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = :vid ORDER BY id ASC');
    $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $array_answervote[$row['id']] = $row['title'];
        $array_urlvote[$row['id']] = $row['url'];
        ++$maxoption;
    }
    $stmt->closeCursor();
    if ($maxoption > 1) {
        $maxoption -= 1;
    }
} else {
    $rowvote = [
        'groups_view' => '6',
        'publ_time' => NV_CURRENTTIME,
        'exp_time' => 0,
        'acceptcm' => 1,
        'active_captcha' => 1,
        'question' => '',
        'link' => '',
        'vote_one' => 0
    ];
}

$rowvote['link'] = nv_htmlspecialchars($rowvote['link'] ?? '');
$rowvote['question_maxlength'] = ($db_config['charset'] == 'utf8') ? 333 : 250;

$publ_date = date('d/m/Y', $rowvote['publ_time']);
$phour = (int) date('H', $rowvote['publ_time']);
$pmin = (int) date('i', $rowvote['publ_time']);

if (!empty($rowvote['exp_time'])) {
    $exp_date = date('d/m/Y', $rowvote['exp_time']);
    $ehour = (int) date('H', $rowvote['exp_time']);
    $emin = (int) date('i', $rowvote['exp_time']);
} else {
    $exp_date = '';
    $ehour = 0;
    $emin = 0;
}

$hour_options = [];
for ($i = 0; $i <= 23; ++$i) {
    $hour_options[] = ['key' => $i, 'title' => str_pad($i, 2, '0', STR_PAD_LEFT)];
}

$min_options = [];
for ($i = 0; $i < 60; ++$i) {
    $min_options[] = ['key' => $i, 'title' => str_pad($i, 2, '0', STR_PAD_LEFT)];
}

$items = [];
foreach ($array_answervote as $id => $title) {
    $items[] = [
        'id' => $id,
        'title' => $title,
        'url' => nv_htmlspecialchars($array_urlvote[$id] ?? '')
    ];
}

$groups_view = !empty($rowvote['groups_view']) ? array_map('intval', explode(',', $rowvote['groups_view'])) : [];

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('content.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('VID', $vid);
$tpl->assign('ROWVOTE', $rowvote);
$tpl->assign('PUBL_DATE', $publ_date);
$tpl->assign('PHOUR', $phour);
$tpl->assign('PMIN', $pmin);
$tpl->assign('EXP_DATE', $exp_date);
$tpl->assign('EHOUR', $ehour);
$tpl->assign('EMIN', $emin);
$tpl->assign('HOUR_OPTIONS', $hour_options);
$tpl->assign('MIN_OPTIONS', $min_options);
$tpl->assign('ITEMS', $items);
$tpl->assign('NEW_ITEM_NUM', count($items) + 1);
$tpl->assign('GROUPS_LIST', $groups_list);
$tpl->assign('GROUPS_VIEW', $groups_view);

$contents = $tpl->fetch('content.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
