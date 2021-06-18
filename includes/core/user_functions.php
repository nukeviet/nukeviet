<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1-27-2010 5:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Meta Property
$meta_property = [
    'og:title' => '',
    'og:type' => '',
    'og:description' => '',
    'og:site_name' => '',
    'og:image' => '',
    'og:url' => ''
];

/**
 * nv_create_submenu()
 *
 * @return void
 */
function nv_create_submenu()
{
    global $nv_vertical_menu, $module_name, $module_info, $op;

    foreach ($module_info['funcs'] as $key => $values) {
        if (!empty($values['in_submenu'])) {
            $func_custom_name = trim(!empty($values['func_custom_name']) ? $values['func_custom_name'] : $key);
            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . ($key != 'main' ? '&amp;' . NV_OP_VARIABLE . '=' . $key : '');
            $act = $key == $op ? 1 : 0;
            $nv_vertical_menu[] = array( $func_custom_name, $link, $act );
        }
    }
}

/**
 * nv_blocks_get_content()
 *
 * @return
 */
function nv_blocks_content($sitecontent)
{
    global $db, $nv_Cache, $module_info, $module_name, $op, $global_config, $lang_global, $sys_mods, $client_info, $theme_config_positions;

    $_posAllowed = [];

    foreach ($theme_config_positions as $_pos) {
        $_pos = trim(( string )$_pos['tag']);
        unset($matches);
        if (preg_match('/^\[([^\]]+)\]$/is', $_pos, $matches)) {
            $_posAllowed[] = $matches[1];
        }
    }

    if (empty($_posAllowed)) {
        return $sitecontent;
    }

    //Tim trong noi dung trang cac doan ma phu hop voi cac nhom block tren
    $_posAllowed = implode('|', array_map('nv_preg_quote', $_posAllowed));
    preg_match_all('/\[(' . $_posAllowed . ')(\d+)?\]()/', $sitecontent, $_posReal);

    if (empty($_posReal[0])) {
        return $sitecontent;
    }

    $_posReal = array_combine($_posReal[0], $_posReal[3]);

    $cache_file = NV_LANG_DATA . '_' . $global_config['module_theme'] . '_' . $module_name . '_' . NV_CACHE_PREFIX . '.cache';
    $blocks = [];

    if (($cache = $nv_Cache->getItem('themes', $cache_file)) !== false) {
        $cache = unserialize($cache);
        if (isset($cache[$module_info['funcs'][$op]['func_id']])) {
            $blocks = $cache[$module_info['funcs'][$op]['func_id']];
        }
        unset($cache);
    } else {
        $cache = [];
        $in = [];
        $list = $sys_mods[$module_name]['funcs'];
        foreach ($list as $row) {
            if ($row['show_func']) {
                $in[] = $row['func_id'];
            }
        }

        $_result = $db->query("SELECT t1.*, t2.func_id FROM " . NV_BLOCKS_TABLE . "_groups t1
             INNER JOIN " . NV_BLOCKS_TABLE . "_weight t2
             ON t1.bid = t2.bid
             WHERE t2.func_id IN (" . implode(',', $in) . ")
             AND t1.theme ='" . $global_config['module_theme'] . "'
             AND t1.active!=''
             ORDER BY t2.weight ASC");

        while ($_row = $_result->fetch()) {
            // Cau hinh block
            $block_config = (!empty($_row['config'])) ? unserialize($_row['config']) : [];
            $block_config['bid'] = $_row['bid'];
            $block_config['module'] = $_row['module'];
            $block_config['title'] = $_row['title'];
            $block_config['block_name'] = substr($_row['file_name'], 0, -4);

            // Tieu de block
            $blockTitle = (!empty($_row['title']) and !empty($_row['link'])) ? "<a href=\"" . $_row['link'] . "\">" . $_row['title'] . "</a>" : $_row['title'];

            if (!isset($cache[$_row['func_id']])) {
                $cache[$_row['func_id']] = [];
            }
            $cache[$_row['func_id']][] = array(
                'bid' => $_row['bid'],
                'position' => $_row['position'],
                'module' => $_row['module'],
                'blockTitle' => $blockTitle,
                'file_name' => $_row['file_name'],
                'template' => $_row['template'],
                'exp_time' => $_row['exp_time'],
                'show_device' => !empty($_row['active']) ? explode(',', $_row['active']) : [],
                'act' => $_row['act'],
                'groups_view' => $_row['groups_view'],
                'all_func' => $_row['all_func'],
                'block_config' => $block_config
            );
        }
        $_result->closeCursor();

        if (isset($cache[$module_info['funcs'][$op]['func_id']])) {
            $blocks = $cache[$module_info['funcs'][$op]['func_id']];
        }

        $cache = serialize($cache);
        $nv_Cache->setItem('themes', $cache_file, $cache);

        unset($cache, $in, $block_config, $blockTitle);
    }

    if (!empty($blocks)) {
        $unact = [];
        global $blockID;

        $array_position = array_keys($_posReal);
        foreach ($blocks as $_key => $_row) {
            if (!defined('NV_IS_DRAG_BLOCK') and !$_row['act']) {
                continue;
            }

            if ($_row['exp_time'] != 0 and $_row['exp_time'] <= NV_CURRENTTIME) {
                $unact[] = $_row['bid'];
                continue;
            }

            // Kiem hien thi tren cac thiet bi
            $_active = false;
            if (in_array(1, $_row['show_device'])) {
                $_active = true;
            } else {
                if ($client_info['is_mobile'] and in_array(2, $_row['show_device'])) {
                    $_active = true;
                } elseif ($client_info['is_tablet'] and in_array(3, $_row['show_device'])) {
                    $_active = true;
                } elseif (!$client_info['is_mobile'] and !$client_info['is_tablet'] and in_array(4, $_row['show_device'])) {
                    $_active = true;
                }
            }

            // Kiem tra quyen xem block
            if ($_active and in_array($_row['position'], $array_position) and nv_user_in_groups($_row['groups_view'])) {
                $block_config = $_row['block_config'];
                $blockTitle = $_row['blockTitle'];
                $content = '';
                $blockID = 'nv' . $_key;

                if ($_row['module'] == 'theme' and file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/' . $_row['file_name'])) {
                    include NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/' . $_row['file_name'];
                } elseif (isset($sys_mods[$_row['module']]['module_file']) and !empty($sys_mods[$_row['module']]['module_file']) and file_exists(NV_ROOTDIR . '/modules/' . $sys_mods[$_row['module']]['module_file'] . '/blocks/' . $_row['file_name'])) {
                    include NV_ROOTDIR . '/modules/' . $sys_mods[$_row['module']]['module_file'] . '/blocks/' . $_row['file_name'];
                }
                unset($block_config);

                if (!empty($content) or defined('NV_IS_DRAG_BLOCK')) {
                    $xtpl = null;
                    $_row['template'] = empty($_row['template']) ? 'default' : $_row['template'];
                    $_template = 'default';

                    if (!empty($module_info['theme']) and file_exists(NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/layout/block.' . $_row['template'] . '.tpl')) {
                        $xtpl = new XTemplate('block.' . $_row['template'] . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['theme'] . '/layout');
                        $_template = $module_info['theme'];
                    } elseif (!empty($global_config['module_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/block.' . $_row['template'] . '.tpl')) {
                        $xtpl = new XTemplate('block.' . $_row['template'] . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
                        $_template = $global_config['module_theme'];
                    } elseif (!empty($global_config['site_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout/block.' . $_row['template'] . '.tpl')) {
                        $xtpl = new XTemplate('block.' . $_row['template'] . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout');
                        $_template = $global_config['site_theme'];
                    } elseif (file_exists(NV_ROOTDIR . '/themes/default/layout/block.' . $_row['template'] . '.tpl')) {
                        $xtpl = new XTemplate('block.' . $_row['template'] . '.tpl', NV_ROOTDIR . '/themes/default/layout');
                    }
                    if (!empty($xtpl)) {
                        $xtpl->assign('BLOCK_ID', $_row['bid']);
                        $xtpl->assign('BLOCK_TITLE', $_row['blockTitle']);
                        $xtpl->assign('BLOCK_CONTENT', $content);
                        $xtpl->assign('TEMPLATE', $_template);
                        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

                        $xtpl->parse('mainblock');
                        $content = $xtpl->text('mainblock');
                    } else {
                        $content = $_row['blockTitle'] . '<br />' . $content . '<br />';
                    }

                    if (defined('NV_IS_DRAG_BLOCK')) {
                        $act_class = $_row['act'] ? '' : ' act0';
                        $act_title = $_row['act'] ? $lang_global['act_block'] : $lang_global['deact_block'];
                        $act_icon = $_row['act'] ? 'fa fa-check-square-o' : 'fa fa-square-o';
                        $checkss = md5(NV_CHECK_SESSION . '_' . $_row['bid']);
                        $content = '<div class="portlet" id="bl_' . ($_row['bid']) . '">
                             <div class="tool">
                                 <a href="javascript:void(0)" class="block_content" name="' . $_row['bid'] . '" alt="' . $lang_global['edit_block'] . '" title="' . $lang_global['edit_block'] . '"><em class="fa fa-wrench"></em></a>
                                 <a href="javascript:void(0)" class="delblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $lang_global['delete_block'] . '" title="' . $lang_global['delete_block'] . '"><em class="fa fa-trash"></em></a>
                                 <a href="javascript:void(0)" class="actblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $act_title . '" title="' . $act_title . '" data-act="' . $lang_global['act_block'] . '" data-deact="' . $lang_global['deact_block'] . '"><em class="' . $act_icon . '" data-act="fa fa-check-square-o" data-deact="fa fa-square-o"></em></a>
                                 <a href="javascript:void(0)" class="outgroupblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $lang_global['outgroup_block'] . '" title="' . $lang_global['outgroup_block'] . '"><em class="fa fa-share-square-o"></em></a>
                             </div>
                             <div class="blockct' . $act_class . '">' . $content . '</div>
                             </div>';
                    }

                    $_posReal[$_row['position']] .= $content;
                }
            }
        }
        if (!empty($unact)) {
            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act=0 WHERE bid IN (' . implode(',', $unact) . ')');
            $nv_Cache->delMod('themes', NV_LANG_DATA);
        }
    }

    if (defined('NV_IS_DRAG_BLOCK')) {
        $array_keys = array_keys($_posReal);
        foreach ($array_keys as $__pos) {
            $__pos_name = str_replace(array( '[', ']' ), array('', ''), $__pos);
            $_posReal[$__pos] = '<div class="column" data-id="' . $__pos_name . '" data-checkss="' . md5(NV_CHECK_SESSION . '_' . $__pos_name) . '">' . $_posReal[$__pos];
            $_posReal[$__pos] .= '<a href="javascript:void(0);" class="add block_content" id="' . $__pos . '" title="' . $lang_global['add_block'] . ' ' . $__pos_name . '" alt="' . $lang_global['add_block'] . '"><em class="fa fa-plus"></em></a>';
            $_posReal[$__pos] .= '</div>';
        }
    }

    $sitecontent = str_replace(array_keys($_posReal), array_values($_posReal), $sitecontent);

    return $sitecontent;
}

/**
 * nv_html_meta_tags()
 *
 * @param bool $html
 * @return
 */
function nv_html_meta_tags($html = true)
{
    global $global_config, $lang_global, $key_words, $description, $module_info, $home, $client_info, $op, $page_title, $canonicalUrl, $meta_property, $nv_BotManager;

    $return = [];

    // Tại trang chủ lấy mô tả của site thay vì mô tả của module chọn làm trang chủ
    $site_description = $home ? $global_config['site_description'] : (!empty($description) ? $description : (empty($module_info['description']) ? '' : $module_info['description']));

    if (empty($site_description)) {
        $ds = [];
        if (!empty($page_title)) {
            $ds[] = $page_title;
        }
        if ($op != 'main') {
            $ds[] = $module_info['funcs'][$op]['func_custom_name'];
        }
        $ds[] = $module_info['custom_title'];
        $ds[] = $client_info['selfurl'];
        $site_description = implode(' - ', $ds);
    } elseif ($site_description == 'no') {
        $site_description = '';
    }

    if (!empty($site_description)) {
        $site_description = preg_replace(['/<[^>]*>/', '/[\r\n\t]+/'], ' ', $site_description);
        $site_description = trim(preg_replace('/[ ]+/', ' ', $site_description));
        if ($global_config['description_length']) {
            $site_description = nv_clean60($site_description, $global_config['description_length']);
        }

        $return[] = [
            'name' => 'name',
            'value' => 'description',
            'content' => $site_description
        ];
    }

    $kw = [];
    if (!empty($key_words)) {
        if ($key_words != 'no') {
            $kw[] = $key_words;
        }
    } elseif (!empty($module_info['keywords'])) {
        $kw[] = $module_info['keywords'];
    }

    if ($home and !empty($global_config['site_keywords'])) {
        $kw[] = $global_config['site_keywords'];
    }

    if (!empty($kw)) {
        $kw = array_unique($kw);
        $key_words = implode(',', $kw);
        $key_words = preg_replace(["/[ ]*\,[ ]+/", "/[\,]+/"], [", ", ", "], $key_words);
        $key_words = nv_strtolower(strip_tags($key_words));
        $return[] = [
            'name' => 'name',
            'value' => 'keywords',
            'content' => $key_words
        ];
        $return[] = [
            'name' => 'name',
            'value' => 'news_keywords',
            'content' => $key_words
        ];
    }

    $return[] = [
        'name' => 'http-equiv',
        'value' => 'Content-Type',
        'content' => 'text/html; charset=' . $global_config['site_charset']
    ];

    // Thêm các thẻ meta từ cấu hình Meta-Tags trong admin
    if ($global_config['idsite'] and file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_metatags.xml')) {
        $file_metatags = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_metatags.xml';
    } else {
        $file_metatags = NV_ROOTDIR . '/' . NV_DATADIR . '/metatags.xml';
    }

    if (file_exists($file_metatags)) {
        $mt = file_get_contents($file_metatags);
        $patters = [];
        $patters['/\{CONTENT\-LANGUAGE\}/'] = $lang_global['Content_Language'];
        $patters['/\{LANGUAGE\}/'] = $lang_global['LanguageName'];
        $patters['/\{SITE\_NAME\}/'] = $global_config['site_name'];
        $patters['/\{SITE\_EMAIL\}/'] = $global_config['site_email'];
        $mt = preg_replace(array_keys($patters), array_values($patters), $mt);
        $mt = preg_replace('/\{(.*)\}/', '', $mt);
        $mt = simplexml_load_string($mt);
        $mt = nv_object2array($mt);

        if ($mt['meta_item']) {
            if (isset($mt['meta_item'][0])) {
                $metatags = $mt['meta_item'];
            } else {
                $metatags[] = $mt['meta_item'];
            }
            foreach ($metatags as $meta) {
                if (($meta['group'] == 'http-equiv' or $meta['group'] == 'name' or $meta['group'] == 'property') and preg_match('/^[a-zA-Z0-9\-\_\.\:]+$/', $meta['value']) and preg_match("/^([^\'\"]+)$/", ( string )$meta['content'])) {
                    $return[] = [
                        'name' => $meta['group'],
                        'value' => $meta['value'],
                        'content' => $meta['content']
                    ];
                }
            }
        }
    }

    // Robot metatags
    $return = array_merge_recursive($return, $nv_BotManager->getMetaTags());

    /**
     * Đọc kỹ giấy phép trước khi thay đổi giá trị này
     * @link https://github.com/nukeviet/nukeviet/blob/nukeviet4.4/LICENSE
     */
    $return[] = [
        'name' => 'name',
        'value' => 'generator',
        'content' => 'NukeViet v4.4'
    ];

    if (defined('NV_IS_ADMIN')) {
        $return[] = [
            'name' => 'http-equiv',
            'value' => 'refresh',
            'content' => $global_config['admin_check_pass_time']
        ];
    }

    if ($global_config['current_theme_type'] == 'r') {
        $return[] = [
            'name' => 'name',
            'value' => 'viewport',
            'content' => 'width=device-width, initial-scale=1'
        ];
    }

    if (empty($canonicalUrl)) {
        if ($home) {
            $canonicalUrl = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
        } else {
            $canonicalUrl = str_replace(NV_MY_DOMAIN . '/', NV_MAIN_DOMAIN . '/', $client_info['selfurl']);
        }
    }
    if (substr($canonicalUrl, 0, 4) != 'http') {
        if (substr($canonicalUrl, 0, 1) != '/') {
            $canonicalUrl = NV_BASE_SITEURL . $canonicalUrl;
        }
        $canonicalUrl = NV_MAIN_DOMAIN . $canonicalUrl;
    }

    // Open Graph protocol http://ogp.me
    if ($global_config['metaTagsOgp']) {
        if (empty($meta_property['og:title'])) {
            $meta_property['og:title'] = $page_title;
        }
        if (empty($meta_property['og:description'])) {
            $meta_property['og:description'] = $site_description;
        }
        if (empty($meta_property['og:type'])) {
            $meta_property['og:type'] = 'website';
        }
        if (empty($meta_property['og:url'])) {
            $meta_property['og:url'] = $canonicalUrl;
        }
        $meta_property['og:site_name'] = $global_config['site_name'];

        foreach ($meta_property as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $value_i) {
                    if (!empty($value_i)) {
                        $return[] = [
                            'name' => 'property',
                            'value' => $key,
                            'content' => $value_i
                        ];
                    }
                }
            } elseif (!empty($value)) {
                $return[] = [
                    'name' => 'property',
                    'value' => $key,
                    'content' => $value
                ];
            }
        }
    } else {
        foreach ($meta_property as $key => $value) {
            if (!preg_match('/^og\:/', $key)) {
                if (is_array($value)) {
                    foreach ($value as $value_i) {
                        if (!empty($value_i)) {
                            $return[] = [
                                'name' => 'property',
                                'value' => $key,
                                'content' => $value_i
                            ];
                        }
                    }
                } elseif (!empty($value)) {
                    $return[] = [
                        'name' => 'property',
                        'value' => $key,
                        'content' => $value
                    ];
                }
            }
        }
    }

    if (!$html) {
        return $return;
    }

    $res = '';
    foreach ($return as $link) {
        $res .= "<meta " . $link['name'] . "=\"" . $link['value'] . "\" content=\"" . $link['content'] . "\" />" . PHP_EOL;
    }
    return $res;
}

/**
 * nv_html_links()
 *
 * @param bool $html
 * @return
 */
function nv_html_links($html = true)
{
    global $canonicalUrl, $module_info, $db_config, $nv_Cache;

    $return = [];
    if (!empty($canonicalUrl)) {
        $return[] = array( 'rel' => 'canonical', 'href' => $canonicalUrl );
    }

    $nv_html_site_rss = nv_html_site_rss(false);
    if ($nv_html_site_rss) {
        $return = array_merge_recursive($return, $nv_html_site_rss);
    }

    $nv_html_css = nv_html_css(false);
    if ($nv_html_css) {
        $return = array_merge_recursive($return, $nv_html_css);
    }

    if (!$html) {
        return $return;
    }

    $res = '';
    foreach ($return as $link) {
        $res .= '<link ';
        foreach ($link as $key => $val) {
            $res .= $key . "=\"" . $val . "\" ";
        }
        $res .= "/>" . PHP_EOL;
    }
    return $res;
}


/**
 * nv_html_page_title()
 *
 * @param bool $html
 * @return
 */
function nv_html_page_title($html = true)
{
    global $home, $module_info, $op, $global_config, $page_title;

    if ($home) {
        $_title = $global_config['site_name'];
    } else {
        if (!isset($global_config['pageTitleMode']) or empty($global_config['pageTitleMode'])) {
            $global_config['pageTitleMode'] = 'pagetitle' . NV_TITLEBAR_DEFIS . 'sitename';
        }

        if (empty($page_title) and !preg_match('/(funcname|modulename|sitename)/i', $global_config['pageTitleMode'])) {
            $_title = $module_info['funcs'][$op]['func_custom_name'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
        } else {
            $_title = preg_replace(array(
                '/pagetitle/i',
                '/funcname/i',
                '/modulename/i',
                '/sitename/i' ), array(
                $page_title,
                $module_info['funcs'][$op]['func_custom_name'],
                $module_info['custom_title'],
                $global_config['site_name'] ), $global_config['pageTitleMode']);
        }
    }
    $_title = nv_htmlspecialchars(strip_tags($_title));
    if ($html) {
        return '<title>' . nv_htmlspecialchars(strip_tags($_title)) . '</title>' . PHP_EOL;
    }
    return $_title;
}


/**
 * nv_html_css()
 *
 * @param bool $html
 * @return
 */
function nv_html_css($html = true)
{
    global $module_info, $module_file;

    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/css/' . $module_info['module_theme'] . '.css')) {
        if ($html) {
            return "<link rel=\"StyleSheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/" . $module_info['module_theme'] . ".css\" type=\"text/css\" />" . PHP_EOL;
        } else {
            return array( array( 'rel' => 'StyleSheet', 'href' => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/" . $module_info['module_theme'] . ".css" ) );
        }
    }
    elseif (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/css/' . $module_file . '.css')) {
        if ($html) {
            return "<link rel=\"StyleSheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/" . $module_file . ".css\" type=\"text/css\" />" . PHP_EOL;
        } else {
            return array( array( 'rel' => 'StyleSheet', 'href' => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/" . $module_file . ".css" ) );
        }
    }

    return $html ? '' : [];
}

/**
 * nv_html_site_rss()
 *
 * @param bool $html
 * @return
 */
function nv_html_site_rss($html = true)
{
    global $rss;

    $return = $html ? '' : [];
    if (!empty($rss)) {
        foreach ($rss as $rss_item) {
            $href = $rss_item['src'] . "\" title=\"" . strip_tags($rss_item['title']);
            if ($html) {
                $return .= "<link rel=\"alternate\" href=\"" . $href . "\" type=\"application/rss+xml\" />" . PHP_EOL;
            } else {
                $return[] = array(
                    'rel' => 'alternate',
                    'href' => $href,
                    'type' => 'application/rss+xml' );
            }
        }
    }

    return $return;
}

/**
 * nv_html_site_js()
 * 
 * @param bool $html
 * Xuất ra dạng string (html) hay để nguyên dạng array
 * Mặc định true
 * 
 * @param array $other_js
 * Thêm js vào ngay sau global.js
 * Mặc định rỗng
 * 
 * @return string | array
 */
function nv_html_site_js($html = true, $other_js = [])
{
    global $global_config, $module_info, $module_name, $module_file, $lang_global, $op, $client_info, $user_info;

    $safemode = defined('NV_IS_USER') ? $user_info['safemode'] : 0;
    $jsDef = "var nv_base_siteurl=\"" . NV_BASE_SITEURL . "\",nv_lang_data=\"" . NV_LANG_INTERFACE . "\",nv_lang_interface=\"" . NV_LANG_INTERFACE . "\",nv_name_variable=\"" . NV_NAME_VARIABLE . "\",nv_fc_variable=\"" . NV_OP_VARIABLE . "\",nv_lang_variable=\"" . NV_LANG_VARIABLE . "\",nv_module_name=\"" . $module_name . "\",nv_func_name=\"" . $op . "\",nv_is_user=" . ((int) defined("NV_IS_USER")) . ", nv_my_ofs=" . round(NV_SITE_TIMEZONE_OFFSET / 3600) . ",nv_my_abbr=\"" . nv_date("T", NV_CURRENTTIME) . "\",nv_cookie_prefix=\"" . $global_config['cookie_prefix'] . "\",nv_check_pass_mstime=" . ((intval($global_config['user_check_pass_time']) - 62) * 1000) . ",nv_area_admin=0,nv_safemode=" . $safemode . ",theme_responsive=" . ((int) ($global_config['current_theme_type'] == 'r'));

    if (defined('NV_IS_DRAG_BLOCK')) {
        $jsDef .= ',drag_block=1,blockredirect="' . nv_redirect_encrypt($client_info['selfurl']) . '",selfurl="' . $client_info['selfurl'] . '",block_delete_confirm="' . $lang_global['block_delete_confirm'] . '",block_outgroup_confirm="' . $lang_global['block_outgroup_confirm'] . '",blocks_saved="' . $lang_global['blocks_saved'] . '",blocks_saved_error="' . $lang_global['blocks_saved_error'] . '",post_url="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=",func_id=' . $module_info['funcs'][$op]['func_id'] . ',module_theme="' . $global_config['module_theme'] . '"';
    }
    $jsDef .= ',nv_is_recaptcha=' . ($global_config['captcha_type'] == 2 ? '1' : '0');
    if ($global_config['captcha_type'] == 2) {
        $jsDef .= ',nv_recaptcha_sitekey="' . $global_config['recaptcha_sitekey'] . '",nv_recaptcha_type="' . $global_config['recaptcha_type'] . '",nv_recaptcha_elements=[]';
    }
    $jsDef .= ';';

    $return = [];
    $return[] = array(
        'ext' => 0,
        'content' => $jsDef
    );
    $return[] = array(
        'ext' => 1,
        'content' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery/jquery.min.js'
    );
    $return[] = array(
        'ext' => 1,
        'content' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/language/' . NV_LANG_INTERFACE . '.js'
    );
    $return[] = array(
        'ext' => 1,
        'content' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/global.js'
    );

    if (!empty($other_js)) {
        foreach ($other_js as $other) {
            if (isset($other['ext']) and ($other['ext'] == '0' or $other['ext'] == '1') and !empty($other['content'])) {
                $return[] = array(
                    'ext' => (int) $other['ext'],
                    'content' => $other['content']
                );
            }
        }
    }
    if (defined('NV_IS_ADMIN')) {
        $return[] = array(
            'ext' => 1,
            'content' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/admin.js'
        );
    }

    // module js
    if (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/js/' . $module_info['module_theme'] . '.js')) {
        $return[] = array(
            'ext' => 1,
            'content' => NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/js/' . $module_info['module_theme'] . '.js'
        );
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/js/' . $module_file . '.js')) {
        $return[] = array(
            'ext' => 1,
            'content' => NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/js/' . $module_file . '.js'
        );
    } elseif (file_exists(NV_ROOTDIR . '/themes/default/js/' . $module_file . '.js')) {
        $return[] = array(
            'ext' => 1,
            'content' => NV_BASE_SITEURL . 'themes/default/js/' . $module_file . '.js'
        );
    }

    if (defined('NV_IS_DRAG_BLOCK')) {
        $return[] = array(
            'ext' => 1,
            'content' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/js/jquery-ui/jquery-ui.min.js'
        );
    }

    if (!$html) {
        return $return;
    }
    $res = '';
    foreach ($return as $js) {
        if ($js['ext'] == 1) {
            $res .= "<script src=\"" . $js['content'] . "\"></script>" . PHP_EOL;
        } else {
            $res .= "<script>" . PHP_EOL;
            $res .= $js['content'] . PHP_EOL;
            $res .= "</script>" . PHP_EOL;
        }
    }
    return $res;
}

/**
 * nv_admin_menu()
 *
 * @return
 */
function nv_admin_menu()
{
    global $lang_global, $admin_info, $module_info, $module_name, $global_config, $client_info, $db_config, $db;

    if ($module_info['theme'] == $module_info['template'] and file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/system/admin_toolbar.tpl')) {
        $block_theme = $module_info['template'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/system/admin_toolbar.tpl')) {
        $block_theme = $global_config['site_theme'];
    } else {
        $block_theme = 'default';
    }

    $xtpl = new XTemplate('admin_toolbar.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/system');
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_ADMINDIR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    $xtpl->assign('URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=authors&amp;id=' . $admin_info['admin_id']);

    if (defined('NV_IS_SPADMIN')) {
        $row = $db->query('SELECT * FROM ' . $db_config['dbsystem'] . '.' . NV_AUTHORS_GLOBALTABLE . '_module WHERE act_' . $admin_info['level'] . ' = 1 AND module=\'themes\'')->fetch();
        if(!empty($row)){
            $new_drag_block = (defined('NV_IS_DRAG_BLOCK')) ? 0 : 1;
            $lang_drag_block = ($new_drag_block) ? $lang_global['drag_block'] : $lang_global['no_drag_block'];

            $xtpl->assign('URL_DBLOCK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;drag_block=' . $new_drag_block . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
            $xtpl->assign('LANG_DBLOCK', $lang_drag_block);
            $xtpl->parse('main.is_spadmin');
        }
    }

    if (defined('NV_IS_MODADMIN') and !empty($module_info['admin_file'])) {
        $xtpl->assign('URL_MODULE', NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
        $xtpl->assign('MODULENAME', $module_info['custom_title']);
        $xtpl->parse('main.is_modadmin');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_groups_list_pub()
 *
 * @param string $mod_data
 * @return
 */
function nv_groups_list_pub($mod_data = 'users')
{
    global $nv_Cache, $db, $db_config, $global_config;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;

    $query = 'SELECT group_id, title, group_type, exp_time FROM ' . $_mod_table . '_groups WHERE act=1 AND (idsite = ' . $global_config['idsite'] . ' OR (idsite =0 AND siteus = 1)) ORDER BY idsite, weight';
    $list = $nv_Cache->db($query, '', $mod_data);

    if (empty($list)) {
        return [];
    }

    $groups = [];
    $reload = [];
    for ($i = 0, $count = sizeof($list); $i < $count; ++$i) {
        if ($list[$i]['exp_time'] != 0 and $list[$i]['exp_time'] <= NV_CURRENTTIME) {
            $reload[] = $list[$i]['group_id'];
        } elseif ($list[$i]['group_type'] == 2) {
            $groups[$list[$i]['group_id']] = $list[$i]['title'];
        }
    }

    if ($reload) {
        $db->query('UPDATE ' . $_mod_table . '_groups SET act=0 WHERE group_id IN (' . implode(',', $reload) . ')');
        $nv_Cache->delMod($mod_data);
    }

    return $groups;
}
