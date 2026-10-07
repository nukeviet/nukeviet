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
 * Cập nhật banner bản quyền trong mã nguồn NukeViet
 *
 * Cách dùng (chạy tại thư mục gốc repo):
 *   php tools/update-banner.php --dry-run     Liệt kê thay đổi, không ghi
 *   php tools/update-banner.php               Ghi thay đổi
 *   php tools/update-banner.php --check       Kiểm tra còn banner chưa chuẩn, exit 1 nếu còn
 *   php tools/update-banner.php --year=2027   Chỉ định năm, mặc định là năm hiện tại
 *   php tools/update-banner.php --list        Kèm danh sách file thay đổi
 *
 * Chỉ sửa đúng giá trị trong dòng của khối comment có VINADES và @copyright:
 * - Năm trong dòng @copyright
 * - Giá trị @version
 * - Tiêu đề NukeViet Content Management System (chuẩn hóa chữ hoa, thường)
 * Khối banner kiểu NukeViet 4 (có @project và @createdate) được thay bằng banner chuẩn.
 * Chỉ quét file git đang theo dõi, bỏ qua file assume-unchanged và skip-worktree.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("Chỉ chạy từ dòng lệnh\n");
}

define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(__DIR__ . '/..')));

const BANNER_VERSION = '5.x';
const BANNER_START_YEAR = '2009';
const BANNER_TITLE = 'NukeViet Content Management System';
const SCAN_DIRS = ['docs', 'scss', 'src', 'tests', 'tools'];
const SCAN_EXTS = ['css', 'js', 'map', 'md', 'php', 'scss', 'tpl'];
const SKIP_PATHS = '/^src\/data\/(cache|tmp)\//';

$options = getopt('', ['dry-run', 'check', 'list', 'year:', 'help']);
if (isset($options['help'])) {
    echo "php tools/update-banner.php [--dry-run|--check] [--year=YYYY] [--list]\n";
    exit(0);
}

$year = (string) ($options['year'] ?? date('Y'));
if (!preg_match('/^\d{4}$/', $year) or (int) $year < (int) BANNER_START_YEAR) {
    fwrite(STDERR, "Năm không hợp lệ: $year\n");
    exit(2);
}
$mode = isset($options['check']) ? 'check' : (isset($options['dry-run']) ? 'dry-run' : 'apply');

/**
 * Danh sách file git đang theo dõi trong phạm vi quét
 *
 * @return array{0: string[], 1: string[]} [file cần xử lý, file assume-unchanged/skip-worktree bị bỏ qua]
 */
function listFiles(): array
{
    $output = shell_exec('git -C ' . escapeshellarg(NV_ROOTDIR) . ' ls-files -v -z');
    if (!is_string($output) or $output === '') {
        fwrite(STDERR, "Không đọc được danh sách file từ git\n");
        exit(2);
    }

    $files = [];
    $ignored = [];
    foreach (explode("\0", $output) as $entry) {
        if (strlen($entry) < 3) {
            continue;
        }
        $tag = $entry[0];
        $path = substr($entry, 2);
        if (!in_array(explode('/', $path)[0], SCAN_DIRS, true) or preg_match(SKIP_PATHS, $path)) {
            continue;
        }
        if (!in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), SCAN_EXTS, true)) {
            continue;
        }
        // Chữ thường: assume-unchanged, S: skip-worktree. Đây là file local của Dev, không đụng tới
        if ($tag === 'S' or ctype_lower($tag)) {
            $ignored[] = $path;
            continue;
        }
        $files[] = $path;
    }

    return [$files, $ignored];
}

/**
 * Mẫu ký tự xuống dòng: dạng thô hoặc dạng đã escape trong chuỗi JSON (sourcesContent của .map)
 *
 * @param bool $json
 * @return string
 */
function eolPattern(bool $json): string
{
    return $json ? '((?:\\\\r)?\\\\n)' : '(\r?\n)';
}

/**
 * Loại dòng banner được phép thay đổi
 *
 * @param string $line
 * @return string|null
 */
function lineKind(string $line): ?string
{
    if (preg_match('/^\s*\*\s*@copyright\s+\(C\)\s+\d{4}(?:-\d{4})?\s+VINADES/i', $line)) {
        return 'copyright';
    }
    if (preg_match('/^\s*\*\s*@version\s+\S+\s*$/', $line)) {
        return 'version';
    }
    if (preg_match('/^\s*\*\s*nukeviet content management system\s*$/i', $line)) {
        return 'title';
    }

    return null;
}

/**
 * Sửa giá trị trong một dòng của khối banner
 *
 * @param string $line
 * @param string $year
 * @return string
 */
