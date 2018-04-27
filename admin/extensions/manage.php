<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-1-2010 22:5
 */

if (! defined('NV_IS_FILE_EXTENSIONS')) {
    die('Stop!!!');
}

$page_title = $lang_module['manage'];

$theme_config = array(
    'sys_icon' => 'fa-cubes',
    'admin_icon' => 'fa-cube',
);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('THEME_CONFIG', $theme_config);

$request = array(
    'type' => $nv_Request->get_title('type', 'get', ''),
    'title' => $nv_Request->get_title('title', 'get', ''),
    'checksess' => $nv_Request->get_title('checksess', 'get', '')
);

// Cac module trong admin
$array_module_admin = nv_scandir(NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module']);

// Cac theme trong admin
$array_theme_admin = nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']);

// Package extensions (Odd feature: Package module, theme)
if (md5('package_' . $request['type'] . '_' . $request['title'] . '_' . NV_CHECK_SESSION) == $request['checksess']) {
    // Kiem tra ung dung ton tai
    if (($request['type'] == 'module' and in_array($request['title'], $array_module_admin)) or ($request['type'] == 'theme' and in_array($request['title'], $array_theme_admin))) {
        $row = array( 0 => array(
            'id' => 0,
            'type' => $request['type'],
            'basename' => $request['title'],
            'author' => 'VINADES <contact@vinades.vn>',
            'version' => $global_config['version'] . ' ' . NV_CURRENTTIME,
            'is_sys' => 1,
            'virtual' => 0,
            'note' => '',
        ) );
    } else {
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();
        $row = $sth->fetchAll();
    }

    if (sizeof($row) == 1) {
        $row = $row[0];

        if (preg_match("/^(.*?)[\s](\(|\<)(.*?)(\)|\>)$/iu", $row['author'], $m)) {
            $row['author'] = trim($m[1]);
            $row['email'] = trim($m[3]);
        } else {
            $row['email'] = 'N/A';
        }
        $row['author'] = str_replace('=', '', $row['author']);

        if (preg_match("/^([0-9\.]+) ([0-9]+)$/i", $row['version'], $m)) {
            $row['version'] = trim($m[1]);
        } else {
            $row['version'] = 'N/A';
        }

        $tempfolder = NV_ROOTDIR . '/' . NV_TEMP_DIR;
        $files_folders = array();

        // Lay danh sach file
        $sql = 'SELECT path FROM ' . $db_config['prefix'] . '_extension_files WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();
        $files = $sth->fetchAll();

        $config_ini = '';
        if ($row['type'] == 'module') {
            // Module folder
            if (file_exists(NV_ROOTDIR . '/modules/' . $row['basename'] . '/')) {
                $files_folders[] = NV_ROOTDIR . '/modules/' . $row['basename'] . '/';
            } elseif (file_exists(NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $row['basename'] . '/')) {
                $files_folders[] = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $row['basename'] . '/';

                $langs_admin = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
                foreach ($langs_admin as $langi) {
                    if (file_exists(NV_ROOTDIR . '/includes/language/' . $langi . '/admin_' . $row['basename'] . '.php')) {
                        $files_folders[] = NV_ROOTDIR . '/includes/language/' . $langi . '/admin_' . $row['basename'] . '.php';
                    }
                }
            }

            // Theme folder
            $theme_package = '';
            if (is_dir(NV_ROOTDIR . '/themes/default/modules/' . $row['basename'])) {
                $theme_package = 'default';
            } elseif (is_dir(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/modules/' . $row['basename'])) {
                $theme_package = $global_config['site_theme'];
            }

            if (! empty($theme_package)) {
                $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/modules/' . $row['basename'] . '/';

                if (file_exists(NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $row['basename'] . '.css')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $row['basename'] . '.css';
                }

                $_files = glob(NV_ROOTDIR . '/themes/' . $theme_package . '/js/' . $row['basename'] . '*.js');
                foreach ($_files as $_file) {
                    $files_folders[] = $_file;
                }

                if (file_exists(NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $row['basename'] . '/')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $row['basename'] . '/';
                }
            }

            // Admin default theme
            if (file_exists(NV_ROOTDIR . '/themes/admin_default')) {
                if (file_exists(NV_ROOTDIR . '/themes/admin_default/css/' . $row['basename'] . '.css')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/admin_default/css/' . $row['basename'] . '.css';
                }

                $_files = glob(NV_ROOTDIR . '/themes/admin_default/js/' . $row['basename'] . '*.js');
                foreach ($_files as $_file) {
                    $files_folders[] = $_file;
                }

                if (file_exists(NV_ROOTDIR . '/themes/admin_default/images/' . $row['basename'] . '/')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/admin_default/images/' . $row['basename'] . '/';
                }

                if (file_exists(NV_ROOTDIR . '/themes/admin_default/modules/' . $row['basename'] . '/')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/admin_default/modules/' . $row['basename'] . '/';
                }
            }
        } elseif ($row['type'] == 'theme') {
            $list = scandir(NV_ROOTDIR . '/themes/' . $row['basename']);
            $array_no_zip = in_array($row['basename'], $array_theme_admin) ? array( '.', '..' ) : array( '.', '..', 'config.ini' );

            foreach ($list as $file_i) {
                if (! in_array($file_i, $array_no_zip)) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $row['basename'] . '/' . $file_i;
                }
            }

            if (! in_array($row['basename'], $array_theme_admin)) {
                if ($xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $row['basename'] . '/config.ini')) {
                    $info = $xml->xpath('info');
                    $layoutdefault = ( string )$xml->layoutdefault;
                    $config_ini = "<?xml version='1.0'?>\n<theme>\n\t<info>\n\t\t<name>" . ( string )$info[0]->name . "</name>\n\t\t<author>" . ( string )$info[0]->author . "</author>\n\t\t<website>" . ( string )$info[0]->website . "</website>\n\t\t<description>" . ( string )$info[0]->description . "</description>\n\t\t<thumbnail>" . ( string )$info[0]->thumbnail . "</thumbnail>\n\t</info>\n\n\t<layoutdefault>" . $layoutdefault . "</layoutdefault>\n\n\t<positions>";

                    $position = $xml->xpath('positions');
                    $positions = $position[0]->position;
                    for ($j = 0, $count = sizeof($positions); $j < $count; ++$j) {
                        $config_ini .= "\n\t\t<position>\n\t\t\t<name>" . $positions[$j]->name . "</name>\n\t\t\t<tag>" . $positions[$j]->tag . "</tag>\n\t\t</position>\n";
                    }

                    $config_ini .= "\t</positions>";

                    $array_layout_other = array();
                    $result = $db->query('SELECT layout, in_module, func_name FROM ' . NV_PREFIXLANG . '_modthemes t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.theme=' . $db->quote($row['basename']) . ' AND t1.func_id=t2.func_id AND t1.layout!=' . $db->quote($layoutdefault));
                    while (list($layout, $in_module, $func_name) = $result->fetch(3)) {
                        $array_layout_other[$layout][$in_module][] = $func_name;
                    }
                    if (! empty($array_layout_other)) {
                        $config_ini .= "\n\n\t<setlayout>";
                        foreach ($array_layout_other as $layout => $array_layout_i) {
                            $config_ini .= "\n\t\t<layout>\n\t\t\t<name>" . $layout . "</name>";
                            foreach ($array_layout_i as $in_module => $arr_func_name) {
                                $config_ini .= "\n\t\t\t<funcs>" . $in_module . ":" . implode(",", $arr_func_name) . "</funcs>";
                            }
                            $config_ini .= "\n\t\t</layout>\n";
                        }
                        $config_ini .= "\t</setlayout>";
                    }

                    $array_layout_block = array();
                    $array_not_all_func = array();
                    $result = $db->query('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme=' . $db->quote($row['basename']) . ' ORDER BY position ASC, weight ASC');

                    while ($_row = $result->fetch()) {
                        $array_layout_block[] = $_row;
                        if (empty($_row['all_func'])) {
                            $array_not_all_func[] = $_row['bid'];
                        }
                    }

                    if (! empty($array_layout_block)) {
                        $array_block_func = array();
                        if (! empty($array_not_all_func)) {
                            $result = $db->query('SELECT bid, func_name, in_module FROM ' . NV_BLOCKS_TABLE . '_weight t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.bid IN (' . implode(',', $array_not_all_func) . ') AND t1.func_id=t2.func_id');
                            while (list($bid, $func_name, $in_module) = $result->fetch(3)) {
                                $array_block_func[$bid][$in_module][] = $func_name;
                            }
                        }


                        $config_ini .= "\n\n\t<setblocks>";
                        foreach ($array_layout_block as $_row) {
                            if (! empty($_row['config'])) {
                                $_row['config'] = htmlspecialchars($_row['config']);
                            }

                            $config_ini .= "\n\t\t<block>";
                            $config_ini .= "\n\t\t\t<module>" . $_row['module'] . "</module>";
                            $config_ini .= "\n\t\t\t<file_name>" . $_row['file_name'] . "</file_name>";
                            $config_ini .= "\n\t\t\t<title>" . $_row['title'] . "</title>";
                            $config_ini .= "\n\t\t\t<template>" . $_row['template'] . "</template>";
                            $config_ini .= "\n\t\t\t<position>" . $_row['position'] . "</position>";
                            $config_ini .= "\n\t\t\t<all_func>" . $_row['all_func'] . "</all_func>";
                            $config_ini .= "\n\t\t\t<config>" . $_row['config'] . "</config>";

                            if (empty($_row['all_func'])) {
                                foreach ($array_block_func[$_row['bid']] as $in_module => $arr_func_name) {
                                    $config_ini .= "\n\t\t\t<funcs>" . $in_module . ":" . implode(",", $arr_func_name) . "</funcs>";
                                }
                            }
                            $config_ini .= "\n\t\t</block>\n";
                        }
                        $config_ini .= "\t</setblocks>";
                    }

                    $config_ini .= "\n</theme>";
                } else {
                    $config_ini = file_get_contents(NV_ROOTDIR . '/themes/default/config.ini');
                }
            }
        }

        //Kiểm tra các file không có trong cấu trúc của module, giao diện
        if (! empty($files)) {
            foreach ($files as $file) {
                $file = NV_ROOTDIR . '/' . $file['path'];

                $_exitfolder = false;
                foreach ($files_folders as $_folder) {
                    if (strpos($file, $_folder) !== false) {
                        $_exitfolder = true;
                        break;
                    }
                }
                if ($_exitfolder == false and file_exists($file)) {
                    $files_folders[] = $file;
                }
            }
        }

        if (! empty($files_folders)) {
            $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $row['type'] . '_' . $row['basename'] . '_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.zip';

            if (file_exists($file_src)) {
                @nv_deletefile($file_src);
            }

            $files_folders = array_unique($files_folders);
            $zip = new PclZip($file_src);
            $zip->add($files_folders, PCLZIP_OPT_REMOVE_PATH, $row['type'] == 'theme' ? (NV_ROOTDIR . '/themes') : NV_ROOTDIR);

            if (! empty($config_ini)) {
                $zip->add(array( array(
                    PCLZIP_ATT_FILE_NAME => 'config.ini',
                    PCLZIP_ATT_FILE_CONTENT => $config_ini,
                    PCLZIP_ATT_FILE_NEW_FULL_NAME => $row['basename'] . '/config.ini'
                ) ));
            }

            // Them file cau hinh ung ung
            $extension_ini = "[extension]\n";
            $extension_ini .= "id=\"" . $row['id'] . "\"\n";
            $extension_ini .= "type=\"" . $row['type'] . "\"\n";
            $extension_ini .= "name=\"" . $row['basename'] . "\"\n";
            $extension_ini .= "version=\"" . $row['version'] . "\"\n";
            $extension_ini .= "\n[author]\n";
            $extension_ini .= "name=\"" . $row['author'] . "\"\n";
            $extension_ini .= "email=\"" . $row['email'] . "\"\n";
            $extension_ini .= "\n[note]\n";
            $extension_ini .= "text=\"" . $row['note'] . "\"\n";

            $zip->add(array( array(
                PCLZIP_ATT_FILE_NAME => 'config.ini',
                PCLZIP_ATT_FILE_CONTENT => $extension_ini,
                PCLZIP_ATT_FILE_NEW_FULL_NAME => 'config.ini'
            ) ));

            $filesize = @filesize($file_src);

            if ($filesize > 0) {
                $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, 'nv4_' . $row['type'] . '_' . $row['basename'] . '.zip');
                $download->download_file();
                exit();
            }
        }
    }

    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}

