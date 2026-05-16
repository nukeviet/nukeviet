/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    // Tắt xác thực 2 bước
    $('[data-toggle="turnoff2step"]').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) return;
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + Date.now(),
            type: 'post',
            data: { tokend: btn.data('tokend'), turnoff2step: 1 },
            dataType: 'json',
            success: function(response) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (response.status !== 'ok') {
                    nvToast(response.mess, 'error');
                    return;
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(error, 'error');
            }
        });
    });

    // Tạo lại mã dự phòng
    $('[data-toggle="changecode2step"]').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) return;
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + Date.now(),
            type: 'post',
            data: { tokend: btn.data('tokend'), changecode2step: 1 },
            dataType: 'json',
            success: function(response) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (response.status !== 'ok') {
                    nvToast(response.mess, 'error');
                    return;
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(error, 'error');
            }
        });
    });

    // In mã dự phòng
    $('[data-toggle="print-codes"]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).attr('href'), 'printcodes', 800, 600);
    });

    // Sao chép mã dự phòng
    const cBtn = $('[data-toggle="copy-codes"]');
    if (cBtn.length) {
        const clipboard = new ClipboardJS(cBtn[0]);
        clipboard.on('success', function() {
            $('span', cBtn).text(cBtn.data('copied'));
        });
    }

    // Xác nhận đã chép mã
    $('.confirmed-codes').on('click', function() {
        $('[data-toggle="confirm-complete"]').prop('disabled', false);
    });
    $('[data-toggle="confirm-complete"]').on('click', function() {
        window.location.href = $(this).data('link');
    });

    // Đóng mở danh sách khóa bảo mật
    $('#security-keys').on('hide.bs.collapse', function(e) {
        locationReplace($(e.currentTarget).data('page-url'));
    });
    $('#security-keys').on('show.bs.collapse', function(e) {
        locationReplace($(e.currentTarget).data('show-keys-url'));
    });

    // Đóng mở danh sách mã dự phòng
    $('#recovery-codes').on('hide.bs.collapse', function(e) {
        locationReplace($(e.currentTarget).data('page-url'));
    });
    $('#recovery-codes').on('show.bs.collapse', function(e) {
        locationReplace($(e.currentTarget).data('show-codes-url'));
    });

    // Thay đổi phương pháp xác thực 2 bước ưa thích
    $('[data-toggle="preferred_2fa_method"]').on('change', function() {
        const btn = $(this);
        btn.prop('disabled', true);
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + Date.now(),
            type: 'post',
            data: { change_preferred_2fa: btn.data('checkss'), pref_2fa: btn.val() },
            dataType: 'json',
            success: function(response) {
                if (response.status !== 'ok') {
                    nvToast(response.mess, 'error');
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false);
                nvToast(error, 'error');
            }
        });
    });
});

$(window).on('load', function() {
    const pkForm = $('#container-edit-app');
    if (pkForm.length && pkForm.data('autoscroll')) {
        $('html, body').animate({ scrollTop: pkForm.offset().top - 60 }, 100);
    }
});

/**
 * Callback riêng cho form xác nhận mật khẩu
 *
 * @param {Object} data
 * @returns
 */
function confirmPassCallback(data) {
    if (!data.redirect && !data.refresh) {
        location.reload();
        return false;
    }
}
