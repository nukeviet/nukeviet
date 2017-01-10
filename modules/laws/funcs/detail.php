<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (!defined('NV_IS_MOD_LAWS'))
    die('Stop!!!');

$lawalias = $alias = isset($array_op[1]) ? $array_op[1] : '';

if (!preg_match('/^([a-z0-9\-\_\.]+)$/i', $alias)) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE alias=' . $db->quote($alias) . ' AND status=1';
if (($result = $db->query($sql)) === false) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

if (($row = $result->fetch()) === false) {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}

$row['aid'] = array();
$result = $db->query('SELECT area_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row_area WHERE row_id=' . $row['id']);
while (list($area_id) = $result->fetch(3)) {
    $row['aid'][] = $area_id;
}

if (!nv_user_in_groups($row['groups_view'])) {
    nv_info_die($lang_module['info_no_allow'], $lang_module['info_no_allow'], $lang_module['info_no_allow_detail']);
}

if ($nv_Request->isset_request('download', 'get')) {
    $fileid = $nv_Request->get_int('id', 'get', 0);

    $row['files'] = explode(',', $row['files']);

    if (!isset($row['files'][$fileid])) {
        Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
        exit();
    }

    if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['files'][$fileid])) {
        Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
        exit();
    }

    // Update download
    $lawsdownloaded = $nv_Request->get_string('lawsdownloaded', 'session', '');
    $lawsdownloaded = !empty($lawsdownloaded) ? unserialize($lawsdownloaded) : array();
    if (!in_array($row['id'], $lawsdownloaded)) {
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_row SET download_hits=download_hits+1 WHERE id=' . $row['id'];
        $db->query($sql);
        $lawsdownloaded[] = $row['id'];
        $lawsdownloaded = serialize($lawsdownloaded);
        $nv_Request->set_Session('lawsdownloaded', $lawsdownloaded);
    }

    $file_info = pathinfo(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['files'][$fileid]);
    $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['files'][$fileid], $file_info['dirname'], $file_info['basename'], true);
    $download->download_file();
    exit();
}

if ($nv_Request->isset_request('pdf', 'get')) {
    $fileid = $nv_Request->get_int('id', 'get', 0);

    $row['files'] = explode(',', $row['files']);

    if (!isset($row['files'][$fileid])) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }

    if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['files'][$fileid])) {
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
    }
    
    $file_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $lawalias . '&download=1&id=' . $fileid;
    $contents = nv_theme_viewpdf($file_url);
    die($contents);
}

$page_title = $row['title'];
$key_words = $row['keywords'];
$description = $row['introtext'];

// Lay van ban thay the no
if (!empty($row['replacement'])) {
    $sql = 'SELECT title, alias, code FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id IN(' . $row['replacement'] . ')';
    $result = $db->query($sql);
    $row['replacement'] = array();
    while (list($_title, $_alias, $_code) = $result->fetch(3)) {
        $row['replacement'][] = array(
            'title' => $_title,
            'code' => $_code,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $_alias
        );
    }
}

// Lay van ban ma no thay the
$row['unreplacement'] = array();
$sql = 'SELECT b.title, b.alias, b.code FROM ' . NV_PREFIXLANG . '_' . $module_data . '_set_replace AS a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_row AS b ON a.oid=b.id WHERE a.nid=' . $row['id'];
$result = $db->query($sql);
while (list($_title, $_alias, $_code) = $result->fetch(3)) {
    $row['unreplacement'][] = array(
        'title' => $_title,
        'code' => $_code,
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $_alias
    );
}

// Lay cac van ban lien quan
if (!empty($row['relatement'])) {
    $sql = 'SELECT title, alias, code FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE id IN(' . $row['relatement'] . ')';
    $result = $db->query($sql);
    $row['relatement'] = array();
    while (list($_title, $_alias, $_code) = $result->fetch(3)) {
        $row['relatement'][] = array(
            'title' => $_title,
            'code' => $_code,
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $_alias
        );
    }
}

// Nguoi ky
if (!empty($row['sgid'])) {
    $sql = 'SELECT title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_signer WHERE id = ' . $row['sgid'];
    $result = $db->query($sql);
    list($row['signer']) = $result->fetch(3);
    $row['signer_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=signer/' . $row['sgid'] . '/' . change_alias($row['signer']);
}

// File download
if (!empty($row['files'])) {
    $row['files'] = explode(',', $row['files']);
    $files = $row['files'];
    $row['files'] = array();

    foreach ($files as $id => $file) {
        $file_title = basename($file);
        $row['files'][] = array(
            'title' => $file_title,
            'key' => md5($id . $file_title),
            'ext' => nv_getextension($file_title),
            'titledown' => $lang_module['download'] . ' ' . (count($files) > 1 ? $id + 1 : ''),
            'url' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $lawalias . '&amp;download=1&amp;id=' . $id,
            'urlpdf' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['detail'] . '/' . $lawalias . '&amp;pdf=1&amp;id=' . $id
        );
    }
}

// Update view hit
$lawsviewed = $nv_Request->get_string('lawsviewed', 'session', '');
$lawsviewed = !empty($lawsviewed) ? unserialize($lawsviewed) : array();
if (!in_array($row['id'], $lawsviewed)) {
    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_row SET view_hits=view_hits+1 WHERE id=' . $row['id'];
    $db->query($sql);
    $lawsviewed[] = $row['id'];
    $lawsviewed = serialize($lawsviewed);
    $nv_Request->set_Session('lawsviewed', $lawsviewed);
}

$order = ($nv_laws_setting['typeview'] == 1) ? 'ASC' : 'DESC';
$nv_laws_setting['detail_other'] = unserialize($nv_laws_setting['detail_other']);
$other_cat = array();
$other_area = array();
$other_subject = array();
$other_signer = array();

if ($nv_laws_setting['detail_other']) {
    if (in_array('cat', $nv_laws_setting['detail_other'])) {
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE cid=' . $row['cid'] . ' AND id!=' . $row['id'] . ' ORDER BY addtime ' . $order . ' LIMIT ' . $nv_laws_setting['other_numlinks']);
        while ($data = $result->fetch()) {
            $data['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $data['alias'];
            $other_cat[$data['id']] = $data;
        }
    }

    if (in_array('area', $nv_laws_setting['detail_other'])) {
        $_row_aid = implode(',', $row['aid']);
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_row_area t2 ON t1.id=t2.row_id WHERE t2.area_id IN (' . $_row_aid . ') AND t1.id!=' . $row['id'] . ' ORDER BY addtime ' . $order . ' LIMIT ' . $nv_laws_setting['other_numlinks']);
        while ($data = $result->fetch()) {
            $data['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $data['alias'];
            $other_area[$data['id']] = $data;
        }
    }

    if (in_array('subject', $nv_laws_setting['detail_other'])) {
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sid=' . $row['sid'] . ' AND id!=' . $row['id'] . ' ORDER BY addtime ' . $order . ' LIMIT ' . $nv_laws_setting['other_numlinks']);
        while ($data = $result->fetch()) {
            $data['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $data['alias'];
            $other_subject[$data['id']] = $data;
        }
    }

    if (in_array('singer', $nv_laws_setting['detail_other'])) {
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_row WHERE sgid=' . $row['sgid'] . ' AND id!=' . $row['id'] . ' ORDER BY addtime ' . $order . ' LIMIT ' . $nv_laws_setting['other_numlinks']);
        while ($data = $result->fetch()) {
            $data['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail/' . $data['alias'];
            $other_signer[$data['id']] = $data;
        }
    }
}

$contents = nv_theme_laws_detail($row, $other_cat, $other_area, $other_subject, $other_signer);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
