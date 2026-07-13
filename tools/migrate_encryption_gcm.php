<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/**
 * Chuyển các giá trị đã mã hóa AES-256-CBC sang AES-256-GCM.
 * Chỉ chạy một lần, nhưng an toàn khi chạy lại nhiều lần.
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

$console_starttime = microtime(true);

define('NV_SYSTEM', true);
define('NV_IS_CONSOLE', true);
define('NV_CONSOLE_DIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME))));

require NV_CONSOLE_DIR . '/cli-cfg.php';
require NV_ROOTDIR . '/includes/mainfile.php';

/**
 * Hàm chuyển đổi giá trị cũ sang định dạng GCM.
 *
 * @param string $value
 * @return string|null
 */
$reencrypt = static function ($value) use ($crypt) {
    if ($value === null or $value === '') {
        return null;
    }
    // Đã chuyển rồi thì bỏ qua
    if ($crypt->decrypt($value) !== false) {
        return null;
    }
    // Không phải ở định dạng cũ AES-256-CBC thì bỏ qua
    $plain = $crypt->decryptDeterministic($value);
    if ($plain === false) {
        return null;
    }

    return $crypt->encrypt($plain);
};

$total = 0;

// Chuyển dữ liệu cấu hình
$config_names = [
    'smtp_password',
    'ftp_user_pass',
    'redis_password',
    'recaptcha_secretkey',
    'turnstile_secretkey',
    'load_files_seccode'
];

$in = "'" . implode("','", $config_names) . "'";
$rows = $db->query("SELECT lang, module, config_name, config_value FROM " . NV_CONFIG_GLOBALTABLE . "
WHERE config_name IN (" . $in . ") AND config_value != ''")->fetchAll();

$stmt_cfg = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . ' SET config_value = :val
WHERE lang = :lang AND module = :module AND config_name = :cname');

$config_migrated = 0;
foreach ($rows as $row) {
    $new = $reencrypt($row['config_value']);
    if ($new === null) {
        continue;
    }
    $stmt_cfg->bindValue(':val', $new, PDO::PARAM_STR);
    $stmt_cfg->bindValue(':lang', $row['lang'], PDO::PARAM_STR);
    $stmt_cfg->bindValue(':module', $row['module'], PDO::PARAM_STR);
    $stmt_cfg->bindValue(':cname', $row['config_name'], PDO::PARAM_STR);
    $stmt_cfg->execute();
    $config_migrated++;
}
echo "Cấu hình secret: đã chuyển $config_migrated giá trị.\n";
$total += $config_migrated;

// Chuyển dữ liệu API secret của từng user
$api_table = $db_config['prefix'] . '_api_user';
$rows = $db->query("SELECT userid, method, secret FROM " . $api_table . " WHERE secret != ''")->fetchAll();

$stmt_api = $db->prepare('UPDATE ' . $api_table . ' SET secret = :secret WHERE userid = :userid AND method = :method');

$api_migrated = 0;
foreach ($rows as $row) {
    $new = $reencrypt($row['secret']);
    if ($new === null) {
        continue;
    }
    $stmt_api->bindValue(':secret', $new, PDO::PARAM_STR);
    $stmt_api->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
    $stmt_api->bindValue(':method', $row['method'], PDO::PARAM_STR);
    $stmt_api->execute();
    $api_migrated++;
}
echo "API secret: đã chuyển $api_migrated giá trị.\n";
$total += $api_migrated;

echo "Xong: tổng cộng đã chuyển $total giá trị sang AES-256-GCM.\n";

$console_endtime = microtime(true);
$execution_time = getConsoleExecuteTime($console_starttime, $console_endtime);
echo ('Execution time: ' . $execution_time . "\n");
echo ("Console end!\n");
