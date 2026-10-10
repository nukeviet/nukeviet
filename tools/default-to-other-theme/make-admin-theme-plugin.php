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
 * Sinh lại 2 plugin includes/plugin/get_global_admin_theme.php và get_module_admin_theme.php
 * để các op quản trị còn viết theo giao diện cũ (XTemplate) dùng giao diện quản trị cũ của site,
 * các op còn lại dùng giao diện quản trị mặc định.
 *
 * Quét:
 * - modules/{module_file}/admin/*.php, hàm dùng chung ở modules/{module_file}/admin.functions.php
 * - admin/{module_name}/*.php (trừ admin.menu.php, functions.php), hàm dùng chung ở admin/{module_name}/functions.php
 *
 * Op bị đánh dấu dùng giao diện cũ khi:
 * - Có new XTemplate hoặc gọi hàm dùng chung có new XTemplate
 * - Gọi tpl (tên viết cứng hoặc $op . '.tpl') không có trong themes/{giao diện mới}/modules/{module}/
 *
 * Cách dùng (chạy sau khi MR core):
 * php tools/default-to-other-theme/make-admin-theme-plugin.php admin_dauthau [--dry-run] [--root=src] [--new=admin_default]
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Chỉ chạy bằng dòng lệnh\n");
}

$oldTheme = '';
$dryRun = false;
$rootDir = realpath(__DIR__ . '/../../src') ?: '';
// Giao diện quản trị mới dùng để kiểm tra tpl đã có chưa, --new= chỉ dùng khi test
$newTheme = 'admin_default';

foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--dry-run') {
        $dryRun = true;
    } elseif (str_starts_with($arg, '--root=')) {
        $rootDir = realpath(substr($arg, 7)) ?: '';
    } elseif (str_starts_with($arg, '--new=')) {
        $newTheme = substr($arg, 6);
    } elseif (!str_starts_with($arg, '--')) {
        $oldTheme = $arg;
    }
}

if ($oldTheme === '' or !preg_match('/^[a-zA-Z0-9_\-]+$/', $oldTheme)) {
    exit("Cách dùng: php " . basename(__FILE__) . " admin_tengiaodiencu [--dry-run] [--root=src] [--new=admin_default]\n");
}
if ($rootDir === '' or !file_exists($rootDir . '/includes/mainfile.php')) {
    exit("Không tìm thấy thư mục gốc của site, dùng --root= để chỉ định\n");
}
$rootDir = str_replace(DIRECTORY_SEPARATOR, '/', $rootDir);

if (!file_exists($rootDir . '/themes/' . $oldTheme . '/theme.php')) {
    exit("Không tồn tại giao diện cũ themes/" . $oldTheme . "\n");
}
if (!file_exists($rootDir . '/themes/' . $newTheme . '/theme.php')) {
    exit("Không tồn tại giao diện mới themes/" . $newTheme . "\n");
}
if ($oldTheme === $newTheme) {
    exit("Giao diện cũ và giao diện mới trùng nhau\n");
}

echo "Thư mục gốc: " . $rootDir . "\n";
echo "Giao diện cũ: " . $oldTheme . ", giao diện mới: " . $newTheme . "\n\n";

$warnings = [];

// Module của site: khớp theo module_file
$siteModules = scanGroup($rootDir . '/modules', '/admin', '/admin.functions.php', [], $rootDir, $newTheme, $warnings);
// Module quản trị của hệ thống: khớp theo module_name
$adminModules = scanGroup($rootDir . '/admin', '', '/functions.php', ['admin.menu.php', 'functions.php'], $rootDir, $newTheme, $warnings);

$siteList = buildList($siteModules);
$adminList = buildList($adminModules);

// In kết quả
$count = 0;
foreach (['modules' => $siteModules, 'admin' => $adminModules] as $group => $modules) {
    foreach ($modules as $module => $info) {
        foreach ($info['flagged'] as $op => $reasons) {
            ++$count;
            echo $group . '/' . $module . ' | ' . $op . ' | ' . implode('; ', $reasons) . "\n";
        }
    }
}
if ($count === 0) {
    echo "Không có op nào cần giao diện cũ\n";
}
if (!empty($warnings)) {
    echo "\nCần xem lại thủ công:\n";
    foreach ($warnings as $warning) {
        echo '- ' . $warning . "\n";
    }
}

if ($dryRun) {
    echo "\n--dry-run: không ghi tệp\n";
    exit(0);
}

foreach (['get_global_admin_theme', 'get_module_admin_theme'] as $hookName) {
    $file = $rootDir . '/includes/plugin/' . $hookName . '.php';
    if (file_put_contents($file, buildPlugin($hookName, $oldTheme, $siteList, $adminList)) === false) {
        exit("Không ghi được tệp " . $file . "\n");
    }
    echo "\nĐã ghi: includes/plugin/" . $hookName . '.php';
}
echo "\n\nKiểm tra 2 plugin đang bật ở Quản trị > Cấu hình > Thiết lập Plugin, sau đó xóa cache.\n";

/**
 * Quét một nhóm module
 *
 * @param string $baseDir     modules hoặc admin
 * @param string $opSubDir    thư mục con chứa op (/admin với modules, rỗng với admin)
 * @param string $sharedFile  tệp hàm dùng chung
 * @param array  $excludes    các tệp không phải op
 * @param string $rootDir
 * @param string $newTheme
 * @param array  $warnings
 * @return array [module => ['ops' => [op], 'flagged' => [op => [lý do]]]]
 */
