/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

"use strict";

$(function() {
    // Xử lý passkey
    const pkForm = $('#passkey-form');

    if (nukeviet.WebAuthnSupported) {
        $('[data-toggle="passkey-add"]', pkForm).removeClass('hidden');
        $('[data-toggle="passkey-not-supported"]', pkForm).addClass('hidden');
    } else {
        $('[data-toggle="passkey-add"]', pkForm).addClass('hidden');
        $('[data-toggle="passkey-not-supported"]', pkForm).removeClass('hidden');
    }

    // Thêm passkey
    $('[data-toggle="passkey-add"]', pkForm).on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return false;
        }
        const form = btn.closest('form');
        const ctn = btn.closest('[data-toggle="ctn"]');
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: {
                checkss: $('[name="checkss"]', form).val(),
                create_challenge: 1,
                enable_login: btn.data('enable-login')
            },
            dataType: 'json',
            success: function(response) {
                if (response.status != 'ok') {
                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                    $('[data-toggle="error"]', ctn).text(response.mess).removeClass('hidden');
                    return;
                }

                let credentialOptions = JSON.parse(response.credentialOptions);
                credentialOptions.challenge = base64UrlToArrayBuffer(credentialOptions.challenge);
                credentialOptions.user.id = base64UrlToArrayBuffer(credentialOptions.user.id);
                if (credentialOptions.excludeCredentials.length > 0) {
                    credentialOptions.excludeCredentials = credentialOptions.excludeCredentials.map(credential => {
                        credential.id = base64UrlToArrayBuffer(credential.id);
                        return credential;
                    });
                }
                navigator.credentials.create({
                    publicKey: credentialOptions
                }).then(credential => {
                    // Sau khi đăng ký thành công, gửi dữ liệu về server
                    const data = {
                        checkss: $('[name="checkss"]', form).val(),
                        save_credential: 1,
                        credential: JSON.stringify({
                            id: credential.id,
                            type: credential.type,
                            rawId: arrayBufferToBase64Url(credential.rawId),
                            response: {
                                clientDataJSON: arrayBufferToBase64Url(credential.response.clientDataJSON),
                                attestationObject: arrayBufferToBase64Url(credential.response.attestationObject),
                            }
                        }),
                        enable_login: btn.data('enable-login')
                    };
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status != 'ok') {
                                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                                $('[data-toggle="error"]', ctn).text(response.mess).removeClass('hidden');
                                return;
                            }
                            if (!btn.data('enable-login')) {
                                location.reload();
                                return;
                            }
                            nv_setCookie(nv_cookie_prefix + '_pkey', 1, 3650, true, 'Strict');
                            $('[data-toggle="md-complete-passkey"]').modal('show');
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr, status, error);
                            icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                            $('[data-toggle="error"]', ctn).text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                        }
                    });
                }).catch(error => {
                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                    $('[data-toggle="error"]', ctn).text(nukeviet.i18n.WebAuthnErrors.creat[error.name] || nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                $('[data-toggle="error"]', ctn).text(nukeviet.i18n.WebAuthnErrors.unknow).removeClass('hidden');
            }
        });
    });

    // Tải lại trang
    $('[data-toggle="passkey-reload"]').on('click', function(e) {
        e.preventDefault();
        location.reload();
    });
    $('[data-toggle="passkey-reload"]').on('hide.bs.modal', function() {
        location.reload();
    });

    // Xóa passkey
    $('[data-toggle="del"]', pkForm).on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return false;
        }
        const form = btn.closest('form');
        if (!confirm(nv_is_del_confirm[0])) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: {
                checkss: $('[name="checkss"]', form).val(),
                del: btn.data('id'),
            },
            dataType: 'json',
            success: function(response) {
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                if (response.status != 'ok') {
                    alert(response.mess);
                    return;
                }
                alert(nv_is_del_confirm[1]);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                alert(nv_is_del_confirm[2]);
            }
        });
    });

    // Sửa nickname
    $('[data-toggle="edit"]', pkForm).on('click', function(e) {
        e.preventDefault();
        const form = $('#passkey-form-nickname');
        const md = $('[data-toggle="md-edit-passkey"]');

        $('[name="nickname"]', form).val($(this).data('nickname')).data('nickname', $(this).data('nickname'));
        $('[name="id"]', form).val($(this).data('id'));
        $('.form-group', form).removeClass('has-error');

        md.modal('show');
    });
    $('#passkey-form-nickname').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const md = $('[data-toggle="md-edit-passkey"]');
        const nickname = trim($('[name="nickname"]', form).val());
        if (nickname == $('[name="nickname"]', form).data('nickname')) {
            md.modal('hide');
            return false;
        }
        if (nickname == '') {
            $('[name="nickname"]', form).focus();
            $('[name="nickname"]', form).closest('.form-group').addClass('has-error');
            return false;
        }

        const btn = $('[type="submit"]', form);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return false;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
        $.ajax({
            url: form.attr('action'),
            type: 'post',
            data: {
                checkss: $('[name="checkss"]', form).val(),
                edit: $('[name="id"]', form).val(),
                nickname: $('[name="nickname"]', form).val(),
            },
            dataType: 'json',
            success: function(response) {
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                if (response.status != 'ok') {
                    alert(response.mess);
                    return;
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                alert(error);
            }
        });
    });

    // Tooltip
    $('[data-toggle="passkey-tooltip"]').tooltip({
        container: 'body'
    });
});
