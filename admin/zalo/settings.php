<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if ($nv_Request->get_string('func', 'get', '') == 'access_token_create') {
    $zalo = new NukeViet\Zalo\Zalo($global_config);
    $result = $zalo->oa_accesstoken_create(NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings&func=accesstoken');

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    if (empty($result)) {
        $err = $zalo->getError();
        $xtpl->assign('ERROR', isset($lang_module[$err]) ? $lang_module[$err] : $err);
        $xtpl->parse('isError');
        $contents = $xtpl->text('isError');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } elseif (isset($result['access_token'])) {
        accessTokenUpdate($result);
        $xtpl->assign('RESULT', $result);
        $xtpl->parse('isSuccess');
        $contents = $xtpl->text('isSuccess');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        $nv_Request->set_Session('oa_code_verifier', $result['code_verifier']);
        nv_redirect_location($result['permission_url']);
    }
}

if ($nv_Request->get_string('func', 'get', '') == 'accesstoken' and $nv_Request->isset_request('code, oa_id', 'get')) {
    $authorization_code = $nv_Request->get_string('code', 'get', '');
    $oa_id = $nv_Request->get_string('oa_id', 'get', '');
    $code_verifier = $nv_Request->get_string('oa_code_verifier', 'session', '');
    $nv_Request->unset_request('oa_code_verifier', 'session');

    $zalo = new NukeViet\Zalo\Zalo($global_config);
    $result = $zalo->oa_accesstoken_new($authorization_code, $oa_id, $code_verifier);

    $xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    if (empty($result)) {
        $err = $zalo->getError();
        $xtpl->assign('ERROR', isset($lang_module[$err]) ? $lang_module[$err] : $err);
        $xtpl->parse('isError');
        $contents = $xtpl->text('isError');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    } else {
        accessTokenUpdate($result);
        $xtpl->assign('RESULT', $result);
        $xtpl->parse('isSuccess');
        $contents = $xtpl->text('isSuccess');
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents, false);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_info['userid']);
$errormess = '';

if ($checkss == $nv_Request->get_string('checkss', 'post') and $nv_Request->get_string('func', 'post', '') == 'settings') {
    $array_config_site = [];
    $array_config_site['zaloOfficialAccountID'] = $nv_Request->get_title('zaloOfficialAccountID', 'post', '');
    $array_config_site['zaloOfficialAccountID'] = preg_replace('/[^0-9]/', '', $array_config_site['zaloOfficialAccountID']);
    $array_config_site['zaloAppID'] = $nv_Request->get_title('zaloAppID', 'post', '');
    $array_config_site['zaloAppID'] = preg_replace('/[^0-9]/', '', $array_config_site['zaloAppID']);
    $array_config_site['zaloAppSecretKey'] = $nv_Request->get_title('zaloAppSecretKey', 'post', '');
    $array_config_site['zaloAppSecretKey'] = preg_replace('/[^a-zA-Z0-9\_\-]/', '', $array_config_site['zaloAppSecretKey']);

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
    foreach ($array_config_site as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delAll(false);
    if (empty($errormess)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$global_config['checkss'] = $checkss;

$xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);

$lang_module['oa_create_note'] = sprintf($lang_module['oa_create_note'], 'https://oa.zalo.me/manage/oa?option=create', 'https://oa.zalo.me/manage/oa');
$lang_module['app_note'] = sprintf($lang_module['app_note'], 'https://developers.zalo.me/createapp', 'https://developers.zalo.me/apps', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php', NV_MY_DOMAIN, NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php', NV_MY_DOMAIN . NV_BASE_ADMINURL . 'index.php');
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $global_config);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

if ($errormess != '') {
    $xtpl->assign('ERROR', $errormess);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['settings'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
