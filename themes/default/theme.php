<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_SYSTEM') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 *  nv_mailHTML()
 *
 * @param string $title
 * @param string $content
 * @param string $footer
 */
function nv_mailHTML($title, $content, $footer = '')
{
    global $global_config, $lang_global;
    $xtpl = new XTemplate('mail.tpl', NV_ROOTDIR . '/themes/default/system');
    $xtpl->assign('SITE_URL', NV_MY_DOMAIN);
    $xtpl->assign('GCONFIG', $global_config);
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('MESSAGE_TITLE', $title);
    $xtpl->assign('MESSAGE_CONTENT', $content);
    $xtpl->assign('MESSAGE_FOOTER', $footer);
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 *  nv_site_theme()
 *
 * @param string $contents
 * @param bool $full
 */
function nv_site_theme($contents, $full = true)
{
    global $home, $array_mod_title, $lang_global, $global_config, $site_mods, $module_name, $module_info, $op_file, $mod_title, $my_head, $my_footer, $client_info, $module_config, $op, $nv_plugin_area;

    // Determine tpl file, check exists tpl file
    $layout_file = ($full) ? 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' : 'simple.tpl';

    if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout/' . $layout_file)) {
        nv_info_die($lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content']);
    }

    if (isset($global_config['sitetimestamp'])) {
        $global_config['timestamp'] += $global_config['sitetimestamp'];
    }

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    if (isset($nv_plugin_area[4])) {
        // Kết nối với các plugin sau khi xây dựng nội dung module
        foreach ($nv_plugin_area[4] as $_fplugin) {
            include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
        }
    }

    $xtpl = new XTemplate($layout_file, NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

    $xtpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $xtpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $xtpl->assign('NV_SITE_TITLE', $global_config['site_name'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['admin_page'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title']);
    $xtpl->assign('SITE_DESCRIPTION', $global_config['site_description']);
    $xtpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['user_check_pass_time']) - 62) * 1000);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('NV_CURRENTTIME', nv_date($global_config['date_pattern'] . ', ' . $global_config['time_pattern'], NV_CURRENTTIME));
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $xtpl->assign('SITE_FAVICON', $site_favicon);
    // System variables
    $xtpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));

    //Meta-tags
    $metatags = nv_html_meta_tags(false);
    if ($global_config['current_theme_type'] == 'r') {
        $metatags[] = array(
            'name' => 'name',
            'value' => 'viewport',
            'content' => 'width=device-width, initial-scale=1'
        );
    }

    foreach ($metatags as $meta) {
        $xtpl->assign('THEME_META_TAGS', $meta);
        $xtpl->parse('main.metatags');
    }

    //Links
    $html_links = array();
    $html_links[] = array(
        'rel' => 'StyleSheet',
        'href' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/font-awesome.min.css'
    );
    if ($global_config['current_theme_type'] == 'r') {
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/bootstrap.min.css'
        );
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/style.css'
        );
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/style.responsive.css'
        );
    } else {
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/bootstrap.non-responsive.css'
        );
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/style.css'
        );
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/style.non-responsive.css'
        );
    }
    if (defined('NV_IS_ADMIN') and $full) {
        $html_links[] = array(
            'rel' => 'StyleSheet',
            'href' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/css/admin.css'
        );
    }
    $html_links = array_merge_recursive($html_links, nv_html_links(false));

    // Customs Style
    if (isset($module_config['themes'][$global_config['module_theme']]) and !empty($module_config['themes'][$global_config['module_theme']])) {
        $config_theme = unserialize($module_config['themes'][$global_config['module_theme']]);

        if (isset($config_theme['css_content']) and !empty($config_theme['css_content'])) {
            $customFileName = $global_config['module_theme'] . '.' . NV_LANG_DATA . '.' . $global_config['idsite'];

            if (!file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $customFileName . '.css')) {
                $replace = array(
                    '[body]' => 'body',
                    '[a_link]' => 'a, a:link, a:active, a:visited',
                    '[a_link_hover]' => 'a:hover',
                    '[content]' => '.wraper',
                    '[header]' => '#header',
                    '[footer]' => '#footer',
                    '[block]' => '.panel, .well, .nv-block-banners',
                    '[block_heading]' => '.panel-default > .panel-heading'
                );

                $css_content = str_replace(array_keys($replace), array_values($replace), $config_theme['css_content']);

                file_put_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $customFileName . '.css', $css_content);
            }

            $html_links[] = array(
                'rel' => 'StyleSheet',
                'href' => NV_BASE_SITEURL . NV_ASSETS_DIR . '/css/' . $customFileName . '.css'
            );
        }

        if (isset($config_theme['gfont']) and !empty($config_theme['gfont']) and isset($config_theme['gfont']['family']) and !empty($config_theme['gfont']['family'])) {
            $subset = isset($config_theme['gfont']['subset']) ? $config_theme['gfont']['subset'] : '';
            $gf = new NukeViet\Client\Gfonts(array(
                'fonts' => array(
                    $config_theme['gfont']
                ),
                'subset' => $subset
            ), $client_info);
            $webFontFile = $gf->getUrlCss();
            array_unshift($html_links, array(
                'rel' => 'StyleSheet',
                'href' => $webFontFile
            ));
        }

        unset($config_theme, $css_content, $webFontFile, $font, $subset, $gf);
    }

    foreach ($html_links as $links) {
        foreach ($links as $key => $value) {
            $xtpl->assign('LINKS', array(
                'key' => $key,
                'value' => $value
            ));
            if (!empty($value)) {
                $xtpl->parse('main.links.attr.val');
            }
            $xtpl->parse('main.links.attr');
        }
        $xtpl->parse('main.links');
    }

    $html_js = nv_html_site_js(false);
    $html_js[] = array(
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/main.js'
    );

    foreach ($html_js as $js) {
        if ($js['ext']) {
            $xtpl->assign('JS_SRC', $js['content']);
            $xtpl->parse('main.js.ext');
        } else {
            $xtpl->assign('JS_CONTENT', PHP_EOL . $js['content'] . PHP_EOL);
            $xtpl->parse('main.js.int');
        }
        $xtpl->parse('main.js');
    }

    if ($client_info['browser']['key'] == 'explorer' and $client_info['browser']['version'] < 9) {
        $xtpl->parse('main.lt_ie9');
    }

    // Module contents
    $xtpl->assign('MODULE_CONTENT', $contents);

    // Header variables
    $xtpl->assign('SITE_NAME', $global_config['site_name']);
    $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
    $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
    if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
        $logo = $global_config['site_logo'];
    }
    $xtpl->assign('LOGO_SRC', NV_BASE_SITEURL . $logo);
    $xtpl->assign('LOGO_WIDTH', $size[0]);
    $xtpl->assign('LOGO_HEIGHT', $size[1]);

    if (isset($size['mime']) and $size['mime'] == 'application/x-shockwave-flash') {
        $xtpl->parse('main.swf');
    } else {
        $xtpl->parse('main.image');
    }

    if (preg_match("/<h1[^\>]*\>/i", $contents)) {
        $xtpl->parse('main.site_name_span');
    } else {
        $xtpl->parse('main.site_name_h1');
    }

    // Only full theme
    if ($full) {
        // Search form variables
        $xtpl->assign('NV_MAX_SEARCH_LENGTH', NV_MAX_SEARCH_LENGTH);
        $xtpl->assign('NV_MIN_SEARCH_LENGTH', NV_MIN_SEARCH_LENGTH);

        if (!$global_config['rewrite_enable']) {
            $xtpl->assign('THEME_SEARCH_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=seek&amp;q=');
        } else {
            $xtpl->assign('THEME_SEARCH_URL', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=seek', true) . '?q=');
        }

        // Breadcrumbs
        if (!$home) {
            $array_mod_title_copy = $array_mod_title;
            if ($global_config['rewrite_op_mod'] != $module_name) {
                $arr_cat_title_i = array(
                    'catid' => 0,
                    'title' => $module_info['custom_title'],
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
                );
                array_unshift($array_mod_title_copy, $arr_cat_title_i);
            }
            if (!empty($array_mod_title_copy)) {
                foreach ($array_mod_title_copy as $arr_cat_title_i) {
                    $xtpl->assign('BREADCRUMBS', $arr_cat_title_i);
                    $xtpl->parse('main.breadcrumbs.loop');
                }
            }
            $xtpl->parse('main.breadcrumbs');
        } elseif (empty($array_mod_title_copy)) {
            $xtpl->parse('main.currenttime');
        }

        // Statistics image
        $theme_stat_img = '';
        if ($global_config['statistic'] and isset($site_mods['statistics'])) {
            $theme_stat_img .= "<a title=\"" . $lang_global['viewstats'] . "\" href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=statistics\"><img alt=\"" . $lang_global['viewstats'] . "\" src=\"" . NV_BASE_SITEURL . "index.php?second=statimg&amp;p=" . nv_genpass() . "\" width=\"88\" height=\"31\" /></a>\n";
        }

        $xtpl->assign('THEME_STAT_IMG', $theme_stat_img);

        // Change theme types
        if (sizeof($global_config['array_theme_type']) > 1) {
            $mobile_theme = empty($module_info['mobile']) ? $global_config['mobile_theme'] : (($module_info['mobile'] != ':pcmod' and $module_info['mobile'] != ':pcsite') ? $module_info['mobile'] : '');
            if (empty($mobile_theme) or empty($global_config['switch_mobi_des'])) {
                $array_theme_type = array_diff($global_config['array_theme_type'], array(
                    'm'
                ));
            } else {
                $array_theme_type = $global_config['array_theme_type'];
            }
            $icons = array(
                'r' => 'random',
                'd' => 'desktop',
                'm' => 'mobile'
            );
            $current_theme_type = (isset($global_config['current_theme_type']) and !empty($global_config['current_theme_type']) and in_array($global_config['current_theme_type'], array_keys($icons))) ? $global_config['current_theme_type'] : 'd';
            foreach ($array_theme_type as $theme_type) {
                $xtpl->assign('STHEME_TYPE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;nv' . NV_LANG_DATA . 'themever=' . $theme_type . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
                $xtpl->assign('STHEME_TITLE', $lang_global['theme_type_' . $theme_type]);
                $xtpl->assign('STHEME_INFO', sprintf($lang_global['theme_type_chose'], $lang_global['theme_type_' . $theme_type]));
                $xtpl->assign('STHEME_ICON', $icons[$theme_type]);

                if ($theme_type == $current_theme_type) {
                    $xtpl->parse('main.theme_type.loop.current');
                } else {
                    $xtpl->parse('main.theme_type.loop.other');
                }

                $xtpl->parse('main.theme_type.loop');
            }
            $xtpl->parse('main.theme_type');
        }
    }

    $xtpl->parse('main');
    $sitecontent = $xtpl->text('main');

    // Only full theme
    if ($full) {
        $sitecontent = nv_blocks_content($sitecontent);
        $sitecontent = str_replace('[THEME_ERROR_INFO]', nv_error_info(), $sitecontent);
    }

    if (!empty($my_head)) {
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . '\\1', $sitecontent, 1);
    }
    if (!empty($my_footer)) {
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . '\\1', $sitecontent, 1);
    }

    if (defined('NV_IS_ADMIN') and $full) {
        $sitecontent = preg_replace('/(<\/body>)/i', PHP_EOL . nv_admin_menu() . PHP_EOL . '\\1', $sitecontent, 1);
    }

    return $sitecontent;
}

/**
 *  nv_error_theme()
 *
 * @param string $title
 * @param string $content
 * @param integer $code
 */
function nv_error_theme($title, $content, $code)
{
    nv_info_die($title, $title, $content, $code);
}