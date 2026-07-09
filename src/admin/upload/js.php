<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

[$template, $dir] = get_module_tpl_dir('upload.js', true);
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir($dir);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TEMPLATE', $template);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('DEBUG', (defined('NV_DEBUG') and NV_DEBUG == 1) ? 'true' : 'false');

// Các biến này tạo nhằm mục đích dễ nhìn trong JS, không bị cảnh báo syntax trong js
$tpl->assign('UPLOAD_ALT_REQUIRE', !empty($global_config['upload_alt_require']) ? 'true' : 'false');
$tpl->assign('UPLOAD_AUTO_ALT', !empty($global_config['upload_auto_alt']) ? 'true' : 'false');
$tpl->assign('COMPRESS_IMAGE_ACTIVE', (class_exists('Tinify\Tinify') and !empty($global_config['tinify_active']) and !empty($global_config['tinify_api'])) ? 'true' : 'false');

$upload_logo = '';
$logo_width = 0;
$logo_height = 0;
$logo_size_s = 0;
$logo_size_m = 0;
$logo_size_l = 0;

if (!empty($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
    $logo_size = nv_ImageInfo(NV_ROOTDIR . '/' . $global_config['upload_logo']);
    if ($logo_size) {
        $upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
        $logo_width = intval($logo_size['width']);
        $logo_height = intval($logo_size['height']);
        $logo_size_s = floatval($global_config['autologosize1']);
        $logo_size_m = floatval($global_config['autologosize2']);
        $logo_size_l = floatval($global_config['autologosize3']);
    }
}
$tpl->assign('UPLOAD_LOGO', $upload_logo);
$tpl->assign('LOGO_WIDTH', $logo_width);
$tpl->assign('LOGO_HEIGHT', $logo_height);
$tpl->assign('LOGO_SIZE_S', $logo_size_s);
$tpl->assign('LOGO_SIZE_M', $logo_size_m);
$tpl->assign('LOGO_SIZE_L', $logo_size_l);

$tpl->assign('MAX_WIDTH', NV_MAX_WIDTH);
$tpl->assign('MAX_HEIGHT', NV_MAX_HEIGHT);

$sys_max_size = $sys_max_size_local = min($global_config['nv_max_size'], nv_converttoBytes(ini_get('upload_max_filesize')), nv_converttoBytes(ini_get('post_max_size')));
if ($global_config['nv_overflow_size'] > $sys_max_size and $global_config['upload_chunk_size'] > 0) {
    $sys_max_size_local = $global_config['nv_overflow_size'];
}
$tpl->assign('NV_MAX_SIZE_BYTES', $sys_max_size_local);
$tpl->assign('NV_CHUNK_SIZE', $global_config['upload_chunk_size']);

$tpl->assign('HTML_POPUP', escapeForJs($tpl->fetch('upload_modal.tpl')));
$tpl->assign('HTML_CONTENT', escapeForJs($tpl->fetch('upload_ctn.tpl')));
$tpl->assign('HTML_DIALOG', escapeForJs($tpl->fetch('upload_dialog.tpl')));
$tpl->assign('HTML_QUEUE_ITEM', escapeForJs($tpl->fetch('upload_queue_item.tpl')));

// Load thêm tệp callback của trình soạn thảo nếu có
$extra_js = '';
if (!empty($admin_info['editor']) and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $admin_info['editor'] . '/nv.callback.js')) {
    $extra_js = NV_STATIC_URL . NV_EDITORSDIR . '/' . $admin_info['editor'] . '/nv.callback.js';
}
$tpl->assign('EXTRA_JS', $extra_js);

$contents = $tpl->fetch('upload.js');

unset($sys_info['server_headers']['content-type'], $sys_info['server_headers']['content-length']);
unset($sys_info['server_headers']['last-modified'], $sys_info['server_headers']['cache-control'], $sys_info['server_headers']['pragma']);

$headers['Content-Type'] = 'application/javascript; charset=UTF-8';
$headers['Last-Modified'] = gmdate('D, d M Y H:i:s', $global_config['timestamp']) . ' GMT';
$headers['Cache-Control'] = 'max-age=2592000, public'; // Cache js 1 tháng kể từ lần sửa cuối của file
$headers['Pragma'] = 'cache';

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';

/**
 * Chuỗi sẽ ở trong cặp ``
 *
 * @param string $html
 * @return string|array
 */
function escapeForJs($html)
{
    $html = str_replace('\\', '\\\\', $html);
    $html = str_replace('`', '\`', $html);
    return $html;
}
