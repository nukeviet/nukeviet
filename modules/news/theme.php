<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_NEWS')) {
    exit('Stop!!!');
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
    global $site_mods, $module_name, $module_upload, $lang_module, $module_config, $module_info, $global_array_cat, $global_array_cat, $catid, $page;

    $xtpl = new XTemplate('viewcat_grid.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    }

    if (!empty($catid)) {
        $xtpl->assign('CAT', $global_array_cat[$catid]);
        $xtpl->parse('main.cattitle');
    }

    $a = 0;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_date('d/m/Y H:i:s', $array_row_i['publtime']);

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
                $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
                $xtpl->parse('main.featuredloop.adminlink');
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
                $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
                $xtpl->parse('main.viewcatloop.adminlink');
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
    global $module_name, $module_upload, $lang_module, $module_config, $module_info, $global_array_cat;

    $xtpl = new XTemplate('viewcat_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 0) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    }

    $a = $page;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_date('d/m/Y H:i:s', $array_row_i['publtime']);

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
            $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
            $xtpl->parse('main.viewcatloop.adminlink');
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
    global $site_mods, $global_array_cat, $module_name, $module_upload, $lang_module, $module_config, $module_info, $catid, $page;

    $xtpl = new XTemplate('viewcat_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    }

    $a = 0;
    foreach ($array_catpage as $array_row_i) {
        $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
        $array_row_i['publtime'] = nv_date('d/m/Y H:i:s', $array_row_i['publtime']);
        $array_row_i['listcatid'] = explode(',', $array_row_i['listcatid']);
        $num_cat = sizeof($array_row_i['listcatid']);

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
                $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
                $xtpl->parse('main.viewcatloop.featured.adminlink');
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
                $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
                $xtpl->parse('main.viewcatloop.news.adminlink');
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
        $xtpl->assign('ORTHERNEWS', $lang_module['other']);

        foreach ($array_cat_other as $array_row_i) {
            $newday = $array_row_i['publtime'] + (86400 * $array_row_i['newday']);
            $array_row_i['publtime'] = nv_date('d/m/Y', $array_row_i['publtime']);

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
 * viewcat_top()
 *
 * @param array  $array_catcontent
 * @param string $generate_page
 * @return string
 */
function viewcat_top($array_catcontent, $generate_page)
{
    global $site_mods, $module_name, $module_upload, $lang_module, $module_config, $module_info, $global_array_cat, $catid, $page;

    $xtpl = new XTemplate('viewcat_top.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('IMGWIDTH0', $module_config[$module_name]['homewidth']);

    if ($catid > 0 and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    }

    // Cac bai viet phan dau
    if (!empty($array_catcontent)) {
        $a = 0;
        foreach ($array_catcontent as $array_catcontent_i) {
            $newday = $array_catcontent_i['publtime'] + (86400 * $array_catcontent_i['newday']);
            $array_catcontent_i['publtime'] = nv_date('d/m/Y H:i:s', $array_catcontent_i['publtime']);

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
                    $xtpl->assign('ADMINLINK', nv_link_edit_page($array_catcontent_i['id']) . ' ' . nv_link_delete_page($array_catcontent_i['id']));
                    $xtpl->parse('main.catcontent.adminlink');
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
 * viewsubcat_main()
 *
 * @param string $viewcat
 * @param array  $array_cat
 * @return string
 */
function viewsubcat_main($viewcat, $array_cat)
{
    global $module_name, $site_mods, $global_array_cat, $lang_module, $module_config, $module_info;

    $xtpl = new XTemplate($viewcat . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);
    $xtpl->assign('IMGWIDTH', $module_config[$module_name]['homewidth']);

    // Hien thi cac chu de con
    foreach ($array_cat as $key => $array_row_i) {
        if (isset($array_cat[$key]['content'])) {
            $array_row_i['rss'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $array_row_i['alias'];
            $xtpl->assign('CAT', $array_row_i);
            $catid = (int) ($array_row_i['catid']);
            $array_row_i['ad_block_cat'] = isset($array_row_i['ad_block_cat']) ? explode(',', $array_row_i['ad_block_cat']) : [];

            $_block_topcat_by_id = '[' . strtoupper($module_name) . '_TOPCAT_' . $array_row_i['catid'] . ']';
            if (in_array('1', $array_row_i['ad_block_cat'], true)) {
                if (!nv_check_block_topcat_news($array_row_i['catid'])) {
                    nv_add_block_topcat_news($array_row_i['catid']);
                }
                $xtpl->assign('BLOCK_TOPCAT', $_block_topcat_by_id);
                $xtpl->parse('main.listcat.block_topcat');
            } else {
                if (nv_check_block_topcat_news($array_row_i['catid'])) {
                    nv_remove_block_topcat_news($array_row_i['catid']);
                }
            }

            $_block_bottomcat_by_id = '[' . strtoupper($module_name) . '_BOTTOMCAT_' . $array_row_i['catid'] . ']';
            if (in_array('2', $array_row_i['ad_block_cat'], true)) {
                if (!nv_check_block_block_botcat_news($array_row_i['catid'])) {
                    nv_add_block_botcat_news($array_row_i['catid']);
                }
                $xtpl->assign('BLOCK_BOTTOMCAT', $_block_bottomcat_by_id);
                $xtpl->parse('main.listcat.block_bottomcat');
            } else {
                if (nv_check_block_block_botcat_news($array_row_i['catid'])) {
                    nv_remove_block_botcat_news($array_row_i['catid']);
                }
            }

            if ($array_row_i['subcatid'] != '') {
                $_arr_subcat = explode(',', $array_row_i['subcatid']);
                $limit = 0;
                foreach ($_arr_subcat as $catid_i) {
                    if ($global_array_cat[$catid_i]['status'] == 1) {
                        $xtpl->assign('SUBCAT', $global_array_cat[$catid_i]);
                        $xtpl->parse('main.listcat.subcatloop');
                        ++$limit;
                    }
                    if ($limit >= 3) {
                        $more = [
                            'title' => $lang_module['more'],
                            'link' => $global_array_cat[$catid]['link']
                        ];
                        $xtpl->assign('MORE', $more);
                        $xtpl->parse('main.listcat.subcatmore');
                        break;
                    }
                }
            }

            $a = 0;
            foreach ($array_cat[$key]['content'] as $array_row_i) {
                $newday = isset($array_row_i['newday']) ? $array_row_i['publtime'] + (86400 * $array_row_i['newday']) : 0;
                $array_row_i['publtime'] = nv_date('d/m/Y H:i', $array_row_i['publtime']);
                ++$a;

                if ($array_row_i['external_link']) {
                    $array_row_i['target_blank'] = 'target="_blank"';
                }

                if ($a == 1) {
                    if ($newday >= NV_CURRENTTIME) {
                        $xtpl->parse('main.listcat.newday');
                    }
                    $xtpl->assign('CONTENT', $array_row_i);

                    if ($array_row_i['imghome'] != '') {
                        $xtpl->assign('HOMEIMG', $array_row_i['imghome']);
                        $xtpl->assign('HOMEIMGALT', !empty($array_row_i['homeimgalt']) ? $array_row_i['homeimgalt'] : $array_row_i['title']);
                        $xtpl->parse('main.listcat.image');
                    }

                    if (defined('NV_IS_MODADMIN')) {
                        $xtpl->assign('ADMINLINK', nv_link_edit_page($array_row_i['id']) . ' ' . nv_link_delete_page($array_row_i['id']));
                        $xtpl->parse('main.listcat.adminlink');
                    }
                } else {
                    if ($newday >= NV_CURRENTTIME) {
                        $xtpl->assign('CLASS', 'icon_new_small');
                    } else {
                        $xtpl->assign('CLASS', 'icon_list');
                    }
                    $array_row_i['hometext_clean'] = nv_clean60(strip_tags($array_row_i['hometext']), $module_config[$module_name]['tooltip_length'], true);
                    $xtpl->assign('OTHER', $array_row_i);
                    if ($module_config[$module_name]['showtooltip']) {
                        $xtpl->parse('main.listcat.related.loop.tooltip');
                    }
                    $xtpl->parse('main.listcat.related.loop');
                }

                if ($a > 1) {
                    $xtpl->assign('WCT', 'col-md-16 ');
                } else {
                    $xtpl->assign('WCT', 'col-md-24');
                }

                $xtpl->set_autoreset();
            }

            if ($a > 1) {
                $xtpl->parse('main.listcat.related');
            }

            if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                $xtpl->parse('main.listcat.comment');
            }

            $xtpl->parse('main.listcat');
        }
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * viewcat_two_column()
 *
 * @param array $array_content
 * @param array $array_catpage
 * @return string
 */
function viewcat_two_column($array_content, $array_catpage)
{
    global $site_mods, $module_name, $module_upload, $module_config, $module_info, $lang_module, $global_array_cat, $catid, $page;

    $xtpl = new XTemplate('viewcat_two_column.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('IMGWIDTH0', $module_config[$module_name]['homewidth']);

    if ($catid and (($global_array_cat[$catid]['viewdescription'] and $page == 1) or $global_array_cat[$catid]['viewdescription'] == 2)) {
        $xtpl->assign('CONTENT', $global_array_cat[$catid]);
        if ($global_array_cat[$catid]['image']) {
            $xtpl->assign('HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_cat[$catid]['image']);
            $xtpl->parse('main.viewdescription.image');
        }
        $xtpl->parse('main.viewdescription');
    }

    // Bai viet o phan dau
    if (!empty($array_content)) {
        foreach ($array_content as $key => $array_content_i) {
            $newday = $array_content_i['publtime'] + (86400 * $array_content_i['newday']);
            $array_content_i['publtime'] = nv_date('d/m/Y H:i:s', $array_content_i['publtime']);

            if ($array_content_i['external_link']) {
                $array_content_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('NEWSTOP', $array_content_i);

            if ($key == 0) {
                if ($array_content_i['imghome'] != '') {
                    $xtpl->assign('HOMEIMG0', $array_content_i['imghome']);
                    $xtpl->assign('HOMEIMGALT0', $array_content_i['homeimgalt']);
                    $xtpl->parse('main.catcontent.content.image');
                }

                if (defined('NV_IS_MODADMIN')) {
                    $xtpl->assign('ADMINLINK', nv_link_edit_page($array_content_i['id']) . ' ' . nv_link_delete_page($array_content_i['id']));
                    $xtpl->parse('main.catcontent.content.adminlink');
                }

                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.catcontent.content.newday');
                }

                if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                    $xtpl->parse('main.catcontent.content.comment');
                }

                $xtpl->parse('main.catcontent.content');
            } else {
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.catcontent.other.newday');
                }

                if ($module_config[$module_name]['showtooltip']) {
                    $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);
                    $xtpl->parse('main.catcontent.other.tooltip');
                }

                $xtpl->parse('main.catcontent.other');
            }
        }

        $xtpl->parse('main.catcontent');
    }

    // Theo chu de
    $a = 0;

    foreach ($array_catpage as $key => $array_catpage_i) {
        $number_content = isset($array_catpage[$key]['content']) ? sizeof($array_catpage[$key]['content']) : 0;
        if ($number_content > 0) {
            $array_catpage_i['rss'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $array_catpage_i['alias'];
            $xtpl->assign('CAT', $array_catpage_i);
            $xtpl->assign('ID', ($a + 1));

            $array_content_i = $array_catpage_i['content'][0];
            $newday = $array_content_i['publtime'] + (86400 * $array_content_i['newday']);
            $array_content_i['hometext'] = nv_clean60(strip_tags($array_content_i['hometext']), 200);
            $array_content_i['publtime'] = nv_date('d/m/Y H:i:s', $array_content_i['publtime']);

            if ($array_content_i['external_link']) {
                $array_content_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('CONTENT', $array_content_i);

            if ($array_content_i['imghome'] != '') {
                $xtpl->assign('HOMEIMG01', $array_content_i['imghome']);
                $xtpl->assign('HOMEIMGALT01', !empty($array_content_i['homeimgalt']) ? $array_content_i['homeimgalt'] : $array_content_i['title']);
                $xtpl->parse('main.loopcat.content.image');
            }

            if (defined('NV_IS_MODADMIN')) {
                $xtpl->assign('ADMINLINK', nv_link_edit_page($array_content_i['id']) . ' ' . nv_link_delete_page($array_content_i['id']));
                $xtpl->parse('main.loopcat.content.adminlink');
            }

            if ($newday >= NV_CURRENTTIME) {
                $xtpl->parse('main.loopcat.content.newday');
            }

            if (isset($site_mods['comment']) and isset($module_config[$module_name]['activecomm']) and $module_config[$module_name]['activecomm']) {
                $xtpl->parse('main.loopcat.content.comment');
            }

            $xtpl->parse('main.loopcat.content');

            if ($number_content > 1) {
                for ($index = 1; $index < $number_content; ++$index) {
                    if ($newday >= NV_CURRENTTIME) {
                        $xtpl->parse('main.loopcat.other.newday');
                        $xtpl->assign('CLASS', 'icon_new_small');
                    } else {
                        $xtpl->assign('CLASS', 'icon_list');
                    }

                    $array_catpage_i['content'][$index]['hometext_clean'] = nv_clean60(strip_tags($array_catpage_i['content'][$index]['hometext']), $module_config[$module_name]['tooltip_length'], true);
                    $xtpl->assign('CONTENT', $array_catpage_i['content'][$index]);

                    if ($module_config[$module_name]['showtooltip']) {
                        $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);
                        $xtpl->parse('main.loopcat.other.tooltip');
                    }

                    $xtpl->parse('main.loopcat.other');
                }
            }

            // Block Top
            $array_catpage_i['ad_block_cat'] = isset($array_catpage_i['ad_block_cat']) ? explode(',', $array_catpage_i['ad_block_cat']) : [];
            if (($a + 1) % 2) {
                $_block_topcat_by_id = '[' . strtoupper($module_name) . '_TOPCAT_' . $array_catpage_i['catid'] . ']';
                if (in_array('1', $array_catpage_i['ad_block_cat'], true)) {
                    if (!nv_check_block_topcat_news($array_catpage_i['catid'])) {
                        nv_add_block_topcat_news($array_catpage_i['catid']);
                    }
                    $xtpl->assign('BLOCK_TOPCAT', $_block_topcat_by_id);
                    $xtpl->parse('main.loopcat.block_topcat');
                } else {
                    if (nv_check_block_topcat_news($array_catpage_i['catid'])) {
                        nv_remove_block_topcat_news($array_catpage_i['catid']);
                    }
                }
            }

            // Block Bottom
            if ($a % 2) {
                $_block_bottomcat_by_id = '[' . strtoupper($module_name) . '_BOTTOMCAT_' . $array_catpage_i['catid'] . ']';
                if (in_array('2', $array_catpage_i['ad_block_cat'], true)) {
                    if (!nv_check_block_block_botcat_news($array_catpage_i['catid'])) {
                        nv_add_block_botcat_news($array_catpage_i['catid']);
                    }
                    $xtpl->assign('BLOCK_BOTTOMCAT', $_block_bottomcat_by_id);
                    $xtpl->parse('main.loopcat.block_bottomcat');
                } else {
                    if (nv_check_block_block_botcat_news($array_catpage_i['catid'])) {
                        nv_remove_block_botcat_news($array_catpage_i['catid']);
                    }
                }
            }

            $xtpl->parse('main.loopcat');
            ++$a;
        }
    }

    // Theo chu de
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * detail_theme()
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
    global $global_config, $module_info, $lang_module, $module_name, $module_config, $lang_global, $client_info;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG_GLOBAL', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TOOLTIP_POSITION', $module_config[$module_name]['tooltip_position']);

    // Khai báo dữ liệu có cấu trúc
    $news_contents['number_edittime'] = (empty($news_contents['edittime']) or $news_contents['edittime'] < $news_contents['number_publtime']) ? $news_contents['number_publtime'] : $news_contents['edittime'];

    $xtpl->assign('SCHEMA_AUTHOR', empty($news_contents['author']) ? $news_contents['post_name'] : $news_contents['author']);
    $xtpl->assign('SCHEMA_DATEPUBLISHED', date('c', $news_contents['number_publtime']));
    $xtpl->assign('SCHEMA_DATEPUBLISHED', date('c', $news_contents['number_edittime']));
    $xtpl->assign('SCHEMA_ORGLOGO', NV_MAIN_DOMAIN . NV_BASE_SITEURL . $global_config['site_logo']);
    $xtpl->assign('SCHEMA_ORGNAME', $global_config['site_name']);
    $xtpl->assign('SCHEMA_URL', $news_contents['link']);

    if (preg_match('/^' . nv_preg_quote(NV_BASE_SITEURL) . '/i', $news_contents['homeimgfile'])) {
        $xtpl->assign('SCHEMA_IMAGE', NV_MAIN_DOMAIN . $news_contents['homeimgfile']);
    } elseif (nv_is_url($news_contents['homeimgfile'])) {
        $xtpl->assign('SCHEMA_IMAGE', $news_contents['homeimgfile']);
    } else {
        $xtpl->assign('SCHEMA_IMAGE', NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/no_image.gif');
    }

    $news_contents['addtime'] = nv_date('d/m/Y h:i:s', $news_contents['addtime']);

    $xtpl->assign('NEWSID', $news_contents['id']);
    $xtpl->assign('NEWSCHECKSS', $news_contents['newscheckss']);
    $xtpl->assign('DETAIL', $news_contents);

    if ($news_contents['allowed_send'] == 1) {
        $xtpl->assign('CHECKSESSION', md5($news_contents['id'] . NV_CHECK_SESSION));
        $xtpl->assign('URL_SENDMAIL', $news_contents['url_sendmail']);
        $xtpl->parse('main.allowed_send');
    }

    if ($news_contents['allowed_print'] == 1) {
        $xtpl->assign('URL_PRINT', $news_contents['url_print']);
        $xtpl->parse('main.allowed_print');
    }

    if ($news_contents['allowed_save'] == 1) {
        $xtpl->assign('URL_SAVEFILE', $news_contents['url_savefile']);
        $xtpl->parse('main.allowed_save');
    }

    if ($news_contents['allowed_rating'] == 1) {
        $xtpl->assign('STRINGRATING', $news_contents['stringrating']);

        foreach ($news_contents['stars'] as $star) {
            $xtpl->assign('STAR', $star);
            $xtpl->parse('main.allowed_rating.star');
        }

        if ($news_contents['disablerating'] == 1) {
            $xtpl->parse('main.allowed_rating.disablerating');
        }

        if ($news_contents['numberrating'] >= $module_config[$module_name]['allowed_rating_point']) {
            $xtpl->parse('main.allowed_rating.data_rating');
        }

        $xtpl->parse('main.allowed_rating');
    }

    if ($news_contents['showhometext']) {
        if (!empty($news_contents['image']['src'])) {
            if ($news_contents['image']['position'] == 1) {
                if (!empty($news_contents['image']['note'])) {
                    $xtpl->parse('main.showhometext.imgthumb.note');
                } else {
                    $xtpl->parse('main.showhometext.imgthumb.empty');
                }
                $xtpl->parse('main.showhometext.imgthumb');
            } elseif ($news_contents['image']['position'] == 2) {
                if (!empty($news_contents['image']['note'])) {
                    $xtpl->parse('main.showhometext.imgfull.note');
                }
                $xtpl->parse('main.showhometext.imgfull');
            }
        }

        $xtpl->parse('main.showhometext');
    }
    if (!empty($news_contents['post_name'])) {
        $xtpl->parse('main.post_name');
    }

    if (!empty($news_contents['files'])) {
        foreach ($news_contents['files'] as $file) {
            $xtpl->assign('FILE', $file);

            // Hỗ trợ xem trực tuyến PDF và ảnh, các định dạng khác tải về để xem
            if (!empty($file['urlfile'])) {
                $xtpl->parse('main.files.loop.show_quick_viewfile');
                $xtpl->parse('main.files.loop.content_quick_viewfile');
            } elseif (preg_match('/^png|jpe|jpeg|jpg|gif|bmp|ico|tiff|tif|svg|svgz$/', $file['ext'])) {
                $xtpl->parse('main.files.loop.show_quick_viewimg');
            }
            $xtpl->parse('main.files.loop');
        }
        $xtpl->parse('main.files');
    }

    if (!empty($news_contents['author']) or !empty($news_contents['source'])) {
        if (!empty($news_contents['author'])) {
            $xtpl->parse('main.author.name');
        }

        if (!empty($news_contents['source'])) {
            $xtpl->parse('main.author.source');
        }

        $xtpl->parse('main.author');
    }
    if ($news_contents['copyright'] == 1) {
        if (!empty($module_config[$module_name]['copyright'])) {
            $xtpl->assign('COPYRIGHT', $module_config[$module_name]['copyright']);
            $xtpl->parse('main.copyright');
        }
    }

    if (!empty($array_keyword)) {
        $t = sizeof($array_keyword) - 1;
        foreach ($array_keyword as $i => $value) {
            $xtpl->assign('KEYWORD', $value['keyword']);
            $xtpl->assign('LINK_KEYWORDS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . urlencode($value['alias']));
            $xtpl->assign('SLASH', ($t == $i) ? '' : ', ');
            $xtpl->parse('main.keywords.loop');
        }
        $xtpl->parse('main.keywords');
    }

    if (defined('NV_IS_MODADMIN')) {
        $xtpl->assign('ADMINLINK', nv_link_edit_page($news_contents['id']) . ' ' . nv_link_delete_page($news_contents['id'], 1));
        $xtpl->parse('main.adminlink');
    }

    if (!empty($module_config[$module_name]['socialbutton'])) {
        global $meta_property;

        if (str_contains($module_config[$module_name]['socialbutton'], 'facebook')) {
            if (!empty($module_config[$module_name]['facebookappid'])) {
                $meta_property['fb:app_id'] = $module_config[$module_name]['facebookappid'];
                $meta_property['og:locale'] = (NV_LANG_DATA == 'vi') ? 'vi_VN' : 'en_US';
            }
            $xtpl->parse('main.socialbutton.facebook');
        }
        if (str_contains($module_config[$module_name]['socialbutton'], 'twitter')) {
            $xtpl->parse('main.socialbutton.twitter');
        }
        if (str_contains($module_config[$module_name]['socialbutton'], 'zalo') and !empty($global_config['zaloOfficialAccountID'])) {
            $xtpl->assign('ZALO_OAID', $global_config['zaloOfficialAccountID']);
            $xtpl->parse('main.socialbutton.zalo');
        }

        $xtpl->parse('main.socialbutton');
    }

    if (!empty($related_new_array) or !empty($related_array) or !empty($topic_array)) {
        if (!empty($related_new_array)) {
            foreach ($related_new_array as $key => $related_new_array_i) {
                if ($module_config[$module_name]['showtooltip']) {
                    $related_new_array_i['hometext_clean'] = nv_clean60(strip_tags($related_new_array_i['hometext']), $module_config[$module_name]['tooltip_length'], true);
                }

                if ($related_new_array_i['external_link']) {
                    $related_new_array_i['target_blank'] = 'target="_blank"';
                }

                $newday = $related_new_array_i['time'] + (86400 * $related_new_array_i['newday']);
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.others.related_new.loop.newday');
                }
                $related_new_array_i['time'] = nv_date('d/m/Y', $related_new_array_i['time']);

                $xtpl->assign('RELATED_NEW', $related_new_array_i);

                if ($module_config[$module_name]['showtooltip']) {
                    $xtpl->parse('main.others.related_new.loop.tooltip');
                }

                $xtpl->parse('main.others.related_new.loop');
            }
            unset($key);
            $xtpl->parse('main.others.related_new');
        }

        if (!empty($related_array)) {
            foreach ($related_array as $related_array_i) {
                if ($module_config[$module_name]['showtooltip']) {
                    $related_array_i['hometext_clean'] = nv_clean60(strip_tags($related_array_i['hometext']), $module_config[$module_name]['tooltip_length'], true);
                }

                if ($related_array_i['external_link']) {
                    $related_array_i['target_blank'] = 'target="_blank"';
                }

                $newday = $related_array_i['time'] + (86400 * $related_array_i['newday']);
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.others.related.loop.newday');
                }
                $related_array_i['time'] = nv_date('d/m/Y', $related_array_i['time']);
                $xtpl->assign('RELATED', $related_array_i);

                if ($module_config[$module_name]['showtooltip']) {
                    $xtpl->parse('main.others.related.loop.tooltip');
                }

                $xtpl->parse('main.others.related.loop');
            }
            $xtpl->parse('main.others.related');
        }

        if (!empty($topic_array)) {
            foreach ($topic_array as $key => $topic_array_i) {
                if ($module_config[$module_name]['showtooltip']) {
                    $topic_array_i['hometext_clean'] = nv_clean60(strip_tags($topic_array_i['hometext']), $module_config[$module_name]['tooltip_length'], true);
                }

                if ($topic_array_i['external_link']) {
                    $topic_array_i['target_blank'] = 'target="_blank"';
                }

                $newday = $topic_array_i['time'] + (86400 * $topic_array_i['newday']);
                if ($newday >= NV_CURRENTTIME) {
                    $xtpl->parse('main.others.topic.loop.newday');
                }
                $topic_array_i['time'] = nv_date('d/m/Y', $topic_array_i['time']);
                $xtpl->assign('TOPIC', $topic_array_i);

                if (!empty($module_config[$module_name]['showtooltip'])) {
                    $xtpl->parse('main.others.topic.loop.tooltip');
                }

                $xtpl->parse('main.others.topic.loop');
            }
            $xtpl->parse('main.others.topic');
        }

        $xtpl->parse('main.others');
    }

    if (!empty($content_comment)) {
        $xtpl->assign('CONTENT_COMMENT', $content_comment);
        $xtpl->parse('main.comment');
    }

    if ($news_contents['status'] != 1) {
        $xtpl->parse('main.no_public');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * no_permission()
 *
 * @return string
 */
function no_permission()
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);

    $xtpl->assign('NO_PERMISSION', $lang_module['no_permission']);
    $xtpl->parse('no_permission');

    return $xtpl->text('no_permission');
}

/**
 * topic_theme()
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
    global $lang_module, $module_info, $module_name, $module_config, $topicid;

    $xtpl = new XTemplate('topic.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TOPPIC_TITLE', $page_title);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);
    if (!empty($description)) {
        $xtpl->assign('TOPPIC_DESCRIPTION', $description);
        if (!empty($topic_image)) {
            $xtpl->assign('HOMEIMG1', $topic_image);
            $xtpl->parse('main.topicdescription.image');
        }
        $xtpl->parse('main.topicdescription');
    }
    if (!empty($topic_array)) {
        foreach ($topic_array as $topic_array_i) {
            if (!empty($topic_array_i['external_link'])) {
                $topic_array_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('TOPIC', $topic_array_i);
            $xtpl->assign('TIME', date('H:i', $topic_array_i['publtime']));
            $xtpl->assign('DATE', date('d/m/Y', $topic_array_i['publtime']));

            if (!empty($topic_array_i['src'])) {
                $xtpl->parse('main.topic.homethumb');
            }

            if ($topicid and defined('NV_IS_MODADMIN')) {
                $xtpl->assign('ADMINLINK', nv_link_edit_page($topic_array_i['id']) . ' ' . nv_link_delete_page($topic_array_i['id']));
                $xtpl->parse('main.topic.adminlink');
            }

            $xtpl->parse('main.topic');
        }
    }

    if (!empty($topic_other_array)) {
        foreach ($topic_other_array as $topic_other_array_i) {
            $topic_other_array_i['publtime'] = nv_date('H:i d/m/Y', $topic_other_array_i['publtime']);

            if ($topic_other_array_i['external_link']) {
                $topic_other_array_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('TOPIC_OTHER', $topic_other_array_i);
            $xtpl->parse('main.other.loop');
        }

        $xtpl->parse('main.other');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * author_theme()
 *
 * @param array  $author_info
 * @param array  $topic_array
 * @param array  $topic_other_array
 * @param string $generate_page
 * @return string
 */
function author_theme($author_info, $topic_array, $topic_other_array, $generate_page)
{
    global $lang_module, $module_info, $module_name, $module_config, $topicid;

    $xtpl = new XTemplate('topic.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TOPPIC_TITLE', $author_info['pseudonym']);
    $xtpl->assign('IMGWIDTH1', $module_config[$module_name]['homewidth']);
    if (!empty($author_info['description'])) {
        $xtpl->assign('TOPPIC_DESCRIPTION', $author_info['description']);
        if (!empty($author_info['image'])) {
            $xtpl->assign('HOMEIMG1', $author_info['image']);
            $xtpl->parse('main.topicdescription.image');
        }
        $xtpl->parse('main.topicdescription');
    }
    if (!empty($topic_array)) {
        foreach ($topic_array as $topic_array_i) {
            if (!empty($topic_array_i['external_link'])) {
                $topic_array_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('TOPIC', $topic_array_i);
            $xtpl->assign('TIME', date('H:i', $topic_array_i['publtime']));
            $xtpl->assign('DATE', date('d/m/Y', $topic_array_i['publtime']));

            if (!empty($topic_array_i['src'])) {
                $xtpl->parse('main.topic.homethumb');
            }

            if ($topicid and defined('NV_IS_MODADMIN')) {
                $xtpl->assign('ADMINLINK', nv_link_edit_page($topic_array_i['id']) . ' ' . nv_link_delete_page($topic_array_i['id']));
                $xtpl->parse('main.topic.adminlink');
            }

            $xtpl->parse('main.topic');
        }
    }

    if (!empty($topic_other_array)) {
        foreach ($topic_other_array as $topic_other_array_i) {
            $topic_other_array_i['publtime'] = nv_date('H:i d/m/Y', $topic_other_array_i['publtime']);

            if ($topic_other_array_i['external_link']) {
                $topic_other_array_i['target_blank'] = 'target="_blank"';
            }

            $xtpl->assign('TOPIC_OTHER', $topic_other_array_i);
            $xtpl->parse('main.other.loop');
        }

        $xtpl->parse('main.other');
    }

    if (!empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.generate_page');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * sendmail_themme()
 *
 * @param mixed $sendmail
 * @return string
 */
function sendmail_themme($sendmail)
{
    global $module_info, $global_config, $lang_module, $lang_global, $module_config, $module_name, $module_captcha;

    $xtpl = new XTemplate('sendmail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('SENDMAIL', $sendmail);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

    if (defined('NV_IS_USER')) {
        $xtpl->parse('main.sender_is_user');
    }

    // Nếu dùng reCaptcha v3
    if ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 3) {
        $xtpl->parse('main.recaptcha3');
    }
    // Nếu dùng reCaptcha v2
    elseif ($module_captcha == 'recaptcha' and $global_config['recaptcha_ver'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    } elseif ($module_captcha == 'captcha') {
        $xtpl->assign('GFX_NUM', NV_GFX_NUM);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('CAPTCHA_REFR_SRC', NV_STATIC_URL . NV_ASSETS_DIR . '/images/refresh.png');
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode']);
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->parse('main.captcha');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * news_print()
 *
 * @param array $result
 * @return string
 */
function news_print($result)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('print.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('CONTENT', $result);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($result['image']['width'])) {
        if ($result['image']['position'] == 1) {
            if (!empty($result['image']['note'])) {
                $xtpl->parse('main.image.note');
            }

            $xtpl->parse('main.image');
        } elseif ($result['image']['position'] == 2) {
            if (!empty($result['image']['note'])) {
                $xtpl->parse('main.imagefull.note');
            }

            $xtpl->parse('main.imagefull');
        }
    }

    if ($result['copyright'] == 1) {
        $xtpl->parse('main.copyright');
    }

    if (!empty($result['author']) or !empty($result['source'])) {
        if (!empty($result['author'])) {
            $xtpl->parse('main.author.name');
        }

        if (!empty($result['source'])) {
            $xtpl->parse('main.author.source');
        }

        $xtpl->parse('main.author');
    }

    if ($result['status'] != 1) {
        $xtpl->parse('main.no_public');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * search_theme()
 *
 * @param string $key
 * @param int    $check_num
 * @param array  $date_array
 * @param array  $array_cat_search
 * @return string
 */
function search_theme($key, $check_num, $date_array, $array_cat_search)
{
    global $module_name, $module_info, $lang_module, $module_name;

    $xtpl = new XTemplate('search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('BASE_URL_SITE', NV_BASE_SITEURL . 'index.php');
    $xtpl->assign('TO_DATE', $date_array['to_date']);
    $xtpl->assign('FROM_DATE', $date_array['from_date']);
    $xtpl->assign('KEY', $key);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('OP_NAME', 'search');

    foreach ($array_cat_search as $search_cat) {
        $xtpl->assign('SEARCH_CAT', $search_cat);
        $xtpl->parse('main.search_cat');
    }

    for ($i = 0; $i <= 3; ++$i) {
        if ($check_num == $i) {
            $xtpl->assign('CHECK' . $i, 'selected=\'selected\'');
        } else {
            $xtpl->assign('CHECK' . $i, '');
        }
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * search_result_theme()
 *
 * @param string $key
 * @param int    $numRecord
 * @param int    $per_pages
 * @param int    $page
 * @param array  $array_content
 * @param int    $catid
 * @param array  $internal_authors
 * @return string
 */
function search_result_theme($key, $numRecord, $per_pages, $page, $array_content, $catid, $internal_authors)
{
    global $module_info, $lang_module, $module_name, $global_array_cat, $module_config, $global_config;

    $xtpl = new XTemplate('search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('KEY', $key);
    $xtpl->assign('IMG_WIDTH', $module_config[$module_name]['homewidth']);
    $xtpl->assign('TITLE_MOD', $lang_module['search_modul_title']);

    if (!empty($array_content)) {
        foreach ($array_content as $value) {
            $catid_i = $value['catid'];
            $authors = [];
            if (isset($internal_authors[$value['id']]) and !empty($internal_authors[$value['id']])) {
                foreach ($internal_authors[$value['id']] as $internal_author) {
                    $authors[] = '<a href="' . $internal_author['href'] . '">' . BoldKeywordInStr($internal_author['pseudonym'], $key) . '</a>';
                }
            }
            if (!empty($value['author'])) {
                $authors[] = BoldKeywordInStr($value['author'], $key);
            }
            $authors = !empty($authors) ? implode(', ', $authors) : '';

            $xtpl->assign('LINK', $global_array_cat[$catid_i]['link'] . '/' . $value['alias'] . '-' . $value['id'] . $global_config['rewrite_exturl']);
            $xtpl->assign('TITLEROW', BoldKeywordInStr(strip_tags($value['title']), $key));
            $xtpl->assign('CONTENT', BoldKeywordInStr(strip_tags($value['hometext']), $key));
            $xtpl->assign('TIME', date('d/m/Y H:i:s', $value['publtime']));
            $xtpl->assign('AUTHOR', $authors);
            $xtpl->assign('SOURCE', BoldKeywordInStr(GetSourceNews($value['sourceid']), $key));
            $xtpl->assign('TARGET_BLANK', !empty($value['external_link']) ? ' target="_blank"' : '');

            if (!empty($value['homeimgfile'])) {
                $xtpl->assign('IMG_SRC', $value['homeimgfile']);
                $xtpl->parse('results.result.result_img');
            }

            $xtpl->parse('results.result');
        }
    }

    if ($numRecord == 0) {
        $xtpl->assign('KEY', $key);
        $xtpl->assign('INMOD', $lang_module['search_modul_title']);
        $xtpl->parse('results.noneresult');
    }

    if ($numRecord > $per_pages) {
        // show pages

        $url_link = $_SERVER['REQUEST_URI'];
        if (strpos($url_link, '&page=') > 0) {
            $url_link = substr($url_link, 0, strpos($url_link, '&page='));
        } elseif (strpos($url_link, '?page=') > 0) {
            $url_link = substr($url_link, 0, strpos($url_link, '?page='));
        }

        $_array_url = [
            'link' => $url_link,
            'amp' => '&page='
        ];

        $generate_page = nv_generate_page($_array_url, $numRecord, $per_pages, $page);

        $xtpl->assign('VIEW_PAGES', $generate_page);
        $xtpl->parse('results.pages_result');
    }

    $xtpl->assign('NUMRECORD', $numRecord);
    $xtpl->assign('MY_DOMAIN', NV_MY_DOMAIN);

    $xtpl->parse('results');

    return $xtpl->text('results');
}

/**
 * nv_theme_viewpdf()
 *
 * @param string $file_url
 * @return string
 */
function nv_theme_viewpdf($file_url)
{
    global $lang_module, $lang_global;
    $xtpl = new XTemplate('viewer.tpl', NV_ROOTDIR . '/' . NV_ASSETS_DIR . '/js/pdf.js');
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('PDF_JS_DIR', NV_STATIC_URL . NV_ASSETS_DIR . '/js/pdf.js/');
    $xtpl->assign('PDF_URL', $file_url);
    $xtpl->parse('main');

    return $xtpl->text('main');
}
