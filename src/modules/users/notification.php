<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

if ($data['type'] == 'remove_2step_request') {
    $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=edit_2step&amp;userid=' . $data['content']['uid'];
    $data['title'] = $nv_Lang->getModule('remove_2step_request', $data['content']['title']);
} else {
    $data['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $data['module'] . '&amp;' . NV_OP_VARIABLE . '=user_waiting';

    if ($data['type'] == 'send_active_link_fail') {
        $data['title'] = $nv_Lang->getModule('notification_sendactive_fail', $data['content']['title']);
    } else {
        $data['title'] = $nv_Lang->getModule('notification_new_acount', $data['content']['title']);
    }
}
