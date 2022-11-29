<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$groups_list = nv_groups_list();

// Nạp lại thành phần con
if ($nv_Request->isset_request('reload', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $array_sub_id = [];

    $rows = $db->query('SELECT id, parentid, module_name, lev, subitem FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id)->fetch();

    $mod_name = $rows['module_name'];
    $mod_data = $site_mods[$rows['module_name']]['module_data'];
    $mod_file = $site_mods[$rows['module_name']]['module_file'];

    if (empty($rows)) {
        exit('NO_' . $lang_module['action_menu_reload_none_success']);
    }

    // Xoa menu cu
    if (!empty($rows['subitem'])) {
        $rows['subitem'] = explode(',', $rows['subitem']);
        foreach ($rows['subitem'] as $subid) {
            $sql = 'SELECT parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $subid;

            list($parentid) = $db->query($sql)->fetch(3);
            nv_menu_del_sub($subid, $parentid);
        }
    }

    if (file_exists(NV_ROOTDIR . '/modules/' . $site_mods[$rows['module_name']]['module_file'] . '/menu.php')) {
        include NV_ROOTDIR . '/modules/' . $site_mods[$rows['module_name']]['module_file'] . '/menu.php';

        list($sort, $weight) = $db->query('SELECT MAX(weight), MAX(sort) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid=' . $rows['parentid'])->fetch(3);

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
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem='" . implode(',', $array_sub_id) . "' WHERE id=" . $id);
        menu_fix_order($mid, $id);
        $nv_Cache->delMod($module_name);
    }
    exit('OK_' . $lang_module['action_menu_reload_success']);
}

// Tạo/sửa menu
if ($nv_Request->get_title('action', 'post') == 'row') {
    $post = [];
    $post['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 250);
    if (empty($post['title'])) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $lang_module['error_menu_name']
        ]);
    }

    $_groups_post = $nv_Request->get_typed_array('groups_view', 'post', 'int', []);
    $post['groups_view'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $post['id'] = $nv_Request->get_int('id', 'post', 0);
    $post['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $post['mid'] = $nv_Request->get_int('item_menu', 'post', 0);
    $post['link'] = $nv_Request->get_string('link', 'post', '', 0, 250);
    $post['note'] = nv_substr($nv_Request->get_title('note', 'post', '', 1), 0, 250);
    $post['module_name'] = nv_substr($nv_Request->get_title('module_name', 'post', '', 1), 0, 250);
    $post['op'] = nv_substr($nv_Request->get_title('func', 'post', '', 1), 0, 250);
    $post['target'] = $nv_Request->get_int('target', 'post', 0);
    $post['active_type'] = $nv_Request->get_int('active_type', 'post', 0);
    $post['css'] = nv_substr($nv_Request->get_title('css', 'post', '', 1), 0, 250);

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
                    $post['module_name'] = $global_config['rewrite_op_mod'] ? $global_config['rewrite_op_mod'] : 'page';
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
        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . (int) ($post['mid']) . ' AND parentid=' . (int) ($post['parentid'] . ' AND mid=' . $post['mid']))->fetchColumn();
        $weight = (int) $weight + 1;
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows
            (parentid, mid, title, link, icon, image, note, weight, sort, lev, subitem, groups_view,
            module_name, op, target, css, active_type, status) VALUES
            (' . (int) ($post['parentid']) . ', ' . (int) ($post['mid']) . ', :title, :link, :icon, :image, :note, ' . (int) $weight . ", 0, 0, '',
            :groups_view, :module_name, :op, " . (int) ($post['target']) . ', :css, ' . (int) ($post['active_type']) . ', 1
        )';

        $data_insert = [];
        $data_insert['title'] = $post['title'];
        $data_insert['link'] = $post['link'];
        $data_insert['icon'] = $post['icon'];
        $data_insert['image'] = $post['image'];
        $data_insert['note'] = $post['note'];
        $data_insert['groups_view'] = $post['groups_view'];
        $data_insert['module_name'] = $post['module_name'];
        $data_insert['op'] = $post['op'];
        $data_insert['css'] = $post['css'];
        $insert_id = $db->insert_id($sql, 'id', $data_insert);
        menu_fix_order($post['mid']);

        if ($post['parentid'] != 0) {
            $arr_item_menu = [];
            $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $post['mid'] . ' AND parentid=' . $post['parentid'];
            $result = $db->query($sql);

            while ($row = $result->fetch()) {
                $arr_item_menu[] = $row['id'];
            }

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem= '" . implode(',', $arr_item_menu) . "' WHERE mid= " . $post['mid'] . ' AND id=' . $post['parentid'];
            $db->query($sql);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Add row menu', 'Row menu id: ' . $insert_id . ' of Menu id: ' . $post['mid'], $admin_info['userid']);
    } else {
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
            parentid=' . (int) ($post['parentid']) . ',
            mid=' . (int) ($post['mid']) . ',
            title= :title,
            link= :link,
            icon= :icon,
            image= :image,
            note= :note,
            groups_view= :groups_view,
            module_name= :module_name,
            op= :op,
            target=' . (int) ($post['target']) . ',
            css= :css,
            active_type=' . (int) ($post['active_type']) . '
        WHERE id=' . (int) ($post['id']));

        $stmt->bindParam(':title', $post['title'], PDO::PARAM_STR);
        $stmt->bindParam(':link', $post['link'], PDO::PARAM_STR);
        $stmt->bindParam(':icon', $post['icon'], PDO::PARAM_STR);
        $stmt->bindParam(':image', $post['image'], PDO::PARAM_STR);
        $stmt->bindParam(':note', $post['note'], PDO::PARAM_STR);
        $stmt->bindParam(':groups_view', $post['groups_view'], PDO::PARAM_STR);
        $stmt->bindParam(':module_name', $post['module_name'], PDO::PARAM_STR);
        $stmt->bindParam(':op', $post['op'], PDO::PARAM_STR);
        $stmt->bindParam(':css', $post['css'], PDO::PARAM_STR);
        $stmt->execute();

        if ($pa_old != $post['parentid']) {
            $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . (int) ($post['mid']) . ' AND parentid=' . (int) ($post['parentid'] . ' '))->fetchColumn();
            $weight = (int) $weight + 1;

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . (int) $weight . ' WHERE id=' . (int) ($post['id']);
            $db->query($sql);
        }

        menu_fix_order($post['mid']);

        if ($post['mid'] != $mid_old) {
            menu_fix_order($mid_old);
        }

        if ($post['parentid'] != 0) {
            $arr_item_menu = [];
            $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid= ' . $post['mid'] . ' AND parentid=' . $post['parentid'];
            $result = $db->query($sql);
            while ($row = $result->fetch()) {
                $arr_item_menu[] = $row['id'];
            }

            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem='" . implode(',', $arr_item_menu) . "' WHERE mid=" . $post['mid'] . ' AND id=' . $post['parentid']);
        }

        if ($pa_old != 0) {
            $arr_item_menu = [];
            $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid= ' . $mid_old . ' AND parentid=' . $pa_old;
            $result = $db->query($sql);
            while ($row = $result->fetch()) {
                $arr_item_menu[] = $row['id'];
            }

            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_rows SET subitem= '" . implode(',', $arr_item_menu) . "' WHERE mid=" . $mid_old . ' AND id=' . $pa_old);
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit row menu', 'Row menu id: ' . $post['id'], $admin_info['userid']);
    }

    $nv_Cache->delMod($module_name);
    nv_jsonOutput([
        'status' => 'OK'
    ]);
}

// Lấy html khi thay đổi khối menu
if ($nv_Request->get_title('action', 'post') == 'link_menu' and $nv_Request->isset_request('mid,parentid', 'post')) {
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);

    $arr_item = [
        [
            'key' => 0,
            'title' => $lang_module['cat0'],
            'selected' => ($parentid == 0) ? ' selected="selected"' : ''
        ]
    ];

    $sp = '&nbsp;&nbsp;&nbsp;';
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid=' . $mid . ' ORDER BY sort';
    $result = $db->query($sql);

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);

    $xtpl->assign('CAT', [
        'key' => 0,
        'title' => $lang_module['cat0'],
        'selected' => ($parentid == 0) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('row.cat');

    while ($row = $result->fetch()) {
        $sp_title = '';
        if ($row['lev'] > 0) {
            for ($i = 1; $i <= $row['lev']; ++$i) {
                $sp_title .= $sp;
            }
        }
        $xtpl->assign('CAT', [
            'key' => $row['id'],
            'title' => $sp_title . $row['title'],
            'selected' => ($parentid == $row['id']) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.cat');
    }
    nv_htmlOutput($xtpl->text('row.cat'));
}

// Lấy các mục của module
if ($nv_Request->get_title('action', 'post') == 'link_module' and $nv_Request->isset_request('module', 'post')) {
    $mod_name = $nv_Request->get_title('module', 'post', '');

    $stmt = $db->prepare('SELECT title, module_file, module_data FROM ' . NV_MODULES_TABLE . ' WHERE title= :module');
    $stmt->bindParam(':module', $mod_name, PDO::PARAM_STR);
    $stmt->execute();
    list($mod_name, $mod_file, $mod_data) = $stmt->fetch(3);
    if (empty($mod_name)) {
        exit($lang_module['add_error_module']);
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

    $contents = '';
    if (!empty($array_item)) {
        $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', $lang_module);

        foreach ($array_item as $key => $item1) {
            $parentid = (isset($item1['parentid'])) ? $item1['parentid'] : 0;
            if (empty($parentid)) {
                $item1['module'] = $mod_name;

                $xtpl->assign('ITEM', $item1);
                $xtpl->parse('row.link.item');

                $array_submenu = [];
                nv_menu_get_submenu($key, '', $array_item, $sp);
                foreach ($array_submenu as $item2) {
                    $xtpl->assign('ITEM', $item2);
                    $xtpl->parse('row.link.item');
                }
            }
        }

        $xtpl->parse('row.link');
        $contents = $xtpl->text('row.link');
    }
    nv_htmlOutput($contents);
}

// Thay đổi thứ tự menu
if ($nv_Request->get_title('action', 'post') == 'chang_weight' and $nv_Request->isset_request('id,mid,parentid,new_weight', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $mid = $nv_Request->get_int('mid', 'post', 0);
    $parentid = $nv_Request->get_int('parentid', 'post', 0);
    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);

    if ($db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id . ' AND parentid=' . $parentid)->fetchColumn()) {
        $query = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id !=' . $id . ' AND parentid=' . $parentid . ' AND mid=' . $mid . ' ORDER BY weight ASC';
        $result = $db->query($query);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $weight . ' WHERE id=' . $row['id']);
        }

        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET weight=' . $new_weight . ' WHERE id=' . $id . ' AND parentid=' . $parentid);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'Change weight row menu', 'Row menu id: ' . $id . ', new weight: ' . $new_weight, $admin_info['userid']);
        menu_fix_order($mid);
        $nv_Cache->delMod($module_name);
    }

    exit('OK');
}

// Thay đổi trạng thái menu
if ($nv_Request->get_title('action', 'post') == 'change_active' and $nv_Request->isset_request('id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $sql = 'SELECT id, status FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id;
    $row = $db->query($sql)->fetch();
    if (!empty($row)) {
        $new_status = (int) $row['status'] ? 0 : 1;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET status=' . $new_status . ' WHERE id=' . $id;
        $db->query($sql);
        $nv_Cache->delMod($module_name);
        $text = $new_status ? 'Active row menu' : 'Inactive row menu';
        nv_insert_logs(NV_LANG_DATA, $module_name, $text, 'Row menu id: ' . $id, $admin_info['userid']);
    }
    exit('OK');
}

// Xoá menu
if ($nv_Request->get_title('action', 'post') == 'delete' and $nv_Request->isset_request('id,mid,parentid', 'post')) {
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
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE mid = ' . $pg['mid'] . ' ORDER BY sort ASC';
$result = $db->query($sql);
$menulist = [];
while ($row = $result->fetch()) {
    $menulist[$row['id']] = $row;
}

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

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $post['mid']) . '&amp;parentid=' . $post['parentid'];
    $xtpl->assign('FORM_CAPTION', ($post['id']) ? $lang_module['edit_menu'] : $lang_module['add_item']);
    $xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload);

    if (!empty($post['icon']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['icon'])) {
        $post['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['icon'];
    }

    if (!empty($post['image']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $post['image'])) {
        $post['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $post['image'];
    }

    $xtpl->assign('DATA', $post);

    foreach ($menublocks as $arr) {
        $xtpl->assign('BLOCK', [
            'key' => $arr['id'],
            'sel' => $arr['id'] == $post['mid'] ? ' selected="selected"' : '',
            'val' => $arr['title']
        ]);
        $xtpl->parse('row.loop');
    }

    $xtpl->assign('CAT', [
        'key' => 0,
        'parentid' => 0,
        'title' => $lang_module['cat0'],
        'selected' => ($post['parentid'] == 0) ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('row.cat');

    $sp = '&nbsp;&nbsp;&nbsp;';
    foreach ($menulist as $row) {
        $sp_title = '';
        if ($row['lev'] > 0) {
            for ($i = 1; $i <= $row['lev']; ++$i) {
                $sp_title .= $sp;
            }
        }

        $xtpl->assign('CAT', [
            'key' => $row['id'],
            'parentid' => $row['parentid'],
            'title' => $sp_title . $row['title'],
            'selected' => ($post['parentid'] == $row['id']) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.cat');
    }

    $list_module = [];
    unset($site_mods['menu'], $site_mods['comment'], $site_mods['zalo']);
    foreach ($site_mods as $key => $title) {
        $xtpl->assign('MODULE', [
            'key' => $key,
            'title' => $title['custom_title'],
            'selected' => ($key == $post['module_name']) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.module');
    }

    if ($post['id'] != 0) {
        if ($post['op'] != '' and isset($site_mods[$post['module_name']])) {
            $mod_name = $post['module_name'];
            $mod_file = $site_mods[$mod_name]['module_file'];
            $mod_data = $site_mods[$mod_name]['module_data'];
            $array_item = [];
            if (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php')) {
                include NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php';
            }
            // Lấy menu từ các chức năng của module
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
                foreach ($array_item as $key => $item) {
                    $parentid = (isset($item['parentid'])) ? $item['parentid'] : 0;
                    if (empty($parentid)) {
                        $item['module'] = $mod_name;
                        $item['selected'] = ($item['alias'] == $post['op']) ? ' selected="selected"' : '';
                        $xtpl->assign('ITEM', $item);
                        $xtpl->parse('row.link.item');
                        if (isset($item['parentid'])) {
                            $array_submenu = [];
                            nv_menu_get_submenu($key, $post['op'], $array_item, $sp);
                            foreach ($array_submenu as $item2) {
                                $xtpl->assign('ITEM', $item2);
                                $xtpl->parse('row.link.item');
                            }
                        }
                    }
                }
            }
            $xtpl->parse('row.link');
        }
    }

    foreach ($groups_list as $key => $title) {
        $xtpl->assign('GROUPS_VIEW', [
            'key' => $key,
            'title' => $title,
            'sel' => (!empty($post['groups_view']) and in_array((int) $key, $post['groups_view'], true)) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.groups_view');
    }

    foreach ($type_target as $key => $target) {
        $xtpl->assign('TARGET', [
            'key' => $key,
            'title' => $target,
            'selected' => ($key == $post['target']) ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.target');
    }

    for ($i = 0; $i <= 2; ++$i) {
        $xtpl->assign('ACTIVE_TYPE', [
            'key' => $i,
            'title' => $lang_module['add_type_active_' . $i],
            'selected' => $post['active_type'] == $i ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('row.active_type');
    }

    $xtpl->parse('row');
    $contents = $xtpl->text('row');
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
    'title' => $lang_module['menu_manager'],
    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name
];
krsort($array_mod_title, SORT_NUMERIC);

// Active last item
$s = sizeof($array_mod_title) - 1;
$array_mod_title[$s]['active'] = true;

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $post['mid'] . '&amp;parentid=' . $post['parentid']);
$xtpl->assign('DATA', $post);
$xtpl->assign('PAGE', $pg);

if (!empty($menublocks)) {
    foreach ($menublocks as $menublock) {
        $xtpl->assign('MID', [
            'key' => $menublock['id'],
            'title' => $menublock['title'],
            'sel' => $menublock['id'] == $pg['mid'] ? ' selected="selected"' : ''
        ]);
        $xtpl->parse('main.mid');
    }
}

$parentid_menulist = [];
foreach ($menulist as $menu_id => $row) {
    if ($row['parentid'] == $pg['parentid']) {
        $parentid_menulist[$row['weight']] = $row['id'];
    }
}
if (!empty($parentid_menulist)) {
    ksort($parentid_menulist);
    $parentid_menulist = array_values($parentid_menulist);
    $num = sizeof($parentid_menulist);

    foreach ($parentid_menulist as $menu_id) {
        $row = $menulist[$menu_id];
        $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE parentid=' . $row['id'];
        $row['nu'] = $db->query($sql)->fetchColumn();

        $row['sub'] = sizeof(array_filter(explode(',', $row['subitem'])));

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
        $row['active'] = $row['status'] ? 'checked="checked"' : '';
        $row['url_title'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $post['mid'] . '&amp;parentid=' . $row['id'];
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;mid=' . $post['mid'] . '&amp;id=' . $row['id'];

        $xtpl->assign('ROW', $row);
        if (!empty($row['icon'])) {
            $xtpl->parse('main.table.loop1.icon');
        }
        for ($i = 1; $i <= $num; ++$i) {
            $xtpl->assign('stt', $i);
            if ($i == $row['weight']) {
                $xtpl->assign('select', 'selected="selected"');
            } else {
                $xtpl->assign('select', '');
            }
            $xtpl->parse('main.table.loop1.weight');
        }

        $func_menu = 0;
        if (isset($site_mods[$row['module_name']])) {
            $mod_site = $site_mods[$row['module_name']];
            $mod_file = $mod_site['module_file'];
            foreach ($mod_site['funcs'] as $funcs) {
                if ($funcs['in_submenu']) {
                    ++$func_menu;
                }
            }

            if (empty($row['op']) and (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/menu.php') or $func_menu > 0)) {
                $xtpl->parse('main.table.loop1.reload');
            }
        }

        if (!empty($row['link'])) {
            $xtpl->parse('main.table.loop1.link');
            $xtpl->parse('main.table.loop1.link2');
        }

        foreach ($row['groups_view'] as $gr) {
            $xtpl->assign('GROUP', $gr);
            $xtpl->parse('main.table.loop1.group');
        }

        $xtpl->parse('main.table.loop1');
    }

    $xtpl->parse('main.table');
} else {
    $xtpl->parse('main.is_empty');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['menu_manager'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
