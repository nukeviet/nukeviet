<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\news\Shared\Logs;

$global_code_defined = [
    'cat_visible_status' => [1, 2],
    'cat_locked_status' => 10,
    'row_locked_status' => 20,
    'edit_timeout' => 180
];

$order_articles = $module_config[$module_name]['order_articles'];
$order_articles_by = ($order_articles) ? 'weight' : 'publtime';
$timecheckstatus = $module_config[$module_name]['timecheckstatus'];
if ($timecheckstatus > 0 and $timecheckstatus < NV_CURRENTTIME) {
    nv_set_status_module();
}

/**
 * nv_set_status_module()
 *
 * @throws PDOException
 */
function nv_set_status_module()
{
    global $nv_Cache, $db, $module_name, $module_data, $global_config;

    $check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5($module_data . 'nv_set_status_module' . $global_config['sitekey']) . '.txt';
    $p = NV_CURRENTTIME - 300;
    if (file_exists($check_run_cronjobs) and @filemtime($check_run_cronjobs) > $p) {
        return;
    }
    file_put_contents($check_run_cronjobs, '');

    // Dang cai bai cho kich hoat theo thoi gian
    $query = $db->query('SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=2 AND publtime < ' . NV_CURRENTTIME . ' ORDER BY publtime ASC');
    while (list($id, $listcatid) = $query->fetch(3)) {
        $array_catid = explode(',', $listcatid);
        foreach ($array_catid as $catid_i) {
            $catid_i = (int) $catid_i;
            if ($catid_i > 0) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=1 WHERE id=' . $id);
            }
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=1 WHERE id=' . $id);
    }

    // Ngung hieu luc cac bai da het han
    $weight_min = 0;
    $query = $db->query('SELECT id, listcatid, archive, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY weight DESC, exptime ASC');
    while (list($id, $listcatid, $archive, $weight) = $query->fetch(3)) {
        if ((int) $archive == 0) {
            nv_del_content_module($id);
            $weight_min = $weight;
        } else {
            nv_archive_content_module($id, $listcatid);
        }
    }

    // Tim kiem thoi gian chay lan ke tiep
    $time_publtime = $db->query('SELECT min(publtime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=2 AND publtime > ' . NV_CURRENTTIME)->fetchColumn();
    $time_exptime = $db->query('SELECT min(exptime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=1 AND exptime > ' . NV_CURRENTTIME)->fetchColumn();

    $timecheckstatus = min($time_publtime, $time_exptime);
    if (!$timecheckstatus) {
        $timecheckstatus = max($time_publtime, $time_exptime);
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'timecheckstatus'");
    $sth->bindValue(':module_name', $module_name, PDO::PARAM_STR);
    $sth->bindValue(':config_value', (int) $timecheckstatus, PDO::PARAM_STR);
    $sth->execute();

    nv_fix_weight_content($weight_min);
    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);

    unlink($check_run_cronjobs);
    clearstatcache();
}

/**
 * nv_del_content_module()
 *
 * @param int $id
 * @return string
 */
