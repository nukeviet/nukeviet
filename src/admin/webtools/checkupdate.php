<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('checkupdate');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('checkupdate.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);

// Check update qua ajax
if ($nv_Request->isset_request('i', 'get')) {
    $i = $nv_Request->get_string('i', 'get');

    if ($i == 'sysUpd' or $i == 'sysUpdRef') {
        // Check phiên bản hệ thống
        $new_version = ($i == 'sysUpd') ? nv_geVersion(28800) : nv_geVersion(120);

        $error = '';
        if ($new_version === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($new_version)) {
            $error = $new_version;
        }
        if (!empty($error)) {
            $html = nv_theme_alert($nv_Lang->getModule('checkSystem'), $error, 'danger');
            nv_htmlOutput($html);
        }

        $version_value = (string) $new_version->version;
        $updateable = (string) $new_version->updateable;
        $updatepackage = (string) $new_version->updatepackage;

        $version = [
            'version' => $version_value,
            'name' => (string) $new_version->name,
            'date' => nv_datetime_format(strtotime((string) $new_version->date)),
            'info' => (string) $new_version->message,
            'need_update' => (nv_version_compare($global_config['version'], (string) $new_version->version) < 0),
            'updateable' => $updateable,
            'link_update' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . $updateable . '&amp;package=' . $updatepackage . '&amp;checksess=' . md5($updateable . $updatepackage . NV_CHECK_SESSION),
            'link' => (string) $new_version->link,
            'updatepackage' => $updatepackage
        ];
        $tpl->assign('VERSION', $version);

        clearstatcache();
        $sysUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
        $tpl->assign('SYSUPDDATE', nv_datetime_format($sysUpdDate));

        $contents = $tpl->fetch('checkupdate-sys.tpl');
        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    // Check phiên bản các ứng dụng
    if ($i == 'extUpd' or $i == 'extUpdRef') {
        $exts = ($i == 'extUpd') ? nv_getExtVersion(28800) : nv_getExtVersion(120);

        $error = '';
        if ($exts === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($exts)) {
            $error = $exts;
        }
        if (!empty($error)) {
            $html = nv_theme_alert($nv_Lang->getModule('checkExtensions'), $error, 'danger');
            nv_htmlOutput($html);
        }

        clearstatcache();
        $extUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml');
        $exts = $exts->xpath('extension');
        $static_exts = (isset($global_config['static_exts']) and is_array($global_config['static_exts'])) ? $global_config['static_exts'] : [];

        $tpl->assign('EXTUPDDATE', nv_datetime_format($extUpdDate));

        $array = [];
        foreach ($exts as $extname => $values) {
            $ext_type = (string) $values->type;
            $ext_name = (string) $values->name;

            if (!isset($static_exts[$ext_type]) or !in_array($ext_name, $static_exts[$ext_type], true)) {
                $value = [
                    'id' => (int) $values->id,
                    'type' => $ext_type,
                    'type_text' => $nv_Lang->existsModule('extType_' . $ext_type) ? $nv_Lang->getModule('extType_' . $ext_type) : $ext_type,
                    'name' => $ext_name,
                    'version' => (string) $values->version,
                    'date' => (string) $values->date,
                    'date_show' => !empty($values->date) ? nv_datetime_format(strtotime((string) $values->date)) : '',
                    'new_version' => (string) $values->new_version,
                    'new_date' => (string) $values->new_date,
                    'new_date_show' => !empty($values->new_date) ? nv_datetime_format(strtotime((string) $values->new_date)) : '',
                    'author' => (string) $values->author,
                    'license' => (string) $values->license,
                    'mode' => (string) $values->mode,
                    'message' => (string) $values->message,
                    'link' => (string) $values->link,
                    'support' => (string) $values->support,
                    'updateable' => [],
                    'origin' => ((string) $values->origin) == 'true' ? true : false,
                    'status_level' => 1,
                    'status_note' => '',
                    'note_level' => 0,
                    'up_need' => 0,
                    'up_new_version' => [],
                    'up_link' => '',
                ];

                // Xử lý lấy phiên bản update
                $updateables = $values->xpath('updateable/upds/upd');

                if (!empty($updateables)) {
                    foreach ($updateables as $updateable) {
                        $value['updateable'][] = [
                            'fid' => (string) $updateable->upd_fid,
                            'old' => explode(',', (string) $updateable->upd_old),
                            'new' => (string) $updateable->upd_new,
                        ];
                    }
                }
                unset($updateables, $updateable);

                // Thông tin cập nhật phiên bản
                if (!empty($value['new_version']) and nv_version_compare($value['version'], $value['new_version']) < 0) {
                    $value['status_level'] = 1;
                    $value['status_note'] = $nv_Lang->getModule('extNote4');

                    $updateVersion = [];
                    foreach ($value['updateable'] as $updateable) {
                        if (in_array($value['version'], $updateable['old'], true)) {
                            if (empty($updateVersion) or nv_version_compare($updateVersion['new'], $updateable['new']) < 0) {
                                $updateVersion = $updateable;
                            }
                        }
                    }
                    $value['up_need'] = 1;
                    $value['up_new_version'] = $updateVersion;
                    if (!empty($updateVersion)) {
                        $value['up_link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=update&amp;eid=' . $value['id'] . '&amp;fid=' . $updateVersion['fid'] . '&amp;checksess=' . md5($value['id'] . $updateVersion['fid'] . NV_CHECK_SESSION);
                    }
                } elseif (!$value['origin']) {
                    $value['status_level'] = 2;
                    $value['status_note'] = $nv_Lang->getModule('extNote1');
                    $value['note_level'] = 2;
                } else {
                    $value['status_level'] = 3;
                    $value['status_note'] = $nv_Lang->getModule('extNote5');
                }

                $array[] = $value;
            }
        }
        $tpl->assign('EXTS', $array);

        $contents = $tpl->fetch('checkupdate-ext.tpl');
        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    nv_htmlOutput('');
}

$contents = $tpl->fetch('checkupdate.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
