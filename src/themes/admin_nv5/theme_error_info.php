<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE') or !defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * @param array $error_info
 * @return string
 */
function nv_error_info_theme($error_info)
{
    global $nv_Lang, $template;

    $errortype = array(
        E_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ),
        E_PARSE => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_NOTICE => array(
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ),
        E_CORE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_CORE_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ),
        E_COMPILE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_COMPILE_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ),
        E_USER_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_USER_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        ),
        E_USER_NOTICE => array(
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ),
        E_STRICT => array(
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ),
        E_RECOVERABLE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            '<span class="text-danger"><i class="fas fa-times"></i></span>'
        ),
        E_DEPRECATED => array(
            $nv_Lang->getGlobal('error_notice'),
            '<span class="text-primary"><i class="fas fa-exclamation-circle"></i></span>'
        ),
        E_USER_DEPRECATED => array(
            $nv_Lang->getGlobal('error_warning'),
            '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>'
        )
    );

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/system');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $error_info);
    $tpl->assign('ERROR_TYPE', $errortype);

    return $tpl->fetch('error_info.tpl');
}
