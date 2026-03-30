<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$groups_list = nv_groups_list();

// Nạp lại thành phần con
if ($nv_Request->isset_request('reload', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit('NO_' . $nv_Lang->getGlobal('error_checkss'));
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $array_sub_id = [];

    $stmt = $db->prepare('SELECT id, parentid, module_name, lev, subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetch();
    $stmt->closeCursor();

    if (empty($rows)) {
        exit('NO_' . $nv_Lang->getModule('action_menu_reload_none_success'));
    }

    $mod_name = $rows['module_name'];
    $mod_data = $site_mods[$rows['module_name']]['module_data'];
    $mod_file = $site_mods[$rows['module_name']]['module_file'];

    // Xoa menu cu
    if (!empty($rows['subitem'])) {
        $rows['subitem'] = explode(',', $rows['subitem']);
        $stmt_parent = $db->prepare('SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');

        foreach ($rows['subitem'] as $subid) {
            $stmt_parent->bindValue(':id', $subid, PDO::PARAM_INT);
            $stmt_parent->execute();
            $parentid = $stmt_parent->fetchColumn();
            nv_menu_del_sub($subid, $parentid);
        }
    }

    if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$rows['module_name']]['module_file'] . '/menu.php')) {
        include NV_ROOTDIR . '/modules/' . $site_mods[$rows['module_name']]['module_file'] . '/menu.php';

        $stmt = $db->prepare('SELECT MAX(weight) AS max_weight, MAX(sort) AS max_sort FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid = :parentid');
        $stmt->bindValue(':parentid', $rows['parentid'], PDO::PARAM_INT);
        $stmt->execute();
        $_row_max = $stmt->fetch();
        $stmt->closeCursor();
        $weight = $_row_max ? $_row_max['max_weight'] : 0;
        $sort = $_row_max ? $_row_max['max_sort'] : 0;

        // Nap lai menu moi
        foreach ($array_item as $key => $item) {
            $pid = (isset($item['parentid'])) ? $item['parentid'] : 0;
            if (empty($pid)) {
                ++$weight;
                ++$sort;
                $groups_view = (isset($item['groups_view'])) ? $item['groups_view'] : '6';
                $parentid = nv_menu_insert_id($mid, $id, $item['title'], $weight, $sort, 0, $mod_name, $item['alias'], $groups_view);
                nv_menu_insert_submenu($mid, $parentid, $sort, $weight, $mod_name, $array_item, $key);
                $array_sub_id[] = $parentid;
            }
        }
    }

    // Thêm menu từ các funtion
    if (!empty($site_mods[$mod_name]['funcs'])) {
        foreach ($site_mods[$mod_name]['funcs'] as $key => $sub_item) {
            if ($sub_item['in_submenu'] == 1) {
                ++$weight;
                ++$sort;
                $array_sub_id[] = nv_menu_insert_id($mid, $id, $sub_item['func_custom_name'], $weight, $sort, 0, $mod_name, $key, $site_mods[$mod_name]['groups_view']);
            }
        }
    }

    if (!empty($array_sub_id)) {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE id = :id');
        $stmt->bindValue(':subitem', implode(',', $array_sub_id), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        menu_fix_order($mid, $id);
        $nv_Cache->delMod($module_name);
    }
    exit('OK_' . $nv_Lang->getModule('action_menu_reload_success'));
}

