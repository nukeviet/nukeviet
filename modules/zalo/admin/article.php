<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ZALO')) {
    exit('Stop!!!');
}

if (!$zalo->isValid()) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=settings');
}

$page_url = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=article';

// Cap nhat danh sach bai viet tu Zalo
if ($nv_Request->isset_request('getlist,type', 'get')) {
    $type = $nv_Request->get_title('type', 'get', '');
    $type != 'video' && $type = 'normal';
    $base_url .= '&amp;getlist=1&amp;type=' . $type;

    $offset = $nv_Request->get_int('offset', 'get', 0);
    if (!empty($offset)) {
        $base_url .= '&amp;offset=' . $offset;
    }

    $limit = 10;
    get_accesstoken($accesstoken);
    $result = $zalo->get_articlelist($accesstoken, $offset, $limit, $type);
    if (empty($result)) {
        $contents = zaloGetError();
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $total = (int) $result['data']['total'];
    $values = [];
    if (!empty($result['data']['medias'])) {
        foreach ($result['data']['medias'] as $article) {
            ++$offset;
            $values[] = '(' . $db->quote($article['id']) . ", '', " . $db->quote($article['type']) . ', ' . $db->quote($article['title']) . ", '', '', '', " . $db->quote($article['status']) . ', ' . floor((int) $article['create_date'] / 1000) . ', ' . floor((int) $article['update_date'] / 1000) . ', ' . (int) $article['total_view'] . ', ' . (int) $article['total_share'] . ', 0)';
        }
    }
    if (!empty($values)) {
        $values = implode(', ', $values);
        $sql = 'INSERT INTO ' . NV_MOD_TABLE . '_article (zalo_id, token, type, title, description, body, related_medias, status, create_date, update_date, total_view, total_share, is_sync) 
            VALUES ' . $values . ' 
            ON DUPLICATE KEY UPDATE status=VALUES(status), create_date=VALUES(create_date), update_date=VALUES(update_date), total_view=VALUES(total_view), total_share=VALUES(total_share)';
        $db->query($sql);
    }

    if ($offset < $total) {
        $xtpl = new XTemplate('article.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
        $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
        $xtpl->assign('GETLIST_LINK', $page_url . '&amp;getlist=1&amp;type=' . $type . '&amp;offset=' . $offset);
        $xtpl->parse('wait_getlist');
        $contents = $xtpl->text('wait_getlist');

        include NV_ROOTDIR . '/includes/header.php';
        echo nv_admin_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
    }

    $not_sync = get_article_not_sync();
    if (!empty($not_sync)) {
        foreach ($not_sync as $id => $zalo_id) {
            get_accesstoken($accesstoken);
            $result = $zalo->article_getdetail($accesstoken, $zalo_id);
            if (empty($result)) {
                $contents = zaloGetError();
                include NV_ROOTDIR . '/includes/header.php';
                echo nv_admin_theme($contents);
                include NV_ROOTDIR . '/includes/footer.php';
            }

            update_article($id, $result['data']);
        }
    }

    nv_redirect_location($page_url . '&amp;type=' . $type);
}

// Xoa bai viet
if ($nv_Request->isset_request('delete,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    $article_info = get_article_info($id);
    if (empty($article_info)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    if (!empty($article_info['zalo_id'])) {
        get_accesstoken($accesstoken, true);

        $result = $zalo->delete_article($accesstoken, $article_info['zalo_id']);
        if (empty($result)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => zaloGetError()
            ]);
        }
    }

    article_delete($id);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Dong bo bai viet
if ($nv_Request->isset_request('sync,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    $article_info = get_article_info($id);
    if (empty($article_info)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    get_accesstoken($accesstoken, true);
    $result = $zalo->article_getdetail($accesstoken, $article_info['zalo_id']);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    update_article($id, $result['data']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

// Lay Zalo_id
if ($nv_Request->isset_request('get_zalo_id,id', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    if (empty($id)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    $token = get_article_token_by_id($id);
    if (empty($token)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('article_not_selected')
        ]);
    }

    get_accesstoken($accesstoken, true);

    $result = $zalo->get_article_id($accesstoken, $token);
    if (empty($result)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => zaloGetError()
        ]);
    }

    zalo_id_update($id, $result['data']['id']);

    nv_jsonOutput([
        'status' => 'success',
        'mess' => ''
    ]);
}

$article_types = [
    'normal' => $nv_Lang->getModule('article_normal'),
    'video' => $nv_Lang->getModule('article_video')
];

$cover_types = [
    'photo' => $nv_Lang->getModule('cover_type_photo'),
    'video' => $nv_Lang->getModule('cover_type_video')
];

$cover_view_types = [
    'horizontal' => $nv_Lang->getModule('video_view_horizontal'),
    'vertical' => $nv_Lang->getModule('video_view_vertical'),
    'square' => $nv_Lang->getModule('video_view_square')
];

$body_types = [
    'text' => $nv_Lang->getModule('body_type_text'),
    'image' => $nv_Lang->getModule('body_type_image'),
    'video' => $nv_Lang->getModule('body_type_video'),
    'product' => $nv_Lang->getModule('body_type_product')
];

$body_video_types = [
    'url' => $nv_Lang->getModule('body_video_url'),
    'id' => $nv_Lang->getModule('body_video_id')
];

$article_status_types = [
    'show' => $nv_Lang->getModule('show'),
    'hide' => $nv_Lang->getModule('hide')
];

$article_comment_types = [
    'show' => $nv_Lang->getModule('show'),
    'hide' => $nv_Lang->getModule('hide')
];

$page_title = $nv_Lang->getModule('article');
$popup = $nv_Request->get_bool('popup', 'get', false);
if (!empty($popup)) {
    $base_url .= '&amp;popup=1';
}

$idfield = $nv_Request->get_title('idfield', 'get', '');
!empty($idfield) && $idfield = preg_replace('/[^a-zA-Z0-9\-\_]+/', '', $idfield);
if (!empty($idfield)) {
    $base_url .= '&amp;idfield=' . $idfield;
}

$currentid = $nv_Request->get_string('currentid', 'get', '');
if (!empty($currentid)) {
    $base_url .= '&amp;currentid=' . $currentid;
    $currentid = array_map('trim', explode(',', $currentid));
}

$type = $nv_Request->get_title('type', 'get', '');
($type != 'normal' and $type != 'video') && $type = '';
$action = $nv_Request->get_title('action', 'get', '');
($action != 'add' and $action != 'edit') && $action = '';

if (!empty($type)) {
    $page_title = $nv_Lang->getModule('article_' . $type . '_list');
    $action != 'edit' && $base_url .= '&amp;type=' . $type;
}

$list_url = $base_url;

if (!empty($action)) {
    $page_title = $nv_Lang->getModule('article_' . $action);
    $base_url .= '&amp;action=' . $action;

    $article = [
        'id' => 0,
        'zalo_id' => '',
        'type' => '',
        'title' => '',
        'author' => '',
        'cover_type' => 'photo',
        'cover_photo_url' => '',
        'cover_video_id' => '',
        'cover_view' => 'horizontal',
        'cover_status' => 'hide',
        'description' => '',
        'body' => [],
        'related_medias' => [],
        'tracking_link' => '',
        'video_id' => '',
        'video_avatar' => '',
        'status' => 'show',
        'comment' => 'show'
    ];
}

if ($action == 'add' and empty($type)) {
    nv_redirect_location($page_url . '&amp;type=normal&amp;action=add');
}

if ($action == 'edit') {
    $id = $nv_Request->get_title('id', 'get', 0);
    if (empty($id)) {
        nv_redirect_location($list_url);
    }

    $article = get_article_info($id);
    if (empty($article)) {
        nv_redirect_location($list_url);
    }

    $base_url .= '&amp;id=' . $id;
    if (!empty($type)) {
        nv_redirect_location($base_url);
    }

    $type = $article['type'];
}

if ($action == 'add' or $action == 'edit') {
    if ($nv_Request->isset_request('save', 'post')) {
        $is_localhost = is_localhost();

        $save_article = [
            'type' => $type
        ];
        $save_article['title'] = $nv_Request->get_title('title', 'post', '');
        if (empty($save_article['title'])) {
            nv_jsonOutput([
                'status' => 'error',
                'body_id' => '',
                'input' => 'title',
                'mess' => $nv_Lang->getModule('title_error')
            ]);
        }

        $save_article['description'] = $nv_Request->get_title('description', 'post', '');
        $save_article['description'] = nv_nl2br($save_article['description'], ' ');
        $save_article['description'] = trim(preg_replace('/\s+/', ' ', $save_article['description']));
        if (empty($save_article['description'])) {
            nv_jsonOutput([
                'status' => 'error',
                'body_id' => '',
                'input' => 'description',
                'mess' => $nv_Lang->getModule('description_empty')
            ]);
        }

        if ($type == 'normal') {
            $save_article['author'] = $nv_Request->get_title('author', 'post', '');
            if (empty($save_article['author'])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'body_id' => '',
                    'input' => 'author',
                    'mess' => $nv_Lang->getModule('author_empty')
                ]);
            }

            $save_article['cover'] = [
                'cover_type' => $nv_Request->get_title('cover_type', 'post', 'photo'),
                'status' => $nv_Request->get_title('cover_status', 'post', 'hide')
            ];
            if ($save_article['cover']['cover_type'] == 'photo') {
                $save_article['cover']['photo_url'] = $nv_Request->get_title('cover_photo_url', 'post', '');
                if (empty($save_article['cover']['photo_url'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'body_id' => '',
                        'input' => 'cover_photo_url',
                        'mess' => $nv_Lang->getModule('cover_photo_url_empty')
                    ]);
                }

                if (!nv_is_url($save_article['cover']['photo_url'])) {
                    if ($is_localhost) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => '',
                            'input' => 'cover_photo_url',
                            'mess' => $nv_Lang->getModule('image_from_localhost')
                        ]);
                    }

                    if (!file_exists(NV_ROOTDIR . $save_article['cover']['photo_url'])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => '',
                            'input' => 'cover_photo_url',
                            'mess' => $nv_Lang->getModule('cover_photo_url_empty')
                        ]);
                    }

                    $save_article['cover']['photo_url'] = NV_MY_DOMAIN . $save_article['cover']['photo_url'];
                }
            } else {
                $save_article['cover']['video_id'] = $nv_Request->get_title('cover_video_id', 'post', '');
                if (empty($save_article['cover']['video_id'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'body_id' => '',
                        'input' => 'cover_video_id',
                        'mess' => $nv_Lang->getModule('cover_video_id_empty')
                    ]);
                }

                if (!video_check($save_article['cover']['video_id'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'body_id' => '',
                        'input' => 'cover_video_id',
                        'mess' => $nv_Lang->getModule('cover_video_id_empty')
                    ]);
                }

                $save_article['cover']['cover_view'] = $nv_Request->get_title('cover_view', 'post', 'horizontal');
            }

            $related_medias = $nv_Request->get_typed_array('related_article', 'post', 'title', []);
            if (!empty($related_medias)) {
                $save_article['related_medias'] = [];
                foreach ($related_medias as $related_article) {
                    $save_article['related_medias'][] = $related_article;
                }
            }

            $tracking_link = $nv_Request->get_string('tracking_link', 'post', '');
            if (!empty($tracking_link) and nv_is_url($tracking_link)) {
                $save_article['tracking_link'] = $tracking_link;
            }
        }

        $save_article['status'] = $nv_Request->get_title('status', 'post', 'hide');
        $save_article['comment'] = $nv_Request->get_title('comment', 'post', 'hide');

        if ($type == 'normal') {
            $body_id = $nv_Request->get_typed_array('body_id', 'post', 'title', []);
            $body_type = $nv_Request->get_typed_array('body_type', 'post', 'title', []);
            $body_content = $nv_Request->get_typed_array('body_content', 'post', 'title', []);
            $body_photo_url = $nv_Request->get_typed_array('body_photo_url', 'post', 'title', []);
            $body_caption = $nv_Request->get_typed_array('body_caption', 'post', 'title', []);
            $body_video_type = $nv_Request->get_typed_array('body_video_type', 'post', 'title', []);
            $body_video_content = $nv_Request->get_typed_array('body_video_content', 'post', 'title', []);
            $body_thumb = $nv_Request->get_typed_array('body_thumb', 'post', 'title', []);
            $body_product_id = $nv_Request->get_typed_array('body_product_id', 'post', 'title', []);

            $save_article['body'] = [];
            foreach ($body_id as $key => $body) {
                if ($body_type[$key] == 'text') {
                    if (empty($body_content[$key])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => $body,
                            'input' => 'body_content',
                            'mess' => $nv_Lang->getModule('body_content_empty')
                        ]);
                    }
                    $save_article['body'][] = [
                        'type' => $body_type[$key],
                        'content' => $body_content[$key]
                    ];
                } elseif ($body_type[$key] == 'image') {
                    if (empty($body_photo_url[$key])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => $body,
                            'input' => 'body_photo_url',
                            'mess' => $nv_Lang->getModule('body_photo_url_empty')
                        ]);
                    }

                    if (!nv_is_url($body_photo_url[$key])) {
                        if ($is_localhost) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_photo_url',
                                'mess' => $nv_Lang->getModule('image_from_localhost')
                            ]);
                        }

                        if (!file_exists(NV_ROOTDIR . $body_photo_url[$key])) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_photo_url',
                                'mess' => $nv_Lang->getModule('body_photo_url_empty')
                            ]);
                        }

                        $body_photo_url[$key] = NV_MY_DOMAIN . $body_photo_url[$key];
                    }
                    $save_article['body'][] = [
                        'type' => $body_type[$key],
                        'url' => $body_photo_url[$key],
                        'caption' => $body_caption[$key]
                    ];
                } elseif ($body_type[$key] == 'video') {
                    if (empty($body_video_content[$key])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => $body,
                            'input' => 'body_video_content',
                            'mess' => $nv_Lang->getModule('body_video_content_empty')
                        ]);
                    }

                    if ($body_video_type[$key] == 'url') {
                        if (!nv_is_url($body_video_content[$key])) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_video_content',
                                'mess' => $nv_Lang->getModule('body_video_content_empty')
                            ]);
                        }
                    } else {
                        if (!video_check($body_video_content[$key])) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_video_content',
                                'mess' => $nv_Lang->getModule('body_video_content_empty')
                            ]);
                        }
                    }

                    $thumb = $body_thumb[$key];
                    if (empty($thumb)) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => $body,
                            'input' => 'body_thumb',
                            'mess' => $nv_Lang->getModule('body_thumb_empty')
                        ]);
                    }
                    if (!nv_is_url($thumb)) {
                        if ($is_localhost) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_thumb',
                                'mess' => $nv_Lang->getModule('image_from_localhost')
                            ]);
                        }

                        if (!file_exists(NV_ROOTDIR . $thumb)) {
                            nv_jsonOutput([
                                'status' => 'error',
                                'body_id' => $body,
                                'input' => 'body_thumb',
                                'mess' => $nv_Lang->getModule('body_thumb_empty')
                            ]);
                        }

                        $thumb = NV_MY_DOMAIN . $thumb;
                    }

                    if ($body_video_type[$key] == 'url') {
                        $save_article['body'][] = [
                            'type' => $body_type[$key],
                            'url' => $body_video_content[$key],
                            'thumb' => $thumb
                        ];
                    } else {
                        $save_article['body'][] = [
                            'type' => $body_type[$key],
                            'video_id' => $body_video_content[$key],
                            'thumb' => $thumb
                        ];
                    }
                } elseif ($body_type[$key] == 'product') {
                    if (empty($body_product_id[$key])) {
                        nv_jsonOutput([
                            'status' => 'error',
                            'body_id' => $body,
                            'input' => 'body_product_id',
                            'mess' => $nv_Lang->getModule('body_product_id_empty')
                        ]);
                    }

                    $save_article['body'][] = [
                        'type' => $body_type[$key],
                        'id' => $body_product_id[$key]
                    ];
                }
            }
        } else {
            $save_article['video_id'] = $nv_Request->get_title('video_id', 'post', '');
            if (empty($save_article['video_id'])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'body_id' => '',
                    'input' => 'video_id',
                    'mess' => $nv_Lang->getModule('video_id_empty')
                ]);
            }

            $save_article['avatar'] = $nv_Request->get_title('video_avatar', 'post', '');
            if (empty($save_article['avatar'])) {
                nv_jsonOutput([
                    'status' => 'error',
                    'body_id' => '',
                    'input' => 'video_avatar',
                    'mess' => $nv_Lang->getModule('video_avatar_empty')
                ]);
            }

            if (!nv_is_url($save_article['avatar'])) {
                if ($is_localhost) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'body_id' => '',
                        'input' => 'video_avatar',
                        'mess' => $nv_Lang->getModule('image_from_localhost')
                    ]);
                }

                if (!file_exists(NV_ROOTDIR . $save_article['avatar'])) {
                    nv_jsonOutput([
                        'status' => 'error',
                        'body_id' => '',
                        'input' => 'video_avatar',
                        'mess' => $nv_Lang->getModule('video_avatar_empty')
                    ]);
                }

                $save_article['avatar'] = NV_MY_DOMAIN . $save_article['avatar'];
            }
        }

        get_accesstoken($accesstoken, true);

        if ($action == 'add') {
            $result = $zalo->article_create($accesstoken, $save_article);
            if (empty($result)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => zaloGetError()
                ]);
            }

            $save_article = ['token' => $result['data']['token']] + $save_article;
            $id = save_article($save_article);
            sleep(3);
            $result = $zalo->get_article_id($accesstoken, $result['data']['token']);
            if (!empty($result['data']['id'])) {
                zalo_id_update($id, $result['data']['id']);
            }
        } else {
            $save_article = ['id' => $article['zalo_id']] + $save_article;
            $result = $zalo->article_update($accesstoken, $save_article);
            if (empty($result)) {
                nv_jsonOutput([
                    'status' => 'error',
                    'mess' => zaloGetError()
                ]);
            }

            update_article($id, $save_article);
        }

        nv_jsonOutput([
            'status' => 'success',
            'redirect' => str_replace('&amp;', '&', $list_url)
        ]);
    }

    if (empty($article['body'])) {
        $article['body'][] = [
            'body_type' => 'text',
            'body_content' => '',
            'body_photo_url' => '',
            'body_video_type' => 'url',
            'body_video_content' => '',
            'body_product_id' => '',
            'body_caption' => '',
            'body_thumb' => ''
        ];
    }

    $xtpl = new XTemplate('article.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('FORM_ACTION', $base_url);
    $xtpl->assign('LIST_LINK', $list_url);
    $xtpl->assign('COVER_VIDEO_GET_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=video&amp;popup=1&amp;idfield=cover_video_id&amp;viewfield=cover_view');
    $xtpl->assign('BODY_VIDEO_GET_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=video&amp;popup=1');
    $xtpl->assign('ARTICLE_VIDEO_GET_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=video&amp;popup=1&amp;idfield=video_id&amp;thumbfield=video_avatar');
    $xtpl->assign('RELATED_ARTICLE_LINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=article&amp;type=' . $type . '&amp;popup=1&amp;idfield=related_medias');

    $xtpl->assign('ARTICLE', $article);

    if ($action == 'add') {
        foreach ($article_types as $key => $name) {
            $xtpl->assign('TYPE', [
                'key' => $key,
                'sel' => ($key == $type) ? ' selected="selected"' : '',
                'url' => $page_url . '&amp;type=' . $key . '&amp;action=add',
                'name' => $name
            ]);
            $xtpl->parse('add.if_add.type');
        }

        $xtpl->parse('add.if_add');
    }

    if ($type == 'normal') {
        foreach ($cover_types as $key => $name) {
            $xtpl->assign('COVER_TYPE', [
                'key' => $key,
                'sel' => (!empty($article['cover_type']) and $key == $article['cover_type']) ? ' selected="selected"' : '',
                'name' => $name
            ]);
            $xtpl->parse('add.if_article_normal.cover_type');
        }

        foreach ($cover_view_types as $key => $name) {
            $xtpl->assign('COVER_VIEW', [
                'key' => $key,
                'sel' => $key == $article['cover_view'] ? ' selected="selected"' : '',
                'name' => $name
            ]);
            $xtpl->parse('add.if_article_normal.cover_view');
        }

        if ($article['cover_type'] == 'photo') {
            $xtpl->parse('add.if_article_normal.cover_video_id_hide');
            $xtpl->parse('add.if_article_normal.cover_view_hide');
        } else {
            $xtpl->parse('add.if_article_normal.cover_photo_url_hide');
        }

        if ($article['cover_status'] == 'show') {
            $xtpl->parse('add.if_article_normal.cover_status_1');
        } else {
            $xtpl->parse('add.if_article_normal.cover_status_0');
        }

        if (!empty($article['related_medias'])) {
            $related_medias = get_article_title_by_zalo_id($article['related_medias']);
            foreach ($related_medias as $zalo_id => $title) {
                $xtpl->assign('RELATED_ARTICLE', [
                    'zalo_id' => $zalo_id,
                    'title' => $title
                ]);
                $xtpl->parse('add.if_article_normal.related_article');
            }
        }

        $xtpl->parse('add.if_article_normal');

        foreach ($article['body'] as $key => $body) {
            $body['key'] = $key;
            $xtpl->assign('BODY', $body);

            foreach ($body_types as $key => $name) {
                $xtpl->assign('BODY_TYPE', [
                    'key' => $key,
                    'sel' => $key == $body['body_type'] ? ' selected="selected"' : '',
                    'name' => $name
                ]);
                $xtpl->parse('add.if_article_normal2.body.body_type');
            }

            if ($body['body_type'] == 'text') {
                $xtpl->parse('add.if_article_normal2.body.body_photo_url_hide');
                $xtpl->parse('add.if_article_normal2.body.body_caption_hide');
                $xtpl->parse('add.if_article_normal2.body.body_video_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_thumb_hide');
                $xtpl->parse('add.if_article_normal2.body.body_product_id_hide');
            } elseif ($body['body_type'] == 'image') {
                $xtpl->parse('add.if_article_normal2.body.body_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_video_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_thumb_hide');
                $xtpl->parse('add.if_article_normal2.body.body_product_id_hide');
            } elseif ($body['body_type'] == 'video') {
                $xtpl->parse('add.if_article_normal2.body.body_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_photo_url_hide');
                $xtpl->parse('add.if_article_normal2.body.body_caption_hide');
                $xtpl->parse('add.if_article_normal2.body.body_product_id_hide');
            } elseif ($body['body_type'] == 'product') {
                $xtpl->parse('add.if_article_normal2.body.body_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_photo_url_hide');
                $xtpl->parse('add.if_article_normal2.body.body_caption_hide');
                $xtpl->parse('add.if_article_normal2.body.body_video_content_hide');
                $xtpl->parse('add.if_article_normal2.body.body_thumb_hide');
            }

            foreach ($body_video_types as $key => $name) {
                $xtpl->assign('BODY_VIDEO_TYPE', [
                    'key' => $key,
                    'sel' => $key == $body['body_video_type'] ? ' selected="selected"' : '',
                    'name' => $name
                ]);
                $xtpl->parse('add.if_article_normal2.body.body_video_type');
            }

            if ($body['body_video_type'] == 'url') {
                $xtpl->parse('add.if_article_normal2.body.body_video_get_disabled');
            }

            $xtpl->parse('add.if_article_normal2.body');
        }

        $xtpl->parse('add.if_article_normal2');
    } else {
        $xtpl->parse('add.if_article_video');
    }

    if ($article['status'] == 'show') {
        $xtpl->parse('add.article_status_show');
    } else {
        $xtpl->parse('add.article_status_hide');
    }

    if ($article['comment'] == 'show') {
        $xtpl->parse('add.article_comment_show');
    } else {
        $xtpl->parse('add.article_comment_hide');
    }

    $xtpl->parse('add');
    $contents = $xtpl->text('add');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 10;

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_MOD_TABLE . '_article');
if (!empty($type)) {
    $db->where("type='" . $type . "'");
}

