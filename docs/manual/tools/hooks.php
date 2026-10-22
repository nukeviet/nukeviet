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
 * Sinh trang danh sách hook từ các lời gọi nv_apply_hook trong src/
 *
 * Mô tả hook viết trong khối comment ngay trên câu lệnh gọi nv_apply_hook:
 *
 *   /**
 *    * @hook ten_hook Mô tả hook xảy ra khi nào
 *    * @param array $user_info Mô tả phần tử thứ nhất của mảng $args
 *    * @return array Mô tả giá trị hàm xử lý cần trả về
 *    * @since 5.0.00
 *    *\/
 *
 * Kết quả: docs/manual/dev/hooks/list.md
 * Chạy: php docs/manual/tools/hooks.php
 */

declare(strict_types=1);

namespace NukeViet\Manual\Tools;

require __DIR__ . '/lib.php';

/**
 * Danh sách file .php trong src/, bỏ thư viện ngoài và dữ liệu
 *
 * @return string[] đường dẫn tính từ gốc repo
 */
function listSourceFiles(): array
{
    $root = repoRoot();
    $files = [];
    $it = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($root . '/src', \FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }
        $rel = substr(str_replace('\\', '/', $file->getPathname()), strlen($root) + 1);
        if (preg_match('#^src/(data|uploads)/#', $rel)) {
            continue;
        }
        if (str_contains($rel, '/vendor/') and !str_contains($rel, '/vendor/vinades/')) {
            continue;
        }
        $files[] = $rel;
    }
    sort($files);

    return $files;
}

/**
 * Xác định module nguồn từ tham số thứ nhất
 *
 * @param string $arg  tham số thứ nhất của nv_apply_hook
 * @param string $file
 * @return string chuỗi rỗng là hệ thống, * là module đang truy cập (không xác định được)
 */
function resolveModule(string $arg, string $file): string
{
    if ($arg === "''" or $arg === '""') {
        return '';
    }
    if (preg_match('/^([\'"])([a-zA-Z0-9_\-]+)\1$/', $arg, $m)) {
        return $m[2];
    }
    if ($arg === '$module_name' and preg_match('#^src/(?:themes/[^/]+/)?modules/([^/]+)/#', $file, $m)) {
        return $m[1];
    }

    return '*';
}

/**
 * Phần tử của mảng $args truyền vào hook
 *
 * @param array $tokens token của tham số $args
 * @return array|null danh sách ['key' => ..., 'expr' => ...], null nếu $args không phải mảng viết trực tiếp
 */
function parseArgsArray(array $tokens): ?array
{
    $first = nextMeaningful($tokens, -1);
    if ($first < 0) {
        return [];
    }
    $openIndex = null;
    if ($tokens[$first] === '[') {
        $openIndex = $first;
    } elseif (is_array($tokens[$first]) and $tokens[$first][0] === T_ARRAY) {
        $openIndex = nextMeaningful($tokens, $first);
    }
    if ($openIndex === null) {
        return null;
    }

    [$items] = splitBracket($tokens, $openIndex);
    $elements = [];
    $index = 0;
    foreach ($items as $item) {
        $depth = 0;
        $arrow = null;
        foreach ($item as $k => $token) {
            if (isOpenBracket($token)) {
                ++$depth;
            } elseif (isCloseBracket($token)) {
                --$depth;
            } elseif ($depth === 0 and is_array($token) and $token[0] === T_DOUBLE_ARROW) {
                $arrow = $k;
                break;
            }
        }
        if ($arrow === null) {
            $elements[] = ['key' => $index++, 'expr' => tokensToString($item)];
        } else {
            $elements[] = [
                'key' => unquote(tokensToString(array_slice($item, 0, $arrow))),
                'expr' => tokensToString(array_slice($item, $arrow + 1))
            ];
        }
    }

    return $elements;
}

/**
 * Tìm các lời gọi nv_apply_hook trong file
 *
 * @param string $file đường dẫn tính từ gốc repo
 * @return array
 */