// Tạo/sửa menu
if ($nv_Request->get_title('action', 'post') == 'row') {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $post = [];
    $post['title'] = $nv_Request->get_title('title', 'post', '', 250);
    if (empty($post['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('error_menu_name')
        ]);
    }

    $_groups_post = $nv_Request->get_typed_array('groups_view', 'post', 'int', []);
    $post['groups_view'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $post['id'] = $nv_Request->get_int('id', 'post', 0);
    $post['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $post['mid'] = $nv_Request->get_int('item_menu', 'post', 0);
    $post['link'] = $nv_Request->get_string('link', 'post', '', 0, 250);
    $post['note'] = $nv_Request->get_title('note', 'post', '', 250);
    $post['module_name'] = $nv_Request->get_title('module_name', 'post', '', 250);
    $post['op'] = $nv_Request->get_title('func', 'post', '', 250);
    $post['target'] = $nv_Request->get_int('target', 'post', 0);
    $post['active_type'] = $nv_Request->get_int('active_type', 'post', 0);
    $post['css'] = $nv_Request->get_title('css', 'post', '', 250);

    $post['icon'] = $nv_Request->get_string('icon', 'post', '');
    if (nv_is_file($post['icon'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $post['icon'] = substr($post['icon'], $lu);
    } else {
        $post['icon'] = '';
    }

    $post['image'] = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($post['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $post['image'] = substr($post['image'], $lu);
    } else {
        $post['image'] = '';
    }

    if (!empty($post['link']) and !nv_is_url($post['link'], true)) {
        $post['link'] = '';
    }

    // Nếu có link và không chỉ ra module liên kết
    // Sẽ phân tích link để xác định module và op
    if (!empty($post['link']) and empty($post['module_name'])) {
        $prs = parse_url($post['link']);
        if ((empty($prs['host']) or in_array($prs['host'], $global_config['my_domains'], true))) {
            // Nếu link chưa rewrite
            if (str_ends_with($prs['path'], 'index.php')) {
                if (!empty($prs['query'])) {
                    parse_str($prs['query'], $output);
                    if (!empty($output[NV_NAME_VARIABLE])) {
                        $post['module_name'] = $output[NV_NAME_VARIABLE];
                    }
                    if (!empty($output[NV_OP_VARIABLE])) {
                        $post['op'] = $output[NV_OP_VARIABLE];
                    }
                }
            }
            // Nếu link đã rewrite
            elseif (!empty($prs['path'])) {
                $base_siteurl_quote = nv_preg_quote(NV_BASE_SITEURL);
                if ($global_config['rewrite_endurl'] != $global_config['rewrite_exturl'] and preg_match('/^' . $base_siteurl_quote . '([a-z0-9\-]+)' . nv_preg_quote($global_config['rewrite_exturl']) . '$/i', $prs['path'], $matches)) {
                    $post['module_name'] = $global_config['rewrite_op_mod'] ?: 'page';
                    $post['op'] = $matches[1];
                } elseif (preg_match('/^' . $base_siteurl_quote . '([a-z0-9\-\_\.\/\+]+)(' . nv_preg_quote($global_config['rewrite_endurl']) . '|' . nv_preg_quote($global_config['rewrite_exturl']) . ')$/i', $prs['path'], $matches)) {
                    $request_uri_array = explode('/', $matches[1], 3);
                    if (in_array($request_uri_array[0], array_keys($language_array), true)) {
                        if ($request_uri_array[0] == NV_LANG_DATA and isset($request_uri_array[1][0])) {
                            $post['module_name'] = $request_uri_array[1];
                            if (isset($request_uri_array[2][0])) {
                                $post['op'] = $request_uri_array[2];
                            }
                        }
                    } elseif (isset($request_uri_array[0][0])) {
                        $post['module_name'] = $request_uri_array[0];
                        if (isset($request_uri_array[1][0])) {
                            $lop = strlen($request_uri_array[0]) + 1;
                            $post['op'] = substr($matches[1], $lop);
                        }
                    }
                }
            }
        }
    }

    if (empty($post['module_name']) and !empty($post['link'])) {
        // Kiểm tra để tách link module nếu nhập trực tiếp link đúng cấu trúc của module
        $checklink = explode('/', $post['link']);
        foreach ($checklink as $k => $v) {
            if (isset($site_mods[$v])) {
                $k1 = $k + 1;
                $post['module_name'] = $v;
                if (isset($checklink[$k1]) and isset($site_mods[$v]['funcs'][$checklink[$k1]])) {
                    $post['op'] = $checklink[$k1];
                }
                break;
            }
        }
    }

    $mid_old = $nv_Request->get_int('mid', 'post', 0);
    $pa_old = $nv_Request->get_int('pa', 'post', 0);

    if ($post['id'] == 0) {
        $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid AND parentid = :parentid');
        $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
        $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
        $stmt->execute();
        $weight = $stmt->fetchColumn();

        $weight = (int) $weight + 1;

        $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_rows (parentid, mid, title, link, icon, image, note, weight, sort, lev, subitem, groups_view, module_name, op, target, css, active_type, status) VALUES (:parentid, :mid, :title, :link, :icon, :image, :note, :weight, 0, 0, '', :groups_view, :module_name, :op, :target, :css, :active_type, 1)");
        $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
        $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $post['title'], PDO::PARAM_STR);
        $stmt->bindValue(':link', $post['link'], PDO::PARAM_STR);
        $stmt->bindValue(':icon', $post['icon'], PDO::PARAM_STR);
        $stmt->bindValue(':image', $post['image'], PDO::PARAM_STR);
        $stmt->bindValue(':note', $post['note'], PDO::PARAM_STR);
        $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
        $stmt->bindValue(':groups_view', $post['groups_view'], PDO::PARAM_STR);
        $stmt->bindValue(':module_name', $post['module_name'], PDO::PARAM_STR);
        $stmt->bindValue(':op', $post['op'], PDO::PARAM_STR);
        $stmt->bindValue(':target', $post['target'], PDO::PARAM_INT);
        $stmt->bindValue(':css', $post['css'], PDO::PARAM_STR);
        $stmt->bindValue(':active_type', $post['active_type'], PDO::PARAM_INT);
        $stmt->execute();
        $insert_id = $db->lastInsertId();

        menu_fix_order($post['mid']);

        if ($post['parentid'] != 0) {
            $arr_item_menu = [];
            $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid AND parentid = :parentid');
            $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
            $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $arr_item_menu[] = $row['id'];
            }
            $stmt->closeCursor();

            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE mid = :mid AND id = :id');
            $stmt->bindValue(':subitem', implode(',', $arr_item_menu), PDO::PARAM_STR);
            $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
            $stmt->bindValue(':id', $post['parentid'], PDO::PARAM_INT);
            $stmt->execute();
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add row menu', 'Row menu id: ' . $insert_id . ' of Menu id: ' . $post['mid'], $admin_info['userid']);
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET parentid = :parentid, mid = :mid, title = :title, link = :link, icon = :icon, image = :image, note = :note, groups_view = :groups_view, module_name = :module_name, op = :op, target = :target, css = :css, active_type = :active_type WHERE id = :id');

        $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
        $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $post['title'], PDO::PARAM_STR);
        $stmt->bindValue(':link', $post['link'], PDO::PARAM_STR);
        $stmt->bindValue(':icon', $post['icon'], PDO::PARAM_STR);
        $stmt->bindValue(':image', $post['image'], PDO::PARAM_STR);
        $stmt->bindValue(':note', $post['note'], PDO::PARAM_STR);
        $stmt->bindValue(':groups_view', $post['groups_view'], PDO::PARAM_STR);
        $stmt->bindValue(':module_name', $post['module_name'], PDO::PARAM_STR);
        $stmt->bindValue(':op', $post['op'], PDO::PARAM_STR);
        $stmt->bindValue(':target', $post['target'], PDO::PARAM_INT);
        $stmt->bindValue(':css', $post['css'], PDO::PARAM_STR);
        $stmt->bindValue(':active_type', $post['active_type'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($pa_old != $post['parentid']) {
            $stmt = $db->prepare('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid AND parentid = :parentid');
            $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
            $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
            $stmt->execute();
            $weight = $stmt->fetchColumn();

            $weight = (int) $weight + 1;

            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight = :weight WHERE id = :id');
            $stmt->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt->bindValue(':id', $post['id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        menu_fix_order($post['mid']);

        if ($post['mid'] != $mid_old) {
            menu_fix_order($mid_old);
        }

        if ($post['parentid'] != 0) {
            $arr_item_menu = [];
            $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid AND parentid = :parentid');
            $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
            $stmt->bindValue(':parentid', $post['parentid'], PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $arr_item_menu[] = $row['id'];
            }
            $stmt->closeCursor();

            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE mid = :mid AND id = :id');
            $stmt->bindValue(':subitem', implode(',', $arr_item_menu), PDO::PARAM_STR);
            $stmt->bindValue(':mid', $post['mid'], PDO::PARAM_INT);
            $stmt->bindValue(':id', $post['parentid'], PDO::PARAM_INT);
            $stmt->execute();
        }

        if ($pa_old != 0) {
            $arr_item_menu = [];
            $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = :mid AND parentid = :parentid');
            $stmt->bindValue(':mid', $mid_old, PDO::PARAM_INT);
            $stmt->bindValue(':parentid', $pa_old, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $arr_item_menu[] = $row['id'];
            }
            $stmt->closeCursor();

            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET subitem = :subitem WHERE mid = :mid AND id = :id');
            $stmt->bindValue(':subitem', implode(',', $arr_item_menu), PDO::PARAM_STR);
            $stmt->bindValue(':mid', $mid_old, PDO::PARAM_INT);
            $stmt->bindValue(':id', $pa_old, PDO::PARAM_INT);
            $stmt->execute();
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit row menu', 'Row menu id: ' . $post['id'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'refresh' => true
    ]);
}

// Lấy html khi thay đổi khối menu
if ($nv_Request->get_title('action', 'post') == 'link_menu' and $nv_Request->isset_request('mid,parentid', 'post')) {
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    $menulist = nv_get_menulist($mid);

    $opts = '<option value="0"' . ($parentid == 0 ? ' selected' : '') . '>' . $nv_Lang->getModule('cat0') . '</option>';
    foreach ($menulist as $row) {
        if ($row['parentid'] == 0) {
            $sel = ($parentid == $row['id']) ? ' selected' : '';
            $opts .= '<option value="' . $row['id'] . '"' . $sel . '>' . nv_htmlspecialchars($row['name']) . '</option>';
            $array_subcat = [];
            nv_menu_get_subcat($row['id'], $menulist, $array_subcat);
            foreach ($array_subcat as $subrow) {
                $sel = ($parentid == $subrow['id']) ? ' selected' : '';
                $opts .= '<option value="' . $subrow['id'] . '"' . $sel . '>' . nv_htmlspecialchars($subrow['name']) . '</option>';
            }
        }
    }
    nv_htmlOutput($opts);
}

// Lấy các mục của module
if ($nv_Request->get_title('action', 'post') == 'link_module' and $nv_Request->isset_request('module', 'post')) {
    $mod_name = $nv_Request->get_title('module', 'post', '');

    $stmt = $db->prepare('SELECT title, module_file, module_data FROM ' . NV_MODULES_TABLE . ' WHERE title = :module');
    $stmt->bindValue(':module', $mod_name, PDO::PARAM_STR);
    $stmt->execute();
    $_row_module = $stmt->fetch();
    $stmt->closeCursor();

    $mod_name = $_row_module ? $_row_module['title'] : '';
    $mod_file = $_row_module ? $_row_module['module_file'] : '';
    $mod_data = $_row_module ? $_row_module['module_data'] : '';

    if (empty($mod_name)) {
        exit($nv_Lang->getModule('add_error_module'));
    }

    $array_item = [];
    if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php')) {
        include NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php';
    }
    // Lấy menu từ các chức năng của module
    $funcs_item = $site_mods[$mod_name]['funcs'];
    foreach ($funcs_item as $key => $sub_item) {
        if ($sub_item['in_submenu'] == 1) {
            $array_item[] = [
                'key' => $key,
                'title' => $sub_item['func_custom_name'],
                'alias' => $key
            ];
        }
    }

    if (empty($array_item)) {
        nv_htmlOutput('');
    }

    $opts = '<option value="">' . $nv_Lang->getModule('no') . '</option>';
    $sps = [];
    $subs = [];
    $i = 0;
    foreach ($array_item as $key => $item1) {
        $item_parentid = $item1['parentid'] ?? 0;
        if (empty($item_parentid)) {
            ++$i;
            $sp_title = $i . '.';
            $subs[$key] = 0;
            $sps[$key] = $sp_title;
            $item1['name'] = $sp_title . ' ' . $item1['title'];
            $item1['module'] = $mod_name;
            $opts .= '<option value="' . nv_htmlspecialchars($item1['alias']) . '" data-title="' . nv_htmlspecialchars($item1['title']) . '">' . nv_htmlspecialchars($item1['name']) . '</option>';

            $array_submenu = [];
            nv_menu_get_submenu($key, '', $array_item, $sps, $subs);
            foreach ($array_submenu as $item2) {
                $opts .= '<option value="' . nv_htmlspecialchars($item2['alias']) . '" data-title="' . nv_htmlspecialchars($item2['title']) . '">' . nv_htmlspecialchars($item2['name']) . '</option>';
            }
        }
    }
    nv_htmlOutput($opts);
}

// Thay đổi thứ tự menu
if ($nv_Request->get_title('action', 'post') == 'chang_weight' and $nv_Request->isset_request('id,mid,parentid,new_weight', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit('NO_' . $nv_Lang->getGlobal('error_checkss'));
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id AND parentid = :parentid');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count) {
        $stmt = $db->prepare('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id != :id AND parentid = :parentid AND mid = :mid ORDER BY weight ASC');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
        $stmt->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt->execute();

        $weight = 0;
        $stmt_update = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight = :weight WHERE id = :row_id');

        while ($row = $stmt->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $stmt_update->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmt_update->bindValue(':row_id', $row['id'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
        $stmt->closeCursor();

        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight = :weight WHERE id = :id AND parentid = :parentid');
        $stmt->bindValue(':weight', $new_weight, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':parentid', $parentid, PDO::PARAM_INT);
        $stmt->execute();

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Change weight row menu', 'Row menu id: ' . $id . ', new weight: ' . $new_weight, $admin_info['userid']);
        menu_fix_order($mid);
        $nv_Cache->delMod($module_name);
    }

    exit('OK');
}

// Thay đổi trạng thái menu
if ($nv_Request->get_title('action', 'post') == 'change_active' and $nv_Request->isset_request('id', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit('NO_' . $nv_Lang->getGlobal('error_checkss'));
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $stmt = $db->prepare('SELECT id, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    $stmt->closeCursor();

    if (!empty($row)) {
        $new_status = (int) $row['status'] ? 0 : 1;
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status = :status WHERE id = :id');
        $stmt->bindValue(':status', $new_status, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $nv_Cache->delMod($module_name);
        $text = $new_status ? 'Active row menu' : 'Inactive row menu';
        nv_insert_logs(NV_LANG_DATA, $module_name, $text, 'Row menu id: ' . $id, $admin_info['userid']);
    }
    exit('OK');
}

// Xoá menu
if ($nv_Request->get_title('action', 'post') == 'delete' and $nv_Request->isset_request('id,mid,parentid', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit('NO_' . $nv_Lang->getGlobal('error_checkss'));
    }

    $id = $nv_Request->get_int('id', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    if (!empty($id) and nv_menu_del_sub($id, $parentid)) {
        menu_fix_order($mid);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Del row menu', 'Row menu id: ' . $id . ' of Menu-block id: ' . $mid, $admin_info['userid']);
        $nv_Cache->delMod($module_name);
    }
    exit('OK');
}

// Xóa nhiều menu
if ($nv_Request->get_title('action', 'post') == 'delete' and $nv_Request->isset_request('idcheck,mid,parentid', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        exit('NO_' . $nv_Lang->getGlobal('error_checkss'));
    }

    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $array_id = $nv_Request->get_title('idcheck', 'post', '');

    if (!empty($array_id)) {
        $array_id = array_map('intval', explode(',', $array_id));
        foreach ($array_id as $id) {
            nv_menu_del_sub($id, $parentid);
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, 'Del row menu', 'Row menu id: ' . implode(',', $array_id), $admin_info['userid']);
        menu_fix_order($mid);
        $nv_Cache->delMod($module_name);
    }
    exit('OK');
}

// Default variable
$error = '';
$pg = [
    'mid' => $nv_Request->get_int('mid', 'get', 0),
    'parentid' => $nv_Request->get_int('parentid', 'get', 0)
];
$post = [
    'active_type' => 0,
    'type_menu' => '',
    'target' => '',
    'module_name' => '',
    'css' => '',
    'groups_view' => [6],
    'mid' => $pg['mid'],
    'id' => $nv_Request->get_int('id', 'get', 0),
    'parentid' => $pg['parentid']
];

// Danh sách các khối menu
$menublocks = nv_list_menu();
if (empty($menublocks)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks');
}

// Bắt buộc phải có khối menu
if (empty($pg['mid']) or empty($menublocks[$pg['mid']])) {
    $pg['mid'] = array_key_first($menublocks);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&mid=' . $pg['mid']);
}

// Danh sách các menu của khối
$menulist = nv_get_menulist($pg['mid']);

// Lấy content cho modal thêm/sửa menu
if ($nv_Request->get_title('action', 'get') == 'add' or !empty($post['id'])) {
    // Nếu có ID của menu
    if (!empty($post['id'])) {
        if (empty($menulist[$post['id']])) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&mid=' . $post['mid']);
        }

        $post = $menulist[$post['id']];
        $post['groups_view'] = array_map('intval', explode(',', $post['groups_view']));
        $post['link'] = nv_htmlspecialchars($post['link']);
    }

    if (!empty($post['icon']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['icon'])) {
        $post['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['icon'];
    } else {
        $post['icon'] = '';
    }

    if (!empty($post['image']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['image'])) {
        $post['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['image'];
    } else {
        $post['image'] = '';
    }

    // Menu blocks options
    $menublocks_options = [];
    foreach ($menublocks as $arr) {
        $menublocks_options[] = [
            'key' => $arr['id'],
            'val' => $arr['title'],
            'selected' => ($arr['id'] == $post['mid'])
        ];
    }

    // Cats options
    $cats_options = [
        [
            'key' => 0,
            'title' => $nv_Lang->getModule('cat0'),
            'selected' => ($post['parentid'] == 0)
        ]
    ];
    foreach ($menulist as $row) {
        if ($row['parentid'] == 0) {
            $cats_options[] = [
                'key' => $row['id'],
                'title' => $row['name'],
                'selected' => ($post['parentid'] == $row['id'])
            ];
            $array_subcat = [];
            nv_menu_get_subcat($row['id'], $menulist, $array_subcat);
            foreach ($array_subcat as $subrow) {
                $cats_options[] = [
                    'key' => $subrow['id'],
                    'title' => $subrow['name'],
                    'selected' => ($post['parentid'] == $subrow['id'])
                ];
            }
        }
    }

    // Modules options
    $site_mods_row = $site_mods;
    unset($site_mods_row['menu'], $site_mods_row['comment'], $site_mods_row['zalo']);
    $modules_options = [];
    foreach ($site_mods_row as $key => $mod) {
        $modules_options[] = [
            'key' => $key,
            'title' => $mod['custom_title'],
            'selected' => ($key == $post['module_name'])
        ];
    }

    // Funcs options (khi có module được chọn)
    $funcs_options = [];
    if (!empty($post['module_name']) and isset($site_mods[$post['module_name']])) {
        $mod_name = $post['module_name'];
        $mod_file = $site_mods[$mod_name]['module_file'];
        $mod_data = $site_mods[$mod_name]['module_data'];
        $array_item = [];
        if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php')) {
            include NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php';
        }
        $funcs_item = $site_mods[$mod_name]['funcs'];
        foreach ($funcs_item as $key => $sub_item) {
            if ($sub_item['in_submenu'] == 1) {
                $array_item[$key] = [
                    'key' => $key,
                    'title' => $sub_item['func_custom_name'],
                    'alias' => $key
                ];
            }
        }
        if (!empty($array_item)) {
            $sps = [];
            $subs = [];
            $i = 0;
            foreach ($array_item as $key => $item) {
                $item_parentid = $item['parentid'] ?? 0;
                if (empty($item_parentid)) {
                    ++$i;
                    $sp_title = $i . '.';
                    $sps[$key] = $sp_title;
                    $subs[$key] = 0;
                    $item['name'] = $sp_title . ' ' . $item['title'];
                    $item['module'] = $mod_name;
                    $item['selected'] = ($item['alias'] == $post['op']);
                    $funcs_options[] = $item;
                    $array_submenu = [];
                    nv_menu_get_submenu($key, $post['op'], $array_item, $sps, $subs);
                    foreach ($array_submenu as $item2) {
                        $item2['selected'] = ($item2['alias'] == $post['op']);
                        $funcs_options[] = $item2;
                    }
                }
            }
        }
    }

    // Groups options
    $groups_options = [];
    foreach ($groups_list as $key => $title) {
        $groups_options[] = [
            'key' => $key,
            'title' => $title,
            'selected' => (!empty($post['groups_view']) and in_array((int) $key, $post['groups_view'], true))
        ];
    }

    // Target options
    $target_options = [];
    foreach ($type_target as $key => $title) {
        $target_options[] = [
            'key' => $key,
            'title' => $title,
            'selected' => ($key == $post['target'])
        ];
    }

    // Active type options
    $active_type_options = [];
    for ($i = 0; $i <= 2; ++$i) {
        $active_type_options[] = [
            'key' => $i,
            'title' => $nv_Lang->getModule('add_type_active_' . $i),
            'selected' => ($post['active_type'] == $i)
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main-row.tpl'));

    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('FORM_CAPTION', $post['id'] ? $nv_Lang->getModule('edit_menu') : $nv_Lang->getModule('add_item'));
    $tpl->assign('DATA', $post);
    $tpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);
    $tpl->assign('MENUBLOCKS_OPTIONS', $menublocks_options);
    $tpl->assign('CATS_OPTIONS', $cats_options);
    $tpl->assign('MODULES_OPTIONS', $modules_options);
    $tpl->assign('FUNCS_OPTIONS', $funcs_options);
    $tpl->assign('GROUPS_OPTIONS', $groups_options);
    $tpl->assign('TARGET_OPTIONS', $target_options);
    $tpl->assign('ACTIVE_TYPE_OPTIONS', $active_type_options);

    $contents = $tpl->fetch('main-row.tpl');
    nv_htmlOutput($contents);
}

$array_mod_title = [];
$parentid = $post['parentid'];
while ($parentid > 0) {
    $array_item_i = $menulist[$parentid];
    $array_mod_title[] = [
        'title' => $array_item_i['title'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&mid=' . $post['mid'] . '&parentid=' . $parentid
    ];
    $parentid = $array_item_i['parentid'];
}
$array_mod_title[] = [
    'title' => $menublocks[$post['mid']]['title'],
    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&mid=' . $post['mid']
];
$array_mod_title[] = [
    'title' => $nv_Lang->getModule('menu_manager'),
    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
];
krsort($array_mod_title, SORT_NUMERIC);

// Active last item
$s = count($array_mod_title) - 1;
$array_mod_title[$s]['active'] = true;

$parentid_menulist = [];
foreach ($menulist as $menu_id => $row) {
    if ($row['parentid'] == $pg['parentid']) {
        $parentid_menulist[$row['weight']] = $row['id'];
    }
}

$array = [];
$weight_options = [];

if (!empty($parentid_menulist)) {
    ksort($parentid_menulist);
    $parentid_menulist = array_values($parentid_menulist);
    $num = count($parentid_menulist);
    $weight_options = range(1, $num);

    $stmt_count = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid = :parentid');

    foreach ($parentid_menulist as $menu_id) {
        $row = $menulist[$menu_id];

        $stmt_count->bindValue(':parentid', $row['id'], PDO::PARAM_INT);
        $stmt_count->execute();
        $row['nu'] = (int) $stmt_count->fetchColumn();

        $row['sub'] = count(array_filter(explode(',', $row['subitem'])));

        $array_groups_view = array_map('intval', explode(',', $row['groups_view']));
        $row['groups_view'] = [];
        foreach ($array_groups_view as $_group_id) {
            if (isset($groups_list[$_group_id])) {
                $row['groups_view'][] = $groups_list[$_group_id];
            }
        }

        if (!empty($row['icon']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['icon'])) {
            $row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['icon'];
        } else {
            $row['icon'] = '';
        }

        if (!empty($row['image']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
            $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
        } else {
            $row['image'] = '';
        }

        $row['link'] = nv_htmlspecialchars($row['link']);
        $row['status'] = (bool) $row['status'];
        $row['url_title'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;mid=' . $post['mid'] . '&amp;parentid=' . $row['id'];
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;mid=' . $post['mid'] . '&amp;id=' . $row['id'];

        $func_menu = 0;
        $row['can_reload'] = false;
        if (isset($site_mods[$row['module_name']])) {
            $mod_site = $site_mods[$row['module_name']];
            $mod_file_row = $mod_site['module_file'];
            foreach ($mod_site['funcs'] as $funcs) {
                if ($funcs['in_submenu']) {
                    ++$func_menu;
                }
            }
            if (empty($row['op']) and (file_exists(NV_ROOTDIR . '/modules/' . $mod_file_row . '/menu.php') or $func_menu > 0)) {
                $row['can_reload'] = true;
            }
        }

        $array[] = $row;
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('main.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('PAGE', $pg);
$tpl->assign('MENUBLOCKS', array_values($menublocks));
$tpl->assign('ARRAY', $array);
$tpl->assign('WEIGHT_OPTIONS', $weight_options);

$contents = $tpl->fetch('main.tpl');

$page_title = $nv_Lang->getModule('menu_manager');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