function fixLine(string $line, string $year): string
{
    switch (lineKind($line)) {
        case 'copyright':
            return preg_replace('/^(\s*\*\s*@copyright\s+\(C\)\s+)\d{4}(?:-\d{4})?(\s+VINADES)/i', '${1}' . BANNER_START_YEAR . '-' . $year . '${2}', $line);
        case 'version':
            return preg_replace('/^(\s*\*\s*@version\s+)\S+/', '${1}' . BANNER_VERSION, $line);
        case 'title':
            return preg_replace('/^(\s*\*\s*)nukeviet content management system/i', '${1}' . BANNER_TITLE, $line);
    }

    return $line;
}

/**
 * Banner chuẩn
 *
 * @param string $eol
 * @param string $year
 * @return string
 */
function defaultBanner(string $eol, string $year): string
{
    return implode($eol, [
        '/**',
        ' * ' . BANNER_TITLE,
        ' * @version ' . BANNER_VERSION,
        ' * @author VINADES.,JSC <contact@vinades.vn>',
        ' * @copyright (C) ' . BANNER_START_YEAR . '-' . $year . ' VINADES.,JSC. All rights reserved',
        ' * @license GNU/GPL version 2 or any later version',
        ' * @see https://github.com/nukeviet The NukeViet CMS GitHub project',
        ' */'
    ]);
}

/**
 * Khối comment có phải banner NukeViet không
 *
 * @param string $block
 * @return bool
 */
function isBanner(string $block): bool
{
    return stripos($block, 'VINADES') !== false and stripos($block, '@copyright') !== false;
}

/**
 * Khối comment có phải banner kiểu NukeViet 4 không
 *
 * @param string $block
 * @return bool
 */
function isLegacyBanner(string $block): bool
{
    return preg_match('/\*\s*@project\s/i', $block) and preg_match('/\*\s*@createdate\s/i', $block);
}

/**
 * Xử lý toàn bộ nội dung một file
 *
 * @param string $text
 * @param bool $json
 * @param string $year
 * @param array $stat Thống kê của file: legacy, blocks, nonstandard
 * @return array{0: string, 1: string} [nội dung sau khi chuyển banner cũ, nội dung cuối cùng]
 */
function processText(string $text, bool $json, string $year, array &$stat): array
{
    $stat = ['legacy' => 0, 'blocks' => 0, 'nonstandard' => 0];
    // File thường: khối comment phải bắt đầu ở đầu dòng để không đụng tới chuỗi trong code (ví dụ NV_FILEHEAD)
    // File .map: khối nằm giữa chuỗi JSON của sourcesContent
    $blockRegex = $json ? '/\/\*[*!].*?\*\//s' : '/^(?:\xEF\xBB\xBF)?[ \t]*\/\*[*!].*?\*\//ms';

    // Bước 1: thay banner NukeViet 4 bằng banner chuẩn, giữ kiểu xuống dòng của file
    $afterLegacy = preg_replace_callback($blockRegex, function ($m) use ($json, $year, &$stat) {
        if (!isBanner($m[0]) or !isLegacyBanner($m[0]) or $json) {
            return $m[0];
        }
        ++$stat['legacy'];
        $eol = preg_match('/\r\n/', $m[0]) ? "\r\n" : "\n";

        return defaultBanner($eol, $year);
    }, $text);

    // Bước 2: chỉ sửa giá trị trong từng dòng
    $final = preg_replace_callback($blockRegex, function ($m) use ($json, $year, &$stat) {
        if (!isBanner($m[0])) {
            return $m[0];
        }
        $parts = preg_split('/' . eolPattern($json) . '/', $m[0], -1, PREG_SPLIT_DELIM_CAPTURE);
        $hasCopyright = false;
        foreach ($parts as $i => $line) {
            // Phần tử lẻ là ký tự xuống dòng
            if ($i % 2) {
                continue;
            }
            lineKind($line) === 'copyright' && $hasCopyright = true;
            $parts[$i] = fixLine($line, $year);
        }
        if (!$hasCopyright) {
            ++$stat['nonstandard'];
        }
        $new = implode('', $parts);
        $new !== $m[0] && ++$stat['blocks'];

        return $new;
    }, $afterLegacy);

    return [$afterLegacy, $final];
}

/**
 * Kiểm chứng: chỉ các dòng banner được phép thay đổi, giữ nguyên số dòng và ký tự xuống dòng
 *
 * @param string $old
 * @param string $new
 * @return string|null Lý do lỗi hoặc null nếu hợp lệ
 */
