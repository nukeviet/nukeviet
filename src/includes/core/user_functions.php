<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Client\Browser;

/**
 * is_current_url()
 * Kiểm tra URL có phải là URL của trạng hiện tại hay không
 *
 * @param string $url
 * @param int    $cmptype
 * @return bool
 */
function is_current_url($url, $cmptype = 0)
{
    global $home, $client_info, $global_config;

    if (strcasecmp($client_info['selfurl'], $url) === 0) {
        return true;
    }

    $url = nv_url_rewrite($url, true);

    if ($home and (strcasecmp($url, nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA)) === 0 or strcasecmp($url, NV_BASE_SITEURL . 'index.php') === 0 or strcasecmp($url, NV_BASE_SITEURL) === 0)) {
        return true;
    }

    if (strcasecmp($url, NV_BASE_SITEURL) === 0) {
        return false;
    }

    $current_url = NV_BASE_SITEURL . str_replace($global_config['site_url'] . '/', '', $client_info['selfurl']);

    return (bool) (
        // Nếu URL hiện tại có chứa URL so sánh
        ($cmptype == 2 and str_contains($current_url, $url)) or
        // Nếu URL hiện tại bắt đầu chứa URL so sánh
        ($cmptype == 1 and str_starts_with($current_url, $url)) or
        // Nếu URL hiện tại khớp hoàn toàn URL so sánh
        (strcasecmp($url, $current_url) === 0)
    );
}

/**
 * Đưa các block vào giao diện hiển thị
 *
 * @param string $sitecontent
 * @return string
 */
