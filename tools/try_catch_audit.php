<?php

/**
 * NukeViet Code Standards Audit (Try-Catch, JSON, Unserialize)
 * Mục tiêu: Tìm try-catch chưa chuẩn, json_encode thiếu NV_JSON_ENCODE, và unserialize thiếu NV_UNSERIALIZE_SAFE.
 */

$searchDir = realpath(__DIR__ . '/../src');
$outputMd = __DIR__ . '/try_catch_audit_report.md';

if (!$searchDir || !is_dir($searchDir)) {
    die("Error: src directory not found.\n");
}

echo "Scanning $searchDir ...\n";

$results = [];
$totalFiles = 0;
$totalIssues = 0;

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($searchDir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $filePath = $file->getPathname();
        
        if (str_contains($filePath, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR)) {
            continue;
        }

        $totalFiles++;
        $content = file_get_contents($filePath);
        
        // 1. Audit Try-Catch
        if (preg_match_all('/catch\s*\(\s*[a-zA-Z0-9_\\\\| \t]+\s*\$([a-zA-Z0-9_]+)\s*\)\s*\{/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $matchIndex => $matchData) {
                $fullMatch = $matchData[0];
                $offset = $matchData[1];
                $errorVarName = $matches[1][$matchIndex][0];
                $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
                $bracketContent = getClosingCharContent($content, $offset + strlen($fullMatch) - 1, '{', '}');
                
                $hasCorrectTrigger = str_contains($bracketContent, "trigger_error(\$" . $errorVarName . ")");
                $hasOldTrigger = str_contains($bracketContent, "trigger_error(\$" . $errorVarName . "->getMessage())");
                
                if (!$hasCorrectTrigger) {
                    $totalIssues++;
                    $type = $hasOldTrigger ? "🔴 TRY (getMessage)" : "❌ TRY (Missing)";
                    $results[$filePath][] = [
                        'line' => $lineNum,
                        'var' => $errorVarName,
                        'type' => $type
                    ];
                }
            }
        }

        // 2. Audit json_encode (NukeViet Standards)
        if (preg_match_all('/json_encode\s*\(/', $content, $matches_json, PREG_OFFSET_CAPTURE)) {
            foreach ($matches_json[0] as $matchData) {
                $offset = $matchData[1];
                $parenStart = $offset + strlen($matchData[0]) - 1;
                $inside = getClosingCharContent($content, $parenStart, '(', ')');
                
                $args = parsePhpArguments($inside);
                $hasSecondArg = isset($args[1]);
                $secondArg = $hasSecondArg ? trim($args[1]) : '';
                
                // Loại bỏ comments nếu có
                $secondArgClean = preg_replace('/\/\*.*?\*\/|\/\/.*?\n/s', '', $secondArg);
                $secondArgClean = trim($secondArgClean);

                if ($secondArgClean !== 'NV_JSON_ENCODE' && $secondArgClean !== 'NV_JSON_ENCODE_SCRIPT') {
                    $totalIssues++;
                    $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
                    $type = $hasSecondArg ? "🟡 JSON ($secondArgClean)" : "🟡 JSON (Wait NV_JSON_ENCODE)";
                    $results[$filePath][] = [
                        'line' => $lineNum,
                        'var' => 'json_encode',
                        'type' => $type
                    ];
                }
            }
        }

        // 3. Audit unserialize (Security - PHP Object Injection)
        if (preg_match_all('/unserialize\s*\(/', $content, $matches_unserial, PREG_OFFSET_CAPTURE)) {
            foreach ($matches_unserial[0] as $matchData) {
                $offset = $matchData[1];
                $parenStart = $offset + strlen($matchData[0]) - 1;
                $inside = getClosingCharContent($content, $parenStart, '(', ')');
                
                $args = parsePhpArguments($inside);
                $hasSecondArg = isset($args[1]);
                $secondArg = $hasSecondArg ? trim($args[1]) : '';
                
                // Loại bỏ comments nếu có
                $secondArgClean = preg_replace('/\/\*.*?\*\/|\/\/.*?\n/s', '', $secondArg);
                $secondArgClean = trim($secondArgClean);

                if ($secondArgClean !== 'NV_UNSERIALIZE_SAFE') {
                    $totalIssues++;
                    $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
                    $type = $hasSecondArg ? "🟠 UNSERIAL ($secondArgClean)" : "❌ UNSERIAL (Missing NV_UNSERIAL_SAFE)";
                    $results[$filePath][] = [
                        'line' => $lineNum,
                        'var' => 'unserialize',
                        'type' => $type
                    ];
                }
            }
        }
    }
}

