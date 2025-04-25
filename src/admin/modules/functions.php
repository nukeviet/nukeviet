<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Template\Email\Cat as EmailCat;

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_modules')
];

define('NV_IS_FILE_MODULES', true);

// Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:modules';
$array_url_instruction['setup'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:setup';
$array_url_instruction['vmodule'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:vmodule';
$array_url_instruction['edit'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:edit';

/**
 * nv_parse_vers()
 *
 * @param mixed $ver
 */
function nv_parse_vers($ver)
{
    return $ver[1] . '-' . nv_date_format(1, $ver[2]);
}

/**
 * nv_fix_module_weight()
 */
function nv_fix_module_weight()
{
    global $db, $nv_Cache;

    $result = $db->query('SELECT title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $weight . ' WHERE title= :title');
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('modules');
}

/**
 * nv_fix_subweight()
 *
 * @param mixed $mod
 */
function nv_fix_subweight($mod)
{
    global $db;

    $subweight = 0;
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :in_module AND show_func=1 ORDER BY subweight ASC');
    $sth->bindParam(':in_module', $mod, PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        ++$subweight;
        $db->query('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=' . $subweight . ' WHERE func_id=' . $row['func_id']);
    }
}

/**
 * nv_setup_block_module()
 *
 * @param mixed $mod
 * @param int   $func_id
 */
function nv_setup_block_module($mod, $func_id = 0)
{
    global $db, $nv_Cache;

    if (empty($func_id)) {
        // xoa du lieu tai bang blocks
        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module)');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE func_id in (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module)');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();
    }

    $array_funcid = [];
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 AND in_module= :module ORDER BY subweight ASC');
    $sth->bindParam(':module', $mod, PDO::PARAM_STR);
    $sth->execute();
    while ([$func_id_i] = $sth->fetch(3)) {
        if ($func_id == 0 or $func_id == $func_id_i) {
            $array_funcid[] = $func_id_i;
        }
    }

    $weight = 0;
    $old_theme = $old_position = '';

    $sql = 'SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE all_func= 1 ORDER BY theme ASC, position ASC, weight ASC';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if ($old_theme == $row['theme'] and $old_position == $row['position']) {
            ++$weight;
        } else {
            $weight = 1;
            $old_theme = $row['theme'];
            $old_position = $row['position'];
        }

        foreach ($array_funcid as $func_id) {
            $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')');
        }
    }

    $nv_Cache->delMod('themes');
}

/**
 * nv_setup_data_module()
 *
 * @param mixed $lang
 * @param mixed $module_name
 * @param int   $sample
 */
