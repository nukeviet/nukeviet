<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

nv_add_hook($module_name, 'change_site_buffer', $priority, function ($vars) {
    $global_config = $vars[0];
    $return = $vars[1];
    $static_querystring = (empty($global_config['static_noquerystring']) and !empty($global_config['timestamp'])) ? '?t=' . $global_config['timestamp'] : '';

    if (!empty($global_config['cdn_url'])) {
        $return[0] = preg_replace_callback("/\<(script|link|img)([^\>]*)(src|href)=['\"]((?!(http(s?)\:)?\/\/)([^\>]*)\.(css|js|jpe?g|png|gif|webp|ttf|woff2?))['\"]([^\>]*)\>/i", function ($matches) use ($global_config, $static_querystring) {
            return '<' . $matches[1] . $matches[2] . $matches[3] . '="' . $global_config['cdn_url'] . $matches[4] . (($matches[8] == 'css' or $matches[8] == 'js') ? $static_querystring : '') . '"' . $matches[9] . '>';
        }, $return[0]);

        if (!empty($return[1]['link'])) {
            $return[1]['link'] = preg_replace_callback("/\<((?!(http(s?)\:)?\/\/)([^\>]*)\.(css|js|jpe?g|png|gif|webp|ttf|woff2?))\>/", function ($matches) use ($global_config, $static_querystring) {
                return '<' . $global_config['cdn_url'] . $matches[1] . (($matches[5] == 'css' or $matches[5] == 'js') ? $static_querystring : '') . '>';
            }, $return[1]['link']);
        }
    } elseif (!empty($static_querystring)) {
        $return[0] = preg_replace("/\<(script|link)(.*?)(src|href)=['\"]((?!(http(s?)\:)?\/\/)([^\>]*)\.(js|css))['\"](.*?)\>/", '<\\1\\2\\3="\\4' . $static_querystring . '"\\9>', $return[0]);
        !empty($return[1]['link']) && $return[1]['link'] = preg_replace("/\<((?!(http(s?)\:)?\/\/)([^\>]*)\.(js|css))\>/", '<\\1' . $static_querystring . '>', $return[1]['link']);
    }

    return $return;
});
