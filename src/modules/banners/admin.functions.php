<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'main',
    'plans-list',
    'change_act_plan',
    'plan-content',
    'del_plan',
    'info-plan',
    'banner-content',
    'change_act_banner',
    'info-banner',
    'show-stat',
    'show-list-stat',
    'del_banner'
];
define('NV_IS_FILE_ADMIN', true);

$targets = [
    '_blank' => $nv_Lang->getModule('target_blank'),
    '_top' => $nv_Lang->getModule('target_top'),
    '_self' => $nv_Lang->getModule('target_self'),
    '_parent' => $nv_Lang->getModule('target_parent')
];

// Document
$array_url_instruction['plans-list'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#khối_quảng_cao';
$array_url_instruction['plan-content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#them_khối_quảng_cao';
$array_url_instruction['banner-content'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#them_quảng_cao';

$array_uploadtype = [
    'images'
];
$array_exp_time = [
    [
        0,
        $nv_Lang->getModule('plan_exp_time_nolimit')
    ],
    [
        86400,
        $nv_Lang->getModule('plan_exp_time_d', 1)
    ],
    [
        604800,
        $nv_Lang->getModule('plan_exp_time_w', 1)
    ],
    [
        1209600,
        $nv_Lang->getModule('plan_exp_time_w', 2)
    ],
    [
        1814400,
        $nv_Lang->getModule('plan_exp_time_w', 3)
    ],
    [
        2592000,
        $nv_Lang->getModule('plan_exp_time_m', 1, 30)
    ],
    [
        5184000,
        $nv_Lang->getModule('plan_exp_time_m', 2, 60)
    ],
    [
        7776000,
        $nv_Lang->getModule('plan_exp_time_m', 3, 90)
    ],
    [
        10368000,
        $nv_Lang->getModule('plan_exp_time_m', 4, 120)
    ],
    [
        12960000,
        $nv_Lang->getModule('plan_exp_time_m', 5, 150)
    ],
    [
        15552000,
        $nv_Lang->getModule('plan_exp_time_m', 6, 180)
    ],
    [
        18144000,
        $nv_Lang->getModule('plan_exp_time_m', 7, 210)
    ],
    [
        20736000,
        $nv_Lang->getModule('plan_exp_time_m', 8, 240)
    ],
    [
        23328000,
        $nv_Lang->getModule('plan_exp_time_m', 9, 270)
    ],
    [
        25920000,
        $nv_Lang->getModule('plan_exp_time_m', 10, 300)
    ],
    [
        28512000,
        $nv_Lang->getModule('plan_exp_time_m', 11, 330)
    ],
    [
        31536000,
        $nv_Lang->getModule('plan_exp_time_y', 1, 365)
    ],
    [
        -1,
        $nv_Lang->getModule('plan_exp_time_custom')
    ]
];

/**
 * nv_CreateXML_bannerPlan()
 */
function nv_CreateXML_bannerPlan()
{
    global $db, $global_config;
    $pattern = ($global_config['idsite']) ? '/^site\_' . $global_config['idsite'] . '\_bpl\_([0-9]+)\.xml$/' : '/^bpl\_([0-9]+)\.xml$/';
    $files = nv_scandir(NV_ROOTDIR . '/' . NV_DATADIR, $pattern);
    if (!empty($files)) {
        foreach ($files as $file) {
            nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file);
        }
    }
    $sql = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE act = 1';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $id = (int) ($row['id']);
        if ($global_config['idsite']) {
            $xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_bpl_' . $id . '.xml';
        } else {
            $xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/bpl_' . $id . '.xml';
        }
        $plan = [];
        $plan['id'] = $id;
        $plan['lang'] = $row['blang'];
        $plan['title'] = $row['title'];
        if (!empty($row['description'])) {
            $plan['description'] = $row['description'];
        }
        $plan['form'] = $row['form'];
        $plan['width'] = $row['width'];
        $plan['height'] = $row['height'];
        $query2 = 'SELECT * FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid = ' . $id . ' AND (exp_time > ' . NV_CURRENTTIME . ' OR exp_time = 0) AND (act = 1 OR act = 0)';
        if ($row['form'] == 'sequential') {
            $query2 .= ' ORDER BY weight ASC';
        }
        $plan['banners'] = [];
        $result2 = $db->query($query2);
        while ($row2 = $result2->fetch()) {
            $plan['banners'][] = [
                'id' => $row2['id'],
                'title' => $row2['title'],
                'clid' => $row2['clid'],
                'file_name' => $row2['file_name'],
                'imageforswf' => $row2['imageforswf'],
                'file_ext' => $row2['file_ext'],
                'file_mime' => $row2['file_mime'],
                'file_width' => $row2['width'],
                'file_height' => $row2['height'],
                'file_alt' => $row2['file_alt'],
                'file_click' => $row2['click_url'],
                'target' => $row2['target'],
                'bannerhtml' => $row2['bannerhtml'],
                'publ_time' => $row2['publ_time'],
                'exp_time' => $row2['exp_time']
            ];
        }
        if (count($plan['banners'])) {
            $array2XML = new NukeViet\Xml\Array2XML();
            $array2XML->saveXML($plan, 'plan', $xmlfile, $encoding = $global_config['site_charset']);
        }
    }
}

/**
 * nv_fix_banner_weight()
 *
 * @param int $pid
 */
function nv_fix_banner_weight($pid)
{
    global $db;
    [$pid, $form] = $db->query('SELECT id, form FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . (int) $pid)->fetch(3);
    if ($pid > 0 and $form == 'sequential') {
        $query_weight = 'SELECT id FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE pid=' . $pid . ' AND act IN(0,1,3) ORDER BY weight ASC, id DESC';
        $result = $db->query($query_weight);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        // Các banner hết hạn và banner chờ duyệt có weight = 0
        $sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET weight=0 WHERE act IN(2,4) AND pid=' . $pid;
        $db->query($sql);
    } elseif ($pid > 0 and $form == 'random') {
        $sql = 'UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET weight=0 WHERE pid=' . $pid;
        $db->query($sql);
    }
}

/**
 * nv_clean60_bannerlink()
 *
 * @param string $string
 * @param int    $num
 * @return string
 */
function nv_clean60_bannerlink($string, $num = 60)
{
    $org_len = nv_strlen($string);
    $new_string = nv_clean60($string, $num);

    return preg_replace('/\.\.\.\.\.\.$/', '...', ($new_string . ($org_len > nv_strlen($new_string) ? '...' : '')));
}

// Tìm kiếm thành viên AJAX
if ($nv_Request->isset_request('ajaxqueryusername', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }
    $_csrf_key = $admin_info['admin_id'] . '_' . $module_name . '_ajaxqueryusername';
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $_csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $username = $nv_Request->get_title('ajaxqueryusername', 'post', '');
    $return = [];

    $default_photo = NV_STATIC_URL . 'themes/' . get_tpl_dir($global_config['site_theme'], 'default', '/images/users/no_avatar.png') . '/images/users/no_avatar.png';

    if (nv_strlen($username) >= 3) {
        if (preg_match('/^\=(.*)$/', $username, $m)) {
            $username = $m[1];
            $sql = 'SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active=1 AND username=' . $db->quote($username) . ' ORDER BY username ASC LIMIT 0,10';
        } else {
            $dbkey = $db->dblikeescape($username);
            $sql = 'SELECT username, first_name, last_name, photo FROM ' . NV_USERS_GLOBALTABLE . " WHERE active=1 AND (
                username LIKE '%" . $dbkey . "%' OR CONCAT(first_name,' ',last_name) LIKE '%" . $dbkey . "%'
            ) ORDER BY username ASC LIMIT 0,10";
        }
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            if (!empty($row['photo'])) {
                $row['photo'] = NV_BASE_SITEURL . $row['photo'];
            } else {
                $row['photo'] = $default_photo;
            }
            $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
            $return[] = $row;
        }
    }

    nv_jsonOutput($return);
}
