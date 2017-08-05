<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_RSS_ADMIN', true);

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:feeds';

$allow_func = array(
    'main'
);