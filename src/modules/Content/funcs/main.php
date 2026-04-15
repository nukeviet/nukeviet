<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * Thin Controller — chỉ 3 nhiệm vụ:
 * 1. Nhận Request (qua Service.resolveRoute)
 * 2. Gọi Service xử lý dữ liệu
 * 3. Đẩy sang View (theme.php)
 */

if (!defined('NV_IS_MOD_CONTENT')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Content\ContentService;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Cat\CatService;
use NukeViet\Module\Content\Shared\SchemaHelper;

$contentRepo = new ContentRepository($db, $tables, $nv_Cache, $module_name);

$service = new ContentService($contentRepo);
$catService = new CatService(new CatRepository($db, $tables, $nv_Cache, $module_name));
$page_url = $base_url;

// 1. Nhận Request — Service xử lý URL parsing
$viewtype = (int) ($config['viewtype'] ?? 0);
$route = $service->resolveRoute($array_op, $viewtype);

if ($route['mode'] === 'none') {
    // viewtype = 2: không hiển thị gì
    $canonicalUrl = getCanonicalUrl($page_url);
    $page_title = $module_info['site_title'];
    $key_words = $module_info['keywords'];
    $contents = '';
} elseif ($route['mode'] === 'detail') {
    // === CHI TIẾT BÀI VIẾT ===
    $rowdetail = $route['row'];
    $id = $route['id'];

    // Bài viết ngưng hoạt động chỉ quản trị mới xem được
    if (empty($rowdetail->status) and !defined('NV_IS_MODADMIN')) {
        throw new \Exception('Bài viết không tồn tại hoặc đã bị ẩn', 404);
    }

    // Nhúng Relationship Entity
    if ($rowdetail->catid > 0) {
        $rowdetail->category = $catService->getDetail($rowdetail->catid);
    }

    $page_url .= '&amp;' . NV_OP_VARIABLE . '=' . $rowdetail->alias . $global_config['rewrite_exturl'];
    $canonicalUrl = getCanonicalUrl($page_url);
    $page_title = $rowdetail->title;

    // Thông tin thời gian cho hiển thị và Schema
    $rowdetail->number_add_time = $rowdetail->add_time;
    $rowdetail->number_edit_time = $rowdetail->edit_time ?: $rowdetail->add_time;

    // Schema.org
    $schema_types = SchemaHelper::$schema_types;
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => $schema_types[$rowdetail->schema_type] ?? 'Article',
        'description' => strip_tags($rowdetail->description) ?: $rowdetail->title,
    ];
    $is_webpage = $rowdetail->schema_type === 'webpage';
    if ($is_webpage) {
        $schema['name'] = $rowdetail->title;
        $schema['inLanguage'] = NV_LANG_DATA;
        $schema['about'] = [
            '@type' => 'Thing',
            'name' => $rowdetail->schema_about ?: 'Organization',
        ];
        $schema['url'] = $canonicalUrl;
    } else {
        $schema['headline'] = $rowdetail->title;
        $schema['mainEntityOfPage'] = $canonicalUrl;
        $schema['datePublished'] = date('c', $rowdetail->number_add_time);
        $schema['dateModified'] = date('c', $rowdetail->number_edit_time);
    }

    // Xử lý ảnh
    if (!empty($rowdetail->image)) {
        if (!nv_is_url($rowdetail->image)) {
            $imagesize = @getimagesize(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowdetail->image);
            $meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowdetail->image;
            $meta_property['og:image:url'] = $meta_property['og:image'];
            $meta_property['og:image:type'] = $imagesize['mime'];
            $meta_property['og:image:width'] = $imagesize[0];
            $meta_property['og:image:height'] = $imagesize[1];
            $meta_property['og:image:alt'] = !empty($rowdetail->imagealt) ? $rowdetail->imagealt : $rowdetail->title;
            !$is_webpage && $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => $meta_property['og:image']
            ];

            $srcset = '';
            if (nv_is_file(NV_BASE_SITEURL . '/' . NV_MOBILE_FILES_DIR . '/' . $module_upload . '/' . $rowdetail->image, NV_MOBILE_FILES_DIR . '/' . $module_upload)) {
                $srcset = NV_BASE_SITEURL . NV_MOBILE_FILES_DIR . '/' . $module_upload . '/' . $rowdetail->image . ' ' . NV_MOBILE_MODE_IMG . 'w, ';
                $srcset .= NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowdetail->image . ' ' . $imagesize[0] . 'w';
            }

            $rowdetail->thumb = [
                'src' => nv_is_file(NV_BASE_SITEURL . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $rowdetail->image, NV_FILES_DIR . '/' . $module_upload)
                    ? NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $rowdetail->image
                    : NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowdetail->image,
                'width' => 100
            ];
            $rowdetail->img = [
                'src' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowdetail->image,
                'srcset' => $srcset,
                'width' => $imagesize[0] > 500 ? 500 : $imagesize[0]
            ];
        } else {
            $rowdetail->thumb = ['src' => $rowdetail->image, 'width' => 100];
            $rowdetail->img = ['src' => $rowdetail->image, 'srcset' => '', 'width' => 500];
            !$is_webpage && $schema['image'] = ['@type' => 'ImageObject', 'url' => $rowdetail->image];
        }
    }

    // Schema Organization
    $schema_org = [
        '@type' => 'Organization',
        'name' => $global_config['site_name'],
        'url' => NV_MY_DOMAIN,
    ];
    if (!empty($global_config['site_logo'])) {
        $schema_org['logo'] = [
            '@type' => 'ImageObject',
            'url' => NV_MY_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']
        ];
    }
    $rowdetail->schema_type !== 'BlogPosting' && $schema['publisher'] = $schema_org;
    !$is_webpage && $schema['author'] = $schema_org;

    // Keywords
    if (!empty($rowdetail->keywords)) {
        $key_words = $rowdetail->keywords;
    } else {
        $key_words = nv_get_keywords($rowdetail->bodytext);
        if (empty($key_words)) {
            $key_words = nv_unhtmlspecialchars($rowdetail->title);
            $key_words = strip_punctuation($key_words);
            $key_words = trim($key_words);
            $key_words = nv_strtolower($key_words);
            $key_words = preg_replace('/[ ]+/', ',', $key_words);
        }
    }

    $description = $rowdetail->description;

    // Format thời gian để hiển thị
    $rowdetail->add_time_formatted = nv_datetime_format($rowdetail->add_time, 1, 0);
    $rowdetail->edit_time_formatted = nv_datetime_format($rowdetail->edit_time, 1, 0);
    $rowdetail->link = $canonicalUrl;

    // Layout func
    $module_info['layout_funcs'][$op_file] = !empty($rowdetail->layout_func) ? $rowdetail->layout_func : $module_info['layout_funcs'][$op_file];

    // Bài liên quan
    $other_links = [];
    $related_articles = (int) ($config['related_articles'] ?? 0);
    if ($related_articles) {
        $related = $contentRepo->getRelated($id, $related_articles, $rowdetail->catid);
        foreach ($related as $other) {
            $other->link = $base_url . '&amp;' . NV_OP_VARIABLE . '=' . $other->alias . $global_config['rewrite_exturl'];
            $other_links[$other->id] = $other;
        }
    }

    // Bình luận
    $content_comment = '';
    if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm'])) {
        define('NV_COMM_ID', $id);
        define('NV_COMM_AREA', $module_info['funcs'][$op]['func_id']);
        $allowed = $module_config[$module_name]['allowed_comm'];
        if ($allowed == '-1') {
            $allowed = $rowdetail->activecomm;
        }
        require_once NV_ROOTDIR . '/modules/comment/comment.php';
        $area = defined('NV_COMM_AREA') ? NV_COMM_AREA : 0;
        $checkss = md5($module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX);
        $content_comment = nv_comment_module($module_name, $checkss, $area, NV_COMM_ID, $allowed, 1);
    }

    // Lượt xem (chống đếm trùng qua session)
    $time_set = $nv_Request->get_int($module_data . '_' . $op . '_' . $id, 'session');
    if (empty($time_set)) {
        $nv_Request->set_Session($module_data . '_' . $op . '_' . $id, NV_CURRENTTIME);
        $contentRepo->incrementHits($id);
    }

    // Hook
    [$rowdetail, $other_links, $content_comment] = nv_apply_hook(
        $module_name,
        'before_detail_theme',
        [$rowdetail, $other_links, $content_comment],
        [$rowdetail, $other_links, $content_comment]
    );

    $nv_schemas[] = $schema;

    // 3. Đẩy sang View
    $contents = nv_content_detail($rowdetail, $other_links, $content_comment);
} else {
    // === DANH SÁCH ===
    $page = $route['page'];
    if ($page > 1) {
        $page_url .= '&amp;' . NV_OP_VARIABLE . '=page-' . $page;
    }
    $canonicalUrl = getCanonicalUrl($page_url);

    $page_title = $module_info['site_title'];
    $key_words = $module_info['keywords'];
    $per_page = (int) ($config['per_page'] ?? 20);

    // 2. Gọi Service
    $result = $service->getList($page, $per_page);
    $num_items = $result['total'];

    betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);
    $array_data = $service->buildItemLinks($result['items'], $base_url, $global_config['rewrite_exturl']);

    $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

    if ($page > 1) {
        $page_title .= NV_TITLEBAR_DEFIS . $nv_Lang->getGlobal('page') . ' ' . $page;
    }

    // Gán relationship chủ đề cho mảng danh sách
    foreach ($array_data as $item) {
        if ($item->catid > 0) {
            $item->category = clone $catService->getDetail($item->catid);
        }
    }

    // 3. Đẩy sang View
    $contents = nv_content_list($array_data, $generate_page);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
