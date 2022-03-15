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

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (!defined('NV_IS_BANNER_CLIENT')) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

if ($nv_Request->isset_request('confirm', 'post')) {
    $post = [
        'title' => $nv_Request->get_title('title', 'post', '', 1),
        'blockid' => $nv_Request->get_title('block', 'post', '', 1),
        'description' => $nv_Request->get_title('description', 'post', '', 1),
        'url' => $nv_Request->get_title('url', 'post', '', 0)
    ];

    // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
    if ($module_captcha == 'recaptcha') {
        $post['captcha'] = $nv_Request->get_title('g-recaptcha-response', 'post', '');
    }
    // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
    elseif ($module_captcha == 'captcha') {
        $post['captcha'] = $nv_Request->get_title('captcha', 'post', '');
    }

    if (!empty($post['url']) and !nv_is_url($post['url'], true)) {
        $post['url'] = '';
    }

    // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
    if (isset($post['captcha']) and !nv_capcha_txt($post['captcha'], $module_captcha)) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => ($module_captcha == 'recaptcha') ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect']
        ]);
    }

    if (empty($global_array_uplans[$post['blockid']]['uploadtype']) and !empty($global_array_uplans[$post['blockid']]['require_image'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => '',
            'mess' => $lang_module['upload_blocked']
        ]);
    }

    if (empty($post['blockid'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'block',
            'mess' => $lang_module['plan_not_selected']
        ]);
    }

    if (!isset($global_array_uplans[$post['blockid']])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'block',
            'mess' => $lang_module['plan_wrong_selected']
        ]);
    }

    if (empty($post['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'title',
            'mess' => $lang_module['title_empty']
        ]);
    }

    if (empty($global_array_uplans[$post['blockid']]['require_image']) and empty($post['url'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'url',
            'mess' => $lang_module['click_url_invalid']
        ]);
    }

    if (!empty($global_array_uplans[$post['blockid']]['require_image']) and !isset($_FILES['image'])) {
        nv_jsonOutput([
            'status' => 'error',
            'input' => 'image',
            'mess' => $lang_module['file_upload_empty']
        ]);
    }

    if (!empty($global_array_uplans[$post['blockid']]['require_image'])) {
        $upload = new NukeViet\Files\Upload(explode(',', $global_array_uplans[$post['blockid']]['uploadtype']), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
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

    if (!empty($global_array_uplans[$post['blockid']]['require_image'])) {
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
        $post['description'] = '';
    }

    // Xác định thời gian bắt đầu, kết thúc
    $begintime = NV_CURRENTTIME;
    $endtime = 0;
    if ($global_array_uplans[$post['blockid']]['exp_time'] > 0) {
        $endtime = $begintime + $global_array_uplans[$post['blockid']]['exp_time'];
    }

    $sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_rows (
            title, pid, clid, file_name, file_ext, file_mime, width, height, file_alt, imageforswf, click_url, bannerhtml, add_time, publ_time, exp_time, hits_total, act, weight
        ) VALUES (
            :title, ' . $post['blockid'] . ', ' . $user_info['userid'] . ', :file_name, :file_ext, :file_mime, ' . $width . ', ' . $height . ", :description, '',
            :url, '', " . NV_CURRENTTIME . ', ' . $begintime . ', ' . $endtime . ', 0, 4, 0
        )';

    $data_insert = [];
    $data_insert['title'] = $post['title'];
    $data_insert['file_name'] = $file_name;
    $data_insert['file_ext'] = $file_ext;
    $data_insert['file_mime'] = $file_mime;
    $data_insert['description'] = $post['description'];
    $data_insert['url'] = $post['url'];

    $id = $db->insert_id($sql, 'id', $data_insert);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $lang_module['addads_success'],
        'redirect' => nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true)
    ]);
}

$contents = nv_banner_theme_addads($global_array_uplans, $page_url);
$page_title = $lang_module['client_addads'];
$canonicalUrl = getCanonicalUrl($page_url, true, true);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
