<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Manual\Tools;

if (PHP_SAPI !== 'cli') {
    exit('CLI only');
}

/**
 * Thư mục gốc repo, dùng dấu /
 */
function repoRoot(): string
{
    return str_replace('\\', '/', dirname(__DIR__, 3));
}

/**
 * Thư mục docs/manual, dùng dấu /
 */
function manualRoot(): string
{
    return str_replace('\\', '/', dirname(__DIR__));
}

/**
 * Link tới dòng code trên GitHub
 *
 * @param string $path đường dẫn tính từ gốc repo, VD src/includes/functions.php
 * @param int    $line
 */
function githubLink(string $path, int $line): string
{
    $branch = getenv('DOCS_BRANCH') ?: 'nukeviet5.0';

    return 'https://github.com/nukeviet/nukeviet/blob/' . $branch . '/' . $path . '#L' . $line;
}

/**
 * In cảnh báo, chạy trong GitHub Actions thì in theo định dạng annotation
 *
 * @param string $message
 * @param string $file đường dẫn tính từ gốc repo
 * @param int    $line
 */
function warn(string $message, string $file = '', int $line = 0): void
{
    if (getenv('GITHUB_ACTIONS') === 'true') {
        $loc = $file === '' ? '' : ' file=' . $file . ($line > 0 ? ',line=' . $line : '');
        fwrite(STDOUT, '::warning' . $loc . '::' . $message . PHP_EOL);
    } else {
        $loc = $file === '' ? '' : $file . ($line > 0 ? ':' . $line : '') . ': ';
        fwrite(STDERR, '[cảnh báo] ' . $loc . $message . PHP_EOL);
    }
}

/**
 * Ghi file, tạo thư mục nếu chưa có
 */
function writeFile(string $path, string $contents): void
{
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $contents);
}

/**
 * Nội dung text của một token
 *
 * @param array|string $token
 */
function tokenText($token): string
{
    return is_array($token) ? $token[1] : $token;
}

/**
 * Gộp danh sách token thành chuỗi một dòng, bỏ comment
 *
 * @param array $tokens
 */
function tokensToString(array $tokens): string
{
    $str = '';
    foreach ($tokens as $token) {
        if (is_array($token) and in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
            continue;
        }
        $str .= tokenText($token);
    }
    $str = preg_replace('/\s+/', ' ', $str);
    $str = preg_replace('/([\(\[])\s+/', '$1', $str);
    $str = preg_replace('/\s+([\)\]])/', '$1', $str);
    $str = preg_replace('/,\s*([\)\]])/', '$1', $str);

    return trim($str);
}

/**
 * Token mở ngoặc làm tăng độ sâu
 *
 * @param array|string $token
 */
