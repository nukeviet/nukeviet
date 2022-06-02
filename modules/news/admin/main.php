<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['content_list'];
$stype = $nv_Request->get_string('stype', 'get', '-');
$sstatus = $nv_Request->get_int('sstatus', 'get', -1);
$catid = $nv_Request->get_int('catid', 'get', 0);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'get', $per_page_old);
$num_items = $nv_Request->get_int('num_items', 'get', 0);
$search_type_date = $nv_Request->get_title('type_date', 'get', 'addtime');
$search_time_from = $nv_Request->get_title('search_time_from', 'get', '');
$search_time_to = $nv_Request->get_title('search_time_to', 'get', '');

if ($per_page < 1 or $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}
if (!in_array($search_type_date, ['addtime', 'publtime', 'exptime'], true)) {
    $search_type_date = 'addtime';
}
if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search_time_from, $m)) {
    $search_time_from = mktime(0, 0, 0, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $search_time_from = 0;
}
if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search_time_to, $m)) {
    $search_time_to = mktime(23, 59, 59, intval($m[2]), intval($m[1]), intval($m[3]));
} else {
    $search_time_to = 0;
}

$q = $nv_Request->get_title('q', 'get', '');
$q = str_replace('+', ' ', $q);
$qhtml = nv_htmlspecialchars($q);

$order_articles = 0;
if ($NV_IS_ADMIN_MODULE and $module_config[$module_name]['order_articles'] and empty($q) and $sstatus == -1) {
    $order_articles = 1;

    $_weight_new = $nv_Request->get_int('order_articles_new', 'post', 0);
    $_id = $nv_Request->get_int('order_articles_id', 'post', 0);
    if ($_id > 0 and $_weight_new > 0) {
        $sql = 'SELECT weight, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $_id;
        $_row1 = $db->query($sql)->fetch();
        if (!empty($_row1)) {
            $_weight1 = min($_weight_new, $_row1['weight']);
            $_weight2 = max($_weight_new, $_row1['weight']);
            if ($_weight_new > $_row1['weight']) {
                // Kiểm tra không cho set weight lơn hơn maxweight
                $maxweight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows')->fetchColumn();
                if ($_weight_new > $maxweight) {
                    $_weight_new = $maxweight;
                }
            }

            $sql = 'SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE weight BETWEEN ' . $_weight1 . '  AND ' . $_weight2 . ' AND id!=' . $_id . ' ORDER BY weight ASC, publtime ASC';
            $result = $db->query($sql);
            $weight = $_weight1;
            while ($_row2 = $result->fetch()) {
                if ($weight == $_weight_new) {
                    ++$weight;
                }
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $_row2['id']);
                $_array_catid = explode(',', $_row2['listcatid']);
                foreach ($_array_catid as $_catid) {
                    try {
                        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . (int) $_catid . ' SET weight=' . $weight . ' WHERE id=' . $_row2['id']);
                    } catch (PDOException $e) {
                    }
                }
                ++$weight;
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $_weight_new . ' WHERE id=' . $_id);
            $_array_catid = explode(',', $_row1['listcatid']);
            foreach ($_array_catid as $_catid) {
                try {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . (int) $_catid . ' SET weight=' . $_weight_new . ' WHERE id=' . $_id);
                } catch (PDOException $e) {
                }
            }
            $nv_Cache->delMod($module_name);
        }
    }
}

$ordername = ($module_config[$module_name]['order_articles'] == 1) ? 'weight' : 'publtime';
$ordername = $nv_Request->get_string('ordername', 'get', $ordername);

$order = $nv_Request->get_string('order', 'get') == 'asc' ? 'asc' : 'desc';

$val_cat_content = [];
$val_cat_content[] = [
    'value' => 0,
    'selected' => ($catid == 0) ? ' selected="selected"' : '',
    'title' => $lang_module['search_cat_all']
];

$array_cat_view = [];
$check_declined = false;
foreach ($global_array_cat as $catid_i => $array_value) {
    $lev_i = $array_value['lev'];
    $check_cat = false;
    if (defined('NV_IS_ADMIN_MODULE')) {
        $check_cat = true;
    } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
        $_cat_admin_i = $array_cat_admin[$admin_id][$catid_i];
        if ($_cat_admin_i['admin'] == 1) {
            $check_cat = true;
            $check_declined = true;
        } elseif ($_cat_admin_i['add_content'] == 1) {
            $check_cat = true;
        } elseif ($_cat_admin_i['pub_content'] == 1 or $_cat_admin_i['app_content'] == 1) {
            $check_cat = true;
            $check_declined = true;
        } elseif ($_cat_admin_i['edit_content'] == 1) {
            $check_cat = true;
        } elseif ($_cat_admin_i['del_content'] == 1) {
            $check_cat = true;
        }
    }

    if ($check_cat) {
        $xtitle_i = '';
        if ($lev_i > 0) {
            $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
            for ($i = 1; $i <= $lev_i; ++$i) {
                $xtitle_i .= '---';
            }
            $xtitle_i .= '>&nbsp;';
        }
        $xtitle_i .= $array_value['title'];
        $sl = '';
        if ($catid_i == $catid) {
            $sl = ' selected="selected"';
        }
        $val_cat_content[] = [
            'value' => $catid_i,
            'selected' => $sl,
            'title' => $xtitle_i
        ];
        $array_cat_view[] = $catid_i;
    }
}
if (!defined('NV_IS_ADMIN_MODULE') and $catid > 0 and !in_array($catid, array_map('intval', $array_cat_view), true)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
}
$array_search = [
    '-' => '---' . $lang_module['search_type'] . '---',
    'title' => $lang_module['search_title'],
    'bodytext' => $lang_module['search_bodytext'],
    'author' => $lang_module['search_author'],
    'admin_id' => $lang_module['search_admin'],
    'sourcetext' => $lang_module['sources']
];
$array_in_ordername = [
    'title',
    'publtime',
    'exptime',
    'hitstotal',
    'hitscm'
];
$array_status_view = [
    '-' => '---' . $lang_module['search_status'] . '---',
    '5' => $lang_module['status_5'],
    '1' => $lang_module['status_1'],
    '0' => $lang_module['status_0'],
    '6' => $lang_module['status_6'],
    '4' => $lang_module['status_4'],
    '2' => $lang_module['status_2'],
    '3' => $lang_module['status_3']
];
$array_status_class = [
    '5' => 'danger',
    '1' => '',
    '0' => 'warning',
    '6' => 'warning',
    '4' => 'info',
    '2' => 'success',
    '3' => 'danger'
];

