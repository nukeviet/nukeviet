<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Ket noi file ngon ngu tuy chinh du lieu
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/custom_' . NV_LANG_INTERFACE . '.php')) {
    $lang_temp = $lang_module;
    require NV_ROOTDIR . '/modules/' . $module_file . '/language/custom_' . NV_LANG_INTERFACE . '.php';
    $lang_module = $lang_module + $lang_temp;
    unset($lang_temp);
}

// Cau hinh mac dinh
$pro_config = $module_config[$module_name];

if (! empty($pro_config)) {
    $temp = explode('x', $pro_config['image_size']);
    $pro_config['homewidth'] = $temp[0];
    $pro_config['homeheight'] = $temp[1];
    $pro_config['blockwidth'] = $temp[0];
    $pro_config['blockheight'] = $temp[1];
}
if (empty($pro_config['format_order_id'])) {
    $pro_config['format_order_id'] = strtoupper($module_name) . '%d';
}
if (empty($pro_config['timecheckstatus'])) {
    $pro_config['timecheckstatus'] = 0;
} // Thoi gian xu ly archive

// Tu dong xoa don hang sau tgian khong duoc xac nhan
if (!empty($pro_config['order_day']) and $pro_config['order_nexttime'] < NV_CURRENTTIME) {
    $result = $db->query('SELECT order_id, order_time FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE transaction_status=0 AND order_time < ' . (NV_CURRENTTIME - (int)$pro_config['order_day'] * 86400));
    while (list($order_id, $order_time) = $result->fetch(3)) {
        $result_del = $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_orders WHERE order_id=' . $order_id);
        if ($result_del) {
            // Thong tin dat hang chi tiet
            $listid = $listnum = array();
            $result = $db->query("SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_orders_id WHERE order_id=" . $order_id);
            while ($row = $result->fetch()) {
                $listid[] = $row['id'];
                $listnum[] = $row['num'];
            }

            // Cong lai san pham trong kho
            if ($pro_config['active_order_number'] == '0') {
                product_number_order($listid, $listnum, "+");
            }

            // Tru lai so san pham da ban
            product_number_sell($listid, $listnum, "-");

            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete order', 'ID: ' . $order_id);
        }
    }
    $db->query("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = " . (NV_CURRENTTIME + 86400) . " WHERE lang='" . NV_LANG_DATA . "' AND module=" . $db->quote($module_name) . ' AND config_name="order_nexttime"');
    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);
}

// Xu ly viec dang san pham tu dong, cho het han san pham ...
if ($pro_config['timecheckstatus'] > 0 and $pro_config['timecheckstatus'] < NV_CURRENTTIME) {
    nv_set_status_module();
}

/**
 * nv_set_status_module()
 *
 * @return
 */
function nv_set_status_module()
{
    global $nv_Cache, $db, $module_name, $module_data, $global_config, $db_config;

    $check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5($module_data . 'nv_set_status_module' . $global_config['sitekey']) . '.txt';
    $p = NV_CURRENTTIME - 300;
    if (file_exists($check_run_cronjobs) and @filemtime($check_run_cronjobs) > $p) {
        return;
    }
    file_put_contents($check_run_cronjobs, '');

    // status_0 = "Cho duyet";
    // status_1 = "Xuat ban";
    // status_2 = "Hen gio dang";
    // status_3= "Het han";

    // Dang cac san pham cho kich hoat theo thoi gian
    $result = $db->query('SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =2 AND publtime < ' . NV_CURRENTTIME . ' ORDER BY publtime ASC');
    while (list($id) = $result->fetch(3)) {
        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status =1 WHERE id=' . $id);
    }

    // Ngung hieu luc cac san pham da het han
    $result = $db->query('SELECT id, archive FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY exptime ASC');
    while (list($id, $archive) = $result->fetch(3)) {
        if (intval($archive) == 0) {
            nv_del_content_module($id);
        } else {
            nv_archive_content_module($id);
        }
    }

    // Tim kiem thoi gian chay lan ke tiep
    $time_publtime = $db->query('SELECT MIN(publtime) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =2 AND publtime > ' . NV_CURRENTTIME)->fetchColumn();

    $time_exptime = $db->query('SELECT MIN(exptime) FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE status =1 AND exptime > ' . NV_CURRENTTIME)->fetchColumn();

    $timecheckstatus = min($time_publtime, $time_exptime);
    if (! $timecheckstatus) {
        $timecheckstatus = max($time_publtime, $time_exptime);
    }

    $db->query("REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES('" . NV_LANG_DATA . "', " . $db->quote($module_name) . ", 'timecheckstatus', '" . intval($timecheckstatus) . "')");
    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);

    unlink($check_run_cronjobs);
    clearstatcache();
}

