<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_LANG')) {
    die('Stop!!!');
}

$dirlang = $nv_Request->get_title('dirlang', 'get', '');

if ($nv_Request->get_string('checksess', 'get') == md5('deleteallfile' . NV_CHECK_SESSION)) {
    if (preg_match('/^([a-z]{2})$/', $dirlang)) {
        $dirs = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
        $err = 0;
        $array_filename = array();

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
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_lang_delete'), $dirlang . ' --> ' . $language_array[$dirlang]['name'], $admin_info['userid']);

        $tpl = new \NukeViet\Template\Smarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $tpl->assign('LANG', $nv_Lang);
        $tpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setting');
        $tpl->assign('IS_ERROR', $err);
        $tpl->assign('ARRAY_FILENAME', $array_filename);

        $contents = $tpl->fetch('delete.tpl');

        $page_title = $language_array[$dirlang]['name'] . ': ' . $nv_Lang->getModule('nv_admin_read');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
