/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if (document.getElementById('role')) {
        // Thay đổi đối tượng
        document.querySelectorAll('#role [name=role_object]').forEach(role_object => {
            role_object.addEventListener('change', (event) => {
                fetch(document.querySelector('#role').getAttribute('action'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'getapitree=' + event.target.value,
                    cache: 'no-cache'
                })
                .then(response => response.text())
                .then(data => {
                    document.querySelector('#apicheck').innerHTML = data;
                });
            })
        });

        // Khi chọn/bỏ chọn API
        document.querySelector('#role').addEventListener('change', event => {
            if (event.target.classList.contains('checkitem')) {
                var isChecked = event.target.checked,
                    totalApiEnabled = parseInt(document.querySelector('#role .total-api-enabled').textContent),
                    childApisItem = event.target.closest('.child-apis-item'),
                    treeObj = document.querySelector('#role .root-api-actions button[aria-controls="' + childApisItem.id + '"] .api-count'),
                    treeTotalAPI = parseInt(treeObj.querySelector('.total_api').textContent),
                    notCheckedLength = childApisItem.querySelectorAll('.checkitem:not(:checked)').length;

                if (isChecked) {
                    document.querySelector('#role .total-api-enabled').classList.add('checked');
                    document.querySelector('#role .total-api-enabled').textContent = ++totalApiEnabled;
                    treeObj.querySelector('.total_api').textContent = ++treeTotalAPI;
                    treeObj.classList.add('checked');
                } else {
                    document.querySelector('#role .total-api-enabled').textContent = --totalApiEnabled;
                    if (totalApiEnabled === 0) {
                        document.querySelector('#role .total-api-enabled').classList.remove('checked');
                    }
                    treeObj.querySelector('.total_api').textContent = --treeTotalAPI;
                    if (treeTotalAPI === 0) {
                        treeObj.classList.remove('checked');
                    }
                }
                childApisItem.querySelector('.checkall').checked = !notCheckedLength;
            }
        });
        // Khi tích vào nút Chọn tất cả
        document.querySelector('#role').addEventListener('change', event => {
            if (event.target.classList.contains('checkall')) {
                var isChecked = event.target.checked,
                    childApisItem = event.target.closest('.child-apis-item');
                childApisItem.querySelectorAll('.checkitem').forEach(checkitem => {
                    if (checkitem.checked !== isChecked) {
                        checkitem.checked = isChecked;
                        checkitem.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
            }
        });
        // Không cho xuống dòng ở textarea
        document.getElementById('role_description').addEventListener('input', () => {
            this.value = this.value.replace(/[\r\n\v]+/g, '');
        });
        // Thêm flood rule
        document.getElementById('role').addEventListener('click', event => {
            var addRuleElement = event.target.closest('.add-rule');
            var delRuleElement = event.target.closest('.del-rule');
            if (addRuleElement) {
                var item = addRuleElement.closest('.item'),
                    newitem = item.cloneNode(true);
                newitem.querySelectorAll('[name^=flood_rules_limit], [name^=flood_rules_interval]').forEach(input => {
                    input.value = '';
                });
                item.after(newitem);
            } else if (delRuleElement) {
                var item = delRuleElement.closest('.item'),
                    items = delRuleElement.closest('.items');
                if (items.querySelectorAll('.item').length > 1) {
                    item.remove();
                } else {
                    item.querySelectorAll('[name^=flood_rules_limit], [name^=flood_rules_interval]').forEach(input => {
                        input.value = '';
                    });
                }
            }
        });
    }
    if (document.getElementById('rolelist')) {
        var rolelist = document.getElementById('rolelist');
        // Lọc danh sách theo loại, đối tượng của role
        document.querySelectorAll('#rolelist .role-type, #rolelist .role-object').forEach(element => {
            element.addEventListener('change', function() {
            var type = document.querySelector('#rolelist .role-type').value,
                object = document.querySelector('#rolelist .role-object').value,
                url = document.querySelector('#rolelist').dataset.pageUrl;
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
        });
        // Thay đổi trạng thái role
        document.querySelectorAll('#rolelist .change-status').forEach(element => {
            element.addEventListener('change', function(e) {
                var that = e.target;
                that.disabled = true;
                fetch(rolelist.dataset.pageUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'changeStatus=' + that.closest('.item').dataset.id + '&checkss=' + rolelist.dataset.checkss,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(data => {
                    setTimeout(() => {
                        that.disabled = false;
                    }, 1000);
                    if (data.status === 'OK') {
                        nvToast(data.mess, 'success');
                    } else if (data.status === 'error') {
                        nvToast(data.mess, 'error');
                    }
                });
            });
        });
        // Xóa role
        document.querySelectorAll('[data-toggle="apiroledel"]').forEach(element => {
            element.addEventListener('click', e => {
                e.preventDefault();
                that = e.target;
                nvConfirm(nv_is_del_confirm[0], () => {
                    fetch(rolelist.dataset.pageUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'roledel=' + that.closest('.item').dataset.id + '&checkss=' + rolelist.dataset.checkss,
                        cache: 'no-cache'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'error') {
                            nvAlert(nv_is_del_confirm[2]);
                            nvToast(nv_is_del_confirm[2], 'error');
                        } else if (data.status === 'OK') {
                            location.reload();
                        }
                    });
                });
            });
        });
    }
    if (document.getElementById('credentiallist')) {
        var credentiallist = document.getElementById('credentiallist'),
            credential_page_url = credentiallist.getAttribute('data-page-url'); ;
        // Lọc quyền truy cập API-role
        credentiallist.querySelectorAll('.role-id')[0].addEventListener('change', e => {
            var role_id = parseInt(e.target.value);
            window.location.href = credential_page_url + (credential_page_url.includes('?') ? '&' : '?') + 'role_id=' + role_id
        });
        $('.role-id', credentiallist).select2({
            theme: 'bootstrap5'
        });

        credentialSelInit = (e) => {
            var get_user_url = e.data('get-user-url');
            e.select2({
                language: nv_lang_interface,
                dropdownParent: $('#credential-add'),
                theme: 'bootstrap5',
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

        credentialFlatInit = (e) => {
            var fmt = nv_jsdate_post.replace(/dd/g, 'd').replace(/mm/g, 'm').replace(/yyyy/g, 'Y');
            e.flatpickr({
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

        // Thêm/sửa quyền truy cập API-role
        document.querySelectorAll('[data-toggle=credential-add], [data-toggle=credential-edit]').forEach(element => {
            element.addEventListener('click', e => {
                var that = e.target.closest('[data-toggle=credential-add], [data-toggle=credential-edit]')
                var url = document.querySelector('#credential-add form').getAttribute('action'),
                    title = that.getAttribute('data-title');
                if (that.getAttribute('data-toggle') === 'credential-edit') {
                    url += '&edit=1&userid=' + that.closest('.item').getAttribute('data-userid');
                }
                fetch(url, {
                    method: 'GET',
                    cache: 'no-cache'
                })
                .then(response => response.text())
                .then(data => {
                    document.querySelector('#credential-add .credential-title').textContent = title;
                    document.querySelector('#credential-add form').innerHTML = data;
                    credentialSelInit($('#getUser'));
                    credentialFlatInit($(".adddate, .enddate"));
                    new bootstrap.Modal(document.getElementById('credential-add')).show();
                });
            });
        });

        // Tìm admin/user
        if (document.getElementById('credential-add')) {
            // Form thêm quyền truy cập
            document.querySelector('#credential-add form').onsubmit = e => {
                e.preventDefault();
                var url = e.target.getAttribute('action');
                var data = new URLSearchParams(new FormData(e.target)).toString();
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: data,
                    cache: 'no-cache'
                })
                .then(response => response.json())
                .then(a => {
                    if (a.status === 'error') {
                        nvAlert(a.mess);
                    } else if (a.status === 'OK') {
                        location.reload();
                    }
                });
            }
        };

        credentiallist.querySelectorAll('.change-status').forEach(element => {
            element.addEventListener('change', e => {
            var userid = parseInt(e.target.closest('.item').getAttribute('data-userid')),
                role_id = parseInt(credentiallist.dataset.roleId),
                that = e.target;
            that.disabled = true;
            fetch(credential_page_url + '&role_id=' + role_id + '&action=changeStatus', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'userid=' + userid,
                cache: 'no-cache'
            })
            .then(response => response.json())
            .then(data => {
                setTimeout(() => {
                    that.disabled = false;
                    nvToast(data.mess, 'success');
                }, 1000);
                if (data.status === 'error') {
                    nvAlert(data.mess);
                }
            });
            });
        });

        document.querySelectorAll('[data-toggle=credentialDel]').forEach(element => {
            element.addEventListener('click', e => {
                var that = e.target.closest('[data-toggle=credentialDel]');
                if (nvConfirm(that.getAttribute('data-confirm'), () => {
                    var userid = parseInt(that.closest('.item').dataset.userid),
                    role_id = parseInt(credentiallist.dataset.roleId);
                    fetch(credential_page_url + '&role_id=' + role_id + '&action=del', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'userid=' + userid,
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
                }));
            });
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
                    changeAuthInit();
                    $('#changeAuth').modal('show')
                }
            })
        });

        changeAuthInit = () => {
            var changeAuth = document.getElementById('changeAuth');

            changeAuth.addEventListener('click', e => {
                if (e.target.classList.contains('create_authentication')) {
                    var method = e.target.dataset.method;
                    fetch(credential_page_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'save=1&method=' + method + '&changeAuth=' + e.target.dataset.userid,
                        cache: 'no-cache'
                    })
                    .then(response => response.json())
                    .then(a => {
                        if (a.status === 'error') {
                            nvAlert(a.mess);
                        } else if (a.status === 'OK') {
                            changeAuth.querySelector('[name=' + method + '_ident]').value = a.ident;
                            changeAuth.querySelector('[name=' + method + '_secret]').value = a.secret;
                            changeAuth.querySelector('[name=' + method + '_ips]').closest('.api_ips').style.display = 'block';
                        }
                    });
                } else if (e.target.classList.contains('delete_authentication')) {
                    var method = e.target.dataset.method;
                    fetch(credential_page_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'del=1&method=' + method + '&changeAuth=' + e.target.dataset.userid,
                        cache: 'no-cache'
                    })
                    .then(response => response.json())
                    .then(a => {
                        if (a.status === 'OK') {
                            changeAuth.querySelector('[name=' + method + '_ident]').value = '';
                            changeAuth.querySelector('[name=' + method + '_secret]').value = '';
                            changeAuth.querySelector('[name=' + method + '_ips]').value = '';
                            changeAuth.querySelector('[name=' + method + '_ips]').closest('.api_ips').style.display = 'none';
                        }
                    });
                } else if (e.target.classList.contains('api_ips_update')) {
                    var method = e.target.dataset.method,
                        ips = changeAuth.querySelector('[name=' + method + '_ips]').value;
                    changeAuth.querySelectorAll('.ips, .api_ips_update').forEach(el => el.disabled = true);
                    fetch(credential_page_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'ips=' + ips + '&method=' + method + '&changeAuth=' + e.target.dataset.userid,
                        cache: 'no-cache'
                    })
                    .then(response => response.json())
                    .then(a => {
                        if (a.status === 'error') {
                            alert(a.mess);
                            changeAuth.querySelectorAll('.ips, .api_ips_update').forEach(el => el.disabled = false);
                        } else if (a.status === 'OK') {
                            changeAuth.querySelector('[name=' + method + '_ips]').value = a.ips;
                            setTimeout(() => {
                                changeAuth.querySelectorAll('.ips, .api_ips_update').forEach(el => el.disabled = false);
                                nvToast(a.mess, 'success');
                            }, 1000);
                        }
                    });
                }
            });

            changeAuth.addEventListener('input', function(e) {
                if (e.target.classList.contains('ips')) {
                    e.target.value = e.target.value.replace(/[\r\n\v]+/g, '');
                }
            });

            changeAuth.querySelectorAll("[data-clipboard-target]").forEach(btn => {
                var tooltip = new bootstrap.Tooltip(btn);
    
                btn.addEventListener("click", function () {
                    var target = changeAuth.querySelector(this.getAttribute("data-clipboard-target"));
                    if (target) {
                        navigator.clipboard.writeText(target.value).then(() => {
                            tooltip.show();
                            setTimeout(() => tooltip.hide(), 1000);
                        });
                    }
                });
            });
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

        document.querySelectorAll("[data-clipboard-target]").forEach(btn => {
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

    if (document.getElementById('logs')) {
        var logs = document.getElementById('logs'),
            page_url = logs.getAttribute('data-page-url');
        logs.querySelectorAll('.log-del').forEach(element => {
            element.addEventListener('click', e => {
                nvConfirm(e.target.closest('.list').dataset.deleteConfirm, () => {
                    fetch(page_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'delLog=' + e.target.closest('.item').dataset.id,
                        cache: 'no-cache'
                    }).then(response => response.text())
                    .then(() => {
                        location.reload();
                    });
                });
            });
        });

        logs.querySelectorAll('.checkall').forEach(element => {
            element.addEventListener('change', e => {
                var isChecked = e.target.checked;
                logs.querySelectorAll('.checkall, .checkitem').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });
        })

        logs.querySelectorAll('.checkitem').forEach(element => {
            element.addEventListener('change', e => {
                var ls = e.target.closest('.list');
                logs.querySelectorAll('.checkall').forEach(checkall => {
                    checkall.checked = !ls.querySelectorAll('.checkitem:not(:checked)').length;
                });
            });
        });

        logs.querySelectorAll('.log-multidel').forEach(element => {
            element.addEventListener('click', () => {
                var list = [];
                logs.querySelectorAll('.checkitem:checked').forEach(item => {
                    list.push(item.closest('.item').dataset.id);
                });
                if (list.length) {
                    nvConfirm(logs.querySelector('.list').dataset.deleteConfirm, () => {
                        fetch(page_url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'delLogs=' + list.join(','),
                            cache: 'no-cache'
                        }).then(() => {
                            location.reload();
                        });
                    })
                }
            });
        });

        logs.querySelectorAll('.log-delall').forEach(element => {
            element.addEventListener('click', () => {
                nvConfirm(logs.querySelector('.list').dataset.deleteConfirm, () => {
                    fetch(page_url, {
                        method: 'POST',
                        headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'delAllLogs=1',
                        cache: 'no-cache'
                    }).then(() => {
                        location.reload();
                    });
                });
            });
        });

        $('.role-id, .command', $('#logs')).select2({
            theme: "bootstrap-5"
        });

        $('.userid', $('#logs')).select2({
            language: nv_lang_interface,
            allowClear: true,
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
