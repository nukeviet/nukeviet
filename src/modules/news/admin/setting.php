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
$socialbuttons = [
    'facebook',
    'twitter',
    'zalo'
];

$groupslist = nv_groups_list();

$savesetting = $nv_Request->get_int('savesetting', 'post', 0);
if (!empty($savesetting)) {
    $checkss = $nv_Request->get_title('checkss', 'post', '');
    if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getGlobal('error_submit_timeout')
        ]);
    }
    $array_config = [];
    $array_config['indexfile'] = $nv_Request->get_title('indexfile', 'post', '', 1);
    $array_config['mobile_indexfile'] = $nv_Request->get_title('mobile_indexfile', 'post', '', 1);
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
    $array_config['showtooltip'] = $nv_Request->get_int('showtooltip', 'post', 0);
    $array_config['tooltip_position'] = $nv_Request->get_string('tooltip_position', 'post', '');
    $array_config['tooltip_length'] = $nv_Request->get_int('tooltip_length', 'post', 0);
    $array_config['showhometext'] = $nv_Request->get_int('showhometext', 'post', 0);
    $array_config['htmlhometext'] = $nv_Request->get_int('htmlhometext', 'post', 0);

    $array_config['report_active'] = (int) $nv_Request->get_bool('report_active', 'post', false);
    $array_config['report_limit'] = $nv_Request->get_int('report_limit', 'post', 0);
    $array_config['report_limit'] <= 0 && $array_config['report_limit'] = 1;

    $array_config['facebookappid'] = $nv_Request->get_title('facebookappid', 'post', '');
    $array_config['socialbutton'] = $nv_Request->get_typed_array('socialbutton', 'post', 'title', []);
    $array_config['show_no_image'] = $nv_Request->get_title('show_no_image', 'post', '', 0);
    $array_config['structure_upload'] = $nv_Request->get_title('structure_upload', 'post', '', 0);
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

    $array_config['instant_articles_active'] = $nv_Request->get_int('instant_articles_active', 'post', 0);
    $array_config['instant_articles_template'] = $nv_Request->get_title('instant_articles_template', 'post', 'default');
    $array_config['instant_articles_httpauth'] = $nv_Request->get_int('instant_articles_httpauth', 'post', 0);
    $array_config['instant_articles_username'] = $nv_Request->get_title('instant_articles_username', 'post', '');
    $array_config['instant_articles_password'] = $nv_Request->get_title('instant_articles_password', 'post', '');
    $array_config['instant_articles_livetime'] = $nv_Request->get_int('instant_articles_livetime', 'post', 0);
    $array_config['instant_articles_gettime'] = $nv_Request->get_int('instant_articles_gettime', 'post', 0);
    $array_config['instant_articles_auto'] = $nv_Request->get_int('instant_articles_auto', 'post', 0);

    if (!empty($array_config['instant_articles_httpauth']) and (empty($array_config['instant_articles_username']) or empty($array_config['instant_articles_password']))) {
        $array_config['instant_articles_httpauth'] = 0;
    }
    if (!empty($array_config['instant_articles_password'])) {
        $array_config['instant_articles_password'] = $crypt->encrypt($array_config['instant_articles_password']);
    }

    if ($array_config['elas_use']) {
        $fp = fsockopen($array_config['elas_host'], $array_config['elas_port'], $errno, $errstr, 30);
        if (!$fp) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('error_elas_host_connect') . (!empty($errstr) ? ': ' . $errstr : '')
            ]);
        }
        fclose($fp);
    }

    if (!nv_is_url($array_config['show_no_image']) and nv_is_file($array_config['show_no_image'])) {
        $lu = strlen(NV_BASE_SITEURL);
        $array_config['show_no_image'] = substr($array_config['show_no_image'], $lu);
    } else {
        $array_config['show_no_image'] = '';
    }

    $array_config['socialbutton'] = array_intersect($array_config['socialbutton'], $socialbuttons);
    if (in_array('zalo', $array_config['socialbutton'], true) and empty($global_config['zaloOfficialAccountID'])) {
        $array_config['socialbutton'] = array_diff($array_config['socialbutton'], [
            'zalo'
        ]);
    }
    $array_config['socialbutton'] = !empty($array_config['socialbutton']) ? implode(',', $array_config['socialbutton']) : '';

    $array_config['schema_type'] = $nv_Request->get_title('schema_type', 'post', '');
    if (!array_key_exists($array_config['schema_type'], $schema_types)) {
        $array_config['schema_type'] = 'newsarticle';
    }

    $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);
    
    nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('setting'), '', $admin_info['userid']);
    
    nv_jsonOutput([
        'status' => 'success',
        'mess' => $nv_Lang->getGlobal('save_success'),
        'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass(), true)
    ]);
}

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('DATA', $module_config[$module_name]);

$array_tooltip_position = [
    'top' => $nv_Lang->getModule('showtooltip_position_top'),
    'bottom' => $nv_Lang->getModule('showtooltip_position_bottom'),
    'left' => $nv_Lang->getModule('showtooltip_position_left'),
    'right' => $nv_Lang->getModule('showtooltip_position_right')
];

