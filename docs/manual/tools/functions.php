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
 * Sinh trang tham chiếu hàm dùng chung từ PHPDoc
 *
 * Kết quả: docs/manual/dev/reference/functions/<nhóm>.md
 * Chạy: php docs/manual/tools/functions.php
 */

declare(strict_types=1);

namespace NukeViet\Manual\Tools;

require __DIR__ . '/lib.php';

/**
 * Các nhóm hàm, đường dẫn tính từ src/
 * Đường dẫn là thư mục thì quét các file .php trực tiếp trong thư mục đó
 */
$groups = [
    'system' => [
        'title' => 'Hàm dùng chung toàn hệ thống',
        'scope' => 'Dùng được ở mọi khu vực: ngoài site, quản trị, cronjob, API.',
        'paths' => ['includes/functions.php']
    ],
    'utf8' => [
        'title' => 'Hàm xử lý chuỗi UTF-8',
        'scope' => 'Dùng được ở mọi khu vực.',
        'paths' => ['includes/utf8']
    ],
    'filesystem' => [
        'title' => 'Hàm thao tác file, thư mục',
        'scope' => 'Dùng được ở mọi khu vực.',
        'paths' => ['includes/core/filesystem_functions.php']
    ],
    'admin' => [
        'title' => 'Hàm khu vực quản trị',
        'scope' => 'Chỉ có trong khu vực quản trị.',
        'paths' => ['includes/core/admin_functions.php']
    ],
    'site' => [
        'title' => 'Hàm ngoài site',
        'scope' => 'Chỉ có ngoài site.',
        'paths' => ['includes/core/user_functions.php']
    ],
    'theme' => [
        'title' => 'Hàm liên quan giao diện',
        'scope' => 'Dùng được ở mọi khu vực.',
        'paths' => ['includes/core/theme_functions.php']
    ]
];

/**
 * Danh sách file .php của một đường dẫn trong nhóm
 *
 * @param string $path tính từ src/
 * @return string[] đường dẫn tính từ gốc repo
 */
function resolvePaths(string $path): array
{
    $abs = repoRoot() . '/src/' . $path;
    if (is_file($abs)) {
        return ['src/' . $path];
    }
    if (!is_dir($abs)) {
        fwrite(STDERR, 'Không tìm thấy: src/' . $path . PHP_EOL);
        exit(1);
    }
    $files = [];
    foreach (scandir($abs) as $name) {
        if (str_ends_with($name, '.php') and is_file($abs . '/' . $name)) {
            $files[] = 'src/' . $path . '/' . $name;
        }
    }

    return $files;
}

/**
 * Tách tham số trong chữ ký hàm
 *
 * @param array $tokens token của một tham số
 * @return array ['name' => ..., 'type' => ..., 'default' => ..., 'byref' => bool, 'variadic' => bool]
 */
function parseSignatureParam(array $tokens): array
{
    $param = ['name' => '', 'type' => '', 'default' => null, 'byref' => false, 'variadic' => false];
    $typeTokens = [];
    $defaultTokens = null;
    foreach ($tokens as $token) {
        if ($defaultTokens !== null) {
            $defaultTokens[] = $token;
            continue;
        }
        if (is_array($token) and $token[0] === T_VARIABLE) {
            $param['name'] = substr($token[1], 1);
        } elseif ($token === '=') {
            $defaultTokens = [];
        } elseif ($param['name'] === '') {
            if (tokenText($token) === '&') {
                $param['byref'] = true;
            } elseif (is_array($token) and $token[0] === T_ELLIPSIS) {
                $param['variadic'] = true;
            } else {
                $typeTokens[] = $token;
            }
        }
    }
    $param['type'] = tokensToString($typeTokens);
    if ($defaultTokens !== null) {
        $param['default'] = tokensToString($defaultTokens);
    }

    return $param;
}

