<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = $lang_module['sources'];

list($sourceid, $title, $link, $logo, $error) = [0, '', 'http://', '', ''];

$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (!empty($savecat)) {
    $sourceid = $nv_Request->get_int('sourceid', 'post', 0);
    $title = $nv_Request->get_title('title', 'post', '', 1);
    $link = strtolower($nv_Request->get_title('link', 'post', ''));

    $url_info = parse_url($link);
    if (isset($url_info['scheme']) and isset($url_info['host'])) {
        $link = $url_info['scheme'] . '://' . $url_info['host'];
    } else {
        $link = '';
    }

    $logo_old = $db->query('SELECT logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid =' . $sourceid)->fetchColumn();

    $logo = $nv_Request->get_title('logo', 'post', '');
    if (!nv_is_url($logo) and nv_is_file($logo, NV_UPLOADS_DIR . '/' . $module_upload . '/source')) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/source/');
        $logo = substr($logo, $lu);
    } elseif (!nv_is_url($logo) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/source/' . $logo_old)) {
        $logo = $logo_old;
    } else {
        $logo = '';
    }

    if (($logo != $logo_old) and !empty($logo_old)) {
        $_count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid != ' . $sourceid . ' AND logo =' . $db->quote(basename($logo_old)))->fetchColumn();
        if (empty($_count)) {
            @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old);
            @unlink(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/source/' . $logo_old);

            $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo_old)))->fetchColumn();
            $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($logo_old)));
        }
    }
    if (empty($title)) {
        $error = $lang_module['error_name'];
    } elseif ($sourceid == 0) {
        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources')->fetchColumn();
        $weight = (int) $weight + 1;
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_sources (title, link, logo, weight, add_time, edit_time) VALUES ( :title, :link, :logo, :weight, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ')';
        $data_insert = [];
        $data_insert['title'] = $title;
        $data_insert['link'] = $link;
        $data_insert['logo'] = $logo;
        $data_insert['weight'] = $weight;

        if ($db->insert_id($sql, 'sourceid', $data_insert)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_add_source', ' ', $admin_info['userid']);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_sources SET title= :title, link = :link, logo= :logo, edit_time=' . NV_CURRENTTIME . ' WHERE sourceid =' . $sourceid);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':link', $link, PDO::PARAM_STR);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_STR);
        if ($stmt->execute()) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_source', 'sourceid ' . $sourceid, $admin_info['userid']);
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        } else {
            $error = $lang_module['errorsave'];
        }
    }
}

$sourceid = $nv_Request->get_int('sourceid', 'get', 0);
if ($sourceid > 0) {
    list($sourceid, $title, $link, $logo) = $db->query('SELECT sourceid, title, link, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources where sourceid=' . $sourceid)->fetch(3);
    $lang_module['add_topic'] = $lang_module['edit_topic'];
}

if (!empty($logo)) {
    $logo = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $logo;
}

$xtpl = new XTemplate('sources.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('OP', $op);

$xtpl->assign('SOURCES_LIST', nv_show_sources_list());

$xtpl->assign('sourceid', $sourceid);
$xtpl->assign('title', $title);
$xtpl->assign('link', $link);
$xtpl->assign('logo', $logo);

if (!empty($logo)) {
    $xtpl->parse('main.logo');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if ($sourceid or $savecat) {
    $xtpl->parse('main.scroll');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
