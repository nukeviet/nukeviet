<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!$pro_config['point_active']) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    die();
}

$q = $nv_Request->get_title('q', 'post,get');
$per_page = 20;
$page = $nv_Request->get_int('page', 'post,get', 1);
$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_USERS_GLOBALTABLE . ' t1')
    ->join('LEFT JOIN ' . $db_config['prefix'] . '_' . $module_data . '_point t2 ON t1.userid=t2.userid');

if (!empty($q)) {
    $db->where('username LIKE :q_username OR first_name LIKE :q_first_name OR last_name LIKE :q_last_name OR email LIKE :q_email');
}

$sth = $db->prepare($db->sql());

if (!empty($q)) {
    $sth->bindValue(':q_username', '%' . $q . '%');
    $sth->bindValue(':q_first_name', '%' . $q . '%');
    $sth->bindValue(':q_last_name', '%' . $q . '%');
    $sth->bindValue(':q_email', '%' . $q . '%');
}
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select('t1.username, t1.first_name, t1.last_name, t1.email, t2.*')->order('t1.userid DESC')->limit($per_page)->offset(($page - 1) * $per_page);
$sth = $db->prepare($db->sql());

if (!empty($q)) {
    $sth->bindValue(':q_username', '%' . $q . '%');
    $sth->bindValue(':q_first_name', '%' . $q . '%');
    $sth->bindValue(':q_last_name', '%' . $q . '%');
    $sth->bindValue(':q_email', '%' . $q . '%');
}
$sth->execute();

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('Q', $q);
$xtpl->assign('money_unit', $pro_config['money_unit']);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($q)) {
    $base_url .= '&q=' . $q;
}
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

while ($view = $sth->fetch()) {
    $view['point_total'] = !empty($view['point_total']) ? $view['point_total'] : 0;
    $view['money'] = nv_number_format($view['point_total'] * $pro_config['point_conversion'], nv_get_decimals($pro_config['money_unit']));
    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['point'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
