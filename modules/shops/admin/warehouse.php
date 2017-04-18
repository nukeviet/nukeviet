<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!$pro_config['active_warehouse']) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
    die();
}

$page_title = sprintf($lang_module['warehouse_day'], nv_date('d/m/Y', NV_CURRENTTIME));

if ($nv_Request->isset_request('checkss', 'get') and $nv_Request->get_string('checkss', 'get') == md5($global_config['sitekey'] . session_id())) {
    $array_data = array();
    $array_warehouse = array( 'title' => $page_title, 'note' => '' );
    $listid = $nv_Request->get_string('listid', 'get', '');
    if (empty($listid)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
        die();
    } else {
        $listid = rtrim($listid, ',');
    }

    if ($nv_Request->isset_request('submit', 'post')) {
        $title = $nv_Request->get_title('title', 'post', $page_title);
        $note = $nv_Request->get_textarea('note', '', 'br');
        $data = $nv_Request->get_array('data', 'post', array());

        $sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse( title, note, user_id, addtime ) VALUES ( :title, :note, ' . $admin_info['admin_id'] . ', ' . NV_CURRENTTIME . ' )';
        $data_insert = array();
        $data_insert['title'] = $title;
        $data_insert['note'] = $note;
        $wid = $db->insert_id($sql, 'wid', $data_insert);

        if ($wid > 0 and !empty($data)) {
            foreach ($data as $pro_id => $data_i) {
                $total_num = 0;
                $price_i = 0;
                $total_price = 0;
                $array_data_group = array();

                foreach ($data_i as $key => $group_data) {
                    if (!empty($group_data['quantity'])) {
                        $total_num += $group_data['quantity'];
                        $price_i = floatval(preg_replace('/[^0-9\.]/', '', $group_data['price']));
                        $total_price += $price_i;
                        $money_unit_i = $group_data['money_unit'];

                        if (isset($group_data['group']) and !empty($group_data['group'])) {
                            $group_data['group'] = array_filter($group_data['group']);
                            asort($group_data['group']);
                            $listgroup = implode(',', $group_data['group']);

                            $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE listgroup="' . $listgroup . '" AND pro_id=' . $pro_id)->fetchColumn();
                            if ($count) {
                                $result = $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity SET quantity = quantity + ' . $group_data['quantity'] . ' WHERE listgroup="' . $listgroup . '" AND pro_id=' . $pro_id);
                            } else {
                                $result = $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity( pro_id, listgroup, quantity ) VALUES( ' . $pro_id . ', ' . $db->quote($listgroup) . ', ' . $group_data['quantity'] . ' )');
                            }
                            $array_data_group[$listgroup] = array( 'quantity' => $group_data['quantity'], 'price' => $price_i, 'money_unit' => $money_unit_i );
                        }
                    }
                }

                // Cap nhat logs nhap kho
                $sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs( wid, pro_id, quantity, price, money_unit ) VALUES ( ' . $wid .  ', ' . $pro_id . ', ' . $total_num . ', ' . $total_price . ', :money_unit )';
                $data_insert = array();
                $data_insert['money_unit'] = $money_unit_i;
                $logid = $db->insert_id($sql, 'logid', $data_insert);
                if (!empty($array_data_group) and $logid > 0) {
                    $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_warehouse_logs_group( logid, listgroup, quantity, price, money_unit ) VALUES ( ' . $logid . ', :listgroup, :quantity, :price, :money_unit )');
                    foreach ($array_data_group as $listgroup => $data_group) {
                        $sth->bindParam(':listgroup', $listgroup, PDO::PARAM_INT);
                        $sth->bindParam(':quantity', $data_group['quantity'], PDO::PARAM_STR);
                        $sth->bindParam(':price', $data_group['price'], PDO::PARAM_STR);
                        $sth->bindParam(':money_unit', $data_group['money_unit'], PDO::PARAM_STR);
                        $sth->execute();
                    }
                }
                // Cap nhat tong so luong
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_rows SET product_number = product_number + ' . $total_num . ' WHERE id=' . $pro_id);
            }
        }
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
        die();
    }

    // List pro_unit
    $array_unit = array();
    $sql = 'SELECT id, ' . NV_LANG_DATA . '_title title FROM ' . $db_config['prefix'] . '_' . $module_data . '_units';
    $result_unit = $db->query($sql);
    if ($result_unit->rowCount() > 0) {
        while ($row = $result_unit->fetch()) {
            $array_unit[$row['id']] = $row;
        }
    }

    $_sql = 'SELECT id, listcatid, ' . NV_LANG_DATA . '_title title, ' . NV_LANG_DATA . '_alias alias, product_number, product_unit, money_unit FROM ' . $db_config['prefix'] . '_' . $module_data . '_rows WHERE id IN (' . $listid . ') ORDER BY addtime DESC';
    $_query = $db->query($_sql);

    while ($row = $_query->fetch()) {
        $array_group = array();
        $result = $db->query('SELECT listgroup FROM ' . $db_config['prefix'] . '_' . $module_data . '_group_quantity WHERE pro_id=' . $row['id']);
        while (list($listgroup) = $result->fetch(3)) {
            $array_group[] = $listgroup;
        }
        $row['listgroup'] = $array_group;
        $array_data[$row['id']] = $row;
    }

    $xtpl = new XTemplate("warehouse.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('WAREHOUSE', $array_warehouse);

    if (!empty($array_data)) {
        $i=1;
        foreach ($array_data as $data) {
            $data['no'] = $i;
            $data['product_unit'] = $array_unit[$data['product_unit']]['title'];
            $data['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_shops_cat[$data['listcatid']]['alias'] . '/' . $data['alias'] . $global_config['rewrite_exturl'];
            $xtpl->assign('DATA', $data);

            // Nhom san pham
            $listgroup = GetGroupID($data['id']);
            $have_group = 0;
            if (!empty($listgroup)) {
                $parent_id = array();
                foreach ($listgroup as $group_id) {
                    $parent_id[] = $global_array_group[$group_id]['parentid'];
                }
                $parent_id = array_unique($parent_id);

                if (!empty($parent_id)) {
                    if (empty($data['listgroup'])) {
                        $data['listgroup'][] = implode(',', $listgroup);
                    }

                    foreach ($parent_id as $parent_id_i) {
                        $parent = $global_array_group[$parent_id_i];
                        if ($parent['in_order']) {
                            $xtpl->assign('PARENT', $parent);
                            $xtpl->parse('main.loop.group.parent');
                        }
                    }

                    $j=0;
                    foreach ($data['listgroup'] as $l_group) {
                        $l_group = explode(',', $l_group);
                        foreach ($parent_id as $parent_id_i) {
                            $parent = $global_array_group[$parent_id_i];
                            if ($parent['in_order']) {
                                $have_group = 1;
                                $xtpl->assign('PARENT', $parent);
                                foreach ($listgroup as $groupid) {
                                    $group = $global_array_group[$groupid];
                                    if ($group['parentid'] == $parent_id_i and $group['in_order']) {
                                        $group['selected'] = in_array($group['groupid'], $l_group) ? 'selected="selected"' : '';
                                        $xtpl->assign('GROUP', $group);
                                        $xtpl->parse('main.loop.group.loop.items.loop');
                                    }
                                }

                                if ($parent['is_require']) {
                                    $xtpl->parse('main.loop.group.loop.items.has_error');
                                    $xtpl->parse('main.loop.group.loop.items.has_requied');
                                }
                                $xtpl->assign('J', $j);
                                $xtpl->parse('main.loop.group.loop.items');
                            }
                        }

                        if (!empty($money_config)) {
                            foreach ($money_config as $code => $info) {
                                $info['selected'] = ($data['money_unit'] == $code) ? "selected=\"selected\"" : "";
                                $xtpl->assign('MON', $info);
                                $xtpl->parse('main.loop.group.loop.money_unit');
                            }
                        }
                        $xtpl->parse('main.loop.group.loop');
                        $j++;
                    }

                    if (!empty($listgroup)) {
                        foreach ($parent_id as $parent_id_i) {
                            $parent = $global_array_group[$parent_id_i];
                            if ($parent['in_order']) {
                                $xtpl->assign('PARENT', $parent);
                                foreach ($listgroup as $groupid) {
                                    $group = $global_array_group[$groupid];
                                    if ($group['parentid'] == $parent_id_i and $group['in_order']) {
                                        $xtpl->assign('GROUP', $group);
                                        $xtpl->parse('main.loop.group.itemsjs.loop');
                                    }
                                }

                                if ($parent['is_require']) {
                                    $xtpl->parse('main.loop.group.itemsjs.has_error');
                                    $xtpl->parse('main.loop.group.itemsjs.has_requied');
                                }

                                $xtpl->parse('main.loop.group.itemsjs');
                            }
                        }

                        if (!empty($money_config)) {
                            foreach ($money_config as $code => $info) {
                                $info['selected'] = ($data['money_unit'] == $code) ? "selected=\"selected\"" : "";
                                $xtpl->assign('MON', $info);
                                $xtpl->parse('main.loop.group.money_unit_js');
                            }
                        }
                    }
                    $xtpl->parse('main.loop.group');
                }
            }

            if (!$have_group) {
                if (!empty($money_config)) {
                    foreach ($money_config as $code => $info) {
                        $info['selected'] = ($data['money_unit'] == $code) ? "selected=\"selected\"" : "";
                        $xtpl->assign('MON', $info);
                        $xtpl->parse('main.loop.product_number.money_unit');
                    }
                }
                $xtpl->parse('main.loop.product_number');
            }

            $xtpl->parse('main.loop');
            $i++;
        }
    }
} else {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=items');
    die();
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
