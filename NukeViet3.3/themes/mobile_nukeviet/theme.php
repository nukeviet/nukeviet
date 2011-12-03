<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_SYSTEM') or !defined('NV_MAINFILE'))
    die('Stop!!!');

function nv_site_theme($contents)
{
    global $home, $array_mod_title, $lang_global, $language_array, $global_config, $module_name, $module_info, $op, $mod_title, $my_head, $my_footer;

    if (!file_exists(NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/layout/layout." . $module_info['layout_funcs'][$op] . ".tpl"))
    {
        nv_info_die($lang_global['error_layout_title'], $lang_global['error_layout_title'], $lang_global['error_layout_content']);
    }

    if (defined('NV_IS_ADMIN'))
    {
        $my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/css/admin.css\" type=\"text/css\" />";
    }

    $xtpl = new XTemplate("layout." . $module_info['layout_funcs'][$op] . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/layout/");
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('THEME_META_TAGS', nv_html_meta_tags());
    $xtpl->assign('THEME_SITE_JS', nv_html_site_js());
    $xtpl->assign('THEME_CSS', nv_html_css());
    $xtpl->assign('THEME_PAGE_TITLE', nv_html_page_title());
    $xtpl->assign('NV_TOP_MENU_HOME', $lang_global['Home']);
    $xtpl->assign('MODULE_CONTENT', $contents . "&nbsp;");

    $xtpl->assign('THEME_NOJS', $lang_global['nojs']);
    $xtpl->assign('THEME_LOGO_TITLE', $global_config['site_name']);
    $xtpl->assign('THEME_SITE_HREF', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA);

    if ($global_config['lang_multi'] and sizeof($global_config['allow_sitelangs']) > 1)
    {
        $xtpl->assign('SELECTLANGSITE', $lang_global['langsite']);
        foreach ($global_config['allow_sitelangs'] as $lang_i)
        {
            $langname = $language_array[$lang_i]['name'];
            $xtpl->assign('LANGSITENAME', $langname);
            $xtpl->assign('LANGSITEURL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . $lang_i);
            if (NV_LANG_DATA != $lang_i)
                $xtpl->parse('main.language.langitem');
            else
                $xtpl->parse('main.language.langcuritem');
        }
        $xtpl->parse('main.language');
    }

    //Breakcolumn
    if ($home != 1)
    {
        $arr_cat_title_i = array('catid' => 0, 'title' => $module_info['custom_title'], 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name);
        $xtpl->assign('BREAKCOLUMN', $arr_cat_title_i);
        $xtpl->parse('main.mod_title.breakcolumn');

        foreach ($array_mod_title as $arr_cat_title_i)
        {
            $xtpl->assign('BREAKCOLUMN', $arr_cat_title_i);
            $xtpl->parse('main.mod_title.breakcolumn');
        }
        $xtpl->parse('main.mod_title');
    }
    //Breakcolumn

    $theme_stat_img = "";
    $theme_footer_js = "";
    if (NV_LANG_INTERFACE == 'vi')
    {
        $theme_footer_js .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/mudim.js\"></script>";
    }
    $xtpl->assign('THEME_IMG_CRONJOBS', NV_BASE_SITEURL . "index.php?second=cronjobs&amp;p=" . nv_genpass());

    $xtpl->parse('main');
    $sitecontent = $xtpl->text('main');
    $sitecontent = nv_blocks_content($sitecontent);
    $my_footer = $theme_footer_js . $my_footer;
    if (defined('NV_IS_ADMIN'))
    {
        $my_footer = nv_admin_menu() . $my_footer;
    }
    if (!empty($my_head))
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . "\\1", $sitecontent, 1);
    if (!empty($my_footer))
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . "\\1", $sitecontent, 1);
    return $sitecontent;
}
?>