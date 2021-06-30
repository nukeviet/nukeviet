<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_admin_checkip()
 *
 * @return
 */
function nv_admin_checkip()
{
    global $global_config;

    if ($global_config['block_admin_ip']) {
        if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php')) {
            $array_adminip = [];
            include NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php';

            if (empty($array_adminip)) {
                return true;
            }

            global $ips;

            foreach ($array_adminip as $ip_i => $array_ip) {
                if (
                    $array_ip['begintime'] < NV_CURRENTTIME and ($array_ip['endtime'] == 0 or $array_ip['endtime'] > NV_CURRENTTIME) and (
                        (empty($array_ip['ip6']) and preg_replace($array_ip['mask'], '', NV_CLIENT_IP) == preg_replace($array_ip['mask'], '', $ip_i)) or
                        (!empty($array_ip['ip6']) and $ips->checkIp6(NV_CLIENT_IP, $array_ip['mask']) === true))
                ) {
                    return true;
                }
            }

            return false;
        }
    }

    return true;
}

/**
 * nv_admin_checkfirewall()
 *
 * @return
 */
function nv_admin_checkfirewall()
{
    global $global_config;

    if ($global_config['admfirewall']) {
        if (file_exists(NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php')) {
            $adv_admins = [];
            include NV_ROOTDIR . '/' . NV_DATADIR . '/admin_config.php';

            if (empty($adv_admins)) {
                return true;
            }

            $auth = nv_set_authorization();

            if (empty($auth['auth_user']) or empty($auth['auth_pw'])) {
                return false;
            }

            $md5_auth_user = md5($auth['auth_user']);
            if (isset($adv_admins[$md5_auth_user])) {
                $array_us = $adv_admins[$md5_auth_user];
                if ($array_us['password'] == md5($auth['auth_pw']) and $array_us['begintime'] < NV_CURRENTTIME and ($array_us['endtime'] == 0 or $array_us['endtime'] > NV_CURRENTTIME)) {
                    return true;
                }
            }

            return false;
        }
    }

    return true;
}

/**
 * nv_admin_checkdata()
 *
 * @param mixed $adm_session_value
 * @return
 */
function nv_admin_checkdata($adm_session_value)
{
    global $db, $global_config;

    $array_admin = json_decode($adm_session_value, true);

    if (
        !is_array($array_admin) or !isset($array_admin['admin_id'])
        or !is_numeric($array_admin['admin_id']) or $array_admin['admin_id'] <= 0
        or !isset($array_admin['checknum']) or !preg_match('/^[a-z0-9]{32}$/', $array_admin['checknum'])
    ) {
        return [];
    }

    $sql = 'SELECT a.admin_id admin_id, a.lev lev, a.position position, a.main_module main_module, a.admin_theme admin_theme, a.check_num check_num, a.last_agent current_agent,
        a.last_ip current_ip, a.last_login current_login, a.files_level files_level, a.editor editor, b.userid userid, b.group_id group_id,
        b.username username, b.email email, b.first_name first_name, b.last_name last_name, b.view_mail view_mail, b.regdate regdate,
        b.sig sig, b.gender gender, b.photo photo, b.birthday birthday, b.in_groups in_groups, b.active2step active2step, b.last_openid last_openid,
        b.password password, b.question question, b.answer answer, b.safemode safemode, b.email_verification_time email_verification_time
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' a, ' . NV_USERS_GLOBALTABLE . ' b
        WHERE a.admin_id = ' . $array_admin['admin_id'] . '
        AND a.lev!=0
        AND a.is_suspend=0
        AND b.userid=a.admin_id
        AND b.active=1';
    $admin_info = $db->query($sql)->fetch();
    if (empty($admin_info)) {
        return [];
    }

    if (
        ($array_admin['checknum'] !== $admin_info['check_num']) // Check_num
        or !isset($array_admin['current_agent']) or empty($array_admin['current_agent']) or ($array_admin['current_agent'] !== $admin_info['current_agent']) // User_agent
        or !isset($array_admin['current_ip']) or empty($array_admin['current_ip']) or ($array_admin['current_ip'] !== $admin_info['current_ip']) // IP
        or !isset($array_admin['current_login']) or empty($array_admin['current_login']) or ($array_admin['current_login'] !== (int) ($admin_info['current_login'])) // Current_login
    ) {
        return [];
    }

    if (empty($admin_info['files_level'])) {
        $allow_files_type = [];
        $allow_modify_files = $allow_create_subdirectories = $allow_modify_subdirectories = 0;
    } else {
        list($allow_files_type, $allow_modify_files, $allow_create_subdirectories, $allow_modify_subdirectories) = explode('|', $admin_info['files_level']);
        $allow_files_type = !empty($allow_files_type) ? explode(',', $allow_files_type) : [];
        $allow_files_type2 = array_values(array_intersect($allow_files_type, $global_config['file_allowed_ext']));
        if ($allow_files_type != $allow_files_type2) {
            $update = implode(',', $allow_files_type2);
            $update .= '|' . $allow_modify_files . '|' . $allow_create_subdirectories . '|' . $allow_modify_subdirectories;

            $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET files_level = :files_level WHERE admin_id=' . $array_admin['admin_id']);
            $sth->bindParam(':files_level', $update, PDO::PARAM_STR);
            $sth->execute();
        }
        $allow_files_type = $allow_files_type2;
    }

    $admin_info['avata'] = !empty($admin_info['photo']) ? NV_BASE_SITEURL . $admin_info['photo'] : '';
    $admin_info['level'] = $admin_info['lev'];
    $admin_info['last_login'] = (int) ($array_admin['last_login']);
    $admin_info['last_agent'] = $array_admin['last_agent'];
    $admin_info['last_ip'] = $array_admin['last_ip'];
    $admin_info['allow_files_type'] = $allow_files_type;
    $admin_info['allow_modify_files'] = (int) $allow_modify_files;
    $admin_info['allow_create_subdirectories'] = (int) $allow_create_subdirectories;
    $admin_info['allow_modify_subdirectories'] = (int) $allow_modify_subdirectories;

    if (empty($admin_info['first_name'])) {
        $admin_info['first_name'] = $admin_info['username'];
    }

    // Thêm tự động nhóm của hệ thống
    $manual_groups = [3];
    if ($admin_info['level'] == 1 or $admin_info['level'] == 2) {
        $manual_groups[] = 2;
    }
    if ($admin_info['level'] == 1 and $global_config['idsite'] == 0) {
        $manual_groups[] = 1;
    }

    $check_in_groups = nv_user_groups($admin_info['in_groups'], true, $manual_groups);
    $admin_info['in_groups'] = $check_in_groups[0];
    $admin_info['2step_require'] = $check_in_groups[1];
    $admin_info['current_openid'] = '';
    $admin_info['st_login'] = !empty($admin_info['password']) ? true : false;
    if ($global_config['allowquestion']) {
        $admin_info['valid_question'] = (!empty($admin_info['question']) and !empty($admin_info['answer'])) ? true : false;
    } else {
        $admin_info['valid_question'] = true;
    }

    $admin_info['current_mode'] = 5;

    unset($admin_info['lev'], $admin_info['files_level'], $admin_info['password'], $admin_info['question'], $admin_info['answer'], $admin_info['check_num']);

    return $admin_info;
}

/**
 * Hàm kiểm tra và lấy thông tin tài khoản quản trị đã đăng nhập bước thứ 1 (tài khoản + mật khẩu)
 *
 * @since 4.3.09
 * @param string $adm_session_value
 * @return array
 */
function nv_admin_check_predata($adm_session_value)
{
    global $db;

    if (empty($adm_session_value)) {
        return [];
    }
    $array_admin = json_decode($adm_session_value, true);
    if (!is_array($array_admin)) {
        return [];
    }
    // Kiểm tra toàn vẹn session
    if (
        !isset($array_admin['admin_id'])
        or !is_numeric($array_admin['admin_id'])
        or $array_admin['admin_id'] <= 0
        or !isset($array_admin['checknum'])
        or !preg_match('/^[a-z0-9]{32}$/', $array_admin['checknum'])
    ) {
        return [];
    }

    // Lấy thông tin từ CSDL
    $sql = 'SELECT a.admin_id admin_id, a.pre_check_num check_num, a.pre_last_agent current_agent,
        a.pre_last_ip current_ip, a.pre_last_login current_login,
        a.last_agent admin_last_agent, a.last_ip admin_last_ip, a.last_login admin_last_login,
        b.userid userid, b.group_id group_id,
        b.username username, b.email email, b.first_name first_name, b.last_name last_name, b.active2step active2step,
        b.last_agent user_last_agent, b.last_ip user_last_ip, b.last_login user_last_login, b.last_openid user_last_openid,
        b.secretkey user_2s_secretkey
        FROM ' . NV_AUTHORS_GLOBALTABLE . ' a, ' . NV_USERS_GLOBALTABLE . ' b
        WHERE a.admin_id = ' . $array_admin['admin_id'] . '
        AND a.lev!=0
        AND a.is_suspend=0
        AND b.userid=a.admin_id
        AND b.active=1';
    $admin_info = $db->query($sql)->fetch();
    if (empty($admin_info)) {
        return [];
    }

    // Đối chiếu dữ liệu từ CSDL và session đảm bảo đúng thông tin
    if (
        ($array_admin['checknum'] !== $admin_info['check_num']) // Check_num
        or !isset($array_admin['current_agent']) or empty($array_admin['current_agent']) or ($array_admin['current_agent'] !== $admin_info['current_agent']) // User_agent
        or !isset($array_admin['current_ip']) or empty($array_admin['current_ip']) or ($array_admin['current_ip'] !== $admin_info['current_ip']) // IP
        or !isset($array_admin['current_login']) or empty($array_admin['current_login']) or ($array_admin['current_login'] !== (int) ($admin_info['current_login'])) // Current_login
        or $array_admin['current_login'] < (NV_CURRENTTIME - 300) // Trạng thái chờ xác thực 2 bước duy trì 5 phút
    ) {
        return [];
    }

    $admin_info['full_name'] = nv_show_name_user($admin_info['first_name'], $admin_info['last_name'], $admin_info['username']);

    unset($admin_info['check_num']);

    return $admin_info;
}
