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
            if (a.status == 'error') {
                nukeviet.toast(a.mess, 'error');
            } else if (a.status == 'OK') {
                $('#apicheck').html(a.html);
            }
        });
    });
    // Khi chọn/bỏ chọn API
    $('#role').on('change', '.checkitem', function() {
        var isChecked = $(this).is(':checked'),
            totalApiEnabled = parseInt($('#role .total-api-enabled').text()),
            childApisItem = $(this).parents('.child-apis-item'),
            treeObj = $('#role .root-api-actions button[aria-controls=' + childApisItem.attr('id') + '] .api-count'),
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
            if ($(this).prop('checked') !== isChecked) {
                $(this).prop('checked', isChecked).trigger('change');
            }
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
        const ranId = nv_randomPassword(6);
        $('.lbl_rule', newitem).attr('for', 'flood_rules_limit' + ranId);
        $('.lbl_interval', newitem).attr('for', 'flood_rules_interval' + ranId);
        $('.ipt_rule', newitem).attr('id', 'flood_rules_limit' + ranId);
        $('.ipt_interval', newitem).attr('id', 'flood_rules_interval' + ranId);
        item.after(newitem);
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
            data: 'changeStatus=' + that.parents('.item').data('id') + '&checkss=' + $('#rolelist').data('checkss'),
            dataType: "json"
        }).done(function(data) {
            setTimeout(() => {
                that.prop('disabled', false);
            }, 1000);
            if (data.status === 'OK') {
                nvToast(data.mess, 'success');
            } else if (data.status === 'error') {
                nvToast(data.mess, 'error');
            }
        })
    });
    // Xóa role
    $('[data-toggle="apiroledel"]').on('click', function(e) {
        e.preventDefault();
        var icon = $(this).find('i'),
            iconClass = icon.attr('class'),
            that = $(this);
        nvConfirm(nv_is_del_confirm[0], function() {
            icon.removeClass(iconClass).addClass('fa-solid fa-spinner fa-spin-pulse');
            $.ajax({
                type: "POST",
                url: $('#rolelist').data('page-url'),
                cache: !1,
                data: 'roledel=' + that.parents('.item').data('id') + '&checkss=' + $('#rolelist').data('checkss'),
                dataType: "json"
            }).done(function(data) {
                if (data.status === 'error') {
                    nvAlert(nv_is_del_confirm[2]);
                    nvToast(nv_is_del_confirm[2], 'error');
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                } else if (data.status === 'OK') {
                    location.reload();
                }
            })
        })
    });
    if ($('#credentiallist').length) {
        var credentiallist = $('#credentiallist'),
            credential_page_url = credentiallist.data('page-url'); ;
        // Lọc quyền truy cập API-role
        $('.role-id', credentiallist).on('change', function() {
            var role_id = parseInt($(this).val());
            window.location.href = credential_page_url + (credential_page_url.includes('?') ? '&' : '?') + 'role_id=' + role_id
        }).select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });

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
                if (a.status == 'error') {
                    nvToast(a.mess, 'error');
                } else if (a.status == 'OK') {
                    $('#credential-add .credential-title-str').text(title);
                    $('#credential-add form').html(a.html);
                    credentialSelInit($('#getUser'));
                    $('#credential-add').modal('show')
                }
            })
        });

        $('.change-status', credentiallist).on('change', function() {
            var userid = parseInt($(this).parents('.item').data('userid')),
                role_id = parseInt(credentiallist.data('role-id')),
                that = $(this),
                checkss = credentiallist.data('checkss');
            that.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: credential_page_url + (credential_page_url.includes('?') ? '&' : '?') + 'role_id=' + role_id + '&action=changeStatus',
                cache: !1,
                data: 'userid=' + userid + '&checkss=' + checkss,
                dataType: "json"
            }).done(function(data) {
                setTimeout(() => {
                    that.prop('disabled', false);
                }, 1000);
                if (data.status === 'error') {
                    nvToast(data.mess, 'error');
                } else if (data.status === 'OK') {
                    nvToast(data.mess, 'success');
                }
            })
        });

        $('[data-toggle=credentialDel]', credentiallist).on('click', function() {
            var icon = $(this).find('i'),
                iconClass = icon.attr('class'),
                that = $(this),
                checkss = credentiallist.data('checkss');
            nvConfirm($(this).data('confirm'), function() {
                var userid = parseInt(that.parents('.item').data('userid')),
                    role_id = parseInt(credentiallist.data('role-id'));
                icon.removeClass(iconClass).addClass('fa-solid fa-spinner fa-spin-pulse');
                $.ajax({
                    type: "POST",
                    url: credential_page_url + (credential_page_url.includes('?') ? '&' : '?') + 'role_id=' + role_id + '&action=del',
                    cache: !1,
                    data: 'userid=' + userid + '&checkss=' + checkss,
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        nvAlert(a.mess);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                    } else if ('OK' == a.status) {
                        location.reload()
                    }
                })
            });
        });

        $('[data-toggle=changeAuth]', credentiallist).on('click', function() {
            var userid = parseInt($(this).parents('.item').data('userid')),
                checkss = credentiallist.data('checkss');
            $.ajax({
                type: "POST",
                url: credential_page_url,
                cache: !1,
                data: 'changeAuth=' + userid + '&checkss=' + checkss,
                dataType: "json"
            }).done(function(a) {
                if ('error' == a.status) {
                    nvAlert(a.mess);
                } else if ('OK' == a.status) {
                    $('#changeAuth .modal-title').text(a.title);
                    $('#changeAuth .modal-body').html(a.body);
                    $('#changeAuth').modal('show');

                    const ctn = $('#changeAuth');
                    $('[data-toggle="tablist"]', ctn).each(function() {
                        const tabEl = $(this)[0];
                        tabEl.addEventListener('shown.bs.tab', event => {
                            $('[data-toggle="credential_auth_dropdown_btn"]').text($(event.target).text());
                        });
                    });
                    $('[data-toggle="credential_auth_dropdown_item"]', ctn).on('click', function(e) {
                        e.preventDefault();
                        const ele = ctn[0].querySelector('[data-toggle="tablist"] [data-bs-toggle="tab"][href="' + $(this).attr('href') + '"]');
                        const tab = bootstrap.Tab.getOrCreateInstance(ele);
                        tab.show();
                    });
                }
            })
        });

        if ($('#changeAuth').length) {
            var changeAuth = $('#changeAuth');
            changeAuth.on("click", "[data-clipboard-target]", function() {
                var tooltip = new bootstrap.Tooltip(this);
                var target = $($(this).data("clipboard-target"));
                if (target.length) {
                    navigator.clipboard.writeText(target.val()).then(() => {
                        tooltip.show();
                        setTimeout(() => tooltip.hide(), 1000);
                    });
                }
            });

            changeAuth.on('click', '.create_authentication', function(e) {
                var method = $(this).data('method'),
                    checkss = credentiallist.data('checkss');
                $('.create_authentication', changeAuth).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'save=1&method=' + method + '&changeAuth=' + $(this).data('userid') + '&checkss=' + checkss,
                    dataType: "json"
                }).done(function(a) {
                    setTimeout(function() {
                        $('.create_authentication', changeAuth).prop('disabled', false);
                    }, 1000);
                    if ('error' == a.status) {
                        nvAlert(a.mess)
                    } else if ('OK' == a.status) {
                        $('[name=' + method + '_ident]', changeAuth).val(a.ident);
                        $('[name=' + method + '_secret]', changeAuth).val(a.secret);
                        $('[name=' + method + '_ips]', changeAuth).parents('.api_ips').slideDown()
                    }
                })
            });
            changeAuth.on('click', '.delete_authentication', function(e) {
                var method = $(this).data('method'),
                    checkss = credentiallist.data('checkss');
                $('.delete_authentication', changeAuth).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'del=1&method=' + method + '&changeAuth=' + $(this).data('userid') + '&checkss=' + checkss,
                    dataType: "json"
                }).done(function(a) {
                    setTimeout(function() {
                        $('.delete_authentication', changeAuth).prop('disabled', false);
                    }, 1000);
                    if ('OK' == a.status) {
                        $('[name=' + method + '_ident]', changeAuth).val('');
                        $('[name=' + method + '_secret]', changeAuth).val('');
                        $('[name=' + method + '_ips]', changeAuth).val('').parents('.api_ips').slideUp();
                    } else if ('error' == a.status) {
                        nvAlert(a.mess)
                    }
                })
            });
            changeAuth.on('input', '.ips', function() {
                $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
            });
            changeAuth.on('click', '.api_ips_update', function() {
                var method = $(this).data('method'),
                    ips = $('[name=' + method + '_ips]', changeAuth).val(),
                    checkss = credentiallist.data('checkss');
                $('.ips, .api_ips_update', changeAuth).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: credential_page_url,
                    cache: !1,
                    data: 'ips=' + ips + '&method=' + method + '&changeAuth=' + $(this).data('userid') + '&checkss=' + checkss,
                    dataType: "json"
                }).done(function(a) {
                    if ('error' == a.status) {
                        nvAlert(a.mess);
                        $('.ips, .api_ips_update', changeAuth).prop('disabled', false)
                    } else if ('OK' == a.status) {
                        $('[name=' + method + '_ips]', changeAuth).val(a.ips);
                        setTimeout(function() {
                            $('.ips, .api_ips_update', changeAuth).prop('disabled', false)
                        }, 1000);
                    }
                })
            })
            var fmt = nv_jsdate_post.replace(/dd/g, 'd').replace(/mm/g, 'm').replace(/yyyy/g, 'Y');
            $(".adddate, .enddate").flatpickr({
                enableTime: false,
                dateFormat: fmt,
                ariaDateFormat: fmt,
                locale: nv_lang_interface,
                appendTo: document.getElementById('credential-add'),
                onOpen: function (selectedDates, dateStr, instance) {
                    if (instance.input.value.length == 0) {
                        instance.setDate(new Date());
                    }
                }
            });
        }
    };
    function credentialSelInit(e) {
        var get_user_url = e.data('get-user-url');
        e.select2({
            language: nv_lang_interface,
            dropdownParent: $('#credential-add'),
            dir: $('html').attr('dir'),
            width: '100%',
            ajax: {
                type: "POST",
                url: get_user_url,
                dataType: 'json',
                delay: 250,
                data: params => {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: (data, params) => {
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
    }

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
                    nvAlert(a.mess);
                } else if ('OK' == a.status) {
                    location.reload()
                }
            })
        });

        var credential_auth = $('#credential_auth');
        $("[data-clipboard-target]").each(function() {
            var tooltip = new bootstrap.Tooltip(this);

            $(this).on("click", function() {
                var target = $($(this).data("clipboard-target"));
                if (target.length) {
                    navigator.clipboard.writeText(target.val()).then(() => {
                    tooltip.show();
                    setTimeout(() => tooltip.hide(), 1000);
                    });
                }
            });
        });
        $('.create_authentication', credential_auth).on('click', function(e) {
            var method = $(this).data('method');
            $('.create_authentication', credential_auth).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: myroleapi_url,
                cache: !1,
                data: 'createAuth=' + method,
                dataType: "json"
            }).done(function(a) {
                setTimeout(function() {
                    $('.create_authentication', credential_auth).prop('disabled', false);
                }, 1000);
                if ('error' == a.status) {
                    nvAlert(a.mess)
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
                setTimeout(function() {
                    $('.create_authentication', credential_auth).prop('disabled', false);
                }, 1000);
                if ('error' == a.status) {
                    nvAlert(a.mess)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ident]', credential_auth).val('');
                    $('[name=' + method + '_secret]', credential_auth).val('');
                    $('[name=' + method + '_ips]', credential_auth).val('').parents('.api_ips').slideUp()
                }
            })
        });
        $('.ips', credential_auth).on('input', function() {
            $(this).val($(this).val().replace(/[\r\n\v]+/g, ''));
        });
        $('.api_ips_update', credential_auth).on('click', function() {
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
                    nvAlert(a.mess);
                    $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                } else if ('OK' == a.status) {
                    $('[name=' + method + '_ips]', credential_auth).val(a.ips);
                    setTimeout(function() {
                        $('.ips, .api_ips_update', credential_auth).prop('disabled', false)
                    }, 1000);
                }
            })
        });

        // Xử lý chọn phương thức xác thực trên Mobile - Trang main
        const authTabEles = document.querySelectorAll('#credential_auth_tabs [data-bs-toggle="tab"]');
        for (const tabEl of authTabEles) {
            tabEl.addEventListener('shown.bs.tab', event => {
                $('[data-toggle="credential_auth_dropdown_btn"]').text($(event.target).text());
            });
        }
        $('[data-toggle="credential_auth_dropdown_item"]').on('click', function(e) {
            e.preventDefault();
            const ele = document.querySelector('#credential_auth_tabs [data-bs-target="' + $(this).attr('href') + '"]');
            const tab = bootstrap.Tab.getOrCreateInstance(ele);
            tab.show();
        });
    };

    if ($('#logs').length) {
        var logs = $('#logs'),
            page_url = logs.data('page-url'),
            checkss = logs.data('checkss');

        // Xoá 1 dòng log
        $('.log-del', logs).on('click', function() {
            var that = $(this),
                icon = $(this).find('i'),
                iconClass = icon.attr('class');
            nvConfirm(that.parents('.list').data('delete-confirm'), function() {
                icon.removeClass(iconClass).addClass('fa-solid fa-spinner fa-spin-pulse');
                $.ajax({
                    type: "POST",
                    url: page_url,
                    cache: !1,
                    data: 'delLog=' + that.parents('.item').data('id') + '&checkss=' + checkss
                }).done(function(a) {
                    if (a.status == 'error') {
                        nvAlert(a.mess);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                    } else if (a.status == 'OK') {
                        location.reload();
                    } else {
                        nvAlert(nv_is_del_confirm[2]);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                    }
                });
            });
        });
        // Xoá nhiều dòng log
        $('.log-multidel', logs).on('click', function() {
            var list = [],
                icon = $(this).find('i'),
                iconClass = icon.attr('class');
            $('.checkitem:checked', logs).each(function() {
                list.push($(this).parents('.item').data('id'))
            });
            if (list.length) {
                nvConfirm($('.list', logs).data('delete-confirm'), () => {
                    icon.removeClass(iconClass).addClass('fa-solid fa-spinner fa-spin-pulse');
                    $.ajax({
                        type: "POST",
                        url: page_url,
                        cache: !1,
                        data: 'delLogs=' + list + '&checkss=' + checkss
                    }).done(function(a) {
                        if (a.status == 'error') {
                            nvAlert(a.mess);
                            icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                        } else if (a.status == 'OK') {
                            location.reload();
                        } else {
                            nvAlert(nv_is_del_confirm[2]);
                            icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                        }
                    })
                })
            }
        });
        // Xoá tất cả log
        $('.log-delall', logs).on('click', function() {
            var icon = $(this).find('i'),
                iconClass = icon.attr('class');

            nvConfirm($('.list', logs).data('delete-confirm'), function() {
                icon.removeClass(iconClass).addClass('fa-solid fa-spinner fa-spin-pulse');
                $.ajax({
                    type: "POST",
                    url: page_url,
                    cache: !1,
                    data: 'delAllLogs=1' + '&checkss=' + checkss
                }).done(function(a) {
                    if (a.status == 'error') {
                        nvAlert(a.mess);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                    } else if (a.status == 'OK') {
                        location.reload();
                    } else {
                        nvAlert(nv_is_del_confirm[2]);
                        icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(iconClass);
                    }
                });
            })
        });

        $('.role-id, .command', logs).select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });

        $('.userid', logs).select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%',
            ajax: {
                type: "POST",
                url: page_url,
                dataType: 'json',
                delay: 250,
                theme: 'bootstrap5',
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

        var fmt = nv_jsdate_post.replace(/dd/g, 'd').replace(/mm/g, 'm').replace(/yyyy/g, 'Y').replace(/\//g, '-');
        $('.fromdate,.todate', $('#logs')).flatpickr({
            enableTime: false,
            dateFormat: fmt,
            ariaDateFormat: fmt,
            locale: nv_lang_interface,
            onOpen: function (selectedDates, dateStr, instance) {
                if (instance.input.value.length == 0) {
                    instance.setDate(new Date());
                }
            }
        });
    }
});
