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

/**
 * Lấy nút xóa bài viết
 *
 * @param array $info cần có ít nhất id, và listcatid
 * @param int $detail
 * @param bool $link_only
 * @return string|array
 */
function nv_link_delete_page(array $info, int $detail = 0, bool $link_only = false)
{
    global $nv_Lang, $admin_info, $module_name;

    if (!nv_check_delete_page($info)) {
        return $link_only ? [] : '';
    }
    $link_info = [
        'id' => $info['id'],
        'checkss' => csrf_create($admin_info['admin_id'] . '_' . $module_name . '_' . $info['id']),
        'detail' => $detail
    ];
    if ($link_only) {
        return $link_info;
    }

    $link = '<a class="btn btn-danger" href="#" data-toggle="nv_del_content" data-id="' . $info['id'] . '" data-checkss="' . $link_info['checkss'] . '" data-adminurl="' . NV_BASE_ADMINURL . '" data-detail="' . $detail . '"><i class="fa-solid fa-trash"></i> ' . $nv_Lang->getGlobal('delete') . '</a>';
    return $link;
}

/**
 * Lấy HTML nút sửa bài viết hoặc link sửa bài viết nếu có quyền
 * không có quyền trả về chuỗi rỗng
 *
 * @param array $info cần có ít nhất id, và listcatid
 * @param bool $link_only
 * @return string
 */
function nv_link_edit_page(array $info, bool $link_only = false)
{
    global $nv_Lang, $module_name;

    if (!nv_check_edit_page($info)) {
        return '';
    }

    $link = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $info['id'];
    if ($link_only) {
        return $link;
    }

    return '<a class="btn btn-primary" href="' . $link . '"><i class="fa-solid fa-pencil"></i> ' . $nv_Lang->getGlobal('edit') . '</a>';
}

/**
 * viewcat_grid_new()
 *
 * @param array  $array_catpage
 * @param int    $catid
 * @param string $generate_page
 * @return string
 */
function viewcat_grid_new($array_catpage, $catid, $generate_page)
{
    global $site_mods, $module_name, $module_upload, $module_config, $global_array_cat, $global_array_cat, $catid, $page, $home;

    $xtpl = new XTemplate('viewcat_grid.tpl', get_module_tpl_dir('viewcat_grid.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    } elseif (!$home) {
        $xtpl->assign('PAGE_TITLE', nv_html_page_title(false));
        $xtpl->parse('main.h1');
    }

    if (!empty($catid)) {
        $xtpl->assign('CAT', $global_array_cat[$catid]);
        $xtpl->parse('main.cattitle');
    }

    $a = 0;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_datetime_format($array_row_i['publtime']);

        if ($array_row_i['external_link']) {
            $array_row_i['target_blank'] = 'target="_blank"';
        }

        $xtpl->clear_autoreset();
        if ($module_config[$module_name]['showtooltip']) {
            $array_row_i['hometext_clean'] = nv_clean60($array_row_i['hometext'], $module_config[$module_name]['tooltip_length'], true);
        }
        $xtpl->assign('CONTENT', $array_row_i);

        ++$a;
        if ($a == 1) {
            if (defined('NV_IS_MODADMIN')) {
                $adminlink = trim(nv_link_edit_page($array_row_i) . ' ' . nv_link_delete_page($array_row_i));
                if (!empty($adminlink)) {
                    $xtpl->assign('ADMINLINK', $adminlink);
                    $xtpl->parse('main.featuredloop.adminlink');
                }
            }

            if ($array_row_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
                $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                $xtpl->parse('main.featuredloop.image');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.featuredloop.newday');
            }

            if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                $xtpl->parse('main.featuredloop.comment');
            }

            $xtpl->set_autoreset();
            $xtpl->parse('main.featuredloop');
        } else {
            if ($module_config[$module_name]['showtooltip']) {
                $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);
                $xtpl->parse('main.viewcatloop.tooltip');
            }

            if (defined('NV_IS_MODADMIN')) {
                $adminlink = trim(nv_link_edit_page($array_row_i) . ' ' . nv_link_delete_page($array_row_i));
                if (!empty($adminlink)) {
                    $xtpl->assign('ADMINLINK', $adminlink);
                    $xtpl->parse('main.viewcatloop.adminlink');
                }
            }

            if ($array_row_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
                $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                $xtpl->parse('main.viewcatloop.image');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.viewcatloop.newday');
            }

            $xtpl->set_autoreset();
            $xtpl->parse('main.viewcatloop');
        }
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * viewcat_list_new()
 *
 * @param array  $array_catpage
 * @param int    $catid
 * @param int    $page
 * @param string $generate_page
 * @return string
 */
