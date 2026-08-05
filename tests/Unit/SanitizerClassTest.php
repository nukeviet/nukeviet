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

use NukeViet\Core\Sanitizer;
use Tests\Support\UnitTester;

/**
 * Kiểm thử class Sanitizer.
 *
 * @group security
 */
class SanitizerClassTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    /**
     * canonicalize() phải giải mã numeric character reference ở mọi dạng viết.
     *
     * Không phụ thuộc số lượng số 0 đứng đầu, hoa/thường, hex/thập phân, có hay
     * không có dấu ";" - đúng như trình duyệt.
     *
     * @group security
     */
    public function testCanonicalizeDecodesEveryNumericReferenceForm(): void
    {
        $forms = [
            'hex thường' => '&#x6a;',
            'hex hoa' => '&#X6A;',
            'hex 1 số 0' => '&#x06a;',
            'hex 9 số 0' => '&#x0000000006a;',
            'hex 20 số 0' => '&#x000000000000000000006a;',
            'hex thiếu dấu ;' => '&#x6a',
            'thập phân' => '&#106;',
            'thập phân 10 số 0' => '&#0000000000106;',
            'thập phân thiếu dấu ;' => '&#106'
        ];

        foreach ($forms as $label => $entity) {
            $this->assertSame(
                'j',
                Sanitizer::canonicalize($entity),
                'canonicalize() không giải mã được numeric reference dạng: ' . $label
            );
        }
    }

    /**
     * canonicalize() phải giải mã cả những ký tự điều khiển mà html_entity_decode() từ chối.
     *
     * html_entity_decode() của PHP trả nguyên "&#13;" thay vì ký tự CR, trong khi trình
     * duyệt vẫn giải mã. Chênh lệch đó đủ để dựng "java&#13;script:" nên phải tự xử lý.
     *
     * @group security
     */
    public function testCanonicalizeDecodesControlCharacterReferences(): void
    {
        $this->assertSame("java\rscript:", Sanitizer::canonicalize('java&#13;script:'), 'Không giải mã được &#13; (CR).');
        $this->assertSame("java\rscript:", Sanitizer::canonicalize('java&#x0d;script:'), 'Không giải mã được &#x0d; (CR).');
        $this->assertSame("java\tscript:", Sanitizer::canonicalize('java&#9;script:'), 'Không giải mã được &#9; (TAB).');
        $this->assertSame("java\nscript:", Sanitizer::canonicalize('java&#10;script:'), 'Không giải mã được &#10; (LF).');
    }

    /**
     * canonicalize() phải giải mã named character reference.
     *
     * @group security
     */
    public function testCanonicalizeDecodesNamedReferences(): void
    {
        $expected = [
            '&colon;' => ':',
            '&sol;' => '/',
            '&period;' => '.',
            '&lpar;' => '(',
            '&rpar;' => ')',
            '&Tab;' => "\t",
            '&NewLine;' => "\n",
            '&lt;' => '<',
            '&amp;' => '&',
            '&quot;' => '"',
            '&apos;' => "'"
        ];

        foreach ($expected as $entity => $char) {
            $this->assertSame(
                $char,
                Sanitizer::canonicalize($entity),
                'canonicalize() không giải mã được named reference: ' . $entity
            );
        }
    }

    /**
     * canonicalize() chỉ được giải mã đúng một lượt, giống trình duyệt.
     *
     * Giải mã chồng sẽ báo động giả với nội dung đã escape hợp lệ, và tệ hơn là
     * làm lệch giữa thứ được kiểm tra và thứ trình duyệt thực sự đọc.
     *
     * @group security
     */
    public function testCanonicalizeDecodesOnlyOnePass(): void
    {
        // Trình duyệt đọc ra chuỗi văn bản "&#x6a;avascript:", không phải scheme thực thi được
        $this->assertSame(
            '&#x6a;avascript:',
            Sanitizer::canonicalize('&amp;#x6a;avascript:'),
            'canonicalize() giải mã chồng nhiều lượt, không còn khớp với hành vi trình duyệt.'
        );
    }

    /**
     * canonicalize() không được đụng tới percent-encoding.
     *
     * Đây là điều kiện để giá trị trả về an toàn khi ghi ngược ra HTML hoặc trả về
     * cho ứng dụng: URL tiếng Việt và tham số chứa ":// " đã encode phải giữ nguyên.
     *
     * @group security
     */
    public function testCanonicalizeKeepsPercentEncodingIntact(): void
    {
        $values = [
            'Ph%E1%BA%A1m_Tu%E1%BA%A5n',
            'https%3A%2F%2Fnukeviet.vn',
            'giam gia 50%25',
            '/tim-kiem?q=a%20b'
        ];

        foreach ($values as $value) {
            $this->assertSame(
                $value,
                Sanitizer::canonicalize($value),
                'canonicalize() làm hỏng giá trị percent-encoded hợp lệ: ' . $value
            );
        }
    }

    /**
     * canonicalize() phải giữ nguyên chuỗi trông giống entity nhưng không phải entity.
     *
     * @group security
     */
    public function testCanonicalizeLeavesNonReferencesUntouched(): void
    {
        $this->assertSame('&#xZZ;', Sanitizer::canonicalize('&#xZZ;'), 'Chuỗi không phải entity bị biến dạng.');
        $this->assertSame('&#;', Sanitizer::canonicalize('&#;'), 'Chuỗi không phải entity bị biến dạng.');
        $this->assertSame('a & b', Sanitizer::canonicalize('a & b'), 'Dấu & đứng một mình bị biến dạng.');
    }

    /**
     * canonicalize() phải áp dụng các quy tắc riêng của HTML5 cho numeric reference.
     *
     * @group security
     */
    public function testCanonicalizeAppliesHtml5SpecialMappings(): void
    {
        $replacement = "\u{FFFD}";

        $this->assertSame($replacement, Sanitizer::canonicalize('&#0;'), 'NULL phải được đổi thành U+FFFD.');
        $this->assertSame($replacement, Sanitizer::canonicalize('&#999999999;'), 'Code point vượt ngưỡng Unicode phải thành U+FFFD.');
        $this->assertSame($replacement, Sanitizer::canonicalize('&#xD800;'), 'Vùng surrogate phải thành U+FFFD.');
        $this->assertSame('—', Sanitizer::canonicalize('&#151;'), 'Dải C1 phải ánh xạ theo windows-1252 (&#151; là dấu gạch ngang dài).');
        $this->assertSame('€', Sanitizer::canonicalize('&#128;'), 'Dải C1 phải ánh xạ theo windows-1252 (&#128; là ký hiệu euro).');
    }

    /**
     * Numeric reference khổng lồ không được tràn số thành một code point hợp lệ.
     *
     * hexdec() trả về float khi giá trị vượt PHP_INT_MAX; ép thẳng về int cho ra
     * một số hợp lệ ngẫu nhiên (VD: 0, tức là ký tự NULL) thay vì bị loại bỏ.
     *
     * @group security
     */
    public function testCanonicalizeHandlesOversizedCodePoints(): void
    {
        $replacement = "\u{FFFD}";

        $this->assertSame($replacement, Sanitizer::canonicalize('&#xFFFFFFFFFFFFFFFFFF;'), 'Entity hex khổng lồ bị tràn số.');
        $this->assertSame($replacement, Sanitizer::canonicalize('&#99999999999999999999;'), 'Entity thập phân khổng lồ bị tràn số.');
        $this->assertSame($replacement, Sanitizer::canonicalize('&#18446744073709551722;'), 'Entity thập phân quanh ngưỡng 64 bit bị tràn số.');
    }

    /**
     * deobfuscate() phải bóc được các lớp che giấu mà canonicalize() cố ý bỏ qua.
     *
     * @group security
     */
    public function testDeobfuscateStripsObfuscationLayers(): void
    {
        $payloads = [
            'percent-encoding' => 'javascript%3Aalert(1)',
            'percent-encoding chữ j' => '%6aavascript:alert(1)',
            'escape kiểu JS' => '\x6aavascript:alert(1)',
            'escape kiểu unicode' => 'javascript:alert(1)',
            'ký tự điều khiển thật' => "java\x00script:alert(1)",
            'entity ký tự điều khiển' => 'java&#13;script:alert(1)',
            'named entity' => 'javascript&colon;alert(1)',
            'giãn cách ký tự' => 'j a v a s c r i p t :alert(1)',
            // Chỉ gỡ dấu chú thích, không gỡ nội dung bên trong: "java/*x*/script" giữ nguyên chữ x
            'dấu chú thích rỗng' => 'java/**/script:alert(1)',
            'dấu chú thích HTML' => 'java<!---->script:alert(1)'
        ];

        foreach ($payloads as $label => $payload) {
            $this->assertMatchesRegularExpression(
                '/javascript\s*:/i',
                Sanitizer::deobfuscate($payload),
                'deobfuscate() không bóc được lớp che giấu: ' . $label
            );
        }
    }

    /**
     * xssValid() phải chặn đúng các biến thể trong báo cáo lỗ hổng.
     *
     * @group security
     */
    public function testXssValidRejectsEntityObfuscatedSchemes(): void
    {
        $payloads = [
            '&#x6a;avascript:alert(1)',
            '&#x000000006a;avascript:alert(1)',
            '&#x0000000006a;avascript:alert(1)',
            '&#0000000000106;avascript:alert(1)',
            'java&#13;script:alert(1)',
            'javascript&colon;alert(1)',
            'vbscript:msgbox(1)'
        ];

        foreach ($payloads as $payload) {
            $this->assertFalse(
                Sanitizer::xssValid($payload),
                'xssValid() cho qua payload nguy hiểm: ' . $payload
            );
        }
    }

    /**
     * URL hợp lệ không được xssValid() từ chối.
     *
     * @group security
     */
    public function testXssValidAcceptsSafeUrl(): void
    {
        $this->assertTrue(Sanitizer::xssValid('https://nukeviet.vn/vi/'), 'URL hợp lệ bị từ chối.');
        $this->assertTrue(Sanitizer::xssValid('/wiki/Ph%E1%BA%A1m_Tu%E1%BA%A5n'), 'URL percent-encoded hợp lệ bị từ chối.');

        // Từ khóa nằm trong path/query chỉ là văn bản, không phải scheme
        $this->assertTrue(Sanitizer::xssValid('https://vi.wikipedia.org/wiki/JavaScript'), 'URL có từ khóa trong path bị từ chối.');
        $this->assertTrue(Sanitizer::xssValid('https://site.vn/?q=vbscript'), 'URL có từ khóa trong query bị từ chối.');
    }

    /**
     * hasDangerousUrlScheme() chỉ xét scheme đứng đầu giá trị.
     *
     * @group security
     */
    public function testHasDangerousUrlSchemeOnlyLooksAtLeadingScheme(): void
    {
        $safe = [
            'URL tương đối' => '/uploads/javascript-logo.png',
            'query có từ khóa' => '/tim-kiem?q=javascript',
            'path có từ khóa' => 'https://vi.wikipedia.org/wiki/JavaScript',
            'neo trong trang' => '#javascript',
            'protocol-relative' => '//nukeviet.vn/x',
            'scheme lồng nhau' => 'https:javascript:alert(1)',
            'dấu : đứng đầu' => ':javascript:alert(1)'
        ];

        foreach ($safe as $label => $value) {
            $this->assertFalse(
                Sanitizer::hasDangerousUrlScheme($value),
                'Giá trị an toàn bị coi là nguy hiểm (' . $label . '): ' . $value
            );
        }

        $dangerous = [
            'javascript' => 'javascript:alert(1)',
            'có khoảng trắng đầu' => '   javascript:alert(1)',
            'dính dấu nháy đơn' => "'javascript:alert(1)'",
            'dính dấu nháy kép' => '"javascript:alert(1)"',
            'vbscript' => 'vbscript:msgbox(1)',
            'ngoài allowlist - view-source' => 'view-source:https://nukeviet.vn',
            'ngoài allowlist - jar' => 'jar:http://evil.vn/x.jar!/',
            'ngoài allowlist - file' => 'file:///etc/passwd',
            'ngoài allowlist - blob' => 'blob:https://evil.vn/1234',
            'ngoài allowlist - ws' => 'ws://evil.vn'
        ];

        foreach ($dangerous as $label => $value) {
            $this->assertTrue(
                Sanitizer::hasDangerousUrlScheme($value),
                'Scheme nguy hiểm hoặc ngoài allowlist không bị chặn (' . $label . '): ' . $value
            );
        }
    }

    /**
     * Các scheme trong allowlist phải được chấp nhận.
     *
     * @group security
     */
    public function testHasDangerousUrlSchemeAcceptsAllowlistedSchemes(): void
    {
        foreach (Sanitizer::SAFE_URL_SCHEMES as $scheme) {
            $this->assertFalse(
                Sanitizer::hasDangerousUrlScheme($scheme . ':gia-tri-bat-ky'),
                'Scheme nằm trong allowlist vẫn bị chặn: ' . $scheme
            );
        }
    }

    /**
     * Chuỗi UTF-8 hỏng không được làm bộ lọc mở toang.
     *
     * preg_replace() với cờ /u trả về null khi gặp byte UTF-8 không hợp lệ. Nếu giá trị
     * dùng để kiểm tra biến thành null thì mọi bước kiểm tra phía sau đều vô hiệu.
     *
     * @group security
     */
    public function testDecodersDoNotFailOpenOnInvalidUtf8(): void
    {
        $payload = "javascript:alert(1)\xC3\x28\xFF\xFE";

        $this->assertIsString(Sanitizer::canonicalize($payload), 'canonicalize() trả về null với chuỗi UTF-8 hỏng.');
        $this->assertIsString(Sanitizer::deobfuscate($payload), 'deobfuscate() trả về null với chuỗi UTF-8 hỏng.');
        $this->assertFalse(Sanitizer::xssValid($payload), 'Payload nguy hiểm kèm byte UTF-8 hỏng vẫn được cho qua.');
    }
}