$_permission_action = [];
$array_list_action = [
    'delete' => $lang_global['delete'],
    're-published' => $lang_module['re_published'],
    'publtime' => $lang_module['publtime_action'],
    'stop' => $lang_module['status_0'],
    'waiting' => $lang_module['status_action_0']
];

// Chuyen sang cho duyet
if (defined('NV_IS_ADMIN_MODULE')) {
    $array_list_action['declined'] = $lang_module['declined'];
    $array_list_action['block'] = $lang_module['addtoblock'];
    $array_list_action['addtotopics'] = $lang_module['addtotopics'];
    $array_list_action['move'] = $lang_module['move'];
} elseif ($check_declined) { // Neu co quyen duyet bai thi
    $array_list_action['declined'] = $lang_module['declined'];
}

if (!in_array($stype, array_keys($array_search), true)) {
    $stype = '-';
}
if ($sstatus < 0 or ($sstatus > 10 and $sstatus != ($global_code_defined['row_locked_status'] + 1))) {
    $sstatus = -1;
}
// Fix error https://github.com/nukeviet/nukeviet/issues/3135
// Tu php 8.x doi so $strict cua function in_array co gi tri la TRUE
if (!in_array($ordername, $array_in_ordername, true)) {
    $ordername = 'id';
}
if ($catid == 0) {
    $from = NV_PREFIXLANG . '_' . $module_data . '_rows r';
} else {
    $from = NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' r';
}
$where = [];
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_title('checkss', 'get', '');

