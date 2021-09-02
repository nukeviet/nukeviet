<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_DATABASE')) {
    exit('Stop!!!');
}

$log_dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/dump_backup';
if ($global_config['idsite']) {
    $log_dir .= '/' . $global_config['site_dir'];
}

$xtpl = new XTemplate('files.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$array_content = $array_time = [];
$files = scandir($log_dir);

foreach ($files as $file) {
    if (preg_match('/^([a-zA-Z0-9]+)\_([a-zA-Z0-9\-\_]+)\.(sql|sql\.gz)+$/', $file, $mc)) {
        $filesize = filesize($log_dir . '/' . $file);
        $filetime = (int) (filemtime($log_dir . '/' . $file));
        $array_time[] = $filetime;

        $array_content[$filetime] = [
            'file' => $file,
            'mc' => $mc,
            'filesize' => $filesize
        ];
    }
}
sort($array_time);

$a = 0;
$count = sizeof($array_time) - 1;
for ($index = $count; $index >= 0; --$index) {
    $filetime = $array_time[$index];
    $value = $array_content[$filetime];
    $file = $value['file'];
    $mc = $value['mc'];

    $link_getfile = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=getfile&amp;filename=' . $file . '&amp;checkss=' . md5($file . NV_CHECK_SESSION);
    $link_delete = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=delfile&amp;filename=' . $file . '&amp;checkss=' . md5($file . NV_CHECK_SESSION);

    $xtpl->assign('ROW', [
        'stt' => ++$a,
        'name' => $mc[2] . '.' . $mc[3],
        'filesize' => nv_convertfromBytes($value['filesize']),
        'filetime' => nv_date('l d/m/Y h:i:s A', $filetime),
        'link_getfile' => $link_getfile,
        'link_delete' => $link_delete
    ]);

    $xtpl->parse('main.loop');
}
$xtpl->assign('BACKUPNOW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=download&amp;checkss=' . NV_CHECK_SESSION);
$page_title = $lang_module['file_backup'];

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
