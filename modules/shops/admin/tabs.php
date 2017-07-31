<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$table_name = $db_config['prefix'] . '_' . $module_data . '_tabs';

//change status
if ($nv_Request->isset_request('change_status', 'post, get')) {
    $id = $nv_Request->get_int('id', 'post, get', 0);
    $content = 'NO_' . $id;

    $query = 'SELECT active FROM ' . $table_name . ' WHERE id=' . $id;
    $row = $db->query($query)->fetch();
    if (isset($row['active'])) {
        $status = ($row['active']) ? 0 : 1;
        $query = 'UPDATE ' . $table_name . ' SET active=' . intval($status) . ' WHERE id=' . $id;
        $db->query($query);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('ajax_action', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $new_vid = $nv_Request->get_int('new_vid', 'post', 0);
    $content = 'NO_' . $id;
    if ($new_vid > 0) {
        $sql = 'SELECT id FROM ' . $table_name . ' WHERE id!=' . $id . ' ORDER BY weight ASC';
        $result = $db->query($sql);
        $weight = 0;
        while ($row = $result->fetch()) {
            ++$weight;
            if ($weight == $new_vid) {
                ++$weight;
            }
            $sql = 'UPDATE ' . $table_name. ' SET weight=' . $weight . ' WHERE id=' . $row['id'];
            $db->query($sql);
        }
        $sql = 'UPDATE ' . $table_name . ' SET weight=' . $new_vid . ' WHERE id=' . $id;
        $db->query($sql);
        $content = 'OK_' . $id;
    }
    $nv_Cache->delMod($module_name);
    include NV_ROOTDIR . '/includes/header.php';
    echo $content;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    if ($id > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $weight = 0;
        $sql = 'SELECT weight FROM ' . $table_name . ' WHERE id =' . $db->quote($id);
        $result = $db->query($sql);
        list($weight) = $result->fetch(3);

        $db->query('DELETE FROM ' . $table_name . '  WHERE id = ' . $db->quote($id));
        if ($weight > 0) {
            $sql = 'SELECT id, weight FROM ' . $table_name . ' WHERE weight >' . $weight;
            $result = $db->query($sql);
            while (list($id, $weight) = $result->fetch(3)) {
                $weight--;
                $db->query('UPDATE ' . $table_name . ' SET weight=' . $weight . ' WHERE id=' . intval($id));
            }
        }
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$row = array( );
$error = array( );
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);
if ($nv_Request->isset_request('submit', 'post')) {
    $field_lang = nv_file_table($table_name);

    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['icon'] = $nv_Request->get_title('icon', 'post', '');

    if (is_file(NV_DOCUMENT_ROOT . $row['icon'])) {
        $row['icon'] = str_replace(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/', '', $row['icon']);
    } else {
        $row['icon'] = '';
    }

    $row['content'] = $nv_Request->get_title('content', 'post', '');

    $row['active'] = $nv_Request->get_int('active', 'post', 1);

    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_title'];
    } elseif (empty($row['content'])) {
        $error[] = $lang_module['error_required_content'];
    }

    if (empty($error)) {
        try {
            if (empty($row['id'])) {
                $listfield = "";
                $listvalue = "";

                foreach ($field_lang as $field_lang_i) {
                    list($flang, $fname) = $field_lang_i;
                    $listfield .= ", " . $flang . "_" . $fname;
                    if ($flang == NV_LANG_DATA) {
                        $listvalue .= ", " . $db->quote($row[$fname]);
                    } else {
                        $listvalue .= ", " . $db->quote($row[$fname]);
                    }
                }

                $stmt = $db->prepare('INSERT INTO ' . $table_name . ' (icon, content, weight, active ' . $listfield .  ') VALUES (:icon, :content, :weight, 1 ' . $listvalue . ')');

                $weight = $db->query('SELECT max(weight) FROM ' . $table_name)->fetchColumn();
                $weight = intval($weight) + 1;
                $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare('UPDATE ' . $table_name . ' SET icon = :icon, content = :content, active = 1, ' . NV_LANG_DATA . '_title=:title WHERE id=' . $row['id']);
                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
            }
            $stmt->bindParam(':icon', $row['icon'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR);

            $exc = $stmt->execute();
            if ($exc) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
} elseif ($row['id'] > 0) {
    $row = $db->query('SELECT * FROM ' . $table_name . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
    $row['title'] = $row[NV_LANG_DATA . '_title'];
} else {
    $row['id'] = 0;
    $row['title'] = '';
    $row['icon'] = '';
    $row['content'] = '';
    $row['active'] = 0;
}
if (!empty($row['icon']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['icon'])) {
    $row['icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['icon'];
}

// Fetch Limit
$show_view = false;
if (!$nv_Request->isset_request('id', 'post,get')) {
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int('page', 'post,get', 1);
    $db->sqlreset()->select('COUNT(*)')->from($table_name);
    $sth = $db->prepare($db->sql());
    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select('*')->order('weight ASC')->limit($per_page)->offset(($page - 1) * $per_page);
    $sth = $db->prepare($db->sql());
    $sth->execute();
}

$cat_form_exit = array( );

$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_template';
$_query = $db->query($_sql);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

if ($show_view) {
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

    while ($view = $sth->fetch()) {
        for ($i = 1; $i <= $num_items; ++$i) {
            $xtpl->assign('WEIGHT', array(
                'key' => $i,
                'title' => $i,
                'selected' => ($i == $view['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.view.loop.weight_loop');
        }
        $view['title'] = $view[NV_LANG_DATA . '_title'];
        $view['content'] = $lang_module['tabs_' . $view['content']];
        $view['active'] = $view['active'] ? 'checked="checked"' : '';
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5($view['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.view.loop');
    }

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.view.generate_page');
    }

    $xtpl->parse('main.view');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$array_select_content = array( );
$array_select_content['content_detail'] = $lang_module['tabs_content_detail'];
$array_select_content['content_otherimage'] = $lang_module['tabs_content_otherimage'];
$array_select_content['content_download'] = $lang_module['tabs_content_download'];
$array_select_content['content_comments'] = $lang_module['tabs_content_comments'];
$array_select_content['content_rate'] = $lang_module['tabs_content_rate'];
$array_select_content['content_customdata'] = $lang_module['tabs_content_customdata'];

$select_content = '';

foreach ($array_select_content as $key => $title) {
    $xtpl->assign('OPTION', array(
        'key' => $key,
        'title' => $title,
        'selected' => ($key == $row['content']) ? ' selected="selected"' : '',
    ));
    $xtpl->parse('main.select_content');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['tabs'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