// Cac dropdown select
$tpl->assign('TOOLTIP_POSITION', $array_tooltip_position);
$tpl->assign('INDEXFILE', $array_viewcat_full);
$tpl->assign('MOBILE_INDEXFILE', $array_viewcat_full);
$tpl->assign('CONFIG_SOURCE', [
    0 => $nv_Lang->getModule('config_source_title'),
    3 => $nv_Lang->getModule('config_source_link'),
    1 => $nv_Lang->getModule('config_source_link_nofollow'),
    2 => $nv_Lang->getModule('config_source_logo')
]);
$tpl->assign('IMGPOSITION', [
    0 => $nv_Lang->getModule('imgposition_0'),
    1 => $nv_Lang->getModule('imgposition_1'),
    2 => $nv_Lang->getModule('imgposition_2')
]);
$tpl->assign('ORDER_ARTICLES', [
    0 => $nv_Lang->getModule('order_articles_0'),
    1 => $nv_Lang->getModule('order_articles_1')
]);

// Social_buttons
$my_socialbuttons = !empty($module_config[$module_name]['socialbutton']) ? array_map('trim', explode(',', $module_config[$module_name]['socialbutton'])) : [];
$socialbutton_list = [];
foreach ($socialbuttons as $socialbutton) {
    $array = [
        'key' => $socialbutton,
        'title' => ucfirst($socialbutton),
        'checked' => (!empty($my_socialbuttons) and in_array($socialbutton, $my_socialbuttons, true)),
        'disabled' => false,
        'note' => ''
    ];
    if ($socialbutton == 'zalo' and empty($global_config['zaloOfficialAccountID'])) {
        $array['note'] = ' (<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings">' . $nv_Lang->getModule('socialbutton_zalo_note') . '</a>)';
        $array['disabled'] = true;
    }
    $socialbutton_list[] = $array;
}
$tpl->assign('SOCIALBUTTONS', $socialbutton_list);

// Show points rating article on google
$rating_point = [];
for ($i = 0; $i <= 6; ++$i) {
    $rating_point[$i] = ($i == 6) ? $nv_Lang->getModule('no_allowed_rating') : $i;
}
$tpl->assign('RATING_POINT', $rating_point);

