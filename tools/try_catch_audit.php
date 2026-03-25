<?php

/**
 * NukeViet Try-Catch Error Handling Audit (Editor Friendly)
 * Mục tiêu: Tạo báo cáo Markdown với đường dẫn tương đối dễ Click.
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
        
        if (preg_match_all('/catch\s*\(\s*[a-zA-Z0-9_\\\\| \t]+\s*\$([a-zA-Z0-9_]+)\s*\)\s*\{/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $matchIndex => $matchData) {
                $fullMatch = $matchData[0];
                $offset = $matchData[1];
                $errorVarName = $matches[1][$matchIndex][0];
                $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;
                $bracketContent = getClosingBracketContent($content, $offset + strlen($fullMatch) - 1);
                
                $hasCorrectTrigger = str_contains($bracketContent, "trigger_error(\$" . $errorVarName . ")");
                $hasOldTrigger = str_contains($bracketContent, "trigger_error(\$" . $errorVarName . "->getMessage())");
                
                if (!$hasCorrectTrigger) {
                    $totalIssues++;
                    $type = $hasOldTrigger ? "🔴 OLD (getMessage)" : "❌ MISSING";
                    $results[$filePath][] = [
                        'line' => $lineNum,
                        'var' => $errorVarName,
                        'type' => $type
                    ];
                }
            }
        }
    }
}

function getClosingBracketContent($text, $startIndex) {
    $count = 1;
    $content = "";
    for ($i = $startIndex + 1; $i < strlen($text); $i++) {
        if ($text[$i] == '{') $count++;
        if ($text[$i] == '}') $count--;
        if ($count == 0) break;
        $content .= $text[$i];
    }
    return $content;
}

// 2. Xuất báo cáo Markdown (Dành cho Clickable Links)
$totalFilesWithIssues = count($results);
$reportMd = "# 🛡️ TRY-CATCH AUDIT REPORT\n\n";
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
