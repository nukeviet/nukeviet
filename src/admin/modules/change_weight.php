<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$mod = $nv_Request->get_title('mod', 'post', '');
$new_weight = $nv_Request->get_int('new_weight', 'post', 0);

if (empty($mod) or empty($new_weight) or !preg_match($global_config['check_module'], $mod)) {
    exit('NO_' . $mod);
}

$sth = $db->prepare('SELECT weight FROM ' . NV_MODULES_TABLE . ' WHERE title= :title');
$sth->bindParam(':title', $mod, PDO::PARAM_STR);
$sth->execute();
$row = $sth->fetch();
if (empty($row)) {
    exit('NO_' . $mod);
}

$sth = $db->prepare('SELECT title FROM ' . NV_MODULES_TABLE . ' WHERE title != :title ORDER BY weight ASC');
$sth->bindParam(':title', $mod, PDO::PARAM_STR);
$sth->execute();

$weight = 0;
while ($row = $sth->fetch()) {
    ++$weight;
    if ($weight == $new_weight) {
        ++$weight;
    }

    $sth2 = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $weight . ' WHERE title= :title');
    $sth2->bindParam(':title', $row['title'], PDO::PARAM_STR);
    $sth2->execute();
}

$sth2 = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $new_weight . ' WHERE title= :title');
$sth2->bindParam(':title', $mod, PDO::PARAM_STR);
$sth2->execute();

$nv_Cache->delMod('modules');

nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['weight'] . ' module: ' . $mod, $weight . ' -> ' . $new_weight, $admin_info['userid']);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $mod;
include NV_ROOTDIR . '/includes/footer.php';