function nv_setup_data_module($lang, $module_name, $sample = 0)
{
    global $nv_Cache, $db, $db_config, $global_config, $install_lang;

    $return = [
        'success' => 0
    ];

    $sth = $db->prepare('SELECT module_file, module_data, module_upload, theme FROM ' . $db_config['prefix'] . '_' . $lang . '_modules WHERE title= :title');
    $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
    $sth->execute();

    [$module_file, $module_data, $module_upload, $module_theme] = $sth->fetch(3);

    if (empty($module_file)) {
        return $return;
    }

    $module_version = [];
    $version_file = NV_ROOTDIR . '/modules/' . $module_file . '/version.php';

    if (file_exists($version_file)) {
        include $version_file;
    }

    $arr_modfuncs = (isset($module_version['modfuncs']) and !empty($module_version['modfuncs'])) ? array_map('trim', explode(',', $module_version['modfuncs'])) : [];

    // Delete config value in prefix_config table
    $sth = $db->prepare('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang= '" . $lang . "' AND module= :module");
    $sth->bindParam(':module', $module_name, PDO::PARAM_STR);
    $sth->execute();

    // Xóa block tùy chỉnh
    nv_purge_blocks($module_name);

    $nv_Cache->delAll();

    // Re-Creat all module table
    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php')) {
        $sql_recreate_module = [];

        try {
            $db->exec('ALTER DATABASE ' . $db_config['dbname'] . ' DEFAULT CHARACTER SET ' . $db_config['charset'] . ' COLLATE ' . $db_config['collation']);
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }

        include NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php';

        if (!empty($sql_create_module)) {
            foreach ($sql_create_module as $sql) {
                try {
                    $db->query($sql);
                } catch (PDOException $e) {
                    trigger_error(print_r($e, true));

                    return $return;
                }
            }
        }
    }

    // Setup layout if site module
    $arr_func_id = [];
    $arr_show_func = [];
    $new_funcs = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/funcs', $global_config['check_op_file']);

    if (!empty($new_funcs)) {
        // Get default layout
        $layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout', $global_config['check_op_layout']);
        if (!empty($layout_array)) {
            $layout_array = preg_replace($global_config['check_op_layout'], '\\1', $layout_array);
        }

        $selectthemes = 'default';
        if (!empty($module_theme) and file_exists(NV_ROOTDIR . '/themes/' . $module_theme . '/config.ini')) {
            $selectthemes = $module_theme;
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini')) {
            $selectthemes = $global_config['site_theme'];
        }

        $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
        $layoutdefault = (string) $xml->layoutdefault;
        $layout = $xml->xpath('setlayout/layout');

        $array_layout_func_default = [];
        for ($i = 0, $count = count($layout); $i < $count; ++$i) {
            $layout_name = (string) $layout[$i]->name;

            if (in_array($layout_name, $layout_array, true)) {
                $layout_funcs = $layout[$i]->xpath('funcs');
                for ($j = 0, $count2 = count($layout_funcs); $j < $count2; ++$j) {
                    $mo_funcs = (string) $layout_funcs[$j];
                    $mo_funcs = explode(':', $mo_funcs);
                    $m = $mo_funcs[0];
                    $arr_f = explode(',', $mo_funcs[1]);
                    foreach ($arr_f as $f) {
                        $array_layout_func_default[$m][$f] = $layout_name;
                    }
                }
            }
        }

        $_layoutdefault = (isset($module_version['layoutdefault'])) ? $module_version['layoutdefault'] : '';
        if (!empty($_layoutdefault)) {
            $_layout_mod = explode(';', $_layoutdefault);
            foreach ($_layout_mod as $_layout_fun) {
                [$layout_name, $_func] = explode(':', trim($_layout_fun));
                $arr_f = explode(',', trim($_func));
                foreach ($arr_f as $f) {
                    if (!isset($array_layout_func_default[$module_name][$f])) {
                        $array_layout_func_default[$module_name][$f] = $layout_name;
                    }
                }
            }
        }

        $arr_func_id_old = [];

        $sth = $db->prepare('SELECT func_id, func_name FROM ' . $db_config['prefix'] . '_' . $lang . '_modfuncs WHERE in_module= :in_module');
        $sth->bindParam(':in_module', $module_name, PDO::PARAM_STR);
        $sth->execute();
        while ($row = $sth->fetch()) {
            $arr_func_id_old[$row['func_name']] = $row['func_id'];
        }

        $new_funcs = preg_replace($global_config['check_op_file'], '\\1', $new_funcs);
        $new_funcs = array_flip($new_funcs);
        $array_keys = array_keys($new_funcs);

        $array_submenu = (isset($module_version['submenu'])) ? array_map('trim', explode(',', $module_version['submenu'])) : [];
        foreach ($array_keys as $func) {
            $show_func = 0;
            $weight = 0;
            $in_submenu = (in_array($func, $array_submenu, true)) ? 1 : 0;
            if (isset($arr_func_id_old[$func]) and isset($arr_func_id_old[$func]) > 0) {
                $arr_func_id[$func] = $arr_func_id_old[$func];
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $lang . '_modfuncs SET show_func= ' . $show_func . ', in_submenu=' . $in_submenu . ', subweight=0 WHERE func_id=' . $arr_func_id[$func]);
            } else {
                $data = [];
                $data['func_name'] = $func;
                $data['alias'] = $func;
                $data['func_custom_name'] = ucfirst($func);
                $data['in_module'] = $module_name;

                $arr_func_id[$func] = $db->insert_id('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_modfuncs
                    (func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES
                     (:func_name, :alias, :func_custom_name, :in_module, ' . $show_func . ', ' . $in_submenu . ', ' . $weight . ", '')", 'func_id', $data);
                if ($arr_func_id[$func]) {
                    $layout = $layoutdefault;
                    if (isset($array_layout_func_default[$module_name][$func])) {
                        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout/layout.' . $array_layout_func_default[$module_name][$func] . '.tpl')) {
                            $layout = $array_layout_func_default[$module_name][$func];
                        }
                    }
                    $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_modthemes (func_id, layout, theme) VALUES (' . $arr_func_id[$func] . ', ' . $db->quote($layout) . ', ' . $db->quote($selectthemes) . ')');
                }
            }
        }

        $subweight = 0;
        foreach ($arr_modfuncs as $func) {
            if (isset($arr_func_id[$func])) {
                $func_id = $arr_func_id[$func];
                $arr_show_func[] = $func_id;
                $show_func = 1;
                ++$subweight;
                $db->query('UPDATE ' . $db_config['prefix'] . '_' . $lang . '_modfuncs SET subweight=' . $subweight . ', show_func=' . $show_func . ' WHERE func_id=' . $func_id);
            }
        }
    } else {
        // Xoa du lieu tai bang _modfuncs
        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $lang . '_modfuncs WHERE in_module= :in_module');
        $sth->bindParam(':in_module', $module_name, PDO::PARAM_STR);
        $sth->execute();
    }

    // Creat upload dirs
    if (isset($module_version['uploads_dir']) and !empty($module_version['uploads_dir'])) {
        $sth_dir = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_dir (dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES (:dirname, 0, 0, 0, 0, 0)');

        foreach ($module_version['uploads_dir'] as $path) {
            $cp = '';
            $arr_p = explode('/', $path);

            foreach ($arr_p as $p) {
                if (trim($p) != '') {
                    if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p)) {
                        $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                        if ($mk[0]) {
                            try {
                                $sth_dir->bindValue(':dirname', NV_UPLOADS_DIR . '/' . $cp . $p, PDO::PARAM_STR);
                                $sth_dir->execute();
                            } catch (PDOException $e) {
                            }
                        }
                    }

                    $cp .= $p . '/';
                }
            }
        }
    }

    // Creat assets dirs
    if (isset($module_version['files_dir']) and !empty($module_version['files_dir'])) {
        foreach ($module_version['files_dir'] as $path) {
            $cp = '';
            $arr_p = explode('/', $path);

            foreach ($arr_p as $p) {
                if (trim($p) != '') {
                    if (!is_dir(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp . $p)) {
                        nv_mkdir(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp, $p);
                    }
                    if (!is_dir(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $cp . $p)) {
                        nv_mkdir(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $cp, $p);
                    }
                    $cp .= $p . '/';
                }
            }
        }
    }

    // Install sample data
    if ($sample) {
        $sample_lang_file = NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php';
        $sample_default_file = NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php';

        if (file_exists($sample_lang_file)) {
            include $sample_lang_file;
        } elseif (file_exists($sample_default_file)) {
            include $sample_default_file;
        }
    }

    // Cài đặt emailtemplate của module nếu có (không cài đặt nếu cài lại module)
    $email_files = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/language', '/^email\_([a-z]{2})\.php$/');
    $email_langs = $return_emails = [];
    foreach ($email_files as $file_i) {
        $email_langs[] = substr($file_i, 6, 2);
    }

    if (!empty($email_langs) and !defined('NV_MODULE_RECREATE')) {
        $array_columns = $db->columns_array($db_config['prefix'] . '_emailtemplates');
        $langs = $email_data = [];
        foreach ($array_columns as $key => $value) {
            if (preg_match('/^([a-z]{2})\_content$/', $key, $m)) {
                $langs[] = $m[1];

                // Đọc email trên tất cả các ngôn ngữ
                if (in_array($m[1], $email_langs, true)) {
                    $module_emails = [];
                    include NV_ROOTDIR . '/modules/' . $module_file . '/language/email_' . $m[1] . '.php';
                    if (!empty($module_emails)) {
                        $email_data[$m[1]] = $module_emails;
                    }
                }
            }
        }

        if (!empty($email_data)) {
            // Tìm ngôn ngữ email mà module hỗ trợ
            if (isset($email_data[$lang])) {
                $email_lang = $lang;
            } elseif (isset($email_data['en'])) {
                $email_lang = 'en';
            } else {
                $email_lang = array_key_first($email_data);
            }

            foreach ($email_data[$email_lang] as $key => $value) {
                // Thêm mới mẫu email cho module này, lấy data của toàn bộ các ngôn ngữ hiện có
                $field_title = $field_value = '';
                foreach ($langs as $lang_i) {
                    $field_title .= ', ' . $lang_i . '_title, ' . $lang_i . '_subject, ' . $lang_i . '_content';
                    $field_value .= ', :' . $lang_i . '_title, :' . $lang_i . '_subject, :' . $lang_i . '_content';
                }

                try {
                    $sql = 'INSERT INTO ' . $db_config['prefix'] . '_emailtemplates (
                        lang, module_file, module_name, id, catid, sys_pids, time_add, send_name, send_email,
                        send_cc, send_bcc, attachments, is_system, is_plaintext, is_disabled,
                        is_selftemplate, default_subject, default_content' . $field_title . '
                    ) VALUES (
                        ' . $db->quote($lang) . ', ' . $db->quote($module_file) . ', ' . $db->quote($module_name) . ',
                        ' . $key . ', ' . intval($value['catid'] ?? EmailCat::CAT_MODULE) . ',
                        ' . $db->quote($value['sys_pids'] ?? $value['pids'] ?? '') . ', ' . NV_CURRENTTIME . ',
                        :send_name, :send_email, :send_cc, :send_bcc, :attachments, ' . intval($value['is_system'] ?? 0) . ',
                        ' . intval($value['is_plaintext'] ?? 0) . ', ' . intval($value['is_disabled'] ?? 0) . ',
                        ' . intval($value['is_selftemplate'] ?? 0) . ', :default_subject, :default_content' . $field_value . '
                    )';

                    $sth = $db->prepare($sql);
                    $sth->bindValue(':send_name', $value['send_name'] ?? '', PDO::PARAM_STR);
                    $sth->bindValue(':send_email', $value['send_email'] ?? '', PDO::PARAM_STR);
                    $sth->bindValue(':send_cc', $value['send_cc'] ?? '', PDO::PARAM_STR);
                    $sth->bindValue(':send_bcc', $value['send_bcc'] ?? '', PDO::PARAM_STR);
                    $sth->bindValue(':attachments', $value['attachments'] ?? '', PDO::PARAM_STR);
                    $sth->bindValue(':default_subject', $value['s'], PDO::PARAM_STR);
                    $sth->bindValue(':default_content', $value['c'], PDO::PARAM_STR);

                    foreach ($langs as $lang_i) {
                        if ($lang_i == $lang or !isset($email_data[$lang_i], $email_data[$lang_i][$key])) {
                            $sth->bindValue(':' . $lang_i . '_title', $value['t'], PDO::PARAM_STR);
                            $sth->bindValue(':' . $lang_i . '_subject', '', PDO::PARAM_STR);
                            $sth->bindValue(':' . $lang_i . '_content', '', PDO::PARAM_STR);
                        } else {
                            $sth->bindValue(':' . $lang_i . '_title', $email_data[$lang_i][$key]['t'] ?? $value['t'], PDO::PARAM_STR);
                            $sth->bindValue(':' . $lang_i . '_subject', $email_data[$lang_i][$key]['s'] ?? '', PDO::PARAM_STR);
                            $sth->bindValue(':' . $lang_i . '_content', $email_data[$lang_i][$key]['c'] ?? '', PDO::PARAM_STR);
                        }
                    }

                    $sth->execute();
                    $emailid = $db->lastInsertId();
                    if (!empty($value['pfile'])) {
                        $return_emails[$emailid] = is_array($value['pfile']) ? $value['pfile'] : [$value['pfile']];
                    }
                } catch (Throwable $e) {
                    trigger_error(print_r($e, true));
                    return $return;
                }
            }
        }
    }

    $nv_Cache->delAll();

    $return['success'] = 1;
    $return['emails'] = $return_emails;
    return $return;
}