/**
 * nv_del_content_module()
 *
 * @param mixed $id
 * @return
 */
function nv_del_content_module($id)
{
    global $db, $module_name, $module_data, $title, $db_config;

    $content_del = 'NO_' . $id;
    $title = '';

    list($id, $listcatid, $title) = $db->query('SELECT id, listcatid, ' . NV_LANG_DATA . '_title FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . intval($id))->fetch(3);
    if ($id > 0) {
        $number_no_del = 0;
        $array_catid = explode(',', $listcatid);
        if ($number_no_del == 0) {
            $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id=' . $id;
            if (! $db->exec($sql)) {
                ++$number_no_del;
            }
        }
        if ($number_no_del == 0) {
            // Xoa binh luan
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote($module_name) . ' AND id = ' . $id);

            // Xoa block san pham
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_block WHERE id = ' . $id);

            // Xoa du lieu nhom san pham
            $groupid = GetGroupID($id);
            if ($db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE pro_id = ' . $id)) {
                // Xoa chi tiet nhap kho
                $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id = ' . $id);

                nv_fix_group_count($groupid);
            }

            // Xoa tai lieu
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_files_rows WHERE id_rows=' . $id);

            // Xoa du lieu tuy bien
            $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_field_value_'.NV_LANG_DATA.' WHERE rows_id = ' . $id);

            $content_del = 'OK_' . $id;
        } else {
            $content_del = 'ERR_' . $lang_module['error_del_content'];
        }
    }
    return $content_del;
}

/**
 * nv_del_group()
 *
 * @param mixed $groupid
 * @return
 */
function nv_del_group($groupid)
{
    global $db, $module_data, $db_config;

    $allgroupid = GetGroupID($groupid);
    if ($db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_group WHERE groupid=" . $groupid)) {
        // Loai bo san pham ra khoi nhom
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items WHERE group_id = ' . $groupid);

        // Xoa cateid
        $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid WHERE groupid = ' . $groupid);

        // Xoa chi tiet nhap kho, neu nhu chi tiet nhap kho co nhom nay, thi xoa luon chi tiet nhap kho
        $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity');
        while ($row = $result->fetch()) {
            if (in_array($groupid, explode(',', $listgroup))) {
                $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id = ' . $row['pro_id'] . ' AND listgroup=' . $db->quote($row['listgroup']));
            }
        }
        nv_fix_group_count($allgroupid);
    }
}

/**
 * nv_archive_content_module()
 *
 * @param mixed $id
 * @return
 */
function nv_archive_content_module($id)
{
    global $db, $module_data, $db_config;
    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET status =3 WHERE id=' . $id);
}

/**
 * nv_link_edit_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_edit_page($id)
{
    global $lang_global, $module_name;
    $link = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\" title=\"" . $lang_global['edit'] . "\"><em class=\"fa fa-edit fa-lg\">&nbsp;</em></a>";
    return $link;
}

/**
 * nv_link_delete_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_delete_page($id)
{
    global $lang_global, $module_name;
    $link = "<a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5($id . session_id()) . "','" . NV_BASE_ADMINURL . "')\" title=\"" . $lang_global['delete'] . "\"><em class=\"fa fa-trash-o fa-lg\">&nbsp;</em></a>";
    return $link;
}

/**
 * nv_file_table()
 *
 * @param mixed $table
 * @return
 */
function nv_file_table($table)
{
    global $db_config, $db;
    $lang_value = nv_list_lang();
    $arrfield = array();
    $result = $db->query('SHOW COLUMNS FROM ' . $table);
    while (list($field) = $result->fetch(3)) {
        $tmp = explode('_', $field, 2);
        foreach ($lang_value as $lang_i) {
            if (! empty($tmp[0]) && ! empty($tmp[1])) {
                if ($tmp[0] == $lang_i) {
                    $arrfield[] = array( $tmp[0], $tmp[1] );
                    break;
                }
            }
        }
    }
    return $arrfield;
}

