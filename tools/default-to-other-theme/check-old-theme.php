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
 * Kiểm tra site còn module, giao diện viết theo kiểu cũ (XTemplate) không,
 * để biết cần làm phần nào trong hướng dẫn xóa giao diện admin_default, default cũ (docs/CHANGE-2026.md).
 * Chỉ đọc, không sửa tệp hay CSDL.
 *
 * Cách dùng (chạy trước khi MR core):
 * php tools/default-to-other-theme/check-old-theme.php [--root=src] [--new=admin_future]
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Chỉ chạy bằng dòng lệnh\n");
}

$rootDir = realpath(__DIR__ . '/../../src') ?: '';
// Giao diện quản trị mới để kiểm tra tpl, trước khi MR là admin_future
$newTheme = 'admin_future';

foreach (array_slice($argv, 1) as $arg) {
    if (str_starts_with($arg, '--root=')) {
        $rootDir = realpath(substr($arg, 7)) ?: '';
    } elseif (str_starts_with($arg, '--new=')) {
        $newTheme = substr($arg, 6);
    }
}

if ($rootDir === '' or !file_exists($rootDir . '/includes/mainfile.php')) {
    exit("Không tìm thấy thư mục gốc của site, dùng --root= để chỉ định\n");
}
$rootDir = str_replace(DIRECTORY_SEPARATOR, '/', $rootDir);
if (!file_exists($rootDir . '/themes/' . $newTheme . '/theme.php')) {
    exit("Không tồn tại themes/" . $newTheme . ", công cụ này chạy trước khi MR core\n");
}

echo "Thư mục gốc: " . $rootDir . "\n";

$warnings = [];
$todo = [];

// Module đã gỡ khỏi core, MR sẽ xóa nên không quét
$removedModules = ['zalo', 'freecontent'];

/*
 * 1. Giao diện quản trị
 */
echo "\n=== 1. Giao diện quản trị ===\n";

$siteModules = scanAdminGroup($rootDir . '/modules', '/admin', '/admin.functions.php', [], $removedModules, $rootDir, $newTheme, $warnings);
$adminModules = scanAdminGroup($rootDir . '/admin', '', '/functions.php', ['admin.menu.php', 'functions.php'], [], $rootDir, $newTheme, $warnings);

$adminCount = 0;
foreach (['modules' => $siteModules, 'admin' => $adminModules] as $group => $modules) {
    foreach ($modules as $module => $flagged) {
        foreach ($flagged as $op => $reasons) {
            ++$adminCount;
            echo $group . '/' . $module . ' | ' . $op . ' | ' . implode('; ', $reasons) . "\n";
        }
    }
}
if ($adminCount > 0) {
    echo "=> Có " . $adminCount . " trang quản trị còn dùng giao diện cũ\n";
} else {
    echo "=> Không có trang quản trị nào còn dùng giao diện cũ\n";
}

/*
 * 2. Giao diện ngoài site
 */
echo "\n=== 2. Giao diện ngoài site ===\n";

// Giao diện riêng của site
$customThemes = [];
$mobileThemes = [];
foreach (glob($rootDir . '/themes/*', GLOB_ONLYDIR) ?: [] as $dir) {
    $theme = basename($dir);
    if (in_array($theme, ['default', 'future', 'mobile_default'], true) or str_starts_with($theme, 'admin_')) {
        continue;
    }
    if (str_starts_with($theme, 'mobile_')) {
        $mobileThemes[] = $theme;
    } else {
        $customThemes[] = $theme;
    }
}
if (!empty($customThemes)) {
    echo "Giao diện riêng: " . implode(', ', $customThemes) . "\n";
    $todo[] = 'Có giao diện riêng: chạy update-theme.php trước khi MR';
} else {
    echo "Không có giao diện riêng\n";
}
if (!empty($mobileThemes)) {
    echo "Giao diện mobile riêng: " . implode(', ', $mobileThemes) . "\n";
    $todo[] = 'Có giao diện mobile riêng (' . implode(', ', $mobileThemes) . '): update-theme.php không xử lý, tự chép những gì đang mượn của default cũ';
}

