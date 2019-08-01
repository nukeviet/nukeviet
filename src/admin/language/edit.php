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

$select_options = [];
$contents = '';

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);

$tpl->registerPlugin('modifier', 'htmlspecialchars', 'nv_htmlspecialchars');

$dirlang = $nv_Request->get_title('dirlang', 'post, get', '');
if (isset($language_array[$dirlang]) and isset($language_array[$dirlang]) and $nv_Request->isset_request('idfile,savedata', 'post') and $nv_Request->get_string('savedata', 'post') == NV_CHECK_SESSION) {
    $numberfile = 0;

    $idfile = $nv_Request->get_int('idfile', 'post', 0);

    $authorSubmit = isset($_POST['pozauthor']['author']) ? $_POST['pozauthor']['author'] : '';
    if (preg_match('/^([^\<]+)\<([^\>]+)\>$/', $authorSubmit, $m) and nv_check_valid_email(trim($m[2])) == '') {
        $authorSubmit = trim(strip_tags($m[1])) . ' <' . trim($m[2]) . '>';
    } else {
        $authorSubmit = false;
    }

    $lang_translator = $nv_Request->get_array('pozauthor', 'post', []);
    $lang_translator_save = [];

    $langtype = isset($lang_translator['langtype']) ? strip_tags($lang_translator['langtype']) : 'lang_module';

    if ($authorSubmit === false) {
        $lang_translator_save['author'] = isset($lang_translator['author']) ? nv_htmlspecialchars(strip_tags($lang_translator['author'])) : 'VINADES.,JSC <contact@vinades.vn>';
    } else {
        $lang_translator_save['author'] = $authorSubmit;
    }
    $lang_translator_save['createdate'] = isset($lang_translator['createdate']) ? nv_unhtmlspecialchars(strip_tags($lang_translator['createdate'])) : date('d/m/Y, H:i');
    $lang_translator_save['copyright'] = isset($lang_translator['copyright']) ? nv_htmlspecialchars(strip_tags($lang_translator['copyright'])) : '@Copyright (C) ' . date('Y') . ' VINADES.,JSC. All rights reserved';
    $lang_translator_save['info'] = isset($lang_translator['info']) ? nv_htmlspecialchars(strip_tags($lang_translator['info'])) : '';
    $lang_translator_save['langtype'] = $langtype;

    $author = serialize($lang_translator_save);

    $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET author_' . $dirlang . '= :author WHERE idfile= :idfile');
    $sth->bindParam(':idfile', $idfile, PDO::PARAM_INT);
    $sth->bindParam(':author', $author, PDO::PARAM_STR);
    $sth->execute();

    $module = $db->query('SELECT module FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile = ' . $idfile)->fetchColumn();

    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_edit') . ' -> ' . $language_array[$dirlang]['name'], $module . ' : idfile = ' . $idfile, $admin_info['userid']);

    $pozlang = $nv_Request->get_array('pozlang', 'post', []);

    if (!empty($pozlang)) {
        $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_' . $dirlang . '= :lang_value WHERE id= :id');
        foreach ($pozlang as $id => $lang_value) {
            $lang_value = trim(strip_tags($lang_value, NV_ALLOWED_HTML_LANG));
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':lang_value', $lang_value, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    $pozlangkey = $nv_Request->get_array('pozlangkey', 'post', []);
    $pozlangval = $nv_Request->get_array('pozlangval', 'post', []);

    $sizeof = sizeof($pozlangkey);
    $sth = $db->prepare('INSERT INTO ' . NV_LANGUAGE_GLOBALTABLE . ' (idfile, lang_key, lang_' . $dirlang . ') VALUES (' . $idfile . ', :lang_key, :lang_value)');
    for ($i = 1; $i <= $sizeof; ++$i) {
        $lang_key = strip_tags($pozlangkey[$i]);
        $lang_value = strip_tags($pozlangval[$i], NV_ALLOWED_HTML_LANG);

        if ($lang_key != '' and $lang_value != '') {
            $lang_value = nv_nl2br($lang_value);
            $lang_value = str_replace('<br />', '<br />', $lang_value);

            $sth->bindParam(':lang_key', $lang_key, PDO::PARAM_STR);
            $sth->bindParam(':lang_value', $lang_value, PDO::PARAM_STR);
            $sth->execute();
        }
    }

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface&dirlang=' . $dirlang);
}

$page_title = $nv_Lang->getModule('nv_admin_edit') . ': ' . $language_array[$dirlang]['name'];

if ($nv_Request->isset_request('idfile,checksess', 'get') and $nv_Request->get_string('checksess', 'get') == md5($nv_Request->get_int('idfile', 'get') . NV_CHECK_SESSION)) {
    $idfile = $nv_Request->get_int('idfile', 'get');

    list ($idfile, $module, $admin_file, $langtype, $author_lang) = $db->query('SELECT idfile, module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile =' . $idfile)->fetch(3);

    if (!empty($dirlang) and !empty($module)) {
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

        $tpl->assign('ALLOWED_HTML_LANG', ALLOWED_HTML_LANG);
        $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
        $tpl->assign('MODULE_NAME', $module_name);
        $tpl->assign('OP', $op);
        $tpl->assign('LANGTYPE', $array_translator['langtype']);
        $tpl->assign('ARRAY_TRANSLATOR', $array_translator);

        $sql = 'SELECT id, lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY id ASC';
        $result = $db->query($sql);
        $array_data = [];
        $a = 3;
        while (list ($id, $lang_key, $lang_value) = $result->fetch(3)) {
            $array_data[] = [
                'key' => $a++,
                'lang_key' => $lang_key,
                'value' => nv_htmlspecialchars($lang_value),
                'id' => $id
            ];
        }

        $tpl->assign('ARRAY_DATA', $array_data);
        $tpl->assign('IDFILE', $idfile);
        $tpl->assign('DIRLANG', $dirlang);
        $tpl->assign('NV_CHECK_SESSION', NV_CHECK_SESSION);

        $contents .= $tpl->fetch('edit.tpl');
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