if (($module_config[$module_name]['elas_use'] == 1) and $checkss == NV_CHECK_SESSION) {
    // Ket noi den csdl elastic
    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);

    $search_elastic = [];
    // Tim kiem theo bodytext,author,title
    $key_elastic_search = nv_EncString($db_slave->dblikeescape($q));

    if ($stype == 'bodytext' or $stype == 'author' or $stype == 'title') {
        if ($stype == 'bodytext') {
            // match:tim kiem theo 1 truong
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_bodyhtml' => $key_elastic_search
                    ]
                ]
            ];
        } elseif ($stype == 'author') {
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_author' => $key_elastic_search
                    ]
                ]
            ];
            // Tim bai viet co internal author trung voi ket qua tim kiem
            $db->sqlreset()
                ->select('id')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
                ->where('alias LIKE :q_alias OR pseudonym LIKE :q_pseudonym');

            $sth = $db->prepare($db->sql());
            $sth->bindValue(':q_alias', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
            $sth->bindValue(':q_pseudonym', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
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
        } elseif ($stype == 'title') {
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_title' => $key_elastic_search
                    ]
                ]
            ];
        }
    } elseif ($stype == 'sourcetext') {
        $qurl = $q;
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
    } elseif ($stype == 'admin_id') {
        $db->sqlreset()
            ->select('userid')
            ->from(NV_USERS_GLOBALTABLE)
            ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->execute();
        $admin_id_search = [];
        $match = [];
        while ($admin_id_search = $sth->fetch(3)) {
            $match[] = [
                'match' => [
                    'admin_id' => $admin_id_search[0]
                ]
            ];
        }
        $result = count($match);
        if ($result == 0) {
            $match[] = [
                'match' => [
                    'admin_id' => -1
                ]
            ];
        }
        $search_elastic_user['filter']['or'] = $match;
        $search_elastic = array_merge($search_elastic, $search_elastic_user);
    } else {
        $key_search = nv_EncString($db_slave->dblikeescape($q));
        $search_elastic = [
            'should' => [
                'multi_match' => [
                    'query' => $key_search,
                    'type' => [
                        'cross_fields'
                    ],
                    'fields' => [
                        'unsigned_title',
                        'unsigned_bodyhtml',
                        'unsigned_author'
                    ],
                    'minimum_should_match' => [
                        '50%'
                    ]
                ]
            ]
        ];
        // Tim bai viet co internal author trung voi ket qua tim kiem
        $db->sqlreset()
            ->select('id')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
            ->where('alias LIKE :q_alias OR pseudonym LIKE :q_pseudonym');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_alias', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_pseudonym', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->execute();
        $match = [];
        while ($id_search = $sth->fetch(3)) {
            $match[] = [
                'match' => [
                    'id' => $id_search[0]
                ]
            ];
        }
        if (!empty($match)) {
            $search_elastic_user['filter']['or'] = $match;
            $search_elastic = array_merge($search_elastic, $search_elastic_user);
        }
        // tim tat ca cac admin_id c� username=$db_slave->dblikeescape($qhtml) ho?c first_name=$db_slave->dblikeescape($qhtml)
        $db->sqlreset()
            ->select('userid')
            ->from(NV_USERS_GLOBALTABLE)
            ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->execute();
        $admin_id_search = [];
        // search elastic theo admin_id v?a t�m dc
        $match = [];
        while ($admin_id_search = $sth->fetch(3)) {
            $match[] = [
                'match' => [
                    'admin_id' => $admin_id_search[0]
                ]
            ];
        }
        $result = count($match);

        if ($result > 0) {
            $search_elastic_user['filter']['or'] = $match;
            $search_elastic = array_merge($search_elastic, $search_elastic_user);
        }
    }

    if (!empty($search_time_from)) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search_time_from, $m)) {
            $match[]['range'][$search_type_date] = [
                'gte' => mktime(00, 00, 00, $m[2], $m[1], $m[3])
            ];
        }
    }

    if (!empty($search_time_to)) {
        if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search_time_to, $m)) {
            $match[]['range'][$search_type_date] = [
                'lte' => mktime(23, 59, 59, $m[2], $m[1], $m[3])
            ];
        }
    }

    if ($catid != 0) {
        $search_elastic_catid = [
            'must' => [
                'match' => [
                    'catid' => $catid
                ]
            ]
        ];
        if (!empty($q)) {
            $search_elastic = array_merge($search_elastic, $search_elastic_catid);
        } else {
            $search_elastic = $search_elastic_catid;
        }
    }

    if ($sstatus != -1) {
        if ($sstatus > $global_code_defined['row_locked_status']) {
            $search_elastic_status = [
                'filter' => [
                    'range' => [
                        'status' => [
                            'gt' => $global_code_defined['row_locked_status']
                        ]
                    ]
                ]
            ];
        } else {
            $search_elastic_status = [
                'filter' => [
                    'match' => [
                        'status' => $sstatus
                    ]
                ]
            ];
        }
        if (!empty($q)) {
            $search_elastic = array_merge($search_elastic, $search_elastic_status);
        } else {
            $search_elastic = $search_elastic_status;
        }
    }

    $array_query_elastic = [];
    $array_query_elastic['query']['bool'] = $search_elastic;
    $array_query_elastic['size'] = $per_page;
    $array_query_elastic['from'] = ($page - 1) * $per_page;

    $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);

    // so dong du lieu lay dc,c?n s?a $num_items=s? dong d? li?u
    $num_checkss = md5($num_items . NV_CHECK_SESSION);
    if ($num_checkss != $nv_Request->get_string('num_checkss', 'get', '')) {
        // print_r($expression)
        $num_items = $response['hits']['total'];
        $num_checkss = md5($num_items . NV_CHECK_SESSION); // ?c?n s?a
    }
    $base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
    if ($catid) {
        $base_url_mod .= '&amp;catid=' . $catid;
    }

    if (!empty($q)) {
        $base_url_mod .= '&amp;q=' . urlencode($q);
    }

    $base_url_mod .= '&amp;stype=' . $stype . '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

    // hien thi du lieu
    $data = $array_ids = $array_userid = [];
    foreach ($response['hits']['hits'] as $key => $value) {
        $array_list_elastic_search = [
            $value['_source']['id'],
            $value['_source']['catid'],
            $value['_source']['listcatid'],
            $value['_source']['admin_id'],
            $value['_source']['title'],
            $value['_source']['alias'],
            $value['_source']['status'],
            $value['_source']['addtime'],
            $value['_source']['edittime'],
            $value['_source']['publtime'],
            $value['_source']['exptime'],
            $value['_source']['hitstotal'],
            $value['_source']['hitscm'],
            $value['_source']['admin_id'],
            $value['_source']['author']
        ];
        list($id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $addtime, $edittime, $publtime, $exptime, $hitstotal, $hitscm, $_userid, $author) = $array_list_elastic_search;
        $publtime = nv_date('H:i d/m/y', $publtime);
        $title = nv_clean60($title);
        if ($catid > 0) {
            $catid_i = $catid;
        }

        $check_permission_edit = $check_permission_delete = false;

        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_permission_edit = $check_permission_delete = true;
        } else {
            $array_temp = explode(',', $listcatid);
            $check_edit = $check_del = 0;

            foreach ($array_temp as $catid_i) {
                if (isset($array_cat_admin[$admin_id][$catid_i])) {
                    if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                        ++$check_edit;
                        ++$check_del;
                        $_permission_action['publtime'] = true;
                        $_permission_action['re-published'] = true;
                        $_permission_action['exptime'] = true;
                        $_permission_action['declined'] = true;
                    } else {
                        if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                            ++$check_edit;
                            if ($status) {
                                $_permission_action['exptime'] = true;
                            }
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and $status == 0) {
                            ++$check_edit;
                            $_permission_action['publtime'] = true;
                            $_permission_action['re-published'] = true;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_edit;
                            $_permission_action['waiting'] = true;
                        }

                        if ($array_cat_admin[$admin_id][$catid_i]['del_content'] == 1) {
                            ++$check_del;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_del;
                            $_permission_action['waiting'] = true;
                        }
                    }
                }
            }

            if ($check_edit == sizeof($array_temp)) {
                $check_permission_edit = true;
            }

            if ($check_del == sizeof($array_temp)) {
                $check_permission_delete = true;
            }
        }

        $admin_funcs = [];
        if ($check_permission_edit) {
            $admin_funcs['edit'] = nv_link_edit_page($id);
        }
        if ($check_permission_delete) {
            $admin_funcs['delete'] = nv_link_delete_page($id);
            $_permission_action['delete'] = true;
        }
        $data[$id] = [
            'id' => $id,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'title' => $title,
            'addtime' => $addtime,
            'edittime' => $edittime,
            'publtime' => $publtime,
            'status_id' => $status,
            'status' => $status > $global_code_defined['row_locked_status'] ? $lang_module['content_locked_bycat'] : $lang_module['status_' . $status],
            'class' => $status > $global_code_defined['row_locked_status'] ? $array_status_class['4'] : $array_status_class[$status],
            'userid' => $_userid,
            'hitstotal' => number_format($hitstotal, 0, ',', '.'),
            'hitscm' => number_format($hitscm, 0, ',', '.'),
            'numtags' => 0,
            'feature' => $admin_funcs,
            'author' => $author
        ];

        $array_ids[$id] = $id;
        $array_userid[$_userid] = $_userid;
    }
} else {
    if ($checkss == NV_CHECK_SESSION) {
        // Tìm theo từ khóa nhập vào
        $search_user = $search_author = false;
        if (!empty($q)) {
            if ($stype == 'bodytext') {
                $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
                $where[] = "c.bodyhtml LIKE '%" . $db_slave->dblikeescape($q) . "%'";
            } elseif ($stype == 'title') {
                $where[] = "r.title LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'";
            } elseif ($stype == 'author') {
                $where[] = "(r.author LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR a.alias LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR a.pseudonym LIKE '%" . $db_slave->dblikeescape($qhtml) . "%')";
                $search_author = true;
            } elseif ($stype == 'sourcetext') {
                $qurl = $q;
                $url_info = parse_url($qurl);
                if (isset($url_info['scheme']) and isset($url_info['host'])) {
                    $qurl = $url_info['scheme'] . '://' . $url_info['host'];
                }
                $where[] = 'r.sourceid IN (SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . "_sources WHERE title like '%" . $db_slave->dblikeescape($q) . "%' OR link like '%" . $db_slave->dblikeescape($qurl) . "%')";
            } elseif ($stype == 'admin_id') {
                $where[] = "(u.username LIKE '%" . $db_slave->dblikeescape($qhtml) . "%' OR u.first_name LIKE '%" . $db_slave->dblikeescape($qhtml) . "%')";
                $search_user = true;
            } else {
                $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
                $where[] = "(r.author LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR r.title LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR c.bodyhtml LIKE '%" . $db_slave->dblikeescape($q) . "%'
                OR u.username LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR u.first_name LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR a.alias LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
                OR a.pseudonym LIKE '%" . $db_slave->dblikeescape($qhtml) . "%')";
                $search_user = true;
                $search_author = true;
            }
        }

        // Thời gian từ
        if (!empty($search_time_from)) {
            $where[] = 'r.' . $search_type_date . ' >= ' . $search_time_from;
        }

        // Thời gian đến
        if (!empty($search_time_to)) {
            $where[] = 'r.' . $search_type_date . ' <= ' . $search_time_to;
        }

        if ($sstatus != -1) {
            if ($sstatus > $global_code_defined['row_locked_status']) {
                $where[] = 'r.status > ' . $global_code_defined['row_locked_status'];
            } else {
                $where[] = 'r.status = ' . $sstatus;
            }
        }
        if ($search_user) {
            $from .= ' LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON r.admin_id=u.userid';
        }
        if ($search_author) {
            $from .= ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON r.id=a.id';
        }
    }

    if (!defined('NV_IS_ADMIN_MODULE')) {
        $from_catid = [];
        foreach ($array_cat_view as $catid_i) {
            $from_catid[] = "FIND_IN_SET(" . $catid_i . ", r.listcatid)";
        }
        if (!empty($from_catid)) {
            // Giới hạn xem những bài trong chuyên mục mình được quản lý
            $where[] = '(' . implode(' OR ', $from_catid) . ')';
        } else {
            // Không có quyền quản lý chuyên mục nào thì xem như không xem được bài viết nào
            $where[] = 'r.id=0';
        }
    }
    $link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=Other';
    $global_array_cat[0] = [
        'catid' => 0,
        'parentid' => 0,
        'title' => 'Other',
        'alias' => 'Other',
        'link' => $link_i,
        'viewcat' => 'viewcat_page_new',
        'subcatid' => 0,
        'numlinks' => 3,
        'description' => '',
        'keywords' => ''
    ];

    $db_slave->sqlreset()->select('COUNT(*)')->from($from);
    if (!empty($where)) {
        $db_slave->where(implode(' AND ', $where));
    }

    $_sql = $db_slave->sql();
    $num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
    if ($num_checkss != $nv_Request->get_string('num_checkss', 'get', '')) {
        $num_items = $db_slave->query($_sql)->fetchColumn();
        $num_checkss = md5($num_items . NV_CHECK_SESSION . $_sql);
    }
    $base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;

    if ($catid) {
        $base_url_mod .= '&amp;catid=' . $catid;
    }

    if (!empty($q)) {
        $base_url_mod .= '&amp;q=' . urlencode($q);
    }

    $base_url_mod .= '&amp;stype=' . $stype . '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

    $db_slave->select('r.id, r.catid, r.listcatid, r.admin_id, r.title, r.alias, r.status, r.weight, r.addtime, r.edittime, r.publtime, r.exptime, r.hitstotal, r.hitscm, r.admin_id, r.author')
        ->order('r.' . $ordername . ' ' . $order)
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());

    $data = $array_ids = $array_userid = [];
    while (list($id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $weight, $addtime, $edittime, $publtime, $exptime, $hitstotal, $hitscm, $_userid, $author) = $result->fetch(3)) {
        $publtime = nv_date('H:i d/m/y', $publtime);

        if ($catid > 0) {
            $catid_i = $catid;
        }

        $check_permission_edit = $check_permission_delete = false;

        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_permission_edit = $check_permission_delete = true;
        } else {
            $array_temp = explode(',', $listcatid);
            $check_edit = $check_del = 0;

            foreach ($array_temp as $catid_i) {
                if (isset($array_cat_admin[$admin_id][$catid_i])) {
                    if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                        ++$check_edit;
                        ++$check_del;
                        $_permission_action['publtime'] = true;
                        $_permission_action['re-published'] = true;
                        $_permission_action['exptime'] = true;
                        $_permission_action['declined'] = true;
                    } else {
                        if ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                            ++$check_edit;
                            if ($status) {
                                $_permission_action['exptime'] = true;
                            }
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 2)) {
                            ++$check_edit;
                            $_permission_action['publtime'] = true;
                            $_permission_action['re-published'] = true;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and $status == 5) {
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_edit;
                            $_permission_action['waiting'] = true;
                        }

                        if ($array_cat_admin[$admin_id][$catid_i]['del_content'] == 1) {
                            ++$check_del;
                        } elseif (($status == 0 or $status == 4 or $status == 5) and $post_id == $admin_id) {
                            ++$check_del;
                            $_permission_action['waiting'] = true;
                        }
                    }
                }
            }

            if ($check_edit == sizeof($array_temp)) {
                $check_permission_edit = true;
            }

            if ($check_del == sizeof($array_temp)) {
                $check_permission_delete = true;
            }
        }

        $admin_funcs = [];
        if ($check_permission_edit) {
            $admin_funcs['edit'] = nv_link_edit_page($id);
        }
        if ($check_permission_delete) {
            $admin_funcs['delete'] = nv_link_delete_page($id);
            $_permission_action['delete'] = true;
        }

        $data[$id] = [
            'id' => $id,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'title' => $title,
            'title_clean' => nv_clean60($title),
            'addtime' => $addtime,
            'edittime' => $edittime,
            'publtime' => $publtime,
            'status_id' => $status,
            'weight' => $weight,
            'status' => $status > $global_code_defined['row_locked_status'] ? $lang_module['content_locked_bycat'] : $lang_module['status_' . $status],
            'class' => $status > $global_code_defined['row_locked_status'] ? $array_status_class['4'] : $array_status_class[$status],
            'userid' => $_userid,
            'hitstotal' => number_format($hitstotal, 0, ',', '.'),
            'hitscm' => number_format($hitscm, 0, ',', '.'),
            'numtags' => 0,
            'feature' => $admin_funcs,
            'author' => $author
        ];

        $array_ids[$id] = $id;
        $array_userid[$_userid] = $_userid;
    }
}

