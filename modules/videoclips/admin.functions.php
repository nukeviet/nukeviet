<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 20 Sep 2012 04:05:46 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) die('Stop!!!');

$allow_func = array(
    'main',
    'topic',
    'vbroken',
    'cbroken',
    'config',
    'view'
);

define('NV_IS_FILE_ADMIN', true);

/**
 * nv_settopics()
 *
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_settopics($list2, $id, $list, $m = 0, $num = 0)
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
                    $list2 = nv_settopics($list2, $value['id'], $list, $m, $num);
                }
            }
        }
    }
    return $list2;
}

/**
 * nv_listTopics()
 *
 * @param mixed $parentid
 * @param integer $m
 * @return
 */
function nv_listTopics($parentid, $m = 0)
{
    global $db, $module_data;

    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` ORDER BY `parentid`,`weight` ASC";
    $result = $db->query($sql);
    $list = array();
    while ($row = $result->fetch()) {
        $list[$row['parentid']][] = array( //
            'id' => (int) $row['id'], //
            'parentid' => (int) $row['parentid'], //
            'title' => $row['title'], //
            'alias' => $row['alias'], //
            'description' => $row['description'], //
            'weight' => (int) $row['weight'], //
            'status' => $row['weight'], //
            'name' => $row['title'], //
            'selected' => $parentid == $row['id'] ? " selected=\"selected\"" : ""
        ) //
;
    }

    if (empty($list)) return $list;

    $list2 = array();
    foreach ($list[0] as $value) {
        if ($value['id'] != $m) {
            $list2[$value['id']] = $value;
            if (isset($list[$value['id']])) {
                $list2 = nv_settopics($list2, $value['id'], $list, $m);
            }
        }
    }

    return $list2;
}

function nv_myAlias($alias, $mode = 0, $id = 0, $_id = 1)
{
    global $db, $module_data;

    if ($mode == 1) //Edit Topic
{
        $where1 = "";
        $where2 = " `id`!=" . $id . " AND";
    } elseif ($mode == 2) //Edit Video
{
        $where1 = " `id`!=" . $id . " AND";
        $where2 = "";
    } else {
        $where1 = $where2 = "";
    }

    if ((list ($count) = $db->sql_fetchrow($db->query("SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_clip` WHERE" . $where1 . " `alias`=" . $db->quote($alias)))) and $count != 0) {
        if (preg_match("/^(.*)\-(\d+)$/", $alias, $matches)) {
            $alias = $matches[1];
            $_id = $matches[2] + 1;
        }
        $alias = nv_myAlias($alias . "-" . $_id, $mode, $id, ++$_id);
    } elseif ((list ($count2) = $db->sql_fetchrow($db->query("SELECT COUNT(*) AS count FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topic` WHERE" . $where2 . " `alias`=" . $db->quote($alias)))) and $count2 != 0) {
        if (preg_match("/^(.*)\-(\d+)$/", $alias, $matches)) {
            $alias = $matches[1];
            $_id = $matches[2] + 1;
        }
        $alias = nv_myAlias($alias . "-" . $_id, $mode, $id, ++$_id);
    }

    return $alias;
}