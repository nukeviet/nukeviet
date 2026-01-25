<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

nv_add_hook($module_name, 'change_site_buffer', $priority, function ($vars) {
    $global_config = $vars[0];
    $return = $vars[1];

    // Xác định giá trị Query string được thêm vào sau các tệp JS/CSS giúp trình duyệt cập nhật lại nội dung khi có thay đổi
    $static_querystring = (empty($global_config['static_noquerystring']) and !empty($global_config['timestamp'])) ? '?t=' . $global_config['timestamp'] : '';

    // Trường hợp sử dụng CDN
    if (!empty($global_config['cdn_url'])) {
        // 1. Thay thế liên kết nội bộ của các tệp tĩnh trong các thẻ script|link|img (trong nội dung trang) thành liên kết bên ngoài sử dụng CDN
        // 2. Thêm Query string vào sau các tệp tĩnh của nội dung trang
        $return[0] = preg_replace_callback("/\<(script|link|img)([^\>]*)(src|href)=['\"]((?!(http(s?)\:)?\/\/)([^\>]*)\.(css|js|jpe?g|png|gif|webp|svg|ttf|woff2?))['\"]([^\>]*)\>/i", function ($matches) use ($global_config, $static_querystring) {
            return '<' . $matches[1] . $matches[2] . $matches[3] . '="' . $global_config['cdn_url'] . $matches[4] . (($matches[8] == 'css' or $matches[8] == 'js') ? $static_querystring : '') . '"' . $matches[9] . '>';
        }, $return[0]);

        // Thay thế các liên kết nội bộ của hình ảnh sử dụng trong CSS-inline (trong nội dung trang) thành liên kết bên ngoài sử dụng CDN
        $return[0] = preg_replace("/(background(-image)?\s*\:[^\;\>\}]*url\(\s*(\"|')?)(?!(http(s?)\:)?\/\/)([^\)]+\.(jpe?g|png|gif|webp|svg))((\"|')?\s*\))/i", '\\1' . $global_config['cdn_url'] . '\\6\\8', $return[0]);

        // 1. Thay thế liên kết nội bộ của các tệp tĩnh có trong các tiêu đề HTTP thành liên kết bên ngoài sử dụng CDN
        // 2. Thêm Query string vào sau các tệp tĩnh của tiêu đề HTTP
        if (!empty($return[1]['link'])) {
            $return[1]['link'] = preg_replace_callback("/\<((?!(http(s?)\:)?\/\/)([^\>]*)\.(css|js|jpe?g|png|gif|webp|svg|ttf|woff2?))\>/", function ($matches) use ($global_config, $static_querystring) {
                return '<' . $global_config['cdn_url'] . $matches[1] . (($matches[5] == 'css' or $matches[5] == 'js') ? $static_querystring : '') . '>';
            }, $return[1]['link']);
        }
    }
    // Nếu không sử dụng CDN nhưng Query string có giá trị không phải rỗng
    elseif (!empty($static_querystring)) {
        // Thêm Query string vào sau các tệp tĩnh của nội dung trang
        $return[0] = preg_replace("/\<(script|link)(.*?)(src|href)=['\"]((?!(http(s?)\:)?\/\/)([^\>]*)\.(js|css))['\"](.*?)\>/", '<\\1\\2\\3="\\4' . $static_querystring . '"\\9>', $return[0]);
        // Thêm Query string vào sau các tệp tĩnh của tiêu đề HTTP
        !empty($return[1]['link']) && $return[1]['link'] = preg_replace("/\<((?!(http(s?)\:)?\/\/)([^\>]*)\.(js|css))\>/", '<\\1' . $static_querystring . '>', $return[1]['link']);
    }

    return $return;
});
