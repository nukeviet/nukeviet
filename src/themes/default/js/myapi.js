/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    if ($('#my-role-api').length) {
        const myroleapi = $('#my-role-api'),
            myroleapi_url = myroleapi.data('page-url'),
            myroleapi_checkss = myroleapi.data('checkss');

        // Kích hoạt / hủy kích hoạt quyền
        $('.credential-activate, .credential-deactivate', myroleapi).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const role_id = btn.closest('.item').data('role-id');
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    changeActivate: role_id,
                    checkss: myroleapi_checkss
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    } else if (res.status == 'OK') {
                        location.reload();
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                }
            });
        });

        // Sao chép thông tin xác thực vào clipboard
        const credential_auth = $('#credential_auth');
        credential_auth.on('click', '[data-bs-toggle="clipboard"]', function(e) {
            e.preventDefault();
        });

        const clipboard = new ClipboardJS('[data-bs-toggle="clipboard"]', {
            container: credential_auth.length ? credential_auth[0] : document.body,
            target: function (trigger) {
                return document.querySelector(trigger.getAttribute('data-bs-target'));
            }
        });
        clipboard.on('success', function(e) {
            const tooltip = bootstrap.Tooltip.getOrCreateInstance(e.trigger);
            tooltip.show();
            setTimeout(function() {
                tooltip.hide();
            }, 1000);
            e.clearSelection();
        });

        // Tạo mới thông tin xác thực
        $('.create_authentication', credential_auth).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const method = btn.data('method');
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    createAuth: method,
                    checkss: myroleapi_checkss
                },
                dataType: 'json',
                success: function(res) {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                    } else if (res.status == 'OK') {
                        $('[name=' + method + '_ident]', credential_auth).val(res.ident);
                        $('[name=' + method + '_secret]', credential_auth).val(res.secret);
                        $('[name=' + method + '_ips]', credential_auth).closest('.api_ips').slideDown();
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                }
            });
        });

        // Xóa thông tin xác thực
        $('.delete_authentication', credential_auth).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            const method = btn.data('method');
            nukeviet.confirm(nv_is_del_confirm[0], () => {
                if (!icon.data('icon')) {
                    icon.data('icon', icon.attr('class'));
                }
                icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

                $.ajax({
                    type: 'POST',
                    url: myroleapi_url,
                    cache: false,
                    data: {
                        delAuth: method,
                        checkss: myroleapi_checkss
                    },
                    dataType: 'json',
                    success: function(res) {
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                        if (res.status == 'OK') {
                            $('[name=' + method + '_ident]', credential_auth).val('');
                            $('[name=' + method + '_secret]', credential_auth).val('');
                            $('[name=' + method + '_ips]', credential_auth).val('').closest('.api_ips').slideUp();
                        }
                    },
                    error: function() {
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    }
                });
            });
        });

        // Cập nhật IPs
        credential_auth.on('input', '.ips', function() {
            const $this = $(this);
            clearTimeout($this.data('timer'));
            $this.data('timer', setTimeout(() => {
                const val = $this.val();
                if (/[\r\n\v]/.test(val)) {
                    $this.val(val.replace(/[\r\n\v]+/g, ''));
                }
            }, 300));
        });
        credential_auth.on('click', '.api_ips_update', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const method = btn.data('method'),
                ips = $('[name=' + method + '_ips]', credential_auth).val().replace(/[\r\n\v]+/g, '');
            $('.ips, .api_ips_update', credential_auth).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    ipsUpdate: ips,
                    method: method,
                    checkss: myroleapi_checkss
                },
                dataType: 'json',
                success: function(res) {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                        $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                    } else if (res.status == 'OK') {
                        $('[name=' + method + '_ips]', credential_auth).val(res.ips);
                        nukeviet.toast(res.mess || 'OK', 'success');
                        setTimeout(function() {
                            $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                        }, 1000);
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                }
            });
        });
    }
});
