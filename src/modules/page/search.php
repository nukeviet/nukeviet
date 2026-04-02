<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    exit('Stop!!!');
}

$sql_where = 'status=1 AND (' . nv_like_logic('title', $dbkeyword, $logic) . ' OR ' . nv_like_logic('description', $dbkeyword, $logic) . ' OR ' . nv_like_logic('bodytext', $dbkeyword, $logic) . ')';
$num_items = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . ' WHERE ' . $sql_where)->fetchColumn();

if ($num_items) {
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

    $stmt = $db->prepare('SELECT id, title, alias, description, bodytext FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . ' WHERE ' . $sql_where . ' LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $result_array[] = [
            'link' => $link . $row['alias'] . $global_config['rewrite_exturl'],
            'title' => BoldKeywordInStr($row['title'], $key, $logic),
            'content' => BoldKeywordInStr($row['description'] . ' ' . $row['bodytext'], $key, $logic)
        ];
    }
    $stmt->closeCursor();
}
