<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$act = $nv_Request->get_int('act', 'get', 0);
$clid = $nv_Request->get_int('clid', 'get', 0);
$pid = $nv_Request->get_int('pid', 'get');
$keyword = $nv_Request->get_title('q', 'get', '');

$sql = 'SELECT id, title, blang, form FROM ' . NV_BANNERS_GLOBALTABLE . '_plans ORDER BY blang, title ASC';
$result = $db->query($sql);

$plans = [];
$plans_form = [];

while ($row = $result->fetch()) {
    $plans[$row['id']] = $row['title'] . ' (' . (!empty($row['blang']) ? $language_array[$row['blang']]['name'] : $lang_module['blang_all']) . ')';
    $plans_form[$row['id']] = $row['form'];
}

$contents = [];
$contents['thead'] = [
    $lang_module['title'],
    $lang_module['in_plan'],
    $lang_module['of_user'],
    $lang_module['publ_date'],
    $lang_module['exp_date'],
    $lang_module['is_act'],
    $lang_global['actions']
];
$contents['view'] = $lang_global['detail'];
$contents['edit'] = $lang_global['edit'];
$contents['del'] = $lang_global['delete'];
$contents['rows'] = [];

$sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE ';
$where = [];
$aray_act = [
    0,
    1,
    2,
    3,
    4
];

if ($pid > 0 and isset($plans[$pid])) {
    $contents['thead'][1] = $lang_module['click_url'];
    if ($plans_form[$pid] == 'sequential' and in_array($act, [0, 1, 3], true) and empty($keyword)) {
        array_unshift($contents['thead'], $lang_module['weight']);
        define('NV_BANNER_WEIGHT', true);
    }
}

if (in_array($act, $aray_act, true)) {
    $where[] = 'act=' . $nv_Request->get_int('act', 'get');
    $contents['caption'] = $lang_module['banners_list' . $act];
} else {
    $contents['caption'] = $lang_module['banners_list'];
}

if ($clid > 0) {
    $user = $db->query('SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $clid)->fetch();
    if (!empty($user)) {
        $where[] = 'clid=' . $clid;
        $contents['caption'] .= ' ' . sprintf($lang_module['banners_list_cl'], $user['username']);
    }
} elseif ($pid > 0 and isset($plans[$pid])) {
    $where[] = 'pid=' . $pid;
    $contents['caption'] .= ' ' . sprintf($lang_module['banners_list_pl'], $plans[$pid]);
}
if (!empty($keyword)) {
    $keyword = $db->dblikeescape($keyword);
    $where[] = "(title LIKE '%" . $keyword . "%' OR file_alt LIKE '%" . $keyword . "%' OR click_url LIKE '%" . $keyword . "%' OR bannerhtml LIKE '%" . $keyword . "%')";
}
if (!empty($where)) {
    $sql .= implode(' AND ', $where);
}
if (defined('NV_BANNER_WEIGHT')) {
    $sql .= ' ORDER BY weight ASC';
    $id = $nv_Request->get_int('id', 'get', 0);
    $new_weight = $nv_Request->get_int('weight', 'get', 0);

    if ($id > 0 and $new_weight > 0) {
        $query_weight = 'SELECT id FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id!=' . $id . ' AND pid=' . $pid . ' AND act IN(0,1,3) ORDER BY weight ASC';
        $result = $db->query($query_weight);

        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $db->query('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id']);
        }

        $db->query('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET weight=' . $new_weight . ' WHERE id=' . $id);

        nv_CreateXML_bannerPlan();
    }
} else {
    $sql .= ' ORDER BY id DESC';
}

$rows = $db->query($sql)->fetchAll();
$array_userids = $array_users = [];

if (defined('NV_BANNER_WEIGHT')) {
    $num = $db->query('SELECT COUNT(*) FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE act IN(0,1,3) AND pid=' . $pid)->fetchColumn();
}

foreach ($rows as $row) {
    if ($row['exp_time'] != 0 and $row['exp_time'] <= NV_CURRENTTIME) {
        $db->exec('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET act=2 WHERE id=' . $row['id']);
        $row['act'] = 2;
    }

    $weight_banner = '';
    if (defined('NV_BANNER_WEIGHT')) {
        $weight_banner = '';
        $weight_banner .= '<select id="id_weight_' . $row['id'] . "\" onchange=\"nv_chang_weight_banners('" . $pid . "','" . $row['id'] . "');\">\n";

        for ($i = 1; $i <= $num; ++$i) {
            $weight_banner .= '<option value="' . $i . '"' . ($i == $row['weight'] ? ' selected="selected"' : '') . '>' . $i . "</option>\n";
        }

        $weight_banner .= '</select>';
    }

    $contents['rows'][$row['id']]['weight'] = $weight_banner;
    $contents['rows'][$row['id']]['title'] = $row['title'];
    if ($pid > 0) {
        $contents['rows'][$row['id']]['pid'] = [
            $row['click_url'],
            nv_clean60_bannerlink($row['click_url'], 50)
        ];
    } else {
        $contents['rows'][$row['id']]['pid'] = [
            NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_plan&amp;id=' . $row['pid'],
            $plans[$row['pid']]
        ];
    }

    $contents['rows'][$row['id']]['clid'] = $row['clid'];
    $contents['rows'][$row['id']]['publ_date'] = date('d/m/Y', $row['publ_time']);
    $contents['rows'][$row['id']]['exp_date'] = !empty($row['exp_time']) ? date('d/m/Y', $row['exp_time']) : $lang_module['unlimited'];
    $contents['rows'][$row['id']]['act'] = [
        'act_' . $row['id'],
        $row['act'],
        'nv_b_chang_act(' . $row['id'] . ",'act_" . $row['id'] . "');"
    ];
    $contents['rows'][$row['id']]['view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=info_banner&amp;id=' . $row['id'];
    $contents['rows'][$row['id']]['edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit_banner&amp;id=' . $row['id'];
    $contents['rows'][$row['id']]['del'] = 'nv_b_del(' . $row['id'] . ');';

    if (!empty($row['clid'])) {
        $array_userids[$row['clid']] = $row['clid'];
    }
}

// Xác định người đăng
if (!empty($array_userids)) {
    $sql = 'SELECT userid, username, md5username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN(' . implode(',', $array_userids) . ')';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $array_users[$row['userid']] = $row;
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo $rows ? nv_b_list_theme($contents, $array_users) : '';
include NV_ROOTDIR . '/includes/footer.php';
