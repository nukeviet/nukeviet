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

$per_page = 50;

$check_allow_upload_dir = nv_check_allow_upload_dir($path);

if (isset($check_allow_upload_dir['view_dir']) and isset($array_dirname[$path])) {
    if ($refresh) {
        if ($sys_info['allowed_set_time_limit']) {
            set_time_limit(0);
        }
        nv_filesListRefresh($path);
    }

    $page = $nv_Request->get_int('page', 'get', 1);
    $type = $nv_Request->get_title('type', 'get', 'file');
    $order = $nv_Request->get_int('order', 'get', 0);

    $q = nv_string_to_filename(htmlspecialchars(trim($nv_Request->get_string('q', 'get')), ENT_QUOTES));

    $selectfile = array_filter(array_unique(array_map('basename', explode('|', htmlspecialchars(trim($nv_Request->get_string('imgfile', 'get', '')), ENT_QUOTES)))));

    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;path=' . $path . '&amp;type=' . $type . '&amp;order=' . $order;

    $check_like = false;

    $db->sqlreset();
    if (empty($q)) {
        $_where = 'did = ' . $array_dirname[$path];
        if ($type == 'image' or $type == 'flash') {
            $_where .= " AND type='" . $type . "'";
        }
        if ($nv_Request->isset_request('author', 'get')) {
            $_where .= ' AND userid=' . $admin_info['userid'];
            $base_url .= '&amp;author';
        }
        $db->select('COUNT(*)')->from(NV_UPLOAD_GLOBALTABLE . '_file')->where($_where);

        $num_items = $db->query($db->sql())->fetchColumn();

        $db->select('*');
        if ($order == 1) {
            $db->order('mtime ASC');
        } elseif ($order == 2) {
            $db->order('title ASC');
        } else {
            $db->order('mtime DESC');
        }
    } else {
        $check_like = true;

        $_where = "(t2.dirname = '" . $path . "' OR t2.dirname LIKE '" . $path . "/%')";
        $_where .= ' AND (t1.title LIKE :keyword1 OR t1.alt LIKE :keyword2)';

        if ($type == 'image' or $type == 'flash') {
            $_where .= " AND t1.type='" . $type . "'";
        }
        if ($nv_Request->isset_request('author', 'get')) {
            $sql .= ' AND t1.userid=' . $admin_info['userid'];
            $base_url .= '&amp;author';
        }
        $db->select('COUNT(*)')->from(NV_UPLOAD_GLOBALTABLE . '_file t1')->join('INNER JOIN ' . NV_UPLOAD_GLOBALTABLE . '_dir t2 ON t1.did = t2.did')->where($_where);

        $sth = $db->prepare($db->sql());
        $keyword = '%' . addcslashes($q, '_%') . '%';
        $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
        $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
        $sth->execute();

        $num_items = $sth->fetchColumn();

        $db->select('t1.*, t2.dirname');
        if ($order == 1) {
            $db->order('t1.mtime ASC');
        } elseif ($order == 2) {
            $db->order('t1.title ASC');
        } else {
            $db->order('t1.mtime DESC');
        }
        $base_url .= '&amp;q=' . $q;
    }

    if ($num_items) {
        $xtpl = new XTemplate('listimg.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

        $db->limit($per_page)->offset(($page - 1) * $per_page);
        $sth = $db->prepare($db->sql());
        if ($check_like) {
            $keyword = '%' . addcslashes($q, '_%') . '%';

            $sth->bindParam(':keyword1', $keyword, PDO::PARAM_STR);
            $sth->bindParam(':keyword2', $keyword, PDO::PARAM_STR);
        }
        $sth->execute();
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
            $file['sel'] = in_array($file['title'], $selectfile, true) ? ' imgsel' : '';
            $file['src'] = NV_BASE_SITEURL . $file['src'] . '?' . $file['mtime'];

            $file['nameLong'] = substr($file['title'], 0, 0 - strlen($file['ext']) - 1);
            if (strlen($file['nameLong']) >= 30) {
                $file['nameLong'] = substr($file['nameLong'], 0, 28) . '..';
            }
            $file['nameLong'] = $file['nameLong'] . '.' . $file['ext'];

            $xtpl->assign('IMG', $file);
            $xtpl->parse('main.loopimg');
        }

        if (!empty($selectfile)) {
            $xtpl->assign('NV_CURRENTTIME', NV_CURRENTTIME);
            $xtpl->parse('main.imgsel');
        }

        if ($num_items > $per_page) {
            $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'imglist');
            $xtpl->assign('GENERATE_PAGE', $generate_page);
            $xtpl->parse('main.generate_page');
        }

        $xtpl->parse('main');
        $contents = $xtpl->text('main');

        include NV_ROOTDIR . '/includes/header.php';
        echo $contents;
        include NV_ROOTDIR . '/includes/footer.php';
    }
}

exit();
