<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$content = $nv_Request->get_title('content', 'post', '', 1);
$keywords = nv_get_keywords($content);

include NV_ROOTDIR . '/includes/header.php';
echo $keywords;
include NV_ROOTDIR . '/includes/footer.php';
