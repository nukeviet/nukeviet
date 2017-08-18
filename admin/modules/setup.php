<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$array_site_cat_module = array();
if ($global_config['idsite']) {
    $_module = $db->query('SELECT module FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($_module)) {
        $array_site_cat_module = explode(',', $_module);
    }
}

$contents = '';

// Thiet lap module moi
$setmodule = $nv_Request->get_title('setmodule', 'get', '', 1);

if (!empty($setmodule) and preg_match($global_config['check_module'], $setmodule)) {
    if ($nv_Request->get_title('checkss', 'get') == md5('setmodule' . $setmodule . NV_CHECK_SESSION)) {
        $sample = $nv_Request->get_int('sample', 'get', 0);

        $sth = $db->prepare('SELECT basename, table_prefix FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=:title AND type=\'module\'');
        $sth->bindParam(':title', $setmodule, PDO::PARAM_STR);
        $sth->execute();
        $modrow = $sth->fetch();

        if (!empty($modrow)) {
            if (!empty($array_site_cat_module) and !in_array($modrow['basename'], $array_site_cat_module)) {
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            }

            $weight = $db->query('SELECT MAX(weight) FROM ' . NV_MODULES_TABLE)->fetchColumn();
            $weight = intval($weight) + 1;

            $module_version = array();
            $custom_title = preg_replace('/(\W+)/i', ' ', $setmodule);
            $version_file = NV_ROOTDIR . '/modules/' . $modrow['basename'] . '/version.php';

            if (file_exists($version_file)) {
                include $version_file;
                if ($setmodule == $modrow['basename'] and isset($module_version['name'])) {
                    $custom_title = $module_version['name'];
                }
            }

            $admin_file = (file_exists(NV_ROOTDIR . '/modules/' . $modrow['basename'] . '/admin.functions.php') and file_exists(NV_ROOTDIR . '/modules/' . $modrow['basename'] . '/admin/main.php')) ? 1 : 0;
            $main_file = (file_exists(NV_ROOTDIR . '/modules/' . $modrow['basename'] . '/functions.php') and file_exists(NV_ROOTDIR . '/modules/' . $modrow['basename'] . '/funcs/main.php')) ? 1 : 0;

            try {
                $sth = $db->prepare("INSERT INTO " . NV_MODULES_TABLE . "
					(title, module_file, module_data, module_upload, module_theme, custom_title, admin_title, set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view, weight, act, admins, rss, sitemap) VALUES
					(:title, :module_file, :module_data, :module_upload, :module_theme, :custom_title, '', " . NV_CURRENTTIME . ", " . $main_file . ", " . $admin_file . ", '', '', '', '', '6', " . $weight . ", 0, '', 1, 1)
				");
                $sth->bindParam(':title', $setmodule, PDO::PARAM_STR);
                $sth->bindParam(':module_file', $modrow['basename'], PDO::PARAM_STR);
                $sth->bindParam(':module_data', $modrow['table_prefix'], PDO::PARAM_STR);
                $sth->bindParam(':module_upload', $setmodule, PDO::PARAM_STR);
                $sth->bindParam(':module_theme', $modrow['basename'], PDO::PARAM_STR);
                $sth->bindParam(':custom_title', $custom_title, PDO::PARAM_STR);
                $sth->execute();
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }

            $nv_Cache->delMod('modules');
            $return = nv_setup_data_module(NV_LANG_DATA, $setmodule, $sample);
            if ($return == 'OK_' . $setmodule) {
                nv_setup_block_module($setmodule);

                $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act=1 WHERE title=:title');
                $sth->bindParam(':title', $setmodule, PDO::PARAM_STR);
                $sth->execute();

                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['modules'] . ' ' . $setmodule, '', $admin_info['userid']);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=edit&mod=' . $setmodule);
            }
        }
    }

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$page_title = $lang_module['modules'];
$modules_exit = array_flip(nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']));
$modules_data = array();

$is_delCache = false;

$sql_data = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type=\'module\' ORDER BY addtime ASC';
$result = $db->query($sql_data);

while ($row = $result->fetch()) {
    if (array_key_exists($row['basename'], $modules_exit)) {
        $modules_data[$row['title']] = $row;
    } else {
        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title= :title AND type=\'module\'');
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title=:title');
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->execute();

        $is_delCache = true;
    }
}

if ($is_delCache) {
    $nv_Cache->delMod('modules');
}

$check_addnews_modules = false;
$arr_module_news = array_diff_key($modules_exit, $modules_data);

foreach ($arr_module_news as $module_name_i => $arr) {
    $check_file_main = NV_ROOTDIR . '/modules/' . $module_name_i . '/funcs/main.php';
    $check_file_functions = NV_ROOTDIR . '/modules/' . $module_name_i . '/functions.php';

    $check_admin_main = NV_ROOTDIR . '/modules/' . $module_name_i . '/admin/main.php';
    $check_admin_functions = NV_ROOTDIR . '/modules/' . $module_name_i . '/admin.functions.php';

    if ((file_exists($check_file_main) and filesize($check_file_main) != 0 and file_exists($check_file_functions) and filesize($check_file_functions) != 0) or (file_exists($check_admin_main) and filesize($check_admin_main) != 0 and file_exists($check_admin_functions) and filesize($check_admin_functions) != 0)) {
        $check_addnews_modules = true;

        $module_version = array();
        $version_file = NV_ROOTDIR . '/modules/' . $module_name_i . '/version.php';

        if (file_exists($version_file)) {
            require_once $version_file;
        }

        if (empty($module_version)) {
            $timestamp = NV_CURRENTTIME - date('Z', NV_CURRENTTIME);
            $module_version = array(
                'name' => $module_name_i,
                'modfuncs' => 'main',
                'is_sysmod' => 0,
                'virtual' => 0,
                'version' => $global_config['version'],
                'date' => date('D, j M Y H:i:s', $timestamp) . ' GMT',
                'author' => '',
                'note' => ''
            );
        }

        $date_ver = intval(strtotime($module_version['date']));

        if ($date_ver == 0) {
            $date_ver = NV_CURRENTTIME;
        }

        $version = $module_version['version'] . ' ' . $date_ver;
        $note = $module_version['note'];
        $author = $module_version['author'];
        $module_data = preg_replace('/(\W+)/i', '_', $module_name_i);

        // Chỉ cho phép ảo hóa module khi virtual = 1, Khi virtual = 2, chỉ đổi được tên các func
        $module_version['virtual'] = ($module_version['virtual'] == 1) ? 1 : 0;

        $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_setup_extensions (type, title, is_sys, is_virtual, basename, table_prefix, version, addtime, author, note) VALUES (
			\'module\', :title, ' . intval($module_version['is_sysmod']) . ', ' . intval($module_version['virtual']) . ', :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note)');

        $sth->bindParam(':title', $module_name_i, PDO::PARAM_STR);
        $sth->bindParam(':basename', $module_name_i, PDO::PARAM_STR);
        $sth->bindParam(':table_prefix', $module_data, PDO::PARAM_STR);
        $sth->bindParam(':version', $version, PDO::PARAM_STR);
        $sth->bindParam(':author', $author, PDO::PARAM_STR);
        $sth->bindParam(':note', $note, PDO::PARAM_STR);
        $sth->execute();
    }
}

if ($check_addnews_modules) {
    $result = $db->query($sql_data);
    while ($row = $result->fetch()) {
        $modules_data[$row['title']] = $row;
    }
}

// Lay danh sach cac module co trong ngon ngu
$modules_for_title = array();
$modules_for_file = array();

$result = $db->query('SELECT * FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
while ($row = $result->fetch()) {
    $modules_for_title[$row['title']] = $row;
    if ($row['title'] == $row['module_file']) {
        $modules_for_file[$row['module_file']] = $row;
    }
}

// Kiem tra module moi
$news_modules_for_file = array_diff_key($modules_data, $modules_for_file);

$array_modules = $array_virtual_modules = $mod_virtual = array();

foreach ($modules_data as $row) {
    if (in_array($row['basename'], $modules_exit)) {
        if (!empty($array_site_cat_module) and !in_array($row['basename'], $array_site_cat_module)) {
            continue;
        }

        if (array_key_exists($row['title'], $news_modules_for_file)) {
            $mod = array();
            $mod['title'] = $row['title'];
            $mod['is_sys'] = $row['is_sys'];
            $mod['virtual'] = $row['is_virtual'];
            $mod['module_file'] = $row['basename'];
            $mod['version'] = preg_replace_callback('/^([0-9a-zA-Z]+\.[0-9a-zA-Z]+\.[0-9a-zA-Z]+)\s+(\d+)$/', 'nv_parse_vers', $row['version']);
            $mod['addtime'] = nv_date('H:i:s d/m/Y', $row['addtime']);
            $mod['author'] = $row['author'];
            $mod['note'] = $row['note'];
            $mod['url_setup'] = array_key_exists($row['title'], $modules_for_title) ? '' : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;setmodule=' . $row['title'] . '&amp;checkss=' . md5('setmodule' . $row['title'] . NV_CHECK_SESSION);

            if ($mod['module_file'] == $mod['title']) {
                $array_modules[] = $mod;

                if ($row['is_virtual']) {
                    $mod_virtual[] = $mod['title'];
                }
            } else {
                $array_virtual_modules[] = $mod;
            }
        }
    }
}

$array_head = array(
    'caption' => $lang_module['module_sys'],
    'head' => array(
        $lang_module['weight'],
        $lang_module['module_name'],
        $lang_module['version'],
        $lang_module['settime'],
        $lang_module['author'],
        ''
    )
);

$array_virtual_head = array(
    'caption' => $lang_module['vmodule'],
    'head' => array(
        $lang_module['weight'],
        $lang_module['module_name'],
        $lang_module['vmodule_file'],
        $lang_module['settime'],
        $lang_module['vmodule_note'],
        ''
    )
);

$contents .= call_user_func('setup_modules', $array_head, $array_modules, $array_virtual_head, $array_virtual_modules);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';