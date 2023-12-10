<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_AUTHORS')) {
    exit('Stop!!!');
}

$admin_id = $nv_Request->get_int('admin_id', 'get', 0);

$query = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $admin_id;
$row = $db->query($query)->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allowed = false;
if (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) {
    $allowed = true;
} elseif (defined('NV_IS_SPADMIN')) {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    } elseif ($row['lev'] == 3 and $global_config['spadmin_add_admin'] == 1) {
        $allowed = true;
    }
} else {
    if ($row['admin_id'] == $admin_info['admin_id']) {
        $allowed = true;
    }
}

if (empty($allowed)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$allmods = [];
foreach ($global_config['setup_langs'] as $l) {
    $allmods[$l] = nv_site_mods($l);
}

$old_modules = [];
if ($row['lev'] == 3) {
    foreach ($allmods as $l => $_site_mods) {
        $array_keys = array_keys($_site_mods);
        foreach ($array_keys as $mod) {
            if (!empty($mod)) {
                if (!empty($_site_mods[$mod]['admins'])) {
                    if (in_array($admin_id, array_map('intval', explode(',', $_site_mods[$mod]['admins'])), true)) {
                        !isset($old_modules[$l]) && $old_modules[$l] = [];
                        $old_modules[$l][] = $mod;
                    }
                }
            }
        }
    }
}

$old_lev_expired = !empty($row['lev_expired']) ? date('d.m.Y', $row['lev_expired']) : '';
$old_downgrade_to_modadmin = !empty($row['after_exp_action']) ? true : false;
$old_after_modules = $old_downgrade_to_modadmin ? json_decode($row['after_exp_action'], true) : [];

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id;
$row_user = $db->query($sql)->fetch();

if (empty($row['files_level'])) {
    $old_allow_files_type = [];
    $old_allow_modify_files = $old_allow_create_subdirectories = $old_allow_modify_subdirectories = 0;
} else {
    [$old_allow_files_type, $old_allow_modify_files, $old_allow_create_subdirectories, $old_allow_modify_subdirectories] = explode('|', $row['files_level']);
    $old_allow_files_type = !empty($old_allow_files_type) ? explode(',', $old_allow_files_type) : [];
}

$error = '';
$adminThemes = [''];
$adminThemes = array_merge($adminThemes, nv_scandir(NV_ROOTDIR . '/themes', $global_config['check_theme_admin']));
unset($adminThemes[0]);
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $admin_id);

$editors = [];
$dirs = nv_scandir(NV_ROOTDIR . '/' . NV_EDITORSDIR, '/^[a-zA-Z0-9_]+$/');
if (!empty($dirs)) {
    foreach ($dirs as $dir) {
        if (file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . $dir . '/nv.php')) {
            $editors[] = $dir;
        }
    }
}