// Module có code ngoài site còn dùng XTemplate
$oldSiteModules = [];
foreach (glob($rootDir . '/modules/*', GLOB_ONLYDIR) ?: [] as $dir) {
    $module = basename($dir);
    if (in_array($module, $removedModules, true)) {
        continue;
    }
    $files = array_merge(
        glob($dir . '/funcs/*.php') ?: [],
        glob($dir . '/blocks/*.php') ?: [],
        array_filter([$dir . '/theme.php', $dir . '/functions.php', $dir . '/global.functions.php'], 'file_exists')
    );
    $hits = [];
    foreach ($files as $file) {
        if (preg_match('/\bnew\s+\\\\?XTemplate\b/i', (string) file_get_contents($file))) {
            $hits[] = substr($file, strlen($dir) + 1);
        }
    }
    if (!empty($hits)) {
        // Thư mục giao diện đang chứa tpl của module
        $tplThemes = [];
        foreach (glob($rootDir . '/themes/*/modules/' . $module, GLOB_ONLYDIR) ?: [] as $tplDir) {
            $theme = basename(dirname(dirname($tplDir)));
            if (!str_starts_with($theme, 'admin_')) {
                $tplThemes[] = $theme;
            }
        }
        $oldSiteModules[$module] = $tplThemes;
        echo "Module " . $module . " dùng XTemplate: " . implode(', ', $hits) . "\n";
        echo "    tpl đang nằm ở giao diện: " . (empty($tplThemes) ? '(không tìm thấy)' : implode(', ', $tplThemes)) . "\n";
    }
}
if (empty($oldSiteModules)) {
    echo "Không có module nào ngoài site còn dùng XTemplate\n";
}

/*
 * 3. CSDL: giao diện đang dùng, plugin
 */
echo "\n=== 3. Cấu hình trong CSDL ===\n";

$pdo = null;
$prefix = '';
if (!file_exists($rootDir . '/config.php')) {
    $warnings[] = 'Không có tệp config.php, bỏ qua phần kiểm tra CSDL';
} else {
    try {
        define('NV_MAINFILE', true);
        $db_config = [];
        $global_config = [];
        include $rootDir . '/config.php';
        $prefix = $db_config['prefix'] ?? '';
        $dsn = 'mysql:host=' . ($db_config['dbhost'] ?? '') . (!empty($db_config['dbport']) ? ';port=' . $db_config['dbport'] : '') . ';dbname=' . ($db_config['dbname'] ?? '') . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $db_config['dbuname'] ?? '', $db_config['dbpass'] ?? '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (Throwable $e) {
        $warnings[] = 'Không kết nối được CSDL (' . $e->getMessage() . '), bỏ qua phần kiểm tra CSDL';
        $pdo = null;
    }
}

$useDefault = false;
$pluginDeclared = false;
if ($pdo !== null) {
    try {
        $langs = $pdo->query('SELECT lang FROM ' . $prefix . '_setup_language WHERE setup=1 ORDER BY weight ASC')->fetchAll(PDO::FETCH_COLUMN);
        $sth = $pdo->prepare('SELECT config_name, config_value FROM ' . $prefix . "_config WHERE lang = :lang AND module = 'global' AND config_name IN ('site_theme', 'mobile_theme', 'user_allowed_theme')");
        foreach ($langs as $lang) {
            $sth->execute([':lang' => $lang]);
            $cfg = $sth->fetchAll(PDO::FETCH_KEY_PAIR);
            echo "Ngôn ngữ " . $lang . ": site_theme=" . ($cfg['site_theme'] ?? '') . ", mobile_theme=" . ($cfg['mobile_theme'] ?? '') . ", user_allowed_theme=" . ($cfg['user_allowed_theme'] ?? '') . "\n";
            if (($cfg['site_theme'] ?? '') === 'default' or in_array('default', explode(',', (string) ($cfg['user_allowed_theme'] ?? '')), true)) {
                $useDefault = true;
            }

            // Module đặt giao diện riêng
            $rows = $pdo->query('SELECT title, theme, mobile FROM ' . $prefix . '_' . $lang . "_modules WHERE theme != '' OR mobile != ''")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                echo "    module " . $row['title'] . ": theme=" . $row['theme'] . ", mobile=" . $row['mobile'] . "\n";
                if ($row['theme'] === 'default') {
                    $useDefault = true;
                }
            }
        }

        $plugins = $pdo->query('SELECT plugin_file FROM ' . $prefix . "_plugins WHERE plugin_file IN ('get_global_admin_theme.php', 'get_module_admin_theme.php')")->fetchAll(PDO::FETCH_COLUMN);
        echo "Plugin chọn giao diện quản trị trong CSDL: " . (empty($plugins) ? 'không có' : implode(', ', $plugins)) . "\n";
        $pluginDeclared = !empty($plugins);
    } catch (Throwable $e) {
        $warnings[] = 'Lỗi đọc CSDL: ' . $e->getMessage();
    }
}

