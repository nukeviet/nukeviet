<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$global_code_defined = array(
    'cat_visible_status' => array(1, 2),
    'cat_locked_status' => 10,
    'row_locked_status' => 20,
    'edit_timeout' => 180
);

$order_articles = $module_config[$module_name]['order_articles'];
$order_articles_by = ($order_articles) ? 'weight' : 'publtime';
$timecheckstatus = $module_config[$module_name]['timecheckstatus'];
if ($timecheckstatus > 0 and $timecheckstatus < NV_CURRENTTIME) {
    nv_set_status_module();
}

/**
 * nv_set_status_module()
 *
 * @return
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
    while (list ($id, $listcatid) = $query->fetch(3)) {
        $array_catid = explode(',', $listcatid);
        foreach ($array_catid as $catid_i) {
            $catid_i = intval($catid_i);
            if ($catid_i > 0) {
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=1 WHERE id=' . $id);
            }
        }
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=1 WHERE id=' . $id);
    }

    // Ngung hieu luc cac bai da het han
    $weight_min = 0;
    $query = $db->query('SELECT id, listcatid, archive, weight FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status=1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY weight DESC, exptime ASC');
    while (list ($id, $listcatid, $archive, $weight) = $query->fetch(3)) {
        if (intval($archive) == 0) {
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

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'timecheckstatus'");
    $sth->bindValue(':module_name', $module_name, PDO::PARAM_STR);
    $sth->bindValue(':config_value', intval($timecheckstatus), PDO::PARAM_STR);
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
 * @param mixed $id
 * @return
 */
function nv_del_content_module($id)
{
    global $db, $module_name, $module_data, $title, $lang_module, $module_config;
    $content_del = 'NO_' . $id;
    $title = '';
    list ($id, $listcatid, $title) = $db->query('SELECT id, listcatid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval($id))->fetch(3);
    if ($id > 0) {
        $number_no_del = 0;
        $array_catid = explode(',', $listcatid);
        foreach ($array_catid as $catid_i) {
            $catid_i = intval($catid_i);
            if ($catid_i > 0) {
                $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' WHERE id=' . $id;
                if (!$db->exec($_sql)) {
                    ++$number_no_del;
                }
            }
        }

        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
        if (!$db->exec($_sql)) {
            ++$number_no_del;
        }

        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail WHERE id = ' . $id;
        if (!$db->exec($_sql)) {
            ++$number_no_del;
        }

        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote($module_name) . ' AND id = ' . $id);
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE id = ' . $id);

        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid IN (SELECT tid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $id . ')');
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $id);

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
 * @param mixed $weight_min
 * @return
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
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . intval($_catid) . ' SET weight=' . $weight . ' WHERE id=' . $_row2['id']);
                } catch (PDOException $e) {}
            }
            ++$weight;
        }
    }
}

/**
 * nv_archive_content_module()
 *
 * @param mixed $id
 * @param mixed $listcatid
 * @return
 */
function nv_archive_content_module($id, $listcatid)
{
    global $db, $module_data;
    $array_catid = explode(',', $listcatid);
    foreach ($array_catid as $catid_i) {
        $catid_i = intval($catid_i);
        if ($catid_i > 0) {
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET status=3 WHERE id=' . $id);
        }
    }
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=3 WHERE id=' . $id);
}

/**
 * nv_link_edit_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_edit_page($id)
{
    global $lang_global, $module_name;
    $link = "<a class=\"btn btn-primary btn-xs btn_edit\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $id . "\"><em class=\"fa fa-edit margin-right\"></em> " . $lang_global['edit'] . "</a>";
    return $link;
}

/**
 * nv_link_delete_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_delete_page($id, $detail = 0)
{
    global $lang_global;
    $link = "<a class=\"btn btn-danger btn-xs\" href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $id . ", '" . md5($id . NV_CHECK_SESSION) . "','" . NV_BASE_ADMINURL . "', " . $detail . ")\"><em class=\"fa fa-trash-o margin-right\"></em> " . $lang_global['delete'] . "</a>";
    return $link;
}

/**
 * nv_get_firstimage()
 *
 * @param string $contents
 * @return
 */
function nv_get_firstimage($contents)
{
    if (preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $contents, $img)) {
        return $img[1];
    } else {
        return '';
    }
}

/**
 * nv_check_block_topcat_news()
 *
 * @param string $catid
 * @return boolean
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

    $find1 = "/<name>" . strtoupper($module_name) . "_TOPCAT_" . $catid . "<\/name>/";
    $find2 = "/<tag>\[" . strtoupper($module_name) . "_TOPCAT_" . $catid . "\]<\/tag>/";
    if (preg_match($find1, $contents) and preg_match($find2, $contents)) {
        return true;
    } else {
        return false;
    }
}

/**
 * nv_check_block_block_botcat_news()
 *
 * @param string $catid
 * @return boolean
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

    $find1 = "/<name>" . strtoupper($module_name) . "_BOTTOMCAT_" . $catid . "<\/name>/";
    $find2 = "/<tag>\[" . strtoupper($module_name) . "_BOTTOMCAT_" . $catid . "\]<\/tag>/";
    if (preg_match($find1, $contents) and preg_match($find2, $contents)) {
        return true;
    } else {
        return false;
    }
}

/**
 * nv_add_block_topcat_news()
 *
 * @param string $catid
 * @return boolean
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
        $find = "/<positions>/";
        $pos = "
        <position>
            <name>" . strtoupper($module_name) . "_TOPCAT_" . $catid . "</name>
            <tag>[" . strtoupper($module_name) . "_TOPCAT_" . $catid . "]</tag>
        </position>
            ";
        $_replace = "<positions>" . $pos;
        $contents = preg_replace($find, $_replace, $contents);
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace("/\\t\\t\\n/", "", $contents);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $contents = $doc->saveXML();

        $fname = $ini_file;
        $fhandle = fopen($fname, "w");
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        } else {
            fclose($fhandle);
            return true;
        }
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_add_block_botcat_news()
 *
 * @param string $catid
 * @return boolean
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
        $find = "/<positions>/";
        $pos = "
        <position>
            <name>" . strtoupper($module_name) . "_BOTTOMCAT_" . $catid . "</name>
            <tag>[" . strtoupper($module_name) . "_BOTTOMCAT_" . $catid . "]</tag>
        </position>
            ";
        $_replace = "<positions>" . $pos;
        $contents = preg_replace($find, $_replace, $contents);
        $contents = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contents);
        $contents = preg_replace("/\\t\\t\\n/", "", $contents);

        $doc = new DOMDocument('1.0', 'utf-8');
        $doc->formatOutput = true;
        $doc->loadXML($contents);
        $contents = $doc->saveXML();

        $fname = $ini_file;
        $fhandle = fopen($fname, "w");
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        } else {
            fclose($fhandle);
            return true;
        }
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_remove_block_topcat_news()
 *
 * @param string $catid
 * @return boolean
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
        $contents = preg_replace("/\\t\\t\\n/", "", $contents);

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
        $fhandle = fopen($fname, "w");
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        } else {
            fclose($fhandle);
            return true;
        }
        $nv_Cache->delMod($module_name);
    }
}

/**
 * nv_remove_block_botcat_news()
 *
 * @param string $catid
 * @return boolean
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
        $contents = preg_replace("/\\t\\t\\n/", "", $contents);

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
        $fhandle = fopen($fname, "w");
        $fwrite = fwrite($fhandle, $contents);
        if ($fwrite === false) {
            return false;
        } else {
            fclose($fhandle);
            return true;
        }
        $nv_Cache->delMod($module_name);
    }
}