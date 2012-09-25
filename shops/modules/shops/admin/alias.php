<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$title = filter_text_input( 'title', 'post', '' );
$alias = change_alias( $title );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $alias;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>