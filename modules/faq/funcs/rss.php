<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (! defined('NV_IS_MOD_FAQ')) {
    die('Stop!!!');
}

$channel = array();
$items = array();

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];

$list_cats = nv_list_cats();

if (! empty($list_cats)) {
    $catalias = isset($array_op[1]) ? $array_op[1] : "";
    $catid = 0;
    if (! empty($catalias)) {
        foreach ($list_cats as $c) {
            if ($c['alias'] == $catalias) {
                $catid = $c['id'];
                break;
            }
        }
    }
    
    if ($catid > 0) {
        $channel['title'] = $module_name . ' - ' . $list_cats[$catid]['title'];
        $channel['link'] = NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$catid]['alias'];
        $channel['description'] = $list_cats[$catid]['description'];
        
        $sql = "SELECT id, catid, title, question, addtime 
        FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $catid . " 
        AND status=1 ORDER BY weight ASC LIMIT 30";
    } else {
        $in = array_keys($list_cats);
        $in = implode(",", $in);
        $sql = "SELECT id, catid, title, question, addtime 
        FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid IN (" . $in . ") 
        AND status=1 ORDER BY weight ASC LIMIT 30";
    }
    if ($module_info['rss']) {
        if (($result = $db->query($sql)) !== false) {
            while (list($id, $cid, $title, $question, $addtime) = $result->fetch(3)) {
                $items[] = array(
                    'title' => $title,
                    'link' => NV_MY_DOMAIN . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$cid]['alias'] . "#faq" . $id,
                    'guid' => $module_name . '_' . $id,
                    'description' => $lang_module['faq_question'] . ": " . $question,
                    'pubdate' => $addtime
                );
            }
        }
    }
}

nv_rss_generate($channel, $items);
die();
