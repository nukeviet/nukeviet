<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_lang_check');

$xtpl = new XTemplate('check.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$language_array_source = ['vi', 'en'];
$array_lang_exit = [];
$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $array_lang_exit[] = trim(substr($row['field'], 7, 2));
    }
}

if (!(sizeof($array_lang_exit) > 1 and (in_array('en', $array_lang_exit, true) or in_array('vi', $array_lang_exit, true)))) {
    $xtpl->assign('LANG_EMPTY', $nv_Lang->getModule('nv_lang_error_exit', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting'));
    $xtpl->parse('empty');
    $contents = $xtpl->text('empty');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$typelang = $nv_Request->get_title('typelang', 'post,get', '');
if (empty($typelang)) {
    $xtpl->parse('main.disabled');
} elseif ($nv_Request->isset_request('savedata', 'post') and $nv_Request->get_string('savedata', 'post') == NV_CHECK_SESSION) {
    $pozlang = $nv_Request->get_array('pozlang', 'post', []);

    if (!empty($pozlang) and isset($language_array[$typelang])) {
        foreach ($pozlang as $id => $lang_value) {
            $lang_value = trim(strip_tags(str_replace(['&amp;', '“', '”'], ['&', '&ldquo;', '&rdquo;'], str_replace(['&lt;', '&gt;'], ['<', '>'], $lang_value)), NV_ALLOWED_HTML_LANG));
            if (!empty($lang_value)) {
                $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $typelang . '= :lang_value, update_' . $typelang . '= ' . NV_CURRENTTIME . ' WHERE id= :id');
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->bindParam(':lang_value', $lang_value, PDO::PARAM_STR);
                $sth->execute();
            }
        }
    }
}

$sourcelang = $nv_Request->get_title('sourcelang', 'post,get', 'vi');
$idfile = $nv_Request->get_int('idfile', 'post,get', 0);
$check_type = $nv_Request->get_int('check_type', 'post,get', 0);
$language_check_type = [
    0 => $nv_Lang->getModule('nv_check_type_0'),
    1 => $nv_Lang->getModule('nv_check_type_1'),
    2 => $nv_Lang->getModule('nv_check_type_2')
];

$array_files = [];

foreach ($language_array as $key => $value) {
    if (in_array($key, $array_lang_exit, true)) {
        $xtpl->assign('LANGUAGE', [
            'key' => $key,
            'selected' => ($key == $typelang) ? ' selected="selected"' : '',
            'disabled' => ($key == $sourcelang) ? ' disabled="disabled"' : '',
            'title' => $value['name']
        ]);

        $xtpl->parse('main.language');
    }
}

foreach ($language_array_source as $key) {
    if (in_array($key, $array_lang_exit, true)) {
        $xtpl->assign('LANGUAGE_SOURCE', [
            'key' => $key,
            'selected' => ($key == $sourcelang) ? ' selected="selected"' : '',
            'title' => $language_array[$key]['name']
        ]);

        $xtpl->parse('main.language_source');
    }
}

$modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
$sql = 'SELECT idfile, module, admin_file FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC';
$result = $db->query($sql);
while ([$idfile_i, $module, $admin_file] = $result->fetch(3)) {
    $module = preg_replace('/^theme\_(.*?)$/', 'Theme: \\1', $module);
    switch ($admin_file) {
        case '1':
            $langsitename = $nv_Lang->getModule('nv_lang_admin');
            break;
        case '0':
            if (in_array($module, $modules_exit, true) or preg_match('/^theme\_(.*?)$/', $module)) {
                $langsitename = $nv_Lang->getModule('nv_lang_whole_site');
            } else {
                $langsitename = $nv_Lang->getModule('nv_lang_site');
            }
            break;
        default:
            $langsitename = $admin_file;
            break;
    }

    $xtpl->assign('LANGUAGE_AREA', [
        'key' => $idfile_i,
        'selected' => ($idfile_i == $idfile) ? ' selected="selected"' : '',
        'title' => $module . ' ' . $langsitename
    ]);

    $xtpl->parse('main.language_area');
    $array_files[$idfile_i] = $module . ' ' . $langsitename;
}

foreach ($language_check_type as $key => $value) {
    $xtpl->assign('LANGUAGE_CHECK_TYPE', [
        'key' => $key,
        'selected' => ($key == $check_type) ? ' selected="selected"' : '',
        'title' => $value
    ]);

    $xtpl->parse('main.language_check_type');
}

if ($nv_Request->isset_request('save', 'post,get') and in_array($sourcelang, $array_lang_exit, true) and in_array($typelang, $array_lang_exit, true)) {
    $array_where = [];
    if ($idfile > 0) {
        $array_where[] = 'idfile=' . $idfile;
    }

    if ($check_type == 0) {
        $array_where[] = 'update_' . $typelang . '=0';
    } elseif ($check_type == 1) {
        $array_where[] = 'lang_' . $typelang . '=lang_' . $sourcelang;
    }

    if (empty($array_where)) {
        $query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' ORDER BY id ASC';
    } else {
        $query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE ' . implode(' AND ', $array_where) . ' ORDER BY id ASC';
    }
    $result = $db->query($query);

    $array_lang_data = [];

    while ([$id, $idfile_i, $lang_key, $datalang, $datasourcelang] = $result->fetch(3)) {
        $array_lang_data[$idfile_i][$id] = [
            'lang_key' => $lang_key,
            'datalang' => $datalang,
            'sourcelang' => $datasourcelang
        ];
    }

    if (!empty($array_lang_data)) {
        $xtpl->assign('DATA', [
            'typelang' => $typelang,
            'sourcelang' => $sourcelang,
            'check_type' => $check_type,
            'idfile' => $idfile,
            'savedata' => NV_CHECK_SESSION
        ]);

        $i = 0;
        foreach ($array_lang_data as $idfile_i => $array_lang_file) {
            $xtpl->assign('CAPTION', $array_files[$idfile_i]);

            foreach ($array_lang_file as $id => $row) {
                $xtpl->assign('ROW', [
                    'stt' => ++$i,
                    'lang_key' => $row['lang_key'],
                    'datalang' => !empty($row['datalang']) ? str_replace(['&lt;', '&gt;', '<', '>', '"', "'"], ['&amp;lt;', '&amp;gt;', '&lt;', '&gt;', '&quot;', '&#039;'], $row['datalang']) : '',
                    'id' => $id,
                    'sourcelang' => !empty($row['sourcelang']) ? str_replace(['&lt;', '&gt;', '<', '>', '"', "'"], ['&amp;lt;', '&amp;gt;', '&lt;', '&gt;', '&quot;', '&#039;'], $row['sourcelang']) : ''
                ]);

                $xtpl->parse('main.data.lang.loop');
            }

            $xtpl->parse('main.data.lang');
        }

        $xtpl->parse('main.data');
    } else {
        $xtpl->parse('main.nodata');
    }

    unset($array_lang_data, $array_files);
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
