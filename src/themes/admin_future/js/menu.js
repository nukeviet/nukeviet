/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function () {
    if (nv_func_name === 'blocks') {
        // Mở modal thêm khối menu
        $('[data-toggle="add-block"]').on('click', function () {
            let url = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: url,
                cache: false
            }).done(function (html) {
                $('#menu-block-modal').html(html);
                let el = document.getElementById('menuBlockModal');
                if (el) {
                    initFormAjKeyboard();
                    new bootstrap.Modal(el).show();
                }
            }).fail(function (xhr, text) {
                nvToast(text, 'error');
            });
        });

        // Mở modal sửa khối menu
        $(document).on('click', '[data-toggle="edit-block"]', function () {
            let btn = $(this);
            let icon = $('i', btn);
            let orig = icon.data('icon');
            if (icon.is('.fa-spinner')) return;
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'GET',
                url: btn.data('url'),
                cache: false
            }).done(function (html) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                $('#menu-block-modal').html(html);
                let el = document.getElementById('menuBlockModal');
                if (el) {
                    initFormAjKeyboard();
                    new bootstrap.Modal(el).show();
                }
            }).fail(function (xhr, text) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                nvToast(text, 'error');
            });
        });

        // Xóa khối menu
        $('[data-toggle="delete-block"]').on('click', function () {
            let btn = $(this);
            let icon = $('i', btn);
            let orig = icon.data('icon');
            if (icon.is('.fa-spinner')) return;
            nvConfirm(nv_is_del_confirm[0], function () {
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks&nocache=' + new Date().getTime(),
                    data: {
                        del: 1,
                        id: btn.data('id'),
                        checkss: btn.data('tokend')
                    },
                    dataType: 'json',
                    cache: false
                }).done(function (res) {
                    if (res.status === 'OK') {
                        location.reload();
                    } else {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(res.mess, 'error');
                    }
                }).fail(function (xhr, text) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    nvToast(text, 'error');
                });
            });
        });
    }
});
