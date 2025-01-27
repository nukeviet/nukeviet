<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/**
 * Tạo tệp SQL lấy row đăng nhập quản trị khớp với các phương thức còn lại
 * chỉ sử dụng khi đăng nhập quản trị bằng passkey
 */

/**
 * @param string $keyid
 * @param string|null $userhandle
 * @return array|false
 */
function check_admin_login(string $keyid, ?string $userhandle): array|false
{
    global $db;

    $sql = 'SELECT
        t1.admin_id admin_id, t1.lev admin_lev, t1.last_agent admin_last_agent, t1.last_ip admin_last_ip, t1.last_login admin_last_login,
        t2.userid, t2.last_agent, t2.last_ip, t2.last_login, t2.last_openid, t2.username, t2.email, t2.password, t2.active2step, t2.in_groups, t2.secretkey,
        t3.id passkey_id, t3.enable_login, t3.keyid, t3.userhandle, t3.publickey, t3.counter keycounter, t3.aaguid, t3.type keytype, t3.nickname passkey_name
    FROM ' . NV_USERS_GLOBALTABLE . '_passkey t3
    INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t2.userid=t3.userid
    INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' t1 ON t1.admin_id=t3.userid
    WHERE t3.userhandle=:userhandle AND t3.keyid=:keyid AND t1.lev!=0 AND t1.is_suspend=0 AND t2.active=1';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':userhandle', $userhandle, PDO::PARAM_STR);
    $stmt->bindParam(':keyid', $keyid, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch();
}
