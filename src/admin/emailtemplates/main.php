<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EMAILTEMPLATES')) {
    exit('Stop!!!');
}

// Xóa mẫu email
if ($nv_Request->isset_request('delete', 'post')) {
    $emailid = $nv_Request->get_int('emailid', 'post', 0);

    $sql = 'SELECT emailid, is_system FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid=' . $emailid;
    $row = $db->query($sql)->fetch();

    if (empty($row) or $row['is_system']) {
        exit('NO_' . $emailid);
    }

    $sql = 'DELETE FROM ' . NV_EMAILTEMPLATES_GLOBALTABLE . ' WHERE emailid = ' . $emailid;

    if ($db->exec($sql)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete tpl', 'ID: ' . $emailid, $admin_info['userid']);
        nv_apply_hook('', 'emailtemplates_after_delete', [$emailid]);
        $nv_Cache->delMod($module_name);
    } else {
        exit('NO_' . $emailid);
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
        ++$i;
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
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
