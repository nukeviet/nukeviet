<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\news\Shared\Posts;

$page_title = $nv_Lang->getModule('content_list');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'nformat', 'nv_number_format');
$tpl->registerPlugin('modifier', 'dformat', 'nv_datetime_format');
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$type_search = [
    '-' => $nv_Lang->getModule('search_type_all'),
    'title' => $nv_Lang->getModule('admin_search_title'),
    'bodytext' => $nv_Lang->getModule('search_bodytext'),
    'author' => $nv_Lang->getModule('search_author'),
    'admin_id' => $nv_Lang->getModule('search_admin'),
    'sourcetext' => $nv_Lang->getModule('sources')
];

$array_search = [];
$array_search['q'] = $nv_Request->get_title('q', 'get', '');
$array_search['qhtml'] = nv_htmlspecialchars($array_search['q']);
$array_search['stype'] = $nv_Request->get_title('stype', 'get', '-');
$array_search['catid'] = $nv_Request->get_absint('catid', 'get', 0);
$array_search['sstatus'] = $nv_Request->get_int('sstatus', 'get', -1);

$per_page_old = $nv_Request->get_absint('per_page', 'cookie', 50);
$per_page = $nv_Request->get_absint('per_page', 'get', $per_page_old);
$num_items = $nv_Request->get_absint('num_items', 'get', 0);
$page = $nv_Request->get_absint('page', 'get', 1);
$is_search = 0;

$array_search['addtime_from'] = $nv_Request->get_title('add_from', 'get', '');
$array_search['addtime_to'] = $nv_Request->get_title('add_to', 'get', '');
$array_search['publtime_from'] = $nv_Request->get_title('pub_from', 'get', '');
$array_search['publtime_to'] = $nv_Request->get_title('pub_to', 'get', '');
$array_search['exptime_from'] = $nv_Request->get_title('exp_from', 'get', '');
$array_search['exptime_to'] = $nv_Request->get_title('exp_to', 'get', '');

$array_search['t_addtime_from'] = nv_d2u_get($array_search['addtime_from']);
$array_search['t_addtime_to'] = nv_d2u_get($array_search['addtime_to'], 23, 59, 59);
$array_search['t_publtime_from'] = nv_d2u_get($array_search['publtime_from']);
$array_search['t_publtime_to'] = nv_d2u_get($array_search['publtime_to'], 23, 59, 59);
$array_search['t_exptime_from'] = nv_d2u_get($array_search['exptime_from']);
$array_search['t_exptime_to'] = nv_d2u_get($array_search['exptime_to'], 23, 59, 59);

$array_search['adv'] = (int) $nv_Request->get_bool('adv', 'get', false);

if (empty($array_search['t_addtime_from'])) {
    $array_search['addtime_from'] = '';
}
if (empty($array_search['t_addtime_to'])) {
    $array_search['addtime_to'] = '';
}
if (empty($array_search['t_publtime_from'])) {
    $array_search['publtime_from'] = '';
}
if (empty($array_search['t_publtime_to'])) {
    $array_search['publtime_to'] = '';
}
if (empty($array_search['t_exptime_from'])) {
    $array_search['exptime_from'] = '';
}
if (empty($array_search['t_exptime_to'])) {
    $array_search['exptime_to'] = '';
}
if ($per_page < 1 or $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}
if (!in_array($array_search['stype'], array_keys($type_search), true)) {
    $array_search['stype'] = '-';
}
if ($array_search['sstatus'] < 0 or ($array_search['sstatus'] > 10 and $array_search['sstatus'] != ($global_code_defined['row_locked_status'] + 1))) {
    $array_search['sstatus'] = -1;
}

if (!empty($array_search['q'])) {
    $base_url .= '&amp;q=' . urlencode($array_search['q']);
    $is_search++;
}
if ($array_search['stype'] != '-') {
    $base_url .= '&amp;stype=' . $array_search['stype'];
}
if ($array_search['catid'] > 0) {
    $base_url .= '&amp;catid=' . $array_search['catid'];
    $is_search++;
}
if ($array_search['sstatus'] > -1) {
    $base_url .= '&amp;sstatus=' . $array_search['sstatus'];
    $is_search++;
}
$keys = [
    'addtime_from' => 'add_from',
    'addtime_to' => 'add_to',
    'publtime_from' => 'pub_from',
    'publtime_to' => 'pub_to',
    'exptime_from' => 'exp_from',
    'exptime_to' => 'exp_to'
];
foreach ($keys as $kk => $vv) {
    if (!empty($array_search[$kk])) {
        $base_url .= '&amp;' . $vv . '=' . urlencode($array_search[$kk]);
        $is_search++;
    }
}