for ($i = 0; $i <= 10; ++$i) {
    $sl = ($i == $sstatus) ? ' selected="selected"' : '';
    $search_status[] = [
        'key' => $i,
        'value' => $lang_module['status_' . $i],
        'selected' => $sl
    ];
}
$fixedkey = $global_code_defined['row_locked_status'] + 1;
$sl = ($fixedkey == $sstatus) ? ' selected="selected"' : '';
$search_status[] = [
    'key' => $fixedkey,
    'value' => $lang_module['status_lockbycat'],
    'selected' => $sl
];

$i = 5;
$search_per_page = [];
while ($i <= 500) {
    $search_per_page[] = [
        'page' => $i,
        'selected' => ($i == $per_page) ? ' selected="selected"' : ''
    ];
    $i = $i + 5;
}

$search_type = [];
foreach ($array_search as $key => $val) {
    $search_type[] = [
        'key' => $key,
        'value' => $val,
        'selected' => ($key == $stype) ? ' selected="selected"' : ''
    ];
}

$arr_search_date = [
    'addtime' => $lang_module['content_publ_date'],
    'publtime' => $lang_module['search_public_time'],
    'exptime' => $lang_module['content_exp_date'],
];

$array_select_type_date = [];
foreach ($arr_search_date as $key => $val) {
    $array_select_type_date[] = [
        'key' => $key,
        'value' => $val,
        'selected' => ($key == $search_type_date) ? ' selected="selected"' : ''
    ];
}

