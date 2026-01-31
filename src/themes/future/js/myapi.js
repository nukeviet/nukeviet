/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if ($('#my-role-api').length) {
        var myroleapi = $('#my-role-api'),
            myroleapi_url = myroleapi.data('page-url');

        $('.credential-activate, .credential-deactivate', myroleapi).on('click', function() {
            var role_id = $(this).parents('.item').data('role-id');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'changeActivate=' + role_id,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(a.mess);
                } else if ('OK' == a.status) {
                    location.reload()
                }
            })
        });

        var credential_auth = $('#credential_auth');
        var clipboard = new ClipboardJS('[data-toggle=clipboard]');
        clipboard.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });

        $('.create_authentication', credential_auth).on('click', function(e) {
            var method = $(this).data('method');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'createAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(e.mess)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ident]', credential_auth).val(a.ident);
                    $('[name=' + method + '_secret]', credential_auth).val(a.secret);
                    $('[name=' + method + '_ips]', credential_auth).parents('.api_ips').slideDown()
                }
            })
        });

        $('.delete_authentication', credential_auth).on('click', function(e) {
            var method = $(this).data('method');
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'delAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('OK' == a.status) {
                    $('[name=' + method + '_ident]', credential_auth).val('');
                    $('[name=' + method + '_secret]', credential_auth).val('');
                    $('[name=' + method + '_ips]', credential_auth).val('').parents('.api_ips').slideUp()
                }
            })
        });

        credential_auth.on('input', '.ips', function() {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
        credential_auth.on('click', '.api_ips_update', function() {
            var method = $(this).data('method'),
                ips = $('[name=' + method + '_ips]', credential_auth).val();
            $('.ips, .api_ips_update', credential_auth).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'ipsUpdate=' + ips + '&method=' + method,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(a.mess);
                    $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ips]', credential_auth).val(a.ips);
                    setTimeout(function() {
                        $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                    }, 1000);
                }
            })
        });

        $(document).on('click', '.open-api-modal', function () {

            var btn     = $(this);
            var roleId  = btn.data('role-id');
            var title   = btn.data('role-title');

            var modal       = $('#apiRoleModal');
            var modalTitle  = modal.find('.modal-title');
            var contentBox  = $('#apiRoleContent');
            var loadingBox  = $('#apiRoleLoading');

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
                }
            }).done(function (res) {

                loadingBox.addClass('d-none');
                contentBox.removeClass('d-none');

                if (res.status !== 'OK') {
                    contentBox.html(
                        '<div class="alert alert-danger">' + res.message + '</div>'
                    );
                    return;
                }

                contentBox.html(renderApiRole(res.data.apis));

            }).fail(function () {
                loadingBox.addClass('d-none');
                contentBox.removeClass('d-none').html(
                    '<div class="alert alert-danger">Lỗi kết nối</div>'
                );
            });
        });

        function renderApiRole(apis) {
            var html = '';

            // 1. XỬ LÝ RIÊNG CHO HỆ THỐNG (Sử dụng card giống bên dưới)
            if (apis['']) {
                html += '<div class="mb-4">';
                html += '  <h5 class="fw-bold text-success mb-3">'; // Màu xanh lá để phân biệt với ngôn ngữ
                html += '    <i class="fa fa-folder-open"></i> API của hệ thống';
                html += '  </h5>';

                for (var catKey in apis['']) {
                    var catData = apis[''][catKey];

                    html += '<div class="card mb-3">';
                    html += '  <div class="card-header fw-bold bg-light">';
                    html += '    <i class="fa fa-folder-open-o"></i> ' + catData.title;
                    html += '  </div>';
                    html += '  <div class="card-body">';

                    for (var apiKey in catData.apis) {
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
            for (var lang in apis) {
                if (lang === '' || !apis.hasOwnProperty(lang)) continue;

                html += '<div class="mb-4">';
                html += '  <h5 class="fw-bold text-primary mb-3">';
                html += '    <i class="fa fa-cogs"></i> Ngôn ngữ: ' + lang;
                html += '  </h5>';

                for (var module in apis[lang]) {
                    for (var cat in apis[lang][module]) {
                        var catData = apis[lang][module][cat];

                        html += '<div class="card mb-3">';
                        html += '  <div class="card-header fw-bold">';
                        html += '    <i class="fa fa-folder-open-o"></i> ' + catData.title;
                        html += '  </div>';
                        html += '  <div class="card-body">';

                        for (var apiKey in catData.apis) {
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
