<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_LAWS', true);

/**
 * nv_module_setting()
 *
 * @return
 */
function nv_module_setting()
{
    global $module_data, $module_name, $nv_Cache;

    $sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
    $list = $nv_Cache->db($sql, '', $module_name);

    $array = [];
    foreach ($list as $values) {
        $array[$values['config_name']] = $values['config_value'];
    }

    return $array;
}

/**
 * nv_setcats()
 *
 * @param mixed $id
 * @param mixed $list
 * @param mixed $name
 * @param mixed $is_parentlink
 * @return
 */
function nv_setcats($id, $list, $name, $is_parentlink)
{
    global $module_name;

    if ($is_parentlink) {
        $name = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list[$id]['alias'] . "\">" . $list[$id]['title'] . "</a> &raquo; " . $name;
    } else {
        $name = $list[$id]['title'] . " &raquo; " . $name;
    }
    $parentid = $list[$id]['parentid'];
    if ($parentid) {
        $name = nv_setcats($parentid, $list, $name, $is_parentlink);
    }

    return $name;
}

/**
 * nv_laws_listcat()
 *
 * @param bool $is_link
 * @param bool $is_parentlink
 * @param string $where
 * @return
 */
function nv_laws_listcat($is_link = false, $is_parentlink = true, $where = 'cat')
{
    global $module_data, $module_name, $module_info, $nv_Cache;

    $field = '';
    if ($where == 'cat') {
        $field = ', newday';
    }

    $sql = "SELECT id, parentid, alias, title, introduction, keywords " . $field . "
    FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $where . " ORDER BY parentid,weight ASC";

    $list = $nv_Cache->db($sql, 'id', $module_name);

    $list2 = [];

    if (!empty($list)) {
        foreach ($list as $row) {
            if (!$row['parentid'] or isset($list[$row['parentid']])) {
                $list2[$row['id']] = $list[$row['id']];
                $list2[$row['id']]['name'] = $list[$row['id']]['title'];
                $list2[$row['id']]['subcats'] = [];

                if ($is_link) {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list2[$row['id']]['alias'] . "\">" . $list2[$row['id']]['name'] . "</a>";
                }

                if ($row['parentid']) {
                    $list2[$row['parentid']]['subcats'][] = $row['id'];

                    $list2[$row['id']]['name'] = nv_setcats($row['parentid'], $list, $list2[$row['id']]['name'], $is_parentlink);
                }

                if ($is_parentlink) {
                    $list2[$row['id']]['name'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "\">" . $module_info['custom_title'] . "</a> &raquo; " . $list2[$row['id']]['name'];
                }
            }
        }
    }

    return $list2;
}

/**
 * nv_get_start_id()
 *
 * @param mixed $page
 * @param mixed $per_page
 * @return
 */
function nv_get_start_id($page, $per_page)
{
    return $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
}

/**
 * Xây dựng dữ liệu để xuất ra giao diện từ query CSDL
 * Mục đích gộp hết code các phần list (main, cat, subject, signer, search) về một
 *
 * @param PDOStatement $result
 * @param number $page
 * @param number $per_page
 * @return array
 */
function raw_law_list_by_result($result, $page = 1, $per_page = 1)
{
    global $db_slave, $module_data, $nv_laws_listsubject, $nv_laws_listarea, $nv_laws_listcat, $module_name, $module_info, $site_mods, $module_config, $nv_laws_setting, $lang_module;

    $array = $array_ids = [];
    $stt = nv_get_start_id($page, $per_page);

    while ($row = $result->fetch()) {
        $row['areatitle'] = '';
        $row['subjecttitle'] = $nv_laws_listsubject[$row['sid']]['title'];
        $row['cattitle'] = $nv_laws_listcat[$row['cid']]['title'];
        $row['url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'], true);
        $row['comm_url'] = nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'], true);

        if (($row['start_comm_time'] > 0 and $row['start_comm_time'] > NV_CURRENTTIME) or ($row['end_comm_time'] > 0 and $row['end_comm_time'] < NV_CURRENTTIME)) {
            $row['allow_comm'] = 0;
        } else {
            $row['allow_comm'] = 1;
        }

        $row['number_comm'] = 0;
        $row['comm_time'] = $row['start_comm_time'] . '-' . $row['end_comm_time'];
        $row['stt'] = $stt ++;
        $row['edit_link'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . $row['id'];

        if ($nv_laws_setting['down_in_home']) {
            // File download
            if (!empty($row['files'])) {
                $row['files'] = explode(",", $row['files']);
                $files = $row['files'];
                $row['files'] = array();

                foreach ($files as $id => $file) {
                    $file_title = basename($file);
                    $row['files'][] = array(
                        "title" => $file_title,
                        "titledown" => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
                        "url" => (!preg_match("/^http*/", $file)) ? NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['detail'] . "/" . $row['alias'] . "&amp;download=1&amp;id=" . $id : $file
                    );
                }
            }
        }

        $array[$row['id']] = $row;
        $array_ids[] = $row['id'];
    }

    if (!empty($array_ids)) {
        // Xác định lĩnh vực (area của các văn bản đã lấy được)
        $array_areas = [];
        $_result = $db_slave->query('SELECT row_id, area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id IN(' . implode(',', $array_ids) . ')');
        while ($row = $_result->fetch()) {
            if (isset($nv_laws_listarea[$row['area_id']])) {
                $array_areas[$row['row_id']][] = $nv_laws_listarea[$row['area_id']]['title'];
            }
        }
        foreach ($array_areas as $_id => $_title) {
            $array[$_id]['areatitle'] = implode(', ', $_title);
        }

        // Đếm số comment (ý kiến) của các văn bản lấy được
        if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
            $area = $module_info['funcs']['detail']['func_id'];
            $_where = 'module=' . $db_slave->quote($module_name);
            if ($area) {
                $_where .= ' AND area= ' . $area;
            }
            $_where .= ' AND id IN(' . implode(',', $array_ids) . ') AND status=1 AND pid=0 GROUP BY id';
            $db_slave->sqlreset()->select('COUNT(cid) countcomment, id')->from(NV_PREFIXLANG . '_comment')->where($_where);
            $_result = $db_slave->query($db_slave->sql());
            while ($row = $_result->fetch()) {
                $array[$row['id']]['number_comm'] = $row['countcomment'];
            }
        }
    }

    return $array;
}

