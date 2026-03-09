<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/..')));

/**
 * @param mixed $dir
 * @return void
 */
function scanDirectory($dir)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        $filePath = $dir . '/' . $file;

        if (is_dir($filePath)) {
            scanDirectory($filePath);
        } elseif (str_ends_with($filePath, '.php')) {
            scanFile($filePath);
        }
    }
}

$groups = [];

function scanFile($filePath)
{
    global $groups;

    echo "Scanning file: " . str_replace(NV_ROOTDIR . '/tests/', '', $filePath) . "\n";

    unset($matches);
    preg_match_all('/\*[\s]*\@group[\s]+([^\s]+)[\r\n\s\t]+/m', file_get_contents($filePath), $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $group) {
            $group = trim($group);
            if (!in_array($group, $groups)) {
                $groups[] = $group;
            }
        }
    }
}

scanDirectory(NV_ROOTDIR . '/tests');

print_r($groups);
asort($groups);
file_put_contents(NV_ROOTDIR . '/tests/groups.txt', implode("\n", $groups) . "\n", LOCK_EX);

echo "Xong\n";