$order_articles = 0;
if ($NV_IS_ADMIN_MODULE and $module_config[$module_name]['order_articles'] and empty($array_search['q']) and $array_search['sstatus'] == -1) {
    $order_articles = 1;

    // Sắp xếp bài đăng theo con số tự nhập
    $_weight_new = $nv_Request->get_int('order_articles_new', 'post', 0);
    $_id = $nv_Request->get_int('order_articles_id', 'post', 0);
    $_order_articles = $nv_Request->get_title('order_articles_checkss', 'post', '');
    if ($_id > 0 and $_weight_new > 0 and $_order_articles == md5($_id . NV_CHECK_SESSION)) {
        $sql = 'SELECT weight, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $_id;
        $_row1 = $db->query($sql)->fetch();
        if (!empty($_row1)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('order_articles'), 'id ' . $_id . ' ' . $_row1['weight'] . ':' . $_weight_new, $admin_info['admin_id']);

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
            nv_htmlOutput('OK');
        }
    }
}

$val_cat_content = [];
$val_cat_content[] = [
    'value' => 0,
    'title' => $nv_Lang->getModule('search_cat_all')
];

$array_cat_view = $array_cat_app = $array_cat_pub = $array_cat_edit = [];
$check_declined = false;
foreach ($global_array_cat as $catid_i => $array_value) {
    $lev_i = $array_value['lev'];
    $check_cat = $check_cat_edit = $check_cat_app = $check_cat_pub = false;
    if (defined('NV_IS_ADMIN_MODULE')) {
        $check_cat = true;
        $check_cat_edit = true;
        $check_cat_app = true;
        $check_cat_pub = true;
    } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
        $_cat_admin_i = $array_cat_admin[$admin_id][$catid_i];
        if ($_cat_admin_i['admin'] == 1) {
            $check_cat = true;
            $check_declined = true;
            $check_cat_edit = true;
            $check_cat_app = true;
            $check_cat_pub = true;
        } else {
            if ($_cat_admin_i['add_content'] == 1) {
                $check_cat = true;
            }
            if ($_cat_admin_i['pub_content'] == 1) {
                $check_cat = true;
                $check_declined = true;
                $check_cat_pub = true;
            }
            if ($_cat_admin_i['app_content'] == 1) {
                $check_cat = true;
                $check_declined = true;
                $check_cat_app = true;
            }
            if ($_cat_admin_i['edit_content'] == 1) {
                $check_cat = true;
                $check_cat_edit = true;
            }
            if ($_cat_admin_i['del_content'] == 1) {
                $check_cat = true;
            }
        }
    }

    if ($check_cat_edit) {
        $array_cat_edit[] = $catid_i;
    }
    if ($check_cat_app) {
        $array_cat_app[] = $catid_i;
    }
    if ($check_cat_pub) {
        $array_cat_pub[] = $catid_i;
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
        $val_cat_content[] = [
            'value' => $catid_i,
            'title' => $xtitle_i
        ];
        $array_cat_view[] = $catid_i;
    }
}
if (!defined('NV_IS_ADMIN_MODULE') and $array_search['catid'] > 0 and !in_array($array_search['catid'], array_map('intval', $array_cat_view), true)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE);
}

$base_url .= '&amp;per_page=' . $per_page;
if (!empty($array_search['adv'])) {
    $base_url .= '&amp;adv=1';
}

// Phần sắp xếp
$array_order = [];
$array_order['field'] = $nv_Request->get_title('of', 'get', '');
$array_order['value'] = $nv_Request->get_title('ov', 'get', '');

$order_fields = [
    'title',
    'publtime',
    'exptime',
    'hitstotal',
    'hitscm'
];
$order_values = ['asc', 'desc'];

if (!in_array($array_order['field'], $order_fields)) {
    $array_order['field'] = '';
}
if (!in_array($array_order['value'], $order_values)) {
    $array_order['value'] = '';
}

if (empty($array_search['catid'])) {
    $from = NV_PREFIXLANG . '_' . $module_data . '_rows r';
} else {
    $from = NV_PREFIXLANG . '_' . $module_data . '_' . $array_search['catid'] . ' r';
}

$_permission_action = [];
$array_list_action = [
    'delete' => $nv_Lang->getGlobal('delete'),
    're-published' => $nv_Lang->getModule('re_published'),
    'publtime' => $nv_Lang->getModule('publtime_action'),
    'stop' => $nv_Lang->getModule('status_0'),
    'waiting' => $nv_Lang->getModule('status_action_0')
];

// Chuyen sang cho duyet
if (defined('NV_IS_ADMIN_MODULE')) {
    $array_list_action['declined'] = $nv_Lang->getModule('declined');
    $array_list_action['block'] = $nv_Lang->getModule('addtoblock');
    $array_list_action['addtotopics'] = $nv_Lang->getModule('addtotopics');
    $array_list_action['move'] = $nv_Lang->getModule('move');
} elseif ($check_declined) { // Neu co quyen duyet bai thi
    $array_list_action['declined'] = $nv_Lang->getModule('declined');
}

$array_editdata = $internal_authors = [];

