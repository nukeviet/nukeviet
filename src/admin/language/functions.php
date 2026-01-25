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

unset($page_title, $select_options);
$select_options = [];

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_language')
];

$allow_func = ['main', 'region'];
if (empty($global_config['idsite'])) {
    $allow_func[] = 'read';
    $allow_func[] = 'copy';
    $allow_func[] = 'edit';
    $allow_func[] = 'download';
    $allow_func[] = 'interface';
    $allow_func[] = 'check';
    $allow_func[] = 'countries';
    if (defined('NV_IS_GODADMIN')) {
        $allow_func[] = 'setting';
        $allow_func[] = 'write';
        $allow_func[] = 'delete';
    }
}

if (!isset($global_config['site_description'])) {
    $global_config['site_description'] = '';
    $global_config['cronjobs_next_time'] = NV_CURRENTTIME;
}

define('ALLOWED_HTML_LANG', 'a, b, blockquote, br, em, h1, h2, h3, h4, h5, h6, hr, p, span, strong, ul, li');

$allowed_html_tags = array_map('trim', explode(',', ALLOWED_HTML_LANG));
$allowed_html_tags = '<' . implode('><', $allowed_html_tags) . '>';

define('NV_ALLOWED_HTML_LANG', $allowed_html_tags);
define('NV_IS_FILE_LANG', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:language';
$array_url_instruction['countries'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:language:countries';
$array_url_instruction['interface'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:language:interface';
$array_url_instruction['check'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:language:check';
$array_url_instruction['setting'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:language:setting';

$dirlang = $nv_Request->get_title('dirlang', 'get', '');

/**
 * nv_admin_add_field_lang()
 *
 * @param mixed $dirlang
 */
function nv_admin_add_field_lang($dirlang)
{
    global $db, $language_array;

    if (isset($language_array[$dirlang]) and !empty($language_array[$dirlang])) {
        $add_field = true;

        $columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
        foreach ($columns_array as $row) {
            if ($row['field'] == 'author_' . $dirlang) {
                $add_field = false;
                break;
            }
        }

        if ($add_field == true) {
            $db->columns_add(NV_LANGUAGE_GLOBALTABLE, 'lang_' . $dirlang, 'string', 4000, true);
            $db->columns_add(NV_LANGUAGE_GLOBALTABLE, 'update_' . $dirlang, 'integer', 2147483647, true, 0);
            $db->columns_add(NV_LANGUAGE_GLOBALTABLE . '_file', 'author_' . $dirlang, 'string', 4000, true);
        }
    }
}

/**
 * nv_update_config_allow_sitelangs()
 *
 * @param mixed $allow_sitelangs
 */
function nv_update_config_allow_sitelangs($allow_sitelangs = [])
{
    global $global_config, $db_config, $db;

    if (defined('NV_IS_GODADMIN') or ($global_config['idsite'] > 0 and defined('NV_IS_SPADMIN'))) {
        if (empty($allow_sitelangs)) {
            $allow_sitelangs = $global_config['allow_sitelangs'];
        }

        $sql = 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language ORDER BY weight ASC';
        $result = $db->query($sql);

        $sitelangs = [];
        while ($row = $result->fetch()) {
            if (in_array($row['lang'], $allow_sitelangs, true)) {
                $sitelangs[] = $row['lang'];
            }
        }

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang='sys' AND module = 'global' AND config_name = 'allow_sitelangs'");
        $sth->bindValue(':config_value', implode(',', $sitelangs), PDO::PARAM_STR);
        $sth->execute();
    }
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
    global $db, $language_array, $global_config, $include_lang, $nv_Lang;

    [$module, $admin_file, $langtype, $author_lang] = $db->query('SELECT module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile =' . (int) $idfile)->fetch(3);

    if (empty($dirlang) or empty($module)) {
        return $nv_Lang->getModule('nv_error_exit_module') . ' : ' . $module;
    }

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
        // Ngôn ngữ của giao diện
        $include_lang = NV_ROOTDIR . '/themes/' . $m[1] . '/language/' . $dirlang . '.php';
    } elseif (in_array($module, $modules_exit, true) and $admin_file == 0) {
        // Ngôn ngữ các module ngoài site
        $include_lang = NV_ROOTDIR . '/modules/' . $module . '/language/' . $dirlang . '.php';
    } elseif ($module == 'global' and $admin_file == 1) {
        // Ngôn ngữ global admin
        $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/admin_' . $module . '.php';
    } elseif ($module == 'global' and $admin_file == 0) {
        // Ngôn ngữ global site
        $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/' . $module . '.php';
    } elseif ($module == 'install' and $admin_file == 0) {
        // Ngôn ngữ cài đặt
        $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/' . $module . '.php';
    } else {
        // Ngôn ngữ module admin
        $admin_file = 1;
        $include_lang = NV_ROOTDIR . '/includes/language/' . $dirlang . '/admin_' . $module . '.php';
    }

    if ($include_lang == '') {
        return $nv_Lang->getModule('nv_error_write_module') . ' : ' . $module;
    }

    $content_lang = "<?php\n\n";
    $content_lang .= NV_FILEHEAD . "\n\n";

    if ($langtype != 'lang_theme') {
        $content_lang .= "if (!defined('NV_MAINFILE')) {\n";
        $content_lang .= "    exit('Stop!!!');\n}\n\n";

        empty($array_translator['info']) && $array_translator['info'] = '';

        $content_lang .= "\$lang_translator['author'] = '" . $array_translator['author'] . "';\n";
        $content_lang .= "\$lang_translator['createdate'] = '" . $array_translator['createdate'] . "';\n";
        $content_lang .= "\$lang_translator['copyright'] = '" . $array_translator['copyright'] . "';\n";
        $content_lang .= "\$lang_translator['info'] = '" . $array_translator['info'] . "';\n";
        $content_lang .= "\$lang_translator['langtype'] = '" . $array_translator['langtype'] . "';\n";
        $content_lang .= "\n";
    }

    $numrows = 0;
    $current_langtype = '';
    $result = $db->query('SELECT langtype, lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY langtype ASC, weight ASC');
    while ([$langtype_row, $lang_key, $lang_value] = $result->fetch(3)) {
        ++$numrows;
        $lang_value = str_replace("\'", "'", $lang_value);
        $lang_value = str_replace("'", "\'", $lang_value);
        if ($current_langtype != '' and $current_langtype != $langtype_row) {
            $content_lang .= "\n";
        }
        $content_lang .= '$' . $langtype_row . "['" . $lang_key . "'] = '" . $lang_value . "';\n";
        $current_langtype = $langtype_row;
    }

    if ($numrows) {
        $number_bytes = file_put_contents($include_lang, trim($content_lang) . "\n", LOCK_EX);
        if (empty($number_bytes)) {
            $errfile = str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $include_lang));

            return $nv_Lang->getModule('nv_error_write_file') . ' : ' . $errfile;
        }
    }

    return '';
}

$language_array = nv_parse_ini_file(NV_ROOTDIR . '/includes/ini/langs.ini', true);
