<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

$console_starttime = microtime(true);

define('NV_SYSTEM', true);
define('NV_IS_CONSOLE', true);
define('NV_CONSOLE_DIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME))));

require NV_CONSOLE_DIR . '/cli-cfg.php';
require NV_ROOTDIR . '/includes/mainfile.php';

$batch_size = 1000;
$migrated = 0;

$stmt_update = $db->prepare('UPDATE ' . $db_config['prefix'] . '_users_backupcodes SET
code = :newcode WHERE userid = :userid AND code = :oldcode');

do {
    $rows = $db->query('SELECT userid, code FROM ' . $db_config['prefix'] . '_users_backupcodes
    WHERE CHAR_LENGTH(code) <= 20 LIMIT ' . $batch_size)->fetchAll();

    foreach ($rows as $row) {
        // Nếu có encryptDeterministic (cập nhật cả 2 lượt đồng bộ) thì dùng, không thì dùng encrypt (cập nhật theo từng step)
        if (method_exists($crypt, 'encryptDeterministic')) {
            $stmt_update->bindValue(':newcode', $crypt->encryptDeterministic($row['code']), PDO::PARAM_STR);
        } else {
            $stmt_update->bindValue(':newcode', $crypt->encrypt($row['code']), PDO::PARAM_STR);
        }

        $stmt_update->bindValue(':userid', $row['userid'], PDO::PARAM_INT);
        $stmt_update->bindValue(':oldcode', $row['code'], PDO::PARAM_STR);
        $stmt_update->execute();
        $migrated++;
    }

    $batch_count = count($rows);
    if ($batch_count > 0) {
        echo "... thực hiện $batch_count hàng (tổng: $migrated)\n";
    }
} while ($batch_count === $batch_size);

echo "Xong: đã mã hóa: $migrated hàng.\n";

$console_endtime = microtime(true);
$execution_time = getConsoleExecuteTime($console_starttime, $console_endtime);
echo ('Execution time: ' . $execution_time . "\n");
echo ("Console end!\n");
