<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC.
 * All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

function _tests_default_server()
{
    $_SERVER['HTTP_HOST'] = NV_TESTS_DOMAIN;
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '';
    $_SERVER['SERVER_NAME'] = NV_TESTS_DOMAIN;
    $_SERVER['SERVER_PORT'] = '80';
    $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
    $_SERVER['HTTP_USER_AGENT'] = 'NUKEVIET CMS. Developed by VINADES. Url: http://nukeviet.vn';
    $_SERVER['SERVER_SOFTWARE'] = 'sd';
}
