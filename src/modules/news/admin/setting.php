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

$page_title = $nv_Lang->getModule('setting');

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$socialbuttons = ['facebook', 'twitter', 'zalo'];
$groupslist = nv_groups_list();
$redirect_url = nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true);

$buildSelectOptions = static function (array $source, $selected, ?callable $titleBuilder = null): array {
    $options = [];
    foreach ($source as $key => $value) {
        $options[] = [
            'key' => $key,
            'title' => $titleBuilder ? $titleBuilder($key, $value) : $value,
            'selected' => (string) $key === (string) $selected
        ];
    }

    return $options;
};

$updateConfigValues = static function (array $configValues) use ($db, $module_name): void {
    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindValue(':module_name', $module_name, PDO::PARAM_STR);

    foreach ($configValues as $config_name => $config_value) {
        $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
};

$item = array_merge([
    'indexfile' => 'viewcat_page_new',
    'mobile_indexfile' => 'viewcat_page_new',
    'per_page' => 20,
    'st_links' => 0,
    'homewidth' => 0,
    'homeheight' => 0,
    'blockwidth' => 0,
    'blockheight' => 0,
    'imagefull' => 0,
    'allowed_rating' => 0,
    'allowed_rating_point' => 0,
    'copyright' => '',
    'showtooltip' => 0,
    'tooltip_position' => 'top',
    'tooltip_length' => 0,
    'showhometext' => 0,
    'htmlhometext' => 0,
    'report_active' => 0,
    'report_limit' => 1,
    'facebookappid' => '',
    'socialbutton' => '',
    'show_no_image' => '',
    'structure_upload' => 'Ym',
    'config_source' => 0,
    'hide_author' => 0,
    'hide_inauthor' => 0,
    'imgposition' => 0,
    'alias_lower' => 0,
    'tags_alias' => 0,
    'auto_tags' => 0,
    'tags_remind' => 0,
    'keywords_tag' => 0,
    'copy_news' => 0,
    'auto_save' => 0,
    'order_articles' => 0,
    'identify_cat_change' => 0,
    'active_history' => 0,
    'elas_use' => 0,
    'elas_host' => '',
    'elas_port' => 0,
    'elas_index' => '',
    'frontend_edit_alias' => 0,
    'frontend_edit_layout' => 0,
    'report_group' => '',
    'schema_type' => 'newsarticle'
], $module_config[$module_name] ?? []);

if ($nv_Request->isset_request('savesetting', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $array_config = [];
    $array_config['indexfile'] = $nv_Request->get_title('indexfile', 'post', '');
    $array_config['mobile_indexfile'] = $nv_Request->get_title('mobile_indexfile', 'post', '');
    $array_config['per_page'] = $nv_Request->get_page('per_page', 'post', 20);
    $array_config['st_links'] = $nv_Request->get_int('st_links', 'post', 0);
    $array_config['homewidth'] = $nv_Request->get_int('homewidth', 'post', 0);
    $array_config['homeheight'] = $nv_Request->get_int('homeheight', 'post', 0);
    $array_config['blockwidth'] = $nv_Request->get_int('blockwidth', 'post', 0);
    $array_config['blockheight'] = $nv_Request->get_int('blockheight', 'post', 0);
    $array_config['imagefull'] = $nv_Request->get_int('imagefull', 'post', 0);
    $array_config['allowed_rating'] = (int) $nv_Request->get_bool('allowed_rating', 'post', false);
    $array_config['allowed_rating_point'] = $nv_Request->get_int('allowed_rating_point', 'post', 0);
    $array_config['copyright'] = $nv_Request->get_editor('copyright', '', NV_ALLOWED_HTML_TAGS);
    $array_config['showtooltip'] = (int) $nv_Request->get_bool('showtooltip', 'post', false);
    $array_config['tooltip_position'] = $nv_Request->get_string('tooltip_position', 'post', 'top');
    $array_config['tooltip_length'] = $nv_Request->get_int('tooltip_length', 'post', 0);
    $array_config['showhometext'] = (int) $nv_Request->get_bool('showhometext', 'post', false);
    $array_config['htmlhometext'] = (int) $nv_Request->get_bool('htmlhometext', 'post', false);
    $array_config['report_active'] = (int) $nv_Request->get_bool('report_active', 'post', false);
    $array_config['report_limit'] = max(1, $nv_Request->get_int('report_limit', 'post', 0));
    $array_config['facebookappid'] = $nv_Request->get_title('facebookappid', 'post', '');
    $array_config['socialbutton'] = $nv_Request->get_typed_array('socialbutton', 'post', 'title', []);
    $array_config['show_no_image'] = $nv_Request->get_title('show_no_image', 'post', '');
    $array_config['structure_upload'] = $nv_Request->get_title('structure_upload', 'post', '');
    $array_config['config_source'] = $nv_Request->get_int('config_source', 'post', 0);
    $array_config['hide_author'] = (int) $nv_Request->get_bool('hide_author', 'post', false);
    $array_config['hide_inauthor'] = (int) $nv_Request->get_bool('hide_inauthor', 'post', false);
    $array_config['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
    $array_config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);
    $array_config['tags_alias'] = $nv_Request->get_int('tags_alias', 'post', 0);
    $array_config['auto_tags'] = $nv_Request->get_int('auto_tags', 'post', 0);
    $array_config['tags_remind'] = $nv_Request->get_int('tags_remind', 'post', 0);
    $array_config['keywords_tag'] = $nv_Request->get_int('keywords_tag', 'post', 0);
    $array_config['copy_news'] = $nv_Request->get_int('copy_news', 'post', 0);
    $array_config['auto_save'] = (int) $nv_Request->get_bool('auto_save', 'post', false);
    $array_config['order_articles'] = $nv_Request->get_int('order_articles', 'post', 0);
    $array_config['identify_cat_change'] = $nv_Request->get_int('identify_cat_change', 'post', 0);
    $array_config['active_history'] = (int) $nv_Request->get_bool('active_history', 'post', false);
    $array_config['elas_use'] = $nv_Request->get_int('elas_use', 'post', 0);
    $array_config['elas_host'] = $nv_Request->get_title('elas_host', 'post', '');
    $array_config['elas_port'] = $nv_Request->get_int('elas_port', 'post', 0);
    $array_config['elas_index'] = $nv_Request->get_title('elas_index', 'post', '');

    if ($array_config['elas_use']) {
        $fp = @fsockopen($array_config['elas_host'], $array_config['elas_port'], $errno, $errstr, 30);
        if (!$fp) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('error_elas_host_connect')
            ]);
        }
        fclose($fp);
    }

    if (!nv_is_url($array_config['show_no_image']) and nv_is_file($array_config['show_no_image'])) {
        $base_site_url_length = strlen(NV_BASE_SITEURL);
        $array_config['show_no_image'] = substr($array_config['show_no_image'], $base_site_url_length);
    } else {
        $array_config['show_no_image'] = '';
    }

    $array_config['socialbutton'] = array_intersect($array_config['socialbutton'], $socialbuttons);
    if (in_array('zalo', $array_config['socialbutton'], true) and empty($global_config['zaloOfficialAccountID'])) {
        $array_config['socialbutton'] = array_diff($array_config['socialbutton'], ['zalo']);
    }
    $array_config['socialbutton'] = !empty($array_config['socialbutton']) ? implode(',', $array_config['socialbutton']) : '';

    $array_config['schema_type'] = $nv_Request->get_title('schema_type', 'post', '');
    if (!array_key_exists($array_config['schema_type'], $schema_types)) {
        $array_config['schema_type'] = 'newsarticle';
    }

    try {
        $updateConfigValues($array_config);

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_setting', 'general', $admin_info['userid']);

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'redirect' => $redirect_url
        ]);
    } catch (Throwable $e) {
        trigger_error($e);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    }
}

