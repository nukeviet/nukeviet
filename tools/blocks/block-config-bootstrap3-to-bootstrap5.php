<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_MAINFILE', true);
define('NV_ROOTDIR', str_replace(DIRECTORY_SEPARATOR, '/', realpath(pathinfo(__file__, PATHINFO_DIRNAME) . '/../../src')));

require NV_ROOTDIR . '/includes/core/filesystem_functions.php';

// Các module
$modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9\-\_]+)$/i');
foreach ($modules as $module) {
    $blocks = nv_scandir(NV_ROOTDIR . '/modules/' . $module . '/blocks', '/^(global|module)\.(.*)\.php$/i');

    foreach ($blocks as $block) {
        $iniName = preg_replace('/\.php$/', '.ini', $block);
        $filePhp = NV_ROOTDIR . '/modules/' . $module . '/blocks/' . $block;
        $fileIni = NV_ROOTDIR . '/modules/' . $module . '/blocks/' . $iniName;

        if (!file_exists($fileIni)) {
            continue;
        }
        list($datafunction, $submitfunction) = getIni($fileIni);
        if (empty($datafunction) or empty($submitfunction)) {
            continue;
        }

        echo "modules/" . $module . "/blocks/" . $block . " > ";

        $cContents = file_get_contents($filePhp);
        $nContents = convert($cContents, $datafunction, $submitfunction);

        if ($cContents != $nContents) {
            echo "\033[0;32mCHANGED\033[0m\n";
            file_put_contents($filePhp, $nContents, LOCK_EX);
        } else {
            echo "NO-CHANGE\n";
        }
    }
}

// Các giao diện
$themes = nv_scandir(NV_ROOTDIR . '/themes', '/^([a-zA-Z0-9\-\_]+)$/i');
foreach ($themes as $theme) {
    $blocks = nv_scandir(NV_ROOTDIR . '/themes/' . $theme . '/blocks', '/^(global|module)\.(.*)\.php$/i');

    foreach ($blocks as $block) {
        $iniName = preg_replace('/\.php$/', '.ini', $block);
        $filePhp = NV_ROOTDIR . '/themes/' . $theme . '/blocks/' . $block;
        $fileIni = NV_ROOTDIR . '/themes/' . $theme . '/blocks/' . $iniName;

        if (!file_exists($fileIni)) {
            continue;
        }
        list($datafunction, $submitfunction) = getIni($fileIni);
        if (empty($datafunction) or empty($submitfunction)) {
            continue;
        }

        echo "themes/" . $theme . "/blocks/" . $block . " > ";

        $cContents = file_get_contents($filePhp);
        $nContents = convert($cContents, $datafunction, $submitfunction);

        if ($cContents != $nContents) {
            echo "\033[0;32mCHANGED\033[0m\n";
            file_put_contents($filePhp, $nContents, LOCK_EX);
        } else {
            echo "NO-CHANGE\n";
        }
    }
}

function getIni(string $fileIni)
{
    $xml = simplexml_load_file($fileIni);
    if ($xml === false) {
        die("Ini error: " . $fileIni . "\n");
    }

    $datafunction = trim((string) $xml->datafunction);
    $submitfunction = trim((string) $xml->submitfunction);

    return [$datafunction, $submitfunction];
}

function convert(string $contents, string $datafunction, string $submitfunction)
{
    $pattern = '/function[\s]+' . preg_quote($datafunction, '/') . '[\s]*\((.*?)function[\s]+' . preg_quote($submitfunction, '/') . '/is';
    if (!preg_match($pattern, $contents, $m)) {
        echo "\033[0;31mWrong rule\033[0m > ";
        return $contents;
    }

    $handlerStr = $m[1];
    $handlerStr = str_replace('form-group', 'row mb-3', $handlerStr);
    $handlerStr = str_replace('control-label col-sm-6', 'col-sm-3 col-form-label text-sm-end text-truncate fw-medium', $handlerStr);
    $handlerStr = str_replace('col-sm-6 control-label', 'col-sm-3 col-form-label text-sm-end text-truncate fw-medium', $handlerStr);
    $handlerStr = str_replace('col-sm-9', 'col-sm-5', $handlerStr);
    $handlerStr = str_replace('col-sm-18', 'col-sm-9', $handlerStr);
    $handlerStr = str_replace('btn-default', 'btn-secondary', $handlerStr);
    $handlerStr = str_replace('btn-xs', 'btn-sm', $handlerStr);
    $handlerStr = str_replace('margin-bottom', 'mb-3', $handlerStr);
    $handlerStr = str_replace('class="ellipsis"', 'class="text-truncate"', $handlerStr);
    $handlerStr = str_replace('input-group-addon', 'input-group-text', $handlerStr);

    $handlerStr = preg_replace_callback('/\<select([^\>]+)\>/is', function($matches) {
        return str_replace('form-control', 'form-select', $matches[0]);
    }, $handlerStr);

    // Kiểm tra lại
    $recheck = false;
    $strs = ['form-group', 'control-label', 'col-sm-18'];
    foreach ($strs as $str) {
        if (strpos($handlerStr, $str) !== false) {
            $recheck = true;
            break;
        }
    }
    if (preg_match('/\<select([^\>]+)form\-control([^\>]+)\>/is', $handlerStr)) {
        echo "\033[0;33mSELECT\033[0m ";
    }

    if ($recheck) {
        echo "\033[0;33mMUST RECHECK\033[0m ";
    }

    return str_replace($m[1], $handlerStr, $contents);
}