if ($nv_Request->get_int('save', 'post', 0)) {
    if ($checkss != $nv_Request->get_string('checkss', 'post')) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $editor = $nv_Request->get_title('editor', 'post', '');
    (!empty($editors) and !empty($editor) and !in_array($editor, $editors, true)) && $editor = '';

    if (defined('NV_IS_SPADMIN')) {
        $allow_files_type = $nv_Request->get_typed_array('allow_files_type', 'post', 'title', []);
        $allow_modify_files = $nv_Request->get_int('allow_modify_files', 'post', 0);
        $allow_create_subdirectories = $nv_Request->get_int('allow_create_subdirectories', 'post', 0);
        $allow_modify_subdirectories = $nv_Request->get_int('allow_modify_subdirectories', 'post', 0);
    } else {
        $allow_files_type = $old_allow_files_type;
        $allow_modify_files = $old_allow_modify_files;
        $allow_create_subdirectories = $old_allow_create_subdirectories;
        $allow_modify_subdirectories = $old_allow_modify_subdirectories;
    }

    $lev = ((defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) and $row['admin_id'] != $admin_info['admin_id']) ? $nv_Request->get_int('lev', 'post', 0) : $row['lev'];
    $lev_expired = (defined('NV_IS_SPADMIN') and $row['admin_id'] != $admin_info['admin_id']) ? $nv_Request->get_title('lev_expired', 'post', '') : $old_lev_expired;
    $downgrade_to_modadmin = ((defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) and $row['admin_id'] != $admin_info['admin_id']) ? $nv_Request->get_bool('downgrade_to_modadmin', 'post', false) : $old_downgrade_to_modadmin;
    ($lev == 3 or empty($lev_expired)) && $downgrade_to_modadmin = false;

    if ($lev == 3 and defined('NV_IS_SPADMIN') and $row['admin_id'] != $admin_info['admin_id']) {
        $modules = [];
        $_modules = $_POST['modules'] ?? [];
        if (!empty($_modules)) {
            foreach ($_modules as $l => $vs) {
                if (!empty($vs)) {
                    foreach ($vs as $m) {
                        if (isset($allmods[$l][$m])) {
                            !isset($modules[$l]) && $modules[$l] = [];
                            $modules[$l][] = $m;
                        }
                    }
                }
            }
        }
    } else {
        $modules = $old_modules;
    }
    ($lev == 2) && $modules = [];

    $ss_after_modules = [];
    if (((defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) and $row['admin_id'] != $admin_info['admin_id'] and $downgrade_to_modadmin)) {
        $after_modules = [];
        $_after_modules = $_POST['after_modules'] ?? [];
        if (!empty($_after_modules)) {
            foreach ($_after_modules as $l => $vs) {
                if (!empty($vs)) {
                    foreach ($vs as $m) {
                        if (isset($allmods[$l][$m])) {
                            !isset($after_modules[$l]) && $after_modules[$l] = [];
                            $after_modules[$l][] = $m;
                            $ss_after_modules[] = $m . ' (' . $language_array[$l]['name'] . ')';
                        }
                    }
                }
            }
        }
    } else {
        $after_modules = $old_after_modules;
    }

    $position = ((defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) or (defined('NV_IS_SPADMIN') and $row['lev'] != 1 and $row['admin_id'] != $admin_info['admin_id'])) ? $nv_Request->get_title('position', 'post') : $row['position'];
    $main_module = $nv_Request->get_title('main_module', 'post', 'siteinfo');
    $admin_theme = $nv_Request->get_string('admin_theme', 'post');
    $admin_theme = (!empty($admin_theme) and in_array($admin_theme, $adminThemes, true)) ? $admin_theme : '';

    if (empty($position)) {
        $error = $nv_Lang->getModule('position_incorrect');
    } else {
        foreach ($global_config['setup_langs'] as $l) {
            if (isset($modules[$l]) or isset($old_modules[$l])) {
                !isset($modules[$l]) && $modules[$l] = [];
                !isset($old_modules[$l]) && $old_modules[$l] = [];

                $add_modules = array_diff($modules[$l], $old_modules[$l]);
                $del_modules = array_diff($old_modules[$l], $modules[$l]);

                if (!empty($add_modules)) {
                    foreach ($add_modules as $mod) {
                        $admins = (!empty($allmods[$l][$mod]['admins']) ? $allmods[$l][$mod]['admins'] . ',' : '') . $admin_id;
                        $admins = array_map('intval', explode(',', $admins));
                        $admins = array_unique($admins);
                        $admins = implode(',', $admins);
                        $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $l . '_modules SET admins= :admins WHERE title= :mod');
                        $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                        $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
                        $sth->execute();
                    }
                }
                if (!empty($del_modules)) {
                    foreach ($del_modules as $mod) {
                        $admins = (!empty($allmods[$l][$mod]['admins'])) ? explode(',', $allmods[$l][$mod]['admins']) : [];
                        $admins = array_diff($admins, [
                            $admin_id,
                            0
                        ]);
                        $admins = array_map('intval', $admins);
                        $admins = (!empty($admins)) ? implode(',', $admins) : '';

                        $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $l . '_modules SET admins= :admins WHERE title= :mod');
                        $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                        $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
                        $sth->execute();
                    }
                }

                if (!empty($add_modules) or !empty($del_modules)) {
                    $nv_Cache->delMod('modules', $l);
                }
            }
        }

        $allow_files_type = array_values(array_intersect($global_config['file_allowed_ext'], $allow_files_type));
        $files_level = (!empty($allow_files_type) ? implode(',', $allow_files_type) : '') . '|' . $allow_modify_files . '|' . $allow_create_subdirectories . '|' . $allow_modify_subdirectories;
        unset($matches);
        $lev_expired_sql = preg_match('/^(0?[1-9]|[12][0-9]|3[01])[\/\-\.](0?[1-9]|1[012])[\/\-\.](\d{4})$/', $lev_expired, $matches) ? mktime(23, 59, 59, $matches[2], $matches[1], $matches[3]) : 0;
        $after_modules_sql = $downgrade_to_modadmin ? json_encode($after_modules) : '';

        $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET editor = :editor, lev=' . $lev . ', lev_expired=' . $lev_expired_sql . ', after_exp_action=:after_exp_action, files_level= :files_level, position= :position, main_module = :main_module, admin_theme = :admin_theme, edittime=' . NV_CURRENTTIME . ' WHERE admin_id=' . $admin_id);
        $sth->bindParam(':editor', $editor, PDO::PARAM_STR);
        $sth->bindParam(':after_exp_action', $after_modules_sql, PDO::PARAM_STR);
        $sth->bindParam(':files_level', $files_level, PDO::PARAM_STR);
        $sth->bindParam(':position', $position, PDO::PARAM_STR);
        $sth->bindParam(':main_module', $main_module, PDO::PARAM_STR);
        $sth->bindParam(':admin_theme', $admin_theme, PDO::PARAM_STR);
        $sth->execute();

        if ($lev != $row['lev']) {
            nv_groups_add_user($lev, $admin_id);
            nv_groups_del_user($row['lev'], $admin_id);
        }

        $result = [];
        $result['admin_id'] = $admin_id;
        $result['login'] = $row_user['username'];
        $result['change'] = [];
        if ($editor != $row['editor']) {
            $result['change']['editor'] = [
                $nv_Lang->getModule('editor'),
                (!empty($row['editor']) ? $row['editor'] : $nv_Lang->getModule('not_use')),
                (!empty($editor) ? $editor : $nv_Lang->getModule('not_use'))
            ];
        }
        if ($allow_files_type != $old_allow_files_type) {
            $result['change']['allow_files_type'] = [
                $nv_Lang->getModule('allow_files_type'),
                (!empty($old_allow_files_type) ? implode(', ', $old_allow_files_type) : $nv_Lang->getGlobal('no')),
                (!empty($allow_files_type) ? implode(', ', $allow_files_type) : $nv_Lang->getGlobal('no'))
            ];
        }
        if ($allow_modify_files != $old_allow_modify_files) {
            $result['change']['allow_modify_files'] = [
                $nv_Lang->getModule('allow_modify_files'),
                (!empty($old_allow_modify_files) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')),
                (!empty($allow_modify_files) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))
            ];
        }
        if ($allow_create_subdirectories != $old_allow_create_subdirectories) {
            $result['change']['allow_create_subdirectories'] = [
                $nv_Lang->getModule('allow_create_subdirectories'),
                (!empty($old_allow_create_subdirectories) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')),
                (!empty($allow_create_subdirectories) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))
            ];
        }
        if ($allow_modify_subdirectories != $old_allow_modify_subdirectories) {
            $result['change']['allow_modify_subdirectories'] = [
                $nv_Lang->getModule('allow_modify_subdirectories'),
                (!empty($old_allow_modify_subdirectories) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no')),
                (!empty($allow_modify_subdirectories) ? $nv_Lang->getGlobal('yes') : $nv_Lang->getGlobal('no'))
            ];
        }
        if ($lev == 2) {
            if ($lev != $row['lev']) {
                $result['change']['lev'] = [
                    $nv_Lang->getModule('lev'),
                    $nv_Lang->getGlobal('level' . $row['lev']),
                    $nv_Lang->getGlobal('level' . $lev)
                ];
            }
            if ($lev_expired_sql != $row['lev_expired']) {
                $result['change']['lev_expired'] = [
                    $nv_Lang->getModule('lev_expired'),
                    !empty($old_lev_expired) ? $old_lev_expired : $nv_Lang->getModule('unlimited'),
                    !empty($lev_expired) ? $lev_expired : $nv_Lang->getModule('unlimited')
                ];
            }

            if ($downgrade_to_modadmin != $old_downgrade_to_modadmin or $after_modules != $old_after_modules) {
                $old_afm = [];
                if (!empty($old_after_modules)) {
                    foreach ($old_after_modules as $l => $mds) {
                        foreach ($mds as $m) {
                            $old_afm[] = $m . ' (' . $language_array[$l]['name'] . ')';
                        }
                    }
                }
                $old_afm = (!empty($old_afm)) ? implode(', ', $old_afm) : '';
                $afm = !empty($after_modules) ? implode(', ', $ss_after_modules) : '';
                $result['change']['after_exp_action'] = [
                    $nv_Lang->getModule('after_exp_action'),
                    $old_downgrade_to_modadmin ? $nv_Lang->getModule('downgrade_to_modadmin') . $old_afm : '',
                    $downgrade_to_modadmin ? $nv_Lang->getModule('downgrade_to_modadmin') . $afm : ''
                ];
            }
        } elseif ($lev == 3) {
            if ($lev != $row['lev']) {
                $result['change']['lev'] = [
                    $nv_Lang->getModule('lev'),
                    $nv_Lang->getGlobal('level' . $row['lev']),
                    $nv_Lang->getGlobal('level' . $lev)
                ];
                $old = [];
                if (!empty($old_modules)) {
                    foreach ($old_modules as $l => $mds) {
                        foreach ($mds as $m) {
                            $old[] = $m . ' (' . $language_array[$l]['name'] . ')';
                        }
                    }
                }
                $old = (!empty($old)) ? implode(', ', $old) : '';
                $new = [];
                if (!empty($modules)) {
                    foreach ($modules as $l => $mds) {
                        foreach ($mds as $m) {
                            $new[] = $m . ' (' . $language_array[$l]['name'] . ')';
                        }
                    }
                }
                $new = (!empty($new)) ? implode(', ', $new) : '';

                $result['change']['modules'] = [
                    $nv_Lang->getModule('nv_admin_modules'),
                    $old,
                    $new
                ];

                if ($lev_expired_sql != $row['lev_expired']) {
                    $result['change']['lev_expired'] = [
                        $nv_Lang->getModule('lev_expired'),
                        !empty($old_lev_expired) ? $old_lev_expired : $nv_Lang->getModule('unlimited'),
                        !empty($lev_expired) ? $lev_expired : $nv_Lang->getModule('unlimited')
                    ];
                }
            } else {
                if (!empty($add_modules) or !empty($del_modules)) {
                    $old = [];
                    if (!empty($old_modules)) {
                        foreach ($old_modules as $l => $mds) {
                            foreach ($mds as $m) {
                                $old[] = $m . ' (' . $language_array[$l]['name'] . ')';
                            }
                        }
                    }
                    $old = (!empty($old)) ? implode(', ', $old) : '';
                    $new = [];
                    if (!empty($modules)) {
                        foreach ($modules as $l => $mds) {
                            foreach ($mds as $m) {
                                $new[] = $m . ' (' . $language_array[$l]['name'] . ')';
                            }
                        }
                    }
                    $new = (!empty($new)) ? implode(', ', $new) : '';
                    $result['change']['modules'] = [
                        $nv_Lang->getModule('nv_admin_modules'),
                        $old,
                        $new
                    ];
                }

                if ($lev_expired_sql != $row['lev_expired']) {
                    $result['change']['lev_expired'] = [
                        $nv_Lang->getModule('lev_expired'),
                        !empty($old_lev_expired) ? $old_lev_expired : $nv_Lang->getModule('unlimited'),
                        !empty($lev_expired) ? $lev_expired : $nv_Lang->getModule('unlimited')
                    ];
                }
            }
        }
        if ($position != $row['position']) {
            $result['change']['position'] = [
                $nv_Lang->getModule('position'),
                $row['position'],
                $position
            ];
        }

        $log_note = [];
        $log_note[] = 'Username: ' . $row_user['username'];
        foreach ($result['change'] as $change) {
            $log_note[] = $change[0] . ': ' . $change[1] . ' =&gt; ' . $change[2];
        }
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('nv_admin_edit'), implode('<br />', $log_note), $admin_info['userid']);

        if (empty($result['change'])) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '#aid' . $admin_id);
        }
        nv_admin_edit_result($result);
    }
} else {
    $lev = (int) ($row['lev']);
    $modules = $old_modules;
    $position = $row['position'];
    $editor = $row['editor'];
    $admin_theme = $row['admin_theme'];
    $main_module = $row['main_module'];
    $allow_files_type = $old_allow_files_type;
    $allow_modify_files = $old_allow_modify_files;
    $allow_create_subdirectories = $old_allow_create_subdirectories;
    $allow_modify_subdirectories = $old_allow_modify_subdirectories;
    $lev_expired = $old_lev_expired;
    $downgrade_to_modadmin = $old_downgrade_to_modadmin;
    $after_modules = $old_after_modules;
}