$can_config_post = defined('NV_IS_ADMIN_FULL_MODULE') or !in_array('admins', $allow_func, true);
if ($can_config_post and $nv_Request->isset_request('savepost', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post', ''), $csrf_key)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_checkss')
        ]);
    }

    $groups_list = $groupslist;
    unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[6]);

    $array_group_id = $nv_Request->get_typed_array('array_group_id', 'post', 'int', []);
    $array_addcontent = $nv_Request->get_typed_array('array_addcontent', 'post', 'int', []);
    $array_postcontent = $nv_Request->get_typed_array('array_postcontent', 'post', 'int', []);
    $array_editcontent = $nv_Request->get_typed_array('array_editcontent', 'post', 'int', []);
    $array_delcontent = $nv_Request->get_typed_array('array_delcontent', 'post', 'int', []);

    $array_config = [
        'frontend_edit_alias' => $nv_Request->get_int('frontend_edit_alias', 'post', 0),
        'frontend_edit_layout' => $nv_Request->get_int('frontend_edit_layout', 'post', 0),
        'report_group' => $nv_Request->get_typed_array('report_group', 'post', 'int', [])
    ];

    $array_config['report_group'] = array_intersect($array_config['report_group'], array_map('intval', array_keys($groupslist)));
    $array_config['report_group'] = !empty($array_config['report_group']) ? implode(',', nv_groups_post($array_config['report_group'])) : '';

    try {
        $updateConfigValues($array_config);

        foreach ($array_group_id as $group_id) {
            if (!isset($groups_list[$group_id])) {
                continue;
            }

            $addcontent = (isset($array_addcontent[$group_id]) and (int) $array_addcontent[$group_id] === 1) ? 1 : 0;
            $postcontent = (isset($array_postcontent[$group_id]) and (int) $array_postcontent[$group_id] === 1) ? 1 : 0;
            $editcontent = (isset($array_editcontent[$group_id]) and (int) $array_editcontent[$group_id] === 1) ? 1 : 0;
            $delcontent = (isset($array_delcontent[$group_id]) and (int) $array_delcontent[$group_id] === 1) ? 1 : 0;

            if ($postcontent === 1) {
                $addcontent = 1;
            }
            if ($group_id === 5) {
                $editcontent = 0;
                $delcontent = 0;
            }

            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config_post SET addcontent = :addcontent, postcontent = :postcontent, editcontent = :editcontent, delcontent = :delcontent WHERE group_id = :group_id');
            $stmt->bindValue(':addcontent', $addcontent, PDO::PARAM_INT);
            $stmt->bindValue(':postcontent', $postcontent, PDO::PARAM_INT);
            $stmt->bindValue(':editcontent', $editcontent, PDO::PARAM_INT);
            $stmt->bindValue(':delcontent', $delcontent, PDO::PARAM_INT);
            $stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_edit_post_setting', 'group permissions', $admin_info['userid']);

        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'redirect' => $redirect_url
        ]);
    } catch (Throwable $e) {
        trigger_error($e);
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('errorsave')
        ]);
    }
}

