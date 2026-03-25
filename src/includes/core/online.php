<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_online_upd()
 *
 * @throws PDOException
 */
function nv_online_upd()
{
    global $db, $client_info, $user_info;
    $userid = 0;
    $username = 'guest';
    if (isset($user_info['userid']) and $user_info['userid'] > 0) {
        $userid = $user_info['userid'];
        $username = $user_info['username'];
    } elseif ($client_info['is_bot']) {
        $username = 'bot:' . $client_info['browser']['name'];
    }

    try {
        $sql = 'INSERT INTO ' . NV_SESSIONS_GLOBALTABLE . ' (session_id, userid, username, onl_time)
            VALUES (:session_id, :userid, :username, :onl_time)
            ON DUPLICATE KEY UPDATE userid = VALUES(userid), username = VALUES(username), onl_time = VALUES(onl_time)';
        $sth = $db->prepare($sql);
        $sth->bindValue(':session_id', $client_info['session_id'], PDO::PARAM_STR);
        $sth->bindValue(':userid', $userid, PDO::PARAM_INT);
        $sth->bindValue(':username', $username, PDO::PARAM_STR);
        $sth->bindValue(':onl_time', NV_CURRENTTIME, PDO::PARAM_INT);
        $sth->execute();
    }
    catch (Throwable $e) {
        trigger_error($e);
    }
}

nv_online_upd();
