<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS')) die('Stop!!!');

$id = isset($array_op[1]) ? intval($array_op[1]) : 0;

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_signer WHERE id=' . $id;
$result = $db->query($sql);
$signer = $result->fetch();
if (empty($signer)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
}

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=signer/' . $signer['id'] . '/' . change_alias($signer['title']);
$page = 1;
if (isset($array_op[3]) and substr($array_op[3], 0, 5) == 'page-') {
    $page = intval(substr($array_op[3], 5));
    $base_url = $base_url . '/page-' . $page;
}
$base_url_rewrite = nv_url_rewrite($base_url, true);
if ($_SERVER['REQUEST_URI'] == $base_url_rewrite) {
    $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
} elseif (NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
    http_response_code(301);
    nv_redirect_location($base_url_rewrite);
} else {
    $canonicalUrl = $base_url_rewrite;
}

$per_page = $nv_laws_setting['numsub'];

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file)) != false) {
        $contents = $cache;
    }
}

if (empty($contents)) {
    $order = ($nv_laws_setting['typeview'] == 1) ? 'ASC' : 'DESC';
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE status=1 AND sgid=' . $signer['id'] . ' ORDER BY addtime ' . $order . ' LIMIT ' . $per_page . ' OFFSET ' . ($page - 1) * $per_page;
    $result = $db->query($sql);
    $query = $db->query('SELECT FOUND_ROWS()');
    $all_page = $query->fetchColumn();
    
    $generate_page = nv_alias_page($page_title, $base_url, $all_page, $per_page, $page);
    
    $array_data = array();
    $stt = $page == 1 ? 1 : ($page * $per_page) - ($per_page == 1 ? 0 : 1);
    while ($row = $result->fetch()) {
        $row['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $row['alias'];
        $row['stt'] = $stt;
        
        if ($nv_laws_setting['down_in_home']) {
            // File download
            if (!empty($row['files'])) {
                $row['files'] = explode(',', $row['files']);
                $files = $row['files'];
                $row['files'] = array();
                
                foreach ($files as $id => $file) {
                    $file_title = basename($file);
                    $row['files'][] = array(
                        'title' => $file_title,
                        'titledown' => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
                        'url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $row['alias'] . '&amp;download=1&amp;id=' . $id
                    );
                }
            }
        }
        
        $array_data[] = $row;
        $stt++;
    }
    
    $contents = nv_theme_laws_signer($array_data, $generate_page, $signer);
    
    $page_title = $mod_title = $signer['title'];
    $key_words = $module_info['keywords'];
    $description = $signer['title'] . ' - ' . $signer['offices'] . ' - ' . $signer['positions'];
    
    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';