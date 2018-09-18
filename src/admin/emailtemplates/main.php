<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    die('Stop!!!');
}

// Xóa mẫu email
if ($nv_Request->isset_request('delete', 'post')) {
    $emailid = $nv_Request->get_int('emailid', 'post', 0);

    $sql = 'SELECT emailid, is_system FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid=' . $emailid;
    $row = $db->query($sql)->fetch();

    if (empty($row) or $row['is_system'])
        die('NO_' . $emailid);

    $sql = 'DELETE FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = ' . $emailid;

    if ($db->exec($sql)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete tpl', 'ID: ' . $emailid, $admin_info['userid']);
        $nv_Cache->delMod($module_name);
    } else {
        die('NO_' . $emailid);
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $emailid;
    include NV_ROOTDIR . '/includes/footer.php';
}

if (empty($global_array_cat)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=categories';
    nv_redirect_location($url);
}

$page_title = $nv_Lang->getModule('tpl_list');

$db->sqlreset()->select('emailid, catid, is_system, is_disabled, ' . NV_LANG_DATA . '_title title')->from(NV_EMAILTEMPLATES_GLOBALTABLE)->order(NV_LANG_DATA . '_title ASC');
$result = $db->query($db->sql());

$array = [];
while ($row = $result->fetch()) {
    $array[$row['catid']][$row['emailid']] = $row;
}

if (empty($array)) {
    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=contents';
    nv_redirect_location($url);
}

// Thêm vào các danh mục khác
$global_array_cat[0] = [
    'catid' => 0,
    'title' => $nv_Lang->getModule('categories_other')
];

// Chuyển danh mục sang 2 cột
$array_cat_change = [];
$array_cat_change[1] = [];
$array_cat_change[2] = [];
$i = 0;
foreach ($global_array_cat as $cat) {
    if (isset($array[$cat['catid']])) {
        $i++;
        $array_cat_change[$i][] = $cat;
        if ($i > 1) {
            $i = 0;
        }
    }
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('DATA', $array);
$tpl->assign('CATS', $array_cat_change);
$tpl->assign('EDIT_BASEURL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=contents&amp;emailid=');

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
