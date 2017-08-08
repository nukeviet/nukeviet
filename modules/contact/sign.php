<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$sign_content = '<br /><br />----------<br />Best regards,<br /><br />' . $admin_info['full_name'] . '<br />';
if (!empty($admin_info['position'])) {
    $sign_content .= $admin_info['position'] . '<br />';
}
$sign_content .= '<br />';
$sign_content .= 'E-mail: ' . $admin_info['email'] . '<br />';
$sign_content .= 'Website: ' . $global_config['site_name'] . '<br />' . $global_config['site_url'];