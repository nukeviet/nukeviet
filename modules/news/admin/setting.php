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

$page_title = $lang_module['setting'];

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
$socialbuttons = [
    'facebook',
    'twitter',
    'zalo'
];
$savesetting = $nv_Request->get_int('savesetting', 'post', 0);
if (!empty($savesetting)) {
    $array_config = [];
    $array_config['indexfile'] = $nv_Request->get_title('indexfile', 'post', '', 1);
    $array_config['mobile_indexfile'] = $nv_Request->get_title('mobile_indexfile', 'post', '', 1);
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', 0);
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

    $array_config['facebookappid'] = $nv_Request->get_title('facebookappid', 'post', '');
    $array_config['socialbutton'] = $nv_Request->get_typed_array('socialbutton', 'post', 'title', []);
    $array_config['show_no_image'] = $nv_Request->get_title('show_no_image', 'post', '', 0);
    $array_config['structure_upload'] = $nv_Request->get_title('structure_upload', 'post', '', 0);
    $array_config['config_source'] = $nv_Request->get_int('config_source', 'post', 0);
    $array_config['imgposition'] = $nv_Request->get_int('imgposition', 'post', 0);
    $array_config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);
    $array_config['tags_alias'] = $nv_Request->get_int('tags_alias', 'post', 0);
    $array_config['auto_tags'] = $nv_Request->get_int('auto_tags', 'post', 0);
    $array_config['tags_remind'] = $nv_Request->get_int('tags_remind', 'post', 0);
    $array_config['keywords_tag'] = $nv_Request->get_int('keywords_tag', 'post', 0);
    $array_config['copy_news'] = $nv_Request->get_int('copy_news', 'post', 0);
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
            $error = $lang_module['error_elas_host_connect'];
        }
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

    if (empty($error)) {
        $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $nv_Cache->delMod('settings');
        $nv_Cache->delMod($module_name);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }
}

