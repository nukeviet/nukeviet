/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Danh sách quảng cáo ở trang chính
    if (nv_func_name === 'main') {
        // Xóa quảng cáo
        $('[data-toggle="del-banner"]').on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) return;
            nvConfirm(btn.data('msgconfirm'), () => {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_banner&nocache=' + new Date().getTime(),
                    data: { id: btn.data('id'), checkss: btn.data('tokend') },
                    dataType: 'json',
                    success: (data) => {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        if (data.status === 'OK' || data.status === 'ok') {
                            location.reload();
                        } else {
                            nvToast(data.mess, 'error');
                        }
                    },
                    error: (xhr, text) => {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            });
        });

        // Kích hoạt / hủy kích hoạt quảng cáo
        $('[data-toggle="toggle-banner-act"]').on('change', function() {
            const chk = $(this);
            if (chk.prop('disabled')) return;

            const doRequest = () => {
                chk.prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(),
                    data: { id: chk.data('id'), checkss: chk.data('tokend') },
                    dataType: 'json',
                    success: (data) => {
                        if (data.status === 'OK' || data.status === 'ok') {
                            location.reload();
                        } else {
                            chk.prop('checked', !chk.prop('checked'));
                            chk.prop('disabled', false);
                            nvToast(data.mess, 'error');
                        }
                    },
                    error: (xhr, text) => {
                        chk.prop('checked', !chk.prop('checked'));
                        chk.prop('disabled', false);
                        nvToast(text, 'error');
                    }
                });
            };

            const msgConfirm = chk.data('msgconfirm');
            if (msgConfirm) {
                nvConfirm(msgConfirm, doRequest, () => {
                    chk.prop('checked', !chk.prop('checked'));
                });
            } else {
                doRequest();
            }
        });
    }
});