$order2 = ($order == 'asc') ? 'desc' : 'asc';
$ord_sql = ' r.' . $ordername . ' ' . $order;

$array_editdata = [];
$internal_authors = [];

if (!empty($array_ids)) {
    // Lấy số tags
    $db_slave->sqlreset()
        ->select('COUNT(*) AS numtags, id')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tags_id')
        ->where('id IN( ' . implode(',', $array_ids) . ' )')
        ->group('id');
    $result = $db_slave->query($db_slave->sql());
    while (list($numtags, $id) = $result->fetch(3)) {
        $data[$id]['numtags'] = $numtags;
    }

    // Xác định người sửa bài viết
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tmp')
        ->where('id IN( ' . implode(',', $array_ids) . ' )');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        $array_editdata[$_row['id']] = $_row;
        $array_userid[$_row['admin_id']] = $_row['admin_id'];
    }

    // Tim cac author noi bo
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_authorlist')
        ->where('id IN (' . implode(',', $array_ids) . ')');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        !isset($internal_authors[$_row['id']]) and $internal_authors[$_row['id']] = [];
        $internal_authors[$_row['id']][] = [
            'href' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;q=' . urlencode($_row['alias']) . '&amp;stype=author&amp;checkss=' . NV_CHECK_SESSION,
            'pseudonym' => $_row['pseudonym']
        ];
    }
}

