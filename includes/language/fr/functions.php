<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * plural()
 *
 * @param mixed  $number
 * @param string $word
 * @return string
 */
function plural($number, $word)
{
    $wordObj = array_map('trim', explode(',', $word));
    $number2 = (int) $number;
    $word = $number2 > 1 ? $wordObj[1] : $wordObj[0];

    return $number . ' ' . $word;
}
