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
            'bad.png'
        ),
        E_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ),
        E_PARSE => array(
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ),
        E_NOTICE => array(
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ),
        E_CORE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ),
        E_CORE_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ),
        E_COMPILE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ),
        E_COMPILE_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ),
        E_USER_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ),
        E_USER_WARNING => array(
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        ),
        E_USER_NOTICE => array(
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ),
        E_STRICT => array(
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ),
        E_RECOVERABLE_ERROR => array(
            $nv_Lang->getGlobal('error_error'),
            'bad.png'
        ),
        E_DEPRECATED => array(
            $nv_Lang->getGlobal('error_notice'),
            'comment.png'
        ),
        E_USER_DEPRECATED => array(
            $nv_Lang->getGlobal('error_warning'),
            'warning.png'
        )
    );

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
