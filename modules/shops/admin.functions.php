<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'main',
    'alias',
    'items',
    'exptime',
    'publtime',
    'setting',
    'content',
    'custom_form',
    'keywords',
    'del_content',
    'cat',
    'change_cat',
    'list_cat',
    'del_cat',
    'block',
    'blockcat',
    'del_block_cat',
    'list_block_cat',
    'chang_block_cat',
    'change_block',
    'list_block',
    'prounit',
    'delunit',
    'order',
    'or_del',
    'or_view',
    'money',
    'delmoney',
    'group',
    'del_group',
    'list_group',
    'change_group',
    'getcatalog',
    'getgroup',
    'discounts',
    'view',
    'tags',
    'tagsajax',
    'seller',
    'copy_product',
    'order_seller',
    'coupons',
    'coupons_view',
    'point',
    'weight',
    'delweight',
    'location',
    'change_location',
    'list_location',
    'del_location',
    'carrier',
    'carrier_config',
    'carrier_config_items',
    'shipping',
    'shops',
    'getprice',
    'review',
    'warehouse',
    'warehouse_logs',
    'download',
    'updateprice'
);

if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'setting';
    $allow_func[] = 'fields';
    $allow_func[] = 'tabs';
    $allow_func[] = 'field_tab';
    $allow_func[] = 'template';
    $allow_func[] = 'detemplate';
    $allow_func[] = 'active_pay';
    $allow_func[] = 'payport';
    $allow_func[] = 'changepay';
    $allow_func[] = 'actpay';
    $allow_func[] = 'docpay';
}

$array_viewcat_full = array(
    'view_home_cat' => $lang_module['view_home_cat'],
    'viewcat_page_list' => $lang_module['viewcat_page_list'],
    'viewcat_page_gird' => $lang_module['viewcat_page_gird']
);
$array_viewcat_nosub = array(
    'viewcat_page_list' => $lang_module['viewcat_page_list'],
    'viewcat_page_gird' => $lang_module['viewcat_page_gird']
);

