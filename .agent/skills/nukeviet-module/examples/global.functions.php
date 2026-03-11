<?php
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
