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
});
