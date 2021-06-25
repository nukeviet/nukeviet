<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);

if (empty($id)) {
    exit('Stop!!!');
}

$sql = 'SELECT act FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . $id;
$row = $db->query($sql)->fetch();
if (empty($row)) {
    exit('Stop!!!');
}

$act = $row['act'] ? 0 : 1;

$sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_plans SET act=' . $act . ' WHERE id=' . $id;
$return = $db->exec($sql) ? 'OK' : 'NO';

$nv_Cache->delMod($module_name);
nv_CreateXML_bannerPlan();

include NV_ROOTDIR . '/includes/header.php';
echo $return . '|act_' . $id . '|' . $id . '|plan_info';
include NV_ROOTDIR . '/includes/footer.php';
