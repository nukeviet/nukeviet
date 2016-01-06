<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 19:49
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$mod = $nv_Request->get_title('mod', 'post');

if (empty($mod) or ! preg_match($global_config['check_module'], $mod)) {
    die('NO_' . $mod);
}

$sth = $db->prepare('SELECT act, module_file FROM ' . NV_MODULES_TABLE . ' WHERE title= :title');
$sth->bindParam(':title', $mod, PDO::PARAM_STR);
$sth->execute();
$row = $sth->fetch();
if (empty($row)) {
    die('NO_' . $mod);
}

$act = intval($row['act']);

if ($act == 2) {
    if (! is_dir(NV_ROOTDIR . '/modules/' . $row['module_file'])) {
        die('NO_' . $mod);
    }
}

$act = ($act != 1) ? 1 : 0;
if ($act == 0 and $mod == $global_config['site_home_module']) {
    die('NO_' . $mod);
}

$sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act=' . $act . ' WHERE title= :title');
$sth->bindParam(':title', $mod, PDO::PARAM_STR);
$sth->execute();

$nv_Cache->delMod('modules');

$temp = ($act == 1) ? $lang_global['yes'] : $lang_global['no'];
nv_insert_logs(NV_LANG_DATA, $module_name, $lang_global['activate'] . ' module "' . $mod . '"', $temp, $admin_info['userid']);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $mod;
include NV_ROOTDIR . '/includes/footer.php';