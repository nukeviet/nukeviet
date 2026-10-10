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
 * Kiểm tra những gì cần chuyển sang Smarty trước khi site chuyển từ giao diện riêng sang giao diện default mới.
 * Chỉ đọc, không sửa tệp hay CSDL.
 *
 * Quét:
 * - Code ngoài site của module (funcs, blocks, theme.php, functions.php, global.functions.php):
 *   còn dùng XTemplate, hoặc gọi tpl chưa có trong themes/default/modules/{module}/
 * - Tệp còn sót trong themes/default/modules/ và themes/default/blocks/:
 *   tệp php dùng XTemplate, tệp tpl viết kiểu XTemplate
 *
 * Cách dùng (chạy sau khi MR core):
 * php tools/default-to-other-theme/check-convert-default.php [--root=src]
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Chỉ chạy bằng dòng lệnh\n");
}

$rootDir = realpath(__DIR__ . '/../../src') ?: '';
foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--root=')) {
        $rootDir = realpath(substr($arg, 7)) ?: '';
    }
}

if ($rootDir === '' or !file_exists($rootDir . '/includes/mainfile.php')) {
    exit("Không tìm thấy thư mục gốc của site, dùng --root= để chỉ định\n");
}
$rootDir = str_replace(DIRECTORY_SEPARATOR, '/', $rootDir);
$themeDir = $rootDir . '/themes/default';
if (!file_exists($themeDir . '/theme.php')) {
    exit("Không tồn tại themes/default\n");
}
if (is_dir($rootDir . '/themes/future')) {
    exit("Còn thư mục themes/future, công cụ này chạy sau khi MR core\n");
}

echo "Thư mục gốc: " . $rootDir . "\n";

// Module đã gỡ khỏi core, không quét
$removedModules = ['zalo', 'freecontent'];

// [tên module hoặc khu vực => [đường dẫn tệp => [lý do]]]
$issues = [];

/*
 * 1. Code ngoài site của module
 */
foreach (glob($rootDir . '/modules/*', GLOB_ONLYDIR) ?: [] as $dir) {
    $module = basename($dir);
    if (in_array($module, $removedModules, true)) {
        continue;
    }
    $tplDir = $themeDir . '/modules/' . $module;
    $files = array_merge(
        glob($dir . '/funcs/*.php') ?: [],
        glob($dir . '/blocks/*.php') ?: [],
        array_filter([$dir . '/theme.php', $dir . '/functions.php', $dir . '/global.functions.php'], 'file_exists')
    );
    foreach ($files as $file) {
        // Tệp trong funcs là op, tpl ghép từ $op thì lấy theo tên tệp
        $op = basename(dirname($file)) === 'funcs' ? basename($file, '.php') : '';
        $reasons = analyzeCode((string) file_get_contents($file), $tplDir, $op);
        if (!empty($reasons)) {
            $issues[$module][substr($file, strlen($rootDir) + 1)] = $reasons;
        }
    }
}

/*
 * 2. Tệp còn sót trong themes/default (MR không xóa tệp riêng của site)
 */
$leftoverDirs = glob($themeDir . '/modules/*', GLOB_ONLYDIR) ?: [];
$leftoverDirs[] = $themeDir . '/blocks';
foreach ($leftoverDirs as $dir) {
    $area = basename($dir) === 'blocks' && dirname($dir) === $themeDir ? 'giao diện (blocks)' : basename($dir);
    foreach (glob($dir . '/*.php') ?: [] as $file) {
        if (preg_match('/\bnew\s+\\\\?XTemplate\b/i', (string) file_get_contents($file))) {
            $issues[$area][substr($file, strlen($rootDir) + 1)][] = 'tệp php dùng XTemplate còn sót';
        }
    }
    foreach (glob($dir . '/*.tpl') ?: [] as $file) {
        if (preg_match('/<!--\s*BEGIN:/', (string) file_get_contents($file))) {
            $issues[$area][substr($file, strlen($rootDir) + 1)][] = 'tpl viết kiểu XTemplate còn sót';
        }
    }
}

/*
 * Kết quả
 */
ksort($issues);
if (!empty($issues)) {
    echo "\n=== Cần xử lý ===\n";
    foreach ($issues as $area => $files) {
        echo "\n" . $area . ":\n";
        ksort($files);
        foreach ($files as $file => $reasons) {
            echo '  - ' . $file . ': ' . implode('; ', array_unique($reasons)) . "\n";
        }
    }
}

echo "\n=== Kết luận ===\n";
if (!empty($issues)) {
    echo "Chưa chuyển được, còn " . count($issues) . " module/khu vực cần xử lý: " . implode(', ', array_keys($issues)) . "\n";
} else {
    echo "Có thể chuyển sang giao diện default: vào Quản trị > Giao diện kích hoạt default, thiết lập layout, xếp lại block\n";
}

/**
 * Tìm dấu hiệu chưa chuyển sang Smarty trong một tệp
 *
 * @param string $code
 * @param string $tplDir thư mục tpl của module ở giao diện default
 * @param string $op     tên op, rỗng nếu không phải tệp trong funcs
 * @return array lý do
 */
function analyzeCode(string $code, string $tplDir, string $op): array
{
    $reasons = [];
    if (preg_match('/\bnew\s+\\\\?XTemplate\b/i', $code)) {
        // Tpl của XTemplate không dùng được với Smarty nên không cần kiểm tra tpl
        return ['dùng XTemplate'];
    }

    // Tên tpl viết cứng, bỏ qua tpl hệ thống có đường dẫn
    $tpls = [];
    if (preg_match_all('/[\'"]([A-Za-z0-9_.\-]+\.tpl)[\'"]/', $code, $m)) {
        $tpls = $m[1];
    }

    // Tên tpl ghép động, bỏ qua layout của giao diện và tpl có đường dẫn
    if (preg_match_all('/([\'"]([^\'"]*)[\'"]\s*\.\s*)?\$(\w+)\s*\.\s*[\'"]\.tpl[\'"]/', $code, $m, PREG_SET_ORDER)) {
        foreach ($m as $match) {
            $prefix = $match[2] ?? '';
            if (str_contains($prefix, 'layout.') or str_contains($prefix, '/')) {
                continue;
            }
            if ($match[3] === 'op' and $op !== '' and $prefix === '') {
                $tpls[] = $op . '.tpl';
            } elseif (empty(glob($tplDir . '/*.tpl'))) {
                // Không đoán được tên tpl, chỉ báo khi module chưa có tpl nào ở giao diện default
                $reasons[] = 'chưa có tpl nào trong themes/default/modules/' . basename($tplDir);
            }
        }
    }

    foreach (array_unique($tpls) as $tpl) {
        if (file_exists($tplDir . '/' . $tpl)) {
            continue;
        }
        // Tpl lấy từ module khác: get_module_tpl_dir('x.tpl', true, 'module') hoặc module: 'module'
        $pattern = '/get_(?:module|block)_tpl_dir\(\s*[\'"]' . preg_quote($tpl, '/') . '[\'"][^)]*?[\'"]([\w\-]+)[\'"]\s*\)/';
        if (preg_match($pattern, $code, $mm) and file_exists(dirname($tplDir) . '/' . $mm[1] . '/' . $tpl)) {
            continue;
        }
        $reasons[] = 'thiếu ' . $tpl . ' trong themes/default/modules/' . basename($tplDir);
    }

    return $reasons;
}
