/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function () {
    // Ẩn hiện schema about tuỳ thuộc vào schema type
    $('#schema_type').on('change', function () {
        var schemaType = $(this).val();
        if (schemaType === 'webpage') {
            $('#schema_about_container').removeClass('d-none');
        } else {
            $('#schema_about_container').addClass('d-none');
        }
    });

    // Trang nội dung: Đếm ký tự tiêu đề
    if ($('#form-page-content').length) {
        $("#titlelength").html($("#idtitle").val().length);
        $("#idtitle").on('keyup paste', function() {
            $("#titlelength").html($(this).val().length);
        });

        // Đếm ký tự mô tả
        $("#descriptionlength").html($("#description").val().length);
        $("#description").on('keyup paste', function() {
            $("#descriptionlength").html($(this).val().length);
        });

        // Tự động lấy alias khi thay đổi tiêu đề (nếu alias rỗng)
        if ($('[data-toggle="getaliaspage"]').data('auto-alias')) {
            $('#idtitle').change(function() {
                $('[data-toggle="getaliaspage"]').trigger('click');
            });
        }

        // Ẩn hiện schema_about tuỳ thuộc vào schema_type
        $('#content_schema_type').on('change', function() {
            if ($(this).val() === 'webpage') {
                $('#schema_about_container').removeClass('d-none');
            } else {
                $('#schema_about_container').addClass('d-none');
            }
        });
    }

    // Xử lý nút lấy alias
    $('[data-toggle="getaliaspage"]').on('click', function() {
        var btn = $(this);
        var icon = $('i', btn);
        var title = $('#idtitle').val();
        if (icon.is('.fa-spin')) {
            return;
        }
        if (title.length > 0) {
            icon.addClass('fa-spin');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(),
                data: {
                    title: title,
                    checkss: btn.data('checkss'),
                    id: btn.data('id')
                },
                cache: false,
                success: function(res) {
                    icon.removeClass('fa-spin');
                    if (res) {
                        $('#idalias').val(res);
                    }
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spin');
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });

    // Xóa 1 bài viết
    $('[data-toggle=nv_del_page]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    id: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function (respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function (xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Kích hoạt/đình chỉ 1 bài viết
    $('[data-toggle="changeActive"]').on('change', function() {
        let btn = $(this);
        let act = btn.is(':checked');
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_status&nocache=' + new Date().getTime(),
            data: {
                checkss: btn.data('checkss'),
                id: btn.data('id')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                btn.prop('disabled', false);
                if (!respon.success) {
                    btn.prop('checked', !act);
                    nvToast(respon.text, 'error');
                }
            },
            error: function(xhr, text, err) {
                btn.prop('checked', !act);
                btn.prop('disabled', false);
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Thay đổi thứ tự module
    $('[data-toggle="changeWeiPage"]').on('change', function() {
        let btn = $(this);
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&nocache=' + new Date().getTime(),
            data: {
                id: btn.data('id'),
                new_weight: btn.val()
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                if (!respon.success) {
                    nvToast(respon.text, 'error');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });
    });
});
