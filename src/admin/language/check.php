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

$page_title = $nv_Lang->getModule('nv_lang_check');

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->registerPlugin('modifier', 'htmlspecialchars', 'nv_htmlspecialchars');

$array_lang_exit = [];

$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');

$add_field = true;
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $array_lang_exit[] .= trim(substr($row['field'], 7, 2));
    }
}

if (empty($array_lang_exit)) {
    $tpl->assign('URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting');
    $contents = $tpl->fetch('check_empty.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$language_array_source = ['vi', 'en'];

$language_check_type = [
    0 => $nv_Lang->getModule('nv_check_type_0'),
    1 => $nv_Lang->getModule('nv_check_type_1'),
    2 => $nv_Lang->getModule('nv_check_type_2')
];

$typelang = $nv_Request->get_title('typelang', 'post,get', '');
$sourcelang = $nv_Request->get_title('sourcelang', 'post,get', '');

$idfile = $nv_Request->get_int('idfile', 'post,get', 0);
$check_type = $nv_Request->get_int('check_type', 'post,get', 0);

if ($nv_Request->isset_request('idfile,savedata', 'post') and $nv_Request->get_string('savedata', 'post') == NV_CHECK_SESSION) {
    $pozlang = $nv_Request->get_array('pozlang', 'post', []);

    if (!empty($pozlang) and isset($language_array[$typelang])) {
        foreach ($pozlang as $id => $lang_value) {
            $lang_value = trim(strip_tags($lang_value, NV_ALLOWED_HTML_LANG));
            if (!empty($lang_value)) {
                $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $typelang . '= :lang_value, update_' . $typelang . '= ' . NV_CURRENTTIME . ' WHERE id= :id');
                $sth->bindParam(':id', $id, PDO::PARAM_INT);
                $sth->bindParam(':lang_value', $lang_value, PDO::PARAM_STR);
                $sth->execute();
            }
        }
    }
}
$array_files = [];

$tpl->assign('ARRAY_LANG_EXIT', $array_lang_exit);
$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('LANGUAGE_ARRAY_SOURCE', $language_array_source);
$tpl->assign('TYPELANG', $typelang);
$tpl->assign('SOURCELANG', $sourcelang);
$tpl->assign('IDFILE', $idfile);
$tpl->assign('CHECK_TYPE', $check_type);
$tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

$language_area = [];
$sql = 'SELECT idfile, module, admin_file FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC';
$result = $db->query($sql);
while (list($idfile_i, $module, $admin_file, ) = $result->fetch(3)) {
    $module = preg_replace('/^theme\_(.*?)$/', 'Theme: \\1', $module);
    switch ($admin_file) {
        case '1':
            $langsitename = $nv_Lang->getModule('nv_lang_admin');
            break;
        case '0':
            $langsitename = $nv_Lang->getModule('nv_lang_site');
            break;
        default:
            $langsitename = $admin_file;
            break;
    }

    $language_area[] = [
        'key' => $idfile_i,
        'title' => $module . " " . $langsitename
    ];
    $array_files[$idfile_i] = $module . " " . $langsitename;
}

$tpl->assign('LANGUAGE_AREA', $language_area);
$tpl->assign('LANGUAGE_CHECK_TYPE', $language_check_type);

$submit = $nv_Request->get_int('submit', 'post,get', 0);

if ($submit > 0 and in_array($sourcelang, $array_lang_exit) and in_array($typelang, $array_lang_exit)) {
    $array_where = [];
    if ($idfile > 0) {
        $array_where[] = 'idfile=' . $idfile;
    }

    if ($check_type == 0) {
        $array_where[] = "update_" . $typelang . "=0";
    } elseif ($check_type == 1) {
        $array_where[] = "lang_" . $typelang . "=lang_" . $sourcelang;
    }

    if (empty($array_where)) {
        $query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' ORDER BY id ASC';
    } else {
        $query = 'SELECT id, idfile, lang_key, lang_' . $typelang . ' as datalang, lang_' . $sourcelang . ' as sourcelang FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE ' . implode(' AND ', $array_where) . ' ORDER BY id ASC';
    }
    $result = $db->query($query);

    $array_lang_data = [];
    while (list($id, $idfile_i, $lang_key, $datalang, $datasourcelang) = $result->fetch(3)) {
        $array_lang_data[$idfile_i][$id] = [
            'lang_key' => $lang_key,
            'datalang' => $datalang,
            'sourcelang' => $datasourcelang
        ];
    }

    $tpl->assign('ARRAY_FILES', $array_files);
    $tpl->assign('ARRAY_LANG_DATA', $array_lang_data);
}

$tpl->assign('IS_SUBMIT', $submit);

$contents = $tpl->fetch('check.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
