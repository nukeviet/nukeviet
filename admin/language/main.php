<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_LANG')) {
    die('Stop!!!');
}

$page_title = $lang_module['nv_lang_data'];
$_md5_lang_multi = md5('lang_multi_' . NV_CHECK_SESSION);
if (!$global_config['lang_multi']) {
    $lang_module['nv_data_note'] = sprintf($lang_module['nv_data_note2'], NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&lang_multi=' . $_md5_lang_multi) . ' ' . $lang_module['nv_data_note'];
}

$_lang_multi = $nv_Request->get_title('lang_multi', 'get', '');

if ($_lang_multi == $_md5_lang_multi) {
    $errormess = '';
    $array_config_global = array();
    $array_config_global['lang_multi'] = 1;
    $array_config_global['rewrite_optional'] = 0;
    $array_config_global['rewrite_op_mod'] = '';

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'global' AND config_name = :config_name");
    foreach ($array_config_global as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR, 30);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    nv_save_file_config_global();

    $array_config_rewrite = array(
        'rewrite_enable' => $array_config_global['rewrite_enable'],
        'rewrite_optional' => $array_config_global['rewrite_optional'],
        'rewrite_endurl' => $global_config['rewrite_endurl'],
        'rewrite_exturl' => $global_config['rewrite_exturl'],
        'rewrite_op_mod' => $array_config_global['rewrite_op_mod'],
        'ssl_https' => $global_config['ssl_https']
    );
    $rewrite = nv_rewrite_change($array_config_rewrite);
    if (empty($rewrite[0])) {
        $lang_module['nv_data_note'] .= sprintf($lang_module['err_writable'], $rewrite[1]);
    } else {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$lang_array_exit = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}+$/');

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$array_lang_setup = array();
$db->sqlreset()->select('*')->from($db_config['prefix'] . '_setup_language')->order('weight ASC');
$result = $db->query($db->sql());
while ($row = $result->fetch()) {
    $array_lang_setup[$row['lang']] = intval($row['setup']);
}

if (defined('NV_IS_GODADMIN') or ($global_config['idsite'] > 0 and defined('NV_IS_SPADMIN'))) {
    // Change weight
    if ($nv_Request->isset_request('changeweight', 'post')) {
        if (!defined('NV_IS_AJAX')) {
            die('NO_Access denied!!!');
        }

        $keylang = $nv_Request->get_title('keylang', 'post', '');

        if (!isset($array_lang_setup[$keylang])) {
            die('NO_Access denied!!!');
        }

        $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
        if (empty($new_weight)) {
            die('NO_Access denied!!!');
        }

        $sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE lang!=' . $db->quote($keylang) . ' ORDER BY weight ASC';
        $result = $db->query($sql);

        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight)
                ++$weight;

            $sql = 'UPDATE ' . $db_config['prefix'] . '_setup_language SET weight=' . $weight . ' WHERE lang=' . $db->quote($row['lang']);
            $db->query($sql);
        }

        $sql = 'UPDATE ' . $db_config['prefix'] . '_setup_language SET weight=' . $new_weight . ' WHERE lang=' . $db->quote($keylang);
        $db->query($sql);

        nv_update_config_allow_sitelangs();
        nv_save_file_config_global();

        include NV_ROOTDIR . '/includes/header.php';
        echo 'OK_' . $keylang;
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $checksess = $nv_Request->get_title('checksess', 'get', '');
    $keylang = $nv_Request->get_title('keylang', 'get', '', 1);
    $deletekeylang = $nv_Request->get_title('deletekeylang', 'get', '', 1);

    if ($nv_Request->isset_request('activelang', 'get') and $checksess == md5('activelang_' . $keylang . NV_CHECK_SESSION) and preg_match('/^[a-z]{2}$/', $keylang)) {
        if (empty($global_config['idsite'])) {
            $activelang = $nv_Request->get_int('activelang', 'get', 0);
            $allow_sitelangs = $global_config['allow_sitelangs'];

            $temp = ($activelang == 1) ? $lang_global['yes'] : $lang_global['no'];
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['nv_lang_slsite'], ' langkey : ' . $keylang . ' [ ' . $temp . ' ]', $admin_info['userid']);

            if ($activelang) {
                $allow_sitelangs[] = $keylang;
            } elseif ($keylang != $global_config['site_lang']) {
                $allow_sitelangs = array_diff($allow_sitelangs, array(
                    $keylang
                ));
            }

            nv_update_config_allow_sitelangs(array_unique($allow_sitelangs));
            nv_save_file_config_global();

            $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
            $xtpl->parse('activelang');
            $contents = $xtpl->text('activelang');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        } else {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=site&' . NV_OP_VARIABLE . '=edit&idsite=' . $global_config['idsite']);
        }
    } elseif ($checksess == md5($keylang . NV_CHECK_SESSION) and in_array($keylang, $lang_array_exit)) {
        if (isset($array_lang_setup[$keylang]) and $array_lang_setup[$keylang] == 1) {
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($lang_module['nv_data_setup']);
            include NV_ROOTDIR . '/includes/footer.php';
        } elseif ($global_config['lang_multi']) {
            nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['nv_setup_new'] . ' ' . $lang_module['nv_lang_data'], ' langkey : ' . $keylang, $admin_info['userid']);

            $site_theme = $db->query("SELECT config_value FROM " . NV_CONFIG_GLOBALTABLE . " where lang='" . $global_config['site_lang'] . "' AND module='global' AND config_name='site_theme'")->fetchColumn();

            $global_config['site_theme'] = $site_theme;

            try {
                $db->exec('ALTER DATABASE ' . $db_config['dbname'] . ' DEFAULT CHARACTER SET ' . $db_config['charset'] . ' COLLATE ' . $db_config['collation']);
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
            require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

            $sql_create_table = nv_create_table_sys($keylang);

            foreach ($sql_create_table as $query) {
                try {
                    $db->query($query);
                } catch (PDOException $e) {
                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_admin_theme('ERROR SETUP SQL: <br />' . $query);
                    include NV_ROOTDIR . '/includes/footer.php';
                }
            }
            $db->columns_add(NV_COUNTER_GLOBALTABLE, $keylang . '_count', 'integer', 2147483647, true, 0);

            if (defined('NV_MODULE_SETUP_DEFAULT')) {
                $lang_module['modules'] = '';
                $lang_module['vmodule_add'] = '';
                $lang_module['blocks'] = '';
                $lang_module['autoinstall'] = '';
                $lang_global['mod_modules'] = '';

                $module_name = 'modules';
                require_once NV_ROOTDIR . '/' . NV_ADMINDIR . '/modules/functions.php';
                $module_name = '';

                $array_module_setup = explode(',', NV_MODULE_SETUP_DEFAULT);
                $modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
                $filesavedata = '';
                if (file_exists(NV_ROOTDIR . '/install/data_' . $keylang . '.php')) {
                    $filesavedata = $keylang;
                } else {
                    $filesavedata = 'en';
                }
                $lang_data = $filesavedata;

                $install_lang = array(); //DO NOT DELETE THIS LINE
                $menu_rows_lev0 = array(); //DO NOT DELETE THIS LINE
                $menu_rows_lev1 = array(); //DO NOT DELETE THIS LINE


                include_once NV_ROOTDIR . '/install/data_' . $filesavedata . '.php';

                $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $keylang . '_modules ORDER BY weight ASC');
                while ($row = $result->fetch()) {
                    $setmodule = $row['title'];
                    $row['module_file'] = $row['module_file'];

                    if (in_array($row['module_file'], $modules_exit) and in_array($setmodule, $array_module_setup)) {
                        nv_setup_data_module($keylang, $setmodule);
                    } else {
                        $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $keylang . '_modules WHERE title= :module');
                        $sth->bindParam(':module', $setmodule, PDO::PARAM_STR);
                        $sth->execute();
                    }
                }

                // Cai dat du lieu mau
                $global_config['site_home_module'] = 'users';
                $_site_home_module = $db->query("SELECT config_value FROM " . $db_config['prefix'] . "_config WHERE module = 'global' AND config_name = 'site_home_module' AND lang=" . $db->quote($global_config['site_lang']))
                    ->fetchColumn();
                if (!empty($_site_home_module)) {
                    $result = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $keylang . "_modules where title=" . $db->quote($_site_home_module));
                    if ($result->fetchColumn()) {
                        $global_config['site_home_module'] = $_site_home_module;
                    }
                }

                try {
                    include_once NV_ROOTDIR . '/install/data_by_lang.php';
                    //xoa du lieu tai bang nvx_vi_modules
                    $db->query("DELETE FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules WHERE module_file NOT IN ('" . implode("', '", $modules_exit) . "')");

                    //xoa du lieu tai bang nvx_setup_extensions
                    $db->query("DELETE FROM " . $db_config['prefix'] . "_setup_extensions WHERE basename NOT IN ('" . implode("', '", $modules_exit) . "') AND type='module'");

                    //xoa du lieu tai bang nvx_vi_blocks_groups
                    $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups WHERE module!=\'theme\' AND module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules)');

                    //xoa du lieu tai bang nvx_vi_blocks
                    $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight WHERE bid NOT IN (SELECT bid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups)');

                    //xoa du lieu tai bang nvx_vi_modthemes
                    $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes WHERE func_id in (SELECT func_id FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE in_module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules))');

                    //xoa du lieu tai bang nvx_vi_modfuncs
                    $db->query('DELETE FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs WHERE in_module NOT IN (SELECT title FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules)');

                    //xoa du lieu tai bang nvx_config
                    $db->query("DELETE FROM " . $db_config['prefix'] . "_config WHERE lang= '" . $lang_data . "' AND module!='global' AND module NOT IN (SELECT title FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules)");

                    $result = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . "_" . $lang_data . "_modules WHERE title='news'");
                    if ($result->fetchColumn()) {
                        $result = $db->query('SELECT catid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_cat ORDER BY sort ASC');
                        while (list ($catid_i) = $result->fetch(3)) {
                            nv_copy_structure_table($db_config['prefix'] . '_' . $lang_data . '_news_' . $catid_i, $db_config['prefix'] . '_' . $lang_data . '_news_rows');
                        }
                        $result->closeCursor();

                        $result = $db->query('SELECT id, listcatid FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_rows ORDER BY id ASC');
                        while (list ($id, $listcatid) = $result->fetch(3)) {
                            $arr_catid = explode(',', $listcatid);
                            foreach ($arr_catid as $catid) {
                                $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_news_' . $catid . ' SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_news_rows WHERE id=' . $id);
                            }
                        }
                        $result->closeCursor();
                    }
                } catch (PDOException $e) {
                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_admin_theme('ERROR SETUP: <br />' . $e->getMessage());
                    include NV_ROOTDIR . '/includes/footer.php';
                }

                // Cai dat du lieu mau module
                $lang = $lang_data;
                try {
                    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $lang_data . '_modules ORDER BY weight ASC');
                    while ($row = $result->fetch()) {
                        $module_name = $row['title'];
                        $module_file = $row['module_file'];
                        $module_data = $row['module_data'];
                        $module_upload = $row['module_upload'];

                        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang_data . '.php')) {
                            include NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang_data . '.php';
                        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php')) {
                            include NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php';
                        }
                    }
                } catch (PDOException $e) {
                    include NV_ROOTDIR . '/includes/header.php';
                    echo nv_admin_theme('ERROR SETUP: <br />' . $e->getMessage());
                    include NV_ROOTDIR . '/includes/footer.php';
                }
            }

            $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $keylang . '&' . NV_NAME_VARIABLE . '=settings&' . NV_OP_VARIABLE . '=main');

            $xtpl->parse('contents_setup');
            $contents = $xtpl->text('contents_setup');

            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($contents);
            include NV_ROOTDIR . '/includes/footer.php';
        } else {
            include NV_ROOTDIR . '/includes/header.php';
            echo nv_admin_theme($lang_module['nv_data_note']);
            include NV_ROOTDIR . '/includes/footer.php';
        }
    } elseif ($checksess == md5($deletekeylang . NV_CHECK_SESSION . 'deletekeylang') and !in_array($deletekeylang, $global_config['allow_sitelangs'])) {
        define('NV_IS_FILE_MODULES', true);

        $lang = $deletekeylang;

        nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['nv_setup_delete'], ' langkey : ' . $deletekeylang, $admin_info['userid']);

        $sql = 'SELECT title, module_file, module_data FROM ' . $db_config['prefix'] . '_' . $lang . '_modules ORDER BY weight ASC';
        $result_del_module = $db->query($sql);

        while (list ($title, $module_file, $module_data) = $result_del_module->fetch(3)) {
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php')) {
                $sql_drop_module = array();

                include NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php';
                if (!empty($sql_drop_module)) {
                    foreach ($sql_drop_module as $sql) {
                        try {
                            $db->query($sql);
                        } catch (PDOException $e) {
                            trigger_error($e->getMessage());
                        }
                    }
                }
            }
        }

        $db->query('ALTER TABLE ' . NV_COUNTER_GLOBALTABLE . ' DROP ' . $deletekeylang . '_count');

        require_once NV_ROOTDIR . '/includes/action_' . $db->dbtype . '.php';

        $sql_drop_table = nv_delete_table_sys($deletekeylang);

        foreach ($sql_drop_table as $sql) {
            try {
                $db->query($sql);
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }
        }

        $db->query("DELETE FROM " . NV_CONFIG_GLOBALTABLE . " WHERE lang = '" . $deletekeylang . "'");
        $db->query("DELETE FROM " . $db_config['prefix'] . "_setup_language WHERE lang = '" . $deletekeylang . "'");

        $sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language ORDER BY weight ASC';
        $result = $db->query($sql);

        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            $sql = 'UPDATE ' . $db_config['prefix'] . '_setup_language SET weight=' . $weight . ' WHERE lang=' . $db->quote($row['lang']);
            $db->query($sql);
        }

        $nv_Cache->delAll();

        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&' . NV_LANG_VARIABLE . '=' . $global_config['site_lang'] . '&rand=' . nv_genpass());
    }
}

