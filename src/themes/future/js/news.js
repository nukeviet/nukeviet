/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

/**
 * Kiểm tra đoạn văn bản đề xuất thay thế phải khác đoạn văn bản lỗi
 *
 * @param {String} val
 * @param {JQuery} ipt
 * @returns {Boolean}
 */
function newsReportFixCheck(val, ipt) {
    const content = trim(strip_tags($('[name="report_content"]', ipt.closest('form')).val()));
    const fix = trim(strip_tags(val.replace(/\s\s+/g, ' ')));

    return content.localeCompare(fix, undefined, {
        sensitivity: 'accent'
    }) !== 0;
}

/**
 * Gửi báo cáo lỗi thành công thì xóa nội dung đã nhập và đóng modal
 *
 * @param {Object} respon
 * @param {JQuery} form
 */
function newsReportCallback(respon, form) {
    $('[name="report_content"], [name="report_fix"], [name="report_email"]', form).val('');
    bootstrap.Modal.getOrCreateInstance(form.closest('.modal')[0]).hide();
}

$(function() {
    // Admin xóa tin
    $('body').on('click', '[data-toggle="nv_del_content"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: btn.data('adminurl') + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content-del&nocache=' + new Date().getTime(),
                data: {
                    id: btn.data('id'),
                    checkss: btn.data('checkss')
                },
                dataType: 'html',
                success: function(res) {
                    const r_split = res.split('_');
                    if (r_split[0] == 'OK') {
                        if (btn.data('detail')) {
                            window.location.href = r_split[2];
                        } else {
                            location.reload();
                        }
                    } else if (r_split[0] == 'ERR') {
                        nukeviet.alert(r_split[1]);
                    } else {
                        nukeviet.alert(nv_is_del_confirm[2]);
                    }
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Thành viên xóa bài viết của mình
    $('body').on('click', '[data-toggle="newsContentDel"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: btn.data('url'),
                data: {
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (respon.status == 'OK') {
                        location.reload();
                        return;
                    }
                    nukeviet.toast(respon.mess || nv_is_del_confirm[2], 'error');
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Lấy liên kết tĩnh của bài viết từ tiêu đề
    const newsContentAlias = (btn) => {
        const form = btn.closest('form');
        const icon = $('i', btn);
        const title = strip_tags(trim($('[name="title"]', form).val()));
        if (title == '' || icon.is('.fa-spin')) {
            return;
        }

        icon.addClass('fa-spin');
        $.ajax({
            type: 'POST',
            cache: false,
            url: btn.data('url'),
            data: {
                get_alias: title,
                checkss: $('[name="checkss"]', form).val()
            },
            dataType: 'text',
            success: function(res) {
                icon.removeClass('fa-spin');
                $('[name="alias"]', form).val(trim(res));
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spin');
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    };
    $('body').on('click', '[data-toggle="newsContentAlias"]', function(e) {
        e.preventDefault();
        newsContentAlias($(this));
    });

    // Tự lấy liên kết tĩnh khi đổi tiêu đề nếu ô liên kết tĩnh được phép nhập
    $('body').on('change', '[data-form="newsContent"] [name="title"]', function() {
        const btn = $('[data-toggle="newsContentAlias"]', $(this).closest('form'));
        if (btn.length) {
            newsContentAlias(btn);
        }
    });

    // Lịch chọn ngày ở form tìm kiếm, khởi tạo khi dùng lần đầu
    const newsSearchDatepicker = (el) => {
        if (!el.length || typeof $.datepicker !== 'object') {
            return;
        }
        if (!el.data('dp-init')) {
            el.datepicker({
                dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showOn: 'focus'
            });
            el.data('dp-init', true);
        }
        el.datepicker('show');
    };
    $('body').on('focus', '[data-form="newsSearch"] [data-provide="datepicker"]', function() {
        newsSearchDatepicker($(this));
    });
    $('body').on('click', '[data-form="newsSearch"] [data-toggle="newsSearchDateBtn"]', function() {
        newsSearchDatepicker($(this).closest('.input-group').find('[data-provide="datepicker"]'));
    });

    // Chuyển từ khóa sang tìm kiếm toàn site
    $('body').on('click', '[data-toggle="newsSearchOnSite"]', function(e) {
        e.preventDefault();

        const input = $('[name="q"]', $(this).closest('form'));
        const min = parseInt(input.attr('minlength'));
        const q =trim(strip_tags(input.val()).replace(/['"<>\\]/g, ''));

        input.val(q);
        nv_validate_reset(input);
        if (q === '') {
            nv_validate_show(input, nv_required);
            input.focus();
            return;
        }
        if (q.length < min) {
            nv_validate_show(input, nv_minlength.replace('{0}', min));
            input.focus();
            return;
        }
        window.location.href = $(this).data('href') + rawurlencode(q);
    });

    // Báo cáo lỗi: bôi đen đoạn văn bản trong bài viết để hiện nút gửi báo cáo
    const reportModal = $('[data-toggle="newsReportModal"]');
    if (reportModal.length && $('[data-toggle="error-report"]').length) {
        const reportForm = $('[data-form="newsReport"]', reportModal);
        const reportTitle = $('.modal-title', reportModal).text();
        let reportTimer = null;
        let reportTip = null;
        let reportExceeding = false;

        // Ô nhập tự giãn chiều cao theo nội dung
        const reportAutoResize = (el) => {
            el.style.height = '5px';
            el.style.height = el.scrollHeight + 'px';
        };

        reportModal.on('show.bs.modal', () => {
            $('[data-valid]', reportForm).each(function() {
                nv_validate_reset($(this));
            });

            const el = reportModal[0];
            const display = el.style.display;
            el.style.visibility = 'hidden';
            el.style.display = 'block';
            $('[data-toggle="newsReportAutoResize"]', reportModal).each(function() {
                reportAutoResize(this);
            });
            el.style.display = display;
            el.style.visibility = '';
        });

        $('[data-toggle="newsReportAutoResize"]', reportModal).on('input', function() {
            reportAutoResize(this);
        }).on('keydown', function(e) {
            // Đoạn văn bản chỉ nằm trên một dòng
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        // Nút nổi mở modal báo cáo lỗi
        const reportTipGet = () => {
            if (reportTip) {
                return reportTip;
            }
            reportTip = $('<div class="position-absolute z-3"></div>').hide();
            const btn = $('<button type="button" class="btn btn-danger btn-sm"></button>');
            btn.append('<i class="fa-solid fa-triangle-exclamation"></i> ').append(document.createTextNode(reportTitle));
            btn.on('click', () => {
                reportTip.hide();
                const modal = bootstrap.Modal.getOrCreateInstance(reportModal[0]);
                if (reportExceeding) {
                    nukeviet.confirm(reportModal.data('truncated'), () => {
                        modal.show();
                    });
                    return;
                }
                modal.show();
            });
            reportTip.append(btn);
            $('body').append(reportTip);

            return reportTip;
        };

        $('body').on('mouseup keyup touchend', '[data-toggle="error-report"]', function() {
            clearTimeout(reportTimer);
            reportTimer = setTimeout(() => {
                const selection = window.getSelection ? window.getSelection() : null;
                if (!selection || selection.rangeCount === 0) {
                    return;
                }

                let text = trim(strip_tags(selection.toString()));
                if (text.length <= 2 || /\r|\n/.test(text)) {
                    reportTip && reportTip.hide();
                    return;
                }

                // Chỉ nhận tối đa 250 ký tự, cắt tại khoảng trắng gần nhất
                reportExceeding = text.length > 250;
                if (reportExceeding) {
                    const pos = text.lastIndexOf(' ', 250);
                    text = text.substring(0, pos > 0 ? pos : 250);
                }
                $('[name="report_content"], [name="report_fix"]', reportForm).val(text);

                // Hiện nút ngay dưới đoạn văn bản được chọn
                const rect = selection.getRangeAt(0).getBoundingClientRect();
                reportTipGet().css({
                    left: Math.max(10, rect.left + window.scrollX),
                    top: rect.bottom + window.scrollY + 8
                }).fadeIn(200);
            }, 100);
        });

        // Bấm ra ngoài thì ẩn nút
        $(document).on('mousedown touchstart', (e) => {
            if (reportTip && !$(e.target).closest(reportTip).length) {
                reportTip.hide();
            }
        });
    }
});
