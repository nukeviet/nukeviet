<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/14/2017 09:47
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array( 'main', 'cat', 'config','acceptqa','editqa' );

define('NV_IS_FILE_ADMIN', true);

/**
 * nv_setcats()
 *
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_setcats($list2, $id, $list, $m = 0, $num = 0)
{
    ++$num;
    $defis = "";
    for ($i = 0; $i < $num; ++$i) {
        $defis .= "--";
    }

    if (isset($list[$id])) {
        foreach ($list[$id] as $value) {
            if ($value['id'] != $m) {
                $list2[$value['id']] = $value;
                $list2[$value['id']]['name'] = "|" . $defis . "&gt; " . $list2[$value['id']]['name'];
                if (isset($list[$value['id']])) {
                    $list2 = nv_setcats($list2, $value['id'], $list, $m, $num);
                }
            }
        }
    }
    return $list2;
}

/**
 * nv_listcats()
 *
 * @param mixed $parentid
 * @param integer $m
 * @return
 */
function nv_listcats($parentid, $m = 0)
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_categories ORDER BY parentid,weight ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['parentid']][] = array(
            'id' => ( int )$row['id'],
            'parentid' => ( int )$row['parentid'],
            'title' => $row['title'],
            'alias' => $row['alias'],
            'description' => $row['description'],
            'groups_view' => ! empty($row['groups_view']) ? explode(",", $row['groups_view']) : array(),
            'weight' => ( int )$row['weight'],
            'status' => $row['weight'],
            'name' => $row['title'],
            'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
            );
    }

    if (empty($list)) {
        return $list;
    }

    $list2 = array();
    foreach ($list[0] as $value) {
        if ($value['id'] != $m) {
            $list2[$value['id']] = $value;
            if (isset($list[$value['id']])) {
                $list2 = nv_setcats($list2, $value['id'], $list, $m);
            }
        }
    }

    return $list2;
}

/**
 * nv_update_keywords()
 *
 * @param mixed $catid
 * @return
 */
function nv_update_keywords($catid)
{
    global $db, $module_data;

    $content = array();

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $catid . " AND status=1";
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $content[] = $row['title'] . " " . $row['question'] . " " . $row['answer'];
    }

    $content = implode(" ", $content);

    $keywords = nv_get_keywords($content);

    if (! empty($keywords)) {
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_categories SET keywords=" . $db->quote($keywords) . " WHERE id=" . $catid);
    }

    return $keywords;
}
