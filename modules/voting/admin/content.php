<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['voting_edit'];

$error = '';
$vid = $nv_Request->get_int('vid', 'post,get');
$submit = $nv_Request->get_string('submit', 'post');
$groups_list = nv_groups_list();

if (! empty($submit)) {
    $question = $nv_Request->get_title('question', 'post', '', 1);
    $link = $nv_Request->get_title('link', 'post', '', 1);

    $_groups_post = $nv_Request->get_array('groups_view', 'post', array());
    $groups_view = ! empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $publ_date = $nv_Request->get_title('publ_date', 'post', '');
    $exp_date = $nv_Request->get_title('exp_date', 'post', '');
    $maxoption = $nv_Request->get_int('maxoption', 'post', 1);

    $array_answervote = $nv_Request->get_array('answervote', 'post');
    $array_urlvote = $nv_Request->get_array('urlvote', 'post');

    $answervotenews = $nv_Request->get_array('answervotenews', 'post');
    $urlvotenews = $nv_Request->get_array('urlvotenews', 'post');
    if ($maxoption > ($sizeof = sizeof($answervotenews) + sizeof($array_answervote)) or $maxoption <= 0) {
        $maxoption = $sizeof;
    }

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
    foreach ($array_answervote as $title) {
        $title = trim(strip_tags($title));
        if ($title != '') {
            ++$number_answer;
        }
    }
    foreach ($answervotenews as $title) {
        $title = trim(strip_tags($title));
        if ($title != '') {
            ++$number_answer;
        }
    }
    $rowvote = array(
        'groups_view' => '6',
        'publ_time' => $begindate,
        'exp_time' => $enddate,
        'acceptcm' => $maxoption,
        'question' => $question,
        'link' => $link
    );

    $active_captcha = $nv_Request->get_int('active_captcha', 'post', 0) ? 1 : 0;

    if (! empty($question) and $number_answer > 1) {
        $error = $lang_module['voting_error'];

        if (empty($vid)) {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (
                question, link, acceptcm, active_captcha, admin_id, groups_view, publ_time, exp_time, act
            ) VALUES (
                ' . $db->quote($question) . ', ' . $db->quote($link) . ', ' . $maxoption . ', ' . $active_captcha . ',' . $admin_info['admin_id'] . ', ' . $db->quote($groups_view) . ', 0, 0, 1
            )';
            $vid = $db->insert_id($sql, 'vid');
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['voting_add'], $question, $admin_info['userid']);
        }

        if ($vid > 0) {
            $maxoption_data = 0;
            foreach ($array_answervote as $id => $title) {
                $title = nv_htmlspecialchars(strip_tags($title));
                if ($title != '') {
                    $url = nv_unhtmlspecialchars(strip_tags($array_urlvote[$id]));
                    if (!nv_is_url($url)) {
                        $url = '';
                    }
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET title = ' . $db->quote($title) . ', url = ' . $db->quote($url) . ' WHERE id =' . intval($id) . ' AND vid =' . $vid);
                    ++$maxoption_data;
                } else {
                    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id =' . intval($id) . ' AND vid =' . $vid);
                }
            }

            foreach ($answervotenews as $key => $title) {
                $title = nv_htmlspecialchars(strip_tags($title));
                if ($title != '') {
                    $url = nv_unhtmlspecialchars(strip_tags($urlvotenews[$key]));

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
                publ_time=' . $begindate . ', exp_time=' . $enddate . ', act=' . $act . '
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
    $array_answervote = array();
    $array_urlvote = array();

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
        $rowvote = array(
            'groups_view' => '6',
            'publ_time' => NV_CURRENTTIME,
            'exp_time' => '',
            'acceptcm' => 1,
            'active_captcha' => 1,
            'question' => '',
            'link' => ''
        );
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
    $xtpl->assign('PHOUR', array(
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $phour ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.phour');
}
for ($i = 0; $i < 60; ++$i) {
    $xtpl->assign('PMIN', array(
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $pmin ? ' selected="selected"' : ''
    ));
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
    $xtpl->assign('EHOUR', array(
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $ehour ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.ehour');
}
for ($i = 0; $i < 60; ++$i) {
    $xtpl->assign('EMIN', array(
        'key' => $i,
        'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
        'selected' => $i == $emin ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.emin');
}

$items = 0;
foreach ($array_answervote as $id => $title) {
    $xtpl->assign('ITEM', array(
        'stt' => ++$items,
        'id' => $id,
        'title' => $title,
        'link' => nv_htmlspecialchars($array_urlvote[$id])
    ));

    $xtpl->parse('main.item');
}

$xtpl->assign('NEW_ITEM', ++$items);
$xtpl->assign('NEW_ITEM_NUM', $items);

$groups_view = explode(',', $rowvote['groups_view']);
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('GROUPS_VIEW', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $groups_view) ? ' checked="checked"' : '',
        'title' => $_title
    ));
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