<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-5-2010 0:14
 */

if (!defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

/**
 * GetSourceNews()
 *
 * @param mixed $sourceid
 * @return
 */
function GetSourceNews($sourceid)
{
    global $db_slave, $module_data;

    if ($sourceid > 0) {
        $sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $sourceid;
        $re = $db_slave->query($sql);

        if (list ($title) = $re->fetch(3)) {
            return $title;
        }
    }
    return '-/-';
}

/**
 * BoldKeywordInStr()
 *
 * @param mixed $str
 * @param mixed $keyword
 * @return
 */
function BoldKeywordInStr($str, $keyword)
{
    $str = nv_br2nl($str);
    $str = nv_nl2br($str, ' ');
    $str = nv_unhtmlspecialchars(strip_tags(trim($str)));
    $str = nv_clean60($str, 300);

    if (!empty($keyword)) {
        $patterns = [
            nv_preg_quote($keyword)
        ];
        $patterns[] = function_exists('searchPatternByLang') ? searchPatternByLang(nv_preg_quote(nv_EncString($keyword))) : nv_preg_quote(nv_EncString($keyword));
        $patterns = array_unique($patterns);
        $patterns = '/(' . implode('|', $patterns) . ')/uis';

        $str = preg_replace($patterns, '<span class="keyword">$1</span>', $str);
    }
    return $str;
}

$key = $nv_Request->get_title('q', 'get', '');
$key = str_replace(["'", '"', '<', '>', "&#039;", "&quot;", "&lt;", "&gt;"], '', $key);
$key = str_replace('+', ' ', urldecode($key));
$key = trim(nv_substr($key, 0, NV_MAX_SEARCH_LENGTH));
$keyhtml = nv_htmlspecialchars($key);

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
if (!empty($key)) {
    $page_url .= '&q=' . urlencode($key);
}

$choose = $nv_Request->get_int('choose', 'get', 0);
if (!empty($choose)) {
    $page_url .= '&choose=' . $choose;
}

$catid = $nv_Request->get_int('catid', 'get', 0);
if (!empty($catid)) {
    $page_url .= '&catid=' . $catid;
}
$from_date = $nv_Request->get_title('from_date', 'get', '', 0);
$date_array['from_date'] = preg_replace('/[^0-9]/', '.', urldecode($from_date));
if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['from_date'])) {
    $page_url .= '&from_date=' . $date_array['from_date'];
}

$to_date = $nv_Request->get_title('to_date', 'get', '', 0);
$date_array['to_date'] = preg_replace('/[^0-9]/', '.', urldecode($to_date));
if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['to_date'])) {
    $page_url .= '&to_date=' . $date_array['to_date'];
}

$base_url = $page_url;
$page = $nv_Request->get_int('page', 'get', 1);
if ($page > 1) {
    $page_url .= '&page=' . $page;
}

$canonicalUrl = getCanonicalUrl($page_url, true);

$array_cat_search = [];
$array_cat_search[0]['title'] = $lang_module['search_all'];
foreach ($global_array_cat as $arr_cat_i) {
    $array_cat_search[$arr_cat_i['catid']] = [
        'catid' => $arr_cat_i['catid'],
        'title' => $arr_cat_i['title'],
        'select' => ($arr_cat_i['catid'] == $catid) ? 'selected' : ''
    ];
}

