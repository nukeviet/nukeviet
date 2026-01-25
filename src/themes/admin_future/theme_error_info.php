<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
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
        'fa-solid fa-ban',
        'danger'
    ],
    E_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'fa-solid fa-triangle-exclamation',
        'warning'
    ],
    E_PARSE => [
        $nv_Lang->getGlobal('error_error'),
        'fa-solid fa-ban',
        'danger'
    ],
    E_NOTICE => [
        $nv_Lang->getGlobal('error_notice'),
        'fa-solid fa-circle-exclamation',
        'info'
    ],
    E_CORE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'fa-solid fa-ban',
        'danger'
    ],
    E_CORE_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'fa-solid fa-triangle-exclamation',
        'warning'
    ],
    E_COMPILE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'fa-solid fa-ban',
        'danger'
    ],
    E_COMPILE_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'fa-solid fa-triangle-exclamation',
        'warning'
    ],
    E_USER_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'fa-solid fa-ban',
        'danger'
    ],
    E_USER_WARNING => [
        $nv_Lang->getGlobal('error_warning'),
        'fa-solid fa-triangle-exclamation',
        'warning'
    ],
    E_USER_NOTICE => [
        $nv_Lang->getGlobal('error_notice'),
        'fa-solid fa-circle-exclamation',
        'info'
    ],
    E_RECOVERABLE_ERROR => [
        $nv_Lang->getGlobal('error_error'),
        'fa-solid fa-ban',
        'danger'
    ],
    E_DEPRECATED => [
        $nv_Lang->getGlobal('error_notice'),
        'fa-solid fa-circle-exclamation',
        'info'
    ],
    E_USER_DEPRECATED => [
        $nv_Lang->getGlobal('error_warning'),
        'fa-solid fa-triangle-exclamation',
        'warning'
    ]
];
if (version_compare(PHP_VERSION, '8.4.0', '<')) {
    $errortype[E_STRICT] = [
        $nv_Lang->getGlobal('error_notice'),
        'fa-solid fa-circle-exclamation',
        'info'
    ];
}

$tpl_dir = get_tpl_dir($php_dir, NV_DEFAULT_SITE_THEME, '/system/error_info.tpl');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $tpl_dir . '/system');
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('ERRORS', $error_info);
$tpl->assign('CONFIGS', $errortype);

return $tpl->fetch('error_info.tpl');
