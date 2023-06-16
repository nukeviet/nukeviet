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

$select_options = [];
$contents = '';

$xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

$dirlang = $nv_Request->get_title('dirlang', 'get', '');
if (empty($dirlang) or !isset($language_array[$dirlang])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface');
}
$idfile = $nv_Request->get_int('idfile', 'get', 0);
$module = '';
if (!empty($idfile)) {
    list($idfile, $module, $admin_file, $langtype, $author_lang) = $db->query('SELECT idfile, module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile =' . $idfile)->fetch(3);
}
if (empty($idfile) or empty($module)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface');
}

if ($nv_Request->get_string('savedata', 'get') == NV_CHECK_SESSION) {
    $postdata = @file_get_contents('php://input');
    $postdata = json_decode($postdata, true);

    if (empty($postdata['pozauthor']['author'])) {
        $postdata['pozauthor']['author'] = 'VINADES.,JSC <contact@vinades.vn>';
    }

    $postdata['pozauthor']['createdate'] = !empty($postdata['pozauthor']['createdate']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['createdate'])) : date('d/m/Y, H:i');
    $postdata['pozauthor']['copyright'] = !empty($postdata['pozauthor']['copyright']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['copyright'])) : '@Copyright (C) ' . date('Y') . ' VINADES.,JSC. All rights reserved';
    $postdata['pozauthor']['info'] = !empty($postdata['pozauthor']['info']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['info'])) : '';
    $postdata['pozauthor']['langtype'] = !empty($postdata['pozauthor']['langtype']) ? strip_tags($postdata['pozauthor']['langtype']) : 'lang_module';
    $author = serialize($postdata['pozauthor']);

    $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET author_' . $dirlang . '= :author WHERE idfile = ' . $idfile);
    $sth->bindParam(':author', $author, PDO::PARAM_STR);
    $sth->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_edit') . ' -> ' . $language_array[$dirlang]['name'], $module . ' : idfile = ' . $idfile, $admin_info['userid']);

    $weight = 0;
    $langkeys = [];
    $sth = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_key = :lang_key, weight = :weight, lang_' . $dirlang . ' = :lang_value, update_' . $dirlang . ' = ' . NV_CURRENTTIME . '  WHERE id= :id');
    $sth2 = $db->prepare('INSERT INTO ' . NV_LANGUAGE_GLOBALTABLE . ' (idfile, langtype, lang_key, weight, lang_' . $dirlang . ', update_' . $dirlang . ') VALUES (' . $idfile . ', :langtype, :lang_key, :weight, :lang_value, ' . NV_CURRENTTIME . ')');
    foreach ($postdata['ids'] as $key => $id) {
        $postdata['values'][$key] = trim(strip_tags(str_replace(['&amp;','“', '”'], ['&', '&ldquo;', '&rdquo;'], str_replace(['&lt;', '&gt;'], ['<', '>'], $postdata['values'][$key])), NV_ALLOWED_HTML_LANG));
        if ($id > 0) {
            if ($postdata['isdels'][$key]) {
                $db->query('DELETE FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE id = ' . $id);
            } else {
                if (preg_match('/^[a-zA-Z0-9\_]{1,50}$/', $postdata['keys'][$key]) and !in_array($postdata['keys'][$key], $langkeys, true)) {
                    ++$weight;
                    $sth->bindParam(':lang_key', $postdata['keys'][$key], PDO::PARAM_STR);
                    $sth->bindParam(':weight', $weight, PDO::PARAM_INT);
                    $sth->bindParam(':lang_value', $postdata['values'][$key], PDO::PARAM_STR);
                    $sth->bindParam(':id', $id, PDO::PARAM_INT);
                    $sth->execute();
                    $langkeys[] = $postdata['keys'][$key];
                }
            }
        } else {
            if (preg_match('/^[a-zA-Z0-9\_]{1,50}$/', $postdata['keys'][$key]) and !in_array($postdata['keys'][$key], $langkeys, true)) {
                ++$weight;
                $sth2->bindParam(':langtype', $postdata['pozauthor']['langtype'], PDO::PARAM_STR);
                $sth2->bindParam(':lang_key', $postdata['keys'][$key], PDO::PARAM_STR);
                $sth2->bindParam(':weight', $weight, PDO::PARAM_INT);
                $sth2->bindParam(':lang_value', $postdata['values'][$key], PDO::PARAM_STR);
                $sth2->execute();
            }
        }
    }

    if (in_array('write', $allow_func, true)) {
        if ($nv_Request->isset_request('write', 'get')) {
            $include_lang = '';
            nv_mkdir(NV_ROOTDIR . '/includes/language/', $dirlang);
            $content = nv_admin_write_lang($dirlang, $idfile);
            //Resets the contents of the opcode cache
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            if (!empty($content)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $content
                ]);
            } else {
                nv_jsonOutput([
                    'status' => 'OK',
                    'mess' => $nv_Lang->getModule('nv_lang_wite_ok') . ': ' . str_replace(NV_ROOTDIR, '', str_replace('\\', '/', $include_lang)),
                    'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface&dirlang=' . $dirlang
                ]);
            }
        }
    }

    nv_jsonOutput([
        'status' => 'OK',
        'redirect' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface&dirlang=' . $dirlang
    ]);
}

if (!$nv_Request->isset_request('checksess', 'get') or $nv_Request->get_string('checksess', 'get') != md5($idfile . NV_CHECK_SESSION)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface');
}

$page_title = $nv_Lang->getModule('nv_admin_edit') . ': ' . $language_array[$dirlang]['name'];

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

$modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);

$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;dirlang=' . $dirlang . '&amp;idfile=' . $idfile);
$xtpl->assign('EDIT_MODULE', $module);
if ($admin_file == '1') {
    $xtpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_admin'));
} elseif ($admin_file == '0') {
    if (in_array($module, $modules_exit, true) or preg_match('/^theme\_(.*?)$/', $module)) {
        $xtpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_whole_site'));
    } else {
        $xtpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_site'));
    }
} else {
    $xtpl->assign('MODULE_AREA', $admin_file);
}
$xtpl->assign('LANGTYPE', $array_translator['langtype']);

foreach ($array_translator as $lang_key => $lang_value) {
    if ($lang_key != 'langtype') {
        $xtpl->assign('ARRAY_TRANSLATOR', [
            'lang_key' => $lang_key,
            'value' => nv_htmlspecialchars($lang_value)
        ]);

        $xtpl->parse('main.array_translator');
    }
}

$sql = 'SELECT id, lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile=' . $idfile . ' ORDER BY weight ASC';
$result = $db->query($sql);
while (list($id, $lang_key, $lang_value) = $result->fetch(3)) {
    $xtpl->assign('ARRAY_DATA', [
        'lang_key' => $lang_key,
        'value' => !empty($lang_value) ? str_replace(['&lt;', '&gt;', '&quot;', '<', '>', '"', "'"], ['&amp;lt;', '&amp;gt;', '&amp;quot;', '&lt;', '&gt;', '&quot;', '&#039;'], $lang_value) : '',
        'id' => $id
    ]);

    $xtpl->parse('main.array_data');
}

if (in_array('write', $allow_func, true)) {
    $xtpl->parse('main.write');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
