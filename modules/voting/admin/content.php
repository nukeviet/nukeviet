<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['voting_edit'];

$error = '';
$vid = $nv_Request->get_int('vid', 'post,get');
$groups_list = nv_groups_list();

if (!empty($vid)) {
    $exists = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid=' . $vid)->fetchColumn();
    if (!$exists) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
}

if ($nv_Request->isset_request('save', 'post')) {
    $question = $nv_Request->get_title('question', 'post', '', 1);
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

    $rowvote = [
        'groups_view' => '6',
        'publ_time' => $begindate,
        'exp_time' => $enddate,
        'acceptcm' => $maxoption,
        'question' => $question,
        'link' => $link
    ];

    $active_captcha = $nv_Request->get_int('active_captcha', 'post', 0) ? 1 : 0;

    if (!empty($question) and $number_answer > 1) {
        $error = $lang_module['voting_error'];

        if (empty($vid)) {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
                question, link, acceptcm, active_captcha, admin_id, groups_view, publ_time, exp_time, act, vote_one
            ) VALUES (
                ' . $db->quote($question) . ', ' . $db->quote($link) . ', ' . $maxoption . ', ' . $active_captcha . ',' . $admin_info['admin_id'] . ', ' . $db->quote($groups_view) . ', 0, 0, 1, ' . $vote_one . '
            )';
            $vid = $db->insert_id($sql, 'vid');
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['voting_add'], $question, $admin_info['userid']);
        }

        if ($vid > 0) {
            $maxoption_data = 0;
            foreach ($array_answervote as $id => $title) {
                if (!empty($title)) {
                    $url = $array_urlvote[$id];
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET title = ' . $db->quote($title) . ', url = ' . $db->quote($url) . ' WHERE id =' . (int) $id . ' AND vid =' . $vid);
                    ++$maxoption_data;
                } else {
                    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id =' . (int) $id . ' AND vid =' . $vid);
                }
            }

            foreach ($answervotenews as $key => $title) {
                if (!empty($title)) {
                    $url = $urlvotenews[$key];
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows (vid, title, url, hitstotal) VALUES (' . $db->quote($vid) . ', ' . $db->quote($title) . ', ' . $db->quote($url) . ', 0)';
                    if ($db->insert_id($sql, 'id')) {
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

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET
                question=' . $db->quote($question) . ', link=' . $db->quote($link) . ', acceptcm = ' . $maxoption . ', active_captcha=' . $active_captcha . ',
                admin_id = ' . $admin_info['admin_id'] . ', groups_view = ' . $db->quote($groups_view) . ',
                publ_time=' . $begindate . ', exp_time=' . $enddate . ', act=' . $act . ', vote_one=' . $vote_one . '
            WHERE vid =' . $vid;

            if ($db->query($sql)) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['voting_edit'], $question, $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            }
        }
    } else {
        $error = $lang_module['voting_error_content'];
    }

    foreach ($answervotenews as $key => $title) {
        $title = trim(strip_tags($title));
        if ($title != '') {
            $array_answervote[] = $title;
            $array_urlvote[] = $urlvotenews[$key];
        }
    }
} else {
    $maxoption = 1;
    $array_answervote = [];
    $array_urlvote = [];

    if ($vid > 0) {
        $queryvote = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE vid=' . $vid;
        $rowvote = $db->query($queryvote)->fetch();

        $sql = 'SELECT id, title, url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid=' . $vid . ' ORDER BY id ASC';
        $result = $db->query($sql);

        while (list($id, $title, $url) = $result->fetch(3)) {
            $array_answervote[$id] = $title;
            $array_urlvote[$id] = $url;
            ++$maxoption;
        }
        if ($maxoption > 1) {
            $maxoption = $maxoption - 1;
        }

        $active_captcha = $rowvote['active_captcha'];
    } else {
        $rowvote = [
            'groups_view' => '6',
            'publ_time' => NV_CURRENTTIME,
            'exp_time' => '',
            'acceptcm' => 1,
            'active_captcha' => 1,
            'question' => '',
            'link' => '',
            'vote_one' => 0
        ];
        $active_captcha = 1;
    }
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;vid=' . $vid);

$rowvote['link'] = nv_htmlspecialchars($rowvote['link']);
$rowvote['active_captcha'] = $active_captcha ? ' checked="checked"' : '';
$rowvote['question_maxlength'] = ($db_config['charset'] == 'utf8') ? 333 : 250;
$rowvote['vote_one'] = $rowvote['vote_one'] ? ' checked="checked"' : '';

$xtpl->assign('DATA', $rowvote);

if ($error != '') {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$tdate = date('d|m|Y|H|i');
list($pday, $pmonth, $pyear, $phour, $pmin) = explode('|', $tdate);
$emonth = $eday = $eyear = $emin = $ehour = 0;

$tdate = date('H|i', $rowvote['publ_time']);
$publ_date = date('d/m/Y', $rowvote['publ_time']);
list($phour, $pmin) = explode('|', $tdate);

// Thoi gian dang
$xtpl->assign('PUBL_DATE', $publ_date);
for ($i = 0; $i <= 23; ++$i) {
    $xtpl->assign('PHOUR', [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $phour ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.phour');
}
for ($i = 0; $i < 60; ++$i) {
    $xtpl->assign('PMIN', [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $pmin ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.pmin');
}

// Thoi gian ket thuc
if ($rowvote['exp_time'] > 0) {
    $tdate = date('H|i', $rowvote['exp_time']);
    $exp_date = date('d/m/Y', $rowvote['exp_time']);
    list($ehour, $emin) = explode('|', $tdate);
} else {
    $emin = $ehour = 0;
    $exp_date = '';
}
$xtpl->assign('EXP_DATE', $exp_date);
for ($i = 0; $i <= 23; ++$i) {
    $xtpl->assign('EHOUR', [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $ehour ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.ehour');
}
for ($i = 0; $i < 60; ++$i) {
    $xtpl->assign('EMIN', [
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $emin ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.emin');
}

$items = 0;
foreach ($array_answervote as $id => $title) {
    $xtpl->assign('ITEM', [
        'stt' => ++$items,
        'id' => $id,
        'title' => $title,
        'link' => nv_htmlspecialchars($array_urlvote[$id])
    ]);

    $xtpl->parse('main.item');
}

$xtpl->assign('NEW_ITEM', ++$items);
$xtpl->assign('NEW_ITEM_NUM', $items);

$groups_view = array_map('intval', explode(',', $rowvote['groups_view']));
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('GROUPS_VIEW', [
        'value' => $_group_id,
        'checked' => in_array((int) $_group_id, $groups_view, true) ? ' checked="checked"' : '',
        'title' => $_title
    ]);
    $xtpl->parse('main.groups_view');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');
if ($vid) {
    $op = '';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