function extractHooks(string $file): array
{
    $source = file_get_contents(repoRoot() . '/' . $file);
    if (!str_contains($source, 'nv_apply_hook')) {
        return [];
    }
    $tokens = token_get_all($source);
    $count = count($tokens);
    $hooks = [];

    for ($i = 0; $i < $count; ++$i) {
        $token = $tokens[$i];
        if (!is_array($token) or !in_array($token[0], [T_STRING, T_NAME_FULLY_QUALIFIED], true) or ltrim($token[1], '\\') !== 'nv_apply_hook') {
            continue;
        }
        $prev = nextMeaningful($tokens, $i, -1);
        if ($prev >= 0 and is_array($tokens[$prev]) and in_array($tokens[$prev][0], [T_FUNCTION, T_OBJECT_OPERATOR, T_DOUBLE_COLON, T_NULLSAFE_OBJECT_OPERATOR], true)) {
            continue;
        }
        $openIndex = nextMeaningful($tokens, $i);
        if ($openIndex < 0 or $tokens[$openIndex] !== '(') {
            continue;
        }

        $line = $token[2];
        [$args, $closeIndex] = splitBracket($tokens, $openIndex);
        // Kết quả hook được dùng trực tiếp làm giá trị của câu lệnh, không nối tiếp biểu thức khác
        $afterCall = nextMeaningful($tokens, $closeIndex);
        $isWholeValue = $afterCall >= 0 and $tokens[$afterCall] === ';';
        $argStr = array_map(__NAMESPACE__ . '\tokensToString', $args);

        if (count($args) < 2 or !preg_match('/^([\'"])([a-zA-Z0-9_]+)\1$/', $argStr[1], $m)) {
            warn('Không đọc được tên hook (tham số thứ hai phải là chuỗi hằng)', $file, $line);
            continue;
        }
        $tag = $m[2];

        // Tìm đầu câu lệnh, khối comment @hook và biến nhận kết quả
        $doc = null;
        $assignTo = '';
        $isReturn = false;
        $before = nextMeaningful($tokens, $i, -1);
        if ($isWholeValue and $before >= 0 and $tokens[$before] === '=') {
            $assignTokens = [];
            for ($j = $before - 1; $j >= 0; --$j) {
                $t = $tokens[$j];
                if (in_array($t, [';', '{', '}'], true) or (is_array($t) and in_array($t[0], [T_OPEN_TAG, T_DOC_COMMENT], true))) {
                    break;
                }
                array_unshift($assignTokens, $t);
            }
            $assignTo = tokensToString($assignTokens);
        } elseif ($before >= 0 and is_array($tokens[$before]) and $tokens[$before][0] === T_RETURN) {
            $isReturn = true;
        }
        for ($j = $i - 1; $j >= 0; --$j) {
            $t = $tokens[$j];
            if (in_array($t, [';', '{', '}'], true) or (is_array($t) and $t[0] === T_OPEN_TAG)) {
                break;
            }
            if (is_array($t) and $t[0] === T_DOC_COMMENT) {
                $doc = parseDocBlock($t[1]);
                break;
            }
        }

        $hooks[] = [
            'tag' => $tag,
            'module' => resolveModule($argStr[0], $file),
            'moduleArg' => $argStr[0],
            'file' => $file,
            'line' => $line,
            'argsExpr' => $argStr[2] ?? '',
            'args' => isset($args[2]) ? parseArgsArray($args[2]) : [],
            'default' => $argStr[3] ?? null,
            'returnType' => isset($argStr[4]) ? (int) $argStr[4] : 0,
            'assignTo' => $assignTo,
            'isReturn' => $isReturn,
            'doc' => $doc
        ];
    }

    return $hooks;
}

/**
 * Tách khối comment @hook
 *
 * @param array $doc kết quả parseDocBlock
 * @return array ['tag' => ..., 'description' => ..., 'params' => [...], 'return' => ..., 'since' => ...]
 */
function parseHookDoc(array $doc): array
{
    $info = ['tag' => '', 'description' => '', 'params' => [], 'return' => null, 'since' => ''];
    $desc = [];
    foreach ($doc['tags'] as $tag) {
        switch ($tag['name']) {
            case 'hook':
                if (preg_match('/^([a-zA-Z0-9_]+)\s*(.*)$/s', $tag['value'], $m)) {
                    $info['tag'] = $m[1];
                    if (trim($m[2]) !== '') {
                        $desc[] = trim($m[2]);
                    }
                }
                break;
            case 'param':
                $p = parseParamTag($tag['value']);
                if ($p !== null) {
                    $info['params'][] = $p;
                }
                break;
            case 'return':
                if (preg_match('/^(\S+)\s*(.*)$/s', $tag['value'], $m)) {
                    $info['return'] = ['type' => $m[1], 'desc' => cleanTagDesc($m[2])];
                }
                break;
            case 'since':
                $info['since'] = $tag['value'];
                break;
        }
    }
    if ($doc['description'] !== '') {
        $desc[] = $doc['description'];
    }
    $info['description'] = implode("\n\n", $desc);

    return $info;
}

