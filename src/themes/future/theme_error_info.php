<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/*
 * Tệp này không bắt buộc trong giao diện nếu không có hệ thống lấy từ giao diện default
 * Giữ tệp này, chỉnh sửa nó nếu muốn phát triển giao diện thông báo lỗi riêng
 */

$errortype = [
    E_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'warning'
    ],
    E_PARSE => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_NOTICE => [
        $nv_Lang->getGlobal('error_notice'),
        'info'
    ],
    E_CORE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_CORE_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'warning'
    ],
    E_COMPILE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_COMPILE_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'warning'
    ],
    E_USER_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_USER_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'warning'
    ],
    E_USER_NOTICE => [
        $nv_Lang->getGlobal('error_notice'),
        'info'
    ],
    E_RECOVERABLE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'danger'
    ],
    E_DEPRECATED => [
        $nv_Lang->getGlobal('error_notice'),
        'info'
    ],
    E_USER_DEPRECATED => [
        $nv_Lang->getGlobal('error_warning'),
        'warning'
    ]
];
if (version_compare(PHP_VERSION, '8.4.0', '<')) {
    $errortype[E_STRICT] = [
        $nv_Lang->getGlobal('error_notice'),
        'info'
    ];
}

$tpl_dir = get_tpl_dir($php_dir, 'default', '/system/error_info.tpl');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $tpl_dir . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('ERRORTYPE', $errortype);
$tpl->assign('ERROR_INFO', $error_info);
$tpl->assign('TEMPLATE', $tpl_dir);

return $tpl->fetch('error_info.tpl');
