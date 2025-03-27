/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var myTimerPage = '', myTimersecField = '';

/**
 * Dừng bộ đếm thời gian không dùng site và chạy lại
 */
function timeoutsesscancel() {
    $("#timeoutsess").slideUp("slow", function() {
        clearInterval(myTimersecField);
        myTimerPage = setTimeout(function() {
            timeoutsessrun()
        }, nv_check_pass_mstime)
    })
}

/**
 * Chạy bộ đếm thời gian không dùng site
 */
function timeoutsessrun() {
    clearInterval(myTimerPage);
    $("#secField").text("60");
    $("#timeoutsess").show();
    var b = (new Date).getTime();
    myTimersecField = setInterval(function() {
        var a = (new Date).getTime();
        a = 60 - Math.round((a - b) / 1E3);
        0 <= a ? $("#secField").text(a) : -3 > a && (clearInterval(myTimersecField), $(window).unbind(), $.ajax({
            type: "POST",
            cache: !1,
            url: nv_base_siteurl + "index.php?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=users&" + nv_fc_variable + "=logout",
            data: "nv_ajax_login=1&system=1"
        }).done(function() {
            location.reload();
        }));
    }, 1E3);
}

/**
 * Tắt thông báo thu thập cookie
 */
function cookie_notice_hide() {
    nv_setCookie(nv_cookie_prefix + '_cn', '1', 365);
    $(".cookie-notice").hide()
}

/**
 * Kích hoạt event khi ấn Enter
 *
 * @param {Event} e
 * @param {HTMLElement} obj
 * @param {String} objEvent
 */
function enterToEvent(e, obj, objEvent) {
    13 != e.which || e.shiftKey || (e.preventDefault(), $(obj).trigger(objEvent))
}

/**
 * Chọn tất cả
 *
 * @param {HTMLElement} a
 */
function checkAll(a) {
    $(".checkAll", a).is(":checked") ? ($(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !0)
    }), $(".checkBtn", a).length && $(".checkBtn", a).prop("disabled", !1)) : ($(".checkSingle", a).not(":disabled").each(function() {
        $(this).prop("checked", !1)
    }), $(".checkBtn", a).length && $(".checkBtn", a).prop("disabled", !0))
}

/**
 * Chọn từng dòng trong danh sách
 *
 * @param {HTMLElement} a
 */
function checkSingle(a) {
    var checked = 0,
        unchecked = 0;
    $(".checkSingle", a).not(":disabled").each(function() {
        $(this).is(":checked") ? checked++ : unchecked++
    });
    0 != checked && 0 == unchecked ? $(".checkAll", a).prop("checked", !0) : $(".checkAll", a).prop("checked", !1);
    $(".checkBtn", a).length && (checked ? $(".checkBtn", a).prop("disabled", !1) : $(".checkBtn", a).prop("disabled", !0))
}

/**
 * Đổi URL trang web mà không làm load lại trang
 *
 * @param {String} url
 */
function locationReplace(url) {
    var uri = window.location.href.substring(window.location.protocol.length + window.location.hostname.length + 2);
    if (url != uri && history.pushState) {
        history.pushState(null, null, url)
    }
}

/**
 * Mở form đăng nhập trong modal
 *
 * @param {String} redirect
 * @returns {Boolean}
 */
function loginForm(redirect) {
    if (nv_is_user == 1) {
        return !1;
    }

    var url = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=login';
    if (!!redirect) {
        url += '&nv_redirect=' + redirect;
    }
    $.ajax({
        type: 'POST',
        url: url,
        cache: !1,
        data: '&nv_ajax=1',
        dataType: "json"
    }).done(function(res) {
        if (res.sso) {
            window.location.href = res.sso;
            return !1;
        }
        modalShow('', res.html, 'recaptchareset');
    });
    return !1
}

/**
 * Xử lý sau khi đăng nhập GSI
 *
 * @param {Object} response
 */
