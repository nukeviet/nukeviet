<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21/12/2010, 8:10
 */

if (!defined('NV_IS_FILE_WEBTOOLS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('checkupdate');
$contents = '';

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('BASE_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);

if ($nv_Request->isset_request('i', 'get')) {
    $i = $nv_Request->get_string('i', 'get');

    if ($i == 'sysUpd' or $i == 'sysUpdRef') {
        $values = [];
        $values['userVersion'] = $global_config['version'];
        $values['isNewVersion'] = false;
        $new_version = ($i == 'sysUpd') ? nv_geVersion(28800) : nv_geVersion(120);

        $error = '';
        if ($new_version === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($new_version)) {
            $error = $new_version;
        }

        if (empty($error)) {
            $values['onlineVersion'] = sprintf($nv_Lang->getModule('newVersion_detail'), (string)$new_version->version, (string)$new_version->name, nv_date('d/m/Y H:i', strtotime((string)$new_version->date)));

            if (nv_version_compare($global_config['version'], (string)$new_version->version) < 0) {
                $values['isNewVersion'] = true;
                $values['new_version_info'] = (string)$new_version->message;

                // Allow auto update to newest version
                if ((string)$new_version->version == (string)$new_version->updateable) {
                    $values['new_version_link'] = sprintf($nv_Lang->getModule('newVersion_info1'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ((string)$new_version->updateable) . '&amp;package=' . ((string)$new_version->updatepackage) . '&amp;checksess=' . md5(((string)$new_version->updateable) . ((string)$new_version->updatepackage) . NV_CHECK_SESSION));
                } elseif (((string)$new_version->updateable) != '') {
                    $values['new_version_link'] = sprintf($nv_Lang->getModule('newVersion_info2'), ((string)$new_version->updateable), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getupdate&amp;version=' . ((string)$new_version->updateable) . '&amp;package=' . ((string)$new_version->updatepackage) . '&amp;checksess=' . md5(((string)$new_version->updateable) . ((string)$new_version->updatepackage) . NV_CHECK_SESSION));
                } else {
                    $values['new_version_link'] = sprintf($nv_Lang->getModule('newVersion_info3'), (string)$new_version->link);
                }
            }

            clearstatcache();
            $sysUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/nukeviet.version.' . NV_LANG_INTERFACE . '.xml');
            $values['timestamp'] = nv_date('d/m/Y H:i', $sysUpdDate);
        }

        $tpl->assign('ERROR', $error);
        $tpl->assign('DATA', $values);

        $html = $tpl->fetch('checkupdate_sys.tpl');
        nv_htmlOutput($html);
    } elseif ($i == 'extUpd' or $i == 'extUpdRef') {
        $exts = ($i == 'extUpd') ? nv_getExtVersion(28800) : nv_getExtVersion(120);

        $error = '';
        if ($exts === false) {
            $error = $nv_Lang->getModule('error_unknow');
        } elseif (is_string($exts)) {
            $error = $exts;
        }

        if (empty($error)) {
            clearstatcache();
            $extUpdDate = filemtime(NV_ROOTDIR . '/' . NV_CACHEDIR . '/extensions.version.' . NV_LANG_INTERFACE . '.xml');
            $exts = $exts->xpath('extension');
            $static_exts = (isset($global_config['static_exts']) and is_array($global_config['static_exts'])) ? $global_config['static_exts'] : [];

            $array_exts = [];
            $stt = 0;

            foreach ($exts as $extname => $values) {
                $ext_type = (string)$values->type;
                $ext_name = (string)$values->name;

                if (!isset($static_exts[$ext_type]) or !in_array($ext_name, $static_exts[$ext_type])) {
                    $array_exts[$stt] = [];
                    $value = array(
                        'id' => (int)$values->id,
                        'type' => $ext_type,
                        'name' => $ext_name,
                        'version' => (string)$values->version,
                        'date' => (string)$values->date,
                        'new_version' => (string)$values->new_version,
                        'new_date' => (string)$values->new_date,
                        'author' => (string)$values->author,
                        'license' => (string)$values->license,
                        'mode' => (string)$values->mode,
                        'message' => (string)$values->message,
                        'link' => (string)$values->link,
                        'support' => (string)$values->support,
                        'updateable' => [],
                        'origin' => ((string)$values->origin) == 'true' ? true : false,
                    );

                    // Xu ly update
                    $updateables = $values->xpath('updateable/upds/upd');

                    if (!empty($updateables)) {
                        foreach ($updateables as $updateable) {
                            $value['updateable'][] = array(
                                'fid' => (string)$updateable->upd_fid,
                                'old' => explode(',', (string)$updateable->upd_old),
                                'new' => (string)$updateable->upd_new,
                            );
                        }
                    }
                    unset($updateables, $updateable);

                    $info = $nv_Lang->getModule('userVersion') . ': ';
                    $info .= !empty($value['version']) ? $value['version'] : 'n/a';
                    $info .= '; ' . $nv_Lang->getModule('onlineVersion') . ': ';
                    $info .= !empty($value['new_version']) ? $value['new_version'] : ((!empty($value['version']) and $value['origin']) ? $value['version'] : 'n/a');

                    $tooltip = [];
                    $tooltip[] = array('title' => $nv_Lang->getModule('userVersion'), 'content' => (!empty($value['version']) ? $value['version'] : 'n/a') . (!empty($value['date']) ? ' (' . nv_date('d/m/Y H:i', strtotime($value['date'])) . ')' : ''));
                    $tooltip[] = array('title' => $nv_Lang->getModule('onlineVersion'), 'content' => (!empty($value['new_version']) ? $value['new_version'] : ((!empty($value['version']) and $value['origin']) ? $value['version'] : 'n/a')) . (!empty($value['new_date']) ? ' (' . nv_date('d/m/Y H:i', strtotime($value['new_date'])) . ')' : ''));

                    if (!empty($value['author'])) {
                        $tooltip[] = array('title' => $nv_Lang->getModule('extAuthor'), 'content' => htmlspecialchars($value['author']));
                    }

                    if (!empty($value['license'])) {
                        $tooltip[] = array('title' => $nv_Lang->getModule('extLicense'), 'content' => $value['license']);
                    }

                    if (!empty($value['mode'])) {
                        $tooltip[] = array('title' => $nv_Lang->getModule('extMode'), 'content' => $value['mode'] == 'sys' ? $nv_Lang->getModule('extModeSys') : $nv_Lang->getModule('extModeOther'));
                    }

                    if (!empty($value['link'])) {
                        $tooltip[] = array('title' => $nv_Lang->getModule('extLink'), 'content' => "<a href=\"" . $value['link'] . "\">" . $value['link'] . "</a>");
                    }

                    if (!empty($value['support'])) {
                        $tooltip[] = array('title' => $nv_Lang->getModule('extSupport'), 'content' => "<a href=\"" . $value['support'] . "\">" . $value['support'] . "</a>");
                    }

                    $array_exts[$stt]['name'] = $value['name'];
                    $array_exts[$stt]['type'] = $nv_Lang->getModule('extType_' . $value['type']);
                    $array_exts[$stt]['info'] = $info;
                    $array_exts[$stt]['tip'] = $tooltip;
                    $array_exts[$stt]['isinvalid'] = (!isset($value['version']));
                    $array_exts[$stt]['upmess'] = '';
                    $array_exts[$stt]['upmode'] = '';

                    // Thong tin cap nhat
                    if (!empty($value['new_version']) and nv_version_compare($value['version'], $value['new_version']) < 0) {
                        $note = $nv_Lang->getModule('extNote4');
                        $icon = 'warning';

                        $updateVersion = [];

                        foreach ($value['updateable'] as $updateable) {
                            if (in_array($value['version'], $updateable['old'])) {
                                if (empty($updateVersion) or nv_version_compare($updateVersion['new'], $updateable['new']) < 0) {
                                    $updateVersion = $updateable;
                                }
                            }
                        }

                        if (empty($updateVersion)) {
                            $array_exts[$stt]['upmess'] = $nv_Lang->getModule('extUpdNote1', $value['link']);
                            $array_exts[$stt]['upmode'] = 'warning';
                        } elseif ($updateVersion['new'] != $value['new_version']) {
                            $array_exts[$stt]['upmess'] = sprintf($nv_Lang->getModule('extUpdNote2'), $updateVersion['new'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=update&amp;eid=' . $value['id'] . '&amp;fid=' . $updateVersion['fid'] . '&amp;checksess=' . md5($value['id'] . $updateVersion['fid'] . NV_CHECK_SESSION));
                            $array_exts[$stt]['upmode'] = 'success';
                        } else {
                            $array_exts[$stt]['upmess'] = sprintf($nv_Lang->getModule('extUpdNote3'), NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=update&amp;eid=' . $value['id'] . '&amp;fid=' . $updateVersion['fid'] . '&amp;checksess=' . md5($value['id'] . $updateVersion['fid'] . NV_CHECK_SESSION));
                            $array_exts[$stt]['upmode'] = 'success';
                        }
                    } elseif (!$value['origin']) {
                        $note = $nv_Lang->getModule('extNote1');
                        $icon = 'danger';
                        $array_exts[$stt]['isinvalid'] = true;
                    } else {
                        $note = $nv_Lang->getModule('extNote5');
                        $icon = 'success';
                    }

                    $array_exts[$stt]['note'] = $note;
                    $array_exts[$stt]['icon'] = $icon;
                    $stt++;
                }
            }

            $tpl->assign('ARRAY_EXTS', $array_exts);
            $tpl->assign('EXTUPDDATE', nv_date('d/m/Y H:i', $extUpdDate));
            $tpl->assign('LINKNEWEXT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=extensions&amp;' . NV_OP_VARIABLE . '=newest');
        }

        $tpl->assign('ERROR', $error);

        $html = $tpl->fetch('checkupdate_ext.tpl');
        nv_htmlOutput($html);
    }
    nv_htmlOutput('');
}

$contents = $tpl->fetch('checkupdate.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
