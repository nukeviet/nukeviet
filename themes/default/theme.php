<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$theme_config = [
    'pagination' => [
        // Nếu dùng bootstrap 3: 'pagination'
        // Nếu dùng bootstrap 4/5: 'pagination justify-content-center'
        'ul_class' => 'pagination',
        // Nếu dùng bootstrap 3: '',
        // Nếu dùng bootstrap 4/5: 'page-item'
        'li_class' => '',
        // Nếu dùng bootstrap 3: '',
        // Nếu dùng bootstrap 4/5: 'page-link'
        'a_class' => ''
    ]
];

/**
 * nv_site_theme()
 *
 * @param string $contents
 * @param bool   $full
 * @return string
 */
function nv_site_theme($contents, $full = true)
{
    global $home, $array_mod_title, $nv_Lang, $global_config, $site_mods, $module_name, $module_info, $op_file, $my_head, $my_footer, $client_info, $module_config, $op, $opensearch_link, $custom_preloads;

    // Determine tpl file, check exists tpl file
    $layout_file = ($full) ? 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' : 'simple.tpl';

    if (!theme_file_exists($global_config['module_theme'] . '/layout/' . $layout_file)) {
        nv_info_die($nv_Lang->getGlobal('error_layout_title'), $nv_Lang->getGlobal('error_layout_title'), $nv_Lang->getGlobal('error_layout_content'));
    }

    if (isset($global_config['sitetimestamp'])) {
        $global_config['timestamp'] += $global_config['sitetimestamp'];
    }

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    // // Hook sector 4
    nv_apply_hook('', 'sector4');

    $xtpl = new XTemplate($layout_file, NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);

    $xtpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $xtpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $xtpl->assign('NV_SITE_TITLE', $global_config['site_name'] . NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('admin_page') . NV_TITLEBAR_DEFIS . $module_info['custom_title']);
    $xtpl->assign('SITE_DESCRIPTION', $global_config['site_description']);
    $xtpl->assign('NV_CHECK_PASS_MSTIME', ((int) ($global_config['user_check_pass_time']) - 62) * 1000);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('NV_CURRENTTIME', nv_date($global_config['date_pattern'] . ', ' . $global_config['time_pattern'], NV_CURRENTTIME));
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $xtpl->assign('SITE_FAVICON', $site_favicon);

    // System variables
    $xtpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));

    // Meta-tags
    $metatags = nv_html_meta_tags(false);
    if ($global_config['current_theme_type'] == 'r') {
        $metatags[] = [
            'name' => 'name',
            'value' => 'viewport',
            'content' => 'width=device-width, initial-scale=1'
        ];
    }

    foreach ($metatags as $meta) {
        $xtpl->assign('THEME_META_TAGS', $meta);
        $xtpl->parse('main.metatags');
    }

    // XÁC ĐỊNH BIẾN $custom_preloads - TẢI TRƯỚC TẬP TIN (không bắt buộc)
    // Các tập tin hình ảnh, font chữ được liệt kê trong các file css nguồn
    // theo mặc định sẽ được tải sau khi trình duyệt đã phân tích xong toàn bộ file css.
    // Tải trước các tập tin hình ảnh, font chữ này sẽ khiến việc load trang nhanh hơn.
    // Thuộc tính 'as' và 'href' bắt buộc khai báo, thuộc tính 'type' - không bắt buộc,
    // thuộc tính 'crossorigin' - bắt buộc nếu tập tin đòi hỏi CORS (ví dụ: font chữ).
    // Nếu đường dẫn của tập tin trong file css nguồn là tương đối thì giá trị của thuộc tính 'href'
    // sẽ là đường dẫn đến nó tính từ thư mục gốc của site (tương tự như của file css nguồn).
    $custom_preloads[] = [
        'as' => 'font',
        // File fontawesome-webfont.woff2 được tải từ font-awesome.min.css,
        // nên đường dẫn đến thư mục của font-awesome.min.css thế nào thì của fontawesome-webfont.woff2 như thế
        'href' => ASSETS_STATIC_URL . '/fonts/fontawesome-webfont.woff2',
        'type' => 'font/woff2',
        'crossorigin' => true
    ];
    $custom_preloads[] = [
        'as' => 'font',
        // File NukeVietIcons.woff2 được tải từ style.css,
        // nên đường dẫn đến thư mục của style.css thế nào thì của NukeVietIcons.woff2 như thế
        'href' => NV_STATIC_URL . 'themes/default/fonts/NukeVietIcons.woff2',
        'type' => 'font/woff2',
        'crossorigin' => true
    ];

    //Links
    $html_links = [];
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => ASSETS_STATIC_URL . '/css/font-awesome.min.css'
    ];
    if ($global_config['current_theme_type'] == 'r') {
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/bootstrap.min.css'
        ];
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/style.css'
        ];
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/style.responsive.css'
        ];
    } else {
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/bootstrap.non-responsive.css'
        ];
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/style.css'
        ];
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/style.non-responsive.css'
        ];
    }
    if (defined('NV_IS_ADMIN') and $full) {
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/admin.css'
        ];
    }
    $html_links = array_merge_recursive($html_links, nv_html_links(false));
    $html_links[] = [
        'rel' => 'stylesheet',
        'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/custom.css'
    ];

    // Customs Style
    if (isset($module_config['themes'][$global_config['module_theme']]) and !empty($module_config['themes'][$global_config['module_theme']])) {
        $config_theme = unserialize($module_config['themes'][$global_config['module_theme']]);

        if (isset($config_theme['css_content']) and !empty($config_theme['css_content'])) {
            $customFileName = $global_config['module_theme'] . '.' . NV_LANG_DATA . '.' . $global_config['idsite'];

            if (!file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $customFileName . '.css')) {
                $replace = [
                    '[body]' => 'body',
                    '[a_link]' => 'a, a:link, a:active, a:visited',
                    '[a_link_hover]' => 'a:hover',
                    '[content]' => '.wraper',
                    '[header]' => '#header',
                    '[footer]' => '#footer',
                    '[block]' => '.panel, .well, .nv-block-banners',
                    '[block_heading]' => '.panel-default > .panel-heading'
                ];

                $css_content = str_replace(array_keys($replace), array_values($replace), $config_theme['css_content']);

                file_put_contents(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/css/' . $customFileName . '.css', $css_content);
            }

            $html_links[] = [
                'rel' => 'stylesheet',
                'href' => NV_STATIC_URL . NV_ASSETS_DIR . '/css/' . $customFileName . '.css'
            ];
        }

        if (isset($config_theme['gfont']) and !empty($config_theme['gfont']) and isset($config_theme['gfont']['family']) and !empty($config_theme['gfont']['family'])) {
            $subset = $config_theme['gfont']['subset'] ?? '';
            $gf = new NukeViet\Client\Gfonts([
                'fonts' => [
                    $config_theme['gfont']
                ],
                'subset' => $subset
            ], $client_info);
            $webFontFile = $gf->getUrlCss();
            array_unshift($html_links, [
                'rel' => 'stylesheet',
                'href' => $webFontFile
            ]);
        }

        unset($config_theme, $css_content, $webFontFile, $font, $subset, $gf);
    }

    if (!empty($opensearch_link)) {
        foreach ($opensearch_link as $ol => $nd) {
            if ($ol == 'site') {
                $html_links[] = [
                    'rel' => 'search',
                    'type' => 'application/opensearchdescription+xml',
                    'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=seek&' . NV_OP_VARIABLE . '=opensearch',
                    'title' => $nd[0]
                ];
            } else {
                if (!empty($site_mods[$ol]['is_search'])) {
                    $html_links[] = [
                        'rel' => 'search',
                        'type' => 'application/opensearchdescription+xml',
                        'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=seek&' . NV_OP_VARIABLE . '=opensearch/' . $ol,
                        'title' => $nd[0]
                    ];
                }
            }
        }
    }

    // CSS của nút ẩn/hiện mật khẩu
    if (($global_config['passshow_button'] === 1) or ($global_config['passshow_button'] === 2 and defined('NV_IS_USER')) or ($global_config['passshow_button'] === 3 and defined('NV_IS_ADMIN'))) {
        $html_links[] = [
            'rel' => 'stylesheet',
            'href' => ASSETS_STATIC_URL . '/js/show-pass-btn/bootstrap3-show-pass.css'
        ];
    }

    foreach ($html_links as $links) {
        foreach ($links as $key => $value) {
            $xtpl->assign('LINKS', [
                'key' => $key,
                'value' => $value
            ]);
            if (!empty($value)) {
                $xtpl->parse('main.links.attr.val');
            }
            $xtpl->parse('main.links.attr');
        }
        $xtpl->parse('main.links');
    }

    $html_js = nv_html_site_js(false);
    $html_js[] = [
        'ext' => 1,
        'content' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/js/main.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/js/custom.js'
    ];

    // JS của nút ẩn/hiện mật khẩu
    if (($global_config['passshow_button'] === 1) or ($global_config['passshow_button'] === 2 and defined('NV_IS_USER')) or ($global_config['passshow_button'] === 3 and defined('NV_IS_ADMIN'))) {
        $html_js[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/show-pass-btn/bootstrap3-show-pass.js'
        ];
    }

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
    $xtpl->assign('LOGO_SRC', NV_STATIC_URL . $global_config['site_logo']);

    if (empty($global_config['site_banner'])) {
        $custom_preloads[] = [
            'as' => 'image',
            'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/header.png',
            'type' => 'image/png'
        ];
        $xtpl->assign('BANNER_SRC', NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/images/header.png');
    } else {
        $custom_preloads[] = [
            'as' => 'image',
            'href' => NV_STATIC_URL . $global_config['site_banner']
        ];
        $xtpl->assign('BANNER_SRC', NV_STATIC_URL . $global_config['site_banner']);
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
            if ($global_config['rewrite_op_mod'] != $module_name) {
                array_unshift($array_mod_title, [
                    'catid' => 0,
                    'title' => $module_info['custom_title'],
                    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
                ]);
            }
            if (!empty($array_mod_title)) {
                $border = 1;
                foreach ($array_mod_title as $arr_cat_title_i) {
                    ++$border;
                    $arr_cat_title_i['position'] = $border;
                    $xtpl->assign('BREADCRUMBS', $arr_cat_title_i);
                    $xtpl->parse('main.breadcrumbs.loop');
                }
            }
            $xtpl->parse('main.breadcrumbs');
        } elseif (empty($array_mod_title)) {
            $xtpl->parse('main.currenttime');
        }

        // Statistics image
        $theme_stat_img = '';
        if ($global_config['statistic'] and isset($site_mods['statistics'])) {
            $theme_stat_img .= '<a title="' . $nv_Lang->getGlobal('viewstats') . '" href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=statistics"><img alt="' . $nv_Lang->getGlobal('viewstats') . '" src="' . NV_BASE_SITEURL . 'index.php?second=statimg&amp;p=' . nv_genpass() . "\" width=\"88\" height=\"31\" /></a>\n";
        }

        $xtpl->assign('THEME_STAT_IMG', $theme_stat_img);

        // Change theme types
        if (sizeof($global_config['array_theme_type']) > 1) {
            $mobile_theme = empty($module_info['mobile']) ? $global_config['mobile_theme'] : (($module_info['mobile'] != ':pcmod' and $module_info['mobile'] != ':pcsite') ? $module_info['mobile'] : '');
            if (empty($mobile_theme) or empty($global_config['switch_mobi_des'])) {
                $array_theme_type = array_diff($global_config['array_theme_type'], [
                    'm'
                ]);
            } else {
                $array_theme_type = $global_config['array_theme_type'];
            }
            $icons = [
                'r' => 'random',
                'd' => 'desktop',
                'm' => 'mobile'
            ];
            $current_theme_type = (isset($global_config['current_theme_type']) and !empty($global_config['current_theme_type']) and in_array($global_config['current_theme_type'], array_keys($icons), true)) ? $global_config['current_theme_type'] : 'd';
            foreach ($array_theme_type as $theme_type) {
                $xtpl->assign('STHEME_TYPE', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;nv' . NV_LANG_DATA . 'themever=' . $theme_type . '&amp;nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
                $xtpl->assign('STHEME_TITLE', $nv_Lang->getGlobal('theme_type_' . $theme_type));
                $xtpl->assign('STHEME_INFO', $nv_Lang->getGlobal('theme_type_chose', $nv_Lang->getGlobal('theme_type_' . $theme_type)));
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

    if (defined('SSO_REGISTER_DOMAIN')) {
        $xtpl->assign('SSO_REGISTER_ORIGIN', SSO_REGISTER_DOMAIN);
        $xtpl->parse('main.crossdomain_listener');
    }

    if ($global_config['cookie_notice_popup'] and !isset($_COOKIE[$global_config['cookie_prefix'] . '_cn'])) {
        $xtpl->assign('COOKIE_NOTICE', $nv_Lang->getGlobal('cookie_notice', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteterms&amp;' . NV_OP_VARIABLE . '=privacy' . $global_config['rewrite_exturl']));
        $xtpl->parse('main.cookie_notice');
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
 * nv_error_theme()
 *
 * @param string $title
 * @param string $content
 * @param int    $code
 */
function nv_error_theme($title, $content, $code)
{
    nv_info_die($title, $title, $content, $code);
}
