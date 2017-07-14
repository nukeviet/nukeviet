<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_USER', true);
define('NV_MOD_TABLE', ($module_data == 'users') ? NV_USERS_GLOBALTABLE : $db_config['prefix'] . '_' . $module_data);
define('NV_2STEP_VERIFICATION_MODULE', 'two-step-verification');

$lang_module['in_groups'] = $lang_global['in_groups'];
require NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * validUserLog()
 *
 * @param mixed $array_user
 * @param mixed $remember
 * @param mixed $opid
 * @return
 */
function validUserLog($array_user, $remember, $opid, $current_mode = 0)
{
    global $db, $global_config, $nv_Request, $lang_module, $global_users_config, $module_name, $client_info;

    $remember = intval($remember);
    $checknum = md5(nv_genpass(10));
    $user = array(
        'userid' => $array_user['userid'],
        'current_mode' => $current_mode,
        'checknum' => $checknum,
        'checkhash' => md5($array_user['userid'] . $checknum . $global_config['sitekey'] . $client_info['browser']['key']),
        'current_agent' => NV_USER_AGENT,
        'last_agent' => $array_user['last_agent'],
        'current_ip' => NV_CLIENT_IP,
        'last_ip' => $array_user['last_ip'],
        'current_login' => NV_CURRENTTIME,
        'last_login' => intval($array_user['last_login']),
        'last_openid' => $array_user['last_openid'],
        'current_openid' => $opid
    );

    $stmt = $db->prepare("UPDATE " . NV_MOD_TABLE . " SET
		checknum = :checknum,
		last_login = " . NV_CURRENTTIME . ",
		last_ip = :last_ip,
		last_agent = :last_agent,
		last_openid = :opid,
		remember = " . $remember . "
		WHERE userid=" . $array_user['userid']);

    $stmt->bindValue(':checknum', $checknum, PDO::PARAM_STR);
    $stmt->bindValue(':last_ip', NV_CLIENT_IP, PDO::PARAM_STR);
    $stmt->bindValue(':last_agent', NV_USER_AGENT, PDO::PARAM_STR);
    $stmt->bindValue(':opid', $opid, PDO::PARAM_STR);
    $stmt->execute();
    $live_cookie_time = ($remember) ? NV_LIVE_COOKIE_TIME : 0;

    $nv_Request->set_Cookie('nvloginhash', serialize($user), $live_cookie_time);

    if (!empty($global_users_config['active_user_logs'])) {
        $log_message = $opid ? ($lang_module['userloginviaopt'] . ' ' . $opid) : $lang_module['st_login'];
        nv_insert_logs(NV_LANG_DATA, $module_name, '[' . $array_user['username'] . '] ' . $log_message, ' Client IP:' . NV_CLIENT_IP, 0);
    }
}

/**
 * nv_del_user()
 *
 * @param mixed $userid
 * @return
 */
function nv_del_user($userid)
{
    global $db, $global_config, $module_name, $user_info, $lang_module;

    $sql = 'SELECT group_id, username, first_name, last_name, email, photo, in_groups, idsite FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid;
    $row = $db->query($sql)->fetch(3);
    if (empty($row)) {
        $return = 0;
    }

    list($group_id, $username, $first_name, $last_name, $email, $photo, $in_groups, $idsite) = $row;

    if ($global_config['idsite'] > 0 and $idsite != $global_config['idsite']) {
        return 0;
    }

    $query = $db->query('SELECT COUNT(*) FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id IN (1,2,3) AND userid=' . $userid);
    if ($query->fetchColumn()) {
        return 0;
    } else {
        $userdelete = (!empty($first_name)) ? $first_name . ' (' . $username . ')' : $username;

        $result = $db->exec('DELETE FROM ' . NV_MOD_TABLE . ' WHERE userid=' . $userid);
        if (!$result) {
            return 0;
        }

        $in_groups = explode(',', $in_groups);

        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id IN (SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid . ' AND approved = 1)');
        $db->query('UPDATE ' . NV_MOD_TABLE . '_groups SET numbers = numbers-1 WHERE group_id=' . (($group_id == 7 or in_array(7, $in_groups)) ? 7 : 4));
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_groups_users WHERE userid=' . $userid);
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_openid WHERE userid=' . $userid);
        $db->query('DELETE FROM ' . NV_MOD_TABLE . '_info WHERE userid=' . $userid);

        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_user', 'userid ' . $userid, $user_info['userid']);

        if (!empty($photo) and is_file(NV_ROOTDIR . '/' . $photo)) {
            @nv_deletefile(NV_ROOTDIR . '/' . $photo);
        }

        $subject = $lang_module['delconfirm_email_title'];
        $message = sprintf($lang_module['delconfirm_email_content'], $userdelete, $global_config['site_name']);
        $message = nl2br($message);
        nv_sendmail($global_config['site_email'], $email, $subject, $message);
        return $userid;
    }
}

// Xác định cấu hình module
$global_users_config = array();
$cacheFile = NV_LANG_DATA . '_' . $module_data . '_config_' . NV_CACHE_PREFIX . '.cache';
$cacheTTL = 3600;
if (($cache = $nv_Cache->getItem($module_name, $cacheFile, $cacheTTL)) != false) {
    $global_users_config = unserialize($cache);
} else {
    $sql = "SELECT config, content FROM " . NV_MOD_TABLE . "_config";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $global_users_config[$row['config']] = $row['content'];
    }
    $cache = serialize($global_users_config);
    $nv_Cache->setItem($module_name, $cacheFile, $cache, $cacheTTL);
}

$group_id = 0;
if (defined('NV_IS_USER') and isset($array_op[0]) and isset($array_op[1]) and ($array_op[0] == 'register' or $array_op[0] == 'editinfo')) {
    $sql = 'SELECT group_id, title, config FROM ' . NV_MOD_TABLE . '_groups';
    $_query = $db->query($sql);
    $group_lists = array();
    while ($_row = $_query->fetch()) {
        $group_lists[$_row['group_id']] = $_row;
    }

    //$group_lists = $nv_Cache->db($sql, 'group_id', $module_name);

    if (isset($group_lists[$array_op[1]])) { // trường hợp trưởng nhóm truy cập sửa thông tin member thì $array_op[1]= group_id
        $result = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users WHERE group_id = ' . $array_op[1] . ' AND userid = ' . $user_info['userid'] . ' AND is_leader = 1');

        if ($row = $result->fetch()) {
            $group = $group_lists[$row['group_id']];
            $group['config'] = unserialize($group['config']);

            if ($group['config']['access_addus'] and $array_op[0] == 'register') { // đăng kí
                $op = 'register';
                $module_info['funcs'][$op] = $sys_mods[$module_name]['funcs'][$op];
                $group_id = $row['group_id'];
                define('ACCESS_ADDUS', $group['config']['access_addus']);
            } else
                if ($group['config']['access_editus'] and $array_op[0] == 'editinfo') { // sửa thông tin
                    $group_id = $row['group_id'];

                    $result = $db->query('SELECT group_id FROM ' . NV_MOD_TABLE . '_groups_users
						WHERE group_id = ' . $group_id . ' and userid = ' . $array_op[2] . ' and is_leader = 0');

                    if ($row = $result->fetch()) { // nếu tài khoản nằm trong nhóm đó thì được quyền sửa
                        $userid = $array_op[2];

                        if ($group['config']['access_passus']) {
                            define('ACCESS_PASSUS', $group['config']['access_passus']);
                        }
                        define('ACCESS_EDITUS', $group['config']['access_editus']);
                    }
                }
        }
    }
}
