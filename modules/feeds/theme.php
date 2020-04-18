<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_RSS')) {
    die('Stop!!!');
}

/**
 * nv_rss_main_theme()
 *
 * @param mixed $array
 * @return
 */
function nv_rss_main_theme($array)
{
    $array .= '<div class="tree well"><ul>';
    $array .= nv_get_rss_link();
    $array .= '</ul></div>';

    return $array;
}