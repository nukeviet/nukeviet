<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=unfollowers';
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 50;

$page_title = $nv_Lang->getModule('unfollowers');

// Lấy danh sách unfollowers từ CSDL
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_MOD_TABLE . '_followers')
    ->where('isfollow=0');
$unfollowers_count = $db->query($db->sql())
    ->fetchColumn();

$db->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page)
    ->order('weight ASC');
$result = $db->query($db->sql());

$unfollowers = [];
while ($row = $result->fetch()) {
    $unfollowers[$row['user_id']] = $row;
}

$generate_page = nv_generate_page($base_url, $unfollowers_count, $per_page, $page);

$xtpl = new XTemplate('unfollowers.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

if ($unfollowers_count) {
    foreach ($unfollowers as $unfollower) {
        $unfollower['user_gender'] = $unfollower['user_gender'] != '' ? $nv_Lang->getModule('user_gender_' . $unfollower['user_gender']) : '';
        $unfollower['updatetime_format'] = nv_date('d/m/Y H:i', $unfollower['updatetime']);
        $xtpl->assign('UNFOLLOWER', $unfollower);
        $xtpl->parse('main.isUnfollowers.follower');
    }
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.isUnfollowers.generate_page');
    }
    $xtpl->parse('main.isUnfollowers');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