function viewcat_list_new($array_catpage, $catid, $page, $generate_page)
{
    global $module_name, $module_upload, $module_config, $global_array_cat, $home;

    $xtpl = new XTemplate('viewcat_list.tpl', get_module_tpl_dir('viewcat_list.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 0) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    } elseif (!$home) {
        $xtpl->assign('PAGE_TITLE', nv_html_page_title(false));
        $xtpl->parse('main.h1');
    }

    $a = $page;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_datetime_format($array_row_i['publtime']);

        if ($module_config[$module_name]['showtooltip']) {
            $array_row_i['hometext_clean'] = nv_clean60(strip_tags($array_row_i['hometext']), $module_config[$module_name]['tooltip_length'], true);
        }

        if ($array_row_i['external_link']) {
            $array_row_i['target_blank'] = 'target="_blank"';
        }

        $xtpl->clear_autoreset();
        $xtpl->assign('NUMBER', ++$a);
        $xtpl->assign('CONTENT', $array_row_i);

        if ($module_config[$module_name]['showtooltip']) {
            $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);
            $xtpl->parse('main.viewcatloop.tooltip');
        }

        if (defined('NV_IS_MODADMIN')) {
            $adminlink = trim(nv_link_edit_page($array_row_i) . ' ' . nv_link_delete_page($array_row_i));
            if (!empty($adminlink)) {
                $xtpl->assign('ADMINLINK', $adminlink);
                $xtpl->parse('main.viewcatloop.adminlink');
            }
        }

        if ($array_row_i['imghome'] != '') {
            $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
            $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
            $xtpl->parse('main.viewcatloop.image');
        }

        if ($newday >= NV_CURRENTTIME) {
            $xtpl->parse('main.viewcatloop.newday');
        }

        $xtpl->set_autoreset();
        $xtpl->parse('main.viewcatloop');
    }
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * viewcat_page_new()
 *
 * @param array  $array_catpage
 * @param array  $array_cat_other
 * @param string $generate_page
 * @return string
 */
