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
$mid = $nv_Request->get_int('mid', 'post', 0);
$parentid = $nv_Request->get_int('parentid', 'post', 0);

if (!nv_menu_del_sub($id, $parentid)) {
    exit('NO_' . $id);
}
menu_fix_order($mid);
nv_insert_logs(NV_LANG_DATA, $module_name, 'Del row menu', 'Row menu id: ' . $id . ' of Menu id: ' . $mid, $admin_info['userid']);
$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id . '_' . $mid . '_' . $parentid;
include NV_ROOTDIR . '/includes/footer.php';
