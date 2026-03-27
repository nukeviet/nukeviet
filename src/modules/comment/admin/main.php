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

$array_where = [];
$params = [];

if (!empty($module) and isset($site_mod_comm[$module])) {
    $array_where[] = 'module = :module_search';
    $params[':module_search'] = [$module, PDO::PARAM_STR];
    $base_url .= '&amp;module=' . $module;
} elseif (!defined('NV_IS_SPADMIN')) {
    if (empty($site_mod_comm)) {
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($nv_Lang->getGlobal('admin_no_allow_func'));
        include NV_ROOTDIR . '/includes/footer.php';
        exit();
    } else {
        $mod_where = [];
        $i = 0;
        foreach ($site_mod_comm as $module_i => $custom_title) {
            $mod_where[] = 'module = :mod' . $i;
            $params[':mod' . $i] = [$module_i, PDO::PARAM_STR];
            ++$i;
        }
        $array_where[] = '(' . implode(' OR ', $mod_where) . ')';
    }
}

if (!empty($from['from_date'])) {
    $array_where[] = 'post_time > :from_date';
    $params[':from_date'] = [$from['from_date'], PDO::PARAM_INT];
    $base_url .= '&amp;from_date=' . nv_u2d_get($from['from_date']);
}

if (!empty($from['to_date'])) {
    $array_where[] = 'post_time < :to_date';
    $params[':to_date'] = [$from['to_date'], PDO::PARAM_INT];
    $base_url .= '&amp;to_date=' . nv_u2d_get($from['to_date']);
}

if ($sstatus == 0 or $sstatus == 1) {
    $array_where[] = 'status = :status_search';
    $params[':status_search'] = [$sstatus, PDO::PARAM_INT];
    $base_url .= '&amp;status=' . $sstatus;
}

if (!empty($from['q'])) {
    $array_like = [];
    if ($stype == 'content_id') {
        $array_like[] = 'id LIKE :id';
        $params[':id'] = ['%' . $from['q'] . '%', PDO::PARAM_STR];
    } else {
        if ($stype == '' or $stype == 'content') {
            $array_like[] = 'content LIKE :content';
            $params[':content'] = ['%' . $from['q'] . '%', PDO::PARAM_STR];
        }
        if ($stype == '' or $stype == 'post_name') {
            $array_like[] = 'post_name LIKE :post_name';
            $params[':post_name'] = ['%' . $from['q'] . '%', PDO::PARAM_STR];
        }
        if ($stype == '' or $stype == 'post_email') {
            $array_like[] = 'post_email LIKE :post_email';
            $params[':post_email'] = ['%' . $from['q'] . '%', PDO::PARAM_STR];
        }
    }
    if (!empty($array_like)) {
        $array_where[] = '(' . implode(' OR ', $array_like) . ')';
    }
    $base_url .= '&amp;q=' . urlencode($from['q']);
}
if ($stype != '') {
    $base_url .= '&amp;stype=' . urlencode($stype);
}

$where_sql = !empty($array_where) ? ' WHERE ' . implode(' AND ', $array_where) : '';

$stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . $where_sql);
foreach ($params as $p => $v) {
    $stmt->bindValue($p, $v[0], $v[1]);
}
$stmt->execute();
$num_items = (int) $stmt->fetchColumn();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$sql = 'SELECT cid, module, area, id, content, attach, userid, post_name, post_email, status FROM ' . NV_PREFIXLANG . '_' . $module_data . $where_sql . ' ORDER BY cid DESC LIMIT :limit OFFSET :offset';
$stmt = $db->prepare($sql);
foreach ($params as $p => $v) {
    $stmt->bindValue($p, $v[0], $v[1]);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
$stmt->execute();

$array = $stmt->fetchAll();
if (empty($array)) {
    $array = [];
}
$stmt->closeCursor();
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
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ARRAY_ROW', $array);
$tpl->assign('GENERATE_PAGE', $generate_page);

$tpl->registerPlugin('modifier', 'nv_clean60', 'nv_clean60');
$tpl->registerPlugin('modifier', 'urlencode', 'urlencode');

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