if (!empty($array_userid)) {
    $db_slave->sqlreset()
        ->select('tb1.userid, tb1.username, tb2.lev admin_lev')
        ->from(NV_USERS_GLOBALTABLE . ' tb1')
        ->join('LEFT JOIN ' . NV_AUTHORS_GLOBALTABLE . ' tb2 ON tb1.userid=tb2.admin_id')
        ->where('tb1.userid IN( ' . implode(',', $array_userid) . ' )');
    $array_userid = [];
    $result = $db_slave->query($db_slave->sql());
    while (list($_userid, $_username, $admin_lev) = $result->fetch(3)) {
        $array_userid[$_userid] = [
            'username' => $_username,
            'admin_lev' => $admin_lev
        ];
    }
}

// Cập nhật lại trạng thái sửa bài nếu timeout hoặc không có người sửa bài
$array_removeid = [];
foreach ($array_editdata as $_id => $_row) {
    if (!isset($array_userid[$_row['admin_id']]) or $_row['time_late'] < (NV_CURRENTTIME - $global_code_defined['edit_timeout'])) {
        $array_removeid[$_id] = $_id;
    }
    if ($_row['admin_id'] == $admin_info['userid'] or !isset($array_userid[$_row['admin_id']]) or $array_userid[$_row['admin_id']]['admin_lev'] > $admin_info['level']) {
        $array_editdata[$_id]['allowtakeover'] = true;
    } else {
        $array_editdata[$_id]['allowtakeover'] = false;
    }
}

if (!empty($array_removeid)) {
    $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tmp WHERE id IN(' . implode(',', $array_removeid) . ')');
    nv_redirect_location($client_info['selfurl']);
}

$base_url_mod .= '&amp;type_date=' . $search_type_date;
$search_time_from = empty($search_time_from) ? '' : nv_date('d/m/Y', $search_time_from);
$search_time_to = empty($search_time_to) ? '' : nv_date('d/m/Y', $search_time_to);
if (!empty($search_time_from)) {
    $base_url_mod .= '&amp;search_time_from=' . urlencode($search_time_from);
}
if (!empty($search_time_to)) {
    $base_url_mod .= '&amp;search_time_to=' . urlencode($search_time_to);
}
$base_url_mod .= '&amp;checkss=' . $checkss;

$base_url_id = $base_url_mod . '&amp;ordername=id&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_name = $base_url_mod . '&amp;ordername=title&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_publtime = $base_url_mod . '&amp;ordername=publtime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_exptime = $base_url_mod . '&amp;ordername=exptime&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitstotal = $base_url_mod . '&amp;ordername=hitstotal&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url_hitscm = $base_url_mod . '&amp;ordername=hitscm&amp;order=' . $order2 . '&amp;page=' . $page;
$base_url = $base_url_mod . '&amp;sstatus=' . $sstatus . '&amp;ordername=' . $ordername . '&amp;order=' . $order;

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('Q', $qhtml);
$xtpl->assign('TIME_FROM', $search_time_from);
$xtpl->assign('TIME_TO', $search_time_to);
$xtpl->assign('CATID', $catid);
$xtpl->assign('BASE_URL', $base_url);
$xtpl->assign('base_url_id', $base_url_id);
$xtpl->assign('base_url_name', $base_url_name);
$xtpl->assign('base_url_publtime', $base_url_publtime);
$xtpl->assign('base_url_exptime', $base_url_exptime);
$xtpl->assign('base_url_hitstotal', $base_url_hitstotal);
$xtpl->assign('base_url_hitscm', $base_url_hitscm);
$xtpl->assign('TOKEND', NV_CHECK_SESSION);

foreach ($val_cat_content as $cat_content) {
    $xtpl->assign('CAT_CONTENT', $cat_content);
    $xtpl->parse('main.cat_content');
}

foreach ($search_type as $search_t) {
    $xtpl->assign('SEARCH_TYPE', $search_t);
    $xtpl->parse('main.search_type');
}

foreach ($array_select_type_date as $search_d) {
    $xtpl->assign('VALUE', $search_d);
    $xtpl->parse('main.search_type_date');
}

foreach ($search_per_page as $s_per_page) {
    $xtpl->assign('SEARCH_PER_PAGE', $s_per_page);
    $xtpl->parse('main.s_per_page');
}

