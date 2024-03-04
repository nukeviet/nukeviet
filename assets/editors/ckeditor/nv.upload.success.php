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

if ($responseType == 'json') {
    $array_data = [];
    $array_data['uploaded'] = 1;
    $array_data['fileName'] = $upload_info['basename'];
    $array_data['url'] = NV_BASE_SITEURL . $path . '/' . $upload_info['basename'];

    nv_jsonOutput($array_data);
} else {
    nv_jsonOutput([
        'uploaded' => 1,
        'fileName' => $upload_info['basename'],
        'url' => NV_BASE_SITEURL . $path . '/' . $upload_info['basename']
    ]);
}
