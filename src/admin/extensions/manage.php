<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_EXTENSIONS')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('manage');

$request = [
    'type' => $nv_Request->get_title('type', 'get', ''),
    'title' => $nv_Request->get_title('title', 'get', ''),
    'checkss' => $nv_Request->get_title('checkss', 'get', '')
];

$array_extType = [
    'module',
    'block',
    'theme',
    'cronjob',
    'other',
    'sys',
    'admin'
];

if (!in_array($request['type'], $array_extType, true)) {
    $request['type'] = '';
}

// Module trong admin
$array_module_admin = nv_scandir(NV_ROOTDIR . '/' . NV_ADMINDIR, $global_config['check_module']);

// Giao diện trong admin
$array_theme_admin = nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']);

// Package extensions (Odd feature: Package module, theme)
if (!empty($request['checkss']) and csrf_check($request['checkss'], $csrf_key . '_package_' . $request['type'] . '_' . $request['title'])) {
    // Kiem tra ung dung ton tai
    if (($request['type'] == 'module' and in_array($request['title'], $array_module_admin, true)) or ($request['type'] == 'theme' and in_array($request['title'], $array_theme_admin, true))) {
        $row = [
            0 => [
                'id' => 0,
                'type' => $request['type'],
                'basename' => $request['title'],
                'author' => 'VINADES <contact@vinades.vn>',
                'version' => $global_config['version'] . ' ' . NV_CURRENTTIME,
                'is_sys' => 1,
                'virtual' => 0,
                'note' => ''
            ]
        ];
    } else {
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();
        $row = $sth->fetchAll();
    }

    if (count($row) == 1) {
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
        $files_folders = [];

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

            if (!empty($theme_package)) {
                $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/modules/' . $row['basename'] . '/';

                $files_css = nv_scandir(NV_ROOTDIR . '/themes/' . $theme_package . '/css', '/^' . nv_preg_quote($row['basename']) . '(?:\.\*|_.*)?\.css$/');
                foreach ($files_css as $file_css) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/css/' . $file_css;
                }

                $files_js = nv_scandir(NV_ROOTDIR . '/themes/' . $theme_package . '/js', '/^' . nv_preg_quote($row['basename']) . '(?:\.\*|_.*)?\.js$/');
                foreach ($files_js as $file_js) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/js/' . $file_js;
                }

                if (file_exists(NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $row['basename'] . '/')) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $theme_package . '/images/' . $row['basename'] . '/';
                }
            }

            // Admin default theme
            if (file_exists(NV_ROOTDIR . '/themes/admin_default')) {
                $files_css = nv_scandir(NV_ROOTDIR . '/themes/admin_default/css', '/^' . nv_preg_quote($row['basename']) . '(?:\.\*|_.*)?\.css$/');
                foreach ($files_css as $file_css) {
                    $files_folders[] = NV_ROOTDIR . '/themes/admin_default/css/' . $file_css;
                }

                $files_js = nv_scandir(NV_ROOTDIR . '/themes/admin_default/js', '/^' . nv_preg_quote($row['basename']) . '(?:\.\*|_.*)?\.js$/');
                foreach ($files_js as $file_js) {
                    $files_folders[] = NV_ROOTDIR . '/themes/admin_default/js/' . $file_js;
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
            $array_no_zip = in_array($row['basename'], $array_theme_admin, true) ? [
                '.',
                '..'
            ] : [
                '.',
                '..',
                'config.ini'
            ];

            foreach ($list as $file_i) {
                if (!in_array($file_i, $array_no_zip, true)) {
                    $files_folders[] = NV_ROOTDIR . '/themes/' . $row['basename'] . '/' . $file_i;
                }
            }

            if (!in_array($row['basename'], $array_theme_admin, true)) {
                if ($xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $row['basename'] . '/config.ini')) {
                    $info = $xml->xpath('info');
                    $layoutdefault = (string) $xml->layoutdefault;
                    $config_ini = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<theme>\n\t<info>\n\t\t<name>" . (string) $info[0]->name . "</name>\n\t\t<author>" . (string) $info[0]->author . "</author>\n\t\t<website>" . (string) $info[0]->website . "</website>\n\t\t<description>" . (string) $info[0]->description . "</description>\n\t\t<thumbnail>" . (string) $info[0]->thumbnail . "</thumbnail>\n\t</info>\n\n\t<layoutdefault>" . $layoutdefault . "</layoutdefault>\n\n\t<positions>";

                    $position = $xml->xpath('positions');
                    $positions = $position[0]->position;
                    for ($j = 0, $count = count($positions); $j < $count; ++$j) {
                        $config_ini .= "\n\t\t<position>\n\t\t\t<name>" . $positions[$j]->name . "</name>\n\t\t\t<tag>" . $positions[$j]->tag . "</tag>\n\t\t</position>\n";
                    }

                    $config_ini .= "\t</positions>";

                    $array_layout_other = [];
                    $stmt = $db->prepare('SELECT layout, in_module, func_name FROM ' . NV_PREFIXLANG . '_modthemes t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.theme = :theme AND t1.func_id = t2.func_id AND t1.layout != :layout');
                    $stmt->bindValue(':theme', $row['basename'], PDO::PARAM_STR);
                    $stmt->bindValue(':layout', $layoutdefault, PDO::PARAM_STR);
                    $stmt->execute();

                    while ($row_stmt = $stmt->fetch()) {
                        $array_layout_other[$row_stmt['layout']][$row_stmt['in_module']][] = $row_stmt['func_name'];
                    }
                    $stmt->closeCursor();

                    if (!empty($array_layout_other)) {
                        $config_ini .= "\n\n\t<setlayout>";
                        foreach ($array_layout_other as $layout => $array_layout_i) {
                            $config_ini .= "\n\t\t<layout>\n\t\t\t<name>" . $layout . '</name>';
                            foreach ($array_layout_i as $in_module => $arr_func_name) {
                                $config_ini .= "\n\t\t\t<funcs>" . $in_module . ':' . implode(',', $arr_func_name) . '</funcs>';
                            }
                            $config_ini .= "\n\t\t</layout>\n";
                        }
                        $config_ini .= "\t</setlayout>";
                    }

                    $array_layout_block = [];
                    $array_not_all_func = [];
                    $stmt = $db->prepare('SELECT * FROM ' . NV_BLOCKS_TABLE . '_groups WHERE theme = :theme ORDER BY position ASC, weight ASC');
                    $stmt->bindValue(':theme', $row['basename'], PDO::PARAM_STR);
                    $stmt->execute();

                    while ($_row = $stmt->fetch()) {
                        $array_layout_block[] = $_row;
                        if (empty($_row['all_func'])) {
                            $array_not_all_func[] = $_row['bid'];
                        }
                    }
                    $stmt->closeCursor();

                    if (!empty($array_layout_block)) {
                        $array_block_func = [];
                        if (!empty($array_not_all_func)) {
                            $placeholders = implode(',', array_fill(0, count($array_not_all_func), '?'));
                            $stmt_res = $db->prepare('SELECT bid, func_name, in_module FROM ' . NV_BLOCKS_TABLE . '_weight t1, ' . NV_MODFUNCS_TABLE . ' t2 WHERE t1.bid IN (' . $placeholders . ') AND t1.func_id = t2.func_id');
                            $stmt_res->execute(array_values($array_not_all_func));
                            while ($_row = $stmt_res->fetch()) {
                                $array_block_func[$_row['bid']][$_row['in_module']][] = $_row['func_name'];
                            }
                            $stmt_res->closeCursor();
                        }

                        $config_ini .= "\n\n\t<setblocks>";
                        foreach ($array_layout_block as $_row) {
                            if (!empty($_row['config'])) {
                                $_row['config'] = htmlspecialchars($_row['config']);
                            }

                            $config_ini .= "\n\t\t<block>";
                            $config_ini .= "\n\t\t\t<module>" . $_row['module'] . '</module>';
                            $config_ini .= "\n\t\t\t<file_name>" . $_row['file_name'] . '</file_name>';
                            $config_ini .= "\n\t\t\t<title>" . $_row['title'] . '</title>';
                            $config_ini .= "\n\t\t\t<template>" . $_row['template'] . '</template>';
                            $config_ini .= "\n\t\t\t<position>" . $_row['position'] . '</position>';
                            $config_ini .= "\n\t\t\t<all_func>" . $_row['all_func'] . '</all_func>';
                            $config_ini .= "\n\t\t\t<bot_visible>" . $_row['bot_visible'] . '</bot_visible>';
                            $config_ini .= "\n\t\t\t<config>" . $_row['config'] . '</config>';

                            if (empty($_row['all_func'])) {
                                foreach ($array_block_func[$_row['bid']] as $in_module => $arr_func_name) {
                                    $config_ini .= "\n\t\t\t<funcs>" . $in_module . ':' . implode(',', $arr_func_name) . '</funcs>';
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

        // Kiểm tra các file không có trong cấu trúc của module, giao diện
        if (!empty($files)) {
            foreach ($files as $file) {
                $file = NV_ROOTDIR . '/' . $file['path'];

                $_exitfolder = false;
                foreach ($files_folders as $_folder) {
                    if (str_contains($file, $_folder)) {
                        $_exitfolder = true;
                        break;
                    }
                }
                if ($_exitfolder == false and file_exists($file)) {
                    $files_folders[] = $file;
                }
            }
        }

        if (!empty($files_folders)) {
            $file_src = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . NV_TEMPNAM_PREFIX . $row['type'] . '_' . $row['basename'] . '_' . md5(nv_genpass(10) . NV_CHECK_SESSION) . '.zip';

            if (file_exists($file_src)) {
                @nv_deletefile($file_src);
            }

            $files_folders = array_unique($files_folders);
            $zip = new PclZip($file_src);
            $zip->add($files_folders, PCLZIP_OPT_REMOVE_PATH, $row['type'] == 'theme' ? (NV_ROOTDIR . '/themes') : NV_ROOTDIR);

            if (!empty($config_ini)) {
                $zip->add([
                    [
                        PCLZIP_ATT_FILE_NAME => 'config.ini',
                        PCLZIP_ATT_FILE_CONTENT => $config_ini,
                        PCLZIP_ATT_FILE_NEW_FULL_NAME => $row['basename'] . '/config.ini'
                    ]
                ]);
            }

            // Them file cau hinh ung ung
            $extension_ini = "[extension]\n";
            $extension_ini .= 'id="' . $row['id'] . "\"\n";
            $extension_ini .= 'type="' . $row['type'] . "\"\n";
            $extension_ini .= 'name="' . $row['basename'] . "\"\n";
            $extension_ini .= 'version="' . $row['version'] . "\"\n";
            $extension_ini .= "\n[author]\n";
            $extension_ini .= 'name="' . $row['author'] . "\"\n";
            $extension_ini .= 'email="' . $row['email'] . "\"\n";
            $extension_ini .= "\n[note]\n";
            $extension_ini .= 'text="' . $row['note'] . "\"\n";

            $zip->add([
                [
                    PCLZIP_ATT_FILE_NAME => 'config.ini',
                    PCLZIP_ATT_FILE_CONTENT => $extension_ini,
                    PCLZIP_ATT_FILE_NEW_FULL_NAME => 'config.ini'
                ]
            ]);

            $filesize = @filesize($file_src);

            if ($filesize > 0) {
                $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . '/' . NV_TEMP_DIR, 'nv4_' . $row['type'] . '_' . $row['basename'] . '.zip');
                $download->download_file();
                exit();
            }
        }
    }

    nv_error404();
}

// Xóa ứng dụng
if (!empty($request['checkss']) and csrf_check($request['checkss'], $csrf_key . '_delete_' . $request['type'] . '_' . $request['title'])) {
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE type = :type AND title = :title';
    $sth = $db->prepare($sql);
    $sth->bindValue(':type', $request['type']);
    $sth->bindValue(':title', $request['title']);
    $sth->execute();
    $row = $sth->fetchAll();

    if (count($row) == 1) {
        $row = $row[0];

        // Lay danh sach file
        $sql = 'SELECT path, duplicate FROM ' . $db_config['prefix'] . '_extension_files WHERE type = :type AND title = :title';
        $sth = $db->prepare($sql);
        $sth->bindValue(':type', $request['type']);
        $sth->bindValue(':title', $request['title']);
        $sth->execute();
        $files = $sth->fetchAll();

        if ($row['type'] == 'module' && preg_match($global_config['check_module'], $request['title'])) {
            $module_exit = [];

            $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
            while ($row_res = $result->fetch()) {
                $lang_i = $row_res['lang'];
                $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file = :module_file');
                $sth->bindValue(':module_file', $request['title'], PDO::PARAM_STR);
                $sth->execute();
                if ($sth->fetchColumn()) {
                    $module_exit[] = $lang_i;
                }
            }
            $result->closeCursor();

            if (empty($module_exit)) {
                $sth = $db->prepare('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_setup_extensions WHERE basename = :basename AND title != :title AND type = \'module\'');
                $sth->bindValue(':basename', $request['title'], PDO::PARAM_STR);
                $sth->bindValue(':title', $request['title'], PDO::PARAM_STR);
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
                        $result2 = $db->query('SELECT lang FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
                        while ($row_res2 = $result2->fetch()) {
                            $lang_i = $row_res2['lang'];
                            $sth = $db->prepare('SELECT COUNT(*) FROM ' . $row['dbsite'] . '.' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE module_file = :module_file');
                            $sth->bindValue(':module_file', $request['title'], PDO::PARAM_STR);
                            $sth->execute();
                            if ($sth->fetchColumn()) {
                                $module_exit[] = $row['title'] . ' :' . $lang_i;
                            }
                        }
                        $result2->closeCursor();
                    } catch (PDOException $e) {
                        // Nothinh
                    }
                }
                $result->closeCursor();
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

                $nv_Cache->delMod('sys');
            }
        } elseif ($row['type'] == 'theme' and (preg_match($global_config['check_theme'], $request['title']) or preg_match($global_config['check_theme_mobile'], $request['title']) or preg_match($global_config['check_theme_admin'], $request['title']))) {
            $check_exit_mod = false;
            $lang_module_array = [];

            $sql_theme = (preg_match($global_config['check_theme_mobile'], $request['title'])) ? 'mobile' : 'theme';

            $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
            while ($row_res = $result->fetch()) {
                $lang_i = $row_res['lang'];
                $module_array = [];

                $sth = $db->prepare('SELECT title, custom_title FROM ' . $db_config['prefix'] . '_' . $lang_i . '_modules WHERE ' . $sql_theme . ' = :theme ORDER BY weight ASC');
                $sth->bindValue(':theme', $request['title'], PDO::PARAM_STR);
                $sth->execute();
                while ($row_sth = $sth->fetch()) {
                    $module_array[] = $row_sth['custom_title'];
                }
                $sth->closeCursor();

                if (!empty($module_array)) {
                    $lang_module_array[] = $lang_i . ': ' . implode(', ', $module_array);
                }
            }
            $result->closeCursor();

            if (!empty($lang_module_array)) {
                nv_jsonOutput([
                    'success' => 0,
                    'text' => printf($nv_Lang->getModule('delele_ext_theme_note_module'), implode('; ', $lang_module_array))
                ]);
            }
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_theme', 'theme ' . $request['title'], $admin_info['userid']);
            nv_deletefile(NV_ROOTDIR . '/themes/' . $request['title'], true);

            if (!file_exists(NV_ROOTDIR . '/themes/' . $request['title'])) {
                $result = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
                while ($row_res = $result->fetch()) {
                    $_lang = $row_res['lang'];
                    $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_modthemes WHERE theme = :theme');
                    $sth->bindValue(':theme', $request['title'], PDO::PARAM_STR);
                    $sth->execute();

                    $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_weight WHERE bid IN (SELECT bid FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme = :theme)');
                    $sth->bindValue(':theme', $request['title'], PDO::PARAM_STR);
                    $sth->execute();

                    $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $_lang . '_blocks_groups WHERE theme = :theme');
                    $sth->bindValue(':theme', $request['title'], PDO::PARAM_STR);
                    $sth->execute();
                }
                $result->closeCursor();
                $nv_Cache->delMod('themes');
                $nv_Cache->delMod('sys');
            } else {
                nv_jsonOutput([
                    'success' => 0,
                    'text' => $nv_Lang->getModule('delele_ext_unsuccess')
                ]);
            }
        }

        // Delete other files
        if (!empty($files)) {
            clearstatcache();
            // Resets the contents of the opcode cache
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
            // Resets the contents of the opcode cache
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

        nv_jsonOutput([
            'success' => 1,
            'text' => $nv_Lang->getModule('delele_ext_success')
        ]);
    }

    nv_jsonOutput([
        'success' => 0,
        'text' => $nv_Lang->getModule('delele_ext_unsuccess')
    ]);
}

$select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selecttype='] = $nv_Lang->getModule('manage');
foreach ($array_extType as $_type) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;selecttype=' . $_type] = $nv_Lang->getModule('extType_' . $_type);
}

$selecttype_old = $nv_Request->get_string('selecttype', 'cookie', '');
$selecttype = $nv_Request->get_string('selecttype', 'get', '');
if ($nv_Request->isset_request('selecttype', 'get') and empty($selecttype)) {
    $nv_Request->unset_request('selecttype', 'cookie');
} elseif (empty($selecttype)) {
    $selecttype = $selecttype_old;
}

if (!in_array($selecttype, $array_extType, true)) {
    $selecttype = '';
}

if ($selecttype_old != $selecttype and !empty($selecttype)) {
    $nv_Request->set_Cookie('selecttype', $selecttype, NV_LIVE_COOKIE_TIME);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->registerPlugin('modifier', 'encodehtml', 'nv_htmlspecialchars');
$tpl->setTemplateDir(get_module_tpl_dir('manage.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('GCONFIG', $global_config);
$tpl->assign('SYS_INFO', $sys_info);
$tpl->assign('SUBMIT_CHECKSS', csrf_create($admin_info['userid'] . '_submit_ext'));

// Array lang setup
$sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$result = $db->query($sql);

$array_langs = [];
while ($row = $result->fetch()) {
    $array_langs[$row['lang']] = $row['lang'];
}
$result->closeCursor();

// Array modules exists
$array_modules_exists = [];

foreach ($array_langs as $lang) {
    $sql = 'SELECT module_file FROM ' . $db_config['prefix'] . '_' . $lang . '_modules';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_modules_exists[$row['module_file']] = $row['module_file'];
    }
    $result->closeCursor();
}

// Array themes exists
$array_themes_indb = [];

// Array blocks exists
$array_blocks_exists = [];

foreach ($array_langs as $lang) {
    $sql = 'SELECT DISTINCT file_name FROM ' . $db_config['prefix'] . '_' . $lang . '_blocks_groups';
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_blocks_exists[$row['file_name']] = $row['file_name'];
    }
    $result->closeCursor();
}

// Array crons exists
$array_crons_exists = [];

foreach ($array_langs as $lang) {
    $sql = 'SELECT DISTINCT run_file FROM ' . NV_CRONJOBS_GLOBALTABLE;
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $array_crons_exists[$row['run_file']] = $row['run_file'];
    }
    $result->closeCursor();
}

// Danh sách các ứng dụng trong CSDL
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_extensions WHERE title=basename';
if (in_array($selecttype, $array_extType, true)) {
    $sql .= ' AND type = :type';
    $page_title .= ': ' . $nv_Lang->getModule('extType_' . $selecttype);
}
$sql .= ' ORDER BY addtime DESC';
$stmt = $db->prepare($sql);
if (in_array($selecttype, $array_extType, true)) {
    $stmt->bindValue(':type', $selecttype, PDO::PARAM_STR);
}
$stmt->execute();

$array_parse = [];
while ($row = $stmt->fetch()) {
    if ($row['type'] == 'theme') {
        $array_themes_indb[] = $row['basename'];
    }

    $row['icon'] = $row['is_sys'] ? ['sys'] : [];
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

    $row['url_package'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $row['type'] . '&amp;title=' . $row['title'] . '&amp;checkss=' . csrf_create($csrf_key . '_package_' . $row['type'] . '_' . $row['title']);
    $row['url_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=' . $row['type'] . '&amp;title=' . $row['title'] . '&amp;checkss=' . csrf_create($csrf_key . '_delete_' . $row['type'] . '_' . $row['title']);
    $row['type'] = $nv_Lang->existsModule('extType_' . $row['type']) ? $nv_Lang->getModule('extType_' . $row['type']) : $nv_Lang->getModule('extType_other');
    $row['version'] = array_filter(explode(' ', $row['version']));

    if (count($row['version']) == 2) {
        $row['version'] = $row['version'][0] . '-' . nv_date_format(1, $row['version'][1]);
    } else {
        $row['version'] = 'N/A';
    }

    $array_parse[] = $row;
}
$stmt->closeCursor();

// Thêm các module trong quản trị
if ($selecttype == '' or $selecttype == 'admin') {
    foreach ($array_module_admin as $row) {
        $array_parse[] = [
            'type' => $nv_Lang->getModule('extType_module'),
            'basename' => $row,
            'author' => 'VINADES <contact@vinades.vn>',
            'version' => $global_config['version'],
            'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=module&amp;title=' . $row . '&amp;checkss=' . csrf_create($csrf_key . '_package_module_' . $row),
            'is_admin' => true,
            'icon' => ['admin', 'sys'],
            'delete_allowed' => false
        ];
    }
}

// Thêm các giao diện trong quản trị
$is_reload = false;
if ($selecttype == '' or $selecttype == 'theme') {
    $theme_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme']);
    $theme_mobile_list = nv_scandir(NV_ROOTDIR . '/themes/', $global_config['check_theme_mobile']);
    $theme_list = array_merge($theme_list, $theme_mobile_list);

    $stmt_insert = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_setup_extensions VALUES (0, \'theme\', :title, 0, 0, :basename, :table_prefix, :version, ' . NV_CURRENTTIME . ', :author, :note)');

    foreach ($theme_list as $_theme) {
        if (!in_array($_theme, $array_themes_indb, true) and file_exists(NV_ROOTDIR . '/themes/' . $_theme . '/config.ini')) {
            if ($xml = @simplexml_load_file(NV_ROOTDIR . '/themes/' . $_theme . '/config.ini')) {
                $info = $xml->xpath('info');
                $table_prefix = preg_replace('/(\W+)/i', '_', $_theme);
                $version = $global_config['version'] . ' ' . NV_CURRENTTIME;
                $note = (string) $info[0]->description;
                $author = (string) $info[0]->author;

                $array_parse[] = [
                    'type' => $nv_Lang->getModule('extType_theme'),
                    'basename' => $_theme,
                    'author' => $author,
                    'version' => '',
                    'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=theme&amp;title=' . $_theme . '&amp;checkss=' . csrf_create($csrf_key . '_package_theme_' . $_theme),
                    'is_admin' => false,
                    'icon' => [],
                    'delete_allowed' => true
                ];

                // Save to database
                $stmt_insert->bindValue(':title', $_theme, PDO::PARAM_STR);
                $stmt_insert->bindValue(':basename', $_theme, PDO::PARAM_STR);
                $stmt_insert->bindValue(':author', $author, PDO::PARAM_STR);
                $stmt_insert->bindValue(':table_prefix', $table_prefix, PDO::PARAM_STR);
                $stmt_insert->bindValue(':version', $version, PDO::PARAM_STR);
                $stmt_insert->bindValue(':note', $note, PDO::PARAM_STR);
                $stmt_insert->execute();

                $is_reload = true;
            }
        }
    }

    foreach ($array_theme_admin as $row) {
        $array_parse[] = [
            'type' => $nv_Lang->getModule('extType_theme'),
            'basename' => $row,
            'author' => 'VINADES <contact@vinades.vn>',
            'version' => $global_config['version'],
            'url_package' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;type=theme&amp;title=' . $row . '&amp;checkss=' . csrf_create($csrf_key . '_package_theme_' . $row),
            'is_admin' => true,
            'icon' => ['admin', 'sys'],
            'delete_allowed' => false
        ];
    }
}

if ($is_reload) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

$tpl->assign('ARRAY', $array_parse);

$contents = $tpl->fetch('manage.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
