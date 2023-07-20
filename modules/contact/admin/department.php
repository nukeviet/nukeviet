<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

/**
 * department_fix_weight()
 *
 * @param int $skip_id
 * @param int $skip_weight
 */
function department_fix_weight($skip_id = 0, $skip_weight = 0)
{
    global $db, $nv_Cache, $module_name;

    $sql = 'SELECT id FROM ' . NV_MOD_TABLE . '_department WHERE id != ' . $skip_id . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $weight = 0;
    $res = [];
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $skip_weight) {
            ++$weight;
        }
        $res[$row['id']] = 'WHEN id = ' . $row['id'] . ' THEN ' . $weight;
    }
    if (!empty($res)) {
        $in = implode(',', array_keys($res));
        $when = implode(' ', $res);
        $db->query('UPDATE ' . NV_MOD_TABLE . '_department SET weight = CASE ' . $when . ' ELSE weight END WHERE id in (' . $in . ')');
        $nv_Cache->delMod($module_name);
    }
}

$page_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

if (defined('NV_IS_SPADMIN')) {
    if ($nv_Request->isset_request('fc', 'post')) {
        $fc = $nv_Request->get_string('fc', 'post', '');
        // Thêm/Sửa bộ phận
        if ($fc == 'content') {
            $id = $nv_Request->get_int('id', 'post', 0);
            if (!empty($id)) {
                $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
                if (!$department) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => 'Unspecified Department'
                    ]);
                }
            } else {
                $department = [
                    'id' => 0,
                    'full_name' => '',
                    'alias' => '',
                    'image' => '',
                    'phone' => '',
                    'fax' => '',
                    'email' => '',
                    'address' => '',
                    'note' => '',
                    'others' => '',
                    'cats' => '',
                    'admins' => '',
                    'act' => 1,
                    'weight' => 0,
                    'is_default' => 0
                ];
            }

            if (defined('NV_EDITOR')) {
                require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
            }

            $mod_admins = mod_admin_list($module_name, true);

            if ($nv_Request->isset_request('save', 'post')) {
                $post = [
                    'full_name' => $nv_Request->get_title('full_name', 'post', ''),
                    'alias' => $nv_Request->get_title('alias', 'post', ''),
                    'note' => $nv_Request->get_editor('note', '', NV_ALLOWED_HTML_TAGS),
                    'image' => $nv_Request->get_title('image', 'post', ''),
                    'phone' => $nv_Request->get_title('phone', 'post', ''),
                    'fax' => $nv_Request->get_title('fax', 'post', ''),
                    'email' => $nv_Request->get_title('email', 'post', ''),
                    'address' => $nv_Request->get_title('address', 'post', ''),
                    'cats' => $nv_Request->get_typed_array('cats', 'post', 'title', []),
                    'others' => [],
                    'admins' => ''
                ];

                if (empty($post['full_name'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('err_part_row_title')
                    ]);
                }

                if (empty($post['alias'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'mess' => $nv_Lang->getModule('error_alias')
                    ]);
                }

                if (!empty($post['email'])) {
                    $_email = array_map('trim', explode(',', $post['email']));
                    $email = [];
                    foreach ($_email as $e) {
                        $check_valid_email = nv_check_valid_email($e, true);
                        if (empty($check_valid_email[0])) {
                            $email[] = $check_valid_email[1];
                        }
                    }
                    $post['email'] = implode(', ', $email);
                }

                if (is_file(NV_DOCUMENT_ROOT . $post['image'])) {
                    $post['image'] = substr($post['image'], strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/'));
                } else {
                    $post['image'] = '';
                }

                $post['cats'] = array_filter($post['cats']);
                $post['cats'] = !empty($post['cats']) ? json_encode(array_unique($post['cats']), JSON_UNESCAPED_UNICODE) : '';
                $post['phone'] = str_replace(['&#91;', '&#93;'], ['[', ']'], $post['phone']);

                $view_level = $nv_Request->get_typed_array('view_level', 'post', 'int', []);
                $exec_level = $nv_Request->get_typed_array('exec_level', 'post', 'int', []);
                $reply_level = $nv_Request->get_typed_array('reply_level', 'post', 'int', []);
                $obt_level = $nv_Request->get_typed_array('obt_level', 'post', 'int', []);
                $admins = [];
                if (!empty($view_level)) {
                    $admins['view_level'] = $view_level;
                }
                if (!empty($exec_level)) {
                    !isset($admins['view_level']) && $admins['view_level'] = [];
                    $admins['view_level'] += $exec_level;
                    $admins['exec_level'] = $exec_level;
                }
                if (!empty($reply_level)) {
                    !isset($admins['view_level']) && $admins['view_level'] = [];
                    $admins['view_level'] += $reply_level;
                    $admins['reply_level'] = $reply_level;
                }
                if (!empty($obt_level)) {
                    !isset($admins['view_level']) && $admins['view_level'] = [];
                    $admins['view_level'] += $obt_level;
                    $admins['obt_level'] = $obt_level;
                }
                if (!empty($admins['view_level'])) {
                    $admins['view_level'] = array_unique($admins['view_level']);
                }
                $post['admins'] = !empty($admins) ? json_encode($admins) : '';

                $other_name = $nv_Request->get_typed_array('other_name', 'post', 'title', []);
                $other_value = $nv_Request->get_typed_array('other_value', 'post', 'title', []);
                $others = [];
                if (!empty($other_name)) {
                    foreach ($other_name as $key => $name) {
                        if (!empty($name) and !empty($other_value[$key])) {
                            $others[$name] = $other_value[$key];
                        }
                    }
                }
                $post['others'] = !empty($others) ? json_encode($others) : '';

                try {
                    if (empty($id)) {
                        $weight = $db->query('SELECT max(weight) FROM ' . NV_MOD_TABLE . '_department')->fetchColumn();
                        $weight = (int) $weight + 1;
                        $is_default = $weight > 1 ? 0 : 1;
                        $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_department (full_name, alias, image, phone, fax, email, address, others, cats, note, admins, act, weight, is_default) VALUES (:full_name, :alias, :image, :phone, :fax, :email, :address, :others, :cats, :note, :admins, 1, ' . $weight . ', ' . $is_default . ')';
                    } else {
                        $sql = 'UPDATE ' . NV_MOD_TABLE . '_department SET full_name=:full_name, alias=:alias, image = :image, phone = :phone, fax=:fax, email=:email, address=:address, others=:others, cats=:cats, note=:note, admins=:admins WHERE id =' . $id;
                    }
                    $sth = $db->prepare($sql);
                    $sth->bindParam(':full_name', $post['full_name'], PDO::PARAM_STR);
                    $sth->bindParam(':alias', $post['alias'], PDO::PARAM_STR);
                    $sth->bindParam(':image', $post['image'], PDO::PARAM_STR);
                    $sth->bindParam(':phone', $post['phone'], PDO::PARAM_STR);
                    $sth->bindParam(':fax', $post['fax'], PDO::PARAM_STR);
                    $sth->bindParam(':email', $post['email'], PDO::PARAM_STR);
                    $sth->bindParam(':address', $post['address'], PDO::PARAM_STR);
                    $sth->bindParam(':others', $post['others'], PDO::PARAM_STR);
                    $sth->bindParam(':cats', $post['cats'], PDO::PARAM_STR);
                    $sth->bindParam(':note', $post['note'], PDO::PARAM_STR);
                    $sth->bindParam(':admins', $post['admins'], PDO::PARAM_STR);
                    $exc = $sth->execute();
                    if ($exc) {
                        nv_insert_logs(NV_LANG_DATA, $module_name, (empty($id) ? 'log_add_row' : 'log_edit_row'), (empty($id) ? $post['full_name'] : 'id: ' . $id . ' ' . $post['full_name']), $admin_info['userid']);
                        $nv_Cache->delMod($module_name);
                        nv_jsonOutput([
                            'status' => 'OK'
                        ]);
                    } else {
                        nv_jsonOutput([
                            'status' => 'error',
                            'mess' => 'An unknown error has occurred'
                        ]);
                    }
                } catch (PDOException $e) {
                    trigger_error($e->getMessage());
                }
            } else {
                if (!empty($department['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $department['image'])) {
                    $department['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $department['image'];
                } else {
                    $department['image'] = '';
                }

                if (!empty($department['others'])) {
                    $others = json_decode($department['others'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $others = unserialize($department['others']);
                    }
                    $department['others'] = $others;
                }
                if (empty($department['others'])) {
                    $department['others'] = ['' => ''];
                }

                if (!empty($department['cats'])) {
                    $cats = json_decode($department['cats'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $cats = explode('|', $department['cats']);
                    }
                    $department['cats'] = $cats;
                } else {
                    $department['cats'] = [''];
                }

                $department['note'] = !empty($department['note']) ? nv_htmlspecialchars(nv_editor_br2nl($department['note'])) : '';
                if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
                    $department['note'] = nv_aleditor('note', '100%', '100px', $department['note'], 'Basic');
                } else {
                    $department['note'] = '<textarea style="width:100%;height:150px" name="note" id="note">' . $department['note'] . '</textarea>';
                }

                if (!empty($department['admins'])) {
                    $department['admins'] = parse_admins($department['admins']);
                } else {
                    $department['admins'] = [];
                }

                $xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
                $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
                $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
                $xtpl->assign('FORM_ACTION', $page_url);
                $xtpl->assign('DEPARTMENT', $department);
                $xtpl->assign('MODULE_UPLOAD', NV_UPLOADS_DIR . '/' . $module_upload);
                $xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);

                foreach ($department['others'] as $name => $value) {
                    $xtpl->assign('OTHER', [
                        'name' => $name,
                        'value' => $value
                    ]);
                    $xtpl->parse('content.other');
                }

                foreach ($department['cats'] as $cat) {
                    $xtpl->assign('CAT', $cat);
                    $xtpl->parse('content.cat');
                }

                foreach ($mod_admins as $admid => $admin_details) {
                    $admin_details['admid'] = $admid;
                    $admin_details['full_name'] = nv_show_name_user($admin_details['first_name'], $admin_details['last_name'], $admin_details['username']);
                    $admin_details['suspend'] = $admin_details['is_suspend'] ? ' class="warning" title="' . $nv_Lang->getGlobal('admin_suspend') . '"' : '';
                    $admin_details['level_txt'] = $nv_Lang->getGlobal('level' . $admin_details['level']);
                    $admin_details['view_level'] = ($admin_details['level'] === 1 or (!empty($department['admins']['view_level']) and in_array($admid, $department['admins']['view_level'], true))) ? ' checked="checked"' : '';
                    $admin_details['exec_level'] = ($admin_details['level'] === 1 or (!empty($department['admins']['exec_level']) and in_array($admid, $department['admins']['exec_level'], true))) ? ' checked="checked"' : '';
                    $admin_details['reply_level'] = ($admin_details['level'] === 1 or (!empty($department['admins']['reply_level']) and in_array($admid, $department['admins']['reply_level'], true))) ? ' checked="checked"' : '';
                    $admin_details['obt_level'] = (!empty($department['admins']['obt_level']) and in_array($admid, $department['admins']['obt_level'], true)) ? ' checked="checked"' : '';
                    $admin_details['disabled'] = $admin_details['level'] === 1 ? ' disabled="disabled"' : '';
                    $xtpl->assign('ADMIN', $admin_details);
                    $xtpl->parse('content.admin');
                }

                $xtpl->parse('content');
                $contents = $xtpl->text('content');
                nv_jsonOutput([
                    'status' => 'OK',
                    'title' => $id ? $nv_Lang->getModule('department_edit') : $nv_Lang->getModule('department_add'),
                    'content' => $contents
                ]);
            }
        }

        // Tạo alias
        if ($fc == 'alias') {
            $id = $nv_Request->get_int('id', 'post', 0);
            $title = $nv_Request->get_title('title', 'post', '');
            if (empty($title)) {
                return '';
            }

            $alias = change_alias($title);
            $i = 0;
            while ($db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_department WHERE id!=' . $id . ' AND alias=' . $db->quote($alias))->fetchColumn()) {
                ++$i;
                $alias .= '-' . $i;
            }

            nv_htmlOutput($alias);
        }

        // Thay đổi thứ tự
        if ($fc == 'change_weight') {
            $id = $nv_Request->get_int('id', 'post', 0);
            $new_weight = $nv_Request->get_int('nw', 'post', 0);

            $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
            if (!$department) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Unspecified Department'
                ]);
            }

            department_fix_weight($id, $new_weight);
            $db->query('UPDATE ' . NV_MOD_TABLE . '_department SET weight=' . $new_weight . ' WHERE id=' . $id);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK'
            ]);
        }

        // Thay đổi trạng thái
        if ($fc == 'change_status') {
            $id = $nv_Request->get_int('id', 'post', 0);
            $new_status = $nv_Request->get_int('ns', 'post', 0);

            $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
            if (!$department) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Unspecified Department'
                ]);
            }

            if (!in_array($new_status, [0, 1, 2], true)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Error status'
                ]);
            }

            $db->query('UPDATE ' . NV_MOD_TABLE . '_department SET act=' . $new_status . ' WHERE id=' . $id);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK'
            ]);
        }

        // Thay đổi mặc định
        if ($fc == 'set_default') {
            $id = $nv_Request->get_int('id', 'post', 0);

            $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
            if (!$department) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Unspecified Department'
                ]);
            }

            $db->query('UPDATE ' . NV_MOD_TABLE . '_department SET is_default=0 WHERE id!=' . $id);
            $db->query('UPDATE ' . NV_MOD_TABLE . '_department SET is_default=1 WHERE id=' . $id);
            $nv_Cache->delMod($module_name);
            nv_jsonOutput([
                'status' => 'OK'
            ]);
        }

        // Xóa bộ phận
        if ($fc == 'delete') {
            $id = $nv_Request->get_int('id', 'post', 0);

            $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
            if (!$department) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => 'Unspecified Department'
                ]);
            }

            if ($db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_supporter WHERE departmentid=' . $id)->fetchColumn()) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => $nv_Lang->getModule('department_delete_error')
                ]);
            }

            if ($db->exec('DELETE FROM ' . NV_MOD_TABLE . '_department WHERE id = ' . $id)) {
                department_fix_weight();
                nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_row', 'rowid ' . $id, $admin_info['userid']);

                $db->query('DELETE FROM ' . NV_MOD_TABLE . '_send WHERE cid = ' . $id);
                $db->query('DELETE FROM ' . NV_MOD_TABLE . '_reply WHERE id NOT IN (SELECT id FROM ' . NV_MOD_TABLE . '_send)');
                $db->query('OPTIMIZE TABLE ' . NV_MOD_TABLE . '_department');
                $db->query('OPTIMIZE TABLE ' . NV_MOD_TABLE . '_send');
                $db->query('OPTIMIZE TABLE ' . NV_MOD_TABLE . '_reply');

                $nv_Cache->delMod($module_name);
                nv_jsonOutput([
                    'status' => 'OK'
                ]);
            }

            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'An unknown error has occurred'
            ]);
        }
    }
}

