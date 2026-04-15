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

use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentService;
use NukeViet\Module\Content\Cat\CatRepository;

$page_title = $nv_Lang->getModule('list');

$contentRepo = new ContentRepository($db, $tables, $nv_Cache, $module_name);

$service = new ContentService($contentRepo);

// Filter theo chuyên mục
$filter_catid = $nv_Request->get_int('catid', 'get', 0);

$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
$cats_all = $catRepo->getAll();

$_rows = $contentRepo->getContentList($filter_catid, -1);
$num = count($_rows);

if ($num < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
}

$array_row = [];

// Tạo map catid => title
$cat_map = [];
foreach ($cats_all as $cat) {
    $cat_map[$cat->catid] = $cat->title;
}

// Weight được giữ đúng thứ tự bởi reorderWeight() sau mỗi delete/reorder.
// autoCorrectWeight() chỉ cần chạy thủ công sau import dữ liệu ngoài luồng thông thường.
foreach ($_rows as $entity) {
    $entity->checkss = csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $entity->id);
    $entity->url_view = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $entity->alias . $global_config['rewrite_exturl'];
    $entity->url_edit = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $entity->id;
    $entity->url_copy = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;copy=' . $entity->id;

    $row = $entity->toArray();
    $row['cat_title'] = $cat_map[$entity->catid] ?? $nv_Lang->getModule('cat_unclassified');
    $array_row[] = $row;
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
$tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('PCONFIG', $config);
$tpl->assign('DATA', $array_row);
$tpl->assign('CATS', $cat_map);
$tpl->assign('FILTER_CATID', $filter_catid);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
