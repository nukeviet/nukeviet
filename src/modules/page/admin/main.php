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

$page_title = $nv_Lang->getModule('list');

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' ORDER BY weight ASC';
$_rows = $db->query($sql)->fetchAll();
$num = count($_rows);

if ($num < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content');
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('PCONFIG', $page_config);

$array_row = [];
$iw = 0;
$is_delCache = false;

foreach ($_rows as $row) {
    ++$iw;

    if ($iw != $row['weight']) {
        $row['weight'] = $iw;
        $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET weight=' . $row['weight'] . ' WHERE id= :id');
        $sth->bindParam(':id', $row['id'], PDO::PARAM_STR);
        $sth->execute();
        $is_delCache = true;
    }

    $array_row[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'weight' => $row['weight'],
        'hitstotal' => nv_number_format($row['hitstotal']),
        'checkss' => md5($row['id'] . NV_CHECK_SESSION),
        'url_view' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $row['alias'] . $global_config['rewrite_exturl'],
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'],
        'status' => $row['status'],
        'edit_time' => nv_datetime_format($row['edit_time'], 1),
        'add_time' => nv_datetime_format($row['add_time'], 1)
    ];
}

if ($is_delCache) {
    $nv_Cache->delMod($module_name);
}

$tpl->assign('DATA', $array_row);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
