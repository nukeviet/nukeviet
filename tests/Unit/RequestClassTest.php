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
 * Kiểm tra bảo mật class Request.
 *
 * @group security
 */
class RequestClassTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    protected function _after(): void
    {
        // Dọn dẹp $_POST để không ảnh hưởng test khác
        unset($_POST['bodytext']);
    }

    /**
     * get_editor() phải lọc bỏ onerror trong srcdoc của iframe.
     *
     * Nếu kết quả trả về vẫn còn chuỗi "onerror" thì Request
     * chưa vô hiệu hóa vector XSS qua iframe[srcdoc].
     *
     * @group security
     */
    public function testGetEditorMustStripOnerrorInIframeSrcdoc(): void
    {
        global $nv_Request;

        // Payload: iframe có srcdoc chứa img với event handler onerror dạng HTML-encoded
        $_POST['bodytext'] = 'aaaa<iframe srcdoc="&lt;img src=1 onerror=alert(document.cookie)&gt;"></iframe>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        // 1. onerror phải bị loại bỏ
        $this->assertStringNotContainsStringIgnoringCase(
            'onerror',
            $result,
            'Lỗi bảo mật XSS: get_editor() không lọc "onerror" trong srcdoc của iframe. '
            . 'Kết quả thực tế: ' . $result
        );

        // 2. attribute srcdoc phải được giữ lại (chỉ lọc nội dung nguy hiểm, không xóa cả attribute)
        $this->assertStringContainsStringIgnoringCase(
            'srcdoc',
            $result,
            'Attribute srcdoc bị xóa hoàn toàn thay vì chỉ lọc nội dung nguy hiểm bên trong.'
        );

        // 3. tag iframe phải được giữ lại
        $this->assertStringContainsStringIgnoringCase(
            'iframe',
            $result,
            'Tag iframe bị xóa, nhưng kịch bản này chỉ cần lọc nội dung srcdoc.'
        );
    }

    /**
     * get_editor() phải giữ nguyên srcdoc với nội dung HTML an toàn.
     *
     * @group security
     */
    public function testGetEditorPreservesSafeSrcdocContent(): void
    {
        global $nv_Request;

        $_POST['bodytext'] = '<iframe srcdoc="&lt;p&gt;Hello World&lt;/p&gt;"></iframe>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        $this->assertStringContainsStringIgnoringCase(
            'srcdoc',
            $result,
            'Attribute srcdoc với nội dung an toàn bị xóa không đúng.'
        );
        $this->assertStringContainsStringIgnoringCase(
            'Hello World',
            $result,
            'Nội dung văn bản an toàn trong srcdoc bị lọc mất.'
        );
    }

    /**
     * get_editor() phải loại bỏ attribute ping khỏi tag <a>.
     *
     * <a ping="https://evil.com/track"> khiến browser gửi POST request
     * đến URL tùy ý khi user click — vector SSRF và tracking lén lút.
     *
     * @group security
     */
    public function testGetEditorMustStripPingAttribute(): void
    {
        global $nv_Request;

        $_POST['bodytext'] = '<a href="https://example.com" ping="https://evil.com/track">Click me</a>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        $this->assertStringNotContainsStringIgnoringCase(
            'ping',
            $result,
            'Lỗi bảo mật SSRF: attribute ping không bị lọc khỏi tag <a>. '
            . 'Kết quả thực tế: ' . $result
        );

        // Tag <a> và href hợp lệ phải được giữ lại
        $this->assertStringContainsString('href', $result, 'Attribute href hợp lệ bị xóa nhầm.');
        $this->assertStringContainsString('Click me', $result, 'Nội dung text của <a> bị xóa nhầm.');
    }

    /**
     * get_editor() phải loại bỏ @import trong style attribute.
     *
     * <div style="@import url(https://evil.com/x.css)"> có thể gây
     * CSS injection trong một số browser/WebView context cũ.
     *
     * @group security
     */
    public function testGetEditorMustStripAtImportInStyleAttribute(): void
    {
        global $nv_Request;

        $_POST['bodytext'] = '<div style="@import url(https://evil.com/x.css);">content</div>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        $this->assertStringNotContainsStringIgnoringCase(
            '@import',
            $result,
            'Lỗi bảo mật CSS injection: @import trong style attribute không bị lọc. '
            . 'Kết quả thực tế: ' . $result
        );

        // Tag <div> và nội dung an toàn phải được giữ lại
        $this->assertStringContainsString('content', $result, 'Nội dung text hợp lệ bị xóa nhầm.');
    }

    /**
     * get_editor() phải loại bỏ @import dạng rải ký tự trong style attribute.
     *
     * `$search` trong filterAttr() normalize `@ i m p o r t` → `@import` trong $value,
     * sau đó blocking check phải loại bỏ attribute này.
     *
     * @group security
     */
    public function testGetEditorMustStripObfuscatedAtImportInStyleAttribute(): void
    {
        global $nv_Request;

        // Dạng rải ký tự bypass filter đơn giản kiểu str_contains
        $_POST['bodytext'] = '<div style="@ i m p o r t url(https://evil.com/x.css);">content</div>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        // Cả dạng rải ký tự lẫn dạng đã normalize đều không được có trong output
        $this->assertStringNotContainsStringIgnoringCase(
            '@ i m p o r t',
            $result,
            'Lỗi: @import obfuscated (dạng rải ký tự) vẫn còn trong style attribute. '
            . 'Kết quả thực tế: ' . $result
        );
        $this->assertStringNotContainsStringIgnoringCase(
            '@import',
            $result,
            'Lỗi: @import (sau normalize) vẫn còn trong style attribute. '
            . 'Kết quả thực tế: ' . $result
        );
    }
}
