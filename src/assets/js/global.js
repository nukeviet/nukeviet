/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

var nukeviet = nukeviet || {};
var nv_base_siteurl, nv_assets_dir, nv_lang_data, nv_lang_interface, nv_name_variable, nv_fc_variable, nv_lang_variable, nv_module_name, nv_func_name, nv_is_user, nv_area_admin, nv_my_ofs, nv_my_dst, nv_my_abbr, nv_cookie_prefix, nv_check_pass_mstime, theme_responsive, nv_safemode;
var OP = -1 != navigator.userAgent.indexOf("Opera"),
    IE = -1 != navigator.userAgent.indexOf("MSIE") && !OP,
    GK = -1 != navigator.userAgent.indexOf("Gecko"),
    SA = -1 != navigator.userAgent.indexOf("Safari"),
    DOM = document.getElementById,
    NS4 = document.layers,
    nv_mailfilter = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/,
    nv_numcheck = /^([0-9])+$/,
    nv_namecheck = /^([a-zA-Z0-9_-])+$/,
    nv_uname_filter = /^([\p{L}\p{Mn}\p{Pd}'][\p{L}\p{Mn}\p{Pd}',\s]*)*$/u,
    nv_unicode_login_pattern = /^[\p{L}\p{Mn}0-9]+([\s]+[\p{L}\p{Mn}0-9]+)*$/u,
    nv_md5check = /^[a-z0-9]{32}$/,
    nv_imgexts = /^.+\.(jpg|gif|png|bmp)$/,
    nv_iChars = "!@#$%^&*()+=-[]\\';,./{}|\":<>?",
    nv_specialchars = /\$|,|@|#|~|`|\%|\*|\^|\&|\(|\)|\+|\=|\[|\-|\_|\]|\[|\}|\{|\;|\:|\'|\"|\<|\>|\?|\||\\|\!|\$|\./g,
    nv_old_Minute = -1,
    strHref = window.location.href,
    script_name,
    sn = strHref,
    query_string = "";

void 0 === nv_base_siteurl && (nv_base_siteurl = "/");
void 0 === nv_assets_dir && (nv_assets_dir = "assets");
void 0 === nv_lang_data && (nv_lang_data = "en");
void 0 === nv_lang_interface && (nv_lang_interface = "en");
void 0 === nv_name_variable && (nv_name_variable = "nv");
void 0 === nv_fc_variable && (nv_fc_variable = "op");
void 0 === nv_lang_variable && (nv_lang_variable = "language");
void 0 === nv_module_name && (nv_module_name = "");
void 0 === nv_func_name && (nv_func_name = "");
void 0 === nv_is_user && (nv_is_user = 0);
void 0 === nv_area_admin && (nv_area_admin = 0);
void 0 === nv_my_ofs && (nv_my_ofs = 7);
void 0 === nv_my_dst && (nv_my_dst = !1);
void 0 === nv_my_abbr && (nv_my_abbr = "ICT");
void 0 === nv_cookie_prefix && (nv_cookie_prefix = "nv4");
void 0 === nv_check_pass_mstime && (nv_check_pass_mstime = 1738E3);
void 0 === theme_responsive && (theme_responsive = 0);
void 0 === nv_safemode && (nv_safemode = 0);

-
1 < strHref.indexOf("?") && ([sn, query_string] = strHref.split("?"));

'undefined' == typeof script_name && (script_name = sn);

function nv_email_check(field_id) {
    return 7 <= field_id.value.length && nv_mailfilter.test(field_id.value)
}

function nv_num_check(field_id) {
    return 1 <= field_id.value.length && nv_numcheck.test(field_id.value)
}

function nv_name_check(field_id) {
    return "" != field_id.value && nv_namecheck.test(field_id.value)
}

function nv_md5_check(field_id) {
    return nv_md5check.test(field_id.value)
}

function nv_iChars_check(field_id) {
    for (var a = 0; a < field_id.value.length; a++)
        if (-1 != nv_iChars.indexOf(field_id.value.charAt(a))) return !0;
    return !1
}

function nv_iChars_Remove(str) {
    return str.replace(nv_specialchars, "");
}

function nv_setCookie(name, value, expiredays, secure, SameSite) {
    var a;
    expiredays = expiredays ? ((a = new Date).setDate(a.getDate() + expiredays), "; expires=" + a.toGMTString()) : "";
    secure = void 0 !== secure && secure ? "; secure" : "";
    SameSite = void 0 !== SameSite && ("Lax" == SameSite || "Strict" == SameSite || "None" == SameSite && "" != secure) ? "; SameSite=" + SameSite : "";
    document.cookie = name + "=" + encodeURIComponent(value) + expiredays + "; path=" + nv_base_siteurl + SameSite + secure
}

function nv_getCookie(name) {
    var cookie = " " + document.cookie,
        search = " " + name + "=",
        offset = 0,
        end = 0;
    return 0 < cookie.length && (offset = cookie.indexOf(search), -1 != offset) ? (offset += search.length, end = cookie.indexOf(";", offset), -1 == end && (end = cookie.length), decodeURIComponent(cookie.substring(offset, end))) : null
}

