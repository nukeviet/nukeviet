<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
}

// Xử lý báo cáo lỗi
if (!empty($module_config[$module_name]['report_active']) and (!empty($module_config[$module_name]['report_group']) and nv_user_in_groups($module_config[$module_name]['report_group']))) {
    $action = $nv_Request->get_title('action', 'post', '');
    if ($action == 'report') {
        $newsid = $nv_Request->get_int('newsid', 'post', 0);
        if (!$newsid) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('article_not_found')
            ]);
        }
        $news_details = $db->query('SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . $newsid . ' AND status=1')->fetch();
        if (empty($news_details)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('article_not_found')
            ]);
        }
        $checkss = md5($newsid . NV_CHECK_SESSION);
        $csrf = $nv_Request->get_title('_csrf', 'post', '');
        if (!hash_equals($checkss, $csrf)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => 'Stop!!!'
            ]);
        }
        unset($nv_seccode);

        if ($module_captcha == 'recaptcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
            $nv_seccode = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } elseif ($module_captcha == 'turnstile') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
            $fcode = $nv_Request->get_title('cf-turnstile-response', 'post', '');
        } elseif ($module_captcha == 'captcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
            $nv_seccode = $nv_Request->get_title('captcha', 'post', '');
        }
        // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
        if (isset($nv_seccode) and !nv_capcha_txt($nv_seccode, $module_captcha)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
            ]);
        }
        $orig_content = $nv_Request->get_title('report_content', 'post', '');
        $orig_content = nv_substr(trim(strip_tags($orig_content)), 0, 250);
        if (nv_strlen($orig_content) < 3) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('report_content_empty')
            ]);
        }
        $repl_content = $nv_Request->get_title('report_fix', 'post', '');
        $repl_content = nv_substr(trim(strip_tags($repl_content)), 0, 250);
        if (nv_strlen($repl_content) > 0 and strcasecmp($orig_content, $repl_content) == 0) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('report_same_values')
            ]);
        }
        $post_email = $nv_Request->get_title('report_email', 'post', '');
        if (defined('NV_IS_USER')) {
            $post_email = $user_info['email'];
        }
        if (!empty($post_email)) {
            $check_email = nv_check_valid_email($post_email, true);
            if (!empty($check_email[0])) {
                $post_email = '';
            } else {
                $post_email = $check_email[1];
            }
        }
        $last_post_time = $db->query('SELECT MAX(post_time) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE post_ip = ' . $db->quote($client_info['ip']))->fetchColumn();
        if ($last_post_time > NV_CURRENTTIME - (int) $module_config[$module_name]['report_limit'] * 60) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $nv_Lang->getModule('sending_too_much')
            ]);
        }
        $md5content = md5($orig_content);
        $rid = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE newsid = ' . $newsid . ' AND md5content = ' . $db->quote($md5content) . ' AND  post_ip = ' . $db->quote($client_info['ip']))->fetchColumn();
        if ($rid) {
            $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_report SET post_email=:post_email, post_time=' . NV_CURRENTTIME . ', repl_content=:repl_content WHERE id=' . $rid);
            $sth->bindParam(':post_email', $post_email, PDO::PARAM_STR);
            $sth->bindParam(':repl_content', $repl_content, PDO::PARAM_STR);
            $sth->execute();
        } else {
            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_report
        (newsid, md5content, post_ip, post_email, post_time, orig_content, repl_content) VALUES
        ( ' . $newsid . ', :md5content, :post_ip, :post_email, ' . NV_CURRENTTIME . ', :orig_content, :repl_content)');
            $sth->bindParam(':md5content', $md5content, PDO::PARAM_STR);
            $sth->bindParam(':post_ip', $client_info['ip'], PDO::PARAM_STR);
            $sth->bindParam(':post_email', $post_email, PDO::PARAM_STR);
            $sth->bindParam(':orig_content', $orig_content, PDO::PARAM_STR);
            $sth->bindParam(':repl_content', $repl_content, PDO::PARAM_STR);
            $sth->execute();
            $rid = $db->lastInsertId();
        }
        nv_insert_notification($module_name, 'report', ['newsid' => $newsid, 'title' => $news_details['title'], 'post_ip' => $client_info['ip'], 'post_email' => $post_email], $rid);
        nv_jsonOutput([
            'status' => 'OK',
            'mess' => $nv_Lang->getModule('report_success')
        ]);
    }
}

$page_title = $module_info['site_title'];
$page_url = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

$contents = $cache_file = '';
$schemas = $desc = $kw = [];
$isMob = ((!empty($global_config['mobile_theme']) and $module_info['template'] == $global_config['mobile_theme']) or ($client_info['is_mobile'] and $global_config['current_theme_type'] != 'd'));
$viewcat = $isMob ? $module_config[$module_name]['mobile_indexfile'] : $module_config[$module_name]['indexfile'];
$no_generate = ['viewcat_none', 'viewcat_main_left', 'viewcat_main_right', 'viewcat_main_bottom', 'viewcat_two_column'];