function GIDHandleCredentialResponse(response) {
    $.ajax({
        type: 'POST',
        url: $('#g_id_onload').data('url'),
        cache: !1,
        data: {
            'credential': response.credential,
            'nv_redirect': $('#g_id_onload').data('redirect'),
            '_csrf': $('#g_id_onload').data('csrf')
        },
        dataType: "json"
    }).done(function(a) {
        if (a.status == 'error') {
            alert(a.mess);
            if (a.is_reload) {
                location.reload()
            }
        } else if (a.status == 'success') {
            if (typeof a.redirect != 'undefined' && a.redirect != '') {
                window.location.href = a.redirect;
                return 1;
            }
            location.reload();
        } else if (a.status == 'OK') {
            var content = $($('#g_id_confirm').html());
            $('a', content).on('click', function(e) {
                e.preventDefault();
                modalHide();
                nv_open_browse(a.redirect, "NVOPID", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,titlebar=no,menubar=0,location=no,status=no");
            });
            modalShow('', content)
        }
    })
}

/**
 * Build lại captcha khi ấn nút đổi captcha
 *
 * @param {HTMLElement | undefined} obj
 */
function loadCaptcha(obj) {
    if ("undefined" === typeof obj) {
        obj = $('body')
    }
    if ($('[data-toggle=recaptcha]', obj).length) {
        reCaptcha2Recreate(obj);
        "undefined" != typeof grecaptcha ? reCaptcha2OnLoad() : reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha2]", obj).length && "undefined" == typeof grecaptcha) {
        reCaptcha2ApiLoad()
    } else if ($("[data-recaptcha3]", obj).length && "undefined" == typeof grecaptcha) {
        reCaptcha3ApiLoad()
    } else if ($("[data-turnstile]", obj).length) {
        turnstileRecreate(obj);
        "undefined" != typeof turnstile ? turnstileOnLoad() : turnstileApiLoad();
    }
}

/**
 * Đổi captcha - Hàm này dùng để gọi nếu muốn đổi captcha
 *
 * @param {HTMLElement | undefined} a
 * @returns {boolean}
 */
function change_captcha(a) {
    loadCaptcha();
    if ($("img.captchaImg").length) {
        $("img.captchaImg").attr("src", nv_base_siteurl + "sload.php?scaptcha=captcha&nocache=" + nv_randomPassword(10));
        "undefined" != typeof a && "" != a && $(a).val("");
    }
    return !1
}

/**
 * Thay mới captcha của form
 *
 * @param {HTMLElement} form
 */
function formChangeCaptcha(form) {
    var btn = $("[onclick*=change_captcha], [data-toggle=change_captcha]", form);
    btn.length && btn.trigger('click');
    if ($('[data-toggle=recaptcha]', form).length || $("[data-recaptcha2], [data-recaptcha3]", $(form).parent()).length) {
        change_captcha();
    }
}

/**
 * Check xem các biến phục vụ Recaptcha đã đủ chưa
 *
 * @returns {Number}
 */
function isRecaptchaCheck() {
    if (nv_recaptcha_sitekey == '') return 0;
    return (nv_recaptcha_ver == 2 || nv_recaptcha_ver == 3) ? nv_recaptcha_ver : 0
}

/**
 * Reset recaptcha V2 trên các thẻ có data-toggle=recaptcha (reset inline recaptcha V2)
 *
 * @param {HTMLElement} obj
 */
function reCaptcha2Recreate(obj) {
    $('[data-toggle=recaptcha]', $(obj)).each(function() {
        if (!$('#modal-' + $(this).attr('id')).length) {
            var callFunc = $(this).data('callback'),
                pnum = $(this).data('pnum'),
                btnselector = $(this).data('btnselector'),
                size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '',
                id = "recaptcha" + (new Date().getTime()) + nv_randomPassword(8),
                div = '<div id="' + id + '" data-toggle="recaptcha"';
            callFunc && (div += ' data-callback="' + callFunc + '"');
            pnum && (div += ' data-pnum="' + pnum + '"');
            btnselector && (div += ' data-btnselector="' + btnselector + '"');
            size && (div += ' data-size="' + size + '"');
            div += '></div>';
            $(this).replaceWith(div)
        }
    })
}

/**
 * Reset turnstile trên các thẻ có data-toggle=turnstile (reset inline turnstile)
 *
 * @param {HTMLElement} obj
 */
function turnstileRecreate(obj) {
    $('[data-toggle=turnstile]', $(obj)).each(function() {
        if (!$('#modal-' + $(this).attr('id')).length) {
            var callFunc = $(this).data('callback'),
                pnum = $(this).data('pnum'),
                btnselector = $(this).data('btnselector'),
                size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '',
                id = "turnstile" + (new Date().getTime()) + nv_randomPassword(8),
                div = '<div id="' + id + '" data-toggle="turnstile"';
            callFunc && (div += ' data-callback="' + callFunc + '"');
            pnum && (div += ' data-pnum="' + pnum + '"');
            btnselector && (div += ' data-btnselector="' + btnselector + '"');
            size && (div += ' data-size="' + size + '"');
            div += '></div>';
            $(this).replaceWith(div)
        }
    })
}