function nv_blocks_content($sitecontent)
{
    global $db, $nv_Cache, $module_info, $module_name, $op, $global_config, $nv_Lang, $sys_mods, $client_info, $theme_config_positions;

    $_posAllowed = [];

    foreach ($theme_config_positions as $_pos) {
        $_posAllowed[] = preg_replace('/[^a-zA-Z0-9\_]+/', '', (string) $_pos['tag']);
    }

    if (empty($_posAllowed)) {
        return $sitecontent;
    }

    // Tim trong noi dung trang cac doan ma phu hop voi cac nhom block tren
    $_posAllowed = implode('|', array_map('nv_preg_quote', $_posAllowed));
    preg_match_all('/\[(' . $_posAllowed . ')(\d+)?\]()/', $sitecontent, $_posReal);

    if (empty($_posReal[0])) {
        return $sitecontent;
    }

    $_posReal = array_combine($_posReal[0], $_posReal[3]);

    $cache_file = $global_config['module_theme'] . '_' . $module_name . '_' . NV_CACHE_PREFIX . '.cache';
    $blocks = [];

    $cacheValid = false;
    if (($cache = $nv_Cache->getItem('themes', $cache_file)) !== false) {
        $mod_blocklist = json_decode($cache, true);
        $cacheValid = (json_last_error() === JSON_ERROR_NONE);
    }
    if (!$cacheValid) {
        $mod_blocklist = [];
        $in = [];
        $list = $sys_mods[$module_name]['funcs'];
        foreach ($list as $row) {
            if ($row['show_func']) {
                $in[] = $row['func_id'];
            }
        }
        $in = implode(',', $in);
        $_result = $db->query('SELECT t1.*, t2.func_id FROM ' . NV_BLOCKS_TABLE . '_groups t1
             INNER JOIN ' . NV_BLOCKS_TABLE . '_weight t2
             ON t1.bid = t2.bid
             WHERE t2.func_id IN (' . $in . ")
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
            $block_config['heading'] = $_row['heading'];

            !isset($mod_blocklist[$_row['func_id']]) && $mod_blocklist[$_row['func_id']] = [];
            $mod_blocklist[$_row['func_id']][] = [
                'bid' => $_row['bid'],
                'position' => $_row['position'],
                'module' => $_row['module'],
                'title' => $_row['title'],
                'link' => $_row['link'],
                'blockTitle' => (!empty($_row['title']) and !empty($_row['link'])) ? '<a href="' . $_row['link'] . '">' . $_row['title'] . '</a>' : $_row['title'],
                'file_name' => $_row['file_name'],
                'template' => $_row['template'],
                'heading' => $_row['heading'],
                'dtime_type' => $_row['dtime_type'],
                'dtime_details' => json_decode($_row['dtime_details'], true),
                'show_device' => !empty($_row['active']) ? array_map('intval', explode(',', $_row['active'])) : [],
                'act' => $_row['act'],
                'groups_view' => $_row['groups_view'],
                'all_func' => $_row['all_func'],
                'bot_visible' => $_row['bot_visible'],
                'block_config' => $block_config
            ];
        }
        $_result->closeCursor();
        $nv_Cache->setItem('themes', $cache_file, json_encode($mod_blocklist, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    if (isset($mod_blocklist[$module_info['funcs'][$op]['func_id']])) {
        $blocks = $mod_blocklist[$module_info['funcs'][$op]['func_id']];
    }

    if (!empty($blocks)) {
        global $blockID;

        //$unact = [];
        $array_position = array_keys($_posReal);
        foreach ($blocks as $_key => $_row) {
            if (!defined('NV_IS_DRAG_BLOCK') and !$_row['act']) {
                continue;
            }

            // Kiểm tra thời gian hiển thị
            // 'regular': thường xuyên, 'specific': theo thời gian cụ thể,
            // 'daily': hàng ngày, 'weekly': hàng tuần,
            // 'monthly': hàng tháng, 'yearly': hàng năm
            $is_show = false;
            if ($_row['dtime_type'] == 'regular') {
                $is_show = true;
            } elseif ($_row['dtime_type'] == 'specific' and !empty($_row['dtime_details'])) {
                foreach ($_row['dtime_details'] as $option) {
                    if (!empty($option['end_date'])) {
                        if (empty($option['start_date'])) {
                            $start_time = NV_CURRENTTIME;
                        } else {
                            $start_date = array_map('intval', explode('/', $option['start_date']));
                            $start_time = mktime($option['start_h'], $option['start_i'], 0, $start_date[1], $start_date[0], $start_date[2]);
                        }
                        $end_date = array_map('intval', explode('/', $option['end_date']));
                        $end_time = mktime($option['end_h'], $option['end_i'], 0, $end_date[1], $end_date[0], $end_date[2]);
                        if (NV_CURRENTTIME >= $start_time and NV_CURRENTTIME <= $end_time) {
                            $is_show = true;
                            break;
                        }
                    }
                }
            } elseif ($_row['dtime_type'] == 'daily' and !empty($_row['dtime_details'])) {
                foreach ($_row['dtime_details'] as $option) {
                    if (isset($option['start_h'], $option['start_i'], $option['end_h'], $option['end_i'])) {
                        $start_time = mktime($option['start_h'], $option['start_i'], 0);
                        $end_time = mktime($option['end_h'], $option['end_i'], 0);
                        if (NV_CURRENTTIME >= $start_time and NV_CURRENTTIME <= $end_time) {
                            $is_show = true;
                            break;
                        }
                    }
                }
            } elseif ($_row['dtime_type'] == 'weekly' and !empty($_row['dtime_details'])) {
                foreach ($_row['dtime_details'] as $option) {
                    if (isset($option['day_of_week'], $option['start_h'], $option['start_i'], $option['end_h'], $option['end_i'])) {
                        if ((int) date('N', NV_CURRENTTIME) == (int) $option['day_of_week']) {
                            $start_time = mktime($option['start_h'], $option['start_i'], 0);
                            $end_time = mktime($option['end_h'], $option['end_i'], 0);
                            if (NV_CURRENTTIME >= $start_time and NV_CURRENTTIME <= $end_time) {
                                $is_show = true;
                                break;
                            }
                        }
                    }
                }
            } elseif ($_row['dtime_type'] == 'monthly' and !empty($_row['dtime_details'])) {
                foreach ($_row['dtime_details'] as $option) {
                    if (isset($option['day'], $option['start_h'], $option['start_i'], $option['end_h'], $option['end_i'])) {
                        if ((int) date('j', NV_CURRENTTIME) == (int) $option['day']) {
                            $start_time = mktime($option['start_h'], $option['start_i'], 0);
                            $end_time = mktime($option['end_h'], $option['end_i'], 0);
                            if (NV_CURRENTTIME >= $start_time and NV_CURRENTTIME <= $end_time) {
                                $is_show = true;
                                break;
                            }
                        }
                    }
                }
            } elseif ($_row['dtime_type'] == 'yearly' and !empty($_row['dtime_details'])) {
                foreach ($_row['dtime_details'] as $option) {
                    if (isset($option['month'], $option['day'], $option['start_h'], $option['start_i'], $option['end_h'], $option['end_i'])) {
                        if (date('j.n', NV_CURRENTTIME) == $option['day'] . '.' . $option['month']) {
                            $start_time = mktime($option['start_h'], $option['start_i'], 0);
                            $end_time = mktime($option['end_h'], $option['end_i'], 0);
                            if (NV_CURRENTTIME >= $start_time and NV_CURRENTTIME <= $end_time) {
                                $is_show = true;
                                break;
                            }
                        }
                    }
                }
            }
            if (!$is_show and !defined('NV_IS_DRAG_BLOCK')) {
                continue;
            }

            // Kiem hien thi tren cac thiet bi
            $_active = false;
            if (in_array(1, $_row['show_device'], true)) {
                $_active = true;
            } else {
                if ($client_info['is_mobile'] and in_array(2, $_row['show_device'], true)) {
                    $_active = true;
                } elseif ($client_info['is_tablet'] and in_array(3, $_row['show_device'], true)) {
                    $_active = true;
                } elseif (!$client_info['is_mobile'] and !$client_info['is_tablet'] and in_array(4, $_row['show_device'], true)) {
                    $_active = true;
                }
            }

            // Kiem tra hien thi voi bot
            if ($client_info['is_bot'] and empty($_row['bot_visible'])) {
                $_active = false;
            }

            // Kiem tra quyen xem block
            if ($_active and in_array($_row['position'], $array_position, true) and nv_user_in_groups($_row['groups_view'])) {
                $block_config = $_row['block_config'];
                $blockTitle = $_row['blockTitle'];
                $content = '';
                $blockID = 'nv' . $_key;

                if ($_row['module'] == 'theme') {
                    if (theme_file_exists($global_config['module_theme'] . '/blocks/' . $_row['file_name'])) {
                        $block_config['real_theme'] = $global_config['module_theme'];
                        $block_config['real_path'] = NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks';
                        include $block_config['real_path'] . '/' . $_row['file_name'];
                    }
                } elseif (isset($sys_mods[$_row['module']]['module_file']) and !empty($sys_mods[$_row['module']]['module_file'])) {
                    if (theme_file_exists($global_config['module_theme'] . '/modules/' . $sys_mods[$_row['module']]['module_file'] . '/' . $_row['file_name'])) {
                        $block_config['real_theme'] = $global_config['module_theme'];
                        $block_config['real_path'] = NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $sys_mods[$_row['module']]['module_file'];
                        include $block_config['real_path'] . '/' . $_row['file_name'];
                    } elseif (module_file_exists($sys_mods[$_row['module']]['module_file'] . '/blocks/' . $_row['file_name'])) {
                        $block_config['real_theme'] = $sys_mods[$_row['module']]['module_file'];
                        $block_config['real_path'] = NV_ROOTDIR . '/modules/' . $sys_mods[$_row['module']]['module_file'] . '/blocks';
                        include $block_config['real_path'] . '/' . $_row['file_name'];
                    }
                }
                unset($block_config);

                if (!empty($content) or defined('NV_IS_DRAG_BLOCK')) {
                    $_row['template'] = empty($_row['template']) ? 'default' : $_row['template'];
                    $_template = get_tpl_dir([(!empty($module_info['theme']) ? $module_info['theme'] : ''), (!empty($global_config['module_theme']) ? $global_config['module_theme'] : ''), $global_config['site_theme'], NV_DEFAULT_SITE_THEME], '', '/layout/block.' . $_row['template'] . '.tpl');
                    if (!empty($_template) and function_exists('nv_block_theme')) {
                        $content = nv_block_theme($content, $_row, $_template);
                    } else {
                        $content = $_row['blockTitle'] . '<br />' . $content . '<br />';
                    }

                    if (defined('NV_IS_DRAG_BLOCK')) {
                        $act_class = $_row['act'] ? '' : ' act0';
                        $act_title = $_row['act'] ? $nv_Lang->getGlobal('act_block') : $nv_Lang->getGlobal('deact_block');
                        $act_icon = $_row['act'] ? 'ic' : 'ic act0';
                        $checkss = md5(NV_CHECK_SESSION . '_' . $_row['bid']);
                        $content = '<div class="portlet" id="bl_' . ($_row['bid']) . '">
                             <div class="tool">
                                 <a href="#" class="block_content" name="' . $_row['bid'] . '" alt="' . $nv_Lang->getGlobal('edit_block') . '" title="' . $nv_Lang->getGlobal('edit_block') . '"><em class="ic"></em></a>
                                 <a href="#" class="delblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $nv_Lang->getGlobal('delete_block') . '" title="' . $nv_Lang->getGlobal('delete_block') . '"><em class="ic"></em></a>
                                 <a href="#" class="actblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $act_title . '" title="' . $act_title . '" data-act="' . $nv_Lang->getGlobal('act_block') . '" data-deact="' . $nv_Lang->getGlobal('deact_block') . '"><em class="' . $act_icon . '" data-act="ic" data-deact="ic act0"></em></a>
                                 <a href="#" class="outgroupblock" name="' . $_row['bid'] . '"  data-checkss="' . $checkss . '" alt="' . $nv_Lang->getGlobal('outgroup_block') . '" title="' . $nv_Lang->getGlobal('outgroup_block') . '"><em class="ic"></em></a>
                             </div>
                             <div class="blockct' . $act_class . '">' . $content . '</div>
                             </div>';
                    }

                    $_posReal[$_row['position']] .= $content;
                }
            }
        }
        /*if (!empty($unact)) {
            $db->query('UPDATE ' . NV_BLOCKS_TABLE . '_groups SET act=0 WHERE bid IN (' . implode(',', $unact) . ')');
            $nv_Cache->delMod('themes', NV_LANG_DATA);
        }*/
    }

    if (defined('NV_IS_DRAG_BLOCK')) {
        $array_keys = array_keys($_posReal);
        foreach ($array_keys as $__pos) {
            $__pos_name = str_replace([
                '[',
                ']'
            ], [
                '',
                ''
            ], $__pos);
            $_posReal[$__pos] = '<div class="column" data-id="' . $__pos_name . '" data-checkss="' . md5(NV_CHECK_SESSION . '_' . $__pos_name) . '">' . $_posReal[$__pos];
            $_posReal[$__pos] .= '<a href="#" class="add block_content" id="' . $__pos . '" title="' . $nv_Lang->getGlobal('add_block') . ' ' . $__pos_name . '" alt="' . $nv_Lang->getGlobal('add_block') . '"><em class="ic"></em></a>';
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
 * @return array|string
 */
function nv_html_meta_tags($html = true)
{
    global $global_config, $nv_Lang, $key_words, $description, $module_info, $home, $op, $page_title, $page_url, $meta_property, $nv_BotManager;

    $return = [];

    $current_page_url = '';
    if (!empty($page_url)) {
        $current_page_url = urlRewriteWithDomain($page_url, NV_MAIN_DOMAIN);
    } elseif ($home) {
        $current_page_url = urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MAIN_DOMAIN);
    }

    if ($home) {
        $site_description = $global_config['site_description'];
    } elseif (!empty($description)) {
        $site_description = $description;
    } elseif (!empty($module_info['funcs'][$op]['description'])) {
        $site_description = $module_info['funcs'][$op]['description'];
    } elseif (!empty($module_info['description'])) {
        $site_description = $module_info['description'];
    } else {
        $ds = [];
        if (!empty($page_title)) {
            $ds[] = $page_title;
        }
        if ($op != 'main') {
            $ds[] = $module_info['funcs'][$op]['func_custom_name'];
        }
        $ds[] = $module_info['custom_title'];
        !empty($current_page_url) && $ds[] = $current_page_url;
        $site_description = implode(' - ', $ds);
    }
    if ($site_description == 'no') {
        $site_description = '';
    }

    if (!empty($site_description)) {
        $site_description = preg_replace([
            '/<[^>]*>/',
            '/[\r\n\t]+/'
        ], ' ', $site_description);
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
        $key_words = preg_replace("/(\s*\,\s*)+/", ', ', $key_words);
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
        $patters['/\{BASE\_SITEURL\}/'] = NV_BASE_SITEURL;
        $patters['/\{UPLOADS\_DIR\}/'] = NV_UPLOADS_DIR;
        $patters['/\{ASSETS\_DIR\}/'] = NV_ASSETS_DIR;
        $patters['/\{CONTENT\-LANGUAGE\}/'] = $nv_Lang->getGlobal('Content_Language');
        $patters['/\{LANGUAGE\}/'] = $nv_Lang->getGlobal('LanguageName');
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
                if (($meta['group'] == 'http-equiv' or $meta['group'] == 'name' or $meta['group'] == 'property') and preg_match('/^[a-zA-Z0-9\-\_\.\:]+$/', $meta['value']) and (!empty($meta['content']) and preg_match("/^([^\'\"]+)$/", (string) $meta['content']))) {
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

    /*
     * Đọc kỹ giấy phép trước khi thay đổi giá trị này
     *
     * @link https://github.com/nukeviet/nukeviet/blob/nukeviet5.0/LICENSE
     */
    $return[] = [
        'name' => 'name',
        'value' => 'generator',
        'content' => 'NukeViet v5.0'
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
        if (empty($meta_property['og:url']) and !empty($current_page_url)) {
            $meta_property['og:url'] = $current_page_url;
        }
        if (empty($meta_property['og:image']) and !empty($global_config['ogp_image'])) {
            $imagesize = @getimagesize(NV_ROOTDIR . '/' . $global_config['ogp_image']);
            if (!empty($imagesize[0])) {
                $meta_property['og:image'] = NV_MAIN_DOMAIN . NV_BASE_SITEURL . $global_config['ogp_image'];
                $meta_property['og:image:url'] = NV_MAIN_DOMAIN . NV_BASE_SITEURL . $global_config['ogp_image'];
                $meta_property['og:image:type'] = $imagesize['mime'];
                $meta_property['og:image:width'] = $imagesize[0];
                $meta_property['og:image:height'] = $imagesize[1];
                $meta_property['og:image:alt'] = $global_config['site_name'];
            }
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
        $res .= '<meta ' . $link['name'] . '="' . $link['value'] . '" content="' . $link['content'] . '" />' . PHP_EOL;
    }

    return $res;
}

/**
 * nv_html_links()
 *
 * @param bool $html
 * @param bool $moduleCss
 * @return array|string
 */
function nv_html_links($html = true, $moduleCss = true)
{
    global $canonicalUrl, $prevPage, $nextPage, $nv_html_links, $global_config, $nv_Lang;

    $return = [];
    if (!empty($canonicalUrl)) {
        if (substr($canonicalUrl, 0, 4) != 'http') {
            if (substr($canonicalUrl, 0, 1) != '/') {
                $canonicalUrl = NV_BASE_SITEURL . $canonicalUrl;
            }
            $canonicalUrl = NV_MAIN_DOMAIN . $canonicalUrl;
        }
        $return[] = [
            'rel' => 'canonical',
            'href' => $canonicalUrl
        ];
    }
    if (!empty($prevPage)) {
        if (substr($prevPage, 0, 4) != 'http') {
            if (substr($prevPage, 0, 1) != '/') {
                $prevPage = NV_BASE_SITEURL . $prevPage;
            }
            $prevPage = NV_MAIN_DOMAIN . $prevPage;
        }
        $return[] = [
            'rel' => 'prev',
            'href' => $prevPage
        ];
    }
    if (!empty($nextPage)) {
        if (substr($nextPage, 0, 4) != 'http') {
            if (substr($nextPage, 0, 1) != '/') {
                $nextPage = NV_BASE_SITEURL . $nextPage;
            }
            $nextPage = NV_MAIN_DOMAIN . $nextPage;
        }
        $return[] = [
            'rel' => 'next',
            'href' => $nextPage
        ];
    }

    $nv_html_site_rss = nv_html_site_rss(false);
    if ($nv_html_site_rss) {
        $return = array_merge_recursive($return, $nv_html_site_rss);
    }

    if ($moduleCss) {
        $nv_html_css = nv_html_css(false);
        if ($nv_html_css) {
            $return = array_merge_recursive($return, $nv_html_css);
        }
    }

    // Thêm các thẻ link từ cấu hình Link-Tags trong admin
    if ($global_config['idsite'] and file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_linktags.xml')) {
        $file_linktags = NV_ROOTDIR . '/' . NV_DATADIR . '/site_' . $global_config['idsite'] . '_linktags.xml';
    } else {
        $file_linktags = NV_ROOTDIR . '/' . NV_DATADIR . '/linktags.xml';
    }
    if (file_exists($file_linktags)) {
        $lt = file_get_contents($file_linktags);
        $patters = [];
        $patters['/\{BASE\_SITEURL\}/'] = NV_BASE_SITEURL;
        $patters['/\{UPLOADS\_DIR\}/'] = NV_UPLOADS_DIR;
        $patters['/\{ASSETS\_DIR\}/'] = NV_ASSETS_DIR;
        $patters['/\{CONTENT\-LANGUAGE\}/'] = $nv_Lang->getGlobal('Content_Language');
        $patters['/\{LANGUAGE\}/'] = $nv_Lang->getGlobal('LanguageName');
        $patters['/\{SITE\_NAME\}/'] = $global_config['site_name'];
        $patters['/\{SITE\_EMAIL\}/'] = $global_config['site_email'];
        $lt = preg_replace(array_keys($patters), array_values($patters), $lt);
        $lt = preg_replace('/\{(.*)\}/', '', $lt);
        $lt = simplexml_load_string($lt);
        $lt = nv_object2array($lt);

        if (!empty($lt['link_item'])) {
            $linktags = [];
            if (isset($lt['link_item'][0])) {
                $linktags = $lt['link_item'];
            } else {
                $linktags[] = $lt['link_item'];
            }
            foreach ($linktags as $link) {
                if (!empty($link['rel'])) {
                    $return[] = $link;
                }
            }
        }
    }

    if (!empty($nv_html_links)) {
        $return = array_merge_recursive($return, $nv_html_links);
    }

    if (!$html) {
        return $return;
    }

    $res = '';
    foreach ($return as $link) {
        $res .= '<link ';
        foreach ($link as $key => $val) {
            $res .= $key . '="' . $val . '" ';
        }
        $res .= '/>' . PHP_EOL;
    }

    return $res;
}

/**
 * nv_html_page_title()
 *
 * @param bool $html
 * @return string
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

        // $module_info['funcs'][$op]['func_site_title'] đã được định nghĩa ở đây:
        // https://github.com/nukeviet/nukeviet/blob/eb042e37437b793202f5e6d7b5c3e6f89e536b90/includes/mainfile.php#L449
        if (empty($page_title) and !preg_match('/(funcname|modulename|sitename)/i', $global_config['pageTitleMode'])) {
            $_title = $module_info['funcs'][$op]['func_site_title'] . NV_TITLEBAR_DEFIS . $module_info['custom_title'];
        } else {
            $_title = preg_replace([
                '/pagetitle/i',
                '/funcname/i',
                '/modulename/i',
                '/sitename/i'
            ], [
                $page_title,
                $module_info['funcs'][$op]['func_site_title'],
                $module_info['custom_title'],
                $global_config['site_name']
            ], $global_config['pageTitleMode']);
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
 * @return array|string
 */
function nv_html_css($html = true)
{
    global $module_info, $module_file;

    if (theme_file_exists($module_info['template'] . '/css/' . $module_info['module_theme'] . '.css')) {
        if ($html) {
            return '<link rel="StyleSheet" href="' . NV_STATIC_URL . 'themes/' . $module_info['template'] . '/css/' . $module_info['module_theme'] . '.css" type="text/css" />' . PHP_EOL;
        }

        return [
            [
                'rel' => 'StyleSheet',
                'href' => NV_STATIC_URL . 'themes/' . $module_info['template'] . '/css/' . $module_info['module_theme'] . '.css'
            ]
        ];
    }
    if (theme_file_exists($module_info['template'] . '/css/' . $module_file . '.css')) {
        if ($html) {
            return '<link rel="StyleSheet" href="' . NV_STATIC_URL . 'themes/' . $module_info['template'] . '/css/' . $module_file . '.css" type="text/css" />' . PHP_EOL;
        }

        return [
            [
                'rel' => 'StyleSheet',
                'href' => NV_STATIC_URL . 'themes/' . $module_info['template'] . '/css/' . $module_file . '.css'
            ]
        ];
    }

    return $html ? '' : [];
}

/**
 * nv_html_site_rss()
 *
 * @param bool $html
 * @return array|string
 */
function nv_html_site_rss($html = true)
{
    global $rss;

    $return = $html ? '' : [];
    if (!empty($rss)) {
        foreach ($rss as $rss_item) {
            $href = $rss_item['src'] . '" title="' . strip_tags($rss_item['title']);
            if ($html) {
                $return .= '<link rel="alternate" href="' . $href . '" type="application/rss+xml" />' . PHP_EOL;
            } else {
                $return[] = [
                    'rel' => 'alternate',
                    'href' => $href,
                    'type' => 'application/rss+xml'
                ];
            }
        }
    }

    return $return;
}

/**
 * nv_html_site_js()
 *
 * @param bool  $html        Xuất ra dạng string (html) hay để nguyên dạng array, Mặc định true
 * @param array $other_js    Thêm js vào ngay sau global.js, Mặc định rỗng
 * @param bool  $language_js Có kết nối với file ngôn ngữ JS hay không
 * @param bool  $global_js   Có kết nối với file global.js hay không
 * @param bool  $default_js  Có kết nối với file JS của theme Default hay không khi thiếu file tương ứng ở theme đang sử dụng
 * @param bool  $module_js   Có kết nối với file JS của module hay không
 * @return array|string
 */
function nv_html_site_js($html = true, $other_js = [], $language_js = true, $global_js = true, $default_js = true, $module_js = true)
{
    global $global_config, $module_info, $module_name, $module_file, $nv_Lang, $op, $client_info, $user_info, $browser, $nv_schemas;

    $safemode = defined('NV_IS_USER') ? $user_info['safemode'] : 0;
    $jsDef = 'var nv_base_siteurl="' . NV_BASE_SITEURL . '",nv_assets_dir="' . NV_ASSETS_DIR . '",nv_lang_data="' . NV_LANG_INTERFACE . '",nv_lang_interface="' . NV_LANG_INTERFACE . '",nv_name_variable="' . NV_NAME_VARIABLE . '",nv_fc_variable="' . NV_OP_VARIABLE . '",nv_lang_variable="' . NV_LANG_VARIABLE . '",nv_module_name="' . $module_name . '",nv_func_name="' . $op . '",nv_is_user=' . ((int) defined('NV_IS_USER')) . ', nv_my_ofs=' . round(NV_SITE_TIMEZONE_OFFSET / 3600) . ',nv_my_abbr="' . nv_date('T', NV_CURRENTTIME) . '",nv_cookie_prefix="' . $global_config['cookie_prefix'] . '",nv_check_pass_mstime=' . ($global_config['user_check_pass_time'] != 0 ? (((int) ($global_config['user_check_pass_time']) - 62) * 1000) : 0) . ',nv_area_admin=0,nv_safemode=' . $safemode . ',theme_responsive=' . ((int) ($global_config['current_theme_type'] == 'r'));
    if (defined('NV_SCRIPT_NONCE')) {
        $jsDef .= ',site_nonce="' . NV_SCRIPT_NONCE . '"';
    }

    if (defined('NV_IS_DRAG_BLOCK')) {
        $jsDef .= ',drag_block=1,blockredirect="' . nv_redirect_encrypt($client_info['selfurl']) . '",selfurl="' . $client_info['selfurl'] . '",block_delete_confirm="' . $nv_Lang->getGlobal('block_delete_confirm') . '",block_outgroup_confirm="' . $nv_Lang->getGlobal('block_outgroup_confirm') . '",blocks_saved="' . $nv_Lang->getGlobal('blocks_saved') . '",blocks_saved_error="' . $nv_Lang->getGlobal('blocks_saved_error') . '",post_url="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=",func_id=' . $module_info['funcs'][$op]['func_id'] . ',module_theme="' . $global_config['module_theme'] . '"';
    }
    $jsDef .= ',nv_recaptcha_ver=' . $global_config['recaptcha_ver'];
    $jsDef .= ',nv_recaptcha_sitekey="' . $global_config['recaptcha_sitekey'] . '"';
    $jsDef .= ',nv_recaptcha_type="' . $global_config['recaptcha_type'] . '"';
    $jsDef .= ',nv_turnstile_sitekey="' . $global_config['turnstile_sitekey'] . '"';

    !isset($global_config['XSSsanitize']) && $global_config['XSSsanitize'] = 1;
    $jsDef .= ',XSSsanitize=' . ($global_config['XSSsanitize'] ? 1 : 0);
    $jsDef .= ',nv_jsdate_get="' . nv_region_config('jsdate_get') . '"';
    $jsDef .= ',nv_jsdate_post="' . nv_region_config('jsdate_post') . '"';
    $jsDef .= ',nv_gfx_width="' . NV_GFX_WIDTH . '"';
    $jsDef .= ',nv_gfx_height="' . NV_GFX_HEIGHT . '"';
    $jsDef .= ',nv_gfx_num="' . NV_GFX_NUM . '"';
    $jsDef .= ',nv_cache_timestamp=' . intval($global_config['timestamp']);

    $jsDef .= ';';

    $return = [];
    $return[] = [
        'ext' => 0,
        'content' => $jsDef
    ];
    $return[] = [
        'ext' => 1,
        'content' => ASSETS_STATIC_URL . '/js/jquery/jquery.min.js'
    ];

    if ($language_js) {
        $return[] = [
            'ext' => 1,
            'content' => ASSETS_LANG_STATIC_URL . '/js/language/' . NV_LANG_INTERFACE . AUTO_MINIFIED . '.js'
        ];
    }

    if ($global_js) {
        if ($global_config['XSSsanitize']) {
            $return[] = [
                'ext' => 1,
                'content' => ASSETS_STATIC_URL . '/js/DOMPurify/purify' . ($browser->isBrowser(Browser::BROWSER_IE) ? '2' : '3') . '.js'
            ];
        }

        $return[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/global' . AUTO_MINIFIED . '.js'
        ];

        $return[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/site' . AUTO_MINIFIED . '.js'
        ];
    }

    if (!empty($other_js)) {
        foreach ($other_js as $other) {
            if (isset($other['ext']) and ($other['ext'] == '0' or $other['ext'] == '1') and !empty($other['content'])) {
                $return[] = [
                    'ext' => (int) $other['ext'],
                    'content' => $other['content']
                ];
            }
        }
    }
    if (defined('NV_IS_ADMIN')) {
        $return[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/admin' . AUTO_MINIFIED . '.js'
        ];
    }

    // module js
    if ($module_js) {
        if (theme_file_exists($module_info['template'] . '/js/' . $module_info['module_theme'] . '.js')) {
            $return[] = [
                'ext' => 1,
                'content' => NV_STATIC_URL . 'themes/' . $module_info['template'] . '/js/' . $module_info['module_theme'] . '.js'
            ];
        } else {
            $fs = glob(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/js/' . $module_file . '.*');
            if (!empty($fs) and in_array(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/js/' . $module_file . '.js', $fs, true)) {
                $return[] = [
                    'ext' => 1,
                    'content' => NV_STATIC_URL . 'themes/' . $module_info['template'] . '/js/' . $module_file . '.js'
                ];
            } elseif ($default_js and (empty($fs) or !in_array(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/js/' . $module_file . '.nojs', $fs, true))) {
                if (theme_file_exists('default/js/' . $module_file . '.js')) {
                    $return[] = [
                        'ext' => 1,
                        'content' => NV_STATIC_URL . 'themes/default/js/' . $module_file . '.js'
                    ];
                }
            }
        }
    }

    if (defined('NV_IS_DRAG_BLOCK')) {
        $return[] = [
            'ext' => 1,
            'content' => ASSETS_STATIC_URL . '/js/jquery-ui/jquery-ui.min.js'
        ];
    }

    // Json-ld schema
    if (!empty($nv_schemas)) {
        $schemas = array_values($nv_schemas);
        if (count($schemas) == 1) {
            $schemas = $schemas[0];
        }
        $return[] = [
            'type' => 'application/ld+json',
            'ext' => 0,
            'content' => json_encode(nv_unhtmlspecialchars($schemas), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        ];
    }

    if (!$html) {
        return $return;
    }
    $res = '';
    foreach ($return as $js) {
        if ($js['ext'] == 1) {
            $res .= '<script' . (defined('NV_SCRIPT_NONCE') ? ' nonce="' . NV_SCRIPT_NONCE . '"' : '') . ' src="' . $js['content'] . '"></script>' . PHP_EOL;
        } else {
            $res .= '<script' . (defined('NV_SCRIPT_NONCE') ? ' nonce="' . NV_SCRIPT_NONCE . '"' : '') . '>' . PHP_EOL;
            $res .= $js['content'] . PHP_EOL;
            $res .= '</script>' . PHP_EOL;
        }
    }

    return $res;
}

/**
 * Lấy giao diện thanh công cụ của quản trị khi đăng nhập ngoài site
 *
 * @return string
 */
function nv_admin_menu()
{
    global $module_info, $global_config, $db_config, $admin_info, $nv_Cache;

    $dir_basenames = [$global_config['site_theme']];
    if ($module_info['theme'] == $module_info['template']) {
        array_unshift($dir_basenames, $module_info['template']);
    }
    $php_dir = get_tpl_dir($dir_basenames, NV_DEFAULT_SITE_THEME, '/theme_toolbar.php');

    $enable_drag = false;
    if (defined('NV_IS_SPADMIN')) {
        $sql = 'SELECT COUNT(*) AS count FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE act_' . $admin_info['level'] . ' = 1 AND module=\'themes\'';
        $list = $nv_Cache->db($sql, '', 'authors');
        if (!empty($list[0]['count'])) {
            $enable_drag = true;
        }
    }

    return require NV_ROOTDIR . '/themes/' . $php_dir . '/theme_toolbar.php';
}

/**
 * nv_groups_list_pub()
 *
 * @param string $mod_data
 * @return array
 */
function nv_groups_list_pub($mod_data = 'users')
{
    global $nv_Cache, $db, $db_config, $global_config;

    $_mod_table = ($mod_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $mod_data;

    $query = 'SELECT g.group_id, d.title, g.group_type, g.exp_time FROM ' . $_mod_table . '_groups AS g LEFT JOIN ' . $_mod_table . "_groups_detail d ON ( g.group_id = d.group_id AND d.lang='" . NV_LANG_DATA . "' ) WHERE g.act=1 AND (g.idsite = " . $global_config['idsite'] . ' OR (g.idsite =0 AND g.siteus = 1)) ORDER BY g.idsite, g.weight';
    $list = $nv_Cache->db($query, '', $mod_data);

    if (empty($list)) {
        return [];
    }

    $groups = [];
    $reload = [];
    for ($i = 0, $count = count($list); $i < $count; ++$i) {
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

/**
 * fix_theme_configs()
 * Xác định lại các biến toàn cục liên quan đến giao diện
 *
 * @param mixed $configs
 * @return array
 */
function fix_theme_configs($configs)
{
    if (!in_array('r', $configs['array_theme_type'], true) and !in_array('d', $configs['array_theme_type'], true)) {
        $configs['array_theme_type'][] = 'r';
    }
    !in_array('m', $configs['array_theme_type'], true) && $configs['mobile_theme'] = '';
    empty($configs['mobile_theme']) && $configs['switch_mobi_des'] = 0;
    if (empty($configs['switch_mobi_des']) and in_array('m', $configs['array_theme_type'], true)) {
        $configs['array_theme_type'] = array_values(array_diff($configs['array_theme_type'], ['m']));
    }

    return [$configs['array_theme_type'], $configs['mobile_theme'], $configs['switch_mobi_des']];
}

/**
 * Xác định kiểu theme hiện tại, theme của module và theme của site
 *
 * @param mixed $global_config
 * @param bool  $is_mobile
 * @param mixed $module_info
 */
function set_theme_configs(&$global_config, &$is_mobile, $module_info)
{
    global $nv_Request, $client_info;

    $cookie_themetype = $nv_Request->get_string(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, 'cookie', '');

    if (($nv_preview_theme = $nv_Request->get_title('nv_preview_theme_' . NV_LANG_DATA, 'session', '')) != '' and in_array($nv_preview_theme, $global_config['array_preview_theme'], true) and theme_file_exists($nv_preview_theme . '/theme.php')) {
        if (preg_match($global_config['check_theme_mobile'], $nv_preview_theme)) {
            $is_mobile = true;
            $global_config['current_theme_type'] = 'm';
        } else {
            $is_mobile = false;
            $global_config['current_theme_type'] = $cookie_themetype ?: $global_config['array_theme_type'][0];
            $array_theme_type = array_flip($global_config['array_theme_type']);
            unset($array_theme_type['m']);
            if (!isset($array_theme_type[$global_config['current_theme_type']])) {
                $array_theme_type = array_flip($array_theme_type);
                $global_config['current_theme_type'] = current($array_theme_type);
            }
        }
        $global_config['module_theme'] = $global_config['site_theme'] = $nv_preview_theme;
        unset($nv_preview_theme);
    } else {
        /*
         * Xác định kiểu giao diện
         * - responsive: r
         * - desktop: d
         * - mobile: m
         */
        $global_config['current_theme_type'] = $cookie_themetype ?: $global_config['array_theme_type'][0];
        if (!in_array($global_config['current_theme_type'], $global_config['array_theme_type'], true)) {
            $global_config['current_theme_type'] = '';
            $nv_Request->set_Cookie(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, '', NV_LIVE_COOKIE_TIME);
        }

        // Xac dinh giao dien chung
        $is_mobile = false;
        $theme_type = '';
        $_theme_mobile = empty($module_info['mobile']) ? $global_config['mobile_theme'] : (($module_info['mobile'] == ':pcsite') ? $global_config['site_theme'] : (($module_info['mobile'] == ':pcmod') ? $module_info['theme'] : $module_info['mobile']));
        if (
            (
                // Giao diện mobile tự động nhận diện dựa vào client
                ($client_info['is_mobile'] and in_array('m', $global_config['array_theme_type'], true)
                    and (empty($global_config['current_theme_type']) or empty($global_config['switch_mobi_des'])))
                // Giao diện mobile lấy từ chuyển đổi giao diện
                or ($global_config['current_theme_type'] == 'm' and !empty($global_config['switch_mobi_des']))
            )
            and !empty($_theme_mobile) and theme_file_exists($_theme_mobile . '/theme.php')
        ) {
            $global_config['module_theme'] = $_theme_mobile;
            $is_mobile = true;
            $theme_type = 'm';
        } else {
            if (empty($global_config['current_theme_type']) and in_array('r', $global_config['array_theme_type'], true) and ($client_info['is_mobile'] or empty($_theme_mobile))) {
                $global_config['current_theme_type'] = 'r';
            }

            $_theme = (!empty($module_info['theme'])) ? $module_info['theme'] : $global_config['site_theme'];
            $_u_theme = $nv_Request->get_title('nv_u_theme_' . NV_LANG_DATA, 'cookie', '');

            if (in_array($_u_theme, $global_config['array_user_allowed_theme'], true) and theme_file_exists($_u_theme . '/theme.php')) {
                // Giao diện do người dùng chọn
                $global_config['module_theme'] = $_u_theme;
                $global_config['site_theme'] = $_u_theme;
            } elseif (!empty($_theme) and theme_file_exists($_theme . '/theme.php')) {
                $global_config['module_theme'] = $_theme;
            } elseif (theme_file_exists('default/theme.php')) {
                $global_config['module_theme'] = 'default';
            } else {
                http_response_code(500);
                trigger_error('Error! Does not exist themes default', 256);
            }
            $theme_type = $global_config['current_theme_type'];
        }

        // Xac lap lai giao kieu giao dien hien tai
        if ($theme_type != $global_config['current_theme_type']) {
            $global_config['current_theme_type'] = $theme_type;
            $nv_Request->set_Cookie(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, $theme_type, NV_LIVE_COOKIE_TIME);
        }
    }
}

/**
 * Xác định loại captcha của module
 *
 * @param string $module_name
 * @return string
 */
function nv_module_captcha(string $module_name): string
{
    global $global_config, $module_config;

    $module_captcha = $module_name == 'users' ? $global_config['captcha_type'] : (
        (isset($module_config[$module_name]) and !empty($module_config[$module_name]['captcha_type'])) ? $module_config[$module_name]['captcha_type'] : ''
    );

    if (
        !(empty($module_captcha) or in_array($module_captcha, ['captcha', 'recaptcha', 'turnstile'], true)) or
        ($module_captcha == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey']))) or
        ($module_captcha == 'turnstile' and (empty($global_config['turnstile_sitekey']) or empty($global_config['turnstile_secretkey'])))
    ) {
        $module_captcha = 'captcha';
    }

    return $module_captcha;
}