function nv_check_timezone() {
    var domainName = location.hostname,
        cookieName = nv_cookie_prefix + '_cltz',
        currentTime = new Date(),
        summerTime = new Date(Date.UTC(2005, 6, 30, 0, 0, 0, 0)),
        winterTime = new Date(Date.UTC(2005, 12, 30, 0, 0, 0, 0)),
        cookieVal;
    domainName = domainName.replace(/www\./g, "");
    domainName = /^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,}$/i.test(domainName) ? "." + domainName : "";
    cookieVal = -summerTime.getTimezoneOffset() + "." + -winterTime.getTimezoneOffset() + "." + -currentTime.getTimezoneOffset() + "|" + nv_base_siteurl + "|" + domainName;
    rawurldecode(nv_getCookie(cookieName)) != cookieVal && nv_setCookie(cookieName, rawurlencode(cookieVal), 365, "https:" === location.protocol ? !0 : !1, "Strict")
}

function is_array(mixed_var) {
    return (mixed_var instanceof Array);
}

function strip_tags(str, allowed_tags) {
    var key = '',
        allowed = false,
        matches = [],
        allowed_array = [],
        allowed_tag = '',
        i = 0,
        k = '',
        html = '';
    var replacer = function(search, replace, str) {
        return str.split(search).join(replace);
    };
    // Build allowes tags associative array
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }

    str += '';

    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);

    // Go through all HTML tags
    for (key in matches) {
        if (isNaN(key)) {
            // IE7 Hack
            continue;
        }

        // Save HTML tag
        html = matches[key].toString();

        // Is tag not in allowed list ? Remove from str !
        allowed = false;

        // Go through all allowed tags
        for (k in allowed_array) {
            // Init
            allowed_tag = allowed_array[k];
            i = -1;

            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('</' + allowed_tag);
            }

            // Determine
            if (i == 0) {
                allowed = true;
                break;
            }
        }

        if (!allowed) {
            str = replacer(html, "", str);
            // Custom replace. No regexing
        }
    }

    return str;
}

// trim(' Kevin van Zonneveld ');
function trim(str, charlist) {
    var whitespace,
        l = 0,
        i = 0;

    str += '';

    if (!charlist) {
        whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    } else {
        charlist += '';
        whitespace = charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '$1');
    }

    l = str.length;
    for (i = 0; i < l; i++) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(i);
            break;
        }
    }

    l = str.length;
    for (i = l - 1; i >= 0; i--) {
        if (whitespace.indexOf(str.charAt(i)) === -1) {
            str = str.substring(0, i + 1);
            break;
        }
    }

    return whitespace.indexOf(str.charAt(0)) === -1 ? str : '';
}

// rawurlencode('Kevin van Zonneveld!'); = > 'Kevin%20van%20Zonneveld%21'
function rawurlencode(str) {
    str = (str + '').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A');
}

// rawurldecode('Kevin+van+Zonneveld%21'); = > 'Kevin+van+Zonneveld!'
function rawurldecode(str) {
    return decodeURIComponent(str);
}

function is_numeric(mixed_var) {
    return !isNaN(mixed_var);
}

function intval(mixed_var, base) {
    let type = typeof(mixed_var);

    if (type === 'boolean') {
        return (mixed_var) ? 1 : 0;
    } else if (type === 'string') {
        let tmp = parseInt(mixed_var, base || 10);
        return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;
    } else if (type === 'number' && isFinite(mixed_var)) {
        return Math.floor(mixed_var);
    } else {
        return 0;
    }
}

/**
 * This function is same as PHP's nl2br() with default parameters.
 *
 * @param {string} str Input text
 * @param {boolean} replaceMode Use replace instead of insert
 * @param {boolean} isXhtml Use XHTML
 * @return {string} Filtered text
 */
function nl2br(str, replaceMode, isXhtml) {
    var breakTag = (isXhtml) ? '<br />' : '<br>';
    var replaceStr = (replaceMode) ? '$1' + breakTag : '$1' + breakTag + '$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
}

/**
 * This function inverses text from PHP's nl2br() with default parameters.
 *
 * @param {string} str Input text
 * @param {boolean} replaceMode Use replace instead of insert
 * @return {string} Filtered text
 */
function br2nl(str, replaceMode) {
    var replaceStr = (replaceMode) ? "\n" : '';
    // Includes <br>, <BR>, <br />, </br>
    return (str + '').replace(/<\s*\/?br\s*[\/]?>/gi, replaceStr);
}