// Xoa ung dung
if (md5('delete_' . $request['type'] . '_' . $request['title'] . '_' . NV_CHECK_SESSION) == $request['checksess']) {
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type = :type AND title = :title';
    $sth = $db->prepare($sql);
    $sth->bindValue(':type', $request['type']);
    $sth->bindValue(':title', $request['title']);
    $sth->execute();
    $row = $sth->fetchAll();

    if (sizeof($row) == 1) {
        $row = $row[0];

        // Lay danh sach file
        $sql = 'SELECT path, duplicate FROM ' . $db_config['prefix'] . '_extension_files WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();
        $files = $sth->fetchAll();

        if ($row['type'] == 'module') {
            $module_exit = array();

            $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1');
            while (list($lang_i) = $result->fetch(3)) {
                $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file= :module_file');
                $sth->bindParam(':module_file', $request['title'], PDO::PARAM_STR);
                $sth->execute();
                if ($sth->fetchColumn()) {
                    $module_exit[] = $lang_i;
                }
            }

            if (empty($module_exit)) {
                $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_setup_extensions WHERE basename= :basename AND title!= :title AND type=\'module\'');
                $sth->bindParam(':basename', $request['title'], PDO::PARAM_STR);
                $sth->bindParam(':title', $request['title'], PDO::PARAM_STR);
                $sth->execute();

                if ($sth->fetchColumn()) {
                    $module_exit = 1;
                }
            }

            if (empty($module_exit) and defined('NV_CONFIG_DIR')) {
                // Kiem tra cac site con
                $result = $db->query('SELECT * FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site ORDER BY domain ASC');
                while ($row = $result->fetch()) {
                    try {
                        $result2 = $db->query('SELECT lang FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_setup_language WHERE setup=1');
                        while (list($lang_i) = $result2->fetch(3)) {
                            $sth = $db->prepare('SELECT COUNT(*) FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file= :module_file');
                            $sth->bindParam(':module_file', $request['title'], PDO::PARAM_STR);
                            $sth->execute();
                            if ($sth->fetchColumn()) {
                                $module_exit[] = $row['title'] . ' :' . $lang_i;
                            }
                        }
                    } catch (PDOException $e) {
                        // Nothinh
                    }
                }
            }

            if (empty($module_exit)) {
                $theme_list_site = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
                $theme_list_mobile = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
                $theme_list_admin = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_admin']);
                $theme_list = array_merge($theme_list_site, $theme_list_mobile, $theme_list_admin);

                foreach ($theme_list as $theme) {
                    // Xóa tất cả các file js của module
                    $_files = glob(NV_ROOTDIR . '/themes/' . $theme . '/js/' . $request['title'] . '*.js');
                    foreach ($_files as $_file) {
                        nv_deletefile($_file);
                    }

                    if (file_exists(NV_ROOTDIR . '/themes/' . $theme . '/css/' . $request['title'] . '.css')) {
                        nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/css/' . $request['title'] . '.css');
                    }

                    if (is_dir(NV_ROOTDIR . '/themes/' . $theme . '/images/' . $request['title'])) {
                        nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/images/' . $request['title'], true);
                    }

                    if (is_dir(NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $request['title'])) {
                        nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/modules/' . $request['title'], true);
                    }
                }

                if (is_dir(NV_ROOTDIR . '/modules/' . $request['title'] . '/')) {
                    nv_deletefile(NV_ROOTDIR . '/modules/' . $request['title'] . '/', true);
                }
            }
        } elseif ($row['type'] == 'theme') {
            $check_exit_mod = false;
            $lang_module_array = array();

            $sql_theme = (preg_match($global_config['check_theme_mobile'], $request['title'])) ? 'mobile' : 'theme';

            $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language where setup = 1');
            while (list($lang_i) = $result->fetch(3)) {
                $module_array = array();

                $sth = $db->prepare('SELECT title, custom_title
					FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules
					WHERE ' . $sql_theme . ' = :theme
					ORDER BY weight ASC');
                $sth->bindParam(':theme', $request['title'], PDO::PARAM_STR);
                $sth->execute();
                while (list($title, $custom_title) = $sth->fetch(3)) {
                    $module_array[] = $custom_title;
                }

                if (! empty($module_array)) {
                    $lang_module_array[] = $lang_i . ': ' . implode(', ', $module_array);
                }
            }

            if (! empty($lang_module_array)) {
                die('ERROR_' . printf($lang_module['delele_ext_theme_note_module'], implode('; ', $lang_module_array)));
            } else {
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_theme', 'theme ' . $request['title'], $admin_info['userid']);
                nv_deletefile(NV_ROOTDIR . '/themes/' . $request['title'], true);

                if (!file_exists(NV_ROOTDIR . '/themes/' . $request['title'])) {
                    $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1');
                    while (list($_lang) = $result->fetch(3)) {
                        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_modthemes WHERE theme = :theme');
                        $sth->bindParam(':theme', $request['title'], PDO::PARAM_STR);
                        $sth->execute();

                        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight WHERE bid IN (SELECT bid FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme= :theme)');
                        $sth->bindParam(':theme', $request['title'], PDO::PARAM_STR);
                        $sth->execute();

                        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme = :theme');
                        $sth->bindParam(':theme', $request['title'], PDO::PARAM_STR);
                        $sth->execute();
                    }
                    $nv_Cache->delMod('themes');

                    $db->query('OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_modthemes');
                    $db->query('OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight');
                    $db->query('OPTIMIZE TABLE ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups');
                } else {
                    die('ERROR_' . $lang_module['delele_ext_unsuccess']);
                }
            }
        }

        // Delete other files
        if (! empty($files)) {
            clearstatcache();
            //Resets the contents of the opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }

            foreach ($files as $file) {
                if (file_exists(NV_ROOTDIR . '/' . $file['path'])) {
                    if ($file['duplicate'] > 0) {
                        $sql = 'UPDATE ' . $db_config['prefix'] . '_extension_files SET duplicate = duplicate - 1 WHERE path = :path';
                        $sth = $db->prepare($sql);
                        $sth->bindValue(':path', $file['path']);
                        $sth->execute();
                    } else {
                        @nv_deletefile(NV_ROOTDIR . '/' . $file['path']);
                    }
                } else {
                    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_extension_files WHERE path = :path';
                    $sth = $db->prepare($sql);
                    $sth->bindValue(':path', $file['path']);
                    $sth->execute();
                }
            }

            clearstatcache();
            //Resets the contents of the opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
        }

        // Delete from table
        $sql = 'DELETE FROM ' . $db_config['prefix'] . '_extension_files WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();

        $sql = 'DELETE FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();

        die('OK_' . $lang_module['delele_ext_success']);
    }

    die("ERROR_" . $lang_module['delele_ext_unsuccess']);
}

