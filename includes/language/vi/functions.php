<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_MAINFILE'))
{
    die('Stop!!!');
}

function plural($n,$w)
{
    $w = array_map("trim",explode(",",$w));
    return $n . " " . $w[0];
}
