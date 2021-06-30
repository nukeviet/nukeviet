<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

$channel = [];
$items = [];

$channel['title'] = $module_info['custom_title'];
$channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$channel['description'] = !empty($module_info['description']) ? $module_info['description'] : $global_config['site_description'];
$atomlink = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'];

$catid = 0;
if (isset($array_op[1])) {
    $alias_cat_url = $array_op[1];
    $cattitle = '';
    foreach ($global_array_cat as $catid_i => $array_cat_i) {
        if ($alias_cat_url == $array_cat_i['alias']) {
            $catid = $catid_i;
            break;
        }
    }
}

$db_slave->sqlreset()
    ->select('id, catid, publtime, title, alias, hometext, homeimgthumb, homeimgfile')
    ->order($order_articles_by . ' DESC')
    ->limit(30);

if (!empty($catid)) {
    $channel['title'] = $module_info['custom_title'] . ' - ' . $global_array_cat[$catid]['title'];
    $channel['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias_cat_url;
    $channel['description'] = $global_array_cat[$catid]['description'];
    $atomlink .= '/' . $alias_cat_url;

    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)->where('status=1');
} else {
    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_rows')->where('status=1 AND inhome=1');
}
if ($module_info['rss']) {
    $result = $db_slave->query($db_slave->sql());
    while (list($id, $catid_i, $publtime, $title, $alias, $hometext, $homeimgthumb, $homeimgfile) = $result->fetch(3)) {
        $catalias = $global_array_cat[$catid_i]['alias'];

        if ($homeimgthumb == 1) {
            // image thumb
            $rimages = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
        } elseif ($homeimgthumb == 2) {
            // image file
            $rimages = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
        } elseif ($homeimgthumb == 3) {
            // image url
            $rimages = $homeimgfile;
        } else {
            // no image
            $rimages = '';
        }
        $rimages = (!empty($rimages)) ? '<img src="' . $rimages . '" width="100" align="left" border="0">' : '';

        $items[] = [
            'title' => $title,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $catalias . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'guid' => $module_name . '_' . $id,
            'description' => $rimages . $hometext,
            'pubdate' => $publtime
        ];
    }
}
nv_rss_generate($channel, $items, $atomlink);
exit();
