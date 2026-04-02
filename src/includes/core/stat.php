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
 * nv_stat_update()
 *
 * @throws PDOException
 */
function nv_stat_update()
{
    global $db, $client_info, $global_config;

    $last_update = $db->query("SELECT c_count FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'c_time' AND c_val = 'last'")->fetchColumn();

    NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone'] && date_default_timezone_set($global_config['statistics_timezone']);
    [$last_year, $last_month, $last_day] = explode('|', date('Y|M|d', $last_update));
    [$current_year, $current_month, $current_day, $current_hour, $current_week] = explode('|', date('Y|M|d|H|l', NV_CURRENTTIME));
    NV_SITE_TIMEZONE_NAME != $global_config['statistics_timezone'] && date_default_timezone_set(NV_SITE_TIMEZONE_NAME);

    // Bắt đầu vào giai đoạn thống kê mới thì reset lại số liệu
    if ($last_year != $current_year) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM " . NV_COUNTER_GLOBALTABLE . " WHERE c_type = 'year' AND c_val = :year");
        $stmt->bindValue(':year', $current_year, PDO::PARAM_STR);
        $stmt->execute();
        $year_exists = $stmt->fetchColumn();

        if (!$year_exists) {
            $stmt = $db->prepare("INSERT INTO " . NV_COUNTER_GLOBALTABLE . " (c_type, c_val) VALUES ('year', :year)");
            $stmt->bindValue(':year', $current_year, PDO::PARAM_STR);
            $stmt->execute();
        }

        $db->query("UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count = 0, " . NV_LANG_DATA . "_count = 0 WHERE c_type IN ('month', 'day', 'hour')");
    } elseif ($last_month != $current_month) {
        $db->query("UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count = 0, " . NV_LANG_DATA . "_count = 0 WHERE c_type IN ('day', 'hour')");
    } elseif ($last_day != $current_day) {
        $db->query("UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count = 0, " . NV_LANG_DATA . "_count = 0 WHERE c_type = 'hour'");
    }

    $bot_name = ($client_info['is_bot'] and !empty($client_info['browser']['name'])) ? $client_info['browser']['name'] : '';
    $br = $client_info['browser']['key'];
    if (strcasecmp($br, 'unknown') === 0) {
        if ($client_info['is_mobile']) {
            $br = 'Mobile';
        } elseif (!empty($bot_name)) {
            $br = 'bots';
        }
    }

    $where = ["(c_type = 'bot' AND c_val = :bot_name)"];
    $stat_bot = false;
    // Ngoại trừ thống kê các BOT, các số liệu khác thống kê nếu là người dùng thực hoặc bật tính cả bot
    if (empty($client_info['is_bot']) or empty($global_config['stat_excl_bot'])) {
        $stat_bot = true;

        $where[] = "(c_type = 'total' AND c_val = 'hits')";
        $where[] = "(c_type = 'year' AND c_val = :year)";
        $where[] = "(c_type = 'month' AND c_val = :month)";
        $where[] = "(c_type = 'day' AND c_val = :day)";
        $where[] = "(c_type = 'dayofweek' AND c_val = :week)";
        $where[] = "(c_type = 'hour' AND c_val = :hour)";
        $where[] = "(c_type = 'browser' AND c_val = :browser)";
        $where[] = "(c_type = 'os' AND c_val = :client_os)";
        $where[] = "(c_type = 'country' AND c_val = :country)";
    }

    $sth = $db->prepare('UPDATE ' . NV_COUNTER_GLOBALTABLE . ' SET last_update = :last_update, c_count = c_count + 1, ' . NV_LANG_DATA . '_count = ' . NV_LANG_DATA . '_count + 1 WHERE ' . implode(' OR ', $where));
    $sth->bindValue(':last_update', NV_CURRENTTIME, PDO::PARAM_INT);
    $sth->bindValue(':bot_name', $bot_name, PDO::PARAM_STR);

    if ($stat_bot) {
        $sth->bindValue(':year', $current_year, PDO::PARAM_STR);
        $sth->bindValue(':month', $current_month, PDO::PARAM_STR);
        $sth->bindValue(':day', $current_day, PDO::PARAM_STR);
        $sth->bindValue(':week', $current_week, PDO::PARAM_STR);
        $sth->bindValue(':hour', $current_hour, PDO::PARAM_STR);
        $sth->bindValue(':browser', $br, PDO::PARAM_STR);
        $sth->bindValue(':client_os', $client_info['client_os']['key'], PDO::PARAM_STR);
        $sth->bindValue(':country', $client_info['country'], PDO::PARAM_STR);
    }
    $sth->execute();

    $stmt = $db->prepare("UPDATE " . NV_COUNTER_GLOBALTABLE . " SET c_count = :current_time WHERE c_type = 'c_time' AND c_val = 'last'");
    $stmt->bindValue(':current_time', NV_CURRENTTIME, PDO::PARAM_INT);
    $stmt->execute();
}

nv_stat_update();

// Đếm lại sau 30 phút khách truy cập không hoạt động
$nv_Request->set_Cookie(STATISTIC_COOKIE_NAME . NV_LANG_DATA, NV_CURRENTTIME, 1800);
