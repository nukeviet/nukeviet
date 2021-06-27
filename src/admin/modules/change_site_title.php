<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if (!$nv_Request->isset_request('id', 'post,get')) {
    exit('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);

$sql = 'SELECT f.func_name AS func_title,f.func_site_title AS func_site_title,m.custom_title AS mod_custom_title
FROM ' . NV_MODFUNCS_TABLE . ' AS f, ' . NV_MODULES_TABLE . ' AS m WHERE f.func_id=' . $id . ' AND f.in_module=m.title';
$row = $db->query($sql)->fetch();

if (empty($row)) {
    exit('NO_' . $id);
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $func_site_title = $nv_Request->get_title('func_site_title', 'post', '');

    $sth = $db->prepare('UPDATE ' . NV_MODFUNCS_TABLE . ' SET func_site_title= :func_site_title WHERE func_id=' . $id);
    $sth->bindParam(':func_site_title', $func_site_title, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('modules');

    exit('OK|show_funcs|show_funcs_action');
}
    $func_site_title = $row['func_site_title'];

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('CAPTION', $nv_Lang->getModule('change_func_sitetitle', $row['func_title'], $row['mod_custom_title']));
$tpl->assign('FUNC_SITE_TITLE', $func_site_title);
$tpl->assign('FUN_ID', $id);

$contents = $tpl->fetch('change_site_title_theme.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
