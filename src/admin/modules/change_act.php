<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$mod = $nv_Request->get_title('mod', 'post');
if (empty($mod) or !preg_match($global_config['check_module'], $mod)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong module!'
    ]);
}

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key . '_' . $mod)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$stmt = $db->prepare('SELECT act, module_file FROM ' . NV_MODULES_TABLE . ' WHERE title = :title');
$stmt->bindValue(':title', $mod, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch();
$stmt->closeCursor();

if (empty($row)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not exists!'
    ]);
}

$act = (int) ($row['act']);
if ($act == 2) {
    if (!is_dir(NV_ROOTDIR . '/modules/' . $row['module_file'])) {
        nv_jsonOutput([
            'success' => 0,
            'text' => 'Not exists on server!'
        ]);
    }
}

$act = ($act != 1) ? 1 : 0;
if ($act == 0 and $mod == $global_config['site_home_module']) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Not allowed!'
    ]);
}

$stmt = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act = :act WHERE title = :title');
$stmt->bindValue(':act', $act, PDO::PARAM_INT);
$stmt->bindValue(':title', $mod, PDO::PARAM_STR);
$stmt->execute();

$nv_Cache->delMod('modules');

$temp = ($act == 1) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no');
nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getGlobal('activate') . ' module "' . $mod . '"', $temp, $admin_info['userid']);
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
