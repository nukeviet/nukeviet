/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
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

    // Autocomplete tìm kiếm người dùng
    const autosearchWrap = $('.autosearch-user');
    if (autosearchWrap.length) {
        let autosearchTimer = null;

        const showSpinner = (wrap) => {
            const spinner = $('#autosearch-spinner', wrap);
            spinner.removeClass('d-none');
            spinner.closest('.autosearch-input-wrap').addClass('input-group');
        };

        const hideSpinner = (wrap) => {
            const spinner = $('#autosearch-spinner', wrap);
            spinner.addClass('d-none');
            spinner.closest('.autosearch-input-wrap').removeClass('input-group');
        };

        $('[name="assign_user"]', autosearchWrap).on('keyup', function(e) {
            const wrap = $(this).closest('.autosearch-user');
            const result = $('.autosearch-result', wrap);
            result.html('').addClass('d-none');
            hideSpinner(wrap);
            if (autosearchTimer) clearTimeout(autosearchTimer);
            if (e.key === 'Enter') return;

            const val = $.trim($(this).val());
            if (val.length < 3) return;

            showSpinner(wrap);
            const url = wrap.data('url');
            const checkss = wrap.data('checkss');

            autosearchTimer = setTimeout(function() {
                const ajaxUrl = url + (url.indexOf('?') !== -1 ? '&' : '?') + 'nocache=' + new Date().getTime();
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    data: { checkss: checkss, ajaxqueryusername: val },
                    dataType: 'json',
                    success: (res) => {
                        hideSpinner(wrap);
                        if (!res.length) return;
                        let html = '';
                        $.each(res, (k, v) => {
                            html += '<a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex align-items-center gap-2" data-value="' + v.username + '">';
                            html += '<img src="' + v.photo + '" width="32" height="32" class="rounded-circle flex-shrink-0">';
                            html += '<div><div class="fw-semibold">' + v.fullname + '</div><small class="text-muted">' + v.username + '</small></div>';
                            html += '</a>';
                        });
                        result.html(html).removeClass('d-none');
                    },
                    error: () => { hideSpinner(wrap); }
                });
            }, 300);
        });

        $(document).on('click', '.autosearch-result a', function() {
            const wrap = $(this).closest('.autosearch-user');
            $('[name="assign_user"]', wrap).val($(this).data('value')).focus();
            $('.autosearch-result', wrap).html('').addClass('d-none');
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.autosearch-user').length) {
                $('.autosearch-result').html('').addClass('d-none');
            }
        });
    }

    // Xem ảnh qua modal (dùng chung cho banner-content và info-banner)
    $(document).on('click', '.open-modal-image', function(e) {
        e.preventDefault();
        const src = $(this).data('src');
        $('#imagemodal-body').html('<img src="' + src + '" class="img-fluid">');
        const modal = new bootstrap.Modal(document.getElementById('imagemodal'));
        modal.show();
    });

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
                data: { id: btn.data('id'), checkss: btn.data('checkss') },
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
                    data: { id: chk.data('id'), checkss: chk.data('checkss') },
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

    // Xóa khối banner (dùng chung cho plans-list và info-plan)
    $('[data-toggle="del-plan"]').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) return;
        nvConfirm(btn.data('msgconfirm'), () => {
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_plan&nocache=' + new Date().getTime(),
                data: { id: btn.data('id'), checkss: btn.data('checkss') },
                dataType: 'json',
                success: (data) => {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(orig);
                    if (data.status === 'OK' || data.status === 'ok') {
                        const redirect = btn.data('redirect');
                        redirect ? (location.href = redirect) : location.reload();
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

    // Danh sách các khối banner
    if (nv_func_name === 'plans-list') {
        // Kích hoạt / hủy kích hoạt khối
        $('[data-toggle="toggle-plan-act"]').on('change', function() {
            const chk = $(this);
            if (chk.prop('disabled')) return;
            chk.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(),
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
        });
    }

    // Trang thông tin chi tiết khối banner
    if (nv_func_name === 'info-plan') {
        // Đình chỉ / Kích hoạt khối banner
        $('[data-toggle="change-act-plan"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) return;
            const orig = icon.data('icon');
            icon.removeClass(orig).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(),
                data: { id: btn.data('id'), checkss: btn.data('checkss') },
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
        });
    }

    // Thêm khối quảng cáo mới
    if (nv_func_name === 'plan-content') {
        $('#plan_exp_time').on('change', function() {
            if ($(this).val() === '-1') {
                $('#plan_exp_time_custom').removeClass('d-none');
            } else {
                $('#plan_exp_time_custom').addClass('d-none');
            }
        });
    }

    // Form thêm banner mới
    if (nv_func_name === 'banner-content') {
        // Khởi tạo datepicker cho ngày bắt đầu và kết thúc
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

        // Cập nhật dấu (*) require_image khi đổi khối
        function updatePlanUI() {
            const opt = $('#pid option:selected');
            const requireImage = opt.data('require-image') === true || opt.data('require-image') === 'true';
            $('#require_image_mark').toggleClass('d-none', !requireImage);
            if (!requireImage) {
                $('#banner').removeClass('is-invalid').closest('.col-12').find('.invalid-feedback').html('');
            }
        }

        $('#pid').on('change', updatePlanUI);
        updatePlanUI();

        // Xoá banner chính: ẩn preview, hiện input upload
        $('[data-toggle="remove-banner"]').on('click', function(e) {
            e.preventDefault();
            $('#current_banner_wrap').addClass('d-none');
            $('#new_banner_wrap').removeClass('d-none');
            $('#remove_banner').val('1');
        });

        // Xoá ảnh mobile: ẩn preview, hiện input upload
        $('[data-toggle="remove-imageforswf"]').on('click', function(e) {
            e.preventDefault();
            $('#current_imageforswf_wrap').addClass('d-none');
            $('#new_imageforswf_wrap').removeClass('d-none');
            $('#remove_imageforswf').val('1');
        });
    }

    // Trang thông tin chi tiết banner
    if (nv_func_name === 'info-banner') {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=show-stat&id=' + id + '&month=' + month + '&ext=' + ext + '&nocache=' + new Date().getTime(),
                success: (html) => {
                    $('#statistic').html(html);
                    btn.prop('disabled', false);
                },
                error: () => {
                    btn.prop('disabled', false);
                }
            });
        });

        // Xem danh sách click theo mục thống kê
        $(document).on('click', '[data-toggle="show-list-stat"]', function(e) {
            e.preventDefault();
            const btn = $(this);
            const container = btn.data('container') || 'statistic';
            let requestQuery = nv_fc_variable + '=show-list-stat';
            requestQuery += '&bid=' + encodeURIComponent(btn.data('bid'));
            requestQuery += '&month=' + encodeURIComponent(btn.data('month'));
            requestQuery += '&ext=' + encodeURIComponent(btn.data('ext'));
            requestQuery += '&val=' + encodeURIComponent(btn.data('val'));

            $('#' + container).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + requestQuery + '&nocache=' + new Date().getTime());
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
                    data: { id: btn.data('id'), checkss: btn.data('checkss') },
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