$array_extType = array( 'module', 'block', 'theme', 'cronjob', 'other', 'sys', 'admin' );
$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selecttype='] = $lang_module['manage'];
foreach ($array_extType as $_type) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selecttype=' . $_type] = $lang_module['extType_' . $_type];
}

$selecttype_old = $nv_Request->get_string('selecttype', 'cookie', '');
$selecttype = $nv_Request->get_string('selecttype', 'get', '');
if ($nv_Request->isset_request('selecttype', 'get') and empty($selecttype)) {
    $nv_Request->unset_request('selecttype', 'cookie');
} elseif (empty($selecttype)) {
    $selecttype = $selecttype_old;
}

if (! in_array($selecttype, $array_extType)) {
    $selecttype = '';
}

if ($selecttype_old != $selecttype and ! empty($selecttype)) {
    $nv_Request->set_Cookie('selecttype', $selecttype, NV_LIVE_COOKIE_TIME);
}

// Cho phep upload
if ($global_config['extension_setup'] == 1 or $global_config['extension_setup'] == 3) {
    $xtpl->assign('SUBMIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload');

    if (! $sys_info['zlib_support']) {
        $xtpl->parse('main.upload_allowed.nozlib');
    } else {
        $xtpl->parse('main.upload_allowed.upload');
    }

    $xtpl->parse('main.upload_allowed');
}

