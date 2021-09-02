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

/**
 * nv_admin_write_lang()
 *
 * @param string $dirlang
 * @param int    $idfile
 * @return string
 */
function nv_admin_write_lang($dirlang, $idfile)
{
    global $db, $language_array, $global_config, $include_lang, $lang_module;

    list($module, $admin_file, $langtype, $author_lang) = $db->query('SELECT module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile =' . (int) $idfile)->fetch(3);

    if (!empty($dirlang) and !empty($module)) {
        if (empty($author_lang)) {
            $array_translator = [];
            $array_translator['author'] = '';
            $array_translator['createdate'] = '';
            $array_translator['copyright'] = '';
            $array_translator['info'] = '';
            $array_translator['langtype'] = $langtype;
        } else {
            $array_translator = unserialize($author_lang);
        }

        $include_lang = '';

        $modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);

        if (preg_match('/^theme\_(.*?)$/', $module, $m)) {
            if ($admin_file == 1) {
                // Ngôn ngữ admin của giao diện
                $include_lang = NV_ROOTDIR . '/themes/' . $m[1] . '/language/admin_' . $dirlang . '.php';
            } else {
                // Ngôn ngữ ngoài site của giao diện
                $include_lang = NV_ROOTDIR . '/themes/' . $m[1] . '/language/' . $dirlang . '.php';
            }
        } elseif (in_array($module, $modules_exit, true) and preg_match('/^block\.(global|module)\.([a-zA-Z0-9\-\_]+)$/', $admin_file)) {
            $include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $admin_file . '_' . $dirlang . '.php';
        } elseif (in_array($module, $modules_exit, true) and $admin_file == 1) {
            $include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $dirlang . '.php';
        } elseif (in_array($module, $modules_exit, true) and $admin_file == 0) {
            $include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php';
        } elseif ($module == 'global' and $admin_file == 1) {
            $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/admin_' . $module . '.php';
        } elseif ($module == 'global' and $admin_file == 0) {
            $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/' . $module . '.php';
        } elseif ($module == 'install' and $admin_file == 0) {
            $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/' . $module . '.php';
        } else {
            $admin_file = 1;
            $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/admin_' . $module . '.php';
        }

        if ($include_lang == '') {
            return $lang_module['nv_error_write_module'] . ' : ' . $module;
        }

        $content_lang = "<?php\n\n";
        $content_lang .= NV_FILEHEAD . "\n\n";

        if ($langtype != 'lang_theme') {
            if ($admin_file) {
                $content_lang .= "\nif (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {";
            } else {
                $content_lang .= "\nif (!defined('NV_MAINFILE')) {";
            }

            $content_lang .= "\n    die('Stop!!!');\n}\n\n";

            $array_translator['info'] = (isset($array_translator['info'])) ? $array_translator['info'] : '';

            $content_lang .= "\$lang_translator['author'] = '" . $array_translator['author'] . "';\n";
            $content_lang .= "\$lang_translator['createdate'] = '" . $array_translator['createdate'] . "';\n";
            $content_lang .= "\$lang_translator['copyright'] = '" . $array_translator['copyright'] . "';\n";
            $content_lang .= "\$lang_translator['info'] = '" . $array_translator['info'] . "';\n";
            $content_lang .= "\$lang_translator['langtype'] = '" . $array_translator['langtype'] . "';\n";
            $content_lang .= "\n";
        } else {
            $content_lang .= "\n";
        }

        $numrows = 0;
        $current_langtype = '';
        $result = $db->query('SELECT langtype, lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY langtype ASC, id ASC');
        while (list($langtype_row, $lang_key, $lang_value) = $result->fetch(3)) {
            ++$numrows;
            $lang_value = nv_unhtmlspecialchars($lang_value);
            $lang_value = str_replace("\'", "'", $lang_value);
            $lang_value = str_replace("'", "\'", $lang_value);
            $lang_value = nv_nl2br($lang_value);
            $lang_value = str_replace('<br />', '<br />', $lang_value);
            if ($current_langtype != '' and $current_langtype != $langtype_row) {
                $content_lang .= "\n";
            }
            $content_lang .= '$' . $langtype_row . "['" . $lang_key . "'] = '" . $lang_value . "';\n";
            $current_langtype = $langtype_row;
        }

        if ($numrows) {
            $number_bytes = file_put_contents($include_lang, trim($content_lang), LOCK_EX);
            if (empty($number_bytes)) {
                $errfile = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $include_lang));

                return $lang_module['nv_error_write_file'] . ' : ' . $errfile;
            }
        }

        return '';
    }

    return $lang_module['nv_error_exit_module'] . ' : ' . $module;
}

$xtpl = new XTemplate('write.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

$include_lang = '';
$page_title = $language_array[$dirlang]['name'];

if ($nv_Request->isset_request('idfile,checksess', 'get') and $nv_Request->get_string('checksess', 'get') == md5($nv_Request->get_int('idfile', 'get') . NV_CHECK_SESSION)) {
    $idfile = $nv_Request->get_int('idfile', 'get');
    nv_mkdir(NV_ROOTDIR . '/includes/language/', $dirlang);
    $content = nv_admin_write_lang($dirlang, $idfile);

    //Resets the contents of the opcode cache
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }

    if (empty($content)) {
        $xtpl->assign('INCLUDE_LANG', str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $include_lang)));
        $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=interface');

        $xtpl->parse('main.complete');
    } else {
        $xtpl->assign('CONTENT', $content);

        $xtpl->parse('main.error');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($nv_Request->isset_request('checksess', 'get') and $nv_Request->get_string('checksess', 'get') == md5('writeallfile' . NV_CHECK_SESSION)) {
    $dirlang = $nv_Request->get_string('dirlang', 'get', '');

    if ($dirlang != '' and preg_match('/^([a-z]{2})$/', $dirlang)) {
        nv_mkdir(NV_ROOTDIR . '/includes/language/', $dirlang);

        $content = '';
        $array_filename = [];

        $result = $db->query('SELECT idfile, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC');
        while (list($idfile, $author_lang) = $result->fetch(3)) {
            $content = nv_admin_write_lang($dirlang, $idfile);

            if (!empty($content)) {
                break;
            }
            $array_filename[] = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $include_lang));
        }

        if (empty($content)) {
            $xtpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting');

            $i = 0;
            foreach ($array_filename as $name) {
                $xtpl->assign('NAME', $name);
                $xtpl->parse('main.write_allfile_complete.loop');
            }

            $xtpl->parse('main.write_allfile_complete');
        } else {
            $xtpl->parse('main.error_write_allfile');
        }
    } else {
        $xtpl->parse('main.error_write_allfile');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main&dirlang=' . $dirlang);
}
