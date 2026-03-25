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

$mod = $nv_Request->get_title('mod', 'post', '');
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($mod) or empty($new_weight) or !preg_match($global_config['check_module'], $mod)) {
    nv_jsonOutput([
        'success' => 0,
        'text' => 'Wrong module!'
    ]);
}

if (!csrf_check($nv_Request->get_string('checkss', 'post'), $admin_info['admin_id'] . '_' . $module_name . '_main')) {
    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getGlobal('error_checkss')
    ]);
}

$stmt = $db->prepare('SELECT weight FROM ' . NV_MODULES_TABLE . ' WHERE title = :title');
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

$stmt_sel = $db->prepare('SELECT title FROM ' . NV_MODULES_TABLE . ' WHERE title != :title ORDER BY weight ASC');
$stmt_sel->bindValue(':title', $mod, PDO::PARAM_STR);
$stmt_sel->execute();

$weight = 0;
$stmt_upd = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight = :weight WHERE title = :title');

while ($row = $stmt_sel->fetch()) {
    ++$weight;
    if ($weight == $new_weight) {
        ++$weight;
    }

    $stmt_upd->bindValue(':weight', $weight, PDO::PARAM_INT);
    $stmt_upd->bindValue(':title', $row['title'], PDO::PARAM_STR);
    $stmt_upd->execute();
}
$stmt_sel->closeCursor();

$stmt_upd->bindValue(':weight', $new_weight, PDO::PARAM_INT);
$stmt_upd->bindValue(':title', $mod, PDO::PARAM_STR);
$stmt_upd->execute();

$nv_Cache->delMod('modules');
nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('weight') . ' module: ' . $mod, $weight . ' -> ' . $new_weight, $admin_info['userid']);
nv_jsonOutput([
    'success' => 1,
    'text' => 'Success!'
]);
