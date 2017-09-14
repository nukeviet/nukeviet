<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if (!defined('NV_IS_AJAX')) {
    die('Wrong URL');
}

$userids = $nv_Request->get_title('userid', 'post', '');
$userids = array_filter(array_unique(array_map('intval', array_map('trim', explode(',', $userids)))));
$setactive = $nv_Request->get_int('setactive', 'post', -1);

foreach ($userids as $userid) {
    if (!$userid or $admin_info['admin_id'] == $userid) {
        continue;
    }

    $sql = 'SELECT a.lev, b.username, b.active, b.idsite FROM ' . NV_AUTHORS_GLOBALTABLE . ' a, ' . NV_MOD_TABLE . ' b WHERE a.admin_id=' . $userid . ' AND a.admin_id=b.userid';
    $row = $db->query($sql)->fetch(3);
    if (empty($row)) {
        $level = 0;
        $sql = 'SELECT username, active, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
        list($username, $active, $idsite) = $db->query($sql)->fetch(3);
    } else {
        list($level, $username, $active, $idsite) = $row;
        $level = (int)$level;
    }

    if (empty($level) or $admin_info['level'] < $level) {
        if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
            continue;
        }
        if ($setactive < 0) {
            $active = $active ? 0 : 1;
        } elseif ($setactive == 0) {
            $active = 0;
        } else {
            $active = 1;
        }

        $sql = 'UPDATE ' . NV_MOD_TABLE . ' SET active=' . $active . ' WHERE userid=' . $userid;
        $result = $db->query($sql);

        $note = ($active) ? $lang_module['active_users'] : $lang_module['unactive_users'];
        nv_insert_logs(NV_LANG_DATA, $module_name, $note, 'userid: ' . $userid . ' - username: ' . $username, $admin_info['userid']);
    }
}

$nv_Cache->delMod($module_name);
nv_htmlOutput('OK');