function nv_del_content_module($id)
{
    global $db, $module_name, $module_data, $title, $lang_module, $module_config;
    $content_del = 'NO_' . $id;
    $title = '';
    list($id, $listcatid, $title) = $db->query('SELECT id, listcatid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . (int) $id)->fetch(3);
    if ($id > 0) {
        $number_no_del = 0;
        $array_catid = explode(',', $listcatid);
        foreach ($array_catid as $catid_i) {
            $catid_i = (int) $catid_i;
            if ($catid_i > 0) {
                $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' WHERE id=' . $id;
                if (!$db->exec($_sql)) {
                    ++$number_no_del;
                }
            }
        }

        // Xóa bảng rows
        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
        if (!$db->exec($_sql)) {
            ++$number_no_del;
        }

        // Xóa bảng detail
        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id = ' . $id;
        if (!$db->exec($_sql)) {
            ++$number_no_del;
        }

        // Xóa lịch sử bài viết
        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_histories WHERE new_id = ' . $id;
        $db->exec($_sql);

        // Xóa log thay đổi trạng thái bài viết
        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_logs WHERE sid=' . $id . ' AND log_key=\'' . Logs::KEY_CHANGE_STATUS . '\'';
        $db->exec($_sql);

        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote($module_name) . ' AND id = ' . $id);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $id);

        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid IN (SELECT tid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $id . ')');
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $id);

        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_author SET numnews = numnews-1 WHERE id IN (SELECT aid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE id=' . $id . ')');
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_authorlist WHERE id = ' . $id);

        nv_delete_notification(NV_LANG_DATA, $module_name, 'post_queue', $id);

        /*conenct to elasticsearch*/
        if ($module_config[$module_name]['elas_use'] == 1) {
            $nukeVietElasticSearh = new NukeViet\ElasticSearch\Functions($module_config[$module_name]['elas_host'], $module_config[$module_name]['elas_port'], $module_config[$module_name]['elas_index']);
            $nukeVietElasticSearh->delete_data(NV_PREFIXLANG . '_' . $module_data . '_rows', $id);
        }

        if ($number_no_del == 0) {
            $content_del = 'OK_' . $id . '_' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
        } else {
            $content_del = 'ERR_' . $lang_module['error_del_content'];
        }
    }

    return $content_del;
}

/**
 * nv_fix_weight_content()
 *
 * @param int $weight_min
 */
function nv_fix_weight_content($weight_min)
{
    global $db, $module_data;
    if ($weight_min > 0) {
        $weight_min = $weight_min - 1;
        $sql = 'SELECT id, listcatid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE weight >= ' . $weight_min . ' ORDER BY weight ASC, publtime ASC';
        $result = $db->query($sql);
        $weight = $weight_min;
        while ($_row2 = $result->fetch()) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $_row2['id']);
            $_array_catid = explode(',', $_row2['listcatid']);
            foreach ($_array_catid as $_catid) {
                try {
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . (int) $_catid . ' SET weight=' . $weight . ' WHERE id=' . $_row2['id']);
                } catch (PDOException $e) {
                }
            }
            ++$weight;
        }
    }
}

/**
 * nv_archive_content_module()
 *
 * @param int    $id
 * @param string $listcatid
 */
function nv_archive_content_module($id, $listcatid)
{
    global $db, $module_data;
    $array_catid = explode(',', $listcatid);
    foreach ($array_catid as $catid_i) {
        $catid_i = (int) $catid_i;
        if ($catid_i > 0) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=3 WHERE id=' . $id);
        }
    }
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=3 WHERE id=' . $id);
}

/**
 * nv_link_edit_page()
 *
 * @param int $id
 * @return string
 */
function nv_link_edit_page($id)
{
    global $lang_global, $module_name;
    $link = '<a class="btn btn-primary btn-xs btn_edit" href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $id . '"><em class="fa fa-edit margin-right"></em> ' . $lang_global['edit'] . '</a>';

    return $link;
}

/**
 * nv_link_delete_page()
 *
 * @param int $id
 * @param int $detail
 * @return string
 */
function nv_link_delete_page($id, $detail = 0)
{
    global $lang_global;
    $link = '<a class="btn btn-danger btn-xs" href="javascript:void(0);" onclick="nv_del_content(' . $id . ", '" . md5($id . NV_CHECK_SESSION) . "','" . NV_BASE_ADMINURL . "', " . $detail . ')"><em class="fa fa-trash-o margin-right"></em> ' . $lang_global['delete'] . '</a>';

    return $link;
}

/**
 * nv_get_firstimage()
 *
 * @param string $contents
 * @return string
 */
function nv_get_firstimage($contents)
{
    if (preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $contents, $img)) {
        return $img[1];
    }

    return '';
}

/**
 * nv_check_block_topcat_news()
 *
 * @param int $catid
 * @return bool
 */
function nv_check_block_topcat_news($catid)
{
    global $global_config, $module_info, $module_name;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    $find1 = '/<name>' . strtoupper($module_name) . '_TOPCAT_' . $catid . "<\/name>/";
    $find2 = "/<tag>\[" . strtoupper($module_name) . '_TOPCAT_' . $catid . "\]<\/tag>/";
    if (preg_match($find1, $contents) and preg_match($find2, $contents)) {
        return true;
    }

    return false;
}