foreach ($search_status as $status_view) {
    $xtpl->assign('SEARCH_STATUS', $status_view);
    $xtpl->parse('main.search_status');
}

// Lấy số lịch sử trong các bài đăng hiển thị
$array_histories = [];
if (!empty($data) and !empty($module_config[$module_name]['active_history'])) {
    $sql = "SELECT COUNT(id) numhis, new_id FROM " . NV_PREFIXLANG . "_" . $module_data . "_row_histories
    WHERE new_id IN(" . implode(',', array_keys($data)) . ") GROUP BY new_id";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_histories[$row['new_id']] = $row['numhis'];
    }
}

$url_copy = '';
$loadhistory = $nv_Request->get_absint('loadhistory', 'get', 0);
$loadhistory_id = 0;
foreach ($data as $row) {
    $is_excdata = 0;
    $is_editing_row = (isset($array_editdata[$row['id']]) and $array_editdata[$row['id']]['admin_id'] != $admin_info['userid']) ? true : false;
    $is_locked_row = (isset($array_editdata[$row['id']]) and !$array_editdata[$row['id']]['allowtakeover']) ? true : false;
    if ($is_locked_row) {
        unset($row['feature']['edit'], $row['feature']['delete']);
    }
    $row['feature_text'] = implode(' ', $row['feature']);
    if ($global_config['idsite'] > 0 and isset($site_mods['excdata']) and isset($push_content['module'][$module_name]) and $row['status_id'] == 1) {
        $count = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $site_mods['excdata']['module_data'] . '_sended WHERE id_content=' . $row['id'] . ' AND module=' . $db_slave->quote($module_name))
            ->fetchColumn();
        if ($count == 0) {
            $is_excdata = 1;
            $row['url_send'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=excdata&amp;' . NV_OP_VARIABLE . '=send&amp;module=' . $module_name . '&amp;id=' . $row['id'];
        }
    }

    if ($row['status_id'] == 4 and empty($row['title'])) {
        $row['title'] = $lang_module['no_name'];
    }
    $row['username'] = isset($array_userid[$row['userid']]) ? $array_userid[$row['userid']]['username'] : '';

    $authors = [];
    if (isset($internal_authors[$row['id']]) and !empty($internal_authors[$row['id']])) {
        foreach ($internal_authors[$row['id']] as $internal_author) {
            $authors[] = '<a href="' . $internal_author['href'] . '">' . $internal_author['pseudonym'] . '</a>';
        }
    }
    if (!empty($row['author'])) {
        $authors[] = $row['author'];
    }
    $row['author'] = !empty($authors) ? implode(', ', $authors) : '';

    $xtpl->assign('ROW', $row);

    if ($is_excdata) {
        $xtpl->parse('main.loop.excdata');
    }
    if ($module_config[$module_name]['copy_news'] == 1) {
        $url_copy = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;copy=1&amp;id=' . $row['id'];
        $xtpl->assign('URL_COPY', $url_copy);
        $xtpl->parse('main.loop.copy_news');
    }

    if ($row['status_id'] == 4) {
        $xtpl->parse('main.loop.text');
    }

    if ($order_articles and !$is_locked_row) {
        $xtpl->parse('main.loop.sort');
    }

    if ($is_editing_row) {
        $xtpl->assign('USER_EDITING', $array_userid[$array_editdata[$row['id']]['admin_id']]['username']);
        $xtpl->assign('LEV_EDITING', $is_locked_row ? 'lock' : 'unlock-alt');
        $xtpl->parse('main.loop.is_editing');
    }
    if (!$is_locked_row) {
        $xtpl->parse('main.loop.checkrow');
    }
    if (isset($row['feature']['edit']) and isset($array_histories[$row['id']])) {
        $xtpl->parse('main.loop.history');
        if ($loadhistory == $row['id']) {
            $loadhistory_id = $row['id'];
        }
    }

    $xtpl->parse('main.loop');
}