$page_title = $nv_Lang->getModule('nv_admin_edit');

$array_module = [];
if ($admin_id != $admin_info['userid']) {
    $result = $db->query('SELECT * FROM ' . $db_config['dbsystem'] . '.' . NV_AUTHORS_GLOBALTABLE . '_module WHERE act_' . $row['lev'] . ' = 1 ORDER BY weight ASC');
    while ($_row = $result->fetch()) {
        $array_module[$_row['module']] = [
            'module' => $_row['module'],
            'title' => $nv_Lang->existsGlobal($_row['lang_key']) ? $nv_Lang->getGlobal($_row['lang_key']) : $_row['module']
        ];
    }
} else {
    foreach ($admin_mods as $mod) {
        $array_module[$mod['module']] = [
            'module' => $mod['module'],
            'title' => $mod['custom_title']
        ];
    }
}

foreach ($site_mods as $index => $value) {
    if ($value['admin_file']) {
        if ($row['lev'] == 3 and !in_array($index, $old_modules, true)) {
            continue;
        }
        $array_module[$index] = [
            'module' => $index,
            'title' => $value['custom_title']
        ];
    }
}

// Parse content
$xtpl = new XTemplate('edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/authors');
$xtpl->assign('CLASS', !empty($error) ? ' class="error"' : '');
$xtpl->assign('INFO', !empty($error) ? $error : $nv_Lang->getModule('nv_admin_edit_info', $row_user['username']));
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;admin_id=' . $admin_id);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('CHECKSS', $checkss);