/**
 * nv_list_lang()
 *
 * @return
 */
function nv_list_lang()
{
    global $db_config, $db;
    $re = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1');
    $lang_value = array();
    while (list($lang_i) = $re->fetch(3)) {
        $lang_value[] = $lang_i;
    }
    return $lang_value;
}

// Tru so luong trong kho $type = "-"
// Cong so luong trong kho $type = "+"
// $listid : danh sach cac id product
// $listnum : danh sach so luong tuong ung

/**
 * product_number_order()
 *
 * @param mixed $listid
 * @param mixed $listnum
 * @param string $type
 * @return
 */
function product_number_order($listid, $listnum, $listgroup, $type = '-')
{
    global $db_config, $db, $module_data;

    foreach ($listid as $i => $id) {
        if ($id > 0) {
            if (empty($listnum[$i])) {
                $listnum[$i] = 0;
            }

            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_number = product_number ' . $type . ' ' . intval($listnum[$i]) . ' WHERE id =' . $id;
            $db->query($sql);

            if (!empty($listgroup)) {
                $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity SET quantity = quantity ' . $type . ' ' . intval($listnum[$i]) . ' WHERE pro_id =' . $id . ' AND listgroup=' . $db->quote($listgroup[$i]);
                $db->query($sql);
            }
        }
    }
}

/**
 * product_number_sell()
 *
 * @param mixed $listid
 * @param mixed $listnum
 * @param string $type
 * @return
 */
function product_number_sell($listid, $listnum, $type = '+')
{
    global $db_config, $db, $module_data;

    foreach ($listid as $i => $id) {
        if ($id > 0) {
            if (empty($listnum[$i])) {
                $listnum[$i] = 0;
            }

            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET num_sell = num_sell ' . $type . ' ' . intval($listnum[$i]) . ' WHERE id =' . $id;
            $db->query($sql);
        }
    }
}

/**
 * nv_fix_group_count()
 *
 * @param mixed $listid
 * @return
 */
function nv_fix_group_count($listid)
{
    global $db, $module_data, $db_config;

    foreach ($listid as $id) {
        if (! empty($id)) {
            $sql = "SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $module_data . "_group_items WHERE ( group_id='" . $id . "' OR group_id REGEXP '^" . $id . "\\\,' OR group_id REGEXP '\\\," . $id . "\\\,' OR group_id REGEXP '\\\," . $id . "$' )";
            $num = $db->query($sql)->fetchColumn();

            $sql = "UPDATE " . $db_config['prefix'] . "_" . $module_data . "_group SET numpro=" . $num . " WHERE groupid=" . intval($id);
            $db->query($sql);

            unset($result);
        }
    }
}

/**
 * GetCatidInParent()
 *
 * @param mixed $catid
 * @param integer $check_inhome
 * @return
 */
function GetCatidInParent($catid, $check_inhome = 0)
{
    global $global_array_shops_cat, $array_cat;
    $array_cat[] = $catid;
    $subcatid = explode(',', $global_array_shops_cat[$catid]['subcatid']);
    if (! empty($subcatid)) {
        foreach ($subcatid as $id) {
            if ($id > 0) {
                if ($global_array_shops_cat[$id]['numsubcat'] == 0) {
                    if (! $check_inhome or ($check_inhome and $global_array_shops_cat[$id]['inhome'] == 1)) {
                        $array_cat[] = $id;
                    }
                } else {
                    $array_cat_temp = GetCatidInParent($id, $check_inhome);
                    foreach ($array_cat_temp as $catid_i) {
                        if (! $check_inhome or ($check_inhome and $global_array_shops_cat[$catid_i]['inhome'] == 1)) {
                            $array_cat[] = $catid_i;
                        }
                    }
                }
            }
        }
    }
    return array_unique($array_cat);
}

/**
 * GetParentCatFilter()
 *
 * @param mixed $cateid
 * @return
 */

