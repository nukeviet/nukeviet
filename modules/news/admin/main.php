<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['content_list'];
$stype = $nv_Request->get_string('stype', 'get', '-');
$sstatus = $nv_Request->get_int('sstatus', 'get', -1);
$catid = $nv_Request->get_int('catid', 'get', 0);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'get', $per_page_old);
$num_items = $nv_Request->get_int('num_items', 'get', 0);

if ($per_page < 1 and $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}

$q = $nv_Request->get_title('q', 'get', '');
$q = str_replace('+', ' ', $q);
$qhtml = nv_htmlspecialchars($q);
$ordername = $nv_Request->get_string('ordername', 'get', 'publtime');
$order = $nv_Request->get_string('order', 'get') == 'asc' ? 'asc' : 'desc';
$val_cat_content = array();
$val_cat_content[] = array(
    'value' => 0,
    'selected' => ($catid == 0) ? ' selected="selected"' : '',
    'title' => $lang_module['search_cat_all']
);
$array_cat_view = array();
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
        $val_cat_content[] = array(
            'value' => $catid_i,
            'selected' => $sl,
            'title' => $xtitle_i
        );
        $array_cat_view[] = $catid_i;
    }
}
if (!defined('NV_IS_ADMIN_MODULE') and $catid > 0 and !in_array($catid, $array_cat_view)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}
$array_search = array(
    '-' => '---' . $lang_module['search_type'] . '---',
    'title' => $lang_module['search_title'],
    'bodytext' => $lang_module['search_bodytext'],
    'author' => $lang_module['search_author'],
    'admin_id' => $lang_module['search_admin'],
    'sourcetext' => $lang_module['sources']
);
$array_in_rows = array(
    'title',
    'bodytext',
    'author',
    'sourcetext'
);
$array_in_ordername = array(
    'title',
    'publtime',
    'exptime',
    'hitstotal',
    'hitscm'
);
$array_status_view = array(
    '-' => '---' . $lang_module['search_status'] . '---',
    '5' => $lang_module['status_5'],
    '1' => $lang_module['status_1'],
    '0' => $lang_module['status_0'],
    '6' => $lang_module['status_6'],
    '4' => $lang_module['status_4'],
    '2' => $lang_module['status_2'],
    '3' => $lang_module['status_3']
);
$array_status_class = array(
    '5' => 'danger',
    '1' => '',
    '0' => 'warning',
    '6' => 'warning',
    '4' => 'info',
    '2' => 'success',
    '3' => 'danger'
);

$_permission_action = array();
$array_list_action = array(
    'delete' => $lang_global['delete'],
    're-published' => $lang_module['re_published'],
    'publtime' => $lang_module['publtime'],
    'stop' => $lang_module['status_0'],
    'waiting' => $lang_module['status_action_0']
);

// Chuyen sang cho duyet
if (defined('NV_IS_ADMIN_MODULE')) {
    $array_list_action['declined'] = $lang_module['declined'];
    $array_list_action['block'] = $lang_module['addtoblock'];
    $array_list_action['addtotopics'] = $lang_module['addtotopics'];
    $array_list_action['move'] = $lang_module['move'];
} elseif ($check_declined) { //Neu co quyen duyet bai thi
    $array_list_action['declined'] = $lang_module['declined'];
}

if (!in_array($stype, array_keys($array_search))) {
    $stype = '-';
}
if ($sstatus < 0 or $sstatus > 10) {
    $sstatus = -1;
}
if (!in_array($ordername, array_keys($array_in_ordername))) {
    $ordername = 'id';
}
if ($catid == 0) {
    $from = NV_PREFIXLANG . '_' . $module_data . '_rows r';
} else {
    $from = NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' r';
}
$where = '';
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');

