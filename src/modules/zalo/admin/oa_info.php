<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('oa_info');

if (!$myZalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

// Lay thong tin QUOTA tin nhan chu dong
if ($nv_Request->isset_request('get_proactive_messages_quota', 'post')) {
    get_accesstoken($accesstoken, true);
    $result = $myZalo->getquota($accesstoken);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    nv_jsonOutput(['status' => 'success', 'mess' => ''] + $result['data']);
}

// Xoa du lieu cu
if ($nv_Request->isset_request('oa_clear', 'post')) {
    oa_truncate();
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oa_info');
}

// Lay thong tin token gui ve tu zalo
if ($nv_Request->isset_request('code, oa_id', 'get')) {
    $code_verifier = $nv_Request->get_string('oa_code_verifier', 'session', '');
    $nv_Request->unset_request('oa_code_verifier', 'session');

    $result = $myZalo->accesstokenGet($code_verifier, 'user');
    if (empty($result)) {
        $contents = zaloGetError();
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        accessTokenUpdate($result);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oa_info');
    }
}

// Lấy thông tin OA từ CSDL
$oa_info = get_oa_info();

// Nếu empty $oa_info hoặc không có $oa_info['oa_id'] => lấy thông tin về
if (empty($oa_info) or empty($oa_info['oa_id']) or $nv_Request->isset_request('oa_info_update', 'post')) {
    $get_accesstoken_info = $myZalo->oa_accesstoken_info($client_info['selfurl']);
    if ($get_accesstoken_info['result'] == 'ok') {
        $accesstoken = $get_accesstoken_info['access_token'];
    } elseif ($get_accesstoken_info['result'] == 'update') {
        accessTokenUpdate($get_accesstoken_info);
        $accesstoken = $get_accesstoken_info['access_token'];
    } elseif ($get_accesstoken_info['result'] == 'new') {
        $nv_Request->set_Session('oa_code_verifier', $get_accesstoken_info['code_verifier']);
        nv_redirect_location($get_accesstoken_info['permission_url']);
    } else {
        $contents = $get_accesstoken_info['mess'];
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $result = $myZalo->get_oa_info($accesstoken);
    if (empty($result)) {
        $contents = zaloGetError();
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $oa_info = $result['data'];
    $oa_info = $oa_info;
    if (empty($oa_info['qrcode'])) {
        $qrcode = oa_qrcode_create();
        if (!empty($qrcode)) {
            $oa_info['qrcode'] = $qrcode;
        }
    }

    $oa_info['updatetime'] = NV_CURRENTTIME;

    OAInfoUpdate($oa_info);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oa_info');
}

$xtpl = new XTemplate('oa_info.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=oa_info');

if ($oa_info['oa_id'] != $global_config['zaloOfficialAccountID']) {
    $xtpl->parse('oa_clear');
    $contents = $xtpl->text('oa_clear');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$keys = ['oa_id', 'name', 'description', 'is_verified', 'oa_type', 'cate_name', 'num_follower', 'avatar', 'cover', 'qrcode', 'package_name', 'package_valid_through_date', 'linked_zca'];
$oa_info = array_replace(array_flip($keys), $oa_info);
foreach ($oa_info as $key => $val) {
    if ($key == 'is_verified' or !empty($val)) {
        $key == 'is_verified' && $val = $nv_Lang->getModule('verify_status_' . $val);
        $key == 'updatetime' && $val = nv_datetime_format($val, 1);
        $key == 'oa_type' && $val = $nv_Lang->getModule('oa_type_' . $val);
        $key == 'qrcode' && $val = NV_MY_DOMAIN . NV_BASE_SITEURL . $val;
        $xtpl->assign('OA', [
            'key' => !empty($nv_Lang->getModule($key)) ? $nv_Lang->getModule($key) : $key,
            'val' => $val
        ]);
        if ($key == 'qrcode' and !empty($val)) {
            $xtpl->parse('main.loop.qrcode');
        } elseif ($key == 'cover' and !empty($val)) {
            $xtpl->parse('main.loop.cover');
        } elseif ($key == 'avatar' and !empty($val)) {
            $xtpl->parse('main.loop.avatar');
        } else {
            $xtpl->parse('main.loop.normal');
        }
        $xtpl->parse('main.loop');
    }
}

$xtpl->assign('ZALO_HOMEPAGE', 'https://zalo.me/' . $global_config['zaloOfficialAccountID']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
