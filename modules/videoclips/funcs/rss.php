<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_VIDEOCLIPS')) die('Stop!!!');

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['atomlink'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss";
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$sql = "SELECT id, addtime, title, alias, hometext, img FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE `status`=1 ORDER BY `addtime` DESC LIMIT 30";

if ($module_info['rss']) {
    $result = $db->query($sql);
    while (list ($id, $publtime, $title, $alias, $hometext, $homeimgfile) = $result->fetch(3)) {
        if (!empty($homeimgfile)) {
            $imageinfo = nv_ImageInfo(NV_ROOTDIR . '/' . $homeimgfile, 120, true, NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name);
            $homeimgfile = $imageinfo['src'];
        }
        
        $rimages = (!empty($homeimgfile)) ? "<img src=\"" . NV_MY_DOMAIN . NV_BASE_SITEURL . $homeimgfile . "\" width=\"100\" border=\"0\" align=\"left\">" : "";
        $items[] = array( //
            'title' => $title, //
            'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias, //
            'guid' => $module_name . '_' . $id, //
            'description' => $rimages . $hometext, //
            'pubdate' => $publtime
        ) //
;
    }
}

nv_rss_generate($channel, $items);
die();

?>