$tpl->assign('SHOW_NO_IMAGE', (!empty($module_config[$module_name]['show_no_image'])) ? NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'] : '');
$tpl->assign('INSTANT_ARTICLES_URL_DEFAULT', urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss', NV_MY_DOMAIN));

if (!empty($module_config[$module_name]['instant_articles_password'])) {
    $tpl->assign('INSTANT_ARTICLES_PASSWORD', $crypt->decrypt($module_config[$module_name]['instant_articles_password']));
} else {
    $tpl->assign('INSTANT_ARTICLES_PASSWORD', '');
}

$array_structure_image = [];
$array_structure_image[''] = NV_UPLOADS_DIR . '/' . $module_upload;
$array_structure_image['Y'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y');
$array_structure_image['Ym'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
$array_structure_image['Y_m'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m');
$array_structure_image['Ym_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m/d');
$array_structure_image['Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y/m/d');
$array_structure_image['username'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin';

$array_structure_image['username_Y'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y');
$array_structure_image['username_Ym'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y_m');
$array_structure_image['username_Y_m'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y/m');
$array_structure_image['username_Ym_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y_m/d');
$array_structure_image['username_Y_m_d'] = NV_UPLOADS_DIR . '/' . $module_upload . '/username_admin/' . date('Y/m/d');

// Thu muc uploads
$tpl->assign('STRUCTURE_UPLOAD', $array_structure_image);

$copyright = nv_htmlspecialchars(nv_editor_br2nl($module_config[$module_name]['copyright']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
    $copyright = nv_aleditor('copyright', '100%', '100px', $copyright, 'Basic', $_uploads_dir, $_uploads_dir);
} else {
    $copyright = '<textarea name="copyright" id="copyright" cols="20" rows="15" class="form-control w-100">' . $copyright . '</textarea>';
}
$tpl->assign('COPYRIGHTHTML', $copyright);

$tpl->assign('PATH', defined('NV_IS_SPADMIN') ? '' : NV_UPLOADS_DIR . '/' . $module_upload);
$tpl->assign('CURRENTPATH', defined('NV_IS_SPADMIN') ? 'images' : NV_UPLOADS_DIR . '/' . $module_upload);

// Cấu hình loại dữ liệu có cấu trúc
$tpl->assign('SCHEMA_TYPES', $schema_types);

if (defined('NV_IS_ADMIN_FULL_MODULE') or !in_array('admins', $allow_func, true)) {
    $groups_list = $groupslist;
    unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[6]);

    $savepost = $nv_Request->get_int('savepost', 'post', 0);
    if (!empty($savepost)) {
        $checkss = $nv_Request->get_title('checkss', 'post', '');
        if (!hash_equals(NV_CHECK_SESSION, $checkss)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getGlobal('error_submit_timeout')
            ]);
        }
        
        $array_config = [];
        $array_group_id = $nv_Request->get_typed_array('array_group_id', 'post', 'int', []);
        $array_addcontent = $nv_Request->get_typed_array('array_addcontent', 'post', 'int', []);
        $array_postcontent = $nv_Request->get_typed_array('array_postcontent', 'post', 'int', []);
        $array_editcontent = $nv_Request->get_typed_array('array_editcontent', 'post', 'int', []);
        $array_delcontent = $nv_Request->get_typed_array('array_delcontent', 'post', 'int', []);

        $array_config['frontend_edit_alias'] = $nv_Request->get_int('frontend_edit_alias', 'post', 0);
        $array_config['frontend_edit_layout'] = $nv_Request->get_int('frontend_edit_layout', 'post', 0);

        $array_config['report_group'] = $nv_Request->get_typed_array('report_group', 'post', 'int', []);
        $array_config['report_group'] = !empty($array_config['report_group']) ? implode(',', nv_groups_post(array_intersect($array_config['report_group'], array_keys($groupslist)))) : '';

        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        foreach ($array_group_id as $group_id) {
            if (isset($groups_list[$group_id])) {
                $addcontent = (isset($array_addcontent[$group_id]) and (int) ($array_addcontent[$group_id]) == 1) ? 1 : 0;
                $postcontent = (isset($array_postcontent[$group_id]) and (int) ($array_postcontent[$group_id]) == 1) ? 1 : 0;
                $editcontent = (isset($array_editcontent[$group_id]) and (int) ($array_editcontent[$group_id]) == 1) ? 1 : 0;
                $delcontent = (isset($array_delcontent[$group_id]) and (int) ($array_delcontent[$group_id]) == 1) ? 1 : 0;
                $addcontent = ($postcontent == 1) ? 1 : $addcontent;
                if ($group_id == 5) {
                    $editcontent = 0;
                    $delcontent = 0;
                }
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . "_config_post SET addcontent = '" . $addcontent . "', postcontent = '" . $postcontent . "', editcontent = '" . $editcontent . "', delcontent = '" . $delcontent . "' WHERE group_id =" . $group_id);
            }
        }

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
        
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('group_content'), '', $admin_info['userid']);
        
        nv_jsonOutput([
            'status' => 'success',
            'mess' => $nv_Lang->getGlobal('save_success'),
            'redirect' => nv_url_rewrite(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass(), true)
        ]);
    }

    $array_post_data = [];

    $sql = 'SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post ORDER BY group_id ASC';
    $result = $db->query($sql);
    while ($_scratch = $result->fetch(3)) {
        [$group_id, $addcontent, $postcontent, $editcontent, $delcontent] = $_scratch;
        unset($_scratch);
        if (isset($groups_list[$group_id])) {
            $array_post_data[$group_id] = [
                'group_id' => $group_id,
                'addcontent' => $addcontent,
                'postcontent' => $postcontent,
                'editcontent' => $editcontent,
                'delcontent' => $delcontent
            ];
        } else {
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post WHERE group_id = ' . $group_id);
        }
    }

    $groups_post = [];
    foreach ($groups_list as $group_id => $group_title) {
        if ((isset($array_post_data[$group_id]))) {
            $addcontent = $array_post_data[$group_id]['addcontent'];
            $postcontent = $array_post_data[$group_id]['postcontent'];
            $editcontent = $array_post_data[$group_id]['editcontent'];
            $delcontent = $array_post_data[$group_id]['delcontent'];
        } else {
            $addcontent = $postcontent = $editcontent = $delcontent = 0;
            $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_config_post (group_id,addcontent,postcontent,editcontent,delcontent) VALUES ( '" . $group_id . "', '" . $addcontent . "', '" . $postcontent . "', '" . $editcontent . "', '" . $delcontent . "' )");
        }

        $groups_post[] = [
            'group_id' => $group_id,
            'group_title' => $group_title,
            'addcontent' => $addcontent,
            'postcontent' => $postcontent,
            'editcontent' => $editcontent,
            'delcontent' => $delcontent,
            'is_guest' => $group_id == 5
        ];
    }

    $report_group = !empty($module_config[$module_name]['report_group']) ? array_map('intval', explode(',', $module_config[$module_name]['report_group'])) : [];
    $report_groups = [];
    foreach ($groupslist as $key => $gr) {
        $key = (int) $key;
        $report_groups[] = [
            'value' => $key,
            'title' => $gr,
            'checked' => (!empty($report_group) and in_array($key, $report_group, true))
        ];
    }

    $tpl->assign('GROUPS_POST', $groups_post);
    $tpl->assign('REPORT_GROUPS', $report_groups);
    $tpl->assign('SHOW_ADMIN_CONFIG_POST', true);
} else {
    $tpl->assign('SHOW_ADMIN_CONFIG_POST', false);
}

$contents = $tpl->fetch('settings.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
