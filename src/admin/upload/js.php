<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$template = get_tpl_dir([$global_config['module_theme'], $global_config['admin_theme']], 'admin_default', '/modules/' . $module_file . '/upload.js');
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TEMPLATE', $template);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('DEBUG', (defined('NV_DEBUG') and NV_DEBUG == 1) ? 'true' : 'false');

// Các biến này tạo nhằm mục đích dễ nhìn trong JS, không bị cảnh báo syntax trong js
$tpl->assign('UPLOAD_ALT_REQUIRE', !empty($global_config['upload_alt_require']) ? 'true' : 'false');
$tpl->assign('UPLOAD_AUTO_ALT', !empty($global_config['upload_auto_alt']) ? 'true' : 'false');

$upload_logo = $upload_logo_config = '';
if (!empty($global_config['upload_logo']) and file_exists(NV_ROOTDIR . '/' . $global_config['upload_logo'])) {
    $upload_logo = NV_BASE_SITEURL . $global_config['upload_logo'];
    $logo_size = getimagesize(NV_ROOTDIR . '/' . $global_config['upload_logo']);
    $upload_logo_config = $logo_size[0] . '|' . $logo_size[1] . '|' . $global_config['autologosize1'] . '|' . $global_config['autologosize2'] . '|' . $global_config['autologosize3'];
}
$tpl->assign('UPLOAD_LOGO', $upload_logo);

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