$configGlobal = $rootDir . '/data/config/config_global.php';
if (file_exists($configGlobal)) {
    $content = (string) file_get_contents($configGlobal);
    $declared = (str_contains($content, 'get_global_admin_theme.php') or str_contains($content, 'get_module_admin_theme.php'));
    echo "Plugin chọn giao diện quản trị trong config_global.php: " . ($declared ? 'có' : 'không có') . "\n";
    $pluginDeclared = ($pluginDeclared or $declared);
}

// Giao diện quản trị đứng đầu danh sách cần làm
if ($adminCount > 0) {
    array_unshift($todo, 'Giữ giao diện quản trị cũ: làm phần "Giao diện quản trị" ở bước 1 và bước 3');
} elseif ($pluginDeclared) {
    array_unshift($todo, 'Không cần giữ giao diện quản trị cũ: chỉ xóa 2 plugin ở Cấu hình > Thiết lập Plugin trước khi MR');
}

if ($useDefault) {
    $todo[] = 'Site đang dùng thẳng giao diện default: bắt buộc chép default thành giao diện riêng (chép thư mục, chạy SQL, update-theme.php, kích hoạt)';
} elseif (!empty($oldSiteModules) and empty($customThemes)) {
    $todo[] = 'Có module ngoài site dùng XTemplate nhưng không có giao diện riêng chứa tpl: kiểm tra lại giao diện đang dùng';
}

/*
 * Kết luận
 */
if (!empty($warnings)) {
    echo "\n=== Cần xem lại thủ công ===\n";
    foreach (array_unique($warnings) as $warning) {
        echo '- ' . $warning . "\n";
    }
}

echo "\n=== Cần làm ===\n";
if (empty($todo)) {
    echo "Không cần làm gì, có thể MR core\n";
}
foreach ($todo as $i => $item) {
    echo ($i + 1) . '. ' . $item . "\n";
}

/**
 * Quét op quản trị của một nhóm module
 *
 * @param string $baseDir     modules hoặc admin
 * @param string $opSubDir    thư mục con chứa op (/admin với modules, rỗng với admin)
 * @param string $sharedFile  tệp hàm dùng chung
 * @param array  $excludes    các tệp không phải op
 * @param array  $skipModules các module bỏ qua
 * @param string $rootDir
 * @param string $newTheme
 * @param array  $warnings
 * @return array [module => [op => [lý do]]], chỉ gồm module có op dùng giao diện cũ
 */
function scanAdminGroup(string $baseDir, string $opSubDir, string $sharedFile, array $excludes, array $skipModules, string $rootDir, string $newTheme, array &$warnings): array
{
    $result = [];
    $dirs = glob($baseDir . '/*', GLOB_ONLYDIR) ?: [];
    sort($dirs);

    foreach ($dirs as $dir) {
        $module = basename($dir);
        if (in_array($module, $skipModules, true)) {
            continue;
        }
        $opFiles = glob($dir . $opSubDir . '/*.php') ?: [];
        $opFiles = array_filter($opFiles, fn ($f) => !in_array(basename($f), $excludes, true));
        if (empty($opFiles)) {
            continue;
        }
        $tplDir = $rootDir . '/themes/' . $newTheme . '/modules/' . $module;
        $label = basename($baseDir) . '/' . $module;

        // Phân tích hàm dùng chung
        $sharedFuncs = [];
        $sharedTop = [];
        if (file_exists($dir . $sharedFile)) {
            [$funcs, $topCode] = splitFunctions((string) file_get_contents($dir . $sharedFile));
            foreach ($funcs as $name => $body) {
                $sharedFuncs[$name] = [
                    'body' => $body,
                    'reasons' => analyzeCode($body, $tplDir, '', $label . $sharedFile . ':' . $name, $warnings)
                ];
            }
            $sharedTop = analyzeCode($topCode, $tplDir, '', $label . $sharedFile, $warnings);
            markCallers($sharedFuncs);
        }

        $flagged = [];
        foreach ($opFiles as $opFile) {
            $op = basename($opFile, '.php');
            $code = (string) file_get_contents($opFile);
            $reasons = analyzeCode($code, $tplDir, $op, $label . '/' . $op . '.php', $warnings);
            foreach ($sharedFuncs as $name => $func) {
                if (!empty($func['reasons']) and preg_match('/(?<![\w>:$])' . preg_quote($name, '/') . '\s*\(/i', $code)) {
                    $reasons[] = 'gọi hàm ' . $name . '()';
                }
            }
            if (!empty($sharedTop)) {
                $reasons[] = basename($sharedFile) . ': ' . implode(', ', $sharedTop);
            }
            if (!empty($reasons)) {
                $flagged[$op] = array_values(array_unique($reasons));
            }
        }

        if (!empty($flagged)) {
            $result[$module] = $flagged;
        }
    }

    return $result;
}