function viewcat_page_new($array_catpage, $array_cat_other, $generate_page)
{
    global $site_mods, $global_array_cat, $module_name, $module_upload, $nv_Lang, $module_config, $catid, $page, $home;

    $xtpl = new XTemplate('viewcat_page.tpl', get_module_tpl_dir('viewcat_page.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    } elseif (!$home) {
        $xtpl->assign('PAGE_TITLE', nv_html_page_title(false));
        $xtpl->parse('main.h1');
    }

    $a = 0;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_datetime_format($array_row_i['publtime']);
        $array_row_i['listcatid'] = explode(',', $array_row_i['listcatid']);
        $num_cat = count($array_row_i['listcatid']);

        $n = 1;
        foreach ($array_row_i['listcatid'] as $listcatid) {
            $listcat = [
                'title' => $global_array_cat[$listcatid]['title'],
                'link' => $global_array_cat[$listcatid]['link']
            ];
            $xtpl->assign('CAT', $listcat);
            (($n < $num_cat) ? $xtpl->parse('main.viewcatloop.cat.comma') : '');
            $xtpl->parse('main.viewcatloop.cat');
            ++$n;
        }

        if ($a == 0) {
            $xtpl->clear_autoreset();

            if ($array_row_i['external_link']) {
                $array_row_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('CONTENT', $array_row_i);

            if (defined('NV_IS_MODADMIN')) {
                $adminlink = trim(nv_link_edit_page($array_row_i) . ' ' . nv_link_delete_page($array_row_i));
                if (!empty($adminlink)) {
                    $xtpl->assign('ADMINLINK', $adminlink);
                    $xtpl->parse('main.viewcatloop.featured.adminlink');
                }
            }

            if ($array_row_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
                $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                $xtpl->parse('main.viewcatloop.featured.image');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.viewcatloop.featured.newday');
            }

            if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                $xtpl->parse('main.viewcatloop.featured.comment');
            }

            $xtpl->parse('main.viewcatloop.featured');
        } else {
            $xtpl->clear_autoreset();

            if ($array_row_i['external_link']) {
                $array_row_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('CONTENT', $array_row_i);

            if (defined('NV_IS_MODADMIN')) {
                $adminlink = trim(nv_link_edit_page($array_row_i) . ' ' . nv_link_delete_page($array_row_i));
                if (!empty($adminlink)) {
                    $xtpl->assign('ADMINLINK', $adminlink);
                    $xtpl->parse('main.viewcatloop.news.adminlink');
                }
            }

            if ($array_row_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG1', $array_row_i['imghome']);
                $xtpl->assign('HOMEIMGALT1', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                $xtpl->parse('main.viewcatloop.news.image');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.viewcatloop.news.newday');
            }

            if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                $xtpl->parse('main.viewcatloop.news.comment');
            }

            $xtpl->set_autoreset();
            $xtpl->parse('main.viewcatloop.news');
        }
        ++$a;
    }
    $xtpl->parse('main.viewcatloop');

    if (!empty($array_cat_other)) {
        $xtpl->assign('ORTHERNEWS', $nv_Lang->getModule('other'));

        foreach ($array_cat_other as $array_row_i) {
            $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
            $array_row_i['publtime'] = nv_date_format(1, $array_row_i['publtime']);

            if ($array_row_i['external_link']) {
                $array_row_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('RELATED', $array_row_i);

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.related.loop.newday');
            }
            $xtpl->parse('main.related.loop');
        }

        $xtpl->parse('main.related');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * Phần mở đầu của chuyên mục đối với kiểu hiển thị theo chuyên mục, tin khác
 * nằm phải (end), trái (start), dưới
 *
 * @param array  $array_catcontent
 * @param string $generate_page
 * @return string
 */
function viewcat_top($array_catcontent, $generate_page)
{
    global $site_mods, $module_name, $module_upload, $module_config, $global_array_cat, $catid, $page, $home;

    $xtpl = new XTemplate('viewcat_top.tpl', get_module_tpl_dir('viewcat_top.tpl'));
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('IMGWIDTH0', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    } elseif (!$home) {
        $xtpl->assign('PAGE_TITLE', nv_html_page_title(false));
        $xtpl->parse('main.h1');
    }

    // Cac bai viet phan dau
    if (!empty($array_catcontent)) {
        $a = 0;
        foreach ($array_catcontent as $array_catcontent_i) {
            $newday = $array_catcontent_i['publtime'] + (86400 * $array_catcontent_i['newday']);
            $array_catcontent_i['publtime'] = nv_datetime_format($array_catcontent_i['publtime']);

            if ($array_catcontent_i['external_link']) {
                $array_catcontent_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('CONTENT', $array_catcontent_i);

            if ($a == 0) {
                if ($array_catcontent_i['imghome'] != '') {
                    $xtpl->assign('HOMEIMG0', $array_catcontent_i['imghome']);
                    $xtpl->assign('HOMEIMGALT0', $array_catcontent_i['homeimgalt']);
                    $xtpl->parse('main.catcontent.image');
                }

                if (defined('NV_IS_MODADMIN')) {
                    $adminlink = trim(nv_link_edit_page($array_catcontent_i) . ' ' . nv_link_delete_page($array_catcontent_i));
                    if (!empty($adminlink)) {
                        $xtpl->assign('ADMINLINK', $adminlink);
                        $xtpl->parse('main.catcontent.adminlink');
                    }
                }
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.catcontent.newday');
                }
                if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                    $xtpl->parse('main.catcontent.comment');
                }
                $xtpl->parse('main.catcontent');
            } else {
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.catcontentloop.newday');
                }
                $xtpl->parse('main.catcontentloop');
            }
            ++$a;
        }
    }

    // Het cac bai viet phan dau
    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * Giao diện tin theo chuyên mục, tin khác nằm phải (end), trái (start), dưới
 *
 * @param string $viewcat Một trong các giá trị sau:
 *   - "viewcat_main_left"
 *   - "viewcat_main_right"
 *   - "viewcat_main_bottom"
 * @param array  $array_cat
 * @param array  $array_articles
 * @param string  $generate_page
 * @return string
 */
function viewsubcat_main($viewcat, $array_cat, $array_articles = [], $generate_page = '')
{
    global $module_name, $site_mods, $nv_Lang, $module_config, $home, $op, $catid, $global_array_cat, $page, $module_upload;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir($viewcat . '.tpl'));
    $tpl->registerPlugin('modifier', 'ddate', 'nv_date_format');
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->registerPlugin('modifier', 'editAllowed', 'nv_link_edit_page');
    $tpl->registerPlugin('modifier', 'deleteAllowed', 'nv_link_delete_page');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('HOME', $home);
    $tpl->assign('PAGE_TITLE', nv_html_page_title(false));
    $tpl->assign('OP', $op);
    $tpl->assign('MODULE_UPLOAD', $module_upload);
    $tpl->assign('MCONFIG', $module_config[$module_name]);
    $tpl->assign('COMMENT_ENABLED', (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']));

    $imgratio = round(($module_config[$module_name]['homewidth'] / ($module_config[$module_name]['homeheight'] ?: $module_config[$module_name]['homewidth'])) * 100, 2);
    $tpl->assign('IMGRATIO', $imgratio);

    $tpl->assign('ARRAY_CATS', $array_cat);
    $tpl->assign('HTML_POSTS', empty($array_articles) ? '' : viewcat_top($array_articles, $generate_page));

    // Hiển thị mô tả chuyên mục
    $show_description = ($catid and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2));
    $tpl->assign('SHOW_DESCRIPTION', $show_description);
    if ($show_description) {
        $tpl->assign('INFO_CAT', $global_array_cat[$catid]);
    }

    return $tpl->fetch($viewcat . '.tpl');
}

/**
 * Xem theo chuyên mục thành 2 cột
 *
 * @param array $array_content Danh sách bài viết thuộc chuyên mục nếu có
 * @param string $generate_page
 * @param array $array_catpage Danh sách chuyên mục con
 * @return string
 */
function viewcat_two_column($array_content, $generate_page, $array_catpage)
{
    global $site_mods, $module_name, $module_config, $nv_Lang, $home, $op, $module_upload, $catid, $global_array_cat, $page;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('viewcat_two_column.tpl'));
    $tpl->registerPlugin('modifier', 'ddate', 'nv_date_format');
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->registerPlugin('modifier', 'editAllowed', 'nv_link_edit_page');
    $tpl->registerPlugin('modifier', 'deleteAllowed', 'nv_link_delete_page');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('HOME', $home);
    $tpl->assign('PAGE_TITLE', nv_html_page_title(false));
    $tpl->assign('OP', $op);
    $tpl->assign('MODULE_UPLOAD', $module_upload);
    $tpl->assign('MCONFIG', $module_config[$module_name]);
    $tpl->assign('COMMENT_ENABLED', (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']));

    $imgratio = round(($module_config[$module_name]['homewidth'] / ($module_config[$module_name]['homeheight'] ?: $module_config[$module_name]['homewidth'])) * 100, 2);
    $tpl->assign('IMGRATIO', $imgratio);

    $tpl->assign('ARRAY_CATS', $array_catpage);
    $tpl->assign('HTML_POSTS', empty($array_content) ? '' : viewcat_top($array_content, $generate_page));

    // Hiển thị mô tả chuyên mục
    $show_description = ($catid and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2));
    $tpl->assign('SHOW_DESCRIPTION', $show_description);
    if ($show_description) {
        $tpl->assign('INFO_CAT', $global_array_cat[$catid]);
    }

    return $tpl->fetch('viewcat_two_column.tpl');
}

/**
 * Giao diện chi tiết bài viết
 *
 * @param array  $news_contents
 * @param array  $array_keyword
 * @param array  $related_new_array
 * @param array  $related_array
 * @param array  $topic_array
 * @param string $content_comment
 * @return string
 */
function detail_theme($news_contents, $array_keyword, $related_new_array, $related_array, $topic_array, $content_comment)
{
    global $nv_Lang, $module_name, $module_config, $meta_property;

    $mconfig = $module_config[$module_name];

    // Tóm tắt hiển thị ở tooltip của các danh sách tin liên quan
    $prepareArticles = function (array $articles) use ($mconfig) {
        foreach ($articles as $key => $article) {
            $articles[$key]['hometext_clean'] = empty($mconfig['showtooltip']) ? '' : nv_clean60(strip_tags($article['hometext']), $mconfig['tooltip_length'], true);
        }
        return $articles;
    };

    // Từ khóa của bài viết
    $keywords = [];
    foreach ($array_keyword as $value) {
        $keywords[] = [
            'keyword' => $value['keyword'],
            'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . urlencode($value['alias'])
        ];
    }

    // Các nút chia sẻ mạng xã hội
    $socials = [
        'facebook' => false,
        'twitter' => false
    ];
    if (!empty($mconfig['socialbutton'])) {
        if (str_contains($mconfig['socialbutton'], 'facebook')) {
            if (!empty($mconfig['facebookappid'])) {
                $meta_property['fb:app_id'] = $mconfig['facebookappid'];
                $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
            }
            $socials['facebook'] = true;
        }
        $socials['twitter'] = str_contains($mconfig['socialbutton'], 'twitter');
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
    $tpl->registerPlugin('modifier', 'ddate', 'nv_date_format');
    $tpl->registerPlugin('modifier', 'editAllowed', 'nv_link_edit_page');
    $tpl->registerPlugin('modifier', 'deleteAllowed', 'nv_link_delete_page');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('MCONFIG', $mconfig);
    $tpl->assign('DETAIL', $news_contents);
    $tpl->assign('KEYWORDS', $keywords);
    $tpl->assign('SOCIALS', $socials);
    $tpl->assign('RELATED_ARTICLES', $prepareArticles($news_contents['related_articles']));
    $tpl->assign('RELATED_NEW', $prepareArticles($related_new_array));
    $tpl->assign('RELATED', $prepareArticles($related_array));
    $tpl->assign('TOPICS', $prepareArticles($topic_array));
    $tpl->assign('CONTENT_COMMENT', $content_comment);

    $contents = $tpl->fetch('detail.tpl');
    if (!empty($mconfig['report_active']) and (!empty($mconfig['report_group']) and nv_user_in_groups($mconfig['report_group']))) {
        $contents .= theme_report($news_contents['id'], $news_contents['newscheckss']);
    }

    return $contents;
}

/**
 * Giao diện báo lỗi bài viết
 *
 * @param mixed $newsid
 * @param mixed $newscheckss
 * @return string
 */
function theme_report($newsid, $newscheckss)
{
    global $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('report.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('NEWSID', $newsid);
    $tpl->assign('NEWSCHECKSS', $newscheckss);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('captcha'));

    return $tpl->fetch('report.tpl');
}

/**
 * Giao diện bài viết trong dòng sự kiện
 *
 * @param array  $topic_array
 * @param array  $topic_other_array
 * @param string $generate_page
 * @param string $page_title
 * @param string $description
 * @param mixed  $topic_image
 * @return string
 */
function topic_theme($topic_array, $topic_other_array, $generate_page, $page_title, $description, $topic_image)
{
    return list_articles_theme([
        'title' => $page_title,
        'image' => $topic_image,
        'description' => $description,
        'list_title' => ''
    ], $topic_array, $topic_other_array, $generate_page);
}

/**
 * Giao diện bài viết của tác giả
 *
 * @param array  $author_info
 * @param array  $topic_array
 * @param array  $topic_other_array
 * @param string $generate_page
 * @return string
 */
function author_theme($author_info, $topic_array, $topic_other_array, $generate_page)
{
    global $page_title, $nv_Lang;

    // Thông số image, description, pseudonym có thể có hoặc không (nếu là guest)
    return list_articles_theme([
        'title' => $page_title,
        'image' => $author_info['image'] ?? '',
        'description' => $author_info['description'] ?? '',
        'list_title' => $author_info['is_guest'] ? '' : $nv_Lang->getModule('list_articles_by_author', $author_info['pseudonym'])
    ], $topic_array, $topic_other_array, $generate_page);
}

/**
 * Giao diện danh sách bài viết dạng có ảnh
 *
 * @param array  $header         Phần đầu trang gồm title, image, description, list_title
 * @param array  $array_articles Danh sách bài viết, ở trang danh sách chủ đề là danh sách chủ đề
 * @param array  $array_others   Danh sách các tin khác
 * @param string $generate_page  HTML phân trang
 * @return string
 */
function list_articles_theme(array $header, array $array_articles, array $array_others, $generate_page)
{
    global $module_name, $module_config, $nv_Lang, $home;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('list_articles.tpl'));
    $tpl->registerPlugin('modifier', 'ddate', 'nv_date_format');
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->registerPlugin('modifier', 'editAllowed', 'nv_link_edit_page');
    $tpl->registerPlugin('modifier', 'deleteAllowed', 'nv_link_delete_page');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('HOME', $home);
    $tpl->assign('PAGE_TITLE', nv_html_page_title(false));

    $imgratio = round(($module_config[$module_name]['homewidth'] / ($module_config[$module_name]['homeheight'] ?: $module_config[$module_name]['homewidth'])) * 100, 2);
    $tpl->assign('IMGRATIO', $imgratio);

    $tpl->assign('HEADER', $header);
    $tpl->assign('ARTICLES', $array_articles);
    $tpl->assign('OTHERS', $array_others);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('list_articles.tpl');
}

/**
 * Giao diện gửi email bài viết cho bạn bè
 *
 * @param mixed $sendmail
 * @return string
 */
function sendmail_theme($sendmail)
{
    global $global_config, $nv_Lang, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('sendmail.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('SENDMAIL', $sendmail);
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('nv_seccode'));

    return $tpl->fetch('sendmail.tpl');
}

/**
 * Giao diện in bài viết
 *
 * @param array $result
 * @return string
 */
function news_print($result)
{
    global $nv_Lang, $module_name;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('print.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('CONTENT', $result);

    return $tpl->fetch('print.tpl');
}

/**
 * Tài liệu HTML độc lập của bài viết để tải về
 *
 * @param array $result
 * @return string
 */
function news_savefile($result)
{
    global $nv_Lang, $module_name;

    // Lấy tệp CSS nhúng inline để có giao diện độc lập
    $assets = addition_module_assets($module_name, 'css', false, '.print');
    $inline_css = '';
    if (!empty($assets['css_path'])) {
        $inline_css = file_get_contents(NV_ROOTDIR . '/' . $assets['css_path']);
        $inline_css = preg_replace(['/^@charset\s+[^;]+;\s*/i', '/\/\*#\s*sourceMappingURL=[^*]*\*\/\s*$/'], '', $inline_css);
        $inline_css = trim($inline_css);
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('savefile.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('IS_RTL', \NukeViet\Template\Config::isRtl());
    $tpl->assign('INLINE_CSS', $inline_css);
    $tpl->assign('CONTENT', $result);

    return $tpl->fetch('savefile.tpl');
}

/**
 * Giao diện form tìm kiếm
 *
 * @param string $key
 * @param int    $check_num
 * @param array  $date_array
 * @param array  $array_cat_search
 * @return string
 */
function search_theme($key, $check_num, $date_array, $array_cat_search)
{
    global $module_name, $nv_Lang;

    // Key của mảng là catid, mục 0 (tất cả chuyên mục) không có sẵn catid và lev
    $cats = [];
    foreach ($array_cat_search as $catid => $search_cat) {
        $cats[] = [
            'catid' => $catid,
            'title' => $search_cat['title'],
            'lev' => $search_cat['lev'] ?? 0,
            'selected' => !empty($search_cat['select'])
        ];
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('search.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('KEY', $key);
    $tpl->assign('CHOOSE', (int) $check_num);
    $tpl->assign('DATE', $date_array);
    $tpl->assign('CATS', $cats);

    return $tpl->fetch('search.tpl');
}

/**
 * Giao diện kết quả tìm kiếm
 *
 * @param string $key
 * @param int    $numRecord
 * @param array  $array_content
 * @param int    $catid
 * @param array  $internal_authors
 * @param string $generate_page
 * @return string
 */
function search_result_theme($key, $numRecord, $array_content, $catid, $internal_authors, $generate_page)
{
    global $nv_Lang, $module_name, $global_array_cat, $module_config, $global_config;

    $array = [];
    foreach ($array_content as $value) {
        // Tác giả nội bộ có trang riêng, tác giả nhập tay chỉ là văn bản
        $authors_internal = [];
        if (!empty($internal_authors[$value['id']])) {
            foreach ($internal_authors[$value['id']] as $internal_author) {
                $authors_internal[] = [
                    'href' => $internal_author['href'],
                    'name' => BoldKeywordInStr($internal_author['pseudonym'], $key)
                ];
            }
        }

        $array[] = [
            'link' => $global_array_cat[$value['catid']]['link'] . '/' . $value['alias'] . '-' . $value['id'] . $global_config['rewrite_exturl'],
            'title' => BoldKeywordInStr(strip_tags($value['title']), $key),
            'title_plain' => strip_tags($value['title']),
            'content' => BoldKeywordInStr(strip_tags($value['hometext']), $key),
            'publtime' => $value['publtime'],
            'authors_internal' => $authors_internal,
            'author' => !empty($value['author']) ? BoldKeywordInStr($value['author'], $key) : '',
            'source' => $value['sourceid'] > 0 ? BoldKeywordInStr(GetSourceNews($value['sourceid']), $key) : '',
            'external_link' => !empty($value['external_link']),
            'homeimgfile' => $value['homeimgfile']
        ];
    }

    $imgratio = round(($module_config[$module_name]['homewidth'] / ($module_config[$module_name]['homeheight'] ?: $module_config[$module_name]['homewidth'])) * 100, 2);

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('search_result.tpl'));
    $tpl->registerPlugin('modifier', 'ddatetime', 'nv_datetime_format');
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('KEY', $key);
    $tpl->assign('NUMRECORD', $numRecord);
    $tpl->assign('ARRAY', $array);
    $tpl->assign('IMGRATIO', $imgratio);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('search_result.tpl');
}

/**
 * Trang thông báo rồi tự động chuyển hướng
 *
 * @param array $data Có các khóa content, urlrefresh
 * @return string
 */
function content_refresh($data)
{
    global $module_name, $nv_Lang;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('content_refresh.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('DATA', $data);

    return $tpl->fetch('content_refresh.tpl');
}

/**
 * Form sửa thông tin tác giả
 *
 * @param array $data Thông tin tác giả
 * @param string $base_url
 * @return string
 */
function edit_author_info($data, $base_url)
{
    global $module_name, $nv_Lang, $csrf_key, $op;

    $data['description_br2nl'] = !empty($data['description']) ? nv_htmlspecialchars(nv_br2nl($data['description'])) : '';

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('content_author_info.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('FORM_ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;author_info=1');
    $tpl->assign('BASE_URL', $base_url);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('DATA', $data);

    return $tpl->fetch('content_author_info.tpl');
}

/**
 * Form thêm, sửa bài viết của thành viên
 *
 * @param array $rowcontent
 * @param string $htmlbodyhtml
 * @param array $catidList
 * @param array $topicList
 * @param array $post_status
 * @param array $layouts
 * @param string $base_url
 * @return string
 */
function content_add($rowcontent, $htmlbodyhtml, $catidList, $topicList, $post_status, $layouts, $base_url)
{
    global $global_config, $module_name, $module_config, $nv_Lang, $csrf_key;

    // Danh sách chuyên mục
    $array_catid_in_row = array_map('intval', explode(',', $rowcontent['listcatid']));
    $cats = [];
    foreach ($catidList as $value) {
        $value['checked'] = in_array($value['catid'], $array_catid_in_row, true);
        $cats[] = $value;
    }

    // Danh sách layout được chọn
    $layout_funcs = [];
    if ($module_config[$module_name]['frontend_edit_layout'] == 1) {
        foreach ($layouts as $value) {
            $layout_funcs[] = preg_replace($global_config['check_op_layout'], '\\1', $value);
        }
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('content_form.tpl'));
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('MCONFIG', $module_config[$module_name]);
    $tpl->assign('BASE_URL', $base_url);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('CAPTCHA_ATTRS', nv_captcha_form_attrs('fcode'));
    $tpl->assign('DATA', $rowcontent);
    $tpl->assign('HTMLBODYTEXT', $htmlbodyhtml);
    $tpl->assign('CATS', $cats);
    $tpl->assign('TOPICS', $topicList);
    $tpl->assign('LAYOUTS', $layout_funcs);
    $tpl->assign('POST_STATUS', $post_status);

    return $tpl->fetch('content_form.tpl');
}

/**
 * Danh sách bài viết của thành viên
 *
 * @param array $articles
 * @param array $my_author_detail
 * @param string $base_url
 * @param string $generate_page
 * @return string
 */
function content_list($articles, $my_author_detail, $base_url, $generate_page)
{
    global $module_name, $module_config, $nv_Lang, $csrf_key;

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('content_list.tpl'));
    $tpl->registerPlugin('modifier', 'dnumber', 'nv_number_format');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('BASE_URL', $base_url);
    $tpl->assign('AUTHOR_PAGE_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=author/' . $my_author_detail['alias']);
    $tpl->assign('CHECKSS', csrf_create($csrf_key));
    $tpl->assign('ARTICLES', $articles);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    $imgratio = round(($module_config[$module_name]['homewidth'] / ($module_config[$module_name]['homeheight'] ?: $module_config[$module_name]['homewidth'])) * 100, 2);
    $tpl->assign('IMGRATIO', $imgratio);

    return $tpl->fetch('content_list.tpl');
}