function GetParentCatFilter($cateid)
{
    global $db, $db_config, $global_array_shops_cat, $module_name, $module_data;

    $cid = 0;
    while (true) {
        $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_cateid WHERE cateid = ' . $cateid)->fetchColumn();
        if ($count == 0 and isset($global_array_shops_cat[$cateid])) {
            $cateid = $global_array_shops_cat[$cateid]['parentid'];
            continue;
        } else {
            $cid = $cateid;
            break;
        }
    }
    return $cid;
}

/**
 * GetGroupidInParent()
 *
 * @param mixed $groupid
 * @param integer $check_inhome
 * @return
 */
function GetGroupidInParent($groupid, $check_inhome = 0, $only_children = 0)
{
    global $global_array_group, $array_group;

    if ($only_children) {
        $array_group = array();
    } else {
        $array_group[] = $groupid;
    }

    $subgroupid = explode(',', $global_array_group[$groupid]['subgroupid']);
    if (! empty($subgroupid)) {
        foreach ($subgroupid as $id) {
            if ($id > 0) {
                if ($global_array_group[$id]['numsubgroup'] == 0) {
                    if (! $check_inhome or ($check_inhome and $global_array_group[$id]['inhome'] == 1)) {
                        $array_group[] = $id;
                    }
                } else {
                    $array_group_temp = GetGroupidInParent($id, $check_inhome);
                    foreach ($array_group_temp as $groupid_i) {
                        if (! $check_inhome or ($check_inhome and $global_array_group[$groupid_i]['inhome'] == 1)) {
                            $array_group[] = $groupid_i;
                        }
                    }
                }
            }
        }
    }
    return array_unique($array_group);
}

/**
 * GetGroupID()
 *
 * @param mixed $pro_id
 * @return
 */
function GetGroupID($pro_id, $group_by_parent = 0)
{
    global $db, $db_config, $module_data, $global_array_group;

    $data = array();
    $result = $db->query('SELECT group_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_items where pro_id=' . $pro_id);
    while ($row = $result->fetch()) {
        if ($group_by_parent) {
            $parentid = $global_array_group[$row['group_id']]['parentid'];
            $data[$parentid][] = $row['group_id'];
        } else {
            $data[] = $row['group_id'];
        }
    }
    return $data;
}

function UpdatePoint($data_content, $add = true)
{
    global $db, $db_config, $module_data;

    $result = $db->query('SELECT point FROM ' . $db_config['prefix'] . "_" . $module_data . '_point_queue WHERE order_id=' . $data_content['order_id']);
    list($point) = $result->fetch(3);
    if (! empty($point)) {
        if ($add) {
            $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . "_" . $module_data . '_point WHERE userid=' . $data_content['user_id']);
            if ($result->rowCount() == 1) {
                $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_point SET point_total=point_total + " . $point . " WHERE userid=" . $data_content['user_id']);
            } else {
                $db->query("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_point(userid, point_total) VALUES (" . $data_content['user_id'] . ", " . $point . ")");
            }

            // Cap nhat lich su nhan diem
            $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_point_queue SET status = 0 WHERE order_id=" . $data_content['order_id']);
            $db->query("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_point_history(userid, order_id, point, time) VALUES (" . $data_content['user_id'] . ", " . $data_content['order_id'] . ", " . $point . ", " . NV_CURRENTTIME . ")");
        } else {
            $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_point SET point_total=point_total - " . $point . " WHERE userid=" . $data_content['user_id']);
            // Cap nhat lich su nhan diem
            $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_point_queue SET status = 1 WHERE order_id=" . $data_content['order_id']);
            $db->query("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_point_history(userid, order_id, point, time) VALUES (" . $data_content['user_id'] . ", " . $data_content['order_id'] . ", -" . $point . ", " . NV_CURRENTTIME . ")");
        }
    }
}

function nv_listmail_notify()
{
    global $db, $global_config, $pro_config;

    $array_mail = array();
    if (!empty($pro_config['groups_notify'])) {
        $result = $db->query('SELECT email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT userid FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE group_id IN ( ' . $pro_config['groups_notify'] . ' ) )');
        while (list($email) = $result->fetch(3)) {
            $array_mail[] = $email;
        }
    }
    $array_mail = array_unique($array_mail);

    return $array_mail;
}
