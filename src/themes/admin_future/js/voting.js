/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function () {
    // Xóa giá trị ngày + reset giờ/phút về 0
    $('[data-toggle="delval"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this).data('target');
        const selects = $(this).data('select');
        if (target) {
            $(target).val('');
        }
        if (selects) {
            $(selects).val('0');
        }
    });

    // Xóa 1 voting
    $('[data-toggle=nv_del_voting]').on('click', function (e) {
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
                    vid: btn.data('vid')
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

    // Hiển thị kết quả voting
    $('[data-toggle="viewresult"]').on('click', function (e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

        $.ajax({
            type: 'POST',
            cache: false,
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=voting&' + nv_fc_variable + '=main&vid=' + btn.data('vid') + '&checkss=' + btn.data('checkss') + '&lid=0',
            data: {
                nv_ajax_voting: 1
            },
            dataType: 'html',
            success: function (res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                var r_split = res.split('_');
                if (r_split[0] === 'ERROR') {
                    nvToast(r_split[1], 'error');
                } else {
                    modalShow(btn.data('title'), res);
                }
            },
            error: function (xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Kích hoạt/đình chỉ 1 voting
    $('[data-toggle="changeActive"]').on('change', function() {
        let btn = $(this);
        let act = btn.is(':checked');
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act&nocache=' + new Date().getTime(),
            data: {
                checkss: btn.data('checkss'),
                vid: btn.data('vid')
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

    // Form thêm/sửa thăm dò
    if (nv_func_name === 'content') {
        // Khởi tạo datepicker cho ngày đăng và ngày kết thúc
        if ($('.datepicker').length) {
            $('.datepicker').datepicker({
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'focus',
                beforeShow: function() {
                    setTimeout(function() {
                        $('.ui-datepicker').css('z-index', 999999999);
                    }, 0);
                }
            });
        }

        $('#publ_date_btn').on('click', function() {
            $('#publ_date').datepicker('show');
        });

        $('#exp_date_btn').on('click', function() {
            $('#exp_date').datepicker('show');
        });

        // Thêm hàng đáp án mới vào bảng
        $('[data-toggle="add-answer"]').on('click', function() {
            const tbody = $('#items tbody');
            const count = tbody.find('tr').length;
            const label = $(this).data('label');
            const newRow = '<tr>'
                + '<td class="text-end text-muted">' + label + ' ' + (count + 1) + '</td>'
                + '<td><input class="form-control form-control-sm" type="text" name="answervotenews[]" maxlength="245" autocomplete="off"></td>'
                + '<td><input class="form-control form-control-sm" type="text" name="urlvotenews[]" maxlength="255" autocomplete="off"></td>'
                + '</tr>';
            tbody.append(newRow);
        });
    }
});
