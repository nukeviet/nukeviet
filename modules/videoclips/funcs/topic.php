<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS')) die('Stop!!!');

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

$_otherTopic = array(
    'main' => array(),
    'sub' => array()
);
foreach ($topicList as $__k => $__v) {
    if ($__k != $topic['id']) {
        if ($__v['parentid'] == $topic['id'])
            $_otherTopic['sub'][] = $topicList[$__k];
        elseif ($__v['parentid'] == $topic['parentid'])
            $_otherTopic['main'][] = $topicList[$__k];
    }
}

$pgnum = 0;
if (isset($array_op[1])) {
    unset($matches);
    if (preg_match("/^page\-(\d+)$/i", $array_op[1], $matches)) {
        $pgnum = (int) $matches[1];
    } else {
        $_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $topic['alias'];
        $_tempUrl = nv_url_rewrite($_tempUrl, 1);
        header('Location: ' . $_tempUrl, true, 301);
        exit();
    }
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
    LIMIT " . $pgnum . "," . $configMods['otherClipsNum'];

$xtpl = new XTemplate("topic.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULECONFIG', $configMods);

if (!empty($_otherTopic['main']) or !empty($_otherTopic['sub'])) {
    if (!empty($_otherTopic['main'])) {
        $xtpl->assign('OTHETP', $lang_module['otherTopic']);
        foreach ($_otherTopic['main'] as $_ottp) {
            $href = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $_ottp['alias'];
            $xtpl->assign('OTHERTOPIC', array(
                'href' => $href,
                'title' => $_ottp['title'],
                'img' => $_ottp['img']
            ));
            if (!empty($_ottp['img'])) {
                $xtpl->parse('main.topicListMain.row1.img1');
            }
            if (!empty($_ottp['subcats'])) {
                $xtpl->parse('main.topicList.topicListMain.row1.iss1');
            }
            $xtpl->parse('main.topicList.topicListMain.row1');
        }
        $xtpl->parse('main.topicList.topicListMain');
    }
    
    if (!empty($_otherTopic['sub'])) {
        foreach ($_otherTopic['sub'] as $_ottp) {
            $href = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $_ottp['alias'];
            $xtpl->assign('OTHERSUBTOPIC', array(
                'href' => $href,
                'title' => $_ottp['title'],
                'img' => $_ottp['img']
            ));
            if (!empty($_ottp['img'])) {
                $xtpl->parse('main.topicList.topicListSub.row2.img2');
            }
            if (!empty($_ottp['subcats'])) {
                $xtpl->parse('main.topicList.topicListSub.row2.iss2');
            }
            $xtpl->parse('main.topicList.topicListSub.row2');
        }
        $xtpl->parse('main.topicList.topicListSub');
    }
    
    $xtpl->parse('main.topicList');
}

$result = $db->query($sql);
$res = $db->query("SELECT FOUND_ROWS()");
$all_page = $res->fetchColumn();
$all_page = intval($all_page);
if ($all_page) {
    $i = 1;
    while ($row = $result->fetch()) {
        if (!empty($row['img'])) {
            $imageinfo = nv_ImageInfo(NV_ROOTDIR . '/' . $row['img'], 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name);
            $row['img'] = $imageinfo['src'];
        } else {
            $row['img'] = NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/video.png";
        }
        $row['href'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=video-" . $row['alias'];
        $row['sortTitle'] = nv_clean60($row['title'], 20);
        $xtpl->assign('OTHERCLIPSCONTENT', $row);
        if ($i == 4) {
            $i = 0;
            $xtpl->parse('main.otherClips.otherClipsContent.clearfix');
        }
        $xtpl->parse('main.otherClips.otherClipsContent');
        ++$i;
    }
    
    $generate_page = nv_generate_page($base_url, $all_page, $configMods['otherClipsNum'], $pgnum);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.otherClips.nv_generate_page');
    }
    
    $xtpl->parse('main.otherClips');
}

$xtpl->parse('main');
$contents = $xtpl->text("main");

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';