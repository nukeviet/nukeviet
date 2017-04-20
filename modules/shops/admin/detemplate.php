<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$contents = "NO_" . $id;

if ($id > 0) {
    $template = $db->query('SELECT '. NV_LANG_DATA .'_title, alias FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE id = ' . $id)->fetch();

    $result = $db->query('SELECT fid, listtemplate, field FROM ' . $db_config['prefix'] . '_' . $module_data . '_field');
    if ($result->rowCount()) {
        while (list($fid, $listtemplate, $field) = $result->fetch(3)) {
            $listtemplate = explode('|', $listtemplate);
            if (in_array($id, $listtemplate)) {
                if (count($listtemplate) > 1) {
                    $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);

                    unset($listtemplate[array_search($id, $listtemplate)]);

                    $listtemplate = implode('|', $listtemplate);
                    $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_field SET listtemplate = " . $listtemplate . " WHERE fid = " . $fid);

                    $file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/files_tpl/cat_form_' . preg_replace('/[\-]/', '_', $template['alias']) . '.tpl';
                    @nv_deletefile($file);

                    $contents = "OK_" . $id;
                } else {
                    $contents = "NO_" . sprintf($lang_module['template_error_only'], $field, $template['title']);
                }
            } else {
                $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);
                $contents = "OK_" . $id;
            }
        }
    } else {
        $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);

        $file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/files_tpl/cat_form_' . preg_replace('/[\-]/', '_', $template['alias']) . '.tpl';
        @nv_deletefile($file);

        $contents = "OK_" . $id;
    }
} else {
    $listall = $nv_Request->get_string('listall', 'post,get');
    $array_id = explode(',', $listall);
    $array_id = array_map("intval", $array_id);

    foreach ($array_id as $id) {
        if ($id > 0) {
            $template = $db->query('SELECT ' . NV_LANG_DATA . '_title, alias FROM ' . $db_config['prefix'] . '_' . $module_data . '_template WHERE id = ' . $id)->fetch();

            $result = $db->query('SELECT fid, listtemplate, field FROM ' . $db_config['prefix'] . '_' . $module_data . '_field');
            if ($result->rowCount()) {
                while (list($fid, $listtemplate, $field) = $result->fetch(3)) {
                    $listtemplate = explode('|', $listtemplate);
                    if (in_array($id, $listtemplate)) {
                        if (count($listtemplate) > 1) {
                            $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);
                            $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_info_" . $id);

                            unset($listtemplate[array_search($id, $listtemplate)]);
                            $listtemplate = implode('|', $listtemplate);
                            $db->query("UPDATE " . $db_config['prefix'] . "_" . $module_data . "_field SET listtemplate = " . $listtemplate . " WHERE fid = " . $fid);

                            $file = NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_name . '/files_tpl/cat_form_' . preg_replace('/[\-]/', '_', $template['alias']) . '.tpl';
                            @nv_deletefile($file);
                        } else {
                            $contents = "NO_" . sprintf($lang_module['template_error_only'], $field, $template['title']);
                        }
                    } else {
                        $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);
                        $contents = "OK_" . $id;
                    }
                }
            } else {
                $db->exec("DELETE FROM " . $db_config['prefix'] . "_" . $module_data . "_template WHERE id=" . $id);
                $contents = "OK_" . $id;
            }
        }
    }
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';