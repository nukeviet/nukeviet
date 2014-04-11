<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$content = $nv_Request->get_title( 'content', 'post', '', 1 );
$keywords = nv_get_keywords( $content );

include NV_ROOTDIR . '/includes/header.php';
echo $keywords;
include NV_ROOTDIR . '/includes/footer.php';