$num_items = $db->query($db->sql())->fetchColumn();

if ($page < 1 or ($page > 1 and $page > ceil($num_items / $per_page))) {
    nv_redirect_location($base_url);
}

$db->select('*')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page)
    ->order('create_date DESC');
$result = $db->query($db->sql());

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$xtpl = new XTemplate('article.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
$xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
$xtpl->assign('FORM_ACTION', $page_url);
$xtpl->assign('ADD_LINK', $base_url . '&amp;action=add');
$xtpl->assign('IDFIELD', $idfield);

if ($popup) {
    $xtpl->parse('main.popup');
} else {
    foreach ($article_types as $key => $name) {
        $xtpl->assign('TYPE', [
            'key' => $key,
            'sel' => (!empty($type) and $key == $type) ? ' selected="selected"' : '',
            'name' => $name
        ]);
        $xtpl->parse('main.if_not_popup.type');
    }

    $xtpl->parse('main.if_not_popup');
}

if ($num_items) {
    if (!$popup) {
        $xtpl->parse('main.isArticles.if_not_popup');
    }

    $get_zalo_id = [];

    while ($row = $result->fetch()) {
        $row['create_date_format'] = nv_date('d/m/Y H:i', $row['create_date']);
        $row['type_format'] = $nv_Lang->getModule('article_' . $row['type']);
        $row['zalo_url'] = !empty($row['zalo_id']) ? 'https://rd.zapps.vn/' . ($row['type'] == 'normal' ? 'detail' : 'video') . '/' . $global_config['zaloOfficialAccountID'] . '?id=' . $row['zalo_id'] . '&pageId=' . $global_config['zaloOfficialAccountID'] : '';
        $xtpl->assign('ARTICLE', $row);

        $remainder = 3600 - NV_CURRENTTIME + $row['create_date'];

        if (!empty($row['zalo_id'])) {
            if ($row['status'] == 'show') {
                $xtpl->parse('main.isArticles.article.zalo_id.view');
            }
            $xtpl->parse('main.isArticles.article.zalo_id');
        } elseif (!empty($row['token']) and $remainder > 0) {
            $xtpl->parse('main.isArticles.article.get_zalo_id');

            empty($get_zalo_id) && $get_zalo_id = [
                'note' => $nv_Lang->getModule('get_zalo_id_note', $row['title'], nv_convertfromSec($remainder)),
                'id' => $row['id']
            ];
        } else {
            $xtpl->parse('main.isArticles.article.not_defined');
        }

        $xtpl->parse('main.isArticles.article.type_' . $row['type']);

        if (!$popup) {
            if (!empty($row['zalo_id'])) {
                $xtpl->parse('main.isArticles.article.if_not_popup');
            }
        } else {
            if (!empty($row['zalo_id']) and !in_array($row['zalo_id'], $currentid, true)) {
                $xtpl->parse('main.isArticles.article.if_popup');
            }
        }

        $xtpl->parse('main.isArticles.article');
    }

    if (!empty($get_zalo_id)) {
        $xtpl->assign('GET_ZALO_ID', $get_zalo_id);
        $xtpl->parse('main.isArticles.get_zalo_id_Modal');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.isArticles.generate_page');
    }

    $xtpl->parse('main.isArticles');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, !$popup);
include NV_ROOTDIR . '/includes/footer.php';