// Tài liệu hướng dẫn
$array_url_instruction['carrier_config_items'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['carrier_config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['carrier'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shops'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shipping_config'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping_config';
$array_url_instruction['shipping'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:shipping';

$array_url_instruction['coupons'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:coupons';
$array_url_instruction['coupons_view'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:coupons';

$array_url_instruction['template'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:template';
$array_url_instruction['template'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:template';

$array_url_instruction['warehouse'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:warehouse';
$array_url_instruction['warehouse_logs'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:warehouse';

$array_url_instruction['order'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:order';
$array_url_instruction['order_view'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:order';

$array_url_instruction['content'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:content';
$array_url_instruction['cat'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:cat';
$array_url_instruction['discount'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:discount';
$array_url_instruction['docpay'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:docpay';
$array_url_instruction['download'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:download';
$array_url_instruction['group'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:group';
$array_url_instruction['items'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:list';
$array_url_instruction['money'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:money';
$array_url_instruction['payport'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:payport';
$array_url_instruction['point'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:point';
$array_url_instruction['unit'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:unit';
$array_url_instruction['review'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:review';
$array_url_instruction['setting'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:setting';
$array_url_instruction['tabs'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:tabs';
$array_url_instruction['tags'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:tags';
$array_url_instruction['weight'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:weight';
$array_url_instruction['block'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:block';
$array_url_instruction['discounts'] = 'http://wiki.nukeviet.vn/nukeviet4:admin:shops:discount';

define('NV_IS_FILE_ADMIN', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/site.functions.php';

/**
 * nv_fix_cat_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT catid, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_cat_order = array( );
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
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE catid=' . $catid_i;
        $db->query($sql);
        $order = nv_fix_cat_order($catid_i, $order, $lev);
    }

    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET numsubcat=' . $numsubcat;
        if ($numsubcat == 0) {
            $sql .= ", subcatid='', viewcat='viewcat_page_list'";
        } else {
            $sql .= ", subcatid='" . implode(",", $array_cat_order) . "'";
        }
        $sql .= ' WHERE catid=' . $parentid;
        $db->query($sql);
    }
    return $order;
}

/**
 * nv_fix_block_cat()
 *
 * @return
 */
function nv_fix_block_cat()
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT bid FROM ' . $db_config['prefix'] . '_' . $module_data . '_block_cat ORDER BY weight ASC';
    $weight = 0;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block_cat SET weight=' . $weight . ' WHERE bid=' . $row['bid'];
        $db->query($sql);
    }
    $result->closeCursor();
}

/**
 * nv_news_fix_block()
 *
 * @param mixed $bid
 * @param bool $repairtable
 * @return
 */
function nv_news_fix_block($bid, $repairtable = true)
{
    global $db, $db_config, $module_data;

    $bid = intval($bid);

    if ($bid > 0) {
        $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE bid=' . $bid . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight <= 500) {
                $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . $row['id'];
            } else {
                $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $row['id'];
            }
            $db->query($sql);
        }
        $result->closeCursor();

        if ($repairtable) {
            $db->query('REPAIR TABLE ' . $db_config['prefix'] . '_' . $module_data . '_block');
        }
    }
}

/**
 * shops_show_cat_list()
 *
 * @param integer $parentid
 * @return
 */
function shops_show_cat_list($parentid = 0)
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_full, $array_viewcat_nosub, $global_config, $module_file;

    $xtpl = new XTemplate('cat_lists.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    if ($parentid > 0) {
        $parentid_i = $parentid;
        $array_cat_title = array( );
        $a = 0;

        while ($parentid_i > 0) {
            list($catid_i, $parentid_i, $title_i) = $db->query('SELECT catid, parentid, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE catid=' . intval($parentid_i))->fetch(3);

            $array_cat_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&amp;parentid=" . $catid_i . "\"><strong>" . $title_i . "</strong></a>";

            ++$a;
        }

        for ($i = $a - 1; $i >= 0; $i--) {
            $xtpl->assign('CAT_NAV', $array_cat_title[$i] . ($i > 0 ? " &raquo; " : ""));
            $xtpl->parse('main.catnav.loop');
        }

        $xtpl->parse('main.catnav');
    }

    $sql = 'SELECT catid, parentid, ' . NV_LANG_DATA . '_title, weight, viewcat, numsubcat, inhome, numlinks, newday FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $num = $result->rowCount();

    if ($num > 0) {
        $a = 0;
        $array_inhome = array(
            $lang_global['no'],
            $lang_global['yes']
        );

        while (list($catid, $parentid, $title, $weight, $viewcat, $numsubcat, $inhome, $numlinks, $newday) = $result->fetch(3)) {
            $array_viewcat = ($numsubcat > 0) ? $array_viewcat_full : $array_viewcat_nosub;
            if (!array_key_exists($viewcat, $array_viewcat)) {
                $viewcat = 'viewcat_page_list';
                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_catalogs SET viewcat= :viewcat WHERE catid=' . $catid);
                $stmt->bindParam(':viewcat', $viewcat, PDO::PARAM_STR);
                $stmt->execute();
            }

            $xtpl->assign('ROW', array(
                'catid' => $catid,
                'cat_link' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;parentid=' . $catid,
                'title' => $title,
                'numsubcat' => $numsubcat > 0 ? ' <span style="color:#FF0101;">(' . $numsubcat . ')</span>' : '',
                'parentid' => $parentid
            ));

            for ($i = 1; $i <= $num; $i++) {
                $xtpl->assign('WEIGHT', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $weight ? ' selected=\'selected\'' : ''
                ));
                $xtpl->parse('main.data.loop.weight');
            }

            foreach ($array_inhome as $key => $val) {
                $xtpl->assign('INHOME', array(
                    'key' => $key,
                    'title' => $val,
                    'selected' => $key == $inhome ? ' selected=\'selected\'' : ''
                ));
                $xtpl->parse('main.data.loop.inhome');
            }

            foreach ($array_viewcat as $key => $val) {
                $xtpl->assign('VIEWCAT', array(
                    'key' => $key,
                    'title' => $val,
                    'selected' => $key == $viewcat ? ' selected=\'selected\'' : ''
                ));
                $xtpl->parse('main.data.loop.viewcat');
            }

            for ($i = 0; $i <= 10; $i++) {
                $xtpl->assign('NUMLINKS', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $numlinks ? ' selected=\'selected\'' : ''
                ));
                $xtpl->parse('main.data.loop.numlinks');
            }

            for ($i = 0; $i <= 30; $i++) {
                $xtpl->assign('NEWDAY', array(
                    'key' => $i,
                    'title' => $i,
                    'selected' => $i == $newday ? ' selected=\'selected\'' : ''
                ));
                $xtpl->parse('main.data.loop.newday');
            }

            $xtpl->parse('main.data.loop');
            ++$a;
        }

        $xtpl->parse('main.data');
    }

    $result->closeCursor();
    unset($sql, $result);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_fix_group_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_group_order($parentid = 0, $sort = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT groupid, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_group WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_group_order = array( );
    while ($row = $result->fetch()) {
        $array_group_order[] = $row['groupid'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }
    foreach ($array_group_order as $groupid_i) {
        ++$sort;
        ++$weight;

        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group SET weight=' . $weight . ', sort=' . $sort . ', lev=' . $lev . ' WHERE groupid=' . $groupid_i;
        $db->query($sql);

        $sort = nv_fix_group_order($groupid_i, $sort, $lev);
    }

    $numsubgroup = $weight;

    if ($parentid > 0) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET numsubgroup=" . $numsubgroup;
        if ($numsubgroup == 0) {
            $sql .= ",subgroupid='', viewgroup='viewcat_page_list'";
        } else {
            $sql .= ",subgroupid='" . implode(",", $array_group_order) . "'";
        }
        $sql .= " WHERE groupid=" . intval($parentid);
        $db->query($sql);
    }
    return $sort;
}

/**
 * shops_show_group_list()
 *
 * @param integer $parentid
 * @return
 */
function shops_show_group_list($parentid = 0)
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_nosub, $module_file, $global_config;

    $xtpl = new XTemplate("group_lists.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    if ($parentid > 0) {
        $parentid_i = $parentid;
        $array_group_title = array( );
        $a = 0;
        while ($parentid_i > 0) {
            list($groupid_i, $parentid_i, $title_i) = $db->query("SELECT groupid, parentid, " . NV_LANG_DATA . "_title FROM " . $db_config['prefix'] . "_" . $module_data . "_group WHERE groupid=" . intval($parentid_i))->fetch(3);

            $array_group_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid_i . "\"><strong>" . $title_i . "</strong></a>";
            ++$a;
        }
        for ($i = $a - 1; $i >= 0; $i--) {
            $xtpl->assign('GROUP_NAV', $array_group_title[$i] . ($i > 0 ? " &raquo; " : ""));
            $xtpl->parse('main.groupnav.loop');
        }

        $xtpl->parse('main.catnav');
    }


    $sql = "SELECT groupid, parentid, " . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_description, weight, viewgroup, numsubgroup, inhome, indetail, in_order FROM " . $db_config['prefix'] . "_" . $module_data . "_group WHERE parentid = '" . $parentid . "' ORDER BY weight ASC";
    $result = $db->query($sql);
    $num = $result->rowCount();

    if ($num > 0) {
        $a = 0;
        $array_yes_no = array(
            $lang_global['no'],
            $lang_global['yes']
        );

        while (list($groupid, $parentid, $title, $description, $weight, $viewgroup, $numsubgroup, $inhome, $indetail, $in_order) = $result->fetch(3)) {
            $array_viewgroup = $array_viewcat_nosub;
            if (!array_key_exists($viewgroup, $array_viewgroup)) {
                $viewgroup = "viewcat_page_list";
                $stmt = $db->prepare("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET viewgroup= :viewgroup WHERE groupid=" . intval($groupid));
                $stmt->bindParam(':viewgroup', $viewgroup, PDO::PARAM_STR);
                $stmt->execute();
            }

            $xtpl->assign('ROW', array(
                "groupid" => $groupid,
                "group_link" => empty($parentid) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=group&amp;parentid=" . $groupid : 'javascript:void(0)',
                "title" => $title,
                "description" => $description,
                "numsubgroup" => $numsubgroup > 0 ? " <span style=\"color:#FF0101;\">(" . $numsubgroup . ")</span>" : "",
                "parentid" => $parentid
            ));

            for ($i = 1; $i <= $num; $i++) {
                $xtpl->assign('OPTION', array(
                    "key" => $i,
                    "title" => $i,
                    "selected" => $i == $weight ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.weight');
            }

            foreach ($array_yes_no as $key => $val) {
                $xtpl->assign('OPTION', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $inhome ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.inhome');

                $xtpl->assign('OPTION', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $indetail ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.indetail');

                $xtpl->assign('OPTION', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $in_order ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.in_order');
            }

            foreach ($array_viewgroup as $key => $val) {
                $xtpl->assign('OPTION', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $viewgroup ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.viewgroup');
            }

            $xtpl->parse('main.data.loop');
            ++$a;
        }

        $xtpl->parse('main.data');
    }

    $result->closeCursor();
    unset($sql, $result);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * shops_show_location_list()
 *
 * @param integer $parentid
 * @return
 */
function shops_show_location_list($parentid = 0, $page, $per_page, $base_url)
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $array_viewcat_nosub, $module_file, $global_config;

    $xtpl = new XTemplate("location_lists.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    if ($parentid > 0) {
        $parentid_i = $parentid;
        $array_location_title = array( );

        $a = 0;
        while ($parentid_i > 0) {
            list($id_i, $parentid_i, $title_i) = $db->query("SELECT id, parentid, title FROM " . $db_config['prefix'] . "_" . $module_data . "_location WHERE id=" . intval($parentid_i))->fetch(3);

            $array_location_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=location&amp;parentid=" . $id_i . "\"><strong>" . $title_i . "</strong></a>";
            ++$a;
        }
        $array_location_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=location\"><strong>" . $lang_module['location'] . "</strong></a>";
        for ($i = $a; $i >= 0; $i--) {
            $xtpl->assign('LOCATION_NAV', $array_location_title[$i] . ($i > 0 ? " &raquo; " : ""));
            $xtpl->parse('main.locationnav.loop');
        }

        $xtpl->parse('main.locationnav');
    }

    // Fetch Limit
    $db->sqlreset()->select('COUNT(*)')->from($db_config['prefix'] . '_' . $module_data . '_location')->where('parentid = ' . $parentid);

    $all_page = $db->query($db->sql())->fetchColumn();

    $db->select('id, parentid, title, weight, numsub')->order('weight ASC')->limit($per_page)->offset(($page - 1) * $per_page);

    $result = $db->query($db->sql());
    if ($result->rowCount()) {
        while (list($id, $parentid, $title, $weight, $numsub) = $result->fetch(3)) {
            $xtpl->assign('ROW', array(
                "id" => $id,
                "location_link" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=location&amp;parentid=" . $id,
                "title" => $title,
                "numsub" => $numsub > 0 ? " <span style=\"color:#FF0101;\">(" . $numsub . ")</span>" : "",
                "parentid" => $parentid
            ));

            for ($i = 1; $i <= $all_page; $i++) {
                $xtpl->assign('OPTION', array(
                    "key" => $i,
                    "title" => $i,
                    "selected" => $i == $weight ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.data.loop.weight');
            }

            $xtpl->parse('main.data.loop');
        }

        $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
        if (!empty($generate_page)) {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.data.generate_page');
        }

        $xtpl->parse('main.data');
    }

    $result->closeCursor();
    unset($sql, $result);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_fix_location_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_location_order($parentid = 0, $sort = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT id, parentid FROM ' . $db_config['prefix'] . '_' . $module_data . '_location WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_location_order = array( );
    while ($row = $result->fetch()) {
        $array_location_order[] = $row['id'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }
    foreach ($array_location_order as $locationid_i) {
        ++$sort;
        ++$weight;

        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_location SET weight=' . $weight . ', sort=' . $sort . ', lev=' . $lev . ' WHERE id=' . $locationid_i;
        $db->query($sql);

        $sort = nv_fix_location_order($locationid_i, $sort, $lev);
    }

    $numsub = $weight;

    if ($parentid > 0) {
        $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_location SET numsub=" . $numsub;
        if ($numsub == 0) {
            $sql .= ",subid=''";
        } else {
            $sql .= ",subid='" . implode(",", $array_location_order) . "'";
        }
        $sql .= " WHERE id=" . intval($parentid);
        $db->query($sql);
    }
    return $sort;
}

/**
 * nv_show_block_cat_list()
 *
 * @return
 */
function nv_show_block_cat_list()
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_config, $module_file;

    $xtpl = new XTemplate("block_cat_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', 'blockcat');

    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_block_cat ORDER BY weight ASC";
    $result = $db->query($sql);

    $num = $result->rowCount();

    if ($num > 0) {
        $a = 0;
        $array_adddefault = array(
            $lang_global['no'],
            $lang_global['yes']
        );

        while ($row = $result->fetch()) {
            $numnews = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block WHERE bid=" . $row['bid'])->fetchColumn();

            $xtpl->assign('ROW', array(
                "bid" => $row['bid'],
                "numnews" => $numnews ? " (" . $numnews . " " . $lang_module['num_product'] . ")" : "",
                "title" => $row[NV_LANG_DATA . '_title']
            ));

            for ($i = 1; $i <= $num; $i++) {
                $xtpl->assign('WEIGHT', array(
                    "key" => $i,
                    "title" => $i,
                    "selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.loop.weight');
            }

            foreach ($array_adddefault as $key => $val) {
                $xtpl->assign('ADDDEFAULT', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $row['adddefault'] ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.loop.adddefault');
            }

            $xtpl->parse('main.loop');
            ++$a;
        }
    }
    $result->closeCursor();

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * shops_show_discounts_list()
 *
 * @return
 */
function shops_show_discounts_list()
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_config, $module_file;

    $xtpl = new XTemplate("discounts_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', 'blockcat');

    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_discounts ORDER BY weight ASC";
    $result = $db->query($sql);

    $num = $result->rowCount();

    if ($num > 0) {
        $a = 0;
        $array_adddefault = array(
            $lang_global['no'],
            $lang_global['yes']
        );

        while ($row = $result->fetch()) {
            $numnews = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_block WHERE bid=" . $row['bid'])->fetchColumn();

            $xtpl->assign('ROW', array(
                "bid" => $row['bid'],
                "numnews" => $numnews ? " (" . $numnews . " " . $lang_module['num_product'] . ")" : "",
                "title" => $row[NV_LANG_DATA . '_title']
            ));

            for ($i = 1; $i <= $num; $i++) {
                $xtpl->assign('WEIGHT', array(
                    "key" => $i,
                    "title" => $i,
                    "selected" => $i == $row['weight'] ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.loop.weight');
            }

            foreach ($array_adddefault as $key => $val) {
                $xtpl->assign('ADDDEFAULT', array(
                    "key" => $key,
                    "title" => $val,
                    "selected" => $key == $row['adddefault'] ? " selected=\"selected\"" : ""
                ));
                $xtpl->parse('main.loop.adddefault');
            }

            $xtpl->parse('main.loop');
            ++$a;
        }
    }
    $result->closeCursor();

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_show_block_list()
 *
 * @param mixed $bid
 * @return
 */
function nv_show_block_list($bid)
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_shops_cat, $global_config, $module_file;

    $xtpl = new XTemplate("block_list.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('BID', $bid);

    $sql = 'SELECT t1.id, t1.listcatid, t1.' . NV_LANG_DATA . '_title, t1.' . NV_LANG_DATA . '_alias, t2.weight FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows as t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_block AS t2 ON t1.id = t2.id WHERE t2.bid= ' . $bid . ' AND t1.inhome=1 ORDER BY t2.weight ASC';

    $result = $db->query($sql);
    $num = $result->rowCount();
    $a = 0;

    while (list($id, $listcatid, $title, $alias, $weight) = $result->fetch(3)) {
        $xtpl->assign('ROW', array(
            'id' => $id,
            'title' => $title,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$listcatid]['alias'] . '/' . $alias . $global_config['rewrite_exturl']
        ));

        for ($i = 1; $i <= $num; $i++) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => $i == $weight ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.loop.weight');
        }

        $xtpl->parse('main.loop');
        ++$a;
    }
    $result->closeCursor();

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * email_new_order_payment()
 *
 * @param mixed $content
 * @param mixed $data_content
 * @param mixed $data_pro
 * @param mixed $data_table
 * @return
 */
function email_new_order_payment($content, $data_content, $data_pro, $data_table = false)
{
    global $module_info, $lang_module, $module_file, $pro_config, $global_config, $money_config;

    if ($data_table) {
        $xtpl = new XTemplate("email_new_order_payment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('DATA', $data_content);

        $i = 0;
        foreach ($data_pro as $pdata) {
            $xtpl->assign('product_name', $pdata['title']);
            $xtpl->assign('product_number', $pdata['product_number']);
            $xtpl->assign('product_price', nv_number_format($pdata['product_price'], nv_get_decimals($pro_config['money_unit'])));
            $xtpl->assign('product_unit', $pdata['product_unit']);
            $xtpl->assign('pro_no', $i + 1);

            $bg = ($i % 2 == 0) ? " style=\"background:#f3f3f3;\"" : "";
            $xtpl->assign('bg', $bg);

            if ($pro_config['active_price'] == '1') {
                $xtpl->parse('data_product.loop.price2');
            }
            $xtpl->parse('data_product.loop');
            ++$i;
        }

        if (!empty($data_content['order_note'])) {
            $xtpl->parse('data_product.order_note');
        }

        $xtpl->assign('order_total', nv_number_format($data_content['order_total'], nv_get_decimals($pro_config['money_unit'])));
        $xtpl->assign('unit', $data_content['unit_total']);

        if ($pro_config['active_price'] == '1') {
            $xtpl->parse('data_product.price1');
            $xtpl->parse('data_product.price3');
        }

        $xtpl->parse('data_product');
        return $xtpl->text('data_product');
        die();
    }

    $xtpl = new XTemplate("email_new_order_payment.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CONTENT', $content);

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * drawselect_number()
 *
 * @param string $select_name
 * @param integer $number_start
 * @param integer $number_end
 * @param integer $number_curent
 * @param string $func_onchange
 * @return
 */
function drawselect_number($select_name = "", $number_start = 0, $number_end = 1, $number_curent = 0, $func_onchange = "")
{
    $html = "<select class=\"form-control\" name=\"" . $select_name . "\" onchange=\"" . $func_onchange . "\">";
    for ($i = $number_start; $i < $number_end; $i++) {
        $select = ($i == $number_curent) ? "selected=\"selected\"" : "";
        $html .= "<option value=\"" . $i . "\"" . $select . ">" . $i . "</option>";
    }
    $html .= "</select>";
    return $html;
}

/**
 * GetCatidInChild()
 *
 * @param mixed $catid
 * @return
 */
function GetCatidInChild($catid)
{
    global $global_array_shops_cat, $array_cat;

    $array_cat[] = $catid;

    if ($global_array_shops_cat[$catid]['parentid'] > 0) {
        $array_cat[] = $global_array_shops_cat[$catid]['parentid'];
        $array_cat_temp = GetCatidInChild($global_array_shops_cat[$catid]['parentid']);
        foreach ($array_cat_temp as $catid_i) {
            $array_cat[] = $catid_i;
        }
    }
    return array_unique($array_cat);
}

/**
 * nv_show_custom_form()
 *
 * @param mixed $is_edit
 * @param mixed $form
 * @param mixed $array_custom
 * @param mixed $array_custom_lang
 * @return
 */
function nv_show_custom_form($is_edit, $form, $array_custom)
{
    global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op, $global_array_shops_cat, $global_config, $module_file;

    $xtpl = new XTemplate('cat_form_' . $form . '.tpl', NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/files_tpl');
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);

    if (preg_match('/^[a-zA-Z0-9\-\_]+$/', $form) and file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/admin/cat_form_' . $form . '.php')) {
        require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin/cat_form_' . $form . '.php';
    }

    if (defined('NV_EDITOR')) {
        require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
    }

    $array_custom_lang = array( );
    $idtemplate = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE alias = "' . preg_replace("/[\_]/", "-", $form) . '"')->fetchColumn();
    if ($idtemplate) {
        $array_tmp = array( );
        $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_field');
        while ($row = $result->fetch()) {
            $listtemplate = explode('|', $row['listtemplate']);
            if (in_array($idtemplate, $listtemplate)) {
                if (!$is_edit) {
                    if ($row['field_type'] == 'date') {
                        $array_custom[$row['field']] = ($row['field_choices']['current_date']) ? NV_CURRENTTIME : $row['default_value'];
                    } elseif ($row['field_type'] == 'number') {
                        $array_custom[$row['field']] = $row['default_value'];
                    } else {
                        if (!empty($row['field_choices'])) {
                            $temp = array_keys($row['field_choices']);
                            $tempkey = intval($row['default_value']) - 1;
                            $array_custom[$row['field']] = (isset($temp[$tempkey])) ? $temp[$tempkey] : '';
                        }
                    }
                } elseif (!empty($row['field_choices'])) {
                    $row['field_choices'] = unserialize($row['field_choices']);
                } elseif (!empty($row['sql_choices'])) {
                    $row['sql_choices'] = explode('|', $row['sql_choices']);
                    $query = 'SELECT ' . $row['sql_choices'][2] . ', ' . $row['sql_choices'][3] . ' FROM ' . $row['sql_choices'][1];
                    $result_sql = $db->query($query);
                    $weight = 0;
                    while (list($key, $val) = $result_sql->fetch(3)) {
                        $row['field_choices'][$key] = $val;
                    }
                }

                if ($row['field_type'] == 'date') {
                    $array_custom[$row['field']] = (empty($array_custom[$row['field']])) ? '' : date('d/m/Y', $array_custom[$row['field']]);
                } elseif ($row['field_type'] == 'textarea') {
                    $array_custom[$row['field']] = nv_htmlspecialchars(nv_br2nl($array_custom[$row['field']]));
                } elseif ($row['field_type'] == 'editor') {
                    $array_custom[$row['field']] = htmlspecialchars(nv_editor_br2nl($array_custom[$row['field']]));
                    if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                        $row['class'] = explode('@', $row['class']);
                        $edits = nv_aleditor('custom[' . $row['fid'] . ']', $row['class'][0], $row['class'][1], $array_custom[$row['fid']]);
                        $array_custom[$row['field']] = $edits;
                    } else {
                        $row['class'] = '';
                    }
                } elseif ($row['field_type'] == 'select') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('OPTION', array(
                            'key' => $key,
                            'selected' => ($key == $array_custom[$row['field']]) ? ' selected="selected"' : '',
                            'title' => $value
                        ));
                        $xtpl->parse('main.select_' . $row['field']);
                    }
                } elseif ($row['field_type'] == 'radio' or $row['field_type'] == 'checkbox') {
                    $number = 0;
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('OPTION', array(
                            'id' => $row['fid'] . '_' . $number++,
                            'key' => $key,
                            'checked' => ($key == $array_custom[$row['field']]) ? ' checked="checked"' : '',
                            'title' => $value
                        ));

                        $xtpl->parse('main.' . $row['field_type'] . '_' . $row['field']);
                    }
                } elseif ($row['field_type'] == 'multiselect') {
                    foreach ($row['field_choices'] as $key => $value) {
                        $xtpl->assign('OPTION', array(
                            'key' => $key,
                            'selected' => ($key == $array_custom[$row['field']]) ? ' selected="selected"' : '',
                            'title' => $value
                        ));
                        $xtpl->parse('main.' . $row['field']);
                    }
                }

                // Du lieu hien thi tieu de
                $array_tmp[$row['fid']] = unserialize($row['language']);
            }
        }

        if (!empty($array_tmp)) {
            foreach ($array_tmp as $f_key => $field) {
                foreach ($field as $key_lang => $lang_data) {
                    if ($key_lang == NV_LANG_INTERFACE) {
                        $array_custom_lang[$f_key] = array(
                            'title' => $lang_data[0],
                            'description' => isset($lang_data[1]) ? $lang_data[1] : ''
                        );
                    }
                }
            }
        }
    }
    $xtpl->assign('ROW', $array_custom);
    $xtpl->assign('CUSTOM_LANG', $array_custom_lang);

    foreach ($array_custom_lang as $k_lang => $custom_lang) {
        if (!empty($custom_lang['description'])) {
            $xtpl->parse('main.' . $k_lang . '_description');
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * Insertabl_catfields()
 *
 * @param mixed $table
 * @param mixed $array
 * @param mixed $idshop
 * @return
 */
function Insertabl_catfields($table, $array, $idshop)
{
    global $db, $module_name, $module_file, $db, $link, $module_info, $global_array_shops_cat, $global_config;

    $result = $db->query("SHOW COLUMNS FROM " . $table);

    $array_column = array( );

    while ($row = $result->fetch()) {
        $array_column[] = $row['field'];
    }
    $sql_insert = '';
    array_shift($array_column);
    array_shift($array_column);
    $array_new = array( );

    foreach ($array as $key => $array_a) {
        $array_new[$key] = $array_a;
    }

    foreach ($array_column as $array_i) {
        $sql_insert .= ",'" . $array_new[$array_i] . "'";
    }

    $sql = " INSERT INTO " . $table . " VALUES ( " . $idshop . ",1 " . $sql_insert . ")";

    $db->query($sql);
}

/**
 * nv_create_form_file()
 *
 * @param mixed $array_template_id
 * @return
 */

function nv_create_form_file($array_template_id)
{
    global $db, $db_config, $module_upload, $module_data, $module_file, $array_template, $lang_module;

    foreach ($array_template_id as $templateids_i) {
        $array_views = array();
        $result = $db->query("SELECT fid, field, field_type, listtemplate FROM " . $db_config['prefix'] . '_' . $module_data . "_field");
        while ($column = $result->fetch()) {
            $column['listtemplate'] = explode('|', $column['listtemplate']);
            if (in_array($templateids_i, $column['listtemplate'])) {
                $array_views[$column['fid']] = $column;
            }
        }

        $array_field_js = array( );
        $content_2 = "<!-- BEGIN: main -->\n";
        $content_2 .= "\t<div class=\"panel panel-default\">\n\t\t<div class=\"panel-heading\">{LANG.tabs_content_customdata}</div>\n";
        $content_2 .= "\t\t<div class=\"panel-body\">\n";

        foreach ($array_views as $key => $column) {
            $content_2 .= "\t\t\t<div class=\"form-group\">\n";
            $content_2 .= "\t\t\t\t<label class=\"col-md-4 control-label\"> {CUSTOM_LANG." . $key . ".title} </label>\n";

            $content_2 .= "\t\t\t\t<div class=\"col-md-20\">";

            if ($column['field_type'] == 'time') {
                $content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_hour]\" value=\"{ROW." . $key . "_hour}\" >:";
                $content_2 .= "<input class=\"form-control\" type=\"text\" pattern=\"^[0-9]{2,2}\$\" name=\"custom[" . $key . "_min]\" value=\"{ROW." . $key . "_min}\" >&nbsp;";
            }

            if ($column['field_type'] == 'textarea') {
                $content_2 .= "<textarea class=\"form-control\" style=\"width: 98%; height:100px;\" cols=\"75\" rows=\"5\" name=\"custom[" . $key . "]\">{ROW." . $key . "}</textarea>";
            } elseif ($column['field_type'] == 'editor') {
                $content_2 .= "{ROW." . $column['field'] . "}";
            } elseif ($column['field_type'] == 'select') {
                $content_2 .= "<select class=\"form-control\" name=\"custom[" . $key . "]\">\n";
                $content_2 .= "\t\t\t\t\t\t\t<option value=\"\"> --- </option>\n";
                $content_2 .= "\t\t\t\t\t\t<!-- BEGIN: select_" . $key . " -->\n";
                $content_2 .= "\t\t\t\t\t\t\t<option value=\"{OPTION.key}\" {OPTION.selected}>{OPTION.title}</option>\n";
                $content_2 .= "\t\t\t\t\t\t\t<!-- END: select_" . $key . " -->\n";
                $content_2 .= "\t\t\t\t\t</select>";
            } elseif ($column['field_type'] == 'radio' or $column['field_type'] == 'checkbox') {
                $type_html = ($column['field_type'] == 'radio') ? 'radio' : 'checkbox';
                $content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $type_html . "_" . $key . " -->\n";
                $content_2 .= "\t\t\t\t\t<label><input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{OPTION.key}\" {OPTION.checked}";

                if (isset($array_requireds[$key])) {
                    $content_2 .= 'required="required" ';
                    if ($oninvalid) {
                        $content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
                    }
                }
                $content_2 .= ">{OPTION.title} &nbsp;</label>\n";
                $content_2 .= "\t\t\t\t\t<!-- END: " . $type_html . "_" . $key . " -->\n";
                $content_2 .= "\t\t\t\t";
            } elseif ($column['field_type'] == 'multiselect') {
                $content_2 .= "\n\t\t\t\t\t<select class=\"form-control\" name=\"custom[" . $key . "][]\" multiple=\"multiple\" >\n";
                $content_2 .= "\t\t\t\t\t\t\t<option value=\"\"> --- </option>\n";
                $content_2 .= "\n\t\t\t\t\t<!-- BEGIN: " . $key . " -->\n";
                $content_2 .= "\t\t\t\t\t\t<option value=\"{OPTION.key}\" {OPTION.selected}>{OPTION.title}</option\n>";
                $content_2 .= "\t\t\t\t\t<!-- END: " . $key . " -->\n";
                $content_2 .= "\t\t\t\t\t</select>\n";
                $content_2 .= "\t\t\t\t";
            } else {
                switch ($column['field_type']) {
                    case 'email':
                        $type_html = 'email';
                        break;
                    case 'url':
                        $type_html = 'url';
                        break;
                    case 'password':
                        $type_html = 'password';
                        break;
                    default:
                        $type_html = 'text';
                }

                $oninvalid = true;
                $content_2 .= "<input class=\"form-control\" type=\"" . $type_html . "\" name=\"custom[" . $key . "]\" value=\"{ROW." . $key . "}\" ";
                if ($column['field_type'] == 'date' or $column['field_type'] == 'time') {
                    $content_2 .= 'id="' . $key . '" pattern="^[0-9]{2,2}\/[0-9]{2,2}\/[0-9]{1,4}$" ';
                    $array_field_js['date'][] = '#' . $key;
                } elseif ($column['field_type'] == 'textfile') {
                    $content_2 .= 'id="id_' . $key . '" ';
                    $array_field_js['file'][] = $key;
                } elseif ($column['field_type'] == 'textalias') {
                    $content_2 .= 'id="id_' . $key . '" ';
                } elseif ($column['field_type'] == 'email') {
                    $content_2 .= "oninvalid=\"setCustomValidity( nv_email )\" oninput=\"setCustomValidity('')\" ";
                    $oninvalid = false;
                } elseif ($column['field_type'] == 'url') {
                    $content_2 .= "oninvalid=\"setCustomValidity( nv_url )\" oninput=\"setCustomValidity('')\" ";
                    $oninvalid = false;
                } elseif ($column['field_type'] == 'number_int') {
                    $content_2 .= "pattern=\"^[0-9]*$\"  oninvalid=\"setCustomValidity( nv_digits )\" oninput=\"setCustomValidity('')\" ";
                    $oninvalid = false;
                } elseif ($column['field_type'] == 'number_float') {
                    $content_2 .= "pattern=\"^([0-9]*)(\.*)([0-9]+)$\" oninvalid=\"setCustomValidity( nv_number )\" oninput=\"setCustomValidity('')\" ";
                    $oninvalid = false;
                }

                if (isset($array_requireds[$key])) {
                    $content_2 .= 'required="required" ';
                    if ($oninvalid) {
                        $content_2 .= "oninvalid=\"setCustomValidity( nv_required )\" oninput=\"setCustomValidity('')\" ";
                    }
                }

                $content_2 .= "/>";
                if ($column['field_type'] == 'textfile') {
                    $content_2 .= '&nbsp;<button type="button" class="btn btn-info" id="img_' . $key . '"><i class="fa fa-folder-open-o">&nbsp;</i> Browse server </button>';
                }
                if ($column['field_type'] == 'textalias' and $array_field_js['textalias'] == $key) {
                    $content_2 .= "&nbsp;<i class=\"fa fa-refresh fa-lg icon-pointer\" onclick=\"nv_get_alias('id_" . $key . "');\">&nbsp;</i>";
                }
            }
            $content_2 .= "</div>\n";
            $content_2 .= "\t\t\t</div>\n";
        }

        $content_2 .= "\t\t</div>\n";
        $content_2 .= "\t</div>\n";

        if (!empty($array_field_js['date'])) {
            $array_field_js['date'] = implode(',', $array_field_js['date']);
            $content_2 .= "\n<script type=\"text/javascript\">\n";
            $content_2 .= "$(document).ready(function() {\n";
            $content_2 .= "\t$(\"" . $array_field_js['date'] . "\").datepicker({\n";
            $content_2 .= "\t	showOn : \"both\",\n";
            $content_2 .= "\t	dateFormat : \"dd/mm/yy\",\n";
            $content_2 .= "\t	changeMonth : true,\n";
            $content_2 .= "\t	changeYear : true,\n";
            $content_2 .= "\t	showOtherMonths : true,\n";
            $content_2 .= "\t	buttonImage : nv_base_siteurl + \"assets/images/calendar.gif\",\n";
            $content_2 .= "\t	buttonImageOnly : true\n";
            $content_2 .= "\t});\n";
            $content_2 .= "});\n";
            $content_2 .= "</script>\n";
        }

        $content_2 .= "<!-- END: main -->";

        if (!file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/files_tpl')) {
            nv_mkdir(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload, 'files_tpl');
        }

        $file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/files_tpl/cat_form_' . preg_replace('/[\-]/', '_', $array_template[$templateids_i]['alias']) . '.tpl';
        file_put_contents($file, $content_2, LOCK_EX);
    }
}

/**
 * nv_get_data_type()
 *
 * @param mixed $dataform
 * @return
 */


function nv_get_data_type($dataform)
{
    $type_date = '';
    if ($dataform['field_type'] == 'number') {
        $type_date = "DOUBLE NOT NULL DEFAULT '" . $dataform['default_value'] . "'";
    } elseif ($dataform['field_type'] == 'date') {
        $type_date = "INT(11) NOT NULL DEFAULT '0'";
    } elseif ($dataform['max_length'] <= 255) {
        $type_date = "VARCHAR( " . $dataform['max_length'] . " ) NOT NULL DEFAULT ''";
    } elseif ($dataform['max_length'] <= 65536) {
        //2^16 TEXT

        $type_date = 'TEXT NOT NULL';
    } elseif ($dataform['max_length'] <= 16777216) {
        //2^24 MEDIUMTEXT

        $type_date = 'MEDIUMTEXT NOT NULL';
    } elseif ($dataform['max_length'] <= 4294967296) {
        //2^32 LONGTEXT

        $type_date = 'LONGTEXT NOT NULL';
    }

    return $type_date;
}
