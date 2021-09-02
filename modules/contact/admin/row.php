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

$id = $nv_Request->get_int('id', 'post,get', 0);

if ($id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
    $frow = $db->query($sql)->fetch();

    if (empty($frow)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=department');
    }

    $page_title = $frow['full_name'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $page_title = $lang_module['add_row_title'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$xtpl = new XTemplate('row.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('NV_ADMIN_THEME', $global_config['admin_theme']);
$xtpl->assign('PATH', NV_UPLOADS_DIR . '/' . $module_upload);

$sql = 'SELECT t1.admin_id, t1.lev as level, t1.is_suspend, t2.username, t2.email, t2.first_name, t2.last_name, t2.active
    FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1
    INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2
    ON t1.admin_id = t2.userid
    WHERE t1.lev!=0';
$result = $db->query($sql);

$adms = [];
while ($row = $result->fetch()) {
    $row['is_suspend'] = ($row['is_suspend'] or empty($row['active'])) ? true : false;
    $adms[$row['admin_id']] = $row;
}

$error = '';

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if ($nv_Request->get_int('save', 'post') == '1') {
    $full_name = $nv_Request->get_title('full_name', 'post', '', 1);
    $alias = $nv_Request->get_title('alias', 'post', '', 1);
    $phone = $nv_Request->get_title('phone', 'post', '', 1);
    $fax = $nv_Request->get_title('fax', 'post', '', 1);
    $email = $nv_Request->get_title('email', 'post', '', 1);
    $address = $nv_Request->get_title('address', 'post', '', 1);
    $otherVar = $nv_Request->get_typed_array('otherVar', 'post', 'title', '');
    $otherVal = $nv_Request->get_typed_array('otherVal', 'post', 'title', '');
    $cats = $nv_Request->get_typed_array('cats', 'post', 'title', '');
    $note = $nv_Request->get_editor('note', '', NV_ALLOWED_HTML_TAGS);

    $image = $nv_Request->get_string('image', 'post', '', 1);
    if (is_file(NV_DOCUMENT_ROOT . $image)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $image = substr($image, $lu);
    } else {
        $image = '';
    }

    $view_level = $nv_Request->get_array('view_level', 'post', []);
    $reply_level = $nv_Request->get_array('reply_level', 'post', []);
    $obt_level = $nv_Request->get_array('obt_level', 'post', []);

    if (!empty($email)) {
        $_email = array_map('trim', explode(',', $email));
        $email = [];
        foreach ($_email as $e) {
            $check_valid_email = nv_check_valid_email($e, true);
            if (empty($check_valid_email[0])) {
                $email[] = $check_valid_email[1];
            }
        }
        $email = implode(', ', $email);
    }

    $admins = [];

    if (!empty($view_level)) {
        foreach ($view_level as $admid) {
            $admins[$admid]['view_level'] = 1;
            $admins[$admid]['reply_level'] = 0;
            $admins[$admid]['obt_level'] = 0;
        }
    }

    if (!empty($reply_level)) {
        foreach ($reply_level as $admid) {
            $admins[$admid]['view_level'] = 1;
            $admins[$admid]['reply_level'] = 1;
            $admins[$admid]['obt_level'] = 0;
        }
    }

    if (!empty($obt_level)) {
        foreach ($obt_level as $admid) {
            $admins[$admid]['view_level'] = 1;
            if (!isset($admins[$admid]['reply_level'])) {
                $admins[$admid]['reply_level'] = 0;
            }
            $admins[$admid]['obt_level'] = 1;
        }
    }

    if (empty($full_name)) {
        $error = $lang_module['err_part_row_title'];
    } elseif (empty($alias)) {
        $error = $lang_module['error_alias'];
    } else {
        $alias = empty($alias) ? change_alias($full_name) : change_alias($alias);
        $admins_list = [];
        foreach ($adms as $admid => $values) {
            if ($values['level'] === 1) {
                $obt_level = (isset($admins[$admid])) ? $admins[$admid]['obt_level'] : 0;
                $admins_list[] = $admid . '/1/1/' . $obt_level;
            } else {
                if (isset($admins[$admid])) {
                    $admins_list[] = $admid . '/' . $admins[$admid]['view_level'] . '/' . $admins[$admid]['reply_level'] . '/' . $admins[$admid]['obt_level'];
                }
            }
        }
        $admins_list = implode(';', $admins_list);

        $others = [];
        if (!empty($otherVar)) {
            foreach ($otherVar as $k => $var) {
                if (!empty($var) and isset($otherVal[$k]) and !empty($otherVal[$k])) {
                    $others[$var] = $otherVal[$k];
                }
            }
        }
        $others = json_encode($others);
        $_cats = array_filter($cats);
        $_cats = array_unique($_cats);
        $_cats = !empty($_cats) ? implode('|', $_cats) : '';

        if ($id) {
            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET full_name=:full_name, alias=:alias, image = :image, phone = :phone, fax=:fax, email=:email, address=:address, others=:others, cats=:cats, note=:note, admins=:admins WHERE id =' . $id;
            $name_key = 'log_edit_row';
            $note_action = 'id: ' . $id . ' ' . $full_name;
        } else {
            $weight = $db->query('SELECT MAX(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department')->fetchColumn();
            ++$weight;
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_department (full_name, alias, image, phone, fax, email, address, others, cats, note, admins, act, weight, is_default) VALUES (:full_name, :alias, :image, :phone, :fax, :email, :address, :others, :cats, :note, :admins, 1, :weight, 0)';
            $name_key = 'log_add_row';
            $note_action = $full_name;
        }

        try {
            $sth = $db->prepare($sql);
            $sth->bindParam(':full_name', $full_name, PDO::PARAM_STR);
            $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
            $sth->bindParam(':image', $image, PDO::PARAM_STR);
            $sth->bindParam(':phone', $phone, PDO::PARAM_STR);
            $sth->bindParam(':fax', $fax, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':address', $address, PDO::PARAM_STR);
            $sth->bindParam(':others', $others, PDO::PARAM_STR);
            $sth->bindParam(':cats', $_cats, PDO::PARAM_STR);
            $sth->bindParam(':note', $note, PDO::PARAM_STR);
            $sth->bindParam(':admins', $admins_list, PDO::PARAM_STR);
            if (!$id) {
                $sth->bindParam(':weight', $weight, PDO::PARAM_STR);
            }
            $sth->execute();
            if ($sth->rowCount()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $name_key, $note_action, $admin_info['userid']);
                $nv_Cache->delMod($module_name);
            }
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=department');
        } catch (PDOException $e) {
            $error = $lang_module['duplicate_alias'];
            trigger_error($e->getMessage());
        }
    }
} else {
    if ($id) {
        $full_name = $frow['full_name'];
        $alias = $frow['alias'];
        $image = $frow['image'];
        $phone = $frow['phone'];
        $fax = $frow['fax'];
        $email = $frow['email'];
        $address = $frow['address'];
        $others = json_decode($frow['others'], true);
        $cats = !empty($frow['cats']) ? explode('|', $frow['cats']) : [];

        $note = nv_editor_br2nl($frow['note']);

        $admins_list = $frow['admins'];
        $admins_list = !empty($admins_list) ? array_map('trim', explode(';', $admins_list)) : [];

        $view_level = $reply_level = $obt_level = [];

        if (!empty($admins_list)) {
            foreach ($admins_list as $l) {
                if (preg_match('/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i', $l)) {
                    $l2 = array_map('intval', explode('/', $l));
                    $admid = (int) ($l2[0]);

                    if (isset($adms[$admid])) {
                        if ($adms[$admid]['level'] === 1) {
                            $view_level[] = $admid;
                            $reply_level[] = $admid;

                            if (isset($l2[3]) and $l2[3] === 1) {
                                $obt_level[] = $admid;
                            }
                        } else {
                            if (isset($l2[1]) and $l2[1] === 1) {
                                $view_level[] = $admid;
                            }

                            if (isset($l2[2]) and $l2[2] === 1) {
                                $reply_level[] = $admid;
                            }

                            if (isset($l2[3]) and $l2[3] === 1) {
                                $obt_level[] = $admid;
                            }
                        }
                    }
                }
            }
        }
    } else {
        $full_name = $alias = $image = $phone = $fax = $email = $note = $address = '';
        $others = $cats = $view_level = $reply_level = $obt_level = [];

        foreach ($adms as $admid => $values) {
            if ($values['level'] === 1) {
                $view_level[] = $admid;
                $reply_level[] = $admid;
            }
        }
    }
}

if (!empty($note)) {
    $note = nv_htmlspecialchars($note);
}

if (empty($id)) {
    $xtpl->parse('main.get_alias');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $note = nv_aleditor('note', '100%', '150px', $note);
} else {
    $note = '<textarea style="width:100%;height:150px" name="note" id="note">' . $note . '</textarea>';
}

if (!empty($image) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $image)) {
    $image = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $image;
}

$xtpl->assign('DATA', [
    'full_name' => $full_name,
    'alias' => $alias,
    'image' => $image,
    'phone' => $phone,
    'fax' => $fax,
    'email' => $email,
    'address' => $address,
    'note' => $note
]);

if (!empty($others)) {
    foreach ($others as $var => $val) {
        $xtpl->assign('OTHER', [
            'var' => $var,
            'val' => $val
        ]);
        $xtpl->parse('main.other');
    }
}

if (!empty($cats)) {
    foreach ($cats as $val) {
        $xtpl->assign('CATS', $val);
        $xtpl->parse('main.cats');
    }
}

// list danh sách bộ phận liên hệ
!empty($view_level) && $view_level = array_map('intval', $view_level);
!empty($reply_level) && $reply_level = array_map('intval', $reply_level);
!empty($obt_level) && $obt_level = array_map('intval', $obt_level);
$a = 0;
foreach ($adms as $admid => $values) {
    $xtpl->assign('ADMIN', [
        'suspend' => ($values['is_suspend']) ? 'class="warning" title="' . $lang_global['admin_suspend'] . '"' : '',
        'username' => $values['username'],
        'full_name' => nv_show_name_user($values['first_name'], $values['last_name'], $values['username']),
        'email' => $values['email'],
        'admid' => $admid,
        'img' => 'admin' . $values['level'],
        'level' => $lang_global['level' . $values['level']],
        'view_level' => ($values['level'] === 1 or (!empty($view_level) and in_array((int) $admid, $view_level, true))) ? ' checked="checked"' : '',
        'reply_level' => ($values['level'] === 1 or (!empty($reply_level) and in_array((int) $admid, $reply_level, true))) ? ' checked="checked"' : '',
        'obt_level' => (!empty($obt_level) and in_array((int) $admid, $obt_level, true)) ? ' checked="checked"' : '',
        'disabled' => $values['level'] === 1 ? ' disabled="disabled"' : ''
    ]);

    $xtpl->parse('main.admin');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
