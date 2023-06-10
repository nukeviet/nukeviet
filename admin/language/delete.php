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

if ($nv_Request->get_string('checksess', 'get') == md5('deleteallfile' . NV_CHECK_SESSION)) {
    $dirlang = $nv_Request->get_title('dirlang', 'get', '');
    $type = $nv_Request->get_string('type', 'get', 'db');
    $type != 'files' && $type = 'db';

    if (preg_match('/^([a-z]{2})$/', $dirlang)) {
        if ($type == 'files') {
            $dirs = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
            $array_filename = [];

            foreach ($dirs as $module) {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php')) {
                    $arrcrt = nv_deletefile(NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php');

                    if ($arrcrt[0] == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('nv_lang_delete_error')
                        ]);
                    }

                    $array_filename[] = $arrcrt[1];
                }
                if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/language/' . $dirlang . '.js')) {
                    $arrcrt = nv_deletefile(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/language/' . $dirlang . '.js', true);

                    if ($arrcrt[0] == 0) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => $nv_Lang->getModule('nv_lang_delete_error')
                        ]);
                    }

                    $array_filename[] = $arrcrt[1];
                }
            }

            if (is_dir(NV_ROOTDIR . '/includes/language/' . $dirlang)) {
                $arrcrt = nv_deletefile(NV_ROOTDIR . '/includes/language/' . $dirlang, true);

                if ($arrcrt[0] == 0) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('nv_lang_delete_error')
                    ]);
                }

                $array_filename[] = $arrcrt[1];
            }
        }

        $array_lang_exit = [];
        $columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
        foreach ($columns_array as $row) {
            if (substr($row['field'], 0, 7) == 'author_') {
                $array_lang_exit[] = trim(substr($row['field'], 7, 2));
            }
        }

        if (!empty($array_lang_exit) and in_array($dirlang, $array_lang_exit, true)) {
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
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_lang_delete'), $dirlang . ' --> ' . $language_array[$dirlang]['name'], $admin_info['userid']);

        if ($type == 'files') {
            nv_jsonOutput([
                'status' => 'OK',
                'mess' => $nv_Lang->getModule('nv_lang_delete_files_ok') . ":\n\n" . implode("\n", $array_filename)
            ]);
        }

        nv_htmlOutput($nv_Lang->getModule('nv_lang_deleteok'));
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