// Hiển thị lịch sử sửa bài
if ($loadhistory) {
    if (!$loadhistory_id) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    $maps_fields = [
        'catid' => $lang_module['cat_parent'],
        'topicid' => $lang_module['topics1'],
        'author' => $lang_module['content_author'],
        'sourceid' => $lang_module['content_sourceid'],
        'publtime' => $lang_module['content_publ_date'],
        'exptime' => $lang_module['content_exp_date'],
        'archive' => $lang_module['content_archive'],
        'title' => $lang_module['name'],
        'alias' => $lang_module['alias'],
        'hometext' => $lang_module['content_hometext'],
        'homeimgfile' => $lang_module['content_homeimg'],
        'homeimgalt' => $lang_module['content_homeimgalt'],
        'inhome' => $lang_module['content_inhome'],
        'allowed_comm' => $lang_module['content_allowed_comm'],
        'allowed_rating' => $lang_module['content_allowed_rating'],
        'external_link' => $lang_module['content_external_link1'],
        'instant_active' => $lang_module['content_insart'],
        'instant_template' => $lang_module['content_instant_template1'],
        'instant_creatauto' => $lang_module['content_instant_creatauto'],
        'titlesite' => $lang_module['titlesite'],
        'description' => $lang_module['description'],
        'bodyhtml' => $lang_module['content_bodytext'],
        'sourcetext' => $lang_module['sources'],
        'imgposition' => $lang_module['imgposition'],
        'layout_func' => $lang_module['pick_layout1'],
        'copyright' => $lang_module['content_copyright'],
        'allowed_send' => $lang_module['content_allowed_send'],
        'allowed_print' => $lang_module['content_allowed_print'],
        'allowed_save' => $lang_module['content_allowed_save'],
        'listcatid' => $lang_module['search_cat'],
        'keywords' => $lang_module['keywords'],
        'tags' => $lang_module['tag'],
        'files' => $lang_module['fileattach'],
        'internal_authors' => $lang_module['content_internal_author']
    ];

    $array_userids = $array_users = [];
    $sql = "SELECT id, historytime, admin_id, changed_fields FROM " . NV_PREFIXLANG . "_" . $module_data . "_row_histories
    WHERE new_id=" . $loadhistory_id . " ORDER BY historytime DESC";
    $result = $db->query($sql);

    $array_histories = [];
    while ($row = $result->fetch()) {
        $row['changed_fields'] = array_map(function($val) {
            global $maps_fields;
            return $maps_fields[$val];
        }, explode(',', $row['changed_fields']));
        $row['changed_fields'] = implode(', ', $row['changed_fields']);

        $array_histories[$row['id']] = $row;

        if (!empty($row['admin_id'])) {
            $array_userids[$row['admin_id']] = $row['admin_id'];
        }
    }

    // Khôi phục 1 phiên bản
    if ($nv_Request->get_title('restorehistory', 'post', '') === NV_CHECK_SESSION) {
        $respon = [
            'success' => false,
            'text' => '',
            'url' => ''
        ];

        $history_id = $nv_Request->get_absint('id', 'post', 0);
        if (!isset($array_histories[$history_id])) {
            $respon['text'] = 'History not exists!!!';
            nv_jsonOutput($respon);
        }

        // Lấy full lịch sử
        $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_row_histories WHERE
        new_id=" . $loadhistory_id . " AND id=" . $history_id;
        $post_new = $db->query($sql)->fetch();
        if (empty($post_new)) {
            $respon['text'] = 'Error detail history!';
            nv_jsonOutput($respon);
        }
        $post_new['internal_authors'] = empty($post_new['internal_authors']) ? [] : explode(',', $post_new['internal_authors']);

        // Kiểm tra xem có lưu phiên bản hiện thời không (nếu chưa lưu)
        $history_time = $data[$loadhistory_id]['edittime'] ?: $data[$loadhistory_id]['addtime'];
        $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_row_histories WHERE
        new_id=" . $loadhistory_id . " AND historytime=" . $history_time;
        if (!$db->query($sql)->fetchColumn()) {
            // Lấy phiên bản hiện thời
            $post_old = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $loadhistory_id)->fetch();
            if (empty($post_old)) {
                nv_htmlOutput('Error row now!');
            }

            // Lấy chi tiết bài viết
            $body_contents = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id=' . $loadhistory_id)->fetch();
            $post_old = array_merge($post_old, $body_contents);
            unset($body_contents);

            // Lấy các tag của bài viết
            $array_tags_old = [];
            $_query = $db->query('SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $loadhistory_id . ' ORDER BY keyword ASC');
            while ($row = $_query->fetch()) {
                $array_tags_old[$row['tid']] = $row['keyword'];
            }
            $post_old['tags'] = implode(', ', $array_tags_old);

            // Lấy danh sach tac gia của bài viết
            $post_old['internal_authors'] = [];
            $_query = $db->query('SELECT aid, pseudonym FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE id=' . $loadhistory_id . ' ORDER BY alias ASC');
            while ($row = $_query->fetch()) {
                $post_old['internal_authors'][] = $row['aid'];
            }

            nv_save_history($post_old, $post_new);
        }

        // Đẩy qua trang content để sử dụng lại cái form đó cho chuẩn
        $respon['success'] = true;
        $respon['url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content&id=' . $loadhistory_id . '&restore=' . $history_id . '&restorehash=' . md5(NV_CHECK_SESSION . $admin_info['admin_id'] . $loadhistory_id . $history_id . $post_new['historytime']);
        nv_jsonOutput($respon);
    }

    if (!empty($array_userids)) {
        $sql = "SELECT userid, username, first_name, last_name, email
        FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN(" . implode(',', $array_userids) . ")";
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
            $row['show_name'] = $row['username'];
            if (!empty($row['full_name'])) {
                $row['show_name'] .= ' (' . $row['full_name'] . ')';
            }
            $array_users[$row['userid']] = $row;
        }
    }

    $xtpl->assign('NEW_ID', $loadhistory_id);

    foreach ($array_histories as $history) {
        $history['historytime'] = nv_date('d/m/Y H:i:s', $history['historytime']);
        $history['admin_id'] = isset($array_users[$history['admin_id']]) ? $array_users[$history['admin_id']]['show_name'] : ('#' . $array_users[$history['admin_id']]);
        $xtpl->assign('HISTORY', $history);
        $xtpl->parse('history.loop');
    }

    $xtpl->parse('history');
    $contents = $xtpl->text('history');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

foreach ($array_list_action as $action_i => $title_i) {
    if (defined('NV_IS_ADMIN_MODULE') or isset($_permission_action[$action_i])) {
        $action_assign = [
            'value' => $action_i,
            'title' => $title_i
        ];
        $xtpl->assign('ACTION', $action_assign);
        $xtpl->parse('main.action');
    }
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
