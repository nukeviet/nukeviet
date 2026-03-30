<?php

/**
 * Script to scan and refactor get_title calls in NukeViet 5.0
 * 1. Scans 'src' directory.
 * 2. Identifies $nv_Request->get_title calls.
 * 3. Removes the 4th parameter if it's exactly 0, 1, true, or false.
 * 4. Extracts $maxlength from nv_substr($nv_Request->get_title(...), 0, $maxlength) and appends it to get_title.
 * 5. Reports findings to refactor_get_title.md
 */

$searchDir = realpath(__DIR__ . '/../src');
$rootDir = realpath(__DIR__ . '/..');

if (!$searchDir) {
    die("Directory 'src' not found.\n");
}

$outputMd = __DIR__ . '/refactor_get_title.md';
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
];

$results = [
    'fixed_params' => [],
    'fixed_substr' => [],
    'more_than_4' => [],
    'errors' => []
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

    $relativeFile = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $filePath);
    $relativeFileUnix = str_replace('\\', '/', $relativeFile);

    // STEP 1: Remove 4th parameter if exactly 4 params and 4th is 0, 1, true, or false
    $arg = '(?>[^,\'"()]+|\'[^\']*\'|"[^"]*")*';
    $pattern_4th_param = '/(\$nv_Request->get_title\s*\(\s*' . $arg . '\s*,\s*' . $arg . '\s*,\s*' . $arg . '\s*),\s*([01]|true|false|TRUE|FALSE)\s*\)/';

    $count1 = 0;
    $newContent = preg_replace_callback($pattern_4th_param, function($matches) use (&$results, $relativeFileUnix) {
        $results['fixed_params'][] = [
            'file' => $relativeFileUnix,
            'old_val' => trim($matches[2])
        ];
        return $matches[1] . ')';
    }, $content, -1, $count1);

    // Safeguard against PCRE limits
    if ($newContent === null) {
        $newContent = $content;
        $count1 = 0;
        $results['errors'][] = ['file' => $relativeFileUnix, 'msg' => 'PCRE Error in Step 1: ' . preg_last_error_msg()];
    }

    // STEP 2: Transform nv_substr($nv_Request->get_title(...), 0, X) into $nv_Request->get_title(..., X)
    $pattern_substr = '/nv_substr\s*\(\s*\$nv_Request->get_title\s*\(\s*([^()]+)\s*\)\s*,\s*0\s*,\s*(?!\s*[01]\s*\))([^()]+)\s*\)/';

    $count2 = 0;
    $newContent2 = preg_replace_callback($pattern_substr, function($matches) use (&$results, $relativeFileUnix) {
        $argsStr = trim($matches[1]);
        $maxlen = trim($matches[2]);

        // Robust parameter splitting respecting quotes
        $args = [];
        $currentArg = '';
        $inSingleQuote = false;
        $inDoubleQuote = false;
        $len = strlen($argsStr);
        for ($i = 0; $i < $len; $i++) {
            $char = $argsStr[$i];

            if ($char === "'" && !$inDoubleQuote) {
                if ($i === 0 || $argsStr[$i-1] !== '\\') {
                    $inSingleQuote = !$inSingleQuote;
                }
            } elseif ($char === '"' && !$inSingleQuote) {
                if ($i === 0 || $argsStr[$i-1] !== '\\') {
                    $inDoubleQuote = !$inDoubleQuote;
                }
            }

            if ($char === ',' && !$inSingleQuote && !$inDoubleQuote) {
                $args[] = trim($currentArg);
                $currentArg = '';
            } else {
                $currentArg .= $char;
            }
        }
        if ($currentArg !== '') {
            $args[] = trim($currentArg);
        }

        if (count($args) === 5) {
            // Replace the 4th parameter (index 3) with maxlen
            $args[3] = $maxlen;
            $newArgsStr = implode(', ', $args);
        } else {
            // Append maxlen
            $newArgsStr = $argsStr . ', ' . $maxlen;
        }

        $results['fixed_substr'][] = [
            'file' => $relativeFileUnix,
            'maxlen' => $maxlen
        ];
        return '$nv_Request->get_title(' . $newArgsStr . ')';
    }, $newContent, -1, $count2);

    if ($newContent2 === null) {
        $newContent2 = $newContent;
        $count2 = 0;
        $results['errors'][] = ['file' => $relativeFileUnix, 'msg' => 'PCRE Error in Step 2: ' . preg_last_error_msg()];
    }

    if ($count1 > 0 || $count2 > 0) {
        file_put_contents($filePath, $newContent2);
        $stats['changed']++;
        $content = $newContent2;
    }

    // Token scanner to find any get_title with MORE than 4 arguments left
    $tokens = token_get_all($content);
    $tokenCount = count($tokens);

    for ($i = 0; $i < $tokenCount; $i++) {
        if (is_array($tokens[$i]) && $tokens[$i][0] === T_OBJECT_OPERATOR) {
            if (isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][1] === 'get_title') {
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
                    $fileUrl = '../' . $relativeFileUnix . '#L' . $startLine;

                    if ($paramCount > 4) {
                        $linkStr = "[{$relativeFileUnix}:{$startLine}]({$fileUrl}) - Params: **{$paramCount}**";
                        $alreadyLogged = false;
                        foreach($results['more_than_4'] as $item) {
                            if($item['link'] === $linkStr) {
                                $alreadyLogged = true; break;
                            }
                        }
                        if(!$alreadyLogged) {
                            $results['more_than_4'][] = ['link' => $linkStr];
                        }
                    }
                }
            }
        }
    }
}

if (!empty($results['errors'])) {
    logMd("\n## ❌ ERRORS (PCRE LIMITS)", $md);
    foreach ($results['errors'] as $err) {
        logMd("- Failed: {$err['file']} ({$err['msg']})", $md);
    }
}

logMd("\n## 1. AUTO-REFACTORED: REMOVED 4TH PARAMETER (0 or 1)", $md);
foreach ($results['fixed_params'] as $item) {
    logMd("- Fixed: {$item['file']} (Removed `{$item['old_val']}`)", $md);
}

logMd("\n## 2. AUTO-REFACTORED: EXTRACTED MAXLENGTH FROM NV_SUBSTR", $md);
foreach ($results['fixed_substr'] as $item) {
    logMd("- Fixed: {$item['file']} (Extracted maxlen `{$item['maxlen']}` to 4th param)", $md);
}

logMd("\n## WARNING: MORE THAN 4 PARAMETERS (Requires manual check)", $md);
foreach ($results['more_than_4'] as $row) {
    logMd("- " . $row['link'], $md);
}

logMd("\n## SUMMARY", $md);
logMd("- Total files scanned: $countFiles", $md);
logMd("- Total files modified: " . $stats['changed'], $md);
if (!empty($results['errors'])) logMd("- Total errors: " . count($results['errors']), $md);
logMd("- Total removed 4th param (0/1): " . count($results['fixed_params']), $md);
logMd("- Total extracted nv_substr: " . count($results['fixed_substr']), $md);
logMd("- Total calls with >4 params: " . count($results['more_than_4']), $md);

fclose($md);
echo "\nDetailed report saved to: $outputMd\n";
