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

    $global_config['site_favicon'] = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $global_config['site_favicon'] = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    if (isset($nv_plugin_area[4])) {
        // Kết nối với các plugin sau khi xây dựng nội dung module
        foreach ($nv_plugin_area[4] as $_fplugin) {
            include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
        }
    }

    $tpl = new \NukeViet\Template\NvSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');

    $tpl->assign('LANG', $lang_global);
    $tpl->assign('NV_BASE_TEMPLATE', NV_BASE_SITEURL . 'themes/' . $global_config['module_theme']);
    $tpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['user_check_pass_time']) - 62) * 1000);
    $tpl->assign('MODULE_NAME', $module_name);

    // System variables
    $tpl->assign('theme_page_title', nv_html_page_title(false));
    $tpl->assign('global_config', $global_config);

    //Meta-tags
    $metatags = nv_html_meta_tags(false);
    if ($global_config['current_theme_type'] == 'r') {
        $metatags[] = array(
            'name' => 'name',
            'value' => 'viewport',
            'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'
        );
    }
    $tpl->assign('metatags', $metatags);

    //Links
    $html_links = array();
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
    $tpl->assign('html_links', $html_links);

    $html_js = nv_html_site_js(false);
    $html_js[] = array(
        'ext' => 1,
        'content' => NV_BASE_SITEURL . 'themes/' . $global_config['module_theme'] . '/js/main.js'
    );
    $tpl->assign('html_js', $html_js);

    if ($client_info['browser']['key'] == 'explorer' and $client_info['browser']['version'] < 9) {
        $tpl->assign('chromeframe', 1);
    }

    // Header variables
    $size = @getimagesize(NV_ROOTDIR . '/' . $global_config['site_logo']);
    $logo = preg_replace('/\.[a-z]+$/i', '.svg', $global_config['site_logo']);
    if (!file_exists(NV_ROOTDIR . '/' . $logo)) {
        $logo = $global_config['site_logo'];
    }
    $_logo = array(
        'src' => NV_BASE_SITEURL . $logo,
        'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA,
        'width' => $size[0],
        'height' => $size[1]
    );
    $tpl->assign('logo', $_logo);
    $sitecontent = $tpl->fetch($layout_file);

    // Module contents
    $sitecontent = str_replace('[MODULE_CONTENT]', $contents, $sitecontent);
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