/**
 * Tìm nội dung giữa cặp ngoặc đóng/mở
 */
function getClosingCharContent($text, $startIndex, $openChar, $closeChar) {
    if (!isset($text[$startIndex]) || $text[$startIndex] !== $openChar) return "";
    $count = 1;
    $content = "";
    for ($i = $startIndex + 1; $i < strlen($text); $i++) {
        if ($text[$i] == $openChar) $count++;
        elseif ($text[$i] == $closeChar) $count--;
        if ($count == 0) break;
        $content .= $text[$i];
    }
    return $content;
}

/**
 * Tách các đối số của hàm PHP từ chuỗi nội dung trong ngoặc
 */
function parsePhpArguments($inside) {
    if (empty(trim($inside))) return [];
    $args = [];
    $current = "";
    $depth = 0;
    $inString = false;
    $stringChar = '';
    
    for ($i = 0; $i < strlen($inside); $i++) {
        $char = $inside[$i];
        if (!$inString) {
            if ($char == "'" || $char == '"') {
                $inString = true;
                $stringChar = $char;
            } elseif (in_array($char, ['(', '[', '{'])) {
                $depth++;
            } elseif (in_array($char, [')', ']', '}'])) {
                $depth--;
            } elseif ($char == ',' && $depth == 0) {
                $args[] = trim($current);
                $current = "";
                continue;
            }
        } else {
            if ($char == $stringChar) {
                $backslashes = 0;
                $k = $i - 1;
                while ($k >= 0 && $inside[$k] == "\\") {
                    $backslashes++;
                    $k--;
                }
                if ($backslashes % 2 == 0) {
                    $inString = false;
                }
            }
        }
        $current .= $char;
    }
    $args[] = trim($current);
    return $args;
}

// 2. Xuất báo cáo Markdown (Dành cho Clickable Links)
$totalFilesWithIssues = count($results);
$reportMd = "# 🛡️ CODE COMPLIANCE AUDIT REPORT\n\n";
$reportMd .= "- **Scanned Files:** $totalFiles\n";
$reportMd .= "- **Total Issues:** $totalIssues\n";
$reportMd .= "- **Files needing fix:** $totalFilesWithIssues\n\n";
$reportMd .= "> **HD:** Nhấn `Ctrl + Click` vào đường dẫn Link bên dưới để mở file tại đúng dòng.\n\n";
$reportMd .= "| File Path & Line | Variable | Status |\n";
$reportMd .= "| :--- | :--- | :--- |\n";

foreach ($results as $path => $issues) {
    // Lấy đường dẫn tương đối từ gốc dự án
    $relativePath = "src" . str_replace(realpath(__DIR__ . '/../src'), '', $path);
    $relativePath = str_replace('\\', '/', $relativePath);
    
    foreach ($issues as $issue) {
        // Link tương đối: ../src/path/to/file.php#L123
        $link = "../" . $relativePath . "#L" . $issue['line'];
        $reportMd .= sprintf("| [%s:%d](%s) | `$%s` | %s |\n", $relativePath, $issue['line'], $link, $issue['var'], $issue['type']);
    }
}

file_put_contents($outputMd, $reportMd);
echo "Audit completed. Report: tools/try_catch_audit_report.md\n";
