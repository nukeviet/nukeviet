/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    if ($('#my-role-api').length) {
        const myroleapi = $('#my-role-api'),
            myroleapi_url = myroleapi.data('page-url');

        // Kích hoạt / hủy kích hoạt quyền
        $('.credential-activate, .credential-deactivate', myroleapi).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const role_id = btn.closest('.item').data('role-id');
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    changeActivate: role_id
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    } else if (res.status == 'OK') {
                        location.reload();
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                }
            });
        });

        const credential_auth = $('#credential_auth');
        const clipboard = new ClipboardJS('[data-bs-toggle="clipboard"]', {
            target: function (trigger) {
                return document.querySelector(trigger.getAttribute('data-bs-target'));
            }
        });
        clipboard.on('success', function(e) {
            const tooltip = bootstrap.Tooltip.getOrCreateInstance(e.trigger);
            tooltip.show();
            setTimeout(function() {
                tooltip.hide();
            }, 1000);
        });

        // Tạo mới thông tin xác thực
        $('.create_authentication', credential_auth).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const method = btn.data('method');
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    createAuth: method
                },
                dataType: 'json',
                success: function(res) {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                    } else if (res.status == 'OK') {
                        $('[name=' + method + '_ident]', credential_auth).val(res.ident);
                        $('[name=' + method + '_secret]', credential_auth).val(res.secret);
                        $('[name=' + method + '_ips]', credential_auth).closest('.api_ips').slideDown();
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                }
            });
        });

        // Xóa thông tin xác thực
        $('.delete_authentication', credential_auth).on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            const method = btn.data('method');
            nukeviet.confirm(nv_is_del_confirm[0], () => {
                if (!icon.data('icon')) {
                    icon.data('icon', icon.attr('class'));
                }
                icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

                $.ajax({
                    type: 'POST',
                    url: myroleapi_url,
                    cache: false,
                    data: {
                        delAuth: method
                    },
                    dataType: 'json',
                    success: function(res) {
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                        if (res.status == 'OK') {
                            $('[name=' + method + '_ident]', credential_auth).val('');
                            $('[name=' + method + '_secret]', credential_auth).val('');
                            $('[name=' + method + '_ips]', credential_auth).val('').closest('.api_ips').slideUp();
                        }
                    },
                    error: function() {
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    }
                });
            });
        });

        // Cập nhật IPs
        credential_auth.on('input', '.ips', function() {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
        credential_auth.on('click', '.api_ips_update', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const method = btn.data('method'),
                ips = $('[name=' + method + '_ips]', credential_auth).val();
            $('.ips, .api_ips_update', credential_auth).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                cache: false,
                data: {
                    ipsUpdate: ips,
                    method: method
                },
                dataType: 'json',
                success: function(res) {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    if (res.status == 'error') {
                        nukeviet.alert(res.mess);
                        $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                    } else if (res.status == 'OK') {
                        $('[name=' + method + '_ips]', credential_auth).val(res.ips);
                        nukeviet.toast(res.mess || 'OK', 'success');
                        setTimeout(function() {
                            $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                        }, 1000);
                    }
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    $('.ips, .api_ips_update', credential_auth).prop('disabled', false);
                }
            });
        });

        // Lấy danh sách các API theo role
        $(document).on('click', '.open-api-modal', function(e) {
            e.preventDefault();
            const btn = $(this);
            const icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }

            if (!icon.data('icon')) {
                icon.data('icon', icon.attr('class'));
            }
            icon.removeClass(icon.data('icon')).addClass('fa-solid fa-spinner fa-spin-pulse me-1');

            const roleId = btn.data('role-id');
            const title = btn.data('role-title');

            const modal = $('#apiRoleModal');
            const modalTitle = modal.find('.modal-title');
            const contentBox = $('#apiRoleContent');
            const loadingBox = $('#apiRoleLoading');

            modalTitle.text(title);
            contentBox.empty().addClass('d-none');
            loadingBox.removeClass('d-none');

            $.ajax({
                type: 'POST',
                url: myroleapi_url,
                dataType: 'json',
                data: {
                    getRole: 1,
                    role_id: roleId
                },
                success: function(res) {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    loadingBox.addClass('d-none');
                    contentBox.removeClass('d-none');

                    if (res.status !== 'OK') {
                        contentBox.html(
                            '<div class="alert alert-danger">' + res.message + '</div>'
                        );
                        return;
                    }

                    contentBox.html(renderApiRole(res.data.apis));
                },
                error: function() {
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse me-1').addClass(icon.data('icon'));
                    loadingBox.addClass('d-none');
                    contentBox.removeClass('d-none').html(
                        '<div class="alert alert-danger">Lỗi kết nối</div>'
                    );
                }
            });
        });

        function renderApiRole(apis) {
            let html = '';

            // 1. XỬ LÝ RIÊNG CHO HỆ THỐNG (Sử dụng card giống bên dưới)
            if (apis['']) {
                html += '<div class="mb-4">';
                html += '  <h5 class="fw-bold text-success mb-3">'; // Màu xanh lá để phân biệt với ngôn ngữ
                html += '    <i class="fa fa-folder-open"></i> API của hệ thống';
                html += '  </h5>';

                for (const catKey in apis['']) {
                    const catData = apis[''][catKey];

                    html += '<div class="card mb-3">';
                    html += '  <div class="card-header fw-bold bg-light">';
                    html += '    <i class="fa fa-folder-open-o"></i> ' + catData.title;
                    html += '  </div>';
                    html += '  <div class="card-body">';

                    for (const apiKey in catData.apis) {
                        html += '<div class="text-truncate mb-2">';
                        html += '  <i class="fa fa-caret-right text-muted"></i> ' + catData.apis[apiKey];
                        html += '</div>';
                    }

                    html += '  </div>';
                    html += '</div>';
                }
                html += '</div><hr>'; // Thêm gạch ngang phân cách nếu cần
            }

            // 2. XỬ LÝ THEO NGÔN NGỮ (Bỏ qua key rỗng)
            for (const lang in apis) {
                if (lang === '' || !apis.hasOwnProperty(lang)) continue;

                html += '<div class="mb-4">';
                html += '  <h5 class="fw-bold text-primary mb-3">';
                html += '    <i class="fa fa-cogs"></i> Ngôn ngữ: ' + lang;
                html += '  </h5>';

                for (const module in apis[lang]) {
                    for (const cat in apis[lang][module]) {
                        const catData = apis[lang][module][cat];

                        html += '<div class="card mb-3">';
                        html += '  <div class="card-header fw-bold">';
                        html += '    <i class="fa fa-folder-open-o"></i> ' + catData.title;
                        html += '  </div>';
                        html += '  <div class="card-body">';

                        for (const apiKey in catData.apis) {
                            html += '<div class="text-truncate mb-2">';
                            html += '  <i class="fa fa-caret-right text-muted"></i> ' + catData.apis[apiKey];
                            html += '</div>';
                        }

                        html += '  </div>';
                        html += '</div>';
                    }
                }
                html += '</div>';
            }

            return html || '<div class="alert alert-warning">Không có dữ liệu</div>';
        }
    }
});