$array_lang_installed = array();
$num = sizeof($array_lang_setup);
$weight = 0;
foreach ($array_lang_setup as $keylang => $setup) {
    if (in_array($keylang, $lang_array_exit)) {
        $weight ++;
        $xtpl->assign('ROW', array(
            'keylang' => $keylang,
            'name' => $language_array[$keylang]['name']
        ));

        if ($setup == 1) {
            $array_lang_installed[$keylang] = $keylang;
        }

        for ($i = 1; $i <= $num; ++$i) {
            $xtpl->assign('WEIGHT', array(
                'w' => $i,
                'selected' => ($i == $weight) ? ' selected="selected"' : ''
            ));

            $xtpl->parse('main.installed_loop.weight');
        }

        if (defined('NV_IS_GODADMIN') or ($global_config['idsite'] > 0 and defined('NV_IS_SPADMIN')) and $setup == 1) {
            if (!in_array($keylang, $global_config['allow_sitelangs'])) {
                $xtpl->assign('DELETE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;deletekeylang=' . $keylang . '&amp;checksess=' . md5($keylang . NV_CHECK_SESSION . 'deletekeylang'));

                $xtpl->parse('main.installed_loop.setup_delete');
            } else {
                $xtpl->parse('main.installed_loop.setup_note');
            }

            if ($keylang != $global_config['site_lang']) {
                $selected_yes = $selected_no = ' ';

                if (in_array($keylang, $global_config['allow_sitelangs'])) {
                    $selected_yes = ' selected="selected"';
                } else {
                    $selected_no = ' selected="selected"';
                }

                $xtpl->assign('ALLOW_SITELANGS', array(
                    'selected_yes' => $selected_yes,
                    'selected_no' => $selected_no,
                    'url_yes' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;activelang=1&amp;checksess=' . md5('activelang_' . $keylang . NV_CHECK_SESSION),
                    'url_no' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;activelang=0&amp;checksess=' . md5('activelang_' . $keylang . NV_CHECK_SESSION)
                ));

                $xtpl->parse('main.installed_loop.allow_sitelangs');
            } else {
                $xtpl->parse('main.installed_loop.allow_sitelangs_note');
            }
        }

        $xtpl->parse('main.installed_loop');
    }
}

$lang_can_install = false;
foreach ($lang_array_exit as $keylang) {
    if (!isset($array_lang_installed[$keylang])) {
        $lang_can_install = true;

        $xtpl->assign('ROW', array(
            'keylang' => $keylang,
            'name' => $language_array[$keylang]['name']
        ));

        if (defined('NV_IS_GODADMIN') or ($global_config['idsite'] > 0 and defined('NV_IS_SPADMIN'))) {
            $xtpl->assign('INSTALL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;keylang=' . $keylang . '&amp;checksess=' . md5($keylang . NV_CHECK_SESSION));
            $xtpl->parse('main.can_install.loop.setup_new');
        }
        $xtpl->parse('main.can_install.loop');
    }
}

if ($lang_can_install) {
    $xtpl->parse('main.can_install');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';