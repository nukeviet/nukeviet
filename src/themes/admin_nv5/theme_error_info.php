<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/**
 * @param array $error_info
 * @return string
 */
function nv_error_info_theme($error_info)
{
    global $nv_Lang, $template;

    $errortype = [
        E_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ],
        E_PARSE => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_NOTICE => [
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ],
        E_CORE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_CORE_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ],
        E_COMPILE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_COMPILE_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ],
        E_USER_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_USER_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ],
        E_USER_NOTICE => [
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ],
        E_STRICT => [
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ],
        E_RECOVERABLE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ],
        E_DEPRECATED => [
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ],
        E_USER_DEPRECATED => [
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ]
    ];

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/system');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $error_info);
    $tpl->assign('ERROR_TYPE', $errortype);

    return $tpl->fetch('error_info.tpl');
}
