/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Thay đổi đối tượng
    $('#role [name=role_object]').on('change', function() {
        $.ajax({
            type: "POST",
            url: $('#role').attr('action'),
            cache: !1,
            data: 'getapitree=' + $(this).val()
        }).done(function(a) {
            $('#apicheck').html(a)
        });
    });

    // Khi chọn/bỏ chọn API
    $('#role').on('change', '.checkitem', function() {
        var isChecked = $(this).is(':checked'),
            totalApiEnabled = parseInt($('#role .total-api-enabled').text()),
            childApisItem = $(this).parents('.child-apis-item'),
            treeObj = $('#role .root-api-actions a[aria-controls=' + childApisItem.attr('id') + '] .api-count'),
            treeTotalAPI = parseInt($('.total_api', treeObj).text()),
            notCheckedLength = $('.checkitem:not(:checked)', childApisItem).length;
        if (isChecked) {
            $('#role .total-api-enabled').addClass('checked').text(++totalApiEnabled);
            $('.total_api', treeObj).text(++treeTotalAPI);
            treeObj.addClass('checked')
        } else {
            $('#role .total-api-enabled').text(--totalApiEnabled);
            if (totalApiEnabled == 0) {
                $('#role .total-api-enabled').removeClass('checked')
            }
            $('.total_api', treeObj).text(--treeTotalAPI)
            if (treeTotalAPI == 0) {
                treeObj.removeClass('checked')
            }
        }
        $('.checkall', childApisItem).prop('checked', !notCheckedLength)
    });
    // Khi tích vào nút Chọn tất cả
    $('#role').on('change', '.checkall', function() {
        var isChecked = $(this).is(':checked'),
            childApisItem = $(this).parents('.child-apis-item');
        $('.checkitem', childApisItem).each(function() {
            $(this).prop('checked', isChecked).trigger('change')
        })
    });
    // Không cho xuống dòng ở textarea
    $('#role_description').on('input', function() {
        $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
    });
    // Thêm flood rule
    $('#role').on('click', '.add-rule', function() {
        var item = $(this).parents('.item'),
            newitem = item.clone();
        $('[name^=flood_rules_limit], [name^=flood_rules_interval]', newitem).val('');
        item.after(newitem)
    });
    // Xóa flood rule
    $('#role').on('click', '.del-rule', function() {
        var item = $(this).parents('.item'),
            items = $(this).parents('.items');
        if ($('.item', items).length > 1) {
            item.remove()
        } else {
            $('[name^=flood_rules_limit], [name^=flood_rules_interval]', item).val('')
        }
    });
    // Xử lý khi form thêm/sửa API-role được submit
    $('#role').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data,
            dataType: "json"
        }).done(function(a) {
            if ('error' == a.status) {
                alert(a.mess)
            } else if ('OK' == a.status) {
                window.location.href = a.redirect
            }
        });
    });
    // Lọc danh sách theo loại, đối tượng của role
    $('#rolelist .role-type, #rolelist .role-object').on('change', function() {
        var type = $('#rolelist .role-type').val(),
            object = $('#rolelist .role-object').val(),
            url = $('#rolelist').data('page-url');
        let params = [];
        if (type != '') {
            params.push('type=' + type);
        }
        if (object != '') {
            params.push('object=' + object);
        }
        if (params.length) {
            url += (url.includes('?') ? '&' : '?') + params.join('&');
        }
        window.location.href = url;
    });
    // Thay đổi trạng thái role
    $('#rolelist .change-status').on('change', function(e) {
        var that = $(this);
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $('#rolelist').data('page-url'),
            cache: !1,
            data: 'changeStatus=' + that.parents('.item').data('id'),
            dataType: "json"
        }).done(function(a) {
            setTimeout(function() {
                that.prop('disabled', false)
            }, 1000);
            if ('error' == a.status) {
                alert(a.mess);
            }
        })
    });
    // Xóa role
    $('[data-toggle="apiroledel"]').on('click', function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: "POST",
                url: $('#rolelist').data('page-url'),
                cache: !1,
                data: 'roledel=' + $(this).parents('.item').data('id'),
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(nv_is_del_confirm[2]);
                } else if ('OK' == a.status) {
                    location.reload()
                }
            })
        }
    });

    if ($('#credentiallist').length) {
        var credentiallist = $('#credentiallist'),
            credential_page_url = credentiallist.data('page-url'); ;
        // Lọc quyền truy cập API-role
        $('.role-id', credentiallist).on('change', function() {
            var role_id = parseInt($(this).val());
            window.location.href = credential_page_url + (credential_page_url.includes('?') ? '&' : '?') + 'role_id=' + role_id
        }).select2();

        // Thêm/sửa quyền truy cập API-role
        $('[data-toggle=credential-add], [data-toggle=credential-edit]', credentiallist).on('click', function() {
            var url = $('#credential-add form').attr('action'),
                title = $(this).data('title');
            if ($(this).is('[data-toggle=credential-edit]')) {
                url += '&edit=1&userid=' + $(this).parents('.item').data('userid')
            }
            $.ajax({
                type: "GET",
                url: url,
                cache: !1
            }).done(function(a) {
                $('#credential-add .credential-title').text(title);
                $('#credential-add form').html(a);
                $('#credential-add').modal('show')
            })
        });

        // Tìm admin/user
        if ($('#credential-add').length) {
            //Form thêm quyền truy cập
            $('#credential-add form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    cache: !1,
                    data: $(this).serialize(),
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        alert(a.mess);
                    } else if ('OK' == a.status) {
                        location.reload()
                    }
                })
            })
        };

        $('.change-status', credentiallist).on('change', function() {
            var userid = parseInt($(this).parents('.item').data('userid')),
                role_id = parseInt(credentiallist.data('role-id')),
                that = $(this);
            that.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: credential_page_url + '&role_id=' + role_id + '&action=changeStatus',
                cache: !1,
                data: 'userid=' + userid,
                dataType: "json"
            }).done(function(a) {
                setTimeout(function() {
                    that.prop('disabled', false)
                }, 1000);
                if ('error' == a.status) {
                    alert(a.mess);
                }
            })
        });

        $('[data-toggle=credentialDel]', credentiallist).on('click', function() {
            if (confirm($(this).data('confirm'))) {
                var userid = parseInt($(this).parents('.item').data('userid')),
                    role_id = parseInt(credentiallist.data('role-id'));
                $.ajax({
                    type: "POST",
                    url: credential_page_url + '&role_id=' + role_id + '&action=del',
                    cache: !1,
                    data: 'userid=' + userid,
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        alert(a.mess);
                    } else if ('OK' == a.status) {
                        location.reload()
                    }
                })
            }
        });

        $('[data-toggle=changeAuth]', credentiallist).on('click', function() {
            var userid = parseInt($(this).parents('.item').data('userid'));
            $.ajax({
                type: "POST",
                url: credential_page_url,
                cache: !1,
                data: 'changeAuth=' + userid,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    alert(a.mess);
                } else if ('OK' == a.status) {
                    $('#changeAuth .modal-title').text(a.title);
                    $('#changeAuth .modal-body').html(a.body);
                    $('#changeAuth').modal('show')
                }
            })
        });

        if ($('#changeAuth').length) {
            var changeAuth = $('#changeAuth');

            var clipboard = new ClipboardJS('[data-toggle=clipboard]');
            clipboard.on('success', function(e) {
                $(e.trigger).tooltip('show');
                setTimeout(function() {
                    $(e.trigger).tooltip('destroy');
                }, 1000);
            });

            changeAuth.on('click', '.create_authentication', function(e) {
                var method = $(this).data('method');
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'save=1&method=' + method + '&changeAuth=' + $(this).data('userid'),
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        alert(a.mess)
                    } else if ('OK' == a.status) {
                        $('[name=' + method + '_ident]', changeAuth).val(a.ident);
                        $('[name=' + method + '_secret]', changeAuth).val(a.secret);
                        $('[name=' + method + '_ips]', changeAuth).parents('.api_ips').slideDown()
                    }
                })
            });
            changeAuth.on('click', '.delete_authentication', function(e) {
                var method = $(this).data('method');
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'del=1&method=' + method + '&changeAuth=' + $(this).data('userid'),
                    dataType: "json"
                }).done(function(a) {
                    if ('OK' == a.status) {
                        $('[name=' + method + '_ident]', changeAuth).val('');
                        $('[name=' + method + '_secret]', changeAuth).val('');
                        $('[name=' + method + '_ips]', changeAuth).val('').parents('.api_ips').slideUp()
                    }
                })
            });
            changeAuth.on('input', '.ips', function() {
                $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
            });
            changeAuth.on('click', '.api_ips_update', function() {
                var method = $(this).data('method'),
                    ips = $('[name=' + method + '_ips]', changeAuth).val();
                $('.ips, .api_ips_update', changeAuth).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'ips=' + ips + '&method=' + method + '&changeAuth=' + $(this).data('userid'),
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        alert(a.mess);
                        $('.ips, .api_ips_update', changeAuth).prop('disabled', false)
                    } else if ('OK' == a.status) {
                        $('[name=' + method + '_ips]', changeAuth).val(a.ips);
                        setTimeout(function() {
                            $('.ips, .api_ips_update', changeAuth).prop('disabled', false)
                        }, 1000);
                    }
                })
            })
        }
    };
    // Trang main
    if (document.getElementById('my-role-api')) {
        var myroleapi = document.getElementById('my-role-api'),
        myroleapi_url = myroleapi.getAttribute('data-page-url');

        myroleapi.querySelectorAll('.credential-activate, .credential-deactivate').forEach(function(button) {
            button.addEventListener('click', function() {
                var role_id = this.closest('.item').dataset.roleId;
                fetch(myroleapi_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'changeActivate=' + role_id,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        nvAlert(data.mess);
                    } else if (data.status === 'OK') {
                        location.reload();
                    }
                });
            });
        });

        var clipboardButtons = document.querySelectorAll("[data-clipboard-target]");

        clipboardButtons.forEach(btn => {
            var tooltip = new bootstrap.Tooltip(btn);

            btn.addEventListener("click", function () {
                var target = document.querySelector(this.getAttribute("data-clipboard-target"));
                if (target) {
                    navigator.clipboard.writeText(target.value).then(() => {
                        tooltip.show();
                        setTimeout(() => tooltip.hide(), 1000);
                    });
                }
            });
        });

        var credential_auth = document.getElementById('credential_auth');
        credential_auth.querySelectorAll('.create_authentication').forEach(function(button) {
            button.addEventListener('click', function() {
                var method = this.dataset.method;
                fetch(myroleapi_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'createAuth=' + method,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        nvAlert(data.mess);
                    } else if (data.status === 'OK') {
                        credential_auth.querySelector('[name=' + method + '_ident]').value = data.ident;
                        credential_auth.querySelector('[name=' + method + '_secret]').value = data.secret;
                        credential_auth.querySelector('[name=' + method + '_ips]').closest('.api_ips').style.display = 'block';
                    }
                });
            });
        });
        credential_auth.querySelectorAll('.delete_authentication').forEach(function(button) {
            button.addEventListener('click', function() {
                var method = this.dataset.method;
                fetch(myroleapi_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'delAuth=' + method,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'OK') {
                        credential_auth.querySelector('[name=' + method + '_ident]').value = '';
                        credential_auth.querySelector('[name=' + method + '_secret]').value = '';
                        credential_auth.querySelector('[name=' + method + '_ips]').value = '';
                        credential_auth.querySelector('[name=' + method + '_ips]').closest('.api_ips').style.display = 'none';
                    }
                });
            });
        });
        credential_auth.querySelectorAll('.ips').forEach(function(input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[\r\n\v]+/g, '');
            });
        });

        credential_auth.querySelectorAll('.api_ips_update').forEach(function(button) {
            button.addEventListener('click', function() {
                var method = this.dataset.method,
                ips = credential_auth.querySelector('[name=' + method + '_ips]').value;
                credential_auth.querySelectorAll('.ips, .api_ips_update').forEach(function(el) {
                    el.disabled = true;
                });
                fetch(myroleapi_url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'ipsUpdate=' + ips + '&method=' + method,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        nvAlert(data.mess);
                        credential_auth.querySelectorAll('.ips, .api_ips_update').forEach(function(el) {
                            el.disabled = false;
                        });
                    } else if (data.status === 'OK') {
                        credential_auth.querySelector('[name=' + method + '_ips]').value = data.ips;
                        setTimeout(function() {
                            credential_auth.querySelectorAll('.ips, .api_ips_update').forEach(function(el) {
                                el.disabled = false;
                            });
                        }, 1000);
                    }
                });
            });
        });
    };

    if ($('#logs').length) {
        var logs = $('#logs'),
            page_url = logs.data('page-url');
        $('.log-del', logs).on('click', function() {
            if (confirm($(this).parents('.list').data('delete-confirm'))) {
                $.ajax({
                    type: "POST",
                    url: page_url,
                    cache: !1,
                    data: 'delLog=' + $(this).parents('.item').data('id')
                }).done(function(a) {
                    location.reload()
                })
            }
        });

        $('.checkall', logs).on('change', function() {
            $('.checkall, .checkitem', logs).prop('checked', $(this).is(':checked'))
        });

        $('.checkitem', logs).on('change', function() {
            var ls = $(this).parents('.list');
            $('.checkall', logs).prop('checked', !$('.checkitem:not(:checked)', ls).length)
        });

        $('.log-multidel', logs).on('click', function() {
            var list = [];
            $('.checkitem:checked', logs).each(function() {
                list.push($(this).parents('.item').data('id'))
            });
            if (list.length) {
                if (confirm($('.list', logs).data('delete-confirm'))) {
                    $.ajax({
                        type: "POST",
                        url: page_url,
                        cache: !1,
                        data: 'delLogs=' + list
                    }).done(function(a) {
                        location.reload()
                    })
                }
            }
        });

        $('.log-delall', logs).on('click', function() {
            if (confirm($('.list', logs).data('delete-confirm'))) {
                $.ajax({
                    type: "POST",
                    url: page_url,
                    cache: !1,
                    data: 'delAllLogs=1'
                }).done(function(a) {
                    location.reload()
                })
            }
        });

        $('.role-id, .command', logs).select2();

        $('.userid', logs).select2({
            language: nv_lang_interface,
            allowClear: true,
            ajax: {
                type: "POST",
                url: page_url,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        getUser: 1,
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup
            },
            minimumInputLength: 3,
            templateResult: function(repo) {
                if (repo.loading) return repo.text;
                return repo.title
            },
            templateSelection: function(repo) {
                return repo.title || repo.text
            }
        });

        $('.fromdate,.todate', logs).datepicker({
            dateFormat: nv_jsdate_get.replace('yyyy', 'yy'),
            showOtherMonths: true,
            showOn: 'focus'
        });
    }
});
