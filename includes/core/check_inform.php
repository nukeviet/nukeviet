<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_SYS_LOAD')) {
    exit('Stop!!!');
}

$count = 0;
$userid = $nv_Request->get_int('__userid', 'post', 0);
$groups = $nv_Request->get_title('__groups', 'post', '');
$csrf = $nv_Request->get_title('_csrf', 'post', '');
$checkss = md5($userid . $groups . NV_CHECK_SESSION);
if ($userid and hash_equals($checkss, $csrf)) {
    nv_apply_hook('', 'check_inform', [$userid, $groups]);
    $dbinfo = $db->query('SELECT inform FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid = ' . $userid)->fetchColumn();
    unset($matches);
    if ($dbinfo and preg_match('/^([0-9]+)\|([0-9]{10,11})$/', $dbinfo, $matches) and ((int) $matches[2] > (NV_CURRENTTIME - 1800))) {
        $count = (int) $matches[1];
    } else {
        $groups = preg_replace('/[^0-9\,]+/', '', $groups);

        $where = [];
        $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
        if (!empty($groups)) {
            $where[] = "(mtb.receiver_grs != '' AND (CONCAT(',', mtb.receiver_grs, ',') REGEXP ',(" . str_replace(',', '|', $groups) . "),'))";
        }
        $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $userid . ', mtb.receiver_ids))';
        $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
        if (!empty($groups)) {
            $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . $groups . ')))';
        } else {
            $where .= " AND (mtb.sender_role != 'group')";
        }
    
        $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE (exc.pid = mtb.id AND exc.userid = ' . $userid . ') AND (exc.shown_time != 0 OR exc.hidden_time != 0))';
        $sql = 'SELECT COUNT(mtb.id) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . $where;
        $count = (int) $db->query($sql)->fetchColumn();
        $db->query('UPDATE ' . NV_USERS_GLOBALTABLE . "_info SET inform='" . $count . "|" . NV_CURRENTTIME . "' WHERE userid=" . $userid);
    }
}

nv_jsonOutput([
    'count' => $count
]);
