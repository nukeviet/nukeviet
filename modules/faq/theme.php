<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (! defined('NV_IS_MOD_FAQ')) {
    die('Stop!!!');
}

/**
 * theme_main_faq()
 *
 * @param mixed $list_cats
 * @return
 */
function theme_main_faq($list_cats)
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file,$user_info,$module_setting;

    $xtpl = new XTemplate("main_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/");
    $xtpl->assign('LANG', $lang_module);
	$link_qa=NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=insertqa";
    $xtpl->assign('LINKQA', $link_qa);
	$link_listqa=NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=list";
    $xtpl->assign('LINKLISTQA', $link_listqa);
	if(!empty($user_info) and $module_setting['user_post']==1) {
		$xtpl->parse('main.isuser');
	}
    $xtpl->assign('WELCOME', $lang_module['faq_welcome']);
    $xtpl->parse('main.welcome');

    foreach ($list_cats as $cat) {
        if (! $cat['parentid']) {
            $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
            $cat['name'] = "<a href=\"" . $cat['link'] . "\">" . $cat['title'] . "</a>";
            $xtpl->assign('SUBCAT', $cat);
            if (! empty($cat['description'])) {
                $xtpl->parse('main.subcats.li.description');
            }
            $xtpl->parse('main.subcats.li');
        }
    }
    $xtpl->parse('main.subcats');

    $xtpl->parse('main');
    return $xtpl->text('main');
}
/**
 * theme_insert_faq()
 *
 * @param mixed $list_cats
 * @param mixed $catid
 * @param mixed $faq
 * @return
 */
function theme_insert_faq($array,$error,$listcats,$id)
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file;
	$xtpl = new XTemplate("insertqa.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/");
    if (defined('IS_EDIT')) {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name ."&amp;". NV_OP_VARIABLE . "=insertqa&amp;edit=1&amp;id=" . $id);
    } else {
        $xtpl->assign('FORM_ACTION', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name ."&amp;". NV_OP_VARIABLE . "=insertqa&amp;add=1");
    }

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('DATA', $array);

    if (! empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    foreach ($listcats as $cat) {
        $xtpl->assign('LISTCATS', $cat);
        $xtpl->parse('main.catid');
    }
	if ($global_config['captcha_type'] == 2) {
        $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
        $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
        $xtpl->parse('main.recaptcha');
    } else {
        $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
        $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
        $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
        $xtpl->parse('main.captcha');
    }
	$xtpl->parse('main');
    return $xtpl->text('main');

}
/**
 * theme_insert_faq()
 *
 * @param mixed $list_cats
 * @param mixed $catid
 * @param mixed $faq
 * @return
 */
function theme_viewlist_faq($array_accept,$generate_page_accept,$array_not_accept,$generate_page_not_accept)
{
	global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file,$op;
	$xtpl = new XTemplate("viewlist.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/");
	$xtpl->assign('LANG', $lang_module);
	$xtpl->assign('GLANG', $lang_global);

	$xtpl->assign('ADD_NEW_FAQ', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=insertqa");

if (defined('NV_IS_CAT')) {
    $xtpl->parse('main.is_cat1');
}
//view cau hoi chua duoc duyet
if (! empty($array_not_accept)) {
    $a = 0;
    foreach ($array_not_accept as $row) {
        $xtpl->assign('CLASS', $a % 2 == 1 ? " class=\"second\"" : "");
		$chechss=md5($row['id'] . NV_CHECK_SESSION);
        $xtpl->assign('CHECKSS', $chechss);
        $xtpl->assign('ROW', $row);
        $xtpl->assign('EDIT_URL', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name ."&amp;". NV_OP_VARIABLE . "=insertqa&amp;edit=1&amp;id=" . $row['id']);
        $xtpl->parse('main.main_not_accept.row_not_accept');
        ++$a;
    }
	if (! empty($generate_page_not_accept)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page_not_accept);
    $xtpl->parse('main.main_not_accept.generate_page_not_accept');
	}
	$xtpl->parse('main.main_not_accept');
}
//view cau hoi dc duoc duyet
if (! empty($array_accept)) {
    $a = 0;
    foreach ($array_accept as $row) {
        $xtpl->assign('CLASS', $a % 2 == 1 ? " class=\"second\"" : "");
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.main_accept.row_accept');
        ++$a;
    }
	if (! empty($generate_page_accept)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page_accept);
    $xtpl->parse('main.main_accept.generate_page_accept');
	}
	$xtpl->parse('main.main_accept');
}


	$xtpl->parse('main');
    return $xtpl->text('main');
}
/**
 * theme_cat_faq()
 *
 * @param mixed $list_cats
 * @param mixed $catid
 * @param mixed $faq
 * @return
 */
function theme_cat_faq($list_cats, $catid, $faq,$generate_page)
{
    global $global_config, $lang_module, $lang_global, $module_info, $module_name, $module_file,$user_info,$module_setting;

    $xtpl = new XTemplate("main_page.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/");
    $xtpl->assign('LANG', $lang_module);
	$link_qa=NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=insertqa";
    $xtpl->assign('LINKQA', $link_qa);
   	$link_listqa=NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=list";
    $xtpl->assign('LINKLISTQA', $link_listqa);
	if(!empty($user_info) and $module_setting['user_post']==1) {
		$xtpl->parse('main.isuser');
	}
    if (! empty($list_cats[$catid]['description'])) {
        $xtpl->assign('WELCOME', $list_cats[$catid]['description']);
        $xtpl->parse('main.welcome');
    }

    if (! empty($list_cats[$catid]['subcats'])) {
        foreach ($list_cats[$catid]['subcats'] as $subcat) {
            $cat = $list_cats[$subcat];
            $cat['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat['alias'];
            $cat['name'] = "<a href=\"" . $cat['link'] . "\">" . $cat['title'] . "</a>";
            $xtpl->assign('SUBCAT', $cat);
            if (! empty($cat['description'])) {
                $xtpl->parse('main.subcats.li.description');
            }
            $xtpl->parse('main.subcats.li');
        }
        $xtpl->parse('main.subcats');
    }

    if (! empty($faq)) {
        foreach ($faq as $row) {
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.is_show_row.row');
        }

        $xtpl->assign('IMG_GO_TOP_SRC', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_name . '/');

        foreach ($faq as $row) {
            $xtpl->assign('ROW', $row);
            $xtpl->parse('main.is_show_row.detail');
        }

        $xtpl->parse('main.is_show_row');
    }
	if(!empty($generate_page))
	$xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main');
    return $xtpl->text('main');
}
