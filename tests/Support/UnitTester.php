<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends \Codeception\Actor
{
    // phpcs:ignore
    use _generated\UnitTesterActions;

    /**
     * Liệt kê tất cả các file PHP trừ file ngôn ngữ và thư mục vendor
     *
     * @param string $dir
     * @param string $base_dir
     * @return string[]
     */
    public function listPhpNotLangVendorFile($dir = '', $base_dir = '')
    {
        $file_list = [];

        if (is_dir($dir)) {
            $array_filedir = scandir($dir);

            foreach ($array_filedir as $v) {
                if ($v == '.' or $v == '..') {
                    continue;
                }

                if (is_dir($dir . '/' . $v)) {
                    foreach ($this->listPhpNotLangVendorFile($dir . '/' . $v, $base_dir . '/' . $v) as $file) {
                        $file_list[] = $file;
                    }
                } else {
                    if (
                        preg_match('/\.php$/', $v) and
                        !preg_match('/^\/?(data|vendor)\//', $base_dir . '/' . $v) and
                        !preg_match('/^\/?includes\/vendor\//', $base_dir . '/' . $v) and
                        !preg_match('/\/?includes\/language/', $base_dir . '/' . $v) and
                        !preg_match('/\/?modules\/(.*?)\/language/', $base_dir . '/' . $v) and
                        !preg_match('/\/?themes\/(.*?)\/language/', $base_dir . '/' . $v)
                        ) {
                            $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                        }
                }
            }
        }

        return $file_list;
    }

    /**
     * Liệt kê tất cả các file PHP
     *
     * @param string $dir
     * @param string $base_dir
     * @param string $ext
     * @return string[]
     */
    public function listFile($dir = '', $base_dir = '', string $ext = 'php')
    {
        $ext = ltrim($ext, '.');
        $file_list = [];

        if (is_dir($dir)) {
            $array_filedir = scandir($dir);

            foreach ($array_filedir as $v) {
                if ($v == '.' or $v == '..') {
                    continue;
                }

                if (is_dir($dir . '/' . $v)) {
                    foreach ($this->listFile($dir . '/' . $v, $base_dir . '/' . $v, $ext) as $file) {
                        $file_list[] = $file;
                    }
                } else {
                    if (preg_match('/\.' . $ext . '$/', $v)) {
                        $file_list[] = preg_replace('/^\//', '', $base_dir . '/' . $v);
                    }
                }
            }
        }

        return $file_list;
    }
}
