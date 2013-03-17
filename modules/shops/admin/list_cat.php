<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );

$contents = nv_show_cat_list( $parentid );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $contents;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>