if (!empty($module_config[$module_name]['elas_use'])) {
    // Ket noi den csdl elastic
    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);

    $search_elastic = [];
    // Tim kiem theo bodytext,author,title
    $key_elastic_search = nv_EncString($db_slave->dblikeescape($array_search['q']));

    if ($array_search['stype'] == 'bodytext' or $array_search['stype'] == 'author' or $array_search['stype'] == 'title') {
        if ($array_search['stype'] == 'bodytext') {
            // match:tim kiem theo 1 truong
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_bodyhtml' => $key_elastic_search
                    ]
                ]
            ];
        } elseif ($array_search['stype'] == 'author') {
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
            $sth->bindValue(':q_alias', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
            $sth->bindValue(':q_pseudonym', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
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
        } elseif ($array_search['stype'] == 'title') {
            $search_elastic = [
                'should' => [
                    'match' => [
                        'unsigned_title' => $key_elastic_search
                    ]
                ]
            ];
        }
    } elseif ($array_search['stype'] == 'sourcetext') {
        $qurl = $array_search['q'];
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
    } elseif ($array_search['stype'] == 'admin_id') {
        $db->sqlreset()
        ->select('userid')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
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
        $key_search = nv_EncString($db_slave->dblikeescape($array_search['q']));
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
        $sth->bindValue(':q_alias', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_pseudonym', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
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
        // tim tat ca cac admin_id c� username=$db_slave->dblikeescape($array_search['qhtml']) ho?c first_name=$db_slave->dblikeescape($array_search['qhtml'])
        $db->sqlreset()
        ->select('userid')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($array_search['qhtml']) . '%', PDO::PARAM_STR);
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

    if (!empty($array_search['t_addtime_from'])) {
        $match[]['range']['addtime'] = [
            'gte' => $array_search['t_addtime_from']
        ];
    }
    if (!empty($array_search['t_addtime_to'])) {
        $match[]['range']['addtime'] = [
            'lte' => $array_search['t_addtime_to']
        ];
    }

    if (!empty($array_search['t_publtime_from'])) {
        $match[]['range']['publtime'] = [
            'gte' => $array_search['t_publtime_from']
        ];
    }
    if (!empty($array_search['t_publtime_to'])) {
        $match[]['range']['publtime'] = [
            'lte' => $array_search['t_publtime_to']
        ];
    }

    if (!empty($array_search['t_exptime_from'])) {
        $match[]['range']['exptime'] = [
            'gte' => $array_search['t_exptime_from']
        ];
    }
    if (!empty($array_search['t_exptime_to'])) {
        $match[]['range']['exptime'] = [
            'lte' => $array_search['t_exptime_to']
        ];
    }

    if ($array_search['catid'] != 0) {
        $search_elastic_catid = [
            'must' => [
                'match' => [
                    'catid' => $array_search['catid']
                ]
            ]
        ];
        if (!empty($array_search['q'])) {
            $search_elastic = array_merge($search_elastic, $search_elastic_catid);
        } else {
            $search_elastic = $search_elastic_catid;
        }
    }

    if ($array_search['sstatus'] != -1) {
        if ($array_search['sstatus'] > $global_code_defined['row_locked_status']) {
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
                        'status' => $array_search['sstatus']
                    ]
                ]
            ];
        }
        if (!empty($array_search['q'])) {
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

    // ES tìm cho ra kết quả số bản ghi luôn, không phải cache phân trang
    $num_items = $response['hits']['total'];

    // Xử lý dữ liệu
    $data = $array_ids = $array_userid = [];
    foreach ($response['hits']['hits'] as $value) {
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
        [$id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $addtime, $edittime, $publtime, $exptime, $hitstotal, $hitscm, $_userid, $author] = $array_list_elastic_search;
        $publtime = nv_datetime_format($publtime, 1);
        $title = nv_clean60($title);
        if ($array_search['catid'] > 0) {
            $catid_i = $array_search['catid'];
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
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 9 or $status == 2)) {
                            // Quyền đăng bài và bài đang dừng, chuyển đăng, từ chối đăng, hẹn đăng
                            ++$check_edit;
                            $_permission_action['publtime'] = true;
                            $_permission_action['re-published'] = true;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and ($status == 5 or $status == 6)) {
                            // Bài chờ duyệt thì được xử lý trong quá trình duyệt
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5 or $status == 6) and $post_id == $admin_id) {
                            // Bài của mình đăng, đang đình chỉ chờ duyệt, đã duyệt thì được sửa và chuyển lại chờ duyệt
                            ++$check_edit;
                            $_permission_action['waiting'] = true;
                        }

                        if ($array_cat_admin[$admin_id][$catid_i]['del_content'] == 1) {
                            ++$check_del;
                        } elseif (($status == 0 or $status == 4 or $status == 5 or $status == 6) and $post_id == $admin_id) {
                            ++$check_del;
                            $_permission_action['waiting'] = true;
                        }
                    }
                }
            }

            if ($check_edit == count($array_temp)) {
                $check_permission_edit = true;
            }

            if ($check_del == count($array_temp)) {
                $check_permission_delete = true;
            }
        }

        $admin_funcs = [];
        if ($check_permission_edit) {
            $admin_funcs['edit'] = nv_link_edit_page([
                'id' => $id,
                'listcatid' => $listcatid
            ]);
        }
        if ($check_permission_delete) {
            $admin_funcs['delete'] = nv_link_delete_page([
                'id' => $id,
                'listcatid' => $listcatid
            ]);
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
            'status' => $status > $global_code_defined['row_locked_status'] ? $nv_Lang->getModule('content_locked_bycat') : $nv_Lang->getModule('status_' . $status),
            'status_num' => $status,
            'userid' => $_userid,
            'hitstotal' => nv_number_format($hitstotal),
            'hitscm' => nv_number_format($hitscm),
            'numtags' => 0,
            'feature' => $admin_funcs,
            'author' => $author
        ];

        $array_ids[$id] = $id;
        $array_userid[$_userid] = $_userid;
    }
} else {
    $where = [];
    $search_user = $search_author = false;

    if (!empty($array_search['q'])) {
        if ($array_search['stype'] == 'bodytext') {
            $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
            $where[] = "c.bodyhtml LIKE '%" . $db_slave->dblikeescape($array_search['q']) . "%'";
        } elseif ($array_search['stype'] == 'title') {
            $where[] = "r.title LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'";
        } elseif ($array_search['stype'] == 'author') {
            $where[] = "(r.author LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR a.alias LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR a.pseudonym LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%')";
            $search_author = true;
        } elseif ($array_search['stype'] == 'sourcetext') {
            $qurl = $array_search['q'];
            $url_info = parse_url($qurl);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $qurl = $url_info['scheme'] . '://' . $url_info['host'];
            }
            $where[] = 'r.sourceid IN (SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . "_sources WHERE title like '%" . $db_slave->dblikeescape($array_search['q']) . "%' OR link like '%" . $db_slave->dblikeescape($qurl) . "%')";
        } elseif ($array_search['stype'] == 'admin_id') {
            $where[] = "(u.username LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%' OR u.first_name LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%')";
            $search_user = true;
        } else {
            $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
            $where[] = "(r.author LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR r.title LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR c.bodyhtml LIKE '%" . $db_slave->dblikeescape($array_search['q']) . "%'
            OR u.username LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR u.first_name LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR a.alias LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%'
            OR a.pseudonym LIKE '%" . $db_slave->dblikeescape($array_search['qhtml']) . "%')";
            $search_user = true;
            $search_author = true;
        }
    }

    // Thời gian từ
    if (!empty($array_search['t_addtime_from'])) {
        $where[] = 'r.addtime >= ' . $array_search['t_addtime_from'];
    }
    if (!empty($array_search['t_publtime_from'])) {
        $where[] = 'r.publtime >= ' . $array_search['t_publtime_from'];
    }
    if (!empty($array_search['t_exptime_from'])) {
        $where[] = 'r.exptime >= ' . $array_search['t_exptime_from'];
    }

    // Thời gian đến
    if (!empty($array_search['t_addtime_to'])) {
        $where[] = 'r.addtime <= ' . $array_search['t_addtime_to'];
    }
    if (!empty($array_search['t_publtime_to'])) {
        $where[] = 'r.publtime <= ' . $array_search['t_publtime_to'];
    }
    if (!empty($array_search['t_exptime_to'])) {
        $where[] = 'r.exptime <= ' . $array_search['t_exptime_to'];
    }

    if ($array_search['sstatus'] != -1) {
        if ($array_search['sstatus'] > $global_code_defined['row_locked_status']) {
            $where[] = 'r.status > ' . $global_code_defined['row_locked_status'];
        } else {
            $where[] = 'r.status = ' . $array_search['sstatus'];
        }
    }
    if ($search_user) {
        $from .= ' LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON r.admin_id=u.userid';
    }
    if ($search_author) {
        $from .= ' LEFT JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist a ON r.id=a.id';
    }

    if (!defined('NV_IS_ADMIN_MODULE')) {
        $from_catid = [];
        foreach ($array_cat_view as $catid_i) {
            $from_catid[] = 'FIND_IN_SET(' . $catid_i . ', r.listcatid)';
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
    $base_url .= '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

    if (!empty($array_order['field']) and !empty($array_order['value'])) {
        $order = 'r.' . $array_order['field'] . ' ' . $array_order['value'];
    } else {
        $order = ($module_config[$module_name]['order_articles'] == 1 ? 'r.weight' : 'r.publtime') . ' DESC';
    }

    $db_slave->select('r.id, r.catid, r.listcatid, r.admin_id, r.title, r.alias, r.status, r.weight, r.addtime, r.edittime, r.publtime, r.exptime, r.hitstotal, r.hitscm, r.admin_id, r.author')
    ->order($order)
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());

    $data = $array_ids = $array_userid = [];
    while ([$id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $weight, $addtime, $edittime, $publtime, $exptime, $hitstotal, $hitscm, $_userid, $author] = $result->fetch(3)) {
        $publtime = nv_datetime_format($publtime, 1);

        if ($array_search['catid'] > 0) {
            $catid_i = $array_search['catid'];
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
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1 and ($status == 0 or $status == 8 or $status == 9 or $status == 2)) {
                            // Quyền đăng bài và bài đang dừng, chuyển đăng, từ chối đăng, hẹn đăng
                            ++$check_edit;
                            $_permission_action['publtime'] = true;
                            $_permission_action['re-published'] = true;
                        } elseif ($array_cat_admin[$admin_id][$catid_i]['app_content'] == 1 and ($status == 5 or $status == 6)) {
                            // Bài chờ duyệt thì được xử lý trong quá trình duyệt
                            ++$check_edit;
                        } elseif (($status == 0 or $status == 4 or $status == 5 or $status == 6) and $post_id == $admin_id) {
                            // Bài của mình đăng, đang đình chỉ chờ duyệt, đã duyệt thì được sửa và chuyển lại chờ duyệt
                            ++$check_edit;
                            $_permission_action['waiting'] = true;
                        }

                        if ($array_cat_admin[$admin_id][$catid_i]['del_content'] == 1) {
                            ++$check_del;
                        } elseif (($status == 0 or $status == 4 or $status == 5 or $status == 6) and $post_id == $admin_id) {
                            ++$check_del;
                            $_permission_action['waiting'] = true;
                        }
                    }
                }
            }

            if ($check_edit == count($array_temp)) {
                $check_permission_edit = true;
            }

            if ($check_del == count($array_temp)) {
                $check_permission_delete = true;
            }
        }

        $admin_funcs = [];
        if ($check_permission_edit) {
            $admin_funcs['edit'] = nv_link_edit_page([
                'id' => $id,
                'listcatid' => $listcatid
            ]);
        }
        if ($check_permission_delete) {
            $admin_funcs['delete'] = nv_link_delete_page([
                'id' => $id,
                'listcatid' => $listcatid
            ]);
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
            'status' => $status > $global_code_defined['row_locked_status'] ? $nv_Lang->getModule('content_locked_bycat') : $nv_Lang->getModule('status_' . $status),
            'status_num' => $status,
            'userid' => $_userid,
            'hitstotal' => nv_number_format($hitstotal),
            'hitscm' => nv_number_format($hitscm),
            'numtags' => 0,
            'feature' => $admin_funcs,
            'author' => $author
        ];

        $array_ids[$id] = $id;
        $array_userid[$_userid] = $_userid;
    }
}

