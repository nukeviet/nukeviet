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
 * @param mixed $file
 * @return void
 */
function updateFile($file)
{
    $defaultBanner = "/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */";

    $relativePath = str_replace(NV_ROOTDIR . '/', '', $file);
    $pathArray = explode('/', $relativePath);
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($pathArray[0], [
        'scss', 'src', 'tests', 'tools'
    ]) or !in_array($ext, [
        'php', 'js', 'scss',
    ])) {
        return;
    }
    if (preg_match('/^src\/data\/(cache|tmp)/', $relativePath)) {
        // Bỏ qua một số thư mục
        return;
    }
    $content = file_get_contents($file);

    $newContent = preg_replace_callback('/\/\*[ ]*\*[\r\n]+(.*?)\*\//s', function ($matches) use ($relativePath, $defaultBanner) {
        if (stripos($matches[1], 'VINADES') === false or stripos($matches[1], '@Copyright') === false) {
            // Không phải đoạn banner của NukeViet thì bỏ qua
            return $matches[0];
        }

        /**
         * Duyệt từng line. Xóa đi line trống hoàn toàn, theo rule line trống cũng phải có kí tự *
         */
        $lines = array_filter(array_map('trim', explode("\n", trim($matches[1]))));
        $checkNukeVietStructre = 0;
        $knownAnnotations = [
            'package', 'author', 'copyright',
            'license', 'see', 'version', 'access', 'project', 'createdate', 'since'
        ];
        $newLines = [];
        $arrayAnnotations = [];
        $classDoc = false;
        $oldStructreDetected = false;
        foreach ($lines as $line) {
            $annotations = '';
            if (preg_match('/^\*[ ]+\@(\w+)[\s]+(.*)$/', $line, $m)) {
                $annotations = strtolower($m[1]);
                $line = trim($m[2]);
                $arrayAnnotations[] = strtolower($m[1]);
            }

            // Các $annotations không xác định
            if (!empty($annotations) and !in_array($annotations, $knownAnnotations)) {
                print_r($annotations . "\n");
                die("Cấu trúc banner không đúng (annotations) tại tệp: $relativePath\n");
            }

            if (!empty($annotations)) {
                // Chỉnh lại các annotations
                if ($annotations == 'project') {
                    $oldStructreDetected = true;
                } elseif ($annotations == 'package') {
                    $classDoc = true;
                } elseif ($annotations == 'version') {
                    //$checkNukeVietStructre++;
                    $line = '5.x';
                } elseif ($annotations == 'author') {
                    $checkNukeVietStructre++;
                    $line = 'VINADES.,JSC <contact@vinades.vn>';
                } elseif ($annotations == 'copyright') {
                    $checkNukeVietStructre++;
                    $line = '(C) 2009-' . date('Y') . ' VINADES.,JSC. All rights reserved';
                }
                $newLines[] = ' * @' . $annotations . ' ' . $line;
            } else {
                // Giữ nguyên line
                $newLines[] = ' ' . $line;
            }
        }
        // Case không có version, author, hoặc copyright => không phải banner NukeViet
        if ($checkNukeVietStructre < 2 and !$oldStructreDetected) {
            return $matches[0];
        }

        // NukeViet cũ
        $oldStructre = ['project', 'author', 'copyright', 'license', 'createdate'];

        // Cấu trúc mới của tệp thường và class
        $basicStructre = ['version', 'author', 'copyright', 'license', 'see'];
        $classStructre = ['package', 'author', 'copyright', 'version', 'since', 'access'];

        if (!empty($arrayAnnotations)) {
            if (count(array_diff($arrayAnnotations, $oldStructre)) == 0 and count(array_diff($oldStructre, $arrayAnnotations)) == 0) {
                // Cấu trúc cũ, trả nguyên về format mới
                return $defaultBanner;
            } elseif ($classDoc and count(array_diff($arrayAnnotations, $classStructre)) > 0) {
                // Cấu trúc class
                print_r($arrayAnnotations);
                die("Cấu trúc banner không đúng (tệp class) tại tệp: $relativePath\n");
            } elseif (!$classDoc and count(array_diff($arrayAnnotations, $basicStructre)) > 0) {
                // Cấu trúc tệp thường
                print_r($arrayAnnotations);
                die("Cấu trúc banner không đúng (tệp thường) tại tệp: $relativePath\n");
            }
        }

        return '/**' . "\n" . implode("\n", $newLines) . "\n" . ' */';
    }, $content);
    if ($newContent != $content) {
        file_put_contents($file, $newContent, LOCK_EX);
        echo 'Update file: ' . $relativePath . "\n";
    }
}

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
        } else {
            updateFile($filePath);
        }
    }
}
scanDirectory(NV_ROOTDIR);
echo "Xong\n";
