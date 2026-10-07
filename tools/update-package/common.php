<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

/*
 * Hàm dùng chung cho các tool tạo gói nâng cấp giả lập
 *
 * Gói sinh ra gồm src/install/update_data.php và src/install/update/
 * - Các tác vụ CSDL là hàm giả, không thay đổi dữ liệu thật
 * - Thư mục update/ chép nguyên byte file có sẵn trong repo nên sau khi di chuyển file thì git không thấy thay đổi
 */

if (PHP_SAPI !== 'cli') {
    exit('Stop!!!');
}

define('UPK_SRCDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(__DIR__ . '/../../src')));
define('UPK_INSTALLDIR', UPK_SRCDIR . '/install');

/**
 * Đọc tham số dòng lệnh dạng --key=value, --flag và tham số vị trí
 *
 * @param array $argv
 * @return array [tham số vị trí, tùy chọn]
 */
function upk_parse_args(array $argv): array
{
    $args = [];
    $options = [];
    foreach (array_slice($argv, 1) as $arg) {
        if (str_starts_with($arg, '--')) {
            $parts = explode('=', substr($arg, 2), 2);
            $options[$parts[0]] = $parts[1] ?? true;
        } else {
            $args[] = $arg;
        }
    }

    return [$args, $options];
}

/**
 * Dừng tool kèm thông báo lỗi
 *
 * @param string $message
 * @return never
 */
function upk_die(string $message): never
{
    fwrite(STDERR, 'Lỗi: ' . $message . PHP_EOL);
    exit(1);
}

/**
 * Tăng số cuối của phiên bản, giữ nguyên độ dài, VD 5.0.00 => 5.0.01
 *
 * @param string $version
 * @return string
 */
function upk_bump_version(string $version): string
{
    if (!preg_match('/^(.*\.)([0-9]+)$/', $version, $m)) {
        upk_die('Phiên bản không hợp lệ: ' . $version);
    }

    return $m[1] . str_pad((string) ((int) $m[2] + 1), strlen($m[2]), '0', STR_PAD_LEFT);
}

/**
 * Không cho ghi đè gói nâng cấp đang có trong src/install
 */
function upk_check_target(): void
{
    $exists = [];
    foreach (['update_data.php', 'update'] as $name) {
        if (file_exists(UPK_INSTALLDIR . '/' . $name)) {
            $exists[] = 'src/install/' . $name;
        }
    }
    foreach (glob(UPK_INSTALLDIR . '/update_docs_*.html') as $file) {
        $exists[] = 'src/install/' . basename($file);
    }
    if (!empty($exists)) {
        upk_die('Đã có gói nâng cấp, hãy chuyển hoặc xóa trước khi tạo gói mới: ' . implode(', ', $exists));
    }
}

/**
 * Kiểm tra đường dẫn được phép chép vào gói
 * Loại install/ (đang chạy trình nâng cấp), data/ (dữ liệu runtime) và includes/constants.php (bị update.php ghi lại theo hằng của máy chủ)
 *
 * @param string $path Đường dẫn tương đối so với src
 * @return bool
 */
function upk_allowed_path(string $path): bool
{
    return !preg_match('#(^|/)\.\.(/|$)#', $path)
        and !preg_match('#^(install|data)(/|$)#', $path)
        and $path !== 'includes/constants.php';
}

/**
 * Chép nguyên byte các file, thư mục trong src vào src/install/update
 *
 * @param array $paths Đường dẫn tương đối so với src
 * @return int Số file đã chép
 */
function upk_copy_paths(array $paths): int
{
    // Kiểm tra hết trước khi chép để không để lại thư mục update/ chép dở
    $paths = array_map(function ($path) {
        return trim(str_replace('\\', '/', $path), '/');
    }, $paths);
    foreach ($paths as $path) {
        if ($path === '' or !upk_allowed_path($path)) {
            upk_die('Không được chép đường dẫn: ' . $path);
        }
        if (!file_exists(UPK_SRCDIR . '/' . $path)) {
            upk_die('Không tồn tại: src/' . $path);
        }
    }

    $count = 0;
    foreach ($paths as $path) {
        $files = [$path];
        if (is_dir(UPK_SRCDIR . '/' . $path)) {
            $files = [];
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(UPK_SRCDIR . '/' . $path, FilesystemIterator::SKIP_DOTS));
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $files[] = substr(str_replace('\\', '/', $file->getPathname()), strlen(UPK_SRCDIR) + 1);
                }
            }
            sort($files);
        }

        foreach ($files as $file) {
            if (!upk_allowed_path($file)) {
                continue;
            }
            $target = UPK_INSTALLDIR . '/update/' . $file;
            if (!is_dir(dirname($target)) and !mkdir(dirname($target), 0755, true)) {
                upk_die('Không tạo được thư mục ' . dirname($target));
            }
            if (!copy(UPK_SRCDIR . '/' . $file, $target)) {
                upk_die('Không chép được src/' . $file);
            }
            ++$count;
        }
    }

    return $count;
}

