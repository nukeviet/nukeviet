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
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + btn.data('module') + '&' + nv_fc_variable + '=logout&nocache=' + new Date().getTime(),
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

    // Nút đổi ảnh đại diện
    $(document).off('click.users', '[data-toggle="changeAvatar"][data-url]').on('click.users', '[data-toggle="changeAvatar"][data-url]', function(e) {
        e.preventDefault();
        if (!nv_safemode) {
            nv_open_browse($(this).data('url'), 'ChangeAvatar', 650, 430, 'resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
        }
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
                        <a href="${response.input}">${response.mess}</a>
                    `).addClass('alert alert-info');
                    $('[data-area="form"]', form).hide();
                    $('[data-area="other-form"]', form).hide();
                    return;
                }
                if (response.status == 'remove2step') {
                    window.location.href = response.input;;
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
                        <a href="${response.input}">${response.mess}</a>
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
});