/**
 * Tên biến dùng trong ví dụ cho một phần tử $args
 */
function exampleVar(array $element, int $position): string
{
    // Biến đơn hoặc mảng các biến đơn, VD $user_info, [$contents, $headers]
    $var = '\$[a-zA-Z_][a-zA-Z0-9_]*';
    if (preg_match('/^' . $var . '$/', $element['expr']) or preg_match('/^\[' . $var . '(, ' . $var . ')*\]$/', $element['expr'])) {
        return $element['expr'];
    }
    if (is_string($element['key']) and preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $element['key'])) {
        return '$' . $element['key'];
    }

    return '$arg' . $position;
}

/**
 * Sinh Markdown cho một hook
 *
 * @param array $sites các vị trí gọi cùng một hook
 */
function renderHook(array $sites, string $anchor): string
{
    // Ưu tiên vị trí có mô tả
    $main = $sites[0];
    foreach ($sites as $site) {
        if ($site['doc'] !== null) {
            $main = $site;
            break;
        }
    }
    $info = $main['doc'] === null ? null : parseHookDoc($main['doc']);

    $md = '### ' . $main['tag'] . ' {#' . $anchor . "}\n\n";
    if ($info !== null and $info['description'] !== '') {
        // Mỗi dòng mô tả là một đoạn
        $md .= mdText(preg_replace('/\n+/', "\n\n", $info['description'])) . "\n\n";
    } else {
        $md .= "_Chưa có mô tả._\n\n";
    }

    $source = match ($main['module']) {
        '' => 'Hệ thống',
        '*' => 'Module đang truy cập (' . mdCode($main['moduleArg']) . ')',
        default => 'Module ' . mdCode($main['module'])
    };
    $md .= '- Nguồn: ' . $source . "\n";
    foreach ($sites as $site) {
        $md .= '- Vị trí: [' . $site['file'] . ':' . $site['line'] . '](' . githubLink($site['file'], $site['line']) . ")\n";
    }
    if ($info !== null and $info['since'] !== '') {
        $md .= '- Từ phiên bản: ' . mdText($info['since']) . "\n";
    }
    $md .= "\n";

    // Dữ liệu đầu vào
    $md .= "**Dữ liệu đầu vào** (" . mdCode('$vars') . ")\n\n";
    if ($main['args'] === null) {
        // $args là biến, các @param mô tả theo tên key của mảng
        $md .= '- ' . mdCode('$vars') . ' là ' . mdCode($main['argsExpr']) . "\n";
        foreach ($info['params'] ?? [] as $p) {
            $md .= '- ' . mdCode("\$vars['" . $p['name'] . "']") . ($p['type'] !== '' ? ' ' . mdCode($p['type']) : '')
                . ($p['desc'] !== '' ? ': ' . mdText($p['desc']) : '') . "\n";
        }
        $md .= "\n";
    } elseif (empty($main['args'])) {
        $md .= "Không có.\n\n";
    } else {
        foreach ($main['args'] as $k => $element) {
            $key = is_int($element['key']) ? (string) $element['key'] : "'" . $element['key'] . "'";
            $line = '- ' . mdCode('$vars[' . $key . ']') . ' là ' . mdCode($element['expr']);
            $p = $info['params'][$k] ?? null;
            if ($p !== null) {
                if ($p['type'] !== '') {
                    $line .= ' ' . mdCode($p['type']);
                }
                if ($p['desc'] !== '') {
                    $line .= ': ' . mdText($p['desc']);
                }
            }
            $md .= $line . "\n";
        }
        $md .= "\n";
    }

    // Giá trị trả về
    $md .= "**Giá trị trả về**\n\n";
    $usesReturn = $main['assignTo'] !== '' || $main['isReturn'] || $main['default'] !== null;
    if ($info !== null and $info['return'] !== null) {
        $md .= '- Hàm xử lý trả về ' . mdCode($info['return']['type']) . ($info['return']['desc'] !== '' ? ': ' . mdText($info['return']['desc']) : '') . "\n";
    } elseif (!$usesReturn) {
        $md .= "- Không dùng giá trị trả về\n";
    }
    if ($main['assignTo'] !== '') {
        $md .= '- Gán vào ' . mdCode($main['assignTo']) . "\n";
    }
    if ($main['default'] !== null) {
        $md .= '- Mặc định khi không có hàm xử lý hoặc hàm xử lý trả về null: ' . mdCode($main['default']) . "\n";
    }
    if ($main['returnType'] === 1) {
        $md .= '- Kết quả các hàm xử lý được gộp bằng ' . mdCode('array_merge') . "\n";
    } elseif ($main['returnType'] === 2) {
        $md .= '- Kết quả các hàm xử lý được gộp bằng ' . mdCode('array_merge_recursive') . "\n";
    }
    $md .= "\n";

    // Ví dụ viết plugin
    $md .= "**Ví dụ**\n\n```php\n";
    $md .= "nv_add_hook(\$module_name, '" . $main['tag'] . "', \$priority, function (\$vars, \$from_data, \$receive_data) {\n";
    if (!empty($main['args'])) {
        foreach ($main['args'] as $k => $element) {
            $key = is_int($element['key']) ? (string) $element['key'] : "'" . $element['key'] . "'";
            $md .= '    ' . exampleVar($element, $k) . ' = $vars[' . $key . "];\n";
        }
        $md .= "\n";
    }
    $md .= "    // Xử lý tại đây\n";
    if ($main['assignTo'] !== '') {
        $ret = preg_replace('/^list\((.*)\)$/s', '[$1]', $main['assignTo']);
        $md .= "\n    return " . $ret . ";\n";
    }
    $md .= "});\n```\n\n";

    return $md;
}