/**
 * Lấy các hàm khai báo ở cấp ngoài cùng của file
 *
 * @param string $file đường dẫn tính từ gốc repo
 * @return array
 */
function extractFunctions(string $file): array
{
    $tokens = token_get_all(file_get_contents(repoRoot() . '/' . $file));
    $count = count($tokens);
    $functions = [];
    $depth = 0;

    for ($i = 0; $i < $count; ++$i) {
        $token = $tokens[$i];
        if (isOpenBracket($token)) {
            ++$depth;
            continue;
        }
        if (isCloseBracket($token)) {
            --$depth;
            continue;
        }
        if ($depth !== 0 or !is_array($token) or $token[0] !== T_FUNCTION) {
            continue;
        }

        // Bỏ qua closure: sau function là dấu (
        $nameIndex = nextMeaningful($tokens, $i);
        if (tokenText($tokens[$nameIndex]) === '&') {
            $nameIndex = nextMeaningful($tokens, $nameIndex);
        }
        if (!is_array($tokens[$nameIndex]) or $tokens[$nameIndex][0] !== T_STRING) {
            continue;
        }
        $name = $tokens[$nameIndex][1];
        $line = $token[2];

        // Hàm bắt đầu bằng _ là hàm nội bộ, không đưa vào tài liệu
        if (str_starts_with($name, '_')) {
            $closeIndex = splitBracket($tokens, nextMeaningful($tokens, $nameIndex))[1];
            $i = $closeIndex;
            continue;
        }

        // PHPDoc ngay trước từ khóa function
        $doc = '';
        $prev = nextMeaningful($tokens, $i, -1);
        if ($prev >= 0 and is_array($tokens[$prev]) and $tokens[$prev][0] === T_DOC_COMMENT) {
            $doc = $tokens[$prev][1];
        }

        // Tham số
        $openIndex = nextMeaningful($tokens, $nameIndex);
        [$paramTokens, $closeIndex] = splitBracket($tokens, $openIndex);
        $params = array_map(__NAMESPACE__ . '\parseSignatureParam', $paramTokens);

        // Kiểu trả về khai báo trong chữ ký
        $returnType = '';
        $j = $closeIndex + 1;
        $retTokens = [];
        while ($j < $count and $tokens[$j] !== '{' and $tokens[$j] !== ';') {
            $retTokens[] = $tokens[$j];
            ++$j;
        }
        $retStr = tokensToString($retTokens);
        if (str_starts_with($retStr, ':')) {
            $returnType = trim(substr($retStr, 1));
        }

        $signature = 'function ' . $name . '(' . implode(', ', array_map(__NAMESPACE__ . '\tokensToString', $paramTokens)) . ')'
            . ($returnType !== '' ? ': ' . $returnType : '');

        $functions[] = [
            'name' => $name,
            'file' => $file,
            'line' => $line,
            'signature' => $signature,
            'params' => $params,
            'returnType' => $returnType,
            'doc' => $doc === '' ? null : parseDocBlock($doc)
        ];

        // Nhảy qua thân hàm
        $i = $closeIndex;
    }

    return $functions;
}

/**
 * Sinh Markdown cho một hàm
 */
