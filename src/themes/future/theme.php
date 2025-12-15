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

use NukeViet\Template\Config;

$theme_config = [
    'pagination' => [
        // Nếu dùng bootstrap 3: 'pagination'
        // Nếu dùng bootstrap 4/5: 'pagination justify-content-center'
        'ul_class' => 'pagination',
        // Nếu dùng bootstrap 3: '',
        // Nếu dùng bootstrap 4/5: 'page-item'
        'li_class' => 'page-item',
        // Nếu dùng bootstrap 3: '',
        // Nếu dùng bootstrap 4/5: 'page-link'
        'a_class' => 'page-link'
    ]
];

/**
 * Hàm xử lý chính của giao diện
 *
 * @param string $contents
 * @param bool   $full
 * @return string
 */
function nv_site_theme($contents, $full = true)
{
    global $home, $array_mod_title, $nv_Lang, $global_config, $site_mods, $module_name, $module_info, $op_file, $my_head, $my_footer, $client_info, $module_config, $op, $opensearch_link, $custom_preloads;

    // Xác định tệp tpl
    $layout_file = ($full) ? 'layout.' . $module_info['layout_funcs'][$op_file] . '.tpl' : 'simple.tpl';

    if (!theme_file_exists($global_config['module_theme'] . '/layout/' . $layout_file)) {
        nv_info_die($nv_Lang->getGlobal('error_layout_title'), $nv_Lang->getGlobal('error_layout_title'), $nv_Lang->getGlobal('error_layout_content'));
    }

    if (isset($global_config['sitetimestamp'])) {
        $global_config['timestamp'] += $global_config['sitetimestamp'];
    }

    // Đọc ngôn ngữ của giao diện
    $nv_Lang->loadFile(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/' . NV_LANG_INTERFACE . '.php');

    // Hook sector 4
    nv_apply_hook('', 'sector4');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('TCONFIG', Config::class);
    $tpl->assign('THEME_PAGE_TITLE', nv_html_page_title(false));
    $tpl->assign('CLIENT_INFO', $client_info);
    $tpl->assign('OUTDATED_BROWSER', nv_outdated_browser());
    $tpl->assign('SITE_MODS', $site_mods);

    $tpl->assign('HOME', $home);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('MODULE_CONTENT', $contents);
    $tpl->assign('H1_EXISTS', preg_match("/<h1[^\>]*\>/i", $contents));

    // Meta-tags
    $metatags = nv_html_meta_tags(false);
    if ($global_config['current_theme_type'] == 'r') {
        $metatags[] = [
            'name' => 'name',
            'value' => 'viewport',
            'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0'
        ];
    }
    $tpl->assign('METATAGS', $metatags);

    // Icon của site
    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }
    $tpl->assign('SITE_FAVICON', $site_favicon);

    // XÁC ĐỊNH BIẾN $custom_preloads - TẢI TRƯỚC TẬP TIN (không bắt buộc)
    // Các tập tin hình ảnh, font chữ được liệt kê trong các file css nguồn
    // theo mặc định sẽ được tải sau khi trình duyệt đã phân tích xong toàn bộ file css.
    // Tải trước các tập tin hình ảnh, font chữ này sẽ khiến việc load trang nhanh hơn.
    // Thuộc tính 'as' và 'href' bắt buộc khai báo, thuộc tính 'type' - không bắt buộc,
    // thuộc tính 'crossorigin' - bắt buộc nếu tập tin đòi hỏi CORS (ví dụ: font chữ).
    // Nếu đường dẫn của tập tin trong file css nguồn là tương đối thì giá trị của thuộc tính 'href'
    // sẽ là đường dẫn đến nó tính từ thư mục gốc của site (tương tự như của file css nguồn).
    /*
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
    */

    /*
     * Links
     */
    $html_links = [];
    $styles = Config::isRtl()
    ? array_merge(
        $global_config['current_theme_type'] != 'r' ? ['style.d.rtl.css'] : [],
        ['style.r.rtl.css', 'nv.style.rtl.css', 'style.rtl.css']
    )
    : [];
    if ($global_config['current_theme_type'] != 'r') {
        $styles[] = 'style.d.css';
    }
    $styles = array_merge($styles, ['style.r.css', 'nv.style.css', 'style.css']);
    foreach ($styles as $style) {
        if (theme_file_exists($global_config['module_theme'] . '/css/' . $style)) {
            $html_links[] = [
                'rel' => 'stylesheet',
                'href' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/css/' . $style
            ];
            break;
        }
    }
    $html_links = array_merge_recursive($html_links, nv_html_links(false));

    // Tùy chỉnh giao diện
    $color_mode = 'light';
    if (isset($module_config['themes'][$global_config['module_theme']]) and !empty($module_config['themes'][$global_config['module_theme']])) {
        $config_theme = json_decode($module_config['themes'][$global_config['module_theme']], true);
        !is_array($config_theme) && $config_theme = [];

        // Chế độ giao diện
        if (isset($config_theme['color'])) {
            $color_mode = $config_theme['color']['mode'] ?? $color_mode;
            if ($color_mode == 'light') {
                $color_mode = $config_theme['color']['light'] ?? $color_mode;
            }
        }

        // CSS biến hoặc toàn cục
        if (!empty($config_theme['variables']) or !empty($config_theme['css']) or (isset($config_theme['gfont']) and !empty($config_theme['gfont']['family']))) {
            $html_links[] = [
                'rel' => 'stylesheet',
                'href' => NV_STATIC_URL . NV_ASSETS_DIR . '/css/' . $global_config['module_theme'] . '.' . NV_LANG_DATA . '.' . $global_config['idsite'] . '.css'
            ];
        }

        // Google Fonts version 2
        if (isset($config_theme['gfont']) and !empty($config_theme['gfont']['family'])) {
            $gf = new NukeViet\Client\Gfonts2($global_config['module_theme'], $config_theme['gfont']);
            array_unshift($html_links, [
                'rel' => 'stylesheet',
                'href' => $gf->getLink()
            ]);
        }

        unset($config_theme, $gf);
    }
    $tpl->assign('COLOR_MODE', $color_mode);

    if (!empty($opensearch_link)) {
        foreach ($opensearch_link as $ol => $nd) {
            if ($ol == '_site') {
                if (!empty($nd['active'])) {
                    $html_links[] = [
                        'rel' => 'search',
                        'type' => 'application/opensearchdescription+xml',
                        'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=seek&' . NV_OP_VARIABLE . '=opensearch',
                        'title' => $nd['shortname']
                    ];
                }
                continue;
            }
            if (isset($site_mods[$ol]) and !empty($site_mods[$ol]['is_search']) and !empty($nd['active'])) {
                $html_links[] = [
                    'rel' => 'search',
                    'type' => 'application/opensearchdescription+xml',
                    'href' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=seek&' . NV_OP_VARIABLE . '=opensearch/' . $ol,
                    'title' => $nd['shortname']
                ];
            }
        }
    }
    $tpl->assign('HTML_LINKS', $html_links);

    /*
     * JS
     */
    $html_js = nv_html_site_js(false);
    $html_js[] = [
        'ext' => 1,
        'content' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/js/nv.main.js'
    ];
    $html_js[] = [
        'ext' => 1,
        'content' => NV_STATIC_URL . 'themes/' . $global_config['module_theme'] . '/js/nv.custom.js'
    ];

    // JS của nút ẩn/hiện mật khẩu
    if (($global_config['passshow_button'] === 1) or ($global_config['passshow_button'] === 2 and defined('NV_IS_USER')) or ($global_config['passshow_button'] === 3 and defined('NV_IS_ADMIN'))) {
        $html_js[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/show-pass-btn/bootstrap5-show-pass.js'
        ];
    }
    $tpl->assign('HTML_JS', $html_js);

    // Thông báo thu thập cookie lần đầu
    $tpl->assign('COOKIE_NOTICE', ($global_config['cookie_notice_popup'] and !isset($_COOKIE[$global_config['cookie_prefix'] . '_cn'])));

    $sitecontent = $tpl->fetch($layout_file);

    // Giao diện đầy đủ thì có thêm block và thông báo lỗi
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




    // FIXME
    $xtpl = new XTemplate($layout_file, NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/layout');
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);

    $xtpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $xtpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $xtpl->assign('NV_SITE_TITLE', $global_config['site_name'] . NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('admin_page') . NV_TITLEBAR_DEFIS . $module_info['custom_title']);
    $xtpl->assign('SITE_DESCRIPTION', $global_config['site_description']);

    $xtpl->assign('NV_CURRENTTIME', nv_datetime_format(NV_CURRENTTIME, 0, 0));
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);

    // System variables


    // Module contents


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

    // Only full theme
    if ($full) {
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


    }
}

/**
 * Giao diện thông báo lỗi ví dụ lỗi 404, 403, 500
 *
 * @param string $title
 * @param string $content
 * @param int    $code
 */
function nv_error_theme($title, $content, $code)
{
    nv_info_die($title, $title, $content, $code);
}

/**
 * Giao diện xử lý khung các block
 *
 * @param string $content
 * @param array $row
 * @param string $template
 * @return string
 */
function nv_block_theme($content, $row, $template)
{
    global $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $template . '/layout');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('BLOCK', $row);
    $tpl->assign('CONTENT', $content);
    $tpl->assign('TEMPLATE', $template);

    return $tpl->fetch('block.' . $row['template'] . '.tpl');
}
