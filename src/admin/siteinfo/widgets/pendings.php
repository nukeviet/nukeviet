<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$widget_info = [
    'id' => 'pendings',
    'name' => $nv_Lang->getModule('pendingInfo'),
    'note' => '',
    'func' => function () {
        global $pending_info, $nv_Lang;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('widget_pendings.tpl'));
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('PENDINGS', $pending_info);

        return $tpl->fetch('widget_pendings.tpl');
    }
];