if ($nv_Request->isset_request('id', 'get')) {
    $id = $nv_Request->get_int('id', 'get', 0);
    if (empty($id)) {
        exit(0);
    }
    $department = $db->query('SELECT * FROM ' . NV_MOD_TABLE . '_department WHERE id=' . $id)->fetch();
    if (empty($department)) {
        exit(0);
    }

    $department['image'] = !empty($department['image']) ? NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_info['module_upload'] . '/' . $department['image'] : '';
    $department['phone'] = !empty($department['phone']) ? str_replace('|', '<br/>', $department['phone']) : '';
    $department['email'] = !empty($department['email']) ? str_replace(',', '<br/>', $department['email']) : '';
    if (!empty($department['others'])) {
        $_others = json_decode($department['others'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $_others = unserialize($department['others']);
        }

        $department['others'] = $_others;
    }
    if (!empty($department['cats'])) {
        $cats = json_decode($department['cats'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $cats = explode('|', $department['cats']);
        }
        $department['cats'] = '<ul><li>' . implode('</li><li>', $cats) . '</li></ul>';
    }
    if (!empty($department['admins'])) {
        $department['admins'] = parse_admins($department['admins']);
        $department['your_authority'] = [];
        if (defined('NV_IS_SPADMIN') or (!empty($department['admins']['view_level']) and in_array((int) $admin_info['userid'], $department['admins']['view_level'], true))) {
            $department['your_authority'][] = $nv_Lang->getModule('admin_view_level');
        }
        if (defined('NV_IS_SPADMIN') or (!empty($department['admins']['exec_level']) and in_array((int) $admin_info['userid'], $department['admins']['exec_level'], true))) {
            $department['your_authority'][] = $nv_Lang->getModule('admin_exec_level');
        }
        if (defined('NV_IS_SPADMIN') or (!empty($department['admins']['reply_level']) and in_array((int) $admin_info['userid'], $department['admins']['reply_level'], true))) {
            $department['your_authority'][] = $nv_Lang->getModule('admin_reply_level');
        }
        if (!empty($department['admins']['obt_level']) and in_array((int) $admin_info['userid'], $department['admins']['obt_level'], true)) {
            $department['your_authority'][] = $nv_Lang->getModule('admin_obt_level');
        }
        $department['your_authority'] = !empty($department['your_authority']) ? '<ul><li>' . implode('</li><li>', $department['your_authority']) . '</li></ul>' : $nv_Lang->getModule('your_not_authority');
    } else {
        $department['your_authority'] = $nv_Lang->getModule('your_not_authority');
    }

    $xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('DEPARTMENT', $department);

    if (!empty($department['image'])) {
        $xtpl->parse('view.image');
    }
    if (!empty($department['phone'])) {
        $xtpl->parse('view.phone');
    }
    if (!empty($department['fax'])) {
        $xtpl->parse('view.fax');
    }
    if (!empty($department['email'])) {
        $xtpl->parse('view.email');
    }
    if (!empty($department['address'])) {
        $xtpl->parse('view.address');
    }
    if (!empty($department['others'])) {
        foreach ($department['others'] as $title => $value) {
            $xtpl->assign('OTHER', [
                'title' => ucfirst($title),
                'value' => str_replace(',', '<br/>', $value)
            ]);
            $xtpl->parse('view.other');
        }
    }
    if (!empty($department['cats'])) {
        $xtpl->parse('view.cats');
    }

    $xtpl->parse('view');
    $contents = $xtpl->text('view');
    nv_jsonOutput([
        'title' => $department['full_name'],
        'content' => $contents
    ]);
}

$departments = get_department_list();
if (empty($departments)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&fc=content');
}

$xtpl = new XTemplate('department.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('OP_URL', $page_url);

$count = sizeof($departments);
if (defined('NV_IS_SPADMIN')) {
    $xtpl->parse('main.is_spadmin');
}
foreach ($departments as $row) {
    $row['phone'] = preg_replace("/(\[|&#91;)[^\]]*(&#93;|\])$/", '', $row['phone']);
    $row['is_default_checked'] = !empty($row['is_default']) ? ' checked="checked"' : '';
    $xtpl->assign('ROW', $row);

    if (defined('NV_IS_SPADMIN')) {
        for ($i = 1; $i <= $count; ++$i) {
            $xtpl->assign('WEIGHT', [
                'value' => $i,
                'selected' => $i == $row['weight'] ? ' selected="selected"' : ''
            ]);
            $xtpl->parse('main.row.is_spadmin1.option');
        }
        $xtpl->parse('main.row.is_spadmin1');
    } else {
        $xtpl->parse('main.row.is_modadmin1');
    }

    if (!empty($row['is_default'])) {
        $xtpl->parse('main.row.is_default');
    }

    $array = [$nv_Lang->getGlobal('disable'), $nv_Lang->getGlobal('active'), $nv_Lang->getModule('department_no_home')];
    if (defined('NV_IS_SPADMIN')) {
        foreach ($array as $key => $val) {
            $xtpl->assign('STATUS', [
                'key' => $key,
                'selected' => $key == $row['act'] ? ' selected="selected"' : '',
                'title' => $val
            ]);

            $xtpl->parse('main.row.is_spadmin2.status');
        }
        $xtpl->parse('main.row.is_spadmin2');
    } else {
        $xtpl->assign('STATUS', $array[$row['act']]);
        $xtpl->parse('main.row.is_modadmin2');
    }

    if (defined('NV_IS_SPADMIN')) {
        $xtpl->parse('main.row.is_spadmin3');
    } else {
        if (!empty($row['is_default'])) {
            $xtpl->parse('main.row.is_modadmin3');
        }
    }

    if (defined('NV_IS_SPADMIN')) {
        $xtpl->parse('main.row.is_spadmin4');
    }

    $xtpl->parse('main.row');
}

if (defined('NV_IS_SPADMIN')) {
    $xtpl->parse('main.is_spadmin5');
    $xtpl->parse('main.is_spadmin6');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $nv_Lang->getModule('departments');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
