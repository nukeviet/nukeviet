<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Gọi global bởi có trường hợp file footer.php include từ trong hàm
global $global_config, $headers;

if (defined('NV_SYSTEM') and !empty($global_config['cdn_url'])) {
    $contents = preg_replace("/\<(script|link|img)([^\>]*)(src|href)=['\"]((?!http(s?)\:\/\/)([^\>]*)\.(css|js|jpg|png|gif))['\"]([^\>]*)\>/i", '<\\1\\2\\3="//' . $global_config['cdn_url'] . '\\4?t=' . $global_config['timestamp'] . '"\\8>', $contents);
} else {
    $contents = preg_replace("/\<(script|link)(.*?)(src|href)=['\"]((?!http(s?)\:\/\/).*?\.(js|css))['\"](.*?)\>/", '<\\1\\2\\3="\\4?t=' . $global_config['timestamp'] . '"\\7>', $contents);
}

if (!empty($headers['link'])) {
    $headers['link'] = preg_replace("/\<((?!http(s?)\:\/\/).*?\.(js|css))\>/", '<\\1?t=' . $global_config['timestamp'] . '>', $headers['link']);
}