if (($module_config[$module_name]['elas_use'] == 1) && $checkss == NV_CHECK_SESSION) {
    // Ket noi den csdl elastic
    $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);

    $search_elastic = array();
    // Tim kiem theo bodytext,author,title
    $key_elastic_search = nv_EncString($db_slave->dblikeescape($q));
    if ($stype == 'bodytext' or $stype == 'author' or $stype == 'title') {
        if ($stype == 'bodytext') {
            //match:tim kiem theo 1 truong
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
        $url_info = @parse_url($qurl);
        //print_r($url_info);die('pass');
        if (isset($url_info['scheme']) and isset($url_info['host'])) {
            $qurl = $url_info['scheme'] . '://' . $url_info['host'];
        }
        // T�m ki?m c� 1 trong c�c t?.
        $search_elastic = [
            'should' => [
                'match' => [
                    'sourcetext' => $db_slave->dblikeescape($qurl)
                ]
            ]
        ];
    } elseif ($stype == 'admin_id') {
        //tim tat ca cac admin_id c� username=$db_slave->dblikeescape($qhtml) ho?c first_name=$db_slave->dblikeescape($qhtml)
        $db->sqlreset()
            ->select('userid')
            ->from(NV_USERS_GLOBALTABLE)
            ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->execute();
        $admin_id_search = array();
        //search elastic theo admin_id v?a t�m dc
        $match = array();
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
        //tim tat ca cac admin_id c� username=$db_slave->dblikeescape($qhtml) ho?c first_name=$db_slave->dblikeescape($qhtml)
        $db->sqlreset()
            ->select('userid')
            ->from(NV_USERS_GLOBALTABLE)
            ->where('username LIKE :q_username OR first_name LIKE :q_first_name');

        $sth = $db->prepare($db->sql());
        $sth->bindValue(':q_username', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->bindValue(':q_first_name', '%' . $db_slave->dblikeescape($qhtml) . '%', PDO::PARAM_STR);
        $sth->execute();
        $admin_id_search = array();
        //search elastic theo admin_id v?a t�m dc
        $match = array();
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
        $search_elastic_status = [
            'filter' => [
                'match' => [
                    'status' => $sstatus
                ]
            ]
        ];
        if (!empty($q)) {
            $search_elastic = array_merge($search_elastic, $search_elastic_status);
        } else {
            $search_elastic = $search_elastic_status;
        }
    }

    $array_query_elastic = array();
    $array_query_elastic['query']['bool'] = $search_elastic;
    $array_query_elastic['size'] = $per_page;
    $array_query_elastic['from'] = ($page - 1) * $per_page;

    $response = $nukeVietElasticSearh->search_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $array_query_elastic);

    //so dong du lieu lay dc,c?n s?a $num_items=s? dong d? li?u
    $num_checkss = md5($num_items . NV_CHECK_SESSION);
    if ($num_checkss != $nv_Request->get_string('num_checkss', 'get', '')) {
        //print_r($expression)
        $num_items = $response['hits']['total'];
        $num_checkss = md5($num_items . NV_CHECK_SESSION); //?c?n s?a
    }
    $base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
    if ($catid) {
        $base_url_mod .= '&amp;catid=' . $catid;
    }
    if (!empty($q)) {
        $base_url_mod .= '&amp;q=' . $q . '&amp;checkss=' . $checkss;
    }
    $base_url_mod .= '&amp;stype=' . $stype . '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

    //hien thi du lieu
    $data = $array_ids = $array_userid = array();
    foreach ($response['hits']['hits'] as $key => $value) {
        $array_list_elastic_search = array(
            $value['_source']['id'],
            $value['_source']['catid'],
            $value['_source']['listcatid'],
            $value['_source']['admin_id'],
            $value['_source']['title'],
            $value['_source']['alias'],
            $value['_source']['status'],
            $value['_source']['publtime'],
            $value['_source']['exptime'],
            $value['_source']['hitstotal'],
            $value['_source']['hitscm'],
            $value['_source']['admin_id']
        );
        list ($id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $publtime, $exptime, $hitstotal, $hitscm, $_userid) = $array_list_elastic_search;
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

        $admin_funcs = array();
        if ($check_permission_edit) {
            $admin_funcs[] = nv_link_edit_page($id);
        }
        if ($check_permission_delete) {
            $admin_funcs[] = nv_link_delete_page($id);
            $_permission_action['delete'] = true;
        }
        $data[$id] = array(
            'id' => $id,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'title' => $title,
            'publtime' => $publtime,
            'status_id' => $status,
            'status' => $lang_module['status_' . $status],
            'class' => $array_status_class[$status],
            'userid' => $_userid,
            'hitstotal' => number_format($hitstotal, 0, ',', '.'),
            'hitscm' => number_format($hitscm, 0, ',', '.'),
            'numtags' => 0,
            'feature' => implode(' ', $admin_funcs)
        );

        $array_ids[] = $id;
        $array_userid[] = $_userid;
    }
} else {
    if ($checkss == NV_CHECK_SESSION) {
        if ($stype == 'bodytext') {
            $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
            $where = " c.bodyhtml LIKE '%" . $db_slave->dblikeescape($q) . "%'";
        } elseif ($stype == "author" or $stype == "title") {
            $where = " r." . $stype . " LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'";
        } elseif ($stype == 'sourcetext') {
            $qurl = $q;
            $url_info = @parse_url($qurl);
            if (isset($url_info['scheme']) and isset($url_info['host'])) {
                $qurl = $url_info['scheme'] . '://' . $url_info['host'];
            }
            $where = " r.sourceid IN (SELECT sourceid FROM " . NV_PREFIXLANG . "_" . $module_data . "_sources WHERE title like '%" . $db_slave->dblikeescape($q) . "%' OR link like '%" . $db_slave->dblikeescape($qurl) . "%')";
        } elseif ($stype == 'admin_id') {
            $where = " (u.username LIKE '%" . $db_slave->dblikeescape($qhtml) . "%' OR u.first_name LIKE '%" . $db_slave->dblikeescape($qhtml) . "%')";
        } elseif (!empty($q)) {
            $from .= ' INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_detail c ON (r.id=c.id)';
            $arr_from = array();
            foreach ($array_in_rows as $key => $val) {
                $arr_from[] = "(r." . $val . " LIKE '%" . $db_slave->dblikeescape($q) . "%')";
            }
            $where = " (r.author LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
    			OR r.title LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
    			OR c.bodyhtml LIKE '%" . $db_slave->dblikeescape($q) . "%'
    			OR u.username LIKE '%" . $db_slave->dblikeescape($qhtml) . "%'
    			OR u.first_name LIKE '%" . $db_slave->dblikeescape($qhtml) . "%')";
        }
        if ($sstatus != -1) {
            if ($where == '') {
                $where = ' r.status = ' . $sstatus;
            } else {
                $where .= ' AND r.status = ' . $sstatus;
            }
        }
        if (strpos($where, 'u.username')) {
            $from .= ' LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON r.admin_id=u.userid';
        }
    }

    if (!defined('NV_IS_ADMIN_MODULE')) {
        $from_catid = array();
        foreach ($array_cat_view as $catid_i) {
            $from_catid[] = "r.listcatid = '" . $catid_i . "'";
            $from_catid[] = "r.listcatid like '" . $catid_i . ",%'";
            $from_catid[] = "r.listcatid like '%," . $catid_i . ",%'";
            $from_catid[] = "r.listcatid like '%," . $catid_i . "'";
        }
        $where .= (empty($where)) ? ' (' . implode(' OR ', $from_catid) . ')' : ' AND (' . implode(' OR ', $from_catid) . ')';
    }
    $link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=Other';
    $global_array_cat[0] = array(
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
    );

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from($from)
        ->where($where);

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
        $base_url_mod .= '&amp;q=' . $q . '&amp;checkss=' . $checkss;
    }
    $base_url_mod .= '&amp;stype=' . $stype . '&amp;num_items=' . $num_items . '&amp;num_checkss=' . $num_checkss;

    $db_slave->select('r.id, r.catid, r.listcatid, r.admin_id, r.title, r.alias, r.status , r.publtime, r.exptime, r.hitstotal, r.hitscm, r.admin_id')
        ->order('r.' . $ordername . ' ' . $order)
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());

    $data = $array_ids = $array_userid = array();
    while (list ($id, $catid_i, $listcatid, $post_id, $title, $alias, $status, $publtime, $exptime, $hitstotal, $hitscm, $_userid) = $result->fetch(3)) {
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

        $admin_funcs = array();
        if ($check_permission_edit) {
            $admin_funcs[] = nv_link_edit_page($id);
        }
        if ($check_permission_delete) {
            $admin_funcs[] = nv_link_delete_page($id);
            $_permission_action['delete'] = true;
        }

        $data[$id] = array(
            'id' => $id,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid_i]['alias'] . '/' . $alias . '-' . $id . $global_config['rewrite_exturl'],
            'title' => $title,
            'publtime' => $publtime,
            'status_id' => $status,
            'status' => $lang_module['status_' . $status],
            'class' => $array_status_class[$status],
            'userid' => $_userid,
            'hitstotal' => number_format($hitstotal, 0, ',', '.'),
            'hitscm' => number_format($hitscm, 0, ',', '.'),
            'numtags' => 0,
            'feature' => implode(' ', $admin_funcs)
        );

        $array_ids[] = $id;
        $array_userid[] = $_userid;
    }
}

