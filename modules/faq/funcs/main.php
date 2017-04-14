<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if (! defined('NV_IS_MOD_FAQ')) {
    die('Stop!!!');
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $lang_module['faq_welcome'];
if(!empty($array_op[1]))
$str=explode ( '-' , $array_op[1]);
if(!empty($str[1])) {
	$page=$str[1];
}
else {
	$page=1;
}
$per_page = 20;
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main';
if (empty($list_cats) and ! $module_setting['type_main']) {
    $page_title = $module_info['custom_title'];

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

//Xem theo chu de
if (! empty($alias) and $catid) {
    $page_title = $module_info['custom_title'] . " - " . $list_cats[$catid]['title'];
    $description = $list_cats[$catid]['description'];
    $mod_title = $list_cats[$catid]['name'];

    $query = "SELECT id,title, question, answer FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $catid . " AND status!=0 ORDER BY weight ASC";
    $all_page = $db->query($query)->rowCount();;
     if (!empty($page)) {
    $query .= ' LIMIT ' . $per_page . ' OFFSET ' . ($page - 1) * $per_page;
	} else {
	    $query .= ' LIMIT ' . $per_page;
	}
    $result = $db->query($query);
    $faq = array();

    while (list($fid, $ftitle, $fquestion, $fanswer) = $result->fetch(3)) {
        $faq[$fid] = array(
            'id' => $fid,
            'title' => $ftitle,
            'question' => $fquestion,
            'answer' => $fanswer
        );
    }

    if (! empty($list_cats[$catid]['keywords'])) {
        $key_words = $list_cats[$catid]['keywords'];
    } elseif (! empty($faq)) {
        $key_words = update_keywords($catid, $faq);
    }
	$generate_page = nv_alias_page($page_title,$base_url, $all_page, $per_page, $page);
    $contents = theme_cat_faq($list_cats, $catid, $faq,$generate_page);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
} elseif ($module_setting['type_main'] == 0) {
    $contents = theme_main_faq($list_cats);
} elseif ($module_setting['type_main'] == 1 or $module_setting['type_main'] == 2) {
    $order = ($module_setting['type_main'] == 1) ? "DESC" : "ASC";

    $query = "SELECT `id`,`title`,`question`,`answer`,`pubtime`,`userid`,`hitstotal`,alias FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE status!=0 ORDER BY addtime " . $order;
    $all_page = $db->query($query)->rowCount();
     if (!empty($page)) {
    $query .= ' LIMIT ' . $per_page . ' OFFSET ' . ($page - 1) * $per_page;
	} else {
	    $query .= ' LIMIT ' . $per_page;
	}
    $result = $db->query($query);
    $faq = array();

    while (list($fid, $ftitle, $fquestion, $fanswer,$fpubtime,$fuserid,$fhitstotal,$falias) = $result->fetch(3)) {
    	$query = $db->query('SELECT `username` FROM '.NV_USERS_GLOBALTABLE.' WHERE `userid`= '. $fuserid)->fetch();
        $faq[$fid] = array(
            'id' => $fid,
            'title' => $ftitle,
            'question' => $fquestion,
            'answer' => $fanswer,
            'pubtime'=>$fpubtime,
            'customer'=>$query['username'],
            'hitstotal'=>$fhitstotal,
            'alias'=>$falias
        );
    }
	$generate_page = nv_alias_page($page_title,$base_url, $all_page, $per_page, $page);
    $contents = theme_cat_faq(array(), 0, $faq,$generate_page);
} else {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
