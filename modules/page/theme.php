<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 11, 2010 8:43:46 PM
 */

if (!defined('NV_IS_MOD_PAGE')) {
    die('Stop!!!');
}

/**
 * nv_page_main()
 *
 * @param mixed $row
 * @param mixed $ab_links
 * @return
 */
function nv_page_main($row, $ab_links, $content_comment)
{
    global $module_name, $lang_module, $lang_global, $module_info, $meta_property, $client_info, $page_config;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CONTENT', $row);

    if (!empty($row['description'])) {
        $xtpl->parse('main.description');
    }

    if ($row['socialbutton']) {
        if (!empty($page_config['facebookapi'])) {
            $meta_property['fb:app_id'] = $page_config['facebookapi'];
            $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';

            $xtpl->assign('SELFURL', $client_info['selfurl']);
            $xtpl->parse('main.socialbutton.facebook');
        }

        $xtpl->parse('main.socialbutton');
    }

    if (!empty($row['image'])) {
        if ($row['imageposition'] > 0) {
            if ($row['imageposition'] == 1) {
                if (!empty($row['imagealt'])) {
                    $xtpl->parse('main.imageleft.alt');
                }
                $xtpl->parse('main.imageleft');
            } else {
                if (!empty($row['imagealt'])) {
                    $xtpl->parse('main.imagecenter.alt');
                }
                $xtpl->parse('main.imagecenter');
            }
        }
    }

    if (defined('NV_IS_MODADMIN')) {
        $xtpl->assign('ADMIN_CHECKSS', md5($row['id'] . NV_CHECK_SESSION));
        $xtpl->assign('ADMIN_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id']);
        $xtpl->parse('main.adminlink');

        // Hiển thị cảnh báo cho người quản trị nếu bài ngưng hoạt động
        if (!$row['status']) {
            $xtpl->parse('main.warning');
        }
    } elseif (!$row['status']) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if (!empty($ab_links)) {
        foreach ($ab_links as $row) {
            $xtpl->assign('OTHER', $row);
            $xtpl->parse('main.other.loop');
        }
        $xtpl->parse('main.other');
    }

    if (!empty($content_comment)) {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_page_main_list()
 *
 * @param mixed $array_data
 * @return
 */
function nv_page_main_list($array_data, $generate_page)
{
    global $lang_global, $module_upload, $module_info, $module_name;

    $template = (file_exists(NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme'] . '/main_list.tpl')) ? $module_info['template'] : 'default';

    $xtpl = new XTemplate('main_list.tpl', NV_ROOTDIR . '/themes/' . $template . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('GLANG', $lang_global);

    if (!empty($array_data)) {
        foreach ($array_data as $row) {
            if (!empty($row['image'])) {
                if (file_exists(NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'])) {
                    $row['image'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $module_upload . '/' . $row['image'];
                } elseif (file_exists(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'])) {
                    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
                } else {
                    $row['image'] = '';
                }
                $row['imagealt'] = !empty($row['imagealt']) ? $row['imagealt'] : $row['title'];
            }

            $xtpl->assign('DATA', $row);

            if (!empty($row['image'])) {
                $xtpl->parse('main.loop.image');
            }
            if (defined('NV_IS_MODADMIN')) {
                $xtpl->assign('ADMIN_CHECKSS', md5($row['id'] . NV_CHECK_SESSION));
                $xtpl->assign('ADMIN_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id']);
                $xtpl->parse('main.loop.adminlink');
            }
            $xtpl->parse('main.loop');
        }
        if ($generate_page != '') {
            $xtpl->assign('GENERATE_PAGE', $generate_page);
        }
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}
