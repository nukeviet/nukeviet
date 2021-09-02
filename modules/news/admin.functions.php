<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

if ($NV_IS_ADMIN_MODULE) {
    define('NV_IS_ADMIN_MODULE', true);
}

if ($NV_IS_ADMIN_FULL_MODULE) {
    define('NV_IS_ADMIN_FULL_MODULE', true);
}

define('NV_MIN_MEDIUM_SYSTEM_ROWS', 100000);

$array_viewcat_full = [
    'viewcat_page_new' => $lang_module['viewcat_page_new'],
    'viewcat_page_old' => $lang_module['viewcat_page_old'],
    'viewcat_list_new' => $lang_module['viewcat_list_new'],
    'viewcat_list_old' => $lang_module['viewcat_list_old'],
    'viewcat_grid_new' => $lang_module['viewcat_grid_new'],
    'viewcat_grid_old' => $lang_module['viewcat_grid_old'],
    'viewcat_main_left' => $lang_module['viewcat_main_left'],
    'viewcat_main_right' => $lang_module['viewcat_main_right'],
    'viewcat_main_bottom' => $lang_module['viewcat_main_bottom'],
    'viewcat_two_column' => $lang_module['viewcat_two_column'],
    'viewcat_none' => $lang_module['viewcat_none']
];
$array_viewcat_nosub = [
    'viewcat_page_new' => $lang_module['viewcat_page_new'],
    'viewcat_page_old' => $lang_module['viewcat_page_old'],
    'viewcat_list_new' => $lang_module['viewcat_list_new'],
    'viewcat_list_old' => $lang_module['viewcat_list_old'],
    'viewcat_grid_new' => $lang_module['viewcat_grid_new'],
    'viewcat_grid_old' => $lang_module['viewcat_grid_old']
];

