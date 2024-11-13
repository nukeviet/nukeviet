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

$contents = $tpl->fetch('upload.js');

unset($sys_info['server_headers']['content-type'], $sys_info['server_headers']['content-length']);
unset($sys_info['server_headers']['last-modified'], $sys_info['server_headers']['cache-control'], $sys_info['server_headers']['pragma']);

$headers['Content-Type'] = 'application/javascript; charset=UTF-8';
$headers['Content-Length'] = strlen($contents);
$headers['Last-Modified'] = gmdate('D, d M Y H:i:s', $global_config['timestamp']) . ' GMT';
$headers['Cache-Control'] = 'max-age=2592000, public'; // Cache js 1 tháng kể từ lần sửa cuối của file
$headers['Pragma'] = 'cache';

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
