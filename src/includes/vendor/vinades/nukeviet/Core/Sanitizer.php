<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Core;

/**
 * NukeViet\Core\Sanitizer
 *
 * @package NukeViet\Core
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class Sanitizer
{
    /**
     * Mã lệnh / hàm bị cấm xuất hiện
     */
    public const DISABLE_COMMANDS = [
        'base64_decode',
        'cmd',
        'passthru',
        'eval',
        'exec',
        'system',
        'fopen',
        'fsockopen',
        'file',
        'file_get_contents',
        'readfile',
        'unlink'
    ];

    /**
     * Các scheme được phép đứng đầu một giá trị URL.
     * Nguyên tắc allowlist: scheme đã biết thì cho phép, còn lại đều cấm.
     * Trong tương lai nếu có scheme mới thì thêm vào danh sách này.
     * Thiết kế này đảm bảo an toàn hơn.
     */
    public const SAFE_URL_SCHEMES = [
        'http',
        'https',
        'ftp',
        'ftps',
        'mailto',
        'tel',
        'sms',
        'callto',
        'skype',
        'viber',
        'zalo',
        'whatsapp',
        'tg',
        'data'
    ];

    /**
     * Ánh xạ dải C1 (0x80-0x9F) sang code point Unicode theo windows-1252.
     *
     * Chuẩn HTML5 yêu cầu trình duyệt áp dụng bảng này khi giải mã numeric character
     * reference nằm trong dải C1, nên canonicalize() phải làm theo cho khớp.
     */
    private const WINDOWS_1252_MAP = [
        0x20AC, 0x0081, 0x201A, 0x0192, 0x201E, 0x2026, 0x2020, 0x2021,
        0x02C6, 0x2030, 0x0160, 0x2039, 0x0152, 0x008D, 0x017D, 0x008F,
        0x0090, 0x2018, 0x2019, 0x201C, 0x201D, 0x2022, 0x2013, 0x2014,
        0x02DC, 0x2122, 0x0161, 0x203A, 0x0153, 0x009D, 0x017E, 0x0178
    ];

    /**
     * Bảng chuẩn hóa các từ khóa nguy hiểm
     */
    private const XSS_KEYWORDS = [
        'expression' => '/e\s*x\s*p\s*r\s*e\s*s\s*s\s*i\s*o\s*n/si',
        'javascript' => '/j\s*a\s*v\s*a\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'livescript' => '/l\s*i\s*v\s*e\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'behavior' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*r/si',
        'behaviour' => '/b\s*e\s*h\s*a\s*v\s*i\s*o\s*u\s*r/si',
        'vbscript' => '/v\s*b\s*s\s*c\s*r\s*i\s*p\s*t/si',
        'script' => '/s\s*c\s*r\s*i\s*p\s*t/si',
        'applet' => '/a\s*p\s*p\s*l\s*e\s*t/si',
        'alert' => '/a\s*l\s*e\s*r\s*t/si',
        'document' => '/d\s*o\s*c\s*u\s*m\s*e\s*n\s*t/si',
        'write' => '/w\s*r\s*i\s*t\s*e/si',
        'cookie' => '/c\s*o\s*o\s*k\s*i\s*e/si',
        'window' => '/w\s*i\s*n\s*d\s*o\s*w/si',
        'data:' => '/d\s*a\s*t\s*a\s*\:/si',
        '@import' => '/@\s*i\s*m\s*p\s*o\s*r\s*t/si'
    ];

    /**
     * Chuẩn hóa, lấy giá trị gốc các từ nguy hiểm
     *
     * @param string $value
     * @return string
     */
    public static function normalizeXssKeywords($value)
    {
        return preg_replace(array_values(self::XSS_KEYWORDS), array_keys(self::XSS_KEYWORDS), $value);
    }

    /**
     * Giải mã HTML entity đúng như trình duyệt làm khi parse tài liệu.
     *
     * Đây là hàm trả lời câu hỏi "trình duyệt sẽ đọc ra cái gì" nên chỉ giải mã một
     * lượt, theo đúng chuẩn HTML, và không đụng tới percent-encoding. Giá trị do hàm
     * này trả về an toàn để ghi ngược ra HTML hoặc trả về cho ứng dụng dùng.
     *
     * Không dùng giá trị này để phát hiện XSS, nếu cần phát hiện XSS hãy dùng deobfuscate()
     *
     * @param string $value
     * @return string
     */
    public static function canonicalize($value)
    {
        if (!is_string($value) or $value === '') {
            return (string) $value;
        }

        /*
         * html_entity_decode() bỏ qua numeric character reference thiếu dấu ";" trong khi
         * trình duyệt vẫn giải mã (kèm parse error). Bổ sung ";" để không còn phụ thuộc
         * vào một giới hạn số 0 đứng đầu cứng nào.
         */
        $value = preg_replace('/&#([xX])0*([0-9a-fA-F]{1,6})(?![0-9a-fA-F;])/', '&#$1$2;', $value);
        $value = preg_replace('/&#0*([0-9]{1,7})(?![0-9;])/', '&#$1;', $value);

        /*
         * Tự giải mã numeric character reference thay vì giao hết cho html_entity_decode():
         * hàm đó từ chối trả về một số ký tự điều khiển (điển hình là &#13; -> CR) trong khi
         * trình duyệt vẫn giải mã bình thường. Chênh lệch đó đủ để dựng "java&#13;script:".
         */
        $value = preg_replace_callback('/&#(?:([xX])([0-9a-fA-F]+)|([0-9]+));/', function ($m) {
            /*
             * So sánh ở dạng số thực trước khi ép về int: entity như "&#xFFFFFFFFFFFFFFFFFF;"
             * vượt xa PHP_INT_MAX, ép kiểu thẳng sẽ tràn về một giá trị hợp lệ ngẫu nhiên.
             */
            $code = ($m[1] !== '') ? hexdec($m[2]) : (float) $m[3];

            return self::codePointToUtf8($code);
        }, $value);

        // Named character reference thì html_entity_decode() xử lý đầy đủ và đúng chuẩn
        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Chuyển một code point Unicode thành chuỗi UTF-8.
     *
     * @param float|int $code
     * @return string
     */
    private static function codePointToUtf8($code)
    {
        /*
         * Các quy tắc HTML5 quy định cho trình duyệt khi giải mã numeric character reference,
         * phải làm theo thì giá trị trả về mới đúng là "thứ trình duyệt đọc được":
         * - NULL, code point vượt ngưỡng Unicode và vùng surrogate đều thành U+FFFD
         * - Dải C1 (0x80-0x9F) ánh xạ sang windows-1252 (VD: &#151; là dấu gạch ngang dài)
         */
        if ($code <= 0 or $code > 0x10FFFF or ($code >= 0xD800 and $code <= 0xDFFF)) {
            $code = 0xFFFD;
        } elseif ($code >= 0x80 and $code <= 0x9F) {
            $code = self::WINDOWS_1252_MAP[(int) $code - 0x80];
        }

        $code = (int) $code;

        if ($code < 0x80) {
            return chr($code);
        }
        if ($code < 0x800) {
            return chr(0xC0 | ($code >> 6)) . chr(0x80 | ($code & 0x3F));
        }
        if ($code < 0x10000) {
            return chr(0xE0 | ($code >> 12)) . chr(0x80 | (($code >> 6) & 0x3F)) . chr(0x80 | ($code & 0x3F));
        }

        return chr(0xF0 | ($code >> 18)) . chr(0x80 | (($code >> 12) & 0x3F)) . chr(0x80 | (($code >> 6) & 0x3F)) . chr(0x80 | ($code & 0x3F));
    }

    /**
     * Bóc mọi lớp che giấu để lộ ra ý đồ tấn công.
     *
     * Hàm này cố ý giải mã quá tay (percent-encoding, escape kiểu JS/CSS, ký tự điều
     * khiển, từ khóa bị giãn cách) vì với việc *phát hiện* thì giải mã thừa chỉ gây
     * chặn nhầm, còn giải mã thiếu là lỗ hổng.
     *
     * Giá trị do hàm này trả về chỉ dùng để ra quyết định chặn/không chặn.
     * Đừng xuất ra HTML hay trả về cho ứng dụng dùng vì nó đã bị giải mã quá tay,
     * không còn đúng thứ trình duyệt đọc được nữa. Nếu cần xuất ra HTML hay trả về cho
     * ứng dụng dùng thì hãy dùng canonicalize() thay vì deobfuscate().
     *
     * @param string $value
     * @return string
     */
    public static function deobfuscate($value)
    {
        if (!is_string($value) or $value === '') {
            return (string) $value;
        }

        // Cặp ký hiệu chú thích dùng để cắt vụn từ khóa
        $value = str_ireplace(['/*', '*/', '<!--', '-->'], '', $value);

        // Percent-encoding: quy về entity để canonicalize() giải mã tiếp
        $value = preg_replace('/%u0([a-z0-9]{3})/i', '&#x$1;', $value);
        $value = preg_replace('/%([a-z0-9]{2})/i', '&#x$1;', $value);

        // Giải mã entity - đầy đủ mọi named reference (&colon; &Tab; &NewLine; &sol; ...)
        $value = self::canonicalize($value);

        // Escape kiểu JavaScript/CSS
        $value = preg_replace_callback('/\\\\u00([0-9a-f]{2})|\\\\x([0-9a-f]{2})/i', function ($m) {
            return chr(hexdec(!empty($m[2]) ? $m[2] : $m[1]));
        }, $value);

        /*
         * Ký tự điều khiển thật, kể cả loại vừa được giải mã ra từ entity. Trình duyệt bỏ qua
         * chúng khi phân giải scheme của URL nên bộ lọc cũng phải bỏ qua.
         *
         * Gỡ luôn U+FFFD: canonicalize() sinh ra ký tự này từ "&#0;" theo đúng chuẩn HTML5,
         * nhưng ở bước phát hiện thì cứ coi như kẻ tấn công đang chèn ký tự vô nghĩa vào giữa
         * từ khóa. Thà chặn nhầm còn hơn phụ thuộc vào cách từng trình duyệt phân giải URL.
         *
         * Cố ý không dùng cờ /u: chỉ cần chuỗi vào có byte UTF-8 hỏng là preg_replace() trả về
         * null, giá trị dùng để kiểm tra biến mất và bộ lọc mở toang.
         */
        $value = preg_replace('/[\x00-\x1f\x7f]/', '', $value);
        $value = str_replace("\xEF\xBF\xBD", '', $value);

        // Gom các từ khóa nguy hiểm bị giãn cách ký tự
        return self::normalizeXssKeywords($value);
    }

    /**
     * Kiểm tra chuỗi có chứa scheme/CSS nguy hiểm ở bất kỳ vị trí nào không.
     *
     * Dùng cho những giá trị mà từ khóa nguy hiểm không nhất thiết đứng đầu mới có hại:
     * CSS trong attribute style (url(javascript:...), expression(...), behavior:, @import),
     * hay danh sách nhiều URL như srcset. Đây cũng là mặc định an toàn cho mọi attribute
     * chưa được phân loại.
     *
     * Với giá trị chắc chắn là một URL đơn lẻ thì dùng hasDangerousUrlScheme() để tránh
     * chặn nhầm từ khóa nằm trong path hoặc query.
     *
     * @param string $value
     * @return bool true nếu phát hiện nguy hiểm
     */
    public static function hasDangerousScheme($value)
    {
        return (bool) (preg_match('/(expression|javascript|behaviou?r|vbscript|mocha|livescript)(\:*)/', $value) or preg_match('/@import/i', $value));
    }

    /**
     * Kiểm tra scheme đứng đầu một giá trị URL có nằm ngoài allowlist không.
     *
     * Chỉ phần scheme mới quyết định trình duyệt có thực thi hay không, nên từ khóa nằm
     * trong path/query chỉ là văn bản: "/uploads/javascript-logo.png" hoàn toàn vô hại.
     *
     * Giá trị truyền vào phải là bản đã qua deobfuscate(). Điều đó bảo đảm tính đúng đắn
     * của phép kiểm tra: deobfuscate() giải mã nhiều hơn trình duyệt, nên nếu trình duyệt
     * dựng được một scheme nguy hiểm thì ở đây chắc chắn cũng nhìn thấy nó.
     *
     * @param string $value
     * @return bool true nếu phát hiện nguy hiểm
     */
    public static function hasDangerousUrlScheme($value)
    {
        /*
         * Trình duyệt bỏ khoảng trắng đầu chuỗi rồi mới phân giải scheme.
         *
         * Gỡ luôn dấu nháy đầu chuỗi: bước tách attribute có thể để sót cặp nháy bao ngoài
         * (VD: giá trị chứa ký tự xuống dòng thật), cho ra "'javascript:alert(1)'". Trình
         * duyệt không thực thi chuỗi đó vì dấu nháy làm scheme mất hiệu lực, nhưng không
         * nên đặt cược vào cách từng parser xử lý - cứ nhìn xuyên qua dấu nháy rồi chặn.
         */
        $value = ltrim((string) $value, " \t\n\r\0\x0B\"'");

        /*
         * Không khớp nghĩa là không có scheme (URL tương đối, neo trong trang, URL bắt đầu
         * bằng "//"). Những giá trị đó không tự thực thi được. Ký tự cho phép trong scheme
         * lấy đúng theo RFC 3986 - gặp ký tự khác là trình duyệt thôi coi đó là scheme.
         */
        if (!preg_match('/^([a-z][a-z0-9+.\-]*)\s*:/i', $value, $m)) {
            return false;
        }

        return !in_array(strtolower($m[1]), self::SAFE_URL_SCHEMES, true);
    }

    /**
     * Kiểm tra chuỗi có chứa lệnh, lời gọi tới hàm PHP bị cấm không.
     *
     * @param string $value
     * @return bool true nếu phát hiện lời gọi bị cấm
     */
    public static function hasDisabledCommand($value)
    {
        return !empty(self::DISABLE_COMMANDS) and (bool) preg_match('#(' . implode('|', self::DISABLE_COMMANDS) . ')(\s*)\((.*?)\)#si', $value);
    }

    /**
     * Kiểm tra một URL có an toàn hay không.
     *
     * Đầu vào theo thiết kế luôn là URL (xem nv_is_url()), nên xét scheme thay vì tìm
     * từ khóa ở mọi vị trí - bằng không "https://vi.wikipedia.org/wiki/JavaScript" cũng
     * bị coi là nguy hiểm.
     *
     * @param string $value
     * @return bool true nếu chuỗi an toàn
     */
    public static function xssValid($value)
    {
        $value = self::deobfuscate($value);

        if (self::hasDangerousUrlScheme($value)) {
            return false;
        }

        if (strcasecmp($value, strip_tags($value)) !== 0) {
            return false;
        }

        return !self::hasDisabledCommand($value);
    }
}
