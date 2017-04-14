<?php

/**
 * @Project  NUKEVIET V3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate  05-05-2010
 */

if (! defined('NV_IS_MOD_SEARCH')) {
    die('Stop!!!');
}

/**
 * nv_faq_list_cats()
 * 
 * @param mixed $module_data
 * @return
 */
function nv_faq_list_cats($module_data)
{
    global $db;

    $sql = "SELECT id, title, alias, groups_view FROM " . NV_PREFIXLANG . "_" . $module_data . "_categories WHERE status=1";
    $result = $db->query($sql);

    $list = array();
    while ($row = $result->fetch()) {
        if (nv_user_in_groups($row['groups_view'])) {
            $list[$row['id']] = array(
                'id' => ( int )$row['id'],
                'title' => $row['title'],
                'alias' => $row['alias']
            );
        }
    }

    return $list;
}

$list_cats = nv_faq_list_cats($m_values['module_data']);
$in = implode(",", array_keys($list_cats));
$num_items = 0;
$result_array = array();

if (!empty($in)) {
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $m_values['module_data'])
        ->where("catid IN (" . $in . ") 
    	AND 
    	(" . nv_like_logic('question', $dbkeyword, $logic) . " 
    	OR " . nv_like_logic('answer', $dbkeyword, $logic) . ")");
    
    $num_items = $db->query($db->sql())->fetchColumn();
    
    if ($num_items) {
        $db->select('id, question, answer, catid')
            ->order('id DESC')
            ->limit($limit)
            ->offset(($page - 1) * $limit);
                
        $tmp_re = $db->query($db->sql());
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';
        
        while (list($id, $question, $answer, $catid) = $tmp_re->fetch(3)) {
            $result_array[] = array(
                'link' => $link . $list_cats[$catid]['alias'] . '#faq' . $id,
                'title' => BoldKeywordInStr($question, $key, $logic),
                'content' => BoldKeywordInStr($answer, $key, $logic)
            );
        }
    }
}
