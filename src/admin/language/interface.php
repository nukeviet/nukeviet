<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_LANG')) {
    exit('Stop!!!');
}

$dirlang_old = $nv_Request->get_string('dirlang', 'cookie', NV_LANG_DATA);
$dirlang = $nv_Request->get_string('dirlang', 'get', $dirlang_old);

$page_title = $nv_Lang->getModule('nv_lang_interface') . ': ' . $language_array[$dirlang]['name'];

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$array_lang_exit = [];

$columns_array = $db->columns_array(NV_LANGUAGE_GLOBALTABLE . '_file');
foreach ($columns_array as $row) {
    if (substr($row['field'], 0, 7) == 'author_') {
        $array_lang_exit[] .= trim(substr($row['field'], 7, 2));
    }
}

if (empty($array_lang_exit) or !in_array($dirlang, $array_lang_exit, true)) {
    $tpl->assign('IS_EMPTY', true);
    $tpl->assign('URL_REDIRECT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=read&dirlang=' . $dirlang . '&checksess=' . md5('readallfile' . NV_CHECK_SESSION));

    $contents = $tpl->fetch('interface.tpl');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$select_options = [];
foreach ($array_lang_exit as $langkey) {
    $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;dirlang=' . $langkey] = $language_array[$langkey]['name'];
}

if (!in_array($dirlang, $array_lang_exit, true)) {
    $dirlang = $global_config['site_lang'];
}

if ($dirlang_old != $dirlang) {
    $nv_Request->set_Cookie('dirlang', $dirlang, NV_LIVE_COOKIE_TIME);
}

$a = 0;
$array = [];

$sql = 'SELECT idfile, module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file ORDER BY idfile ASC';
$result = $db->query($sql);
while (list($idfile, $module, $admin_file, $langtype, $author_lang) = $result->fetch(3)) {
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

    if (empty($author_lang)) {
        $array_translator = [];
        $array_translator['author'] = '';
        $array_translator['createdate'] = '';
        $array_translator['copyright'] = '';
        $array_translator['info'] = '';
        $array_translator['langtype'] = '';
    } else {
        $array_translator = unserialize($author_lang);
    }

    $array[] = [
        'stt' => ++$a,
        'module' => preg_replace('/^theme\_(.*?)$/', 'Theme: \\1', $module),
        'langsitename' => $langsitename,
        'author' => nv_htmlspecialchars($array_translator['author']),
        'createdate' => $array_translator['createdate'],
        'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;dirlang=' . $dirlang . '&amp;idfile=' . $idfile . '&amp;checksess=' . md5($idfile . NV_CHECK_SESSION),
        'url_export' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=write&amp;dirlang=' . $dirlang . '&amp;idfile=' . $idfile . '&amp;checksess=' . md5($idfile . NV_CHECK_SESSION),
        'allow_write' => in_array('write', $allow_func, true)
    ];
}

$tpl->assign('ARRAY', $array);
$contents = $tpl->fetch('interface.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