$xtpl = new XTemplate('settings.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $module_config[$module_name]);
if (!empty($error)) {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

$array_tooltip_position = [
    'top' => $lang_module['showtooltip_position_top'],
    'bottom' => $lang_module['showtooltip_position_bottom'],
    'left' => $lang_module['showtooltip_position_left'],
    'right' => $lang_module['showtooltip_position_right']
];

// Vi tri hien thi tooltip
foreach ($array_tooltip_position as $key => $val) {
    $xtpl->assign('TOOLTIP_P', [
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['tooltip_position'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.tooltip_position');
}

// Cach hien thi tren trang chu
foreach ($array_viewcat_full as $key => $val) {
    $xtpl->assign('INDEXFILE', [
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['indexfile'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.indexfile');

    $xtpl->assign('MOBILE_INDEXFILE', [
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['mobile_indexfile'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.mobile_indexfile');
}

// So bai viet tren mot trang
for ($i = 5; $i <= 100; ++$i) {
    $xtpl->assign('PER_PAGE', [
        'key' => $i,
        'title' => $i,
        'selected' => $i == $module_config[$module_name]['per_page'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.per_page');
}

// Bai viet chi hien thi link
for ($i = 0; $i <= 50; ++$i) {
    $xtpl->assign('ST_LINKS', [
        'key' => $i,
        'title' => $i,
        'selected' => $i == $module_config[$module_name]['st_links'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.st_links');
}

// Social_buttons
$my_socialbuttons = !empty($module_config[$module_name]['socialbutton']) ? array_map('trim', explode(',', $module_config[$module_name]['socialbutton'])) : [];
foreach ($socialbuttons as $socialbutton) {
    $array = [
        'key' => $socialbutton,
        'title' => ucfirst($socialbutton),
        'checked' => (!empty($my_socialbuttons) and in_array($socialbutton, $my_socialbuttons, true)) ? ' checked="checked"' : ''
    ];
    if ($socialbutton == 'zalo' and empty($global_config['zaloOfficialAccountID'])) {
        $array['title'] .= ' (<a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=zalo&amp;' . NV_OP_VARIABLE . '=settings">' . $lang_module['socialbutton_zalo_note'] . '</a>)';
        $array['checked'] = ' disabled="disabled"';
    }
    $xtpl->assign('SOCIALBUTTON', $array);
    $xtpl->parse('main.socialbutton');
}

// Show points rating article on google
for ($i = 0; $i <= 6; ++$i) {
    $xtpl->assign('RATING_POINT', [
        'key' => $i,
        'title' => ($i == 6) ? $lang_module['no_allowed_rating'] : $i,
        'selected' => $i == $module_config[$module_name]['allowed_rating_point'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.allowed_rating_point');
}

$xtpl->assign('SHOWTOOLTIP', $module_config[$module_name]['showtooltip'] ? ' checked="checked"' : '');
$xtpl->assign('SHOWHOMETEXT', $module_config[$module_name]['showhometext'] ? ' checked="checked"' : '');
$xtpl->assign('HTMLHOMETEXT', $module_config[$module_name]['htmlhometext'] ? ' checked="checked"' : '');
$xtpl->assign('TAGS_ALIAS', $module_config[$module_name]['tags_alias'] ? ' checked="checked"' : '');
$xtpl->assign('ALIAS_LOWER', $module_config[$module_name]['alias_lower'] ? ' checked="checked"' : '');
$xtpl->assign('AUTO_TAGS', $module_config[$module_name]['auto_tags'] ? ' checked="checked"' : '');
$xtpl->assign('TAGS_REMIND', $module_config[$module_name]['tags_remind'] ? ' checked="checked"' : '');
$xtpl->assign('KEYWORDS_TAG', $module_config[$module_name]['keywords_tag'] ? ' checked="checked"' : '');
$xtpl->assign('COPY_NEWS', $module_config[$module_name]['copy_news'] ? ' checked="checked"' : '');
$xtpl->assign('ELAS_USE', $module_config[$module_name]['elas_use'] ? ' checked="checked"' : '');
$xtpl->assign('SHOW_NO_IMAGE', (!empty($module_config[$module_name]['show_no_image'])) ? NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'] : '');
$xtpl->assign('INSTANT_ARTICLES_URL_DEFAULT', NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=instant-rss', true));
$xtpl->assign('INSTANT_ARTICLES_ACTIVE', $module_config[$module_name]['instant_articles_active'] ? ' checked="checked"' : '');
$xtpl->assign('INSTANT_ARTICLES_HTTPAUTH', $module_config[$module_name]['instant_articles_httpauth'] ? ' checked="checked"' : '');
$xtpl->assign('INSTANT_ARTICLES_AUTO', $module_config[$module_name]['instant_articles_auto'] ? ' checked="checked"' : '');
$xtpl->assign('IDENTIFY_CAT_CHANGE', $module_config[$module_name]['identify_cat_change'] ? ' checked="checked"' : '');
$xtpl->assign('ALLOWED_RATING', $module_config[$module_name]['allowed_rating'] ? ' checked="checked"' : '');
$xtpl->assign('ACTIVE_HISTORY', !empty($module_config[$module_name]['active_history']) ? ' checked="checked"' : '');

$xtpl->assign('FRONTEND_EDIT_ALIAS', $module_config[$module_name]['frontend_edit_alias'] ? ' checked="checked"' : '');
$xtpl->assign('FRONTEND_EDIT_LAYOUT', $module_config[$module_name]['frontend_edit_layout'] ? ' checked="checked"' : '');

if (!empty($module_config[$module_name]['instant_articles_password'])) {
    $xtpl->assign('INSTANT_ARTICLES_PASSWORD', $crypt->decrypt($module_config[$module_name]['instant_articles_password']));
} else {
    $xtpl->assign('INSTANT_ARTICLES_PASSWORD', '');
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

$structure_image_upload = isset($module_config[$module_name]['structure_upload']) ? $module_config[$module_name]['structure_upload'] : 'Ym';

// Thu muc uploads
foreach ($array_structure_image as $type => $dir) {
    $xtpl->assign('STRUCTURE_UPLOAD', [
        'key' => $type,
        'title' => $dir,
        'selected' => $type == $structure_image_upload ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.structure_upload');
}

for ($i = 0; $i < 2; ++$i) {
    $xtpl->assign('ORDER_ARTICLES', [
        'key' => $i,
        'title' => $lang_module['order_articles_' . $i],
        'selected' => $i == $module_config[$module_name]['order_articles'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.order_articles');
}

// Cau hinh hien thi nguon tin
$array_config_source = [
    0 => $lang_module['config_source_title'],
    3 => $lang_module['config_source_link'],
    1 => $lang_module['config_source_link_nofollow'],
    2 => $lang_module['config_source_logo']
];
foreach ($array_config_source as $key => $val) {
    $xtpl->assign('CONFIG_SOURCE', [
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['config_source'] ? ' selected="selected"' : ''
    ]);
    $xtpl->parse('main.config_source');
}

$array_imgposition = [
    0 => $lang_module['imgposition_0'],
    1 => $lang_module['imgposition_1'],
    2 => $lang_module['imgposition_2']
];

// Position images
foreach ($array_imgposition as $id_imgposition => $title_imgposition) {
    $sl = ($id_imgposition == $module_config[$module_name]['imgposition']) ? ' selected="selected"' : '';
    $xtpl->assign('id_imgposition', $id_imgposition);
    $xtpl->assign('title_imgposition', $title_imgposition);
    $xtpl->assign('posl', $sl);
    $xtpl->parse('main.looppos');
}

$copyright = nv_htmlspecialchars(nv_editor_br2nl($module_config[$module_name]['copyright']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $_uploads_dir = NV_UPLOADS_DIR . '/' . $module_upload;
    $copyright = nv_aleditor('copyright', '100%', '100px', $copyright, 'Basic', $_uploads_dir, $_uploads_dir);
} else {
    $copyright = '<textarea style="width: 100%" name="copyright" id="copyright" cols="20" rows="15">' . $copyright . '</textarea>';
}
$xtpl->assign('COPYRIGHTHTML', $copyright);

$xtpl->assign('PATH', defined('NV_IS_SPADMIN') ? '' : NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('CURRENTPATH', defined('NV_IS_SPADMIN') ? 'images' : NV_UPLOADS_DIR . '/' . $module_upload);

if (defined('NV_IS_ADMIN_FULL_MODULE') or !in_array('admins', $allow_func, true)) {
    $groups_list = nv_groups_list();
    unset($groups_list[1], $groups_list[2], $groups_list[3], $groups_list[6]);

    $savepost = $nv_Request->get_int('savepost', 'post', 0);
    if (!empty($savepost)) {
        $array_config = [];
        $array_group_id = $nv_Request->get_typed_array('array_group_id', 'post', 'int', []);
        $array_addcontent = $nv_Request->get_typed_array('array_addcontent', 'post', 'int', []);
        $array_postcontent = $nv_Request->get_typed_array('array_postcontent', 'post', 'int', []);
        $array_editcontent = $nv_Request->get_typed_array('array_editcontent', 'post', 'int', []);
        $array_delcontent = $nv_Request->get_typed_array('array_delcontent', 'post', 'int', []);

        $array_config['frontend_edit_alias'] = $nv_Request->get_int('frontend_edit_alias', 'post', 0);
        $array_config['frontend_edit_layout'] = $nv_Request->get_int('frontend_edit_layout', 'post', 0);

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
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
    }

    $array_post_data = [];

    $sql = 'SELECT group_id, addcontent, postcontent, editcontent, delcontent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_post ORDER BY group_id ASC';
    $result = $db->query($sql);
    while (list($group_id, $addcontent, $postcontent, $editcontent, $delcontent) = $result->fetch(3)) {
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

    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

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

        $xtpl->assign('ROW', [
            'group_id' => $group_id,
            'group_title' => $group_title,
            'addcontent' => $addcontent ? ' checked="checked"' : '',
            'postcontent' => $postcontent ? ' checked="checked"' : '',
            'editcontent' => $group_id != 5 ? ($editcontent ? ' checked="checked"' : '') : ' disabled="disabled"',
            'delcontent' => $group_id != 5 ? ($delcontent ? ' checked="checked"' : '') : ' disabled="disabled"'
        ]);

        $xtpl->parse('main.admin_config_post.loop');
    }

    $xtpl->parse('main.admin_config_post');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