// Array lang setup
$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$result = $db->query($sql);

$array_langs = array();
while ($row = $result->fetch()) {
    $array_langs[$row['lang']] = $row['lang'];
}

// Array modules exists
$array_modules_exists = array();

foreach ($array_langs as $lang) {
    $sql = 'SELECT module_file FROM ' . $db_config['prefix'] . '_' . $lang . '_modules';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_modules_exists[$row['module_file']] = $row['module_file'];
    }
}

// Array themes exists
$array_themes_indb = array();

// Array blocks exists
$array_blocks_exists = array();

foreach ($array_langs as $lang) {
    $sql = 'SELECT DISTINCT file_name FROM ' . $db_config['prefix'] . '_' . $lang . '_blocks_groups';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_blocks_exists[$row['file_name']] = $row['file_name'];
    }
}

// Array crons exists
$array_crons_exists = array();

foreach ($array_langs as $lang) {
    $sql = 'SELECT DISTINCT run_file FROM ' . NV_CRONJOBS_GLOBALTABLE;
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_crons_exists[$row['run_file']] = $row['run_file'];
    }
}

// List extensions
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=basename';
if (in_array($selecttype, $array_extType)) {
    $sql .= ' AND type = ' . $db->quote($selecttype);
    $page_title .= ': ' . $lang_module['extType_' . $selecttype];
}
$sql .= ' ORDER BY addtime DESC';
$result = $db->query($sql);

