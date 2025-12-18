/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * Tạo thẻ báo lỗi cho các trường dữ liệu trong form khi valid
 *
 * @param {JQuery} ipt
 * @param {Object} data
 * @param {String} message
 * @returns {JQuery}
 */
function _make_check_invalid(ipt, data, message) {
    const form = ipt.closest('form');
    const iptGroup = ipt.parent();
    const hasInputGroup = iptGroup.length && iptGroup.is('.input-group');
    let element = ipt.next();
    if (element.is('label')) {
        // Dạng form-check
        element = element.next();
    } else if (hasInputGroup) {
        element = iptGroup.next();
    }

    // Thẻ sẽ tạo báo lỗi sau đó
    let eleBeforeInvalid = ipt.next().is('label') ? ipt.next() : ipt;
    if (hasInputGroup) {
        eleBeforeInvalid = iptGroup;
    }

    if (!element.length || (!element.is('.invalid-feedback') && !element.is('.invalid-tooltip'))) {
        element = $(`<div class="invalid-${data.errType}"></div>`).insertAfter(eleBeforeInvalid);
    }
    element.text(message);
    if (data.type === 'editor') {
        ipt.addClass('is-invalid');
    } else {
        $(_get_input_name(ipt), form).addClass('is-invalid');
    }
    hasInputGroup && iptGroup.addClass('is-invalid');

    return element;
}

/**
 * Kiểm tra từng input trong form
 *
 * @param {JQuery} ipt
 * @param {String | undefined | null} customMess Thông báo tùy chỉnh hay mặc định
 * @param {"editor" | undefined | null} specialType Kiểu đăc biệt ví dụ như trình soạn thảo
 * @returns {JQuery | null}
 */
function _check_invalid(ipt, customMess, specialType) {
    let baseIpt = ipt;
    if (typeof specialType === 'string') {
        if (specialType === 'editor') {
            ipt = ipt.closest('[data-valid]');
        }
    }

    if (ipt.is('.is-invalid')) {
        return;
    }

    // Xác định custom message
    let elErr = ipt.next();
    let errMess = '';
    if (ipt.data('error-mess') && ipt.data('error-mess').length > 0) {
        errMess = ipt.data('error-mess');
    } else {
        if (elErr.is('label')) {
            // Dạng form-check
            elErr = elErr.next();
        }
        if (elErr.length == 1 && (elErr.is('.invalid-feedback') || elErr.is('.invalid-tooltip'))) {
            if (!elErr.data('error-mess') || elErr.data('error-mess') == '') {
                elErr.data('error-mess', elErr.text());
            }
            errMess = elErr.data('error-mess');
        }
    }

    const valid = {
        type: ($(ipt).data('valid') || $(ipt).attr('type') || 'text').toLowerCase(),
        empty: $(ipt).data('empty') !== undefined ? $(ipt).data('empty') : '',
        allowedEmpty: !!$(ipt).data('allowed-empty'),
        min: $(ipt).data('min') !== undefined ? $(ipt).data('min') : 1,
        max: $(ipt).data('max') !== undefined ? $(ipt).data('max') : 1,
        minLen: $(ipt).attr('minlength') !== undefined ? parseFloat($(ipt).attr('minlength')) : -1,
        maxLen: $(ipt).attr('maxlength') !== undefined ? parseFloat($(ipt).attr('maxlength')) : -1,
        errMess: errMess.length > 0 ? errMess : null,
        errType: $(ipt).data('error-type') || 'tooltip'
    };
    if (valid.type === 'editor') {
        valid.editorId = baseIpt.attr('id');
        valid.editorTextarea = baseIpt;
    }
    const form = ipt.closest('form');
    if (customMess && customMess.length > 0) {
        return _make_check_invalid(ipt, valid, customMess);
    }
    // Check bắt buộc dạng nhập
    if (!valid.allowedEmpty && (valid.type == 'email' || valid.type == 'text' || valid.type == 'password' || valid.type == 'phone' || valid.type == 'tel') && (
        trim(ipt.val()) == valid.empty ||
        (valid.minLen >= 0 && trim(ipt.val()).length < valid.minLen) ||
        (valid.maxLen >= 0 && trim(ipt.val()).length > valid.maxLen)
    )) {
        let mess = nv_required;
        if (valid.minLen >=0 && valid.maxLen >= 0) {
            mess = nv_rangelength.replace('{0}', valid.minLen).replace('{1}', valid.maxLen);
        } else if (valid.minLen >= 0) {
            mess = nv_minlength.replace('{0}', valid.minLen);
        } else if (valid.maxLen >= 0) {
            mess = nv_maxlength.replace('{0}', valid.maxLen);
        }
        return _make_check_invalid(ipt, valid, valid.errMess || mess);
    }
    // Check bắt buộc dạng chọn checkbox, radio trên item cuối của cùng nhóm name
    if ((valid.type === 'radio' || valid.type === 'checkbox')) {
        const checked = $(_get_input_name(ipt), form).filter(':checked').length;
        if (checked < valid.min || checked > valid.max) {
            let mess;
            if (valid.min < 1) {
                mess = nv_maxcheck.replace('{0}', valid.max);
            } else if (valid.max < 1) {
                mess = nv_mincheck.replace('{0}', valid.min);
            } else {
                mess = nv_rangecheck.replace('{0}', valid.min).replace('{1}', valid.max);
            }
            return _make_check_invalid(ipt, valid, valid.errMess || mess);
        }
    }
    // Check rule
    if (valid.type == 'email' && !nv_mailfilter.test(trim(ipt.val()))) {
        return _make_check_invalid(ipt, valid, valid.errMess || nv_email);
    }
    // Check editor
    if (valid.type == 'editor') {
        if (window.nveditor && window.nveditor[valid.editorId]) {
            // Trường hợp có trình soạn thảo được render ra
            const editor = window.nveditor[valid.editorId];
            if (editor.getData().trim().length < 1) {
                return _make_check_invalid(ipt, valid, valid.errMess || nv_required);
            }
        } else if (trim(valid.editorTextarea.val()).length < 1) {
            // Trường hợp không có quyền sử dụng trình soạn thảo, chỉ có textarea
            return _make_check_invalid(ipt, valid, valid.errMess || nv_required);
        }
    }
}