/**
 * Sinh nội dung các hàm tác vụ giả
 *
 * @param string $prefix Tiền tố tên hàm
 * @param string $fail Rỗng, warn hoặc error
 * @return array [tasklist, lang theo ngôn ngữ, mã PHP các hàm]
 */
function upk_build_tasks(string $prefix, string $fail): array
{
    $tasks = [
        $prefix . 'once' => [
            'rq' => 2,
            'vi' => 'Tác vụ giả: chạy một lần, chỉ đọc CSDL',
            'en' => 'Fake task: run once, read only',
            'fr' => 'Tâche factice : exécution unique, lecture seule'
        ],
        $prefix . 'loop' => [
            'rq' => 2,
            'vi' => 'Tác vụ giả: chạy lặp nhiều lượt',
            'en' => 'Fake task: run in several passes',
            'fr' => 'Tâche factice : exécution en plusieurs passes'
        ],
        $prefix . 'temp' => [
            'rq' => 1,
            'vi' => 'Tác vụ giả: ghi trên bảng tạm, không đổi dữ liệu thật',
            'en' => 'Fake task: write on a temporary table only',
            'fr' => 'Tâche factice : écriture sur une table temporaire'
        ],
        $prefix . 'noop' => [
            'rq' => 0,
            'vi' => 'Tác vụ giả: không tác động CSDL',
            'en' => 'Fake task: no database access',
            'fr' => 'Tâche factice : aucun accès à la base'
        ]
    ];
    if ($fail === 'warn' or $fail === 'error') {
        $tasks[$prefix . 'fail'] = [
            'rq' => $fail === 'warn' ? 1 : 2,
            'vi' => 'Tác vụ giả: luôn thất bại (' . $fail . ')',
            'en' => 'Fake task: always fails (' . $fail . ')',
            'fr' => 'Tâche factice : échoue toujours (' . $fail . ')'
        ];
    }

    $tasklist = [];
    $lang = ['vi' => [], 'en' => [], 'fr' => []];
    foreach ($tasks as $func => $task) {
        $tasklist[] = ['rq' => $task['rq'], 'l' => $func, 'f' => $func];
        foreach (array_keys($lang) as $l) {
            $lang[$l][$func] = $task[$l];
        }
    }

    $code = strtr(<<<'PHP'
/**
 * Kết quả mặc định của một tác vụ
 *
 * @return array
 */
function {P}result()
{
    return [
        'status' => 1,
        'complete' => 1,
        'next' => 1,
        'link' => 'NO',
        'lang' => 'NO',
        'message' => ''
    ];
}

/**
 * Chạy một lần, chỉ đọc CSDL
 *
 * @return array
 */
function {P}once()
{
    global $db;

    $return = {P}result();
    try {
        $num = $db->query('SELECT COUNT(*) FROM ' . NV_CONFIG_GLOBALTABLE)->fetchColumn();
        $return['message'] = $num . ' config';
    } catch (Throwable $e) {
        trigger_error(print_r($e, true));
        $return['status'] = 0;
        $return['message'] = $e->getMessage();
    }
    usleep(500000);

    return $return;
}

/**
 * Chạy lặp nhiều lượt: chưa xong thì trả next=0 kèm link gọi lại chính nó với lượt kế tiếp
 *
 * @return array
 */
function {P}loop()
{
    global $db, $nv_Request, $nv_update_baseurl;

    $total = 5;
    $page = max(1, $nv_Request->get_int('fakepage', 'get', 1));

    $return = {P}result();
    try {
        $db->query('SELECT config_name FROM ' . NV_CONFIG_GLOBALTABLE . ' ORDER BY config_name LIMIT 20 OFFSET ' . (($page - 1) * 20))->fetchAll();
    } catch (Throwable $e) {
        trigger_error(print_r($e, true));
        $return['status'] = 0;
        $return['message'] = $e->getMessage();

        return $return;
    }
    usleep(500000);

    $return['message'] = $page . '/' . $total;
    if ($page < $total) {
        $return['complete'] = 0;
        $return['next'] = 0;
        $return['link'] = $nv_update_baseurl . '&fakepage=' . ($page + 1);
    }

    return $return;
}

/**
 * Ghi trên bảng TEMPORARY, bảng tự mất khi đóng kết nối nên không đổi dữ liệu thật
 *
 * @return array
 */
function {P}temp()
{
    global $db, $db_config;

    $table = $db_config['prefix'] . '_fake_update_tmp';
    $return = {P}result();
    try {
        $db->query('CREATE TEMPORARY TABLE ' . $table . ' (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, val VARCHAR(50) NOT NULL DEFAULT \'\') ENGINE=MEMORY');
        $db->query('INSERT INTO ' . $table . " (val) VALUES ('a'), ('b'), ('c')");
        $db->query('UPDATE ' . $table . " SET val = CONCAT(val, '1')");
        $num = $db->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
        $db->query('DROP TEMPORARY TABLE ' . $table);
        $return['message'] = $num . ' rows';
    } catch (Throwable $e) {
        trigger_error(print_r($e, true));
        $return['status'] = 0;
        $return['message'] = $e->getMessage();
    }

    return $return;
}

/**
 * Không tác động CSDL
 *
 * @return array
 */
function {P}noop()
{
    usleep(300000);

    return {P}result();
}

PHP, ['{P}' => $prefix]);

    if (isset($tasks[$prefix . 'fail'])) {
        $code .= strtr(<<<'PHP'
/**
 * Luôn thất bại để thử giao diện báo lỗi
 *
 * @return array
 */
function {P}fail()
{
    $return = {P}result();
    $return['status'] = 0;
    $return['message'] = 'Fake failure';

    return $return;
}

PHP, ['{P}' => $prefix]);
    }

    return [$tasklist, $lang, $code];
}

