<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_RSS')) {
    exit('Stop!!!');
}

/**
 * nv_rss_main_theme()
 *
 * @param string $array
 * @return string
 */
function nv_rss_main_theme($array)
{
    $array .= '<div class="tree well"><ul>';
    $array .= nv_get_rss_link();
    $array .= '</ul></div>';

    return $array;
}
