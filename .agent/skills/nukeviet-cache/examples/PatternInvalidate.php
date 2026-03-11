<?php
// Sau khi UPDATE/INSERT/DELETE thành công
if ($sth->execute()) {
    $nv_Cache->delMod($module_name);
    // Hoặc nếu module có liên quan đến module khác (vd: menu)
    $nv_Cache->delMod('menu');
}
