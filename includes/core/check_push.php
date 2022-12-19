<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$count = 0;
$userid = $nv_Request->get_int('__userid', 'post', 0);
$csrf = $nv_Request->get_title('_csrf', 'post', '');
$checkss = md5($userid . NV_CHECK_SESSION);
if ($userid and hash_equals($checkss, $csrf)) {
    $groups = $nv_Request->get_title('__groups', 'post', '');
    $groups = preg_replace('/[^0-9\,]+/', '', $groups);

    $where = [];
    $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
    if (!empty($groups)) {
        $array_groups = explode(',', $groups);
        $array_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $array_groups))));

        $wh = [];
        foreach ($array_groups as $gr) {
            $wh[] = 'FIND_IN_SET(' . $gr . ', mtb.receiver_grs)';
        }
        $wh = implode(' OR ', $wh);
        $where[] = "(mtb.receiver_grs != '' AND (" . $wh . '))';
    }
    $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $userid . ', mtb.receiver_ids))';
    $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
    if (!empty($groups)) {
        $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . $groups . ')))';
    } else {
        $where .= " AND (mtb.sender_role != 'group')";
    }

    $where .= ' AND NOT EXISTS (SELECT * FROM ' . NV_PUSH_STATUS_GLOBALTABLE . ' AS exc WHERE (exc.pid = mtb.id AND exc.userid = ' . $userid . ') AND (exc.shown_time != 0 OR exc.hidden_time != 0))';
    $sql = 'SELECT mtb.id FROM ' . NV_PUSH_GLOBALTABLE . ' AS mtb WHERE ' . $where;
    $result = $db->query($sql);
    if ($result) {
        $count = $result->rowCount();
    }
}

nv_jsonOutput([
    'count' => $count
]);