function renderFunction(array $fn): string
{
    $doc = $fn['doc'] ?? ['description' => '', 'tags' => []];

    // Dòng đầu PHPDoc thường lặp lại tên hàm, VD "nv_htmlspecialchars()"
    $description = preg_replace('/^\s*' . preg_quote($fn['name'], '/') . '\s*\([^\n]*\)\s*(\n|$)/i', '', $doc['description']);
    $description = trim($description);

    $docParams = [];
    $returnTag = null;
    $since = '';
    $deprecated = null;
    $sees = [];
    $throws = [];
    foreach ($doc['tags'] as $tag) {
        switch ($tag['name']) {
            case 'param':
                $p = parseParamTag($tag['value']);
                if ($p !== null) {
                    $docParams[$p['name']] = $p;
                }
                break;
            case 'return':
                $returnTag = $tag['value'];
                break;
            case 'since':
                $since = $tag['value'];
                break;
            case 'deprecated':
                $deprecated = $tag['value'];
                break;
            case 'see':
                $sees[] = $tag['value'];
                break;
            case 'throws':
                $throws[] = $tag['value'];
                break;
        }
    }

    // Đặt anchor đúng tên hàm, mặc định VitePress đổi _ thành -
    $md = '## ' . $fn['name'] . ' {#' . $fn['name'] . "}\n\n";
    $md .= "```php\n" . $fn['signature'] . "\n```\n\n";

    if ($deprecated !== null) {
        $md .= '**Không dùng nữa.** ' . mdText($deprecated) . "\n\n";
    }
    if ($description !== '') {
        $md .= mdText($description) . "\n\n";
    }

    if (!empty($fn['params'])) {
        $md .= "**Tham số**\n\n";
        foreach ($fn['params'] as $p) {
            $d = $docParams[$p['name']] ?? null;
            $type = $p['type'] !== '' ? $p['type'] : ($d['type'] ?? '');
            $line = '- ' . mdCode(($p['variadic'] ? '...' : '') . ($p['byref'] ? '&' : '') . '$' . $p['name']);
            if ($type !== '') {
                $line .= ' ' . mdCode($type);
            }
            if ($p['default'] !== null) {
                $line .= ', mặc định ' . mdCode($p['default']);
            }
            if ($d !== null and $d['desc'] !== '') {
                $line .= ': ' . mdText($d['desc']);
            }
            $md .= $line . "\n";
        }
        $md .= "\n";
    }

    $retType = $fn['returnType'];
    $retDesc = '';
    if ($returnTag !== null and preg_match('/^(\S+)\s*(.*)$/s', $returnTag, $m)) {
        if ($retType === '') {
            $retType = $m[1];
        }
        $retDesc = cleanTagDesc($m[2]);
    }
    if ($retType !== '') {
        $md .= '**Trả về:** ' . mdCode($retType) . ($retDesc !== '' ? ' ' . mdText($retDesc) : '') . "\n\n";
    }

    foreach ($throws as $t) {
        $md .= '**Ngoại lệ:** ' . mdText($t) . "\n\n";
    }
    if ($since !== '') {
        $md .= '**Từ phiên bản:** ' . mdText($since) . "\n\n";
    }
    foreach ($sees as $s) {
        $md .= '**Xem thêm:** ' . mdText($s) . "\n\n";
    }

    $md .= 'Khai báo tại [' . $fn['file'] . ':' . $fn['line'] . '](' . githubLink($fn['file'], $fn['line']) . ")\n\n";

    return $md;
}

$outDir = manualRoot() . '/dev/reference/functions';
$total = 0;

foreach ($groups as $slug => $group) {
    $functions = [];
    $files = [];
    foreach ($group['paths'] as $path) {
        foreach (resolvePaths($path) as $file) {
            $files[] = $file;
            foreach (extractFunctions($file) as $fn) {
                $functions[] = $fn;
            }
        }
    }
    usort($functions, fn ($a, $b) => strcasecmp($a['name'], $b['name']));

    $md = generatedHeader('functions.php');
    $md .= '# ' . $group['title'] . "\n\n";
    $md .= $group['scope'] . "\n\n";
    $md .= 'Nguồn: ' . implode(', ', array_map(fn ($f) => mdCode($f), $files)) . "\n\n";
    $md .= "::: v-pre\n\n";
    foreach ($functions as $fn) {
        $md .= renderFunction($fn);
    }
    $md .= ":::\n";

    writeFile($outDir . '/' . $slug . '.md', $md);
    $total += count($functions);
    echo 'dev/reference/functions/' . $slug . '.md: ' . count($functions) . ' hàm' . PHP_EOL;
}

echo 'Tổng: ' . $total . ' hàm' . PHP_EOL;