/**
 * nv_check_block_block_botcat_news()
 *
 * @param int $catid
 * @return bool
 */
function nv_check_block_block_botcat_news($catid)
{
    global $global_config, $module_info, $module_name;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    $find1 = '/<name>' . strtoupper($module_name) . '_BOTTOMCAT_' . $catid . "<\/name>/";
    $find2 = "/<tag>\[" . strtoupper($module_name) . '_BOTTOMCAT_' . $catid . "\]<\/tag>/";
    if (preg_match($find1, $contents) and preg_match($find2, $contents)) {
        return true;
    }

    return false;
}

/**
 * nv_add_block_topcat_news()
 *
 * @param int $catid
 * @return bool|void
 */
function nv_add_block_topcat_news($catid)
{
    global $global_config, $module_info, $module_name, $nv_Cache;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    if (!nv_check_block_topcat_news($catid) and !empty($contents)) {
        $find = '/<positions>/';
        $pos = '
        <position>
            <name>' . strtoupper($module_name) . '_TOPCAT_' . $catid . '</name>
            <tag>[' . strtoupper($module_name) . '_TOPCAT_' . $catid . ']</tag>
        </position>
            ';
        $_replace = '<positions>' . $pos;
        $contents = preg_replace($find, $_replace, $contents);
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace('/\\t\\t\\n/', '', $contents);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $contents = $doc->saveXML();

        $fname = $ini_file;
        $fhandle = fopen($fname, 'w');
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        }
        fclose($fhandle);

        return true;
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_add_block_botcat_news()
 *
 * @param int $catid
 * @return bool|void
 */
function nv_add_block_botcat_news($catid)
{
    global $global_config, $module_info, $module_name, $nv_Cache;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    if (!nv_check_block_block_botcat_news($catid) and !empty($contents)) {
        $find = '/<positions>/';
        $pos = '
        <position>
            <name>' . strtoupper($module_name) . '_BOTTOMCAT_' . $catid . '</name>
            <tag>[' . strtoupper($module_name) . '_BOTTOMCAT_' . $catid . ']</tag>
        </position>
            ';
        $_replace = '<positions>' . $pos;
        $contents = preg_replace($find, $_replace, $contents);
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace('/\\t\\t\\n/', '', $contents);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $contents = $doc->saveXML();

        $fname = $ini_file;
        $fhandle = fopen($fname, 'w');
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        }
        fclose($fhandle);

        return true;
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_remove_block_topcat_news()
 *
 * @param int $catid
 * @return bool|void
 */
function nv_remove_block_topcat_news($catid)
{
    global $global_config, $module_info, $module_name, $nv_Cache;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    if (nv_check_block_topcat_news($catid)) {
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace('/\\t\\t\\n/', '', $contents);

        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $xpath = new DOMXpath($doc);
        $positions = $xpath->query('//name[text()="' . strtoupper($module_name) . '_TOPCAT_' . $catid . '"]/parent::position');
        foreach ($positions as $position) {
            $position->parentNode->removeChild($position);
        }
        $contents = $doc->saveXML();
        $fname = $ini_file;
        $fhandle = fopen($fname, 'w');
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        }
        fclose($fhandle);

        return true;
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_remove_block_botcat_news()
 *
 * @param int $catid
 * @return bool|void
 */
function nv_remove_block_botcat_news($catid)
{
    global $global_config, $module_info, $module_name, $nv_Cache;

    if (!empty($module_info['theme'])) {
        $ini_file = NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/config.ini';
    } else {
        $ini_file = NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini';
    }
    $contents = file_get_contents($ini_file);

    if (nv_check_block_block_botcat_news($catid)) {
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace('/\\t\\t\\n/', '', $contents);

        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $xpath = new DOMXpath($doc);
        $positions = $xpath->query('//name[text()="' . strtoupper($module_name) . '_BOTTOMCAT_' . $catid . '"]/parent::position');
        foreach ($positions as $position) {
            $position->parentNode->removeChild($position);
        }
        $contents = $doc->saveXML();
        $fname = $ini_file;
        $fhandle = fopen($fname, 'w');
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        }
        fclose($fhandle);

        return true;
        $nv_Cache->delMod($module_name);
    }
}

/**
 * get_pseudonym_alias()
 *
 * @param string $pseudonym
 * @param int    $aid
 * @return string
 */
function get_pseudonym_alias($pseudonym, $aid)
{
    global $db_slave, $module_data;

    $alias = change_alias($pseudonym);

    $tab = NV_PREFIXLANG . '_' . $module_data . '_author';
    $stmt = $db_slave->prepare('SELECT COUNT(*) FROM ' . $tab . ' WHERE id!=' . $aid . ' AND alias= :alias');
    $stmt->bindParam(':alias', $alias, PDO::PARAM_STR);
    $stmt->execute();
    $nb = $stmt->fetchColumn();
    if (!empty($nb)) {
        return false;
    }

    return $alias;
}

/**
 * my_author_detail()
 *
 * @param int $userid
 * @return array
 */
function my_author_detail($userid)
{
    global $db, $module_data;

    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_author WHERE uid =' . $userid;
    $result = $db->query($sql);
    $detail = $result->fetch();
    if (!$detail) {
        $sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid =' . $userid;
        $result = $db->query($sql);
        $row = $result->fetch();
        $pseudonym = '';
        if (!empty($row['first_name'])) {
            $pseudonym .= $row['first_name'];
            if (!empty($row['last_name'])) {
                $pseudonym .= ' ' . $row['last_name'];
            }
        }
        if (empty($pseudonym)) {
            $pseudonym = $row['username'];
        }

        $alias = get_pseudonym_alias($pseudonym, 0);
        if (!$alias) {
            $alias = change_alias($pseudonym) . '-' . $userid;
        }

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_author (uid, alias, pseudonym, image, description, add_time) VALUES ( ' . $userid . ", :alias, :pseudonym, '', '', " . NV_CURRENTTIME . ')';
        $data_insert = [];
        $data_insert['alias'] = $alias;
        $data_insert['pseudonym'] = $pseudonym;
        $id = $db->insert_id($sql, 'id', $data_insert);

        $detail = [
            'id' => $id,
            'uid' => $userid,
            'alias' => $alias,
            'pseudonym' => $pseudonym,
            'image' => '',
            'description' => '',
            'add_time' => NV_CURRENTTIME,
            'edit_time' => 0,
            'active' => 1,
            'numnews' => 0
        ];
    }

    return $detail;
}

/**
 * @param array $post_old
 * @param array $post_new
 * @return string[]
 */
function nv_save_history($post_old, $post_new)
{
    if (is_array($post_old['files'])) {
        $post_old['files'] = empty($post_old['files']) ? '' : implode(',', $post_old['files']);
    }
    $change_fields = [];
    $key_text = [
        'catid',
        'topicid',
        'author',
        'sourceid',
        'publtime',
        'exptime',
        'archive',
        'title',
        'alias',
        'hometext',
        'homeimgfile',
        'homeimgalt',
        'inhome',
        'allowed_comm',
        'allowed_rating',
        'external_link',
        'instant_active',
        'instant_template',
        'instant_creatauto',
        'titlesite',
        'description',
        'bodyhtml',
        'sourcetext',
        'imgposition',
        'layout_func',
        'copyright',
        'allowed_send',
        'allowed_print',
        'allowed_save',
    ];
    $key_textlist = [
        'listcatid',
        'keywords',
        'tags',
        'files',
    ];
    $key_array = [
        'internal_authors',
    ];

    foreach ($key_text as $key) {
        if ($post_old[$key] != $post_new[$key]) {
            $change_fields[] = $key;
        }
    }
    foreach ($key_textlist as $key) {
        $old = array_map('trim', explode(',', $post_old[$key]));
        $new = array_map('trim', explode(',', $post_new[$key]));
        if (array_diff($old, $new) != [] or array_diff($new, $old) != []) {
            $change_fields[] = $key;
        }
    }
    foreach ($key_array as $key) {
        if (array_diff($post_old[$key], $post_new[$key]) != [] or array_diff($post_new[$key], $post_old[$key]) != []) {
            $change_fields[] = $key;
        }
    }

    if (!empty($change_fields)) {
        global $admin_info, $user_info, $module_data, $db;

        $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_row_histories (
            new_id, historytime, catid, listcatid, topicid, admin_id,
            author, sourceid, publtime, exptime, archive, title, alias,
            hometext, homeimgfile, homeimgalt, inhome, allowed_comm,
            allowed_rating, external_link, instant_active, instant_template,
            instant_creatauto, titlesite, description, bodyhtml, keywords, sourcetext,
            files, tags, internal_authors, imgposition, layout_func, copyright,
            allowed_send, allowed_print, allowed_save, changed_fields
        ) VALUES (
            :new_id, :historytime, :catid, :listcatid, :topicid, :admin_id,
            :author, :sourceid, :publtime, :exptime, :archive, :title, :alias,
            :hometext, :homeimgfile, :homeimgalt, :inhome, :allowed_comm,
            :allowed_rating, :external_link, :instant_active, :instant_template,
            :instant_creatauto, :titlesite, :description, :bodyhtml, :keywords, :sourcetext,
            :files, :tags, :internal_authors, :imgposition, :layout_func,
            :copyright, :allowed_send, :allowed_print, :allowed_save, :changed_fields
        )";
        $array_insert = [];
        $array_insert['new_id'] = $post_old['id'];
        $array_insert['historytime'] = empty($post_old['edittime']) ? $post_old['addtime'] : $post_old['edittime'];
        $array_insert['catid'] = $post_old['catid'];
        $array_insert['listcatid'] = $post_old['listcatid'];
        $array_insert['topicid'] = $post_old['topicid'];
        $array_insert['admin_id'] = empty($admin_info) ? (empty($user_info) ? 0 : $user_info['userid']) : $admin_info['admin_id'];
        $array_insert['author'] = $post_old['author'];
        $array_insert['sourceid'] = $post_old['sourceid'];
        $array_insert['publtime'] = $post_old['publtime'];
        $array_insert['exptime'] = $post_old['exptime'];
        $array_insert['archive'] = $post_old['archive'];
        $array_insert['title'] = $post_old['title'];
        $array_insert['alias'] = $post_old['alias'];
        $array_insert['hometext'] = $post_old['hometext'];
        $array_insert['homeimgfile'] = $post_old['homeimgfile'];
        $array_insert['homeimgalt'] = $post_old['homeimgalt'];
        $array_insert['inhome'] = $post_old['inhome'];
        $array_insert['allowed_comm'] = $post_old['allowed_comm'];
        $array_insert['allowed_rating'] = $post_old['allowed_rating'];
        $array_insert['external_link'] = $post_old['external_link'];
        $array_insert['instant_active'] = $post_old['instant_active'];
        $array_insert['instant_template'] = $post_old['instant_template'];
        $array_insert['instant_creatauto'] = $post_old['instant_creatauto'];
        $array_insert['titlesite'] = $post_old['titlesite'];
        $array_insert['description'] = $post_old['description'];
        $array_insert['bodyhtml'] = $post_old['bodyhtml'];
        $array_insert['keywords'] = $post_old['keywords'];
        $array_insert['sourcetext'] = $post_old['sourcetext'];
        $array_insert['files'] = $post_old['files'];
        $array_insert['tags'] = $post_old['tags'];
        $array_insert['internal_authors'] = implode(',', $post_old['internal_authors']);
        $array_insert['imgposition'] = $post_old['imgposition'];
        $array_insert['layout_func'] = $post_old['layout_func'];
        $array_insert['copyright'] = $post_old['copyright'];
        $array_insert['allowed_send'] = $post_old['allowed_send'];
        $array_insert['allowed_print'] = $post_old['allowed_print'];
        $array_insert['allowed_save'] = $post_old['allowed_save'];
        $array_insert['changed_fields'] = implode(',', $change_fields);
        $db->insert_id($sql, 'id', $array_insert);
    }

    return $change_fields;
}