/**
 * Làm sạch form chống XSS
 *
 * @param {HTMLElement} form
 */
function formXSSsanitize(form) {
    $(form).find("input, textarea").not(":submit, :reset, :image, :file, :disabled").not('[data-sanitize-ignore]').each(function() {
        let value;
        if (this.dataset.editorname && window.nveditor && window.nveditor[this.dataset.editorname]) {
            value = window.nveditor[this.dataset.editorname].getData();
        } else {
            value = $(this).val();
        }
        $(this).val(DOMPurify.sanitize(value, {}));
    });
}

/**
 * Xử lý khi ấn nút submit form:
 * - Làm sạch chống XSS
 * - Check lỗi trước khi submit
 * - Gọi reCaptcha, turnstile, captcha nếu có
 * - Submit form
 *
 * @param {Event} event
 * @param {HTMLElement} form
 * @returns
 */
function btnClickSubmit(event, form) {
    event.preventDefault();

    $(form).find("textarea").each(function() {
        if (this.dataset.editorname && window.nveditor && window.nveditor[this.dataset.editorname]) {
            $(this).val(window.nveditor[this.dataset.editorname].getData());
        }
    });

    if (XSSsanitize) {
        formXSSsanitize(form);
    }

    if ($(form).attr('data-precheck')) {
        var preCheck = $(form).data('precheck');
        if ('string' == typeof preCheck && "function" === typeof window[preCheck]) {
            if (!window[preCheck](form)) {
                return !1
            }
        }
    }
    if ($(form).attr('data-recaptcha3')) {
        reCaptchaExecute(form, function() {
            $(form).submit();
        })
    } else if ($(form).attr('data-recaptcha2')) {
        reCaptcha2Execute(form, function() {
            $(form).submit();
        })
    } else if ($(form).attr('data-turnstile')) {
        turnstileExecute(form, function() {
            $(form).submit();
        })
    } else if ($(form).attr('data-captcha')) {
        captchaExecute(form, function() {
            $(form).submit();
        })
    } else {
        $(form).submit();
    }
}

/**
 * Kiểm tra chạy callback sau khi xác thực captcha
 *
 * @param {Function} callFunc
 */
function captchaCallFuncLoad(callFunc) {
    if ("function" === typeof callFunc) {
        callFunc()
    } else if ('string' == typeof callFunc && "function" === typeof window[callFunc]) {
        window[callFunc]()
    }
}

/**
 * Xử lý sau khi gọi API reCaptcha v2 xong
 */
var reCaptcha2OnLoad = function() {
    // Init captcha 2 trên các element có data-toggle=recaptcha (build inline recaptcha V2)
    $('[data-toggle=recaptcha]').each(function() {
        var id = $(this).attr('id'),
            callFunc = $(this).data('callback'),
            size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '';

        if (typeof window[callFunc] === 'function') {
            if (typeof nukeviet.reCapIDs[id] === "undefined") {
                nukeviet.reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'size': size,
                    'callback': callFunc
                })
            } else {
                grecaptcha.reset(nukeviet.reCapIDs[id])
            }
        } else {
            if (typeof nukeviet.reCapIDs[id] === "undefined") {
                var pnum = parseInt($(this).data('pnum')),
                    btnselector = $(this).data('btnselector'),
                    btn = $('#' + id),
                    k = 1;

                for (k; k <= pnum; k++) {
                    btn = btn.parent();
                }
                btn = $(btnselector, btn);
                if (btn.length) {
                    btn.prop('disabled', true);
                }

                nukeviet.reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'size': size,
                    'callback': function() {
                        reCaptcha2Callback(id, false)
                    },
                    'expired-callback': function() {
                        reCaptcha2Callback(id, true)
                    },
                    'error-callback': function() {
                        reCaptcha2Callback(id, true)
                    }
                })
            } else {
                grecaptcha.reset(nukeviet.reCapIDs[id])
            }
        }
    })
}

/**
 * Hàm xử lý sau khi inline reCaptcha v2  hoặc turnstile được tạo xong, hết hạn, xác thực xong
 * mặc định là disabled nút submit nếu tạo, hết hạn và enable nếu xác thực xong
 *
 * @param {String} id
 * @param {Boolean} val
 */
var reCaptcha2Callback = function(id, val) {
    var btn = $('#' + id),
        pnum = parseInt(btn.data('pnum')),
        btnselector = btn.data('btnselector'),
        k = 1;
    for (k; k <= pnum; k++) {
        btn = btn.parent();
    }
    btn = $(btnselector, btn);
    if (btn.length) {
        btn.prop('disabled', val);
    }
}