function nv_is_dst() {
    var now = new Date();
    var dst_start = new Date();
    var dst_end = new Date();
    // Set dst start to 2AM 2nd Sunday of March
    dst_start.setMonth(2);
    // March
    dst_start.setDate(1);
    // 1st
    dst_start.setHours(2);
    dst_start.setMinutes(0);
    dst_start.setSeconds(0);
    // 2AM
    // Need to be on first Sunday
    if (dst_start.getDay())
        dst_start.setDate(dst_start.getDate() + (7 - dst_start.getDay()));
    // Set to second Sunday
    dst_start.setDate(dst_start.getDate() + 7);
    // Set dst end to 2AM 1st Sunday of November
    dst_end.setMonth(10);
    dst_end.setDate(1);
    dst_end.setHours(2);
    dst_end.setMinutes(0);
    dst_end.setSeconds(0);
    // 2AM
    // Need to be on first Sunday
    if (dst_end.getDay())
        dst_end.setDate(dst_end.getDate() + (7 - dst_end.getDay()));
    return (now > dst_start && now < dst_end);
}

function nv_DigitalClock(div_id) {
    if (document.getElementById(div_id)) {
        nv_my_dst && nv_is_dst() && (nv_my_ofs += 1);

        var newDate = new Date();
        newDate.setHours(newDate.getHours() + (newDate.getTimezoneOffset() / 60) + nv_my_ofs);

        var intMinutes = newDate.getMinutes(),
            intSeconds = newDate.getSeconds();

        if (intMinutes != nv_old_Minute) {
            nv_old_Minute = intMinutes;
            var intHours = newDate.getHours(),
                intDay = newDate.getDay(),
                intMonth = newDate.getMonth(),
                intWeekday = newDate.getDate(),
                intYear = newDate.getYear();

            200 > intYear && (intYear += 1900);
            var strDayName = new String(nv_aryDayName[intDay]);
            var strMonthNumber = intMonth + 1;
            9 >= intHours && (intHours = "0" + intHours);
            9 >= intMinutes && (intMinutes = "0" + intMinutes);
            9 >= strMonthNumber && (strMonthNumber = "0" + strMonthNumber);
            9 >= intWeekday && (intWeekday = "0" + intWeekday);

            document.getElementById(div_id).innerHTML = intHours + ':' + intMinutes + ' ' + nv_my_abbr + ' &nbsp; ' + strDayName + ', ' + intWeekday + '/' + strMonthNumber + '/' + intYear;
        }
        setTimeout('nv_DigitalClock("' + div_id + '")', (60 - intSeconds) * 1000);
    }
}

function nv_checkAll(oForm, cbName, caName, check_value) {
    if (typeof oForm == 'undefined' || typeof oForm[cbName] == 'undefined') {
        return false;
    }
    if (oForm[cbName].length) {
        for (var i = 0; i < oForm[cbName].length; i++) {
            oForm[cbName][i].checked = check_value;
        }
    } else {
        oForm[cbName].checked = check_value;
    }

    if (oForm[caName].length) {
        for (var j = 0; j < oForm[caName].length; j++) {
            oForm[caName][j].checked = check_value;
        }
    } else {
        oForm[caName].checked = check_value;
    }
}

function nv_UncheckAll(oForm, cbName, caName, check_value) {
    if (typeof oForm == 'undefined' || typeof oForm[cbName] == 'undefined') {
        return false;
    }
    var ts = 0;

    if (oForm[cbName].length) {
        for (var i = 0; i < oForm[cbName].length; i++) {
            if (oForm[cbName][i].checked != check_value) {
                ts = 1;
                break;
            }
        }
    }

    var chck = false;
    if (ts == 0) {
        chck = check_value;
    }

    if (oForm[caName].length) {
        for (var j = 0; j < oForm[caName].length; j++) {
            oForm[caName][j].checked = chck;
        }
    } else {
        oForm[caName].checked = chck;
    }
}

function nv_set_disable_false(sid) {
    if (document.getElementById(sid)) {
        var sd = document.getElementById(sid);
        sd.disabled = false;
    }

}

function nv_settimeout_disable(sid, tm) {
    var sd = document.getElementById(sid);
    sd.disabled = true;
    var nv_timer = setTimeout('nv_set_disable_false("' + sid + '")', tm);
    return nv_timer;
}

function nv_randomPassword(plength) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    var pass = "";
    for (var z = 0; z < plength; z++) {
        pass += chars.charAt(Math.floor(Math.random() * 62));
    }
    return pass;
}

function nv_urldecode_ajax(my_url, containerid) {
    my_url = rawurldecode(my_url);
    $("#" + containerid).load(my_url);
    return;
}

function nv_isExternal(url) {
    var host = window.location.hostname.toLowerCase(),
        regex = new RegExp('^(?:(?:f|ht)tp(?:s)?\:)?//(?:[^\@]+\@)?([^:/]+)', 'im'),
        match = url.match(regex),
        domain = ((match ? match[1].toString() : ((url.indexOf(':') < 0) ? host : ''))).toLowerCase();
    return domain != host
}

function nv_open_browse(theURL, winName, w, h, features) {
    w = Math.min(w, (screen.availWidth ? screen.availWidth : screen.width) - 64);
    h = Math.min(h, (screen.availHeight ? screen.availHeight : screen.height) - 64);
    var LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0,
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0,
        settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition;
    if (features != '') {
        settings += ',' + features;
    }
    window.open(theURL, winName, settings).focus()
}

