<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 03-05-2010
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    die('Stop!!!');
}

if ($module_config[$m_values['module_name']]['elas_use'] == 1) {

    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$m_values['module_name']]['elas_host'], $module_config[$m_values['module_name']]['elas_port'], $module_config[$m_values['module_name']]['elas_index']);

    $dbkeyword = nv_EncString($dbkeyword);

    $search_elastic = [
        'should' => [
            'multi_match' => [ // dung multi_match:tim kiem theo nhieu truong
                'query' => $dbkeyword, // tim kiem theo tu khoa
                'type' => [
                    'cross_fields'
                ],
                'fields' => [
                    'unsigned_title',
                    'unsigned_hometext',
                    'unsigned_bodyhtml'
                ], // tim kiem theo 3 truong m?c d?nh l� ho?c
                'minimum_should_match' => [
                    '50%'
                ]
            ]
        ]
    ];

    $array_query_elastic = array();
    $array_query_elastic['query']['bool'] = $search_elastic;
    $array_query_elastic['size'] = $limit;
    $array_query_elastic['from'] = ($page - 1) * $limit;

    $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows', $array_query_elastic);

    $num_items = $response['hits']['total'];
    if ($num_items) {
        $array_cat_alias = array();
        $array_cat_alias[0] = 'other';

        $sql_cat = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_cat';
        $re_cat = $db_slave->query($sql_cat);
        while (list ($catid, $alias) = $re_cat->fetch(3)) {
            $array_cat_alias[$catid] = $alias;
        }
        $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

        foreach ($response['hits']['hits'] as $key => $value) {
            $content = $value['_source']['hometext'] . strip_tags($value['_source']['bodyhtml']);
            $url = $link . $array_cat_alias[$value['_source']['catid']] . '/' . $value['_source']['alias'] . '-' . $value['_source']['id'] . $global_config['rewrite_exturl'];
            $result_array[] = array(
                'link' => $url,
                'title' => BoldKeywordInStr($value['_source']['title'], $key, $logic),
                'content' => BoldKeywordInStr($content, $key, $logic)
            );
        }
    }
} else {
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows r')
        ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_detail c ON (r.id=c.id)')
        ->where('(' . nv_like_logic('r.title', $dbkeywordhtml, $logic) . ' OR ' . nv_like_logic('r.hometext', $dbkeyword, $logic) . ' OR ' . nv_like_logic('c.bodyhtml', $dbkeyword, $logic) . ')	AND r.status= 1');

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();

    if ($num_items) {
        $array_cat_alias = array();
        $array_cat_alias[0] = 'other';

        $sql_cat = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_cat';
        $re_cat = $db_slave->query($sql_cat);
        while (list ($catid, $alias) = $re_cat->fetch(3)) {
            $array_cat_alias[$catid] = $alias;
        }

        $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=';

        $db_slave->select('r.id, r.title, r.alias, r.catid, r.hometext, c.bodyhtml')
            ->order('publtime DESC')
            ->limit($limit)
            ->offset(($page - 1) * $limit);
        $result = $db_slave->query($db_slave->sql());
        while (list ($id, $tilterow, $alias, $catid, $hometext, $bodytext) = $result->fetch(3)) {
            $content = strip_tags($hometext, 'br') . strip_tags($bodytext);
            $url = $link . $array_cat_alias[$catid] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'];
            $result_array[] = array(
                'link' => $url,
                'title' => BoldKeywordInStr($tilterow, $key, $logic),
                'content' => BoldKeywordInStr($content, $key, $logic)
            );
        }
    }
}