/**
 * Mở modal captcha lên
 *
 * @param {HTMLElement} modal
 * @param {Function | undefined | null} showCb
 * @param {Function | undefined | null} shownCb
 */
function _showCaptchaModal(modal, showCb, shownCb) {
    nukeviet.cr.mdCapDb = {
        overflow: null,
        paddingRight: null,
        scroll: null
    };

    const body = document.body;
    const backdrop = document.createElement('div');

    backdrop.classList.add('cr-cap-backdrop', 'cr-fade');
    body.append(backdrop);

    modal.removeAttribute('aria-hidden');
    modal.setAttribute('aria-modal', 'true');
    modal.style.display = 'block';

    nukeviet.cr.mdCapDb.overflow = body.style.overflow;
    nukeviet.cr.mdCapDb.paddingRight = body.style.paddingRight;
    nukeviet.cr.mdCapDb.scroll = document.documentElement.scrollHeight > window.innerHeight;

    setTimeout(() => {
        modal.classList.add('cr-show');
        backdrop.classList.add('cr-show');

        body.style.overflow = 'hidden';
        nukeviet.cr.mdCapDb.scroll && (body.style.paddingRight = nukeviet.getScrollbarWidth() + 'px');
        body.classList.add('cr-cap-open');
    }, 1);
    if (showCb) {
        showCb();
    }
    setTimeout(() => {
        _offBsenforceFocus('cap');
        if (shownCb) {
            shownCb();
        }
    }, 160);
}

/**
 * Mở modal captcha hình lên để xác thực
 *
 * @param {HTMLElement} obj
 * @param {Function} callFunc
 */
var captchaExecute = function(obj, callFunc) {
    let mdCap = document.getElementById('modal-img-captcha');
    const body = document.body;
    if (!mdCap) {
        mdCap = document.createElement('div');
        mdCap.id = 'modal-img-captcha';
        mdCap.classList.add('cr-cap', 'cr-fade');
        mdCap.setAttribute('tabindex', '-1');
        mdCap.setAttribute('aria-labelledby', 'modal-img-captcha-label');
        mdCap.setAttribute('aria-hidden', 'true');
        mdCap.innerHTML = `<div class="cr-cap-dialog">
            <div class="cr-cap-content">
                <div class="cr-cap-header">
                    <div class="cr-cap-title" id="modal-img-captcha-label">${nukeviet.i18n.seccode1}</div>
                    <button type="button" class="cr-btn-close" data-cr-dismiss="modal" aria-label="${nukeviet.i18n.close}"></button>
                </div>
                <div class="cr-cap-body cr-center">
                    <div class="cr-cap-img">
                        <img class="captchaImg" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="${nv_gfx_width}" height="${nv_gfx_height}" alt="Captcha Image" title="${nukeviet.i18n.seccode}" aria-label="${nukeviet.i18n.seccode}">
                        <span class="cr-pointer" data-toggle="change_captcha" data-obj="#modal-captcha-value" title="${nukeviet.i18n.captcharefresh}" aria-label="${nukeviet.i18n.captcharefresh}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                            </svg>
                        </span>
                    </div>
                    <div class="cr-cap-input">
                        <label for="modal-captcha-value">${nukeviet.i18n.seccode} <span class="cr-danger">(*)</span></label>
                        <input type="text" id="modal-captcha-value" value="" class="cr-fcontrol" maxlength="${nv_gfx_num}" data-toggle="enterToEvent" data-obj="#modal-captcha-button" data-obj-event="click">
                        <div class="cr-invalid-feedback"></div>
                    </div>
                    <button type="button" id="modal-captcha-button" class="cr-btn cr-btn-primary">${nv_confirm}</button>
                </div>
            </div>
        </div>`;
        body.appendChild(mdCap);
    }

    mdCap.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    mdCap.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
    });
    change_captcha('#modal-captcha-value');
    _showCaptchaModal(mdCap, () => {
        const btn = document.getElementById('modal-captcha-button');
        btn.replaceWith(btn.cloneNode(true));
        document.getElementById('modal-captcha-button').addEventListener('click', (event) => {
            event.preventDefault();

            const cIpt = document.getElementById('modal-captcha-value');
            let captcha = trim(strip_tags(cIpt.value));

            if (captcha.length < parseInt(cIpt.getAttribute('maxlength'))) {
                cIpt.classList.add('is-invalid');
                cIpt.nextElementSibling.textContent = nv_code;
                cIpt.focus();
            } else {
                mdCap.querySelector('[data-cr-dismiss="modal"]').click();
                if (obj instanceof jQuery) {
                    obj = obj[0];
                }

                let name = obj.dataset.captcha;
                let input = obj.querySelector('[name="' + name + '"]');

                if (input) {
                    input.value = captcha;
                } else {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = captcha;
                    obj.appendChild(input);
                }

                setTimeout(() => {
                    captchaCallFuncLoad(callFunc);
                }, 100);
            }
        });
    }, () => {
        document.getElementById('modal-captcha-value').focus();
    });
}

