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
        var clipboard1 = new ClipboardJS('#credential_ident_btn');
        var clipboard2 = new ClipboardJS('#credential_secret_btn');
        clipboard1.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });
        clipboard2.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });

        $('.create_authentication', credential_auth).on('click', function(e) {
            $('.has-error', credential_auth).removeClass('has-error');
            $('.auth-info', credential_auth).text($('.auth-info', credential_auth).data('default'));
            var method = $('[name=method]', credential_auth).val();
            if (method == '') {
                $('[name=method]', credential_auth).parent().addClass('has-error');
                $('.auth-info', credential_auth).text($('.auth-info', credential_auth).data('error')).parent().addClass('has-error');
                $('[name=method]', credential_auth).focus();
                return !1
            }

            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'createAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    $('[name=method]', credential_auth).parent().addClass('has-error');
                    $('.auth-info', credential_auth).text($('.auth-info', credential_auth).data('error')).parent().addClass('has-error');
                    $('[name=method]', credential_auth).focus();
                } else if ('OK' == a.status) {
                    $('[name=ident]', credential_auth).val(a.ident);
                    $('[name=secret]', credential_auth).val(a.secret);
                    $('.api_ips', credential_auth).slideDown()
                }
            })
        });
        $('[name=ips]', credential_auth).on('input', function() {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
        $('.api_ips_update', credential_auth).on('click', function() {
            var ips = $('[name=ips]', credential_auth).val();
            $('[name=ips], .api_ips_update', credential_auth).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'ipsUpdate=' + ips
            }).done(function(a) {
                $('[name=ips]', credential_auth).val(a);
                setTimeout(function() {
                    $('[name=ips], .api_ips_update', credential_auth).prop('disabled', false)
                }, 1000);
            })
        })
    }
});
