<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$per_page = 50;
$array_allowed_type = ['all', 'file', 'image', 'flash', 'video'];
$array_allowed_author = ['all', 'me'];
$array_allowed_order = ['newest', 'oldest', 'name'];

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (isset($check_allow_upload_dir['view_dir']) and isset($array_dirname[$path])) {
    if ($refresh) {
        if ($sys_info['allowed_set_time_limit']) {
            set_time_limit(0);
        }
        nv_filesListRefresh($path);
    }

    $page = $nv_Request->get_int('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', '');
    if (!in_array($type, $array_allowed_type)) {
        reset($array_allowed_type);
        $type = current($array_allowed_type);
    }
    $order = $nv_Request->get_title('order', 'get', '');
    if (!in_array($order, $array_allowed_order)) {
        reset($array_allowed_order);
        $order = current($array_allowed_order);
    }
    $author = $nv_Request->get_title('author', 'get', '');
    if (!in_array($author, $array_allowed_author)) {
        reset($array_allowed_author);
        $author = current($array_allowed_author);
    }
    $q = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('q', 'get')), ENT_QUOTES));
    $selectfile = array_filter(array_unique(array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('imgfile', 'get', '')), ENT_QUOTES)))));

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;path=' . $path . '&amp;type=' . $type . '&amp;order=' . $order . '&amp;author=' . $author;
    $check_like = false;

    $db->sqlreset();
    if (empty($q)) {
        $_where = 'did = ' . $array_dirname[$path];

        // Tìm theo kiểu
        if ($type != 'all' and $type != 'file') {
            $_where .= " AND type='" . $type . "'";
        }

        // Tìm theo người upload
        if ($author == 'me') {
            $_where .= ' AND userid=' . $admin_info['userid'];
        }
        $db->select('COUNT(*)')->from(NV_UPLOAD_GLOBALTABLE . '_file')->where($_where);

        $num_items = $db->query($db->sql())->fetchColumn();

        $db->select('*');
        if ($order == 'oldest') {
            $db->order('mtime ASC');
        } elseif ($order == 'name') {
            $db->order('title ASC');
        } else {
            $db->order('mtime DESC');
        }
    } else {
        $check_like = true;

        $_where = "(t2.dirname = '" . $path . "' OR t2.dirname LIKE '" . $path . "/%')";
        $_where .= " AND (t1.title LIKE :keyword1 OR t1.alt LIKE :keyword2)";

        // Tìm theo kiểu
        if ($type != 'all' and $type != 'file') {
            $_where .= " AND t1.type='" . $type . "'";
        }

        // Tìm theo người upload
        if ($author == 'me') {
            $sql .= ' AND t1.userid=' . $admin_info['userid'];
        }

        $db->select('COUNT(*)')->from(NV_UPLOAD_GLOBALTABLE . '_file t1')->join('INNER JOIN ' . NV_UPLOAD_GLOBALTABLE . '_dir t2 ON t1.did = t2.did')->where($_where);

        $sth = $db->prepare($db->sql());
        $keyword = '%' . addcslashes($q, '_%') . '%';
        $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
        $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
        $sth->execute();

        $num_items = $sth->fetchColumn();

        $db->select('t1.*, t2.dirname');
        if ($order == 'oldest') {
            $db->order('t1.mtime ASC');
        } elseif ($order == 'name') {
            $db->order('t1.title ASC');
        } else {
            $db->order('t1.mtime DESC');
        }
        $base_url .= '&amp;q=' . urlencode($q);
    }

    if ($num_items) {
        $tpl = new \NukeViet\Template\Smarty();
        $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $tpl->assign('LANG', $nv_Lang);

        $db->limit($per_page)->offset(($page - 1) * $per_page);
        $sth = $db->prepare($db->sql());
        if ($check_like) {
            $keyword = '%' . addcslashes($q, '_%') . '%';

            $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
            $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
        }
        $sth->execute();
        $array_files = [];
        while ($file = $sth->fetch()) {
            $file['data'] = $file['sizes'];
            if ($file['type'] == 'image' or $file['ext'] == 'swf') {
                $file['size'] = str_replace('|', ' x ', $file['sizes']) . ' pixels';
            } else {
                $file['size'] = nv_convertfromBytes($file['filesize']);
            }

            $file['data'] .= '|' . $file['ext'] . '|' . $file['type'] . '|' . nv_convertfromBytes($file['filesize']) . '|' . $file['userid'] . '|' . nv_date('l, d F Y, H:i:s P', $file['mtime']) . '|';
            $file['data'] .= (empty($q)) ? '' : $file['dirname'];
            $file['data'] .= '|' . $file['mtime'];

            $file['is_img'] = $file['type'] == 'image' ? 'true' : 'false';
            $file['src'] = NV_BASE_SITEURL . $file['src'] . '?' . $file['mtime'];

            $file['nameLong'] = substr($file['title'], 0, 0 - strlen($file['ext']) - 1);
            if (strlen($file['nameLong']) >= 30) {
                $file['nameLong'] = substr($file['nameLong'], 0, 28) . '..';
            }
            $file['nameLong'] = $file['nameLong'] . '.' . $file['ext'];

            $array_files[] = $file;
        }

        $tpl->assign('ARRAY_FILES', $array_files);
        $tpl->assign('SELECTFILE', $selectfile);

        $contents = $tpl->fetch('listimg.tpl');

        nv_jsonOutput([
            'body' => $contents,
            'nav' => nv_generate_page($base_url, $num_items, $per_page, $page)
        ]);
    }
}

nv_jsonOutput([
    'body' => '',
    'nav' => ''
]);
