/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function () {
    if (nv_func_name === 'main') {
        // Thay đổi khối menu
        $('[data-toggle="change-mid"]').on('change', function () {
            const mid = $(this).val();
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&mid=' + mid;
        });

        // Mở modal thêm menu
        $('[data-toggle="add-menu"]').on('click', function () {
            const url = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: url,
                cache: false
            }).done(function (html) {
                $('#edit').html(html);
                const el = document.getElementById('menuRowModal');
                if (el) {
                    initFormAjKeyboard();
                    $('#edit [name=parentid], #edit [name=module_name], #edit [name=func], #edit [name^=groups_view]').select2({dropdownParent: $('#menuRowModal')});
                    new bootstrap.Modal(el).show();
                }
            }).fail(function (xhr, text) {
                nvToast(text, 'error');
            });
        });

        // Mở modal sửa menu
        $(document).on('click', '[data-toggle="edit-menu"]', function () {
            const btn = $(this);
            const icon = $('i', btn);
            const orig = icon.data('icon');
            if (icon.is('.fa-spinner')) return;
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'GET',
                url: btn.data('url'),
                cache: false
            }).done(function (html) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                $('#edit').html(html);
                const el = document.getElementById('menuRowModal');
                if (el) {
                    initFormAjKeyboard();
                    $('#edit [name=parentid], #edit [name=module_name], #edit [name=func], #edit [name^=groups_view]').select2({dropdownParent: $('#menuRowModal')});
                    new bootstrap.Modal(el).show();
                }
            }).fail(function (xhr, text) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                nvToast(text, 'error');
            });
        });

        // Khi thay đổi module — load danh sách func
        $(document).on('change', '#edit [name=module_name]', function () {
            const val = $(this).val();
            const funcRow = $('#edit .field-func');
            const funcSelect = $('#edit [name=func]');

            if (val === '') {
                funcRow.addClass('d-none');
                return;
            }

            $.post(
                script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
                'action=link_module&module=' + val,
                function (res) {
                    if (res !== '') {
                        funcSelect.html(res);
                        funcRow.removeClass('d-none');
                    } else {
                        funcRow.addClass('d-none');
                    }
                    funcSelect.select2({dropdownParent: $('#menuRowModal')});
                }
            );
        });

        // Khi thay đổi khối menu trong modal — load lại parentid
        $(document).on('change', '#edit [name=item_menu]', function () {
            $.post(
                script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
                'action=link_menu&mid=' + $(this).val() + '&parentid=' + $(this).data('parentid'),
                function (res) {
                    const parentSel = $('#edit [name=parentid]');
                    parentSel.html(res);
                    parentSel.select2({dropdownParent: $('#menuRowModal')});
                }
            );
        });

        // Lấy title từ module/func đang chọn
        $(document).on('click', '#edit [data-toggle="get-title"]', function () {
            const obj = $('#edit');
            const titleInput = $('[name=title]', obj);
            const module = $('[name=module_name]', obj).val();
            const opobj = $('[name=func]', obj);
            let val = '';

            if (opobj.length && opobj.val() !== '') {
                val = $.trim($('[name=func] option[value="' + opobj.val() + '"]', obj).data('title') || '');
            } else if (module !== '') {
                val = $.trim($('[name=module_name] option[value="' + module + '"]', obj).text());
            }

            titleInput.val(val).removeClass('is-invalid');
            titleInput.siblings('.invalid-feedback').text('');
        });

        // Lấy link từ module/func đang chọn
        $(document).on('click', '#edit [data-toggle="get-link"]', function () {
            const obj = $('#edit');
            const module = $('[name=module_name]', obj).val();
            const opobj = $('[name=func]', obj);

            if (opobj.length && opobj.val() !== '') {
                $('[name=link]', obj).val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module + '&' + nv_fc_variable + '=' + opobj.val());
                return;
            }
            if (module !== '') {
                $('[name=link]', obj).val(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + module);
                return;
            }
            $('[name=link]', obj).val('');
        });

        // Thay đổi thứ tự menu
        $('#menulist').on('change', '[data-toggle="change-weight"]', function () {
            const id = $(this).closest('.item').data('id');
            const mid = $('#menulist').data('mid');
            const parentid = $('#menulist').data('parentid');
            const new_weight = $(this).val();
            $.ajax({
                type: 'POST',
                url: $('#menulist').attr('action'),
                cache: false,
                data: 'action=chang_weight&mid=' + mid + '&parentid=' + parentid + '&id=' + id + '&new_weight=' + new_weight + '&checkss=' + $('#menulist').data('checkss')
            }).done(function () {
                location.reload();
            }).fail(function (xhr, text) {
                nvToast(text, 'error');
            });
        });

        // Toggle trạng thái hiển thị
        $('#menulist').on('click', '[data-toggle="change-active"]', function () {
            const id = $(this).closest('.item').data('id');
            const chk = $(this);
            chk.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: $('#menulist').attr('action'),
                cache: false,
                data: 'action=change_active&id=' + id + '&checkss=' + $('#menulist').data('checkss')
            }).done(function () {
                setTimeout(function () {
                    chk.prop('disabled', false);
                }, 1000);
            }).fail(function (xhr, text) {
                chk.prop('disabled', false);
                nvToast(text, 'error');
            });
        });

        // Xóa một menu item
        $('#menulist').on('click', '[data-toggle="item-delete"]', function () {
            const item = $(this).closest('.item');
            const id = item.data('id');
            const mid = $('#menulist').data('mid');
            const parentid = $('#menulist').data('parentid');
            const num = parseInt(item.data('num'));
            const catLang = (typeof cat !== 'undefined') ? cat : '';
            const catonLang = (typeof caton !== 'undefined') ? caton : '';
            const msg = num ? catLang + num + catonLang : nv_is_del_confirm[0];

            nvConfirm(msg, function () {
                $.post(
                    script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
                    'action=delete&id=' + id + '&parentid=' + parentid + '&mid=' + mid + '&checkss=' + $('#menulist').data('checkss'),
                    function () {
                        location.reload();
                    }
                );
            });
        });

        // Xóa nhiều menu
        $('[data-toggle="multi-delete"]').on('click', function () {
            const mid = $('#menulist').data('mid');
            const parentid = $('#menulist').data('parentid');
            const list = [];
            $('#menulist [name^="idcheck"]:checked').each(function () {
                list.push($(this).val());
            });

            if (!list.length) {
                nvAlert($(this).data('error'));
                return;
            }

            nvConfirm(nv_is_del_confirm[0], function () {
                $.ajax({
                    type: 'POST',
                    url: $('#menulist').attr('action'),
                    cache: false,
                    data: 'action=delete&mid=' + mid + '&parentid=' + parentid + '&idcheck=' + list.join(',') + '&checkss=' + $('#menulist').data('checkss')
                }).done(function () {
                    location.reload();
                }).fail(function (xhr, text) {
                    nvToast(text, 'error');
                });
            });
        });

        // Nạp lại các thành phần con của menu
        $('#menulist').on('click', '[data-toggle="menu-reload"]', function () {
            const btn = $(this);
            const icon = $('i', btn);
            const orig = icon.data('icon');
            if (icon.is('.fa-spinner')) return;

            nvConfirm($('#menulist').data('reload-confirm'), function () {
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.post(
                    $('#menulist').attr('action'),
                    'reload=1&mid=' + $('#menulist').data('mid') + '&id=' + btn.closest('.item').data('id') + '&checkss=' + $('#menulist').data('checkss'),
                    function (res) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        location.reload();
                    }
                );
            });
        });

        // Khởi tạo popover cho tiêu đề có link
        $('#menulist [data-toggle="popover-link"]').each(function () {
            const el = this;
            new bootstrap.Popover(el, {
                html: true,
                trigger: 'click',
                placement: 'top',
                content: function () {
                    const url = $(el).data('contents');
                    return '<a href="' + url + '" target="_blank" rel="noopener">' + url + '</a>';
                }
            });
        });

        $('body').on('click', function (e) {
            $('#menulist [data-toggle="popover-link"]').each(function () {
                const popover = bootstrap.Popover.getInstance(this);
                if (popover && !$(this).is(e.target) && $(this).has(e.target).length === 0) {
                    popover.hide();
                }
            });
        });
    }

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
                        checkss: btn.data('checkss')
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
