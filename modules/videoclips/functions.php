<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_VIDEOCLIPS', true);

/**
 * nv_settopics()
 *
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @return
 */
function nv_settopics($id, $list, $name)
{
    global $module_name;
    
    $name = $list[$id]['title'] . " &raquo; " . $name;
    $parentid = $list[$id]['parentid'];
    if ($parentid) $name = nv_settopics($parentid, $list, $name);
    return $name;
}

/**
 * nv_list_topics()
 *
 * @return
 */
function nv_list_topics()
{
    global $db, $module_data, $module_name, $module_info;
    
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE `status`=1 ORDER BY `parentid`,`weight` ASC";
    $result = $db->query($sql);
    
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['id']] = array( //
            'id' => (int) $row['id'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'description' => $row['description'], //
            'parentid' => (int) $row['parentid'], //
            'img' => $row['img'], //
            'subcats' => array(), //
            'keywords' => $row['keywords']
        ) //
;
    }
    
    $list2 = array();
    
    if (!empty($list)) {
        foreach ($list as $row) {
            if (!$row['parentid'] or isset($list[$row['parentid']])) {
                $list2[$row['id']] = $list[$row['id']];
                $list2[$row['id']]['name'] = $list[$row['id']]['title'];
                
                if ($row['parentid']) {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];
                    $list2[$row['id']]['name'] = nv_settopics($row['parentid'], $list, $list2[$row['id']]['name']);
                }
            }
        }
    }
    
    return $list2;
}

/**
 * nv_extKeywords()
 *
 * @param mixed $keywords
 * @return
 */
function nv_extKeywords($keywords)
{
    if (empty($keywords)) return "";
    $keywords = explode(",", $keywords);
    $keywords = array_map("trim", $keywords);
    $keywords = array_unique($keywords);
    $keywords = implode(",", $keywords);
    return $keywords;
}

$configMods = array();
$configMods['otherClipsNum'] = 16; //So video-clip hien thi tren trang chu hoac trang The loai
$configMods['playerAutostart'] = 0; //Co tu dong phat video hay khong
$configMods['playerSkin'] = ""; //Skin cua player
$configMods['commNum'] = "20"; //So comment hien thi mac dinh
$configMods['playerMaxWidth'] = 640; //Chieu rong toi da cua player
if (file_exists(NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php")) {
    require (NV_ROOTDIR . "/" . NV_DATADIR . "/config_module-" . $module_data . ".php");
}
if (!empty($configMods['playerSkin'])) {
    $configMods['playerSkin'] = ",skin:\"" . NV_BASE_SITEURL . NV_ASSETS_DIR . "images/jwplayer/skin/" . $configMods['playerSkin'] . ".zip\"";
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
if (isset($module_info['description'])) $description = $module_info['description'];
$array_mod_title = array();

$topicList = nv_list_topics();
$topicList2 = array();
foreach ($topicList as $key => $_topicList)
    $topicList2[$_topicList['alias']] = $key;
    
    // Chỗ này gây lỗi khi click vào nut LIKE, UNLIKE nên phải bỏ đi
    //if ( isset( $array_op[0] ) and ( $array_op0 = strtolower( $array_op[0] ) ) != $array_op[0] )
    //{
    //	$_tempUrl = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $array_op0;
    //	if ( isset( $array_op[1] ) ) $_tempUrl .= "/" . ( $array_op[1] );
    //	$_tempUrl = nv_url_rewrite( $_tempUrl, 1 );
    //	header( 'Location: ' . $_tempUrl, true, 301 );
    //	exit;
    //}


if (isset($array_op[0]) and !empty($array_op[0]) and !preg_match("/^page\-(\d+)$/", $array_op[0], $matches)) {
    
    if (preg_match('/^(video)\-([a-z0-9\-]+)$/i', $array_op[0], $matches)) {
        $op = 'detail';
        $alias_url = $matches[2];
    } else if (isset($topicList2[$array_op[0]])) {
        $op = 'topic';
    }
}