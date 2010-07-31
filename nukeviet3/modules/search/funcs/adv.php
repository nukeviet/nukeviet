<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate 04/05/2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

$key_words = $module_info['keywords'];

$mod_title = isset( $lang_module['search_title_adv'] ) ? $lang_module['search_title_adv'] : $module_info['custom_title'];

$key = filter_text_input( 'q', 'post', '', 1, 1000 );

$mod = filter_text_input( 'mod', 'post', 'all', 1 );

$_SESSION["keyword"] = $key;

if ( $mod == 'all' )

{
    
    $url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

}

else

{
    
    $url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $mod . "&" . NV_OP_VARIABLE . "=search";

}

Header( 'Location:' . $url );
exit();

?> 