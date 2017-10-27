<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) die('Stop!!!');

$allow_func = array(
    'main',
    'area',
    'cat',
    'subject',
    'examine',
    'getlid',
    'signer',
    'scontent',
    'config',
    'change_cat',
    'view'
);

define('NV_IS_FILE_ADMIN', true);

function nv_setCats($list2, $id, $list, $num = 0)
{
    $num++;
    $defis = "";
    for ($i = 0; $i < $num; $i++) {
        $defis .= "---";
    }

    if (isset($list[$id])) {
        foreach ($list[$id] as $value) {
            $list2[$value['id']] = $value;
            $list2[$value['id']]['count'] = isset($list[$value['id']]) ? count($list[$value['id']]) : 0;
            $list2[$value['id']]['pcount'] = count($list[$list2[$value['id']]['parentid']]);
            $list2[$value['id']]['name'] = "&nbsp;|" . $defis . " " . $list2[$value['id']]['name'];
            if (isset($list[$value['id']])) {
                $list2 = nv_setCats($list2, $value['id'], $list, $num);
            }
        }
    }
    return $list2;
}

function nv_catList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY parentid,weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['parentid']][] = array( //
            'id' => (int) $row['id'], //
            'parentid' => (int) $row['parentid'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => (int) $row['weight'], //
            'name' => $row['title'], //
            'newday' => $row['newday'] //
        );
    }

    if (empty($list)) {
        return $list;
    }

    $list2 = array();
    foreach ($list[0] as $value) {
        $list2[$value['id']] = $value;
        $list2[$value['id']]['count'] = isset($list[$value['id']]) ? count($list[$value['id']]) : 0;
        $list2[$value['id']]['pcount'] = count($list[$list2[$value['id']]['parentid']]);
        if (isset($list[$value['id']])) {
            $list2 = nv_setCats($list2, $value['id'], $list);
        }
    }

    return $list2;
}

function fix_catWeight($parentid)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . intval($parentid) . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
}

function nv_aList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_area ORDER BY parentid,weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['parentid']][] = array( //
            'id' => (int) $row['id'], //
            'parentid' => (int) $row['parentid'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'weight' => (int) $row['weight'], //
            'name' => $row['title'] //
        );
    }

    if (empty($list)) {
        return $list;
    }

    $list2 = array();
    foreach ($list[0] as $value) {
        $list2[$value['id']] = $value;
        $list2[$value['id']]['count'] = isset($list[$value['id']]) ? count($list[$value['id']]) : 0;
        $list2[$value['id']]['pcount'] = count($list[$list2[$value['id']]['parentid']]);
        if (isset($list[$value['id']])) {
            $list2 = nv_setCats($list2, $value['id'], $list);
        }
    }

    return $list2;
}

function fix_aWeight($parentid)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_area WHERE parentid=" . intval($parentid) . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_area SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
}

function nv_sList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject ORDER BY weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['id']] = array( //
            'id' => (int) $row['id'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'numlink' => $row['numlink'], //
            'weight' => (int) $row['weight'] //
        );
    }

    return $list;
}

function nv_eList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_examine ORDER BY weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['id']] = array( //
            'id' => (int) $row['id'], //
            'title' => $row['title'], //
            'weight' => (int) $row['weight'] //
        );
    }

    return $list;
}

function nv_sgList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_signer ORDER BY id ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['id']] = array( //
            'id' => (int) $row['id'], //
            'title' => $row['title'] //
            //'alias' => $row['alias'], //
            //'weight' => ( int )$row['weight'] //
        );
    }

    return $list;
}

function fix_subjectWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_subject SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
}

function fix_examineWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_examine ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while ($row = $result->fetch()) {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_examine SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
}

function nv_GetCatidInParent($id, $array_cat)
{
    $array_id = array();
    $array_id[] = $id;

    if (!empty($array_cat)) {
        foreach ($array_cat as $cat) {
            if ($cat['parentid'] == $id) {
                $array_id[] = $cat['id'];
                $array_id_tmp = nv_GetCatidInParent($cat['id'], $array_cat);
                foreach ($array_id_tmp as $id_tmp) {
                    $array_id[] = $id_tmp;
                }
            }
        }
    }
    return array_unique($array_id);
}
