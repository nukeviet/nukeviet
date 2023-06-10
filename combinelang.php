<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_MAINFILE', true);
define('NV_ADMIN', true);
// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

function check_conflicts($lang_module, &$temp_lang_module, $lang, $lang_file)
{
    echo 'Check for conflicts: ';
    $_intersect_keys = array_intersect_key($lang_module, $temp_lang_module);
    $valid = true;
    if (!empty($_intersect_keys)) {
        $intersect_keys = [];
        foreach ($_intersect_keys as $key => $value) {
            if ($value != $temp_lang_module[$key]) {
                $intersect_keys[$key] = [
                    $lang => htmlspecialchars($temp_lang_module[$key]),
                    $lang_file => htmlspecialchars($value)
                ];
            }
        }
        if (!empty($intersect_keys)) {
            $valid = false;
            echo '<span style="color:red">The following language variables have the same key but different values. Change the key in either file or assign them the same value:</span><br/>';
            echo '<pre style="color:DarkBlue">' . print_r($intersect_keys, true) . '</pre>';
        }
    }

    if ($valid) {
        echo '<span style="color:green">No conflict</span><br/>';
        $temp_lang_module = array_merge($temp_lang_module, $lang_module);
    }

    return $valid;
}

$dirs = glob(NV_ROOTDIR . '/modules/*/language/');
$dirs = array_merge($dirs, glob(NV_ROOTDIR . '/themes/*/language/'));
$langs = ['en', 'fr', 'vi'];
foreach ($dirs as $langdir) {
    foreach ($langs as $lang) {
        $site_lang_exists = file_exists($langdir . $lang . '.php');
        $admin_lang_exists = file_exists($langdir . 'admin_' . $lang . '.php');
        if ($site_lang_exists or $admin_lang_exists) {
            $combinelang_file = $langdir . 'lang.' . $lang . '.php';
            $combinelang_file_exists = file_exists($combinelang_file);
            //if (!$combinelang_file_exists) {
                $temp_lang_module = [];
                $lang_module = [];
                $lang_block = [];

                if ($site_lang_exists) {
                    $lang_file = $langdir . $lang . '.php';
                    echo 'Loaded file: <code style="color:SlateBlue">' . $lang_file . '</code><br/>';
                    include $lang_file;
                    if (!empty($lang_module)) {
                        $valid = check_conflicts($lang_module, $temp_lang_module, $lang, $lang_file);
                    }
                    if (!empty($lang_block)) {
                        $valid = check_conflicts($lang_block, $temp_lang_module, $lang, $lang_file);
                    }
                }

                $lang_module = [];
                $lang_block = [];
                if ($admin_lang_exists) {
                    $lang_file = $langdir . 'admin_' . $lang . '.php';
                    echo 'Loaded file: <code style="color:SlateBlue">' . $lang_file . '</code><br/>';
                    include $lang_file;

                    if (!empty($lang_module)) {
                        $valid = check_conflicts($lang_module, $temp_lang_module, $lang, $lang_file);
                    }
                    if (!empty($lang_block)) {
                        $valid = check_conflicts($lang_block, $temp_lang_module, $lang, $lang_file);
                    }
                }

                $block_langs = glob($langdir . 'block.*.*_' . $lang . '.php');
                if (!empty($block_langs)) {
                    foreach ($block_langs as $block_lang) {
                        $lang_block = [];
                        echo 'Loaded file: <code style="color:SlateBlue">' . $block_lang . '</code><br/>';
                        include $block_lang;
                        if (!empty($lang_block)) {
                            $valid = check_conflicts($lang_block, $temp_lang_module, $lang, $block_lang);
                        }
                    }
                }
                if ($valid) {
                    echo 'Create file: <code style="color:SlateBlue">' . $combinelang_file . '</code><br/>';
                    $temp_lang_module = array_map(function($val) {
                        return str_replace("'", "\'", $val);
                    }, $temp_lang_module);
                    $data = "<?php\n\n/**\n * NukeViet Content Management System\n * @version 4.x\n * @author VINADES.,JSC <contact@vinades.vn>\n * @copyright (C) 2009-" . date('Y') . " VINADES.,JSC. All rights reserved\n * @license GNU/GPL version 2 or any later version\n * @see https://github.com/nukeviet The NukeViet CMS GitHub project\n */\n\n";
                    $data .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
                    $data .= "\$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';\n";
                    $data .= "\$lang_translator['createdate'] = '" . date('d/m/Y, H:i') . "';\n";
                    $data .= "\$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';\n";
                    $data .= "\$lang_translator['info'] = '';\n";
                    $data .= "\$lang_translator['langtype'] = 'lang_module';\n\n";
                    foreach ($temp_lang_module as $key => $value) {
                        $data .= "\$lang_module['" . $key . "'] = '" . $value . "';\n";
                    }
                    file_put_contents($combinelang_file, $data, LOCK_EX);
                }
                echo '############################<br/><br/>';
            //} else {
            //    echo 'File <code style="color:SlateBlue">' . $combinelang_file . '</code> has been created before<br/>';
            //}
        }
    }
}