function nv_setIframeHeight(iframeId) {
    var ifDoc, ifRef = document.getElementById(iframeId);
    try {
        ifDoc = ifRef.contentWindow.document.documentElement;
    } catch (e) {
        try {
            ifDoc = ifRef.contentDocument.documentElement;
        } catch (ee) {}
    }
    if (ifDoc) {
        ifRef.height = 1;
        ifRef.height = ifDoc.scrollHeight;
    }
}

/**
 * @param {string} base64url - The Base64 URL encoded string to convert.
 * @returns {ArrayBuffer} The resulting ArrayBuffer.
 */
function base64UrlToArrayBuffer(base64url) {
    const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
    const padding = base64.length % 4;
    if (padding) {
        base64.padEnd(base64.length + (4 - padding), '=');
    }
    const binaryString = atob(base64);
    const buffer = new ArrayBuffer(binaryString.length);
    const view = new Uint8Array(buffer);
    for (let i = 0; i < binaryString.length; i++) {
        view[i] = binaryString.charCodeAt(i);
    }
    return buffer;
}

/**
 * @param {ArrayBuffer} buffer - The ArrayBuffer to be converted.
 * @returns {string} The Base64 URL-safe encoded string.
 */
function arrayBufferToBase64Url(buffer) {
    return btoa(String.fromCharCode.apply(null, new Uint8Array(buffer)))
        .replace(/\+/g, "-")
        .replace(/\//g, "_")
        .replace(/=+$/, ""); // Loại bỏ padding
}

// Load multiliple js,css files
function getFiles(files, callback) {
    var progress = 0;
    files.forEach(function(fileurl) {
        var dtype = fileurl.substring(fileurl.lastIndexOf('.') + 1) == 'js' ? 'script' : 'text',
            attrs = "undefined" !== typeof site_nonce ? {
                'nonce': site_nonce
            } : {};
        $.ajax({
            url: fileurl,
            cache: true,
            dataType: dtype,
            scriptAttrs: attrs,
            success: function() {
                if (dtype == 'text') {
                    $("<link/>", {
                        rel: "stylesheet",
                        href: fileurl
                    }).appendTo("head")
                }
                if (++progress == files.length) {
                    if ("function" === typeof callback) {
                        callback()
                    }
                }
            }
        })
    })
}

/**
 * Tương đương html_entity_decode
 *
 * @param {String} html
 * @returns {String}
 */
function htmlEntityDecode(html) {
    const doc = new DOMParser().parseFromString(html, "text/html");
    return doc.documentElement.textContent;
}

/**
 * Kiểm tra xem trình duyệt có phải là In-App Browser hay không
 * Kiểm tra một cách tương đối
 *
 * @returns {Boolean}
 */
function isInAppBrowser() {
    const ua = navigator.userAgent.toLowerCase();
    const knownInAppIndicators = [
        "fbav", // Facebook
        "instagram",
        "line",
        "zalo",
        "tiktok",
        "messenger"
    ];

    // Kiểm tra các chuỗi đặc trưng trong UA
    const matchesKnownApp = knownInAppIndicators.some(indicator => ua.includes(indicator));

    // Kiểm tra Android WebView
    const isAndroidWebView =
    ua.includes("android") &&
    (ua.includes("wv") || ua.includes("version/") && ua.includes("chrome/") && ua.includes("safari/"));

    // Kiểm tra iOS WebView (không có từ "safari")
    const isIOSWebView =
    /iphone|ipad|ipod/.test(ua) &&
    !ua.includes("safari") &&
    !ua.includes("crios"); // loại trừ Chrome iOS

    // Một số WebView chặn window.open
    const blocksWindowOpen = typeof window.open !== "function";

    // Tổ hợp các yếu tố
    return matchesKnownApp || isAndroidWebView || isIOSWebView || blocksWindowOpen;
}

/**
 * Kiểm tra xem thiết bị có phải là Apple hay không
 * @returns {Boolean}
 */
function isAppleDevice() {
    try {
        const ua = typeof navigator !== 'undefined' ? (navigator.userAgent || '') : '';
        if (/iPhone|iPad|iPod/.test(ua)) return true;

        if (/Macintosh/.test(ua) && typeof navigator !== 'undefined' && navigator.maxTouchPoints > 1) {
            return true;
        }

        if (/Macintosh/.test(ua)) return true;

        return false;
    } catch (e) {
        return false;
    }
}

nv_check_timezone();

nukeviet.WebAuthnSupported = 'PublicKeyCredential' in window && 'credentials' in navigator && 'create' in navigator.credentials && 'get' in navigator.credentials;
nukeviet.getScrollbarWidth = () => {
    const outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.overflow = 'scroll';
    outer.style.msOverflowStyle = 'scrollbar';
    outer.style.position = 'fixed';
    document.body.appendChild(outer);

    const inner = document.createElement('div');
    outer.appendChild(inner);

    const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;

    outer.parentNode.removeChild(outer);

    return scrollbarWidth;
};
nukeviet.cr = {};
nukeviet.turnstileIDs = [];
nukeviet.reCapIDs = [];

// Ap dung trinh nghe thu dong cho touchstart
// https://web.dev/uses-passive-event-listeners/?utm_source=lighthouse&utm_medium=devtools
jQuery.event.special.touchstart = {
    setup: function(_, ns, handle) {
        this.addEventListener('touchstart', handle, {
            passive: !ns.includes('noPreventDefault')
        });
    }
};

/**
 * Tìm modal Bootstrap đang hiển thị trên cùng
 *
 * @returns
 */
function _getTopBsModal() {
    let modals = document.querySelectorAll('.modal');
    let topModal = null;
    let maxZIndex = 0;

    modals.forEach(modal => {
        if (window.getComputedStyle(modal).display === 'block') {
            let zIndex = parseInt(window.getComputedStyle(modal).zIndex, 10);
            if (zIndex > maxZIndex) {
                maxZIndex = zIndex;
                topModal = modal;
            }
        }
    });

    return topModal;
}

/**
 * Tắt lệnh ép focus vào modal Bootstrap đang mở. Cho phép focus vào các phần tử khác
 *
 * @param {String} forwhat - Từ cái gì
 * @returns
 */
function _offBsenforceFocus(forwhat) {
    if (
        (!window.jQuery || !$ || !$.fn || !($.fn.modal && $.fn.modal.Constructor && $.fn.modal.Constructor.VERSION)) ||
        (forwhat === 'cap' && document.body.classList.contains('cr-md-open')) ||
        (forwhat === 'md' && document.body.classList.contains('cr-cap-open'))
    ) {
        return;
    }
    let topModal = _getTopBsModal();
    if (topModal) {
        const bsVersion = parseInt($.fn.modal.Constructor.VERSION.substring(0, 1));
        if (bsVersion > 4) {
            const md = bootstrap.Modal.getInstance(topModal);
            md._focustrap.deactivate();
        } else {
            $(document).off('focusin.bs.modal');
        }
    }
}

/**
 * Bật lại lệnh ép focus vào modal Bootstrap đang mở
 *
 * @param {String} forwhat - Từ cái gì
 * @returns
 */
function _onBsenforceFocus(forwhat) {
    let topModal = _getTopBsModal();
    if (
        !topModal ||
        (!window.jQuery || !$ || !$.fn || !($.fn.modal && $.fn.modal.Constructor && $.fn.modal.Constructor.VERSION)) ||
        (forwhat === 'cap' && document.body.classList.contains('cr-md-open')) ||
        (forwhat === 'md' && document.body.classList.contains('cr-cap-open'))
    ) {
        return;
    }
    const bsVersion = parseInt($.fn.modal.Constructor.VERSION.substring(0, 1));
    if (bsVersion > 4) {
        const md = bootstrap.Modal.getInstance(topModal);
        md._focustrap.activate();
    } else {
        const md = $(topModal).data('bs.modal');
        md.enforceFocus();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Core default modal
    if (!document.getElementById('sitemodal')) {
        const body = document.body;
        const modal = document.createElement('div');
        modal.id = 'sitemodal';
        modal.classList.add('cr-md', 'cr-fade');
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-labelledby', 'sitemodalLabel');
        modal.setAttribute('aria-hidden', 'true');
        modal.innerHTML = `<div class="cr-md-dialog">
            <div class="cr-md-content">
                <div class="cr-md-header">
                    <div class="cr-md-title" id="sitemodalLabel">&nbsp;</div>
                    <button type="button" class="cr-btn-close" data-cr-dismiss="modal" aria-label="${nukeviet.i18n.close}"></button>
                </div>
                <div class="cr-md-body"></div>
            </div>
        </div>`;
        body.appendChild(modal);
        nukeviet.cr.mdDb = {
            overflow: null,
            paddingRight: null,
            scroll: null,
            cb: null
        };

        // Đóng modal
        modal.querySelector('[data-cr-dismiss="modal"]').addEventListener('click', (e) => {
            e.preventDefault();

            const body = document.body;
            const modal = document.getElementById('sitemodal');
            const backdrop = document.querySelector('.cr-md-backdrop');

            body.classList.remove('cr-md-open');
            body.style.overflow = nukeviet.cr.mdDb.overflow;
            body.style.paddingRight = nukeviet.cr.mdDb.paddingRight;
            if (body.getAttribute('style') === '') {
                body.removeAttribute('style');
            }
            if (body.getAttribute('class') === '') {
                body.removeAttribute('class');
            }

            modal.classList.remove('cr-show');
            backdrop.classList.remove('cr-show');
            setTimeout(() => {
                modal.style.display = 'none';
                modal.removeAttribute('aria-modal');
                modal.setAttribute('aria-hidden', 'true');
                modal.querySelector('.cr-md-title').innerHTML = '';
                modal.querySelector('.cr-md-body').innerHTML = '';
                body.removeChild(backdrop);
                _onBsenforceFocus('md');
                if (nukeviet.cr.mdDb.cb) {
                    nukeviet.cr.mdDb.cb(modal);
                }
                nukeviet.cr.mdDb.cb = null;
            }, 150);
        });

        // Click ngoài modal
        modal.addEventListener('click', (event) => {
            const content = modal.querySelector('.cr-md-content');
            if (!modal.classList.contains('cr-md-static') && !content.contains(event.target) && !event.target.closest('.cr-md-content')) {
                modal.classList.add('cr-md-static');
                setTimeout(() => {
                    modal.classList.remove('cr-md-static');
                }, 310);
            }
        });

        // ESC để đóng modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.body.classList.contains('cr-md-open')) {
                const modal = document.getElementById('sitemodal');
                modal.querySelector('[data-cr-dismiss="modal"]').click();
            }
        });

        // Mở modal
        window.modalShow = (title, content, callback, closeCallback) => {
            const body = document.body;
            const modal = document.getElementById('sitemodal');
            if (body.classList.contains('cr-md-open')) {
                return;
            }

            const backdrop = document.createElement('div');
            backdrop.classList.add('cr-md-backdrop', 'cr-fade');
            body.append(backdrop);

            modal.querySelector('.cr-md-title').innerHTML = title;

            const mdBody = modal.querySelector('.cr-md-body');
            mdBody.innerHTML = content;
            mdBody.querySelectorAll('script').forEach(oldScript => {
                const newScript = document.createElement('script');
                if (oldScript.src) {
                    newScript.src = oldScript.src;
                } else {
                    newScript.textContent = oldScript.textContent;
                }
                oldScript.replaceWith(newScript);
            });

            modal.removeAttribute('aria-hidden');
            modal.setAttribute('aria-modal', 'true');
            modal.style.display = 'block';

            nukeviet.cr.mdDb.overflow = body.style.overflow;
            nukeviet.cr.mdDb.paddingRight = body.style.paddingRight;
            nukeviet.cr.mdDb.scroll = document.documentElement.scrollHeight > window.innerHeight;

            // Tạo độ trễ cho các transition
            setTimeout(() => {
                modal.classList.add('cr-show');
                backdrop.classList.add('cr-show');

                body.style.overflow = 'hidden';
                nukeviet.cr.mdDb.scroll && (body.style.paddingRight = nukeviet.getScrollbarWidth() + 'px');
                body.classList.add('cr-md-open');
            }, 1);
            setTimeout(() => {
                _offBsenforceFocus('md');
                if (typeof callback === 'function') {
                    callback(modal);
                }
                if (typeof closeCallback === 'function') {
                    nukeviet.cr.mdDb.cb = closeCallback;
                } else if (callback === 'recaptchareset') {
                    loadCaptcha(modal);
                }
            }, 160);
        };

        // Hàm đóng modal
        window.modalHide = () => {
            if (!document.body.classList.contains('cr-md-open')) {
                return;
            }
            const modal = document.getElementById('sitemodal');
            modal.querySelector('[data-cr-dismiss="modal"]').click();
        };
    }

    if (typeof modalShowByObj === 'undefined') {
        window.modalShowByObj = (obj, callback) => {
            if (!(obj instanceof Element)) {
                obj = document.querySelector(obj);
            }
            if (!obj) {
                return;
            }
            modalShow(obj.getAttribute('title'), obj.innerHTML, callback);
        };
    }

    // Alertbox + Confirm box
    if (typeof nukeviet.confirm !== 'function') {
        nukeviet.confirm = (message, cbConfirm, cbCancel, cancelBtn) => {
            const body = document.body;
            if (body.classList.contains('cr-alert-open')) {
                return;
            }
            body.classList.add('cr-alert-open');
            if (typeof cancelBtn == 'undefined') {
                cancelBtn = true;
            }
            if (typeof cbConfirm == 'undefined') {
                cbConfirm = () => {};
            }
            if (typeof cbCancel == 'undefined') {
                cbCancel = () => {};
            }

            const id = 'alert-' + nv_randomPassword(8);

            // Đối tượng box
            const box = document.createElement('div');
            box.id = id;
            box.classList.add('cr-alert', 'cr-fade');
            box.setAttribute('tabindex', '-1');
            box.setAttribute('aria-labelledby', `${id}-body`);
            box.setAttribute('aria-hidden', 'true');
            box.innerHTML = `<div class="cr-alert-dialog cr-alert-dialog-scrollable">
                <div class="cr-alert-content">
                    <div class="cr-alert-body" id="${id}-body"></div>
                    <div class="cr-alert-footer" id="${id}-footer">
                        <button type="button" class="cr-btn cr-btn-icon cr-btn-primary" id="${id}-confirm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="cr-svg-icon" viewBox="0 0 16 16">
                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425z"/>
                            </svg> ` + nv_confirm + `
                        </button>
                        ` + (cancelBtn ? `<button type="button" class="cr-btn cr-btn-icon cr-btn-secondary" id="${id}-close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="cr-svg-icon" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg> ` + nv_close + `
                        </button>` : '') + `
                    </div>
                </div>
            </div>`;
            const boxBody = box.querySelector('.cr-alert-body');
            if (typeof message == 'object' && message.html) {
                boxBody.innerHTML = message.message;
            } else {
                boxBody.textContent = htmlEntityDecode(message);
            }
            // Click ngoài alert box
            box.addEventListener('click', (event) => {
                const content = box.querySelector('.cr-alert-content');
                if (!box.classList.contains('cr-alert-static') && !content.contains(event.target) && !event.target.closest('.cr-alert-content')) {
                    box.classList.add('cr-alert-static');
                    setTimeout(() => {
                        box.classList.remove('cr-alert-static');
                    }, 310);
                }
            });

            // Đối tượng backdrop
            const backdrop = document.createElement('div');
            backdrop.classList.add('cr-alert-backdrop', 'cr-fade');

            body.append(box, backdrop);

            box.removeAttribute('aria-hidden');
            box.setAttribute('aria-modal', 'true');
            box.style.display = 'block';

            const cOverflow = body.style.overflow;
            const cPaddingRight = body.style.paddingRight;
            const cVScroll = document.documentElement.scrollHeight > window.innerHeight;

            setTimeout(() => {
                box.classList.add('cr-show');
                backdrop.classList.add('cr-show');

                body.style.overflow = 'hidden';
                cVScroll && (body.style.paddingRight = nukeviet.getScrollbarWidth() + 'px');
            }, 1);

            // Xử lý nút ấn
            const close = (event) => {
                ([...box.querySelectorAll('button')].map(ele => ele.setAttribute('disabled', 'disabled')));

                body.style.overflow = cOverflow;
                body.style.paddingRight = cPaddingRight;
                if (body.getAttribute('style') === '') {
                    body.removeAttribute('style');
                }
                if (body.getAttribute('class') === '') {
                    body.removeAttribute('class');
                }

                box.classList.remove('cr-show');
                backdrop.classList.remove('cr-show');
                setTimeout(() => {
                    box.style.display = 'none';
                    body.removeChild(box);
                    body.removeChild(backdrop);
                    body.classList.remove('cr-alert-open');
                    if (event == 'confirm') {
                        cbConfirm();
                    } else if (event == 'cancel') {
                        cbCancel();
                    }
                }, 150);
            }
            if (cancelBtn) {
                document.getElementById(id + '-close').addEventListener('click', () => {
                    close('cancel');
                });
            }
            document.getElementById(id + '-confirm').addEventListener('click', () => {
                close('confirm');
            });
        };

        // ESC để đóng alert box
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && document.body.classList.contains('cr-alert-open')) {
                const al = document.querySelector('.cr-alert');
                if (al) {
                    const btnClose = al.querySelector('.cr-btn-secondary');
                    if (btnClose) {
                        btnClose.click();
                        return;
                    }
                    const btnConfirm = al.querySelector('.cr-btn-primary');
                    if (btnConfirm) {
                        btnConfirm.click();
                    }
                }
            }
        });

        // Enter để confirm
        document.addEventListener('keyup', function(event) {
            if (event.key === 'Enter' && document.body.classList.contains('cr-alert-open')) {
                const btnConfirm = document.querySelector('.cr-alert .cr-btn-primary');
                if (btnConfirm) {
                    btnConfirm.click();
                }
            }
        });
    }

    if (typeof nukeviet.alert !== 'function') {
        nukeviet.alert = (message, callback) => {
            if (typeof callback == 'undefined') {
                callback = () => {};
            }
            nukeviet.confirm(message, callback, () => {}, false);
        };
    }

    if (typeof nvConfirm !== 'function') {
        window.nvConfirm = nukeviet.confirm;
    }

    if (typeof nvAlert !== 'function') {
        window.nvAlert = nukeviet.alert;
    }

    // Toast
    if (!document.getElementById('site-toasts')) {
        nukeviet.cr.toast = {
            hasKbInter: false,
            hasMInter: false
        };

        /**
         * Xử lý hành động hover và di chuột ra khỏi toast
         *
         * @param {HTMLElement} item
         * @param {Event} event
         */
        const _ToastInteraction = (item, event) => {
            const isInter = (event.type === 'mouseover' || event.type === 'focusin') ? true : false;
            const toasts = document.getElementById('site-toasts');

            switch (event.type) {
                case 'mouseover':
                case 'mouseout': {
                    nukeviet.cr.toast.hasMInter = isInter
                    break
                }

                case 'focusin':
                case 'focusout': {
                    nukeviet.cr.toast.hasKbInter = isInter
                    break
                }

                default: {
                    break
                }
            }

            if (isInter) {
                toasts.querySelectorAll('.cr-toast').forEach(item => {
                    if (item.dataset._timeout) {
                        clearTimeout(item.dataset._timeout);
                    }
                });
                return
            }

            const nextElement = event.relatedTarget
            if (item === nextElement || item.contains(nextElement)) {
                return;
            }

            toasts.querySelectorAll('.cr-toast').forEach(item => {
                item.dataset._timeout = setTimeout(() => {
                    _ToastTimeout(item);
                }, 5000);
            });
        };

        /**
         * Ẩn toast tự động
         *
         * @param {HTMLElement} item
         */
        const _ToastTimeout = (item) => {
            _ToastHide(item);
        };

        const _ToastHide = (item) => {
            item.classList.add('cr-showing');
            setTimeout(() => {
                item.remove();

                let toasts = document.getElementById('site-toasts');
                if (!toasts.querySelector('.cr-toast')) {
                    toasts.classList.add('cr-d-none');
                }
            }, 151);
        };

        /**
         *
         * @param {String | Object} text
         * @param {'secondary' | 'error' | 'danger' | 'primary' | 'success' | 'info' | 'warning' | 'light' | 'dark'} level
         * @param {'s' | 'c'} halign
         * @param {'t' | 'm' | 'c'} valign
         */
        nukeviet.toast = (text, level, halign, valign) => {
            let toasts = document.getElementById('site-toasts');
            if (!toasts) {
                toasts = document.createElement('div');
                toasts.id = 'site-toasts';
                toasts.classList.add('cr-toasts', 'cr-d-none');
                toasts.innerHTML = `<div class="cr-toast-lists">
                    <div class="cr-toast-items" aria-live="polite" aria-atomic="true"></div>
                </div>`;
                document.body.appendChild(toasts);
            }
            const items = toasts.querySelector('.cr-toast-items');
            const toastsScroll = toasts.querySelector('.cr-toast-lists');

            const id = nv_randomPassword(8);
            const tLevel = {
                'secondary': 'cr-toast-lev-secondary',
                'error': 'cr-toast-lev-danger',
                'danger': 'cr-toast-lev-danger',
                'primary': 'cr-toast-lev-primary',
                'success': 'cr-toast-lev-success',
                'info': 'cr-toast-lev-info',
                'warning': 'cr-toast-lev-warning',
                'light': 'cr-toast-lev-light',
                'dark': 'cr-toast-lev-dark',
            };
            const hAlign = {
                's': ' cr-toast-start',
                'c': ' cr-toast-center',
            };
            const vAlign = {
                't': ' cr-toast-top',
                'm': ' cr-toast-middle',
                'c': ' cr-toast-middle',
            };
            level = tLevel[level] || ' ';
            halign = hAlign[halign] || '';
            valign = vAlign[valign] || '';

            const align = halign + valign;
            const allAlign = 'cr-toast-top cr-toast-start cr-toast-center cr-toast-middle';

            const item = document.createElement('div');
            item.setAttribute('data-id', id);
            item.setAttribute('id', 'toast-' + id);
            item.setAttribute('role', 'alert');
            item.setAttribute('aria-live', 'assertive');
            item.setAttribute('aria-atomic', 'true');
            item.className = 'cr-toast cr-fade cr-showing ' + level;
            item.dataset._timeout = null;

            const itemBody = document.createElement('div');
            itemBody.className = 'cr-toast-body';
            if (typeof text === 'object') {
                itemBody.innerHTML = text.message;
            } else {
                itemBody.textContent = htmlEntityDecode(text);
            }

            const itemClose = document.createElement('div');
            itemClose.className = 'cr-toast-close';

            const btnClose = document.createElement('button');
            btnClose.type = 'button';
            btnClose.className = 'cr-btn-close';
            btnClose.setAttribute('data-cr-dismiss', 'toast');
            btnClose.setAttribute('aria-label', nv_close);
            btnClose.addEventListener('click', (event) => {
                event.preventDefault();
                btnClose.disabled = true;
                _ToastHide(item);
            });

            itemClose.appendChild(btnClose);

            item.appendChild(itemBody);
            item.appendChild(itemClose);

            items.appendChild(item);

            if (align != '') {
                allAlign.split(' ').forEach(cls => {
                    if (cls.trim()) toasts.classList.remove(cls);
                });
                align.split(' ').forEach(cls => {
                    if (cls.trim()) toasts.classList.add(cls);
                });
            }
            toasts.classList.remove('cr-d-none');

            item.classList.add('cr-show');
            toastsScroll.scrollTop = toastsScroll.scrollHeight;
            setTimeout(() => {
                item.classList.remove('cr-showing');
                item.dataset._timeout = setTimeout(() => {
                    _ToastTimeout(item);
                }, 5000);
                item.addEventListener('mouseover', (event) => {  _ToastInteraction(item, event); });
                item.addEventListener('mouseout', (event) => {  _ToastInteraction(item, event); });
                item.addEventListener('focusin', (event) => {  _ToastInteraction(item, event); });
                item.addEventListener('focusout', (event) => {  _ToastInteraction(item, event); });
            }, 151);
        };

        if (typeof nvToast !== 'function') {
            window.nvToast = nukeviet.toast;
        }
    }
});
