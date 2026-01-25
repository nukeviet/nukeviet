/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Chọn ngày tháng
    if ($('.datepicker-post').length) {
        $('.datepicker-post').datepicker({
            dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl'
        });
    }

    // Nút chọn ngày tháng
    $('[data-toggle="focusDate"]').on('click', function(e) {
        e.preventDefault();
        $('input', $(this).parent()).focus();
    });

    // Nút xóa ngày tháng
    $('[data-toggle="clearDate"]').on('click', function(e) {
        e.preventDefault();
        $('input', $(this).parent()).val('');
    });

    // Cuộn trang đến element
    let autoScroll = $('[data-toggle="autoScroll"]');
    if (autoScroll.length == 1) {
        $('html, body').animate({
            scrollTop: autoScroll.offset().top - 10
        }, 150);
    }

    // Xóa tài khoản tường lửa
    $('[data-toggle="delFwUser"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(btn.data('message'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    delid: btn.data('id'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                success: function(data) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (data.error) {
                        nvToast(data.message, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Đổi phiên bản IP cấm
    $('#ipt_ip_version').on('change', function() {
        if ($(this).val() == '4') {
            $('#ip4_mask').removeClass('d-none');
            $('#ip6_mask').addClass('d-none');
        } else {
            $('#ip4_mask').addClass('d-none');
            $('#ip6_mask').removeClass('d-none');
        }
    });

    // Thay đổi quyền sử dụng các module admin
    $('[data-toggle="cAdnMod"]').on('change', function() {
        let btn = $(this);
        if (btn.is(':disabled')) {
            return;
        }
        let checked = btn.is(':checked');
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                changact: btn.data('level'),
                mid: btn.data('id'),
                checkss: btn.data('checkss')
            },
            dataType: 'json',
            success: function(data) {
                btn.prop('disabled', false);
                if (data.error) {
                    nvToast(data.message, 'error');
                    btn.prop('checked', !checked);
                }
            },
            error: function(xhr, text, err) {
                btn.prop('disabled', false);
                btn.prop('checked', !checked);
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Thay đổi thứ tự các module admin
    $('[data-toggle="wAdnMod"]').on('change', function() {
        let btn = $(this);
        if (btn.is(':disabled')) {
            return;
        }
        let weight = btn.val();
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                changeweight: btn.data('id'),
                new_vid: weight,
                checkss: btn.data('checkss')
            },
            dataType: 'json',
            success: function(data) {
                btn.prop('disabled', false);
                if (data.error) {
                    nvToast(data.message, 'error');
                    btn.val(btn.data('current'));
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                btn.prop('disabled', false);
                btn.val(btn.data('current'));
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Chọn thành viên để thêm quản trị
    $('#element_userid_btn').on('click', function() {
        nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=element_userid&return=username&filtersql=' + $(this).data('filtersql'), 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
    });

    // Chọn hết các module khi thêm/sửa quản trị
    $('[data-toggle=checkall]').on('click', function() {
        var obj = $(this).parents('[data-toggle=checklist]');
        $('[data-toggle=checkitem]', obj).prop("checked", $(this).data('check-value'));
    });

    // Thay đổi quyền hạn người quản trị khi thêm/sửa quản trị
    $('[data-toggle="authorLev"]').on('change', function() {
        var lev_expired = $('[name=lev_expired]').val();

        if ($(this).attr('value') == '2') {
            $('#modslist').slideUp(150);
            $('#modslist input').prop('disabled', true);
            if (lev_expired != '') {
                $('#after_exp_action input').prop('disabled', false);
                $('#after_exp_action').slideDown(150);
            } else {
                $('#after_exp_action').slideUp(150);
                $('#after_exp_action input').prop('disabled', true);
            }
        } else {
            $('#modslist input').prop('disabled', false);
            $('#modslist').slideDown(150);
            if ($('#after_exp_action').length) {
                $('#after_exp_action').hide();
                $('#after_exp_action input').prop('disabled', true);
            }
        }
    });

    // Xử lí khi khi thêm/sửa quản trị nút hạ cấp khi hết thời gian
    $('[name=downgrade_to_modadmin]').on('change', function() {
        if ($(this).is(':checked')) {
            $('#modslist2 input').prop('disabled', false);
            $('#modslist2').slideDown(150);
        } else {
            $('#modslist2').slideUp(150);
            $('#modslist2 input').prop('disabled', true);
        }
    });
    $('[name=lev_expired]').on('change', function() {
        var lev_expired = $(this).val(),
            lev = $('[name=lev]:checked').val();
        if ($('#after_exp_action').length) {
            if (lev == 2 && lev_expired != '') {
                $('#after_exp_action input').prop('disabled', false);
                $('#after_exp_action').slideDown(150);
            } else {
                $('#after_exp_action').slideUp(150);
                $('#after_exp_action input').prop('disabled', true);
            }
        }
    });

    // Xóa hết oauth của quản trị
    $('[data-toggle="truncate2step"]').on('click', function(e) {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    admin_id: btn.data('userid'),
                    delall: $('body').data('checksess')
                },
                dataType: 'json',
                success: function(data) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (data.error) {
                        nvToast(data.message, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa một oauth của quản trị
    $('[data-toggle="del2step"]').on('click', function(e) {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    id: btn.data('id'),
                    admin_id: btn.data('userid'),
                    del: $('body').data('checksess')
                },
                dataType: 'json',
                success: function(data) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (data.error) {
                        nvToast(data.message, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });
});
