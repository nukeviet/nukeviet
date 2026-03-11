<?php
if (!defined('NV_IS_MOD_TENMODULE')) {
    exit('Stop!!!');
}

// Logic xử lý dữ liệu — có thể viết inline hoặc gọi hàm từ theme.php
$contents = nv_tenmodule_main($data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
