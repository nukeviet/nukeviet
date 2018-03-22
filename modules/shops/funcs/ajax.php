<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if (!defined('NV_IS_MOD_SHOPS')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('changesprice', 'post')) {
    $sorts = $nv_Request->get_int('sort', 'post', 0);
    $nv_Request->set_Session('sorts', $sorts, NV_LIVE_SESSION_TIME);
    $nv_Cache->delMod($module_name);
    die('OK');
}

if ($nv_Request->isset_request('changeviewtype', 'post')) {
    $viewtype = $nv_Request->get_string('viewtype', 'post', '');
    $nv_Request->set_Session('viewtype', $viewtype, NV_LIVE_SESSION_TIME);
    $nv_Cache->delMod($module_name);
    die('OK');
}