for ($i = 0; $i <= 10; $i++) {
    $sl = ($i == $sstatus) ? ' selected="selected"' : '';
    $search_status[] = array(
        'key' => $i,
        'value' => $lang_module['status_' . $i],
        'selected' => $sl
    );
}

$i = 5;
$search_per_page = array();
while ($i <= 500) {
    $search_per_page[] = array(
        'page' => $i,
        'selected' => ($i == $per_page) ? ' selected="selected"' : ''
    );
    $i = $i + 5;
}

$search_type = array();
foreach ($array_search as $key => $val) {
    $search_type[] = array(
        'key' => $key,
        'value' => $val,
        'selected' => ($key == $stype) ? ' selected="selected"' : ''
    );
}

$order2 = ($order == 'asc') ? 'desc' : 'asc';
$ord_sql = ' r.' . $ordername . ' ' . $order;

// Lay so tags
if (!empty($array_ids)) {
    $db_slave->sqlreset()
        ->select('COUNT(*) AS numtags, id')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_tags_id')
        ->where('id IN( ' . implode(',', $array_ids) . ' )')
        ->group('id');
    $result = $db_slave->query($db_slave->sql());
    while (list ($numtags, $id) = $result->fetch(3)) {
        $data[$id]['numtags'] = $numtags;
    }
}

