<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$sign_content = '<br /><br />----------<br />Best regards,<br /><br />' . $admin_info['full_name'] . '<br />';
if (!empty($admin_info['position'])) {
    $sign_content .= $admin_info['position'] . '<br />';
}
$sign_content .= '<br />';
$sign_content .= 'E-mail: ' . $admin_info['email'] . '<br />';
//$sign_content .= 'Website: ' . $global_config['site_name'] . '<br />' . $global_config['site_url'];
