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
 * User js có thể bị gọi nhiều lần do cơ chế global
 * nên mọi code thêm vào đây cần kiểm soát để chỉ chạy một lần trên một phần tử
 * cho dù script này được gọi nhiều lần
 */
$(function() {
    // Xử lý khi ấn nút đăng nhập ở block user Button
    $('[data-toggle="userLoginBlButton"]').each(function() {
        if ($(this).data('event-inited')) {
            return;
        }
        $(this).data('event-inited', true);

        $(this)[0].addEventListener('show.bs.dropdown', event => {
            const btn = $(event.relatedTarget);
            if (btn.data('loaded')) {
                console.log('Đã load rồi');
                return;
            }
            btn.data('loaded', 1);
            $.ajax({
                type: 'POST',
                url: btn.data('url'),
                cache: !1,
                data: {
                    nv_ajax: 1,
                    nv_redirect: btn.data('redirect')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.sso) {
                        window.location.href = res.sso;
                        return !1;
                    }
                    if (res.reload) {
                        location.reload();
                        return !1;
                    }
                    btn.siblings('.dropdown-menu').first().html(res.html);
                    change_captcha();
                },
                error: function(xhr, text, err) {
                    btn.data('loaded', 0);
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Nút đăng xuất
    $(document).off('click.users', '[data-toggle="bt_logout"]').on('click.users', '[data-toggle="bt_logout"]', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        const form = btn.closest('[data-toggle="form"]');
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: false,
            url: btn.data('url') || (nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + btn.data('module') + '&' + nv_fc_variable + '=logout&nocache=' + new Date().getTime()),
            data: 'nv_ajax_login=1',
            dataType: 'html',
            success: function(res) {
                $('[data-toggle="ct"]', form).remove();
                $('[data-toggle="message"]', form).html(res).removeClass('d-none');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xử lý cảnh báo của sổ WebView trên tất cả các form đăng nhập
    if (isInAppBrowser()) {
        $('form[data-toggle="userLogin"]').each(function() {
            const form = $(this);
            if (form.data('isinappbrowser-initialized')) {
                return;
            }
            form.data('isinappbrowser-initialized', true);

            const thirdPartyLogin = ($('[data-toggle="openID_load"]', form).length > 0 || $('.g_id_signin', form).length > 0);
            const ele = $('[data-toggle="webview-warning"]', form);
            let message = '';
            if (thirdPartyLogin && nukeviet.WebAuthnSupported) {
                message = form.data('note-webview1');
            } else if (nukeviet.WebAuthnSupported && !thirdPartyLogin) {
                message = form.data('note-webview2');
            } else if (thirdPartyLogin && !nukeviet.WebAuthnSupported) {
                message = form.data('note-webview3');
            }
            if (message) {
                ele.removeClass('d-none');
                ele.html(message);
            }
        });
    }

    // Xử lý passkey trên toàn bộ các form đăng nhập
    $('form[data-toggle="userLogin"]').each(function() {
        const form = $(this);
        if (form.data('passkey-initialized') || !nukeviet.WebAuthnSupported) {
            return;
        }
        form.data('passkey-initialized', true);

        const ctn = $('[data-area="passkey-ctn"]', form);
        const link = $('[data-toggle="passkey-link"]', ctn);
        const btn = $('[data-toggle="passkey-btn"]', ctn);
        const err = $('[data-area="passkey-error"]', ctn);
        const icon = $('i', btn);

        ctn.removeClass('d-none');

        if (nv_getCookie(nv_cookie_prefix + '_pkey') == 1) {
            btn.removeClass('d-none');
        } else {
            link.removeClass('d-none');
        }

        link.on('click', function(e) {
            e.preventDefault();
            link.addClass('d-none');
            btn.removeClass('d-none').trigger('click');
        });

        // Đăng nhập bằng passkey
        btn.on('click', function(e) {
            e.preventDefault();
            if (icon.is('.fa-spinner')) {
                return;
            }
            err.text('').addClass('d-none');
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: {
                    login_with_passkey: 1,
                    checkss: $('[name="_csrf"]', form).val(),
                    create_challenge: 1,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status != 'ok') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        err.text(response.mess || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                        return;
                    }

                    let requestOptions = JSON.parse(response.requestOptions);
                    requestOptions.challenge = base64UrlToArrayBuffer(requestOptions.challenge);

                    try {
                        navigator.credentials.get({
                            publicKey: requestOptions
                        }).then(assertion => {
                            const data = {
                                login_with_passkey: 1,
                                checkss: $('[name="_csrf"]', form).val(),
                                auth_assertion: 1,
                                nv_redirect: $('[name="nv_redirect"]', form).val(),
                                assertion: JSON.stringify({
                                    id: assertion.id,
                                    type: assertion.type,
                                    rawId: arrayBufferToBase64Url(assertion.rawId),
                                    response: {
                                        clientDataJSON: arrayBufferToBase64Url(assertion.response.clientDataJSON),
                                        authenticatorData: arrayBufferToBase64Url(assertion.response.authenticatorData),
                                        signature: arrayBufferToBase64Url(assertion.response.signature),
                                        userHandle: arrayBufferToBase64Url(assertion.response.userHandle),
                                    }
                                }),
                            };
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: data,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.status != 'ok') {
                                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                                        err.text(response.mess).removeClass('d-none');
                                        return;
                                    }
                                    nv_setCookie(nv_cookie_prefix + '_pkey', 1, 3650, true, 'Strict');
                                    $('[data-area="info"]', form).html(`
                                        ${response.mess}
                                        <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                                    `).addClass('alert alert-success');
                                    $('[data-area="form"]', form).hide();
                                    $('[data-area="other-form"]', form).hide();
                                    setTimeout(() => {
                                        if ("undefined" != typeof response.redirect && "" != response.redirect) {
                                            window.location.href = response.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    }, 3000);
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr, status, error);
                                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                                    err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                                }
                            });
                        }).catch(error => {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                        });
                    } catch (error) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    err.text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                }
            });
        });
    });

    // Xử lý xác thực 2FA bằng WebAuthn trên tất cả các form đăng nhập
    $('form[data-toggle="userLogin"]').each(function() {
        const form = $(this);
        if (form.data('passkey-verify-initialized') || !nukeviet.WebAuthnSupported) {
            return;
        }
        form.data('passkey-verify-initialized', true);

        const step2Ctn = $('[data-area="step2"]', form);
        const ctn = $('[data-item="key"]', step2Ctn);
        const btn = $('[data-toggle="passkey-verify"]', ctn);
        const err = $('[data-area="passkey-error"]', ctn);
        const icon = $('i', btn);

        btn.on('click', function(e) {
            e.preventDefault();
            if (icon.is('.fa-spinner')) {
                return;
            }
            $('[name="auth_assertion"]', form).val('');
            err.text('').addClass('d-none');
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

            const formData = new FormData(form[0]);
            formData.append('create_challenge', 1);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                success: function(response) {
                    if (response.status != 'ok') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        err.text(response.mess || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                        return;
                    }

                    let requestOptions = JSON.parse(response.requestOptions);
                    requestOptions.challenge = base64UrlToArrayBuffer(requestOptions.challenge);
                    if (requestOptions.allowCredentials.length > 0) {
                        requestOptions.allowCredentials = requestOptions.allowCredentials.map(credential => {
                            credential.id = base64UrlToArrayBuffer(credential.id);
                            return credential;
                        });
                    }

                    try {
                        navigator.credentials.get({
                            publicKey: requestOptions
                        }).then(assertion => {
                            $('[name="auth_assertion"]', form).val(JSON.stringify({
                                id: assertion.id,
                                type: assertion.type,
                                rawId: arrayBufferToBase64Url(assertion.rawId),
                                response: {
                                    clientDataJSON: arrayBufferToBase64Url(assertion.response.clientDataJSON),
                                    authenticatorData: arrayBufferToBase64Url(assertion.response.authenticatorData),
                                    signature: arrayBufferToBase64Url(assertion.response.signature),
                                    userHandle: arrayBufferToBase64Url(assertion.response.userHandle),
                                }
                            }));
                            const formData = new FormData(form[0]);
                            $.ajax({
                                url: form.attr('action'),
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: 'json',
                                cache: false,
                                success: function (response) {
                                    if (response.status != 'ok') {
                                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                                        err.text(response.mess).removeClass('d-none');
                                        return;
                                    }

                                    $('[data-area="info"]', form).html(`
                                        ${response.mess}
                                        <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                                    `).addClass('alert alert-success');
                                    $('[data-area="form"]', form).hide();
                                    $('[data-area="other-form"]', form).hide();
                                    setTimeout(() => {
                                        if ("undefined" != typeof response.redirect && "" != response.redirect) {
                                            window.location.href = response.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    }, 3000);
                                },
                                error: function (xhr, status, error) {
                                    console.log(xhr, status, error);
                                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                                    err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                                }
                            });
                        }).catch(error => {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                        });
                    } catch (error) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                    }
                },
                error: function (xhr, text, err) {
                    console.log(xhr, text, err);
                    err.text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                }
            });
        });
    });

    // Xử lý submit form đăng nhập
    $(document).off('submit.users', '[data-toggle=userLogin]').on('submit.users', '[data-toggle=userLogin]', function(e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        const selTor = 'input,button,select,textarea';
        $(selTor, form).prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (response) {
                formChangeCaptcha(form);
                if (response.status == 'error') {
                    setTimeout(() => {
                        $(selTor, form).prop('disabled', false);
                    }, 1000);
                    if (response.input && response.input.length > 0) {
                        const ipt = $('[name="' + response.input + '"]:visible', form);
                        if (ipt.length > 0) {
                            ipt.each(function() {
                                nv_validate_show($(this), response.mess, 'tooltip');
                            });
                            return ipt.first().focus();
                        }
                    }
                    return nukeviet.toast(response.mess, 'error');
                }
                if (response.status == 'ok') {
                    $('[data-area="info"]', form).html(`
                        ${response.mess}
                        <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                    `).addClass('alert alert-success');
                    $('[data-area="form"]', form).hide();
                    $('[data-area="other-form"]', form).hide();
                    setTimeout(() => {
                        if ("undefined" != typeof response.redirect && "" != response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    }, 3000);
                    return;
                }
                if (response.status == '2steprequire') {
                    $('[data-area="info"]', form).html(`
                        <a href="${response.redirect}">${response.mess}</a>
                    `).addClass('alert alert-info');
                    $('[data-area="form"]', form).hide();
                    $('[data-area="other-form"]', form).hide();
                    return;
                }
                if (response.status == 'remove2step') {
                    window.location.href = response.redirect;
                    return;
                }
                if (response.status == '2step') {
                    form.removeAttr('data-captcha data-recaptcha2 data-recaptcha3 data-turnstile');
                    $(selTor, form).prop('disabled', false);

                    // Trình duyệt không hỗ trợ passkey
                    if (response.method_key && !nukeviet.WebAuthnSupported) {
                        response.pref_method = 'app';
                        response.method_key = 0;
                    }

                    const step2Ctn = $('[data-area="step2"]', form);
                    $(`[data-item="${response.pref_method}"]`, step2Ctn).removeClass('d-none');
                    $('[data-area="step2-methods"]', step2Ctn).data('is-key', response.method_key ? 1 : 0);
                    if (!response.tfa_recovery) {
                        $('[data-toggle="2fa-choose-recovery"]', form).closest('[data-area="2fa-cctn"]').addClass('d-none');
                    } else {
                        $('[data-toggle="2fa-choose-recovery"]', form).closest('[data-area="2fa-cctn"]').removeClass('d-none');
                    }
                    if (!response.method_key || response.pref_method == 'key') {
                        $('[data-toggle="2fa-choose"][data-method="key"]', form).closest('[data-area="2fa-cctn"]').addClass('d-none');
                    } else {
                        $('[data-toggle="2fa-choose"][data-method="key"]', form).closest('[data-area="2fa-cctn"]').removeClass('d-none');
                    }
                    if (response.pref_method == 'app') {
                        $('[data-toggle="2fa-choose"][data-method="app"]', form).closest('[data-area="2fa-cctn"]').addClass('d-none');
                    } else {
                        $('[data-toggle="2fa-choose"][data-method="app"]', form).closest('[data-area="2fa-cctn"]').removeClass('d-none');
                    }

                    $('[data-area="step1"], [data-area="step2"]', form).toggleClass('d-none');
                    return;
                }
                if (response.status == 'activation') {
                    $('[data-area="info"]', form).html(`
                        <a href="${response.redirect}">${response.mess}</a>
                    `).addClass('alert alert-info');
                    return;
                }
                nukeviet.toast('Unknown error!', 'error');
            },
            error: function (xhr, status, error) {
                console.log(xhr, status, error);
                form.find('input,button,select,textarea').prop('disabled', false);
                nukeviet.toast(error || status, 'error');
            }
        });
    });

    // Chọn phương thức xác thực 2 bước khi đăng nhập
    $(document).off('click.users', '[data-toggle="2fa-choose"]').on('click.users', '[data-toggle="2fa-choose"]', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const methods = $('[data-area="step2-methods"]', form);
        $('[data-toggle="2fa-choose"]', methods).closest('[data-area="2fa-cctn"]').removeClass('d-none');
        if (!methods.data('is-key')) {
            $('[data-toggle="2fa-choose"][data-method="key"]', methods).closest('[data-area="2fa-cctn"]').addClass('d-none');
        }
        $(this).closest('[data-area="2fa-cctn"]').addClass('d-none');

        const tstepCtn = $('[data-area="step2"]', form);
        $('[type="text"]', tstepCtn).val('').each(function() {
            nv_validate_reset($(this));
        });
        $('[name="auth_assertion"]', form).val('');
        $('[data-area="passkey-error"]', form).text('').addClass('d-none');
        $('[data-item]', tstepCtn).addClass('d-none');
        $(`[data-item="${$(this).data('method')}"]`, tstepCtn).removeClass('d-none');
    });

    // Khôi phục 2FA khi không đăng nhập được
    $(document).off('click.users', '[data-toggle="2fa-choose-recovery"]').on('click.users', '[data-toggle="2fa-choose-recovery"]', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        $('[name="cant_do_2step"]', form).val('1');
        form.submit();
    });

    // Reset form 2FA tương đương tải lại trang
    $(document).off('click.users', '[data-toggle="validReset2fa"]').on('click.users', '[data-toggle="validReset2fa"]', function() {
        location.reload();
    });

    // Lưu thuộc tính captcha ban đầu của form quên mật khẩu để khôi phục khi quay về bước 1
    $('form[data-toggle="usersLostPass"]').each(function() {
        const attrs = {};
        ['data-captcha', 'data-recaptcha2', 'data-recaptcha3', 'data-turnstile'].forEach(name => {
            if (this.hasAttribute(name)) {
                attrs[name] = this.getAttribute(name);
            }
        });
        $(this).data('captcha-attrs', attrs);
    });

    // Xử lý submit form quên mật khẩu nhiều bước
    $(document).off('submit.users', '[data-toggle="usersLostPass"]').on('submit.users', '[data-toggle="usersLostPass"]', function(e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        const selTor = 'input,button,select,textarea';
        $(selTor, form).prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: data,
            dataType: 'json',
            cache: false,
            success: function(response) {
                const info = $('[data-area="info"]', form);
                if (response.status == 'ok') {
                    info.html(`
                        ${response.mess}
                        <div class="spinner-border text-success spinner-border-sm" role="status"></div>
                    `).removeClass('alert-info').addClass('alert-success');
                    $('[data-area="form"]', form).hide();
                    setTimeout(() => {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    }, 6000);
                    return;
                }

                $(selTor, form).prop('disabled', false);
                userLostpassStep(form, response.step, response.info);

                if (response.status == 'error') {
                    if (response.input) {
                        const ipt = $('[name="' + response.input + '"]:visible', form);
                        if (ipt.length > 0) {
                            nv_validate_show(ipt.first(), response.mess, 'tooltip');
                            ipt.first().focus();
                            return;
                        }
                    }
                    nukeviet.toast(response.mess, 'error');
                    if (response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 3000);
                    }
                    return;
                }

                // Chuyển sang bước tiếp theo: answer, verify, new_password
                if (response.input) {
                    $('[name="' + response.input + '"]', form).val('').focus();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
                $(selTor, form).prop('disabled', false);
                nukeviet.toast(error || status, 'error');
            }
        });
    });

    // Xử lý submit form tắt xác thực 2 bước
    $(document).off('submit.users', '[data-toggle="usersR2s"]').on('submit.users', '[data-toggle="usersR2s"]', function(e) {
        e.preventDefault();
        const form = $(this);
        const data = form.serialize();
        const selTor = 'input,button,select,textarea';
        const info = $('[data-area="info"]', form);
        $(selTor, form).prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: data,
            dataType: 'json',
            cache: false,
            success: function(response) {
                // Xử lý xong hoặc phiên làm việc kết thúc: báo kết quả rồi chuyển trang
                if (response.status == 'OK' || response.status == 'failed' || (response.status == 'error' && response.redirect)) {
                    const success = (response.status == 'OK');
                    info.html(`
                        ${response.mess}
                        <div class="spinner-border spinner-border-sm ${success ? 'text-success' : 'text-danger'}" role="status"></div>
                    `).removeClass('alert-info alert-success alert-danger').addClass(success ? 'alert-success' : 'alert-danger');
                    $('[data-area="form"]', form).hide();
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 5000);
                    return;
                }

                $(selTor, form).prop('disabled', false);

                // Mã xác minh đã gửi qua email, chuyển sang bước nhập mã
                if (response.status == 'step2') {
                    $('[name="email_sent"]', form).val(1);
                    $('[data-step="step1"]', form).addClass('d-none');
                    $('[data-step="step2"]', form).removeClass('d-none');
                    info.html(response.mess).removeClass('alert-success alert-danger').addClass('alert-info');
                    $('[name="verifykey"]', form).focus();
                    return;
                }

                if (response.input) {
                    const ipt = $('[name="' + response.input + '"]:visible', form);
                    if (ipt.length > 0) {
                        nv_validate_show(ipt.first(), response.mess, 'tooltip');
                        ipt.first().focus();
                        return;
                    }
                }
                nukeviet.toast(response.mess, 'error');
            },
            error: function(xhr, status, error) {
                console.log(xhr, status, error);
                $(selTor, form).prop('disabled', false);
                nukeviet.toast(error || status, 'error');
            }
        });
    });

    // Xử lý cho form đăng ký tài khoản
    /**
     * Hiển thị lịch chọn ngày cho một trường
     *
     * @param {JQuery} el
     */
    function userRegDatepicker(el) {
        el = $(el);
        if (!el.length || typeof $.datepicker !== 'object') {
            return;
        }
        if (!el.data('dp-init')) {
            const options = {
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'focus',
                yearRange: '-200:+0'
            };
            // Khoảng ngày cho phép khai báo ở cấu hình trường dữ liệu
            if (el.data('min-date')) {
                options.minDate = el.data('min-date');
            }
            if (el.data('max-date')) {
                options.maxDate = el.data('max-date');
                options.yearRange = '-200:+200';
            }
            el.datepicker(options);
            el.data('dp-init', true);
        }
        el.datepicker('show');
    }
    const regForm = $('form[data-form="userRegister"]');
    regForm.each(function() {
        const form = $(this);
        if (form.data('initialized')) {
            return;
        }
        form.data('initialized', true);

        // Chọn câu hỏi bảo mật từ danh sách gợi ý
        $(form).on('click', '[data-toggle="addQuestion"]', function(e) {
            e.preventDefault();
            const q = $('[name="question"]', form);
            q.val($(this).text());
            nv_validate_reset(q);
        });

        // Hiển thị lịch chọn ngày
        $(form).on('focus', '[data-provide="datepicker"]', function() {
            userRegDatepicker(this);
        });
        $(form).on('click', '[data-toggle="datepickerBtn"]', function() {
            userRegDatepicker($(this).closest('.input-group').find('[data-provide="datepicker"]'));
        });

        // Hiển thị điều khoản sử dụng trong modal
        $(form).on('click', '[data-toggle="usageTermsShow"]', function(e) {
            e.preventDefault();
            const title = $(this).data('title');
            $.ajax({
                type: 'POST',
                cache: true,
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register&nocache=' + new Date().getTime(),
                data: 'get_usage_terms=1',
                dataType: 'html',
                success: function(html) {
                    let modalEl = document.getElementById('sitemodalTerm');
                    if (!modalEl) {
                        const wrap = document.createElement('div');
                        wrap.innerHTML = `<div id="sitemodalTerm" class="modal fade" tabindex="-1">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="${nukeviet.i18n.close}"></button>
                                    </div>
                                    <div class="modal-body"></div>
                                </div>
                            </div>
                        </div>`;
                        document.body.appendChild(wrap.firstElementChild);
                        modalEl = document.getElementById('sitemodalTerm');
                    }
                    $('.modal-title', modalEl).html(title);
                    $('.modal-body', modalEl).html(html);
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            });
        });
    });

    // Thêm file cho trường dữ liệu kiểu file
    $(document).off('click.users', '[data-form="userRegister"] [data-toggle="addfilebtn"]')
    .on('click.users', '[data-form="userRegister"] [data-toggle="addfilebtn"]', function() {
        const btn = $(this);
        const filelist = btn.closest('.filelist');
        const maxnum = parseInt(filelist.data('maxnum')) || 0;
        let filenum = $('[name^="custom_fields"]', filelist).length;
        const modalEl = document.getElementById(btn.data('modal'));
        const modalObj = $(modalEl);
        const fileAccept = modalObj.data('accept');
        const maxsize = parseInt(modalObj.data('maxsize'));
        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);

        const setAddFileBtn = (num) => {
            (maxnum && num >= maxnum) ? btn.hide() : btn.show();
        };

        const updateFileInput = () => {
            const input = $('<input type="file" class="form-control">');
            if (fileAccept) {
                input.attr('accept', fileAccept);
            }
            input.on('change', function() {
                const sFileName = $(this).val();
                if (!sFileName.length) {
                    return;
                }
                // Kiểm tra phần mở rộng
                if (fileAccept) {
                    const arr = fileAccept.split(',');
                    let okExt = false;
                    for (let j = 0; j < arr.length; j++) {
                        const ext = arr[j];
                        if (sFileName.substr(sFileName.length - ext.length).toLowerCase() === ext.toLowerCase()) {
                            okExt = true;
                            break;
                        }
                    }
                    if (!okExt) {
                        updateFileInput();
                        nukeviet.toast(modalObj.data('ext-error') + ' ' + arr.join(', '), 'error');
                        return;
                    }
                }
                // Kiểm tra dung lượng
                if (typeof this.files !== 'undefined') {
                    if (this.files[0].size > maxsize) {
                        const maxKB = (maxsize / 1024).toFixed(2);
                        const curKB = (this.files[0].size / 1024).toFixed(2);
                        updateFileInput();
                        nukeviet.toast(modalObj.data('size-error') + ' (' + curKB + ' KB) ' + modalObj.data('size-error2') + ' (' + maxKB + ' KB)', 'error');
                        return;
                    }

                    const data = new FormData();
                    data.append('file', this.files[0]);
                    data.append('field', modalObj.data('field'));
                    data.append('_csrf', modalObj.data('csrf'));
                    data.append('field_fileupload', 1);
                    $.ajax({
                        type: 'POST',
                        url: modalObj.data('url'),
                        data: data,
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: 'json'
                    }).done(function(a) {
                        if (a.status === 'error') {
                            updateFileInput();
                            nukeviet.toast(a.mess, 'error');
                            return;
                        }
                        if (a.status === 'OK') {
                            const newfile = $('<li class="mt-1"><input type="checkbox" name="custom_fields[' + filelist.data('field') + '][]" value="' + a.file_key + '" class="' + filelist.data('oclass') + '" checked> ' + a.file_value + ' (<a href="javascript:void(0)" data-toggle="userfile_del">' + modalObj.data('delete') + '</a>)</li>');
                            $('[data-toggle="userfile_del"]', newfile).on('click', function() {
                                $.ajax({
                                    type: 'POST',
                                    cache: false,
                                    url: modalObj.data('url'),
                                    data: {
                                        file: a.file_key,
                                        _csrf: a.csrf,
                                        field_filedel: 1
                                    },
                                    dataType: 'json',
                                    success: function(e) {
                                        if (e.status === 'OK') {
                                            newfile.remove();
                                            --filenum;
                                            setAddFileBtn(filenum);
                                        } else {
                                            nukeviet.toast(e.mess || a.mess, 'error');
                                        }
                                    }
                                });
                            });
                            $('.items', filelist).append(newfile);
                            bsModal.hide();
                            ++filenum;
                            setAddFileBtn(filenum);
                        }
                    });
                }
            });
            $('.fileinput', modalObj).html(input);
        };

        updateFileInput();
        bsModal.show();
    });

    // Quản trị xóa tài khoản ngay tại trang chi tiết thành viên
    $('body').off('click', '[data-toggle="admindeluser"]')
    .on('click', '[data-toggle="admindeluser"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(nv_is_del_confirm[0], () => {
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: btn.data('url'),
                data: {
                    userid: btn.data('userid'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'error') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        return nukeviet.toast(res.mess || nv_is_del_confirm[2], 'error');
                    }
                    window.location.href = btn.data('back');
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    /**
     * Địa chỉ xử lý ajax của trang quản lý nhóm
     * Tệp này được load ở các module khác tuy nhiên trang quản lý nhóm
     * chỉ có tại module users, vì vậy sử dụng nv_module_name, nv_func_name luôn đúng.
     */
    const groupsUrl = () => nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime();

    /**
     * Gửi một thao tác quản lý thành viên nhóm rồi tải lại trang
     *
     * @param {JQuery} btn
     * @param {Object} postData
     * @param {String} confirmMess
     */
    const groupsPostAction = (btn, postData, confirmMess) => {
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(confirmMess, () => {
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                url: groupsUrl(),
                type: 'POST',
                data: postData,
                dataType: 'json',
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    if (res.status != 'ok') {
                        return nukeviet.toast(res.mess, 'error');
                    }
                    nukeviet.toast(res.mess, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    };

    // Duyệt, từ chối, loại khỏi nhóm và xóa hẳn tài khoản, mỗi thao tác gửi một tham số riêng
    const groupsUserActions = {
        groupApproved: 'approved',
        groupDenied: 'denied',
        groupExclude: 'exclude',
        groupDelUser: 'del'
    };
    const groupsUserActionsSelector = '[data-toggle="groupApproved"], [data-toggle="groupDenied"], [data-toggle="groupExclude"], [data-toggle="groupDelUser"]';
    $(document).off('click.users', groupsUserActionsSelector)
    .on('click.users', groupsUserActionsSelector, function(e) {
        e.preventDefault();

        const btn = $(this);
        const toggle = btn.data('toggle');
        const page = btn.closest('[data-area="page"]');
        if (typeof groupsUserActions[toggle] === 'undefined') {
            return;
        }

        const postData = {
            checkss: page.data('checkss'),
            gid: page.data('gid')
        };
        postData[groupsUserActions[toggle]] = btn.data('id');

        let confirmMess = nv_is_exclude_user_confirm[0];
        if (toggle === 'groupApproved') {
            confirmMess = nv_is_add_user_confirm[0];
        } else if (toggle === 'groupDelUser') {
            confirmMess = nv_is_del_confirm[0];
        }

        groupsPostAction(btn, postData, confirmMess);
    });

    // Ô tìm và chọn tài khoản để thêm vào nhóm
    $('[data-toggle="groupAddUserSelect"]').each(function() {
        if ($(this).data('event-inited') || typeof $.fn.select2 === 'undefined') {
            return;
        }
        $(this).data('event-inited', true);

        const ele = $(this);
        ele.select2({
            placeholder: ele.data('placeholder'),
            minimumInputLength: 3,
            ajax: {
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&get_user_json=1&gid=' + ele.data('gid') + '&checkss=' + ele.data('checkss'),
                dataType: 'json',
                delay: 250,
                cache: true,
                data: (params) => ({
                    q: params.term,
                    page: params.page
                }),
                processResults: (data, params) => {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                }
            },
            // Trả về đối tượng DOM để select2 không phải chèn HTML thô từ dữ liệu tài khoản
            templateResult: (repo) => {
                if (repo.loading) {
                    return repo.text;
                }
                return $('<div></div>')
                    .append($('<span></span>').text(repo.username))
                    .append('<br>')
                    .append($('<small class="text-muted"></small>').text('(' + repo.fullname + ')'));
            },
            templateSelection: (repo) => repo.username || repo.text,
            language: {
                inputTooShort: () => ele.data('min-search')
            }
        });
    });

    // Thêm tài khoản đã chọn vào nhóm
    $(document).off('click.users', '[data-toggle="groupAddUser"]').on('click.users', '[data-toggle="groupAddUser"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const page = btn.closest('[data-area="page"]');
        const select = $('[data-toggle="groupAddUserSelect"]', page);
        const uid = parseInt(select.val(), 10) || 0;

        if (uid < 1) {
            return nukeviet.alert(btn.data('msg-nochoice'), () => {
                select.select2('open');
            });
        }

        groupsPostAction(btn, {
            checkss: page.data('checkss'),
            gid: page.data('gid'),
            uid: uid
        }, nv_is_add_user_confirm[0]);
    });

    // Mở modal danh sách tài khoản đợi kích hoạt
    $(document).off('click.users', '[data-toggle="groupUserWaiting"]').on('click.users', '[data-toggle="groupUserWaiting"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        const page = btn.closest('[data-area="page"]');
        if (icon.is('.fa-spinner')) {
            return;
        }

        const orig = icon.data('icon');
        icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            url: groupsUrl(),
            type: 'POST',
            data: {
                checkss: page.data('checkss'),
                gid: page.data('gid'),
                getuserid: 1
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                if (res.status != 'ok') {
                    return nukeviet.toast(res.mess, 'error');
                }
                modalShow(btn.data('title'), res.html);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Tìm tài khoản đợi kích hoạt, máy chủ trả về HTML nên không dùng được handler ajax-form chung
    $(document).off('submit.users', '[data-form="groupGetUid"]').on('submit.users', '[data-form="groupGetUid"]', function(e) {
        e.preventDefault();

        const form = $(this);
        const btn = $('[type="submit"]', form);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        const orig = icon.data('icon');
        const action = form.attr('action') + '&' + form.serialize();
        icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
        $('input, button', form).prop('disabled', true);

        $.ajax({
            type: 'GET',
            url: action,
            cache: false,
            success: function(html) {
                $('#resultdata').html(html);
            },
            error: function(xhr, text, err) {
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            },
            complete: function() {
                $('input, button', form).prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
            }
        });
    });

    // Kích hoạt một tài khoản trong danh sách đợi kích hoạt
    $(document).off('click.users', '[data-toggle="groupActiveUser"]').on('click.users', '[data-toggle="groupActiveUser"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        const form = $('[data-form="groupGetUid"]');
        if (icon.is('.fa-spinner') || form.length < 1) {
            return;
        }

        const orig = icon.data('icon');
        icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: {
                checkss: $('[name="checkss"]', form).val(),
                act: 1,
                userid: btn.data('userid')
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                if (res.status != 'ok') {
                    return nukeviet.toast(res.mess, 'error');
                }
                nukeviet.toast(res.mess, 'success');
                form.trigger('submit');
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Tải danh sách thông báo của nhóm
    $('[data-toggle="groupInform"]').each(function() {
        if ($(this).data('event-inited')) {
            return;
        }
        $(this).data('event-inited', true);

        const ctn = $(this);
        $.ajax({
            type: 'GET',
            url: ctn.data('ajax-url'),
            success: function(html) {
                ctn.html(html);
            },
            error: function(xhr, text, err) {
                console.log(xhr, text, err);
            }
        });
    });

    /**
     * Địa chỉ xử lý ajax của trang bảo mật và quyền riêng tư
     * Trang chỉ có tại module users nên nv_module_name, nv_func_name luôn đúng
     */
    const privacyUrl = () => nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime();

    /**
     * Gửi thao tác đăng xuất phiên đăng nhập, chuyển sang trang xác nhận mật khẩu nếu cần
     *
     * @param {JQuery} btn
     * @param {Object} postData
     */
    const privacyLogoutAction = (btn, postData) => {
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(nukeviet.i18n.confirmAction, () => {
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                url: privacyUrl(),
                type: 'POST',
                data: postData,
                dataType: 'json',
                cache: false,
                success: function(res) {
                    if (res.status == 'not_verified') {
                        window.location.href = res.redirect;
                        return;
                    }
                    if (res.status != 'ok') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        return nukeviet.toast(res.mess, 'error');
                    }
                    nukeviet.toast(res.mess, 'success');
                    setTimeout(() => {
                        res.redirect ? window.location.href = res.redirect : location.reload();
                    }, 2000);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    };

    // Thông báo kết quả thao tác được thực hiện tự động sau khi xác nhận mật khẩu
    $('[data-area="usersSecurityPrivacy"]').each(function() {
        if ($(this).data('event-inited')) {
            return;
        }
        $(this).data('event-inited', true);

        const autoToast = $(this).data('auto-toast');
        if (autoToast) {
            nukeviet.toast(autoToast, 'success');
        }
    });

    // Tải thêm phiên đăng nhập
    $(document).off('click.users', '[data-toggle="usersLoginMore"]').on('click.users', '[data-toggle="usersLoginMore"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        const page = btn.closest('[data-area="usersSecurityPrivacy"]');
        if (icon.is('.fa-spinner')) {
            return;
        }

        const orig = icon.data('icon');
        icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
        page.data('page', (parseInt(page.data('page'), 10) || 1) + 1);
        $.ajax({
            url: privacyUrl(),
            type: 'POST',
            data: {
                checkss: page.data('checkss'),
                loadmorelogins: 1,
                login_offset: page.data('next-offset'),
                page: page.data('page')
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                if (res.status != 'ok') {
                    return nukeviet.toast(res.mess, 'error');
                }

                $('[data-area="loginsCtn"]', page).append(res.contents);
                if (res.more) {
                    page.data('next-offset', res.next_offset);
                } else {
                    $('[data-area="loginMoreCtn"]', page).remove();
                    page.data('next-offset', 0);
                }
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Đăng xuất khỏi một phiên đăng nhập
    $(document).off('click.users', '[data-toggle="usersLoginRemove"]').on('click.users', '[data-toggle="usersLoginRemove"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const page = btn.closest('[data-area="usersSecurityPrivacy"]');
        privacyLogoutAction(btn, {
            checkss: page.data('checkss'),
            dellogin: 1,
            idlogin: btn.data('idlogin'),
            page: page.data('page')
        });
    });

    // Đăng xuất khỏi tất cả các phiên khác
    $(document).off('click.users', '[data-toggle="usersLoginRemoveAll"]').on('click.users', '[data-toggle="usersLoginRemoveAll"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const page = btn.closest('[data-area="usersSecurityPrivacy"]');
        privacyLogoutAction(btn, {
            checkss: page.data('checkss'),
            delloginall: 1
        });
    });
});

/**
 * Hiển thị đúng bước của form quên mật khẩu theo phản hồi máy chủ
 * Bước 1 cần captcha, các bước sau dùng lại mã captcha đã xác thực lưu trong session
 *
 * @param {JQuery} form
 * @param {String} step
 * @param {String} info
 */
function userLostpassStep(form, step, info) {
    step = step || 'step1';
    $('[name="step"]', form).val(step);
    $('[data-step]', form).addClass('d-none');
    $('[data-step="' + step + '"]', form).removeClass('d-none');

    const infoEl = $('[data-area="info"]', form);
    infoEl.html(info ? info : infoEl.data('default'));

    const attrs = form.data('captcha-attrs') || {};
    if (step == 'step1') {
        // Mã captcha cũ đã bị hủy, khôi phục để lần gửi sau xác thực lại
        Object.keys(attrs).forEach(name => {
            form.attr(name, attrs[name]);
        });
        formChangeCaptcha(form);
    } else if (Object.keys(attrs).length > 0) {
        form.removeAttr(Object.keys(attrs).join(' '));
    }
}

/**
 * Kiểm tra nhập lại mật khẩu mới trùng với mật khẩu mới
 * Được gọi qua data-valid-callback của form quên mật khẩu
 *
 * @param {String} val
 * @param {JQuery} ipt
 * @returns {Boolean}
 */
function userLostpassRepassCheck(val, ipt) {
    return val === $('[name="new_password"]', ipt.closest('form')).val();
}

/**
 * Kiểm tra tên đăng nhập theo kiểu ký tự cho phép, độ dài do minlength/maxlength lo
 * Được gọi qua data-valid-callback của ô tên đăng nhập form đăng ký
 *
 * @param {String} val
 * @param {JQuery} ipt
 * @returns {Boolean}
 */
function userRegLoginCheck(val, ipt) {
    const type = ipt.data('login-type');

    if (type == '1' && !/^[0-9]+$/.test(val)) {
        return false;
    }
    if (type == '2' && !/^[a-z0-9]+$/i.test(val)) {
        return false;
    }
    if (type == '3' && !/^[a-z0-9]+[a-z0-9\-\_\s]+[a-z0-9]+$/i.test(val)) {
        return false;
    }
    if (type == '4' && typeof nv_unicode_login_pattern !== 'undefined' && !nv_unicode_login_pattern.test(val)) {
        return false;
    }
    return true;
}