$contents = call_user_func('search_theme', $key, $choose, $date_array, $array_cat_search);
$where = '';
$tbl_src = '';
if (empty($key) and ($catid == 0) and empty($from_date) and empty($to_date)) {
    $contents .= '<div class="alert alert-danger">' . $lang_module['empty_data_search'] . '</div>';
} elseif (!empty($key) and nv_strlen($key) < NV_MIN_SEARCH_LENGTH) {
    $contents .= '<div class="alert alert-danger">' . sprintf($lang_module['search_word_short'], NV_MIN_SEARCH_LENGTH) . '</div>';
} else {
    $dbkey = $db_slave->dblikeescape($key);
    $dbkeyhtml = $db_slave->dblikeescape($keyhtml);
    $internal_authors = [];

    if ($module_config[$module_name]['elas_use'] == 1) {
        // ket noi den csdl elastic
        $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);

        $dbkeyhtml = nv_EncString($dbkeyhtml);
        if ($choose == 1) {
            $search_elastic = [
                'should' => [
                    'multi_match' => [ // dung multi_match:tim kiem theo nhieu truong
                        'query' => $dbkeyhtml, // tim kiem theo t? kh�a
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
        } else if ($choose == 2) {
            // match:tim kiem theo 1 truong
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_author' => $dbkeyhtml
                    ]
                ]
            ];
            // Tim bai viet co internal author trung voi ket qua tim kiem
            if ($db->dbtype == 'mysql' and function_exists('searchKeywordforSQL')) {
                $where = 'alias REGEXP :q_alias OR pseudonym REGEXP :q_pseudonym';
                $_dbkeyhtml = searchKeywordforSQL($dbkeyhtml);
            } else {
                $where = 'alias LIKE :q_alias OR pseudonym LIKE :q_pseudonym';
                $_dbkeyhtml = '%' . $dbkeyhtml . '%';
            }
            $db->sqlreset()
                ->select('id')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
                ->where($where);
            $sth = $db->prepare($db->sql());
            $sth->bindValue(':q_alias', $_dbkeyhtml, PDO::PARAM_STR);
            $sth->bindValue(':q_pseudonym', $_dbkeyhtml, PDO::PARAM_STR);
            $sth->execute();
            $match = [];
            while ($id_search = $sth->fetch(3)) {
                $match[] = [
                    'match' => [
                        'id' => $id_search[0]
                    ]
                ];
            }
            if (empty($match)) {
                $match[] = [
                    'match' => [
                        'id' => -1
                    ]
                ];
            }
            $search_elastic_user['filter']['or'] = $match;
            $search_elastic = array_merge($search_elastic, $search_elastic_user);
        } else if ($choose == 3) {
            $qurl = $key;
            $url_info = parse_url($qurl);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $qurl = $url_info['scheme'] . '://' . $url_info['host'];
            }
            $search_elastic = [
                'should' => [
                    'match' => [
                        'sourcetext' => $db_slave->dblikeescape($qurl)
                    ]
                ]
            ];
        } else {

            $search_elastic = [
                'should' => [
                    'multi_match' => [ // dung multi_match:tim kiem theo nhieu truong
                        'query' => $dbkeyhtml, // tim kiem theo tu khoa
                        'type' => [
                            'cross_fields'
                        ],
                        'fields' => [
                            'unsigned_title',
                            'unsigned_hometext',
                            'unsigned_bodyhtml',
                            'sourcetext',
                            'unsigned_author'
                        ], // tim kiem theo 3 truong m?c d?nh l� ho?c
                        'minimum_should_match' => [
                            '50%'
                        ]
                    ]
                ]
            ];
            // Tim bai viet co internal author trung voi ket qua tim kiem
            if ($db->dbtype == 'mysql' and function_exists('searchKeywordforSQL')) {
                $where = 'alias REGEXP :q_alias OR pseudonym REGEXP :q_pseudonym';
                $_dbkeyhtml = searchKeywordforSQL($dbkeyhtml);
            } else {
                $where = 'alias LIKE :q_alias OR pseudonym LIKE :q_pseudonym';
                $_dbkeyhtml = '%' . $dbkeyhtml . '%';
            }
            $db->sqlreset()
                ->select('id')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
                ->where($where);
            $sth = $db->prepare($db->sql());
            $sth->bindValue(':q_alias', $_dbkeyhtml, PDO::PARAM_STR);
            $sth->bindValue(':q_pseudonym', $_dbkeyhtml, PDO::PARAM_STR);
            $sth->execute();
            $match = [];
            while ($id_search = $sth->fetch(3)) {
                $match[] = [
                    'match' => [
                        'id' => $id_search[0]
                    ]
                ];
            }
            if (empty($match)) {
                $match[] = [
                    'match' => [
                        'id' => -1
                    ]
                ];
            }
            $search_elastic_user['filter']['or'] = $match;
            $search_elastic = array_merge($search_elastic, $search_elastic_user);
        }
        if ($catid > 0) {
            $search_elastic_catid = [
                'filter' => [
                    'term' => [
                        'catid' => $catid
                    ]
                ]
            ];
        }

        $todate_elastic = [];
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['to_date'], $m)) {
            $todate_elastic = [
                'lte' => mktime(23, 59, 59, $m[2], $m[1], $m[3])
            ];
        }
        $fromdate_elastic = [];
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['from_date'], $m)) {
            $fromdate_elastic = [
                'gte' => mktime(0, 0, 0, $m[2], $m[1], $m[3])
            ];
        }

        $array_query_elastic = [];
        $array_query_elastic['query']['bool'] = $search_elastic;
        $array_query_elastic['size'] = $per_page;
        $array_query_elastic['from'] = ($page - 1) * $per_page;

        if ($date_elastic = array_merge($todate_elastic, $fromdate_elastic)) {
            $array_query_elastic['query']['bool']['must']['range']['publtime'] = $date_elastic;
            $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);
        } elseif ($todate_elastic) {
            $array_query_elastic['query']['bool']['must']['range']['publtime'] = $todate_elastic;
            $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);
        } elseif ($fromdate_elastic) {
            $array_query_elastic['query']['bool']['must']['range']['publtime'] = $fromdate_elastic;
            $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);
        } else {
            $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);
        }
        $numRecord = $response['hits']['total'];
        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($numRecord / $per_page), $base_url, '&page-', $prevPage, $nextPage);

        foreach ($response['hits']['hits'] as $key => $value) {
            $homeimgthumb = $value['_source']['homeimgthumb'];
            if ($homeimgthumb == 1) {
                // image thumb
                $img_src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $value['_source']['homeimgfile'];
            } elseif ($homeimgthumb == 2) {
                // image file
                $img_src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $value['_source']['homeimgfile'];
            } elseif ($homeimgthumb == 3) {
                // image url
                $img_src = $value['_source']['homeimgfile'];
            } elseif (!empty($show_no_image)) {
                // no image
                $img_src = NV_BASE_SITEURL . $show_no_image;
            } else {
                $img_src = '';
            }
            $array_content[] = [
                'id' => $value['_source']['id'],
                'title' => $value['_source']['title'],
                'alias' => $value['_source']['alias'],
                'catid' => $value['_source']['catid'],
                'hometext' => $value['_source']['hometext'],
                'author' => $value['_source']['author'],
                'publtime' => $value['_source']['publtime'],
                'homeimgfile' => $img_src,
                'sourceid' => $value['_source']['sourceid'],
                'external_link' => $value['_source']['external_link']
            ];
            $internal_authors[] = $value['_source']['id'];
        }
    } else {
        if ($choose == 1) {
            if ($db->dbtype == 'mysql' and function_exists('searchKeywordforSQL')) {
                $_dbkey = searchKeywordforSQL($dbkey);
                $_dbkeyhtml = searchKeywordforSQL($dbkeyhtml);
                $where = "AND ( tb1.title REGEXP '" . $_dbkeyhtml . "' OR tb1.hometext REGEXP '" . $_dbkey . "' OR tb2.bodyhtml REGEXP '" . $_dbkey . "' ) ";
            } else {
                $_dbkey = '%' . $dbkey . '%';
                $_dbkeyhtml = '%' . $dbkeyhtml . '%';
                $where = "AND ( tb1.title LIKE '" . $_dbkeyhtml . "' OR tb1.hometext LIKE '" . $_dbkey . "' OR tb2.bodyhtml LIKE '" . $_dbkey . "' ) ";
            }
            $tbl_src = ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail tb2 ON ( tb1.id = tb2.id ) ';
            
        } elseif ($choose == 2) {
            if ($db->dbtype == 'mysql' and function_exists('searchKeywordforSQL')) {
                $_dbkeyhtml = searchKeywordforSQL($dbkeyhtml);
                $where = "AND ( tb1.author REGEXP '" . $_dbkeyhtml . "'
                    OR a.alias REGEXP '" . $_dbkeyhtml . "'
                    OR a.pseudonym REGEXP '" . $_dbkeyhtml . "') ";
            } else {
                $_dbkeyhtml = '%' . $dbkeyhtml . '%';
                $where = "AND ( tb1.author LIKE '" . $_dbkeyhtml . "'
                    OR a.alias LIKE '" . $_dbkeyhtml . "'
                    OR a.pseudonym LIKE '" . $_dbkeyhtml . "') ";
            }
            $tbl_src = ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON (tb1.id = a.id) ';
        } elseif ($choose == 3) {
            $qurl = $key;
            $url_info = parse_url($qurl);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $qurl = $url_info['scheme'] . '://' . $url_info['host'];
            }
            $where = "AND (tb1.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title LIKE '%" . $db_slave->dblikeescape($dbkey) . "%' OR link LIKE '%" . $db_slave->dblikeescape($qurl) . "%'))";
        } else {
            $qurl = $key;
            $url_info = parse_url($qurl);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $qurl = $url_info['scheme'] . '://' . $url_info['host'];
            }
            if ($db->dbtype == 'mysql' and function_exists('searchKeywordforSQL')) {
                $_dbkey = searchKeywordforSQL($dbkey);
                $_dbkeyhtml = searchKeywordforSQL($dbkeyhtml);
                $where = " AND (( tb1.title REGEXP '" . $_dbkeyhtml . "' 
                    OR tb1.hometext REGEXP '" . $_dbkey . "' 
                    OR tb1.author REGEXP '" . $_dbkeyhtml . "' 
                    OR tb2.bodyhtml REGEXP '" . $_dbkey . "'
                    OR a.alias REGEXP '" . $_dbkeyhtml . "'
                    OR a.pseudonym REGEXP '" . $_dbkeyhtml . "') 
                    OR (tb1.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title LIKE '%" . $db_slave->dblikeescape($dbkey) . "%' OR link LIKE '%" . $db_slave->dblikeescape($qurl) . "%')))";
            } else {
                $_dbkey = '%' . $dbkey . '%';
                $_dbkeyhtml = '%' . $dbkeyhtml . '%';
                $where = " AND (( tb1.title LIKE '" . $_dbkeyhtml . "' 
                    OR tb1.hometext LIKE '" . $_dbkey . "' 
                    OR tb1.author LIKE '" . $_dbkeyhtml . "' 
                    OR tb2.bodyhtml LIKE '" . $_dbkey . "'
                    OR a.alias LIKE '" . $_dbkeyhtml . "'
                    OR a.pseudonym LIKE '" . $_dbkeyhtml . "') 
                    OR (tb1.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title LIKE '%" . $db_slave->dblikeescape($dbkey) . "%' OR link LIKE '%" . $db_slave->dblikeescape($qurl) . "%')))";
            }
            $tbl_src = ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail tb2 ON ( tb1.id = tb2.id )';
            $tbl_src .= ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON (tb1.id = a.id)';
        }

        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['to_date'], $m)) {
            $where .= ' AND tb1.publtime <=' . mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        }
        if (preg_match('/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/', $date_array['from_date'], $m)) {
            $where .= ' AND tb1.publtime >=' . mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        }

        if ($catid > 0) {
            $table_search = NV_PREFIXLANG . '_' . $module_data . '_' . $catid;
        } else {
            $table_search = NV_PREFIXLANG . '_' . $module_data . '_rows';
        }

        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from($table_search . ' as tb1 ' . $tbl_src)
            ->where('tb1.status=1 ' . $where);

        $numRecord = $db_slave->query($db_slave->sql())
            ->fetchColumn();
        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($numRecord / $per_page), $base_url, '&page=', $prevPage, $nextPage);

        $db_slave->select('tb1.id,tb1.title,tb1.alias,tb1.catid,tb1.hometext,tb1.author,tb1.publtime,tb1.homeimgfile, tb1.homeimgthumb,tb1.sourceid,tb1.external_link')
            ->order('tb1.' . $order_articles_by . ' DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());

        $array_content = [];
        $show_no_image = $module_config[$module_name]['show_no_image'];

        while (list ($id, $title, $alias, $catid, $hometext, $author, $publtime, $homeimgfile, $homeimgthumb, $sourceid, $external_link) = $result->fetch(3)) {
            if ($homeimgthumb == 1) {
                // image thumb
                $img_src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $homeimgfile;
            } elseif ($homeimgthumb == 2) {
                // image file
                $img_src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $homeimgfile;
            } elseif ($homeimgthumb == 3) {
                // image url
                $img_src = $homeimgfile;
            } elseif (!empty($show_no_image)) {
                // no image
                $img_src = NV_BASE_SITEURL . $show_no_image;
            } else {
                $img_src = '';
            }
            $array_content[] = [
                'id' => $id,
                'title' => $title,
                'alias' => $alias,
                'catid' => $catid,
                'hometext' => $hometext,
                'author' => $author,
                'publtime' => $publtime,
                'homeimgfile' => $img_src,
                'sourceid' => $sourceid,
                'external_link' => $external_link
            ];
            $internal_authors[] = $id;
        }
    }

    if (!empty($internal_authors)) {
        $internal_authors = implode(',', $internal_authors);
        $db->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
            ->where("id IN (" . $internal_authors . ")");
        $result = $db->query($db->sql());
        $internal_authors = [];
        while ($row = $result->fetch()) {
            !isset($internal_authors[$row['id']]) && $internal_authors[$row['id']] = [];
            $internal_authors[$row['id']][] = [
                'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=author/' . $row['alias'],
                'pseudonym' => $row['pseudonym']
            ];
        }
    }

    $contents .= search_result_theme($key, $numRecord, $per_page, $page, $array_content, $catid, $internal_authors);
}

if (empty($key)) {
    $page_title = $lang_module['search_title'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
} else {
    $page_title = $key . NV_TITLEBAR_DEFIS . $lang_module['search_title'];
    if ($page > 2) {
        $page_title .= NV_TITLEBAR_DEFIS . $lang_global['page'] . ' ' . $page;
    }
    $page_title .= NV_TITLEBAR_DEFIS . $module_info['custom_title'];
}

$key_words = $description = 'no';
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
