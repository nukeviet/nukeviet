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

$allow_func = [
    'main',
    'plans_list',
    'plist',
    'change_act_plan',
    'add_plan',
    'edit_plan',
    'del_plan',
    'info_plan',
    'banners_list',
    'add_banner',
    'edit_banner',
    'b_list',
    'change_act_banner',
    'info_banner',
    'show_stat',
    'show_list_stat',
    'del_banner'
];
define('NV_IS_FILE_ADMIN', true);

$targets = [
    '_blank' => $lang_module['target_blank'],
    '_top' => $lang_module['target_top'],
    '_self' => $lang_module['target_self'],
    '_parent' => $lang_module['target_parent']
];

// Document
$array_url_instruction['banners_list'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#quảng_cao';
$array_url_instruction['plans_list'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#khối_quảng_cao';
$array_url_instruction['add_plan'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#them_khối_quảng_cao';
$array_url_instruction['edit_plan'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#sửa_khối_quảng_cao';
$array_url_instruction['add_banner'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#them_quảng_cao';
$array_url_instruction['edit_banner'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:banners#sửa_quảng_cao';

$array_uploadtype = [
    'images'
];
$array_exp_time = [
    [
        0,
        $lang_module['plan_exp_time_nolimit']
    ],
    [
        86400,
        sprintf($lang_module['plan_exp_time_d'], 1)
    ],
    [
        604800,
        sprintf($lang_module['plan_exp_time_w'], 1)
    ],
    [
        1209600,
        sprintf($lang_module['plan_exp_time_w'], 2)
    ],
    [
        1814400,
        sprintf($lang_module['plan_exp_time_w'], 3)
    ],
    [
        2592000,
        sprintf($lang_module['plan_exp_time_m'], 1, 30)
    ],
    [
        5184000,
        sprintf($lang_module['plan_exp_time_m'], 2, 60)
    ],
    [
        7776000,
        sprintf($lang_module['plan_exp_time_m'], 3, 90)
    ],
    [
        10368000,
        sprintf($lang_module['plan_exp_time_m'], 4, 120)
    ],
    [
        12960000,
        sprintf($lang_module['plan_exp_time_m'], 5, 150)
    ],
    [
        15552000,
        sprintf($lang_module['plan_exp_time_m'], 6, 180)
    ],
    [
        18144000,
        sprintf($lang_module['plan_exp_time_m'], 7, 210)
    ],
    [
        20736000,
        sprintf($lang_module['plan_exp_time_m'], 8, 240)
    ],
    [
        23328000,
        sprintf($lang_module['plan_exp_time_m'], 9, 270)
    ],
    [
        25920000,
        sprintf($lang_module['plan_exp_time_m'], 10, 300)
    ],
    [
        28512000,
        sprintf($lang_module['plan_exp_time_m'], 11, 330)
    ],
    [
        31536000,
        sprintf($lang_module['plan_exp_time_y'], 1, 365)
    ],
    [
        -1,
        $lang_module['plan_exp_time_custom']
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
        if (sizeof($plan['banners'])) {
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
    list($pid, $form) = $db->query('SELECT id, form FROM ' . NV_BANNERS_GLOBALTABLE . '_plans WHERE id=' . (int) $pid)->fetch(3);
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
 * nv_add_plan_theme()
 *
 * @param array $contents
 * @param array $array_uploadtype
 * @param array $groups_list
 * @return string
 */
function nv_add_plan_theme($contents, $array_uploadtype, $groups_list)
{
    global $global_config, $module_file, $module_upload, $lang_module, $lang_global, $array_exp_time;

    $xtpl = new XTemplate('add_plan.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('CLASS', $contents['is_error'] ? ' class="error"' : '');

    foreach ($contents['blang'][3] as $key => $blang) {
        $xtpl->assign('BLANG', [
            'key' => $key,
            'title' => $blang['name'],
            'selected' => $key == $contents['blang'][4] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.blang');
    }

    foreach ($contents['form'][2] as $form) {
        $xtpl->assign('FORM', [
            'key' => $form,
            'title' => isset($lang_module['form_' . $form]) ? $lang_module['form_' . $form] : $form,
            'checked' => $form == $contents['form'][3] ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('main.form');
    }

    if ($contents['description'][5] and nv_function_exists('nv_aleditor')) {
        $description = nv_aleditor($contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
    } else {
        $description = '<textarea name="' . $contents['description'][1] . '" id="' . $contents['description'][1] . '" style="width:' . $contents['description'][3] . ';height:' . $contents['description'][4] . '">' . $contents['description'][2] . '</textarea>\n';
    }
    $xtpl->assign('DESCRIPTION', $description);

    for ($i = 1; $i >= 0; --$i) {
        $require_image = [
            'key' => $i,
            'title' => $lang_module['require_image' . $i],
            'checked' => $i == $contents['require_image'] ? ' checked="checked"' : ''
        ];
        $xtpl->assign('REQUIRE_IMAGE', $require_image);
        $xtpl->parse('main.require_image');
    }

    $contents['uploadtype'] = explode(',', $contents['uploadtype']);
    foreach ($array_uploadtype as $uploadtype) {
        $uploadtype = [
            'key' => $uploadtype,
            'title' => $uploadtype,
            'checked' => in_array($uploadtype, $contents['uploadtype'], true) ? ' checked="checked"' : ''
        ];
        $xtpl->assign('UPLOADTYPE', $uploadtype);
        $xtpl->parse('main.uploadtype');
    }

    $uploadgroup = array_map('intval', explode(',', $contents['uploadgroup']));
    foreach ($groups_list as $_group_id => $_title) {
        $xtpl->assign('UPLOADGROUP', [
            'key' => $_group_id,
            'checked' => in_array((int) $_group_id, $uploadgroup, true) ? ' checked="checked"' : '',
            'title' => $_title
        ]);
        $xtpl->parse('main.uploadgroup');
    }

    foreach ($array_exp_time as $exp_time) {
        $exp_time = [
            'key' => $exp_time[0],
            'title' => $exp_time[1],
            'selected' => $contents['exp_time'] == $exp_time[0] ? ' selected="selected"' : ''
        ];
        $xtpl->assign('EXP_TIME', $exp_time);
        $xtpl->parse('main.exp_time');
    }
    $xtpl->assign('DISPLAY_CUSTOM_EXPTIME', $contents['exp_time'] == -1 ? '' : ' style="display:none;"');

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_edit_plan_theme()
 *
 * @param array $contents
 * @param array $array_uploadtype
 * @param array $groups_list
 * @return string
 */
function nv_edit_plan_theme($contents, $array_uploadtype, $groups_list)
{
    global $global_config, $module_file, $module_upload, $lang_module, $lang_global, $array_exp_time;

    $xtpl = new XTemplate('edit_plan.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('CLASS', $contents['is_error'] ? ' class="error"' : '');

    foreach ($contents['blang'][3] as $key => $blang) {
        $xtpl->assign('BLANG', [
            'key' => $key,
            'title' => $blang['name'],
            'selected' => $key == $contents['blang'][4] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.blang');
    }

    foreach ($contents['form'][2] as $form) {
        $xtpl->assign('FORM', [
            'key' => $form,
            'title' => isset($lang_module['form_' . $form]) ? $lang_module['form_' . $form] : $form,
            'checked' => $form == $contents['form'][3] ? ' checked="checked"' : ''
        ]);
        $xtpl->parse('main.form');
    }

    if ($contents['description'][5] and nv_function_exists('nv_aleditor')) {
        $description = nv_aleditor($contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2], '', NV_UPLOADS_DIR . '/' . $module_upload, NV_UPLOADS_DIR . '/' . $module_upload . '/files');
    } else {
        $description = '<textarea name="' . $contents['description'][1] . '" id="' . $contents['description'][1] . '" style="width:' . $contents['description'][3] . ';height:' . $contents['description'][4] . '">' . $contents['description'][2] . '</textarea>\n';
    }
    $xtpl->assign('DESCRIPTION', $description);

    for ($i = 1; $i >= 0; --$i) {
        $require_image = [
            'key' => $i,
            'title' => $lang_module['require_image' . $i],
            'checked' => $i == $contents['require_image'] ? ' checked="checked"' : ''
        ];
        $xtpl->assign('REQUIRE_IMAGE', $require_image);
        $xtpl->parse('main.require_image');
    }

    $contents['uploadtype'] = explode(',', $contents['uploadtype']);
    foreach ($array_uploadtype as $uploadtype) {
        $uploadtype = [
            'key' => $uploadtype,
            'title' => $uploadtype,
            'checked' => in_array($uploadtype, $contents['uploadtype'], true) ? ' checked="checked"' : ''
        ];
        $xtpl->assign('UPLOADTYPE', $uploadtype);
        $xtpl->parse('main.uploadtype');
    }

    $uploadgroup = array_map('intval', explode(',', $contents['uploadgroup']));
    foreach ($groups_list as $_group_id => $_title) {
        $xtpl->assign('UPLOADGROUP', [
            'key' => $_group_id,
            'checked' => in_array((int) $_group_id, $uploadgroup, true) ? ' checked="checked"' : '',
            'title' => $_title
        ]);
        $xtpl->parse('main.uploadgroup');
    }

    foreach ($array_exp_time as $exp_time) {
        $exp_time = [
            'key' => $exp_time[0],
            'title' => $exp_time[1],
            'selected' => $contents['exp_time'] == $exp_time[0] ? ' selected="selected"' : ''
        ];
        $xtpl->assign('EXP_TIME', $exp_time);
        $xtpl->parse('main.exp_time');
    }
    $xtpl->assign('DISPLAY_CUSTOM_EXPTIME', $contents['exp_time'] == -1 ? '' : ' style="display:none;"');

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_plans_list_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_plans_list_theme($contents)
{
    global $global_config, $module_file;
    $xtpl = new XTemplate('plans_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_plist_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_plist_theme($contents)
{
    global $global_config, $module_file;
    $xtpl = new XTemplate('plist.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    foreach ($contents['thead'] as $key => $thead) {
        $xtpl->assign('THEAD', $thead);
        $xtpl->parse('main.thead');
    }
    $a = 0;
    if (!empty($contents['rows'])) {
        foreach ($contents['rows'] as $pl_id => $values) {
            $values['checked'] = $values['act'][1] ? ' checked="checked"' : '';
            $xtpl->assign('ROW', $values);
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_add_banner_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_add_banner_theme($contents)
{
    global $global_config, $module_file, $lang_module;

    $xtpl = new XTemplate('add_banner.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    if (!empty($contents['upload_blocked'])) {
        $xtpl->parse('upload_blocked');

        return $xtpl->text('upload_blocked');
    }

    $xtpl->assign('CLASS', $contents['is_error'] ? ' class="error"' : '');

    foreach ($contents['plan'][2] as $pid => $ptitle) {
        $xtpl->assign('PLAN', [
            'key' => $pid,
            'title' => $ptitle,
            'require_image' => $contents['plan'][5][$pid] == 1 ? 'true' : 'false',
            'exp_time' => $contents['plan'][6][$pid] > 0 ? 'true' : 'false',
            'selected' => $pid == $contents['plan'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.plan');
    }

    foreach ($contents['target'][2] as $target => $ptitle) {
        $xtpl->assign('TARGET', [
            'key' => $target,
            'title' => $ptitle,
            'selected' => $target == $contents['target'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.target');
    }

    for ($i = 0; $i <= 23; ++$i) {
        $xtpl->assign('HOUR', [
            'key' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'pub_selected' => $i == $contents['publ_date'][3] ? ' selected="selected"' : '',
            'exp_selected' => $i == $contents['exp_date'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.h_pub');
        $xtpl->parse('main.h_exp');
    }

    for ($i = 0; $i <= 59; ++$i) {
        $xtpl->assign('MIN', [
            'key' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'pub_selected' => $i == $contents['publ_date'][4] ? ' selected="selected"' : '',
            'exp_selected' => $i == $contents['exp_date'][4] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.m_pub');
        $xtpl->parse('main.m_exp');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_edit_banner_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_edit_banner_theme($contents)
{
    global $global_config, $module_file, $lang_module, $lang_global;

    $xtpl = new XTemplate('edit_banner.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

    if (!empty($contents['upload_blocked'])) {
        $xtpl->parse('upload_blocked');

        return $xtpl->text('upload_blocked');
    }

    $xtpl->assign('CLASS', $contents['is_error'] ? ' class="error"' : '');

    foreach ($contents['plan'][2] as $pid => $ptitle) {
        $xtpl->assign('PLAN', [
            'key' => $pid,
            'title' => $ptitle,
            'require_image' => $contents['plan'][4][$pid] == 1 ? 'true' : 'false',
            'exp_time' => $contents['plan'][5][$pid] > 0 ? 'true' : 'false',
            'selected' => $pid == $contents['plan'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.plan');
    }

    foreach ($contents['target'][2] as $target => $ptitle) {
        $xtpl->assign('TARGET', [
            'key' => $target,
            'title' => $ptitle,
            'selected' => $target == $contents['target'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.target');
    }

    if (!empty($contents['file_name'][1])) {
        $xtpl->parse('main.img_info');
        $xtpl->assign('SHOW_BANNER', ' class="hidden"');
    } else {
        $xtpl->assign('SHOW_BANNER', '');
    }

    // Nút xem ảnh trên mobile
    if (!empty($contents['imageforswf'][0])) {
        $xtpl->parse('main.imageforswf');
        $xtpl->assign('SHOW_IMAGEFORSWF', ' class="hidden"');
    } else {
        $xtpl->assign('SHOW_IMAGEFORSWF', '');
    }

    for ($i = 0; $i <= 23; ++$i) {
        $xtpl->assign('HOUR', [
            'key' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'pub_selected' => $i == $contents['publ_date'][3] ? ' selected="selected"' : '',
            'exp_selected' => $i == $contents['exp_date'][3] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.h_pub');
        $xtpl->parse('main.h_exp');
    }

    for ($i = 0; $i <= 59; ++$i) {
        $xtpl->assign('MIN', [
            'key' => $i,
            'title' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'pub_selected' => $i == $contents['publ_date'][4] ? ' selected="selected"' : '',
            'exp_selected' => $i == $contents['exp_date'][4] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.m_pub');
        $xtpl->parse('main.m_exp');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_banners_list_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_banners_list_theme($contents)
{
    global $global_config, $module_file;
    $xtpl = new XTemplate('banners_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_b_list_theme()
 *
 * @param array $contents
 * @param array $array_users
 * @return string
 */
function nv_b_list_theme($contents, $array_users = [])
{
    global $global_config, $module_file, $lang_module, $module_name, $global_config, $lang_global;

    $xtpl = new XTemplate('b_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('MODULE_NAME', $module_name);

    if (defined('NV_BANNER_WEIGHT')) {
        $xtpl->parse('main.nv_banner_weight');
    }

    if (!empty($contents['searchform'])) {
        $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php');
        $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
        $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
        $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
        $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);

        foreach ($contents['plans'] as $plan) {
            $plan['selected'] = $plan['id'] == $contents['pid'] ? ' selected="selected"' : '';
            $xtpl->assign('PLAN', $plan);
            $xtpl->parse('main.searchform.plan');
        }

        $xtpl->parse('main.searchform');
    }

    foreach ($contents['thead'] as $key => $thead) {
        $xtpl->assign('THEAD', $thead);
        $xtpl->parse('main.thead');
    }

    $is_allowed_viewuser = nv_user_in_groups($global_config['whoviewuser']);
    $a = 0;

    if (!empty($contents['rows'])) {
        foreach ($contents['rows'] as $b_id => $values) {
            $values['delfile'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=del_banner&id=' . $b_id;
            $values['checked'] = $values['act'][1] == '1' ? ' checked="checked"' : '';
            $xtpl->assign('ROW', $values);

            if (defined('NV_BANNER_WEIGHT')) {
                $xtpl->parse('main.loop.nv_banner_weight');
            }

            if (!empty($values['clid']) and isset($array_users[$values['clid']])) {
                $user = $array_users[$values['clid']];
                if ($is_allowed_viewuser) {
                    $user['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=memberlist/' . change_alias($user['username']) . '-' . $user['md5username'];
                } else {
                    $user['link'] = 'javascript:void(0);';
                }
                $xtpl->assign('USER', $user);
                $xtpl->parse('main.loop.user');
            }

            $xtpl->parse('main.loop');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_info_b_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_info_b_theme($contents)
{
    global $global_config, $module_file, $lang_module, $module_name;
    $xtpl = new XTemplate('info_b.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    if (isset($contents['act'])) {
        $xtpl->parse('main.act');
    }
    $a = 0;
    if ($contents['rows'][5][1] == '') {
        $contents['rows'][4][1] = '';
    }
    foreach ($contents['rows'] as $row) {
        $xtpl->assign('ROW1', $row);
        $xtpl->parse('main.loop1');
    }
    foreach ($contents['stat'][3] as $k => $v) {
        $xtpl->assign('K', $k);
        $xtpl->assign('V', $v);
        $xtpl->parse('main.stat1');
    }
    foreach ($contents['stat'][5] as $k => $v) {
        $xtpl->assign('K', $k);
        $xtpl->assign('V', $v);
        $xtpl->parse('main.stat2');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_show_stat_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_show_stat_theme($contents)
{
    global $global_config, $module_file, $lang_module, $module_name;
    $xtpl = new XTemplate('show_stat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    if (!empty($contents[2])) {
        $a = 0;
        foreach ($contents[2] as $key => $value) {
            $xtpl->assign('KEY', $key);
            $xtpl->assign('ROW', $value);
            if (!preg_match('/^[0-9]+$/', $key)) {
                $xtpl->parse('main.loop.t1');
            } else {
                $xtpl->parse('main.loop.t2');
            }
            if (!empty($value[1])) {
                $xtpl->assign('WIDTH', $value[1] * 3);
                $xtpl->parse('main.loop.t3');
            }
            $xtpl->parse('main.loop');
        }
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * nv_show_list_stat_theme()
 *
 * @param array $contents
 * @return string
 */
function nv_show_list_stat_theme($contents)
{
    global $global_config, $module_file, $lang_module, $module_name;
    $xtpl = new XTemplate('show_list_stat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CONTENTS', $contents);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    foreach ($contents['thead'] as $key => $thead) {
        $xtpl->assign('THEAD', $thead);
        $xtpl->parse('main.thead');
    }
    $a = 0;
    foreach ($contents['rows'] as $row) {
        $xtpl->assign('ROW', $row);
        foreach ($row as $r) {
            $xtpl->assign('R', $r);
            $xtpl->parse('main.loop.r');
        }
        $xtpl->parse('main.loop');
    }
    if (!empty($contents['generate_page'])) {
        $xtpl->parse('main.generate_page');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
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
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if ($checkss != NV_CHECK_SESSION or !defined('NV_IS_AJAX')) {
        exit('Wrong URL');
    }
    $username = $nv_Request->get_title('ajaxqueryusername', 'post', '');
    $return = [];

    if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/images/users/no_avatar.png')) {
        $default_photo = NV_STATIC_URL . 'themes/' . $global_config['site_theme'] . '/images/users/no_avatar.png';
    } else {
        $default_photo = NV_STATIC_URL . 'themes/default/images/users/no_avatar.png';
    }

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
