<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

define('NV_ADMIN', true);
define('NV_MAINFILE', true);

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('NV_ROOTDIR', str_replace('\\', '/', realpath(dirname(__FILE__) . '/../../src/')));
require NV_ROOTDIR . '/data/config/config_global.php';
require NV_ROOTDIR . '/includes/functions.php';

$array_static_lang = ['vi', 'en', 'fr'];

// Quét các module cần xử lý
$array_modules = [];
$__tmp = scandir(NV_ROOTDIR . '/modules');
foreach ($__tmp as $__tmp__) {
    if (preg_match('/^[a-zA-Z]+[a-zA-Z0-9]{0,}$/', $__tmp__)) {
        $array_modules[] = $__tmp__;
    }
}

foreach ($array_modules as $module_file) {
    // Quét tiếp các block của module
    $array_blocks = [];
    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks')) {
        $__tmp = scandir(NV_ROOTDIR . '/modules/' . $module_file . '/blocks');
        foreach ($__tmp as $__tmp__) {
            if (preg_match('/^(global|module)\.([a-zA-Z0-9\_\-]+)\.php$/i', $__tmp__, $m)) {
                $array_blocks[] = [
                    'php_file' => $__tmp__,
                    'name' => $m[1] . '.' . $m[2]
                ];
            }
        }
    }

    // Duyệt từng block để xử lý
    foreach ($array_blocks as $block_info) {
        // Duyệt mỗi block theo từng ngôn ngữ
        foreach ($array_static_lang as $lang) {
            // Load file lang block.config_[lang].php ra rồi thì sẽ xóa nó luôn
            $global_lang_loaded = false;

            // Lang cuối cùng sẽ thực hiện xuất ra file
            $finalLangTranslator = $finalLangModule = [];

            // Tồn tại file lang thì đọc ra
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang . '.php')) {
                $lang_translator = $lang_module = [];
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang . '.php';
                $finalLangTranslator = $lang_translator;
                $finalLangModule = $lang_module;
            }

            // Lang mới
            $finalLangModuleNew = $finalLangModule;

            /*
             * Kiểm tra xem có file lang php nào không
             * Nếu có include ra để lấy ngôn ngữ
             */
            $lang_translator = $lang_block = [];
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $block_info['name'] . '_' . $lang . '.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $block_info['name'] . '_' . $lang . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/block.config_' . $lang . '.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/block.config_' . $lang . '.php';
                $global_lang_loaded = true;
            }

            /*
             * Nếu không có file lang php thì kiểm tra tiếp file ini
             */
            if (empty($lang_block) and file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['name'] . '.ini')) {
                // Đọc nội dung file ini tìm ra ngôn ngữ
                $xml = simplexml_load_file(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['name'] . '.ini');
                $xmllanguage = $xml->xpath('language');
                $language = (empty($xmllanguage)) ? array() : (array)$xmllanguage[0];
                if (isset($language[$lang])) {
                    $lang_block = (array)$language[$lang];
                }
            }

            $lang_block_convert = [];
            if (empty($lang_block)) {
                echo $lang . ': Pass block ' . $module_file . ':' . $block_info['php_file'] . PHP_EOL;
            } else {
                foreach ($lang_block as $_key => $_value) {
                    if (!isset($finalLangModuleNew[$_key])) {
                        // Thêm vào nếu chưa có key
                        $finalLangModuleNew[$_key] = $_value;
                    } elseif (isset($finalLangModuleNew[$_key]) and $finalLangModuleNew[$_key] != $_value) {
                        $_key_new = 'bl_' . $_key;
                        // Nếu key chuyển mà có thì báo lỗi
                        if (isset($finalLangModuleNew[$_key_new])) {
                            die('Error key exists ' . $_key_new . ' lang ' . $lang . ' module ' . $module_file . ' block ' . $block_info['php_file']);
                        }
                        // Nếu có key mà khác lang thì chuyển key prefix
                        $lang_block_convert[] = [
                            'old' => $_key,
                            'new' => $_key_new
                        ];
                        $finalLangModuleNew[$_key_new] = $_value;
                    }
                }
            }

            // Build lại file lang
            if ($finalLangModuleNew != $finalLangModule) {
                if (preg_match('/^(0?\d|[1-2]{1}\d|3[0-1]{1})[\-\/\.]{1}(0?\d|1[0-2]{1})[\-\/\.]{1}(19[\d]{2}|20[\d]{2})[\-\/\.\,\\s]{2}(0?\d|[1]{1}\d|2[0-4]{1})[\-\/\.\:]{1}([0-5]?[0-9])$/', $finalLangTranslator['createdate'], $m)) {
                    $createdate = mktime($m[4], $m[5], 0, $m[2], $m[1], $m[3]);
                } elseif (preg_match('/^(0?\d|[1-2]{1}\d|3[0-1]{1})[\-\/\.]{1}(0?\d|1[0-2]{1})[\-\/\.]{1}(19[\d]{2}|20[\d]{2})$/', $finalLangTranslator['createdate'], $m)) {
                    $createdate = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
                } else {
                    $createdate = time();
                }

                $content_lang = "<?php\n\n";
                $content_lang .= "/**\n";
                $content_lang .= "* @Project NUKEVIET 4.x\n";
                $content_lang .= "* @Author VINADES.,JSC <contact@vinades.vn>\n";
                $content_lang .= "* @Copyright (C) " . date("Y") . " VINADES.,JSC. All rights reserved\n";
                $content_lang .= "* @Language " . $language_array[$lang]['name'] . "\n";
                $content_lang .= "* @License CC BY-SA (http://creativecommons.org/licenses/by-sa/4.0/)\n";
                $content_lang .= "* @Createdate " . gmdate("M d, Y, h:i:s A", $createdate) . "\n";
                $content_lang .= "*/\n";
                $content_lang .= "\nif (!defined('NV_MAINFILE')) {";
                $content_lang .= "\n    die('Stop!!!');\n}\n\n";

                $finalLangTranslator['info'] = (isset($finalLangTranslator['info'])) ? $finalLangTranslator['info'] : "";

                $content_lang .= "\$lang_translator['author'] = '" . str_replace(['(', ')'], ['<', '>'], $finalLangTranslator['author']) . "';\n";
                $content_lang .= "\$lang_translator['createdate'] = '" . $finalLangTranslator['createdate'] . "';\n";
                $content_lang .= "\$lang_translator['copyright'] = '" . $finalLangTranslator['copyright'] . "';\n";
                $content_lang .= "\$lang_translator['info'] = '" . $finalLangTranslator['info'] . "';\n";
                $content_lang .= "\$lang_translator['langtype'] = '" . $finalLangTranslator['langtype'] . "';\n";
                $content_lang .= "\n";

                foreach ($finalLangModuleNew as $lang_key => $lang_value) {
                    $lang_value = nv_unhtmlspecialchars($lang_value);
                    $lang_value = str_replace("\'", "'", $lang_value);
                    $lang_value = str_replace("'", "\'", $lang_value);
                    $lang_value = nv_nl2br($lang_value);
                    $lang_value = str_replace('<br/>', '<br />', $lang_value);

                    $content_lang .= "\$lang_module['" . $lang_key . "'] = '" . $lang_value . "';\n";
                }

                file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang . '.php', $content_lang, LOCK_EX);
            }

            // Duyệt lang block build lại file php
            $php_file_contents = file_get_contents(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['php_file']);
            $php_file_contents_new = str_replace('$nv_Lang->getBlock', '$nv_Lang->getModule', $php_file_contents);
            foreach ($lang_block_convert as $_convert) {
                $php_file_contents_new = str_replace('$nv_Lang->getModule(\'' . $_convert['old'] . '\'', '$nv_Lang->getModule(\'' . $_convert['new'] . '\'', $php_file_contents_new);
            }
            if ($php_file_contents_new != $php_file_contents) {
                file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['php_file'], $php_file_contents_new, LOCK_EX);
            }

            // Xóa lang global nếu có load ra
            if ($global_lang_loaded) {
                unlink(NV_ROOTDIR . '/modules/' . $module_file . '/language/block.config_' . $lang . '.php');
            }

            // Xóa file lang nếu có
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $block_info['name'] . '_' . $lang . '.php')) {
                unlink(NV_ROOTDIR . '/modules/' . $module_file . '/language/block.' . $block_info['name'] . '_' . $lang . '.php');
            }
        }

        // Đọc lại file ini và xóa phần config lang đi
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['name'] . '.ini')) {
            $file_ini_contents = file_get_contents(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['name'] . '.ini');
            $file_ini_contents_new = preg_replace('/[\r\n\s\t]*\<language\>(.*?)\<\/language\>[\r\n\s\t]*/isu', "\n", $file_ini_contents);
            if ($file_ini_contents_new != $file_ini_contents) {
                file_put_contents(NV_ROOTDIR . '/modules/' . $module_file . '/blocks/' . $block_info['name'] . '.ini', $file_ini_contents_new, LOCK_EX);
            }
        }
    }
}
