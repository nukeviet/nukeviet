<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS')) die('Stop!!!');

$alias = isset($array_op[1]) ? $array_op[1] : "";

if (!preg_match("/^([a-z0-9\-\_\.]+)$/i", $alias)) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
}

$page = 1;
if (isset($array_op[2]) and substr($array_op[2], 0, 5) == 'page-') {
    $page = intval(substr($array_op[2], 5));
}

$catid = 0;
foreach ($nv_laws_listarea as $c) {
    if ($c['alias'] == $alias) {
        $catid = $c['id'];
        break;
    }
}

if (empty($catid)) {
    nv_redirect_location(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true);
}

// Set page title, keywords, description
$page_title = $mod_title = $nv_laws_listarea[$catid]['title'];
$key_words = empty($nv_laws_listarea[$catid]['keywords']) ? $module_info['keywords'] : $nv_laws_listarea[$catid]['keywords'];
$description = empty($nv_laws_listarea[$catid]['introduction']) ? $page_title : $nv_laws_listarea[$catid]['introduction'];

//
$per_page = $nv_laws_setting['numsub'];
$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=area/" . $nv_laws_listarea[$catid]['alias'];

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    $cat = $nv_laws_listarea[$catid];
    $in = "";
    if (empty($cat['subcats'])) {
        $in = " t2.area_id=" . $catid;
    } else {
        $in = $cat['subcats'];
        $in[] = $catid;
        $in = " t2.area_id IN(" . implode(",", $in) . ")";
    }
    
    $order = ($nv_laws_setting['typeview'] == 1) ? "ASC" : "DESC";
    
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row t1 INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_row_area t2 ON t1.id=t2.row_id WHERE status=1 AND" . $in . " ORDER BY addtime " . $order . " LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
    $result = $db->query($sql);
    $query = $db->query("SELECT FOUND_ROWS()");
    $all_page = $query->fetchColumn();
    
    $generate_page = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);
    
    $array_data = array();
    $stt = nv_get_start_id($page, $per_page);
    while ($row = $result->fetch()) {
        $row['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'];
        $row['stt'] = $stt;
        
        if ($nv_laws_setting['down_in_home']) {
            // File download
            if (!empty($row['files'])) {
                $row['files'] = explode(",", $row['files']);
                $files = $row['files'];
                $row['files'] = array();
                
                foreach ($files as $id => $file) {
                    $file_title = basename($file);
                    $row['files'][] = array(
                        "title" => $file_title,
                        "titledown" => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
                        "url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=detail/" . $row['alias'] . "&amp;download=1&amp;id=" . $id
                    );
                }
            }
        }
        
        $array_data[] = $row;
        $stt++;
    }
    
    $contents = nv_theme_laws_area($array_data, $generate_page, $cat);
    
    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';