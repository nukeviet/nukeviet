<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_SEOTOOLS')) {
    die('Stop!!!');
}

$page_title = $nv_Lang->getModule('googleplus_page_title');

// Sua
if ($nv_Request->isset_request('edit', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);

    if (empty($title)) {
        nv_htmlOutput('NO');
    }
    $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_googleplus SET title = :title, edit_time=' . NV_CURRENTTIME . ' WHERE gid=' . $gid);
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    if (!$sth->execute()) {
        nv_htmlOutput('NO');
    }
    nv_htmlOutput('OK');
}

// Them
if ($nv_Request->isset_request('add', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $title = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 250);
    $idprofile = nv_substr($nv_Request->get_title('idprofile', 'post', '', 1), 0, 25);

    if (empty($title)) {
        nv_htmlOutput('NO');
    }

    $weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_googleplus')->fetchColumn();
    $weight = intval($weight) + 1;
    try {
        $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_googleplus (title, idprofile, weight, add_time, edit_time) VALUES ( :title, :idprofile, ' . $weight . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')');
        $sth->bindParam(':title', $title, PDO::PARAM_STR);
        $sth->bindParam(':idprofile', $idprofile, PDO::PARAM_STR);
        $sth->execute();
    } catch (PDOException $e) {
        nv_htmlOutput('NO');
    }

    $nv_Cache->delMod('seotools');
    nv_htmlOutput('OK');
}

// Chinh thu tu
if ($nv_Request->isset_request('changeweight', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);

    $numrows = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_googleplus WHERE gid=' . $gid)->fetchColumn();
    if ($numrows != 1) {
        nv_htmlOutput('NO');
    }

    $query = 'SELECT gid FROM ' . $db_config['prefix'] . '_googleplus WHERE gid!=' . $gid . ' ORDER BY weight ASC';
    $result = $db->query($query);
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_vid) {
            ++$weight;
        }
        $db->query('UPDATE ' . $db_config['prefix'] . '_googleplus SET weight=' . $weight . ' WHERE gid=' . $row['gid']);
    }
    $db->query('UPDATE ' . $db_config['prefix'] . '_googleplus SET weight=' . $new_vid . ' WHERE gid=' . $gid);
    nv_htmlOutput('OK');
}

// Xoa
if ($nv_Request->isset_request('del', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);

    $gid = $db->query('SELECT gid FROM ' . $db_config['prefix'] . '_googleplus WHERE gid=' . $gid)->fetchColumn();

    if ($gid) {
        $db->query('UPDATE ' . NV_MODULES_TABLE . ' SET gid=0 WHERE gid=' . $gid);
        $nv_Cache->delMod('modules');

        $query = 'DELETE FROM ' . $db_config['prefix'] . '_googleplus WHERE gid=' . $gid;
        if ($db->exec($query)) {
            // fix weight question
            $result = $db->query('SELECT gid FROM ' . $db_config['prefix'] . '_googleplus ORDER BY weight ASC');
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $db->query('UPDATE ' . $db_config['prefix'] . '_googleplus SET weight=' . $weight . ' WHERE gid=' . $row['gid']);
            }
            $result->closeCursor();
            $nv_Cache->delMod('seotools');
            nv_htmlOutput('OK');
        }
    }
    nv_htmlOutput('NO');
}

// Change for module
if ($nv_Request->isset_request('changemod', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $title = $nv_Request->get_title('changemod', 'post', 0);

    if (!isset($site_mods[$title])) {
        nv_htmlOutput('NO');
    }

    $gid = $nv_Request->get_int('gid', 'post', 0);

    $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET gid=' . $gid . ' WHERE title= :title');
    $sth->bindParam(':title', $title, PDO::PARAM_STR);
    $sth->execute();

    $nv_Cache->delMod('modules');
    nv_htmlOutput('OK');
}

$tpl = new \NukeViet\Template\Smarty();
$tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

// Danh sách
if ($nv_Request->isset_request('qlist', 'post')) {
    if (!defined('NV_IS_AJAX')) {
        nv_htmlOutput('Wrong URL');
    }

    $array_googleplus = array();
    $result = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_googleplus ORDER BY weight ASC');
    while ($row = $result->fetch()) {
        $array_googleplus[$row['gid']] = $row;
    }

    $tpl->assign('SITE_MODS', $site_mods);
    $tpl->assign('NUMGOOGLEPLUS', sizeof($array_googleplus));
    $tpl->assign('GOOGLEPLUS', $array_googleplus);

    $contents = $tpl->fetch('googleplus_list.tpl');
} else {
    $contents = $tpl->fetch('googleplus.tpl');
    $contents = nv_admin_theme($contents);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