global $nv_laws_listcat, $nv_laws_listarea, $nv_laws_listsubject, $nv_laws_setting;
$nv_laws_listcat = nv_laws_listcat();
$nv_laws_listarea = nv_laws_listcat(false, false, 'area');
$nv_laws_setting = nv_module_setting();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_subject ORDER BY weight ASC";
$list = $nv_Cache->db($sql, 'id', $module_name);
foreach ($list as $row) {
    $nv_laws_listsubject[$row['id']] = $row;
}

$rss[] = array(
    'title' => $module_info['custom_title'],
    'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss"
);

$catid = 0;
$catalias = "";

if ($op == "main") {
    $nv_vertical_menu = [];

    if (!empty($nv_laws_listcat)) {
        if (!empty($array_op)) {
            $catalias = isset($array_op[0]) ? $array_op[0] : "";
        }

        // Xac dinh ID cua chu de
        foreach ($nv_laws_listcat as $c) {
            if ($c['alias'] == $catalias) {
                $catid = intval($c['id']);
                break;
            }
        }

        if ($catid > 0) {
            $op = "cat";

            $parentid = $catid;
            while ($parentid > 0) {
                $c = $nv_laws_listcat[$parentid];
                $array_mod_title[] = array(
                    'catid' => $parentid,
                    'title' => $c['title'],
                    'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias']
                );
                $parentid = $c['parentid'];
            }
            krsort($array_mod_title, SORT_NUMERIC);
        }
    }
}

foreach ($nv_laws_listcat as $c) {
    if ($c['parentid'] == 0) {
        $sub_menu = [];
        $act = ($c['id'] == $catid) ? 1 : 0;
        if ($act or ($catid > 0 and $c['id'] == $nv_laws_listcat[$catid]['parentid'])) {
            foreach ($c['subcats'] as $catid_i) {
                $s_c = $nv_laws_listcat[$catid_i];
                $s_act = ($s_c['alias'] == $catalias) ? 1 : 0;
                $s_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $s_c['alias'];
                $sub_menu[] = array(
                    $s_c['title'],
                    $s_link,
                    $s_act
                );
            }
        }

        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $c['alias'];
        $nv_vertical_menu[] = array(
            $c['title'],
            $link,
            $act,
            'submenu' => $sub_menu
        );
    }

    $rss[] = array(
        'title' => $module_info['custom_title'] . ' - ' . $c['title'],
        'src' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=rss/" . $c['alias']
    );
}
