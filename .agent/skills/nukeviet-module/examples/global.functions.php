<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Guard: NV_MAINFILE (giống block của module)
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Có thể dùng PSR-4 class từ thư mục Shared/
use NukeViet\Module\tenmodule\Shared\Helper;

/**
 * Hàm helper dùng chung cả frontend lẫn admin
 */
function nv_tenmodule_get_item($id)
{
    global $db_slave;
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_tenmodule WHERE id = ' . (int) $id;
    return $db_slave->query($sql)->fetch();
}
