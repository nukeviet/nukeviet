<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (! defined('NV_IS_MOD_NEWS')) {
    die('Stop!!!');
}

$contents = '';
$publtime = 0;

if (nv_user_in_groups($global_array_cat[$catid]['groups_view'])) {
    $query = $db_slave->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid . ' WHERE id = ' . $id);
    $news_contents = $query->fetch();

    if ($news_contents['id'] > 0) {
        $body_contents = $db_slave->query('SELECT titlesite, description, bodyhtml, keywords, sourcetext, files, layout_func, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_detail where id=' . $news_contents['id'])->fetch();
        $news_contents = array_merge($news_contents, $body_contents);
        unset($body_contents);

        if ($news_contents['external_link']) {
            nv_redirect_location($news_contents['sourcetext']);
        }

        if ($nv_Request->isset_request('download', 'get')) {
            $fileid = $nv_Request->get_int('id', 'get', 0);

            $news_contents['files'] = explode(',', $news_contents['files']);

            if (!isset($news_contents['files'][$fileid])) {
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
            }

            if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['files'][$fileid])) {
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true);
            }

            $file_info = pathinfo(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['files'][$fileid]);
            $download = new NukeViet\Files\Download(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['files'][$fileid], $file_info['dirname'], $file_info['basename'], true);
            $download->download_file();
            exit();
        }

        if ($nv_Request->isset_request('pdf', 'get')) {
            $fileid = $nv_Request->get_int('id', 'get', 0);

            $news_contents['files'] = explode(',', $news_contents['files']);

            if (!isset($news_contents['files'][$fileid])) {
                nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
            }

            if (!file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['files'][$fileid])) {
                nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
            }

            $file_url = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$news_contents['catid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true) . '?download=1&id=' . $fileid;

            $contents = nv_theme_viewpdf($file_url);
            nv_htmlOutput($contents);
        }

        $base_url_rewrite = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$news_contents['catid']]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);
        if ($_SERVER['REQUEST_URI'] == $base_url_rewrite) {
            $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
        } elseif (NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
            //chuyen huong neu doi alias
            nv_redirect_location($base_url_rewrite);
        } else {
            $canonicalUrl = $base_url_rewrite;
        }
        $canonicalUrl = str_replace('&', '&amp;', $canonicalUrl);

        $show_no_image = $module_config[$module_name]['show_no_image'];

        if (defined('NV_IS_MODADMIN') or ($news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME and ($news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME))) {
            $time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $id, 'session');
            if (empty($time_set)) {
                $nv_Request->set_Session($module_data . '_' . $op . '_' . $id, NV_CURRENTTIME);
                $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal=hitstotal+1 WHERE id=' . $id;
                $db->query($query);

                $array_catid = explode(',', $news_contents['listcatid']);
                foreach ($array_catid as $catid_i) {
                    $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $catid_i . ' SET hitstotal=hitstotal+1 WHERE id=' . $id;
                    $db->query($query);
                }
            }
            $news_contents['showhometext'] = $module_config[$module_name]['showhometext'];
            if (! empty($news_contents['homeimgfile'])) {
                $src = $alt = $note = '';
                $width = $height = 0;
                if ($news_contents['homeimgthumb'] == 1 and $news_contents['imgposition'] == 1) {
                    $src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
                    $news_contents['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
                    $width = $module_config[$module_name]['homewidth'];
                } elseif ($news_contents['homeimgthumb'] == 3) {
                    $src = $news_contents['homeimgfile'];
                    $width = ($news_contents['imgposition'] == 1) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
                } elseif (file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'])) {
                    $src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
                    if ($news_contents['imgposition'] == 1) {
                        $width = $module_config[$module_name]['homewidth'];
                    } else {
                        $imagesize = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile']);
                        if ($imagesize[0] > 0 and $imagesize[0] > $module_config[$module_name]['imagefull']) {
                            $width = $module_config[$module_name]['imagefull'];
                        } else {
                            $width = $imagesize[0];
                        }
                    }
                    $news_contents['homeimgfile'] = $src;
                }

                if (! empty($src)) {
                    $meta_property['og:image'] = (preg_match('/^(http|https|ftp|gopher)\:\/\//', $news_contents['homeimgfile'])) ? $news_contents['homeimgfile'] : NV_MY_DOMAIN . $news_contents['homeimgfile'];
                    if ($news_contents['imgposition'] > 0) {
                        $news_contents['image'] = array(
                            'src' => $src,
                            'width' => $width,
                            'alt' => (empty($news_contents['homeimgalt'])) ? $news_contents['title'] : $news_contents['homeimgalt'],
                            'note' => $news_contents['homeimgalt'],
                            'position' => $news_contents['imgposition']
                        );
                    }
                } elseif (!empty($show_no_image)) {
                    $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
                }
            } elseif (! empty($show_no_image)) {
                $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
            }

            // File download
            if (!empty($news_contents['files'])) {
                $news_contents['files'] = explode(',', $news_contents['files']);
                $files = $news_contents['files'];
                $news_contents['files'] = array();

                foreach ($files as $file_id => $file) {
                    $file_title = (!preg_match("/^http*/", $file)) ? basename($file) : $lang_module['click_to_download'];
                    $news_contents['files'][] = array(
                        'title' => $file_title,
                        'key' => md5($file_id . $file_title),
                        'ext' => nv_getextension($file_title),
                        'titledown' => $lang_module['download'] . ' ' . (count($files) > 1 ? $file_id + 1 : ''),
                        'src' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $file,
                        'url' => (!preg_match("/^http*/", $file)) ?  $base_url_rewrite . '?download=1&amp;id=' . $file_id : $file,
                        'urlpdf' => $base_url_rewrite . '?pdf=1&amp;id=' . $file_id,
                        'urldoc' => (preg_match("/^http*/", $file)) ? $file : 'https://docs.google.com/viewer?embedded=true&url=' . NV_MY_DOMAIN . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $file
                    );
                }
            }

            $publtime = intval($news_contents['publtime']);
            $meta_property['og:type'] = 'article';
            $meta_property['article:published_time'] = date('Y-m-dTH:i:s', $publtime);
            $meta_property['article:modified_time'] = date('Y-m-dTH:i:s', $news_contents['edittime']);
            if ($news_contents['exptime']) {
                $meta_property['article:expiration_time'] = date('Y-m-dTH:i:s', $news_contents['exptime']);
            }
            $meta_property['article:section'] = $global_array_cat[$news_contents['catid']]['title'];
        }

        if (defined('NV_IS_MODADMIN') and $news_contents['status'] != 1) {
            $alert = sprintf($lang_module['status_alert'], $lang_module['status_' . $news_contents['status']]);
            $my_footer .= "<script type=\"text/javascript\">alert('". $alert ."')</script>";
            $news_contents['allowed_send'] = 0;
            $module_config[$module_name]['socialbutton'] = 0;
        }
    }

    if ($publtime == 0) {
        $redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true) . '" />';
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect, 404);
    }

    $news_contents['url_sendmail'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sendmail/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);
    $news_contents['url_print'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);
    $news_contents['url_savefile'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=savefile/' . $global_array_cat[$catid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true);

    $news_contents['source'] = '';
    if( $news_contents['sourceid'] )
    {
        $sql = 'SELECT title, link, logo FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sources WHERE sourceid = ' . $news_contents['sourceid'];
        $result = $db_slave->query($sql);
        list($sourcetext, $source_link, $source_logo) = $result->fetch(3);
        unset($sql, $result);
        if ($module_config[$module_name]['config_source'] == 0) {
            $news_contents['source'] = $sourcetext; // Hiển thị tiêu đề nguồn tin
        } elseif ($module_config[$module_name]['config_source'] == 1) {
            $news_contents['source'] = '<a title="' . $sourcetext . '" rel="nofollow" href="' . $news_contents['sourcetext'] . '">' . $source_link . '</a>'; // Hiển thị link của nguồn tin
        } elseif ($module_config[$module_name]['config_source'] == 3) {
            $news_contents['source'] = '<a title="' . $sourcetext . '" href="' . $news_contents['sourcetext'] . '">' . $source_link . '</a>'; // Hiển thị link của nguồn tin
        } elseif ($module_config[$module_name]['config_source'] == 2 and ! empty($source_logo)) {
            $news_contents['source'] = '<img width="100px" src="' . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/source/' . $source_logo . '">';
        }
    }

    $news_contents['publtime'] = nv_date('l - d/m/Y H:i', $news_contents['publtime']);
    $news_contents['newscheckss'] = md5($news_contents['id'] . NV_CHECK_SESSION);

    $related_new_array = array();
    $related_array = array();
    if ($st_links > 0) {
        $db_slave->sqlreset()
            ->select('id, title, alias, publtime, homeimgfile, homeimgthumb, hometext, external_link')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1 AND publtime > ' . $publtime)
            ->order('id ASC')
            ->limit($st_links);

        $related = $db_slave->query($db_slave->sql());
        while ($row = $related->fetch()) {
            if ($row['homeimgthumb'] == 1) {
                //image thumb
                $row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                //image file
                $row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                //image url
                $row['imghome'] = $row['homeimgfile'];
            } elseif (! empty($show_no_image)) {
                //no image
                $row['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $row['imghome'] = '';
            }

            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
            $related_new_array[] = array(
                'title' => $row['title'],
                'time' => $row['publtime'],
                'link' => $link,
                'newday' => $global_array_cat[$catid]['newday'],
                'hometext' => $row['hometext'],
                'imghome' => $row['imghome'],
                'external_link' => $row['external_link']
            );
        }
        $related->closeCursor();

        sort($related_new_array, SORT_NUMERIC);

        $db_slave->sqlreset()
            ->select('id, title, alias, publtime, homeimgfile, homeimgthumb, hometext, external_link')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_' . $catid)
            ->where('status=1 AND publtime < ' . $publtime)
            ->order('id DESC')
            ->limit($st_links);

        $related = $db_slave->query($db_slave->sql());
        while ($row = $related->fetch()) {
            if ($row['homeimgthumb'] == 1) {
                //image thumb
                $row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                //image file
                $row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                //image url
                $row['imghome'] = $row['homeimgfile'];
            } elseif (! empty($show_no_image)) {
                //no image
                $row['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $row['imghome'] = '';
            }

            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
            $related_array[] = array(
                'title' => $row['title'],
                'time' => $row['publtime'],
                'link' => $link,
                'newday' => $global_array_cat[$catid]['newday'],
                'hometext' => $row['hometext'],
                'imghome' => $row['imghome'],
                'external_link' => $row['external_link']
            );
        }

        $related->closeCursor();
        unset($related, $row);
    }

    $topic_array = array();
    if ($news_contents['topicid'] > 0 & $st_links > 0) {
        list($topic_title, $topic_alias) = $db_slave->query('SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_topics WHERE topicid = ' . $news_contents['topicid'])->fetch(3);

        $topiclink = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['topic'] . '/' . $topic_alias;

        $db_slave->sqlreset()
            ->select('id, catid, title, alias, publtime, homeimgfile, homeimgthumb, hometext, external_link')
            ->from(NV_PREFIXLANG . '_' . $module_data . '_rows t1')
            ->where('status=1 AND topicid = ' . $news_contents['topicid'] . ' AND id != ' . $id)
            ->order('id DESC')
            ->limit($st_links);
        $topic = $db_slave->query($db_slave->sql());
        while ($row = $topic->fetch()) {
            if ($row['homeimgthumb'] == 1) {
                //image thumb
                $row['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 2) {
                //image file
                $row['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
            } elseif ($row['homeimgthumb'] == 3) {
                //image url
                $row['imghome'] = $row['homeimgfile'];
            } elseif (! empty($show_no_image)) {
                //no image
                $row['imghome'] = NV_BASE_SITEURL . $show_no_image;
            } else {
                $row['imghome'] = '';
            }

            $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
            $topic_array[] = array(
                'title' => $row['title'],
                'link' => $link,
                'time' => $row['publtime'],
                'newday' => $global_array_cat[$row['catid']]['newday'],
                'topiclink' => $topiclink,
                'topictitle' => $topic_title,
                'hometext' => $row['hometext'],
                'imghome' => $row['imghome'],
                'external_link' => $row['external_link']
            );
        }
        $topic->closeCursor();
        unset($topic, $rows);
    }

    if ($news_contents['allowed_rating']) {
        $time_set_rating = $nv_Request->get_int($module_name . '_' . $op . '_' . $news_contents['id'], 'cookie', 0);
        if ($time_set_rating > 0) {
            $news_contents['disablerating'] = 1;
        } else {
            $news_contents['disablerating'] = 0;
        }
        $news_contents['stringrating'] = sprintf($lang_module['stringrating'], $news_contents['total_rating'], $news_contents['click_rating']);
        $news_contents['numberrating'] = ($news_contents['click_rating'] > 0) ? round($news_contents['total_rating'] / $news_contents['click_rating'], 1) : 0;
        $news_contents['langstar'] = array(
            'note' => $lang_module['star_note'],
            'verypoor' => $lang_module['star_verypoor'],
            'poor' => $lang_module['star_poor'],
            'ok' => $lang_module['star_ok'],
            'good' => $lang_module['star_good}'],
            'verygood' => $lang_module['star_verygood']
        );
    }

    list($post_username, $post_first_name, $post_last_name) = $db_slave->query('SELECT username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $news_contents['admin_id'])->fetch(3);
    $news_contents['post_name'] = nv_show_name_user($post_first_name, $post_last_name, $post_username);

    $array_keyword = array();
    $key_words = array();
    $_query = $db_slave->query('SELECT a1.keyword, a2.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_tags a2 ON a1.tid=a2.tid WHERE a1.id=' . $news_contents['id']);
    while ($row = $_query->fetch()) {
        $array_keyword[] = $row;
        $key_words[] = $row['keyword'];
        $meta_property['article:tag'][] = $row['keyword'];
    }

    // comment
    if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
        define('NV_COMM_ID', $id);//ID bài viết hoặc
        define('NV_COMM_AREA', $module_info['funcs'][$op]['func_id']);//để đáp ứng comment ở bất cứ đâu không cứ là bài viết
        //check allow comemnt
        $allowed = $module_config[$module_name]['allowed_comm'];//tuy vào module để lấy cấu hình. Nếu là module news thì có cấu hình theo bài viết
        if ($allowed == '-1') {
            $allowed = $news_contents['allowed_comm'];
        }
        require_once NV_ROOTDIR . '/modules/comment/comment.php';
        $area = (defined('NV_COMM_AREA')) ? NV_COMM_AREA : 0;
        $checkss = md5($module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);

        $content_comment = nv_comment_module($module_name, $checkss, $area, NV_COMM_ID, $allowed, 1);
    } else {
        $content_comment = '';
    }

    // Xu ly Layout tuy chinh
    $module_info['layout_funcs'][$op_file] = !empty($news_contents['layout_func']) ? $news_contents['layout_func'] : $module_info['layout_funcs'][$op_file];

    $contents = detail_theme($news_contents, $array_keyword, $related_new_array, $related_array, $topic_array, $content_comment);
    $id_profile_googleplus = $news_contents['gid'];

    $page_title = empty($news_contents['titlesite']) ? $news_contents['title'] : $news_contents['titlesite'];
    $key_words = ($module_config[$module_name]['keywords_tag'] and empty($news_contents['keywords'])) ? implode(',', $key_words) : $news_contents['keywords'];
    $description = empty($news_contents['description']) ? nv_clean60(strip_tags($news_contents['hometext']), 160) : $news_contents['description'];
} else {
    $contents = no_permission($global_array_cat[$catid]['groups_view']);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';