if (!empty($array_userid)) {
    $array_userid = array_unique($array_userid);
    $db_slave->sqlreset()
        ->select('userid, username')
        ->from(NV_USERS_GLOBALTABLE)
        ->where('userid IN( ' . implode(',', $array_userid) . ' )');
    $array_userid = array();
    $result = $db_slave->query($db_slave->sql());
    while (list ($_userid, $_username) = $result->fetch(3)) {
        $array_userid[$_userid] = $_username;
    }
}

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
$xtpl->assign('base_url_id', $base_url_id);
$xtpl->assign('base_url_name', $base_url_name);
$xtpl->assign('base_url_publtime', $base_url_publtime);
$xtpl->assign('base_url_exptime', $base_url_exptime);
$xtpl->assign('base_url_hitstotal', $base_url_hitstotal);
$xtpl->assign('base_url_hitscm', $base_url_hitscm);

foreach ($val_cat_content as $cat_content) {
    $xtpl->assign('CAT_CONTENT', $cat_content);
    $xtpl->parse('main.cat_content');
}

foreach ($search_type as $search_t) {
    $xtpl->assign('SEARCH_TYPE', $search_t);
    $xtpl->parse('main.search_type');
}

foreach ($search_per_page as $s_per_page) {
    $xtpl->assign('SEARCH_PER_PAGE', $s_per_page);
    $xtpl->parse('main.s_per_page');
}

foreach ($search_status as $status_view) {
    $xtpl->assign('SEARCH_STATUS', $status_view);
    $xtpl->parse('main.search_status');
}

$url_copy = '';
foreach ($data as $row) {
    $is_excdata = 0;
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
    $row['username'] = isset($array_userid[$row['userid']]) ? $array_userid[$row['userid']] : '';
	$xtpl->assign('ROW', $row);

    if ($is_excdata) {
        $xtpl->parse('main.loop.excdata');
    }
	if($module_config[$module_name]['copy_news'] == 1) {
		$url_copy = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;copy=1&amp;id=' . $row['id'];
		$xtpl->assign('URL_COPY', $url_copy);
		$xtpl->parse('main.loop.copy_news');
	}

    if ($row['status_id'] == 4) {
        $xtpl->parse('main.loop.text');
    }
    $xtpl->parse('main.loop');
}

while (list ($action_i, $title_i) = each($array_list_action)) {
    if (defined('NV_IS_ADMIN_MODULE') or isset($_permission_action[$action_i])) {
        $action_assign = array(
            'value' => $action_i,
            'title' => $title_i
        );
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
