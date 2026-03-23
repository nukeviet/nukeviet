/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
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
                        nv_func_name === 'main' ? location.reload() : location.href = data.redirect;
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

    // Danh sách quảng cáo ở trang chính
    if (nv_func_name === 'main') {
        // Kích hoạt / hủy kích hoạt qua checkbox
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

    // Trang thông tin chi tiết banner
    if (nv_func_name === 'info-banner') {
        // Xem ảnh banner qua modal
        $(document).on('click', '.open-modal-image', function(e) {
            e.preventDefault();
            const src = $(this).data('src');
            $('#imagemodal-body').html('<img src="' + src + '" class="img-fluid">');
            const modal = new bootstrap.Modal(document.getElementById('imagemodal'));
            modal.show();
        });

        // Xem thống kê chi tiết
        $('#btn-show-stat').on('click', function() {
            const btn = $(this);
            if (btn.prop('disabled')) return;
            const month = $('#select_month').val();
            const ext = $('#select_ext').val();
            const id = btn.data('id');
            btn.prop('disabled', true);
            $.ajax({
                type: 'GET',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=show_stat&id=' + id + '&month=' + month + '&ext=' + ext + '&nocache=' + new Date().getTime(),
                success: (html) => {
                    $('#statistic').html(html);
                    btn.prop('disabled', false);
                },
                error: () => {
                    btn.prop('disabled', false);
                }
            });
        });

        // Đình chỉ / Kích hoạt banner
        $('[data-toggle="change-act-banner"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) return;
            const doRequest = () => {
                const orig = icon.data('icon');
                icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(),
                    data: { id: btn.data('id'), checkss: btn.data('tokend') },
                    dataType: 'json',
                    success: (data) => {
                        if (data.status === 'OK' || data.status === 'ok') {
                            location.reload();
                        } else {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                            nvToast(data.mess, 'error');
                        }
                    },
                    error: (xhr, text) => {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                        nvToast(text, 'error');
                    }
                });
            };
            const msgConfirm = btn.data('msgconfirm');
            if (msgConfirm) {
                nvConfirm(msgConfirm, doRequest);
            } else {
                doRequest();
            }
        });
    }
});
