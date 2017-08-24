<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@tdfoss.vn)
 * @Copyright (C) 2017 TDFOSS.,LTD. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21/07/2017 13:45
 */
if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
    nv_redirect_location('http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '') . '://' . substr($_SERVER['HTTP_HOST'], 4) . $_SERVER['REQUEST_URI']);
}