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

$select_options = [];
$contents = '';

$dirlang = $nv_Request->get_title('dirlang', 'get', '');
if (empty($dirlang) or !isset($language_array[$dirlang])) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface');
}
$idfile = $nv_Request->get_int('idfile', 'get', 0);
$module = '';
if (!empty($idfile)) {
    $stmt_file = $db->prepare('SELECT idfile, module, admin_file, langtype, author_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . '_file WHERE idfile = :idfile');
    $stmt_file->bindValue(':idfile', $idfile, PDO::PARAM_INT);
    $stmt_file->execute();

    $_row = $stmt_file->fetch();
    $idfile = $_row ? $_row['idfile'] : 0;
    $module = $_row ? $_row['module'] : '';
    $admin_file = $_row ? $_row['admin_file'] : 0;
    $langtype = $_row ? $_row['langtype'] : '';
    $author_lang = $_row ? $_row['author_' . $dirlang] : '';
}
if (empty($idfile) or empty($module)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=interface');
}

if (csrf_check($nv_Request->get_string('savedata', 'get'), $csrf_key)) {
    $postdata = @file_get_contents('php://input');
    $postdata = json_decode($postdata, true);

    if (empty($postdata['pozauthor']['author'])) {
        $postdata['pozauthor']['author'] = 'VINADES.,JSC <contact@vinades.vn>';
    }

    $postdata['pozauthor']['createdate'] = !empty($postdata['pozauthor']['createdate']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['createdate'])) : date('d/m/Y, H:i');
    $postdata['pozauthor']['copyright'] = !empty($postdata['pozauthor']['copyright']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['copyright'])) : '@Copyright (C) ' . date('Y') . ' VINADES.,JSC. All rights reserved';
    $postdata['pozauthor']['info'] = !empty($postdata['pozauthor']['info']) ? strip_tags(nv_unhtmlspecialchars($postdata['pozauthor']['info'])) : '';
    $postdata['pozauthor']['langtype'] = (isset($postdata['pozauthor']['langtype']) && preg_match('/^[a-z0-9\_]{3,30}$/', $postdata['pozauthor']['langtype'])) ? $postdata['pozauthor']['langtype'] : 'lang_module';
    $author = serialize($postdata['pozauthor']);

    $stmt_file_upd = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . '_file SET author_' . $dirlang . ' = :author WHERE idfile = :idfile');
    $stmt_file_upd->bindValue(':author', $author, PDO::PARAM_STR);
    $stmt_file_upd->bindValue(':idfile', $idfile, PDO::PARAM_INT);
    $stmt_file_upd->execute();
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_edit') . ' -> ' . $language_array[$dirlang]['name'], $module . ' : idfile = ' . $idfile, $admin_info['userid']);

    $weight = 0;
    $langkeys = [];
    
    $stmt_upd = $db->prepare('UPDATE ' . NV_LANGUAGE_GLOBALTABLE . ' SET lang_key = :lang_key, weight = :weight, lang_' . $dirlang . ' = :lang_value, update_' . $dirlang . ' = ' . NV_CURRENTTIME . ' WHERE id = :id');
    $stmt_ins = $db->prepare('INSERT INTO ' . NV_LANGUAGE_GLOBALTABLE . ' (idfile, langtype, lang_key, weight, lang_' . $dirlang . ', update_' . $dirlang . ') VALUES (:idfile, :langtype, :lang_key, :weight, :lang_value, ' . NV_CURRENTTIME . ')');
    $stmt_ins->bindValue(':idfile', $idfile, PDO::PARAM_INT);
    $stmt_del = $db->prepare('DELETE FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE id = :id');

    foreach ($postdata['ids'] as $key => $id) {
        $postdata['values'][$key] = trim(strip_tags(str_replace(['&amp;', '“', '”'], ['&', '&ldquo;', '&rdquo;'], str_replace(['&lt;', '&gt;'], ['<', '>'], $postdata['values'][$key])), NV_ALLOWED_HTML_LANG));
        if ($id > 0) {
            if ($postdata['isdels'][$key]) {
                $stmt_del->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt_del->execute();
            } else {
                if (preg_match('/^[a-zA-Z0-9\_]{1,50}$/', $postdata['keys'][$key]) and !in_array($postdata['keys'][$key], $langkeys, true)) {
                    ++$weight;
                    $stmt_upd->bindValue(':lang_key', $postdata['keys'][$key], PDO::PARAM_STR);
                    $stmt_upd->bindValue(':weight', $weight, PDO::PARAM_INT);
                    $stmt_upd->bindValue(':lang_value', $postdata['values'][$key], PDO::PARAM_STR);
                    $stmt_upd->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt_upd->execute();
                    $langkeys[] = $postdata['keys'][$key];
                }
            }
        } else {
            if (preg_match('/^[a-zA-Z0-9\_]{1,50}$/', $postdata['keys'][$key]) and !in_array($postdata['keys'][$key], $langkeys, true)) {
                ++$weight;
                $stmt_ins->bindValue(':langtype', $postdata['pozauthor']['langtype'], PDO::PARAM_STR);
                $stmt_ins->bindValue(':lang_key', $postdata['keys'][$key], PDO::PARAM_STR);
                $stmt_ins->bindValue(':weight', $weight, PDO::PARAM_INT);
                $stmt_ins->bindValue(':lang_value', $postdata['values'][$key], PDO::PARAM_STR);
                $stmt_ins->execute();
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

if (!$nv_Request->isset_request('checksess', 'get') or !csrf_check($nv_Request->get_string('checksess', 'get'), $admin_info['admin_id'] . '_' . $module_name . '_edit_' . $idfile)) {
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
    $array_translator = unserialize($author_lang, NV_UNSERIALIZE_SAFE);
}

$modules_exit = nv_scandir(NV_ROOTDIR . '/modules', $global_config['check_module']);

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('edit.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->registerPlugin('modifier', 'strencode', 'nv_htmlspecialchars');

$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;dirlang=' . $dirlang . '&amp;idfile=' . $idfile);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('TRANSLATOR', $array_translator);
$tpl->assign('EDIT_MODULE', $module);
if ($admin_file == '1') {
    $tpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_admin'));
} elseif ($admin_file == '0') {
    if (in_array($module, $modules_exit, true) or preg_match('/^theme\_(.*?)$/', $module)) {
        $tpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_whole_site'));
    } else {
        $tpl->assign('MODULE_AREA', $nv_Lang->getModule('nv_lang_site'));
    }
} else {
    $tpl->assign('MODULE_AREA', $admin_file);
}
$tpl->assign('ALLOWED_WRITE', in_array('write', $allow_func, true));

$stmt_list = $db->prepare('SELECT id, lang_key, lang_' . $dirlang . ' FROM ' . NV_LANGUAGE_GLOBALTABLE . ' WHERE idfile = :idfile ORDER BY weight ASC');
$stmt_list->bindValue(':idfile', $idfile, PDO::PARAM_INT);
$stmt_list->execute();

$array = [];
while ($_row = $stmt_list->fetch()) {
    $id = $_row['id'];
    $lang_key = $_row['lang_key'];
    $lang_value = $_row['lang_' . $dirlang];
    $array[] = [
        'lang_key' => $lang_key,
        'value' => !empty($lang_value) ? str_replace(['&lt;', '&gt;', '&quot;', '<', '>', '"', "'"], ['&amp;lt;', '&amp;gt;', '&amp;quot;', '&lt;', '&gt;', '&quot;', '&#039;'], $lang_value) : '',
        'id' => $id
    ];
}
$tpl->assign('ARRAY', $array);

$contents = $tpl->fetch('edit.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
