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
})();
