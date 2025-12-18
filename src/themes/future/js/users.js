/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

(function () {
    // User js có thể bị loại nhiều lần do cơ chế global nên xử lý để chạy code trong này chỉ một lần
    if (window.__userJsLoaded) {
        return;
    }
    window.__userJsLoaded = true;

    $(function() {
        // Xử lý khi ấn nút đăng nhập ở block user Button
        $('[data-toggle="userLoginBlButton"]').each(function() {
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
        $('body').on('click', '[data-toggle="bt_logout"]', function(e) {
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
        $('body').on('click', '[data-toggle="changeAvatar"][data-url]', function(e) {
            e.preventDefault();
            if (!nv_safemode) {
                nv_open_browse($(this).data('url'), 'ChangeAvatar', 650, 430, 'resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
            }
        });
    });

    window.userLoginRun = () => {
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
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
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
                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
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
                                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
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
                                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                        err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                                    }
                                });
                            }).catch(error => {
                                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                            });
                        } catch (error) {
                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                            err.text(nukeviet.i18n.WebAuthnErrors.get[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                        icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                        err.text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('d-none');
                    }
                });
            });
        });
    };

    $(document).ready(function() {
        // Xử lý các form đăng nhập
        window.userLoginRun();

        // Xử lý submit form đăng nhập
        $('body').on('submit', '[data-toggle=userLogin]', function(e) {
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
                        $(selTor, form).not('[type=submit]').prop('disabled', false);
                        $('[nv_resetInputValid]', form).each(function() {
                            nv_resetInputValid($(this));
                        });
                    }
                    if (response.status == 'ok') {

                    }
                    if (response.status == '2steprequire') {

                    }
                    if (response.status == 'remove2step') {

                    }
                    if (response.status == '2step') {

                    }
                    if (response.status == 'activation') {

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
    });
})();
