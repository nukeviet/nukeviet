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

// Duyệt tất cả các cron đến giờ chạy
$cron_result = $db->query('SELECT * FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE act=1 AND start_time <= ' . NV_CURRENTTIME . ' ORDER BY is_sys DESC');
while ($cron_row = $cron_result->fetch()) {
    // Kiểm tra chính xác cron có đúng thời gian chạy hay chưa
    $cron_allowed = false;
    if (empty($cron_row['inter_val'])) {
        $cron_allowed = true;
    } else {
        $interval = $cron_row['inter_val'] * 60;
        if ($cron_row['last_time'] + $interval < NV_CURRENTTIME) {
            $cron_allowed = true;
        }
    }

    if ($cron_allowed) {
        if ($sys_info['allowed_set_time_limit']) {
            set_time_limit(0);
        }

        // Xác định thời điểm thực hiện lần này
        if (empty($cron_row['inter_val_type']) or empty($cron_row['inter_val'])) {
            // Tính tại thời điểm run thực, cron chỉ chạy một lần cũng tính từ thời điểm run thực
            $this_time = NV_CURRENTTIME;
        } else {
            if ($cron_row['last_time'] <= 0) {
                // Cron chưa chạy lần nào thì lần chạy này tính từ thời điểm bắt đầu
                $this_time = $cron_row['start_time'] + $interval;
            } else {
                // Lần chạy này tính từ thời điểm chạy lần trước
                $this_time = $cron_row['last_time'] + $interval;
            }
            // Nếu lần thực hiện sau mà nhỏ hơn bây giờ thì tính lại lần chạy này ngay trước thời điểm hiện tại
            if (($this_time + $interval) < NV_CURRENTTIME) {
                $this_time += (floor((NV_CURRENTTIME - $this_time) / $interval) * $interval);
            }
        }

        if (!empty($cron_row['run_file']) and preg_match('/^([a-zA-Z0-9\-\_\.]+)\.php$/', $cron_row['run_file']) and is_file(NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'])) {
            if (!defined('NV_IS_CRON')) {
                define('NV_IS_CRON', true);
            }
            require_once NV_ROOTDIR . '/includes/cronjobs/' . $cron_row['run_file'];
        }
        if (!nv_function_exists($cron_row['run_func'])) {
            nv_insert_notification('settings', 'auto_deactive_cronjobs', ['cron_id' => $cron_row['id']]);
            $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . $this_time . ', last_result=0 WHERE id=' . $cron_row['id']);
            continue;
        }

        /*
         * Kiểm tra file trên server, đảm bảo không thực thi cron nhiều lần tại một thời điểm
         * Ví dụ Client A đang khởi chạy, trong khi chạy chưa xong thì Client B lại khởi chạy tiếp
         */
        $check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5($cron_row['run_file'] . $cron_row['run_func'] . $global_config['sitekey']) . '.txt';
        $p = NV_CURRENTTIME - 300;
        if (file_exists($check_run_cronjobs) and @filemtime($check_run_cronjobs) > $p) {
            continue;
        }
        file_put_contents($check_run_cronjobs, var_export($cron_row, true));

        $params = (!empty($cron_row['params'])) ? array_map('trim', explode(',', $cron_row['params'])) : [];
        $result2 = call_user_func_array($cron_row['run_func'], $params);
        if (!$result2) {
            nv_insert_notification('settings', 'auto_deactive_cronjobs', ['cron_id' => $cron_row['id']]);
            $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . $this_time . ', last_result=0 WHERE id=' . $cron_row['id']);
        } else {
            if ($cron_row['del']) {
                $db->query('DELETE FROM ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' WHERE id = ' . $cron_row['id']);
            } elseif (empty($cron_row['inter_val'])) {
                nv_insert_notification('settings', 'auto_deactive_cronjobs', ['cron_id' => $cron_row['id']]);
                $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET act=0, last_time=' . $this_time . ', last_result=1 WHERE id=' . $cron_row['id']);
            } else {
                $db->query('UPDATE ' . $db_config['dbsystem'] . '.' . NV_CRONJOBS_GLOBALTABLE . ' SET last_time=' . $this_time . ', last_result=1 WHERE id=' . $cron_row['id']);

                $cronjobs_next_time = $this_time + $interval;
                if ($db->exec('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = '" . $cronjobs_next_time . "' WHERE lang = '" . NV_LANG_DATA . "' AND module = 'global' AND config_name = 'cronjobs_next_time' AND (CAST(config_value AS UNSIGNED) < " . NV_CURRENTTIME . ' OR CAST(config_value AS UNSIGNED) > ' . $cronjobs_next_time . ')')) {
                    $nv_Cache->delMod('settings');
                }
            }
        }
        unlink($check_run_cronjobs);
        clearstatcache();
    }
}

$image = imagecreate(1, 1);
header('Content-type: image/jpg');
imagejpeg($image, null, 80);
imagedestroy($image);
exit();
