/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function () {
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
});