/**
 * Tìm dấu hiệu giao diện cũ trong một đoạn code
 *
 * @param string $code
 * @param string $tplDir   thư mục tpl của module ở giao diện mới
 * @param string $op       tên op, rỗng nếu là hàm dùng chung
 * @param string $label    tên hiển thị khi cảnh báo
 * @param array  $warnings
 * @return array lý do
 */
function analyzeCode(string $code, string $tplDir, string $op, string $label, array &$warnings): array
{
    $reasons = [];
    if (preg_match('/\bnew\s+\\\\?XTemplate\b/i', $code)) {
        $reasons[] = 'dùng XTemplate';
    }

    // Tên tpl viết cứng, bỏ qua tpl hệ thống có đường dẫn
    $tpls = [];
    if (preg_match_all('/[\'"]([A-Za-z0-9_.\-]+\.tpl)[\'"]/', $code, $m)) {
        $tpls = $m[1];
    }

    // Tên tpl ghép động, bỏ qua layout của giao diện site và tpl có đường dẫn
    if (preg_match_all('/([\'"]([^\'"]*)[\'"]\s*\.\s*)?\$(\w+)\s*\.\s*[\'"]\.tpl[\'"]/', $code, $m, PREG_SET_ORDER)) {
        foreach ($m as $match) {
            $prefix = $match[2] ?? '';
            if (str_contains($prefix, 'layout.') or str_contains($prefix, '/')) {
                continue;
            }
            if ($match[3] === 'op' and $op !== '' and $prefix === '') {
                $tpls[] = $op . '.tpl';
            } else {
                $warnings[] = $label . ': tên tpl ghép từ biến $' . $match[3];
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
        $reasons[] = 'thiếu ' . $tpl;
    }

    return $reasons;
}

/**
 * Tách các hàm trong tệp PHP
 *
 * @param string $code
 * @return array [[tên hàm => thân hàm], phần code ngoài hàm]
 */
function splitFunctions(string $code): array
{
    $tokens = token_get_all($code);
    $funcs = [];
    $top = '';
    $count = count($tokens);

    for ($i = 0; $i < $count; ++$i) {
        $token = $tokens[$i];
        if (is_array($token) and $token[0] === T_FUNCTION) {
            // Tìm tên hàm, bỏ qua hàm ẩn danh
            $j = $i + 1;
            while ($j < $count and is_array($tokens[$j]) and $tokens[$j][0] === T_WHITESPACE) {
                ++$j;
            }
            if ($j < $count and is_array($tokens[$j]) and $tokens[$j][0] === T_STRING) {
                $name = $tokens[$j][1];
                $body = '';
                $depth = 0;
                $started = false;
                for ($k = $i; $k < $count; ++$k) {
                    $text = is_array($tokens[$k]) ? $tokens[$k][1] : $tokens[$k];
                    $body .= $text;
                    if ($text === '{' or (is_array($tokens[$k]) and in_array($tokens[$k][0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true))) {
                        ++$depth;
                        $started = true;
                    } elseif ($text === '}') {
                        --$depth;
                        if ($started and $depth === 0) {
                            break;
                        }
                    }
                }
                $funcs[$name] = $body;
                $i = $k;
                continue;
            }
        }
        $top .= is_array($token) ? $token[1] : $token;
    }

    return [$funcs, $top];
}

/**
 * Hàm gọi tới hàm dùng giao diện cũ cũng bị đánh dấu
 *
 * @param array $funcs
 */
function markCallers(array &$funcs): void
{
    do {
        $changed = false;
        foreach ($funcs as $name => $func) {
            if (!empty($func['reasons'])) {
                continue;
            }
            foreach ($funcs as $callee => $calleeFunc) {
                if ($callee !== $name and !empty($calleeFunc['reasons']) and preg_match('/(?<![\w>:$])' . preg_quote($callee, '/') . '\s*\(/i', $func['body'])) {
                    $funcs[$name]['reasons'][] = 'gọi hàm ' . $callee . '()';
                    $changed = true;
                    break;
                }
            }
        }
    } while ($changed);
}
