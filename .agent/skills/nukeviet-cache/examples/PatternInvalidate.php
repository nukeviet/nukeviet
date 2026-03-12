<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Sau khi UPDATE/INSERT/DELETE thành công
if ($sth->execute()) {
    $nv_Cache->delMod($module_name);
    // Hoặc nếu module có liên quan đến module khác (vd: menu)
    $nv_Cache->delMod('menu');
}
