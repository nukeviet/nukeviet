<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

$page_title = $module_info['site_title'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (!defined('NV_IS_BANNER_CLIENT')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$array = [];

if ($nv_Request->isset_request('confirm', 'post')) {
    $array['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $array['blockid'] = $nv_Request->get_title('block', 'post', '', 1);
    $array['description'] = $nv_Request->get_title('description', 'post', '', 1);
    $array['url'] = $nv_Request->get_title('url', 'post', '', 0);

    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    if ($module_captcha == 'recaptcha') {
        $array['captcha'] = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    }
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    elseif ($module_captcha == 'captcha') {
        $array['captcha'] = $nv_Request->get_title('captcha', 'post', '');
    }

    if ($array['url'] == 'http://') {
        $array['url'] = '';
    }

    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
    if (isset($array['captcha']) and !nv_capcha_txt($array['captcha'], $module_captcha)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'captcha',
            'mess' => ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']
        ]);
    }

    if (empty($global_array_uplans[$array['blockid']]['uploadtype']) and !empty($global_array_uplans[$array['blockid']]['require_image'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $lang_module['upload_blocked']
        ]);
    }

    if (empty($array['blockid'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'block',
            'mess' => $lang_module['plan_not_selected']
        ]);
    }

    if (!isset($global_array_uplans[$array['blockid']])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'block',
            'mess' => $lang_module['plan_wrong_selected']
        ]);
    }

    if (empty($array['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'title',
            'mess' => $lang_module['title_empty']
        ]);
    }

    if ((empty($global_array_uplans[$array['blockid']]['require_image']) and empty($array['url'])) or (!empty($array['url']) and !nv_is_url($array['url']))) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'url',
            'mess' => $lang_module['click_url_invalid']
        ]);
    }

    if (!empty($global_array_uplans[$array['blockid']]['require_image']) and !isset($_FILES['image'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'image',
            'mess' => $lang_module['file_upload_empty']
        ]);
    }

    if (!empty($global_array_uplans[$array['blockid']]['require_image'])) {
        $upload = new NukeViet\Files\Upload(explode(',', $global_array_uplans[$array['blockid']]['uploadtype']), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $upload_info = $upload->save_file($_FILES['image'], NV_UPLOADS_REAL_DIR . '/' . NV_BANNER_DIR, false);

        if (is_file($_FILES['image']['tmp_name'])) {
            @unlink($_FILES['image']['tmp_name']);
        }

        if (!empty($upload_info['error'])) {
            nv_jsonOutput([
                'status' => 'error',
                'input' => 'image',
                'mess' => $upload_info['error']
            ]);
        }
    }

    if (!empty($global_array_uplans[$array['blockid']]['require_image'])) {
        $file_name = $upload_info['basename'];
        $file_ext = $upload_info['ext'];
        $file_mime = $upload_info['mime'];
        $width = $upload_info['img_info'][0];
        $height = $upload_info['img_info'][1];
    } else {
        $file_name = 'no_image';
        $file_ext = 'no_image';
        $file_mime = 'no_image';
        $width = $height = 0;
        $array['description'] = '';
    }

    // Xác định thời gian bắt đầu, kết thúc
    $begintime = NV_CURRENTTIME;
    $endtime = 0;
    if ($global_array_uplans[$array['blockid']]['exp_time'] > 0) {
        $endtime = $begintime + $global_array_uplans[$array['blockid']]['exp_time'];
    }

    $sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_rows (
            title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight
        ) VALUES (
            :title, ' . $array['blockid'] . ', ' . $user_info['userid'] . ', :file_name, :file_ext, :file_mime, ' . $width . ', ' . $height . ", :description, '',
            :url, '', " . NV_CURRENTTIME . ', ' . $begintime . ', ' . $endtime . ', 0, 4, 0
        )';

    $data_insert = [];
    $data_insert['title'] = $array['title'];
    $data_insert['file_name'] = $file_name;
    $data_insert['file_ext'] = $file_ext;
    $data_insert['file_mime'] = $file_mime;
    $data_insert['description'] = $array['description'];
    $data_insert['url'] = $array['url'];

    $id = $db->insert_id($sql, 'id', $data_insert);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $lang_module['addads_success'],
        'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true)
    ]);
} else {
    $array['blockid'] = 0;
    $array['title'] = '';
    $array['description'] = '';
    $array['url'] = '';
}

$xtpl = new XTemplate('addads.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$xtpl->assign('NV_BASE_URLSITE', NV_BASE_SITEURL);
$xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
$xtpl->assign('FORM_ACTION', $page_url);

$xtpl->assign('MANAGEMENT', $manament);
$xtpl->parse('main.management');

foreach ($global_array_uplans as $row) {
    $row['title'] .= ' (' . (empty($row['blang']) ? $lang_module['addads_block_lang_all'] : $lang_array[$row['blang']]) . ')';
    $row['typeimage'] = $row['require_image'] ? 'true' : 'false';
    $row['uploadtype'] = str_replace(',', ', ', $row['uploadtype']);
    $row['selected'] = $array['blockid'] == $row['id'] ? ' selected="selected"' : '';
    $xtpl->assign('blockitem', $row);
    $xtpl->parse('main.blockitem');
}

$xtpl->assign('DATA', $array);

// Nếu dùng reCaptcha v3
if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
    $xtpl->parse('main.recaptcha3');
}
// Nếu dùng reCaptcha v2
elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
    $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
    $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
    $xtpl->parse('main.recaptcha');
} elseif ($module_captcha == 'captcha') {
    $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
    $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
    $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
    $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
    $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
    $xtpl->parse('main.captcha');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
