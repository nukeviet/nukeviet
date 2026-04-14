<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_CONTENT')) {
    exit('Stop!!!');
}

/**
 * nv_content_detail() — Hiển thị chi tiết bài viết (NVSmarty)
 *
 * @param object $row ContentEntity
 * @param array  $other_links Bài viết liên quan
 * @param string $content_comment HTML bình luận
 * @return string
 */
function nv_content_detail($row, array $other_links, string $content_comment): string
{
    global $module_name, $config;

    // Gọi toArray() để biến Entity thành Array, tương thích Smarty
    $row_array = $row->toArray();

    // Admin edit link
    if (defined('NV_IS_MODADMIN')) {
        $row_array['adminlink'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE
            . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name
            . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row->id;
        if (!$row->status) {
            $row_array['is_inactive'] = true;
        }
    } elseif (!$row->status) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
            . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
    $tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $tpl->assign('CONTENT', $row_array);
    $tpl->assign('OTHER_LINKS', array_map(fn($e) => $e->toArray(), $other_links));
    $tpl->assign('CONTENT_COMMENT', $content_comment);
    $tpl->assign('SOCIAL_CONFIG', $config['socialbutton'] ?? '');

    return $tpl->fetch('detail.tpl');
}

/**
 * nv_content_list() — Hiển thị danh sách bài viết (NVSmarty)
 *
 * @param array  $array_data Mảng các ContentEntity đã được set link
 * @param string $generate_page HTML phân trang
 * @return string
 */
function nv_content_list(array $array_data, string $generate_page): string
{
    global $module_upload;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main_list.tpl'));
    $tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $tpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    // Chuẩn bị data cho Smarty: chuỗi hóa Entity thành Array, xử lý thumbnail
    $list_array = [];
    foreach ($array_data as $entity) {
        $row = $entity->toArray();
        if (!empty($row['image'])) {
            if (nv_is_file(NV_BASE_SITEURL . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $row['image'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'];
            } elseif (nv_is_file(NV_BASE_SITEURL . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'], NV_UPLOADS_DIR . '/' . $module_upload)) {
                $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
            } else {
                $row['image'] = '';
            }
            $row['imagealt'] = !empty($row['imagealt']) ? $row['imagealt'] : $row['title'];
        }
        if (empty($row['description'])) {
            $row['description'] = nv_clean60(strip_tags(trim($row['bodytext'])), 300);
        } else {
            $row['description'] = nv_clean60($row['description'], 300);
        }
        $list_array[] = $row;
    }

    $tpl->assign('DATA', $list_array);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('main_list.tpl');
}
