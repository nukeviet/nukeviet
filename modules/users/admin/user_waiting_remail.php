<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['userwait_resend_email'];
$set_active_op = 'user_waiting';
$checkss = md5(NV_CHECK_SESSION . '_' . $module_name . '_' . $op . '_' . $set_active_op);
if ($nv_Request->isset_request('ajax', 'post')) {
    $per_email = $nv_Request->get_int('per_email', 'post', 0);
    $offset = $nv_Request->get_int('offset', 'post', 0);
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    $useriddel = array_unique(array_filter(array_map("trim", explode(',', $nv_Request->get_title('useriddel', 'post', '')))));

    $respon = [
        'continue' => false,
        'messages' => [],
        'useriddel' => '',
    ];

    if ($tokend == $checkss and $per_email > 0 and $offset >= 0) {
        $sql = "SELECT * FROM " . NV_MOD_TABLE . "_reg";
        if($global_config['idsite'] > 0){
            $sql .= ' WHERE idsite=' . $global_config['idsite'];
        }
        $sql .= " ORDER BY userid ASC LIMIT " . $offset . ", " . $per_email;
        $result = $db->query($sql);
        $numrows = $result->rowCount();
        if ($numrows) {
            while ($row = $result->fetch()) {
                // Kiểm tra xem email đã tồn tại chưa nếu có xóa đi
                if ($db->query("SELECT userid FROM " . NV_MOD_TABLE . " WHERE email=" . $db->quote($row['email']))->fetchColumn()) {
                    $respon['messages'][] = $row['email'] . ': ' . $lang_module['userwait_resend_delete'];
                    if (!in_array($row['userid'], $useriddel)) {
                        $useriddel[] = $row['userid'];
                    }
                } else {
                    $register_active_time = isset($global_users_config['register_active_time']) ? $global_users_config['register_active_time'] : 86400;
                    $_full_name = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);

                    $subject = $lang_module['account_active'];
                    $message = sprintf($lang_module['account_active_info'], $_full_name, $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $row['userid'] . '&checknum=' . $row['checknum'], $row['username'], $row['email'], nv_date('H:i d/m/Y', NV_CURRENTTIME + $register_active_time));
                    $checkSend = nv_sendmail([$global_config['site_name'], $global_config['site_email']], $row['email'], $subject, $message);

                    if ($checkSend) {
                        /*
                         * Cập nhật lại thời gian đăng ký là ngay lúc gửi mail này
                         * để đảm bảo thành viên vào kích hoạt thì không bị xóa mất tài khoản chờ duyệt
                         */
                        $db->query("UPDATE " . NV_MOD_TABLE . "_reg SET regdate=" . NV_CURRENTTIME . " WHERE userid=" . $row['userid']);
                    }

                    $respon['messages'][] = $row['email'] . ': ' . ($checkSend ? $lang_module['userwait_resend_ok'] : $lang_module['userwait_resend_error']);
                }
            }
        }

        if (!empty($useriddel)) {
            $respon['useriddel'] = implode(',', $useriddel);
        }

        // Nếu lấy đủ số tài khoản thì thử chạy lần nữa
        if ($numrows >= $per_email) {
            $respon['continue'] = true;
        } else {
            // Xóa các email đã kích hoạt
            if (!empty($respon['useriddel'])) {
                try {
                    $db->query("DELETE FROM " . NV_MOD_TABLE . "_reg WHERE userid IN(" . $respon['useriddel'] . ")");
                } catch (PDOException $e) {
                    trigger_error(print_r($e, true));
                }
            }
        }
    } else {
        $respon['messages'][] = 'Wrong request!!!';
    }

    nv_jsonOutput($respon);
}

$xtpl = new XTemplate('user_waiting_remail.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('TOKEND', $checkss);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
