<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$dirlang = $nv_Request->get_title('dirlang', 'get', '');

if ($nv_Request->get_string('checksess', 'get') == md5('deleteallfile' . NV_CHECK_SESSION)) {
    if (preg_match('/^([a-z]{2})$/', $dirlang)) {
        $dirs = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
        $err = 0;
        $array_filename = [];

        foreach ($dirs as $module) {
            if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php')) {
                $arrcrt = nv_deletefile(NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php');

                if ($arrcrt[0] == 0) {
                    $err = 1;
                }

                $array_filename[] = $arrcrt[1];
            }

            if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php')) {
                $arrcrt = nv_deletefile(NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php');

                if ($arrcrt[0] == 0) {
                    $err = 1;
                }

                $array_filename[] = $arrcrt[1];
            }
            if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/language/' . $dirlang . '.js')) {
                $arrcrt = nv_deletefile(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/language/' . $dirlang . '.js', true);

                if ($arrcrt[0] == 0) {
                    $err = 1;
                }

                $array_filename[] = $arrcrt[1];
            }

            $blocks = nv_scandir(NV_ROOTDIR . '/modules/' . $module . '/language/', '/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)\_' . $dirlang . '\.php$/');

            foreach ($blocks as $file_i) {
                $arrcrt = nv_deletefile(NV_ROOTDIR . '/modules/' . $module . '/language/' . $file_i);

                if ($arrcrt[0] == 0) {
                    $err = 1;
                }

                $array_filename[] = $arrcrt[1];
            }
        }

        if (is_dir(NV_ROOTDIR . '/includes/language/' . $dirlang)) {
            $arrcrt = nv_deletefile(NV_ROOTDIR . '/includes/language/' . $dirlang, true);

            if ($arrcrt[0] == 0) {
                $err = 1;
            }

            $array_filename[] = $arrcrt[1];
        }

        if ($err == 0) {
            $columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE);
            if (isset($columns_array['lang_' . $dirlang])) {
                try {
                    $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . '_file DROP author_' . $dirlang);
                    $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . ' DROP lang_' . $dirlang);
                    $db->query('ALTER TABLE ' . NV_LANGUAGE_GLOBALTABLE . ' DROP update_' . $dirlang);
                } catch (PDOException $e) {
                    trigger_error($e->getMessage());
                }
            }
            $contents = $lang_module['nv_lang_deleteok'];
        } else {
            $contents = $lang_module['nv_lang_delete_error'];
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['nv_lang_delete'], $dirlang . ' --> ' . $language_array[$dirlang]['name'], $admin_info['userid']);

        $xtpl = new XTemplate('delete.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);
        $xtpl->assign('GLANG', $lang_global);

        $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting');
        $xtpl->assign('INFO', $contents);

        if (!empty($array_filename)) {
            $i = 0;
            foreach ($array_filename as $name) {
                if (empty($name)) {
                    continue;
                }

                $xtpl->assign('NAME', $name);
                $xtpl->parse('main.info.loop');
            }

            $xtpl->parse('main.info');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');

        $page_title = $language_array[$dirlang]['name'] . ': ' . $lang_module['nv_admin_read'];

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
