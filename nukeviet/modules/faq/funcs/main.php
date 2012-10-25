<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_FAQ' ) ) die( 'Stop!!!' );

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$description = $lang_module['faq_welcome'];

$contents = "";

if ( empty( $list_cats ) and ! $module_setting['type_main'] )
{
    $page_title = $module_info['custom_title'];

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

//Xem theo chu de
if ( ! empty( $alias ) and $catid )
{
    $page_title = $module_info['custom_title'] . " - " . $list_cats[$catid]['title'];
    $description = $list_cats[$catid]['description'];
    $mod_title = $list_cats[$catid]['name'];

    $query = "SELECT `id`,`title`, `question`, `answer` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `catid`=" . $catid . " AND `status`=1 ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );

    $faq = array();

    while ( list( $fid, $ftitle, $fquestion, $fanswer ) = $db->sql_fetchrow( $result ) )
    {
        $faq[$fid] = array( //
            'id' => $fid, //
            'title' => $ftitle, //
            'question' => $fquestion, //
            'answer' => $fanswer //
            );
    }

    if ( ! empty( $list_cats[$catid]['keywords'] ) )
    {
        $key_words = $list_cats[$catid]['keywords'];
    } elseif ( ! empty( $faq ) )
    {
        $key_words = update_keywords( $catid, $faq );
    }

    $contents = theme_cat_faq( $list_cats, $catid, $faq, $mod_title );

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}
elseif ( $module_setting['type_main'] == 0 )
{
	$contents = theme_main_faq( $list_cats, $mod_title );
}
elseif ( $module_setting['type_main'] == 1 or  $module_setting['type_main'] == 2 )
{
	$order = ( $module_setting['type_main'] == 1 ) ? "DESC" : "ASC";
	
    $query = "SELECT `id`,`title`, `question`, `answer` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `status`=1 ORDER BY `addtime` " . $order;
    $result = $db->sql_query( $query );

    $faq = array();

    while ( list( $fid, $ftitle, $fquestion, $fanswer ) = $db->sql_fetchrow( $result ) )
    {
        $faq[$fid] = array( //
            'id' => $fid, //
            'title' => $ftitle, //
            'question' => $fquestion, //
            'answer' => $fanswer //
        );
    }
	
    $contents = theme_cat_faq( array(), 0, $faq, $mod_title );
}
else
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>