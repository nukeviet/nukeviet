<?php
if (!defined('NV_IS_MOD_TENMODULE')) {
    exit('Stop!!!');
}

/**
 * Hiển thị trang chính
 * @param array $data
 * @return string
 */
function nv_tenmodule_main($data)
{
    global $lang_module, $lang_global, $module_info;

    // Dùng $module_info['module_theme'] (không phải $module_file) cho đường dẫn tpl ngoài site
    // Fallback về 'default' nếu theme hiện tại chưa có tpl
    $theme = file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/main.tpl')
        ? $module_info['template'] : 'default';

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->parse('main');

    return $xtpl->text('main');
}
