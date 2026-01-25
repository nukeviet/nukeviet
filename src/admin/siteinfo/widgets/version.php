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

if (!defined('NV_IS_GODADMIN')) {
    return;
}

$widget_info = [
    'id' => 'version',
    'name' => $nv_Lang->getModule('version'),
    'note' => '',
    'func' => function () {
        global $nv_Lang, $global_config;

        $field = [];
        $field[] = ['key' => $nv_Lang->getModule('version_user'), 'value' => $global_config['version']];
        if (file_exists(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml')) {
            $new_version = simplexml_load_file(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
        } else {
            $new_version = [];
        }

        $info = '';
        if (!empty($new_version)) {
            $field[] = [
                'key' => $nv_Lang->getModule('version_news'),
                'value' => $nv_Lang->getModule('newVersion_detail', (string) $new_version->version, nv_datetime_format(strtotime($new_version->date)))
            ];

            if (nv_version_compare($global_config['version'], $new_version->version) < 0) {
                $info = $nv_Lang->getModule('newVersion_info', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=webtools&amp;' . NV_OP_VARIABLE . '=checkupdate');
            }
        }

        $tpl = new \NukeViet\Template\NVSmarty();
        $tpl->setTemplateDir(get_module_tpl_dir('widget_version.tpl'));
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('FIELDS', $field);
        $tpl->assign('INFO', $info);

        return $tpl->fetch('widget_version.tpl');
    }
];