/**
 * Kiểm tra khối comment, in cảnh báo
 */
function checkHook(array $hook): void
{
    if ($hook['doc'] === null) {
        warn('Hook ' . $hook['tag'] . ' chưa có khối comment @hook', $hook['file'], $hook['line']);

        return;
    }
    $info = parseHookDoc($hook['doc']);
    if ($info['tag'] === '') {
        warn('Hook ' . $hook['tag'] . ' có comment nhưng thiếu tag @hook', $hook['file'], $hook['line']);
    } elseif ($info['tag'] !== $hook['tag']) {
        warn('@hook ' . $info['tag'] . ' không khớp tên hook ' . $hook['tag'], $hook['file'], $hook['line']);
    }
    if (is_array($hook['args']) and count($info['params']) !== count($hook['args'])) {
        warn('Hook ' . $hook['tag'] . ' có ' . count($hook['args']) . ' phần tử $args nhưng comment có ' . count($info['params']) . ' @param', $hook['file'], $hook['line']);
    }
}

// Gom theo module nguồn rồi theo tên hook
$groups = [];
$total = 0;
foreach (listSourceFiles() as $file) {
    foreach (extractHooks($file) as $hook) {
        checkHook($hook);
        $groups[$hook['module']][$hook['tag']][] = $hook;
        ++$total;
    }
}

uksort($groups, function ($a, $b) {
    // Hệ thống trước, module đang truy cập cuối cùng
    $order = fn ($m) => $m === '' ? '0' : ($m === '*' ? '2' : '1' . $m);

    return strcmp($order($a), $order($b));
});

$md = generatedHeader('hooks.php');
$md .= "# Danh sách hook\n\n";
$md .= 'Danh sách được sinh từ các lời gọi ' . mdCode('nv_apply_hook') . ' trong mã nguồn. ';
$md .= 'Hàm xử lý nhận ' . mdCode('$vars') . ' là mảng dữ liệu đầu vào, ' . mdCode('$from_data') . ' là thông tin module nguồn, ';
$md .= mdCode('$receive_data') . " là thông tin module nhận.\n\n";
$md .= "::: v-pre\n\n";

// Anchor là tên hook, trùng tên giữa các module thì thêm tiền tố module
$tagCount = [];
foreach ($groups as $tags) {
    foreach (array_keys($tags) as $tag) {
        $tagCount[$tag] = ($tagCount[$tag] ?? 0) + 1;
    }
}

foreach ($groups as $module => $tags) {
    ksort($tags);
    $md .= '## ' . match ($module) {
        '' => 'Hook hệ thống',
        '*' => 'Hook của module đang truy cập',
        default => 'Module ' . $module
    } . "\n\n";
    foreach ($tags as $tag => $sites) {
        $prefix = match ($module) {
            '' => 'system',
            '*' => 'current',
            default => $module
        };
        $md .= renderHook($sites, $tagCount[$tag] > 1 ? $prefix . '-' . $tag : $tag);
    }
}
$md .= ":::\n";

writeFile(manualRoot() . '/dev/hooks/list.md', $md);
echo 'dev/hooks/list.md: ' . $total . ' lời gọi, ' . array_sum(array_map('count', $groups)) . ' hook' . PHP_EOL;
