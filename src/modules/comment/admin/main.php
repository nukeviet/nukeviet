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

$page_title = $nv_Lang->getModule('admin_comment');

$page = $nv_Request->get_page('page', 'get', 1);
$module = $nv_Request->get_title('module', 'get');
$per_page = $nv_Request->get_page('per_page', 'get', 20);
$stype = $nv_Request->get_string('stype', 'get', '');
$sstatus = $nv_Request->get_title('sstatus', 'get', 2);
$from['q'] = $nv_Request->get_title('q', 'get', '');
$from['from_date'] = nv_d2u_get($nv_Request->get_title('from_date', 'get', ''));
$from['to_date'] = nv_d2u_get($nv_Request->get_title('to_date', 'get', ''));
$array_search = [
    'content' => $nv_Lang->getModule('search_content'),
    'post_name' => $nv_Lang->getModule('search_post_name'),
    'post_email' => $nv_Lang->getModule('search_post_email'),
    'content_id' => $nv_Lang->getModule('search_content_id')
];
$array_status_view = [
    '2' => $nv_Lang->getModule('search_status'),
    '1' => $nv_Lang->getModule('enable'),
    '0' => $nv_Lang->getModule('disable')
];
if (!in_array($stype, array_keys($array_search), true)) {
    $stype = '';
}

if (!in_array($sstatus, array_keys($array_status_view))) {
    $sstatus = 2;
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
        echo nv_admin_theme($nv_Lang->getGlobal('admin_no_allow_func'));
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        $mod_where = [];
        foreach ($site_mod_comm as $module_i => $custom_title) {
            $mod_where[] = 'module = ' . $db->quote($module_i);
        }
        $array_where[] = '( ' . implode(' OR ', $mod_where) . ' )';
    }
}
if (!empty($from['from_date'])) {
    $array_where[] = 'post_time > ' . $from['from_date'];
    $base_url .= '&amp;from_date=' . nv_u2d_get($from['from_date']);
}

if (!empty($from['to_date'])) {
    $array_where[] = 'post_time < ' . $from['to_date'];
    $base_url .= '&amp;to_date=' . nv_u2d_get($from['to_date']);
}

if ($sstatus == 0 or $sstatus == 1) {
    $array_where[] = 'status = ' . $sstatus;
    $base_url .= '&amp;status=' . $sstatus;
}
if (!empty($from['q'])) {
    $array_like = [];
    if ($stype == 'content_id') {
        $array_like[] = 'id LIKE :id';
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
if (str_contains($sql, ':id')) {
    $sth->bindValue(':id', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
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
if (str_contains($sql, ':id')) {
    $sth->bindValue(':id', '%' . $from['q'] . '%', PDO::PARAM_STR);
}
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
$array = $sth->fetchAll();
if (empty($array)) {
    $array = [];
}
$sth->closeCursor();
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $admin_info['userid']);
$from['from_date'] = nv_u2d_get($from['from_date']);
$from['to_date'] = nv_u2d_get($from['to_date']);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('FROM', $from);
$tpl->assign('STYPE', $stype);
$tpl->assign('SSTATUS', $sstatus);
$tpl->assign('MODULE', $module);
$tpl->assign('MODULE_UPLOAD', $module_upload);
$tpl->assign('SITE_MOD_COMM', $site_mod_comm);
$tpl->assign('PER_PAGE', $per_page);
$tpl->assign('ARRAY_SEARCH', $array_search);
$tpl->assign('ARRAY_STATUS_VIEW', $array_status_view);
$tpl->assign('CHECKSS', $checkss);
$tpl->assign('ARRAY_ROW', $array);
$tpl->assign('GENERATE_PAGE', $generate_page);

$tpl->registerPlugin('modifier', 'nv_clean60', 'nv_clean60');
$tpl->registerPlugin('modifier', 'urlencode', 'urlencode');

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