$array_parse = array();
while ($row = $result->fetch()) {
    if ($row['type'] == 'theme') {
        $array_themes_indb[] = $row['basename'];
    }

    $row['icon'] = $row['is_sys'] ? array( $theme_config['sys_icon'] ) : array();
    $row['is_admin'] = false;
    $row['delete_allowed'] = $row['is_sys'] == 0 ? true : false;

    if ($row['type'] == 'module' and isset($array_modules_exists[$row['basename']])) {
        $row['delete_allowed'] = false;
    } elseif ($row['type'] == 'theme' and ($global_config['site_theme'] == $row['basename'] or $row['basename'] == 'default')) {
        $row['delete_allowed'] = false;
    } elseif ($row['type'] == 'block' and isset($array_blocks_exists[$row['basename']])) {
        $row['delete_allowed'] = false;
    } elseif ($row['type'] == 'cronjob' and isset($array_crons_exists[$row['basename']])) {
        $row['delete_allowed'] = false;
    }

    $row['url_package'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $row['type'] . '&amp;title=' . $row['title'] . '&amp;checksess=' . md5('package_' . $row['type'] . '_' . $row['title'] . '_' . NV_CHECK_SESSION);
    $row['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $row['type'] . '&amp;title=' . $row['title'] . '&amp;checksess=' . md5('delete_' . $row['type'] . '_' . $row['title'] . '_' . NV_CHECK_SESSION);
    $row['type'] = isset($lang_module['extType_' . $row['type']]) ? $lang_module['extType_' . $row['type']] : $lang_module['extType_other'];
    $row['version'] = array_filter(explode(" ", $row['version']));

    if (sizeof($row['version']) == 2) {
        $row['version'] = $row['version'][0] . '-' . nv_date('d/m/Y', $row['version'][1]);
    } else {
        $row['version'] = 'N/A';
    }

    $array_parse[] = $row;
}

// Them cac module admin
if ($selecttype == '' or $selecttype == 'admin') {
    foreach ($array_module_admin as $row) {
        $array_parse[] = array(
            'type' => $lang_module['extType_module'],
            'basename' => $row,
            'author' => 'VINADES <contact@vinades.vn>',
            'version' => $global_config['version'],
            'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=module&amp;title=' . $row . '&amp;checksess=' . md5('package_module_' . $row . '_' . NV_CHECK_SESSION),
            'is_admin' => true,
            'icon' => array( $theme_config['admin_icon'], $theme_config['sys_icon'] ),
            'delete_allowed' => false,
        );
    }
}

// Them cac theme admin
$is_reload = false;
if ($selecttype == '' or $selecttype == 'theme') {
    $theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
    $theme_mobile_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
    $theme_list = array_merge($theme_list, $theme_mobile_list);
    foreach ($theme_list as $_theme) {
        if (! in_array($_theme, $array_themes_indb) and file_exists(NV_ROOTDIR . '/themes/' . $_theme . '/config.ini')) {
            if ($xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $_theme . '/config.ini')) {
                $info = $xml->xpath('info');
                $table_prefix = preg_replace('/(\W+)/i', '_', $_theme);
                $version = $global_config['version'] . ' ' . NV_CURRENTTIME;
                $note = ( string )$info[0]->description;
                $author = ( string )$info[0]->author;

                $array_parse[] = array(
                    'type' => $lang_module['extType_theme'],
                    'basename' => $_theme,
                    'author' => $author,
                    'version' => '',
                    'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=theme&amp;title=' . $_theme . '&amp;checksess=' . md5('package_theme_' . $_theme . '_' . NV_CHECK_SESSION),
                    'is_admin' => false,
                    'icon' => array(),
                    'delete_allowed' => true,
                );

                // Save to database
                $sql = 'INSERT INTO ' . $db_config['prefix'] . '_setup_extensions VALUES( 0, \'theme\', :title, 0, 0, :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note )';
                $sth = $db->prepare($sql);
                $sth->bindParam(':title', $_theme, PDO::PARAM_STR);
                $sth->bindParam(':basename', $_theme, PDO::PARAM_STR);
                $sth->bindParam(':author', $author, PDO::PARAM_STR);
                $sth->bindParam(':table_prefix', $table_prefix, PDO::PARAM_STR);
                $sth->bindParam(':version', $version, PDO::PARAM_STR);
                $sth->bindParam(':note', $note, PDO::PARAM_STR);
                $sth->execute();

                $is_reload = true;
            }
        }
    }

    foreach ($array_theme_admin as $row) {
        $array_parse[] = array(
            'type' => $lang_module['extType_theme'],
            'basename' => $row,
            'author' => 'VINADES <contact@vinades.vn>',
            'version' => $global_config['version'],
            'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=theme&amp;title=' . $row . '&amp;checksess=' . md5('package_theme_' . $row . '_' . NV_CHECK_SESSION),
            'is_admin' => true,
            'icon' => array( $theme_config['admin_icon'], $theme_config['sys_icon'] ),
            'delete_allowed' => false,
        );
    }
}

if ($is_reload) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

foreach ($array_parse as $row) {
    $row['author'] = nv_htmlspecialchars($row['author']);

    $xtpl->assign('ROW', $row);

    if (! empty($row['icon'])) {
        foreach ($row['icon'] as $icon) {
            $xtpl->assign('ICON', $icon);
            $xtpl->parse('main.loop.icons.loop');
        }

        $xtpl->parse('main.loop.icons');
    }

    if ($row['delete_allowed'] === true) {
        $xtpl->parse('main.loop.delete');
    }

    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';