$tooltip_options = $buildSelectOptions([
    'top' => $nv_Lang->getModule('showtooltip_position_top'),
    'bottom' => $nv_Lang->getModule('showtooltip_position_bottom'),
    'left' => $nv_Lang->getModule('showtooltip_position_left'),
    'right' => $nv_Lang->getModule('showtooltip_position_right')
], $item['tooltip_position']);

$indexfile_options = $buildSelectOptions($array_viewcat_full, $item['indexfile']);
$mobile_indexfile_options = $buildSelectOptions($array_viewcat_full, $item['mobile_indexfile']);

$per_page_options = [];
for ($i = 5; $i <= 100; ++$i) {
    $per_page_options[] = [
        'key' => $i,
        'title' => $i,
        'selected' => $i === (int) $item['per_page']
    ];
}

$st_link_options = [];
for ($i = 0; $i <= 50; ++$i) {
    $st_link_options[] = [
        'key' => $i,
        'title' => $i,
        'selected' => $i === (int) $item['st_links']
    ];
}

$socialbutton_selected = !empty($item['socialbutton']) ? array_map('trim', explode(',', $item['socialbutton'])) : [];
$socialbutton_options = [];
foreach ($socialbuttons as $socialbutton) {
    $is_disabled = $socialbutton === 'zalo' and empty($global_config['zaloOfficialAccountID']);
    $socialbutton_options[] = [
        'key' => $socialbutton,
        'title' => ucfirst($socialbutton),
        'checked' => !$is_disabled and in_array($socialbutton, $socialbutton_selected, true),
        'disabled' => $is_disabled
    ];
}

$rating_point_options = [];
for ($i = 0; $i <= 6; ++$i) {
    $rating_point_options[] = [
        'key' => $i,
        'title' => $i === 6 ? $nv_Lang->getModule('no_allowed_rating') : $i,
        'selected' => $i === (int) $item['allowed_rating_point']
    ];
}

$array_structure_image = [
    '' => NV_UPLOADS_DIR . '/' . $module_upload,
    'Y' => NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y'),
    'Ym' => NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m'),
    'Y_m' => NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m'),
    'Ym_d' => NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m/d'),
    'Y_m_d' => NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m/d'),
    'username' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin',
    'username_Y' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y'),
    'username_Ym' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y_m'),
    'username_Y_m' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y/m'),
    'username_Ym_d' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y_m/d'),
    'username_Y_m_d' => NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y/m/d')
];
$structure_upload_options = $buildSelectOptions($array_structure_image, $item['structure_upload'] ?: 'Ym');

$config_source_options = $buildSelectOptions([
    0 => $nv_Lang->getModule('config_source_title'),
    3 => $nv_Lang->getModule('config_source_link'),
    1 => $nv_Lang->getModule('config_source_link_nofollow'),
    2 => $nv_Lang->getModule('config_source_logo')
], $item['config_source']);

$imgposition_options = $buildSelectOptions([
    0 => $nv_Lang->getModule('imgposition_0'),
    1 => $nv_Lang->getModule('imgposition_1'),
    2 => $nv_Lang->getModule('imgposition_2')
], $item['imgposition']);

$order_articles_options = [];
for ($i = 0; $i < 2; ++$i) {
    $order_articles_options[] = [
        'key' => $i,
        'title' => $nv_Lang->getModule('order_articles_' . $i),
        'selected' => $i === (int) $item['order_articles']
    ];
}