$array_allowed_comm = [
    $lang_global['no'],
    $lang_global['level6'],
    $lang_global['level4']
];

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news';
$array_url_instruction['cat'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#quản_ly_chuyen_mục';
$array_url_instruction['content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#them_bai_viet';
$array_url_instruction['tags'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#quản_ly_tags';
$array_url_instruction['groups'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#cac_nhom_tin';
$array_url_instruction['topics'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#theo_dong_sự_kiện';
$array_url_instruction['sources'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#nguồn_tin';
$array_url_instruction['admins'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#phan_quyền_quản_ly';
$array_url_instruction['setting'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:news#cấu_hinh_module';

global $global_array_cat;
$global_array_cat = [];
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY sort ASC';
$result = $db_slave->query($sql);
while ($row = $result->fetch()) {
    $global_array_cat[$row['catid']] = $row;
}

/**
 * nv_fix_cat_order()
 *
 * @param int $parentid
 * @param int $order
 * @param int $lev
 * @return
 */
function nv_fix_cat_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $module_data;

    $sql = 'SELECT catid, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_cat_order = [];
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['catid'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }
    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE catid=' . (int) $catid_i;
        $db->query($sql);
        $order = nv_fix_cat_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET numsubcat=' . $numsubcat;
        if ($numsubcat == 0) {
            $sql .= ",subcatid='', viewcat='viewcat_page_new'";
        } else {
            $sql .= ",subcatid='" . implode(',', $array_cat_order) . "'";
        }
        $sql .= ' WHERE catid=' . (int) $parentid;
        $db->query($sql);
    }

    return $order;
}

/**
 * nv_fix_topic()
 *
 * @return
 */
function nv_fix_topic()
{
    global $db, $module_data;
    $sql = 'SELECT topicid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_topics SET weight=' . $weight . ' WHERE topicid=' . (int) ($row['topicid']);
        $db->query($sql);
    }
    $result->closeCursor();
}

/**
 * nv_fix_block_cat()
 *
 * @return
 */
function nv_fix_block_cat()
{
    global $db, $module_data;
    $sql = 'SELECT bid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
    $weight = 0;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat SET weight=' . $weight . ' WHERE bid=' . (int) ($row['bid']);
        $db->query($sql);
    }
    $result->closeCursor();
}

/**
 * nv_fix_source()
 *
 * @return
 */
function nv_fix_source()
{
    global $db, $module_data;
    $sql = 'SELECT sourceid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET weight=' . $weight . ' WHERE sourceid=' . (int) ($row['sourceid']);
        $db->query($sql);
    }
    $result->closeCursor();
}

/**
 * nv_news_fix_block()
 *
 * @param mixed $bid
 * @param bool  $repairtable
 * @return
 */
function nv_news_fix_block($bid, $repairtable = true)
{
    global $db, $module_data;
    $bid = (int) $bid;
    if ($bid > 0) {
        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where bid=' . $bid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight <= 100) {
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $row['id'];
            } else {
                $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $row['id'];
            }
            $db->query($sql);
        }
        $result->closeCursor();
        if ($repairtable) {
            $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_' . $module_data . '_block');
        }
    }
}

/**
 * nv_show_cat_list()
 *
 * @param int $parentid
 * @return
 */
function nv_show_cat_list($parentid = 0)
{
    global $db, $lang_module, $lang_global, $module_name, $module_data, $array_viewcat_full, $array_viewcat_nosub, $array_cat_admin, $global_array_cat, $admin_id, $global_config, $module_file, $module_config, $global_code_defined;

    $xtpl = new XTemplate('cat_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    // Cac chu de co quyen han
    $array_cat_check_content = [];
    foreach ($global_array_cat as $catid_i => $array_value) {
        if (defined('NV_IS_ADMIN_MODULE')) {
            $array_cat_check_content[] = $catid_i;
        } elseif (isset($array_cat_admin[$admin_id][$catid_i])) {
            if ($array_cat_admin[$admin_id][$catid_i]['admin'] == 1) {
                $array_cat_check_content[] = $catid_i;
            } elseif ($array_cat_admin[$admin_id][$catid_i]['add_content'] == 1) {
                $array_cat_check_content[] = $catid_i;
            } elseif ($array_cat_admin[$admin_id][$catid_i]['pub_content'] == 1) {
                $array_cat_check_content[] = $catid_i;
            } elseif ($array_cat_admin[$admin_id][$catid_i]['edit_content'] == 1) {
                $array_cat_check_content[] = $catid_i;
            }
        }
    }

    // Cac chu de co quyen han
    if ($parentid > 0) {
        $parentid_i = $parentid;
        $array_cat_title = [];
        $stt = 0;
        while ($parentid_i > 0) {
            $array_cat_title[] = [
                'active' => ($stt++ == 0) ? true : false,
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $parentid_i,
                'title' => $global_array_cat[$parentid_i]['title']
            ];
            $parentid_i = $global_array_cat[$parentid_i]['parentid'];
        }
        $array_cat_title[] = [
            'active' => false,
            'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat',
            'title' => $lang_module['cat_parent']
        ];
        krsort($array_cat_title, SORT_NUMERIC);

        foreach ($array_cat_title as $cat) {
            $xtpl->assign('CAT', $cat);
            if ($cat['active']) {
                $xtpl->parse('main.cat_title.active');
            } else {
                $xtpl->parse('main.cat_title.loop');
            }
        }
        $xtpl->parse('main.cat_title');
    }

    $sql = 'SELECT catid, parentid, title, alias, weight, viewcat, numsubcat, numlinks, newday, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . $parentid . ' ORDER BY weight ASC';
    $rowall = $db->query($sql)->fetchAll(3);
    $num = sizeof($rowall);
    $a = 1;
    $array_status = [
        $lang_module['cat_status_0'],
        $lang_module['cat_status_1'],
        $lang_module['cat_status_2']
    ];
    $is_large_system = (nv_get_mod_countrows() > NV_MIN_MEDIUM_SYSTEM_ROWS);

    $xtpl->assign('MAX_WEIGHT', $num);
    $xtpl->assign('MAX_NUMLINKS', 20);
    $xtpl->assign('MAX_NEWDAY', 10);

    foreach ($rowall as $row) {
        list($catid, $parentid, $title, $alias, $weight, $viewcat, $numsubcat, $numlinks, $newday, $status) = $row;
        if (defined('NV_IS_ADMIN_MODULE')) {
            $check_show = 1;
        } else {
            $array_cat = GetCatidInParent($catid);
            $check_show = array_intersect($array_cat, $array_cat_check_content);
        }

        if (!empty($check_show)) {
            $array_viewcat = ($numsubcat > 0) ? $array_viewcat_full : $array_viewcat_nosub;
            if (!array_key_exists($viewcat, $array_viewcat)) {
                $viewcat = 'viewcat_page_new';
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET viewcat= :viewcat WHERE catid=' . (int) $catid);
                $stmt->bindParam(':viewcat', $viewcat, PDO::PARAM_STR);
                $stmt->execute();
            }

            $admin_funcs = [];
            $weight_disabled = $func_cat_disabled = true;
            if (!empty($module_config[$module_name]['instant_articles_active'])) {
                $admin_funcs[] = '<a title="' . $lang_module['cat_instant_view'] . '" href="' . NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss/' . $alias, true) . '" class="btn btn-default btn-xs viewinstantrss" data-toggle="tooltip" data-modaltitle="' . $lang_module['cat_instant_title'] . '"><em class="fa fa-rss"></em><span class="visible-xs-inline-block">&nbsp;' . $lang_module['cat_instant_viewsimple'] . "</span></a>\n";
            }
            if (defined('NV_IS_ADMIN_MODULE') or (isset($array_cat_admin[$admin_id][$catid]) and $array_cat_admin[$admin_id][$catid]['add_content'] == 1)) {
                $func_cat_disabled = false;
                $admin_funcs[] = '<a title="' . $lang_module['content_add'] . '" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;catid=' . $catid . '&amp;parentid=' . $parentid . '" class="btn btn-success btn-xs" data-toggle="tooltip"><em class="fa fa-plus"></em><span class="visible-xs-inline-block">&nbsp;' . $lang_module['content_add'] . "</span></a>\n";
            }
            if (defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
                $func_cat_disabled = false;
                $admin_funcs[] = '<a title="' . $lang_global['edit'] . '" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;catid=' . $catid . '&amp;parentid=' . $parentid . '#edit" class="btn btn-info btn-xs" data-toggle="tooltip"><em class="fa fa-edit"></em><span class="visible-xs-inline-block">&nbsp;' . $lang_global['edit'] . "</span></a>\n";
            }
            if (defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)) {
                $weight_disabled = false;
                $admin_funcs[] = '<a title="' . $lang_global['delete'] . '" href="javascript:void(0);" onclick="nv_del_cat(' . $catid . ')" class="btn btn-danger btn-xs" data-toggle="tooltip"><em class="fa fa-trash-o"></em><span class="visible-xs-inline-block">&nbsp;' . $lang_global['delete'] . '</span></a>';
            }

            $xtpl->assign('ROW', [
                'catid' => $catid,
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $catid,
                'title' => $title,
                'adminfuncs' => implode(' ', $admin_funcs)
            ]);

            $xtpl->assign('STT', $a);
            $xtpl->assign('STATUS', $status > $global_code_defined['cat_locked_status'] ? $lang_module['cat_locked_byparent'] : $array_status[$status]);
            $xtpl->assign('STATUS_VAL', $status);
            $xtpl->assign('VIEWCAT', $array_viewcat[$viewcat]);
            $xtpl->assign('VIEWCAT_VAL', $viewcat);
            $xtpl->assign('VIEWCAT_MODE', $numsubcat > 0 ? 'full' : 'nosub');
            $xtpl->assign('NUMLINKS', $numlinks);
            $xtpl->assign('NEWDAY', $newday);

            if ($weight_disabled) {
                $xtpl->parse('main.data.loop.stt');
            } else {
                $xtpl->parse('main.data.loop.weight');
            }

            if ($func_cat_disabled) {
                $xtpl->parse('main.data.loop.disabled_status');
                $xtpl->parse('main.data.loop.disabled_viewcat');
                $xtpl->parse('main.data.loop.title_numlinks');
                $xtpl->parse('main.data.loop.title_newday');
            } else {
                if ($status > $global_code_defined['cat_locked_status']) {
                    $xtpl->assign('STATUS', $lang_module['cat_locked_byparent']);
                    $xtpl->parse('main.data.loop.disabled_status');
                } elseif ($is_large_system and $status == 0) {
                    $xtpl->assign('STATUS', $array_status[$status]);
                    $xtpl->parse('main.data.loop.disabled_status');
                } else {
                    $xtpl->parse('main.data.loop.status');
                }

                $xtpl->parse('main.data.loop.viewcat');
                $xtpl->parse('main.data.loop.numlinks');
                $xtpl->parse('main.data.loop.newday');
            }

            if ($numsubcat) {
                $xtpl->assign('NUMSUBCAT', $numsubcat);
                $xtpl->parse('main.data.loop.numsubcat');
            }

            $xtpl->parse('main.data.loop');
            ++$a;
        }
    }

    if ($num > 0) {
        foreach ($array_viewcat_full as $k => $v) {
            $xtpl->assign('K', $k);
            $xtpl->assign('V', $v);
            $xtpl->parse('main.data.viewcat_full');
        }
        foreach ($array_viewcat_nosub as $k => $v) {
            $xtpl->assign('K', $k);
            $xtpl->assign('V', $v);
            $xtpl->parse('main.data.viewcat_nosub');
        }
        foreach ($array_status as $key => $val) {
            if (!$is_large_system or $key != 0) {
                $xtpl->assign('K', $key);
                $xtpl->assign('V', $val);
                $xtpl->parse('main.data.status');
            }
        }
        $xtpl->parse('main.data');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    return $contents;
}

/**
 * nv_show_topics_list()
 *
 * @return
 */
function nv_show_topics_list($page = 1)
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $module_config, $global_config, $module_file, $module_info;

    $per_page = $module_config[$module_name]['per_page'];
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_topics');

    $num_items = $db_slave->query($db_slave->sql())->fetchColumn();
    $max_height = $page * $per_page;
    if ($max_height > $num_items) {
        $max_height = $num_items;
    }

    $db_slave->select('*')
        ->order('weight ASC')
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $_array_topic = $db_slave->query($db_slave->sql())->fetchAll();
    $num = sizeof($_array_topic);

    if ($num > 0) {
        $xtpl = new XTemplate('topics_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);
        foreach ($_array_topic as $row) {
            $numnews = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows where topicid=' . $row['topicid'])->fetchColumn();

            $xtpl->assign('ROW', [
                'topicid' => $row['topicid'],
                'description' => $row['description'],
                'title' => $row['title'],
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=topicsnews&amp;topicid=' . $row['topicid'],
                'linksite' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $row['alias'],
                'numnews' => $numnews,
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=topics&amp;topicid=' . $row['topicid'] . '#edit'
            ]);

            for ($i = (($page - 1) * $per_page) + 1; $i <= $max_height; ++$i) {
                $xtpl->assign('WEIGHT', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.weight');
            }

            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
        $contents .= nv_generate_page(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=topics', $num_items, $per_page, $page);
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}

/**
 * nv_show_block_cat_list()
 *
 * @return
 */
function nv_show_block_cat_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $module_file, $global_config, $module_info;

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block_cat ORDER BY weight ASC';
    $_array_block_cat = $db_slave->query($sql)->fetchAll();
    $num = sizeof($_array_block_cat);

    if ($num > 0) {
        $array_adddefault = [
            $lang_global['no'],
            $lang_global['yes']
        ];

        $xtpl = new XTemplate('blockcat_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);

        foreach ($_array_block_cat as $row) {
            $numnews = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block where bid=' . $row['bid'])->fetchColumn();

            $xtpl->assign('ROW', [
                'bid' => $row['bid'],
                'title' => $row['title'],
                'numnews' => $numnews,
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=block&amp;bid=' . $row['bid'],
                'linksite' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['groups'] . '/' . $row['alias'],
                'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=groups&amp;bid=' . $row['bid'] . '#edit'
            ]);

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.weight');
            }

            foreach ($array_adddefault as $key => $val) {
                $xtpl->assign('ADDDEFAULT', [
                    'key' => $key,
                    'title' => $val,
                    'selected' => $key == $row['adddefault'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.adddefault');
            }

            for ($i = 1; $i <= 30; ++$i) {
                $xtpl->assign('NUMBER', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['numbers'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.number');
            }

            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}

/**
 * nv_show_sources_list()
 *
 * @return
 */
function nv_show_sources_list()
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $nv_Request, $module_file, $global_config;

    $num = $db_slave->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sources';
    $num_items = ($num > 1) ? $num : 1;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'get', 1);

    $xtpl = new XTemplate('sources_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($num > 0) {
        $db_slave->sqlreset()
            ->select('*')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_sources')
            ->order('weight')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        while ($row = $result->fetch()) {
            $xtpl->assign('ROW', [
                'sourceid' => $row['sourceid'],
                'title' => $row['title'],
                'link' => $row['link'],
                'url_edit' => $base_url . '&amp;sourceid=' . $row['sourceid']
            ]);

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.weight');
            }

            $xtpl->parse('main.loop');
        }
        $result->closeCursor();

        $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}

/**
 * nv_show_block_list()
 *
 * @param mixed $bid
 * @return
 */
function nv_show_block_list($bid)
{
    global $db_slave, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_cat, $module_file, $global_config;

    $xtpl = new XTemplate('block_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('BID', $bid);

    $global_array_cat[0] = ['alias' => 'Other'];

    $sql = 'SELECT t1.id, t1.catid, t1.title, t1.alias, t1.publtime, t1.status, t1.hitstotal, t1.hitscm, t2.weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_block t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' AND t1.status=1 ORDER BY t2.weight ASC';
    $array_block = $db_slave->query($sql)->fetchAll();
    $num = sizeof($array_block);
    if ($num > 0) {
        foreach ($array_block as $row) {
            $xtpl->assign('ROW', [
                'publtime' => nv_date('H:i d/m/Y', $row['publtime']),
                'status' => $lang_module['status_' . $row['status']],
                'hitstotal' => number_format($row['hitstotal'], 0, ',', '.'),
                'hitscm' => number_format($row['hitscm'], 0, ',', '.'),
                'id' => $row['id'],
                'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
                'title' => $row['title']
            ]);

            for ($i = 1; $i <= $num; ++$i) {
                $xtpl->assign('WEIGHT', [
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.weight');
            }

            $xtpl->parse('main.loop');
        }

        if (defined('NV_IS_SPADMIN')) {
            $xtpl->assign('ORDER_PUBLTIME', md5($bid . NV_CHECK_SESSION));
            $xtpl->parse('main.order_publtime');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');
    } else {
        $contents = '&nbsp;';
    }

    return $contents;
}

/**
 * GetCatidInParent()
 *
 * @param mixed $catid
 * @return
 */
function GetCatidInParent($catid)
{
    global $global_array_cat;
    $array_cat = [];
    $array_cat[] = $catid;
    $subcatid = explode(',', $global_array_cat[$catid]['subcatid']);
    if (!empty($subcatid)) {
        foreach ($subcatid as $id) {
            if ($id > 0) {
                if ($global_array_cat[$id]['numsubcat'] == 0) {
                    $array_cat[] = $id;
                } else {
                    $array_cat_temp = GetCatidInParent($id);
                    foreach ($array_cat_temp as $catid_i) {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }

    return array_unique($array_cat);
}

/**
 * redriect()
 *
 * @param string $msg1
 * @param string $msg2
 * @param mixed  $nv_redirect
 * @return
 */
function redriect($msg1, $msg2, $nv_redirect, $autoSaveKey = '', $go_back = '')
{
    global $global_config, $module_file, $module_name;
    $xtpl = new XTemplate('redriect.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

    if (empty($nv_redirect)) {
        $nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    }
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_REDIRECT', $nv_redirect);
    $xtpl->assign('MSG1', $msg1);
    $xtpl->assign('MSG2', $msg2);

    if (!empty($autoSaveKey)) {
        $xtpl->assign('AUTOSAVEKEY', $autoSaveKey);
        $xtpl->parse('main.removelocalstorage');
    }

    if (nv_strlen($msg1) > 255) {
        $xtpl->assign('REDRIECT_T1', 20);
        $xtpl->assign('REDRIECT_T2', 20000);
    } else {
        $xtpl->assign('REDRIECT_T1', 5);
        $xtpl->assign('REDRIECT_T2', 5000);
    }

    if ($go_back) {
        $xtpl->parse('main.go_back');
    } else {
        $xtpl->parse('main.meta_refresh');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

/**
 * get_mod_alias()
 *
 * @param mixed  $title
 * @param string $mod
 * @param int    $id
 * @return
 */
function get_mod_alias($title, $mod = '', $id = 0)
{
    global $module_data, $module_config, $module_name, $db_slave;

    if (empty($title)) {
        return '';
    }

    $alias = change_alias($title);
    if ($module_config[$module_name]['alias_lower']) {
        $alias = strtolower($alias);
    }
    $id = (int) $id;

    if ($mod == 'cat') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_cat';
        $stmt = $db_slave->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE catid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db_slave->query('SELECT MAX(catid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    } elseif ($mod == 'topics') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_topics';
        $stmt = $db_slave->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE topicid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db_slave->query('SELECT MAX(topicid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    } elseif ($mod == 'blockcat') {
        $tab = NV_PREFIXLANG . '_' . $module_data . '_block_cat';
        $stmt = $db_slave->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE bid!=' . $id . ' AND alias= :alias');
        $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $nb = $stmt->fetchColumn();
        if (!empty($nb)) {
            $nb = $db_slave->query('SELECT MAX(bid) FROM ' . $tab)->fetchColumn();

            $alias .= '-' . ((int) $nb + 1);
        }
    }

    return $alias;
}

/**
 * nv_get_mod_countrows()
 *
 * @return
 */
function nv_get_mod_countrows()
{
    global $module_data, $nv_Cache, $module_name;
    $sql = 'SELECT COUNT(*) totalnews FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows';
    $list = $nv_Cache->db($sql, '', $module_name);

    return $list[0]['totalnews'];
}