if ($page > 1) {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=page-' . $page;

    /*
     * @link https://github.com/nukeviet/nukeviet/issues/2990
     * Một số kiểu hiển thị không được đánh page
     */
    if (in_array($viewcat, $no_generate, true)) {
        nv_redirect_location($base_url);
    }
}

$canonicalUrl = getCanonicalUrl($page_url);

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = $module_info['template'] . '-' . $op . '-' . $viewcat . '-' . $page . '-' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, ttl: 3600)) != false) {
        $cache = json_decode($cache, true);
        $contents = $cache['html'];
        $schemas = $cache['schemas'];
        $desc = $cache['description'];
        $kw = $cache['keyword'];
    }
    unset($cache);
}

if (empty($contents)) {
    $show_no_image = $module_config[$module_name]['show_no_image'];
    $array_catpage = [];
    $array_cat_other = [];
    $show_schema = false;

    if ($viewcat == 'viewcat_none') {
        $contents = '';
    } elseif ($viewcat == 'viewcat_page_new' or $viewcat == 'viewcat_page_old') {
        $show_schema = true;
        $order_by = ($viewcat == 'viewcat_page_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';
        $db_slave->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, weight, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $weight_publtime = 0;
        $result = $db_slave->query($db_slave->sql());
        $i = 0;
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;
            $weight_publtime = ($order_articles) ? $item['weight'] : $item['publtime'];

            if ($i < 200) {
                $desc[] = $item['title'];
            }

            $i += nv_strlen($item['title']);
        }

        if ($st_links > 0) {
            $db_slave->sqlreset()
                ->select('id, catid, listcatid, addtime, edittime, publtime, title, alias, external_link, hitstotal')
                ->from(NV_PREFIXLANG . '_' . $module_data . '_rows');

            if ($viewcat == 'viewcat_page_new') {
                $db_slave->where('status= 1 AND inhome=1 AND ' . $order_articles_by . ' < ' . $weight_publtime);
            } else {
                $db_slave->where('status= 1 AND inhome=1 AND ' . $order_articles_by . ' > ' . $weight_publtime);
            }
            $db_slave->order($order_by)->limit($st_links);

            $result = $db_slave->query($db_slave->sql());
            while ($item = $result->fetch()) {
                $item['newday'] = $global_array_cat[$item['catid']]['newday'];
                $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                $array_cat_other[] = $item;
            }
        }

        $viewcat = 'viewcat_page_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, $array_cat_other, $generate_page);
    } elseif ($viewcat == 'viewcat_main_left' or $viewcat == 'viewcat_main_right' or $viewcat == 'viewcat_main_bottom') {
        $array_cat = [];

        $key = 0;
        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_articles_by . ' DESC');

        foreach ($global_array_cat as $_catid => $array_cat_i) {
            if ($array_cat_i['parentid'] == 0 and $array_cat_i['status'] == 1) {
                $array_cat[$key] = $array_cat_i;
                $featured = 0;
                if ($array_cat_i['featured'] != 0) {
                    $result = $db_slave->query($db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('id=' . $array_cat_i['featured'] . ' and status= 1 AND inhome=1')
                        ->sql());
                    if ($item = $result->fetch()) {
                        $item['imghome'] = $item['imgmobile'] = '';
                        get_homeimgfile($item);

                        $item['newday'] = $array_cat_i['newday'];
                        $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                        $array_cat[$key]['content'][] = $item;
                        $featured = $item['id'];
                    }
                }

                if ($featured) {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1 AND id!=' . $featured)
                        ->limit($array_cat_i['numlinks'] - 1);
                } else {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks']);
                }

                $result = $db_slave->query($db_slave->sql());
                while ($item = $result->fetch()) {
                    $item['imghome'] = $item['imgmobile'] = '';
                    get_homeimgfile($item);

                    $item['newday'] = $array_cat_i['newday'];
                    $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_cat[$key]['content'][] = $item;
                }

                $desc[] = $array_cat_i['title'];
                $kw[] = $array_cat_i['title'];

                ++$key;
            }
        }

        $contents = viewsubcat_main($viewcat, $array_cat);
    } elseif ($viewcat == 'viewcat_two_column') {
        // Cac bai viet phan dau
        $array_content = $array_catpage = [];

        // cac bai viet cua cac chu de con
        $key = 0;

        $db_slave->sqlreset()
            ->select('id, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->where('status= 1 AND inhome=1')
            ->order($order_articles_by . ' DESC');
        foreach ($global_array_cat as $_catid => $array_cat_i) {
            if ($array_cat_i['parentid'] == 0 and $array_cat_i['status'] == 1) {
                $array_catpage[$key] = $array_cat_i;
                $featured = 0;
                if ($array_cat_i['featured'] != 0) {
                    $result = $db_slave->query($db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('id=' . $array_cat_i['featured'] . ' and status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks'])
                        ->sql());
                    while ($item = $result->fetch()) {
                        $item['imghome'] = $item['imgmobile'] = '';
                        get_homeimgfile($item);

                        $item['newday'] = $array_cat_i['newday'];
                        $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                        $array_catpage[$key]['content'][] = $item;
                        $featured = $item['id'];
                    }
                }
                if ($featured) {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1 AND id!=' . $featured)
                        ->limit($array_cat_i['numlinks'] - 1);
                } else {
                    $db_slave->from(NV_PREFIXLANG . '_' . $module_data . '_' . $_catid)
                        ->where('status= 1 AND inhome=1')
                        ->limit($array_cat_i['numlinks']);
                }
                $result = $db_slave->query($db_slave->sql());

                while ($item = $result->fetch()) {
                    $item['imghome'] = $item['imgmobile'] = '';
                    get_homeimgfile($item);

                    $item['newday'] = $array_cat_i['newday'];
                    $item['link'] = $array_cat_i['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
                    $array_catpage[$key]['content'][] = $item;
                }

                $desc[] = $array_cat_i['title'];
                $kw[] = $array_cat_i['title'];
            }

            ++$key;
        }
        unset($sql, $result);
        //Het cac bai viet cua cac chu de con
        $contents = viewcat_two_column($array_content, '', $array_catpage);
    } elseif ($viewcat == 'viewcat_grid_new' or $viewcat == 'viewcat_grid_old') {
        $show_schema = true;
        $order_by = ($viewcat == 'viewcat_grid_new') ? $order_articles_by . '  DESC' : $order_articles_by . '  ASC';
        $db_slave->sqlreset()
            ->select('COUNT(*) ')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        $i = 0;
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;

            if ($i < 200) {
                $desc[] = $item['title'];
            }

            $i += nv_strlen($item['title']);
        }

        $viewcat = 'viewcat_grid_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, 0, $generate_page);
    } elseif ($viewcat == 'viewcat_list_new' or $viewcat == 'viewcat_list_old') {
        // Xem theo tieu de
        $order_by = ($viewcat == 'viewcat_list_new') ? $order_articles_by . ' DESC, addtime DESC' : $order_articles_by . ' ASC, addtime ASC';
        $show_schema = true;

        $db_slave->sqlreset()
            ->select('COUNT(*) ')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
            ->where('status= 1 AND inhome=1');

        $num_items = $db_slave->query($db_slave->sql())
            ->fetchColumn();

        // Không cho tùy ý đánh số page + xác định trang trước, trang sau
        betweenURLs($page, ceil($num_items / $per_page), $base_url, '&amp;' . NV_OP_VARIABLE . '=page-', $prevPage, $nextPage);

        $db_slave->select('id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, external_link, hitstotal, hitscm, total_rating, click_rating')
            ->order($order_by)
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);

        $result = $db_slave->query($db_slave->sql());
        $i = 0;
        while ($item = $result->fetch()) {
            $item['imghome'] = $item['imgmobile'] = '';
            get_homeimgfile($item);

            $item['newday'] = $global_array_cat[$item['catid']]['newday'];
            $item['link'] = $global_array_cat[$item['catid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
            $array_catpage[] = $item;

            if ($i < 200) {
                $desc[] = $item['title'];
            }

            $i += nv_strlen($item['title']);
        }

        $viewcat = 'viewcat_list_new';
        $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
        $contents = call_user_func($viewcat, $array_catpage, 0, ($page - 1) * $per_page, $generate_page);
    }

    // CollectionPage nếu như không hiển thị theo dạng chuyên mục
    if ($show_schema and !empty($array_catpage)) {
        $schemas_items = get_schema_colpage_items($array_catpage);
        $schemas = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $module_info['custom_title'],
            'headline' => $module_info['site_title'] ?: $module_info['custom_title'],
            'description' => $module_info['description'] ?: $module_info['custom_title'],
            'url' => $canonicalUrl,
            'inLanguage' => NV_LANG_DATA,
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $schemas_items,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $global_config['site_name'],
                'url' => NV_MY_DOMAIN
            ],
        ];
        if (!empty($global_config['site_logo'])) {
            $schemas['publisher']['logo'] = [
                '@type' => 'ImageObject',
                'url' => NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']
            ];
        }
    }

    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, json_encode([
            'html' => $contents,
            'schemas' => $schemas,
            'description' => $desc,
            'keyword' => $kw
        ]), ttl: 3600);
    }
}

if (!empty($schemas)) {
    $nv_schemas[] = $schemas;
}

if ($page > 1) {
    $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page') . ' ' . $page;
}

if (empty($module_info['description']) and !empty($desc)) {
    $description = $module_info['site_title'] . ': ' . implode(', ', $desc);
}

$key_words = $module_info['keywords'];
if (empty($key_words) and !empty($kw)) {
    $key_words = implode(', ', $kw);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
