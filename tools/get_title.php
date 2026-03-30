<?php

/**
 * Script to find and categorize $nv_Request->get_title usage in NukeViet 5.0
 * Categorization:
 * 1. Exactly 4 parameters
 * 2. More than 4 parameters
 */
		$value = nv_substr( $value, 0, $maxlength );

$searchDir = realpath(__DIR__ . '/../src');
$rootDir = realpath(__DIR__ . '/..');

if (!$searchDir) {
    die("Directory 'src' not found.\n");
}

$outputFile = __DIR__ . '/get_title_results.txt';
$outputMd = __DIR__ . '/get_title_results.md';

$out = fopen($outputFile, 'w');
$md = fopen($outputMd, 'w');

function logEx($msg, $out, $md, $isMd = false) {
    echo $msg . "\n";
    if ($out) fwrite($out, $msg . "\n");
    if ($md) fwrite($md, ($isMd ? $msg : $msg) . "\n");
}

function logMd($msg, $md) {
    fwrite($md, $msg . "\n");
}

logEx("# NukeViet 5.0 - get_title usage report", null, $md, true);
logEx("Scanning $searchDir...", $out, $md);

$results = [
    '4_params' => [],
    'more_than_4' => []
];

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($searchDir));

foreach ($files as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') {
        continue;
    }

    $filePath = $file->getRealPath();
    $content = file_get_contents($filePath);

    if (strpos($content, '->get_title') === false) {
        continue;
    }

    $tokens = token_get_all($content);
    $count = count($tokens);

    for ($i = 0; $i < $count; $i++) {
        // Find $variable->get_title
        if (is_array($tokens[$i]) && $tokens[$i][0] === T_OBJECT_OPERATOR) {
            if (isset($tokens[$i + 1]) && is_array($tokens[$i + 1]) && $tokens[$i + 1][1] === 'get_title') {

                // Identify variable calling the method
                $variableName = '';
                if (isset($tokens[$i - 1]) && is_array($tokens[$i - 1]) && $tokens[$i - 1][0] === T_VARIABLE) {
                    $variableName = $tokens[$i - 1][1];
                }

                // Find start of parameters (the '(' token)
                $j = $i + 2;
                while ($j < $count && $tokens[$j] !== '(') {
                    $j++;
                }

                if ($j < $count && $tokens[$j] === '(') {
                    $startLine = $tokens[$i][2];
                    $paramCount = 0;
                    $bracketLevel = 0;
                    $hasContent = false;

                    $k = $j + 1;
                    while ($k < $count) {
                        $token = $tokens[$k];

                        if ($token === '(') {
                            $bracketLevel++;
                            $hasContent = true;
                        } elseif ($token === ')') {
                            if ($bracketLevel === 0) {
                                // End of function call
                                if ($hasContent) {
                                    $paramCount++;
                                }
                                break;
                            }
                            $bracketLevel--;
                        } elseif ($token === ',' && $bracketLevel === 0) {
                            $paramCount++;
                            $hasContent = false; // Reset for next param
                        } elseif (!is_array($token) || ($token[0] !== T_WHITESPACE && $token[0] !== T_COMMENT && $token[0] !== T_DOC_COMMENT)) {
                            $hasContent = true;
                        }
                        $k++;
                    }

                    $relativeFile = str_replace($rootDir . DIRECTORY_SEPARATOR, '', $filePath);
                    $relativeFileUnix = str_replace('\\', '/', $relativeFile);
                    $fileUrl = '../' . $relativeFileUnix . '#L' . $startLine;

                    if ($paramCount === 4) {
                        $results['4_params'][] = [
                            'text' => "{$relativeFile} (Line {$startLine}) - Calls: {$variableName}->get_title",
                            'link' => "[{$relativeFile}:{$startLine}]({$fileUrl}) - Calls: `{$variableName}->get_title`"
                        ];
                    } elseif ($paramCount > 4) {
                        $results['more_than_4'][] = [
                            'text' => "{$relativeFile} (Line {$startLine}) - Params: {$paramCount} - Calls: {$variableName}->get_title",
                            'link' => "[{$relativeFile}:{$startLine}]({$fileUrl}) - Params: **{$paramCount}** - Calls: `{$variableName}->get_title`"
                        ];
                    }
                }
            }
        }
    }
}

logEx("\n--- [ CATEGORY: EXACTLY 4 PARAMETERS ] ---", $out, null);
logMd("\n## CATEGORY: EXACTLY 4 PARAMETERS", $md);
foreach ($results['4_params'] as $row) {
    fwrite($out, $row['text'] . "\n");
    logMd("- " . $row['link'], $md);
}
logEx("Total: " . count($results['4_params']), $out, $md);

logEx("\n--- [ CATEGORY: MORE THAN 4 PARAMETERS ] ---", $out, null);
logMd("\n## CATEGORY: MORE THAN 4 PARAMETERS", $md);
foreach ($results['more_than_4'] as $row) {
    fwrite($out, $row['text'] . "\n");
    logMd("- " . $row['link'], $md);
}
logEx("Total: " . count($results['more_than_4']), $out, $md);

fclose($out);
fclose($md);
echo "\nResults saved to:\n$outputFile\n$outputMd\n";


