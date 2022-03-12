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

$topic = $topicList[$topicList2[$array_op[0]]];

$page_title = $topic['title'] . ' - ' . $page_title;
if (!empty($topic['keywords'])) $key_words = nv_extKeywords($topic['keywords'] . (!empty($key_words) ? ',' . $key_words : ''));
if (!empty($topic['description'])) $description = $topic['description'];

if ($topic['parentid']) {
    $array_mod_title[] = array(
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $topicList[$topic['parentid']]['alias'],
        'title' => $topicList[$topic['parentid']]['title']
    );
}

$array_mod_title[] = array(
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $topic['alias'],
    'title' => $topic['title']
);

$pgnum = 1;
$issetPgnum = false;
if (isset($array_op[1])) {
    unset($matches);
    if (preg_match("/^page\-(\d+)$/i", $array_op[1], $matches)) {
        $pgnum = (int) $matches[1];
        $issetPgnum = true;
    } else {
        $_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $topic['alias'];
        $_tempUrl = nv_url_rewrite($_tempUrl, 1);
        header('Location: ' . $_tempUrl, true, 301);
        exit();
    }
}
if (isset($array_op[2]) or $pgnum < 1 or ($pgnum < 2 and $issetPgnum)) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name);
}

$base_url = array();
$base_url['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $topic['alias'];
$base_url['amp'] = "/page-";

$sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` a,
    `" . NV_PREFIXLANG . "_" . $module_data . "_hit` b
    WHERE a.tid=" . $db->quote($topic['id']) . "
    AND a.id=b.cid
    AND a.status=1
    ORDER BY a.id DESC
    LIMIT " . (($pgnum - 1) * $configMods['otherClipsNum']) . "," . $configMods['otherClipsNum'];

$xtpl = new XTemplate("topic.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULECONFIG', $configMods);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_THEME', $module_info['module_theme']);
$xtpl->assign('TOPIC', $topic);

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
