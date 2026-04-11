<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_VOTING')) {
    exit('Stop!!!');
}

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
$vid = $nv_Request->get_int('vid', 'get,post', 0);

// Danh sách bình chọn
if (empty($vid)) {
    $canonicalUrl = getCanonicalUrl($page_url);

    $page_title = $module_info['site_title'];
    $key_words = $module_info['keywords'];

    $sql = 'SELECT vid, question, link, acceptcm, active_captcha, groups_view, publ_time, exp_time
    FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE act=1 ORDER BY publ_time DESC';
    $list = $nv_Cache->db($sql, 'vid', 'voting');

    $allowed = [];
    $is_update = [];

    if (!empty($list)) {
        foreach ($list as $row) {
            if ($row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME) {
                $is_update[] = $row['vid'];
            } elseif ($row['publ_time'] <= NV_CURRENTTIME and nv_user_in_groups($row['groups_view'])) {
                $sql = 'SELECT id, vid, title, url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = :vid ORDER BY id ASC';
                $bind = [
                    [':vid', (int) $row['vid'], PDO::PARAM_INT]
                ];
                $items = $nv_Cache->db($sql, 'id', $module_name, bind: $bind);

                $allowed[] = [
                    'checkss' => md5($row['vid'] . NV_CHECK_SESSION),
                    'accept' => (int) $row['acceptcm'],
                    'active_captcha' => (int) $row['active_captcha'],
                    'errsm' => (int) $row['acceptcm'] > 1 ? $nv_Lang->getModule('voting_warning_all', (int) $row['acceptcm']) : $nv_Lang->getModule('voting_warning_accept1'),
                    'vid' => $row['vid'],
                    'question' => (empty($row['link'])) ? $row['question'] : '<a target="_blank" href="' . $row['link'] . '">' . $row['question'] . '</a>',
                    'action' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name,
                    'langresult' => $nv_Lang->getModule('voting_result'),
                    'langsubmit' => $nv_Lang->getModule('voting_hits'),
                    'publtime' => nv_datetime_format($row['publ_time'], 1, 0),
                    'items' => $items
                ];
            }
        }
    }

    if (!empty($is_update)) {
        $is_update_ids = implode(', ', array_map('intval', $is_update));
        $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET act=0 WHERE vid IN (' . $is_update_ids . ')');
        $stmt->execute();

        $nv_Cache->delMod($module_name);
    }

    $contents = '';
    if (!empty($allowed)) {
        $contents = voting_main($allowed);
    }

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Chi tiết + bình chọn
$sql = 'SELECT vid, question, acceptcm, active_captcha, groups_view, publ_time, exp_time, vote_one FROM ' . NV_PREFIXLANG . '_' . $module_data;
if (!defined('NV_IS_MODADMIN')) {
    $sql .= ' WHERE act=1';
}
$list = $nv_Cache->db($sql, 'vid', 'voting');
if (empty($list) or !isset($list[$vid])) {
    nv_redirect_location(nv_url_rewrite($page_url, true));
}

$row = $list[$vid];
if (((int) $row['exp_time'] < 0 or ((int) $row['exp_time'] > 0 and $row['exp_time'] < NV_CURRENTTIME)) and !defined('NV_IS_MODADMIN')) {
    nv_redirect_location(nv_url_rewrite($page_url, true));
}

if (!nv_user_in_groups($row['groups_view'])) {
    nv_redirect_location(nv_url_rewrite($page_url, true));
}

if ($row['groups_view'] == '5' or $row['groups_view'] == '6') {
    $row['vote_one'] = '0';
}

if (empty($row['vote_one'])) {
    $difftimeout = !empty($module_config[$module_name]['difftimeout']) ? $module_config[$module_name]['difftimeout'] : 3600;
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/voting_logs';
    $log_fileext = preg_match('/[a-z]+/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
    $pattern = '/^(.*)\.' . $log_fileext . '$/i';
    $logs = nv_scandir($dir, $pattern);

    if (!empty($logs)) {
        foreach ($logs as $file) {
            $vtime = filemtime($dir . '/' . $file);

            if (!$vtime or $vtime <= NV_CURRENTTIME - $difftimeout) {
                @unlink($dir . '/' . $file);
            }
        }
    }
}

$note = '';
$is_error = false;

// Gửi bình chọn
if ($nv_Request->isset_request('checkss', 'post')) {
    if ($nv_Request->get_title('checkss', 'post', '') !== md5($vid . NV_CHECK_SESSION)) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Session error!'
        ]);
    }

    // Kiểm tra captcha nếu thăm dò có bật
    if ($row['active_captcha']) {
        unset($fcaptcha);

        if ($module_captcha == 'recaptcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng reCaptcha
            $fcaptcha = $nv_Request->get_title('g-recaptcha-response', 'post', '');
        } elseif ($module_captcha == 'turnstile') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng Turnstile
            $fcaptcha = $nv_Request->get_title('cf-turnstile-response', 'post', '');
        } elseif ($module_captcha == 'captcha') {
            // Xác định giá trị của captcha nhập vào nếu sử dụng captcha hình
            $fcaptcha = $nv_Request->get_title('fcode', 'post', '');
        }

        // Kiểm tra tính hợp lệ của captcha nhập vào, nếu không hợp lệ => thông báo lỗi
        if (isset($fcaptcha) and !nv_capcha_txt($fcaptcha, $module_captcha)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => ($module_captcha == 'recaptcha') ? $nv_Lang->getGlobal('securitycodeincorrect1') : (($module_captcha == 'turnstile') ? $nv_Lang->getGlobal('securitycodeincorrect2') : $nv_Lang->getGlobal('securitycodeincorrect'))
            ]);
        }
    }

    if (is_array($_POST['option'] ?? '')) {
        $id_answers = $nv_Request->get_typed_array('option', 'post', 'int', []);
    } else {
        $id_answers = [$nv_Request->get_int('option', 'post', 0)];
    }
    $id_answers = array_filter(array_unique($id_answers));
    $num_answers = count($id_answers);
    $only_result = $nv_Request->get_bool('viewresult', 'post', false);

    $result_valid = true;
    if ($num_answers < 1 or $num_answers > $row['acceptcm']) {
        $result_valid = false;
        !$only_result && nv_jsonOutput([
            'status' => 'error',
            'input' => $row['acceptcm'] > 1 ? 'option[' : 'option',
            'mess' => ($row['acceptcm'] > 1) ? $nv_Lang->getModule('voting_warning_all', $row['acceptcm']) : $nv_Lang->getModule('voting_warning_accept1')
        ]);
    }

    if ($result_valid) {
        if (!empty($row['vote_one'])) {
            // Mỗi người chỉ bình chọn một lần
            $is_voted = false;
            $stmt_voted = $db->prepare('SELECT voted FROM ' . NV_PREFIXLANG . '_' . $module_data . '_voted WHERE vid = :vid');
            $stmt_voted->bindValue(':vid', $vid, PDO::PARAM_INT);
            $stmt_voted->execute();
            $userlist = $stmt_voted->fetchColumn();
            if (!empty($userlist)) {
                if (preg_match('/\,\s*' . $user_info['userid'] . '\s*\,/', ',' . $userlist . ',')) {
                    $is_voted = true;
                }
            }

            if ($is_voted) {
                $note = $nv_Lang->getModule('limit_vote_msg');
                $is_error = true;
            } else {
                $answer_ids = implode(', ', array_map('intval', $id_answers));
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal = hitstotal+1 WHERE vid = :vid AND id IN (' . $answer_ids . ')');
                $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt->execute();

                $userlist .= !empty($userlist) ? ',' . $user_info['userid'] : $user_info['userid'];
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . "_voted (vid, voted) VALUES (:vid, :voted) ON DUPLICATE KEY UPDATE voted = VALUES(voted)");
                $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt->bindValue(':voted', $userlist, PDO::PARAM_STR);
                $stmt->execute();

                $note = $nv_Lang->getModule('okmsg');
            }
        } else {
            // Bình chọn không cần tài khoản
            $logfile = 'vo' . $vid . '_' . md5(NV_LANG_DATA . $global_config['sitekey'] . $client_info['ip'] . $vid) . '.' . $log_fileext;
            if (file_exists($dir . '/' . $logfile)) {
                $timeout = filemtime($dir . '/' . $logfile);
                $timeout = ceil(($difftimeout - NV_CURRENTTIME + $timeout) / 60);
                $note = $nv_Lang->getModule('timeoutmsg', $timeout);
                $is_error = true;
            } else {
                $answer_ids = implode(', ', array_map('intval', $id_answers));
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hitstotal = hitstotal+1 WHERE vid = :vid AND id IN (' . $answer_ids . ')');
                $stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
                $stmt->execute();

                file_put_contents($dir . '/' . $logfile, '', LOCK_EX);
                $note = $nv_Lang->getModule('okmsg');
            }
        }
    }
}

$stmt = $db->prepare('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE vid = :vid ORDER BY id ASC');
$stmt->bindValue(':vid', $vid, PDO::PARAM_INT);
$stmt->execute();

$totalvote = 0;
$vrow = [];

while ($row2 = $stmt->fetch()) {
    $totalvote += (int) $row2['hitstotal'];
    $vrow[] = $row2;
}
$stmt->closeCursor();

$voting = [
    'question' => $row['question'],
    'total' => $totalvote,
    'pubtime' => nv_datetime_format($row['publ_time']),
    'row' => $vrow,
    'note' => $note,
    'is_error' => $is_error,
    'ajax' => $nv_Request->get_int('nv_ajax_voting', 'post', 0)
];

$contents = voting_result($voting);

$page_title = $row['question'];
$page_url .= '&amp;vid=' . $vid;
$canonicalUrl = getCanonicalUrl($page_url);

if ($voting['ajax']) {
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => '',
        'html' => nv_url_rewrite($contents)
    ]);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