/**
 * Ghi src/install/update_data.php
 *
 * @param array $cfg Các khóa formodule, from, to, auto
 * @param array $tasklist
 * @param array $lang
 * @param string $code
 */
function upk_write_update_data(array $cfg, array $tasklist, array $lang, string $code): void
{
    $config = [
        'type' => 1,
        // Tiền tố NVUD để webtools/deleteupdate dọn được file nhật ký config_update_NVUD*.php
        'packageID' => 'NVUDFAKE' . date('ymdHis'),
        'formodule' => $cfg['formodule'],
        'release_date' => time(),
        'author' => 'VINADES.,JSC <contact@vinades.vn>',
        'support_website' => 'https://github.com/nukeviet/nukeviet',
        'to_version' => $cfg['to'],
        'allow_old_version' => [$cfg['from']],
        'update_auto_type' => $cfg['auto'],
        'lang' => $lang,
        'tasklist' => array_map(function ($task) use ($cfg) {
            return ['r' => $cfg['to']] + $task;
        }, $tasklist)
    ];

    $content = "<?php\n\n";
    $content .= "/**\n * Gói nâng cấp giả lập do tools/update-package tạo lúc " . date('Y-m-d H:i:s') . "\n";
    $content .= " * Các tác vụ không thay đổi dữ liệu thật, file trong update/ giống hệt file trong repo\n */\n\n";
    $content .= "if (!defined('NV_IS_UPDATE')) {\n    exit('Stop!!!');\n}\n\n";
    $content .= '$nv_update_config = ' . var_export($config, true) . ";\n\n";
    $content .= $code;

    if (file_put_contents(UPK_INSTALLDIR . '/update_data.php', rtrim($content) . "\n") === false) {
        upk_die('Không ghi được src/install/update_data.php');
    }
}

/**
 * Ghi file hướng dẫn nâng cấp thủ công để thử bước con 5 hoặc kiểu nâng cấp bằng tay
 */
function upk_write_docs(): void
{
    $docs = [
        'vi' => '<h2>Hướng dẫn nâng cấp giả lập</h2><p>Đây là nội dung hướng dẫn mẫu do tools/update-package tạo. Không cần làm gì thêm.</p>',
        'en' => '<h2>Fake upgrade guide</h2><p>This is a sample guide created by tools/update-package. Nothing else to do.</p>',
        'fr' => '<h2>Guide de mise à jour factice</h2><p>Guide d\'exemple créé par tools/update-package. Rien d\'autre à faire.</p>'
    ];
    foreach ($docs as $lang => $html) {
        file_put_contents(UPK_INSTALLDIR . '/update_docs_' . $lang . '.html', $html . "\n");
    }
}

/**
 * Chạy toàn bộ quy trình tạo gói
 *
 * @param array $cfg Các khóa formodule, from, paths, options
 */
function upk_make(array $cfg): void
{
    $options = $cfg['options'];
    $fail = (string) ($options['fail'] ?? '');
    if (!in_array($fail, ['', 'warn', 'error'], true)) {
        upk_die('--fail chỉ nhận warn hoặc error');
    }
    $auto = (int) ($options['auto'] ?? 1);
    if (!in_array($auto, [0, 1, 2], true)) {
        upk_die('--auto chỉ nhận 0, 1 hoặc 2');
    }
    $paths = isset($options['files']) ? array_filter(explode(',', (string) $options['files'])) : $cfg['paths'];

    upk_check_target();

    $from = (string) $cfg['from'];
    $to = upk_bump_version($from);
    $prefix = 'nv_up_fake' . ($cfg['formodule'] === '' ? '' : 'mod') . '_';
    [$tasklist, $lang, $code] = upk_build_tasks($prefix, $fail);

    $count = upk_copy_paths($paths);
    upk_write_update_data([
        'formodule' => $cfg['formodule'],
        'from' => $from,
        'to' => $to,
        'auto' => $auto
    ], $tasklist, $lang, $code);
    if (!empty($options['docs']) or $auto === 0) {
        upk_write_docs();
    }

    echo 'Đã tạo gói nâng cấp giả lập' . ($cfg['formodule'] === '' ? ' hệ thống' : ' module ' . $cfg['formodule']) . PHP_EOL;
    echo '- Phiên bản: ' . $from . ' => ' . $to . PHP_EOL;
    echo '- Tác vụ: ' . count($tasklist) . ', file chép vào src/install/update: ' . $count . PHP_EOL;
    echo '- Chạy tại: /install/update.php (đăng nhập quản trị tối cao)' . PHP_EOL;
}
