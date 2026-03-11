<?php
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// Module Admin sử dụng NVSmarty
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->assign('LANG', $lang_module);
$tpl->assign('GLANG', $lang_global);

// Gán biến cho giao diện admin...
// $tpl->assign('VAR_NAME', $var_value);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
