/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if ($('#my-role-api').length) {
        var myroleapi = $('#my-role-api'),
            myroleapi_url = myroleapi.data('page-url');

        $('.credential-activate, .credential-deactivate', myroleapi).on('click', function() {
            var role_id = $(this).parents('.item').data('role-id');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'changeActivate=' + role_id,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(a.mess);
                } else if ('OK' == a.status) {
                    location.reload()
                }
            })
        });

        var credential_auth = $('#credential_auth');
        var clipboard = new ClipboardJS('[data-toggle=clipboard]');
        clipboard.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });

        $('.create_authentication', credential_auth).on('click', function(e) {
            var method = $(this).data('method');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'createAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(e.mess)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ident]', credential_auth).val(a.ident);
                    $('[name=' + method + '_secret]', credential_auth).val(a.secret);
                    $('[name=' + method + '_ips]', credential_auth).parents('.api_ips').slideDown()
                }
            })
        });

        $('.delete_authentication', credential_auth).on('click', function(e) {
            var method = $(this).data('method');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'delAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('OK' == a.status) {
                    $('[name=' + method + '_ident]', credential_auth).val('');
                    $('[name=' + method + '_secret]', credential_auth).val('');
                    $('[name=' + method + '_ips]', credential_auth).val('').parents('.api_ips').slideUp()
                }
            })
        });

        credential_auth.on('input', '.ips', function() {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
        credential_auth.on('click', '.api_ips_update', function() {
            var method = $(this).data('method'),
                ips = $('[name=' + method + '_ips]', credential_auth).val();
            $('.ips, .api_ips_update', credential_auth).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'ipsUpdate=' + ips + '&method=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(a.mess);
                    $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ips]', credential_auth).val(a.ips);
                    setTimeout(function() {
                        $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                    }, 1000);
                }
            })
        })
    }
});