/**
 * Kích hoạt recaptcha 2 khi submit form có recaptcha2
 *
 * @param {HTMLElement} obj
 * @param {Function} callFunc
 * @returns {boolean}
 */
var reCaptcha2Execute = function(obj, callFunc) {
    if ("undefined" === typeof grecaptcha) {
        reCaptcha2ApiLoad();
        setTimeout(function() {
            $('[type=submit]', obj).trigger('click')
        }, 2E3);
        return !1
    }

    var id = $(obj).attr('data-recaptcha2'),
        res = $("[name=g-recaptcha-response]", obj).val(),
        isExist = false;
    if (id.length == 16 && typeof nukeviet.reCapIDs[id] !== "undefined" && $('#' + nukeviet.reCapIDs[id]).length && !!res) {
        if (grecaptcha.getResponse(nukeviet.reCapIDs[id]) == res) {
            isExist = true
        }
    }
    if (isExist) {
        captchaCallFuncLoad(callFunc);
    } else {
        // Tạo id ngẫu nhiên cho captcha
        if (id.length != 16) {
            id = nv_randomPassword(16);
            $(obj).attr('data-recaptcha2', id);
        }
        // Thêm modal của captcha nếu chưa có
        const mdId = `modal-${id}`;
        let md = document.getElementById(mdId);
        if (!md) {
            md = document.createElement('div');
            md.id = mdId;
            md.classList.add('cr-cap', 'cr-fade');
            md.setAttribute('tabindex', '-1');
            md.setAttribute('aria-labelledby', `modal-${id}-label`);
            md.setAttribute('aria-hidden', 'true');
            md.innerHTML = `<div class="cr-cap-dialog">
                <div class="cr-cap-content">
                    <div class="cr-cap-header">
                        <div class="cr-cap-title" id="${id}-label">${verify_not_robot}</div>
                        <button type="button" class="cr-btn-close" data-cr-dismiss="modal" aria-label="${nukeviet.i18n.close}"></button>
                    </div>
                    <div class="cr-cap-body cr-center">
                        <div class="nv-recaptcha-default">
                            <div id="${id}" data-toggle="recaptcha"></div>
                        </div>
                    </div>
                </div>
            </div>`;
            document.body.appendChild(md);
        }
        _showCaptchaModal(md, () => {
            if (typeof nukeviet.reCapIDs[id] === "undefined") {
                nukeviet.reCapIDs[id] = grecaptcha.render(id, {
                    'sitekey': nv_recaptcha_sitekey,
                    'type': nv_recaptcha_type,
                    'callback': function(response) {
                        md.querySelector('[data-cr-dismiss="modal"]').click();
                        $("[name=g-recaptcha-response]", obj).length ? $("[name=g-recaptcha-response]", obj).val(response) : (response = $('<input type="hidden" name="g-recaptcha-response" value="' + response + '"/>'), $(obj).append(response));
                        setTimeout(function() {
                            captchaCallFuncLoad(callFunc);
                        }, 100);
                    }
                });
            } else {
                grecaptcha.reset(nukeviet.reCapIDs[id]);
            }
        });
    }
}

/**
 * Tải API reCaptcha v2
 */
