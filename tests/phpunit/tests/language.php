<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

use NukeViet\Test\TestCase;

class TestsLanguageBase extends TestCase
{
    /**
     * Kiểm tra file ngôn ngữ hệ thống không tồn tại trong các ngôn ngữ khác Tiếng Việt
     */
    public function testLangSystemNotExistsOtherLang()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $files = nv_scandir(NV_ROOTDIR . '/includes/language/vi', '/^([a-z0-9\_]+)\.php$/');
        $fileNotExists = [];
        foreach ($files as $file) {
            foreach ($langs as $lang) {
                if (!file_exists(NV_ROOTDIR . '/includes/language/' . $lang . '/' . $file)) {
                    $fileNotExists[] = $lang . ':' . $file;
                }
            }
        }
        $this->assertCount(0, $fileNotExists, implode(PHP_EOL, $fileNotExists));
    }

    /**
     * Kiểm tra file ngôn ngữ module không tồn tại trong các ngôn ngữ khác Tiếng Việt
     */
    public function testLangModuleNotExistsOtherLang()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9]+)$/');
        $fileNotExists = [];
        foreach ($modules as $module) {
            foreach ($langs as $lang) {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/admin_vi.php') and !file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $lang . '.php')) {
                    $fileNotExists[] = $module . ':admin_' . $lang . '.php';
                }
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/vi.php') and !file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang . '.php')) {
                    $fileNotExists[] = $module . ':' . $lang . '.php';
                }
            }
        }
        $this->assertCount(0, $fileNotExists, implode(PHP_EOL, $fileNotExists));
    }

    /**
     * Kiểm tra ngôn ngữ hệ thống bị thừa so với Tiếng Việt
     * Kiểm tra ngôn ngữ hệ thống chưa dịch so với Tiếng Việt
     */
    public function testLangSystemRedundantOrNotTranslated()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $files = nv_scandir(NV_ROOTDIR . '/includes/language/vi', '/^([a-z0-9\_]+)\.php$/');
        foreach ($files as $file) {
            if ($file == 'functions.php') {
                continue;
            }
            $lang_translator = $lang_module = $lang_global = [];
            require NV_ROOTDIR . '/includes/language/vi/' . $file;
            $compareLang1 = empty($lang_module) ? $lang_global : $lang_module;
            foreach ($langs as $lang) {
                if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang . '/' . $file)) {
                    $lang_translator = $lang_module = $lang_global = [];
                    require NV_ROOTDIR . '/includes/language/' . $lang . '/' . $file;
                    $compareLang2 = empty($lang_module) ? $lang_global : $lang_module;
                    $redundant = array_diff_key($compareLang2, $compareLang1);
                    $notTranslated = array_diff_key($compareLang1, $compareLang2);
                    $this->assertCount(0, $redundant, 'Redundant lang ' . $lang . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant)));
                    $this->assertCount(0, $notTranslated, 'Not Translated lang ' . $lang . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated)));
                }
            }
        }
    }

    /**
     * Kiểm tra ngôn ngữ module bị thừa so với Tiếng Việt
     * Kiểm tra ngôn ngữ module chưa dịch so với Tiếng Việt
     */
    public function testLangModuleRedundantOrNotTranslated()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9]+)$/');

        foreach ($modules as $module) {
            $checkLangAdmin = file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/admin_vi.php');
            $checkLangSite = file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/vi.php');

            $compareAdminLang1 = $compareSiteLang1 = [];
            if ($checkLangAdmin) {
                $lang_translator = $lang_module = [];
                require NV_ROOTDIR . '/modules/' . $module . '/language/admin_vi.php';
                $compareAdminLang1 = $lang_module;
            }
            if ($checkLangSite) {
                $lang_translator = $lang_module = [];
                require NV_ROOTDIR . '/modules/' . $module . '/language/vi.php';
                $compareSiteLang1 = $lang_module;
            }

            foreach ($langs as $lang) {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $lang . '.php') and $checkLangAdmin) {
                    $lang_translator = $lang_module = [];
                    require NV_ROOTDIR . '/modules/' . $module . '/language/admin_' . $lang . '.php';
                    $compareAdminLang2 = $lang_module;
                    $redundant = array_diff_key($compareAdminLang2, $compareAdminLang1);
                    $notTranslated = array_diff_key($compareAdminLang1, $compareAdminLang2);
                    $this->assertCount(0, $redundant, 'Redundant lang ' . $lang . ' module ' . $module . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant)));
                    $this->assertCount(0, $notTranslated, 'Not Translated lang ' . $lang . ' module ' . $module . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated)));
                }
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang . '.php') and $checkLangSite) {
                    $lang_translator = $lang_module = [];
                    require NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang . '.php';
                    $compareSiteLang2 = $lang_module;
                    $redundant = array_diff_key($compareSiteLang2, $compareSiteLang1);
                    $notTranslated = array_diff_key($compareSiteLang1, $compareSiteLang2);
                    $this->assertCount(0, $redundant, 'Redundant lang ' . $lang . ' module ' . $module . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant)));
                    $this->assertCount(0, $notTranslated, 'Not Translated lang ' . $lang . ' module ' . $module . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated)));
                }
            }
        }
    }
}
