/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var nv_base_siteurl, nv_lang_data, nv_name_variable, nv_fc_variable, nv_lang_variable, nv_module_name, nv_func_name, nv_is_user, nv_area_admin, nv_my_ofs, nv_my_dst, nv_my_abbr, nv_cookie_prefix, nv_check_pass_mstime, theme_responsive, nv_safemode;
var OP = -1 != navigator.userAgent.indexOf("Opera"),
    IE = -1 != navigator.userAgent.indexOf("MSIE") && !OP,
    GK = -1 != navigator.userAgent.indexOf("Gecko"),
    SA = -1 != navigator.userAgent.indexOf("Safari"),
    DOM = document.getElementById,
    NS4 = document.layers,
    nv_mailfilter = /^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/,
    nv_numcheck = /^([0-9])+$/,
    nv_namecheck = /^([a-zA-Z0-9_-])+$/,
    nv_uname_filter = /^([\p{L}\p{Mn}\p{Pd}'][\p{L}\p{Mn}\p{Pd}',\s]*)*$/u,
    nv_md5check = /^[a-z0-9]{32}$/,
    nv_imgexts = /^.+\.(jpg|gif|png|bmp)$/,
    nv_iChars = "!@#$%^&*()+=-[]\\';,./{}|\":<>?",
    nv_specialchars = /\$|,|@|#|~|`|\%|\*|\^|\&|\(|\)|\+|\=|\[|\-|\_|\]|\[|\}|\{|\;|\:|\'|\"|\<|\>|\?|\||\\|\!|\$|\./g,
    nv_old_Minute = -1,
    strHref = window.location.href,
    script_name = strHref,
    query_string = "";

void 0 === nv_base_siteurl && (nv_base_siteurl = "/");
void 0 === nv_lang_data && (nv_lang_data = "en");
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
1 < strHref.indexOf("?") && ([script_name, query_string] = strHref.split("?"));

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
    var a, domainName = document.domain;
    expiredays = expiredays ? ((a = new Date).setDate(a.getDate() + expiredays), "; expires=" + a.toGMTString()) : "";
    domainName = domainName.replace(/www\./g, "");
    domainName = /^([0-9a-z][0-9a-z-]+\.)+[a-z]{2,}$/i.test(domainName) ? "; domain=." + domainName : "";
    secure = void 0 !== secure && secure ? "; secure" : "";
    SameSite = void 0 !== SameSite && ("Lax" == SameSite || "Strict" == SameSite || "None" == SameSite && "" != secure) ? "; SameSite=" + SameSite : "";
    document.cookie = name + "=" + escape(value) + expiredays + domainName + "; path=" + nv_base_siteurl + SameSite + secure
}

function nv_getCookie(name) {
    var cookie = " " + document.cookie,
        search = " " + name + "=",
        offset = 0,
        end = 0;
    return 0 < cookie.length && (offset = cookie.indexOf(search), -1 != offset) ? (offset += search.length, end = cookie.indexOf(";", offset), -1 == end && (end = cookie.length), unescape(cookie.substring(offset, end))) : null
}

function nv_check_timezone() {
    var domainName = document.domain,
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
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%5B').replace(/\)/g, '%5D').replace(/\*/g, '%2A');
}

// rawurldecode('Kevin+van+Zonneveld%21'); = > 'Kevin+van+Zonneveld!'
function rawurldecode(str) {
    return decodeURIComponent(str);
}

function is_numeric(mixed_var) {
    return !isNaN(mixed_var);
}

function intval(mixed_var, base) {
    var type = typeof(mixed_var);

    if (type === 'boolean') {
        return (mixed_var) ? 1 : 0;
    } else if (type === 'string') {
        tmp = parseInt(mixed_var, base || 10);
        return (isNaN(tmp) || !isFinite(tmp)) ? 0 : tmp;
    } else if (type === 'number' && isFinite(mixed_var)) {
        return Math.floor(mixed_var);
    } else {
        return 0;
    }
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

function nv_show_hidden(div_id, st) {
    var divid = document.getElementById(div_id);
    if (st == 2) {
        if (divid.style.visibility == 'hidden' || divid.style.display == 'none') {
            divid.style.visibility = 'visible';
            divid.style.display = 'block';
        } else {
            divid.style.visibility = 'hidden';
            divid.style.display = 'none';
        }
    } else if (st == 1) {
        divid.style.visibility = 'visible';
        divid.style.display = 'block';
    } else {
        divid.style.visibility = 'hidden';
        divid.style.display = 'none';
    }
    return;
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
    nv_timer = setTimeout('nv_set_disable_false("' + sid + '")', tm);
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

nv_check_timezone();

(function() {
    if (typeof window.CustomEvent === "function") return false; //If not IE

    function CustomEvent(event, params) {
        params = params || {
            bubbles: false,
            cancelable: false,
            detail: undefined
        };
        var evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
    }

    CustomEvent.prototype = window.Event.prototype;

    window.CustomEvent = CustomEvent;

    //closest for IE
    Element.prototype.matches || (Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector);
    Element.prototype.closest || (Element.prototype.closest = function(b) {
        var a = this;
        do {
            if (Element.prototype.matches.call(a, b)) return a;
            a = a.parentElement || a.parentNode
        } while (null !== a && 1 === a.nodeType);
        return null
    })
})();

// Ap dung trinh nghe thu dong cho touchstart
// https://web.dev/uses-passive-event-listeners/?utm_source=lighthouse&utm_medium=devtools
jQuery.event.special.touchstart = {
    setup: function(_, ns, handle) {
        this.addEventListener('touchstart', handle, {
            passive: !ns.includes('noPreventDefault')
        });
    }
};
