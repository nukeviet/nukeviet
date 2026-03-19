<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

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
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('DATA', $data);

    return $tpl->fetch('main.tpl');
}
