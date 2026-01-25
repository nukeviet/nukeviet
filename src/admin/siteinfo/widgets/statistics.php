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
    'id' => 'statistics',
    'name' => $nv_Lang->getModule('moduleInfo'),
    'note' => '',
    'func' => function () {
        global $stat_info, $nv_Lang;

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('widget_statistics.tpl'));
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('STATS', $stat_info);

        return $tpl->fetch('widget_statistics.tpl');
    }
];
