<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('upload_manager');
$contents = '';

$path = (defined('NV_IS_SPADMIN')) ? '' : NV_UPLOADS_DIR;
$path = nv_check_path_upload($nv_Request->get_string('path', 'get', $path));
$currentpath = nv_check_path_upload($nv_Request->get_string('currentpath', 'get', $path));
$type = $nv_Request->get_string('type', 'get');
$popup = $nv_Request->get_int('popup', 'get', 0);
$area = htmlspecialchars(trim($nv_Request->get_string('area', 'get')), ENT_QUOTES);
$alt = htmlspecialchars(trim($nv_Request->get_string('alt', 'get')), ENT_QUOTES);
$currentfile = $nv_Request->get_string('currentfile', 'get', '');

$selectfile = '';
if (!empty($currentfile)) {
    $selectfile = nv_string_to_filename(pathinfo($currentfile, PATHINFO_BASENAME));
    $currentfilepath = nv_check_path_upload(pathinfo($currentfile, PATHINFO_DIRNAME));
    if (!empty($currentfilepath) and !empty($selectfile)) {
        $currentpath = $currentfilepath;
    }
}
if (empty($currentpath)) {
    $currentpath = NV_UPLOADS_DIR;
}

if ($type != 'image' and $type != 'flash') {
    $type = 'file';
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('POPUP', $popup);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);

$tpl->assign('CURRENTPATH', $currentpath);
$tpl->assign('PATH', $path);
$tpl->assign('TYPE', $type);
$tpl->assign('AREA', $area);
$tpl->assign('ALT', $alt);
$tpl->assign('FUNNUM', $nv_Request->get_int('CKEditorFuncNum', 'get', 0));

$tpl->assign('SELFILE', $selectfile);

$sfile = ($type == 'file') ? ' selected="selected"' : '';
$simage = ($type == 'image') ? ' selected="selected"' : '';
$sflash = ($type == 'flash') ? ' selected="selected"' : '';

$tpl->assign('SFLASH', $sflash);
$tpl->assign('SIMAGE', $simage);
$tpl->assign('SFILE', $sfile);

// Find logo config
$upload_logo = $upload_logo_config = '';
if (!empty($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
    $upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
    $logo_size = getimagesize(NV_ROOTDIR . '/' . $global_config['upload_logo']);
    $upload_logo_config = $logo_size[0] . '|' . $logo_size[1] . '|' . $global_config['autologosize1'] . '|' . $global_config['autologosize2'] . '|' . $global_config['autologosize3'];
}

$tpl->assign('UPLOAD_LOGO', $upload_logo);
$tpl->assign('UPLOAD_LOGO_CONFIG', $upload_logo_config);

// Xuất javascript các cấu hình
if ($nv_Request->isset_request('js', 'get')) {
    $sys_max_size = min($global_config['nv_max_size'], nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));

    $tpl->assign('NV_MY_DOMAIN', NV_MY_DOMAIN);
    $tpl->assign('NV_MAX_SIZE', nv_convertfromBytes($sys_max_size));
    $tpl->assign('NV_MAX_SIZE_BYTES', $sys_max_size);
    $tpl->assign('NV_MAX_WIDTH', NV_MAX_WIDTH);
    $tpl->assign('NV_MAX_HEIGHT', NV_MAX_HEIGHT);
    $tpl->assign('NV_MIN_WIDTH', 10);
    $tpl->assign('NV_MIN_HEIGHT', 10);
    $tpl->assign('NV_CHUNK_SIZE', $global_config['upload_chunk_size']);
    $tpl->assign('NV_AUTO_RESIZE', $global_config['nv_auto_resize']);
    $tpl->assign('UPLOAD_ALT_REQUIRE', !empty($global_config['upload_alt_require']) ? 'true' : 'false');
    $tpl->assign('UPLOAD_AUTO_ALT', !empty($global_config['upload_auto_alt']) ? 'true' : 'false');

    // Tìm ra file js
    $js_file = NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/js/nv.upload.js';
    if (!file_exists($js_file)) {
        $js_file = NV_ROOTDIR . '/themes/admin_default/js/nv.upload.js';
    }

    $contents = $tpl->fetch('lang.tpl');
    $contents .= "\n" . file_get_contents($js_file);

    unset($sys_info['server_headers']['content-type'], $sys_info['server_headers']['content-length']);

    $headers['Content-Type'] = 'application/javascript';
    $headers['Last-Modified'] = gmdate('D, d M Y H:i:s', filemtime($js_file)) . " GMT";
    $headers['Cache-Control'] = 'max-age=2592000, public'; // Cache js 1 tháng kể từ lần sửa cuối của file
    $headers['Pragma'] = 'cache';

    include NV_ROOTDIR . '/includes/header.php';
    echo $contents;
    include NV_ROOTDIR . '/includes/footer.php';
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);


if ($popup) {
    // Check upload allow file types
    if ($type == 'image' and in_array('images', $admin_info['allow_files_type'])) {
        $allow_files_type = ['images'];
    } elseif ($type == 'flash' and in_array('flash', $admin_info['allow_files_type'])) {
        $allow_files_type = ['flash'];
    } else {
        $allow_files_type = $admin_info['allow_files_type'];
    }


    if (!empty($global_config['upload_alt_require'])) {
        $xtpl->parse('main.alt_remote');
    }

    if (!empty($global_config['upload_auto_alt'])) {
        $xtpl->parse('main.auto_alt');
    }

    if (!$global_config['nv_auto_resize']) {
        $xtpl->parse('main.no_auto_resize');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
}

$contents = $tpl->fetch($op . '.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo $popup ? $contents : nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
