<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace Tests\Unit;

use Tests\Support\UnitTester;

/**
 * Kiểm tra hàm nv_url_rewrite() với các URL có và không có #fragment.
 */
class UrlRewriteTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    /**
     * Cấu hình rewrite dùng cho test
     */
    private const REWRITE_CONFIG = [
        'rewrite_enable' => 1,
        'admin_rewrite' => 0,
        'rewrite_optional' => 0,
        'rewrite_op_mod' => '',
        'rewrite_endurl' => '/',
        'rewrite_exturl' => '.html',
        'check_rewrite_file' => 1,
        'setup_langs' => ['vi'],
        'allow_sitelangs' => ['vi']
    ];

    /**
     * @var array
     */
    private $config_backup = [];

    protected function _before()
    {
        global $global_config;

        if (!defined('NV_BASE_SITEURL')) {
            define('NV_BASE_SITEURL', '/');
        }
        if (!defined('NV_BASE_ADMINURL')) {
            define('NV_BASE_ADMINURL', NV_BASE_SITEURL . 'admin/');
        }

        foreach (self::REWRITE_CONFIG as $key => $value) {
            $this->config_backup[$key] = $global_config[$key] ?? null;
            $global_config[$key] = $value;
        }
    }

    protected function _after()
    {
        global $global_config;

        foreach ($this->config_backup as $key => $value) {
            if ($value === null) {
                unset($global_config[$key]);
            } else {
                $global_config[$key] = $value;
            }
        }
    }

    /**
     * URL không có # vẫn rewrite như trước
     */
    public function testRewriteWithoutFragment(): void
    {
        $base = NV_BASE_SITEURL;
        $cases = [
            'index.php?language=vi&nv=news' => 'vi/news/',
            'index.php?language=vi&nv=news&op=abc' => 'vi/news/abc/',
            'index.php?language=vi&nv=news&op=abc.html' => 'vi/news/abc.html',
            'index.php?language=vi&nv=news&op=abc&page=2' => 'vi/news/abc/?page=2'
        ];

        foreach ($cases as $url => $expected) {
            $this->assertSame($base . $expected, nv_url_rewrite($base . $url, true), 'URL: ' . $url);
        }
    }

    /**
     * #fragment phải được giữ nguyên ở cuối URL sau khi rewrite
     */
    public function testRewriteKeepsFragmentAtEnd(): void
    {
        $base = NV_BASE_SITEURL;
        $cases = [
            'index.php?language=vi#top' => 'vi/#top',
            'index.php?language=vi&nv=news#top' => 'vi/news/#top',
            'index.php?language=vi&nv=news&op=abc#top' => 'vi/news/abc/#top',
            'index.php?language=vi&nv=news&op=abc.html#top' => 'vi/news/abc.html#top',
            'index.php?language=vi&nv=news&op=abc&page=2#top' => 'vi/news/abc/?page=2#top',
            'index.php?language=vi&nv=news/abc#top' => 'vi/news/abc/#top',
            'index.php?language=vi&nv=news&op=abc#' => 'vi/news/abc/#',
            'index.php?language=vi&nv=news&op=abc#a#b' => 'vi/news/abc/#a#b'
        ];

        foreach ($cases as $url => $expected) {
            $this->assertSame($base . $expected, nv_url_rewrite($base . $url, true), 'URL: ' . $url);
        }
    }

    /**
     * Rewrite trong nội dung HTML có &amp; và #fragment
     */
    public function testRewriteBufferWithFragment(): void
    {
        $base = NV_BASE_SITEURL;
        $buffer = '<a href="' . $base . 'index.php?language=vi&amp;nv=news&amp;op=abc&amp;page=2#comment">x</a>';
        $expected = '<a href="' . $base . 'vi/news/abc/?page=2#comment">x</a>';

        $this->assertSame($expected, nv_url_rewrite($buffer));
    }

    /**
     * Dấu # của entity dạng số (do nv_htmlspecialchars sinh ra) không được hiểu là fragment,
     * URL có entity trong query được giữ nguyên không rewrite
     */
    public function testNumericEntityIsNotFragment(): void
    {
        $base = NV_BASE_SITEURL;
        $buffers = [
            '<a href="' . $base . 'index.php?language=vi&amp;nv=seek&amp;q=it&#039;s">x</a>',
            '<a href="' . $base . 'index.php?language=vi&amp;nv=seek&amp;q=a&#x002F;b&amp;page=2">x</a>',
            '<a href="' . $base . 'index.php?language=vi&amp;nv=seek&amp;q=c&#x23;">x</a>',
            '<a href="' . $base . 'index.php?language=vi&amp;nv=seek&amp;q=it&#039;s#top">x</a>'
        ];

        foreach ($buffers as $buffer) {
            $this->assertSame($buffer, nv_url_rewrite($buffer), 'Buffer: ' . $buffer);
        }

        // Entity chỉ nằm trong fragment thì query vẫn rewrite bình thường
        $buffer = '<a href="' . $base . 'index.php?language=vi&amp;nv=news&amp;op=abc#it&#039;s">x</a>';
        $expected = '<a href="' . $base . 'vi/news/abc/#it&#039;s">x</a>';
        $this->assertSame($expected, nv_url_rewrite($buffer));
    }

    /**
     * URL không đủ điều kiện rewrite thì trả nguyên bản, kể cả phần #fragment
     */
    public function testNotRewritableUrlKeepsOriginal(): void
    {
        $base = NV_BASE_SITEURL;
        $urls = [
            'index.php?language=xx&nv=news#top',
            'index.php?language=vi&nv=news&op=a_b#top',
            'index.php?language=vi&op=abc&nv=news#top'
        ];

        foreach ($urls as $url) {
            $this->assertSame($base . $url, nv_url_rewrite($base . $url, true), 'URL: ' . $url);
        }
    }
}