function verifyText(string $old, string $new): ?string
{
    $oldLines = explode("\n", $old);
    $newLines = explode("\n", $new);
    if (count($oldLines) !== count($newLines)) {
        return 'số dòng thay đổi';
    }
    foreach ($oldLines as $i => $line) {
        if ($line === $newLines[$i]) {
            continue;
        }
        $kind = lineKind($newLines[$i]);
        if ($kind === null or $kind !== lineKind($line)) {
            return 'dòng ' . ($i + 1) . ' thay đổi ngoài phạm vi cho phép';
        }
        if (str_ends_with($line, "\r") !== str_ends_with($newLines[$i], "\r")) {
            return 'dòng ' . ($i + 1) . ' bị đổi ký tự xuống dòng';
        }
    }

    return null;
}

/**
 * Kiểm chứng file .map: JSON hợp lệ, chỉ sourcesContent thay đổi và thay đổi trong phạm vi cho phép
 *
 * @param string $old
 * @param string $new
 * @return string|null
 */
function verifyMap(string $old, string $new): ?string
{
    $oldMap = json_decode($old, true);
    $newMap = json_decode($new, true);
    if (!is_array($oldMap) or !is_array($newMap)) {
        return 'JSON không hợp lệ';
    }
    $oldSources = $oldMap['sourcesContent'] ?? [];
    $newSources = $newMap['sourcesContent'] ?? [];
    unset($oldMap['sourcesContent'], $newMap['sourcesContent']);
    if ($oldMap !== $newMap or count($oldSources) !== count($newSources)) {
        return 'thay đổi ngoài sourcesContent';
    }
    foreach ($oldSources as $i => $source) {
        $error = verifyText((string) $source, (string) $newSources[$i]);
        if ($error !== null) {
            return 'sourcesContent[' . $i . ']: ' . $error;
        }
    }

    return null;
}

[$files, $ignored] = listFiles();

$changed = [];
$legacyFiles = [];
$failed = [];
$nonstandard = [];
$byExt = [];
$totalBlocks = 0;

foreach ($files as $path) {
    $realPath = NV_ROOTDIR . '/' . $path;
    if (!is_file($realPath)) {
        continue;
    }
    $old = file_get_contents($realPath);
    if ($old === false or stripos($old, 'VINADES') === false) {
        continue;
    }

    $json = str_ends_with($path, '.map');
    $stat = [];
    [$afterLegacy, $new] = processText($old, $json, $year, $stat);

    if ($stat['nonstandard'] > 0) {
        $nonstandard[] = $path;
    }
    if ($new === $old) {
        continue;
    }

    $error = $json ? verifyMap($old, $new) : verifyText($afterLegacy, $new);
    if ($error !== null) {
        $failed[] = $path . ': ' . $error;
        continue;
    }

    $changed[] = $path;
    $stat['legacy'] > 0 && $legacyFiles[] = $path;
    $ext = $json ? 'map' : strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $byExt[$ext] = ($byExt[$ext] ?? 0) + 1;
    $totalBlocks += $stat['blocks'] + $stat['legacy'];

    if ($mode === 'apply') {
        file_put_contents($realPath, $new, LOCK_EX);
    }
}

ksort($byExt);
$titles = [
    'apply' => 'Đã cập nhật',
    'dry-run' => 'Sẽ cập nhật (chạy thử)',
    'check' => 'Banner chưa chuẩn'
];
echo "Năm: {$year} | Phiên bản: " . BANNER_VERSION . "\n";
echo $titles[$mode] . ': ' . count($changed) . ' file, ' . $totalBlocks . " khối banner\n";
foreach ($byExt as $ext => $count) {
    echo "  .{$ext}: {$count}\n";
}
if (isset($options['list']) or $mode === 'check') {
    foreach ($changed as $path) {
        echo "  - {$path}\n";
    }
}
if (!empty($legacyFiles)) {
    echo "\nThay banner NukeViet 4 bằng banner chuẩn (cần xem lại): " . count($legacyFiles) . "\n  - " . implode("\n  - ", $legacyFiles) . "\n";
}
if (!empty($failed)) {
    echo "\nKhông qua kiểm chứng, không ghi: " . count($failed) . "\n  - " . implode("\n  - ", $failed) . "\n";
}
if (!empty($nonstandard)) {
    echo "\nCó khối VINADES + @copyright không đúng mẫu, cần xem tay: " . count($nonstandard) . "\n  - " . implode("\n  - ", $nonstandard) . "\n";
}
if (!empty($ignored)) {
    echo "\nBỏ qua file assume-unchanged/skip-worktree: " . count($ignored) . "\n  - " . implode("\n  - ", $ignored) . "\n";
}

if (!empty($failed) or ($mode === 'check' and !empty($changed))) {
    exit(1);
}
exit(0);