function scanGroup(string $baseDir, string $opSubDir, string $sharedFile, array $excludes, string $rootDir, string $newTheme, array &$warnings): array
{
    $result = [];
    $dirs = glob($baseDir . '/*', GLOB_ONLYDIR) ?: [];
    sort($dirs);

    foreach ($dirs as $dir) {
        $module = basename($dir);
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

        $ops = [];
        $flagged = [];
        foreach ($opFiles as $opFile) {
            $op = basename($opFile, '.php');
            $ops[] = $op;
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

        $result[$module] = ['ops' => $ops, 'flagged' => $flagged];
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

/**
 * Danh sách đưa vào plugin, cả module dùng giao diện cũ thì ghi ['*']
 *
 * @param array $modules
 * @return array
 */
function buildList(array $modules): array
{
    $list = [];
    foreach ($modules as $module => $info) {
        if (empty($info['flagged'])) {
            continue;
        }
        $list[$module] = count($info['flagged']) === count($info['ops']) ? ['*'] : array_keys($info['flagged']);
    }

    return $list;
}

/**
 * Xuất mảng dạng [] thụt lề 4 dấu cách
 *
 * @param array $list
 * @param int   $indent
 * @return string
 */
function exportList(array $list, int $indent): string
{
    if (empty($list)) {
        return '[]';
    }
    $pad = str_repeat(' ', $indent + 4);
    $lines = [];
    foreach ($list as $module => $ops) {
        $lines[] = $pad . var_export((string) $module, true) . ' => [' . implode(', ', array_map(fn ($op) => var_export((string) $op, true), $ops)) . ']';
    }

    return "[\n" . implode(",\n", $lines) . "\n" . str_repeat(' ', $indent) . ']';
}

/**
 * Nội dung tệp plugin
 *
 * @param string $hookName
 * @param string $oldTheme
 * @param array  $siteList
 * @param array  $adminList
 * @return string
 */
function buildPlugin(string $hookName, string $oldTheme, array $siteList, array $adminList): string
{
    return "<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/*
 * Tệp sinh tự động bởi tools/default-to-other-theme/make-admin-theme-plugin.php
 * Các op quản trị còn dùng giao diện cũ " . $oldTheme . ", các op còn lại dùng giao diện mặc định
 */
nv_add_hook(\$module_name, '" . $hookName . "', \$priority, function (\$vars) {
    // Module của site: module_file => các op, ['*'] là cả module
    \$site_modules = " . exportList($siteList, 4) . ";
    // Module quản trị của hệ thống: module_name => các op, ['*'] là cả module
    \$admin_modules = " . exportList($adminList, 4) . ";

    \$module_info = \$vars[2];
    if (isset(\$module_info['module_file'])) {
        \$ops = \$site_modules[\$module_info['module_file']] ?? [];
    } else {
        \$ops = \$admin_modules[\$vars[1]] ?? [];
    }
    if (in_array('*', \$ops, true) or in_array(\$vars[3], \$ops, true)) {
        return '" . $oldTheme . "';
    }

    return \$vars[0];
});
";
}
