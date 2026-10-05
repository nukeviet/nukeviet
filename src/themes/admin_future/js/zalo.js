/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if (nv_func_name === 'settings') {
        const pageUrl = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name;

        // Popup trả kết quả tạo access token: làm mới cửa sổ cha rồi tự đóng
        const tokenResult = $('#zalo-token-result');
        if (tokenResult.length) {
            if (window.opener && !window.opener.closed) {
                if (tokenResult.attr('data-status') === 'success') {
                    window.opener.location.reload();
                } else if (typeof window.opener.nvToast === 'function') {
                    window.opener.nvToast(tokenResult.attr('data-mess'), 'error');
                }
                window.close();
            }
            return;
        }

        const settingTabs = $('#zalo-setting-tabs');
        const settingSelect = $('#zalo-setting-select');

        // Chuyển tới một tab thiết lập
        const gotoTab = (tab) => {
            const link = $('[data-tab="' + tab + '"]', settingTabs);
            if (link.length) {
                link[0].click();
            }
        };

        // Tải nội dung tab bằng ajax (đơn vị hành chính, mã gọi quốc gia)
        const tabLoad = (pane, force) => {
            if (!force && pane.attr('data-loaded') !== 'false') {
                return;
            }
            pane.attr('data-loaded', 'true');

            const box = $('.zalo-tab-content', pane);
            const data = {};
            data[pane.data('load')] = 1;
            if (pane.is('[data-subdiv-parent]')) {
                data.subdivParent = pane.attr('data-subdiv-parent');
            }

            box.css('opacity', 0.5);
            $.ajax({
                type: 'POST',
                cache: false,
                url: pageUrl,
                data: data,
                dataType: 'html',
                success: function(html) {
                    box.html(html).css('opacity', 1);
                    initFormAjKeyboard($('.ajax-submit', box));
                    // Khởi tạo lại header cố định của bảng
                    $(window).trigger('resize');
                },
                error: function(xhr, text, err) {
                    pane.attr('data-loaded', 'false');
                    box.css('opacity', 1);
                    nvToast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        };

        // Tooltip cho nội dung tải bằng ajax và các dòng nhập được thêm mới
        $('.tab-pane[data-load]').each(function() {
            new bootstrap.Tooltip(this, {
                selector: '[data-bs-toggle="tooltip"]'
            });
        });

        $('[data-bs-toggle="pill"]', settingTabs).on('show.bs.tab', function() {
            const tab = $(this).data('tab');
            $('[data-toggle="dropdown-value"]', settingSelect).text($(this).text().trim());
            $('.dropdown-item', settingSelect).removeClass('active').filter('[data-tab="' + tab + '"]').addClass('active');

            const pane = $($(this).attr('href'));
            if (pane.is('[data-load]')) {
                tabLoad(pane, false);
            }
        });

        // Tab đang mở khi tải trang cần nội dung ajax
        const activePane = $('.tab-pane.active[data-load]');
        if (activePane.length) {
            tabLoad(activePane, false);
        }

        // Chọn tab ở chế độ mobile
        $('.dropdown-item', settingSelect).on('click', function(e) {
            e.preventDefault();
            gotoTab($(this).data('tab'));
        });

        // Click vào các bước ở thanh tiến độ hoặc liên kết chuyển tab
        $('[data-toggle="zaloSettingGoto"]').on('click', function(e) {
            e.preventDefault();
            gotoTab($(this).data('tab'));
        });

        // Mở popup tạo access token
        $('[data-toggle="zaloAccessTokenCreate"]').on('click', function(e) {
            e.preventDefault();
            nv_open_browse(this.href, 'NVNB', 500, 500, 'resizable=no,scrollbars=1,toolbar=no,location=no,status=no');
        });

        // Bật ghi nhận IP của Zalo Webhook rồi mở hướng dẫn kiểm tra
        $('[data-toggle="zaloWebhookIPCheck"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: pageUrl,
                data: {
                    func: 'check_zaloip',
                    checkss: btn.data('tokend')
                },
                dataType: 'json'
            }).done(function(res) {
                if (res.status === 'OK') {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('zalo-webhook-ip-modal')).show();
                } else {
                    nvToast(res.mess, 'error');
                }
            }).fail(function(xhr, text, err) {
                nvToast(err || text, 'error');
                console.log(xhr, text, err);
            }).always(function() {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
            });
        });

        // Cập nhật IP của Zalo Webhook từ các sự kiện vừa ghi nhận
        $('[data-toggle="zaloWebhookIPUpdate"]').on('click', function() {
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: pageUrl,
                data: {
                    func: 'zalowebhook_ip_update',
                    checkss: btn.data('tokend')
                },
                dataType: 'json'
            }).done(function(res) {
                if (res.status === 'OK') {
                    window.location.href = res.redirect;
                    return;
                }
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(res.mess, 'error');
            }).fail(function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err || text, 'error');
                console.log(xhr, text, err);
            });
        });

        // Đổi đơn vị hành chính cấp trên
        $('body').on('change', '[data-toggle="zaloSubdivParent"]', function() {
            const val = $(this).val();
            const url = $(this).data('url') + (val ? '&subdiv=' + val : '');
            const pane = $('#zalo-vnsubdivisions');
            pane.attr('data-subdiv-parent', val);
            $('[data-tab="vnsubdivisions"]', settingTabs).attr('data-location', url).data('location', url);
            locationReplace(url);
            tabLoad(pane, true);
        });

        // Mở khóa ô tên chính thức của đơn vị hành chính
        $('body').on('click', '[data-toggle="zaloSubdivUnlock"]', function() {
            const btn = $(this);
            const input = btn.siblings('input');
            const icon = $('i', btn);
            if (input.prop('readonly')) {
                nvConfirm(btn.closest('tbody').data('msgconfirm'), () => {
                    input.prop('readonly', false).trigger('focus');
                    icon.removeClass('fa-lock').addClass('fa-lock-open');
                });
            } else {
                input.prop('readonly', true);
                icon.removeClass('fa-lock-open').addClass('fa-lock');
            }
        });

        // Thêm một dòng nhập vào danh sách nhiều giá trị
        $('body').on('click', '[data-toggle="zaloInputAdd"]', function() {
            const list = $(this).closest('.zalo-multi-inputs');
            if ($('.zalo-multi-input', list).length >= parseInt(list.data('max'))) {
                return;
            }
            const item = $(this).closest('.zalo-multi-input');
            const newItem = item.clone();
            newItem.removeClass('is-invalid');
            $('[aria-describedby]', newItem).removeAttr('aria-describedby');
            $('input', newItem).val('').removeClass('is-invalid');
            item.after(newItem);
            $('input', newItem).trigger('focus');
        });

        // Xóa một dòng nhập, dòng cuối cùng chỉ được làm rỗng
        $('body').on('click', '[data-toggle="zaloInputRemove"]', function() {
            const list = $(this).closest('.zalo-multi-inputs');
            const item = $(this).closest('.zalo-multi-input');
            if ($('.zalo-multi-input', list).length > 1) {
                const tip = bootstrap.Tooltip.getInstance(this);
                if (tip) {
                    tip.dispose();
                }
                item.remove();
            } else {
                $('input', item).val('').trigger('focus');
            }
        });

        // Gõ vào một ô thì bỏ trạng thái lỗi của cả danh sách
        $('body').on('input', '.zalo-multi-inputs input', function() {
            $('.is-invalid', $(this).closest('.zalo-multi-inputs')).removeClass('is-invalid');
        });
    }
});
