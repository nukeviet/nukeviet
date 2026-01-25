<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

/**
 * Kiểm tra các vấn đề về ngôn ngữ
 */
class LangTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    /**
     * Kiểm tra file ngôn ngữ hệ thống không tồn tại trong các ngôn ngữ khác Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
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
     * Kiểm tra file ngôn ngữ module thừa
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testFileLangModuleRedundancy()
    {
        $modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9\_\-]+)$/');
        foreach ($modules as $module) {
            $langs = nv_scandir(NV_ROOTDIR . '/modules/' . $module . '/language', '/^admin\_(.*?)\.php$/');
            $this->assertCount(0, $langs, implode(PHP_EOL, $langs));
        }
    }

    /**
     * Kiểm tra file ngôn ngữ theme thừa
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testFileLangThemeRedundancy()
    {
        $themes = nv_scandir(NV_ROOTDIR . '/themes', '/^([a-zA-Z0-9\_\-]+)$/');
        foreach ($themes as $theme) {
            $langs = nv_scandir(NV_ROOTDIR . '/themes/' . $theme . '/language', '/^admin\_(.*?)\.php$/');
            $this->assertCount(0, $langs, implode(PHP_EOL, $langs));
        }
    }

    /**
     * Kiểm tra file ngôn ngữ module không tồn tại trong các ngôn ngữ khác Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testLangModuleNotExistsOtherLang()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9]+)$/');
        $fileNotExists = [];
        foreach ($modules as $module) {
            foreach ($langs as $lang) {
                if (file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/vi.php') and !file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/' . $lang . '.php')) {
                    $fileNotExists[] = $module . ':' . $lang . '.php';
                }
            }
        }
        $this->assertCount(0, $fileNotExists, implode(PHP_EOL, $fileNotExists));
    }

    /**
     * Kiểm tra file ngôn ngữ giao diện không tồn tại trong các ngôn ngữ khác Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testLangThemeNotExistsOtherLang()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $themes = nv_scandir(NV_ROOTDIR . '/themes', '/^([a-zA-Z0-9\_\-]+)$/');
        $fileNotExists = [];
        foreach ($themes as $theme) {
            if (!file_exists(NV_ROOTDIR . '/themes/' . $theme . '/language/vi.php')) {
                continue;
            }

            foreach ($langs as $lang) {
                if (!file_exists(NV_ROOTDIR . '/themes/' . $theme . '/language/' . $lang . '.php')) {
                    $fileNotExists[] = $theme . ':' . $lang . '.php';
                }
            }
        }
        $this->assertCount(0, $fileNotExists, implode(PHP_EOL, $fileNotExists));
    }

    /**
     * Kiểm tra ngôn ngữ hệ thống bị thừa so với Tiếng Việt
     * Kiểm tra ngôn ngữ hệ thống chưa dịch so với Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testLangSystemRedundantOrNotTranslated()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $files = nv_scandir(NV_ROOTDIR . '/includes/language/vi', '/^(.*)\.php$/');

        foreach ($langs as $lang) {
            foreach ($files as $file) {
                $check_file = NV_ROOTDIR . '/includes/language/' . $lang . '/' . $file;
                $this->assertFileExists($check_file, 'File not exists: includes/language/' . $lang . '/' . $file);

                if (!file_exists($check_file) or $file == 'functions.php') {
                    continue;
                }

                // Lấy ngôn ngữ gốc VI kiểm tra
                $lang_translator = $lang_module = $lang_global = $lang_block = [];
                require NV_ROOTDIR . '/includes/language/vi/' . $file;
                $compareLang1 = [
                    'm' => $lang_module,
                    'g' => $lang_global,
                    'b' => $lang_block
                ];

                // Lấy ngôn ngữ này kiểm tra
                $lang_translator = $lang_module = $lang_global = $lang_block = [];
                require NV_ROOTDIR . '/includes/language/' . $lang . '/' . $file;
                $compareLang2 = [
                    'm' => $lang_module,
                    'g' => $lang_global,
                    'b' => $lang_block
                ];

                $redundant_m = array_diff_key($compareLang2['m'], $compareLang1['m']);
                $redundant_g = array_diff_key($compareLang2['g'], $compareLang1['g']);
                $redundant_b = array_diff_key($compareLang2['b'], $compareLang1['b']);

                $notTranslated_m = array_diff_key($compareLang1['m'], $compareLang2['m']);
                $notTranslated_g = array_diff_key($compareLang1['g'], $compareLang2['g']);
                $notTranslated_b = array_diff_key($compareLang1['b'], $compareLang2['b']);

                $this->assertCount(0, $redundant_m, 'Redundant lang module ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_m)));
                $this->assertCount(0, $redundant_g, 'Redundant lang global ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_g)));
                $this->assertCount(0, $redundant_b, 'Redundant lang block ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_b)));

                $this->assertCount(0, $notTranslated_m, 'Not Translated lang module ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_m)));
                $this->assertCount(0, $notTranslated_g, 'Not Translated lang global ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_g)));
                $this->assertCount(0, $notTranslated_b, 'Not Translated lang block ' . $lang . ' in file ' . $file . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_b)));
            }
        }
    }

    /**
     * Kiểm tra ngôn ngữ module bị thừa so với Tiếng Việt
     * Kiểm tra ngôn ngữ module chưa dịch so với Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testLangModuleRedundantOrNotTranslated()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $modules = nv_scandir(NV_ROOTDIR . '/modules', '/^([a-zA-Z0-9]+)$/');

        foreach ($modules as $module) {
            $checkLangSite = file_exists(NV_ROOTDIR . '/modules/' . $module . '/language/vi.php');

            $compareSiteLang1 = [];
            if ($checkLangSite) {
                $lang_translator = $lang_module = [];
                require NV_ROOTDIR . '/modules/' . $module . '/language/vi.php';
                $compareSiteLang1 = $lang_module;
            }

            foreach ($langs as $lang) {
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

    /**
     * Kiểm tra ngôn ngữ theme bị thừa so với Tiếng Việt
     * Kiểm tra ngôn ngữ theme chưa dịch so với Tiếng Việt
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testLangThemeRedundantOrNotTranslated()
    {
        $langs = array_diff(nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/'), ['vi']);
        $themes = nv_scandir(NV_ROOTDIR . '/themes', '/^([a-zA-Z0-9\-\_]+)$/');

        foreach ($themes as $theme) {
            $checkLang = file_exists(NV_ROOTDIR . '/themes/' . $theme . '/language/vi.php');
            $compareLang1 = [];
            if ($checkLang) {
                $lang_translator = $lang_module = $lang_global = $lang_block = [];
                require NV_ROOTDIR . '/themes/' . $theme . '/language/vi.php';
                $compareLang1 = [
                    'm' => $lang_module,
                    'g' => $lang_global,
                    'b' => $lang_block
                ];
            }

            foreach ($langs as $lang) {
                if (file_exists(NV_ROOTDIR . '/themes/' . $theme . '/language/' . $lang . '.php') and $checkLang) {
                    $lang_translator = $lang_module = $lang_global = $lang_block = [];
                    require NV_ROOTDIR . '/themes/' . $theme . '/language/' . $lang . '.php';
                    $compareLang2 = [
                        'm' => $lang_module,
                        'g' => $lang_global,
                        'b' => $lang_block
                    ];

                    $redundant_m = array_diff_key($compareLang2['m'], $compareLang1['m']);
                    $redundant_g = array_diff_key($compareLang2['g'], $compareLang1['g']);
                    $redundant_b = array_diff_key($compareLang2['b'], $compareLang1['b']);

                    $notTranslated_m = array_diff_key($compareLang1['m'], $compareLang2['m']);
                    $notTranslated_g = array_diff_key($compareLang1['g'], $compareLang2['g']);
                    $notTranslated_b = array_diff_key($compareLang1['b'], $compareLang2['b']);

                    $this->assertCount(0, $redundant_m, 'Redundant lang module ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_m)));
                    $this->assertCount(0, $redundant_g, 'Redundant lang global ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_g)));
                    $this->assertCount(0, $redundant_b, 'Redundant lang block ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($redundant_b)));

                    $this->assertCount(0, $notTranslated_m, 'Not Translated lang module ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_m)));
                    $this->assertCount(0, $notTranslated_g, 'Not Translated lang global ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_g)));
                    $this->assertCount(0, $notTranslated_b, 'Not Translated lang block ' . $lang . ' theme ' . $theme . ':' . PHP_EOL . implode(PHP_EOL, array_keys($notTranslated_b)));
                }
            }
        }
    }

    /**
     * Kiểm tra các biến $lang_module, $lang_global, $lang_block trong các file không phải ngôn ngữ + vendor
     * Nếu có chứng tỏ còn sót theo cách viết 4.5
     *
     * @group install
     * @group install-only
     * @group all
     */
    public function testOldLangRedundant()
    {
        $allfiles = $this->tester->listPhpNotLangVendorFile(NV_ROOTDIR);

        foreach ($allfiles as $filepath) {
            $filecontents = file_get_contents(NV_ROOTDIR . '/' . $filepath);
            $this->assertEquals(0, preg_match("/\\$(lang_global|lang_module|lang_block)[\s]*\[/", $filecontents), 'File: ' . $filepath);
        }
    }
}
