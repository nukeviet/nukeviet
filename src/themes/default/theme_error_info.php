<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
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
            'bad.png'
        ],
        E_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ],
        E_PARSE => [
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ],
        E_NOTICE => [
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ],
        E_CORE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ],
        E_CORE_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ],
        E_COMPILE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ],
        E_COMPILE_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ],
        E_USER_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ],
        E_USER_WARNING => [
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ],
        E_USER_NOTICE => [
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ],
        E_STRICT => [
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ],
        E_RECOVERABLE_ERROR => [
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ],
        E_DEPRECATED => [
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ],
        E_USER_DEPRECATED => [
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ]
    ];

    $image_path = NV_BASE_SITEURL . 'themes/' . $template . '/images/icons/';

    $xtpl = new XTemplate('error_info.tpl', NV_ROOTDIR . '/themes/' . $template . '/system');
    $xtpl->assign('TPL_E_CAPTION', $nv_Lang->getGlobal('error_info_caption'));

    foreach ($error_info as $key => $value) {
        $xtpl->assign('TPL_E_ALT', $errortype[$value['errno']][0]);
        $xtpl->assign('TPL_E_SRC', $image_path . $errortype[$value['errno']][1]);
        $xtpl->assign('TPL_E_ERRNO', $errortype[$value['errno']][0]);
        $xtpl->assign('TPL_E_MESS', $value['info']);
        $xtpl->set_autoreset();
        $xtpl->parse('error_info.error_item');
    }

    $xtpl->parse('error_info');

    return $xtpl->text('error_info');
}