var reCaptcha2ApiLoad = function() {
    if (isRecaptchaCheck() == 2) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.defer = true;
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        a.src = "//www.google.com/recaptcha/api.js?hl=" + nv_lang_interface + "&onload=reCaptcha2OnLoad&render=explicit";
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

/**
 * Thực thi reCaptcha v3
 *
 * @param {HTMLElement} obj
 * @param {Function} callFunc
 */
var reCaptchaExecute = function(obj, callFunc) {
    grecaptcha.execute(nv_recaptcha_sitekey, {
        action: "formSubmit"
    }).then(function(a) {
        $("[name=g-recaptcha-response]", obj).length ? $("[name=g-recaptcha-response]", obj).val(a) : (a = $('<input type="hidden" name="g-recaptcha-response" value="' + a + '"/>'), $(obj).append(a));
        captchaCallFuncLoad(callFunc)
    })
}

/**
 * Tải API reCaptcha v3
 */
var reCaptcha3ApiLoad = function() {
    if (isRecaptchaCheck() == 3) {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.defer = true;
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        a.src = "//www.google.com/recaptcha/api.js?render=" + nv_recaptcha_sitekey;
        document.getElementsByTagName("head")[0].appendChild(a)
    }
}

/**
 * Tải API Turnstile
 */
var turnstileApiLoad = () => {
    var a = document.createElement("script");
    a.type = "text/javascript";
    a.defer = true;
    "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
    a.src = "//challenges.cloudflare.com/turnstile/v0/api.js?onload=turnstileOnLoad&render=explicit";
    document.getElementsByTagName("head")[0].appendChild(a)
}

/**
 * Xử lý sau khi gọi API turnstile xong
 */
var turnstileOnLoad = function() {
    // Init turnstile trên các element có data-toggle=turnstile (build inline turnstile)
    $('[data-toggle=turnstile]').each(function() {
        var id = $(this).attr('id'),
            callFunc = $(this).data('callback'),
            size = ($(this).data('size') && $(this).data('size') == 'compact') ? 'compact' : '';

        if (typeof window[callFunc] === 'function') {
            if (typeof nukeviet.turnstileIDs[id] === "undefined") {
                nukeviet.turnstileIDs[id] = turnstile.render('#' + id, {
                    'sitekey': nv_turnstile_sitekey,
                    'size': size,
                    'theme': 'light',
                    'language': nv_lang_interface,
                    'callback': callFunc
                })
            } else {
                turnstile.reset(nukeviet.turnstileIDs[id])
            }
        } else {
            if (typeof nukeviet.turnstileIDs[id] === "undefined") {
                var pnum = parseInt($(this).data('pnum')),
                    btnselector = $(this).data('btnselector'),
                    btn = $('#' + id),
                    k = 1;

                for (k; k <= pnum; k++) {
                    btn = btn.parent();
                }
                btn = $(btnselector, btn);
                if (btn.length) {
                    btn.prop('disabled', true);
                }

                nukeviet.turnstileIDs[id] = turnstile.render('#' + id, {
                    'sitekey': nv_turnstile_sitekey,
                    'size': size,
                    'theme': 'light',
                    'language': nv_lang_interface,
                    'callback': function() {
                        reCaptcha2Callback(id, false)
                    },
                    'expired-callback': function() {
                        reCaptcha2Callback(id, true)
                    },
                    'error-callback': function() {
                        reCaptcha2Callback(id, true)
                    }
                })
            } else {
                turnstile.reset(nukeviet.turnstileIDs[id])
            }
        }
    })
}

/**
 * Kích hoạt turnstile 2 khi submit form có turnstile
 *
 * @param {HTMLElement} obj
 * @param {Function} callFunc
 * @returns
 */
var turnstileExecute = function(obj, callFunc) {
    if ("undefined" === typeof turnstile) {
        turnstileApiLoad();
        setTimeout(function() {
            $('[type=submit]', obj).trigger('click')
        }, 2E3);
        return !1
    }

    var id = $(obj).attr('data-turnstile'),
        res = $("[name=cf-turnstile-response]", obj).val(),
        isExist = false;
    if (id.length == 16 && typeof nukeviet.turnstileIDs[id] !== "undefined" && $('#' + nukeviet.turnstileIDs[id]).length && !!res) {
        if (turnstile.getResponse(nukeviet.turnstileIDs[id]) == res) {
            isExist = true
        }
    }
    if (isExist) {
        captchaCallFuncLoad(callFunc);
    } else {
        // Tạo id ngẫu nhiên cho captcha
        if (id.length != 16) {
            id = 'tt' + nv_randomPassword(14);
            $(obj).attr('data-turnstile', id);
        }
        // Thêm modal của captcha nếu chưa có
        const mdId = `modal-${id}`;
        let md = document.getElementById(mdId);
        if (!md) {
            md = document.createElement('div');
            md.id = mdId;
            md.classList.add('cr-cap', 'cr-fade');
            md.setAttribute('tabindex', '-1');
            md.setAttribute('aria-labelledby', `modal-${id}-label`);
            md.setAttribute('aria-hidden', 'true');
            md.innerHTML = `<div class="cr-cap-dialog">
                <div class="cr-cap-content">
                    <div class="cr-cap-header">
                        <div class="cr-cap-title" id="${id}-label">${verify_not_robot}</div>
                        <button type="button" class="cr-btn-close" data-cr-dismiss="modal" aria-label="${nukeviet.i18n.close}"></button>
                    </div>
                    <div class="cr-cap-body cr-center">
                        <div class="nv-recaptcha-default">
                            <div id="${id}" data-toggle="turnstile"></div>
                        </div>
                    </div>
                </div>
            </div>`;
            document.body.appendChild(md);
        }
        _showCaptchaModal(md, () => {
            if (typeof nukeviet.turnstileIDs[id] === "undefined") {
                nukeviet.turnstileIDs[id] = turnstile.render('#' + id, {
                    'sitekey': nv_turnstile_sitekey,
                    'theme': 'light',
                    'language': nv_lang_interface,
                    'callback': function(response) {
                        md.querySelector('[data-cr-dismiss="modal"]').click();
                        $("[name=cf-turnstile-response]", obj).length ? $("[name=cf-turnstile-response]", obj).val(response) : (response = $('<input type="hidden" name="cf-turnstile-response" value="' + response + '"/>'), $(obj).append(response));
                        setTimeout(function() {
                            captchaCallFuncLoad(callFunc);
                        }, 100);
                    }
                })
            } else {
                turnstile.reset(nukeviet.turnstileIDs[id]);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Đóng modal captcha nói chung: recaptcha, turnstile, captcha
    document.addEventListener('click', (event) => {
        if (
            !event.target.matches('[data-cr-dismiss="modal"]') ||
            !document.body.classList.contains('cr-cap-open')
        ) {
            return;
        }
        event.preventDefault();

        const modal = event.target.closest('.cr-cap');
        const body = document.body;

        const backdrop = document.querySelector('.cr-cap-backdrop');

        body.classList.remove('cr-cap-open');
        body.style.overflow = nukeviet.cr.mdCapDb.overflow;
        body.style.paddingRight = nukeviet.cr.mdCapDb.paddingRight;
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
            body.removeChild(backdrop);
            _onBsenforceFocus('cap');
        }, 150);
    });
});

$(function() {
    // Modify all empty link
    $('body').on("click", 'a[href="#"], a[href=""]', function(e) {
        e.preventDefault();
    });

    if ($('a[data-target]').length) {
        $('a[data-target]').each(function() {
            $(this).attr('target', $(this).data('target'))
        })
    }

    // Add rel="noopener noreferrer nofollow" to all external links
    $('a[href^="http"]').not('a[href*="' + location.hostname + '"]').not('[rel*=dofollow]').attr({
        target: "_blank",
        rel: "noopener noreferrer nofollow"
    });

    // Show messger timeout login users
    nv_is_user && nv_check_pass_mstime != 0 && (myTimerPage = setTimeout(function() {
        timeoutsessrun()
    }, nv_check_pass_mstime));

    // Windows commands
    $('body').on('click', '[data-toggle=winCMD][data-cmd]', function(e) {
        e.preventDefault();
        if ($(this).data('cmd') == 'print') {
            window.print()
        } else if ($(this).data('cmd') == 'close') {
            window.close()
        } else if ($(this).data('cmd') == 'open') {
            window.open($(this).data('url'), $(this).data('win-name'), $(this).data('win-opts'))
        }
    });

    // timeoutsesscancel
    $('[data-toggle=timeoutsesscancel]').on('click', function(e) {
        e.preventDefault();
        timeoutsesscancel()
    });

    // Hide Cookie Notice Popup
    $('[data-toggle=cookie_notice_hide]').on('click', function(e) {
        e.preventDefault();
        cookie_notice_hide()
    });

    // JS của nv_generate_page
    $('body').on('click', '[data-toggle=gen-page-js][data-func][data-href][data-obj]', function(e) {
        e.preventDefault();
        if ('function' === typeof window[$(this).data('func')]) {
            window[$(this).data('func')]($(this).data('href'), $(this).data('obj'))
        }
    });

    // Gọi modal đăng nhập thành viên
    $('body').on('click', '[data-toggle=loginForm]', function(e) {
        e.preventDefault();
        $(this).data('redirect') ? loginForm($(this).data('redirect')) : loginForm()
    });

    //XSSsanitize + Captcha
    $('body').on('click', '[type=submit]:not([name])', function(e) {
        var form = $(this).parents('form');
        if (!$('[name=submit]', form).length) {
            btnClickSubmit(e, form)
        }
    });

    // Thay Captcha hình mới
    $('body').on('click', '[data-toggle=change_captcha]', function(e) {
        e.preventDefault();
        $(this).data('obj') ? change_captcha($(this).data('obj')) : change_captcha()
    });

    // enterToEvent
    $('body').on('keyup', '[data-toggle=enterToEvent][data-obj][data-obj-event]', function(e) {
        enterToEvent(e, $(this).data('obj'), $(this).data('obj-event'))
    });

    // checkAll
    $('body').on('click', '[data-toggle=checkAll]', function() {
        checkAll($(this).parents('form'))
    });

    // checkSingle
    $('body').on('click', '[data-toggle=checkSingle]', function() {
        checkSingle($(this).parents('form'))
    });

    //Change Localtion
    $("[data-location]").on("click", function() {
        if (window.location.origin + $(this).data("location") != window.location.href) {
            locationReplace($(this).data("location"))
        }
    });

    // modalShowByObj
    $('body').on('click', '[data-toggle=modalShowByObj]', function(e) {
        e.preventDefault();
        var obj = $(this).data('obj') ? $(this).data('obj') : this,
            callback = $(this).data('callback');
        callback ? modalShowByObj(obj, callback) : modalShowByObj(obj);
    });

    // maxLength for textarea
    $("textarea").on("input propertychange", function() {
        var a = $(this).prop("maxLength");
        if (!a || "number" != typeof a) {
            var a = $(this).attr("maxlength"),
                b = $(this).val();
            b.length > a && $(this).val(b.substr(0, a))
        }
    });

    //Alerts
    $("[data-dismiss=alert]").on("click", function() {
        $(this).is(".close") && $(this).parent().remove()
    });

    // Chỉ cho gõ ký tự dạng số ở input có class number
    $('body').on('input', '.number', function() {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    // Chỉ cho gõ các ký tự [a-zA-Z0-9_] ở input có class alphanumeric
    $('body').on('input', '.alphanumeric', function() {
        $(this).val($(this).val().replace(/[^a-zA-Z0-9\_]/gi, ''))
    });

    // Không cho xuống dòng
    $('body').on('input', '.nonewline', function () {
        var val = $(this).val().replace(/\n$/gi, '');
        $(this).val(val.replace(/\s*\n\s*/gi, ' '))
    });

    // uncheck khi click vào radio nếu radio đang ở trạng thái checked
    $('body').on("click mousedown", 'input[type=radio].uncheckRadio, .uncheckRadio input[type=radio]', function() {
        var c;
        return function(i) {
            c = "click" == i.type ? !c || (this.checked = !1) : this.checked
        }
    }());
});

$(window).on('load', function() {
    (0 < $(".fb-like").length) && (1 > $("#fb-root").length && $("body").append('<div id="fb-root"></div>'), function(a, b, c) {
        var d = a.getElementsByTagName(b)[0];
        var fb_app_id = ($('[property="fb:app_id"]').length > 0) ? '&appId=' + $('[property="fb:app_id"]').attr("content") : '';
        var fb_locale = ($('[property="og:locale"]').length > 0) ? $('[property="og:locale"]').attr("content") : ((nv_lang_data == "vi") ? 'vi_VN' : 'en_US');
        a.getElementById(c) || (a = a.createElement(b), a.id = c, a.src = "//connect.facebook.net/" + fb_locale + "/all.js#xfbml=1" + fb_app_id, "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce), d.parentNode.insertBefore(a, d));
    }(document, "script", "facebook-jssdk"));
    0 < $(".twitter-share-button").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//platform.twitter.com/widgets.js";
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();
    0 < $(".zalo-share-button, .zalo-follow-only-button, .zalo-follow-button, .zalo-chat-widget").length && function() {
        var a = document.createElement("script");
        a.type = "text/javascript";
        a.src = "//sp.zalo.me/plugins/sdk.js";
        "undefined" !== typeof site_nonce && a.setAttribute('nonce', site_nonce);
        var b = document.getElementsByTagName("script")[0];
        b.parentNode.insertBefore(a, b);
    }();

    // Nếu có recaptcha thì load API. Recaptcha 2 hoặc 3 chỉ hỗ trợ 1 trong 2
    if ($('[data-toggle=recaptcha]').length || $("[data-recaptcha2]").length) {
        reCaptcha2ApiLoad();
    } else if ($("[data-recaptcha3]").length) {
        reCaptcha3ApiLoad();
    }
    // Nếu có turnstile thì load API
    if ($('[data-toggle=turnstile]').length || $("[data-turnstile]").length) {
        turnstileApiLoad();
    }
});
