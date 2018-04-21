<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$global_array_genders = array(
    'N' => array(
        'key' => 'N',
        'title' => $nv_Lang->getModule('na'),
        'selected' => ''
    ),
    'M' => array(
        'key' => 'M',
        'title' => $nv_Lang->getModule('male'),
        'selected' => ''
    ),
    'F' => array(
        'key' => 'F',
        'title' => $nv_Lang->getModule('female'),
        'selected' => ''
    )
);
