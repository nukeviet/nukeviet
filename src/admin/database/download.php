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

$checkss = $nv_Request->get_title('checkss', 'post,get');

if ($checkss == NV_CHECK_SESSION) {
    $tables = $nv_Request->get_array('tables', 'post', []);
    $type = $nv_Request->get_title('type', 'post', '');
    $ext = $nv_Request->get_title('ext', 'post', $global_config['dump_backup_ext']);

    if (empty($tables)) {
        $tables = [];
    } elseif (!is_array($tables)) {
        $tables = [
            $tables
        ];
    }

    $tab_list = [];

    $result = $db->query("SHOW TABLES LIKE '" . $db_config['prefix'] . "_%'");
    while ($item = $result->fetch(3)) {
        $tab_list[] = $item[0];
    }
    $result->closeCursor();

    $contents = [];
    $contents['tables'] = (empty($tables)) ? $tab_list : array_values(array_intersect($tab_list, $tables));
    $contents['type'] = ($type != 'str') ? 'all' : 'str';
    $contents['savetype'] = ($ext != 'sql') ? 'gz' : 'sql';
    $contents['filename'] = tempnam(NV_ROOTDIR . '/' . NV_TEMP_DIR, NV_TEMPNAM_PREFIX);

    include NV_ROOTDIR . '/includes/core/dump.php';

    $result = nv_dump_save($contents);
    if (!empty($result)) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['download'], 'File name: ' . basename($contents['filename']), $admin_info['userid']);

        $content['mime'] = ($contents['savetype'] == 'gz') ? 'application/x-gzip' : 'text/x-sql';
        $contents['fname'] = $db->dbname . '.sql';

        if ($contents['savetype'] == 'gz') {
            $contents['fname'] .= '.gz';
        }

        //Download file
        $download = new NukeViet\Files\Download($result[0], NV_ROOTDIR . '/' . NV_TEMP_DIR, basename($contents['fname']));
        $download->download_file();
        exit();
    }
}
exit();
