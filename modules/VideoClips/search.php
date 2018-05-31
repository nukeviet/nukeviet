<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_IS_MOD_SEARCH')) die('Stop!!!');

$sql = 'SELECT SQL_CALC_FOUND_ROWS title,alias,hometext
    FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_clip
    WHERE status=1 AND (' . nv_like_logic('title', $dbkeyword, $logic) . '
        OR ' . nv_like_logic('hometext', $dbkeyword, $logic) . '
        OR ' . nv_like_logic('bodytext', $dbkeyword, $logic) . ')
    LIMIT ' . ($page - 1) * $limit . ',' . $limit;

$tmp_re = $db->query($sql);

$result = $db->query('SELECT FOUND_ROWS()');
$num_items = $result->fetchColumn();

if ($num_items) {
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';
    
    while (list ($tilterow, $alias, $content) = $tmp_re->fetch(3)) {
        $url = $link . $alias;
        
        $result_array[] = array(
            'link' => $url,
            'title' => BoldKeywordInStr($tilterow, $key, $logic),
            'content' => BoldKeywordInStr($content, $key, $logic)
        );
    }
}