if ((defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) or (defined('NV_IS_SPADMIN') and $row['lev'] != 1 and $row['admin_id'] != $admin_info['admin_id'])) {
    $xtpl->assign('POSITION', $position);
    $xtpl->parse('edit.position');
}

foreach ($adminThemes as $_admin_theme) {
    $xtpl->assign('THEME_NAME', $_admin_theme);
    $xtpl->assign('THEME_SELECTED', $_admin_theme == $admin_theme ? ' selected="selected"' : '');
    $xtpl->parse('edit.admin_theme');
}

if (!empty($editors)) {
    foreach ($editors as $edt) {
        $xtpl->assign('VALUE', $edt);
        $xtpl->assign('SELECTED', $edt == $editor ? ' selected="selected"' : '');
        $xtpl->parse('edit.editor.loop');
    }
    $xtpl->parse('edit.editor');
}

foreach ($array_module as $module) {
    $module['selected'] = ($module['module'] == $main_module) ? 'selected="selected"' : '';
    $xtpl->assign('MODULE', $module);
    $xtpl->parse('edit.module');
}

if (defined('NV_IS_SPADMIN') and !empty($global_config['file_allowed_ext'])) {
    foreach ($global_config['file_allowed_ext'] as $tp) {
        $xtpl->assign('VALUE', $tp);
        $xtpl->assign('CHECKED', in_array($tp, $allow_files_type, true) ? ' checked="checked"' : '');
        $xtpl->parse('edit.allow_files_type.loop');
    }
    $xtpl->parse('edit.allow_files_type');
}