if (!empty($array_ids)) {
    // Lấy số tags
    $db_slave->sqlreset()
    ->select('COUNT(*) AS numtags, id')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tags_id')
    ->where('id IN( ' . implode(',', $array_ids) . ' )')
    ->group('id');
    $result = $db_slave->query($db_slave->sql());
    while ([$numtags, $id] = $result->fetch(3)) {
        $data[$id]['numtags'] = nv_number_format($numtags);
    }

    // Xác định người đang sửa bài viết
    $db_slave->sqlreset()
    ->select('*')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_tmp')
    ->where('new_id IN( ' . implode(',', $array_ids) . ') AND type=0');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        $array_editdata[$_row['new_id']] = $_row;
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

    // Xác định lý do từ chối bài viết
    $db_slave->sqlreset()
        ->select('id, reject_reason')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_detail')
        ->where('id IN (' . implode(',', $array_ids) . ')');
    $result = $db_slave->query($db_slave->sql());
    while ($_row = $result->fetch()) {
        $data[$_row['id']]['reject_reason'] = $_row['reject_reason'];
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
    while ([$_userid, $_username, $admin_lev] = $result->fetch(3)) {
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
        $array_removeid[$_row['id']] = $_row['id'];
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

// Lấy số lịch sử trong các bài đăng hiển thị
$array_histories = [];
if (!empty($data) and !empty($module_config[$module_name]['active_history'])) {
    $sql = 'SELECT COUNT(id) numhis, new_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories
    WHERE new_id IN(' . implode(',', array_keys($data)) . ') GROUP BY new_id';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_histories[$row['new_id']] = $row['numhis'];
    }
}

$base_url_order = $base_url;
if ($page > 1) {
    $base_url_order .= '&amp;page=' . $page;
}

$tpl->assign('SEARCH', $array_search);
$tpl->assign('TYPE_SEARCH', $type_search);
$tpl->assign('LIST_CAT', $val_cat_content);

$search_status = [];
for ($i = 0; $i <= 10; ++$i) {
    $search_status[] = [
        'key' => $i,
        'value' => $nv_Lang->getModule('status_' . $i),
        'selected' => ($i == $array_search['sstatus'])
    ];
}
$fixedkey = $global_code_defined['row_locked_status'] + 1;
$search_status[] = [
    'key' => $fixedkey,
    'value' => $nv_Lang->getModule('status_lockbycat'),
    'selected' => ($fixedkey == $array_search['sstatus'])
];
$tpl->assign('SEARCH_STATUS', $search_status);
$tpl->assign('PER_PAGE', $per_page);
$tpl->assign('PAGINATION', nv_generate_page($base_url, $num_items, $per_page, $page));

$actions = [];
foreach ($array_list_action as $action_i => $title_i) {
    if (defined('NV_IS_ADMIN_MODULE') or isset($_permission_action[$action_i])) {
        $actions[] = [
            'value' => $action_i,
            'title' => $title_i
        ];
    }
}
$tpl->assign('ACTIONS', $actions);
$tpl->assign('MCONFIG', $module_config[$module_name]);
$tpl->assign('STATUS_INDICATOR', [
    '----' => 'locking',
    Posts::STATUS_DEACTIVE => 'deactive',
    Posts::STATUS_DRAFT => 'draft',
    Posts::STATUS_EXPIRED => 'expired',
    Posts::STATUS_LOCKING => 'locking',
    Posts::STATUS_PUBLISH => 'publish',
    Posts::STATUS_PUBLISH_CHECKING => 'publish-checking',
    Posts::STATUS_PUBLISH_REJECT => 'publish-reject',
    Posts::STATUS_PUBLISH_TRANSFER => 'publish-transfer',
    Posts::STATUS_REVIEW_REJECT => 'review-reject',
    Posts::STATUS_REVIEW_TRANSFER => 'review-transfer',
    Posts::STATUS_REVIEWING => 'reviewing',
    Posts::STATUS_WAITING => 'waiting'
]);

// Xử lý các bài viết trước khi đưa ra giao diện
$loadhistory = $nv_Request->get_absint('loadhistory', 'get', 0);
$loadhistory_id = 0;

$array = [];
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
        $row['title'] = $nv_Lang->getModule('no_name');
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

    $row['tool_excdata'] = $is_excdata;
    $row['tool_sort'] = ($order_articles and !$is_locked_row);
    $row['is_editing'] = $is_editing_row;
    $row['user_editing'] = $is_editing_row ? $array_userid[$array_editdata[$row['id']]['admin_id']]['username'] : '';
    $row['is_locked'] = $is_locked_row;
    $row['show_history'] = false;
    $row['checksess'] = md5($row['id'] . NV_CHECK_SESSION);
    $row['abs_link'] = urlRewriteWithDomain($row['link'], NV_MY_DOMAIN);

    if (isset($row['feature']['edit']) and isset($array_histories[$row['id']])) {
        $row['show_history'] = true;
        if ($loadhistory == $row['id']) {
            $loadhistory_id = $row['id'];
        }
    }

    $array[] = $row;
}
$tpl->assign('DATA', $array);
$tpl->assign('ARRAY_ORDER', $array_order);
$tpl->assign('BASE_URL', $base_url);
$tpl->assign('BASE_URL_ORDER', $base_url_order);

// Hiển thị lịch sử sửa bài
if ($loadhistory) {
    if (!$loadhistory_id) {
        nv_error404();
    }

    $maps_fields = [
        'catid' => $nv_Lang->getModule('cat_parent'),
        'topicid' => $nv_Lang->getModule('topics1'),
        'author' => $nv_Lang->getModule('content_author'),
        'sourceid' => $nv_Lang->getModule('content_sourceid'),
        'publtime' => $nv_Lang->getModule('content_publ_date'),
        'exptime' => $nv_Lang->getModule('content_exp_date'),
        'archive' => $nv_Lang->getModule('content_archive'),
        'title' => $nv_Lang->getModule('name'),
        'alias' => $nv_Lang->getModule('alias'),
        'hometext' => $nv_Lang->getModule('content_hometext'),
        'homeimgfile' => $nv_Lang->getModule('content_homeimg'),
        'homeimgalt' => $nv_Lang->getModule('content_homeimgalt'),
        'inhome' => $nv_Lang->getModule('content_inhome'),
        'allowed_comm' => $nv_Lang->getModule('content_allowed_comm'),
        'allowed_rating' => $nv_Lang->getModule('content_allowed_rating'),
        'external_link' => $nv_Lang->getModule('content_external_link1'),
        'instant_active' => $nv_Lang->getModule('content_insart'),
        'instant_template' => $nv_Lang->getModule('content_instant_template1'),
        'instant_creatauto' => $nv_Lang->getModule('content_instant_creatauto'),
        'titlesite' => $nv_Lang->getModule('titlesite'),
        'description' => $nv_Lang->getModule('description'),
        'bodyhtml' => $nv_Lang->getModule('content_bodytext'),
        'voicedata' => $nv_Lang->getModule('voice'),
        'sourcetext' => $nv_Lang->getModule('sources'),
        'imgposition' => $nv_Lang->getModule('imgposition'),
        'layout_func' => $nv_Lang->getModule('pick_layout1'),
        'copyright' => $nv_Lang->getModule('content_copyright'),
        'allowed_send' => $nv_Lang->getModule('content_allowed_send'),
        'allowed_print' => $nv_Lang->getModule('content_allowed_print'),
        'allowed_save' => $nv_Lang->getModule('content_allowed_save'),
        'auto_nav' => $nv_Lang->getModule('auto_nav'),
        'group_view' => $nv_Lang->getModule('group_view'),
        'listcatid' => $nv_Lang->getModule('search_cat'),
        'keywords' => $nv_Lang->getModule('keywords'),
        'tags' => $nv_Lang->getModule('tag'),
        'files' => $nv_Lang->getModule('fileattach'),
        'internal_authors' => $nv_Lang->getModule('content_internal_author'),
        'schema_type' => $nv_Lang->getModule('content_schema_type'),
    ];

    $array_userids = $array_users = [];
    $sql = 'SELECT id, historytime, admin_id, changed_fields FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories
    WHERE new_id=' . $loadhistory_id . ' ORDER BY historytime DESC';
    $result = $db->query($sql);

    $array_histories = [];
    while ($row = $result->fetch()) {
        $row['changed_fields'] = array_map(function ($val) {
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
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories WHERE
        new_id=' . $loadhistory_id . ' AND id=' . $history_id;
        $post_new = $db->query($sql)->fetch();
        if (empty($post_new)) {
            $respon['text'] = 'Error detail history!';
            nv_jsonOutput($respon);
        }
        $post_new['internal_authors'] = empty($post_new['internal_authors']) ? [] : explode(',', $post_new['internal_authors']);
        $post_new['voicedata'] = empty($post_new['voicedata']) ? [] : json_decode($post_new['voicedata'], true);

        // Kiểm tra xem có lưu phiên bản hiện thời không (nếu chưa lưu)
        $history_time = $data[$loadhistory_id]['edittime'] ?: $data[$loadhistory_id]['addtime'];
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories WHERE
        new_id=' . $loadhistory_id . ' AND historytime=' . $history_time;
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
        $post_old['voicedata'] = empty($post_old['voicedata']) ? [] : json_decode($post_old['voicedata'], true);

        // Đẩy qua trang content để sử dụng lại cái form đó cho chuẩn
        $respon['success'] = true;
        $respon['url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content&id=' . $loadhistory_id . '&restore=' . $history_id . '&restorehash=' . md5(NV_CHECK_SESSION . $admin_info['admin_id'] . $loadhistory_id . $history_id . $post_new['historytime']);
        nv_jsonOutput($respon);
    }

    if (!empty($array_userids)) {
        $sql = 'SELECT userid, username, first_name, last_name, email
        FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN(' . implode(',', $array_userids) . ')';
        $result = $db->query($sql);

        while ($row = $result->fetch()) {
            $row['full_name'] = nv_show_name_user($row['first_name'], $row['last_name']);
            if (empty($row['full_name'])) {
                $row['show_name'] = $row['username'];
            } else {
                $row['show_name'] = $row['full_name'] . ' (' . $row['full_name'] . ')';
            }
            $array_users[$row['userid']] = $row;
        }
    }

    $tpl->assign('USERS', $array_users);
    $tpl->assign('NEW_ID', $loadhistory_id);
    $tpl->assign('HISTORIES', $array_histories);

    $contents = $tpl->fetch('history.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

// Hiển thị thông báo bài viết đang sửa, bài viết nháp đang viết nếu không tìm kiếm
$array_drafts = [
    'count' => 0,
    'list' => []
];
$array_others = [];
$array_others_count = 0;
if (!$is_search) {
    $db->sqlreset()->select('COUNT(id)')->from(NV_PREFIXLANG . '_' . $module_data . '_tmp')
        ->where('admin_id=' . $admin_info['admin_id'] . ' AND type=1');
    $array_drafts['count'] = $db->query($db->sql())->fetchColumn();

    $db->select('id, new_id, time_edit, time_late, properties')->order('time_late DESC')
        ->limit(10)
        ->offset(0);
    $result = $db->query($db->sql());

    $new_ids = [];
    while ($row = $result->fetch()) {
        if (!empty($row['new_id'])) {
            $new_ids[$row['new_id']] = $row['new_id'];
        }
        $row['allowed_edit'] = true;

        $row['properties'] = json_decode($row['properties'], true);
        if (!is_array($row['properties'])) {
            $row['properties'] = [];
        }
        $row['title'] = $row['properties']['title'] ?? '';
        unset($row['properties']);

        $array_drafts['list'][$row['id']] = $row;
    }

    // Trong số các bài sửa tạm này tìm tiêu đề
    $new_titles = [];
    if (!empty($new_ids)) {
        $db->sqlreset()->select('id, title, listcatid')->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('id IN (' . implode(',', $new_ids) . ')');
        $result = $db->query($db->sql());
        while ($row = $result->fetch()) {
            $new_titles[$row['id']] = [
                'title' => $row['title'],
                'catids' => array_filter(explode(',', $row['listcatid']))
            ];
        }

        foreach ($array_drafts['list'] as $id => $row) {
            if (isset($new_titles[$row['new_id']])) {
                if (empty($row['title'])) {
                    $array_drafts['list'][$id]['title'] = $new_titles[$row['new_id']]['title'];
                }
                $array_drafts['list'][$id]['allowed_edit'] = count(array_intersect($new_titles[$row['new_id']]['catids'], $array_cat_edit)) > 0;
            }
        }
    }

    // Cache các số đếm, sẽ theo admin
    if (!defined('NV_IS_ADMIN_MODULE')) {
        $cache_file = 'admmainothers_' . $admin_info['admin_id'] . '_' . NV_CACHE_PREFIX . '.cache';
    } else {
        $cache_file = 'admmainothers_' . NV_CACHE_PREFIX . '.cache';
    }
    $cacheTTL = 86400 * 7;

    if (($cache = $nv_Cache->getItem($module_name, $cache_file, ttl: $cacheTTL)) != false) {
        [$array_others, $array_others_count] = json_decode($cache, true);
    } else {
        // Đếm số bài lưu nháp do tôi đăng và còn quyền xem
        $where = [];
        $where[] = "status=" . Posts::STATUS_DRAFT;
        $where[] = "admin_id=" . $admin_info['admin_id'];
        if (!defined('NV_IS_ADMIN_MODULE')) {
            $from_catid = [];
            foreach ($array_cat_edit as $catid_i) {
                $from_catid[] = 'FIND_IN_SET(' . $catid_i . ', listcatid)';
            }
            if (!empty($from_catid)) {
                $where[] = '(' . implode(' OR ', $from_catid) . ')';
            } else {
                // Không còn edit bài trong chuyên mục nào nữa thì khỏi xem
                $where[] = 'id=0';
            }
        }
        $sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE " . implode(' AND ', $where);
        $number = $db->query($sql)->fetchColumn();
        if ($number > 0) {
            $array_others[] = [
                'title' => $nv_Lang->getModule('queue_draft', nv_number_format($number)),
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sstatus=' . Posts::STATUS_DRAFT,
                'count' => $number
            ];
            $array_others_count += $number;
        }

        // Đếm số bài chờ duyệt trong các chuyên mục tôi quản lý
        $where = [];
        $where[] = "status=" . Posts::STATUS_REVIEW_TRANSFER;
        if (!defined('NV_IS_ADMIN_MODULE')) {
            $from_catid = [];
            foreach ($array_cat_app as $catid_i) {
                $from_catid[] = 'FIND_IN_SET(' . $catid_i . ', listcatid)';
            }
            if (!empty($from_catid)) {
                $where[] = '(' . implode(' OR ', $from_catid) . ')';
            } else {
                $where[] = 'id=0';
            }
        }
        $sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE " . implode(' AND ', $where);
        $number = $db->query($sql)->fetchColumn();
        if ($number > 0) {
            $array_others[] = [
                'title' => $nv_Lang->getModule('queue_approval', nv_number_format($number)),
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sstatus=' . Posts::STATUS_REVIEW_TRANSFER,
                'count' => $number
            ];
            $array_others_count += $number;
        }

        // Đếm số bài chờ đăng trong các chuyên mục tôi quản lý
        $where = [];
        $where[] = "status=" . Posts::STATUS_PUBLISH_TRANSFER;
        if (!defined('NV_IS_ADMIN_MODULE')) {
            $from_catid = [];
            foreach ($array_cat_pub as $catid_i) {
                $from_catid[] = 'FIND_IN_SET(' . $catid_i . ', listcatid)';
            }
            if (!empty($from_catid)) {
                $where[] = '(' . implode(' OR ', $from_catid) . ')';
            } else {
                $where[] = 'id=0';
            }
        }
        $sql = "SELECT COUNT(id) FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE " . implode(' AND ', $where);
        $number = $db->query($sql)->fetchColumn();
        if ($number > 0) {
            $array_others[] = [
                'title' => $nv_Lang->getModule('queue_public', nv_number_format($number)),
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;sstatus=' . Posts::STATUS_PUBLISH_TRANSFER,
                'count' => $number
            ];
            $array_others_count += $number;
        }
        $nv_Cache->setItem($module_name, $cache_file, json_encode([$array_others, $array_others_count]), ttl: $cacheTTL);
    }
}
$tpl->assign('DRAFTS', $array_drafts);
$tpl->assign('ARRAY_OTHERS', $array_others);
$tpl->assign('ARRAY_OTHERS_COUNT', $array_others_count);

$contents = $tpl->fetch('main.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
