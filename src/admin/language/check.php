<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$page_title = $nv_Lang->getModule('nv_lang_check');

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('check.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$language_array_source = ['vi', 'en'];
$array_lang_exit = [];
$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $array_lang_exit[] = trim(substr($row['field'], 7, 2));
    }
}

if (!(count($array_lang_exit) > 1 and (in_array('en', $array_lang_exit, true) or in_array('vi', $array_lang_exit, true)))) {
    $tpl->assign('LANG_EMPTY', $nv_Lang->getModule('nv_lang_error_exit', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setting'));
    $contents = $tpl->fetch('check.tpl');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}
$tpl->assign('LANG_EMPTY', '');

$typelang = $nv_Request->get_title('typelang', 'post,get', '');
$tpl->assign('TYPELANG', $typelang);

if (!empty($typelang) and $nv_Request->isset_request('savedata', 'post') and $nv_Request->get_string('savedata', 'post') == NV_CHECK_SESSION) {
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

$modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);
$sql = 'SELECT idfile, module, admin_file FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC';
$result = $db->query($sql);

$array_files = [];
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
    $array_files[$idfile_i] = $module . ' ' . $langsitename;
}

$tpl->assign('LANGUAGE_ARRAY', $language_array);
$tpl->assign('LANGUAGE_ARRAY_SOURCE', $language_array_source);
$tpl->assign('LANG_EXIT', $array_lang_exit);
$tpl->assign('SOURCELANG', $sourcelang);
$tpl->assign('CHECK_TYPE', $check_type);
$tpl->assign('ARRAY_FILES', $array_files);
$tpl->assign('IDFILE', $idfile);

$array_lang_data = [];
$is_submit = 0;

if ($nv_Request->isset_request('save', 'post,get') and in_array($sourcelang, $array_lang_exit, true) and in_array($typelang, $array_lang_exit, true)) {
    $array_where = [];
    $is_submit = 1;
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

    while ([$id, $idfile_i, $lang_key, $datalang, $datasourcelang] = $result->fetch(3)) {
        $array_lang_data[$idfile_i][$id] = [
            'lang_key' => $lang_key,
            'datalang' => $datalang,
            'sourcelang' => $datasourcelang
        ];
    }
}

$tpl->assign('ARRAY_LANG_DATA', $array_lang_data);
$tpl->assign('IS_SUBMIT', $is_submit);

$contents = $tpl->fetch('check.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
