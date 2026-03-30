<?php

/**
 * Script to scan and refactor get_title calls in NukeViet 5.0
 * 1. Scans 'src' directory.
 * 2. Identifies $nv_Request->get_title calls.
 * 3. If exactly 4 parameters and the 4th is 0 or 1, removes the 4th parameter.
 * 4. Reports findings to get_title_results.md
 */

$searchDir = realpath(__DIR__ . '/../src');
$rootDir = realpath(__DIR__ . '/..');

if (!$searchDir) {
    die("Directory 'src' not found.\n");
}

$outputMd = __DIR__ . '/get_title_results.md';
$md = fopen($outputMd, 'w');

function logMd($msg, $md)
{
    echo $msg . "\n";
    fwrite($md, $msg . "\n");
}

logMd("# NukeViet 5.0 - get_title refactor report", $md);
logMd("Scanning $searchDir...", $md);

$stats = [
    'scanned' => 0,
    'changed' => 0,
    'reported_4' => 0,
    'reported_more' => 0
];

$results = [
    'fixed' => [],
    'manual_4' => [],
    'more_than_4' => []
];

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($searchDir));
$countFiles = 0;

foreach ($files as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') {
        continue;
    }
    $countFiles++;

    $filePath = $file->getRealPath();
    $content = file_get_contents($filePath);

    if (strpos($content, '->get_title') === false) {
        continue;
    }

    $tokens = token_get_all($content);
    $tokenCount = count($tokens);
    $fileChanged = false;

    // Use a backward loop or carefully track offsets if we were to use tokens for replacement
    // But for simplicity and to match user's previous approach, we use tokens to FIND and regex/string search to FIX if possible.
    // However, since we want to be precise, let's use the token-based parameter detection.

    for ($i = 0; $i < $tokenCount; $i++) {
        if (is_array($tokens[$i]) && $tokens[$i][0] === T_OBJECT_OPERATOR) {
            if (isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][1] === 'get_title') {
                
                // Find start of parameters
                $j = $i + 2;
                while ($j < $tokenCount && $tokens[$j] !== '(') {
                    $j++;
                }

                if ($j < $tokenCount && $tokens[$j] === '(') {
                    $startLine = $tokens[$i][2];
                    $params = [];
                    $currentParamTokens = [];
                    $bracketLevel = 0;

                    $k = $j + 1;
                    while ($k < $tokenCount) {
                        $token = $tokens[$k];

                        if ($token === '(') {
                            $bracketLevel++;
                            $currentParamTokens[] = $token;
                        } elseif ($token === ')') {
                            if ($bracketLevel === 0) {
                                if (!empty($currentParamTokens)) {
                                    $params[] = $currentParamTokens;
                                }
                                break;
                            }
                            $bracketLevel--;
                            $currentParamTokens[] = $token;
                        } elseif ($token === ',' && $bracketLevel === 0) {
                            $params[] = $currentParamTokens;
                            $currentParamTokens = [];
                        } else {
                            $currentParamTokens[] = $token;
                        }
                        $k++;
                    }

                    $paramCount = count($params);
                    $relativeFile = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $filePath);
                    $relativeFileUnix = str_replace('\\', '/', $relativeFile);
                    $fileUrl = '../' . $relativeFileUnix . '#L' . $startLine;

                    if ($paramCount === 4) {
                        // Check 4th param
                        $lastParam = $params[3];
                        $lastParamStr = '';
                        foreach ($lastParam as $p) {
                            if (is_array($p)) {
                                if ($p[0] !== T_WHITESPACE && $p[0] !== T_COMMENT && $p[0] !== T_DOC_COMMENT) {
                                    $lastParamStr .= $p[1];
                                }
                            } else {
                                $lastParamStr .= $p;
                            }
                        }
                        $lastParamStr = trim($lastParamStr);

                        if ($lastParamStr === '0' || $lastParamStr === '1') {
                            // Can be fixed automatically
                            // We need to find the exact text in the file to replace.
                            // To be safe, we'll use a regex on the specific line if possible, 
                            // but get_title calls can span multiple lines.
                            
                            $results['fixed'][] = [
                                'file' => $relativeFile,
                                'line' => $startLine,
                                'link' => "[{$relativeFile}:{$startLine}]({$fileUrl})",
                                'old_val' => $lastParamStr
                            ];
                        } else {
                            $results['manual_4'][] = [
                                'link' => "[{$relativeFile}:{$startLine}]({$fileUrl}) - 4th param: `{$lastParamStr}`"
                            ];
                        }
                    } elseif ($paramCount > 4) {
                        $results['more_than_4'][] = [
                            'link' => "[{$relativeFile}:{$startLine}]({$fileUrl}) - Params: **{$paramCount}**"
                        ];
                    }
                }
            }
        }
    }
}

// Perform Auto-Refactor for those identified as 'fixed'
// We group by file to minimize file operations
$filesToFix = [];
foreach ($results['fixed'] as $item) {
    if (!isset($filesToFix[$item['file']])) {
        $filesToFix[$item['file']] = [];
    }
    $filesToFix[$item['file']][] = $item;
}

// Regex for replacement: handles the 4th argument if it's 0 or 1.
// We apply it generally to the file, but we already know which files have it.
$pattern = '/(\$nv_Request->get_title\s*\(\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*,\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*,\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*),\s*([01])\s*\)/';

logMd("\n## AUTO-REFACTORED CALLS (4th param was 0 or 1)", $md);
foreach ($filesToFix as $relPath => $items) {
    $fullPath = $rootDir . DIRECTORY_SEPARATOR . $relPath;
    $content = file_get_contents($fullPath);
    $newContent = preg_replace($pattern, '$1)', $content, -1, $count);
    
    if ($count > 0) {
        file_put_contents($fullPath, $newContent);
        $stats['changed']++;
        foreach ($items as $item) {
            logMd("- Fixed: {$item['link']} (Removed `{$item['old_val']}`)", $md);
        }
    } else {
        // Fallback for cases where regex failed due to complexity, but token scan found it
        foreach ($items as $item) {
             logMd("- FAILED TO AUTO-FIX: {$item['link']}", $md);
        }
    }
}

logMd("\n## MANUAL CHECK REQUIRED (4 parameters, but 4th is not 0 or 1)", $md);
foreach ($results['manual_4'] as $row) {
    logMd("- " . $row['link'], $md);
}

logMd("\n## MORE THAN 4 PARAMETERS", $md);
foreach ($results['more_than_4'] as $row) {
    logMd("- " . $row['link'], $md);
}

logMd("\n## SUMMARY", $md);
logMd("- Total files scanned: $countFiles", $md);
logMd("- Total files modified: " . $stats['changed'], $md);
logMd("- Total calls fixed: " . count($results['fixed']), $md);
logMd("- Total manual check (4 params): " . count($results['manual_4']), $md);
logMd("- Total calls with >4 params: " . count($results['more_than_4']), $md);

fclose($md);
echo "\nDetailed report saved to: $outputMd\n";
