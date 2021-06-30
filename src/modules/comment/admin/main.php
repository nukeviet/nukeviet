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

$page_title = $lang_module['comment'];

$page = $nv_Request->get_int('page', 'get', 1);
$module = $nv_Request->get_title('module', 'get');
$per_page = $nv_Request->get_int('per_page', 'get', 20);
$stype = $nv_Request->get_string('stype', 'get', '');
$sstatus = $nv_Request->get_title('sstatus', 'get', 2);
$from['q'] = $nv_Request->get_title('q', 'get', '');
$from['from_date'] = $nv_Request->get_title('from_date', 'get', '');
$from['to_date'] = $nv_Request->get_title('to_date', 'get', '');

$array_search = [
    'content' => $lang_module['search_content'],
    'post_name' => $lang_module['search_post_name'],
    'post_email' => $lang_module['search_post_email'],
    'content_id' => $lang_module['search_content_id']
];
$array_status_view = [
    '2' => $lang_module['search_status'],
    '1' => $lang_module['enable'],
    '0' => $lang_module['disable']
];
if (!in_array($stype, array_keys($array_search), true)) {
    $stype = '';
}

if (!in_array($sstatus, array_keys($array_status_view), true)) {
    $sstatus = 2;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('FROM', $from);

foreach ($array_search as $key => $val) {
    $xtpl->assign('OPTION', [
        'key' => $key,
        'title' => $val,
        'selected' => ($key == $stype) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.search_type');
}

foreach ($array_status_view as $key => $val) {
    $xtpl->assign('OPTION', [
        'key' => $key,
        'title' => $val,
        'selected' => ($key == $sstatus) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.search_status');
}

$xtpl->assign('OPTION', [
    'key' => '',
    'title' => $lang_module['search_module_all'],
    'selected' => ($module == '') ? ' selected="selected"' : ''
]);
$xtpl->parse('main.module');

foreach ($site_mod_comm as $module_i => $row) {
    $custom_title = (!empty($row['admin_title'])) ? $row['admin_title'] : $row['custom_title'];
    $xtpl->assign('OPTION', [
        'key' => $module_i,
        'title' => $custom_title,
        'selected' => ($module_i == $module) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.module');
}

$i = 15;
while ($i < 100) {
    $i = $i + 5;
    $xtpl->assign('OPTION', ['page' => $i, 'selected' => ($i == $per_page) ? ' selected="selected"' : '']);
    $xtpl->parse('main.per_page');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $module_data);

$array_where = [];
if (!empty($module) and isset($site_mod_comm[$module])) {
    $array_where[] = 'module = ' . $db->quote($module);
    $base_url .= '&amp;module=' . $module;
} elseif (!defined('NV_IS_SPADMIN')) {
    // Gới hạn module tìm kiếm nếu không phải là quản trị site
    if (empty($site_mod_comm)) {
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($lang_global['admin_no_allow_func']);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        $mod_where = [];
        foreach ($site_mod_comm as $module_i => $custom_title) {
            $mod_where[] = 'module = ' . $db->quote($module_i);
        }
        $array_where[] = '( ' . implode(' OR ', $mod_where) . ' )';
    }
}

if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from['from_date'], $m)) {
    $array_where[] = 'post_time > ' . mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    $base_url .= '&amp;from_date=' . $from['from_date'];
}

if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $from['to_date'], $m)) {
    $array_where[] = 'post_time < ' . mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    $base_url .= '&amp;to_date=' . $from['to_date'];
}

if ($sstatus == 0 or $sstatus == 1) {
    $array_where[] = 'status = ' . $sstatus;
    $base_url .= '&amp;status=' . $sstatus;
}
if (!empty($from['q'])) {
    $array_like = [];
    if ($stype == 'content_id' and preg_match('/^([0-9]+)$/', $from['q'])) {
        $array_like[] = 'id =' . (int) ($from['q']);
    } else {
        if ($stype == '' or $stype == 'content') {
            $array_like[] = 'content LIKE :content';
        }

        if ($stype == '' or $stype == 'post_name') {
            $array_like[] = 'post_name LIKE :post_name';
        }

        if ($stype == '' or $stype == 'post_email') {
            $array_like[] = 'post_email LIKE :post_email';
        }
    }
    if (!empty($array_like)) {
        $array_where[] = '( ' . implode(' OR ', $array_like) . ' )';
    }
    $base_url .= '&amp;q=' . urlencode($from['q']);
}
if ($stype != '') {
    $base_url .= '&amp;stype=' . urlencode($stype);
}

if (!empty($array_where)) {
    $db->where(implode(' AND ', $array_where));
}
$sql = $db->sql();
$sth = $db->prepare($sql);
if (str_contains($sql, ':content')) {
    $sth->bindValue(':content', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
if (str_contains($sql, ':post_name')) {
    $sth->bindValue(':post_name', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
if (str_contains($sql, ':post_email')) {
    $sth->bindValue(':post_email', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
$sth->execute();
$num_items = $sth->fetchColumn();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$db->select('cid, module, area, id, content, attach, userid, post_name, post_email, status')->order('cid DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$sql = $db->sql();
$sth = $db->prepare($sql);
if (str_contains($sql, ':content')) {
    $sth->bindValue(':content', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
if (str_contains($sql, ':post_name')) {
    $sth->bindValue(':post_name', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
if (str_contains($sql, ':post_email')) {
    $sth->bindValue(':post_email', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
$sth->execute();
$array = [];
while (list($cid, $module, $area, $id, $content, $attach, $userid, $post_name, $email, $status) = $sth->fetch(3)) {
    if ($userid > 0) {
        $email = '<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid . '"> ' . $email . '</a>';
    }
    $content = nv_br2nl($content);
    $row = [
        'cid' => $cid,
        'post_name' => $post_name,
        'email' => $email,
        'title' => nv_clean60(strip_tags($content), 255),
        'content' => $content,
        'module' => $module,
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module . '&amp;' . NV_OP_VARIABLE . '=view&amp;area=' . $area . '&amp;id=' . $id,
        'active' => $status ? 'checked="checked"' : '',
        'status' => ($status == 1) ? 'check' : 'circle-o',
        'linkedit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;cid=' . $cid,
        'linkdelete' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=del&amp;list=' . $cid
    ];

    $xtpl->assign('ROW', $row);

    if (!empty($attach)) {
        $xtpl->assign('ATTACH_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;downloadfile=' . urlencode(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $attach));
        $xtpl->parse('main.loop.attach');
    }
    $xtpl->parse('main.loop');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
