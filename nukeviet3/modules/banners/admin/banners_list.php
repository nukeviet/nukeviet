<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/12/2010 12:34
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['banners_list'];

$contents = array();
$contents['containerid'] = "banners_list";
$contents['aj'] = "nv_show_banners_list('banners_list', 0, 0, 1);";

$contents = call_user_func( "nv_banners_list_theme", $contents );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>