$schema_type_options = $buildSelectOptions($schema_types, $item['schema_type']);

$copyright = nv_htmlspecialchars(nv_editor_br2nl($item['copyright']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
    $copyright = nv_aleditor('copyright', '100%', '100px', $copyright, 'Basic', $_uploads_dir, $_uploads_dir);
} else {
    $copyright = '<textarea class="form-control" name="copyright" id="copyright" rows="10">' . $copyright . '</textarea>';
}

$post_config_rows = [];
$report_group_options = [];
if ($can_config_post) {
    $groups_list = $groupslist;
    unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[6]);

    $array_post_data = [];
    $stmt = $db->prepare('SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post ORDER BY group_id ASC');
    $stmt->execute();
    while ($_row = $stmt->fetch()) {
        $group_id = (int) $_row['group_id'];
        if (isset($groups_list[$group_id])) {
            $array_post_data[$group_id] = [
                'group_id' => $group_id,
                'addcontent' => (int) $_row['addcontent'],
                'postcontent' => (int) $_row['postcontent'],
                'editcontent' => (int) $_row['editcontent'],
                'delcontent' => (int) $_row['delcontent']
            ];
        } else {
            $stmt_del = $db->prepare('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post WHERE group_id = :group_id');
            $stmt_del->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt_del->execute();
        }
    }
    $stmt->closeCursor();

    foreach ($groups_list as $group_id => $group_title) {
        if (isset($array_post_data[$group_id])) {
            $row = $array_post_data[$group_id];
        } else {
            $row = [
                'group_id' => $group_id,
                'addcontent' => 0,
                'postcontent' => 0,
                'editcontent' => 0,
                'delcontent' => 0
            ];
            $stmt_ins = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_config_post (group_id, addcontent, postcontent, editcontent, delcontent) VALUES (:group_id, 0, 0, 0, 0)');
            $stmt_ins->bindValue(':group_id', $group_id, PDO::PARAM_INT);
            $stmt_ins->execute();
        }

        $post_config_rows[] = [
            'group_id' => $group_id,
            'group_title' => $group_title,
            'addcontent' => !empty($row['addcontent']),
            'postcontent' => !empty($row['postcontent']),
            'editcontent' => $group_id != 5 && !empty($row['editcontent']),
            'delcontent' => $group_id != 5 && !empty($row['delcontent']),
            'disable_editcontent' => $group_id == 5,
            'disable_delcontent' => $group_id == 5
        ];
    }

    $report_group = !empty($item['report_group']) ? array_map('intval', explode(',', $item['report_group'])) : [];
    foreach ($groupslist as $group_id => $group_title) {
        $group_id = (int) $group_id;
        $report_group_options[] = [
            'value' => $group_id,
            'title' => $group_title,
            'checked' => in_array($group_id, $report_group, true)
        ];
    }
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('settings.tpl'));

$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('CHECKSS', csrf_create($csrf_key));
$tpl->assign('ITEM', $item);
$tpl->assign('TOOLTIP_OPTIONS', $tooltip_options);
$tpl->assign('INDEXFILE_OPTIONS', $indexfile_options);
$tpl->assign('MOBILE_INDEXFILE_OPTIONS', $mobile_indexfile_options);
$tpl->assign('PER_PAGE_OPTIONS', $per_page_options);
$tpl->assign('ST_LINK_OPTIONS', $st_link_options);
$tpl->assign('SOCIALBUTTON_OPTIONS', $socialbutton_options);
$tpl->assign('RATING_POINT_OPTIONS', $rating_point_options);
$tpl->assign('STRUCTURE_UPLOAD_OPTIONS', $structure_upload_options);
$tpl->assign('CONFIG_SOURCE_OPTIONS', $config_source_options);
$tpl->assign('IMGPOSITION_OPTIONS', $imgposition_options);
$tpl->assign('ORDER_ARTICLES_OPTIONS', $order_articles_options);
$tpl->assign('SCHEMA_TYPE_OPTIONS', $schema_type_options);
$tpl->assign('COPYRIGHTHTML', $copyright);
$tpl->assign('SHOW_NO_IMAGE', !empty($item['show_no_image']) ? NV_BASE_SITEURL . $item['show_no_image'] : '');
$tpl->assign('UPLOAD_PATH', defined('NV_IS_SPADMIN') ? '' : NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('UPLOAD_CURRENT', defined('NV_IS_SPADMIN') ? 'images' : NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('CAN_CONFIG_POST', $can_config_post);
$tpl->assign('POST_CONFIG_ROWS', $post_config_rows);
$tpl->assign('REPORT_GROUP_OPTIONS', $report_group_options);

$contents = $tpl->fetch('settings.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
