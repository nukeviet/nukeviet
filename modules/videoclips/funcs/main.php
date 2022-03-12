<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS')) {
    die('Stop!!!');
}

$pgnum = 1;
$issetPgnum = false;
if (isset($array_op[0]) and !empty($array_op[0])) {
    unset($matches);
    if (preg_match("/^page\-(\d+)$/", $array_op[0], $matches)) {
        $pgnum = (int) $matches[1];
        $issetPgnum = true;
    } else {
        nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
    }
}
if (isset($array_op[1]) or $pgnum < 1 or ($pgnum < 2 and $issetPgnum)) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
}

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_THEME', $module_info['module_theme']);

if (isset($configMods['idhomeclips']) and $configMods['idhomeclips'] > 0) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_clip a, ' . NV_PREFIXLANG . '_' . $module_data . '_hit b WHERE id=' . intval($configMods['idhomeclips']) . ' AND a.status=1 AND a.id=b.cid LIMIT 1';
    $result = $db->query($sql);
    $clip = $result->fetch();
    if (!empty($clip)) {
        $clip['filepath'] = !empty($clip['internalpath']) ? NV_BASE_SITEURL . $clip['internalpath'] : $clip['externalpath'];
        $clip['url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $clip['alias'] . $global_config['rewrite_exturl'], 1);
        $clip['editUrl'] = nv_url_rewrite(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&op=main&edit&id=" . $clip['id'] . "&redirect=1", 1);

        // kiểm tra có phải youtube
        if (preg_match("/(http(s)?\:)?\/\/([w]{3})?\.youtube[^\/]+\/watch\?v\=([^\&]+)$/isU", $clip['externalpath'], $m)) {
            $xtpl->assign('CODE', $m[4]);
            if ($configMods['playerAutostart'] == 1) $xtpl->assign('autoplay', '&amp;autoplay=1');
            $xtpl->assign('DETAILCONTENT', $clip);
            $xtpl->parse('main.video_youtube');
        } else {
            $xtpl->assign('DETAILCONTENT', $clip);
            $xtpl->parse('main.video_flash');
        }
    }
}

$base_url = array();
$base_url['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url['amp'] = '/page-';

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM " . NV_PREFIXLANG . "_" . $module_data . "_clip a,
    " . NV_PREFIXLANG . "_" . $module_data . "_hit b
    WHERE a.id=b.cid
    AND a.status=1
    ORDER BY a.id DESC
    LIMIT " . (($pgnum - 1) * $configMods['otherClipsNum']) . "," . $configMods['otherClipsNum'];

$array_data = array();
$result = $db->query($sql);
$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();
$all_page = intval($all_page);
if ($all_page) {
    $numClips = 0;
    while ($row = $result->fetch()) {
        $numClips++;
        if (!empty($row['img'] && file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $row['img']))) {
            $row['img'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['img'];
        } elseif (!empty($row['img'] && file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['img']))) {
            $row['img'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['img'];
        } else {
            $row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_info['module_theme'] . "/video.png";
        }
        $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $row['alias'] . $global_config['rewrite_exturl'];
        $row['sortTitle'] = nv_clean60($row['title'], $module_config[$module_name]['clean_title_video']);
        $array_data[$row['id']] = $row;
    }

    $generate_page = nv_generate_page($base_url, $all_page, $configMods['otherClipsNum'], $pgnum);

    if (function_exists('nv_template_' . $configMods['viewtype'])) {
        $xtpl->assign('OTHERCLIPSCONTENT', call_user_func('nv_template_' . $configMods['viewtype'], $array_data, $generate_page));
    }

    if ($pgnum > 1 and $numClips < 1) {
        nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
    }

    $xtpl->parse('main.otherClips');
}

$xtpl->parse('main');
$contents = $xtpl->text("main");

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
