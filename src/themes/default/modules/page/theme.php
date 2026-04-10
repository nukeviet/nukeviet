<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_PAGE')) {
    exit('Stop!!!');
}

/**
 * nv_page_main()
 *
 * @param array  $row
 * @param array  $ab_links
 * @param string $content_comment
 * @return string
 */
function nv_page_main($row, $ab_links, $content_comment)
{
    global $module_name, $module_info, $meta_property, $client_info, $page_config, $global_config;

    [$template, $dir] = get_module_tpl_dir('main.tpl', true);
    $xtpl = new XTemplate('main.tpl', $dir);
    $xtpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);
    $xtpl->assign('CONTENT', $row);

    if (!empty($row['description'])) {
        $xtpl->parse('main.description');
    }

    if ($row['socialbutton'] and !empty($page_config['socialbutton'])) {
        if (str_contains($page_config['socialbutton'], 'facebook')) {
            if (!empty($page_config['facebookapi'])) {
                $meta_property['fb:app_id'] = $page_config['facebookapi'];
                $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
            }

            $xtpl->parse('main.socialbutton.facebook');
        }
        if (str_contains($page_config['socialbutton'], 'twitter')) {
            $xtpl->parse('main.socialbutton.twitter');
        }
        if (str_contains($page_config['socialbutton'], 'zalo') and !empty($global_config['zaloOfficialAccountID'])) {
            $xtpl->assign('ZALO_OAID', $global_config['zaloOfficialAccountID']);
            $xtpl->parse('main.socialbutton.zalo');
        }

        $xtpl->parse('main.socialbutton');
    }

    if (!empty($row['image'])) {
        if ($row['imageposition'] > 0) {
            if ($row['imageposition'] == 1 and !empty($row['description'])) {
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
        $xtpl->assign('ADMIN_CHECKSS', $row['admin_checkss']);
        $xtpl->assign('ADMIN_EDIT', $row['admin_edit']);
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
 * @param array  $array_data
 * @param string $generate_page
 * @return string
 */
function nv_page_main_list($array_data, $generate_page)
{
    global $module_upload, $module_info, $module_name;

    $xtpl = new XTemplate('main_list.tpl', get_module_tpl_dir('main_list.tpl'));
    $xtpl->assign('GLANG', \NukeViet\Core\Language::$lang_global);

    if (!empty($array_data)) {
        foreach ($array_data as $row) {
            $xtpl->assign('DATA', $row);

            if (!empty($row['image'])) {
                $xtpl->parse('main.loop.image');
            }
            if (defined('NV_IS_MODADMIN')) {
                $xtpl->assign('ADMIN_CHECKSS', $row['admin_checkss']);
                $xtpl->assign('ADMIN_EDIT', $row['admin_edit']);
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