/**
 * Xác định name thẻ input trong trường hợp có multiple
 *
 * @param {JQuery} ipt
 * @returns {String}
 */
function _get_input_name(ipt) {
    const name = ipt.attr('name');
    const matches = name.match(/\[/g);
    const groupCount = matches ? matches.length : 0;

    if (groupCount > 1) {
        return `[name^="${name.replace(/\[[^\]]*\]$/, '')}"]`;
    }
    if (groupCount === 1) {
        return `[name^="${name.split('[')[0]}["]`;
    }
    return `[name="${name}"]`;
}

/**
 * Focus vào input bị lỗi đầu tiên trong form hoặc input chỉ định
 *
 * @param {JQuery} form
 * @param {JQuery | undefined} ipt
 * @returns {Boolean}
 */
function _focus_error(form, ipt) {
    const invalid = form.find('.is-invalid').first();
    if (invalid.length < 1) {
        return true;
    }
    const type = (invalid.attr('type') || 'text').toLowerCase();
    if (type !== 'radio' && type !== 'checkbox') {
        invalid.focus();
    } else {
        const rect = invalid[0].getBoundingClientRect();
        if (rect.top < 0 || rect.bottom > window.innerHeight) {
            invalid[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    return false;
}

/**
 * @param { JQuery<HTMLElement> } ipt
 * @returns
 */
var nv_resetInputValid = (ipt) => {
    const form = ipt.closest('form');
    if (form.length < 1 || (!form.is('[data-toggle="ajax-form"]') && !form.is('[data-precheck="nv_precheck_form"]'))) {
        return;
    }
    const type = (ipt.attr('type') || 'text').toLowerCase();

    if (type === 'radio' || type === 'checkbox') {
        $(_get_input_name(ipt), form).removeClass('is-invalid is-valid');
        return;
    } else if (ipt.is('select')) {
        ipt.removeClass('is-invalid is-valid');
        if (ipt.parent().is('.input-group')) {
            ipt.parent().removeClass('is-invalid is-valid');
        }
    } else {
        let pr = ipt.parent();
        let prAlso = ipt.parent().is('.input-group');
        ipt.removeClass('is-invalid is-valid');
        if (prAlso) pr.removeClass('is-invalid is-valid');
    }
};

/**
 * Hàm kiểm tra validate form trước khi submit mặc định
 *
 * @param {HTMLElement | JQuery} form
 * @returns {Boolean}
 */
function nv_precheck_form(form) {
    if (!(form instanceof jQuery)) {
        form = $(form);
    }

    $('.is-invalid', form).removeClass('is-invalid');
    $('.is-valid', form).removeClass('is-valid');
    $('.invalid-tooltip, .valid-feedback', form).text('');

    const processedNames = new Set();
    // Input thông thường theo bootstrap
    $('[data-valid][name]', form).each(function() {
        const ipt = $(this);
        const sName = _get_input_name(ipt);
        if (processedNames.has(sName)) {
            return;
        }
        processedNames.add(sName);
        _check_invalid($(sName, form).last());
    });
    // Các cấu trúc đặc biệt như trình soạn thảo
    $('[data-valid][data-name]', form).each(function() {
        const ipt = $(this);
        const sName = `[name="${ipt.data('name')}"]`;
        if (processedNames.has(sName)) {
            return;
        }
        processedNames.add(sName);
        _check_invalid($(sName, form).last(), null, ipt.data('valid'));
    });

    return _focus_error(form);
}

$(function() {
    // Đồng hồ
    const sClock = $('#site-digital-clock');
    if (sClock.length) {
        setInterval(() => {
            nv_my_dst && nv_is_dst() && (nv_my_ofs += 1);
            const newDate = new Date();
            newDate.setHours(newDate.getHours() + (newDate.getTimezoneOffset() / 60) + nv_my_ofs);
            sClock.text(nv_format_date(sClock.data('format'), newDate));
        }, 1000);
    }

    // Thanh menu ngang mặc định
    const menu = $('[data-toggle="main-nav"]');
    if (menu.length == 1) {
        const buildMenu = () => {
            const iExpanded = $('[data-toggle="item-expanded"]', menu);
            menu.removeClass('processed has-expanded no-expanded');
            $('[data-toggle="item-lev-1"]', menu).removeClass('d-none');
            $('[data-toggle="submenu"]', menu).removeClass('submenu-end');
            iExpanded.find('ul').remove();
            if (nukeviet.isMScreen()) {
                return;
            }

            const expandWidth = iExpanded.innerWidth();
            let menuWidth = menu.innerWidth();
            let expanded = false;
            let sWidth = 0;
            $('[data-toggle="item-lev-1"]', menu).each(function() {
                sWidth += $(this).innerWidth();
                if (sWidth > menuWidth) {
                    expanded = true;
                }
            });
            if (!expanded) {
                menu.addClass('processed no-expanded');
                return;
            }

            const stacks = [];
            menuWidth = menu.innerWidth() - expandWidth;
            sWidth = 0;
            $('[data-toggle="item-lev-1"]', menu).each(function() {
                sWidth += $(this).innerWidth();
                if (sWidth > menuWidth) {
                    stacks.push($(this).clone());
                    $(this).addClass('d-none');
                }
            });
            menu.addClass('processed has-expanded');
            const mExpanded = $('<ul data-toggle="submenu"></ul>');
            stacks.forEach((item) => {
                mExpanded.append(item);
            });
            iExpanded.append(mExpanded);

            // Bố trí lại vị trí các submenu
            $('[data-toggle="submenu"]', menu).each(function() {
                if (document.documentElement.dir === 'rtl') {
                    if (this.getBoundingClientRect().left < 0) {
                        $(this).addClass('submenu-end');
                    }
                } else if (this.getBoundingClientRect().right > document.documentElement.clientWidth) {
                    $(this).addClass('submenu-end');
                }
            });
        };
        let timer = null;

        $(window).on('resize', function() {
            clearTimeout(timer);
            timer = setTimeout(buildMenu, 50);
        });
        buildMenu();

        // Ẩn hiện submenu trên mobile
        $('[data-toggle="subtg"]', menu).on('click', function(e) {
            e.preventDefault();
            if (!nukeviet.isMScreen()) {
                return;
            }

            const btn = $(this);
            const pMenu = btn.closest('li');
            const sMenu = $('> ul:first', pMenu);

            if (pMenu.is('.opening') || pMenu.is('.closing') || sMenu.length !== 1) {
                return;
            }
            if (pMenu.is('.open')) {
                // Thu gọn
                pMenu.addClass('closing');
                sMenu[0].style.height = sMenu[0].scrollHeight + "px";
                setTimeout(() => {
                    sMenu[0].style.height = 0;
                }, 1);
                setTimeout(() => {
                    pMenu.removeClass('closing open');
                    sMenu[0].style.height = null;
                }, 150);
                return;
            }
            // Mở rộng
            pMenu.addClass('opening');
            sMenu[0].style.height = sMenu[0].scrollHeight + "px";
            setTimeout(() => {
                pMenu.addClass('open').removeClass('opening');
                sMenu[0].style.height = null;
            }, 150);
        });
    }

    // Xử lý đóng mở menu mobile
    const mainNavToggler = $('[data-toggle="toggle-main-nav"]');
    mainNavToggler.on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const hd = $('[data-toggle="site-header"]');
        const body = document.getElementsByTagName('body')[0];

        if (hd.is('.showing') || hd.is('.hiding')) {
            return;
        }
        btn.toggleClass('active');
        if (hd.is('.show')) {
            // Ẩn
            hd.addClass('hiding');
            hd.data('backdrop').removeClass('show');

            setTimeout(() => {
                hd.removeClass('show showing hiding');
                hd.data('backdrop').remove();
                hd.data('backdrop', null);

                body.style.overflow = hd.data('body-overflow');
                body.style.paddingRight = hd.data('body-padding-right');
                if (body.getAttribute('style') === '') {
                    body.removeAttribute('style');
                }
            }, 300);
            return;
        }

        // Hiện
        const backDrop = $('<div class="site-header-backdrop fade"></div>');
        backDrop.on('click', function() {
            btn.trigger('click');
        });
        hd.addClass('showing');
        hd.after(backDrop);
        hd.data('backdrop', backDrop);

        hd.data('body-overflow', body.style.overflow);
        hd.data('body-padding-right', body.style.paddingRight);
        body.style.overflow = 'hidden';
        if (document.documentElement.scrollHeight > window.innerHeight) {
            body.style.paddingRight = nukeviet.getScrollbarWidth() + 'px';
        }

        setTimeout(() => {
            backDrop.addClass('show');
        }, 1);
        setTimeout(() => {
            hd.addClass('show').removeClass('showing');
        }, 300);
    });
    $(window).on('resize', function() {
        // Đang mở menu mobile mà resize thì tự đóng
        if (mainNavToggler.is('.active') && !nukeviet.isMScreen()) {
            mainNavToggler.trigger('click');
        }
    });

    // Google map
    $('.company-map-modal').on('show.bs.modal', function() {
        if (!$('iframe', this).length) {
            $('.modal-body', this).html('<iframe class="w-100 fh-300" frameborder="0" src="' + $(this).data('src') +'" allowfullscreen></iframe>')
        }
    });

    // QR-code
    $('[data-toggle="siteQrCode"').on('show.bs.dropdown', function(e) {
        const btn = $(e.target);
        const ctn = btn.closest('.dropup');
        if (btn.data('load') != 'no') {
            return;
        }
        btn.data('load', 'loading');
        const img = new Image;
        $(img).on('load', function() {
            $('img', ctn).attr('src', img.src);
            $('.loader', ctn).addClass('d-none');
            btn.data('load', 'yes');
        });
        img.src = nv_base_siteurl + "index.php?second=qr&u=" + encodeURIComponent($(btn).data("url"));
    });

    // Đổi kiểu giao diện
    $('[data-toggle="siteThemeModeChange"]').on('click', function(e) {
        e.preventDefault();
        window.location.href = $(this).data('type');
    });

    // Tooltip mặc định của block: Ảnh + Mô tả
    ([...document.querySelectorAll('[data-toggle="tooltipArticle"]')].map(tipEl => new bootstrap.Tooltip(tipEl, {
        boundary: document.body,
        container: 'body',
        html: true,
        trigger: 'hover',
        title: () => {
            let html = '';
            if ($(tipEl).data('img') != '') {
                html += '<div class="img"><div class="img-inner"><img src="' + $(tipEl).data('img') + '" alt="' + $(tipEl).data('alt') + '"></div></div>';
            }
            html += '<div class="tip-body">' + $(tipEl).data('hometext') + '</div>';
            return html;
        },
        template: '<div class="tooltip tooltip-block-articles" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
    })));

    // Xử lý các form ajax mặc định
    $('body').on('submit', '[data-toggle="ajax-form"]', function(e) {
        e.preventDefault();

        const form = $(this);
        if (!_focus_error(form)) {
            return;
        }

        $('.is-invalid', form).removeClass('is-invalid');
        $('.is-valid', form).removeClass('is-valid');

        if (typeof(CKEDITOR) !== 'undefined') {
            for (let instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
                CKEDITOR.instances[instance].setReadOnly(true);
            }
        }

        const processSuccess = (respon) => {
            if (respon.status == 'OK' || respon.status == 'ok' || respon.status == 'success') {
                let cb;
                const callback = form.data('callback');
                if ('function' === typeof callback) {
                    cb = callback(respon, form);
                } else if ('string' == typeof callback && "function" === typeof window[callback]) {
                    cb = window[callback](respon, form);
                }
                if (cb === 0 || cb === false) {
                    return;
                }
                let timeout = 0;
                if (respon.mess) {
                    nukeviet.toast(respon.mess, respon.warning ? 'warning' : 'success');
                    timeout = respon.timeout ? respon.timeout : 2000;
                }
                if (respon.redirect) {
                    setTimeout(() => {
                        window.location.href = respon.redirect;
                    }, timeout);
                } else if (respon.refresh) {
                    setTimeout(() => {
                        window.location.reload();
                    }, timeout);
                } else {
                    setTimeout(() => {
                        $('input, textarea, select, button', form).prop('disabled', false);
                        if (typeof(CKEDITOR) !== 'undefined') {
                            for (let instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].setReadOnly(false);
                            }
                        }
                    }, 1000);
                }
                return;
            }
            // Gửi form thất bại
            $('input, textarea, select, button', form).prop('disabled', false);
            if (respon.tab) {
                bootstrap.Tab.getOrCreateInstance(document.getElementById(respon.tab)).show();
            }
            if (respon.input) {
                const ipt = respon.input.includes('[') ? $('[name^="' + respon.input + '"]', form) : $('[name="' + respon.input + '"]', form);
                if (ipt.length) {
                    _check_invalid(ipt.last(), respon.mess);
                    _focus_error(form);
                    return;
                }
            }
            nukeviet.toast(respon.mess, 'error');
        };
        const processError = (xhr, text, err) => {
            $('input, textarea, select, button', form).prop('disabled', false);
            nukeviet.toast(err || text, 'error');
            console.log(xhr, text, err);
        };
        const ajOptions = {
            url: form.attr('action'),
            type: (form.attr('method') || 'POST').toUpperCase(),
            dataType: 'json',
            cache: false,
            success: processSuccess,
            error: processError
        };
        if (ajOptions.type === 'POST') {
            ajOptions.data = (new FormData(form[0]));
            ajOptions.processData = false;
            ajOptions.contentType = false;
        } else {
            ajOptions.data = form.serialize();
        }
        $('input, textarea, select, button', form).prop('disabled', true);
        $.ajax(ajOptions);
    });

    $(document).on('change keyup', '[data-valid]', function(e) {
        if (e.type === "keyup" && e.which === 13) {
            return;
        }
        nv_resetInputValid($(this));
    });

    // Nút reset form
    $(document).on('click', '[data-toggle="nv-reset-form"]', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        if (form.length < 1) {
            return;
        }
        form[0].reset();
        formChangeCaptcha(form);

        // Reset trình soạn thảo
        $('textarea[id]', form).each(function() {
            if (window.nveditor && window.nveditor[this.id]) {
                window.nveditor[this.id].setData('');
            }
        });

        // Reset trạng thái validate
        $('[data-valid]', form).each(function() {
            nv_resetInputValid($(this));
        });

        // Hàm reset riêng nếu có
        const resetExtend = form.data('reset-extend');
        if (resetExtend && typeof window[resetExtend] === 'function') {
            window[resetExtend](form);
        }
    });

    // Tooltip
    ([...document.querySelectorAll('[data-bs-toggle="tooltip"]')].map(tipEle => {
        const dataToggle = tipEle.getAttribute('data-toggle');
        if (!dataToggle || !dataToggle.startsWith('tooltip')) {
            new bootstrap.Tooltip(tipEle);
        }
    }));

    // Popover
    ([...document.querySelectorAll('[data-bs-toggle="popover"]')].map(popEle => new bootstrap.Popover(popEle)));

    // Default toasts
    ([...document.querySelectorAll('.toast')].map(toastEl => new bootstrap.Toast(toastEl)));
});