function isOpenBracket($token): bool
{
    if (is_array($token)) {
        return in_array($token[0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true);
    }

    return in_array($token, ['(', '[', '{'], true);
}

/**
 * @param array|string $token
 */
function isCloseBracket($token): bool
{
    return !is_array($token) and in_array($token, [')', ']', '}'], true);
}

/**
 * Tách các phần tử ngăn cách bởi dấu phẩy ở cấp ngoài cùng, bắt đầu sau token mở ngoặc tại $openIndex
 *
 * @param array $tokens
 * @param int   $openIndex vị trí token mở ngoặc
 * @return array [danh sách phần tử (mỗi phần tử là mảng token), vị trí token đóng ngoặc]
 */
function splitBracket(array $tokens, int $openIndex): array
{
    $items = [];
    $current = [];
    $depth = 0;
    $count = count($tokens);
    for ($i = $openIndex; $i < $count; ++$i) {
        $token = $tokens[$i];
        if (isOpenBracket($token)) {
            ++$depth;
            if ($depth === 1) {
                continue;
            }
        } elseif (isCloseBracket($token)) {
            --$depth;
            if ($depth === 0) {
                if (tokensToString($current) !== '') {
                    $items[] = $current;
                }

                return [$items, $i];
            }
        } elseif ($token === ',' and $depth === 1) {
            $items[] = $current;
            $current = [];
            continue;
        }
        $current[] = $token;
    }

    return [$items, $count - 1];
}

/**
 * Vị trí token có nghĩa kế tiếp (bỏ khoảng trắng, comment)
 *
 * @param array $tokens
 * @param int   $index
 * @param int   $step 1 hoặc -1
 */
function nextMeaningful(array $tokens, int $index, int $step = 1): int
{
    for ($i = $index + $step; $i >= 0 and $i < count($tokens); $i += $step) {
        $token = $tokens[$i];
        if (is_array($token) and in_array($token[0], [T_WHITESPACE, T_COMMENT], true)) {
            continue;
        }

        return $i;
    }

    return -1;
}

/**
 * Bỏ dấu nháy nếu là chuỗi hằng
 */
function unquote(string $str): string
{
    if (preg_match('/^([\'"])(.*)\1$/s', $str, $m)) {
        return $m[2];
    }

    return $str;
}

/**
 * Phân tích PHPDoc thành mô tả và các tag
 *
 * @param string $doc
 * @return array ['description' => string, 'tags' => [['name' => ..., 'value' => ...], ...]]
 */
function parseDocBlock(string $doc): array
{
    $doc = preg_replace('/^\s*\/\*\*/', '', $doc);
    $doc = preg_replace('/\*\/\s*$/', '', $doc);
    $lines = preg_split('/\R/', $doc);

    $description = [];
    $tags = [];
    $current = -1;
    foreach ($lines as $line) {
        $line = preg_replace('/^\s*\* ?/', '', $line);
        if (preg_match('/^@([a-zA-Z\-]+)\s*(.*)$/', trim($line), $m)) {
            $tags[] = ['name' => strtolower($m[1]), 'value' => trim($m[2])];
            $current = count($tags) - 1;
        } elseif ($current >= 0) {
            if (trim($line) !== '') {
                $tags[$current]['value'] .= "\n" . trim($line);
            }
        } else {
            $description[] = rtrim($line);
        }
    }

    return [
        'description' => trim(implode("\n", $description)),
        'tags' => $tags
    ];
}

/**
 * Tách giá trị tag @param thành kiểu, tên biến, mô tả
 *
 * @param string $value
 * @return array|null ['type' => ..., 'name' => ..., 'desc' => ...]
 */
function parseParamTag(string $value): ?array
{
    if (preg_match('/^(?:(\S+)\s+)?&?(?:\.\.\.)?\$([a-zA-Z0-9_]+)\s*(.*)$/s', $value, $m)) {
        $type = $m[1];
        if (str_starts_with($type, '$')) {
            $type = '';
        }

        return ['type' => $type, 'name' => $m[2], 'desc' => cleanTagDesc($m[3])];
    }

    return null;
}

/**
 * Bỏ ký tự nối ở đầu mô tả của tag, VD "=> Module khởi chạy"
 */
function cleanTagDesc(string $desc): string
{
    return trim(preg_replace('/^(=>|:|-)\s*/', '', preg_replace('/\s+/', ' ', trim($desc))));
}

/**
 * Escape văn bản Markdown để Vue của VitePress không hiểu nhầm là thẻ HTML,
 * giữ nguyên phần nằm trong code block và inline code
 */
function mdText(string $text): string
{
    $parts = preg_split('/(```.*?```|`[^`\n]*`)/s', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    $out = '';
    foreach ($parts as $i => $part) {
        if ($i % 2 === 1) {
            $out .= $part;
        } else {
            $out .= str_replace(['<', '>'], ['&lt;', '&gt;'], $part);
        }
    }

    return $out;
}

/**
 * Inline code Markdown, tự chọn số dấu ` phù hợp
 */
function mdCode(string $code): string
{
    $fence = str_contains($code, '`') ? '``' : '`';
    $pad = str_contains($code, '`') ? ' ' : '';

    return $fence . $pad . $code . $pad . $fence;
}

/**
 * Phần đầu chung cho file Markdown sinh tự động
 *
 * @param string $tool tên công cụ sinh file
 */
function generatedHeader(string $tool): string
{
    return "---\neditLink: false\nlastUpdated: false\n---\n\n<!-- generated: docs/manual/tools/" . $tool . ", không sửa tay -->\n\n";
}
