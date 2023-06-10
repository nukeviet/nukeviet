<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$dirlang = $nv_Request->get_title('dirlang', 'get', '');

if ($nv_Request->get_string('checksess', 'get') == md5('deleteallfile' . NV_CHECK_SESSION)) {
    $array_lang_exit = [];
    $columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
    foreach ($columns_array as $row) {
        if (substr($row['field'], 0, 7) == 'author_') {
            $array_lang_exit[] = trim(substr($row['field'], 7, 2));
        }
    }

    if (!empty($dirlang) and !empty($array_lang_exit) and in_array($dirlang, $array_lang_exit, true)) {
        try {
            $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . '_file DROP author_' . $dirlang);
            $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . ' DROP lang_' . $dirlang);
            $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . ' DROP update_' . $dirlang);
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }

        if (sizeof($array_lang_exit) == 1) {
            $db->query('TRUNCATE ' . NV_LANGUAGE_GLOBALTABLE . '_file');
            $db->query('TRUNCATE ' . NV_LANGUAGE_GLOBALTABLE);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_lang_delete'), $dirlang . ' --> ' . $language_array[$dirlang]['name'], $admin_info['userid']);

        nv_htmlOutput($nv_Lang->getModule('nv_lang_deleteok'));
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