if (defined('NV_IS_SPADMIN')) {
    $xtpl->assign('CHECKED', $allow_modify_files ? ' checked="checked"' : '');
    $xtpl->parse('edit.allow_modify_files');

    $xtpl->assign('CHECKED', $allow_create_subdirectories ? ' checked="checked"' : '');
    $xtpl->parse('edit.allow_create_subdirectories');

    $xtpl->assign('CHECKED', $allow_modify_subdirectories ? ' checked="checked"' : '');
    $xtpl->parse('edit.allow_modify_subdirectories');
}

if (defined('NV_IS_SPADMIN') and $row['admin_id'] != $admin_info['admin_id']) {
    $xtpl->assign('LEV_EXPIRED', $lev_expired);

    if ($admin_info['level'] == 1) {
        $xtpl->assign('CHECKED2', $lev == 2 ? ' checked="checked"' : '');
        $xtpl->assign('CHECKED3', $lev == 3 ? ' checked="checked"' : '');
        $xtpl->parse('edit.lev.if');

        if ($lev == 2) {
            $xtpl->parse('edit.lev.modslist_hidden');
        }

        $xtpl->assign('DOWNGRADE_TO_MODADMIN_CHECKED', $downgrade_to_modadmin ? ' checked="checked"' : '');
        if ($lev == 3 or empty($lev_expired)) {
            $xtpl->parse('edit.lev.after_exp_action.hidden');
        }
        if (!$downgrade_to_modadmin) {
            $xtpl->parse('edit.lev.after_exp_action.modslist2_hidden');
        }

        foreach ($allmods as $lg => $mds) {
            $xtpl->assign('LANG_AFTER_MODS', ['code' => $lg, 'name' => $language_array[$lg]['name']]);
            foreach ($mds as $mod => $vls) {
                $xtpl->assign('MOD_VALUE', $mod);
                $xtpl->assign('MOD_CHECKED', (!empty($after_modules[$lg]) and in_array($mod, $after_modules[$lg], true)) ? 'checked="checked"' : '');
                $xtpl->assign('CUSTOM_TITLE', $vls['custom_title']);
                $xtpl->parse('edit.lev.after_exp_action.lang_after_mods.mod');
            }
            $xtpl->parse('edit.lev.after_exp_action.lang_after_mods');
        }

        $xtpl->parse('edit.lev.after_exp_action');
    }

    foreach ($allmods as $lg => $mds) {
        $xtpl->assign('LANG_MODS', ['code' => $lg, 'name' => $language_array[$lg]['name']]);
        foreach ($mds as $mod => $dts) {
            $xtpl->assign('VALUE', $mod);
            $xtpl->assign('CHECKED', (!empty($modules[$lg]) and in_array($mod, $modules[$lg], true)) ? 'checked="checked"' : '');
            $xtpl->assign('CUSTOM_TITLE', $dts['custom_title']);
            $xtpl->parse('edit.lev.lang_mods.loop');
        }
        $xtpl->parse('edit.lev.lang_mods');
    }

    $xtpl->parse('edit.lev');
}

$xtpl->parse('edit');
$contents = $xtpl->text('edit');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
